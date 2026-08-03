import { assertNotNull, assertNotUndefined } from "../helpers";
const EnrichedKeywordsField = "msl_enriched_keyword_uri" as const;
const OriginalKeywordsField = "msl_original_keyword_uri" as const;

const noFiltersElement = `<h6 class="italic">- no filter applied -</h6>`;

export class AppliedKeywordFilters {
    // todo can insert key values and remembers the order
    appliedFilters = new Map();
    textFieldElement: HTMLElement;
    removeBinIcon: HTMLElement;
    constructor() {
        const textFieldElement = document.getElementById(
            "applied-filters-text",
        );
        assertNotNull(
            textFieldElement,
            `Text field element for applied keywords could not be found. This is a bug.`,
        );
        this.textFieldElement = textFieldElement;
        this.textFieldElement.innerHTML = noFiltersElement;

        const appliedFiltersTitleElement =
            document.getElementById("remove-bin-icon");
        assertNotNull(
            appliedFiltersTitleElement,
            `Remove bin field element for applied keywords could not be found. This is a bug.`,
        );
        appliedFiltersTitleElement.hidden = true;
        this.removeBinIcon = appliedFiltersTitleElement;
    }

    // Methods for mapcontroller to use
    // TODO we have to add 'remove all icon' too
    // todo add a listener for the new filter
    // todo Do we want to keep a state or do we want to repopulate?
    // TODO do we care about the order? We probably do, what is the best way to keep the order?
    // Map!
    public addFilter({
        field,
        value,
        type,
    }: {
        field: string;
        value: string;
        type: "keyword";
    }): void;
    public addFilter({
        value,
        type,
    }: {
        value: string;
        type: "freeText";
    }): void;
    public addFilter({
        field,
        value,
        type,
    }: {
        field?: string;
        value: string;
        type: "keyword" | "freeText";
    }): void {
        const { keyForMap, appliedValue } = this.getValuesFromMapFilters({
            field,
            value,
            type,
        });

        this.appliedFilters.set(keyForMap, appliedValue);
        this.updateAppliedFilterElements("add");
    }

    public removeFilter({
        field,
        value,
        type,
    }: {
        field: string;
        value: string;
        type: "keyword";
    }): void;
    public removeFilter({
        value,
        type,
    }: {
        value: string;
        type: "freeText";
    }): void;
    public removeFilter({
        field,
        value,
        type,
    }: {
        field?: string;
        value: string;
        type: "keyword" | "freeText";
    }): void {
        const { keyForMap } = this.getValuesFromMapFilters({
            field,
            value,
            type,
        });
        this.appliedFilters.delete(keyForMap);
        this.updateAppliedFilterElements("remove");
    }

    //   <div>
    //         <div class="flex flex-col items-center place-content-center gap-2">

    //             <div class="w-fit flex flex-row items-center place-content-center gap-3 ">
    //                 <h5 class="inline">Applied Filters </h5>
    //                 @if (count($activeFiltersFrontend) > 0)
    //                     <a href="{{ route('data-access') }}" id="remove-all-popup">
    //                         <div
    //                             class="
    //                             flex place-content-center
    //                             hover-interactive
    //                             p-2
    //                             size-fit
    //                             ">
    //                             <x-ri-delete-bin-2-line class="remove-all-icon" />

    //                         </div>

    //                         <script>
    //                             tippy('#remove-all-popup', {
    //                                 content: "remove all filters",
    //                                 placement: "right",
    //                                 theme: "msl"
    //                             });
    //                         </script>
    //                     </a>
    //                 @endif
    //             </div>

    //             <div class="word-card-parent" id="active-filter-container">

    //                 @if (count($activeFiltersFrontend) > 0)
    //                     @foreach ($activeFiltersFrontend as $filter)
    //                         <a href="{{ $filter['removeUrl'] }}" class="">
    //                             @include('public.components.word-card', [
    //                                 'word' => $filter['label'],
    //                                 'closeIcon' => true,
    //                             ])
    //                         </a>
    //                     @endforeach
    //                     <script>
    //                         tippy.delegate('#active-filter-container', {
    //                             target: '.word-card',
    //                             content: "click to remove filter",
    //                             theme: "msl",
    //                             placement: "right"
    //                         });
    //                     </script>
    //                 @else
    //                     <h6 class="italic">- no filter applied -</h6>
    //                 @endif
    //             </div>

    private updateAppliedFilterElements(type: "add" | "remove") {
        if (!this.appliedFilters.size) {
            this.textFieldElement.textContent = noFiltersElement;
            this.removeBinIcon.hidden = true;

            return;
        }
        this.removeBinIcon.hidden = false;
    }

    private getValuesFromMapFilters({
        field,
        value,
        type,
    }: {
        field?: string | undefined;
        value: string;
        type: "keyword" | "freeText";
    }): { keyForMap: string; appliedValue: string } {
        let appliedValue = "";
        let keyForMap = "";
        if (type === "freeText") {
            keyForMap = value;
            appliedValue = "Search: " + value;
        } else {
            assertNotUndefined(
                field,
                "Field should by definition have a value. This is a bug.",
            );
            keyForMap = appliedValue =
                field === OriginalKeywordsField ||
                field === EnrichedKeywordsField
                    ? value
                    : field;
        }
        return { keyForMap, appliedValue };
    }
}
