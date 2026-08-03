import { assertNotNull, assertNotUndefined } from "../helpers";
import type { MapController } from "./mapController";

export class SearchTextField {
    mapController: MapController;
    constructor(mapController: MapController) {
        this.mapController = mapController;
        this.initInput();
    }

    private initInput() {
        const formField = document.getElementById("form-search-text");
        assertNotNull(
            formField,
            `Form search field was not found. This is a bug.`,
        );

        const self = this;
        formField.addEventListener("submit", async function (e) {
            e.preventDefault(); // stops the page from reloading

            const inputField = document.getElementById(
                "search-text",
            ) as HTMLInputElement | null;
            assertNotNull(
                inputField,
                `Input search field was not found. This is a bug.`,
            );
            await self.mapController.handleSearchTextAdd(inputField.value);
            inputField.value = "";
        });
    }
}
