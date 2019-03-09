<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\User::class, 1)->create([
            'email' => 'gabrieldarezzo@yahoo.com.br',
            'name' => 'Gabriel Darezzo',
        ]);
        factory(\App\User::class, 19)->create();
    }
}
