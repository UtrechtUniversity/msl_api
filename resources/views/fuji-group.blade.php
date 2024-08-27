@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">        
            <div class="card">
                <div class="card-header">Grouped FUJI FAIR assessments</div>
                <div class="card-body">
					@if($assessments->count() > 0)
						<table class="table">
							<thead>
								<tr>
									<th>doi</th>
									<th>processed</th>
									<th>score</th>
								</tr>
							</thead>
							<tbody>
								@foreach($assessments as $assessment)								
    								<tr>
    									<td><a href="{{ route('fuji-view-assessment', $assessment->id) }}">{{ $assessment->doi }}</a></td>																		
    									<td>{{ $assessment->processed }}</td>
    									<td>{{ $assessment->score_percent }}</td>    									
    								</tr>
								@endforeach
							</tbody>
						</table>
					@else
						<p>No creates/updates found for this seed.</p>
					@endif
                </div>
            </div>                                    
        </div>
    </div>
</div>
@endsection
