<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Mail\EventMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function signIn(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
        if (auth('web')->attempt($credentials)) {
            $user = auth('web')->user();
          
            $token = $user->createToken("User")->accessToken;
            
            return response()->json([
                'data' => $user->refresh(),
                'token' => $token,
            ]);
            // return $user;
        } else {
            return "fail";
        }
    }

    public function registration(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required',
            'password' => 'required|min:8|confirmed',

        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($request->password)
        ]);
        $token = $user->createToken("User");
        $accessToken = $token->accessToken;
        return response()->json([
            'data' => $user->refresh(),
            'token' => $accessToken,
        ]);
    }



    
    public function users()
    {
        $users =  User::all();
        return response()->json([
            "users" => $users,
        ]);
    }
}
