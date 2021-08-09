<?php

namespace Amcoders\Plugin\shop\http\controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Auth;
use App\Order;
use App\Reservation;
use Carbon\Carbon;
use App\Location;
use App\User;
use App\Terms;
use App\Orderlog;
use App\Riderlog;
use App\Reservationlog;
use OneSignal;
use Amcoders\Plugin\Plugin;
class ReservationController extends controller
{

	 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $auth_id=Auth::id();

        if ($request->start) {
          $start = date("Y-m-d",strtotime($request->start));
          $end = date("Y-m-d",strtotime($request->end));
          $status = $request->status;

          if (!empty($request->status)) {
            $allorders=Reservation::where('vendor_id',$auth_id)->whereBetween('created_at',[$start,$end])->count();
            $accepted=Reservation::where('vendor_id',$auth_id)->whereBetween('created_at',[$start,$end])->where('status',3)->count();
            $pendings=Reservation::where('vendor_id',$auth_id)->whereBetween('created_at',[$start,$end])->where('status',2)->count();
            $declineds=Reservation::where('vendor_id',$auth_id)->whereBetween('created_at',[$start,$end])->where('status',0)->count();
            $orders= Reservation::where('vendor_id',$auth_id)->with('customerinfo')->whereBetween('created_at',[$start,$end])
            ->where('status', '=', $status)->paginate(100);
          }
          else
          {
            $allorders=Reservation::where('vendor_id',$auth_id)->whereBetween('created_at',[$start,$end])->count();
            $accepted=Reservation::where('vendor_id',$auth_id)->whereBetween('created_at',[$start,$end])->where('status',3)->count();
            $pendings=Reservation::where('vendor_id',$auth_id)->whereBetween('created_at',[$start,$end])->where('status',2)->count();
            $declineds=Reservation::where('vendor_id',$auth_id)->whereBetween('created_at',[$start,$end])->where('status',0)->count();
            $orders= Reservation::where('vendor_id',$auth_id)->with('customerinfo')->whereBetween('created_at',[$start,$end])->paginate(100);
          }

          $start = $request->start;
          $end = $request->end;
          $st = $request->status;

          return view('plugin::reservation.report',compact('orders','start','end','st','allorders','accepted','pendings','declineds'));
        }

        if ($request->src) {
          $orders= Reservation::where('vendor_id',$auth_id)->with('customerinfo')->where('id',$request->src)->paginate(20);
          $src = $request->src;
          return view('plugin::reservation.report',compact('orders','src'));
        }

        if ($request->status || $request->status==0 && $request->status!=null ) {
          $orders =  Reservation::where('vendor_id',$auth_id)->with('customerinfo')->where('status',$request->status)->latest()->paginate(20);
          return view('plugin::reservation.report',compact('orders'));
        }

        $orders =  Reservation::where('vendor_id',$auth_id)->with('customerinfo')->latest()->paginate(20);
        // dd($orders[0]->customerinfo);
        return view('plugin::reservation.report',compact('orders'));
    }

    /**
     * Get Order response
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
      $auth_id=Auth::id();

      if ($request->id) {
        $find_row= Reservation::where('vendor_id',$auth_id)->find($request->id);
        if (!empty($find_row)) {
            $find_row->seen=1;
            $find_row->save();
        }
        return "ok";
      }

      $newOrders=Reservation::whereDate('created_at', Carbon::today())->where('vendor_id',$auth_id)->where('status',2)->select('id','seen','payment_method','total','updated_at')->latest()->get()->map(function($data){
          $qry['id']= $data->id;
        $qry['seen']= $data->seen;
        $qry['payment_method']= strtoupper($data->payment_method);
        $qry['total']= (float)$data->total;
        $qry['time']= $data->updated_at->diffForHumans();
        return $qry;
      });

      $OrderAccepts=Reservation::whereDate('created_at', Carbon::today())->where('vendor_id',$auth_id)->where('status',3)->select('id','seen','payment_method','total','updated_at')->latest()->get()->map(function($data){
        $qry['id']= $data->id;
        $qry['seen']= $data->seen;
        $qry['payment_method']= strtoupper($data->payment_method);
        $qry['total']= (float)$data->total;
        $qry['time']= $data->updated_at->diffForHumans();
        return $qry;
      });

      return response()->json(['newOrders'=>$newOrders,'OrderAccepts'=>$OrderAccepts]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $auth_id=Auth::id();

      $info = Reservation::with('vendorinfo','reservationlog')->where('vendor_id',$auth_id)->find($id);

      if (empty($info)) {
        abort(404);
      }

      return view('plugin::reservation.show',compact('info'));

    }

    public function invoice($id)
    {
      $auth_id=Auth::id();
      $info = Reservation::with('vendorinfo')->where('vendor_id',$auth_id)->findorFail($id);
      $customer_info=json_decode($info->data);
      $vendor_info=json_decode($info->vendorinfo->info->content);
      $pdf = \PDF::loadView('plugin::reservation.invoice',compact('info','customer_info','vendor_info'));
      return $pdf->download('invoice.pdf');
    }

    public function invoice_print($id)
    {
      $auth_id=Auth::id();
      $info = Reservation::with('vendorinfo')->where('vendor_id',$auth_id)->findorFail($id);
      $customer_info=json_decode($info->data);
      $vendor_info=json_decode($info->vendorinfo->info->content);
      return view('plugin::reservation.invoice_print',compact('info','customer_info','vendor_info'));
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
        'status' => 'required',
      ]);

      $auth_id=Auth::id();
      $reservation=Reservation::where('vendor_id',$auth_id)->find($id);

      if (empty($reservation)) {
        abort(401);
      }

      $reservation->status=$request->status;
      $reservation->save();

      $log = new Reservationlog;
      $log->reservation_id = $reservation->id;
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

        $commsion=User::with('usersaas')->find(Auth::id());


        $or=Reservation::where('vendor_id',$auth_id)->find($id);
        if ($commsion->usersaas->commission != 0) {
          $com1=$commsion->usersaas->commission/100;
          $net_commision=$com1*$or->total;
          $or->commission=$net_commision;
        }
        else
        {
         $or->commission = 0;
        }
        $or->save();
      }

      if ($request->status == 3) {
        return response()->json(['Reservation Processed']);
      }
      else
      {
        return response()->json(['Reservation Cancelled']);
      }

    }


}
