<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{

    public function users()
    {
        $users =  User::all();
        return response()->json([
            "users" => $users,
        ]);
    }
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
            'password'=>'required',
            'contact'=>'required'


        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'contact' => $validated['contact'],
            'password' =>Hash::make($validated['password'])
        ]);
        $token = $user->createToken("User");
        $accessToken = $token->accessToken;
        return response()->json([
            'data' => $user->refresh(),
            'token' => $accessToken,
        ]);
    }


    public function update(Request $request,User $user){
        {
            $user = User::findOrFail($user);
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'contact' => 'required',
                'about' => 'required',
                'subscription_plan'=>'required'

            ]);
            $image = $request->file('profile')->getClientOriginalName();
            $image = $request->file('image')->storeAs('public/image/profile', $image);
            $user->update([
                'event' => $request->name,
                'email' => $request->email, 
                'about' => $request->about,
                'subscription_plan'=>$request->subscription_plan,
                'contact' => $request->contact,
                'image' => $image,
            ]);
    
            return response()->json(['message' => 'user updated successfully', 'data' => $user]);
        }
    }
     
         public function profile(Request $request,User $user){
            $user = User::findOrFail($user);
            return $user;
         }
   
         public function logout(Request $request) {
            Auth::logout();
            return"loged out ";
          }
}
