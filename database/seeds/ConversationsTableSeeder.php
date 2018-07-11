<?php

use Illuminate\Database\Seeder;

class ConversationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $conv1 = new App\Conversation();
        $conv1->save();


        $conv2 = new App\Conversation();
        $conv2->save();



    }
}
