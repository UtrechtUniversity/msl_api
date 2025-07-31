@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
        <h1>Create a survey</h1>
            <form action="{{ route('status-survey-process') }}" method="POST">
                @csrf

                <div class="card">

                    <div class="row">
                        <h4>Name of survey</h4>

                        This is a dropdown
                        <div class="col">
                            <input type="text" name="name">
                        </div>
                    </div>


                    <div class="row">
                        <h4>Is active?</h4>
                        <div class="col">
                            <select class="" name="isSurveyActive">
                                    <option value="yes">Yes<option>    								
                                    <option value="no">No<option>    								
                            </select>
                        </div>
                    </div>
                    

                </div>
                <button type="submit" class="btn btn-primary">Add survey</button>

            </form>
        </div>
	</div>
</div>
@endsection
