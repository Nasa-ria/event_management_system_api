<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Mail\EventMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegistrationController extends Controller
{
    public function SignIn(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
        // dd(Auth::attempt($credentials));
        if (Auth::attempt($credentials)) {
            return "login successfully";
        }
        return "fail";
    }

    public function Registration(Request $request){
       
        $request->validate([
            'event_name' => 'required',
            'contact'=>'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'attendees'=>'required',

        ]);

        $event= Event::create([
            'event_name' => request()->event_name,
            'attendees' => request()->attendees,
            'contact' => request()->contact,
            'email' => request()->email,
            'password' => Hash::make($request['password']),

        ]);
        $token = $event->createToken("events");
        $accessToken = $token->accessToken;
                 $mail = $request->email;       
    $mail= Mail::to($mail)->send(new EventMail());
        

        // /**
        //  * Check if the email has been sent successfully, or not.
        //  * Return the appropriate message.
        //  */
        if($mail){
        return response()->json([
            'data' => $event->refresh(),
            'token' => $accessToken,
            "Email has been sent successfully."
        ]);
        }
        return "Oops! There was some error sending the email.";
    }
    
    public function index(){
        $events=  Event::all();
        return response()->json([
           "users"=>$events,
        ]);
    }
}
