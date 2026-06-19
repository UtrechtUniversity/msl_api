import { INSIDE, OVERLAPPING, type GeoFeatureResultSet } from "../types/map";
import { getDefaultTab, type Paginator } from "./utils.js";
import { ResultsSidebar } from "./resultsSidebar.js";
import { MenuButtons } from "./menuButtons";
import { MapView } from "./mapView";
import type { GeoFeatureDataPublications } from "../types/datapublication";
import { Pagination } from "./pagination";

// If we dont assign L, typescript is complaining about using a UMD global in a module.
const L = window.L;
type SearchFilter = {
    boundingBox: string;
    page: number;
    pageSize: 10;
};
export class MapController {
    // UI elements
    resultsSidebar: ResultsSidebar;
    mapView: MapView;
    pagination: Pagination;
    // The current class controlls the map but also the state of the tabs
    // State
    activeTab: GeoFeatureResultSet = getDefaultTab();
    results: GeoFeatureDataPublications | null = null;
    searchFilters: SearchFilter = { boundingBox: "", page: 1, pageSize: 10 };
    paginator: Paginator | null = null;

    constructor() {
        this.mapView = new MapView();
        this.resultsSidebar = new ResultsSidebar();
        this.pagination = new Pagination();

        // Callbacks
        this.mapView.setHandlerfn({
            onCleanUp: () => {
                this.resultsSidebar.resetList();
            },
            onFeatureHover: (doi) => {
                this.resultsSidebar.highlight(doi, { scroll: true });
            },
            onFeatureOut: (doi) => {
                this.resultsSidebar.removeHighlight(doi);
            },
        });
        this.resultsSidebar.setHandlerfn({
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
        this.pagination.setHandlerfn({
            onPageChange: (page) => this.handlePageChange(page),
        });
    }

    // Methods about requests and populating

    private async addFeaturesAndSidebarInMap() {
        ({ data: this.results, meta: this.paginator } =
            await this.getJsonFromRequest());

        await this.mapView.drawResponse(this.results);
        this.resultsSidebar.populate(this.results);

        this.pagination.setArgs(this.paginator);
        this.pagination.populate();

        this.mapView.handleActivatedLayers(this.activeTab);
        this.resultsSidebar.handleActivationOfTab(this.activeTab)();
    }

    public async getJsonFromRequest(): Promise<{
        data: GeoFeatureDataPublications;
        meta: Paginator;
    }> {
        const boundingBox = this.searchFilters.boundingBox;
        if (!boundingBox)
            throw new Error(
                "Bounding box doesn't have a correct value. This is a bug.",
            );
        const params = new URLSearchParams({
            boundingBox: this.searchFilters.boundingBox,
            page: this.searchFilters.page.toString(),
            pageSize: this.searchFilters.pageSize.toString(),
        });

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
        const { data, meta } = await response.json();

        return { data, meta };
    }
    // Methods about interactions

    public insideFilter() {
        this.setActivatedTab(INSIDE);
    }
    public overlapFilter() {
        this.setActivatedTab(OVERLAPPING);
    }

    public enableDrawing() {
        this.searchFilters.boundingBox = "";
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
        this.searchFilters.boundingBox = "";
        this.resetAllInformation();

        this.mapView.setDrawingEnable(false);
    }

    private setActivatedTab(activatedTab: GeoFeatureResultSet) {
        this.activeTab = activatedTab;
        this.resultsSidebar.handleActivationOfTab(activatedTab)();
        this.mapView.handleActivatedLayers(activatedTab);
    }

    private handlePageChange(page: number) {
        this.mapView.removeAllLayers({ except: "rectangle" });
        this.resultsSidebar.resetList();
        this.pagination.resetValues();
        this.paginator = null;
        this.results = null;

        this.searchFilters.page = page;
        this.addFeaturesAndSidebarInMap();
    }

    // Helper methods
    private resetAllInformation() {
        this.mapView.removeAllLayers();
        this.resultsSidebar.resetList();
        this.pagination.clear();
        this.paginator = null;
        this.results = null;
    }
}

const mapController = new MapController();
const menuButtons = new MenuButtons(mapController);
