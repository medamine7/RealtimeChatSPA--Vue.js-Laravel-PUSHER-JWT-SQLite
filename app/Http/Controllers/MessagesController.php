<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\Conversation;
use App\Conversation_user;
use JWTAuth;
use App\Events\MessageSent;
use App\Events\ConversationEvent;


class MessagesController extends Controller
{
    public function sendMessage(Request $request){
        $conversation_id=$request->get('conversation_id');
    	$message_to=$request->get('message_to');
    	$body=$request->get('body');
    	$author_id= JWTAuth::parseToken()->toUser()->id;
        $new = false;
        $message;
        
        if( $conversation_id ){
        	$message= new Message([
        		'body' => $body,
        		'author_id' => $author_id,
        		'conversation_id' => $conversation_id
        	]);

        	$message->save();

        }else{
            $new_conversation = new Conversation();
            $new_conversation->save();
            $conversation_id=$new_conversation->id;
            $new=true;

            $first_conversation_user = new Conversation_user([
                'user_id' => $message_to,
                'conversation_id' => $new_conversation->id
            ]);
            $first_conversation_user->save();

            $second_conversation_user = new Conversation_user([
                'user_id' => $author_id,
                'conversation_id' => $new_conversation->id
            ]);
            $second_conversation_user->save();

            

            $message= new Message([
                'body' => $body,
                'author_id' => $author_id,
                'conversation_id' => $new_conversation->id
            ]);

            $message->save();
        }


        broadcast(new MessageSent($message))->toOthers();
        broadcast(new ConversationEvent($message_to))->toOthers();
    	   
        return response()->json(["conversation_id"=>$conversation_id,"new_conversation" => $new],200);
    }
}
