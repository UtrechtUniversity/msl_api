import { INSIDE, OVERLAPPING, type GeoFeatureResultSet } from "../types/map";
import { getDefaultTab } from "./utils.js";
import { ResultsSidebar } from "./resultsSidebar.js";
import { MenuButtons } from "./menuButtons";
import { MapView } from "./mapView";
import type { GeoFeatureDataPublications } from "../types/datapublication";

// If we dont assign L, typescript is complaining about using a UMD global in a module.
const L = window.L;
type SearchFilter = {
    boundingBox: string | null;
};
export class MapController {
    // UI elements
    sideBar: ResultsSidebar;
    mapView: MapView;

    // The current class controlls the map but also the state of the tabs
    // State
    activeTab: GeoFeatureResultSet;
    results: GeoFeatureDataPublications | null;
    searchFilters: SearchFilter = { boundingBox: null };
    constructor() {
        this.mapView = new MapView();
        this.sideBar = new ResultsSidebar();
        this.activeTab = getDefaultTab();
        this.results = null;
        // Callbacks
        this.mapView.setHandlerfn({
            onCleanUp: () => {
                this.sideBar.resetList();
            },
            onFeatureHover: (doi) => {
                this.sideBar.highlight(doi, { scroll: true });
            },
            onFeatureOut: (doi) => {
                this.sideBar.removeHighlight(doi);
            },
        });
        this.sideBar.setHandlerfn({
            onFeatureHover: (doi) => {
                this.mapView.setMarkersStyle({
                    doi,
                    resultSet: this.activeTab,
                    highlightOrReset: "highlight",
                });
            },
            onFeatureOut: (doi) => {
                this.mapView.setMarkersStyle({
                    doi: doi,
                    resultSet: this.activeTab,
                    highlightOrReset: "reset",
                });
            },
        });
    }

    // Methods about requests and populating

    private async addFeaturesAndSidebarInMap() {
        this.results = await this.getJsonFromRequest();
        await this.mapView.drawResponse(this.results);
        this.sideBar.populate(this.results);

        this.mapView.handleActivatedLayers(this.activeTab);
        this.sideBar.handleActivationOfTab(this.activeTab)();
    }

    public async getJsonFromRequest(): Promise<GeoFeatureDataPublications> {
        const boundingBox = this.searchFilters.boundingBox;
        if (!boundingBox)
            throw new Error(
                "Bounding box doesn't have a correct value. This is a bug.",
            );
        const parameters = { boundingBox, limit: "10" };
        const params = new URLSearchParams(parameters);

        const route = "/api/geoJsonDataPublications?" + params;

        const response: Response = await fetch(route, {
            method: "GET",
        });
        if (!response.ok) {
            throw new Error(
                "The response failed with status: " +
                    response.status +
                    " - " +
                    response.statusText,
            );
        }
        const data = (await response.json()).data;
        return data;
    }
    // Methods about interactions

    public insideFilter() {
        this.setActivatedTab(INSIDE);
    }
    public overlapFilter() {
        this.setActivatedTab(OVERLAPPING);
    }

    public enableDrawing() {
        this.searchFilters.boundingBox = null;
        this.resetAllInformation();
        // Start spatial filtering draw
        this.mapView.setDrawingEnable(true);
    }
    public completeDrawing() {
        this.mapView.setDrawingEnable(false);

        this.searchFilters.boundingBox = this.mapView.drawBoundingBox();
        if (!this.searchFilters.boundingBox) return;

        this.addFeaturesAndSidebarInMap();
    }

    public removeDrawing() {
        this.searchFilters.boundingBox = null;
        this.resetAllInformation();

        this.mapView.setDrawingEnable(false);
    }

    private setActivatedTab(activatedTab: GeoFeatureResultSet) {
        this.activeTab = activatedTab;
        this.sideBar.handleActivationOfTab(activatedTab)();
        this.mapView.handleActivatedLayers(activatedTab);
    }

    // Helper methods
    private resetAllInformation() {
        this.searchFilters.boundingBox = null;

        this.mapView.removeAllLayers();
        this.sideBar.resetList();
        this.results = null;
    }
}

const mapController = new MapController();
const menuButtons = new MenuButtons(mapController);
