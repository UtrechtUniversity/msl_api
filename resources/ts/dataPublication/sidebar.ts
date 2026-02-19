/* global L */

import { Control, DomEvent, DomUtil, Evented, Mixin, type Map } from "leaflet";
import type { DataPublication } from "../types/datapublication.ts";
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
    _listView: null,
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

        const listView = DomUtil.create('div', 'list-view', mainPane);
        listView.id = 'data_publications_list';

        this._listView = listView
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
        const authors = dataPublication.creators.length > 0 ? dataPublication.creators.map(creator => creator.fullName).join(' | ') : '- no authors found -';
        item.className = 'data-publication-item';
        item.setAttribute('data-id', dataPublication.doi)
        item.innerHTML = `
                <div class="data-publication-title"> ${dataPublication.title ?? '- no title found -'}</div>
                <div class="data-publication-authors"> ${authors}</div>
                <div class="data-publication-date"> ${dataPublication.dates[0]?.date ?? '- no date found -'} </div>
            `;
        return item
    },
    populate: function (dataPublications: DataPublication[]) {

        assertElementNotNull(this._listView, { name: 'data_publications_list', id: true })
        const list = this._listView

        list.innerHTML = '';
        dataPublications.forEach(dataPublication => {

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

            list.appendChild(item);
        });
        this.open();

    },
    resetList: function () {
        assertElementNotNull(this._listView, { name: 'data_publications_list', id: true })

        const parent = this._listView
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