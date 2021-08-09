@section('style')

@endsection
@extends('layouts.backend.app')

@section('content')

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<h4 class="mb-20">{{ __('Edit Menu') }}</h4>
				<div class="row">
					<div class="col-lg-12">
						
						<form method="post" id="basicform" class="custom-form" action="{{ route('store.menu.update',$info->id) }}">
							@csrf
							@method('PUT')
							<div class="custom-form">
								<div class="form-group">
									<label for="name">{{ __('Menu Name') }} (English)</label>
									<input type="text" name="name_en" id="name_en" class="form-control input-rounded" placeholder="Enter Menu Name English" required value="{{ $info->name_en }}">
								</div>

								<div class="form-group">
									<label for="name">{{ __('Menu Name') }} (Arabic)</label>
									<input type="text" name="name_ar" id="name_ar" class="form-control input-rounded" placeholder="Enter Menu Name Arabic" required value="{{ $info->name_ar }}">
								</div>
								
								<div class="form-group">
									<label for="p_id">{{ __('Parent Menu') }}</label>
									<select class="custom-select mr-sm-2" name="p_id" id="p_id">
										<option value="">None</option>
										<?php echo ConfigCategory(1,$info->p_id) ?>
										
									</select>
								</div>

								<?php 

								if(!empty($info->preview)){
								  
								    $media['preview'] = $info->preview->content;
								    $media['value'] = $info->preview->content;
								    echo  mediasection($media);
								    
								  
								  
								}
								else{
								 echo mediasection();
								}

								?>

								<button class="btn btn-primary col-12 mt-15">{{ __('Update') }}</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
{{ mediasingle() }}
@endsection

@section('script')
<script src="{{ asset('admin/js/form.js') }}"></script>
<script src="{{ asset('admin/js/media.js') }}"></script>

<script>
	"use strict";
	function errosresponse(xhr){
		$("#errors").html("<li class='text-danger'>"+xhr.responseJSON[0]+"</li>")
	}
	(function ($) {
	    $('.use').on('click',function(){
	      	$('#preview').attr('src',myradiovalue);
	      	$('#preview_input').val(myradiovalue);
	    });
  	})(jQuery);
</script>
@endsection