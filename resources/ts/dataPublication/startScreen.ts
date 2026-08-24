import { assertNotNull } from "../helpers";
const MAP_AREA_ID = "datapublication-map-area" as const;
const START_SCREEN_ID = "start-screen-overlay" as const;
export class StartScreen {
    private mapArea: HTMLElement;
    private startScreen: HTMLElement;
    constructor() {
        const mapArea = document.getElementById(MAP_AREA_ID);
        assertNotNull(
            mapArea,
            `Element '${MAP_AREA_ID}' could not be found. This is a bug.`,
        );
        this.mapArea = mapArea;
        const startScreen = document.getElementById(START_SCREEN_ID);
        assertNotNull(
            startScreen,
            `Element '${START_SCREEN_ID}' could not be found. This is a bug.`,
        );
        this.startScreen = startScreen;
        this.mapArea.addEventListener("click", () => {
            this.startScreen.hidden = true;
        });
    }
}
