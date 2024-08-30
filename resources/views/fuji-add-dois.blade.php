@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
        	<div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
        	</div>
        	
        	@if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        
            <div class="card">            	           
                <div class="card-header">Add DOIs</div>
                <div class="card-body">
					<form method="post" action="{{ route('fuji-process-dois') }}">
					@csrf
					<div class="form-group">
    					<label for="export-identifier-input">DOI export identifier</label>
    					<input type="text" class="form-control" id="export-identifier-input" name="export-identifier">
  					</div>
					
					<div class="form-group">
    					<label for="group-identifier-input">DOI group identifier</label>
    					<input type="text" class="form-control" id="group-identifier-input" name="group-identifier">
  					</div>
  					
					<div class="form-group">						
						<label for="dois-input">Enter DOIs to process</label>
    					<textarea class="form-control" id="dois-input" name="dois" rows="10"></textarea>  											
					</div>
										
					<button type="submit" class="btn btn-primary mb-2">Process DOIs</button>					
					</form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
