<?php

namespace Amcoders\Plugin\shop\http\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Media;
use App\ReservationImage;
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
class ReserveImageController extends Controller
{
    protected $filename;
    protected $ext;
    protected $fullname;

    public function __construct()
    {
       $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sliders = ReservationImage::latest()->paginate(30);  
        return view('plugin::reserve_image.index',compact('sliders'));
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
        // $restaurants=User::where('role_id',3)->get();
        return view('plugin::reserve_image.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'preview' => 'required',
        ]);
        $user_id=Auth::id();
        $post=new ReservationImage;
        $post->image=$request->preview;
        $post->restaurant_id=$user_id;
        $post->save();

        return response()->json(['Reservation Image Created']);
    }

    public function edit($id)
    {
        $info=ReservationImage::find($id);
        // $restaurants=User::where('role_id',3)->get();
        // $products=Terms::where('auth_id', $info->restaurant_id)->get();
        return view('plugin::reserve_image.edit',compact('info'));
    }

    public function update(Request $request, $id)
    {
       $validatedData = $request->validate([
            'preview' => 'required',
        ]);
        
        $user_id=Auth::id();
        $post= ReservationImage::find($id);
        $post->image=$request->preview;
        $post->restaurant_id=$user_id;
        $post->save();

        return response()->json(['Reservation Image Updated']);
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
               ReservationImage::destroy($id);
            }
        }
        return response()->json('Success');
    }
   
 }   
