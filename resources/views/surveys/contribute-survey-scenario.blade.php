@section('title', 'Scenario Survey')
<x-layout_main>
<div class="py-8 lg:py-16 px-4 mx-auto max-w-screen-md">
    <h1 class="pt-20">Data Tooling - Survey</h1>

    <form method="POST"
    autocomplete="off"
    action={{ route('survey-form-process', ['surveyName' => $surveyName]) }}
    class="space-y-8 flex flex-col justify-center items-center" novalidate>
        @csrf 
        
            <div class="w-full">
                <x-survey-component :allQuestions="$allQuestions" />
            </div>
            

            <div class="flex place-content-center">
                <button type="submit" class="btn btn-primary" >Submit</button>
            </div>
            
        </form>
        
</div>


</x-layout_main>