@section('style')
@if (Amcoders\Plugin\Plugin::is_active('WebNotification'))
<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
<script>
  "use strict";
  window.OneSignal = window.OneSignal || [];
  OneSignal.push(function() {
    OneSignal.init({
      appId: '{{ env('ONESIGNAL_APP_ID') }}',
      notifyButton: {
        enable: true,
      },
      subdomainName: "{{ env('ONESIGNAL_SUB_DOMAIN') }}",
    });
    OneSignal.on('subscriptionChange', function (isSubscribed) {
      OneSignal.getUserId(function(userId) {
       $('#signal_user_id').val(userId);
       $('#hiddenbtn').click();
     });
    });
  });
</script>
@endif
@endsection
@extends('layouts.backend.app')

@section('content')
<form method="post" id="basicform" action="{{ route('store.subscribe') }}">
  @csrf
  <input type="hidden" name="player_id"  id="signal_user_id" >
  <button type="hidden" class="none" id="hiddenbtn"></button>
</form>

<div class="row">
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon">
        @if(Auth::user()->status=='approved')
        <img src="{{ asset('uploads/open.png') }}" height="60">
        @else
        <img src="{{ asset('uploads/close.png') }}" height="60">
        @endif
      </div>
      <div class="card-wrap">
        <div class="card-header">
         @if(Auth::user()->status=='approved')
         <h4>{{ __('Resturant Open') }}</h4>
         @else
         <h4>{{ __('Resturant Close') }}</h4>
         @endif
       </div>
       <div class="card-body">
        @if(Auth::user()->status=='approved' || Auth::user()->status=='offline')
        <form action="{{ route('store.status') }}" method="post">
          @csrf

          @if(Auth::user()->status=='approved')
          <input type="hidden" name="status" value="offline">
          <button class="btn btn-danger btn-sm text-white mt-2">{{ __('Turn Off') }}</button>
          @else
          <input type="hidden" name="status" value="approved">
          <button class="btn btn-success btn-sm text-white mt-2">{{ __('Turn On') }}</button>
          @endif
        </form>
        @endif
      </div>
    </div>
  </div>
</div>
<div class="col-lg-3 col-md-6 col-sm-6 col-12">
  <div class="card card-statistic-1">
    <div class="card-icon">
      <img src="{{ asset('uploads/money.png') }}" height="60">
    </div>
    <div class="card-wrap">
      <div class="card-header">
        <h4>{{ __('Total Earnings') }}</h4>
      </div>
      <div class="card-body">
        {{ number_format($totals,2) }}
      </div>
    </div>
  </div>
</div>
<div class="col-lg-3 col-md-6 col-sm-6 col-12">
  <div class="card card-statistic-1">
    <div class="card-icon">
      <img src="{{ asset('uploads/salary.png') }}" height="60">

    </div>
    <div class="card-wrap">
      <div class="card-header">
        <h4>{{ __('Earnings In this Month') }}</h4>
      </div>
      <div class="card-body">
        {{ number_format($earningMonth,2) }}
      </div>
    </div>
  </div>
</div>
@if(Auth::user()->badge)
<div class="col-lg-3 col-md-6 col-sm-6 col-12">
  <div class="card card-statistic-1">
    <div class="card-icon">
      <img height="80" class="rounded pb-1 mb-1" src="{{ asset(Auth::user()->badge->preview->content ?? null) }}">
    </div>
    <div class="card-wrap">
      <div class="card-header">
        <h4>{{ __('Your Badge') }}</h4>
      </div>
      <div class="card-body">
        {{ Auth::user()->badge->title_en ?? '' }}
      </div>
    </div>
  </div>
