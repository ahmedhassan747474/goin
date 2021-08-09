@section('style')

@endsection
@extends('layouts.backend.app')

@section('content')
<section class="section">
  <div class="section-header">
    <h1>{{ __('Dashboard') }}</h1>
  </div>
  <div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
      <div class="card card-statistic-1">
        <div class="card-icon bg-primary">
          <i class="fas fa-money-bill"></i>
        </div>
        <div class="card-wrap">
          <div class="card-header">
            <h4>{{ __('Total Earnings') }}</h4>
          </div>
          <div class="card-body">
            {{ number_format($totalearns,2) }}
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
      <div class="card card-statistic-1">
        <div class="card-icon bg-danger">
          <i class="fas fa-hand-holding-usd"></i>
        </div>
        <div class="card-wrap">
          <div class="card-header">
            <h4>{{ __('Total Payouts') }}</h4>
          </div>
          <div class="card-body">
            {{ number_format($totalPayouts,2) }}
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
      <div class="card card-statistic-1">
        <div class="card-icon bg-warning">
         <i class="fas fa-utensils"></i>
       </div>
       <div class="card-wrap">
        <div class="card-header">
          <h4>{{ __('Total Restaurant') }}</h4>
        </div>
        <div class="card-body">
          {{ number_format($resturents) }}
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-success">
        <i class="fas fa-users"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>{{ __('Total Customers') }}</h4>
        </div>
        <div class="card-body">
         {{ number_format($customers) }}
       </div>
     </div>
   </div>
 </div>                  
</div>
<div class="row">
  <div class="col-lg-4 col-md-6 col-sm-12">
    <div class="card card-statistic-2">
      <div class="card-stats">
        <div class="card-stats-title">{{ __('Order Statistics') }} 

        </div>
        <div class="card-stats-items">
          <div class="card-stats-item">
            <div class="card-stats-item-count">{{ $totalPending }}</div>
            <div class="card-stats-item-label">{{ __('In Pending') }}</div>
          </div>
          <div class="card-stats-item">
            <div class="card-stats-item-count">{{ $totalProccessing }}</div>
            <div class="card-stats-item-label">{{ __('In Processing') }}</div>
          </div>
          <div class="card-stats-item">
            <div class="card-stats-item-count">{{ $totalCompleted }}</div>
            <div class="card-stats-item-label">{{ __('Completed') }}</div>
          </div>
        </div>
      </div>
      <div class="card-icon shadow-primary bg-primary">
        <i class="fas fa-archive"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>{{ __('Total Orders') }}</h4>
        </div>
        <div class="card-body">
          {{ number_format($totalOrders) }}
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-md-6 col-sm-12">
    <div class="card card-statistic-2">
      <div class="card-stats">
        <div class="card-stats-title">{{ __('Todays order Statistics') }} 

        </div>
        <div class="card-stats-items">
          <div class="card-stats-item">
            <div class="card-stats-item-count">{{ number_format($totalTodayPending) }}</div>
            <div class="card-stats-item-label">{{ __('In Pending') }}</div>
          </div>
          <div class="card-stats-item">
            <div class="card-stats-item-count">{{ number_format($totalTodayProccessing) }}</div>
            <div class="card-stats-item-label">{{ __('In Processing') }}</div>
          </div>
          <div class="card-stats-item">
            <div class="card-stats-item-count">{{ $totalTodaComplete }}</div>
            <div class="card-stats-item-label">{{ __('Completed') }}</div>
          </div>
        </div>
      </div>
      <div class="card-icon shadow-primary bg-primary">
        <i class="fas fa-archive"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>{{ __('Total Orders') }}</h4>
        </div>
        <div class="card-body">
          {{ number_format($totalTodaOrders) }}
        </div>
      </div>
    </div>
  </div>


  <div class="col-lg-4 col-md-6 col-sm-12">
    <div class="card card-statistic-2">
      <div class="card-stats">
        <div class="card-stats-title">{{ __('Payout Statistics') }} 

        </div>
        <div class="card-stats-items">
          <div class="card-stats-item">
            <div class="card-stats-item-count">{{ $totalPayoutPending }}</div>
            <div class="card-stats-item-label">{{ __('In Pending') }}</div>
          </div>
          <div class="card-stats-item">
            <div class="card-stats-item-count">{{ $totalPayoutPending }}</div>
            <div class="card-stats-item-label">{{ __('In Pending') }}</div>
          </div>
          <div class="card-stats-item">
            <div class="card-stats-item-count">{{ $totalPayoutComplete }}</div>
            <div class="card-stats-item-label">{{ __('Completed') }}</div>
          </div>
        </div>
      </div>
      <div class="card-icon shadow-primary bg-primary">
        <i class="fas fa-archive"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>{{ __('Total Payouts') }}</h4>
        </div>
        <div class="card-body">
          {{ number_format($totalPayoutCount) }}
        </div>
      </div>
    </div>
  </div>

