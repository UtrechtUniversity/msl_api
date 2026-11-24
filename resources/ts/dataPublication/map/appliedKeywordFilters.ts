import {
    getElementByIdOrThrow,
    throwWhenCallBackNotInitialized,
} from "../../assertions";
import {
    FREE_TEXT_SEARCH_QUERY,
    TREE_KEYWORD,
    type ActiveTextFilterInfo,
    type FreeTextSearchQueryInfoWithType,
    type KeywordType,
    type TreeKeywordAddInfoWithType,
} from "../../types/map/keywordFilters";
import { CLOSE_ICON } from "./icons";

const NO_FILTER_ELEMENT =
    `<h6 class="italic">- no filter applied -</h6>` as const;

export class AppliedKeywordFilters {
    private hasExistingAppliedTextFilters = false;
    private removeBin: HTMLElement;
    private activeFilterContainer: HTMLElement;
    private onActiveTreeKeywordFilterRemove: (opts: {
        id: string;
    }) => Promise<void> | void = throwWhenCallBackNotInitialized;
    private onActiveFreeTextSearchQueryRemove: (opts: {
        id: string;
    }) => Promise<void> | void = throwWhenCallBackNotInitialized;
    private onActiveTextFilterRemoveAll: () => void | Promise<void> =
        throwWhenCallBackNotInitialized;

    constructor() {
        const removeBin = getElementByIdOrThrow("remove-bin-icon");
        removeBin.hidden = true;
        removeBin.addEventListener("click", async () => {
            await this.onActiveTextFilterRemoveAll();
        });
        this.removeBin = removeBin;

        this.activeFilterContainer = getElementByIdOrThrow(
            "active-filter-container",
        );
        this.activeFilterContainer.innerHTML = NO_FILTER_ELEMENT;
    }

    public setHandlerfn({
        onActiveTreeKeywordFilterRemove,
        onActiveFreeTextSearchQueryRemove,
        onActiveTextFilterRemoveAll,
    }: {
        onActiveTreeKeywordFilterRemove: (opts: {
            id: string;
        }) => Promise<void>;
        onActiveFreeTextSearchQueryRemove: (opts: {
            id: string;
        }) => Promise<void>;
        onActiveTextFilterRemoveAll: () => Promise<void>;
    }) {
        this.onActiveTreeKeywordFilterRemove = onActiveTreeKeywordFilterRemove;
        this.onActiveFreeTextSearchQueryRemove =
            onActiveFreeTextSearchQueryRemove;
        this.onActiveTextFilterRemoveAll = onActiveTextFilterRemoveAll;
    }

    private addAppliedTextFilterElement({
        type,
        displayName,
        id,
    }: {
        type: KeywordType;
        displayName: string;
        id: string;
    }) {
        if (!this.hasExistingAppliedTextFilters)
            this.activeFilterContainer.innerHTML = "";

        this.hasExistingAppliedTextFilters = true;

        this.removeBin.hidden = false;

        const element = createAppliedTextFilterElement({
            displayName,
            id,
        });
        element.addEventListener("click", async () => {
            type === TREE_KEYWORD
                ? await this.onActiveTreeKeywordFilterRemove({ id })
                : await this.onActiveFreeTextSearchQueryRemove({ id });
        });

        this.activeFilterContainer.appendChild(element);
    }

    private removeAppliedTextFilterElement({ id }: { id: string }) {
        const elementToRemove = getElementByIdOrThrow(id);
        elementToRemove.remove();

        return;
    }

    public addTextFilter(opts: ActiveTextFilterInfo): void {
        const { displayNameForUI } = this.getDisplayNameForTextFilters(opts);

        this.addAppliedTextFilterElement({
            type: opts.type,
            displayName: displayNameForUI,
            id: opts.id,
        });
    }
    public removeAllActiveTextFilters() {
        this.hasExistingAppliedTextFilters = false;
        this.resetAppliedTextFiltersInUI();
    }
    public removeAppliedTextFilter({ id }: { id: string }): void {
        this.removeAppliedTextFilterElement({
            id,
        });
    }

    private getDisplayNameForTextFilters(
        opts: TreeKeywordAddInfoWithType | FreeTextSearchQueryInfoWithType,
    ): { displayNameForUI: string } {
        if (opts.type === FREE_TEXT_SEARCH_QUERY)
            return { displayNameForUI: "Search: " + opts.value };

        return { displayNameForUI: opts.displayName };
    }

    private resetAppliedTextFiltersInUI() {
        this.removeBin.hidden = true;
        this.activeFilterContainer.innerHTML = NO_FILTER_ELEMENT;
    }
}

function createAppliedTextFilterElement({
    displayName,
    id,
}: {
    displayName: string;
    id: string;
}): HTMLElement {
    const wrapper = document.createElement("div");

    const htmlString = `  
                <div id=${id} class="group h-fit max-w-60 relative hover:overflow-visible">

                    <div class="word-card truncate">
                        ${CLOSE_ICON}
                        <text class="word-value text-base"> ${displayName.substring(0, 40)} </text>
                    </div>

                     <div class="word-card hover-neutral hidden group-hover:block w-fit group-hover:wrap-anywhere group-hover:absolute group-hover:top-0 group-hover:left-0 group-hover:z-10">
                        ${CLOSE_ICON}
                        <text class="word-value">${displayName}</text>
                    </div>

                </div>

                            `;
    wrapper.innerHTML = htmlString;
    return wrapper.firstElementChild as HTMLElement;
}
