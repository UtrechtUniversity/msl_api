import { assertNotNull } from "../helpers";
import type { Paginator } from "./utils";

export class Pagination {
    paginator: Paginator | null = null;
    range: {
        lowerRange: number;
        upperRange: number;
        rangeShown: number;
        count: number;
        currentPage: number;
    } | null = null;
    constructor() {}

    public setArgs(paginator: Paginator) {
        this.paginator = paginator;
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
