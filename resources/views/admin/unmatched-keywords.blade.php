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
                <div class="card-header">Unmatched terms</div>
                <div class="card-body">
                	<p>Download Excel file containing unmatched term information.</p>
					<a class="btn btn-primary" href="{{ route('download-unmatched-keywords') }}">Download</a>
					<hr>
                                	 
                	@foreach($keywords as $keyword => $count)
                		<button type="button" class="btn btn-primary m-1">
							{{ $keyword }} <span class="badge bg-secondary">{{ $count }}</span>
						</button>
                	@endforeach                					
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
