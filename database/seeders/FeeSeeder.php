<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Fee;

class FeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Fee::create(['name' => 'Iuran Bulanan', 'amount' => 50000]);
        Fee::create(['name' => 'Iuran Tahunan', 'amount' => 600000]);
    }
}
