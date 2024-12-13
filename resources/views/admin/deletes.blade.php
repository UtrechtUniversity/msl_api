@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
        <h1>Deletes</h1>
            <div class="card">
                <div class="card-header">Deletes</div>
                <div class="card-body">
					@if($deletes->count() > 0)
						<table class="table">
							<thead>
								<tr>
									<th>#</th>
									<th>ckan_id</th>
									<th>response_code</th>
									<th>created_at</th>									
								</tr>
							</thead>
							<tbody>
								@foreach($deletes as $delete)
								<tr 
								@switch($delete->response_code)
									@case(null)
										class="table-primary"
										@break
										
									@case(200)
										class="table-success"
										@break
										
									@case(404)
										class="table-danger"
										@break
								@endswitch
								>
									<td>{{ $loop->iteration }}</td>
									<td>{{ $delete->ckan_id }}</td>
									<td>
										@if($delete->response_code == '')
											in queue
										@else
											{{ $delete->response_code }}
										@endif
									</td>
									<td>{{ $delete->created_at }}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						<div class="d-flex justify-content-center">
							{{ $deletes->links() }}
						</div>
					@else
						<p>No deletes found.</p>
					@endif
                </div>
            </div>                                    
        </div>
    </div>
</div>
@endsection