</div>
@endif
{{--
<div class="col-3">
    <div class="card card-statistic-1">
      <div class="card-icon">
        @if(Auth::user()->is_reserve_open=='approved')
        <img src="{{ asset('uploads/open.png') }}" height="60">
        @else
        <img src="{{ asset('uploads/close.png') }}" height="60">
        @endif
      </div>
      <div class="card-wrap">
        <div class="card-header">
         @if(Auth::user()->is_reserve_open=='approved')
         <h4>{{ __('Reservation Open') }}</h4>
         @else
         <h4>{{ __('Reservation Close') }}</h4>
         @endif
        </div>
        <div class="card-body">
          @if(Auth::user()->is_reserve_open=='approved' || Auth::user()->is_reserve_open=='offline')
          <form action="{{ route('store.is_reserve_open') }}" method="post">
            @csrf

            @if(Auth::user()->is_reserve_open=='approved')
            <input type="hidden" name="is_reserve_open" value="offline">
            <button class="btn btn-danger btn-sm text-white mt-2">{{ __('Turn Off') }}</button>
            @else
            <input type="hidden" name="is_reserve_open" value="approved">
            <button class="btn btn-success btn-sm text-white mt-2">{{ __('Turn On') }}</button>
            @endif
          </form>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="col-9">
    <div class="card mb-0">
      <div class="card-body">
        <ul class="nav nav-pills">
          <li class="nav-item">
            <a class="nav-link @if(url()->full() ==  route('store.dashboard')) active @endif" href="{{ route('store.dashboard') }}">{{ __('All Reservations') }}<span class="badge @if(url()->full() ==  route('store.dashboard')) badge-white @else badge-info @endif">{{ $allorders ?? \App\Reservation::where('vendor_id',Auth::id())->count() }}</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link @if(url()->full() ==  route('store.dashboard','status=3')) active @endif" href="{{ route('store.dashboard','status=3') }}">{{ __('Accepted Reservations') }}<span class="badge badge-primary">{{ $accepted ??  \App\Reservation::where('vendor_id',Auth::id())->where('status',3)->count() }}</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link @if(url()->full() ==  route('store.dashboard','status=2')) active @endif" href="{{ route('store.dashboard','status=2') }}">{{ __('Pending Reservations') }}<span class="badge badge-warning">{{ $pendings ?? \App\Reservation::where('vendor_id',Auth::id())->where('status',2)->count() }}</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link @if(url()->full() ==  route('store.dashboard','status=0')) active @endif" href="{{ route('store.dashboard','status=0') }}">{{ __('Declined Reservations') }}<span class="badge badge-danger">{{ $declineds ?? \App\Reservation::where('vendor_id',Auth::id())->where('status',0)->count() }}</span></a>
          </li>
        </ul>
      </div>
    </div>
  </div> --}}

