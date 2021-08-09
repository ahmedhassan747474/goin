@extends('layouts.backend.app')
@section('content')
<div class="row">
 <div class="col-lg-8">      
  <div class="card">
   <div class="card-body">
     <div class="alert alert-danger none errorarea">
      <ul id="errors">

      </ul>
    </div>
    <h4>{{ __('Make Reservation') }}</h4>
    <form method="post" class="basicform" action="{{ route('admin.reservation.store') }}">
     @csrf
     <div class="custom-form pt-20">

      <div class="form-group">
        <label for="date">{{ __('Enter Date') }}</label>
        <input type="date" name="date" id="date" class="form-control input-rounded" placeholder="Enter Date" required value="">
      </div>

      <div class="form-group">
        <label for="time">{{ __('Enter Time') }}</label>
        <input type="time" name="time" id="time" class="form-control input-rounded" placeholder="Enter Time" required value="">
      </div>

      <div class="form-group">
        <label for="person">{{ __('Enter Number Of Person') }}</label>
        <input type="number" min="1" name="person" id="person" class="form-control input-rounded" placeholder="Enter Number Of Person" required value="">
      </div>

      <div class="form-group">
        <label for="message">{{ __('Enter Message Or Note') }} (Optional)</label>
        <textarea name="message" class="form-control" cols="10" rows="6" placeholder="Enter Message" id="message"></textarea>
      </div>

     </div>
   </div>
 </div>

</div>
<div class="col-lg-4">
  <div class="single-area">
   <div class="card">
    <div class="card-body">
     <h5>{{ __('Publish') }}</h5>
     <hr>
     <div class="btn-publish">
      <button type="submit" class="btn btn-primary col-12 basicbtn"><i class="fa fa-save"></i> {{ __('Save') }}</button>
    </div>
  </div>
</div>
</div>

<div class="single-area">
  <div class="card sub">
    <div class="card-body">
      <h5>{{ __('Select Restaurant') }}</h5>
      <hr>
      <select class="custom-select mr-sm-2 select_restaurant" id="inlineFormCustomSelect" name="vendor_id" required="">
        <option value="">{{ __('Select Restaurant') }}</option>
        @foreach($restaurants as $restaurant)
        <option value="{{$restaurant->id}}">{{$restaurant->name}}</option>
        @endforeach
      </select>
    </div>
  </div>
</div>

<div class="single-area">
  <div class="card sub">
    <div class="card-body">
      <h5>{{ __('Select Customer') }}</h5>
      <hr>
      <select class="custom-select mr-sm-2 select_product" id="inlineFormCustomSelect" name="user_id">
        <option value="">{{ __('Select Customer') }}</option>
        @foreach($users as $user)
        <option value="{{$user->id}}">{{$user->name}}</option>
        @endforeach
      </select>
    </div>
  </div>
</div>



</form>

@endsection

@section('script')
<script src="{{ asset('admin/js/form.js') }}"></script>
<script type="text/javascript">
"use strict"; 
  //success response will assign this function
 function success(res){
  location.reload();
 }
 function errosresponse(xhr){

  $("#errors").html("<li class='text-danger'>"+xhr.responseJSON[0]+"</li>")
 }
</script>
@endsection