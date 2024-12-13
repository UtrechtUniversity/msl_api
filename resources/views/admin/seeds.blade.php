@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
        <h1>Creates for seed #{{ $seed->id }}</h1>
            <div class="card">
                <div class="card-header">Creates/Updates</div>
                <div class="card-body">
					@if($creates->count() > 0)
						<table class="table">
							<thead>
								<tr>
									<th>#</th>
									<th>created_at</th>
									<th>type</th>
									<th>response code</th>
								</tr>
							</thead>
							<tbody>
								@foreach($creates as $create)								
    								<tr>
    									<td>{{ $create->id }}</td>																		
    									<td>{{ $create->created_at }}</td>
    									<td>{{ $create->processed_type }}</td>
    									<td>{{ $create->response_code }}</td>
    								</tr>
								@endforeach
							</tbody>
						</table>
					@else
						<p>No creates/updates found for this seed.</p>
					@endif
                </div>
            </div>                                    
        </div>
    </div>
</div>
@endsection
