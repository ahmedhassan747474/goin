@extends('layouts.backend.app')
@section('content')
<div class="row">
 <div class="col-lg-9">      
  <div class="card">
   <div class="card-body">
     <div class="alert alert-danger none errorarea">
      <ul id="errors">

      </ul>
    </div>
    <h4>{{ __('Add new product') }}</h4>
    <form method="post" class="basicform" action="{{ route('store.product.store') }}">
     @csrf
     <div class="custom-form pt-20">

       @php
       $arr['title']= 'Product Name (English)';
       $arr['id']= 'name';
       $arr['type']= 'text';
       $arr['placeholder']= 'Product Title';
       $arr['name']= 'title_en';
       $arr['is_required'] = true;

       echo  input($arr);

       $arr['title']= 'Product Name (Arabic)';
       $arr['id']= 'name';
       $arr['type']= 'text';
       $arr['placeholder']= 'Product Title';
       $arr['name']= 'title_ar';
       $arr['is_required'] = true;

       echo  input($arr);

       $arr['title']= 'Description (English)';
       $arr['id']= 'description';
       $arr['placeholder']= 'description';
       $arr['name']= 'description_en';
       $arr['is_required'] = true;

       echo  textarea($arr);

       $arr['title']= 'Description (Arabic)';
       $arr['id']= 'description';
       $arr['placeholder']= 'description';
       $arr['name']= 'description_ar';
       $arr['is_required'] = true;

       echo  textarea($arr);

       @endphp

      <!--  <div class="form-group">
        <label for="price">Price</label>
        <input type="text" placeholder="Product Price" name="price" class="form-control" id="price" required="" value="" autocomplete="off">
      </div> -->

      <!-- @foreach($sizes as $size)
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroup-sizing-default">{{ $size->name_en }}</span>
        </div>
        <input type="hidden" name="size[]" value="{{$size->id}}">
        <input type="number" min="1" step="0.01" class="form-control" name="price[]" placeholder="Product Price" autocomplete="off" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
      </div>
      @endforeach -->

      <div class="add_more">
        <div class="row">

          <div class="form-group mb-2 col-3">
            <label for="staticEmail2" class="sr-only">Size Name (English)</label>
            <input type="text" class="form-control" name="size_name_en[]" id="staticEmail2" placeholder="Size Name English" value="" required>
          </div>

          <div class="form-group mb-2 col-3">
            <label for="staticEmail2" class="sr-only">Size Name (Arabic)</label>
            <input type="text" class="form-control" name="size_name_ar[]" id="staticEmail2" placeholder="Size Name Arabic" value="" required>
          </div>

          <div class="form-group mb-2 col-2">
            <label for="staticEmail2" class="sr-only">Calories</label>
            <input type="text" class="form-control" name="calories[]" id="calories" placeholder="Calories" value="" required>
          </div>

          <div class="form-group mb-2 col-2">
            <label for="inputPassword2" class="sr-only">Price</label>
            <input type="number" min="1" step="0.01" class="form-control" name="size_price[]" id="inputPassword2" placeholder="Price" required>
          </div>

          <div class="form-group mb-2 col-2">
            <button type="button" id="more" class="btn btn-primary btn-block py-2">Add More</button>
          </div>

        </div>
      </div>

      <div class="form-group">
        <label for="discount">Discount</label>
        <input type="number" placeholder="Product Discount" min="1" max="100" step="0.01" name="discount" class="form-control" id="discount" value="" autocomplete="off">
      </div>

       @php
       $arr['title']= 'Excerpt';
       $arr['id']= 'excerpt';
       $arr['placeholder']= 'short description';
       $arr['name']= 'excerpt';
       $arr['is_required'] = false;

       echo  textarea($arr);


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
<div class="single-area">
 <div class="card sub">
  <div class="card-body">
   <h5>{{ __('Status') }}</h5>
   <hr>
   <select class="custom-select mr-sm-2" id="inlineFormCustomSelect" name="status">
    <option value="1">{{ __('Published') }}</option>
    <option value="2">{{ __('Draft') }}</option>

  </select>
</div>
</div>
</div>

<div class="single-area">
 <div class="card sub">
  <div class="card-body">
   <h5>{{ __('Categories') }}</h5>
   <hr>
   <div class="scroll-bar-wrap">
     <div class="category-list">
       {{ AdminCategory(1) }}
       <div class="cover-bar"></div>
     </div>
   </div>
 </div>
</div>
</div>


{{ mediasection() }}

@if(count($addons) > 0)
<div class="single-area">
 <div class="card sub">
  <div class="card-body">
   <h5>{{ __('Addon Product') }}</h5>
   <hr>
   <div class="scroll-bar-wrap">
     <div class="category-list">
      @foreach($addons as $key => $addon)
       <div class="custom-control custom-checkbox"><input type="checkbox" name="addon[]" class="custom-control-input" value="{{ $addon->id }}" id="addon{{ $addon->id }}">
        <label class="custom-control-label" for="addon{{ $addon->id }}">{{ $addon->title_en }}
        </label>
      </div>
      @endforeach
    </div>
  </div>
</div>
</div>
</div>
@endif

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


   // More Size - Price
    var i = 0;
    var div = '';
    $('#more').on('click', function () {
      div += '<div class="row row_id_'+i+'">';

        div += '<div class="form-group mb-2 col-3">';
          div += '<label for="staticEmail2" class="sr-only">Size Name (English)</label>';
          div += '<input type="text" class="form-control" name="size_name_en[]" id="staticEmail2" placeholder="Size Name English" value="" required>';
        div += '</div>';

        div += '<div class="form-group mb-2 col-3">';
          div += '<label for="staticEmail2" class="sr-only">Size Name (Arabic)</label>';
          div += '<input type="text" class="form-control" name="size_name_ar[]" id="staticEmail2" placeholder="Size Name Arabic" value="" required>';
        div += '</div>';

        div += '<div class="form-group mb-2 col-2">';
          div += '<label for="staticEmail2" class="sr-only">Calories</label>';
          div += '<input type="text" class="form-control" name="calories[]" id="calories" placeholder="Calories" value="" required>';
        div += '</div>';

        div += '<div class="form-group mb-2 col-2">';
          div += '<label for="inputPassword2" class="sr-only">Price</label>';
          div += '<input type="number" min="1" step="0.01" class="form-control" name="size_price[]" id="inputPassword2" placeholder="Price" required>';
        div += '</div>';

        div += '<div class="form-group mb-2 col-2">';
          div += '<button type="button" data-id="' + i + '" class="btn btn-danger btn-block py-2 deleteMore">Delete</button>';
        div += '</div>';
        
      div += '</div>';

      $('.add_more').append(div);

      div = '';
      i++;
    });

    $(document).on('click', '.deleteMore', function(){  
        var button_id = $(this).data("id"); 
        $('.row_id_'+button_id+'').remove();
    }); 

</script>
@endsection