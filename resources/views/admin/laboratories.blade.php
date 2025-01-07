@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
        <h1>Laboratories</h1>
            <div class="card">
                <div class="card-header">-</div>
                <div class="card-body">
					@if($laboratories->count() > 0)
						<table class="table">
							<thead>
								<tr>
									<th>fast id</th>
									<th>fast name</th>
									<th>latitude</th>
									<th></th>
									<th></th>									
								</tr>
							</thead>
							<tbody>
								@foreach($laboratories as $laboratory)
								<tr>
									<td>{{ $laboratory->fast_id }}</td>
									<td>{{ $laboratory->name }}</td>
									<td>{{ $laboratory->latitude }}</td>
									<td></td>
									<td></td>
								</tr>
								@endforeach
							</tbody>
						</table>
						<div class="d-flex justify-content-center">
							
						</div>
					@else
						<p>No laboratories found</p>
					@endif

					<a href="{{ route('download-lab-data') }}" class="btn btn-primary">Download Excel file</a>
                </div>
            </div>                                    
        </div>
    </div>
</div>
@endsection
