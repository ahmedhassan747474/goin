<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Media;
use App\TableImage;
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

class TableImageController extends Controller
{
    protected $filename;
    protected $ext;
    protected $fullname;

    public function __construct()
    {
       $this->middleware('auth');
    }

    public function index(Request $request, $id)
    {
        $sliders = TableImage::where('table_id', $id)->latest()->paginate(30);  
        
        $table_id = $id;

        return view('admin.table_image.index',compact('sliders', 'table_id'));
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

    public function create(Request $request, $id)
    {
        $table_id = $id;
        return view('admin.table_image.create', compact('table_id'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'preview'   => 'required',
            'table_id'  => 'required' 
        ]);

        $post=new TableImage;
        $post->image    =$request->preview;
        $post->table_id =$request->table_id;
        $post->save();
        
        return response()->json(['Image Created']);
    }

    public function edit($id)
    {
        $info=TableImage::find($id);
        return view('admin.table_image.edit',compact('info'));
    }

    public function update(Request $request, $id)
    {
       $validatedData = $request->validate([
            'preview'   => 'required'
        ]);

        $post= TableImage::find($id);
        $post->image=$request->preview;
        $post->save();

        return response()->json(['Image Updated']);
    }

    public function destroy(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $id) {
               TableImage::destroy($id);
            }
        }
        return response()->json('Success');
    }
   
 }   
