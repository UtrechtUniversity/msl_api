import { getElementByIdOrThrow } from "../../assertions";
import type { MapController } from "./mapController";

export class SearchTextField {
    mapController: MapController;
    constructor(mapController: MapController) {
        this.mapController = mapController;
        this.initInput();
    }

    private initInput() {
        const formField = getElementByIdOrThrow("form-search-text");

        const self = this;
        formField.addEventListener("submit", async function (e) {
            e.preventDefault(); // stops the page from reloading

            const inputField = getElementByIdOrThrow(
                "search-text",
            ) as HTMLInputElement;
            await self.mapController.handleSearchTextAdd({
                value: inputField.value,
            });
            inputField.value = "";
        });
    }
}
