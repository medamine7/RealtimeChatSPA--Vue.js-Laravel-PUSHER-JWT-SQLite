<?php

use Illuminate\Database\Seeder;

class Conversation_userTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('conversation_user')->insert([
            'user_id' => 1,
            'conversation_id' => 1,
        ]);

        DB::table('conversation_user')->insert([
            'user_id' => 2,
            'conversation_id' => 1,
        ]);



        DB::table('conversation_user')->insert([
            'user_id' => 1,
            'conversation_id' => 2,
        ]);


        DB::table('conversation_user')->insert([
            'user_id' => 3,
            'conversation_id' => 2,
        ]);
    }
}
