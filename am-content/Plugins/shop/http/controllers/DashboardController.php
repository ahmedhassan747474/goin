<?php

namespace Amcoders\Plugin\shop\http\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Order;
use App\Transactions;
use Auth;
use Carbon\Carbon;
use App\User;
use App\Options;
use App\Reservation;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
    	$totalseles=Order::where('vendor_id',Auth::id())->where('payment_status',1)->where('status',1)->sum('total');
    	$totalcommsion=Order::where('vendor_id',Auth::id())->where('payment_status',1)->where('status',1)->sum('commission');
    	$totals=$totalseles-$totalcommsion;

    	$totalselesmonth=Order::where('vendor_id',Auth::id())
    	->where('payment_status',1)
    	->where('status',1)
    	->whereYear('created_at', Carbon::now()->year)
    	->whereMonth('created_at', Carbon::now()->month)
    	->sum('total');
    	$totalcommsionmonth=Order::where('vendor_id',Auth::id())
    	->where('payment_status',1)
    	->where('status',1)
    	->whereYear('created_at', Carbon::now()->year)
    	->whereMonth('created_at', Carbon::now()->month)
    	->sum('commission');

    	$earningMonth=$totalselesmonth-$totalcommsionmonth;

    	$orders=Order::where('vendor_id',Auth::id())->latest()->paginate(20);

    	$notice=Options::where('key','announcement')->where('lang',1)->first();


        $auth_id=Auth::id();
        $status = $request->status;

        // dd(route('store.dashboard'));
        if ($request->src) {
            $reservations= Reservation::where('vendor_id',$auth_id)->with('customerinfo')->where('id',$request->src)->paginate(20);
            $src = $request->src;
            return view('shop.dashboard',compact('totals','earningMonth','notice','orders','reservations'));
        }

          if ($request->status || $request->status==0 && $request->status!=null) {
            $reservations =  Reservation::where('vendor_id',$auth_id)->with('customerinfo')->where('status',$request->status)->latest()->paginate(20);
            return view('shop.dashboard',compact('totals','earningMonth','notice','orders','reservations'));
        }

          $reservations =  Reservation::where('vendor_id',$auth_id)->with('customerinfo')->latest()->paginate(20);
          // dd($orders[0]->customerinfo);

    	return view('shop.dashboard',compact('totals','earningMonth','notice','orders','reservations'));
    }

    public function status(Request $request)
    {

    	$user=User::find(Auth::id());
    	$user->status=$request->status;
    	$user->save();
    	return back();
    }

    public function is_reserve_open(Request $request)
    {
        $user=User::find(Auth::id());
        $user->is_reserve_open=$request->is_reserve_open;
        $user->save();
        return back();
    }
}
