import type { InclusiveExclusiveGeoJsonDataPublications } from "../types/datapublication";
import { EXCLUSIVE, INCLUSIVE, type ResultSet } from "../types/map";
import { TAB_CONFIG, type Entries } from "./utils.js";
import { ResultsSidebar } from "./resultsSidebar.js";
import { MenuButtons } from './menuButtons';
import { MapView } from './mapView';



// If we dont assign L, typescript is complaining about using a UMD global in a module.
const L = window.L;



export class MapController {
    mapView: MapView
    // The current class controlls the map but also the state of the tabs
    // State
    activatedTab: ResultSet
    // UI elements
    sideBar: ResultsSidebar;



    constructor() {
        this.mapView = new MapView()
        this.sideBar = new ResultsSidebar();
        this.activatedTab = this.getDefaultTab()
        this.mapView.setHandlerfn({
            onCleanUp: () => { this.sideBar.resetList() },
            onFeatureHover: (doi) => { this.sideBar.highlight(doi, { scroll: true }) },
            onFeatureOut: (doi) => { this.sideBar.removeHighlight(doi) }
        })
        this.sideBar.setHandlerfn({
            onFeatureHover: (doi) => {
                this.mapView.setMarkersStyle({
                    doi,
                    resultSet: this.activatedTab,
                    highlightOrReset: 'highlight'
                });
            }, onFeatureOut: (doi) => {
                this.mapView.setMarkersStyle({
                    doi: doi,
                    resultSet: this.activatedTab,
                    highlightOrReset: 'reset'
                })
            }
        })
    }


    public async getJsonFromRequest(boundingBox: string): Promise<InclusiveExclusiveGeoJsonDataPublications> {
        const parameters = { boundingBox, limit: '10' }
        const params = new URLSearchParams(parameters);

        const route = '/api/geoJsonDataPublications?' + params;

        const response: Response = await fetch(route, {
            method: "GET",
        });
        if (!response.ok) {
            throw new Error('The response failed with status: ' + response.status + ' - ' + response.statusText);
        }
        const data = (await response.json()).data
        return data;
    }

    public insideFilter() {
        this.setActivatedTab(INCLUSIVE)
    }
    public overlapFilter() {
        this.setActivatedTab(EXCLUSIVE)
    }

    public enableDrawing() {
        this.mapView.setDrawingEnable(true)
        this.mapView.removeAllLayers(
        )
        this.sideBar.resetList()
    }
    public completeDrawing() {
        const boundingBox = this.mapView.draw();
        if (!boundingBox) return;
        this.mapView.setDrawingEnable(false)
        this.addFeaturesAndSidebarInMap(boundingBox);
        this.getDefaultTab()
    }

    public removeDrawing() {
        this.mapView.removeAllLayers()
        this.sideBar.resetList()
        this.mapView.setDrawingEnable(false)
        this.getDefaultTab()
    }



    private setActivatedTab(activatedTab: ResultSet) {
        this.activatedTab = activatedTab
        this.sideBar.handleActivationOfTab(activatedTab)()
        this.mapView.handleActivatedLayers(activatedTab)
    }
    private getDefaultTab(): ResultSet {
        for (const [tabName, tabInfo] of Object.entries(TAB_CONFIG) as Entries<typeof TAB_CONFIG>) {
            if (tabInfo.active) {
                this.setActivatedTab(tabName);
                return tabName;
            }
        }
        throw new Error(' Default result set value was not set. This is a bug.')
    }



    private async addFeaturesAndSidebarInMap(boundingBox: string) {

        const geo = await this.getJsonFromRequest(boundingBox);
        await this.mapView.drawResponse(geo);
        this.sideBar.populate(geo);

    }
}





const mapController = new MapController();
const menuButtons = new MenuButtons(mapController)


