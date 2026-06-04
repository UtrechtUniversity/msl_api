import type { InclusiveExclusiveGeoJsonDataPublications } from '../types/datapublication';
import { EXCLUSIVE, INCLUSIVE, type ResultSet } from '../types/map';
import { getDefaultTab, getResultSetMappingObj, TAB_CONFIG, type Entries } from './utils.js';
import { ResultsSidebar } from './resultsSidebar.js';
import { MenuButtons } from './menuButtons';
import { MapView } from './mapView';



// If we dont assign L, typescript is complaining about using a UMD global in a module.
const L = window.L;



export class MapController {
    // UI elements
    sideBar: ResultsSidebar;
    mapView: MapView

    // The current class controlls the map but also the state of the tabs
    // State
    activeTab: ResultSet
    results: InclusiveExclusiveGeoJsonDataPublications | null




    constructor() {
        this.mapView = new MapView()
        this.sideBar = new ResultsSidebar();
        this.activeTab = getDefaultTab()
        this.results = null
        // Callbacks
        this.mapView.setHandlerfn({
            onCleanUp: () => { this.sideBar.resetList() },
            onFeatureHover: (doi) => { this.sideBar.highlight(doi, { scroll: true }) },
            onFeatureOut: (doi) => { this.sideBar.removeHighlight(doi) }
        })
        this.sideBar.setHandlerfn({
            onFeatureHover: (doi) => {
                this.mapView.setMarkersStyle({
                    doi,
                    resultSet: this.activeTab,
                    highlightOrReset: 'highlight'
                });
            }, onFeatureOut: (doi) => {
                this.mapView.setMarkersStyle({
                    doi: doi,
                    resultSet: this.activeTab,
                    highlightOrReset: 'reset'
                })
            }
        })
    }

    // Methods about requests and populating

    private async addFeaturesAndSidebarInMap(boundingBox: string) {

        this.results = await this.getJsonFromRequest(boundingBox);
        await this.mapView.drawResponse(this.results);
        this.sideBar.populate(this.results);

    }

    public async getJsonFromRequest(boundingBox: string): Promise<InclusiveExclusiveGeoJsonDataPublications> {
        const parameters = { boundingBox, limit: '10' }
        const params = new URLSearchParams(parameters);

        const route = '/api/geoJsonDataPublications?' + params;

        const response: Response = await fetch(route, {
            method: 'GET',
        });
        if (!response.ok) {
            throw new Error('The response failed with status: ' + response.status + ' - ' + response.statusText);
        }
        const data = (await response.json()).data
        return data;
    }
    // Methods about interactions

    public insideFilter() {
        this.setActivatedTab(INCLUSIVE)
    }
    public overlapFilter() {
        this.setActivatedTab(EXCLUSIVE)
    }

    public enableDrawing() {
        this.resetAllInformation()
        // Start spatial filtering draw
        this.mapView.setDrawingEnable(true)

    }
    public completeDrawing() {
        this.mapView.setDrawingEnable(false)

        const boundingBox = this.mapView.drawBoundingBox();
        if (!boundingBox) return;

        this.addFeaturesAndSidebarInMap(boundingBox);
    }

    public removeDrawing() {
        this.resetAllInformation()

        this.mapView.setDrawingEnable(false)

    }


    private setActivatedTab(activatedTab: ResultSet) {
        this.activeTab = activatedTab
        this.sideBar.handleActivationOfTab(activatedTab)()
        this.mapView.handleActivatedLayers(activatedTab)
    }

    // Helper methods
    private resetAllInformation() {
        this.mapView.removeAllLayers()
        this.sideBar.resetList()
        this.results = null
    }

}




const mapController = new MapController()
const menuButtons = new MenuButtons(mapController)


