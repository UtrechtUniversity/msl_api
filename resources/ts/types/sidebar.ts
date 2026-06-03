import { Evented } from "leaflet";
import type * as Leaflet from 'leaflet';
import type { DataPublication, GeoJsonDataPublications, InclusiveExclusiveGeoJsonDataPublications } from "./datapublication.js";
import { type ResultSet, type ResultSetMapping } from "./map.js";


export type ViewPerTab = { _tab: HTMLElement | null, _listView: HTMLElement[] }
export interface Sidebar {
    // private methods
    _sidebar: HTMLElement | null
    _map: Leaflet.Map | null,
    _tabViews: ResultSetMapping<ViewPerTab>,
    _createListItem(dataPublication: DataPublication): HTMLDivElement,
    _activateTab(activatedTab: ResultSet): void,
    _initViews(): void,
    _list: HTMLElement | null,

    // public methods
    includes: Evented,
    initialize(): void,
    highlight(id: string, opts?: { scroll: boolean }): void,
    removeHighlight(id: string): void,
    addTo(map: Leaflet.Map): this,
    handleActivationOfTab(activatedTab: ResultSet): () => void
    populate(dataPublications: InclusiveExclusiveGeoJsonDataPublications): void,
    resetList(): void,
    onFeatureHover: (doi: string) => void,
    onFeatureOut: (doi: string) => void,
    setHandlerfn: ({ onFeatureHover, onFeatureOut }:
        {
            onFeatureHover: (doi: string) => void,
            onFeatureOut: (doi: string) => void
        }) => void

}