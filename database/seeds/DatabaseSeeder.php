<?php

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
        $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(GgnsTableSeeder::class);
        $this->call(ArtikelsTableSeeder::class);
        $this->call(KundesTableSeeder::class);
        $this->call(DienstleistungsTableSeeder::class);
    }
}
