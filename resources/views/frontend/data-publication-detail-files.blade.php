@section('title', 'Data publication')
<x-layout_main>

    <div class="flex flex-col sm:flex-row pt-10 sm:pt-0 justify-center items-center w-full relative">
        @session('data_publication_active_search')
            <div class="px-2 md:px-10 sm:absolute left-0 ">
                    <a href="{{ $value }}">
                        <div class="btn btn-primary btn-wide bg-primary-200">
                            <x-ri-arrow-left-line id="" class="goBack-icon inline"/>
                            Back to search results
                        </div>
                    </a>
            </div>
        @endsession
        <div class="tab-links-parent ">
            @include('components.tab-links',[
                // 'categoryName'  => 'Sections',
                'routes'        => array(
                        'Metadata'  => route("data-publication-detail", ['id' => $data['name']]),
                        'Files'     => route("data-publication-detail-files", ['id' => $data['name']])
                ),
                'routeActive'   => route("data-publication-detail-files", ['id' => $data['name']])
            ])
        </div>
    </div>

    <div class="main-content ">

        <div class="detail-div justify-center">

                <h2>Files</h2>
                <h5 class="pt-10 font-bold">{{ $data['title'] }}</h5>
                @if (array_key_exists("msl_publisher", $data))
                <h6 class="pb-10 italic"> {{ $data['msl_publisher'] }}</h6>
                @endif
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


                @if (array_key_exists("msl_files", $data))

                    @php
                        $allFolders = [];
                        $allFiles = [];
                        $allExtensions = [];
                        foreach ($data['msl_files'] as $download) {
                            if (! in_array($download['msl_extension'], $allExtensions) && $download['msl_extension'] != '' ) {
                                $allExtensions [] = $download['msl_extension'];
                            }
                            if ($download['msl_is_folder']) {
                                $allFolders[] = $download;

                            } else {
                                $allFiles[] = $download;
                            }
                        }
                    @endphp

                    @if (count($allFolders) != 0)
                        <h3 class="text-center py-2 pt-20">
                            {{ count($allFolders) }} folders
                        </h3>
                    @endif

                    @if ( count($allFiles) != 0)
                        <h3 class="text-center py-2">
                            {{ count($allFiles) }} files
                        </h3>
                    @endif

                    <p class="text-center pb-0">available file types</p>
                    <div class="text-center pt-0 flex">
                        @foreach ($allExtensions as $key => $extension)
                                <p class="font-bold">.{{ $extension }}</p>
                            @if (sizeof($allExtensions) -1 != $key )
                            <p class="px-2"> | </p> 
                            @endif
                        @endforeach
                    </div>

                    <p class="text-center pt-6 pb-0 italic">for files: click downloads</p>
                    <p class="text-center pt-0 italic">for folders: click opens new tab</p>

                    <div class='bg-primary-100 flex flex-wrap overflow-auto gap-5 w-1/2 max-h-96 p-4 rounded-md content-start'>
                        

                        @foreach ($allFolders as $key => $download)

                            <a class=" bg-base-300 shadow-md flex justify-around flex-row p-1 w-full hover-interactive h-12"
                                href="{!! $download['msl_download_link'] !!}" title="download file">

                                    <div class='flex justify-left items-center w-full'>
                                        <div class=""> <x-ri-folder-3-fill class="folder-icon mx-6"/></div>
                                        <div class="overflow-hidden ">                                                
                                            <p class='no-underline py-0 px-4 '>
                                            {{ $download['msl_file_name'] }}.{{ $download['msl_extension'] }}
                                        </p></div>
                                    </div>
                            </a>

                        @endforeach

                        @foreach ($allFiles as $key => $download)

                            <a class=" bg-base-300 shadow-md flex justify-around flex-row px-4 w-full hover:bg-secondary-100 h-12"
                                href="{!! $download['msl_download_link'] !!}" title="download file">

                                    <div class='flex flex-row justify-left items-center w-full'>
                                        <div> <p class="no-underline py-0 px-4 w-20">{{ $key + 1 }}</p> </div>
                                        <div> <x-ri-file-3-fill class="file-icon mr-6"/> </div>
                                        <div class="overflow-hidden py-0 px-4">                                                
                                            <p class='no-underline '>
                                            {{ $download['msl_file_name'] }}
                                        </p></div>
                                    </div>
                            </a>

                        @endforeach
                    </div>

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

@push('vite')
    @vite(['resources/js/tooltip.js'])
@endpush


</x-layout_main>