</div>

<div class="row">
  <!-- <div class="col-3">
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
  </div> -->

  <div class="col-12">
    <div class="card mb-0">
      <div class="card-body">
        <ul class="nav nav-pills">
          <li class="nav-item">
            <a style="font-size: 1.3em;margin-left: 1em;" class="nav-link @if(url()->full() ==  route('admin.dashboard')) active @endif" href="{{ route('admin.dashboard') }}">{{ __('All Reservations') }}<span class="badge @if(url()->full() ==  route('admin.dashboard')) badge-white @else badge-info @endif">{{ $allorders ?? \App\Reservation::count() }}</span></a>
          </li>
          <li class="nav-item">
            <a style="font-size: 1.3em;margin-left: 1em;" class="nav-link @if(url()->full() ==  route('admin.dashboard','status=3')) active @endif" href="{{ route('admin.dashboard','status=3') }}">{{ __('Accepted Reservations') }}<span class="badge badge-primary">{{ $accepted ??  \App\Reservation::where('status',3)->count() }}</span></a>
          </li>
          <li class="nav-item">
            <a style="font-size: 1.3em;margin-left: 1em;" class="nav-link @if(url()->full() ==  route('admin.dashboard','status=2')) active @endif" href="{{ route('admin.dashboard','status=2') }}">{{ __('Pending Reservations') }}<span class="badge badge-warning">{{ $pendings ?? \App\Reservation::where('status',2)->count() }}</span></a>
          </li>
          <li class="nav-item">
            <a style="font-size: 1.3em;margin-left: 1em;" class="nav-link @if(url()->full() ==  route('admin.dashboard','status=0')) active @endif" href="{{ route('admin.dashboard','status=0') }}">{{ __('Declined Reservations') }}<span class="badge badge-danger">{{ $declineds ?? \App\Reservation::where('status',0)->count() }}</span></a>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="col-12 mt-2">
    <div class="card">
      <div class="card-body">
        <div class="float-left">
          <form>

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
        </form>
      </div>
      <div class="float-right">
        <form>
          <div class="input-group mt-3 col-12">

            <input type="text" class="form-control" placeholder="{{__('Search By Reservation ID')}}" required="" name="src" value="{{ $src ?? '' }}">
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
            @foreach($orders as $key => $row )
            <tr>
              <td><a href="{{ route('store.order.show',$row->id) }}">#{{ $row->id }}</a></td>
              <td>{{ $row->customerinfo->name }}</td>
              <td>{{ $row->customerinfo->email }}</td>
              <td>{{ $row->customerinfo->phone }}</td>
              <td>{{ $row->person }}</td>
              <td>
                @if($row->status == 2)
                <span class="badge badge-warning"> {{ __('Pending') }} </span>
                @elseif($row->status == 3)
                <span class="badge badge-primary"> {{ __('Accepted') }} </span>
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
        {{ $orders->links() }}
      </div>
    </div>
  </div>
              </div>
 <div class="col-lg-6 col-md-6 col-12">

  <div class="card">
    <div class="card-header">
      <h4 class="d-inline">{{ __('New Resturent Request') }}</h4>
      <div class="card-header-action">
        <a href="{{ url('/admin/resturents/requests') }}" class="btn btn-primary">{{ __('View All') }}</a>
      </div>
    </div>
    <div class="card-body">             
      <ul class="list-unstyled list-unstyled-border">

        @foreach($requestResturent as $row)
        <li class="media">
          <img class="mr-3 rounded-circle" width="50" src="{{ asset($row->avatar) }}" alt="">
          <div class="media-body">
            @if(empty($row->email_verified_at))
            <div class="badge badge-pill badge-danger mb-1 float-right">{{  __('Not Verified') }}</div>
            @else
            <div class="badge badge-pill badge-success mb-1 float-right">{{ __('Verified') }}</div>
            @endif
            <h6 class="media-title"><a href="{{ url('/admin/user',$row->id) }}">{{ $row->name }}</a></h6>
            <div class="text-small text-muted">{{ $row->email }} <div class="bullet"></div> <span class="text-primary">{{ $row->created_at->diffforHumans() }}</span></div>
          </div>
        </li>
        @endforeach
      </ul>
    </div>
  </div>
