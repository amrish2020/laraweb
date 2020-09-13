<?php

use Illuminate\Database\Seeder;

class HotelPackagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hotel_packages')->insert([
            'hotel_name' => 'Galaxy hotel',
            'price' => '200',
            'package_validity' => '15 days',
            'duration' => '3 Dyas 2 nights',
            'photo' => 'default.png',
            'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries',
        ]);
        DB::table('hotel_packages')->insert([
            'hotel_name' => 'Paradise hotel',
            'price' => '100',
            'package_validity' => '15 days',
            'duration' => '2 Dyas 1 nights',
            'photo' => 'default.png',
            'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries',
        ]);
        DB::table('hotel_packages')->insert([
            'hotel_name' => 'Sun Inn hotel',
            'price' => '300',
            'package_validity' => '15 days',
            'duration' => '4 Dyas 3 nights',
            'photo' => 'default.png',
            'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries',
        ]);
    }
}
