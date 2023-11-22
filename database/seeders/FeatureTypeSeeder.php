<?php

namespace Database\Seeders;

use App\Models\FeatureType;
use Illuminate\Database\Seeder;

class FeatureTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (FeatureType::getList() as $row) {
            FeatureType::firstOrCreate($row);
        }
    }
}
