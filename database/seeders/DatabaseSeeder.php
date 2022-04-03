<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
//        $this->call(ArticleSeeder::class);
        $this->call(OriginSeeder::class);
         \App\Models\User::factory(20)->create();
         \App\Models\BabyName::factory(200)->create();
         \App\Models\Article::factory(300)->create();
         \App\Models\Message::factory(20)->create();
         \App\Models\Category::factory(20)->create();
    }
}
