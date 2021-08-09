@extends('layouts.backend.app')
@section('content')
<div class="row">

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
            <a class="nav-link @if(url()->full() ==  route('store.reservation.index')) active @endif" href="{{ route('store.reservation.index') }}">{{ __('All Reservations') }}<span class="badge @if(url()->full() ==  route('store.reservation.index')) badge-white @else badge-info @endif">{{ $allorders ?? \App\Reservation::where('vendor_id',Auth::id())->count() }}</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link @if(url()->full() ==  route('store.reservation.index','status=3')) active @endif" href="{{ route('store.reservation.index','status=3') }}">{{ __('Accepted Reservations') }}<span class="badge badge-primary">{{ $accepted ??  \App\Reservation::where('vendor_id',Auth::id())->where('status',3)->count() }}</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link @if(url()->full() ==  route('store.reservation.index','status=2')) active @endif" href="{{ route('store.reservation.index','status=2') }}">{{ __('Pending Reservations') }}<span class="badge badge-warning">{{ $pendings ?? \App\Reservation::where('vendor_id',Auth::id())->where('status',2)->count() }}</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link @if(url()->full() ==  route('store.reservation.index','status=0')) active @endif" href="{{ route('store.reservation.index','status=0') }}">{{ __('Declined Reservations') }}<span class="badge badge-danger">{{ $declineds ?? \App\Reservation::where('vendor_id',Auth::id())->where('status',0)->count() }}</span></a>
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
        {{ $orders->links() }}
      </div>
    </div>
  </div>
</div>
</div>
@endsection

@section('script')
@endsection
