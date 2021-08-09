<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Menu;
use App\User;

use App\Notifications\SendPushNotification;
use Kutia\Larafirebase\Facades\Larafirebase;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
    }
    public function fcm(){
        return view('firebase');
    }

    public function saveToken(Request $request)
    {
        auth()->user()->update(['device_token'=>$request->token]);
        return response()->json(['token saved successfully.']);
    }

    public function notification(Request $request){
        $usersTokenArrEN=['fC5zbELmDbC7h8HU7B_9BM:APA91bEC7c3--v4wW5Dr7ey1RKnW1Lslr0TN7HaUpaQdNQ3-nvauYLorHElNxjrbrPUj0W-DEs-1G8XveN7Guvpb64aAKHe0Dq3IlaMAk6PJ_QvpfnR0QyyzsAndyyBVlnuDARJgP0Z_'];
            sendFCM('اسم المطعم', 'name', 'fgfgfgf', 'title', $usersTokenArrEN, 'fgfgf', 'gfg', 3, 'en');

        $request->validate([
            'title'=>'required',
            'body'=>'required'
        ]);

        // dd($request->all());
        try{
            $fcmTokens = User::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();

            // dd($fcmTokens);
            //Notification::send(null,new SendPushNotification($request->title,$request->message,$fcmTokens));

            /* or */

            //auth()->user()->notify(new SendPushNotification($title,$message,$fcmTokens));

            /* or */

            Larafirebase::withTitle($request->title)
                ->withBody($request->body)
                ->sendMessage($fcmTokens);

            return redirect()->back()->with('success','Notification Sent Successfully!!');

        }catch(\Exception $e){
            report($e);
            return redirect()->back()->with('error','Something goes wrong while sending notification.');
        }
    }

    public function sendNotification(Request $request)
    {

        $firebaseToken = User::whereNotNull('fcm_token')->pluck('fcm_token')->all();

        $SERVER_API_KEY = 'AAAAjASMazw:APA91bHdknevHPgIjirich-mMwYJx1bJKt6RHkXGEErnF5Cml1tgznJkov-VZZLFgth_GcARa7NAJN3wp32r4AVdry6tj9SWr-nlxu41rgfzdjxYCpWOojU6y8w97I9lVryKOuzxznoD';

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        dd($response);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      $data = Menu::first();
      $menus=json_decode($data->link);
      return $menus;
      return view('welcome',compact('data'));

    }

    public function updateToken(Request $request){
        try{
            $request->user()->update(['fcm_token'=>$request->token]);
            return response()->json([
                'success'=>true
            ]);
        }catch(\Exception $e){
            report($e);
            return response()->json([
                'success'=>false
            ],500);
        }
    }

    public function home()
    {
        // $usersTokenArrEN=['fC5zbELmDbC7h8HU7B_9BM:APA91bEC7c3--v4wW5Dr7ey1RKnW1Lslr0TN7HaUpaQdNQ3-nvauYLorHElNxjrbrPUj0W-DEs-1G8XveN7Guvpb64aAKHe0Dq3IlaMAk6PJ_QvpfnR0QyyzsAndyyBVlnuDARJgP0Z_'];
        $usersTokenArrEN = User::whereNotNull('token_fcm')->pluck('token_fcm')->all();

        sendFCM('Title English', 'العنوان بالعربي', 'Message EN', 'الرساله بالعربي', $usersTokenArrEN, 'fgfgf', 'gfg', 3, 'ar');

        return view('home');
    }




}

