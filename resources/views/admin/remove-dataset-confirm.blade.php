@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Confirm selection</div>
                <div class="card-body">
					@if(count($results) == 0)
						<p>No results found.</p>
						
						<a href="{{ route('remove-dataset') }}" class="btn btn-warning">New search</a>
					@else
						<p>{{ count($results) }} results found</p>
						
						<form method="post" action="{{ route('remove-dataset-confirmed') }}">
						@csrf
						<table class="table">
							<thead>
								<tr>
									<th>#</th>
									<th>title</th>
								</tr>
							</thead>
							<tbody>
								@foreach($results as $result)
								<tr>
									<td>
										{{ $loop->iteration }}
										<input type="hidden" name="names[]" value="{{ $result['name'] }}">
									</td>
									<td>{{ $result['title'] }}</td>																	
								</tr>
								@endforeach
							</tbody>
						</table>
						
						<button type="submit" class="btn btn-danger">Delete</button>
						<a href="{{ route('remove-dataset') }}" class="btn btn-warning">New search</a>
						</form>
					@endif
                </div>
            </div>
            
            
            
        </div>
    </div>
</div>
@endsection