@if(!empty($notice))
<div class="col-lg-6 col-md-6 col-6 col-sm-6">
  @else
  <div class="col-lg-6 col-md-6 col-6 col-sm-6">
    @endif

    <div class="card card-primary">
      <div class="card-header">
        <h4>{{ __('Latest Order') }}</h4>
        <div class="card-header-action">
          <a href="{{ route('store.order.index') }}" class="btn btn-primary">View All</a>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped mb-0">
            <thead>
              <tr>
                <th>{{ __('Order') }}</th>
                <th>{{ __('Amount') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Payment Status') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach($orders as $row)
              <tr>
                <td>
                  {{ __('Order No') }} #{{ $row->id }}
                  <div class="table-links">
                    @if($row->order_type==1)
                    <a href="{{ route('store.order.show',$row->id) }}">{{ __('Home Delivery') }}</a>
                    @else
                    <a href="{{ route('store.order.show',$row->id) }}">{{ __('Pickup') }}</a>
                    @endif
                    <div class="bullet"></div>
                    <a href="{{ route('store.order.show',$row->id) }}">{{ __('View Order') }}</a>
                    <div class="bullet"></div>
                    <a href="{{ route('store.invoice',$row->id) }}">{{ __('Download Invoice') }}</a>
                  </div>
                </td>
                <td>
                  {{ number_format($row->total,2) }}
                </td>
                <td>@if($row->status == 1)
                  <span class="badge badge-success">{{ __('Completed') }}</span>
                  @elseif($row->status == 2)
                  <span class="badge badge-primary"> {{ __('Pending') }} </span>
                   @elseif($row->status == 3) <span class="badge badge-warning"> {{ __('Accepted') }} </span>
                   @elseif($row->status == 0)  <span class="badge badge-danger"> {{ __('Cancelled') }} </span>
                    @endif</td>


                <td>@if($row->payment_status == 1)
                  <span class="badge badge-success">{{ __('Completed') }}</span>
                  @elseif($row->payment_status == 0)  <span class="badge badge-danger"> {{ __('Pending') }} </span>
                @endif</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          {{ $orders->links() }}
        </div>
      </div>
    </div>
  </div>


  @if(!empty($notice))
  @php
  $json=json_decode($notice->value);
  @endphp
  <div class="col-lg-6 col-md-6 col-12 col-sm-12">
    <div class="card card-danger">
      <div class="card-header">
        <h4>{{ __('Announcement') }}</h4>
      </div>
      <div class="card-body">
        <h5>{{ $json->title }}</h5>
        <p>{{ $json->message }}</p>
      </div>
    </div>
  </div>
  @endif


<div class="col-lg-6 col-md-6 col-6 col-sm-6">
    <?php
    // $reservations= App\Reservation::where('vendor_id',Auth::id())->with('customerinfo')->paginate(15);

    ?>
    <div class="row">
        <div class="col-3">
            <div class="card card-statistic-1">
              <div class="card-icon">
                @if(Auth::user()->is_reserve_open=='approved')
                <img style="padding-left: 0" src="{{ asset('uploads/open.png') }}" height="60">
                @else
                <img style="padding-left: 0" src="{{ asset('uploads/close.png') }}" height="60">
                @endif
              </div>
              <div class="card-wrap">
                <div class="card-header">
                 @if(Auth::user()->is_reserve_open=='approved')
                 <h4 style="margin: -13px;padding: 1px;text-align: center;">{{ __('Reservation Open') }}</h4>
                 @else
                 <h4 style="margin: -13px;padding: 1px;text-align: center;">{{ __('Reservation Close') }}</h4>
                 @endif
                </div>
                <div class="card-body">
                  @if(Auth::user()->is_reserve_open=='approved' || Auth::user()->is_reserve_open=='offline')
                  <form action="{{ route('store.is_reserve_open') }}" method="post">
                    @csrf

                    @if(Auth::user()->is_reserve_open=='approved')
                    <input type="hidden" name="is_reserve_open" value="offline">
                    <button style="margin-left: -9px;
    position: relative;
    top: 5px;
    left: 6px;" class="btn btn-danger btn-sm text-white mt-2">{{ __('Turn Off') }}</button>
                    @else
                    <input type="hidden" name="is_reserve_open" value="approved">
                    <button style="margin-left: -9px;
    position: relative;
    top: 5px;
    left: 6px;" class="btn btn-success btn-sm text-white mt-2">{{ __('Turn On') }}</button>
                    @endif
                  </form>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <div class="col-9">
            <div class="card mb-0">
              <div class="card-body">
                <ul class="nav nav-pills">
                  <li class="nav-item">
                    <a class="nav-link @if(url()->full() ==  route('store.dashboard')) active @endif" href="{{ route('store.dashboard') }}">{{ __('All Reservations') }}<span class="badge @if(url()->full() ==  route('store.dashboard')) badge-white @else badge-info @endif">{{ $allorders ?? \App\Reservation::where('vendor_id',Auth::id())->count() }}</span></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link @if(url()->full() ==  route('store.dashboard','status=3')) active @endif" href="{{ route('store.dashboard','status=3') }}">{{ __('Accepted Reservations') }}<span class="badge badge-primary">{{ $accepted ??  \App\Reservation::where('vendor_id',Auth::id())->where('status',3)->count() }}</span></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link @if(url()->full() ==  route('store.dashboard','status=2')) active @endif" href="{{ route('store.dashboard','status=2') }}">{{ __('Pending Reservations') }}<span class="badge badge-warning">{{ $pendings ?? \App\Reservation::where('vendor_id',Auth::id())->where('status',2)->count() }}</span></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link @if(url()->full() ==  route('store.dashboard','status=0')) active @endif" href="{{ route('store.dashboard','status=0') }}">{{ __('Declined Reservations') }}<span class="badge badge-danger">{{ $declineds ?? \App\Reservation::where('vendor_id',Auth::id())->where('status',0)->count() }}</span></a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
    </div>


    <div class="col-12 mt-2">
      <div class="card">
        <div class="card-body">
          <div class="float-left">
            {{-- <form>

              <div class="row">

                <div class="form-group ml-3">
                  <label>{{ __('Starting Date') }}</label>
                  <input type="date" name="start" class="form-control" required value="{{ $start ?? '' }}">
                </div>
                <div class="form-group ml-2">
                 <label>{{ __('Ending Date') }}</label>
                 <input type="date" name="end" class="form-control" required value="{{ $end ?? '' }}">
               </div>
               <div class="form-group ml-2">
                 <label>{{ __('Status') }}</label>
                 <select class="form-control" name="status">
                  @php
                  if(!isset($st)) {
                    $st=null;
                  }
                  @endphp
                   <option value="" selected>{{ __('All') }}</option>
                   <option value="3" {{$st == '3' ? 'selected' : ''}}>{{ __('Accept') }}</option>
                   <option value="2" {{$st == '2' ? 'selected' : ''}}>{{ __('Pending') }}</option>
                   <option value="0" {{$st == '0' ? 'selected' : ''}}>{{ __('Declined') }}</option>
                 </select>
               </div>

               <div class="form-group mt-4">
                <button class="btn btn-primary btn-lg  ml-2 mt-1" type="submit">{{ __('Filter') }}</button>
              </div>
            </div>
          </form> --}}
        </div>
        <div class="float-right">
          <form>
            <div class="input-group mt-3 col-12">

              <input type="text" class="form-control" placeholder="Search By Reservation ID" required="" name="src" value="{{ $src ?? '' }}">
              <div class="input-group-append">
                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
              </div>
            </div>
          </form>
        </div>
        <div class="table-responsive">
          <table class="table table-striped table-hover text-center table-borderless">
            <thead>
              <tr>
                <th>{{ __('Reservation ID') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Phone') }}</th>
                <th>{{ __('Person') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Time') }}</th>
                <th>{{ __('Action') }}</th>

              </tr>
            </thead>
            <tbody>
              @foreach($reservations as $key => $row )
              <tr>
                <td><a href="{{ route('store.order.show',$row->id) }}">#{{ $row->id }}</a></td>
                <td>{{ $row->customerinfo->name }}</td>
                <td>{{ $row->customerinfo->email }}</td>
                <td>{{ $row->customerinfo->phone }}</td>
                <td>{{ $row->person }}</td>
                <td>
                  @if($row->status == 2)
                  <span class="badge badge-primary"> {{ __('Pending') }} </span>
                  @elseif($row->status == 3)
                  <span class="badge badge-warning"> {{ __('Accepted') }} </span>
                  @elseif($row->status == 0)
                  <span class="badge badge-danger"> {{ __('Cancelled') }} </span>
                  @endif
                </td>
                <td>{{ $row->date }}</td>
                <td>{{ $row->time }}</td>
                <td>
                  <div class="dropdown d-inline">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Action
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item has-icon" href="{{ route('store.reservation.show',$row->id) }}"><i class="fas fa-eye"></i> View</a>
                      <a class="dropdown-item has-icon" target="_blank" href="{{ route('store.reserve_invoice_print',$row->id) }}"><i class="fas fa-print"></i> Print Now</a>
                       <a class="dropdown-item has-icon" href="{{ route('store.reserve_invoice',$row->id) }}"><i class="fas fa-file-invoice"></i> Download Invoice</a>
                    </div>
                  </div>
              </td>
              </tr>
              @endforeach
            </tbody>

            <tfoot>
              <tr>
                <th>{{ __('Reservation ID') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Phone') }}</th>
                <th>{{ __('Person') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Time') }}</th>
                <th>{{ __('Action') }}</th>
              </tr>
            </tfoot>
          </table>
          {{ $reservations->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
</div>
@endsection

@section('script')

<script src="{{ asset('admin/js/form.js') }}"></script>
<script type="text/javascript">
  "use strict";
  function success(arg) {
    //window.location.reload();
  }
</script>
@endsection
