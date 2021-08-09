<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\BoookMail;
use App\Notification;
use App\Reservation;
use App\Reservationlog;
use App\Table;

class TableController extends Controller
{
	public function store(Request $request)
	{
		$validator = \Validator::make($request->all(), [
            'number_of_gutes' => 'required',
            'date' => 'required',
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()[0]], 400);
        }

        $data = [
        	'number_of_gutes' => $request->number_of_gutes,
        	'date' => $request->date,
        	'name' => $request->name,
        	'email' => $request->email,
        	'mobile' => $request->mobile,
        	'message' => $request->message
        ];

		$user = User::find($request->vendor_id);
		Mail::to($user->email)->send(new BoookMail($user,$data));
		return response()->json('ok');
	}

    public function storeDB(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'table_id'      => 'required|integer',
            'vendor_id'     => 'required|integer',
            'date'          => 'required|date_format:Y-m-d',
            'time'          => 'required|date_format:H:i:s',
            'person'        => 'required|integer|min:1',
            'message'       => 'nullable|string'
        ]);

        if($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()[0]], 400);
        }

        $reservation = new Reservation();
        $reservation->user_id       = Auth::User()->id;
        $reservation->vendor_id     = $request->vendor_id;
        $reservation->table_id      = $request->table_id;
        $reservation->status        = 2;//$request->status;
        $reservation->date          = $request->date;
        $reservation->time          = $request->time;
        $reservation->person        = $request->person;
        $reservation->message       = $request->message;
        $reservation->save();

        $users = User::where('role_id', 1)
                    ->orWhere('id', $request->vendor_id)->get();

        $getRestaurantName = User::where('id', $request->vendor_id)->first();


        $users_english = User::where('role_id', 1)->where('language', 'en')->get();
        $users_arabic = User::where('role_id', 1)->where('language', 'ar')->get();

        foreach($users_english as $user){
            $usersTokenArrEN[]=$user->token_fcm;
        }

        foreach($users_arabic as $user){
            $usersTokenArrAR[]=$user->token_fcm;
        }

// dd($users);
        foreach ($users as $user) {
            // dd($user->id);
            $notification = Notification::create([
                'title_en'      => $getRestaurantName->name,
                'title_ar'      => $getRestaurantName->name,
                'content_en'    => $request->message_en,
                'content_ar'    => $request->message_ar,
                'image'         => '',
                'user_id'       => $user->id,
                'restaurant_id' => $request->vendor_id,
                'product_id'    => null,
                'type'          => 1,
                'type_id'       => auth()->user()->id
            ]);
        }

        if(isset($usersTokenArrEN)){
            sendFCM($getRestaurantName->name, $getRestaurantName->name, $request->message_en, $request->message_ar, $usersTokenArrEN, $request->preview, $request->vendor_id, $request->product_id, 'en');
        }

        if(isset($usersTokenArrAR)){
            sendFCM($getRestaurantName->name, $getRestaurantName->name, $request->message_en, $request->message_ar, $usersTokenArrAR, $request->preview, $request->vendor_id, $request->product_id, 'ar');
        }


        return $reservation;

    }

    public function cancelReservation(Request $request)
    {
        $validator = validator()->make($request->all(),[
            'reservation_id'  => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->first()]);
        }

        $reservation = Reservation::where('user_id',Auth::id())->find($request->reservation_id);

        if ($reservation->status != 2) {
            return response()->json(['error' => 'You Can\'t Cancel Reservation'], 400);
        }

        $reservation->status = 0;
        $reservation->save();

        $log = new Reservationlog;
        $log->reservation_id = $reservation->id;
        $log->status = 0;
        $log->save();

        //notifications
        $users = User::where('role_id', 1)
                    ->orWhere('id', $reservation->vendor_id)->get();

        $getRestaurantName = User::where('id', $reservation->vendor_id)->first();


        $users_english = User::where('role_id', 1)->where('language', 'en')->get();
        $users_arabic = User::where('role_id', 1)->where('language', 'ar')->get();

        foreach($users_english as $user){
            $usersTokenArrEN[]=$user->token_fcm;
        }

        foreach($users_arabic as $user){
            $usersTokenArrAR[]=$user->token_fcm;
        }

// dd($users);
        foreach ($users as $user) {
            // dd($user->id);
            $notification = Notification::create([
                'title_en'      => $getRestaurantName->name,
                'title_ar'      => $getRestaurantName->name,
                'content_en'    => $request->message_en,
                'content_ar'    => $request->message_ar,
                'image'         => '',
                'user_id'       => $user->id,
                'restaurant_id' => $reservation->vendor_id,
                'product_id'    => null,
                'type'          => 1,
                'type_id'       => auth()->user()->id
            ]);
        }

        if(isset($usersTokenArrEN)){
            sendFCM($getRestaurantName->name, $getRestaurantName->name, $request->message_en, $request->message_ar, $usersTokenArrEN, $request->preview, $reservation->vendor_id, $request->product_id, 'en');
        }

        if(isset($usersTokenArrAR)){
            sendFCM($getRestaurantName->name, $getRestaurantName->name, $request->message_en, $request->message_ar, $usersTokenArrAR, $request->preview, $reservation->vendor_id, $request->product_id, 'ar');
        }


        return response()->json($reservation);
    }

    public function details($id)
    {
        $info = Reservation::where('user_id',Auth::id())->with('vendorinfo', 'restaurantinfo', 'reservationlog')->find($id);
        return $info;
    }

    public function get_list_of_reservation()
	{
		$reservations = Reservation::where('user_id',Auth::User()->id)
            ->with('vendorinfo', 'restaurantinfo', 'reservationlog')
            ->orderBy('id','DESC')
            ->get();

        return response()->json([
			'data'		=> $reservations
		]);
	}
}
