<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'name'      => 'Buku',
            'price'     => 10000,
            'stock'  => 15,
        ]);
        Product::create([
            'name'      => 'Pensil',
            'price'     => 5000,
            'stock'  => 20,
        ]);
        Product::create([
            'name'      => 'Penghapus',
            'price'     => 3000,
            'stock'  => 5,
        ]);
    }
}
