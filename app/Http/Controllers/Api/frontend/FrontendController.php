<?php

namespace App\Http\Controllers\Api\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Terms;
use App\Location;
use App\Category;
use App\User;
use Str;
use App\Options;
use App\Slider;
use App\Group;
use App;
use App\Table;
use App\TableDay;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Reservation;
use Illuminate\Support\Facades\DB;

class FrontendController extends Controller
{
	function __construct()
    {
        App::setLocale(request()->header('Accept-Language'));
        date_default_timezone_set('Asia/Riyadh');
        // ini_set( 'serialize_precision', -1 );
    }

	public function main(Request $request)
	{
        $latitude=isset($request->latitude) ? $request->latitude : 0;
        $longitude=isset($request->longitude) ? $request->longitude : 0;
        $distance=isset($request->distance) ? $request->distance : 1000;

		$sliders = Slider::with('product.postsize','product.preview','product.postcategory')->latest()->get();

		$categories = Category::where('type',1)->with('products.postsize')->latest()->get();

		$foodies = Terms::with('postsize','preview','postcategory')->where('status',1)->where('terms.type',6)->latest()->get();

        if($latitude !=0 && $longitude !=0){
            $restaurants = User::where('role_id',3)->whereIn('status',['approved', 'offline'])
			->select('id','slug','name', 'status', 'is_reserve_open')
			->with('categories.products.postsize', 'avg_ratting','ratting','shopcategory','delivery','preview','coupons', 'reserve_image')
			->with(['location' => function ($query) use($latitude, $longitude,$distance) {
                return $query->select(DB::raw('user_id,term_id,latitude,longitude , ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
                ->having('distance', '<', $distance)
                ->orderBy('distance')->get();
            }])->orderBy(Location::select(DB::raw('( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
            ->whereColumn('locations.user_id','users.id'))
            ->whereHas('location',function($qu) use($latitude, $longitude,$distance) {
                return $qu->select(DB::raw('user_id,term_id,latitude,longitude , ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
                ->having('distance', '<', $distance)
                ->orderBy('distance');
            })
            ->get();

        }else{
            $restaurants = User::where('role_id',3)->whereIn('status',['approved', 'offline'])
			->select('id','slug','name', 'status', 'is_reserve_open')
			->with('categories.products.postsize', 'avg_ratting','ratting','shopcategory','delivery','preview','coupons', 'reserve_image')
			->with(['location' => function ($query) use($latitude, $longitude,$distance) {
                return $query->select(DB::raw('user_id,term_id,latitude,longitude , ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))

                ->orderBy('distance')->get();
            }])->orderBy(Location::select(DB::raw('( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
            ->whereColumn('locations.user_id','users.id'))

            ->get();
        }


            // dd($restaurants);
		$groups = Group::select('id', 'name_en', 'name_ar')->with('products.postsize')->get();

		return response()->json([
			'sliders'		=> $sliders,
			'categories'	=> $categories,
			'foodies'		=> $foodies,
			'restaurants'	=> $restaurants,
			'groups'		=> $groups
		]);
	}

	public function restaurant_qr(Request $request,$id)
	{
		$restaurants = User::where('role_id',3)->where('status','approved')
			->select('id','slug','name', 'status', 'is_reserve_open')
			->with('categories.products.postsize', 'location', 'avg_ratting','ratting','shopcategory','delivery','preview','coupons', 'reserve_image')
			->where('id', $id)
			->first();

		return response()->json($restaurants);
	}

	public function list_of_categories(Request $request)
	{
		$categories = Category::where('type',1)->with('products.postsize')->latest()->paginate($request->limit);

		return response()->json($categories);
	}

	public function list_of_foodies(Request $request)
	{
		$foodies = Terms::with('postsize','preview','postcategory')->where('status',1)->where('terms.type',6)->latest()->paginate($request->limit);

		return response()->json($foodies);
	}

	public function list_of_restaurants(Request $request)
	{
        $latitude=isset($request->latitude) ? $request->latitude : 0;
        $longitude=isset($request->longitude) ? $request->longitude : 0;
        $distance=isset($request->distance) ? $request->distance : 1000;

		// $restaurants = User::where('role_id',3)->where('status','approved')
		// 	->select('id','slug','name', 'status', 'is_reserve_open')
		// 	->with('categories.products.postsize', 'location', 'avg_ratting','ratting','shopcategory','delivery','preview','coupons', 'reserve_image')
		// 	->paginate($request->limit);

            if($latitude !=0 && $longitude !=0){
                $restaurants = User::where('role_id',3)->whereIn('status',['approved', 'offline'])
                ->select('id','slug','name', 'status', 'is_reserve_open')
                ->with('categories.products.postsize', 'avg_ratting','ratting','shopcategory','delivery','preview','coupons', 'reserve_image')
                ->with(['location' => function ($query) use($latitude, $longitude,$distance) {
                    return $query->select(DB::raw('user_id,term_id,latitude,longitude , ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
                    ->having('distance', '<', $distance)
                    ->orderBy('distance')->get();
                }])->orderBy(Location::select(DB::raw('( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
                ->whereColumn('locations.user_id','users.id'))
                ->whereHas('location',function($qu) use($latitude, $longitude,$distance) {
                    return $qu->select(DB::raw('user_id,term_id,latitude,longitude , ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
                    ->having('distance', '<', $distance)
                    ->orderBy('distance');
                })
                ->paginate($request->limit);

            }else{
                $restaurants = User::where('role_id',3)->whereIn('status',['approved', 'offline'])
                ->select('id','slug','name', 'status', 'is_reserve_open')
                ->with('categories.products.postsize', 'avg_ratting','ratting','shopcategory','delivery','preview','coupons', 'reserve_image')
                ->with(['location' => function ($query) use($latitude, $longitude,$distance) {
                    return $query->select(DB::raw('user_id,term_id,latitude,longitude , ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))

                    ->orderBy('distance')->get();
                }])->orderBy(Location::select(DB::raw('( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
                ->whereColumn('locations.user_id','users.id'))

                ->paginate($request->limit);
            }

		return response()->json($restaurants);
	}

	public function list_of_foodies_of_group(Request $request, $id)
	{
		$foodies = Terms::with('postsize','preview','postcategory')
			->where('status',1)
			// ->where('terms.type',6)
			// ->join('group_product', 'group_product.product_id', '=', 'terms.id')
			->whereHas('productsofgroup', function($query) use ($request) {
				$query->where('group_id', $request->id);
			})
			// ->where('group_id', $id)
			->select('terms.*')
			->latest()
			->paginate($request->limit);

		return response()->json($foodies);
	}

	public function search(Request $request)
	{
		$validator = \Validator::make($request->all(), [
            'type'	=> 'required|in:1,2',
            'text'	=> 'required|string'
        ]);

        if($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()[0]]);
        }

		if ($request->type == 1) {
			$foodies = Terms::with('postsize','preview','postcategory')
				->where('status',1)
				->where('terms.type',6)
				->where(function($query) use ($request) {
	    			$query->where('title_en', 'like', '%'.$request->text.'%');
	                $query->orWhere('title_ar', 'like', '%'.$request->text.'%');
	    		})
				->latest()->paginate($request->limit);

			return response()->json($foodies);
		} else {
			$restaurants = User::where('role_id',3)->where('status','approved')
				->select('id','slug','name', 'status', 'is_reserve_open')
				->with('categories.products.postsize', 'location', 'avg_ratting','ratting','shopcategory','delivery','preview','coupons', 'reserve_image')
				->where(function($query) use ($request) {
	    			$query->where('name', 'like', '%'.$request->text.'%');
	    		})
				->paginate($request->limit);

			return response()->json($restaurants);
		}
	}

	public function list_of_restaurants_by_category(Request $request)
	{
        $latitude=isset($request->latitude) ? $request->latitude : 0;
        $longitude=isset($request->longitude) ? $request->longitude : 0;
        $distance=isset($request->distance) ? $request->distance : 1000;

        if($latitude !=0 && $longitude !=0){
            $restaurants = User::where('role_id',3)
		    ->whereHas('categories', function($query) use ($request) {
		        $query->where('id', $request->id);
		    })
		    ->where('status','approved')
			->select('id','slug','name', 'status', 'is_reserve_open')
			->with('categories.products.postsize', 'location', 'avg_ratting','ratting','shopcategory','delivery','preview','coupons', 'reserve_image')
			->with(['location' => function ($query) use($latitude, $longitude,$distance) {
                return $query->select(DB::raw('user_id,term_id,latitude,longitude , ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
                ->having('distance', '<', $distance)
                ->orderBy('distance')->get();
            }])->orderBy(Location::select(DB::raw('( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
            ->whereColumn('locations.user_id','users.id'))
            ->whereHas('location',function($qu) use($latitude, $longitude,$distance) {
                return $qu->select(DB::raw('user_id,term_id,latitude,longitude , ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
                ->having('distance', '<', $distance)
                ->orderBy('distance');
            })
            ->paginate($request->limit);

        }else{
            $restaurants = User::where('role_id',3)
		    ->whereHas('categories', function($query) use ($request) {
		        $query->where('id', $request->id);
		    })
		    ->where('status','approved')
			->select('id','slug','name', 'status', 'is_reserve_open')
			->with('categories.products.postsize', 'location', 'avg_ratting','ratting','shopcategory','delivery','preview','coupons', 'reserve_image')
			->with(['location' => function ($query) use($latitude, $longitude,$distance) {
                return $query->select(DB::raw('user_id,term_id,latitude,longitude , ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))

                ->orderBy('distance')->get();
            }])->orderBy(Location::select(DB::raw('( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
            ->whereColumn('locations.user_id','users.id'))

            ->paginate($request->limit);
        }



		return response()->json($restaurants);
	}

	public function get_list_of_tables(Request $request)
	{
	    $validator = validator()->make($request->all(),[
            'restaurant_id'  => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->first()]);
        }

		$tables = Table::where('restaurant_id', $request->restaurant_id)
            ->with('images', 'restaurant')
            ->orderBy('id','DESC')
            ->get();

        foreach($tables as $table){
            $days = TableDay::where('table_id', $table->id)->select('table_id', 'day', 'open', 'close', 'status')->get();
            $table->days = $days;
            $index = 0;
            foreach($days as $day){
                $nowDayName = strtolower(Carbon::parse(now())->format('l'));
                $nowDate = Carbon::now()->toDateTimeString();
                // dd($nowDayName, $nowDate, $day);
                $hoursArr = array();

                if($day->day == $nowDayName){
                    $index++;
                }

                if($index > 0){
                    $date_string = Carbon::now()->addDays($index-1)->toDateString();
                    $index++;
                } else {
                    $date_string = null;
                }

                $start = Carbon::parse($day->open);
                $end = Carbon::parse($day->close);
                $hours = $end->diffInHours($start);
                // dd($hours);  //8
                $index_arr = 0;
                for($i = 0; $i < $hours; $i++){
                    if($date_string != null) {
                        $time_string = Carbon::parse($day->open)->addHours($i)->toTimeString();
                        $checkReservation = Reservation::where('table_id', $day->table_id)->whereDate('date', $date_string)->whereTime('time', $time_string)->whereIn('status', [2, 3])->count();
                        if($checkReservation == 0){
                            // $hoursArr[]['number'] = $i;
                            // $hoursArr[]['time'] = $time_string;
                            $hoursArr[] = (object) array('number' => $index_arr, 'time' => $time_string);
                            $index_arr++;
                        }
                    }
                }
                // dd($hoursArr);
                $day->date_string = $date_string;
                $day->time_string = $hoursArr;
            }
            // $period = CarbonPeriod::create('2018-06-14', '2018-06-20');
        }

        return response()->json([
			'data'		=> $tables
		]);
	}



	//all city
	public function AllCity()
	{
		$locations=Terms::where('type',2)->withcount('Locationcount')->with('preview')->latest()->get()->map(function($data){
			$qry['id']=$data->id;
			$qry['title']=$data->title;
			$qry['count']=$data->locationcount_count;
			$qry['preview']=$data->preview->content;
			return $qry;
		});

		return response()->json(['allcity'=>$locations]);
	}

	public function GetCityId($slug)
	{
		$slug=Str::slug($slug);
		$info=Terms::where('slug',$slug)->where('status',1)->where('type',2)->first();
		if (empty($info)) {
			return response()->json('City Not Found',404);
		}
		return response()->json(['data'=>$info]);
	}


    //all category
	function category(Request $request)
	{
		if ($request->random=='off') {
			return $categories=Category::where('type',2)->select('id','name_en','name_ar','avatar')->take($request->limit)->get();
		}
		else{
			return $categories=Category::where('type',2)->select('id','name_en','name_ar','avatar')->inRandomOrder()->take($request->limit)->get();
// 			return $categeories=\App\Category::where('type',2)->whereHas('user')->with('user')->latest()->paginate(12);

		}

	}
	
	//cuisine category
	function cuisine_category(Request $request)
	{

	    return $categeories=Category::where('type',2)->whereHas('user')->with('user')->latest()->paginate(12);

	}

	//city by resturants
	public function CityByUsers(Request $request,$id)
	{
		$info=Terms::where('status',1)->where('type',2)->with('excerpt')->find($id);
		$mapinfo=json_decode($info->excerpt->content ?? '');

		$data['info']['title']=$info->title;
		$data['info']['id']=$info->id;

		$data['info']['latitude']=(double)$mapinfo->latitude;
		$data['info']['longitude']=(double)$mapinfo->longitude;
		$data['info']['zoom']=(double)$mapinfo->zoom;

		if (!empty($request->cats)) {

			$posts=Location::join('user_category','user_category.user_id','locations.user_id')
			->where('locations.role_id',3)
			->where('locations.term_id',$id)
			->where('user_category.category_id',$request->cats)
			->wherehas('restaurant')
			->with('restaurant')
			->orderBy('locations.id',$request->order ?? 'DESC')
			->paginate(12);
		}
		else{
			return $posts=Location::where('role_id',3)
			->where('term_id',$id)
			->wherehas('restaurant')
			->with('restaurant')
			->orderBy('id',$request->order ?? 'DESC')
			->paginate(12);
		}

		$data['restaurants']=$posts;

		return $data;

	}

	//offerable restaurants
	public function offerAble($id)
	{
	   $users=Location::where('term_id',$id)->select('id','user_id','term_id','latitude','longitude')->wherehas('Offerables')->with('Offerables')->latest()->paginate(10);
	   if (empty($users)) {
	   	return response()->json('City Not Found',404);
	   }
	   return response()->json(['restaurants'=>$users]);

	}

	public function home($id)
	{
		$Offerables=Location::where('term_id',$id)->select('id','user_id','term_id','latitude','longitude')->wherehas('Offerables')->with('Offerables')->latest()->paginate(10);
		 $categories=Category::where('type',2)->select('id','name_en','avatar')->inRandomOrder()->get()->map(function($q)
		{
			$data['id']=$q->id;
			$data['name']=$q->name_en;
			if (!empty($q->avatar)) {
				$data['avatar']=asset(imagesize($q->avatar,'medium'));
			}
			else{
				$data['avatar']=asset('uploads/store.jpg');
			}

            // dd($data);

			return $data;
		});

        // dd($categories);
		$all_resturants=Location::where('role_id',3)
			->where('term_id',$id)
			->wherehas('restaurant_info')
			->with('restaurant_info')
			->latest()
			->paginate(12);

            // dd($all_resturants);
		return response()->json(['offerables'=>$Offerables,'categories'=>$categories,'all_resturants'=>$all_resturants]);

	}

	public function getResturents($id)
	{
		$users=Location::where('role_id',3)
			->where('term_id',$id)
			->wherehas('restaurant')
			->with('restaurant')
			->latest()
			->paginate(12);
		if (empty($users)) {
		return response()->json('User Not Found',404);
		}

		return response()->json(['all_resturants'=>$users]);
	}




	public function restaurantView($id)
	{
		$store=User::where('status','approved')->where('role_id',3)->with('info','gallery','preview','avg_ratting','delivery','pickup','shopcategory','location','shopday','ratting','vendor_reviews')->find($id);
		if (empty($store)) {
			return response()->json('Something Wrong',404);
		}
		$data['info']['id']=$store->id;
		$data['info']['name']=$store->name;
		$data['info']['slug']=$store->slug;
		if (!empty($store->preview->content)) {
			$data['info']['preview']=asset($store->preview->content);
		}
		else{
			$data['info']['preview']=asset($store->avatar);
		}

		$data['info']['avg_ratting']=$store->avg_ratting->content;
		$data['info']['ratting']=$store->ratting->content;
		$data['info']['delivery']=$store->delivery->content;
		$data['info']['pickup']=$store->pickup->content;
		$data['info']['shopcategory']=$store->shopcategory;
		$data['info']['location']=$store->location;
		$data['info']['shopday']=$store->shopday;
		$data['info']['about']=json_decode($store->info->content);
		$data['info']['reviews']=$store->vendor_reviews;

		$gallaries=[];
		$arrs=explode(',', $store->gallery->content);
		foreach ($arrs as $key => $value) {
			if (!empty($value)) {
				array_push($gallaries, asset($value));
			}

		}

		$data['info']['gallary']=$gallaries;
		$data['info']['avatar']=asset($store->avatar);

		$menus=Category::where('user_id',$id)->where('type',1)->inRandomOrder()->get();
		$products= Terms::where('auth_id',$id)->with('price','preview','postcategory')->where('status',1)->where('terms.type',6)->latest()->get();

		$info['info']=$data;
		$info['menus']=$menus;
		$info['products']=$products;

		return response()->json($info);
	}

	public function ResturantProductList($id)
	{
		$categories =Category::where('user_id',$id)->where('type',1)->select('id','name_en','name_ar','user_id')->wherehas('products')->with('products')->get();
		return response()->json(['products'=>$categories]);
	}

	public function deliveryfee()
	{
	    return $delivery_fee = Options::where('key','km_rate')->first()->value;
	}
}
