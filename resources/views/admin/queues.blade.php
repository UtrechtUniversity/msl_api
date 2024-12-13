@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
        <h1>Queues</h1>
            <div class="card">
                <div class="card-header">Deletes</div>
                <div class="card-body">
					@if($deletes->count() > 0)
						<table class="table">
							<thead>
								<tr>
									<th>#</th>
									<th>ckan_id</th>
									<th>created_at</th>
								</tr>
							</thead>
							<tbody>
								@foreach($deletes as $delete)
								<tr>
									<td>{{ $loop->iteration }}</td>
									<td>{{ $delete->ckan_id }}</td>
									<td>{{ $delete->created_at }}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					@else
						<p>No deletes in queue.</p>
					@endif
                </div>
            </div>                                    
        </div>
    </div>
</div>
@endsection
