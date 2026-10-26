import { getElementByIdOrThrow } from "../../assertions";

const mapViewTab = getElementByIdOrThrow("map-view-tab");

mapViewTab.addEventListener("click", (e: Event) => {
    const searchParams = new URLSearchParams(window.location.search);

    if (searchParams.size > 0) {
        const text = `Switching to the map view will not transfer your applied filters in the list view. 
            \nDo you want to proceed?`;
        if (!confirm(text)) {
            e.preventDefault();
        }
    }
});
