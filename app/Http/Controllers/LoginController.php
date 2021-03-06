<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function login(Request $request){
    	$hash = app()->make('hash');

    	$username = $request->input('username');
    	$password = $request->input('password');

    	$user = User::where('username',$username)->first();
    	if(!$user){
            return response()->json($this->notif(array('status'=>'failed','message'=>'Username or password wrong!')));
    	}else{
    		if($hash->check($password,$user->password)){
    			$api_token = sha1(time());
    			$create_token = User::where('id',$user->id)->update(['api_token'=>$api_token]);
    			if($create_token){
    				$data['api_token'] = $api_token;
    				$data['user'] = $user;
                    return response()->json($this->notif(array('status'=>'success','data'=>$data)));
    			}
    		}else{
    			return response()->json($this->notif(array('status'=>'failed','message'=>'Username or password wrong!')));
    		}
    	}
    }

}
