<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use JWTAuth;

class UserController extends Controller
{

    public function login(Request $request){
        $this->validate($request, [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6', 
        ]);

        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));

    }


    public function register(Request $request){

    	$this->validate($request, [
    		'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
    	]);

    	$user = new User([
    		'name' => $request->input('name'),
    		'email' => $request->input('email'),
    		'password' => bcrypt($request->input('password'))
    	]);


    	$user->save();

    	return response()->json([
			"message"=>"User created"
		],201);


    }


    public function userInfo(Request $request){
        $user = JWTAuth::parseToken()->toUser();

        return response()->json(compact('user'),200);
    }


    public function findUser(Request $request){
        $user_id = JWTAuth::parseToken()->toUser()->id;
        $keyword=$request->get('keyword');

        $users= User::where('name','LIKE', "%{$keyword}%")
        ->where('id','!=', $user_id)
        ->get();

        return response()->json(compact('users'),200);
    }


    public function findUserConversation(Request $request){
        $user_id = JWTAuth::parseToken()->toUser()->id;
        $other_user_id= $request->get('id');
        $conversation = User::find($user_id)
        ->conversations()
        ->whereHas('users',function($query) use ($other_user_id){
            $query->where('user_id',$other_user_id);
        })
        ->with(['users' => function($query) use ($other_user_id){
            $query->where('user_id', $other_user_id)
            ->get();
        },
        'messages' => function($query){
                $query->orderBy('created_at','desc');
            }
        ])
        ->first();
        return response()->json(compact('conversation'),200);
    }
}
