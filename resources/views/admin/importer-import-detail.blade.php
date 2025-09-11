@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div>
            	<a href="{{ route('importer-imports-flow', ['importer_id' => $importer_id, 'import_id' => $import_id]) }}">Back to import results</a>
            </div>
            @if($sourceDatasetIdentifier)       
                <div class="card">
                    <div class="card-header">
                    	<a class="btn" data-bs-toggle="collapse" href="#collapse-source-identifier">
                    	Source dataset identifier
                    	<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
							<path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
						</svg>
                    	</a>
                    </div>
                    <div id="collapse-source-identifier" class="collapse">
                        <div class="card-body">
                        	<table class="table">
                        		<thead>
                        		
                        		</thead>
                        		<tbody>
                            		<tr>
                            			<td>id</td>
                            			<td>{{ $sourceDatasetIdentifier->id }}</td>
                            		</tr>
                            		<tr>
                            			<td>source identifier</td>
                            			<td>{{ $sourceDatasetIdentifier->identifier }}</td>
                            		</tr>
                            		<tr>
                            			<td>response code</td>
                            			<td>{{ $sourceDatasetIdentifier->response_code }}</td>
                            		</tr>
                            		<tr>
                            			<td>created at</td>
                            			<td>{{ $sourceDatasetIdentifier->created_at }}</td>
                            		</tr>
                        		</tbody>
                        	</table>
                        </div>
                    </div>
                </div>
                
                <?php $sourceDataset = $sourceDatasetIdentifier->source_dataset ?>
                @if($sourceDataset)            
                <div class="card mt-3">
                	<div class="card-header">
                		<a class="btn" data-bs-toggle="collapse" href="#collapse-source-dataset">
                		Source dataset
                		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
							<path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
						</svg>
						</a>
                	</div>
                	<div id="collapse-source-dataset" class="collapse">
                		<div class="card-body">
                    		<table class="table">
    							<thead>
    								
    							</thead>
    							<tbody>
    								<tr>
    									<td>id:</td>
    									<td>{{ $sourceDataset->id }}</td>
    								</tr>
    								<tr>
    									<td>source dataset identifier:</td>
    									<td>{{ $sourceDataset->source_dataset_identifier->identifier }}</td>
    								</tr>
    								<tr>
    									<td>status:</td>
    									<td>
    										@switch($sourceDataset->status)
        										@case(null)
        										in queue
        										@break
        										
        										@case('succes')
        										succes
        										@break
        										
        										@case('error')
        										error
        										@break
        									@endswitch
    									</td>																	
    								</tr>
    								<tr>
    									<td>importer:</td>
    									<td>{{ $sourceDataset->source_dataset_identifier->import->importer->name }}</td>
    								</tr>															
    							</tbody>
    						</table>
    						<p>Mapping log:</p>
    						@if(count($sourceDataset->mapping_logs) > 0)
    						<table class="table">
    							<thead>
    								<tr>
    									<th>#</th>
    									<th>type</th>
    									<th>message</th>
    									<th>created_at</th>
    								</tr>
    							</thead>
    							<tbody>							
    								@foreach($sourceDataset->mapping_logs as $mapping_log)
    									<tr 
    									 @switch($mapping_log->type)									
    									 	@case('ERROR')
    											class="table-danger"
    											@break
    										
    										@case('WARNING')
    											class="table-warning"
    											@break
    										@endswitch
    									>
    										<td>{{ $loop->iteration }}</td>
    										<td>{{ $mapping_log->type }}</td>
    										<td>{{ $mapping_log->message }}</td>
    										<td>{{ $mapping_log->created_at }}</td>
    									</tr>
    								@endforeach
    							</tbody>
    						</table>
    						@else
    							<p>No mapping log entries</p>
    						@endif
    						
    						<p>Source data:</p>
    						<div class="overflow-scroll"><pre>{{ $sourceDataset->source_dataset }}</pre></div>
						</div>
                	</div>
                </div>            
                @endif
                
                <?php if($sourceDataset) { $datasetCreate = $sourceDataset->dataset_create; } else { $datasetCreate = null; } ?>
                @if($datasetCreate)
            	<div class="card mt-3">
            		<div class="card-header">
            			<a class="btn" data-bs-toggle="collapse" href="#collapse-dataset-create">
            			Dataset create
            			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
							<path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
						</svg>
						</a>
            		</div>
            		<div id="collapse-dataset-create" class="collapse">
                		<div class="card-body">
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
                		</div>
            		</div>
            	</div>                        
            	@endif
            @endif                                    
        </div>
    </div>
</div>
@endsection
