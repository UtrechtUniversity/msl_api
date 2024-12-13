@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
        <h1>Importers</h1>
        
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
        </div>
        
     	<div id="accordion">
     	
     	@if($importers->count() > 0)
     		@foreach($importers as $importer)
     			<div class="card">
                    <div class="card-header">
                      <a class="btn" data-bs-toggle="collapse" href="#collapse-{{ $loop->iteration }}">
                        {{ $importer->name }} 
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
                      				<td>{{ $importer->name }}</td>
                      			</tr>
                      			<tr>
                      				<td>Description:</td>
                      				<td>{{ $importer->description }}</td>
                      			</tr>
                      			<tr>
                      				<td>Data repository:</td>
                      				<td>{{ $importer->data_repository->name }}</td>
                      			</tr>
                      			<tr>
                      				<td>Data repository ckan name:</td>
                      				<td>{{ $importer->data_repository->ckan_name }}</td>
                      			</tr>
                      			<tr>
                      				<td>Type:</td>
                      				<td>{{ $importer->type }}</td>
                      			</tr>
                      			<tr>
                      				<td>Options:</td>
                      				<td>
                      					@if(is_array($importer->options))                      					
                      					<pre><code>{{ json_encode($importer->options, JSON_PRETTY_PRINT) }}</code></pre>                      					
                      					@else
                  						{{ $importer->options }}
                      					@endif  					                      				
                      				</td>
                      			</tr>
                      		</tbody>
                      	</table>
                      	<form method="post" action="{{ route('create-import') }}">
                      		@csrf
                      		<input name="importer-id" type="hidden" value="{{ $importer->id }}">
                      		<button type="submit" class="btn btn-primary">Start import</button>
                      		<a class="btn btn-warning" href="{{ route('importer-imports', ['id' => $importer->id]) }}" title="view imports">View imports</a>
                      	</form>                      	
                      </div>
                    </div>
            	</div>
     		@endforeach
     	@else
			<p>No importers available.</p>
		@endif
		</div>
	</div>
</div>
@endsection
