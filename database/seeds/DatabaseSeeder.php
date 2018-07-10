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
        $this->call([
        	UsersTableSeeder::class,
        	MessagesTableSeeder::class,
        	ConversationsTableSeeder::class,
        	Conversation_userTableSeeder::class
        ]);
    }
}
