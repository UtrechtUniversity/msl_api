import type { Paginator } from "./utils";

export class Pagination {
    paginator: Paginator | null = null;
    constructor() {}

    public setArgs(paginator: Paginator) {
        this.paginator = paginator;
    }
}
