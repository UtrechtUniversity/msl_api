@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
        <h1>Imports</h1>
            <div class="card">
                <div class="card-header">Imports</div>
                <div class="card-body">
					@if($imports->count() > 0)
						<table class="table">
							<thead>
								<tr>
									<th>#</th>
									<th>Importer</th>
									<th>response code</th>
									<th>total count</th>
									<th>created_at</th>									
								</tr>
							</thead>
							<tbody>
								@foreach($imports as $import)
								<tr 
								@switch($import->response_code)
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
									<td>{{ $import->importer->name }}</td>
									<td>
										@if($import->response_code == '')
											in queue
										@else
											{{ $import->response_code }}
										@endif
									</td>
									<td>{{ $import->source_dataset_identifiers->count() }}</td>
									<td>{{ $import->created_at }}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						<div class="d-flex justify-content-center">
							{{ $imports->links() }}
						</div>
					@else
						<p>No imports in queue.</p>
					@endif
                </div>
            </div>                                    
        </div>
    </div>
</div>
@endsection
