@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
        <h1>Source datasets</h1>
            <div class="card">
                <div class="card-header">Source datasets</div>
                <div class="card-body">
					@if($sourceDatasets->count() > 0)
						<table class="table">
							<thead>
								<tr>
									<th>Id</th>
									<th>Importer</th>									
									<th>status</th>
									<th>created_at</th>
									<th></th>									
								</tr>
							</thead>
							<tbody>
								@foreach($sourceDatasets as $sourceDataset)
									<tr
									 @switch($sourceDataset->status)
										@case(null)
										class="table-primary"
										@break
										
										@case('succes')
										class="table-success"
										@break
										
										@case('error')
										class="table-danger"
										@break
									@endswitch
									>
										<td>{{ $sourceDataset->id }}</td>
										<td>{{ $sourceDataset->source_dataset_identifier->import->importer->name }}</td>
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
										<td>{{ $sourceDataset->created_at }}</td>
										<td><a href="{{ route('source-dataset', ['id' => $sourceDataset->id]) }}">view details</a></td>
									</tr>
								@endforeach
							</tbody>
						</table>
						
						<div class="d-flex justify-content-center">
							{{ $sourceDatasets->links() }}
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
