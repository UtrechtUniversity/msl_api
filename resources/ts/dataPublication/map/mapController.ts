import {
    INSIDE,
    OVERLAPPING,
    type GeoFeatureResultSet,
} from "../../types/map/resultSet";
import { SearchResultsList } from "./searchResultsList.js";
import { MenuButtons } from "./menuButtons";
import { MapView } from "./mapView";
import type { GeoFeatureDataPublications } from "../../types/map/datapublication";
import { Pagination } from "./pagination";
import { cloneDeep } from "lodash";
import { ResultsMetadata } from "./resultsMetadata";
import { getIdForTreeKeyword, KeywordTree } from "./keywordTree";
import { SearchTextField } from "./searchTextField";
import { AppliedKeywordFilters } from "./appliedKeywordFilters";
import { StartScreen } from "./startScreen";
import { getElementByIdOrThrow } from "../../assertions";
import {
    FREE_TEXT_SEARCH_QUERY,
    TREE_KEYWORD,
    type ActiveTextFilterInfo,
    type FreeTextSearchQueryInfo,
    type TextFilters,
    type TreeKeywordInfo,
} from "../../types/map/keywordFilters";
import {
    getDefaultResultSetTab,
    KEYWORDS,
    LAT_LONG_RANGE,
    RESULTS,
} from "./config";
import type { Facets, Paginator } from "../../types/map/components";
import { SidebarTabs } from "./sidebarTabs";

const BOUNDING_BOX_OF_THE_WORLD =
    "[" +
    [
        LAT_LONG_RANGE.MIN.LONG,
        LAT_LONG_RANGE.MIN.LAT,
        LAT_LONG_RANGE.MAX.LONG,
        LAT_LONG_RANGE.MAX.LAT,
    ].toString() +
    "]";

const LOADING_SCREEN_ID = "loading-screen-overlay" as const;

type SearchFilter = {
    boundingBox: string;
    page: number;
    pageSize: 10;
    /**
     * We want to keep order of active filters,
     *  without differentiating between keywords or free text search arguments.
     */
    activeTextFilters: Map<string, ActiveTextFilterInfo>;
};

const DEFAULT_SEARCH_FILTERS: SearchFilter = {
    boundingBox: "",
    page: 1,
    pageSize: 10,
    activeTextFilters: new Map(),
} as const;

export class MapController {
    // UI elements
    //list of results on sidebar
    searchResultsList: SearchResultsList;
    // keyword tree on sidebar
    keywordTree: KeywordTree;
    // tabs for toggling between list of results and keyword tree
    sidebarTabs: SidebarTabs;
    mapView: MapView;
    pagination: Pagination;
    resultsMetadata: ResultsMetadata;
    appliedKeywordFilters: AppliedKeywordFilters;
    startScreen: StartScreen;
    // buttons regarding spatial filtering setting
    menuButtons: MenuButtons;
    loadingScreen: HTMLElement;

    // State
    resultSetActiveTab: GeoFeatureResultSet = getDefaultResultSetTab();
    results: GeoFeatureDataPublications | null = null;
    searchFilters: SearchFilter = cloneDeep(DEFAULT_SEARCH_FILTERS);
    paginator: Paginator | null = null;
    facets: Facets = {};

