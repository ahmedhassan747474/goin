@extends('layouts.backend.app')

@section('style')
@endsection

@section('content')
<div class="row">
  <div class="col-lg-9">      
    <div class="card">
      <div class="card-body">
        <h4>{{ __('Add new Restaurant') }}</h4>
        <form method="post" action="{{ route('admin.vendor.store') }}" id="basicform">
          @csrf
          <div class="pt-20">

            <div class="form-group">
              <label>{{ __('Name') }}</label>
              <input type="text" placeholder="{{__('Enter Name')}}" name ="name" class="form-control" autocomplete="off" value="" required="">
            </div>

            <div class="form-group">
              <label>{{ __('Email') }}</label>
              <input type="email" placeholder="{{__('Enter Email')}}" name ="email" class="form-control" autocomplete="off" value="" required>
            </div>

            <div class="form-group">
              <label>{{ __('Password') }}</label>
              <input type="password" placeholder="{{__('Enter Password')}}" name ="password" class="form-control" autocomplete="off" minlength="8">
            </div>

            <div class="form-group">
              <label for="delivery">{{ __('Delivery Time') }}</label>
              <input type="number" placeholder="{{__('Enter Maximum Delivery Time')}}" name ="delivery" class="form-control" id="delivery" required value="" autocomplete="off">
            </div>

            <div class="form-group">
              <label for="pickup">{{ __('Pick Up Time') }}</label>
              <input type="number" placeholder="{{__('Enter Maximum Pick Up Time')}}" name ="delivery" class="form-control" id="pickup" required value="" autocomplete="off">
            </div>

            <div class="form-group">
              <label for="description">{{ __('Shop Description') }}</label>
              <textarea name="description" class="form-control" cols="30" rows="3" placeholder="{{__('Shop Description')}}" id="description" maxlength="250" required=""></textarea>
            </div>

            <div class="form-group">
              <label for="phone1">{{ __('Support Phone Number 1') }}</label>
              <input type="number" placeholder="{{__('Support Phone Number')}}" name ="phone1" class="form-control" id="phone1" required value="" autocomplete="off">
            </div>

            <div class="form-group">
              <label for="phone2">{{ __('Support Phone Number 2') }}</label>
              <input type="number" placeholder="{{__('Support Phone Number')}}" name ="phone2" class="form-control" id="phone2" required value="" autocomplete="off">
            </div>

            <div class="form-group">
              <label for="email1">{{ __('Support Email 1') }}</label>
              <input type="email" placeholder="{{__('Support Email')}}" name ="email1" class="form-control" id="email1" required value="" autocomplete="off">
            </div>

            <div class="form-group">
              <label for="email2">{{ __('Support Email 2') }}</label>
              <input type="email" placeholder="{{__('Support Email')}}" name ="email2" class="form-control" id="email2" required value="" autocomplete="off">
            </div>

            <div class="form-group">
              <label for="address_line">{{ __('Address Line') }}</label>
              <input type="text" placeholder="{{__('Address Line')}}" name  ="address_line" class="form-control" id="address_line" required value="" autocomplete="off">
            </div>

            <div class="form-group">
              <label >{{ __('Select Your City') }}</label>
              <select class="form-control" name ="city" >
                @php
                $locations=\App\Terms::where('type',2)->where('status',1)->get();
                @endphp
                 
                @foreach($locations as $key => $row)
                <option value="{{ $row->id }}">{{ $row->title_en }}</option>
                @endforeach
             </select>
            </div>

            <div class="form-group">
              <label for="location_input">{{ __('Full address') }}</label>
              <input type="text" placeholder="{{__('Enter Full Address')}}" name ="full_address" class="form-control" id="location_input" required value="" autocomplete="off">
            </div>

            <label>{{ __('Drag Your Address') }}</label>
            <div id="map-canvas" class="map-canvas"></div>

            <input type="hidden')}}" name ="latitude" id="latitude" value="00.00">
            <input type="hidden')}}" name ="longitude" id="longitude" value="00.00">

          </div>

        </div>
      </div>

    </div>
    <div class="col-lg-3">

      <div class="single-area">
        <div class="card sub">
          <div class="card-body">
            <h5>{{ __('Publish') }}</h5>
            <hr>
            <div class="btn-publish">
              <button type="submit" class="btn btn-primary col-12"><i class="fa fa-save"></i> {{ __('Save') }}</button>
            </div>
          </div>
        </div>
      </div>

      <div class="single-area">
        <div class="card sub">
            <div class="card-body">
                <h5><a href="#" data-toggle="modal" data-target=".media-single" class="text-dark">{{ __('Shop Banner Image') }}</a></h5>
                <hr>
                <a href="#" data-toggle="modal" data-target=".media-single" class="single-modal">
                    <img class="img-fluid" id="preview" src="{{ asset('admin/img/img/placeholder.png') }}">
                </a>
            </div>
        </div>
      </div>
      <input type="hidden" id="preview_input" class="input_image')}}" name ="preview" value="">

      @php
      $usercat=[];
      @endphp

      <div class="single-area">
        <div class="card sub">
          <div class="card-body">
            <h5>{{ __('Shop Tags') }}</h5>
            <hr>
            <div class="scroll-bar-wrap">
              <div class="category-list">
                <?php echo AdminCategoryUpdate(2, $usercat); ?>
                <div class="cover-bar"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="single-area">
        <div class="card sub">
          <div class="card-body">
            <h5><a href="#" data-toggle="modal" data-target=".media-multiple" class="multi-modal">
            {{ __('Shop Gallery Image') }}</a></h5>
            <hr>
            <a href="#" data-toggle="modal" data-target=".media-multiple" class="single-modal">
              
              @php
              $preview = '';
              @endphp

              @if(empty($preview))  
              <img class="img-fluid" id="gallery" src="{{ asset('admin/img/img/placeholder.png') }}">
              @else
              
              @foreach($preview ?? [] as $row)
              <img class="gallary-src" height="80" src="{{ asset($row) }}"/>
              @endforeach
              @endif
              <div id="gallary-img"></div>
            </a>
          </div>
        </div>
      </div>
      <input type="hidden" id="gallary_input" class="input_image')}}" name ="gallary_input" value="">

    </div>
  </form>

  {{ mediasingle() }}
  {{ mediamulti() }}
@endsection
@section('script')
<script src="{{ asset('admin/js/form.js') }}"></script>
<script src="{{ asset('admin/js/media.js') }}"></script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('PLACE_KEY') }}&libraries=places&callback=initialize"></script>
<script src="{{ theme_asset('khana/public/js/information.js') }}"></script>
<script>
   "use strict";
  (function ($) {
      $('.use').on('click',function(){

      $('#preview').attr('src',myradiovalue);
      $('#preview_input').val(myradiovalue);
      
    });

   $('.use1').on('click',function(){
      $('.multi-img').hide();
      $('.gallary-src').remove();
      $('#gallery').remove()

      $.each(mycheckboxvalue, function(index, value){
        $("#gallary-img").append('<img class="gallary-src" height="80" src="' + value + '" />');
      });
      $('#gallery').remove()
      $('#gallary_input').val(mycheckboxvalue.toString())

    });
  })(jQuery);

</script>
@endsection
