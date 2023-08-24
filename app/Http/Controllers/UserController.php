<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;




class UserController extends Controller
{

    public function users()
    {
        $users =  User::all();
        return response()->json([
            "users" => $users,
        ]);
    }


    public function loginWithGoogle()
    {
        try{
            Socialite::driver('google')->stateless()->redirect();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Google authentication failed: ' . $e->getMessage()]);
        }
      
    }

    public function loginWithGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();

                    //  dd($user);
            // $googleUser = User::where('google_id', $user->id)->first();
    
            // if ($googleUser) {
            //     Auth::login($googleUser, true); 
            // } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id
                ]);
                dd(Auth::login($newUser));
                
            // }
    
            // return response()->json(['message' => 'Logged in with Google', 'user' => Auth::user()]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Google authentication failed: ' . $e->getMessage()]);
        }
    }
    
    
       // $user = User::updateOrCreate([
                //     'googl_id' => $googleUser->id,
                // ], [
                //     'name' => $googleUser->name,
                //     'email' => $googleUser->email,
                //     'google_token' => $googleUser->token,
                //     'google_refresh_token' => $googleUser->refreshToken,
                // ]);
    
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
            'password' => 'required',
            'contact' => 'required'
        ]);
       

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'contact' => $validated['contact'],
            'password' => Hash::make($validated['password'])
        ]);
        $token = $user->createToken("User");
        $accessToken = $token->accessToken;
        return response()->json([
            'data' => $user->refresh(),
            'token' => $accessToken,
        ]);
    }


    public function update(Request $request, User $user)
    { 
            $user = User::find($user);
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $image = $file->getClientOriginalName();
                $path= $request->file('image')->storeAs('public/image/profile', $image);
                // Process the file
            } else {
                $image = $user->image; 
            }

            if($user){
                $user->$request->get('name');
                $user ->$request->get('email');
                $user->$request->get('about');
                $user->$request->get('subscription_plan');
                $user ->$request->get('contact');
                $user->image = $image;
                $user->save();
                return response()->json(['message' => 'user updated successfully', 'data' => $user]);
            }
        
        }
    

    public function profile(Request $request, User $user)
    {
        $user = User::findOrFail($user);
        return $user;
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return "loged out ";
    }
}
