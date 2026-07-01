<?php

namespace Database\Seeders;

use App\Models\AhpGlobalWeight;
use App\Models\Aspek;
use App\Models\Division;
use App\Models\KpiDivisi;
use App\Models\KpiDivisiDistribution;
use App\Models\KpiDivisiDistributionItem;
use App\Models\KpiUmum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Period2026PreparationSeeder extends Seeder
{
    private array $ri = [
        1 => 0.00,
        2 => 0.00,
        3 => 0.58,
        4 => 0.90,
        5 => 1.12,
        6 => 1.24,
        7 => 1.32,
        8 => 1.41,
        9 => 1.45,
        10 => 1.49,
    ];

    public function run(): void
    {
        DB::transaction(function (): void {
            $divisions = $this->ensureDivisions();
            $months = $this->monthRange(2026, 1, 2026, 12);

            foreach ($months as [$bulan, $tahun]) {
                $this->seedKpiUmum($bulan, $tahun);
                $this->seedKpiDivisi($divisions, $bulan, $tahun);
                $this->applyAhpWeights($divisions, $bulan, $tahun);
                $this->seedAspek($bulan, $tahun);
                $this->seedKuantitatifDistributions($divisions, $bulan, $tahun);
            }

            $this->ensureGlobalAhpWeight();
        });
    }

    private function ensureDivisions(): array
    {
        $names = [
            'Technical Support Team',
            'Chat Sales Agent',
            'Creatif Desain',
        ];

        $divisions = [];
        foreach ($names as $name) {
            $division = Division::firstOrCreate(['name' => $name]);
            $divisions[$name] = $division;
        }

        return $divisions;
    }

    private function seedKpiUmum(int $bulan, int $tahun): void
    {
        foreach ($this->kpiUmumDefinitions() as $kpi) {
            KpiUmum::updateOrCreate(
                [
                    'nama' => $kpi['nama'],
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                ],
                [
                    'tipe' => $kpi['tipe'],
                    'satuan' => $kpi['satuan'],
                    'target' => $kpi['target'],
                ]
            );
        }
    }

    private function seedKpiDivisi(array $divisions, int $bulan, int $tahun): void
    {
        foreach ($this->kpiDivisiDefinitions() as $divisionName => $kpis) {
            $division = $divisions[$divisionName] ?? null;
            if (!$division) {
                continue;
            }

            foreach ($kpis as $kpi) {
                KpiDivisi::updateOrCreate(
                    [
                        'division_id' => $division->id,
                        'nama' => $kpi['nama'],
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                    ],
                    [
                        'tipe' => $kpi['tipe'],
                        'satuan' => $kpi['satuan'],
                        'target' => $kpi['target'],
                    ]
                );
            }
        }
    }

    private function applyAhpWeights(array $divisions, int $bulan, int $tahun): void
    {
        $umumDefs = [
            'JT' => 'Jumlah Tugas Diselesaikan Tepat Waktu',
            'KL' => 'Kualitas Hasil Pekerjaan',
            'KH' => 'Persentase Kehadiran',
            'RS' => 'Rata-Rata Waktu Respon Terhadap Permintaan',
        ];
        $umumPairs = [
            'JT_KL' => 2,
            'JT_KH' => 4,
            'JT_RS' => 8,
            'KL_KH' => 2,
            'KL_RS' => 4,
            'KH_RS' => 2,
        ];

        [$umumWeights] = $this->computeAhp(array_keys($umumDefs), $umumPairs);
        foreach ($umumDefs as $code => $name) {
            KpiUmum::where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->where('nama', $name)
                ->update(['bobot' => round($umumWeights[$code] ?? 0.0, 6)]);
        }

        foreach ($this->kpiDivisiAhpDefinitions() as $divisionName => $definition) {
            $division = $divisions[$divisionName] ?? null;
            if (!$division) {
                continue;
            }

            [$weights] = $this->computeAhp(array_keys($definition['criteria']), $definition['pairs']);

            foreach ($definition['criteria'] as $code => $name) {
                KpiDivisi::where('division_id', $division->id)
                    ->where('bulan', $bulan)
                    ->where('tahun', $tahun)
                    ->where('nama', $name)
                    ->update(['bobot' => round($weights[$code] ?? 0.0, 6)]);
            }
        }
    }

    private function ensureGlobalAhpWeight(): void
    {
        $weight = AhpGlobalWeight::query()->firstOrNew(['id' => 1]);

        if ($weight->exists) {
            return;
        }

        $weight->forceFill([
            'w_kpi_umum' => 0.33333333,
            'w_kpi_divisi' => 0.50000000,
            'w_peer' => 0.16666667,
            'lambda_max' => 3.00000000,
            'ci' => 0.00000000,
            'cr' => 0.00000000,
        ])->save();
    }

    private function seedAspek(int $bulan, int $tahun): void
    {
        $aspekNames = [
            'Kerja Sama Team',
            'Komunikasi',
            'Tanggung Jawab',
            'Kedisiplinan',
            'Kontribusi Terhadap Target Team',
        ];

        foreach ($aspekNames as $name) {
            Aspek::updateOrCreate(
                [
                    'nama' => $name,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                ],
                []
            );
        }
    }

    private function seedKuantitatifDistributions(array $divisions, int $bulan, int $tahun): void
    {
        foreach ($divisions as $division) {
            $creatorId = $this->creatorIdForDivision($division->id);

            $distribution = KpiDivisiDistribution::updateOrCreate(
                [
                    'division_id' => $division->id,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                ],
                [
                    'status' => 'approved',
                    'hr_note' => null,
                    'created_by' => $creatorId,
                ]
            );

            $users = User::where('division_id', $division->id)
                ->where('role', 'karyawan')
                ->orderBy('full_name')
                ->get();

            if ($users->isEmpty()) {
                continue;
            }

            $kpis = KpiDivisi::where('division_id', $division->id)
                ->where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->where('tipe', 'kuantitatif')
                ->get();

            foreach ($kpis as $kpi) {
                foreach ($this->splitTarget((float) $kpi->target, $users->count()) as $index => $target) {
                    $user = $users[$index];

                    KpiDivisiDistributionItem::updateOrCreate(
                        [
                            'distribution_id' => $distribution->id,
                            'kpi_divisi_id' => $kpi->id,
                            'user_id' => $user->id,
                        ],
                        [
                            'target' => $target,
                        ]
                    );
                }
            }
        }
    }

    private function creatorIdForDivision(int $divisionId): int
    {
        $creatorId = User::where('role', 'leader')
            ->where('division_id', $divisionId)
            ->value('id');

        $creatorId ??= User::where('role', 'hr')->value('id');
        $creatorId ??= User::query()->value('id');

        if (!$creatorId) {
            throw new \RuntimeException('Tidak ada user untuk created_by distribusi KPI divisi. Jalankan UserSeeder terlebih dahulu.');
        }

        return (int) $creatorId;
    }

    private function splitTarget(float $target, int $userCount): array
    {
        $total = (int) round($target);
        $count = max(1, $userCount);
        $base = intdiv($total, $count);
        $remainder = $total - ($base * $count);

        $targets = [];
        for ($i = 0; $i < $count; $i++) {
            $targets[] = $base + ($i < $remainder ? 1 : 0);
        }

        return $targets;
    }

    private function kpiUmumDefinitions(): array
    {
        return [
            ['nama' => 'Jumlah Tugas Diselesaikan Tepat Waktu', 'tipe' => 'kuantitatif', 'satuan' => 'Tugas', 'target' => 40],
            ['nama' => 'Kualitas Hasil Pekerjaan', 'tipe' => 'kualitatif', 'satuan' => 'Point', 'target' => 90],
            ['nama' => 'Persentase Kehadiran', 'tipe' => 'persentase', 'satuan' => '%', 'target' => 95],
            ['nama' => 'Rata-Rata Waktu Respon Terhadap Permintaan', 'tipe' => 'response', 'satuan' => 'Menit', 'target' => 60],
        ];
    }

    private function kpiDivisiDefinitions(): array
    {
        return [
            'Technical Support Team' => [
                ['nama' => 'Jumlah Kendala Yang Diselesaikan', 'tipe' => 'kuantitatif', 'satuan' => 'Kendala', 'target' => 50],
                ['nama' => 'Tingkat Kepuasan Pengguna Terhadap Pelayanan', 'tipe' => 'kualitatif', 'satuan' => 'Point', 'target' => 85],
                ['nama' => 'Rata-Rata Waktu Respon Pelayanan', 'tipe' => 'response', 'satuan' => 'Menit', 'target' => 15],
                ['nama' => 'Persentase Penyelesaian Kendala sesuai SLA', 'tipe' => 'persentase', 'satuan' => '%', 'target' => 95],
            ],
            'Chat Sales Agent' => [
                ['nama' => 'Jumlah Lead Yang Ditangani', 'tipe' => 'kuantitatif', 'satuan' => 'Lead', 'target' => 100],
                ['nama' => 'Kualitas Interaksi Chat dengan Pelanggan', 'tipe' => 'kualitatif', 'satuan' => 'Point', 'target' => 85],
                ['nama' => 'Rata-Rata Waktu Respon Chat Masuk', 'tipe' => 'response', 'satuan' => 'Detik', 'target' => 60],
                ['nama' => 'Rata-Rata Konversi Leads Menjadi Transaksi', 'tipe' => 'persentase', 'satuan' => '%', 'target' => 30],
            ],
            'Creatif Desain' => [
                ['nama' => 'Jumlah Proyek Desain yang Diselesaikan', 'tipe' => 'kuantitatif', 'satuan' => 'Desain', 'target' => 15],
                ['nama' => 'Kualitas Hasil Desain', 'tipe' => 'kualitatif', 'satuan' => 'Point', 'target' => 85],
                ['nama' => 'Rata-Rata Waktu Penyelesaian Permintaan Desain', 'tipe' => 'response', 'satuan' => 'Hari', 'target' => 2],
                ['nama' => 'Persentase Proyek Tepat Waktu', 'tipe' => 'persentase', 'satuan' => '%', 'target' => 95],
            ],
        ];
    }

    private function kpiDivisiAhpDefinitions(): array
    {
        return [
            'Technical Support Team' => [
                'criteria' => [
                    'QTY' => 'Jumlah Kendala Yang Diselesaikan',
                    'QLT' => 'Tingkat Kepuasan Pengguna Terhadap Pelayanan',
                    'RSP' => 'Rata-Rata Waktu Respon Pelayanan',
                    'PCT' => 'Persentase Penyelesaian Kendala sesuai SLA',
                ],
                'pairs' => [
                    'QTY_QLT' => 3,
                    'QTY_RSP' => 3,
                    'QTY_PCT' => 9,
                    'QLT_RSP' => 1,
                    'QLT_PCT' => 3,
                    'RSP_PCT' => 3,
                ],
            ],
            'Chat Sales Agent' => [
                'criteria' => [
                    'QTY' => 'Jumlah Lead Yang Ditangani',
                    'QLT' => 'Kualitas Interaksi Chat dengan Pelanggan',
                    'RSP' => 'Rata-Rata Waktu Respon Chat Masuk',
                    'PCT' => 'Rata-Rata Konversi Leads Menjadi Transaksi',
                ],
                'pairs' => [
                    'QTY_QLT' => 3,
                    'QTY_RSP' => 9,
                    'QTY_PCT' => 9,
                    'QLT_RSP' => 3,
                    'QLT_PCT' => 3,
                    'RSP_PCT' => 1,
                ],
            ],
            'Creatif Desain' => [
                'criteria' => [
                    'QTY' => 'Jumlah Proyek Desain yang Diselesaikan',
                    'QLT' => 'Kualitas Hasil Desain',
                    'RSP' => 'Rata-Rata Waktu Penyelesaian Permintaan Desain',
                    'PCT' => 'Persentase Proyek Tepat Waktu',
                ],
                'pairs' => [
                    'QTY_QLT' => 2,
                    'QTY_RSP' => 4,
                    'QTY_PCT' => 4,
                    'QLT_RSP' => 2,
                    'QLT_PCT' => 2,
                    'RSP_PCT' => 1,
                ],
            ],
        ];
    }

    private function computeAhp(array $codes, array $pairs): array
    {
        $n = count($codes);
        $matrix = array_fill(0, $n, array_fill(0, $n, 1.0));

        for ($i = 0; $i < $n; $i++) {
            for ($j = $i + 1; $j < $n; $j++) {
                $key = $codes[$i] . '_' . $codes[$j];
                $value = isset($pairs[$key]) ? (float) $pairs[$key] : 1.0;
                $value = max(1.0 / 9.0, min(9.0, $value));
                $matrix[$i][$j] = $value;
                $matrix[$j][$i] = 1.0 / $value;
            }
        }

        $columnSums = array_fill(0, $n, 0.0);
        for ($j = 0; $j < $n; $j++) {
            for ($i = 0; $i < $n; $i++) {
                $columnSums[$j] += $matrix[$i][$j];
            }
        }

        $weights = array_fill(0, $n, 0.0);
        for ($i = 0; $i < $n; $i++) {
            $rowTotal = 0.0;
            for ($j = 0; $j < $n; $j++) {
                $rowTotal += $matrix[$i][$j] / max($columnSums[$j], 1e-12);
            }
            $weights[$i] = $rowTotal / $n;
        }

        $weightSum = array_sum($weights);
        if ($weightSum > 0) {
            foreach ($weights as $index => $weight) {
                $weights[$index] = $weight / $weightSum;
            }
        }

        $weightedMatrix = array_fill(0, $n, 0.0);
        for ($i = 0; $i < $n; $i++) {
            $sum = 0.0;
            for ($j = 0; $j < $n; $j++) {
                $sum += $matrix[$i][$j] * $weights[$j];
            }
            $weightedMatrix[$i] = $sum;
        }

        $ratios = [];
        for ($i = 0; $i < $n; $i++) {
            $ratios[] = $weights[$i] > 0 ? $weightedMatrix[$i] / $weights[$i] : 0.0;
        }

        $lambdaMax = array_sum($ratios) / $n;
        $ci = $n > 1 ? ($lambdaMax - $n) / ($n - 1) : 0.0;
        $ri = $this->ri[$n] ?? 1.49;
        $cr = $ri > 0 ? $ci / $ri : 0.0;

        $result = [];
        foreach ($codes as $index => $code) {
            $result[$code] = $weights[$index];
        }

        return [$result, $cr];
    }

    private function monthRange(int $startYear, int $startMonth, int $endYear, int $endMonth): array
    {
        $months = [];
        $year = $startYear;
        $month = $startMonth;

        while ($year < $endYear || ($year === $endYear && $month <= $endMonth)) {
            $months[] = [$month, $year];
            $month++;
            if ($month > 12) {
                $month = 1;
                $year++;
            }
        }

        return $months;
    }
}
