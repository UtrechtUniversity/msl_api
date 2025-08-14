@section('title', 'Data publication')
<x-layout_main>

    <div class="mainContentDiv">

        {{-- a general no small width view notification --}}
        @include('components.no_mobile_view')

        <div class="noMobileView_wideScreenDiv">

            <div class="absolute">

                @session('data_publication_active_search')
                    @include('components.tabLinks',[
                        // 'categoryName'  => 'Results',
                        'includeIcon'   => 'goBack',
                        'routes'        => array(
                                'Back to search results'   => $value,
                        )
                    ])
                @endsession
            </div>

        <div class="tabLinksParent">
            @include('components.tabLinks',[
                // 'categoryName'  => 'Sections',
                'routes'        => array(
                        'Metadata'   => route("data-publication-detail", ['id' => $data['name']]),
                        'Files'  => route("data-publication-detail-files", ['id' => $data['name']])
                ),
                'routeActive'   => route("data-publication-detail-files", ['id' => $data['name']])
            ])
        </div>


        <div class="listMapDetailDivParent">
            <div class="detailDiv">
                <div class="detailEntryDiv flex flex-col place-items-center gap-4">
                    <h2>Files</h2>
                    <h5 class="pt-10 font-bold">{{ $data['title'] }}</h5>
                    <h6 class="pb-10 italic"> {{ $data['msl_publisher'] }}</h6>

                    <div class="bg-warning-300 rounded-lg 
                    flex flex-col place-items-center w-2/3
                    p-6
                    text-warning-900
                    ">
                        <x-ri-error-warning-line class="warning-icon"/>

                        <p class="text-center">
                            Please note that this page may not contain all files of the data publication, 
                            as originally published at the source repository: 
                            <br>
                            <br>
                            @if (array_key_exists("msl_source",$data))
                                <a class="detailEntrySub2 text-center" href="{{ $data['msl_source'] }}" target="_blank">{{ $data['msl_source'] }}</a>
                            @endif 
                            <br>
                            <br>
                            Each data repository manages data differently, while the interface with MSL is being worked on continuously. 
                            In the meantime, we recommend visiting the page of the data repository to ensure you find all the files you’re looking for.
                        </p>

                    </div>
                </div>
                    
                <div class="detailEntryDiv"> 


                    <div class="
                        flex flex-col
                        place-items-center w-full
                        ">
                                        {{-- msl_files --}}
                        @if (array_key_exists("msl_files", $data))
                        <h3 class="text-center py-4">{{ count($data['msl_files']) }} files</h3>

                        @php
                            $allExtensions = [];
                            foreach ($data['msl_files'] as $download) {
                                if (! in_array($download['msl_extension'], $allExtensions) ) {
                                    $allExtensions [] = $download['msl_extension'];
                                }
                            }
                        @endphp

                        <p class="text-center pb-0">available file types: 

                            
                        </p>
                        <div class="text-center pt-0 flex">
                            @foreach ($allExtensions as $key => $extension)
                                    <p class="font-bold">.{{ $extension }}</p>
                                @if (sizeof($allExtensions) -1 != $key )
                                   <p class="px-2"> | </p> 
                                @endif
                            @endforeach
                        </div>


                        
                        <p class="text-center pt-6">(click to download)</p>

                            <div class='bg-primary-100 flex flex-wrap overflow-auto gap-5 w-1/2 h-96 p-4 rounded-md'>
                                
                                @foreach ($data['msl_files'] as $key => $download)

                                    <a class=" bg-base-300 shadow-md flex justify-around flex-row px-4 w-full hover:bg-secondary-100 "
                                        href="{!! $download['msl_download_link'] !!}" title="download file">

                                            <div class='flex justify-left items-center w-full'>
                                                <p class="no-underline px-4 w-20">{{ $key + 1 }}</p>
                                                <x-ri-file-3-fill class="file-icon"/>
                                                <div 
                                                id=""
                                                class='font-medium no-underline px-4'>
                                                    {{ $download['msl_file_name'] }}.{{ $download['msl_extension'] }}
                                                </div>
                                            </div>
                                    </a>

                                @endforeach
                            {{-- </div> --}}

                        @else
                            <div class="detailEntryDiv flex flex-col place-items-center gap-4">
                                <div class="flex flex-col place-items-center bg-info-300
                                rounded-lg
                                w-2/3 p-6
                                text-info-900">

                                    <x-ri-emotion-sad-line class="info-icon size-14 fill-info-800"/>
                                    
                                    <p class=" text-center">No files found for this data publication or files not yet ingested by MSL.</p>

                                </div>

                            </div>

                        @endif
                    </div>

                </div>                        
            </div>
        </div>
       
    </div>

@push('vite')
    @vite(['resources/js/tooltip.js'])
@endpush


</x-layout_main>