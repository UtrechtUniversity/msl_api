@section('title', 'Scenario Survey')
<x-layout_main>
<div class="py-8 lg:py-16 px-4 mx-auto max-w-screen-md">
    <h1 class="pt-20">Data Tooling - Survey</h1>

    {{-- {{ $imageSource }} --}}

    <form method="POST" action="{{ route('contribute-survey-scenario-process') }}" class="space-y-8 flex flex-col justify-center items-center" novalidate>
        @csrf 




            <div class="w-full">
                <p class="text-left font-bold p-0 pt-8 pb-4">
                    Which role describes your occupation?
                </p>
                @include('forms.components.dropDownSelect',[
                    'sectionName'   => 'role',
                    'placeholder'   => 'Select your role',
                    'ElementsArray'=>    array(
                        'Industry researcher',
                        'Academic researcher ',
                        'PhD student',
                        'Master student',
                        'Bachelor student',
                        'Research supervisor'
                    )
                ])

            </div>


            <p class="p-20">scenario here</p>




            <div class="w-full">
                <p class="text-left font-bold p-0 pt-8 pb-4">
                    Do you recognize challenges in this scenario in your work?
                </p>

                <div class="flex justify-evenly">
                    @include('forms.components.radioSelect',[
                        'sectionName'   => 'scenario-similarChallenges',
                        'ElementsArray'=>    array(
                            "Strongly Disagree",
                            'Disagree',
                            'Neutral',
                            'Agree',
                            'Strongly Agree'
                        )
        
                    ])
                </div>


                <p class="text-left font-bold p-0 pt-8 pb-4">
                    If applicable, please give examples of such challenges:
                </p>
                <div  class="flex flex-col w-full gap-4">
                        @include('forms.components.freeText',[
                            'sectionName'   => 'scenario-similarChallenges-examples',
                            'placeholder'   => 'write something',
                            'textBlock' => true
                        ])
                </div>
            </div>


            <div class="w-full">
                <p class="text-left font-bold p-0 pt-8 pb-4">
                    Would you use the data service presented in this scenario for your work?
                </p>
                <div class="flex justify-evenly">
                    @include('forms.components.radioSelect',[
                        'sectionName'   => 'scenario-useSameTool',
                        'ElementsArray'=>    array(
                            "Strongly Disagree",
                            'Disagree',
                            'Neutral',
                            'Agree',
                            'Strongly Agree'
                        )
        
                    ])                    
                </div>

            </div>


            <div class="w-full">
                <p class="text-left font-bold p-0 pt-8 pb-4">
                    How do you approach a similar challenge in your work?
                </p>
                <div  class="flex flex-col w-full gap-4">
                    @include('forms.components.freeText',[
                        'sectionName'   => 'scenario-similarChallenges-approach',
                        'placeholder'   => 'write something',
                        'textBlock' => true
                    ])
                </div>
            </div>

            <div class="w-full">
                <p class="text-left font-bold p-0 pt-8 pb-4">
                    How would you change this scenario to make it reflect your work? --needs work
                </p>
                <div  class="flex flex-col w-full gap-4">
                    @include('forms.components.freeText',[
                        'sectionName'   => 'scenario-similarChallenges-approach',
                        'placeholder'   => 'write something',
                        'textBlock' => true
                    ])
                </div>
            </div>

            <div class="w-full">
                <p class="text-left font-bold p-0 pt-8 pb-4">
                    How do you approach the problem shown in the scenario in your current setup. What tools are you using? --needs work
                </p>
                <div  class="flex flex-col w-full gap-4">
                    @include('forms.components.freeText',[
                        'sectionName'   => 'scenario-similarChallenges-approach',
                        'placeholder'   => 'write something',
                        'textBlock' => true
                    ])
                </div>
            </div>

            <div class="flex flex-col items-center w-full">

                <p class="text-left font-bold p-0 pt-8 pb-4">
                    When would you see this data tool being beneficial in your process?
                </p>

                <div class="flex flex-col w-96 px-10 py-8
                  @if ($errors->has('multiCheckbox'))
                          error-highlight bg-error-300 text-error-700 rounded-md
                      @endif
                  ">
                  @include('forms.components.checkBox',[
                      'sectionName'   => 'multiCheckbox',
                      'showErrMess'   => true,
                      'horizontal'    => true,
                      'ElementsArray'=>    array(
                          'Problem Identificaton',
                          'Literature Review',
                          'Setting Research Questions, Objectives, and Hypothesis',
                          'Choosing the Study Design',
                          'Deciding on the Sample Design',
                          'Collecting Data',
                          'Processing and Analyzing Data',
                          'Writing the Report'
                      )

                  ])
              </div>
            </div>



    </form>
        
</div>


</x-layout_main>