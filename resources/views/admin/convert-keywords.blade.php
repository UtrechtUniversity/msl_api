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
                        <div class="error-highlight-input" role="alert">
                            <h3 class="text-error-800 p-6">{{ session('error') }}</h3>
                        </div>
                    @endif
                </div>


                <div class="card p-10 ">
                    <h2 class="justify-center">Upload a file</h2>
                    <div class="card-body">
                        <form action="{{ route('process-file') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            @include('forms.components.dropDownSelect', [
                                'sectionName' => 'domain-selection',
                                'placeholder' => 'Select the domain/field',
                                'ElementsArray' => $displayNames,
                            ])
                            <br>

                            <div class="">
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