    constructor() {
        this.mapView = new MapView();
        this.searchResultsList = new SearchResultsList();
        this.pagination = new Pagination();
        this.resultsMetadata = new ResultsMetadata();
        this.keywordTree = new KeywordTree();
        this.appliedKeywordFilters = new AppliedKeywordFilters();
        this.startScreen = new StartScreen();
        this.menuButtons = new MenuButtons();
        this.sidebarTabs = new SidebarTabs();

        // Callbacks
        this.mapView.setHandlerfn({
            //TODO hoveron hoverout
            onFeatureHover: this.handleFeatureHoverOn,
            onFeatureOut: this.handleFeatureHoverOut,
        });
        //TODO hoveron hoverout

        this.searchResultsList.setHandlerfn({
            onFeatureHover: this.handleFeatureHoverOn,
            onFeatureOut: this.handleFeatureHoverOut,
        });
        // TODO check this
        this.pagination.setHandlerfn({
            onPageChange: async (page) => this.handlePageChange(page),
        });

        this.keywordTree.setHandlerfn({
            onTreeKeywordFilterAdd: async (
                args: TreeKeywordInfo,
            ): Promise<void> => {
                await this.handleTreeKeywordFilterAdd(args);
            },
            onTreeKeywordFilterRemove: async (args: {
                id: string;
            }): Promise<void> => {
                await this.handleTreeKeywordFilterRemove(args);
            },
            onTreeToggle: async (e: JQuery.ClickEvent): Promise<boolean> => {
                let isConfirmed = true;
                if (this.hasActiveFilters()) {
                    const text = `Your currently selected filters will be removed when you switch trees.`;
                    isConfirmed = confirm(text);
                    !isConfirmed
                        ? e.preventDefault()
                        : await this.removeAllFilters();
                }
                return isConfirmed;
            },
        });
        this.appliedKeywordFilters.setHandlerfn({
            onActiveTreeKeywordRemove: async (args: {
                id: string;
            }): Promise<void> => {
                await this.handleTreeKeywordFilterRemove(args);
            },
            onActiveFreeTextQueryRemove: async (args: {
                id: string;
            }): Promise<void> => {
                await this.handleFreeTextSearchQueryRemove(args);
            },
            onActiveTextFiltersRemoveAll: async () => {
                await this.handleRemoveAllTextFilters();
            },
        });
        this.menuButtons.setHandlerfn({
            onEnableDrawing: () => {
                this.enableDrawing();
                return;
            },
            onCompleteDrawing: async () => {
                await this.completeDrawing();
                return !!this.searchFilters.boundingBox;
            },
            onRemoveDrawing: async () => {
                await this.removeDrawing();
            },
            // todo check in menubuttons if we have something similar
            onSpatialFilterSetting: (type: GeoFeatureResultSet) => {
                switch (type) {
                    case INSIDE:
                        this.insideFilter();
                        break;
                    case OVERLAPPING:
                        this.overlapFilter();
                        break;
                }
            },
        });
        this.loadingScreen = getElementByIdOrThrow(LOADING_SCREEN_ID);
        this.addRedirectionWarning();
    }
    public async init(): Promise<void> {
        ({ facets: this.facets } = await this.getResponse());
        await this.keywordTree.init(this.facets);
    }

    // Methods about requests and populating

    private async populateWithResults() {
        ({
            data: this.results,
            meta: this.paginator,
            facets: this.facets,
        } = await this.getResponse());

        const keywords: TextFilters = this.getTextFiltersAsRequestArgs();

        this.keywordTree.updateTrees(this.facets, keywords);

        this.mapView.drawResponse(this.results);
        this.searchResultsList.populate(this.results, {
            includeIcons: !!this.searchFilters.boundingBox,
        });

        this.pagination.setArgs(this.paginator);
        this.pagination.populate();

        this.resultsMetadata.updateMetadata(this.paginator);

        this.mapView.handleResultSetActivatedLayers(this.resultSetActiveTab);
        this.searchResultsList.handleResultSetActivationOfTab(
            this.resultSetActiveTab,
        )();
    }

