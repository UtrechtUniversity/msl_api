import { assertNotNull } from "../helpers";
import {
    FREE_TEXT_SEARCH_KEYWORD,
    throwWhenCallBackNotInitialized,
    TREE_KEYWORD,
    type ActiveKeywordFilterInfo,
    type FreeTextAddInfoWithType,
    type KeywordType,
    type TreeKeywordAddInfoWithType,
} from "./utils";

const NO_FILTER_ELEMENT =
    `<h6 class="italic">- no filter applied -</h6>` as const;
const CLOSE_ICON = `
        <svg class="close-icon" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M11.9997 10.5865L16.9495 5.63672L18.3637 7.05093L13.4139 12.0007L18.3637 16.9504L16.9495 18.3646L11.9997 13.4149L7.04996 18.3646L5.63574 16.9504L10.5855 12.0007L5.63574 7.05093L7.04996 5.63672L11.9997 10.5865Z"
            ></path>
        </svg>` as const;

export class AppliedKeywordFilters {
    private areExistingAppliedFilters = false;
    private removeBin: HTMLElement;
    private activeFilterContainer: HTMLElement;
    private onActiveKeywordRemove: (opts: {
        id: string;
    }) => Promise<void> | void = throwWhenCallBackNotInitialized;
    private onActiveFreeTextRemove: (opts: {
        id: string;
    }) => Promise<void> | void = throwWhenCallBackNotInitialized;
    private onActiveFilterRemoveAll: () => void | Promise<void> =
        throwWhenCallBackNotInitialized;

    constructor() {
        const removeBin = getElementOrThrow(
            "remove-bin-icon",
            "Remove bin field",
        );
        removeBin.hidden = true;
        removeBin.addEventListener("click", () => {
            this.onActiveFilterRemoveAll();
        });
        this.removeBin = removeBin;

        this.activeFilterContainer = getElementOrThrow(
            "active-filter-container",
            "Active filter container ",
        );
        this.activeFilterContainer.innerHTML = NO_FILTER_ELEMENT;
    }

    public setHandlerfn({
        onActiveTreeKeywordRemove: onActiveKeywordRemove,
        onActiveFreeTextKeywordRemove: onActiveFreeTextRemove,
        onActiveFilterRemoveAll,
    }: {
        onActiveTreeKeywordRemove: (opts: { id: string }) => Promise<void>;
        onActiveFreeTextKeywordRemove: (opts: { id: string }) => Promise<void>;
        onActiveFilterRemoveAll: () => Promise<void>;
    }) {
        this.onActiveKeywordRemove = onActiveKeywordRemove;
        this.onActiveFreeTextRemove = onActiveFreeTextRemove;

        this.onActiveFilterRemoveAll = onActiveFilterRemoveAll;
    }

    private addAppliedFilterElement({
        type,
        displayName,
        id,
    }: {
        type: KeywordType;
        displayName: string;
        id: string;
    }) {
        if (!this.areExistingAppliedFilters)
            this.activeFilterContainer.innerHTML = "";

        this.areExistingAppliedFilters = true;

        this.removeBin.hidden = false;

        const element = createKeywordElement({
            displayName,
            id,
        });
        element.addEventListener("click", async () => {
            type === TREE_KEYWORD
                ? await this.onActiveKeywordRemove({ id })
                : await this.onActiveFreeTextRemove({ id });
        });

        this.activeFilterContainer.appendChild(element);
    }

    private removeAppliedFilterElement({ id }: { id: string }) {
        const elementToRemove = document.getElementById(id);
        assertNotNull(
            elementToRemove,
            `Element with id '${id}' could not be found. This is a bug.`,
        );
        elementToRemove.remove();

        return;
    }

    public addFilter(opts: ActiveKeywordFilterInfo): void {
        const { displayNameForUI } = this.getDisplayNameForKeywords(opts);

        this.addAppliedFilterElement({
            type: opts.type,
            displayName: displayNameForUI,
            id: opts.id,
        });
    }
    public removeAllActiveKeywordFilters() {
        this.areExistingAppliedFilters = false;
        this.resetAppliedKeywordsInUI();
    }
    public removeFilter({ id }: { id: string }): void {
        this.removeAppliedFilterElement({
            id,
        });
    }
    public removeKeywordFilter(opts: { id: string }) {
        this.removeFilter(opts);
    }

    public removeFreeTextFilter(opts: { id: string }) {
        this.removeFilter(opts);
    }
    private getDisplayNameForKeywords(
        opts: TreeKeywordAddInfoWithType | FreeTextAddInfoWithType,
    ): { displayNameForUI: string } {
        if (opts.type === FREE_TEXT_SEARCH_KEYWORD)
            return { displayNameForUI: "Search: " + opts.value };

        return { displayNameForUI: opts.displayName };
    }

    private resetAppliedKeywordsInUI() {
        this.removeBin.hidden = true;
        this.activeFilterContainer.innerHTML = NO_FILTER_ELEMENT;
    }
}

function getElementOrThrow(elementId: string, name: string) {
    const element = document.getElementById(elementId);
    assertNotNull(
        element,
        ` Element '${name}'could not be found. This is a bug.`,
    );
    return element;
}

function createKeywordElement({
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
                            ${CLOSE_ICON}
                        <text class="word-value"> ${displayName} </text>
                    </div>

                    <div
                        class="word-card hover-neutral hidden group-hover:block w-fit group-hover:wrap-anywhere group-hover:absolute group-hover:top-0 group-hover:left-0 group-hover:z-10"
                    >
                    ${CLOSE_ICON}
                        <text class="word-value">${displayName}</text>
                    </div>
                </div>

                            `;
    wrapper.innerHTML = htmlString;
    return wrapper.firstElementChild as HTMLElement;
}
