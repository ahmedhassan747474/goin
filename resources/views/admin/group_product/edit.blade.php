@extends('layouts.backend.app')

@section('content')

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<h4 class="mb-20">{{ __('Edit Group Product') }}</h4>
				<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-danger none errorarea">
							<ul id="errors">

							</ul>
						</div>
						<form method="post" id="basicform" class="custom-form" action="{{ route('admin.group.product.update',$info->id) }}">
							@csrf
							@method('PUT')
							<div class="custom-form">
								<div class="form-group">
									<label for="exampleFormControlSelect1">{{ __('Select Products') }}</label>
								    <select class="form-control js-example-basic-multiple" name="items[]" id="exampleFormControlSelect1" multiple="multiple" style="width:100%">
								    	@foreach($items as $item)
								      	<option value="{{$item->id}}" {{$item->is_selected == 1 ? 'selected' : ''}}>({{$item->title_en ? $item->title_en : 'Not Exist'}} / {{$item->title_ar ? $item->title_ar : 'لا يوجد'}}) - {{$item->rest_name}}</option>
								      	@endforeach
								    </select>
									
								</div>
								<button class="btn btn-success col-12 mt-15">{{ __('Update') }}</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script src="{{ asset('admin/js/form.js') }}"></script>
<script>
	"use strict";	
	function success(res){
		location.reload();
	}
	function errosresponse(xhr){
		$("#errors").html("<li class='text-danger'>"+xhr.responseJSON[0]+"</li>")
	}

	$(document).ready(function() {
	    $('.js-example-basic-multiple').select2();
	});
</script>
@endsection