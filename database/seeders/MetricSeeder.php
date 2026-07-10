<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Metric;
use Illuminate\Database\Seeder;

class MetricSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'A' => [
                ['Production Output', 'kMH', 'naik'],
            ],
            'B' => [
                ['Customer claim Register', 'QTY', 'turun'],
                ['Customer claim /sales', 'QTY/100 kMH', 'turun'],
                ['Customer claim Unregister', 'QTY', 'turun'],
                ['Customer claim /sales', 'QTY/100 kMH', 'turun'],
                ['Receiving claim', 'QTY', 'turun'],
                ['Receiving claim/sales', 'QTY/100 kMH', 'turun'],
                ['Internal Defect PA', 'ppm', 'turun'],
                ['Internal Defect FA', 'dpm', 'turun'],
            ],
            'C' => [
                ['Man power', 'Orang', 'naik'],
                ['Man power DL', 'Orang', 'naik'],
                ['Man power IDL', 'Orang', 'naik'],
                ['Man power PS', 'Orang', 'naik'],
                ['Man power ADM', 'Orang', 'naik'],
                ['Man power Other', 'Orang', 'naik'],
            ],
            'D' => [
                ['Production Output', 'kMH', 'naik'],
                ['Direct Efficiency', '%', 'naik'],
                ['Direct Productivity', '%', 'naik'],
                ['Downtime ratio', '%', 'turun'],
                ['Excluding time Ratio', '%', 'turun'],
                ['Total Production', '%', 'naik'],
                ['Total Efisiensi', '%', 'naik'],
                ['Total Efisiensi Include Layoff', '%', 'naik'],
                ['Output/orang', 'MH/orang', 'naik'],
            ],
            'E' => [
                ['Rate of Indirect Personel', '%', 'turun'],
                ['Attendance Rate (exclude no loading)', '%', 'naik'],
                ['Attendance Ratio (include no loading)', '%', 'naik'],
                ['Turn Over Ratio', '%', 'turun'],
            ],
            'F' => [
                ['Production Output/m2', 'MH/m2', 'naik'],
                ['Ratio 2 Shift', '%', 'naik'],
            ],
            'G' => [
                ['Sales (kMH)', 'kMH', 'naik'],
                ['Sales (USD)', 'kUSD', 'naik'],
                ['Process Charge', 'USD/MH', 'naik'],
                ['Profit Improvement', 'kUSD', 'naik'],
                ['Profit Improvement Additional', 'kUSD', 'naik'],
            ],
            'H' => [
                ['Air Freight Material', 'kUSD', 'turun'],
                ['Air Freight F/G', 'kUSD', 'turun'],
                ['Sorting Cost', 'kUSD', 'turun'],
                ['Overtime Cost', 'kUSD', 'turun'],
                ['Inventory Level Material', 'Days', 'turun'],
                ['Inventory Level WIP', 'Days', 'turun'],
                ['Inventory Level Finish Good', 'Days', 'turun'],
            ],
            'I' => [
                ['Cutting Output/Jam', 'Pcs/jam', 'naik'],
                ['Cutting Output/Jam', 'Pcs/jam', 'naik'],
                ['Wire Loss', '%', 'turun'],
            ],
            'J' => [
                ['Delay Shipment/SR (QTY)', '%', 'turun'],
                ['Quality Supplier Performance', 'PPM', 'naik'],
                ['Delivery Supplier Performance', '%', 'naik'],
            ],
            'K' => [
                ['Kecelakaan Kerja (Internal & External)', 'QTY', 'turun'],
                ['Temporary Layoff Eksternal', 'kUSD', 'turun'],
                ['Temporary Layoff Internal', 'kUSD', 'turun'],
                ['OT Manhour', 'kMH', 'turun'],
            ],
        ];

        foreach ($data as $kodeGroup => $items) {
            $group = Group::where('kode_group', $kodeGroup)->first();

            foreach ($items as [$namaItem, $satuan, $arahTarget]) {
                Metric::create([
                    'group_id' => $group->id,
                    'nama_item' => $namaItem,
                    'satuan' => $satuan,
                    'arah_target' => $arahTarget,
                    'is_aktif' => true,
                ]);
            }
        }
    }
}