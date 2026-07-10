<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            ['kode_group' => 'A'],
            ['kode_group' => 'B'],
            ['kode_group' => 'C'],
            ['kode_group' => 'D'],
            ['kode_group' => 'E'],
            ['kode_group' => 'F'],
            ['kode_group' => 'G'],
            ['kode_group' => 'H'],
            ['kode_group' => 'I'],
            ['kode_group' => 'J'],
            ['kode_group' => 'K'],
        ];

        foreach ($groups as $group) {
            Group::create($group);
        }
    }
}