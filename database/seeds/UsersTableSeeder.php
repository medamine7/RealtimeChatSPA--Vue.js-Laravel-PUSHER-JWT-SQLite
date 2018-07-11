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
        

        DB::table('users')->insert([
            'name' => 'John Doe',
            'email' => 'john@gmail.com',
            'avatar' => '/img/user1.jpg',
            'password' => bcrypt('123456'),
        ]);


        DB::table('users')->insert([
            'name' => 'Jane Rose',
            'email' => 'jane@gmail.com',
            'avatar' => '/img/user2.jpg',
            'password' => bcrypt('123456'),
        ]);


        DB::table('users')->insert([
            'name' => 'Peter Parker',
            'email' => 'spiderman@gmail.com',
            'avatar' => '/img/user3.jpg',
            'password' => bcrypt('123456'),
        ]);
    }
}
