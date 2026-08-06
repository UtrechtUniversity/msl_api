import { INSIDE, OVERLAPPING, type GeoFeatureResultSet } from "../types/map";
import {
    getDefaultTab,
    type ActiveFilterInfo,
    type Facets,
    type FreeTextActiveInfo,
    type KeywordFilters,
    type Paginator,
} from "./utils.js";
import { ResultsSidebar } from "./resultsSidebar.js";
import { MenuButtons } from "./menuButtons";
import { MapView } from "./mapView";
import type { GeoFeatureDataPublications } from "../types/datapublication";
import { Pagination } from "./pagination";
import { cloneDeep, isEmpty, omit } from "lodash";
import { ResultsMetadata } from "./resultsMetadata";
import { KeywordTree } from "./keywordTree";
import { SearchTextField } from "./searchTextField";
import { AppliedKeywordFilters } from "./appliedKeywordFilters";

const BOUNDING_BOX_OF_THE_WORLD = "[-180,-90,180,90]";
type SearchFilter = {
    filters: {
        boundingBox: string;
        page: number;
        pageSize: 10;
        keywords: KeywordFilters;
        freeText: string[];
    };
};

const DEFAULT_SEARCH_FILTERS: SearchFilter = {
    filters: {
        boundingBox: "",
        page: 1,
        pageSize: 10,
        keywords: {},
        freeText: [],
    },
} as const;

export class MapController {
    // UI elements
    resultsSidebar: ResultsSidebar;
    mapView: MapView;
    pagination: Pagination;
    resultsMetadata: ResultsMetadata;
    keywordTree: KeywordTree;
    appliedKeywords: AppliedKeywordFilters;
    // State
    activeTab: GeoFeatureResultSet = getDefaultTab();
    results: GeoFeatureDataPublications | null = null;
    searchFilters: SearchFilter = cloneDeep(DEFAULT_SEARCH_FILTERS);
    paginator: Paginator | null = null;
    facets: Facets = {};

