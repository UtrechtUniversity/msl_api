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
                <div class="card-header">Generate filter menu/tree</div>
                <div class="card-body">				
					<p>Download generated export of data used by portal to display filter menu in search results.</p>
					<a class="btn btn-primary" href="{{ route('filter-tree-download') }}">Download tree with interpreted keywords</a>
					<a class="btn btn-primary" href="{{ route('filter-tree-download-original') }}">Download tree with orginal assigned keywords</a>
                    <a class="btn btn-primary" href="{{ route('filter-tree-download-equipment') }}">Download tree equipment</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
