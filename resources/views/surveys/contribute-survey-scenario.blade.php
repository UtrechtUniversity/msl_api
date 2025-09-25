@section('title', 'Scenario Survey')
<x-layout_main>
<div class="py-8 lg:py-16 px-4 mx-auto max-w-screen-md">
    <h1 class="pt-20">Data Tooling - Survey</h1>

        <x-survey-component :allQuestions="$allQuestions" :surveyName="$surveyName"/>
        
</div>


</x-layout_main>