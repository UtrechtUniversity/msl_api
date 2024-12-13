@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
        <h1>Seeds for seeder {{ $seeder->name }}</h1>
            <div class="card">
                <div class="card-header">Seeds</div>
                <div class="card-body">
					@if($seeds->count() > 0)
						<table class="table">
							<thead>
								<tr>
									<th>#</th>
									<th>created_at</th>
									<th>actions</th>
								</tr>
							</thead>
							<tbody>
								@foreach($seeds as $seed)								
    								<tr>
    									<td>{{ $seed->id }}</td>																		
    									<td>{{ $seed->created_at }}</td>
    									<td><a href="{{ route('seeds', ['id' => $seed->id]) }}" title="view details">View details</a></td>
    								</tr>
								@endforeach
							</tbody>
						</table>
					@else
						<p>No seeds found for this seeder.</p>
					@endif
                </div>
            </div>                                    
        </div>
    </div>
</div>
@endsection
