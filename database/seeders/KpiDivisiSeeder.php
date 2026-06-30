<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\KpiDivisi;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class KpiDivisiSeeder extends Seeder
{
    public function run(): void
    {
        $definition = [
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

        $start = Carbon::create(2024, 1, 1);
        $end = Carbon::create(2025, 8, 1);

        foreach ($definition as $divisionName => $kpis) {
            $division = Division::firstOrCreate(['name' => $divisionName]);

            for ($cursor = $start->copy(); $cursor <= $end; $cursor->addMonth()) {
                foreach ($kpis as $kpi) {
                    KpiDivisi::updateOrCreate(
                        [
                            'division_id' => $division->id,
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
}
