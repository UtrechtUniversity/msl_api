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
                <div class="card-header">Generate uri-labels</div>
                <div class="card-body">				
					<p>Download generated export of data used by portal to display labels of uris.</p>
					<a class="btn btn-primary" href="{{ route('uri-label-download') }}">Download uri-label dict</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
