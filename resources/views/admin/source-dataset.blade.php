@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
        <h1>Source dataset</h1>
            <div class="card">
                <div class="card-header">Source dataset</div>
                <div class="card-body">
					@if($sourceDataset)											
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
						<p>Source data:</p>
						<div class="overflow-scroll"><pre>{{ $sourceDataset->source_dataset }}</pre></div>
					@else
						<p>Source dataset not found.</p>
					@endif
                </div>
            </div>                                    
        </div>
    </div>
</div>
@endsection
