import { Evented } from "leaflet";
import type * as Leaflet from 'leaflet';
import type { DataPublication, GeoJsonDataPublications, InclusiveExclusiveGeoJsonDataPublications } from "./datapublication.js";
import { type ResultSet, type ResultSetMapping } from "./map.js";


export type ViewPerTab = { _tab: HTMLLIElement | null, _listView: HTMLElement | null }
export interface Sidebar {
    // private methods
    _sidebar: HTMLElement | null
    _map: Leaflet.Map | null,
    _tabViews: ResultSetMapping<ViewPerTab>,
    _createListItem(dataPublication: DataPublication): HTMLDivElement,
    _initViews(): void,

    // public methods
    includes: Evented,
    initialize(): void,
    highlight(id: string, resultSet: ResultSet, opts?: { scroll: boolean }): void,
    removeHighlight(id: string): void,
    addTo(map: Leaflet.Map): this,
    populate(dataPublications: InclusiveExclusiveGeoJsonDataPublications): void,
    resetList(): void,

}