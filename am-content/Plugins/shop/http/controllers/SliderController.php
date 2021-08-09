<?php

namespace Amcoders\Plugin\shop\http\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Media;
use App\Slider;
use File;
use Image;
use Illuminate\Support\Facades\Storage;
use App\Options;
use Illuminate\Support\Str;
use Validator,Response;
use ArtisansWeb\Optimizer;
use Auth;
use App\User;
use App\Userplan;
use App\Terms;
use App\Notification;
class SliderController extends Controller
{

    protected $filename;
    protected $ext;
    protected $fullname;

    public function __construct()
    {
       $this->middleware('auth');;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      if (Auth::User()->role->id == 1) {
        
        if (!Auth()->user()->can('slider.list')) {
            return abort(401);
        }

        $sliders = Slider::latest()->paginate(30);  

        return view('plugin::admin.slider.index',compact('sliders'));
      }
        
    }

    public function json(Request $request){

        $auth_id=Auth::User()->role->id;


        if ($auth_id==1) {
            if (!empty($request->id)) {
                $row=Media::where('id', '<', $request->id)->select('id','name','url')->latest()->limit(12)->get();
                return response()->json($row);
            }
            else{
                $row=Media::latest()->select('id','name','url')->limit(12)->get();
                return response()->json($row);
            }
        }
        elseif($auth_id==3){
            $user_id=Auth::id();
             if (!empty($request->id)) {
                $row=Media::where('id', '<', $request->id)->where('user_id',$user_id)->select('id','name','url')->latest()->limit(12)->get();
                return response()->json($row);
            }
            else{
                $row=Media::where('user_id',$user_id)->latest()->select('id','name','url')->limit(12)->get();
                return response()->json($row);
            }
        }
    }

    public function create(Request $request)
    {
        $restaurants=User::where('role_id',3)->get();
        return view('plugin::admin.slider.create',compact('restaurants'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title_en' => 'required|max:100',
            'title_ar' => 'required|max:100',
            'preview' => 'required',
            'restaurant_id' => 'required' 
        ]);

        $post=new Slider;
        $post->title_en=$request->title_en;
        $post->title_ar=$request->title_ar;
        $post->image=$request->preview;
        $post->restaurant_id=$request->restaurant_id;
        $post->product_id=$request->product_id;
        $post->save();

        $users = User::where('role_id', 2)->get();
        // dd($users);

        $getRestaurantName = User::where('id', $request->restaurant_id)->first();

        foreach ($users as $user) {
            $notification = Notification::create([
                'title_en'      => $getRestaurantName->name,
                'title_ar'      => $getRestaurantName->name,
                'content_en'    => $request->title_en,
                'content_ar'    => $request->title_ar,
                'image'         => $request->preview,
                'user_id'       => $user->id,
                'restaurant_id' => $request->restaurant_id,
                'product_id'    => $request->product_id,
                'type'          => 1,
                'type_id'       => auth()->user()->id
            ]);
        }
        
        $users_english = User::where('role_id', 2)->where('language', 'en')->get();
        $users_arabic = User::where('role_id', 2)->where('language', 'ar')->get();
        
        foreach($users_english as $user){
            $usersTokenArrEN[]=$user->token_fcm;
        }
        
        foreach($users_arabic as $user){
            $usersTokenArrAR[]=$user->token_fcm;
        }
        
        // dd($usersTokenArr);
        if(isset($usersTokenArrEN)){
            sendFCM($getRestaurantName->name, $getRestaurantName->name, $request->title_en, $request->title_ar, $usersTokenArrEN, $request->preview, $request->restaurant_id, $request->product_id, 'en');
        }
        
        if(isset($usersTokenArrAR)){
            sendFCM($getRestaurantName->name, $getRestaurantName->name, $request->title_en, $request->title_ar, $usersTokenArrAR, $request->preview, $request->restaurant_id, $request->product_id, 'ar');
        }
        
        return response()->json(['Slider Created']);
    }

    public function edit($id)
    {
        $info=Slider::find($id);
        $restaurants=User::where('role_id',3)->get();
        $products=Terms::where('auth_id', $info->restaurant_id)->get();
        return view('plugin::admin.slider.edit',compact('info', 'restaurants', 'products'));
    }

    public function update(Request $request, $id)
    {
       $validatedData = $request->validate([
            'title_en' => 'required|max:100',
            'title_ar' => 'required|max:100',
            'preview' => 'required',
            'restaurant_id' => 'required' 
        ]);

        $post= Slider::find($id);
        $post->title_en=$request->title_en;
        $post->title_ar=$request->title_ar;
        $post->image=$request->preview;
        $post->restaurant_id=$request->restaurant_id;
        $post->product_id=$request->product_id;
        $post->save();

        $users = User::where('role_id', 2)->get();

        $getRestaurantName = User::where('id', $request->restaurant_id)->first();

        foreach ($users as $user) {
            $notification = Notification::create([
                'title_en'      => $getRestaurantName->name,
                'title_ar'      => $getRestaurantName->name,
                'content_en'    => $request->title_en,
                'content_ar'    => $request->title_ar,
                'image'         => $request->preview,
                'user_id'       => $user->id,
                'restaurant_id' => $request->restaurant_id,
                'product_id'    => $request->product_id,
                'type'          => 1,
                'type_id'       => auth()->user()->id
            ]);
        }

        sendFCM($getRestaurantName->name, $getRestaurantName->name, $request->title_en, $request->title_ar, null, $request->preview, $request->restaurant_id, $request->product_id);

        return response()->json(['Slider Updated']);
    }

    public function get_products(Request $request)
    {
        $items = Terms::whereAuthId($request->restaurant_id)->get();

        return response()->json($items);
    }

    public function destroy(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $id) {
               Slider::destroy($id);
            }
        }
        return response()->json('Success');
    }
   
 }   
