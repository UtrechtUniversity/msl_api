// Pagination
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
// Keyword tree
export type FacetItem = { name: string; display_name: string; count: string };

// MapView
export type BoundingBox=[number,number,number,number]