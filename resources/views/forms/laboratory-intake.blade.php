@section('title', 'Laboratory intake form')
<x-layout_main>

    <section class="">
    <div class="px-4 mx-auto max-w-screen-md">
        <h1 class="">Laboratory intake form</h1>
        <p class="mb-8 lg:mb-16 text-center">  </p>
        <form method="POST" action="{{ route('laboratory-intake-process') }}" class="space-y-4" novalidate>
            @csrf

            <h2> Contact details </h2>
            @include('forms.components.freeText',[
                'sectionName'   => 'lab-name',
                'title'         => 'The name of your laboratory',
                'placeholder'   => 'Laboratory name',
                'errors'        => $errors
            ])


        <p class="text-left p-0 gap-0">Address</p>
        <div  class="flex flex-col w-full gap-4">
            <div class="flex flex-row w-full gap-4">
                @include('forms.components.freeText',[
                    'sectionName'   => 'street',
                    'placeholder'   => 'Street Name',
                ])

                @include('forms.components.freeText',[
                    'sectionName'   => 'street-no',
                    'placeholder'   => 'Street Number',
                ])

                @include('forms.components.freeText',[
                    'sectionName'   => 'street-detail',
                    'placeholder'   => 'Floor, Unit etc.',
                ])
            </div>

            <div class="flex flex-row w-full gap-4">
                @include('forms.components.freeText',[
                    'sectionName'   => 'postalCode',
                    'placeholder'   => 'Postal Code',
                ])

                @include('forms.components.freeText',[
                    'sectionName'   => 'city',
                    'placeholder'   => 'City',
                ])
            </div>

            <div class="flex flex-row w-full gap-4">
                @include('forms.components.freeText',[
                    'sectionName'   => 'state',
                    'placeholder'   => 'State/Region',
                ])

                @include('forms.components.freeText',[
                    'sectionName'   => 'country',
                    'placeholder'   => 'Country',
                ])
            </div>

            <div class="flex flex-row w-full gap-4">
                @include('forms.components.freeText',[
                    'sectionName'   => 'url',
                    'title'         => 'Website',
                    'placeholder'   => 'Provide an active weblink/URL to your laboratory. Format example: https://www.nameofwebsite.com',
                ])
            </div>

        </div>

            <h2 class="pt-20">Laboratory description</h2>
            <div>
                <p class="text-left p-0 py-4">This laboratory description will be shown on the TCS MSL catalogue webpage 
                    <a href="https://epos-msl.uu.nl/lab/5f7c8ccbe6dc7fa403b3f7c6041a76a8">(Example)
                    </a>.</p>
                    @include('forms.components.freeText',[
                        'sectionName'   => 'description',
                        'placeholder'   => 'Provide a short, concise description of your laboratory (max. 4000 characters). Please include description of its research activities and/or focus. You can include a global description of the equipment in your laboratory',
                        'textBlock'     => true
                    ])
            </div>

            <h2 class="pt-20">Your contribution to EPOS MSL</h2>
            <div>
                <p class="text-left py-4">Please select one  
                    <a href={{ route('about')  }}>subdomain</a> under which your laboratory fits best</p>
                    <div class="w-full px-8">
                        @include('forms.components.dropDownSelect',[
                            'sectionName'   => 'subdomain',
                            'placeholder'   => 'Select Subdomain',
                            'ElementsArray'=>    array(
                                'Analogue modelling of geological processes',
                                'Geochemistry',
                                'Microscopy & tomography',
                                'Paleomagnetic and magnetic data',
                                'Rock and melt physical properties',
                                'Geo-energy test beds.',
                            )
                        ])
                    </div>
        
                    <p class="text-left py-2 pt-8">
                        This laboratory contributes to EPOS MSL by means of:
                    </p>
                    <div class="flex flex-col gap-2 pb-6 bg-white  rounded-lg ">
                        <div class="flex flex-col px-10 py-8
                        {{-- custom error message --}}
                         @if ($errors->has('dataSharing-facilityAccess'))
                                error-highlight bg-error-300 text-error-700 rounded-md
                            @endif
                        ">
                            <div class="w-1/2 self-center">
                                @include('forms.components.checkBox',[
                                    'sectionName'   => 'dataSharing-facilityAccess',
                                    'showErrMess'   => true,
                                    'ElementsArray'=>    array(
                                        'Facility Access and Data sharing'
                                    )
                                ])
                            </div>

                        </div>

                        <div class="flex flex-row gap-10 bg-white rounded-md px-4">

                            <div class="w-1/3">
                                <p>Data sharing</p>
                            </div>
                            <div class="w-full">
                                <p class="text-justified">
                                    The laboratory publishes data at a reputable data repository 
                                    (see: data repositories currently linked to EPOS MSL), including a 
                                    DOI, and under an open access license of your choice. 
                                    Once published, the corresponding metadata will be harvested by EPOS MSL. 
                                    You use MSL-harmonized vocabulary terms as metadata/keywords in publishing your data. 
                                </p>

                            </div>
        
                        </div>
        
                        <div class="flex flex-row gap-10 bg-white rounded-md px-4">
                            <div class="w-1/3 ">
                                <p>Facility Access</p>

                            </div>
                            <div class="w-full">
                                <p  class="text-justified">
                                    After approval you are required to register all equipment on 
                                    Facility Access SysTem (FAST). Equipment of the laboratory will be made discoverable 
                                    on the central EPOS portal, promoting visibility, knowledge sharing and access.
                                </p>
                            </div>
        
                        </div>
                    </div>

            </div>
            
            <h2 class="pt-20">Laboratory contact person </h2>
            <p class="text-left p-0 pt-8">
                Laboratory contact person is a dedicated lab manager or 
                scientist who acts as the main contact point between the laboratory and EPOS MSL.
            </p>
            <div  class="flex flex-col w-full gap-4">
                <div class="flex flex-row w-full gap-4">
                    @include('forms.components.freeText',[
                        'sectionName'   => 'contact-firstName',
                        'placeholder'   => 'First Name',
                    ])
    
                    @include('forms.components.freeText',[
                        'sectionName'   => 'contact-lastName',
                        'placeholder'   => 'Last Name',
                    ])
                </div>

                <div class="flex flex-row w-full gap-4">
                    @include('forms.components.freeText',[
                        'sectionName'   => 'contact-nationality',
                        'placeholder'   => 'Your nationality',
                    ])
                </div>
                <div class="w-full">
                    @include('forms.components.dropDownSelect',[
                        'sectionName'   => 'contact-gender',
                        'placeholder'   => 'Select your gender',
                        'ElementsArray'=>    array(
                            'Female',
                            'Male',
                            'Other'
                        )
                    ])

                </div>

    
                <div class="flex flex-row w-full gap-4">
                    @include('forms.components.freeText',[
                        'sectionName'   => 'contact-email',
                        'placeholder'   => 'email@address.edu',
                    ])
                </div>
    
                <div class="flex flex-row w-full gap-4">
                    @include('forms.components.freeText',[
                        'sectionName'   => 'contact-affiliation',
                        'placeholder'   => 'research institute, university',
                    ])

                    @include('forms.components.freeText',[
                        'sectionName'   => 'contact-affiliation-country',
                        'placeholder'   => 'Country of the institude, university',
                    ])
                </div>
    
            </div>



            <div class="w-full flex place-content-center p-20">
                <button type="submit" class="btn btn-primary">Submit Application</button>
            </div>

        </form>
    </div>
  </section>

</x-layout_main>