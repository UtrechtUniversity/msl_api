import { assertNotNull, assertNotUndefined } from "../helpers";
import {
    throwWhenCallBackNotInitialized,
    type ActiveFilterInfo,
    type FreeTextActiveInfo,
    type KeywordActiveInfo,
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

export class AppliedKeywordFilters {
    // In other components other than mapController, we don't keep track of state
    // Here we have to keep track of it, since order matters!
    private appliedFilters = new Map<string, ActiveFilterInfo>();
    private removeBinIcon: HTMLElement;
    private activeFilterContainer: HTMLElement;
    private onActiveFilterRemove: (
        opts: ActiveFilterInfo,
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
        onActiveFilterRemove: (opts: ActiveFilterInfo) => Promise<void>;
    }) {
        this.onActiveFilterRemove = onActiveFilterRemove;
    }

    private addAppliedFilterElement({
        displayName,
        id,
    }: {
        displayName: string;
        id: string;
    }) {
        // If type='add'
        //TODO explain this or move it to remove? Or have it as default for when we start?
        if (this.appliedFilters.size === 1)
            this.activeFilterContainer.innerHTML = "";
        this.removeBinIcon.hidden = false;

        const element = this.createKeywordElement({
            displayName,
            id,
        });
        const self = this;
        element.addEventListener("click", async () => {
            const mtdataInfo = self.appliedFilters.get(id);
            assertNotUndefined(
                mtdataInfo,
                `Active filter with display name '${displayName}' should exist. This is a bug.`,
            );
            await self.onActiveFilterRemove(mtdataInfo);
        });

        this.activeFilterContainer.appendChild(element);
    }

    private removeAppliedFilterElement({ id }: { id: string }) {
        if (!this.appliedFilters.size) {
            this.activeFilterContainer.innerHTML = noFiltersElement;
            this.removeBinIcon.hidden = true;
            return;
        }
        // If type='remove' and are filters left
        const elementToRemove = document.getElementById(id);
        assertNotNull(
            elementToRemove,
            `Element with id '${id}' could not be found. This is a bug.`,
        );
        elementToRemove.remove();
        return;
    }

    // Methods for mapcontroller to use
    // TODO we have to add 'remove all icon' too
    // todo add a listener for the new filter
    // todo Do we want to keep a state or do we want to repopulate?
    // TODO do we care about the order? We probably do, what is the best way to keep the order?
    // Map!

    public addFilter(opts: DistributiveOmit<ActiveFilterInfo, "id">): void {
        const { displayNameForUI, id } = this.getValuesFromMapFilters(opts);
        const metadata: ActiveFilterInfo = {
            ...opts,
            id,
        };

        this.appliedFilters.set(id, metadata);
        this.addAppliedFilterElement({
            displayName: displayNameForUI,
            id,
        });
    }

    public removeFilter({ id }: { id: string }): void {
        this.appliedFilters.delete(id);
        this.removeAppliedFilterElement({
            id,
        });
    }
    public removeKeywordFilter(
        opts: Pick<KeywordActiveInfo, "name" | "value">,
    ) {
        const id = getIdForActiveKeyword({
            value: opts.value,
            name: opts.name,
        });
        this.removeFilter({ id });
    }

    public removeFreeTextFilter(opts: Pick<FreeTextActiveInfo, "id">) {
        this.removeFilter({ id: opts.id });
    }
    private getValuesFromMapFilters(
        opts: DistributiveOmit<ActiveFilterInfo, "id">,
    ): { displayNameForUI: string; id: string } {
        if (opts.type === "freeText")
            return this.getValuesForFreeText({ value: opts.value });

        return this.getValuesForKeywords(opts);
    }

    private getValuesForKeywords({
        name,
        value,
        displayName,
    }: {
        name: string;
        value: string;
        displayName: string;
    }): {
        displayNameForUI: string;
        id: string;
    } {
        assertNotUndefined(
            displayName,
            "Field should by definition have a value. This is a bug.",
        );
        const displayNameForUI = displayName;
        const id = getIdForActiveKeyword({ value, name });
        return { displayNameForUI, id };
    }

    private getValuesForFreeText({ value }: { value: string }): {
        displayNameForUI: string;
        id: string;
    } {
        const displayNameForUI = "Search: " + value;
        const id = "freeText" + "_" + crypto.randomUUID();
        return { displayNameForUI, id };
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

function getIdForActiveKeyword({
    value,
    name,
}: {
    value: string;
    name: string;
}) {
    return "keyword" + "_" + (value !== "true" ? value : name);
}
