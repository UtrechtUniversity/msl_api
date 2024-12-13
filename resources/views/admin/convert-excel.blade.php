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
                <div class="card-header">Upload excel file</div>
                <div class="card-body">
					<form action="{{ route('process-excel-file') }}" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="mb-3">
                        	<label for="formFile" class="form-label">
                        		This form is used to convert a xlsx file to a basic json format. Currently used to convert the xlsx file used by the the YODA importer. 
                        		First row of xlsx is expected to contain column names that are used as fieldnames in conversion to json. 
                        	</label>
                        	<input class="form-control" type="file" id="formFile" name="excel-file">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Upload file</button>
					</form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
