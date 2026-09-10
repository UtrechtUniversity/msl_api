<div class='mx-auto sm:p-2 w-full '>

    <div class="flex flex-col items-center w-full h-20   ">
        <div id="applied-filters-title" class="w-fit flex flex-row ">
            <h6 class="text-align-center inline"> Applied Filters </h6>
            <div id="remove-bin-icon" hidden>
                <div
                    class="
                                flex place-content-center
                                hover-interactive
                                p-2
                                size-fit
                                ">
                    <x-ri-delete-bin-2-line class="size-5 fill-error-600 rounded-full inline" />

                </div>
            </div>
        </div>
        {{-- This is where we will create elements for active filters or the default text element through js --}}
        <div class="  flex flex-wrap justify-center content-start w-full gap-1 overflow-y-auto"
            id="active-filter-container">

        </div>

        <script>
            tippy('#remove-all-popup', {
                content: "remove all filters",
                placement: "right",
                theme: "msl"
            });
            tippy.delegate('#active-filter-container', {
                target: '.word-card',
                content: "click to remove filter",
                theme: "msl",
                placement: "right"
            });
        </script>
    </div>
</div>
