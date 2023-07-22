<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class babyNamesOriginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array()

        baby_name_origin::insert($data);
    }
}
