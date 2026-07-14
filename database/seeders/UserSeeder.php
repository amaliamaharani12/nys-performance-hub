<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin NYS',
            'email' => 'admin@nys.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'group_id' => null,
            'is_aktif' => true,
        ]);

        $groups = Group::orderBy('kode_group')->get();

        foreach ($groups as $group) {
            User::create([
                'name' => 'PIC Group ' . $group->kode_group,
                'email' => 'pic.' . strtolower($group->kode_group) . '@nys.test',
                'password' => Hash::make('password'),
                'role' => 'pic',
                'group_id' => $group->id,
                'is_aktif' => true,
            ]);
        }
    }
}