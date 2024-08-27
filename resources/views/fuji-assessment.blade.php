@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">        
            <div class="card">
                <div class="card-header">Grouped FUJI FAIR assessments</div>
                <div class="card-body">
					@if($assessment)
					<table class="table">
						<thead>
							
						</thead>
						<tbody>
							<tr>
								<td>id:</td>
								<td>{{ $assessment->id }}</td>
							</tr>
							<tr>
								<td>group identifier:</td>
								<td>{{ $assessment->group_identifier }}</td>
							</tr>
							<tr>
								<td>doi:</td>
								<td>{{ $assessment->doi }}</td>
							</tr>
							<tr>
								<td>processed:</td>
								<td>{{ $assessment->processed }}</td>
							</tr>
							<tr>
								<td>response code:</td>
								<td>{{ $assessment->response_code }}</td>
							</tr>
							<tr>
								<td>score percentage:</td>
								<td>{{ $assessment->score_percent }}</td>
							</tr>																					
						</tbody>
					</table>
					
					<p>Full response:</p>
					<div class="overflow-scroll"><pre>{{ $assessment->getResponseBodyAsJson(true) }}</pre></div>
					
					
					@endif
                </div>
            </div>                                    
        </div>
    </div>
</div>
@endsection
