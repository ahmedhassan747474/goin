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
    <h4>{{ __('Add new slider') }}</h4>
    <form method="post" class="basicform" action="{{ route('admin.slider.store') }}">
     @csrf
     <div class="custom-form pt-20">

       @php
       $arr['title']= __('Slider Name (English)');
       $arr['id']= 'name';
       $arr['type']= 'text';
       $arr['placeholder']= __('Slider Title English');
       $arr['name']= 'title_en';
       $arr['is_required'] = true;

       echo  input($arr);

       $arr['title']= __('Slider Name (Arabic)');
       $arr['id']= 'name';
       $arr['type']= 'text';
       $arr['placeholder']= __('Slider Title Arabic');
       $arr['name']= 'title_ar';
       $arr['is_required'] = true;

       echo  input($arr);

       @endphp

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

<div class="single-area">
  <div class="card sub">
    <div class="card-body">
      <h5>{{ __('Select Restaurant') }}</h5>
      <hr>
      <select class="custom-select mr-sm-2 select_restaurant" id="inlineFormCustomSelect" name="restaurant_id">
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
      <h5>{{ __('Select Product') }}</h5>
      <hr>
      <select class="custom-select mr-sm-2 select_product" id="inlineFormCustomSelect" name="product_id">
        <option value="">{{ __('Select Product') }}</option>
      </select>
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

   $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Get Products From Restaurant ID
    $('.select_restaurant').on('click', function() {
        var restaurant = $(this).val();
        $.ajax({
            type: "POST",
            dataType: "json",
            url: '{{route('admin.get_products')}}',
            data: {'restaurant_id': restaurant},
            success: function(data){
                var append = "<option value='' selected disabled>{{ __('Select Product') }}</option>";
                for (var i = 0; i < data.length; i++) {
                    append += "<option value='"+ data[i].id +"'>"+ data[i].title_en +"</option>"
                }
                $('.select_product').html(append);
            }
        });
    })

</script>
@endsection