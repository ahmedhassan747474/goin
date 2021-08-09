@extends('layouts.backend.app')

@section('content')
<div class="row" id="category_body">
	<div class="col-lg-4">
		<div class="card">
			<div class="card-body">
				<form id="basicform" method="post" action="{{ route('store.table.insert') }}">
					@csrf
					<div class="custom-form">
						<div class="form-group">
							<label for="name_en">{{ __('Name') }} (English)</label>
							<input type="text" name="name_en" class="form-control" id="name_en" placeholder="Size Name English">
						</div>

						<div class="form-group">
							<label for="name_ar">{{ __('Name') }} (Arabic)</label>
							<input type="text" name="name_ar" class="form-control" id="name_ar" placeholder="Size Name Arabic">
						</div>

						<div class="form-group">
							<label for="no_guest">{{ __('Number of Guest') }}</label>
							<input type="text" name="no_guest" class="form-control" id="no_guest" placeholder="Enter Number of Guest">
						</div>

						<div class="form-group">
							<label for="status">{{ __('Status') }}</label>
                            <select class="form-control" name="status">
                                <option value="0">{{ __('Offline') }}</option>
                                <option value="1">{{ __('Online') }}</option>
                                <option value="2">{{ __('Busy') }}</option>
                            </select>
						</div>

						<div class="form-group mt-20">
							<button class="btn btn-primary col-12" type="submit">{{ __('Add New Table') }}</button>
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
					$tables=App\Table::where('name_en','LIKE','%'.$req.'%' )->orWhere('name_ar','LIKE','%'.$req.'%' )->latest()->paginate(12);
				}
				else{
					$tables=App\Table::latest()->paginate(12);
				}
				@endphp
				<div class="table-responsive">
					<div class="card-action-filter">
						<form>
						<input type="text" class="form-control" name="query" required>
						</form>
						<form id="basicform1" method="post" action="{{ route('store.table.delete') }}">
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
									<th class="am-title">{{ __('Number of Guest') }}</th>
									<th class="am-title">{{ __('Status') }}</th>

								</tr>
							</thead>
							<tbody>
								@foreach($tables as $table)
								<tr>
									<th>
										<div class="custom-control custom-checkbox">
											<input type="checkbox" name="ids[]" class="custom-control-input" id="customCheck{{ $table->id }}" value="{{ $table->id }}">
											<label class="custom-control-label" for="customCheck{{ $table->id }}"></label>
										</div>
									</th>
									<td>
										{{ $table->name_en }}
										<div class="hover">
											<a href="{{ route('store.table.editable',$table->id) }}">{{ __('Edit') }}</a>
											<a href="{{ route('store.table_image.index',$table->id) }}">{{ __('Images') }}</a>
											<a href="{{ route('store.table_day.index',$table->id) }}">{{ __('Days') }}</a>
										</div>
									</td>
									<td>
										{{ $table->no_guest }}
									</td>
									<td>
										@if($table->status == 0)
										{{ __('Offline') }}
										@elseif($table->status == 1)
										{{ __('Online') }}
										@else
										{{ __('Busy') }}
										@endif
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
								<th class="am-title">{{ __('Number of Guest') }}</th>
								<th class="am-title">{{ __('Status') }}</th>
							</tr>
						</tfoot>
					</table>
					<div class="f-right">{{ $tables->links() }}</div>
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
