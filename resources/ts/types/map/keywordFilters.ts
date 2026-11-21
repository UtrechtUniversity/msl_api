export type TextFilters = { [key: string]: string[] };
export type ActiveTextFilterInfo = TreeKeywordActiveInfo | FreeTextActiveInfo;

interface TreeKeywordActiveInfo extends TreeKeywordAddInfoWithType {
    id: string;
}
interface FreeTextActiveInfo extends FreeTextSearchQueryInfoWithType {
    id: string;
}

export interface TreeKeywordAddInfoWithType extends TreeKeywordInfo {
    type: TreeKeyword;
}
export interface FreeTextSearchQueryInfoWithType extends FreeTextSearchQueryInfo {
    type: FreeTextSearchQuery;
}
export type TreeKeywordInfo = {
    name: string;
    value: string;
    displayName: string;
};

export type FreeTextSearchQueryInfo = {
    value: string;
};

export const TREE_KEYWORD = "treeKeyword" as const;
export type TreeKeyword = typeof TREE_KEYWORD;

export const FREE_TEXT_SEARCH_QUERY = "freeTextSearchQuery" as const;
export type FreeTextSearchQuery = typeof FREE_TEXT_SEARCH_QUERY;

export type KeywordType = TreeKeyword | FreeTextSearchQuery;
