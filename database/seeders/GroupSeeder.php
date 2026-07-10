<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            ['kode_group' => 'A', 'nama_group' => 'Production Output'],
            ['kode_group' => 'B', 'nama_group' => 'Customer & Receiving Claim'],
            ['kode_group' => 'C', 'nama_group' => 'Man Power'],
            ['kode_group' => 'D', 'nama_group' => 'Efficiency & Productivity'],
            ['kode_group' => 'E', 'nama_group' => 'Personnel Rate'],
            ['kode_group' => 'F', 'nama_group' => 'Production per M2'],
            ['kode_group' => 'G', 'nama_group' => 'Sales & Process Charge'],
            ['kode_group' => 'H', 'nama_group' => 'Freight & Inventory'],
            ['kode_group' => 'I', 'nama_group' => 'Cutting'],
            ['kode_group' => 'J', 'nama_group' => 'Shipment & Supplier'],
            ['kode_group' => 'K', 'nama_group' => 'Safety & Layoff'],
        ];

        foreach ($groups as $group) {
            Group::create($group);
        }
    }
}