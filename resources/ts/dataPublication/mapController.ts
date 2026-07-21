import { INSIDE, OVERLAPPING, type GeoFeatureResultSet } from "../types/map";
import { getDefaultTab, type Facets, type Paginator } from "./utils.js";
import { ResultsSidebar } from "./resultsSidebar.js";
import { MenuButtons } from "./menuButtons";
import { MapView } from "./mapView";
import type { GeoFeatureDataPublications } from "../types/datapublication";
import { Pagination } from "./pagination";
import { cloneDeep } from "lodash";
import { ResultsMetadata } from "./resultsMetadata";
import { KeywordTree } from "./keywordTree";

const BOUNDING_BOX_DEFAULT = "[-180,-90,180,90]";
type SearchFilter = {
    //TODO maybe add a function on how to create filters  and remove filters based on the filter.
    activeFilters: string[];
    filters: {
        boundingBox: string;
        page: number;
        pageSize: 10;
        keywords: { [key: string]: string[] };
    };
};

const DEFAULT_SEARCH_FILTERS: SearchFilter = {
    activeFilters: [],
    filters: {
        boundingBox: "",
        page: 1,
        pageSize: 10,
        keywords: {},
    },
} as const;

export class MapController {
    // UI elements
    resultsSidebar: ResultsSidebar;
    mapView: MapView;
    pagination: Pagination;
    resultsMetadata: ResultsMetadata;
    keywordTree: KeywordTree;
    // State
    activeTab: GeoFeatureResultSet = getDefaultTab();
    results: GeoFeatureDataPublications | null = null;
    searchFilters: SearchFilter = DEFAULT_SEARCH_FILTERS;
    paginator: Paginator | null = null;
    facets: Facets = {};

    constructor() {
        this.mapView = new MapView();
        this.resultsSidebar = new ResultsSidebar();
        this.pagination = new Pagination();
        this.resultsMetadata = new ResultsMetadata();
        this.keywordTree = new KeywordTree();

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
        this.keywordTree.setHandlerfn({
            onKeywordFilterUpdate: async (
                type: "remove" | "add",
                filter: { key: string; value: string },
            ) => {
                return type === "add"
                    ? await this.handleKeywordFilterAdd(filter)
                    : await this.handleKeywordFilterRemove(filter);
            },
        });
    }

    public async init() {
        ({ facets: this.facets } = await this.getJsonFromRequest());
        this.keywordTree.init(this.facets);
    }

    // Methods about requests and populating

    private async populateElements() {
        ({
            data: this.results,
            meta: this.paginator,
            facets: this.facets,
        } = await this.getJsonFromRequest());
        this.keywordTree.recreateFacets(this.facets);

        await this.mapView.drawResponse(this.results);
        this.resultsSidebar.populate(this.results, {
            includeIcons:
                this.searchFilters.activeFilters.includes("boundingBox"),
        });

        this.pagination.setArgs(this.paginator);
        this.pagination.populate();

        this.resultsMetadata.updateMetadata(this.paginator);

        this.mapView.handleActivatedLayers(this.activeTab);
        this.resultsSidebar.handleActivationOfTab(this.activeTab)();
    }

    public async getJsonFromRequest(): Promise<{
        data: GeoFeatureDataPublications;
        meta: Paginator;
        facets: Facets;
    }> {
        const boundingBox =
            this.searchFilters.filters.boundingBox || BOUNDING_BOX_DEFAULT;
        const params = new URLSearchParams({
            boundingBox: boundingBox,
            page: this.searchFilters.filters.page.toString(),
            pageSize: this.searchFilters.filters.pageSize.toString(),
            keywords: JSON.stringify(this.searchFilters.filters.keywords),
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

        const { data, meta, facets } = await response.json();
        return { data, meta, facets };
    }

    // Methods about interactions
    public insideFilter() {
        this.setActivatedTab(INSIDE);
    }
    public overlapFilter() {
        this.setActivatedTab(OVERLAPPING);
    }

    public enableDrawing() {
        this.searchFilters.filters.boundingBox = "";
        this.searchFilters.activeFilters =
            this.searchFilters.activeFilters.filter(
                (filter) => filter !== "boundingBox",
            );
        this.resetAllInformation();
        // Start spatial filtering draw
        this.mapView.setDrawingEnable(true);
    }
    public completeDrawing() {
        this.mapView.setDrawingEnable(false);

        this.searchFilters.filters.boundingBox = this.mapView.drawBoundingBox();
        if (!this.searchFilters.filters.boundingBox) return;
        this.searchFilters.activeFilters.push("boundingBox");

        this.populateElements();
    }

    public async removeDrawing() {
        this.searchFilters.filters.boundingBox = "";
        this.searchFilters.activeFilters =
            this.searchFilters.activeFilters.filter(
                (filter) => filter !== "boundingBox",
            );

        this.resetAllInformation();
        //TODO something is going wrong with async?
        if (!this.searchFilters.activeFilters.length) {
            ({ facets: this.facets } = await this.getJsonFromRequest());
        } else {
            await this.populateElements();
        }

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

        this.searchFilters.filters.page = page;
        this.searchFilters.activeFilters.push("page:" + page);
        this.populateElements();
    }
    private async handleKeywordFilterAdd({
        key,
        value,
    }: {
        key: string;
        value: string;
    }) {
        const valuesInTree = this.searchFilters.filters.keywords[key];
        const setToAdd = new Set(valuesInTree ?? []);
        setToAdd.add(value);
        this.searchFilters.filters.keywords[key] = [...setToAdd];
        this.searchFilters.activeFilters.push(key + ":" + value);

        this.resetNecessaryInformationForKeyword();
        await this.populateElements();
        return this.facets;
    }

    private async handleKeywordFilterRemove({
        key,
        value,
    }: {
        key: string;
        value: string;
    }): Promise<Facets> {
        let valuesInTree = this.searchFilters.filters.keywords[key];
        if (!valuesInTree)
            throw new Error("Key not found in tree. This is a bug.");
        valuesInTree = valuesInTree.filter(
            (valueOfKey: string) => valueOfKey !== value,
        );
        this.searchFilters.filters.keywords[key] = valuesInTree;

        if (this.searchFilters.filters.keywords[key].length === 0)
            delete this.searchFilters.filters.keywords[key];

        this.searchFilters.activeFilters =
            this.searchFilters.activeFilters.filter(
                (filter) => filter !== key + ":" + value,
            );

        this.resetNecessaryInformationForKeyword();
        if (!this.searchFilters.activeFilters.length) {
            ({ facets: this.facets } = await this.getJsonFromRequest());
        } else {
            await this.populateElements();
        }
        return this.facets;
    }

    // Helper methods
    private resetAllInformation() {
        this.mapView.removeAllLayers();
        this.resultsSidebar.resetList();
        this.pagination.clear();
        this.resultsMetadata.removeMetadata();
        this.resetSearchFilter();
        this.paginator = null;
        this.results = null;
        this.facets = {};
    }
    private resetNecessaryInformationForKeyword() {
        this.mapView.removeAllLayers({ except: "rectangle" });
        this.resultsSidebar.resetList();
        this.pagination.clear();
        this.resultsMetadata.removeMetadata();
        this.paginator = null;
        this.results = null;
    }
    private resetSearchFilter() {
        this.searchFilters = cloneDeep(DEFAULT_SEARCH_FILTERS);
    }
}

const mapController = new MapController();
await mapController.init();
const menuButtons = new MenuButtons(mapController);
