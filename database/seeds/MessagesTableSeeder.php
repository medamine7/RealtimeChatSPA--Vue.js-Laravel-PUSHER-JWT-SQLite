<?php

use Illuminate\Database\Seeder;

class MessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
         DB::table('messages')->insert([
            'body' => 'hi',
            'author_id' => 1,
            'conversation_id' => 1,
        ]);

        DB::table('messages')->insert([
            'body' => 'hello',
            'author_id' => 2,
            'conversation_id' => 1,
        ]);



        DB::table('messages')->insert([
            'body' => 'hello Peter',
            'author_id' => 1,
            'conversation_id' => 2,
        ]);


        DB::table('messages')->insert([
            'body' => 'hello John',
            'author_id' => 3,
            'conversation_id' => 2,
        ]);
    }
}
