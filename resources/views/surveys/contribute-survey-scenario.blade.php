@section('title', 'Scenario Survey')
<x-layout_main>
<div class="py-8 lg:py-16 px-4 mx-auto max-w-screen-md">
    <h1 class="pt-20">Data Tooling - Survey</h1>

    @if(sizeof($allQuestions) > 0 )
        <x-survey-component :allQuestions="$allQuestions" :surveyName="$surveyName"/>
    @else
        <div class="w-full 
                flex justify-center
                p-4">

            <img 
            src= {{ asset("images/surveys/scenario/other/noContribution.jpg") }}
            alt=""
            class="max-w-96 object-contain "/>
            
        </div>

        <p>
            Unfortunatly, a survey for this domain does not exist due to a lack of volunteers to complete the process to produce a mockup and scenario. 
            If you are interested to participate, please <a class="link link-hover underline"
            href="{{ route("contact-us") }}">contact us</a>!!!
        </p>

    @endif
        
</div>


</x-layout_main>