    public async getResponse(): Promise<{
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
            keywords: JSON.stringify(this.getTextFiltersAsRequestArgs()),
        });
        this.getFreeTextSearchQueriesAsArray().forEach((text) => {
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
        this.setResultSetActivatedTab(INSIDE);
    }
    public overlapFilter() {
        this.setResultSetActivatedTab(OVERLAPPING);
    }

    public enableDrawing() {
        this.searchFilters.boundingBox = "";
        this.mapView.removeExistingDrawnBoundingBox();
        // Start spatial filtering draw
        this.mapView.setDrawingEnable(true);
    }

    public async completeDrawing() {
        this.mapView.setDrawingEnable(false);

        this.searchFilters.boundingBox = this.mapView.drawBoundingBox();
        if (!this.searchFilters.boundingBox) {
            await this.resetAndRePopulateAfterUpdate();
            return;
        }
        await this.resetAndRePopulateAfterUpdate({
            except: "boundingBox",
        });
        this.sidebarTabs.activateSidebarTab(RESULTS);
    }

    public async removeDrawing() {
        this.searchFilters.boundingBox = "";

        await this.resetAndRePopulateAfterUpdate();

        this.mapView.setDrawingEnable(false);
    }

    //TODO make sure it is consistent
    public handleFeatureHoverOn = ({
        doi,
        resultSet,
        scroll,
    }: {
        doi: string;
        resultSet?: GeoFeatureResultSet;
        scroll?: true;
    }) => {
        const appliedResultSet = resultSet ?? this.resultSetActiveTab;
        this.mapView.setMarkersStyle({
            doi,
            resultSet: appliedResultSet,
            highlightOrReset: "highlight",
        });
        this.searchResultsList.highlight(doi, { scroll: scroll ?? false });
    };

    public handleFeatureHoverOut = ({
        doi,
        resultSet,
    }: {
        doi: string;
        resultSet?: GeoFeatureResultSet;
    }) => {
        const appliedResultSet = resultSet ?? this.resultSetActiveTab;

        this.mapView.setMarkersStyle({
            doi,
            resultSet: appliedResultSet,
            highlightOrReset: "reset",
        });
        this.searchResultsList.removeHighlight(doi);
    };

    private async handlePageChange(page: number) {
        this.searchFilters.page = page;
        await this.resetAndRePopulateAfterUpdate({
            except: "page",
        });
    }

    public async handleFreeTextSearchQueryAdd({
        value,
    }: FreeTextSearchQueryInfo) {
        const id = createIdForFreeTextSearchQuery();
        this.searchFilters.activeTextFilters.set(id, {
            value,
            id,
            type: FREE_TEXT_SEARCH_QUERY,
        });

        this.appliedKeywordFilters.addTextFilter({
            id,
            value,
            type: FREE_TEXT_SEARCH_QUERY,
        });
        await this.resetAndRePopulateAfterUpdate({
            except: "boundingBox",
        });
        this.sidebarTabs.activateSidebarTab(RESULTS);
    }

    private async handleFreeTextSearchQueryRemove({ id }: { id: string }) {
        this.searchFilters.activeTextFilters.delete(id);
        this.appliedKeywordFilters.removeAppliedTextFilter({ id });
        await this.resetAndRePopulateAfterUpdate({
            except: "boundingBox",
        });
    }

    private async handleRemoveAllTextFilters() {
        this.searchFilters.activeTextFilters = new Map();

        this.appliedKeywordFilters.removeAllActiveTextFilters();
        await this.resetAndRePopulateAfterUpdate({
            except: "boundingBox",
        });
    }

    private async handleTreeKeywordFilterAdd({
        name,
        value,
        displayName,
    }: TreeKeywordInfo) {
        const id = getIdForTreeKeyword({ value, name });
        this.searchFilters.activeTextFilters.set(id, {
            value,
            name,
            id,
            displayName,
            type: TREE_KEYWORD,
        });

        this.appliedKeywordFilters.addTextFilter({
            id,
            name,
            value,
            displayName,
            type: TREE_KEYWORD,
        });
        await this.resetAndRePopulateAfterUpdate({
            except: "boundingBox",
        });
    }

    private async handleTreeKeywordFilterRemove(args: {
        id: string;
    }): Promise<void> {
        this.appliedKeywordFilters.removeAppliedTextFilter(args);
        this.searchFilters.activeTextFilters.delete(args.id);
        await this.resetAndRePopulateAfterUpdate({
            except: "boundingBox",
        });
    }
    // Warning
    private addRedirectionWarning() {
        const listViewTab = getElementByIdOrThrow("list-view-tab");
        listViewTab.addEventListener("click", (e: Event) => {
            if (this.hasActiveFilters()) {
                const text = `Switching to the list view will not transfer your applied filters in the map view. 
            \nDo you want to proceed?`;
                if (!confirm(text)) {
                    e.preventDefault();
                }
            }
        });
    }

    // Other methods

    private setResultSetActivatedTab(
        resultSetActivatedTab: GeoFeatureResultSet,
    ) {
        this.resultSetActiveTab = resultSetActivatedTab;
        this.searchResultsList.handleResultSetActivationOfTab(
            resultSetActivatedTab,
        )();
        this.mapView.handleResultSetActivatedLayers(resultSetActivatedTab);
    }
    //TODO move it outside
    private getFreeTextSearchQueriesAsArray(): string[] {
        const freeText = [];
        for (const [_, metadata] of this.searchFilters.activeTextFilters) {
            if (metadata.type !== FREE_TEXT_SEARCH_QUERY) continue;
            freeText.push(metadata.value);
        }
        return freeText;
    }
    //TODO move it outside
    private getTextFiltersAsRequestArgs(): TextFilters {
        const keywords: TextFilters = {};
        for (const [_, metadata] of this.searchFilters.activeTextFilters) {
            if (metadata.type !== TREE_KEYWORD) continue;
            const values = keywords[metadata.name];
            keywords[metadata.name] = values
                ? [...values, metadata.value]
                : [metadata.value];
        }
        return keywords;
    }
    /**
     * Reset and populate after an update in filters.
     * In some cases, we want to not reset drawn bounding box
     * or page information.
     */
    private async resetAndRePopulateAfterUpdate(
        opts: { except: "boundingBox" | "page" } | undefined = undefined,
    ) {
        this.resetComponentsAndData(opts);
        await this.populateBasedOnActiveFilters();
    }

    private async removeAllFilters() {
        this.searchFilters.boundingBox = "";
        this.searchFilters.activeTextFilters = new Map();
        await this.resetAndRePopulateAfterUpdate();
    }
    /**
     * We reset parts of the map, after an update in filters.
     */
    private resetComponentsAndData(opts?: { except: "boundingBox" | "page" }) {
        this.mapView.removeAllLayers(
            // In cases of preserving bounding box information and page information,
            // we want to keep the bounding box drawn
            opts ? { except: "rectangle" } : undefined,
        );
        this.searchResultsList.resetList();
        this.resultsMetadata.removeMetadata();
        if (this.searchFilters.activeTextFilters.size === 0) {
            this.appliedKeywordFilters.removeAllActiveTextFilters();
            if (!this.searchFilters.boundingBox) this.menuButtons.reset();
        }
        // If no filters are present,
        // we have to make sure that pagination elements get removed.
        if (!this.hasActiveFilters()) {
            this.pagination.clear();
        }
        // When we have change in page, we don't want to reset the page,
        // since this is the new filter value!
        if (opts?.except !== "page") {
            this.resetPage();
        }
        this.paginator = null;
        this.results = null;
        this.facets = {};
    }

    private resetPage() {
        this.searchFilters.page = DEFAULT_SEARCH_FILTERS.page;
        return;
    }
    private hasActiveFilters(): boolean {
        const filters = this.searchFilters;
        if (filters.boundingBox) return true;
        if (filters.activeTextFilters.size !== 0) return true;
        return false;
    }

    private async populateBasedOnActiveFilters() {
        this.showLoadingScreen({ show: true });

        if (!this.hasActiveFilters()) {
            this.appliedKeywordFilters.removeAllActiveTextFilters();
            ({ facets: this.facets } = await this.getResponse());
            this.keywordTree.updateTrees(this.facets, {});
            this.sidebarTabs.activateSidebarTab(KEYWORDS);
        } else {
            await this.populateWithResults();
        }
        this.showLoadingScreen({ show: false });
    }
    private showLoadingScreen({ show }: { show: true | false }) {
        this.loadingScreen.hidden = !show;
    }
}

const mapController = new MapController();
await mapController.init();
const searchTextField = new SearchTextField(mapController);

function createIdForFreeTextSearchQuery() {
    const id = "freeText" + "_" + crypto.randomUUID();
    return id;
}
