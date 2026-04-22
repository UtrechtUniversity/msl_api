<div class='mx-auto sm:p-4 w-full'>

    <div class="search-bar-container form-field-text p-0">

        <div class="search-bar-container-icon">
            <x-ri-search-line class="search-icon" />
        </div>

        {{-- Search bar --}}
        <form class="w-full h-16">
            <input type="hidden" name="page" value="1" />
            <input class="search-bar" type="text" id="search" placeholder="Search datapublications.."
                name="query[]" />

        </form>
    </div>

    <div class="flex flex-col justify-around pt-6 gap-3">
        <div class="flex max-[700px]:flex-col items-center place-content-center gap-3">
            {{-- content above map --}}
            <div class="basis-1/2 flex items-center gap-2 min-h-[40px]">
            </div>
        </div>
        <div>
        </div>

    </div>
</div>
