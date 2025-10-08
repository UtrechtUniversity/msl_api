@section('title', 'Scenario Survey')
<x-layout_main>
<div class="mainContentDiv flex-col">
    <h1 class="pt-20">Data Tooling - Survey</h1>

    <div class="max-w-(--breakpoint-md) pt-10 sm:pt-20">
        <h3 class="pb-2 pt-6">What is this survey about?</h3>
        <p class="inline">Having data findable, centrally, in EPOS, doesn’t necessarily make these easy to re-use.  </p>
        <p class="inline">
            At what point during your research would you actually want to re-use data? How should data be offered, such that you could easily re-use these? You can help us explore questions like these by filling out a 10-minute survey. When you select your research domain below, you’ll find a scenario of a researcher looking to re-use data. The survey is about this scenario. We will use your (anonymous) input to further refine specifications on tools to be developed, and to understand where and when these would be useful in research. We appreciate your time! 
        </p>

        
    </div>

        <h3 class="pb-2 pt-16">Select your domain!</h3>
        <div class="sm:max-w-(--breakpoint-lg) flex md:flex-row flex-wrap sm:p-10 py-10 gap-8 justify-center place-items-center">
            @foreach ($allDomains as $surveyName => $domainName)
                    <a role="button" 
                    href="{{ route('survey-form', ['surveyName' => $surveyName]) }}"
                    class="no-underline m-1 p-4 bg-base-300 rounded-lg w-64 h-24 place-content-center shadow-lg hover:bg-secondary-100 hover:text-secondary-900"
                    > 
                        <h5 class="text-base font-normal">{{ $domainName }}</h5>
                    </a>
            @endforeach
        </div>
        <div class="flex">
            <x-ri-information-line  class="info-icon"/>
            <h5 class="text-info-900">Inactive domain surveys are not displayed</h5>
        </div>
</div>


</x-layout_main>