@extends('layouts.backend.app')

@section('content')

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<h4 class="mb-20">{{ __('Edit Table') }}</h4>
				<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-danger none errorarea">
							<ul id="errors">

							</ul>
						</div>
						<form method="post" id="basicform" class="custom-form" action="{{ route('store.table.upgrade',$info->id) }}">
							@csrf
							@method('PUT')
							<div class="custom-form">
								<div class="form-group">
									<label for="name_en">{{ __('Table Name') }} (English)</label>
									<input type="text" name="name_en" id="name_en" class="form-control input-rounded" placeholder="Enter Table Name English" required value="{{ $info->name_en }}">
								</div>

								<div class="form-group">
									<label for="name_ar">{{ __('Table Name') }} (Arabic)</label>
									<input type="text" name="name_ar" id="name_ar" class="form-control input-rounded" placeholder="Enter Table Name English" required value="{{ $info->name_ar }}">
								</div>
								
								<div class="form-group">
        							<label for="no_guest">{{ __('Number of Guest') }}</label>
        							<input type="text" name="no_guest" class="form-control" id="no_guest" placeholder="Enter Number of Guest" required value="{{ $info->no_guest }}">
        						</div>
        						
        						<div class="form-group">
        							<label for="status">{{ __('Status') }}</label>
                                    <select class="form-control" name="status">
                                        <option value="0" {{ $info->status == 0 ? 'selected' : '' }}>{{ __('Offline') }}</option>
                                        <option value="1" {{ $info->status == 1 ? 'selected' : '' }}>{{ __('Online') }}</option>
                                        <option value="2" {{ $info->status == 2 ? 'selected' : '' }}>{{ __('Busy') }}</option>
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
</script>
@endsection