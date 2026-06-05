import type { GeoFeature } from "../types/datapublication";
import { INSIDE, OVERLAPPING, type GeoFeatureResultSet } from "../types/map";
import type { MapController } from "./mapController";
import { TAB_CONFIG, type Entries } from "./utils";

const ACTIVE = "active" as const;
const OVERLAPPING_BUTTON_ID = "overlapping-filter-btn" as const;
const INSIDE_BUTTON_ID = "inside-filter-btn" as const;
const SPATIAL_DRAW = "spatial-draw" as const;
const SPATIAL_REMOVE = "spatial-remove" as const;
export class MenuButtons {
    overlappingFilterButton: HTMLButtonElement;
    insideFilterButton: HTMLButtonElement;
    spatialDrawButton: HTMLButtonElement;
    spatialRemoveButton: HTMLButtonElement;
    drawingEnabled: boolean = false;

    mapController: MapController;
    constructor(mapController: MapController) {
        this.mapController = mapController;

        this.overlappingFilterButton = this.getButtonElement(
            OVERLAPPING_BUTTON_ID,
        );
        this.insideFilterButton = this.getButtonElement(INSIDE_BUTTON_ID);
        this.spatialDrawButton = this.getButtonElement(SPATIAL_DRAW);
        this.spatialRemoveButton = this.getButtonElement(SPATIAL_REMOVE);

        this.disableButtonForDrawing();
        this.setDefaultActiveResultSetButton();
        this.initButtons();
    }

    private initButtons() {
        // Add listeners to buttons
        this.spatialDrawButton.addEventListener("click", () => {
            this.drawingEnabled = !this.drawingEnabled;
            if (this.drawingEnabled) {
                this.mapController.enableDrawing();
                this.disableButtonForDrawing();
                this.setDefaultActiveResultSetButton();
                this.spatialDrawButton.innerText = "Stop spatial drawing";
            } else {
                this.mapController.completeDrawing();
                this.enableButtonsAfterDrawing();
                this.spatialDrawButton.innerText = "Draw spatial filter";
            }
        });

        this.spatialRemoveButton.addEventListener("click", () => {
            this.mapController.removeDrawing();
            this.disableButtonForDrawing();
        });

        this.overlappingFilterButton.addEventListener("click", () => {
            this.makeActiveButton(OVERLAPPING);
        });

        this.insideFilterButton.addEventListener("click", () => {
            this.makeActiveButton(INSIDE);
        });
    }

    //Helper methods
    private getButtonElement(id: string): HTMLButtonElement {
        const htmlElement = document.getElementById(id);
        assertIsHTMLButtonElement(htmlElement);
        return htmlElement;
    }
    private setDefaultActiveResultSetButton() {
        for (const [tabName, tabInfo] of Object.entries(TAB_CONFIG) as Entries<
            typeof TAB_CONFIG
        >) {
            if (tabInfo.active) {
                this.makeActiveButton(tabName);
            }
        }
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
            this.mapController.overlapFilter();
            return;
        }
        this.overlappingFilterButton.classList.remove(ACTIVE);
        this.insideFilterButton.classList.add(ACTIVE);
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
