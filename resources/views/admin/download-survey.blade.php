@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">


        <h1>Download survey</h1>
            <form action="{{ route('download-survey-process') }}" method="POST">
                @csrf

                <div class="card">

                    <div class="row">
                        <h4>Select survey by name</h4>

                        <div class="row">
                			<div class="col">
                        		<select class="form-select" aria-label="select organization" name="surveyID">
        							@foreach($allSurveys as $survey)
        								<option value="{{ $survey['id'] }}" >{{ $survey['name'].' - Status:'}}@if ($survey['active'])
                                            active
                                        @else
                                            inactive
                                        @endif
                                            {{ " - responses: ".count($survey->responses) }}
                                    </option>    								
        							@endforeach
        						</select>
							</div>
						</div>
                    </div>                    

                </div>
                <button type="submit" class="btn btn-primary">download survey</button>

            </form>
        </div>
	</div>
</div>
@endsection
