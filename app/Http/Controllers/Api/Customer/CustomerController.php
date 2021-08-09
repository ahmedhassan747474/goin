<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Notification;
use App\Options;
class CustomerController extends Controller
{

    //customer login
    public function Login(Request $request)
    {
        $data=$request->validate([
            'email'     => 'required',
            'password'  => 'required',
            'token_fcm' => 'nullable'
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'The provided credentials are incorrect.'],400);
        }
        
        if($request->token_fcm){
            $user->token_fcm = $request->token_fcm;
            $user->save();
        }
        
        if($request->language){
            $user->language = $request->language;
            $user->save();
        }

        $token= $user->createToken('token')->plainTextToken;
        $token=explode('|', $token);
        
        $info['token']                  =$token[1];
        $info['login_id']               =$token[0];
        $info['id']                     =$user->id;
        $info['name']                   =$user->name;
        $info['avatar']                 =asset($user->avatar);
        $info['email']                  =$user->email;
        $info['status']                 =$user->status;
        $info['email_verified_at']      =$user->email_verified_at;
        $info['provider_facebook_id']   =$user->provider_facebook_id;
        $info['provider_google_id']     =$user->provider_google_id;
        $info['is_verify']              =$user->is_verify;
        $info['phone']                  =$user->phone;
        
        $tax = Options::where('key', 'tax')->first();
        $info['tax']                    =$tax->value;

        return response()->json($info);
        
