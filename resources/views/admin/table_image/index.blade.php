@extends('layouts.backend.app')
@section('content')
@include('layouts.backend.partials.headersection',['title'=> __('All Image Files') ])
<div class="row">
	<div class="col-12 mt-2">
		<div class="card">
			<div class="card-body">
				<div class="float-left">
					<div class="d-flex">
						<div class="single-filter">
							<a href="{{route('store.table_image.create', $table_id)}}" class="btn btn-primary m-2 ml-1">{{ __('Add Image') }}</a>
						</div>
					</div>
				</div>
				<form id="basicform" method="post" action="{{ route('store.table_image.destroy') }}">
				@csrf
				<div class="float-left">
					<div class="d-flex">
						<div class="single-filter mt-1">
							<button type="submit" class="btn btn-primary mt-1 ml-2">{{ __('Delete') }}</button>
						</div>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-striped table-hover text-center table-borderless">
						<thead>
							<tr>
								<th class="am-select">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="custom-control-input checkAll" id="checkAll">
										<label class="custom-control-label" for="checkAll"></label>
									</div>
								</th>
								<th>{{ __('File') }}</th>
							</tr>
						</thead>
						<tbody>
							@foreach($sliders as $row)
							<tr>
								<th>
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="id[]" class="custom-control-input" id="customCheck{{ $row->id }}" value="{{ $row->id }}">
										<label class="custom-control-label" for="customCheck{{ $row->id }}"></label>
									</div>
								</th>
								<td>
								    <img src="{{ asset($row->image) }}" height="50">
								    <div class="hover">
									<a href="{{ route('store.table_image.edit',$row->id) }}">{{ __('Edit') }}</a>

									<a href="{{ route('store.table_image.edit',$row->id) }}" class="last">{{ __('View') }}</a>
								</td>
							</tr>
							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<th class="am-select">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="custom-control-input checkAll" id="checkAll">
										<label class="custom-control-label" for="checkAll"></label>
									</div>
								</th>
								<th>{{ __('File') }}</th>
							</tr>
						</tfoot>
					</table>
					{{ $sliders->links() }}
				</div>
			</div>
		</div>
	</div>
</div>
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