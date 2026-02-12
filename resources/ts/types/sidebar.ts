import { Evented } from "leaflet";
import type * as Leaflet from 'leaflet';
import type { DataPublication, GeoFeature, InclusiveExclusiveGeoJsonDataPublications } from "./datapublication.ts";

export interface Sidebar {
    // private methods
    _sidebar: HTMLElement | null
    _pane: HTMLElement | null,
    _closeButton: HTMLSpanElement | null,
    _tab: HTMLElement | null,
    _container: HTMLElement | null,
    _exclusiveTab: HTMLLIElement | null,
    _inclusiveTab: HTMLLIElement | null,
    _inclusiveListView: HTMLElement | null,
    _exclusiveListView: HTMLDivElement | null
    _map: Leaflet.Map | null,
    _tabLink: null | HTMLAnchorElement,
    _initSideBarElement(id: string): void,
    _initTab(): void,
    _initContent(): void,
    _initPane: () => void,
    _onOpenClick(): void,
    _onCloseClick(): void
    _options: { position: "left" },
    _createListItem(dataPublication: DataPublication): HTMLDivElement,
    // public methods

    open(): this,
    close(): this,
    includes: Evented,
    initialize({ id }: { id: string }): void
    highlight(id: string): void,
    removeHighlight(id: string): void,
    addTo(map: Leaflet.Map): this,
    populate(dataPublications: InclusiveExclusiveGeoJsonDataPublications): void,
    resetList(): void,

}

