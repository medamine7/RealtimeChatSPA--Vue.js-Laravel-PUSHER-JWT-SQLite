<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conversation;
use JWTAuth;


class ConversationsController extends Controller
{

    	public function getConversation(Request $request){
    		$conversation_id=$request->get('conversation_id');
            $user_id=JWTAuth::parseToken()->toUser()->id;

            $conversation=Conversation::where('id',$conversation_id)
            ->with(['messages' => function($query){
                $query->orderBy('created_at','desc');
            },
            'users' => function($query) use ($user_id){
                $query->where('user_id','!=', $user_id)
                ->first();
            }])
            ->first();

            return response()->json(compact('conversation'),200);
        }


        public function getConversations(Request $request){
            $user_id=JWTAuth::parseToken()->toUser()->id;

            $conversations = Conversation::orderBy('created_at','desc')
            ->with(['users' => function($query) use ($user_id){
                $query->where('user_id','!=', $user_id)
                ->get();
            },
            'last_message'])
            ->whereHas('users', function($query) use ($user_id){
                $query->where('user_id',$user_id);
            })->get();

            return response()->json(compact('conversations'),200);
    	}
}
