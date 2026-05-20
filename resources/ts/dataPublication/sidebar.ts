/* global L */

import { Control, DomUtil, Evented, Mixin, type Map } from "leaflet";
import type { DataPublication, GeoJsonDataPublications, InclusiveExclusiveGeoJsonDataPublications } from "../types/datapublication.js";
import type { Sidebar, ViewPerTab } from "../types/sidebar.js";
import { assertNotNull } from "../helpers.js";
import { TAB_CONFIG, } from "./utils.js";


//TODO Rename from sidebar

export const sideBar = Control.extend<Sidebar>(/** @lends L.Control.Sidebar.prototype */ {
    includes: (Evented.prototype || Mixin.Events),
    _sidebar: null,
    _map: null,
    _resultList: null,
    // TODO we need an element which will include the list of datapublications
    initialize: function () {
        this._sidebar = document.querySelector('#sidebar-content [data-content="Results"]')
        assertNotNull(this._sidebar, 'sidebar')
        this._resultList = document.querySelector('#datapublication-results')
        assertNotNull(this._resultList, 'resultList')

        this._resultList.className = 'list-view'
        this._resultList.id = 'data_publication_list'
        this._sidebar.appendChild(this._resultList)
    },

    /**
        * Add this sidebar to the specified map.
        *
        * @param {L.Map} map
        * @returns {Sidebar}
        */
    addTo: function (map: Map): Sidebar {

        this._map = map;
        return this;
    },
    /**
        * Highlight items of the map related to specific
        * data publication
        */
    highlight(id: string) {
        $('[data-id="' + id + '"]').addClass('highlight');
    },
    /**
        * Remove highlight in items of the map related to specific
        * data publication
        */
    removeHighlight(id: string) {
        $('[data-id="' + id + '"]').removeClass('highlight');
    },

    _createListItem(dataPublication: DataPublication) {

        const item = document.createElement('div');
        item.className = 'data-publication-item';
        item.setAttribute('data-id', dataPublication.doi)

        const authors = dataPublication.creators.length > 0 ? dataPublication.creators.map(creator => creator.fullName).join(' | ') : '- no authors found -';
        const icon = (dataPublication.inclusive) ? '<i class="fa-solid fa-circle-xmark"></i>' : '<i class="fa-solid fa-xmark"></i>'

        item.innerHTML = `
                <a href="${dataPublication.portalLink}" target="_blank" class="publication-link">
                 <div class="data-publication-content">
                <p class="data-publication-title"> ${dataPublication.title ?? '- no title found -'}</p>
                <p class="data-publication-authors"> ${authors}</p>
                <p class="data-publication-date"> ${dataPublication.dates[0]?.date ?? '- no date found -'} </p>
                </div>
                <span class="publication-icon">
                  ${icon}
                 </span>
            </a>
            `;
        return item
    },
    populate: function (dataPublications: { 'all': GeoJsonDataPublications }) {


        assertElementNotNull(this._resultList, { name: "resultList" });

        this._resultList.innerHTML = '';
        dataPublications['all'].data_publications.forEach(dataPublication => {

            const item = this._createListItem(dataPublication)

            item.addEventListener('mouseover', () => {
                assertNotNull(this._map, `Map is undefined. This is a bug.`)
                this._map.fire('sidebar-hover', {
                    id: dataPublication.doi,
                    resultSet: 'all'
                });

            });

            item.addEventListener('mouseleave', () => {
                assertNotNull(this._map, `Map is undefined. This is a bug.`)
                this._map.fire('sidebar-leave',
                    { id: dataPublication.doi, resultSet: 'all' })

            });


            this._resultList!.appendChild(item);
        });



    },


    resetList: function () {

        for (const tabName of Object.keys(TAB_CONFIG) as Array<keyof typeof TAB_CONFIG>) {
            //TODO fix this
            const tabElements = DomUtil.create('div', 'bla');
            assertNotNull(tabElements,
                'The listview of tabViews was not populated properlym for the default tab. This is a bug.'
            )
            const listView = tabElements
            while (listView.firstChild) {
                listView.firstChild.remove()
            }
        }

    },




});









function assertSideBarNotNull(sideBar: HTMLElement | null): asserts sideBar is HTMLElement {
    return assertNotNull(sideBar, `Sidebar should be set by now.This is a bug.`)
}

function assertElementNotNull(element: HTMLElement | null, { name, id }: { name: string, id?: true }): asserts element is HTMLElement {
    if (!id)
        return assertNotNull(sideBar, ` The element '${name}' should be set by now.This is a bug.`)
    return assertNotNull(sideBar, ` The element with id '${name}' should be set by now.This is a bug.`)

}

function assertTabElementsNotNull(viewPerTab: ViewPerTab): asserts viewPerTab is { [K in keyof ViewPerTab]: NonNullable<ViewPerTab[K]> } {
    for (const [key, value] of Object.entries(viewPerTab)) {
        if (value == null) {
            throw new Error(`viewPerTab.${key} is null. This is a bug.`);
        }
    }
}