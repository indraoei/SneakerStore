<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\User;

class UserController extends Controller
{
    function SignUp(Request $request){
        try{
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'address' => 'required',
                'phone_number' => 'required'
            ]);

            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = bcrypt($request->input('password'));
            $user->address = $request->input('address');
            $user->phone_number = $request->input('phone_number');
            $user->save();

            $token = JWTAuth::fromUser($user);
            return response()->json(['message' => 'Succesfully Create User', 'token' => $token], 200);

            // return "Success";
        }
        catch(\Exception $e){
             return "Failure";
            // return response()->json(['message' => 'Failed to create user, exception:' + $e], 500);
        }
    }

    function SignIn(Request $request){
        $credentials = $request->only('email','password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        }
        catch (\JWTException $e){
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json(compact('token'));
    }
}
