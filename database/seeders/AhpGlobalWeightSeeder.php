<?php

namespace Database\Seeders;

use App\Models\AhpGlobalWeight;
use Illuminate\Database\Seeder;

class AhpGlobalWeightSeeder extends Seeder
{
    public function run(): void
    {
        $weight = AhpGlobalWeight::query()->firstOrNew(['id' => 1]);

        $weight->forceFill([
            'w_kpi_umum' => 0.33333333,
            'w_kpi_divisi' => 0.50000000,
            'w_peer' => 0.16666667,
            'lambda_max' => 3.00000000,
            'ci' => 0.00000000,
            'cr' => 0.00000000,
        ])->save();
    }
}
