@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">        
            <div class="card">
                <div class="card-header">Grouped FUJI FAIR assessments</div>
                <div class="card-body">
					@if($groups->count() > 0)
						<table class="table">
							<thead>
								<tr>
									<th>group identifier</th>
									<th>avarage percentage</th>
									<th>count</th>
								</tr>
							</thead>
							<tbody>
								@foreach($groups as $group)								
    								<tr>
    									<td><a href="{{ route('fuji-view-assessment-group', $group->group_identifier) }}">{{ $group->group_identifier }}</a></td>																		
    									<td>{{ $group->avg_percent }}</td>
    									<td>{{ $group->count }}</td>    									
    								</tr>
								@endforeach
							</tbody>
						</table>
					@else
						<p>No assessment groups found</p>
					@endif
                </div>
            </div>                                    
        </div>
    </div>
</div>
@endsection