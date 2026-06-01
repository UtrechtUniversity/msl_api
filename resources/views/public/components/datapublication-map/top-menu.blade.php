    <div>
        @vite(['resources/css/datapublicationMap/top-menu.css'])
    </div>
    <div id='datapublication-menu' class="flex flex-col w-full pt-6 gap-3">
        {{-- content above map --}}
        <div class="flex gap-2 min-h-[40px] flex-row justify-between w-full">
            <div class='flex-1  flex flex-col items-center justify-center '>
                <div>
                    <h6> Spatial Filter Settings </h6>
                </div>
                <div class='flex flex-row gap-3'>
                    <div class="py-4">
                        <button id='overlapping-filter-btn' class=" menu-btn btn btn-md ">
                            <i class="fa-solid fa-xmark"></i>
                            <span>Overlapping</span>
                        </button>
                    </div>
                    <div class="py-4">
                        <button id='inside-filter-btn' class="menu-btn btn btn-md">
                            <i class="fa-solid fa-circle-xmark"></i>
                            <span>Inside</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class='flex-1  flex flex-col items-center justify-center'>
                <div>
                    <h6> Spatial Interactions </h6>
                </div>
                <div class='flex flex-row gap-3'>
                    <div class="py-4">
                        <button id='spatial-draw'class="menu-btn btn btn-md ">Draw spatial filter</button>
                    </div>
                    <div class="py-4">
                        <button id='spatial-remove' class="menu-btn btn btn-md ">Remove spatial filter</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
