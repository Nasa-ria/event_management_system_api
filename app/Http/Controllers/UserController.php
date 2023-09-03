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
           $google= Socialite::driver('google')->stateless()->redirect();
         
        } catch (\Exception $e) {
            return response()->json(['message' => 'Google authentication failed: ' . $e->getMessage()]);
        }
      
    }

    public function loginWithGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();

    
            $googleUser = User::where('google_id', $user->id)->first();
            if ($googleUser) {
                Auth::login($googleUser, true);
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id
                ]);
                Auth::login($newUser);
            }
    
            return response()->json(['message' => 'Logged in with Google', 'user' => Auth::user()]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Google authentication failed: ' . $e->getMessage()]);
        }
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
      
        return response()->json([
            'data' => $user->refresh()
        ]);
    }


    public function update(Request $request, User $user)
    { 
           dd($request->all());
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'image' => 'required',
            'contact' => 'required',
            'subscription_plan' => 'required',
            'about' => 'required'
        ]);

        $user = User::find($user);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        // Handle image upload and update the user's image field
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
    
            // Update the user's image property
               $user->image = $imageName;
                $user->$request->get('name');
                $user ->$request->get('email');
                $user->$request->get('about');
                $user->$request->get('subscription_plan');
                $user ->$request->get('contact');
                $user->save();
                return response()->json(['message' => 'user updated successfully', 'data' => $user]);
            }

            return response()->json(['message' => 'Image upload failed'], 400);
        
        }
    

    public function profile(Request $request, User $user)
    {
        $user = User::find($user);
        return $user;
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return "loged out ";
    }
}
