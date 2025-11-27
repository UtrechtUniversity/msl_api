@section('title', 'Datapublications map')
<x-layout_main>

    <div class="mainContentDiv">

        {{-- a general no small width view notification --}}
        @include('components.no_mobile_view')

        {{-- top div --}}
        <div class="noMobileView_wideScreenDiv">



            {{-- content bottom div --}}
            <div class="listMapDetailDivParent">


                {{-- main field --}}
                <div class="listMapDiv">


                    {{-- list view --}}
                    <div class="listView">

                        {{-- loop list content --}}
                        @include('components.dp-map-view')


                    </div>



                </div>


            </div>

        </div>

    </div>

</x-layout_main>