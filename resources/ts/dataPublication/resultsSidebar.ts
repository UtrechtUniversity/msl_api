/* global L */

import type { DataPublication, InclusiveExclusiveGeoJsonDataPublications } from '../types/datapublication';
import { EXCLUSIVE, INCLUSIVE, type ResultSet, type ResultSetMapping } from '../types/map';
import { DomUtil } from 'leaflet';
import { assertNotNull } from '../helpers.js';
import { assertSingleArray, TAB_CONFIG, throwWhenCallBackNotInitialized } from './utils.js';



type ViewPerTab = { listView: HTMLElement[] }




export class ResultsSidebar {
    private sidebar: HTMLElement
    private listDiv: HTMLDivElement
    private listViewPerTab: ResultSetMapping<ViewPerTab>
    public onFeatureHover: (doi: string) => void = throwWhenCallBackNotInitialized
    public onFeatureOut: (doi: string) => void = throwWhenCallBackNotInitialized
    constructor() {
        const sideBarElement: HTMLElement | null = document.querySelector(' #sidebar-content [data-content="Results"] #datapublication-results')
        assertNotNull(sideBarElement, 'sidebar')
        this.sidebar = sideBarElement


        const createdListView = DomUtil.create('div', 'list-view', this.sidebar)
        createdListView.id = 'data_publications_list'
        this.listDiv = createdListView;
        this.listViewPerTab = (Object.keys(TAB_CONFIG) as Array<ResultSet>)
            .reduce((acc, key) => {
                acc[key] = { listView: [] };
                return acc;
            }, {} as { [key in ResultSet]: ViewPerTab })
    }


    public setHandlerfn({
        onFeatureHover,
        onFeatureOut
    }: {
        onFeatureHover: (doi: string) => void,
        onFeatureOut: (doi: string) => void
    }): void {
        this.onFeatureHover = onFeatureHover
        this.onFeatureOut = onFeatureOut
    }


    /**
        * Highlight items of the map related to specific
        * data publication
        */
    public highlight(id: string, { scroll }: { scroll: boolean } = { scroll: false }): void {
        //We only want to highlight the element in the correct result set.
        const elements = $('#' + 'data_publications_list ' + '[data-id="' + id + '"]')
        assertSingleArray(elements, `Found more than one datapublications with doi '${id}' to highlight. This is a bug. `)
        const element = elements[0]
        element.classList.add('highlight');
        if (scroll) {
            element.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'start' })
        }
    }


    /**
       * Remove highlight in items of the map related to specific
       * data publication
       */
    public removeHighlight(id: string): void {
        $('[data-id="' + id + '"]').removeClass('highlight');
    }


    private _createListItem(dataPublication: DataPublication): HTMLDivElement {

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
    }



    public populate(dataPublications: InclusiveExclusiveGeoJsonDataPublications): void {

        this.listDiv.innerHTML = '';
        for (const dataPublication of dataPublications[EXCLUSIVE].data_publications) {
            const item = this._createListItem(dataPublication)
            //TODO there is something wrong with semantics here
            if (!dataPublication.inclusive) this.listViewPerTab[EXCLUSIVE].listView.push(item)
            item.addEventListener('mouseover', () => {
                this.onFeatureHover(dataPublication.doi);
                this.highlight(dataPublication.doi)

            });
            item.addEventListener('mouseleave', () => {
                this.onFeatureOut(dataPublication.doi);
                this.removeHighlight(dataPublication.doi)

            });
            this.listDiv.appendChild(item);
        };

    }


    public resetList(): void {
        while (this.listDiv.firstChild) {
            this.listDiv.firstChild.remove()
        }
    }

    public handleActivationOfTab(activatedTab: ResultSet): () => void {
        return () => {
            this.activateTab(activatedTab)

        }
    }
    private activateTab(activatedTab: ResultSet): void {

        if (activatedTab === INCLUSIVE) {

            this.listViewPerTab[EXCLUSIVE].listView.forEach((item) => item.classList.add('disabled'))

        }
        else {
            this.listViewPerTab[EXCLUSIVE].listView.forEach((item) => item.classList.remove('disabled'))
        }

    }

};

