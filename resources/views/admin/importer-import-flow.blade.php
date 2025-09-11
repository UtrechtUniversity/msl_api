@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
        
        <ul class="nav nav-tabs">
          <li class="nav-item">
            <a class="nav-link active" href="#">Datasets</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('importer-imports-log', ['importer_id' => $importer_id, 'import_id' => $import_id]) }}">Mapping log</a>
          </li>          
        </ul>
        
            <div class="card">
                
                <div class="card-body">
					@if($sourceDatasetIdentifiers->count() > 0)
						<table class="table">
							<thead>
								<tr>
									<th>#</th>
									<th>source dataset identifier</th>
									<th>source identifier</th>
									<th>source dataset</th>
									<th>create</th>
									<th>created_at</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach($sourceDatasetIdentifiers as $sourceDatasetIdentifier)
								<tr">
									<td>
										{{ $sourceDatasetIdentifier->id }} 										 
									</td>
									<td>
										{{ $sourceDatasetIdentifier->identifier }}
									</td>									
									
									<td>
										@if($sourceDatasetIdentifier->response_code == 200)
											<p class="text-success">success</p>
										@elseif(is_null($sourceDatasetIdentifier->response_code))
											<p class="text-primary">in queue</p>
										@else
											<p class="text-danger">error</p>
										@endif
									</td>
									
									<?php $sourceDataset = $sourceDatasetIdentifier->source_dataset ?>
									<td>
										@if($sourceDataset)
											@if($sourceDataset->status == 'succes')
    											<p class="text-success">success</p>
    										@elseif(is_null($sourceDataset->status))
    											<p class="text-primary">in queue</p>
    										@else
    											<p class="text-danger">error</p>
    										@endif
										@endif										
									</td>
									
									<?php if($sourceDataset) { $datasetCreate = $sourceDataset->dataset_create; } else { $datasetCreate = null; } ?>
									<td>
										@if($datasetCreate)
											@if($datasetCreate->response_code == 200)
    											<p class="text-success">success</p>
    										@elseif(is_null($datasetCreate->response_code))
    											<p class="text-primary">in queue</p>
    										@else
    											<p class="text-danger">error</p>
    										@endif
										@endif
									</td>
									
									<td>{{ $sourceDatasetIdentifier->created_at }}</td>
									<td>
										<a href="{{ route('importer-imports-detail', ['importer_id' => $importer_id, 'import_id' => $import_id, 'source_dataset_identifier_id' => $sourceDatasetIdentifier->id]) }}" title="view details">view</a>
									</td>
								</tr>								
								@endforeach
							</tbody>
						</table>
						
						<div class="d-flex justify-content-center">
							{{ $sourceDatasetIdentifiers->links() }}
						</div>
						

					@else
						<p>No data found.</p>
					@endif
                </div>
            </div>                                    
        </div>
    </div>
</div>
@endsection
