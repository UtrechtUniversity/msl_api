import { INSIDE, OVERLAPPING, type GeoFeatureResultSet } from "../types/map";
import {
    getDefaultTab,
    getIdForKeyword,
    type ActiveFilterInfo,
    type Facets,
    type FreeTextAddInfo,
    type KeywordAddInfo,
    type KeywordFilters,
    type Paginator,
} from "./utils.js";
import { ResultsSidebar } from "./resultsSidebar.js";
import { MenuButtons } from "./menuButtons";
import { MapView } from "./mapView";
import type { GeoFeatureDataPublications } from "../types/datapublication";
import { Pagination } from "./pagination";
import { cloneDeep } from "lodash";
import { ResultsMetadata } from "./resultsMetadata";
import { KeywordTree } from "./keywordTree";
import { SearchTextField } from "./searchTextField";
import { AppliedKeywordFilters } from "./appliedKeywordFilters";
type FiltersInformation = ActiveFilterInfo;
const BOUNDING_BOX_OF_THE_WORLD = "[-180,-90,180,90]";
type SearchFilter = {
    boundingBox: string;
    page: number;
    pageSize: 10;
    filters: Map<string, FiltersInformation>;
};

const DEFAULT_SEARCH_FILTERS: SearchFilter = {
    boundingBox: "",
    page: 1,
    pageSize: 10,
    filters: new Map(),
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
            onKeywordFilterAdd: async (opts: KeywordAddInfo): Promise<void> => {
                this.handleKeywordFilterAdd(opts);
            },
            onKeywordFilterRemove: async (opts: {
                id: string;
            }): Promise<void> => {
                this.handleKeywordFilterRemove(opts);
            },
        });
        this.appliedKeywords.setHandlerfn({
            onActiveKeywordRemove: async (opts: {
                id: string;
            }): Promise<void> => {
                this.handleKeywordFilterRemove(opts);
            },
            onActiveFreeTextRemove: async (opts: {
                id: string;
            }): Promise<void> => {
                this.handleSearchTextRemove(opts);
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

        const keywords: KeywordFilters = this.getKeywords();

        this.keywordTree.updateTrees(this.facets, keywords);

        await this.mapView.drawResponse(this.results);
        this.resultsSidebar.populate(this.results, {
            includeIcons: !!this.searchFilters.boundingBox,
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
            this.searchFilters.boundingBox || BOUNDING_BOX_OF_THE_WORLD;
        const params = new URLSearchParams({
            boundingBox: boundingBox,
            page: this.searchFilters.page.toString(),
            pageSize: this.searchFilters.pageSize.toString(),
            keywords: JSON.stringify(this.getKeywords()),
        });
        this.getFreeText().forEach((text) => {
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
        this.searchFilters.boundingBox = "";
        this.resetComponentsAndData();
        // Start spatial filtering draw
        this.mapView.setDrawingEnable(true);
    }
    public async completeDrawing() {
        this.mapView.setDrawingEnable(false);

        this.searchFilters.boundingBox = this.mapView.drawBoundingBox();
        if (!this.searchFilters.boundingBox) return;

        await this.populateElements();
    }

    public async removeDrawing() {
        this.searchFilters.boundingBox = "";

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

        this.searchFilters.page = page;
        await this.populateElements();
    }

    public async handleSearchTextAdd({ value }: FreeTextAddInfo) {
        const id = getIdForFreeText();
        this.searchFilters.filters.set(id, { value, id, type: "freeText" });

        this.appliedKeywords.addFilter({
            id,
            value,
            type: "freeText",
        });
        await this.resetAndRePopulateAfterUpdateTextFilters("add", {
            except: "boundingBox",
        });
    }

    private async handleSearchTextRemove({ id }: { id: string }) {
        this.searchFilters.filters.delete(id);
        this.appliedKeywords.removeFilter({ id });
        await this.resetAndRePopulateAfterUpdateTextFilters("remove", {
            except: "boundingBox",
        });
    }
    private async handleRemoveAllFilters() {
        this.searchFilters.filters = new Map();

        this.appliedKeywords.removeAllFilters();
        await this.resetAndRePopulateAfterUpdateTextFilters("remove", {
            except: "boundingBox",
        });
    }
    private async handleKeywordFilterAdd({
        name,
        value,
        displayName,
    }: KeywordAddInfo) {
        const id = getIdForKeyword({ value, name });
        this.searchFilters.filters.set(id, {
            value,
            name,
            id,
            displayName,
            type: "keyword",
        });

        // We have to explicitly add the filter in appliedKeywords instance,
        // since we are keeping state of the keywords
        // and their order inside this component.
        // That means that we don't make additions/deletion
        // in standard reset methods of mapController.

        this.appliedKeywords.addFilter({
            id,
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
        id,
    }: {
        id: string;
    }): Promise<void> {
        this.appliedKeywords.removeKeywordFilter({
            id,
        });
        this.searchFilters.filters.delete(id);
        await this.resetAndRePopulateAfterUpdateTextFilters("remove", {
            except: "boundingBox",
        });
    }

    // Helper methods

    private getFreeText(): string[] {
        let freeText = [];
        for (const [_, value] of this.searchFilters.filters) {
            if (value.type !== "freeText") continue;
            freeText.push(value.value);
        }
        return freeText;
    }

    private getKeywords(): KeywordFilters {
        let keywords: KeywordFilters = {};
        for (const [key, value] of this.searchFilters.filters) {
            if (value.type !== "keyword") continue;
            const element = keywords[value.name];
            keywords[value.name] = element
                ? [...element, value.value]
                : [value.value];
        }
        return keywords;
    }
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
        if (this.searchFilters.filters.size === 0)
            this.appliedKeywords.removeAllFilters();
        // We never want to reset all filters at the same time
        this.resetPage();
        this.paginator = null;
        this.results = null;
        this.facets = {};
    }

    private resetPage() {
        this.searchFilters.page = DEFAULT_SEARCH_FILTERS.page;
    }
    private areActiveFilters(): boolean {
        const filters = this.searchFilters;
        if (!!filters.boundingBox) return true;
        if (filters.filters.size !== 0) return true;
        return false;
    }
    private async populateBasedOnActiveFiltersOrReset() {
        if (!this.areActiveFilters()) {
            this.appliedKeywords.removeAllFilters();
            ({ facets: this.facets } = await this.getJsonFromRequest());
            this.keywordTree.updateTrees(this.facets, {});
        } else {
            await this.populateElements();
        }
    }
}

const mapController = new MapController();
await mapController.init();
const menuButtons = new MenuButtons(mapController);
const searchTextField = new SearchTextField(mapController);

function getIdForFreeText() {
    const id = "freeText" + "_" + crypto.randomUUID();
    return id;
}
