<?php

namespace Amcoders\Theme\khana\http\controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Order;
use Carbon\Carbon;
use App\Location;
use App\User;
use App\Terms;
use App\Orderlog;
use App\Riderlog;
use OneSignal;
use Amcoders\Plugin\Plugin;
use App\Notification;
use App\Reservation;
use App\Reservationlog;
use Validator,Response;
/**
 *
 */
class ReservationController extends controller
{
	public function index(Request $request)
	{
		if (!Auth()->user()->can('reservation.list')) {
			return abort(401);
		}
		if (!empty($request->src)) {
			$src=$request->src;
			// $orders = Order::where('id',$request->src)->paginate(20);
			$orders = Reservation::with('customerinfo')->where('id',$request->src)->paginate(20);
			return view('theme::admin.reservation.index',compact('orders','src'));

		}
		// $orders = Order::latest()->paginate(20);
		$orders = Reservation::with('customerinfo')->latest()->paginate(20);

		return view('theme::admin.reservation.index',compact('orders'));
	}

	public function details($id)
	{
		if (!Auth()->user()->can('reservation.control')) {
			return abort(401);
		}

		$info = Reservation::with('vendorinfo','reservationlog')->find($id);
		if (empty($info)) {
			abort(404);
		}

		return view('theme::admin.reservation.details',compact('info'));
	}

	public function date_filter(Request $request)
	{
		if (!Auth()->user()->can('reservation.control')) {
			return abort(401);
		}

		$date = $request->date;
		$starting_date = substr($date, 0,10);
		$start_date = date( "Y-m-d h:i:s", strtotime($starting_date));
		$ending_date = substr($date, -10);
		$end_date = date( "Y-m-d h:i:s", strtotime($ending_date));
		$cities = Terms::where('type',2)->get();
		if($request->type)
		{
			$type = $request->type;

			if($request->type == 'pending')
			{
				$status = 2;
			}elseif($request->type == 'accepted')
			{
				$status = 3;
			}elseif($request->type == 'complete')
			{
				$status = 1;
			}else
			{
				$status = 0;
			}
			$orders = Reservation::where('status',$status)->whereBetween('created_at', [$start_date, $end_date])
			->paginate(20);
			return view('theme::admin.reservation.index',compact('cities','orders','type'));
		}else{
			$orders = Reservation::whereBetween('created_at', [$start_date, $end_date])
			->paginate(20);
			return view('theme::admin.reservation.index',compact('cities','orders'));
		}
	}

	public function decline($id)
	{
		$order = Reservation::find($id);
		$order->rider_id = null;
		$order->save();

		return back();
	}

	public function delete($id)
	{
		Reservation::find($id)->delete();

		return back();
	}

	public function city(Request $request)
	{
		return $request->all();
	}

	public function type($type)
	{
		if (!Auth()->user()->can('reservation.control')) {
			return abort(401);
		}
		if($type == 'pending')
		{
			$status = 2;
		}elseif($type == 'accepted')
		{
			$status = 3;
		}elseif($type == 'complete')
		{
			$status = 1;
		}else
		{
			$status = 0;
		}

		$orders = Reservation::where('status',$status)->paginate(20);
		$cities = Terms::where('type',2)->get();
		return view('theme::admin.reservation.index',compact('orders','cities','type'));
	}

