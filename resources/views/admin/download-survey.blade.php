@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="card p-10 ">
                <div class="card-header">Select survey by name</div>
                <div class="card-body">
                    <form action="{{ route('download-survey-process') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div>
                                <select class="form-select" aria-label="domain-selection" name="surveyID">
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

                        <br>

                        <button type="submit" class="btn btn-primary">download survey</button>
                    </form>
                </div>

            </div>

        </div>
	</div>
</div>
@endsection