        // return response()->json(['token'=>$token[1],'login_id'=>$token[0]]);
    }


    public function loginWithSocial(Request $request) 
    {
        $messages = [
            'email.regex'       => __('enter your email like example@gmail.xyz'),
        ];

        $validator = validator()->make($request->all(), [
            'name'          => 'required|max:50|string',
            'email'         => 'required|max:120|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            // 'image'         => 'required',
            'provider_id'   => 'required',
            'provider_type' => 'required|in:facebook,google,twitter',
            'token_fcm'     => 'nullable'
        ], $messages);

        if($validator->fails()) 
        {
            return $this->getErrorMessage($validator);
        }
        
        $checkAccount = User::where('provider_facebook_id', '=', $request->provider_id)
            ->orWhere('provider_google_id', '=', $request->provider_id)
            ->count();

        if ($checkAccount == 0) {

            $checkEmail = User::where('email', '=', $request->email)->count();

            if($checkEmail == 0) {
                if($request->image) {
                    $newData = file_get_contents($request->image);
                    $dir = "images/users";
                    $uploadfile = $dir . "/pic_" .time() . uniqid() .".jpg";
                    file_put_contents(public_path() . '/' . $uploadfile, $newData);
                    $profile_photo = $uploadfile;
                }

                $user = new User();
                $user->name         = $request->name;
                $user->email        = $request->email;
                // $user->image     = $profile_photo;
                if($request->token_fcm){
                    $user->token_fcm = $request->token_fcm;
                }
                
                if($request->language){
                    $user->language = $request->language;
                }

                if ($request->provider_type == 'facebook') {
                    $user->provider_facebook_id = $request->provider_id;
                } elseif($request->provider_type == 'google') {
                    $user->provider_google_id = $request->provider_id;
                }
                
                $user->role_id = 2;

                $user->save();
                        
                // $token = JWTAuth::fromUser($user);
                $token = $user->createToken('token')->plainTextToken;
                $token = explode('|', $token);

                $info['token']                  =$token[1];
                $info['login_id']               =$token[0];
                $info['id']                     =$user->id;
                $info['name']                   =$user->name;
                $info['avatar']                 =asset($user->avatar);
                $info['email']                  =$user->email;
                $info['status']                 =$user->status;
                $info['email_verified_at']      =$user->email_verified_at;
                $info['provider_facebook_id']   =$user->provider_facebook_id;
                $info['provider_google_id']     =$user->provider_google_id;
                $info['is_verify']              =$user->is_verify;
                $info['phone']                  =$user->phone;
                
                $tax = Options::where('key', 'tax')->first();
                $info['tax']                    =$tax->value;

                return response()->json($info);
                
            } else {
                
                if ($request->provider_type == 'facebook') {
                    User::where('email', '=', $request->email)
                        ->update([
                            'token_fcm'             => $request->token_fcm,
                            'provider_facebook_id'  => $request->provider_id,
                        ]);
                } elseif ($request->provider_type == 'google') {
                    User::where('email', '=', $request->email)
                        ->update([
                            'token_fcm'             => $request->token_fcm,
                            'provider_google_id'    => $request->provider_id,
                        ]);
                }
                
                $user = User::where('email', '=', $request->email)->first();

                // $token = JWTAuth::fromUser($user);
                $token = $user->createToken('token')->plainTextToken;
                $token = explode('|', $token);

                $info['token']                  =$token[1];
                $info['login_id']               =$token[0];
                $info['id']                     =$user->id;
                $info['name']                   =$user->name;
                $info['avatar']                 =asset($user->avatar);
                $info['email']                  =$user->email;
                $info['status']                 =$user->status;
                $info['email_verified_at']      =$user->email_verified_at;
                $info['provider_facebook_id']   =$user->provider_facebook_id;
                $info['provider_google_id']     =$user->provider_google_id;
                $info['is_verify']              =$user->is_verify;
                $info['phone']                  =$user->phone;
                
                $tax = Options::where('key', 'tax')->first();
                $info['tax']                    =$tax->value;

                return response()->json($info);
            }

        } else {
            
            // if ($request->provider_type == 'facebook') {
            //     User::where('provider_facebook_id', '=', $request->provider_id)->update(['token' => $request->token]);
            // } elseif ($request->provider_type == 'google') {
            //     User::where('provider_google_id', '=', $request->provider_id)->update(['token' => $request->token]);
            // }
            
            $user = User::where('email', '=', $request->email)->first();
            
            if($request->token_fcm){
                $user->token_fcm = $request->token_fcm;
                $user->save();
            }
            
            if($request->language){
                $user->language = $request->language;
                $user->save();
            }

            $token = $user->createToken('token')->plainTextToken;
            $token = explode('|', $token);

            $info['token']                  =$token[1];
            $info['login_id']               =$token[0];
            $info['id']                     =$user->id;
            $info['name']                   =$user->name;
            $info['avatar']                 =asset($user->avatar);
            $info['email']                  =$user->email;
            $info['status']                 =$user->status;
            $info['email_verified_at']      =$user->email_verified_at;
            $info['provider_facebook_id']   =$user->provider_facebook_id;
            $info['provider_google_id']     =$user->provider_google_id;
            $info['is_verify']              =$user->is_verify;
            $info['phone']                  =$user->phone;
            
            $tax = Options::where('key', 'tax')->first();
            $info['tax']                    =$tax->value;

            return response()->json($info);
            
        }
        return response()->json($response);
    }


    //customer register
    public function Register(Request $request)
    {
        
        $validator = \Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()[0]]);
        }

        $user= User::create([
            'name' => $request->name,
            'role_id' => 2,
            'email' => $request->email,
            'status' => 'approved',
            'password' => Hash::make($request->password),
        ]);
        
        if($request->token_fcm){
            $user->token_fcm = $request->token_fcm;
            $user->save();
        }
        
        if($request->language){
            $user->language = $request->language;
            $user->save();
        }

        $token= $user->createToken('token')->plainTextToken;
        $token=explode('|', $token);

        $info['token']                  =$token[1];
        $info['login_id']               =$token[0];
        $info['id']                     =$user->id;
        $info['name']                   =$user->name;
        $info['avatar']                 =asset($user->avatar);
        $info['email']                  =$user->email;
        $info['status']                 =$user->status;
        $info['email_verified_at']      =$user->email_verified_at;
        $info['provider_facebook_id']   =$user->provider_facebook_id;
        $info['provider_google_id']     =$user->provider_google_id;
        $info['is_verify']              =$user->is_verify;
        $info['phone']                  =$user->phone;
        
        $tax = Options::where('key', 'tax')->first();
        $info['tax']                    =$tax->value;

        return response()->json($info);

        // return response()->json(['token'=>$token[1],'login_id'=>$token[0]]);
    }


    //user logut
    public function Logout(Request $request)
    {
        $user = $request->user();
        $user->token_fcm = null;
        $user->save();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        return response()->json('logout success');
    }


    //user basic info
    public function Info(Request $request)
    {
        $data                           = $request->user();
        $info['id']                     =$data->id;
        $info['name']                   =$data->name;
        $info['avatar']                 =asset($data->avatar);
        $info['email']                  =$data->email;
        $info['status']                 =$data->status;
        $info['email_verified_at']      =$data->email_verified_at;
        $info['provider_facebook_id']   =$data->provider_facebook_id;
        $info['provider_google_id']     =$data->provider_google_id;
        $info['is_verify']              =$data->is_verify;
        $info['phone']                  =$data->phone;
        
        $tax = Options::where('key', 'tax')->first();
        $info['tax']                    =$tax->value;
        
        return response()->json($info);
    }

    public function notificationList(Request $request)
    {
        $list = Notification::where('user_id', Auth::user()->id)->get();
        // return response()->json($list);
        
        		return response()->json([
			'data'		=> $list
		]);
    }

    
}
