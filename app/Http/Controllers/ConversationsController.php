<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conversation;
use JWTAuth;


class ConversationsController extends Controller
{

    	public function getConversation(Request $request){
    		$conversation_id=$request->get('conversation_id');

    		$conversation=Conversation::where('id',$conversation_id)->get();
    	}


    	public function getConversations(Request $request){
    		$user_id=JWTAuth::parseToken()->toUser()->id;

    		$conversations = Conversation::where(function ($query) use ($user_id) {
			    $query->where('user1_id' , $user_id)
			          ->orWhere('user2_id' , $user_id);
			})
			->orderBy('created_at','desc')
			->get();

			return response()->json(compact('conversations'),200);
    	}
}
