import type { MapController } from './mapController';

export class MenuButtons {

    overlappingFilterButton: HTMLButtonElement
    insideFilterButton: HTMLButtonElement
    spatialDrawButton: HTMLButtonElement
    spatialRemoveButton: HTMLButtonElement
    drawingEnabled: boolean = false

    mapController: MapController
    constructor(mapController: MapController) {

        this.overlappingFilterButton = this.getButtonElement('overlapping-filter-btn')
        this.insideFilterButton = this.getButtonElement('inside-filter-btn')
        this.spatialDrawButton = this.getButtonElement('spatial-draw')
        this.spatialRemoveButton = this.getButtonElement('spatial-remove')


        this.disableButtonForDrawing()
        this.initButtons()
        this.mapController = mapController
    }


    initButtons() {
        // Add listeners to buttons
        this.spatialDrawButton.addEventListener('click', () => {

            this.drawingEnabled = !this.drawingEnabled
            if (this.drawingEnabled) {
                this.mapController.enableDrawing()
                this.disableButtonForDrawing()
                this.spatialDrawButton.innerText = 'Stop spatial drawing'
            } else {
                this.mapController.completeDrawing()
                this.enableButtonsAfterDrawing()
                this.spatialDrawButton.innerText = 'Draw spatial filter'
            }
        });

        this.spatialRemoveButton.addEventListener('click', () => {
            this.mapController.removeDrawing()
            this.disableButtonForDrawing()
        });

        this.overlappingFilterButton.addEventListener('click', () => {
            this.makeActiveButton('overlapping')
            this.mapController.overlapFilter()
        }
        );

        this.insideFilterButton.addEventListener('click', () => {
            this.makeActiveButton('inside')
            this.mapController.insideFilter()
        }
        )
    }

    //Helper methods
    private getButtonElement(id: string): HTMLButtonElement {
        const htmlElement = document.getElementById(id)
        assertIsHTMLButtonElement(htmlElement)
        return htmlElement;
    }

    private disableButtonForDrawing(): void {
        this.overlappingFilterButton.disabled = true
        this.insideFilterButton.disabled = true
        this.spatialRemoveButton.disabled = true
    }
    private enableButtonsAfterDrawing(): void {
        this.overlappingFilterButton.disabled = false
        this.insideFilterButton.disabled = false
        this.spatialRemoveButton.disabled = false
    }

    private makeActiveButton(buttonType: 'overlapping' | 'inside'): void {

        if (buttonType === 'overlapping') {
            this.overlappingFilterButton.classList.add('active')
            this.insideFilterButton.classList.remove('active');
            return;
        }
        this.overlappingFilterButton.classList.remove('active')
        this.insideFilterButton.classList.add('active');
    }

}

function assertIsHTMLButtonElement(
    el: HTMLElement | null,
    message = 'Element is not an HTMLButtonElement'
): asserts el is HTMLButtonElement {
    if (!(el instanceof HTMLButtonElement)) {
        throw new Error(message);
    }
}