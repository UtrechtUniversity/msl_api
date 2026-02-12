/* global L */

import { Control, DomEvent, DomUtil, Evented, Mixin, type Map } from "leaflet";
import type { DataPublication, InclusiveExclusiveGeoJsonDataPublications } from "../types/datapublication.ts";
import type { Sidebar } from "../types/sidebar.ts";
import { assertNotNull } from "../helpers.js";


export const sideBar = Control.extend<Sidebar>(/** @lends L.Control.Sidebar.prototype */ {
    includes: (Evented.prototype || Mixin.Events),

    _options: {
        position: 'left',
    },
    _sidebar: null,
    _pane: null,
    _closeButton: null,
    _tab: null,
    _tabLink: null,
    _container: null,
    _map: null,
    //TODO can I make them a list?
    _exclusiveTab: null,
    _inclusiveTab: null,
    _inclusiveListView: null,
    _exclusiveListView: null,
    initialize: function () {
        // Sidebar element
        this._initSideBarElement('sidebar')
        // Tabs
        this._initTab()
        // Content
        this._initContent()
        // Main pane
        this._initPane()
    },


    _initSideBarElement(id: string) {
        this._sidebar = DomUtil.get(id);
        assertNotNull(this._sidebar, `Sidebar element #${id} not found. This is a bug.`)

        DomUtil.addClass(this._sidebar, 'sidebar-' + this._options.position);
    },
    _initContent() {
        assertSideBarNotNull(this._sidebar)

        this._container = DomUtil.create('div', 'sidebar-content', this._sidebar);

        // Make sure scrolling in sidebar doesn't end up scrolling the page.
        this._container.addEventListener('mouseenter', () => {
            document.body.style.overflow = 'hidden';
        });
        this._container.addEventListener('mouseleave', () => {
            document.body.style.overflow = '';
        });
    },
    _initTab() {
        assertSideBarNotNull(this._sidebar)
        const tab = DomUtil.create('div', 'sidebar-tabs', this._sidebar)
        const ul = DomUtil.create('ul', '', tab);
        ul.setAttribute('role', 'tablist');

        const li = DomUtil.create('li', '', ul);
        const link: HTMLAnchorElement = DomUtil.create('a', '', li);
        link.href = '#main';
        link.setAttribute('role', 'tab');
        link.innerHTML = '<i class="fa fa-bars"></i>';

        DomEvent.on(link, 'click', this._onOpenClick, this);
        this._tabLink = link;
        this._tab = li;
    },

    _initPane() {
        assertElementNotNull(this._container, { name: '_container' })
        const mainPane = DomUtil.create('div', 'sidebar-pane', this._container);
        mainPane.id = 'home';

        const header = DomUtil.create('h1', 'sidebar-header', mainPane);
        header.textContent = 'Data publications';

        const closeButton = DomUtil.create('span', 'sidebar-close', header);
        DomUtil.create('i', 'fa fa-caret-left', closeButton);
        DomEvent.on(closeButton, 'click', this._onCloseClick, this);


        // Tabs container
        const tabs = DomUtil.create('div', 'sidebar-header-tabs', mainPane);

        const tabList = DomUtil.create('ul', 'tab-list', tabs);
        //Tab for exclusive datapublications
        this._exclusiveTab = DomUtil.create('li', 'tab active', tabList);
        this._exclusiveTab.textContent = 'Exclusive results';

        //Tab for inclusive datapublications
        this._inclusiveTab = DomUtil.create('li', 'tab', tabList);
        this._inclusiveTab.textContent = 'Inclusive results';


        //List for exclusive datapublications
        this._exclusiveListView = DomUtil.create('div', 'list-view', mainPane);
        this._exclusiveListView.id = 'exclusive_data_publications_list';

        //List for inclusive datapublications, hidden!!
        this._inclusiveListView = DomUtil.create('div', 'list-view hidden', mainPane);
        this._inclusiveListView.id = 'inclusive_data_publications_list';

        this._pane = mainPane
        this._closeButton = closeButton;
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


    /**
     * Open sidebar (if necessary).
     *
     */
    open: function () {
        assertElementNotNull(this._pane, { name: `_pane` });
        assertElementNotNull(this._tab, { name: `_tab` });
        assertSideBarNotNull(this._sidebar);

        DomUtil.addClass(this._pane, 'active');
        DomUtil.addClass(this._tab, 'active');


        // open sidebar (if necessary)
        assertSideBarNotNull(this._sidebar)
        if (DomUtil.hasClass(this._sidebar, 'collapsed')) {
            DomUtil.removeClass(this._sidebar, 'collapsed');
        }

        return this;
    },

    /**
     * Close the sidebar (if necessary).
     */
    close: function () {
        assertElementNotNull(this._pane, { name: `_pane` });
        assertElementNotNull(this._tab, { name: `_tab` });
        assertSideBarNotNull(this._sidebar);

        DomUtil.removeClass(this._pane, 'active');
        DomUtil.removeClass(this._tab, 'active');

        // close sidebar
        assertSideBarNotNull(this._sidebar)
        if (!DomUtil.hasClass(this._sidebar, 'collapsed')) {

            DomUtil.addClass(this._sidebar, 'collapsed');
        }

        return this;
    },

    _createListItem(dataPublication: DataPublication) {

        const item = document.createElement('div');
        item.className = 'data-publication-item';
        item.setAttribute('data-id', dataPublication.doi)
        item.innerHTML = `
                <div class="data-publication-title"> Title: ${dataPublication.title}</div>
                <div class="data-publication-authors"> Authors: ${dataPublication.creators[0].fullName} etc.</div>
                <div class="data-publication-date"> Date: ${dataPublication.dates[0]?.date ?? ''} </div>
            `;
        return item
    },
    populate: function (dataPublications: InclusiveExclusiveGeoJsonDataPublications) {
        //TODO can I reuse a function here?
        assertElementNotNull(this._exclusiveListView, { name: 'exclusive_data_publications_list', id: true })
        assertElementNotNull(this._inclusiveListView, { name: 'inclusive_data_publications_list', id: true })

        const excList = this._exclusiveListView

        excList.innerHTML = '';
        dataPublications.exclusive.data_publications.forEach(dataPublication => {

            const item = this._createListItem(dataPublication)

            item.addEventListener('mouseenter', () => {
                assertNotNull(this._map, `Map is undefined. This is a bug.`)
                this._map.fire('sidebar-hover', {
                    id: dataPublication.doi
                });

            });

            item.addEventListener('mouseleave', () => {
                assertNotNull(this._map, `Map is undefined. This is a bug.`)
                this._map.fire('sidebar-leave',
                    { id: dataPublication.doi })

            });

            excList.appendChild(item);
        });

        const incList = this._inclusiveListView

        incList.innerHTML = '';
        dataPublications.inclusive.data_publications.forEach(dataPublication => {

            const item = this._createListItem(dataPublication)

            item.addEventListener('mouseenter', () => {
                assertNotNull(this._map, `Map is undefined. This is a bug.`)
                this._map.fire('sidebar-hover', {
                    id: dataPublication.doi
                });

            });

            item.addEventListener('mouseleave', () => {
                assertNotNull(this._map, `Map is undefined. This is a bug.`)
                this._map.fire('sidebar-leave',
                    { id: dataPublication.doi })

            });

            incList.appendChild(item);
        });


        DomEvent.on(this._exclusiveTab!, 'click', () => {
            this._exclusiveTab!.classList.add('active');
            this._inclusiveTab!.classList.remove('active');
            this._exclusiveListView!.hidden = false;
            this._inclusiveListView!.hidden = true;
            this._map!.fire('tab-click', { id: 'exclusive' }
            );
        });

        DomEvent.on(this._inclusiveTab!, 'click', () => {
            this._inclusiveTab!.classList.add('active');
            this._exclusiveTab!.classList.remove('active');
            this._exclusiveListView!.hidden = true;
            this._inclusiveListView!.hidden = false;
            this._map!.fire('tab-click', { id: 'inclusive' }
            );
        });
        this.open();

    },


    // _handleActivationOfTab(tabName: 'inclusive' | 'exclusive') {
    //     return tabName === 'exclusive' ? () => {
    //         this._exclusiveTab!.classList.add('active');
    //         this._inclusiveTab!.classList.remove('active');
    //         this._exclusiveListView!.hidden = false;
    //         this._inclusiveListView!.hidden = true;
    //         this._map!.fire('tab-click', { id: 'exclusive' }
    //         ): () => {
    //             this._inclusiveTab!.classList.add('active');
    //             this._exclusiveTab!.classList.remove('active');
    //             this._exclusiveListView!.hidden = true;
    //             this._inclusiveListView!.hidden = false;
    //             this._map!.fire('tab-click', { id: 'inclusive' }
    //             );
    //         }

    //     }
    resetList: function () {
        assertElementNotNull(this._exclusiveListView, { name: 'data_publications_list', id: true })

        const parent = this._exclusiveListView
        while (parent.firstChild) {
            parent.firstChild.remove()
        }
        this.close()
    },



    _onOpenClick: function () {
        this.open();
    },


    _onCloseClick: function () {
        this.close();
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