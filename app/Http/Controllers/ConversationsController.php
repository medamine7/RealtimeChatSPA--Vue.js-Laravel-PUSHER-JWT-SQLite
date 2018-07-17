<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conversation;
use JWTAuth;
Use DB;

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




            $convs = DB::table('conversation_user')
            ->select('conversation_id')
            ->where('user_id',$user_id)
            ->get();

            $convs = json_decode(json_encode($convs), true);

            $result=collect([]);

            foreach($convs as $conv){

                $temp=DB::table('conversations')
                ->where('conversations.id',$conv)
                ->join('conversation_user','conversations.id','=','conversation_user.conversation_id')
                ->join('users','users.id','=','conversation_user.user_id')
                ->join('messages','messages.conversation_id','=','conversations.id')
                ->select('users.name','users.avatar','conversations.id as conversation_id','messages.*')
                ->where('users.id','!=',1)
                ->orderBy('messages.created_at','desc')
                ->first();


                $result->push(array(
                    "user" => [
                                "name"=>$temp->name,
                                "avatar"=>$temp->avatar
                            ],
                    "conversation_id" => $temp->conversation_id,
                    "id"=> $temp->id,
                    "body"=> $temp->body,
                    "author_id"=> $temp->author_id,
                    "created_at"=> $temp->created_at,
                    "updated_at"=> "$temp->updated_at"

                ));
            }

            $result=$result->sortByDesc('created_at');



            /***    *************************/

            return response()->json(['conversations' => $result] ,200);
    	}
}
