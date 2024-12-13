@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
        <h1>Creates</h1>
            <div class="card">
                <div class="card-header">Creates</div>
                <div class="card-body">
					@if($createActions->count() > 0)
						<table class="table">
							<thead>
								<tr>
									<th>Id</th>
									<th>Importer</th>									
									<th>status</th>
									<th>type</th>
									<th>created_at</th>
									<th></th>									
								</tr>
							</thead>
							<tbody>
								@foreach($createActions as $createAction)
									<tr
									 @switch($createAction->response_code)
										@case(null)
    										class="table-primary"
    										@break
										
    									@case(200)
    										class="table-success"
    										@break
    										
    									@case(404)
    										class="table-danger"
    										@break
    										
										@default()
											class="table-warning"
											@break
									@endswitch
									>
										<td>{{ $createAction->id }}</td>
										<td>{{ $createAction->source_dataset->source_dataset_identifier->import->importer->name }}</td>
										<td>
											@if($createAction->response_code == '')
    											in queue
    										@else
    											{{ $createAction->response_code }}
    										@endif
										</td>
										<td>{{ $createAction->processed_type }}</td>
										<td>{{ $createAction->created_at }}</td>
										<td><a href="{{ route('create-action', ['id' => $createAction->id]) }}">view details</a></td>
									</tr>
								@endforeach
							</tbody>
						</table>
						
						<div class="d-flex justify-content-center">
							{{ $createActions->links() }}
						</div>
					@else
						<p>No create data found.</p>
					@endif
                </div>
            </div>                                    
        </div>
    </div>
</div>
@endsection
