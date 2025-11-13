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
                <div class="card-header">Query generator</div>
                <div class="card-body">
                    <h4>Query group 1 ({{ $group1Count }} terms)</h4>
                    <pre>{{ $queryGroup1 }}</pre>

                    <h4>Query group 2 ({{ $group2Count }} terms)</h4>
                    <pre>{{ $queryGroup2 }}</pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