    constructor() {
        this.mapView = new MapView();
        this.resultsSidebar = new ResultsSidebar();
        this.pagination = new Pagination();
        this.resultsMetadata = new ResultsMetadata();
        this.keywordTree = new KeywordTree();
        this.appliedKeywords = new AppliedKeywordFilters();

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
            onPageChange: async (page) => this.handlePageChange(page),
        });
        this.keywordTree.setHandlerfn({
            onKeywordFilterUpdate: async (
                type: "remove" | "add",
                filter: { name: string; value: string; displayName: string },
            ): Promise<void> => {
                return type === "add"
                    ? this.handleKeywordFilterAdd(filter)
                    : this.handleKeywordFilterRemove(filter);
            },
        });
        this.appliedKeywords.setHandlerfn({
            onActiveFilterRemove: async (
                opts: ActiveFilterInfo,
            ): Promise<void> => {
                return opts.type === "freeText"
                    ? this.handleSearchTextRemove(opts)
                    : this.handleKeywordFilterRemove(opts);
            },
            onActiveFilterRemoveAll: async () => {
                this.handleRemoveAllFilters();
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
        this.keywordTree.updateTrees(
            this.facets,
            this.searchFilters.filters.keywords,
        );

        await this.mapView.drawResponse(this.results);
        this.resultsSidebar.populate(this.results, {
            includeIcons: !!this.searchFilters.filters.boundingBox,
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
            this.searchFilters.filters.boundingBox || BOUNDING_BOX_OF_THE_WORLD;
        const params = new URLSearchParams({
            boundingBox: boundingBox,
            page: this.searchFilters.filters.page.toString(),
            pageSize: this.searchFilters.filters.pageSize.toString(),
            keywords: JSON.stringify(this.searchFilters.filters.keywords),
        });
        this.searchFilters.filters.freeText.forEach((text) => {
            params.append("freeText[]", text);
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
        this.resetComponentsAndData();
        // Start spatial filtering draw
        this.mapView.setDrawingEnable(true);
    }
    public async completeDrawing() {
        this.mapView.setDrawingEnable(false);

        this.searchFilters.filters.boundingBox = this.mapView.drawBoundingBox();
        if (!this.searchFilters.filters.boundingBox) return;

        await this.populateElements();
    }

    public async removeDrawing() {
        this.searchFilters.filters.boundingBox = "";

        this.resetAndRePopulateAfterUpdateTextFilters("remove");

        this.mapView.setDrawingEnable(false);
    }

    private setActivatedTab(activatedTab: GeoFeatureResultSet) {
        this.activeTab = activatedTab;
        this.resultsSidebar.handleActivationOfTab(activatedTab)();
        this.mapView.handleActivatedLayers(activatedTab);
    }

    private async handlePageChange(page: number) {
        this.mapView.removeAllLayers({ except: "rectangle" });
        this.resultsSidebar.resetList();
        this.pagination.resetValues();
        this.paginator = null;
        this.results = null;

        this.searchFilters.filters.page = page;
        await this.populateElements();
    }

    public async handleSearchTextAdd(value: string) {
        this.searchFilters.filters.freeText.push(value);
        this.appliedKeywords.addFilter({
            value,
            type: "freeText",
        });
        await this.resetAndRePopulateAfterUpdateTextFilters("add", {
            except: "boundingBox",
        });
    }

    private async handleSearchTextRemove(opts: FreeTextActiveInfo) {
        this.searchFilters.filters.freeText =
            this.searchFilters.filters.freeText.filter(
                (textFilter) => opts.value !== textFilter,
            );
        // We have to explicitly add the filter in appliedKeywords instance,
        // since we are keeping state of the keywords
        // and their order inside this component.
        // That means that we don't make additions/deletion
        // in standard reset methods of mapController.
        this.appliedKeywords.removeFilter({ id: opts.id });
        await this.resetAndRePopulateAfterUpdateTextFilters("remove", {
            except: "boundingBox",
        });
    }
    private async handleRemoveAllFilters() {
        this.searchFilters.filters.freeText = [];
        this.searchFilters.filters.keywords = {};
        this.appliedKeywords.removeAllFilters();
        await this.resetAndRePopulateAfterUpdateTextFilters("remove", {
            except: "boundingBox",
        });
    }
    private async handleKeywordFilterAdd({
        name,
        value,
        displayName,
    }: {
        name: string;
        value: string;
        displayName: string;
    }) {
        const existingValuesInTree = this.searchFilters.filters.keywords[name];
        this.searchFilters.filters.keywords[name] = [
            ...(existingValuesInTree ?? []),
            value,
        ];
        // We have to explicitly add the filter in appliedKeywords instance,
        // since we are keeping state of the keywords
        // and their order inside this component.
        // That means that we don't make additions/deletion
        // in standard reset methods of mapController.

        this.appliedKeywords.addFilter({
            name,
            value,
            displayName,
            type: "keyword",
        });
        await this.resetAndRePopulateAfterUpdateTextFilters("add", {
            except: "boundingBox",
        });
    }

    private async handleKeywordFilterRemove({
        name,
        value,
    }: {
        name: string;
        value: string;
    }): Promise<void> {
        let valuesInTree = this.searchFilters.filters.keywords[name];
        if (!valuesInTree)
            throw new Error("Key not found in tree. This is a bug.");
        valuesInTree = valuesInTree.filter(
            (valueOfKey: string) => valueOfKey !== value,
        );
        this.searchFilters.filters.keywords[name] = valuesInTree;

        if (this.searchFilters.filters.keywords[name].length === 0) {
            this.searchFilters.filters.keywords = omit(
                this.searchFilters.filters.keywords,
                name,
            );
        }

        this.appliedKeywords.removeKeywordFilter({
            name,
            value,
        });
        await this.resetAndRePopulateAfterUpdateTextFilters("remove", {
            except: "boundingBox",
        });
    }

    // Helper methods
    /**
     * Reset and populate after an update in search text or keywords filters.
     * For bounding box, we reset in starting drawing and
     * populate after the user confirms the selection of area.
     */
    private async resetAndRePopulateAfterUpdateTextFilters(
        type: "add" | "remove",
        opts: { except: "boundingBox" } | undefined = undefined,
    ) {
        this.resetComponentsAndData(opts);
        if (type === "add") {
            await this.populateElements();
            return;
        }
        await this.populateBasedOnActiveFiltersOrReset();
    }
    private resetComponentsAndData(opts?: { except: "boundingBox" }) {
        this.mapView.removeAllLayers(
            opts?.except === "boundingBox"
                ? { except: "rectangle" }
                : undefined,
        );
        this.resultsSidebar.resetList();
        this.pagination.clear();
        this.resultsMetadata.removeMetadata();
        // We never want to reset all filters at the same time
        this.resetPage();
        this.paginator = null;
        this.results = null;
        this.facets = {};
    }

    private resetPage() {
        this.searchFilters.filters.page = DEFAULT_SEARCH_FILTERS.filters.page;
    }
    private areActiveFilters(): boolean {
        const filters = this.searchFilters.filters;
        if (!!filters.boundingBox) return true;
        if (!isEmpty(filters.keywords)) return true;
        if (!!filters.freeText.length) return true;
        return false;
    }
    private async populateBasedOnActiveFiltersOrReset() {
        if (!this.areActiveFilters()) {
            ({ facets: this.facets } = await this.getJsonFromRequest());
            this.keywordTree.updateTrees(
                this.facets,
                this.searchFilters.filters.keywords,
            );
        } else {
            await this.populateElements();
        }
    }
}

const mapController = new MapController();
await mapController.init();
const menuButtons = new MenuButtons(mapController);
const searchTextField = new SearchTextField(mapController);
