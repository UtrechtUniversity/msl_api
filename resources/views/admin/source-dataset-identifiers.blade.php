@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
        <h1>Source identifiers</h1>
            <div class="card">
                <div class="card-header">Source identifiers</div>
                <div class="card-body">
					@if($identifiers->count() > 0)
						<table class="table">
							<thead>
								<tr>
									<th>Id</th>
									<th>Source identifier</th>
									<th>Importer</th>									
									<th>response code</th>
									<th>created_at</th>									
								</tr>
							</thead>
							<tbody>
								@foreach($identifiers as $identifier)
								<tr 
								@switch($identifier->response_code)
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
									<td>{{ $identifier->id }}</td>
									<td>{{ $identifier->identifier }}</td>
									<td>{{ $identifier->import->importer->name }}</td>
									<td>
										@if($identifier->response_code == '')
											in queue
										@else
											{{ $identifier->response_code }}
										@endif
									</td>									
									<td>{{ $identifier->created_at }}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						
						<div class="d-flex justify-content-center">
							{{ $identifiers->links() }}
						</div>
					@else
						<p>No identifier data found.</p>
					@endif
                </div>
            </div>                                    
        </div>
    </div>
</div>
@endsection
