@extends('layouts.backend.app')
@section('content')
<div class="row">
 <div class="col-lg-9">      
  <div class="card">
   <div class="card-body">
    
    <h4>{{ __('Edit Image') }}</h4>
    <form method="post" class="basicform" action="{{ route('store.table_image.update',$info->id) }}">
     @csrf
     @method('PUT')
      <div class="custom-form pt-20">

      @php

        $media['preview'] = $info->image;
        $media['value'] = $info->image;
        echo  mediasection($media);

      @endphp

      </div>
   </div>
 </div>

</div>
<div class="col-lg-3">

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
    // location.reload();
   }

</script>
@endsection