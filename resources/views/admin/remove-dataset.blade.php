@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Remove by name</div>
                <div class="card-body">
					<form method="get" action="{{ route('remove-dataset-confirm') }}">
						<div class="row mb-3">
    						<label for="datasetId" class="col-sm-2 col-form-label">Dataset name</label>
    						<div class="col-sm-10">
      							<input type="text" class="form-control" id="datasetId" name="datasetId">
    						</div>
  						</div>
  						<button type="submit" class="btn btn-primary">Search</button>
					</form>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">Remove by repository/owner organization</div>
                <div class="card-body">
					<form method="get" action="{{ route('remove-dataset-confirm') }}">
						<div class="row mb-3">
    						<label for="datasetSource" class="col-sm-2 col-form-label">Organization</label>
    						<div class="col-sm-10">
    							<select class="form-select" aria-label="select organization" name="datasetSource">
    								@foreach($organizations as $organization)
    									<option value="{{ $organization['id'] }}">{{ $organization['name'] }}</option>    								
    								@endforeach
    							</select>
    						
      							<!--  input type="text" class="form-control" id="datasetSource" name="datasetSource"> -->
    						</div>
  						</div>
  						<button type="submit" class="btn btn-primary">Search</button>
					</form>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
