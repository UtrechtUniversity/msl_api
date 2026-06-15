import { assertNotNull } from "../helpers";
import type { Paginator } from "./utils";

export class Pagination {
    paginateElement: HTMLElement;
    paginator: Paginator | null = null;
    range: {
        lowerRange: number;
        upperRange: number;
        rangeShown: number;
        count: number;
        currentPage: number;
    } | null = null;
    constructor() {
        const paginateElement = document.getElementById("results-pagination");
        assertNotNull(
            paginateElement,
            `There is not element for pagination of results. This is a bug.`,
        );
        this.paginateElement = paginateElement;
    }

    public setArgs(paginator: Paginator) {
        this.paginator = paginator;
    }

    populate() {}

    public clear() {
        //TODO clean up range and paginator?
        this.paginator = null;
        this.range = null;
        while (this.paginateElement.firstChild) {
            this.paginateElement.firstChild.remove();
        }
    }
    private getRange() {
        assertNotNull(
            this.paginator,
            `Paginator arguments should have been populated. This is a bug.`,
        );
        const rangeUnilateral = 2;

        this.range = {
            lowerRange: this.paginator.currentPage - rangeUnilateral,
            upperRange: this.paginator.currentPage + rangeUnilateral,
            rangeShown: rangeUnilateral * 2 + 1,
            count: this.paginator.lastPage,
            currentPage: this.paginator.currentPage,
        };
    }
}
