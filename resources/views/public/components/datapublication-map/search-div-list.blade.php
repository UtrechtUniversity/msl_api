<div class='mx-auto sm:p-4 w-full'>

    <div class="flex flex-col justify-center items-center h-16 w-full ">
        <div id="applied-filters-title" class="w-fit flex flex-row items-center place-content-center gap-3 ">
            <h5 class="text-align-center inline"> Applied Filters </h5>
            <div id="remove-bin-icon" hidden>
                <div
                    class="
                                flex place-content-center
                                hover-interactive
                                p-2
                                size-fit
                                ">
                    <x-ri-delete-bin-2-line class="remove-all-icon" />

                </div>
            </div>
        </div>
        {{-- This is where we will create elements for active filters or the default text element through js --}}
        <div class="word-card-parent" id="active-filter-container">

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
