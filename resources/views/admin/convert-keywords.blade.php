@extends('layouts.app')
{{-- 
    Input Variables needed:
    $displayNames (Array) -> contains all domain names as string to be listed in the dropdown menu

--}}
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

                    @if (session('error'))
                        <div class="card-body" role="alert">
                            <h4 class="p-6">{{ session('error') }}</h4>
                        </div>
                    @endif
                </div>

                <div class="card p-10 ">
                    <div class="card-header">Upload a file</div>
                    <div class="card-body">
                        <form action="{{ route('process-file') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div>
                                    <select class="form-select" aria-label="domain-selection" name="domain-selection">
                                        @foreach($displayNames as $displayName)
                                            <option value="{{ $displayName }}">{{ $displayName }}</option>    								
                                        @endforeach
                                    </select>    					
                                </div>
                            </div>                       

                            <div>
                                <input class="form-control" type="file" id="formFile" name="uploaded-file">
                            </div>
                            <br>

                            <button type="submit" class="btn btn-primary">Upload file</button>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
