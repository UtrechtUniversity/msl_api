import { Evented } from "leaflet";
import type * as Leaflet from 'leaflet';
import type { DataPublication, InclusiveExclusiveGeoJsonDataPublications } from "./datapublication.ts";
import { type InclusiveOrExclusive, type MappingOnTabs } from "./map.js";


type ViewPerTab = { _tab: HTMLLIElement, _listView: HTMLElement }
export interface Sidebar {
    // private methods
    _sidebar: HTMLElement | null
    _pane: HTMLElement | null,
    _closeButton: HTMLSpanElement | null,
    _tab: HTMLElement | null,
    _container: HTMLElement | null,
    _tabViews: Partial<MappingOnTabs<ViewPerTab>>,
    _map: Leaflet.Map | null,
    _tabLink: null | HTMLAnchorElement,
    _initSideBarElement(id: string): void,
    _initTab(): void,
    _initContent(): void,
    _initPane: () => void,
    _onOpenClick(): void,
    _onCloseClick(): void,
    _options: { position: "left" },
    _createListItem(dataPublication: DataPublication): HTMLDivElement,
    _activateTab(activatedTab: InclusiveOrExclusive): void,
    // public methods

    open(): this,
    close(): this,
    includes: Evented,
    initialize({ id }: { id: string }): void
    highlight(id: string): void,
    removeHighlight(id: string): void,
    addTo(map: Leaflet.Map): this,
    populate(dataPublications: InclusiveExclusiveGeoJsonDataPublications): void,
    handleActivationOfTab(activatedTab: InclusiveOrExclusive): () => void
    setDefaultTab(): void,
    resetList(): void,

}