  public function update(Request $request, $id)
  {
    $validatedData = $request->validate([
      'status' => 'required',
    ]);


    $order=Reservation::find($id);
    $auth_id=$order->vendor_id;

    if (empty($order)) {
      abort(401);
    }

    $order->status=$request->status;
    $order->save();

    $log = new Reservationlog;
    $log->reservation_id = $order->id;
    $log->status = $request->status;
    $log->save();

      if ($request->status == 1) {
       $sum = Reservation::where('vendor_id',$auth_id)->where('status',1)->sum('total');
       $sellerbadges=Terms::where('type',3)->where('status',1)->where('slug', '>=', $sum)->orderBy('slug','ASC')->first();
       	if (!empty($sellerbadges)) {
         	$seller = User::find($auth_id);
         	$seller->badge_id = $sellerbadges->id;
         	$seller->save();
     		}

       $commsion=User::with('usersaas')->find($auth_id);


       $or=Reservation::where('vendor_id',$auth_id)->find($id);
       if ($commsion->usersaas->commission != 0) {

         $com1=$commsion->usersaas->commission/100;
         $net_commision=$com1*$or->total;
         $or->commission=$net_commision;

       }
       else{
         $or->commission = 0;
       }
       $or->save();

     	}
         $users = User::where('id', $order->user_id)->get();

        $getRestaurantName = User::where('id', $order->vendor_id)->first();

        // $users_english = User::where('role_id', 1)->where('language', 'en')->get();
        // $users_arabic = User::where('role_id', 1)->where('language', 'ar')->get();

        $users_english = User::where('id', $order->user_id)->where('language', 'en')->get();
        $users_arabic = User::where('id', $order->user_id)->where('language', 'ar')->get();

        foreach($users_english as $user){
        $usersTokenArrEN[]=$user->token_fcm;
        }

        foreach($users_arabic as $user){
        $usersTokenArrAR[]=$user->token_fcm;
        }


        if ($request->status == 3) {

            $message_en='Reservation Processed and Reservation number is #'.$order->id;
            $message_ar='تم قبول الحجز ورقم الحجز هو #'.$order->id ;
            // dd($users);
            foreach ($users as $user) {
                // dd($user->id);
                $notification = Notification::create([
                    'title_en'      => $getRestaurantName->name,
                    'title_ar'      => $getRestaurantName->name,
                    'content_en'    => $message_en,
                    'content_ar'    => $message_ar,
                    'image'         => '',
                    'user_id'       => $user->id,
                    'restaurant_id' => $order->vendor_id,
                    'product_id'    => null,
                    'type'          => 1,
                    'type_id'       => auth()->user()->id
                ]);
            }

            if(isset($usersTokenArrEN)){
            sendFCM($getRestaurantName->name, $getRestaurantName->name, $message_en, $message_ar, $usersTokenArrEN, $request->preview, $order->vendor_id, $request->product_id, 'en');
            }

            if(isset($usersTokenArrAR)){
            sendFCM($getRestaurantName->name, $getRestaurantName->name, $message_en, $message_ar, $usersTokenArrAR, $request->preview, $order->vendor_id, $request->product_id, 'ar');
            }

            return response()->json(['Reservation Processed']);
          }
          elseif($request->status == 1){

            $message_en ='Reservation Completed #'.$order->id;
            $message_ar ='تم اكتمال الحجز #'.$order->id;
            // dd($users);
            foreach ($users as $user) {
                // dd($user->id);
                $notification = Notification::create([
                    'title_en'      => $getRestaurantName->name,
                    'title_ar'      => $getRestaurantName->name,
                    'content_en'    => $message_en,
                    'content_ar'    => $message_ar,
                    'image'         => '',
                    'user_id'       => $user->id,
                    'restaurant_id' => $order->vendor_id,
                    'product_id'    => null,
                    'type'          => 1,
                    'type_id'       => auth()->user()->id
                ]);
            }

            if(isset($usersTokenArrEN)){
            sendFCM($getRestaurantName->name, $getRestaurantName->name, $message_en, $message_ar, $usersTokenArrEN, $request->preview, $order->vendor_id, $request->product_id, 'en');
            }

            if(isset($usersTokenArrAR)){
            sendFCM($getRestaurantName->name, $getRestaurantName->name, $message_en, $message_ar, $usersTokenArrAR, $request->preview, $order->vendor_id, $request->product_id, 'ar');
            }

            return response()->json(['Reservation Completed']);
          }
          else{
            $message_en ='Reservation Cancelled #'.$order->id;
            $message_ar ='تم الغاء الحجز رقم #'.$order->id;
            // dd($users);
            foreach ($users as $user) {
                // dd($user->id);
                $notification = Notification::create([
                    'title_en'      => $getRestaurantName->name,
                    'title_ar'      => $getRestaurantName->name,
                    'content_en'    => $message_en,
                    'content_ar'    => $message_ar,
                    'image'         => '',
                    'user_id'       => $user->id,
                    'restaurant_id' => $order->vendor_id,
                    'product_id'    => null,
                    'type'          => 1,
                    'type_id'       => auth()->user()->id
                ]);
            }

            if(isset($usersTokenArrEN)){
            sendFCM($getRestaurantName->name, $getRestaurantName->name, $message_en, $message_ar, $usersTokenArrEN, $request->preview, $order->vendor_id, $request->product_id, 'en');
            }

            if(isset($usersTokenArrAR)){
            sendFCM($getRestaurantName->name, $getRestaurantName->name, $message_en, $message_ar, $usersTokenArrAR, $request->preview, $order->vendor_id, $request->product_id, 'ar');
            }


            return response()->json(['Reservation Cancelled']);
          }

  }

  public function create()
  {
  	$restaurants=User::where('role_id',3)->where('is_reserve_open', 'approved')->get();
  	$users=User::where('role_id',2)->get();
    return view('theme::admin.reservation.create',compact('restaurants', 'users'));
  }

  public function store(Request $request)
  {
    $validatedData = $request->validate([
        'vendor_id'     => 'required|integer',
        'date'          => 'required|date_format:Y-m-d',
        'time'          => 'required|date_format:H:i',
        'person'        => 'required|integer|min:1',
        'message'       => 'nullable|string'
    ]);

    $reservation 								= new Reservation();
    $reservation->user_id       = $request->user_id ? $request->user_id : Auth::User()->id;
    $reservation->vendor_id     = $request->vendor_id;
    $reservation->status        = 2;//$request->status;
    $reservation->date          = $request->date;
    $reservation->time          = $request->time;
    $reservation->person        = $request->person;
    $reservation->message       = $request->message;
    $reservation->save();

    // return $reservation;

    return response()->json(['Reservation Created']);

  }
}
