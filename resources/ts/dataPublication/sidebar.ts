/* global L */

import { Control, DomUtil, Evented, Mixin, type Map } from "leaflet";
import type { DataPublication, InclusiveExclusiveGeoJsonDataPublications } from "../types/datapublication.js";
import type { Sidebar, ViewPerTab } from "../types/sidebar.js";
import { assertNotNull } from "../helpers.js";
import { getResultSetMappingObj, TAB_CONFIG, type Entries, } from "./utils.js";
import { type ResultSet } from "../types/map.js";



export const sideBar = Control.extend<Sidebar>(/** @lends L.Control.Sidebar.prototype */ {
    includes: (Evented.prototype || Mixin.Events),
    _sidebar: null,
    _map: null,
    _tabViews: getResultSetMappingObj(() => { return { _tab: null, _listView: null } }),

    initialize: function () {
        this._sidebar = document.querySelector(' #sidebar-content [data-content="Results"] #datapublication-results')
        assertNotNull(this._sidebar, 'sidebar')
        for (const [tabName, tabInfo] of Object.entries(TAB_CONFIG) as Entries<typeof TAB_CONFIG>) {
            const createdListView = DomUtil.create('div', 'list-view', this._sidebar)
            createdListView.id = tabName + '_data_publications_list'
            createdListView.hidden = !tabInfo.active
            this._tabViews[tabName] = { _tab: null, _listView: createdListView }

        }
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
    highlight(id: string, resultSet: ResultSet, { scroll }: { scroll: boolean } = { scroll: false }) {
        //We only want to highlight the element in the correct result set.
        const elements = $('#' + resultSet + '_data_publications_list ' + '[data-id="' + id + '"]')
        assertSingleArray(elements, `Found more than one datapublications with doi '${id}' to highlight. This is a bug. `)
        const element = elements[0]
        element.classList.add('highlight');
        if (scroll) {
            element.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'start' })
        }
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
    populate: function (dataPublications: InclusiveExclusiveGeoJsonDataPublications) {


        for (const tabName of Object.keys(TAB_CONFIG) as Array<keyof typeof TAB_CONFIG>) {
            const { _tab, _listView } = this._tabViews[tabName]
            if (!_listView) throw new Error('List view should not be null. This is a bug.')
            _listView.innerHTML = '';
            for (const dataPublication of dataPublications[tabName].data_publications) {
                const item = this._createListItem(dataPublication)

                item.addEventListener('mouseover', () => {
                    assertNotNull(this._map, `Map is undefined. This is a bug.`)
                    this._map.fire('sidebar-hover', {
                        id: dataPublication.doi,
                        resultSet: tabName
                    });

                });

                item.addEventListener('mouseleave', () => {
                    assertNotNull(this._map, `Map is undefined. This is a bug.`)
                    this._map.fire('sidebar-leave',
                        { id: dataPublication.doi, resultSet: tabName })

                });


                _listView.appendChild(item);
            };

        }

    },


    resetList: function () {

        for (const tabName of Object.keys(TAB_CONFIG) as Array<keyof typeof TAB_CONFIG>) {

            const tabElements = this._tabViews[tabName]
            assertNotNull(tabElements._listView,
                'The listview of tabViews was not populated properlym for the default tab. This is a bug.'
            )
            const listView = tabElements._listView
            while (listView.firstChild) {
                listView.firstChild.remove()
            }
        }
    }

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

function assertSingleArray<T>(arr: ArrayLike<T>, message: string): asserts arr is ArrayLike<T> & { 0: T; length: 1 } {
    if (arr.length !== 1) {
        throw new Error(`Expected array to have exactly 1 element, but it has ${arr.length}.`);
    }
}

