<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use JWTAuth;

class MessagesController extends Controller
{
    public function sendMessage(Request $request){
    	$conversation_id=$request->get('conversation_id');
    	$body=$request->get('message');
    	$author_id= JWTAuth::parseToken()->toUser()->id;



    	$message= new Message([
    		'message' => $body,
    		'author_id' => $author_id,
    		'conversation_id' => $conversation_id
    	]);


    	$message->save();

    	return response()->json(['message'=>'your message was sent successfully!'],201);

    }
}
