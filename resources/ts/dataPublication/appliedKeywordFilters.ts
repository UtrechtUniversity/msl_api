import { assertNotNull, assertNotUndefined } from "../helpers";
import {
    throwWhenCallBackNotInitialized,
    type ActiveFilterInfo,
    type freeTextFilterInfo,
    type keywordFilterInfo,
} from "./utils";

const noFiltersElement = `<h6 class="italic">- no filter applied -</h6>`;
const closeIcon = `
        <svg class="close-icon" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M11.9997 10.5865L16.9495 5.63672L18.3637 7.05093L13.4139 12.0007L18.3637 16.9504L16.9495 18.3646L11.9997 13.4149L7.04996 18.3646L5.63574 16.9504L10.5855 12.0007L5.63574 7.05093L7.04996 5.63672L11.9997 10.5865Z"
            ></path>
        </svg>`;

type DistributiveOmit<T, K extends PropertyKey> = T extends any
    ? Omit<T, K>
    : never;
type la = DistributiveOmit<ActiveFilterInfo, "id">;

export class AppliedKeywordFilters {
    // todo can insert key values and remembers the order
    private appliedFilters = new Map<string, ActiveFilterInfo>();
    private removeBinIcon: HTMLElement;
    private activeFilterContainer: HTMLElement;
    private onActiveFilterRemove: (
        opts: freeTextFilterInfo | keywordFilterInfo,
    ) => Promise<void> | void = throwWhenCallBackNotInitialized;

    constructor() {
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
        this.activeFilterContainer.innerHTML = noFiltersElement;
    }

    public setHandlerfn({
        onActiveFilterRemove,
    }: {
        onActiveFilterRemove: (
            opts: freeTextFilterInfo | keywordFilterInfo,
        ) => Promise<void>;
    }) {
        this.onActiveFilterRemove = onActiveFilterRemove;
    }
    private updateAppliedFilterElements({
        updateType,
        displayName,
        id,
    }: {
        updateType: "add" | "remove";
        displayName: string;
        id: string;
    }) {
        // If type='remove' and no filters left
        if (!this.appliedFilters.size) {
            this.activeFilterContainer.innerHTML = noFiltersElement;
            this.removeBinIcon.hidden = true;
            return;
        }
        // If type='remove' and are filters left
        if (updateType === "remove") {
            document.getElementById(id)?.remove();
        }
        // If type='add'
        if (this.appliedFilters.size === 1)
            this.activeFilterContainer.innerHTML = "";
        this.removeBinIcon.hidden = false;

        const element = this.createKeywordElement({
            displayName,
            id,
        });
        const self = this;
        element.addEventListener("click", async () => {
            const mtdataInfo = self.appliedFilters.get(displayName);
            assertNotUndefined(
                mtdataInfo,
                `Active filter with display name '${displayName}' should exist. This is a bug.`,
            );
        });

        this.activeFilterContainer.appendChild(element);
    }

    // Methods for mapcontroller to use
    // TODO we have to add 'remove all icon' too
    // todo add a listener for the new filter
    // todo Do we want to keep a state or do we want to repopulate?
    // TODO do we care about the order? We probably do, what is the best way to keep the order?
    // Map!
    public addFilter({
        name,
        value,
        type,
        displayName,
    }: {
        name: string;
        value: string;
        type: "keyword";
        displayName: string;
    }): void;
    public addFilter({
        value,
        type,
    }: {
        value: string;
        type: "freeText";
    }): void;
    public addFilter(opts: DistributiveOmit<ActiveFilterInfo, "id">): void {
        const { displayNameForUI, id } = this.getValuesFromMapFilters({
            value: opts.value,
            displayName: opts.type === "keyword" ? opts.displayName : undefined,
            type: opts.type,
        });
        const metadata: ActiveFilterInfo = {
            ...opts,
            id,
        };

        this.appliedFilters.set(displayNameForUI, metadata);
        this.updateAppliedFilterElements({
            updateType: "add",
            displayName: displayNameForUI,
            id,
        });
    }

    public removeFilter({
        displayName,
        value,
        type,
    }: {
        displayName: string;
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
        value,
        type,
        displayName,
    }: {
        value: string;
        type: "keyword" | "freeText";
        displayName?: string;
    }): void {
        const { displayNameForUI, id } = this.getValuesFromMapFilters({
            value,
            type,
            displayName,
        });
        this.appliedFilters.delete(displayNameForUI);
        this.updateAppliedFilterElements({
            updateType: "remove",
            displayName: displayNameForUI,
            id,
        });
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
        value,
        displayName,
        type,
    }: {
        value: string;
        displayName: string | undefined;
        type: "keyword" | "freeText";
    }): { displayNameForUI: string; id: string } {
        let displayNameForUI = "";
        if (type === "freeText") {
            displayNameForUI = "Search: " + value;
        } else {
            assertNotUndefined(
                displayName,
                "Field should by definition have a value. This is a bug.",
            );
            displayNameForUI = displayName;
        }
        return { displayNameForUI, id: type + "_" + value };
    }
    private createKeywordElement({
        displayName,
        id,
    }: {
        displayName: string;
        id: string;
    }): HTMLElement {
        const wrapper = document.createElement("div");
        const htmlString = `  
                <div id=${id} class="keyword-word-card group h-fit max-w-60 relative hover:overflow-visible">
                    <div class="word-card truncate">
                            ${closeIcon}
                        <text class="word-value"> ${displayName} </text>
                    </div>

                    <div
                        class="word-card hover-neutral hidden group-hover:block w-fit group-hover:wrap-anywhere group-hover:absolute group-hover:top-0 group-hover:left-0 group-hover:z-10"
                    >
                    ${closeIcon}
                        <text class="word-value">${displayName}</text>
                    </div>
                </div>

                            `;
        wrapper.innerHTML = htmlString;
        return wrapper.firstElementChild! as HTMLElement;
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
