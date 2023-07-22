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
            return "login successfully";
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
        $token = $user->createToken("nasa");
        $accessToken = $token->accessToken;
                //  $mail = $request->email;       
    // $mail= Mail::to($mail)->send(new EventMail());
        

        // /**
        //  * Check if the email has been sent successfully, or not.
        //  * Return the appropriate message.
        //  */
        // if($mail){
        return response()->json([
            'data' => $user->refresh(),
            'token' => $accessToken,
            // "Email has been sent successfully."
        ]);
        // }
        // return "Oops! There was some error sending the email.";
    }
    
    public function index(){
        $events=  Event::all();
        return response()->json([
           "users"=>$events,
        ]);
    }
}