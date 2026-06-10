<div class='mx-auto sm:p-4 w-full'>

    <div class="search-bar-container form-field-text p-0">
        {{-- Search bar --}}
        <div class="search-bar-container-icon">
            <x-ri-search-line class="search-icon" />
        </div>

        <form class="w-full h-16" disabled>
            <input class="search-bar opacity-25 " type="text" id="search" placeholder="Search datapublications.."
                name="query[]" disabled />
        </form>
    </div>

    <div class="flex justify-around pt-6 gap-3">
        <div class="flex max-[700px]:flex-col items-center place-content-center gap-3">
            {{-- content above map --}}
            <div class="basis-1/2 flex items-center gap-2 min-h-[40px]">
            </div>
        </div>
        <div>

        </div>
    </div>
</div>
