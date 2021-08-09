@extends('layouts.backend.app')

@section('content')
<div class="row" id="category_body">
	<div class="col-lg-4">      
		<div class="card">
			<div class="card-body">
				<form id="basicform" method="post" action="{{ route('admin.group.store') }}">
					@csrf
					<div class="custom-form">
						<div class="form-group">
							<label for="name">{{ __('Name') }}</label>
							<input type="text" name="name" class="form-control" id="name" placeholder="Group Name">
						</div>
						<div class="form-group mt-20">
							<button class="btn btn-primary col-12" type="submit">{{ __('Add New Group') }}</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-lg-8" >      
		<div class="card">
			<div class="card-body">
				@php
				if (!empty($req)) {
					$groups=App\Group::where('name','LIKE','%'.$req.'%' )->latest()->paginate(12);	
				}
				else{
					$groups=App\Group::latest()->paginate(12);
				}
				@endphp
				<div class="table-responsive">
					<div class="card-action-filter">
						<form>
						<input type="text" class="form-control" name="query" required>
						</form>
						<form id="basicform1" method="post" action="{{ route('admin.group.destroy') }}">
							@csrf
							<div class="card-filter-content">
								<div class="single-filter">
									<div class="form-group">
										<select class="form-control" name="method">
											<option value="delete">{{ __('Delete Permanently') }}</option>
										</select>
									</div>
								</div>
								<div class="single-filter">
									<button type="submit" class="btn">{{ __('Apply') }}</button>
								</div>
							</div>
						</div>
						<table class="table category">
							<thead>
								<tr>
									<th class="am-select">
										<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input checkAll" id="checkAll">
											<label class="custom-control-label" for="checkAll"></label>
										</div>
									</th>
									<th class="am-title">{{ __('Title') }}</th>

								</tr>
							</thead>
							<tbody>
								@foreach($groups as $group)
								<tr>
									<th>
										<div class="custom-control custom-checkbox">
											<input type="checkbox" name="ids[]" class="custom-control-input" id="customCheck{{ $group->id }}" value="{{ $group->id }}">
											<label class="custom-control-label" for="customCheck{{ $group->id }}"></label>
										</div>
									</th>
									<td>
										{{ $group->name }}
										<div class="hover">
											<a href="{{ route('admin.group.edit',$group->id) }}">{{ __('Edit') }}</a>
										</div>
									</td>
								</tr>
								@endforeach

							</tbody>
						</form>	
						<tfoot>
							<tr>
								<th class="am-select">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="custom-control-input checkAll" id="checkAll">
										<label class="custom-control-label" for="checkAll"></label>
									</div>
								</th>
								<th class="am-title">{{ __('Title') }}</th>
							</tr>
						</tfoot>
					</table>
					<div class="f-right">{{ $groups->links() }}</div>
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
	function success(res){
		location.reload();
	}
</script>
@endsection