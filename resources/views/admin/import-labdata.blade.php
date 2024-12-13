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
                <div class="card-header">Laboratory data</div>
                <div class="card-body">
					<form method="post" action="{{ route('update-fast-data') }}">
                  		@csrf
                  		<button type="submit" class="btn btn-primary">Update data from FAST</button>
                  		<a class="btn btn-primary" href="{{ route('update-lab-organizations-data') }}">Update organizations using ROR</a>
                  		<a class="btn btn-primary" href="{{ route('update-laboratory-keywords') }}">Update laboratory keywords</a>
                  		<a class="btn btn-primary" href="{{ route('registry-turtle') }}">Registry turtle file</a>
                  	</form>  
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
