<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Oli Mesin 10W-40',    'sku' => 'OLI-001', 'price' => 45000,  'stock' => 20, 'unit' => 'liter'],
            ['name' => 'Filter Oli',           'sku' => 'FLT-001', 'price' => 35000,  'stock' => 15, 'unit' => 'pcs'],
            ['name' => 'Busi NGK',             'sku' => 'BSI-001', 'price' => 25000,  'stock' => 30, 'unit' => 'pcs'],
            ['name' => 'Kampas Rem Depan',     'sku' => 'REM-001', 'price' => 120000, 'stock' => 10, 'unit' => 'set'],
            ['name' => 'Cairan Radiator',      'sku' => 'RAD-001', 'price' => 55000,  'stock' => 8,  'unit' => 'liter'],
            ['name' => 'Van Belt',             'sku' => 'VBL-001', 'price' => 85000,  'stock' => 5,  'unit' => 'pcs'],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}