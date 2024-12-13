@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
        <h1>Seeders</h1>
        
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
        </div>
        
        @if($seeders->count() > 0)
     		@foreach($seeders as $seeder)
     			<div class="card">
                    <div class="card-header">
                      <a class="btn" data-bs-toggle="collapse" href="#collapse-{{ $loop->iteration }}">
                        {{ $seeder->name }} 
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
							<path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
						</svg>
                      </a>
                    </div>
                    <div id="collapse-{{ $loop->iteration }}" class="collapse">
                      <div class="card-body">
                      	<table class="table">
                      		<thead>
                      			<tr>
                      				<th></th>
                      				<th></th>
                      			</tr>
                      		</thead>
                      		<tbody>
                      			<tr>
                      				<td>Name:</td>
                      				<td>{{ $seeder->name }}</td>
                      			</tr>
                      			<tr>
                      				<td>Description:</td>
                      				<td>{{ $seeder->description }}</td>
                      			</tr>                      			
                      			<tr>
                      				<td>Type:</td>
                      				<td>{{ $seeder->type }}</td>
                      			</tr>
                      			<tr>
                      				<td>Options:</td>
                      				<td>
                      					@if(is_array($seeder->options))                      					
                      					<pre><code>{{ json_encode($seeder->options, JSON_PRETTY_PRINT) }}</code></pre>                      					
                      					@else
                  						{{ $seeder->options }}
                      					@endif  					                      				
                      				</td>
                      			</tr>
                      		</tbody>
                      	</table>
                      	<form method="post" action="{{ route('create-seed') }}">
                      		@csrf
                      		<input name="seeder-id" type="hidden" value="{{ $seeder->id }}">
                      		<button type="submit" class="btn btn-primary">Start seeder</button>
                      		<a class="btn btn-warning" href="{{ route('seeder-seeds', ['id' => $seeder->id]) }}" title="view seeds">View seeds</a>
                      	</form>                      	
                      </div>
                    </div>
            	</div>
     		
     		@endforeach
     	@else	
 			<p>No seeders available</p>
     	@endif
	</div>
</div>
@endsection
