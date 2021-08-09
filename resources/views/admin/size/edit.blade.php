@extends('layouts.backend.app')

@section('content')

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<h4 class="mb-20">{{ __('Edit Size') }}</h4>
				<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-danger none errorarea">
							<ul id="errors">

							</ul>
						</div>
						<form method="post" id="basicform" class="custom-form" action="{{ route('store.size.update',$info->id) }}">
							@csrf
							@method('PUT')
							<div class="custom-form">
								<div class="form-group">
									<label for="name_en">{{ __('Size Name') }} (English)</label>
									<input type="text" name="name_en" id="name_en" class="form-control input-rounded" placeholder="Enter Size Name English" required value="{{ $info->name_en }}">
								</div>

								<div class="form-group">
									<label for="name_ar">{{ __('Size Name') }} (Arabic)</label>
									<input type="text" name="name_ar" id="name_ar" class="form-control input-rounded" placeholder="Enter Size Name English" required value="{{ $info->name_ar }}">
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
</script>
@endsection