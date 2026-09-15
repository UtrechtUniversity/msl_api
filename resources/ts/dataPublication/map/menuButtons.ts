import { getElementOrThrow } from "../../helpers";
import {
    INSIDE,
    OVERLAPPING,
    type DrawingActionType,
    type GeoFeatureResultSet,
    type Inside,
    type Overlapping,
} from "../../types/map";
import {
    getDefaultTab,
    TAB_CONFIG,
    throwWhenCallBackNotInitialized,
} from "../utils";

const ACTIVE = "active" as const;
const OVERLAPPING_BUTTON_ID = "overlapping-filter-btn" as const;
const INSIDE_BUTTON_ID = "inside-filter-btn" as const;
const SPATIAL_DRAW_ID = "spatial-draw" as const;
const SPATIAL_REMOVE_ID = "spatial-remove" as const;

const L = window.L;
export class MenuButtons {
    overlappingFilterButton: HTMLButtonElement;
    insideFilterButton: HTMLButtonElement;
    spatialDrawButton: HTMLButtonElement;
    spatialRemoveButton: HTMLButtonElement;
    root: HTMLElement;
    drawingEnabled: boolean = false;

    private onEnableDrawing: () => void = throwWhenCallBackNotInitialized;
    private onCompleteDrawing: () => Promise<false | true> | void =
        throwWhenCallBackNotInitialized;
    private onRemoveDrawing: () => Promise<void> | void =
        throwWhenCallBackNotInitialized;
    private onSpatialFilter: (type: GeoFeatureResultSet) => void =
        throwWhenCallBackNotInitialized;
    constructor() {
        this.overlappingFilterButton = this.createButton({
            id: OVERLAPPING_BUTTON_ID,
            text: "Overlapping",
            disabled: true,
            includeIcon: OVERLAPPING,
        });

        this.insideFilterButton = this.createButton({
            id: INSIDE_BUTTON_ID,
            text: "Inside",
            disabled: true,
            includeIcon: INSIDE,
        });

        this.spatialDrawButton = this.createButton({
            id: SPATIAL_DRAW_ID,
            text: "Draw spatial filter",
            disabled: false,
        });

        this.spatialRemoveButton = this.createButton({
            id: SPATIAL_REMOVE_ID,
            text: "Remove spatial filter",
            disabled: true,
        });
        this.addListenersInButtons();

        this.root = this.createMenu();

        const mapElement = getElementOrThrow("map");

        mapElement.appendChild(this.root);
    }

    public setHandlerfn({
        onEnableDrawing,
        onCompleteDrawing,
        onRemoveDrawing,
        onSpatialFilter,
    }: {
        onEnableDrawing: () => void;
        onCompleteDrawing: () => Promise<false | true> | void;
        onRemoveDrawing: () => Promise<void> | void;
        onSpatialFilter: (type: GeoFeatureResultSet) => void;
    }) {
        this.onEnableDrawing = onEnableDrawing;
        this.onCompleteDrawing = onCompleteDrawing;
        this.onRemoveDrawing = onRemoveDrawing;
        this.onSpatialFilter = onSpatialFilter;
    }

    private stopPropagation(element: HTMLElement) {
        L.DomEvent.disableClickPropagation(element);
        L.DomEvent.disableScrollPropagation(element);
    }
    public reset() {
        this.disableButtonForDrawing();
    }

