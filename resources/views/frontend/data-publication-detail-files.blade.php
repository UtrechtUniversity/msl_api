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
                    <p class="text-center">(click to download)</p>

                    <div class="bg-warning-300 rounded-lg 
                    flex flex-col place-items-center w-2/3
                    p-6
                    text-warning-900
                    ">
                        <x-ri-error-warning-line class="warning-icon"/>

                        <p class="text-center">Please note that this list is not exhaustive or complete and <span class="bg-none font-bold">under active development</span>. Each data repository manages data differently, which requires an interface to be developed and implented by MSL.</p>

                    </div>
                </div>
                    
                <div class="detailEntryDiv"> 

                    <div class="flex flex-wrap justify-center place-content-center gap-5 w-full">                
                        @if (array_key_exists("msl_downloads", $data))
                            @foreach ($data['msl_downloads'] as $download)

                                <div class="card bg-base-300 shadow-xl flex justify-around flex-row p-2 w-9/12 ">
                                    

                                    <a href="{!! $download['msl_download_link'] !!}" title="download file"
                                    class="no-underline"
                                    id=""
                                    >
                                        <x-ri-file-3-fill class="file-icon"/>
                                        &nbsp;
                                        &nbsp;
                                        &nbsp;
                                        <p class="inline underline">
                                            {{ $download['msl_file_name'] }}.{{ $download['msl_extension'] }}
                                        </p>
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <div class="detailEntryDiv flex flex-col place-items-center gap-4">
                                <div class="flex flex-col place-items-center bg-info-300
                                rounded-lg
                                w-2/3 p-6
                                text-info-900">

                                    <x-ri-emotion-sad-line class="info-icon size-14 fill-info-800"/>

                                    
                                    <p class=" text-center">No files found for this data publication or files not yet ingested by MSL. Check the source to make sure you dont miss anything:</p>
                                            
                                    @if (array_key_exists("msl_source",$data))
                                        <a class="detailEntrySub2 text-center" href="{{ $data['msl_source'] }}" target="_blank">{{ $data['msl_source'] }}</a>
                                    @endif
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