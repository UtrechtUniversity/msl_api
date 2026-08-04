import { assertNotNull, assertNotUndefined } from "../helpers";
import { throwWhenCallBackNotInitialized } from "./utils";
const EnrichedKeywordsField = "msl_enriched_keyword_uri" as const;
const OriginalKeywordsField = "msl_original_keyword_uri" as const;

const noFiltersElement = `<h6 class="italic">- no filter applied -</h6>`;
const closeIcon = `
        <svg class="close-icon" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M11.9997 10.5865L16.9495 5.63672L18.3637 7.05093L13.4139 12.0007L18.3637 16.9504L16.9495 18.3646L11.9997 13.4149L7.04996 18.3646L5.63574 16.9504L10.5855 12.0007L5.63574 7.05093L7.04996 5.63672L11.9997 10.5865Z"
            ></path>
        </svg>`;
export class AppliedKeywordFilters {
    // todo can insert key values and remembers the order
    private appliedFilters = new Map<string, string>();
    private textFieldElement: HTMLElement;
    private removeBinIcon: HTMLElement;
    private activeFilterContainer: HTMLElement;
    private onActiveFilterUpdate: (
        type: "remove" | "add",
        filter: {
            key: string;
            value: string;
        },
    ) => Promise<void> | void = throwWhenCallBackNotInitialized;

    constructor() {
        this.textFieldElement = getElementInActiveFilters(
            "applied-filters-text",
            "Applied filter text",
        );
        this.textFieldElement.innerHTML = noFiltersElement;

        const appliedFiltersTitleElement = getElementInActiveFilters(
            "remove-bin-icon",
            "Remove bin field",
        );
        appliedFiltersTitleElement.hidden = true;
        this.removeBinIcon = appliedFiltersTitleElement;

        this.activeFilterContainer = getElementInActiveFilters(
            "active-filter-container",
            "Active filter container ",
        );
    }

    public setHandlerfn({
        onActiveFilterUpdate,
    }: {
        onActiveFilterUpdate: (
            type: "remove" | "add",
            filter: {
                key: string;
                value: string;
            },
        ) => Promise<void>;
    }) {
        this.onActiveFilterUpdate = onActiveFilterUpdate;
    }
    private updateAppliedFilterElements(type: "add" | "remove") {
        if (!this.appliedFilters.size) {
            //TODO this deletes the element below
            this.activeFilterContainer.innerHTML = "";
            this.textFieldElement.textContent = noFiltersElement;
            this.removeBinIcon.hidden = true;

            return;
        }
        this.removeBinIcon.hidden = false;
        let elements = "";
        for (const filter of this.appliedFilters.values()) {
            elements += this.createKeywordElement(filter);
        }
        this.activeFilterContainer.innerHTML = elements;

        $("div.keyword-word-card").on("click", function () {
            console.log("here");
        });
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
    //
    //                 @else
    //                     <h6 class="italic">- no filter applied -</h6>
    //                 @endif
    //             </div>

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
    private createKeywordElement(name: string): string {
        return `  
                <div class="keyword-word-card group h-fit max-w-60 relative hover:overflow-visible">
                    <div class="word-card truncate">
                            ${closeIcon}
                        <text class="word-value"> ${name} </text>
                    </div>

                    <div
                        class="word-card hover-neutral hidden group-hover:block w-fit group-hover:wrap-anywhere group-hover:absolute group-hover:top-0 group-hover:left-0 group-hover:z-10"
                    >
                    ${closeIcon}
                        <text class="word-value">${name}</text>
                    </div>
                </div>

                            `;
    }
}

function getElementInActiveFilters(elementId: string, name: string) {
    const elementInActiveFilters = document.getElementById(elementId);
    assertNotNull(
        elementInActiveFilters,
        ` Element '${name}' in applied keywords could not be found. This is a bug.`,
    );
    return elementInActiveFilters;
}
