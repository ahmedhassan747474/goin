<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Auth;
// use Cart;
use Session;
use App\Order;
use App\Orderlog;
use App\Ordermeta;
use App\OrderMetaAddon;
use App\Cart;
use App\CartItem;
use App\CartItemAddons;
use App\Notification;
use App\Options;

class OrderController extends Controller
{
	public function index()
	{
		$orders = Order::where('user_id',Auth::User()->id)
            ->with('orderlist','vendorinfo','riderinfo','coupon','orderlog','riderlog','liveorder')
            ->orderBy('id','DESC')
            ->get();
// 		return $orders;

        return	response()->json([
			'data'		=> $orders
		]);
	}

	public function details($id)
	{
		$info=Order::where('user_id',Auth::id())
			->with('orderlist','vendorinfo','riderinfo','coupon','orderlog','riderlog','liveorder')
			->find($id);
		$tax = Options::where('key', 'tax')->first();
		$info->tax = $tax->value;
		return $info;
	}

	public function store(Request $request)
	{
	   // dd($request->restaurant_id);
		$validator = validator()->make($request->all(),[
            'restaurant_id'		=> 'required',
            'order_type' 		=> 'required',
            'payment_method'	=> 'required',
            'payment_status'	=> 'required',
            'coupon_id'			=> 'nullable',
            'total'				=> 'required',
            'shipping'			=> 'nullable',
            'commission'		=> 'nullable',
            'discount'			=> 'nullable',
            'status'			=> 'required',
            'name'				=> 'required',
            'phone'				=> 'required',
            'address'			=> 'required',
            'latitude'			=> 'required',
            'longitude'			=> 'required',
            'note'				=> 'nullable',
        ]);

        if($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->first()]);
        }

		$order = new Order();
		$order->user_id 		= Auth::User()->id;
		$order->vendor_id 		= $request->restaurant_id;
		$order->seen 			= 0;
		$order->order_type 		= $request->order_type;
		$order->payment_method 	= $request->payment_method;
		$order->payment_status 	= $request->payment_status;

		if($request->coupon_id)
		{
			$order->coupon_id 	= $request->coupon_id;
		}

		$order->total 			= $request->total;
		$order->shipping 		= $request->shipping;
		$order->commission 		= $request->commission;
		$order->discount 		= $request->discount;
		$order->status 			= $request->status;

		$data['name']			=$request->name;
        $data['phone']			=$request->phone;
        $data['address']		=$request->address;
        $data['latitude']		=$request->latitude;
        $data['longitude']		=$request->longitude;
        $data['note']			=$request->order_note;

        $order->data 			= json_encode($data);
        $order->save();

        $getCart = Cart::where('user_id', '=', Auth::User()->id)
        	->where('restaurant_id', '=', $request->restaurant_id)
        	->with('items')
        	->first();

        if ($getCart) {
        	$cart_item = CartItem::where('cart_id', $getCart->id)->get();
        	foreach($cart_item as $value) {
	         	$ordermeta 				= new Ordermeta;
	            $ordermeta->order_id 	= $order->id;
	            $ordermeta->qty 		= $value->quantity;
	            $ordermeta->total  		= $value->price;
	            $ordermeta->term_id  	= $value->product_id;
	            $ordermeta->size_id  	= $value->size_id;
	            $ordermeta->save();

	            $cart_addon = CartItemAddons::where('cart_item_id', $value->id)->get();
	            foreach($cart_addon as $addon){
	                $ordermetaaddon                 = new OrderMetaAddon;
	                $ordermetaaddon->order_meta_id  = $ordermeta->id;
	                $ordermetaaddon->addons_id      = $addon->addons_id;
	                $ordermetaaddon->save();
	            }
	        }
        }

     	// foreach($request->datacart as $key => $value) {
      //    	$ordermeta 				= new Ordermeta;
      //       $ordermeta->order_id 	= $order->id;
      //       $ordermeta->qty 		= $value['quantity'];
      //       $ordermeta->total  		= $value['price'];
      //       $ordermeta->term_id  	= $value['food']['id'];
      //       $ordermeta->save();
      //   }



        $users = User::where('role_id', 1)
                    ->orWhere('id', $request->restaurant_id)->get();

        $getRestaurantName = User::where('id', $request->restaurant_id)->first();


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
                'restaurant_id' => $request->restaurant_id,
                'product_id'    => null,
                'type'          => 1,
                'type_id'       => auth()->user()->id
            ]);
        }

        if(isset($usersTokenArrEN)){
            sendFCM($getRestaurantName->name, $getRestaurantName->name, $request->message_en, $request->message_ar, $usersTokenArrEN, $request->preview, $request->restaurant_id, $request->product_id, 'en');
        }

        if(isset($usersTokenArrAR)){
            sendFCM($getRestaurantName->name, $getRestaurantName->name, $request->message_en, $request->message_ar, $usersTokenArrAR, $request->preview, $request->restaurant_id, $request->product_id, 'ar');
        }


        // return $order;
        $deleteCart = Cart::where('user_id', '=', Auth::User()->id)->where('restaurant_id', '=', $request->restaurant_id)->delete();
        return response()->json($order);
	}

	public function myCart(Request $request)
	{
        // $validator = validator()->make($request->all(),[
        //     'restaurant_id' => 'required',
        // ]);

        // if($validator->fails())
        // {
        //     return response()->json(['errors'=>$validator->errors()->first()]);
        // }

        $getCart = Cart::where('user_id', '=', Auth::User()->id)
        	// ->where('restaurant_id', '=', $request->restaurant_id)
        	->with('items.product.preview', 'items.size', 'items.addons.addon')
        	->first();

        return response()->json($getCart);
//         return	response()->json([
// 			'data'		=> $getCart
// 		]);
    }

    public function addToCart(Request $request)
    {
        $validator = validator()->make($request->all(),[
            'restaurant_id'		=> 'required',
            'product_id' 		=> 'required',
            'size_id'			=> 'required',
            'quantity' 			=> 'required',
            'price' 			=> 'required',
            'special_request' 	=> 'nullable',
            'addons'            => 'array|nullable'
        ]);

        if($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->first()]);
        }

        $checkCart = Cart::where('user_id', '=', Auth::User()->id)->where('restaurant_id', '=', $request->restaurant_id)->get();

        if($checkCart->count() > 0) {
            $cart = $checkCart[0];
        } else {
        	$cart = Cart::create([
                'user_id' 		=> Auth::User()->id,
                'restaurant_id' => $request->restaurant_id,
                'created_at' 	=> get_local_time($request->getClientIp())  //get_local_time('102.40.192.233')
            ]);
        }

        if ($request->product_id) {
        	$cart_item = CartItem::create([
                'product_id'		=> $request->product_id,
                'size_id' 			=> $request->size_id,
                'cart_id' 			=> $cart->id,
                'quantity' 			=> $request->quantity,
                'price' 			=> $request->price,
                'special_request' 	=> $request->special_request,
            ]);

            foreach ($request->addons as $addon) {
                $cart_item_addons = CartItemAddons::create([
                    'cart_item_id'      => $cart_item->id,
                    'addons_id'         => $addon
                ]);
            }

            $getCart = Cart::where('id', $cart->id)->with('items.product.preview', 'items.size', 'items.addons.addon')->first();

            return response()->json($getCart);
        }
    }

    public function editCart(Request $request)
    {
    	$validator = validator()->make($request->all(),[
    		'cart_item_id'		=> 'required',
            'restaurant_id'		=> 'required',
            'product_id' 		=> 'required',
            'size_id'			=> 'required',
            'quantity' 			=> 'required',
            'price' 			=> 'required',
            'special_request' 	=> 'nullable',
        ]);

        if($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->first()]);
        }

        $cart = Cart::where('user_id', '=', Auth::User()->id)
        	->where('restaurant_id', '=', $request->restaurant_id)
        	->with('items')
        	->first();

        if ($cart) {
        	$cart_item = CartItem::where('cart_id', $cart->id)->where('id', $request->cart_item_id)
        	->update([
	            'product_id'		=> $request->product_id,
	            'size_id' 			=> $request->size_id,
	            'cart_id' 			=> $cart->id,
	            'quantity' 			=> $request->quantity,
	            'price' 			=> $request->price,
	            'special_request' 	=> $request->special_request,
	        ]);

            // $checkAddons = CartItemAddons::where('cart_item_id', $request->cart_item_id)->count();
            // if($checkAddons > 0){
                $deleteAddons = CartItemAddons::where('cart_item_id', $request->cart_item_id)->delete();
                foreach ($request->addons as $addon) {
                    $cart_item_addons = CartItemAddons::create([
                        'cart_item_id'      => $cart_item->id,
                        'addons_id'         => $addon
                    ]);
                }
            // }
        }

        $getCart = Cart::where('id', $cart->id)->with('items.product.preview', 'items.size', 'items.addons.addon')->first();

        return response()->json($getCart);
    }

    public function deleteCart(Request $request)
    {
        $validator = validator()->make($request->all(),[
        	'cart_item_id'		=> 'required',
            'restaurant_id'		=> 'required'
        ]);

        if($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->first()]);
        }

        $cart = Cart::where('user_id', '=', Auth::User()->id)
        	->where('restaurant_id', '=', $request->restaurant_id)
        	->with('items')
        	->first();

        if ($cart) {
        	$cart_item = CartItem::where('cart_id', $cart->id)->where('id', $request->cart_item_id)->delete();
        }

        $getCart = Cart::where('id', $cart->id)->with('items.product.preview', 'items.size', 'items.addons.addon')->first();

        return response()->json($getCart);
    }

    public function cancelOrder(Request $request)
    {
    	$validator = validator()->make($request->all(),[
            'order_id'	=> 'required'
        ]);

        if($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->first()]);
        }

        $order = Order::where('user_id',Auth::id())->find($request->order_id);

        if ($order->status != 2) {
            return response()->json(['error' => 'You Can\'t Cancel Order'], 400);
        }

        $order->status = 0;
      	$order->save();
        //   dd($order);
      	$log = new Orderlog;
      	$log->order_id = $order->id;
      	$log->status = 0;
      	$log->save();


        $users = User::where('role_id', 1)
                    ->orWhere('id', $order->vendor_id)->get();

        $getRestaurantName = User::where('id', $order->vendor_id)->first();


        $users_english = User::where('role_id', 1)->where('language', 'en')->get();
        $users_arabic = User::where('role_id', 1)->where('language', 'ar')->get();

        foreach($users_english as $user){
            $usersTokenArrEN[]=$user->token_fcm;
        }

        foreach($users_arabic as $user){
            $usersTokenArrAR[]=$user->token_fcm;
        }

        foreach ($users as $user) {
            // dd($user->id);
            $notification = Notification::create([
                'title_en'      => $getRestaurantName->name,
                'title_ar'      => $getRestaurantName->name,
                'content_en'    => $request->message_en,
                'content_ar'    => $request->message_ar,
                'image'         => '',
                'user_id'       => $user->id,
                'restaurant_id' => $order->vendor_id,
                'product_id'    => null,
                'type'          => 1,
                'type_id'       => auth()->user()->id
            ]);
        }

        if(isset($usersTokenArrEN)){
            sendFCM($getRestaurantName->name, $getRestaurantName->name, $request->message_en, $request->message_ar, $usersTokenArrEN, $request->preview, $order->vendor_id, $request->product_id, 'en');
        }

        if(isset($usersTokenArrAR)){
            sendFCM($getRestaurantName->name, $getRestaurantName->name, $request->message_en, $request->message_ar, $usersTokenArrAR, $request->preview, $order->vendor_id, $request->product_id, 'ar');
        }

      	return response()->json($order);
    }

}

