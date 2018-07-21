<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use JWTAuth;
use Hash;

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



    public function changeAvatar(Request $request){

        $avatarBase64= $request->get('avatar');
        $user_id=$request->get('user_id');


        $exploded=explode(',' , $avatarBase64);
        $avatarBase64_decoded=base64_decode($exploded[1]);

        $extension= str_contains($exploded[0],'jpeg')? 'jpg' : 'png';


        $avatar_name = "avatar-".time().'-'.$user_id.'.'.$extension;

        $path = public_path().'/img/' . $avatar_name;

        file_put_contents($path, $avatarBase64_decoded);


        $user= User::find($user_id);
        $user->avatar='/img/'.$avatar_name;
        $user->save();


        return response()->json(['message'=>'avatar changed',
            'new_path' => $user->avatar]
            ,200);
    }
    
    public function changePassword(Request $request){

        $input_password= $request->get('password');
        $new_password = $request->get('new_password');

        $this->validate($request, [
            'password' => 'required|string|min:6', 
            'new_password' => 'required|string|min:6', 
        ]);

        $user = JWTAuth::parseToken()->toUser();




        if (Hash::check($input_password, $user->password)) {
            $user = User::find($user->id);

            $user->password = bcrypt($new_password);

            $user->save();
        }else {
             return response()->json([
                'status' => 'error',
                'message'    => 'Wrong password'
            ], 401);
        }

    }
}