</div>
<div class="col-lg-6 col-md-6 col-12">

  <div class="card">
    <div class="card-header">
      <h4 class="d-inline">{{ __('New Rider Request') }}</h4>
      <div class="card-header-action">
        <a href="{{ url('/admin/rider/requests') }}" class="btn btn-primary">{{ __('View All') }}</a>
      </div>
    </div>
    <div class="card-body">             
      <ul class="list-unstyled list-unstyled-border">
        @foreach($requestRider as $row)
        <li class="media">
          <img class="mr-3 rounded-circle" width="50" src="{{ asset($row->avatar) }}" alt="">
          <div class="media-body">
            @if(empty($row->email_verified_at))
            <div class="badge badge-pill badge-danger mb-1 float-right">{{  __('Not Verified') }}</div>
            @else
            <div class="badge badge-pill badge-success mb-1 float-right">{{ __('Verified') }}</div>
            @endif
            <h6 class="media-title"><a href="{{ url('/admin/user',$row->id) }}">{{ $row->name }}</a></h6>
            <div class="text-small text-muted">{{ $row->email }} <div class="bullet"></div> <span class="text-primary">{{ $row->created_at->diffforHumans() }}</span></div>
          </div>
        </li>
        @endforeach

      </ul>
    </div>
  </div>
</div>
</div>
<div class="row">
  <div class="col-lg-5 col-md-12 col-12 col-sm-12">
    <form id="basicform" method="post" class="needs-validation" novalidate="" action="{{ route('admin.announcement') }}">
      @csrf
      @php
      $json=json_decode($announcement->value ?? '');

      @endphp
      <div class="card">
        <div class="card-header">
          <h4>{{ __('Announcement') }}</h4>
        </div>
        <div class="card-body pb-0">
          <div class="form-group">
            <label>{{ __('Title') }}</label>
            <input type="text" name="title" class="form-control"  required="" value="{{ $json->title ?? '' }}">
            <div class="invalid-feedback">
              Please fill in the title
            </div>
          </div>
          <div class="form-group">
            <label>{{ __('Message') }}</label>
            <textarea class="form-control" required name="message">{{ $json->message ?? '' }}</textarea>

            <div class="invalid-feedback">
              Please fill in the title
            </div>
          </div>
          <div class="form-group">
            <label>{{ __('Status') }}</label>
            <select class="form-control" name="status">
              <option value="1" @if($announcement->lang ?? '' == 1) selected="" @endif>{{{ __('Show') }}}</option> 
              <option value="0" @if($announcement->lang ?? '' == 0) selected="" @endif>{{ __('Hide') }}</option> 
            </select>
          </div>
        </div>
        <div class="card-footer pt-0">
          <button class="btn btn-primary" type="submit">Save</button>
        </div>
      </div>
    </form>
  </div>
  <div class="col-lg-7 col-md-12 col-12 col-sm-12">
    <div class="card">
      <div class="card-header">
        <h4>{{ __('New Order') }}</h4>
        <div class="card-header-action">
          <a href="{{ url('/admin/order') }}" class="btn btn-primary">View All</a>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped mb-0">
            <thead>
              <tr>
                <th>{{ __('Order Type') }}</th>
                <th>{{ __('Amount') }}</th>
                <th>{{ __('Author') }}</th>
                <th>{{ __('Action') }}</th>
              </tr>
            </thead>
            <tbody>  
              @foreach($newOrders as $row)                       
              <tr>
                <td>
                  @if($row->order_type == 1)
                  <span class="badge badge-success">{{ __('Home Delivery') }}</span>
                  @else
                  <span class="badge badge-success">{{ __('Pickup') }}</span>
                  @endif

                </td>
                <td>{{ number_format($row->total+$row->shipping) }}</td>
                <td>
                  <a href="{{ url('/admin/user',$row->vendor_id) }}" class="font-weight-600"><img src="{{ asset($row->vendor->avatar ?? null) }}" alt="" width="30" class="rounded-circle mr-1"> {{ $row->vendor->name ?? null }}</a>
                </td>
                <td>
                  <a href="{{ url('/admin/order',$row->id) }}" class="btn btn-primary btn-action mr-1"><i class="fas fa-eye"></i></a>

                </td>
              </tr>
              @endforeach

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  
</div>
</div>

</section>
@endsection

@section('script')
<script src="{{ asset('admin/js/form.js') }}"></script>
@endsection