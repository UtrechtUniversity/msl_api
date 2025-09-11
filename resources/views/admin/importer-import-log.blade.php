@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
        
        <ul class="nav nav-tabs">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('importer-imports-flow', ['importer_id' => $importer_id, 'import_id' => $import_id]) }}">Datasets</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="#">Mapping log</a>
          </li>          
        </ul>
        
            <div class="card">
                
                <div class="card-body">
					@if($logs->count() > 0)
						<table class="table">
							<thead>
								<tr>
									<th>identifier</th>
									<th>doi</th>
									<th>type</th>
									<th>message</th>
									<th>created_at</th>
								</tr>
							</thead>
							<tbody>							
								@foreach($logs as $log)
									<tr 
									 @switch($log->type)									
									 	@case('ERROR')
											class="table-danger"
											@break
										
										@case('WARNING')
											class="table-warning"
											@break
										@endswitch
									>
																		
										<td>{{ $log->source_dataset->source_dataset_identifier->identifier }}</td>
										<td>
											<?php $doi = $log->getDatasetDOI(); ?>
											@if($doi !== "")
												<a href="https://dx.doi.org/{{ $log->getDatasetDOI() }}">{{ $log->getDatasetDOI() }}</a>
											@endif										
										</td>
										<td>{{ $log->type }}</td>
										<td>{{ $log->message }}</td>
										<td>{{ $log->created_at }}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
						
						<div class="d-flex justify-content-center">
							{{ $logs->links() }}
						</div>
						
						<a href="{{ route('importer-imports-log-export', ['importer_id' => $importer_id, 'import_id' => $import_id]) }}" class="btn btn-success">Download as xlsx</a>
					@else
						<p>No mapping log entries found.</p>
					@endif
                </div>
            </div>                                    
        </div>
    </div>
</div>
@endsection
