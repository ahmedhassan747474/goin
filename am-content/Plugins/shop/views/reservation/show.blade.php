@extends('layouts.backend.app')
@section('content')
<div class="section-body">
  
  <div class="row">
    <div class="col-12 col-md-12 col-lg-7">
      <div class="card">

        <div class="card-header">
          <h4>{{ __('Reservations') }}</h4>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover text-center">
              <thead>
                <tr>
                  <th scope="col">{{ __('Reservation No') }}</th>
                  <th scope="col">{{ __('Name') }}</th>
                  <th scope="col">{{ __('Email') }}</th>
                  <th scope="col">{{ __('Phone') }}</th>
                </tr>
              </thead>
              <tbody>

                <tr>
                  <td>#{{ $info->id ?? '' }}</td>
                  <td>{{ $info->customerinfo->name ?? '' }}</td>
                  <td>{{ $info->customerinfo->email ?? '' }}</td>
                  <td>{{ $info->customerinfo->phone ?? '' }}</td>
                </tr>

              </tbody>
            </table>
          </div>
        </div>
        @if($info->status != 0)
        <div class="card-footer">
          <form method="post" id="basicform" action="{{ route('store.reservation.update',$info->id) }}">
            @csrf
            @method('PUT')
            <div class="row">
              <div class="form-group col-lg-12">
                @if($info->status == 2)
                <label>{{ __('Reservation Status') }}</label>
                <select class="form-control" name="status">
                  <option value="3">Accept Reservation</option>
                  <option value="0">Decline Reservation</option>
                </select>
                @endif
              </div>
            </div>

            @if($info->status == 2)
            <button type="submit" class="btn btn-primary col-12 submit-btn">{{ __('Processed') }}</button>
            @endif
          </form>

          </div>
          @endif
        </div>

        <div class="card">
          <div class="card-header">
            <h5 class="text-primary text-center">{{ __('Reservation Log') }}</h5>
          </div>
          <div class="card-body">

            <div class="activities">

              <div class="activity">
                <div class="activity-icon bg-primary text-white shadow-primary">
                  <i class="far fa-paper-plane"></i>
                </div>
                <div class="activity-detail">
                  <div class="mb-2">
                    <span class="text-job text-primary">{{ $info->created_at->diffForHumans() }}</span>
                    <span class="bullet"></span>

                  </div>
                  <p>{{ __('Reservation Created') }}</p>
                </div>
              </div>

              @foreach($info->reservationlog ?? []  as $key => $row)

              <div class="activity">
                <div class="activity-icon bg-primary text-white shadow-primary">
                 @if($row->status == 3)
                 <i class="fas fa-comment-alt"></i>
                 @elseif($row->status == 2)
                 <i class="far fa-paper-plane"></i>
                 @elseif($row->status == 0)
                 <i class="fas fa-ban"></i>
                 @endif
               </div>
               <div class="activity-detail">
                <div class="mb-2">
                  <span class="text-job text-primary">{{ $row->created_at->diffForHumans() }}</span>              
                </div>
                @if($row->status == 3)
                <p class="text-warning">{{ __('Order Accepted') }} </p>
                @elseif($row->status == 2)
                <p class="text-primary">{{ __('Order Created') }} </p>
                @elseif($row->status == 0)
                <p class="text-danger">{{ __('Order Cancelled') }} </p>
                @endif
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-12 col-lg-5">
      <div class="card">

        <div class="card-body">

          <div class="profile-widget">

            <div class="profile-widget-header">                     

              <div class="profile-widget-items">
                <div class="profile-widget-item">
                  <div class="profile-widget-item-label">{{ __('Person') }}</div>
                  <div class="profile-widget-item-value">{{ $info->person }}</div>
                </div>
                <div class="profile-widget-item">
                  <div class="profile-widget-item-label">{{ __('Date') }}</div>
                  <div class="profile-widget-item-value">{{ $info->date }}</div>
                </div>
                <div class="profile-widget-item">
                  <div class="profile-widget-item-label">{{ __('Time') }}</div>
                  <div class="profile-widget-item-value">{{ $info->time }}</div>

                 </div>
                 <div class="profile-widget-item">
                  <div class="profile-widget-item-label">{{ __('Reservation Status') }}</div>
                  <div class="profile-widget-item-value">@if($info->status == 0)
                   <span class="text-danger">{{ __('Cancelled') }}</span>
                   @elseif($info->status == 2) <span class="text-warning">{{ __('Pending') }}</span> 
                   @elseif($info->status == 3) <span class="text-primary">{{ __('Accepted') }}</span> 
                   @endif
                 </div>

               </div>
             </div>
           </div>
           @php
           $customerInfo=json_decode($info->data);

           @endphp
           <div class="profile-widget-description">
            <div class="profile-widget-name">{{ __('Customer Name') }}: <div class="text-muted d-inline font-weight-normal"> {{ $info->customerInfo->name }}</div></div>
            <div class="profile-widget-name">{{ __('Customer Phone') }}: <div class="text-muted d-inline font-weight-normal"> {{ $info->customerInfo->phone }}</div></div>
            <div class="font-weight-bold mb-2">{{ __('Reservation Note') }}</div>
            {{ $info->message }}
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
   //response will assign this function
   function success(res){
    $('.submit-btn').remove();
  }

</script>

@endsection