@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
        <h1>Create</h1>
            <div class="card">
                <div class="card-header">Create</div>
                <div class="card-body">
					@if($datasetCreate)											
						<table class="table">
							<thead>
								
							</thead>
							<tbody>
								<tr>
									<td>id:</td>
									<td>{{ $datasetCreate->id }}</td>
								</tr>
								<tr>
									<td>response_code:</td>
									<td>
										{{ $datasetCreate->response_code }}
									</td>																	
								</tr>
								<tr>
									<td>importer:</td>
									<td>{{ $datasetCreate->source_dataset->source_dataset_identifier->import->importer->name }}</td>
								</tr>
								<tr>
									<td>dataset type (class):</td>
									<td>{{ $datasetCreate->dataset_type }}</td>
								</tr>														
								<tr>
									<td>created:</td>
									<td>{{ $datasetCreate->created_at }}</td>
								</tr>
								<tr>
									<td>processed:</td>
									<td>{{ $datasetCreate->processed }}</td>
								</tr>
								<tr>
									<td>processed_type:</td>
									<td>{{ $datasetCreate->processed_type }}</td>
								</tr>
							</tbody>
						</table>
						
						<p>Dataset:</p>
						<div class="overflow-scroll"><pre>{{ $datasetCreate->getDatasetAsJson(true) }}</pre></div>
						<br>
						<p>Response:</p>
						<div class="overflow-scroll"><pre>{{ $datasetCreate->getResponseBodyAsJson(true) }}</pre></div>
					@else
						<p>Create not found.</p>
					@endif
                </div>
            </div>                                    
        </div>
    </div>
</div>
@endsection
