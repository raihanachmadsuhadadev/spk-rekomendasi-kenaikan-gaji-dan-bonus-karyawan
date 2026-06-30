<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\KpiUmum;

class KpiUmumSeeder extends Seeder
{
    public function run(): void
    {
        // Definisi KPI sesuai gambar (tipe harus lowercase agar cocok dgn enum migration)
        $masterKpi = [
            [
                'nama'   => 'Jumlah Tugas Diselesaikan Tepat Waktu',
                'tipe'   => 'kuantitatif',
                'satuan' => 'Tugas',
                'target' => 40,
            ],
            [
                'nama'   => 'Kualitas Hasil Pekerjaan',
                'tipe'   => 'kualitatif',
                'satuan' => 'Point',
                'target' => 90,
            ],
            [
                'nama'   => 'Persentase Kehadiran',
                'tipe'   => 'persentase',
                'satuan' => '%',
                'target' => 95,
            ],
            [
                'nama'   => 'Rata-Rata Waktu Respon Terhadap Permintaan',
                'tipe'   => 'response',
                'satuan' => 'Menit',
                'target' => 60,
            ],
        ];

        // Range bulan: Jan 2024 s/d Jul 2025 (inklusif)
        $start = Carbon::create(2024, 1, 1);
        $end   = Carbon::create(2025, 8, 1);

        for ($cursor = $start->copy(); $cursor <= $end; $cursor->addMonth()) {
            foreach ($masterKpi as $kpi) {
                KpiUmum::updateOrCreate(
                    [
                        'nama' => $kpi['nama'],
                        'bulan' => (int) $cursor->month,
                        'tahun' => (int) $cursor->year,
                    ],
                    [
                        'tipe' => $kpi['tipe'],
                        'satuan' => $kpi['satuan'],
                        'target' => $kpi['target'],
                        'bobot' => null,
                    ]
                );
            }
        }
    }
}
