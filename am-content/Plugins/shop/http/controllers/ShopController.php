<?php 

namespace Amcoders\Plugin\shop\http\controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Auth;
use App\Media;
use App\Terms;
use App\Meta;
use App\PostCategory;
use App\Productmeta;
use App\Addon;
use App\Shopday;
use App\Location;
use App\Usermeta;
use App\Usercategory;
use App\User;
use App\Onesignal;
use App\Options;
use App\Size;
use App\TermSize;
use File;
use Illuminate\Support\Facades\Storage;
use Hash;
/**
 * 
 */
class ShopController extends controller
{
	

    public function media()
    {
        $auth_id=Auth::id();
        $posts=Media::where('user_id',$auth_id)->latest()->paginate(20);
        return view('plugin::media.index',compact('posts'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function MediaDestroy(Request $request)
    {
     if ($request->status == 'delete') {
      
     
      $role_id=Auth::user()->role_id;
      $auth_id=Auth::id();
      if ($role_id == 4 || $role_id == 2) {
        redirect()->back();
      }
        $info=Options::where('key','lp_filesystem')->first();
        $info=json_decode($info->value);

        $imageSizes= Options::where('key','lp_imagesize')->first();
        $imageSizes= json_decode($imageSizes->value);
        if ($request->ids) {

            foreach ($request->ids as $id) {
                $media=Media::where('user_id',$auth_id)->find($id);
                if ($info->system_type=='do') {
                 
                  $check=  Storage::disk('do')->delete($media->path.'/'.$media->name);
                    foreach ($imageSizes as $size) {
                        $imgArr=explode('.', $media->name);
                       
                      $check=  Storage::disk('do')->delete($media->path.'/'.$imgArr[0].$size->key.'.'.$imgArr[1]); 
                     }
                }
                else{
                    $file=$media->name;
                   
                   if (file_exists($file)) {
                  
                     unlink($file);
                     foreach ($imageSizes as $size) {
                        $img=explode('.', $file);
                        if (file_exists($img[0].$size->key.'.'.$img[1])) {
                           unlink($img[0].$size->key.'.'.$img[1]);
                        }
                         
                     }
                 }
                
             }

             Media::destroy($id);
           
               
           }
       }
       }
      
       return response()->json('Delete Success');
    }



    public function subscribe(Request $request)
    {
      $check=Onesignal::where('user_id',Auth::id())->where('player_id',$request->player_id)->first();
      if (empty($check)) {
         $user = new Onesignal;
         $user->user_id= Auth::id();
         $user->player_id = $request->player_id;
         $user->save();
      }
      
    }

	 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $auth_id=Auth::id();

        if($request->src) {
            $posts=Terms::with('preview','price')->withCount('order')
                ->where('type',6)
                ->where('auth_id',$auth_id)
                ->where('title_ar','LIKE','%'.$request->src.'%')
                ->orWhere('title_en','LIKE','%'.$request->src.'%')
                ->latest()->paginate(20);
        }
        elseif($request->st) 
        {
            if ($request->st=='trash') {
                $posts=Terms::with('preview','price')->withCount('order')
                    ->where('type',6)
                    ->where('auth_id',$auth_id)
                    ->where('status',0)
                    ->latest()->paginate(20);
                 $status=$request->st;
                return view('plugin::products.index',compact('posts','auth_id','status'));
            }
            else
            {
                $posts=Terms::with('preview','price')->withCount('order')
                    ->where('type',6)
                    ->where('auth_id',$auth_id)
                    ->where('status',$request->st)
                    ->latest()->paginate(20);
                $status=$request->st;
                return view('plugin::products.index',compact('posts','auth_id','status'));
           }
        }
        else
        {
            $posts=Terms::with('preview','price')->withCount('order')
                ->where('type',6)
                ->where('auth_id',$auth_id)
                ->latest()
                ->where('status','!=',0)
                ->paginate(20);
        }

        $status=1;

        $posts=Terms::with('preview','price')->withCount('order')->where('type',6)->where('auth_id',$auth_id)->latest()->paginate(20);
        // $sizes = Size::where('restaurant_id',$auth_id)->get();
        return view('plugin::products.index',compact('posts', 'auth_id', 'status'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $addons=Terms::where('type',8)->where('auth_id',Auth::id())->select('id','title_en')->get();
        $sizes = Size::where('restaurant_id',Auth::id())->get();
        return view('plugin::products.create',compact('addons', 'sizes'));
    }


    public function day()
    {
        $days=Shopday::where('user_id',Auth::id())->get();
        return view('plugin::day.index',compact('days'));
        
    }

    public function updateday(Request $request)
    {

        Shopday::where('user_id',Auth::id())->delete();

        foreach($request->day as $key=>$row){
        $days=new Shopday;
        $days->user_id = Auth::id(); 
        $days->status = $request->status[$key]; 
        $days->opening = $request->opening[$key]; 
        $days->close = $request->closeing[$key]; 
        $days->day = strtolower($request->day[$key]); 
        $days->save();
        }

      return response()->json(['Update Success']);


    }


    public function information()
    {
        $info=User::with('info','preview','livechat','delivery','pickup','location','usercategory','gallery')->find(Auth::id());
        return view('plugin::information.index',compact('info'));
    }

    public function informationcreate()
    {
        return view('plugin::admin.vendors.add_restaurant');
    }

    public function informationstore(Request $request)
    {        
        $user = new User;
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password = Hash::make($request->password);
        $user->plan_id = 1;
        $user->role_id = 3;
        $user->badge_id = 9;
        $user->status = 'approved';
        $user->save();

        $auth_id = $user->id;
        // dd($auth_id);
               
        //for location

        $location = new Location; 
        $location->user_id = $auth_id;
        $location->term_id = $request->city;
        $location->latitude = $request->latitude;
        $location->longitude = $request->longitude;
        $location->save();

        //for banner
        $preview=Usermeta::where('user_id',$auth_id)->where('type','preview')->first();
        if (empty($preview)) {
            $preview=new Usermeta;
            $preview->user_id=$auth_id;
            $preview->type='preview';
        }
        $preview->content=$request->preview;
        $preview->save();

        //for delivery
        $delivery=Usermeta::where('user_id',$auth_id)->where('type','delivery')->first();
        if (empty($delivery)) {
            $delivery=new Usermeta;
            $delivery->user_id=$auth_id;
            $delivery->type='delivery';
        }
        $delivery->content=$request->delivery;
        $delivery->save();

        //for pickup

        $pickup=Usermeta::where('user_id',$auth_id)->where('type','pickup')->first();
        if (empty($pickup)) {
            $pickup=new Usermeta;
            $pickup->user_id=$auth_id;
            $pickup->type='pickup';
        }
        $pickup->content=$request->pickup;
        $pickup->save();

        //for information
        $info=Usermeta::where('user_id',$auth_id)->where('type','info')->first();
        if (empty($info)) {
            $info=new Usermeta;
            $info->user_id=$auth_id;
            $info->type='info';
        }
       
        $information['description']=$request->description;
        $information['phone1']=$request->phone1;
        $information['phone2']=$request->phone2;
        $information['email1']=$request->email1;
        $information['email2']=$request->email2;
        $information['address_line']=$request->address_line;
        $information['full_address']=$request->full_address;

        $info->content=json_encode($information);
        $info->save();
        //for laivechat
        $livechat=Usermeta::where('user_id',$auth_id)->where('type','livechat')->first();
        if (empty($livechat)) {
            $livechat=new Usermeta;
            $livechat->user_id=$auth_id;
            $livechat->type='livechat';
        }

        $livechat->content=$request->property_id;
        $livechat->save();

        //for tags
        if ($request->category) {
         $user_category=Usercategory::where('user_id',$auth_id)->delete();

         foreach ($request->category as $key => $row) {
          $cat=new Usercategory;
          $cat->user_id=$auth_id;
          $cat->category_id =$row;
          $cat->save();
          }
        }
        //for gallery
        $gallery=Usermeta::where('user_id',$auth_id)->where('type','gallery')->first();
        if (empty($gallery)) {
            $gallery=new Usermeta;
            $gallery->user_id=$auth_id;
            $gallery->type='gallery';
        }

        $gallery->content=$request->gallary_input;
        $gallery->save();
        

        return response()->json(['Restaurant Created']);
    }

    public function informationupdate(Request $request)
    {
        $auth_id=Auth::id();
        
        //for location
        $location=Location::where('user_id',$auth_id)->first();
        if (empty($location)) {
           $location=new Location; 
           $location->user_id=$auth_id;
        }
        $location->term_id = $request->city;
        $location->latitude = $request->latitude;
        $location->longitude = $request->longitude;
        $location->save();

        //for banner
        $preview=Usermeta::where('user_id',$auth_id)->where('type','preview')->first();
        if (empty($preview)) {
            $preview=new Usermeta;
            $preview->user_id=$auth_id;
            $preview->type='preview';
        }
        $preview->content=$request->preview;
        $preview->save();

        //for delivery
        $delivery=Usermeta::where('user_id',$auth_id)->where('type','delivery')->first();
        if (empty($delivery)) {
            $delivery=new Usermeta;
            $delivery->user_id=$auth_id;
            $delivery->type='delivery';
        }
        $delivery->content=$request->delivery;
        $delivery->save();

        //for pickup

        $pickup=Usermeta::where('user_id',$auth_id)->where('type','pickup')->first();
        if (empty($pickup)) {
            $pickup=new Usermeta;
            $pickup->user_id=$auth_id;
            $pickup->type='pickup';
        }
        $pickup->content=$request->pickup;
        $pickup->save();

        //for information
        $info=Usermeta::where('user_id',$auth_id)->where('type','info')->first();
        if (empty($info)) {
            $info=new Usermeta;
            $info->user_id=$auth_id;
            $info->type='info';
        }
       
        $information['description']=$request->description;
        $information['phone1']=$request->phone1;
        $information['phone2']=$request->phone2;
        $information['email1']=$request->email1;
        $information['email2']=$request->email2;
        $information['address_line']=$request->address_line;
        $information['full_address']=$request->full_address;

        $info->content=json_encode($information);
        $info->save();
        //for laivechat
        $livechat=Usermeta::where('user_id',$auth_id)->where('type','livechat')->first();
        if (empty($livechat)) {
            $livechat=new Usermeta;
            $livechat->user_id=$auth_id;
            $livechat->type='livechat';
        }

        $livechat->content=$request->property_id;
        $livechat->save();

        //for tags
        if ($request->category) {
         $user_category=Usercategory::where('user_id',$auth_id)->delete();

         foreach ($request->category as $key => $row) {
          $cat=new Usercategory;
          $cat->user_id=$auth_id;
          $cat->category_id =$row;
          $cat->save();
          }
        }
        //for gallery
        $gallery=Usermeta::where('user_id',$auth_id)->where('type','gallery')->first();
        if (empty($gallery)) {
            $gallery=new Usermeta;
            $gallery->user_id=$auth_id;
            $gallery->type='gallery';
        }

        $gallery->content=$request->gallary_input;
        $gallery->save();
        

        return response()->json(['Information Updated']);


    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'title_en' => 'required|max:100',
            'title_ar' => 'required|max:100',
            'description_en' => 'required',
            'description_ar' => 'required',
        ]);


        $slug=Str::slug($request->title_en);
        if ($slug=='') {
            $slug=str_replace(' ', '-', $request->title_en);
        }
       
        $post=new Terms;
        $post->title_en=$request->title_en;
        $post->title_ar=$request->title_ar;
        $post->description_en=$request->description_en;
        $post->description_ar=$request->description_ar;
        $post->slug=$slug;
        $post->type=6;
        $post->auth_id=Auth::id();
        $post->status=$request->status;
        $post->discount=$request->discount;
        $post->save();

        $post_meta = new Meta;
        $post_meta->term_id=$post->id;
        $post_meta->type='excerpt';
        $post_meta->content=$request->excerpt;
        $post_meta->save();

        $post_meta = new Meta;
        $post_meta->term_id=$post->id;
        $post_meta->type='preview';
        $post_meta->content=$request->preview;
        $post_meta->save();

        $product=new Productmeta;
        $product->term_id = $post->id;
        // $product->price = $request->price;
        $product->price = 0;
        $product->save();

        if ($request->category) {
            foreach ($request->category as $cat_row) {
                $cat= new PostCategory;
                $cat->term_id=$post->id;
                $cat->category_id=$cat_row;
                $cat->save();
            }
        }

        if ($request->addon) {
            foreach ($request->addon as $addon_row) {
                $cat= new Addon;
                $cat->term_id=$post->id;
                $cat->addon_id=$addon_row;
                $cat->save();         
            }
        }

        // if($request->size && $request->price){
        //     foreach($request->price as $key => $value){
        //         if ($value != null) {
        //             $term_size = new TermSize;
        //             $term_size->product_id = $post->id;
        //             $term_size->size_id = $request->size[$key];
        //             $term_size->price = $value;
        //             $term_size->save();
        //         }
        //     }
        // }

        if($request->size_name_en && $request->size_name_ar && $request->size_price ){
            foreach($request->size_name_en as $key => $value){
                if ($value != null) {
                    $term_size = new TermSize;
                    $term_size->product_id = $post->id;
                    $term_size->size_name_en = $value;
                    $term_size->size_name_ar = $request->size_name_ar[$key];
                    $term_size->calories = $request->calories[$key];
                    $term_size->price = $request->size_price[$key];
                    $term_size->save();
                }
            }
        }

        return response()->json(['Product Created']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $info=Terms::with('preview','excerpt','price','postcategory','addonid')->where('auth_id',Auth::id())->find($id);
        $addons=Terms::where('type',8)->where('auth_id',Auth::id())->select('id','title_en')->get();
        // $sizes = Size::where('restaurant_id',Auth::id())->get();

        $sizes = TermSize::where('product_id',$id)->get();
        // dd($item_size);
        // foreach ($sizes as $size) {
        //     $item_size = TermSize::where('product_id',$id)->where('size_id', $size->id)->first();
        //     if ($item_size) {
        //         $size->price = $item_size->price;
        //     }
        // }

        return view('plugin::products.edit',compact('info','addons', 'sizes'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       $validatedData = $request->validate([
            'title_en' => 'required|max:100',
            'title_ar' => 'required|max:100',
            'description_en' => 'required',
            'description_ar' => 'required',
        ]);

        $post= Terms::find($id);
        $post->title_en=$request->title_en;
        $post->title_ar=$request->title_ar;
        $post->description_en=$request->description_en;
        $post->description_ar=$request->description_ar;
        $post->status=$request->status;
        $post->save();

        $post_meta =  Meta::where('term_id',$id)->where('type','excerpt')->first();
        if (!empty($post_meta)) {
          $post_meta->content=$request->excerpt;
          $post_meta->save();
      }
       
        $postdetail =  Meta::where('term_id',$id)->where('type','content')->first();
       
        if (!empty($postdetail)) {
            $post_meta->content=$request->content;
            $postdetail->save();
        }

        $pr =  Meta::where('term_id',$id)->where('type','preview')->first();
       
        if (!empty($pr)) {
            $pr->content=$request->preview;
            $pr->save();
        }
        
        if ($request->category) {
            PostCategory::where('term_id',$id)->delete();    
            foreach ($request->category as $cat_row) {
                if ($cat_row != 0) {
                    $cat= new PostCategory;
                    $cat->term_id=$id;
                    $cat->category_id=$cat_row;
                    $cat->save();
                }
            }
        }

        $product= Productmeta::where('term_id',$id)->first();
        // $product->price = $request->price;
        $product->price = 0;
        $product->save();

        if ($request->addon) {
            Addon::where('term_id',$post->id)->delete();
            foreach ($request->addon as $addon_row) {
                $cat= new Addon;
                $cat->term_id=$post->id;
                $cat->addon_id=$addon_row;
                $cat->save();         
            }
        }

        // if($request->size && $request->price){
        //     TermSize::where('product_id',$post->id)->delete();
        //     foreach($request->price as $key => $value){
        //         if ($value != null) {
        //             $term_size = new TermSize;
        //             $term_size->product_id = $post->id;
        //             $term_size->size_id = $request->size[$key];
        //             $term_size->price = $value;
        //             $term_size->save();
        //         }
        //     }
        // }

        if($request->size_name_en && $request->size_name_ar && $request->size_price){

            TermSize::where('product_id',$post->id)->delete();

            foreach($request->size_name_en as $key => $value){
                if ($value != null) {
                    $term_size = new TermSize;
                    $term_size->product_id = $post->id;
                    $term_size->size_name_en = $value;
                    $term_size->size_name_ar = $request->size_name_ar[$key];
                    $term_size->calories = $request->calories[$key];
                    $term_size->price = $request->size_price[$key];
                    $term_size->save();
                }
            }
        }

        return response()->json(['Product Updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        
        if ($request->status=='publish') {
            if ($request->ids) {

                foreach ($request->ids as $id) {
                    $post=Terms::find($id);
                    $post->status=1;
                    $post->save();   
                }
                    
            }
        }
        elseif ($request->status=='trash') {
            if ($request->ids) {
                foreach ($request->ids as $id) {
                    $post=Terms::find($id);
                    $post->status=0;
                    $post->save();   
                }
            }
        }
        elseif ($request->status=='delete') {
            if ($request->ids) {
                foreach ($request->ids as $id) {
                   Terms::destroy($id);
                }
            }
        }
        return response()->json('Success');

    }
}