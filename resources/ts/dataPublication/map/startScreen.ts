import { getElementByIdOrThrow } from "../../assertions";
const MAP_AREA_ID = "datapublication-map-area" as const;
const START_SCREEN_ID = "start-screen-overlay" as const;
export class StartScreen {
    private mapArea: HTMLElement;
    private startScreen: HTMLElement;
    constructor() {
        this.mapArea = getElementByIdOrThrow(MAP_AREA_ID);
        this.startScreen = getElementByIdOrThrow(START_SCREEN_ID);
        this.mapArea.addEventListener("click", () => {
            this.startScreen.hidden = true;
        });
    }
}
