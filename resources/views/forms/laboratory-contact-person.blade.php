{{-- 
    vars:
    'labName'          => $data['title'] ,
    'labAffiliation'   => $data['msl_organization_name'] 


--}}

@section('title', 'Laboratory contact person')
<x-layout_main>

    <section class="">
        <div class="py-8 lg:py-16 px-4 mx-auto max-w-screen-md">
            <h1 class="">Contact Laboratory</h1>
            <p class="mb-8 lg:mb-16 text-center">Do you have questions? Want to use their facilities? </p>

            <div class="flex flex-col w-full place-items-center gap-4">
                <p class="text-center">You are reaching out to</p>

                <div class="flex flex-col px-20 gap-4 bg-primary-200 p-4 rounded-lg">
                    <h5>{{ $data['title'] }}</h5>
                    <h5>{{ $data['msl_organization_name'] }}</h5>
                </div>
            </div>

            <form method="POST" action="{{ route('laboratory-contact-person-process') }}" class="space-y-8" novalidate>
                @csrf                        
                <div  class="flex flex-col w-full gap-4">
                    
                    <div class="flex flex-row w-full gap-4">
                        @include('forms.components.freeText',[
                            'sectionName'   => 'email',
                            'title'         => 'Your Email *',
                            'placeholder'   => 'email@address.edu',
                        ])
                    </div>
    
                    <div class="flex flex-row w-full gap-4">
                        @include('forms.components.freeText',[
                            'sectionName'   => 'firstName',
                            'title'         => 'First Name *',
                            'placeholder'   => 'First Name',
                        ])
        
                        @include('forms.components.freeText',[
                            'sectionName'   => 'lastName',
                            'title'         => 'Last Name *',
                            'placeholder'   => 'Last Name',
                        ])
                    </div>
        
                    <div class="flex flex-row w-full gap-4">
                        @include('forms.components.freeText',[
                            'sectionName'   => 'affiliation',
                            'title'         => 'Affiliation *',
                            'placeholder'   => 'e.g. research institute, university',
                        ])
                    </div>
        
                    <div class="flex flex-row w-full gap-4">
                        @include('forms.components.freeText',[
                            'sectionName'   => 'subject',
                            'title'         => 'Subject *',
                            'placeholder'   => 'Why are you reaching out?',
                        ])
                    </div>

                </div>

                <div class="w-full">
                    @include('forms.components.freeText',[
                        'sectionName'   => 'message',
                        'title'         => 'Message *',
                        'placeholder'   => 'Your message',
                        'textBlock'     => true
                    ])
                </div>
    
                <div class="w-full flex place-content-center">
                    <button type="submit" class="btn btn-primary">Send message</button>
                </div>
    
            </form>
        </div>
      </section>


</x-layout_main>
