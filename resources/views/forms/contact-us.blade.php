@section('title', 'Contact Us')
<x-layout_main>

    <section class="">
    <div class="py-8 lg:py-16 px-4 mx-auto max-w-screen-md">
        <h1 class="">Contact Us</h1>
        <p class="mb-8 lg:mb-16 text-center">Do you have questions? Want to contribute and get started? Want to report an error on this page? Please get in touch with us! </p>
        <form method="POST" action="{{ route('contact-us-process') }}" class="space-y-8" novalidate>
            @csrf
            


            <div  class="flex flex-col w-full gap-4">

                <div class="flex flex-row w-full gap-4">
                    @include('forms.components.freeText',[
                        'sectionName'   => 'email',
                        'title'         => 'Email *',
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
                        'title'         => 'Affiliation',
                        'placeholder'   => 'e.g. research institute, university',
                    ])
                </div>
    
            </div>

            <div class="w-full py-4">
                @include('forms.components.dropDownSelect',[
                    'sectionName'   => 'subject',
                    'title'         => 'Subject *',
                    'placeholder'   => 'Select subject',
                    'ElementsArray' =>    array(
                        'Report a bug or feedback',
                        'Contribute as a repository',
                    )
                ])
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