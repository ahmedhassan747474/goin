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
    <h4>{{ __('Add New Reservation Image') }}</h4>
    <form method="post" class="basicform" action="{{ route('store.reserve_image.store') }}">
     @csrf
     <div class="custom-form pt-20">

       {{ mediasection() }}

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



</form>

{{ mediasingle() }}
@endsection

@section('script')
<script src="{{ asset('admin/js/form.js') }}"></script>
<script src="{{ asset('admin/js/media.js') }}"></script>
<script>
   "use strict";
  (function ($) {
    $('.use').on('click',function(){

      $('#preview').attr('src',myradiovalue);
      $('#preview_input').val(myradiovalue);
      
    });
  })(jQuery);
   //response will assign this function
   function success(res){
     location.reload();
   }

</script>
@endsection