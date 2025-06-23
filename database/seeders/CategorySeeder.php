<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = array(
            ["name" => "Ukiran Tongkonan"],
            ["name" => "Ritual Adat"],
            ["name" => "Sejarah dan Legenda Toraja"],
            ["name" => "Rumah Adat Toraja (Tongkonan)"],
            ["name" => "Seni dan Musik Tradisional"],
            ["name" => "Pakaian Adat dan Perhiasan"],
            ["name" => "Kuliner Tradisional Toraja"],
            ["name" => "Sistem Kepercayaan dan Filosofi Hidup"],
            ["name" => "Lokasi Wisata Budaya"],
            ["name" => "Bahasa dan Sastra Toraja"],
        );

        Category::insert($data);
    }
}
