<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DivisionSeeder::class,
            UserSeeder::class,
            KpiUmumSeeder::class,
            KpiDivisiSeeder::class,
            AhpKpiWeightSeeder::class,
            AhpGlobalWeightSeeder::class,
            AspekSeeder::class,
            KpiDivisiKuantitatifDistributionSeeder::class,
            KpiUmumRealisasiSeeder::class,
            KpiDivisiKuantitatifRealizationSeeder::class,
            KpiDivisiKualitatifRealizationSeeder::class,
            KpiDivisiResponseRealizationSeeder::class,
            KpiDivisiPersentaseRealizationSeeder::class,
            PeerAssessmentSeeder::class,
        ]);
    }
}
