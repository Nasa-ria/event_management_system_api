<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Mail\EventMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegistrationController extends Controller
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
            
            return $user;
        } else {
            return "fail";
        }
        
    }

    public function registration(Request $request){
       
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
    
   

    public function eventRegistration(Request $request , User $user){
        $validated = $request->validate([
            'event' => 'required|string',
            'email' => 'required',
            'attendees' => 'required',
            'contact' =>'required'

        ]);

        $event = Event::create([
            'user_id' => $user->id, // Corrected the assignment of user_id
            'event' => $validated['event'],
            'email' => $validated['email'],
            'attendees' => $validated['attendees'],
            'contact' => $validated['contact'],
           
        ]);
        $email = $validated['email'];
        Mail::to($email)->send(new EventMail());
        return response()->json([
            "event"=>$event,
         ]);
     }
     public function users(){
        $users=  User::all();
         return response()->json([
            "users"=>$users,
         ]);
    }
}