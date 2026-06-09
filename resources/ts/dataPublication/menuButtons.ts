import { assertNotNull } from "../helpers";
import { INSIDE, OVERLAPPING, type GeoFeatureResultSet } from "../types/map";
import type { MapController } from "./mapController";
import { getDefaultTab } from "./utils";

const ACTIVE = "active" as const;
const OVERLAPPING_BUTTON_ID = "overlapping-filter-btn" as const;
const INSIDE_BUTTON_ID = "inside-filter-btn" as const;
const SPATIAL_DRAW = "spatial-draw" as const;
const SPATIAL_REMOVE = "spatial-remove" as const;

const L = window.L;

export class MenuButtons {
    overlappingFilterButton: HTMLButtonElement | null = null;
    insideFilterButton: HTMLButtonElement | null = null;
    spatialDrawButton: HTMLButtonElement | null = null;
    spatialRemoveButton: HTMLButtonElement | null = null;
    root: HTMLElement;
    drawingEnabled: boolean = false;

    mapController: MapController;
    constructor(mapController: MapController) {
        this.mapController = mapController;
        this.root = this.createMenu();
    }

    private stopPropagation(element: HTMLElement) {
        L.DomEvent.disableClickPropagation(element);
        L.DomEvent.disableScrollPropagation(element);
    }

    private createMenu(): HTMLElement {
        const root = document.getElementById("menu-on-map");
        assertNotNull(
            root,
            `The element for top-menu does not exist, this is a bug.`,
        );
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

        const overlappingElement = this.createButton(
            "overlapping-filter-btn",
            "Overlapping",
            true,
        );
        this.overlappingFilterButton = overlappingElement.button;

        const insideElement = this.createButton(
            "inside-filter-btn",
            "Inside",
            true,
        );
        this.insideFilterButton = insideElement.button;

        this.overlappingFilterButton.addEventListener("click", () => {
            this.makeActiveButton(OVERLAPPING);
        });

        this.insideFilterButton.addEventListener("click", () => {
            this.makeActiveButton(INSIDE);
        });
        buttonRow.appendChild(overlappingElement.wrapper);
        buttonRow.appendChild(insideElement.wrapper);

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

        const drawElement = this.createButton(
            "spatial-draw",
            "Draw spatial filter",
            false,
        );
        this.spatialDrawButton = drawElement.button;

        const stopDrawElement = this.createButton(
            "spatial-remove",
            "Remove spatial filter",
            true,
        );
        this.spatialRemoveButton = stopDrawElement.button;

        // Add listeners to buttons
        this.spatialDrawButton.addEventListener("click", () => {
            this.drawingEnabled = !this.drawingEnabled;
            if (this.drawingEnabled) {
                this.mapController.enableDrawing();
                this.disableButtonForDrawing();
                this.setDefaultActiveResultSetButton();
                this.spatialDrawButton!.innerText = "Stop spatial drawing";
            } else {
                this.mapController.completeDrawing();
                this.enableButtonsAfterDrawing();
                this.spatialDrawButton!.innerText = "Draw spatial filter";
            }
        });

        this.spatialRemoveButton.addEventListener("click", () => {
            this.mapController.removeDrawing();
            this.disableButtonForDrawing();
        });

        buttonRow.appendChild(drawElement.wrapper);
        buttonRow.appendChild(stopDrawElement.wrapper);

        section.appendChild(title);
        section.appendChild(buttonRow);

        return section;
    }

    private createButton(
        id: string,
        text: string,
        disabled: boolean,
    ): { wrapper: HTMLDivElement; button: HTMLButtonElement } {
        const wrapper = document.createElement("div");
        wrapper.className = "py-4";

        const button = document.createElement("button");
        button.id = id;
        button.className = "menu-btn btn btn-md";
        button.textContent = text;
        button.disabled = disabled;

        wrapper.appendChild(button);

        return { wrapper, button };
    }

    //Helper methods
    private setDefaultActiveResultSetButton() {
        const activeTab = getDefaultTab();
        this.makeActiveButton(activeTab);
    }

    private disableButtonForDrawing(): void {
        this.overlappingFilterButton!.disabled = true;
        this.insideFilterButton!.disabled = true;
        this.spatialRemoveButton!.disabled = true;
    }
    private enableButtonsAfterDrawing(): void {
        this.overlappingFilterButton!.disabled = false;
        this.insideFilterButton!.disabled = false;
        this.spatialRemoveButton!.disabled = false;
    }

    private makeActiveButton(buttonType: GeoFeatureResultSet): void {
        if (buttonType === OVERLAPPING) {
            this.overlappingFilterButton!.classList.add(ACTIVE);
            this.insideFilterButton!.classList.remove(ACTIVE);
            this.mapController.overlapFilter();
            return;
        }
        this.overlappingFilterButton!.classList.remove(ACTIVE);
        this.insideFilterButton!.classList.add(ACTIVE);
        this.mapController.insideFilter();
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
