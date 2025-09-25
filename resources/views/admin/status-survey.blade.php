@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">


            <div class="card p-10 ">
                <div class="card-header">Change status of survey</div>
                <div class="card-body">
                    <form action="{{ route('status-survey-process') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div>
                                <select class="form-select" aria-label="select organization" name="surveyID">
        							@foreach($allSurveys as $survey)
        								<option value="{{ $survey['id'] }}" >{{ $survey['name'].' - Status:'}}@if ($survey['active'])
                                            active
                                        @else
                                            inactive
                                        @endif</option>    								
        							@endforeach
        						</select>  					
                            </div>
                        </div>            
                        <br>           
                        <div class="row">
                            <h4>Is active?</h4>
                            <div class="col">
                                <select name="isSurveyActive">
                                        <option value="yes">Yes<option>
                                        <option value="no">No<option>  								
                                </select>
                            </div>
                        </div>
                        <br>

                        <button type="submit" class="btn btn-primary">Change survey status</button>
                    </form>
                </div>

 
        </div>
	</div>
</div>
@endsection