    private addListenersInButtons() {
        this.overlappingFilterButton.addEventListener("click", () => {
            this.makeActiveButton(OVERLAPPING);
        });

        this.insideFilterButton.addEventListener("click", () => {
            this.makeActiveButton(INSIDE);
        });

        // Add listeners to buttons
        this.spatialDrawButton.addEventListener("click", async () => {
            this.drawingEnabled = !this.drawingEnabled;
            if (this.drawingEnabled) {
                this.onEnableDrawing();
                this.disableButtonForDrawing();
                this.setDefaultActiveResultSetButton();
                this.spatialDrawButton.innerText = "Stop spatial drawing";
            } else {
                const isValidBoundingBox = await this.onCompleteDrawing();
                if (isValidBoundingBox) this.enableButtonsAfterDrawing();
                this.spatialDrawButton.innerText = "Draw spatial filter";
            }
        });

        this.spatialRemoveButton.addEventListener("click", async () => {
            await this.onRemoveDrawing();
            this.disableButtonForDrawing();
        });
    }
    private createMenu(): HTMLElement {
        const root = document.createElement("div");
        root.id = "menu-on-map";

        this.stopPropagation(root);
        const menu = document.createElement("div");
        menu.id = "datapublication-menu";
        menu.className = "flex flex-col w-full pt-6 gap-3";

        const row = document.createElement("div");
        row.className =
            "flex gap-2 min-h-[40px] flex-row justify-between w-full";

        row.appendChild(this.createSpatialFilterSection());
        row.appendChild(this.createSpatialInteractionSection());

        menu.appendChild(row);
        root.appendChild(menu);

        return root;
    }

    private createSpatialFilterSection(): HTMLDivElement {
        const section = document.createElement("div");
        section.className = "flex-1 flex flex-col items-center justify-center";

        const title = document.createElement("h6");
        title.textContent = "Spatial Filter Settings";

        const buttonRow = document.createElement("div");
        buttonRow.className = "flex flex-row gap-3";

        buttonRow.appendChild(
            this.createButtonWrapper(this.overlappingFilterButton),
        );
        buttonRow.appendChild(
            this.createButtonWrapper(this.insideFilterButton),
        );

        section.appendChild(title);
        section.appendChild(buttonRow);

        return section;
    }

    private createSpatialInteractionSection(): HTMLDivElement {
        const section = document.createElement("div");
        section.className = "flex-1 flex flex-col items-center justify-center";

        const title = document.createElement("h6");
        title.textContent = "Spatial Interactions";

        const buttonRow = document.createElement("div");
        buttonRow.className = "flex flex-row gap-3";

        buttonRow.appendChild(this.createButtonWrapper(this.spatialDrawButton));
        buttonRow.appendChild(
            this.createButtonWrapper(this.spatialRemoveButton),
        );

        section.appendChild(title);
        section.appendChild(buttonRow);

        return section;
    }

    //Helper methods

    private createButton({
        id,
        text,
        disabled,
        includeIcon,
    }: {
        id: string;
        text: string;
        disabled: boolean;
        includeIcon?: Overlapping | Inside;
    }): HTMLButtonElement {
        const img = !includeIcon ? "" : TAB_CONFIG[includeIcon].icon;
        const button = document.createElement("button");

        button.id = id;
        button.className = "menu-btn btn btn-md";
        button.disabled = disabled;

        button.innerHTML = `${img}
        <span>${text}</span>`;

        return button;
    }

    private createButtonWrapper(button: HTMLButtonElement): HTMLDivElement {
        const wrapper = document.createElement("div");
        wrapper.className = "py-4";

        wrapper.appendChild(button);

        return wrapper;
    }

    private setDefaultActiveResultSetButton() {
        const activeTab = getDefaultTab();
        this.makeActiveButton(activeTab);
    }

    private disableButtonForDrawing(): void {
        this.overlappingFilterButton.disabled = true;
        this.insideFilterButton.disabled = true;
        this.spatialRemoveButton.disabled = true;
    }
    private enableButtonsAfterDrawing(): void {
        this.overlappingFilterButton.disabled = false;
        this.insideFilterButton.disabled = false;
        this.spatialRemoveButton.disabled = false;
    }

    private makeActiveButton(buttonType: GeoFeatureResultSet): void {
        if (buttonType === OVERLAPPING) {
            this.overlappingFilterButton.classList.add(ACTIVE);
            this.insideFilterButton.classList.remove(ACTIVE);
            this.onSpatialFilter(OVERLAPPING);
            return;
        }
        this.overlappingFilterButton.classList.remove(ACTIVE);
        this.insideFilterButton.classList.add(ACTIVE);
        this.onSpatialFilter(INSIDE);
    }
}

function assertIsHTMLButtonElement(
    el: HTMLElement | null,
    message = "Element is not an HTMLButtonElement",
): asserts el is HTMLButtonElement {
    if (!(el instanceof HTMLButtonElement)) {
        throw new Error(message);
    }
}
