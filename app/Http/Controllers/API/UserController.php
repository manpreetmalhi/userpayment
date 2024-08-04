<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;


class UserController extends Controller
{
    // created Register API
    public function register(Request $request)
    {
           try{
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:100',
            'email'=>'required|string|email|max:100|unique:users',
            'password'=>'required|string|min:6|confirmed',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);

        return response()->json([
            'msg'=>'User Registered Successfully',
            'user'=>$user ,
        ]);
    }catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'msg' => $e->getMessage(),
        ]);
    }

    }
    public function test(){
        return response()->json([
            'msg'=>'User Registered Successfully',
            'user'=>'123',
        ]);
    }

    // created Login API
    public function login(Request $request)
    {
        try{
        $validator = Validator::make($request->all(), [
            'email'=>'required|string|email|',
            'password'=>'required|string|min:6',
        ]);

        if ($validator->fails())
        {
            return response()->json($validator->errors());
        }
        
        $token = auth()->attempt($validator->validated());
        if (!$token)
        {
            return response()->json([
                'success'=>false,
                'msg' =>'Username or Password is incorrect.',
            ]);

        }
        return response()->json([
            'success' => true,
            'msg'=>'Successfully Login',
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->factory()->getTTL()*60
        ]);
    }catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'msg' => $e->getMessage(),
        ]);
    }
    }
    // created Logout API
    public function logout()
    {
        try {
            auth()->logout();
            return response()->json([
                'success' => true,
                'msg' => 'User Log out Successfull',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => $e->getMessage(),
            ]);

        }

    }

    // created profile API
    public function profile()
    {
        try {
            return response()->json([
                'success' => true,
                'data' => auth()->user(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => $e->getMessage(),
            ]);

        }

    }

    // created profile-update API
    public function updateProfile(Request $request)
    {
try{
        if (auth()->user()){
            $validator = Validator::make($request->all(), [
                'id'=>'required',
                'name'=>'required|string',
                'email'=>'required|string|email',
            ]);

            if ($validator->fails())
            {
                return response()->json($validator->errors());
            }

            $user = User::find($request->id);
            $user->name = $request->name;
            if ($user->email != $request->email) {
                $user->is_verified = 0;
            }
            $user->email = $request->email;
            $user->save();
            return response()->json([
                'success' => true,
                'msg' => 'User Updated Successfully',
                'data' => $user,
            ]);



        } else {
            return response()->json([
                'success' => false,
                'msg' => 'User is Not Authenticated',
            ]);
        }
    }catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'msg' => $e->getMessage(),
        ]);
    }
    }
     // created profile-destroy API
     public function deactiveProfile(Request $request)
     {
        try{
 
         if (auth()->user()){
             $validator = Validator::make($request->all(), [
                 'id'=>'required'
             ]);
 
             if ($validator->fails())
             {
                 return response()->json($validator->errors());
             }
            
             User::destroy($request->id);
            
             return response()->json([
                 'success' => true,
                 'msg' => 'User Deactivate Successfully',
                
             ]);
 
 
 
         } else {
             return response()->json([
                 'success' => false,
                 'msg' => 'User is Not Authenticated',
             ]);
         }
        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => $e->getMessage(),
            ]);
        }
     }
 
    // send verification mail with verify link
    public function sendVerifyMail($email)
    {
        if (auth()->user()){
            $user = User::where('email', $email)->get();
            if (count($user) > 0) {
              
                $random = Str::random(40);
                $domain = URL::to('/');
                $url = $domain.'/verify-mail/'.$random;

                
                $data['url'] = $url;
                $data['email'] = $email;
                $data['title'] = 'Email Verification';
                $data['body'] = 'Please click here to below verify your mail';

                Mail::send('verifyMail', ['data'=>$data], function($message) use ($data){
                    $message->to($data['email'])->subject($data['title']);
                });

                $user = User::find($user[0]['id']);
                $user->remember_token = $random;
                $user->save();
                return response()->json([
                    'success' => true,
                    'msg' => 'Mail Sent Successfully',
                ]);


            } else {
                return response()->json([
                    'success' => false,
                    'msg' => 'User not found.',
                ]);
            }

        } else {
            return response()->json([
                'success' => false,
                'msg' => 'User is Not Authenticated',
            ]);
        }
    }

    // token link after verification message
    public function verificationMail($token)
    {
        $user = User::where('remember_token', $token)->get();
        if (count($user) > 0) {
            $datetime = Carbon::now()->format('Y-m-d H:i:s');
            $user = User::find($user[0]['id']);
            $user->remember_token = '';
            $user->is_verified = 1;
            $user->email_verified_at = $datetime;
            $user->save();

            return "<h1>Email Verified Successfully</h1>";


        } else {
            return view('404');
        }

    }

    public function refreshToken()
    {

        if (auth()->user()){

            return response()->json([
                'success' => true,
                'token' => auth()->refresh(),
                'token_type' => 'Bearer',
                'expires_in' => auth()->factory()->getTTL()*60
            ]);



        } else {
            return response()->json([
                'success' => false,
                'msg' => 'User is Not Authenticated',
            ]);
        }
    }



}
