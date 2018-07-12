<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\Conversation;
use JWTAuth;

class MessagesController extends Controller
{
    public function sendMessage(Request $request){
    	$conversation_id=$request->get('conversation_id');
    	$body=$request->get('body');
    	$author_id= JWTAuth::parseToken()->toUser()->id;

        
        if( Conversation::find(1) ){
        	$message= new Message([
        		'body' => $body,
        		'author_id' => $author_id,
        		'conversation_id' => $conversation_id
        	]);

        	$message->save();

        }else{
            $new_conversation = new Conversation();
            $new_conversation->save();

            $message= new Message([
                'body' => $body,
                'author_id' => $author_id,
                'conversation_id' => $new_conversation->id
            ]);

            $message->save();
        }



    	return response()->json(['message'=>'your message was sent successfully!'],201);

    }
}
