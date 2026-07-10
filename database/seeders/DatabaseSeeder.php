<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            GroupSeeder::class,
            MetricSeeder::class,
            UserSeeder::class,
            TargetSeeder::class,
            ActualSeeder::class,
        ]);
    }
}