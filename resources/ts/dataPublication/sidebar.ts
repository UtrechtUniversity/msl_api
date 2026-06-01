/* global L */

import type { DataPublication, InclusiveExclusiveGeoJsonDataPublications } from "../types/datapublication";
import type { Sidebar, ViewPerTab } from "../types/sidebar";
import { EXCLUSIVE, INCLUSIVE, type ResultSet } from "../types/map";
import { Control, DomUtil, Evented, Mixin, type Map } from "leaflet";
import { assertNotNull } from "../helpers.js";
import { getResultSetMappingObj, TAB_CONFIG, type Entries, } from "./utils.js";



export const sideBar = Control.extend<Sidebar>(/** @lends L.Control.Sidebar.prototype */ {
    includes: (Evented.prototype || Mixin.Events),
    _sidebar: null,
    _map: null,
    _tabViews: getResultSetMappingObj(() => { return { _tab: null, _listView: [] } }),
    _list: null,

    initialize: function () {
        this._sidebar = document.querySelector(' #sidebar-content [data-content="Results"] #datapublication-results')
        this._initViews()
    },

    _initViews() {
        assertNotNull(this._sidebar, 'sidebar')
        // for (const [tabName, tabInfo] of Object.entries(TAB_CONFIG) as Entries<typeof TAB_CONFIG>) {
        const createdListView = DomUtil.create('div', 'list-view', this._sidebar)
        createdListView.id = 'data_publications_list'
        // createdListView.hidden = !tabInfo.active
        this._list = createdListView;
        for (const [tabName, tabInfo] of Object.entries(TAB_CONFIG) as Entries<typeof TAB_CONFIG>) {

            const tabButton = document.getElementById(tabInfo.buttonId)
            assertElementNotNull(tabButton, { name: tabInfo.buttonId, id: true })
            this._tabViews[tabName] = { _tab: tabButton, _listView: [] }

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
    highlight(id: string, { scroll }: { scroll: boolean } = { scroll: false }) {
        //We only want to highlight the element in the correct result set.
        const elements = $('#' + 'data_publications_list ' + '[data-id="' + id + '"]')
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
                <a href="${dataPublication.portalLink}" target="_blank" class="data-publication-link">
                 <div class="data-publication-content">
                <p class="data-publication-title"> ${dataPublication.title ?? '- no title found -'}</p>
                <p class="data-publication-authors"> ${authors}</p>
                <p class="data-publication-date"> ${dataPublication.dates[0]?.date ?? '- no date found -'} </p>
                </div>
                <span class="data-publication-icon">
                  ${icon}
                 </span>
            </a>
            `;
        return item
    },
    populate: function (dataPublications: InclusiveExclusiveGeoJsonDataPublications) {


        // for (const tabName of Object.keys(TAB_CONFIG) as Array<keyof typeof TAB_CONFIG>) {
        // const { _tab: _, _listView } = this._tabViews[tabName]
        if (!this._list) throw new Error('List view should not be null. This is a bug.')
        this._list.innerHTML = '';
        for (const dataPublication of dataPublications[EXCLUSIVE].data_publications) {
            const item = this._createListItem(dataPublication)
            if (!dataPublication.inclusive) this._tabViews[EXCLUSIVE]._listView.push(item)
            item.addEventListener('mouseover', () => {
                assertNotNull(this._map, `Map is undefined. This is a bug.`)
                this._map.fire('sidebar-hover', {
                    id: dataPublication.doi,
                    resultSet: EXCLUSIVE
                });

            });

            item.addEventListener('mouseleave', () => {
                assertNotNull(this._map, `Map is undefined. This is a bug.`)
                this._map.fire('sidebar-leave',
                    { id: dataPublication.doi, resultSet: EXCLUSIVE })

            });


            this._list.appendChild(item);
        };

        // }

    },


    resetList: function () {

        for (const tabName of Object.keys(TAB_CONFIG) as Array<keyof typeof TAB_CONFIG>) {

            // const tabElements = this._tabViews[tabName]
            assertNotNull(this._list,
                'The listview of tabViews was not populated properlym for the default tab. This is a bug.'
            )
            // const listView = tabElements._listView
            while (this._list.firstChild) {
                this._list.firstChild.remove()
            }
        }
    },

    handleActivationOfTab: function (activatedTab: ResultSet) {
        return () => {
            this._activateTab(activatedTab)

        }
    },
    _activateTab: function (activatedTab: ResultSet) {
        const deactivateTab = (activatedTab === EXCLUSIVE) ? INCLUSIVE : EXCLUSIVE
        const activatedTabElements = this._tabViews[activatedTab]
        const deactivatedTabElements = this._tabViews[deactivateTab]

        assertNotNull(this._map, `Map is undefined. This is a bug.`)
        assertTabElementsNotNull(activatedTabElements);
        assertTabElementsNotNull(deactivatedTabElements);



        if (activatedTab === INCLUSIVE) {

            this._tabViews[EXCLUSIVE]._listView.forEach((item) => item.classList.add('disabled'))

        }
        else {
            this._tabViews[EXCLUSIVE]._listView.forEach((item) => item.classList.remove('disabled'))
        }
        // activatedTabElements._tab.classList.add('active')
        // activatedTabElements._listView.hidden = false;
        // deactivatedTabElements._tab.classList.remove('active')
        // deactivatedTabElements._listView.hidden = true;
        this._map.fire('tab-click', { id: activatedTab }
        )

    },

});





function assertSingleArray<T>(arr: ArrayLike<T>, message: string): asserts arr is ArrayLike<T> & { 0: T; length: 1 } {
    if (arr.length !== 1) {
        throw new Error(`Expected array to have exactly 1 element, but it has ${arr.length}.`);
    }
}


function assertTabElementsNotNull(viewPerTab: ViewPerTab): asserts viewPerTab is { [K in keyof ViewPerTab]: NonNullable<ViewPerTab[K]> } {
    for (const [key, value] of Object.entries(viewPerTab)) {
        if (value == null) {
            throw new Error(`viewPerTab.${key} is null. This is a bug.`);
        }
    }
}

function assertElementNotNull(element: HTMLElement | null, { name, id }: { name: string, id?: true }): asserts element is HTMLElement {
    if (!id)
        return assertNotNull(sideBar, ` The element '${name}' should be set by now.This is a bug.`)
    return assertNotNull(sideBar, ` The element with id '${name}' should be set by now.This is a bug.`)

}