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
            'name' => 'Abariel Darezzo',
        ]);
        factory(\App\User::class, 1)->create([
            'email' => 'teste@yahoo.com.br',
            'name' => 'ADarezzo',
        ]);
        factory(\App\User::class, 5)->create();
    }
}
