<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Auth;


class SettingsController extends Controller
{
	public function update(Request $request)
	{
	   // echo "<pre>";
    //     print_r($request->all());
    //     die;
	   //// dd($request->all());
	   // return response()->json($request->all());
		$user = Auth::User();
		$validator = \Validator::make($request->all(), [
            'name'              => 'nullable',
            'email'             => 'unique:users,email,' . $user->id,
            'image'             => 'nullable|image',
            'phone'             => 'nullable'
        ]);

        if($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()->all()[0]], 400);
        }

        if ($request->has('image')) {
            $imageName = date('dmy').time().'.'.request()->image->getClientOriginalExtension();
            request()->image->move('uploads/',$imageName);
            $avatar= url('/') . '/uploads/'.$imageName;

            if (file_exists($user->avatar)) {
                unlink($user->avatar);
            }
        }
        else{
            $avatar=$user->avatar;
        }
        
        if ($request->name) {
            $user->name     = $request->name;
        }
        
        if ($request->email) {
            $user->email     = $request->email;
        }
        
        if ($request->phone) {
            $user->phone     = $request->phone;
        }

        $user->avatar   = $avatar;
        $user->save();

        if($request->current_password)
        {
        	$validator = \Validator::make($request->all(), [
	            'current_password' => 'required|password',
            	'password' => 'required|confirmed'
	        ]);

	        if($validator->fails())
	        {
	            return response()->json(['error'=>$validator->errors()->all()[0]], 400);
	        }

	        $user->password = Hash::make($request->password);
	        $user->save();

	        // return $user;
	       
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
            return response()->json($info);
            
        }
        
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
        return response()->json($info);
        
        // return $user;
	}
}
