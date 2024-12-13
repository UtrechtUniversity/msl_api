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
        
            <div class="card">
                <div class="card-header">Abstract matching</div>
                <div class="card-body">
                	<form method="get" action="{{ route('abstract-matching') }}">
                		<div class="row">
                			<div class="col">
                        		<select class="form-select" aria-label="select organization" name="datasetSource">
        							@foreach($organizations as $organization)
        								<option value="{{ $organization['id'] }}" @if ($selected == $organization['id']) selected="selected" @endif >{{ $organization['name'] }}</option>    								
        							@endforeach
        						</select>
							</div>
    						<div class="col">
    							<button type="submit" class="btn btn-primary">Search</button>
    						</div>
						</div>
                	</form>
                
					<hr>
					
					@if (count($data) > 0)                
                    	<p>Download Excel file containing abstract keyword matching results</p>
    					<a class="btn btn-primary" href="{{ route('abstract-matching-download', ['data_repo' => $selected]) }}">Download</a>
    					<hr>
					@endif
					
					@foreach ($data as $row)
                    	<div class="card">
                    		<div class="card-body">
                    			<p>{{ $row['title'] }}</p>
                    			<p>{{ $row['abstract'] }}</p>
                    			<hr>
                    			
                    			@foreach($row['keywords'] as $keyword)
                    				<button type="button" class="btn btn-primary m-1">
            							{{ $keyword }}
            						</button>
                    			@endforeach
                    		</div>
                    	</div>
       				@endforeach	
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
