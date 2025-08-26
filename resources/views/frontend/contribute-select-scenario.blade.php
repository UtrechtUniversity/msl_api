@section('title', 'Scenario Survey')
<x-layout_main>
<div class="mainContentDiv flex-col">
    <h1 class="pt-20">Data Tooling - Survey</h1>

    <div class="max-w-screen-md pt-10 sm:pt-20">
        <h3 class="pb-2 pt-6">What is this survey about?</h3>
        <p class="inline">
          Many people are hesitant to answer questions about themselves and their opinions. If you are developing your survey for a science fair project, people will probably be more willing to help if you clearly state your intentions. At the top of your survey, write a brief statement explaining why you are collecting the information and reassure each respondent that the information is entirely anonymous. If you need to know specifics about a person, respect their privacy by identifying them as subject1, subject2, etc...
        </p>
    </div>

        <h3 class="pb-2 pt-16">Select your domain!</h3>
        <div class="sm:max-w-screen-lg flex md:flex-row flex-wrap sm:p-10 py-10 gap-8 justify-center place-items-center">
            @foreach ($allDomains as $id => $domainName)
                    <a role="button" 
                    href="{{ route('survey-form', ['surveyId' => $id]) }}"
                    class="no-underline m-1 p-4 bg-base-300 rounded-lg w-64 h-24 place-content-center shadow-lg hover:bg-secondary-100 hover:text-secondary-900"
                    > 
                        <h5 class="text-base font-normal">{{ $domainName }}</h5>
                    </a>
            @endforeach
        </div>
        
</div>


</x-layout_main>