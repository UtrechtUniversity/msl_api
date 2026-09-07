export type Paginator = {
    resultsCount: number;
    totalCount: number;
    currentPage: number;
    lastPage: number;
    perPage: number;
};

export type Facets = {
    [key: string]: {
        items: FacetItem[];
    };
};
export type FacetItem = { name: string; display_name: string; count: string };