export type KeywordFilters = { [key: string]: string[] };
export type ActiveKeywordFilterInfo =
    | TreeKeywordActiveInfo
    | FreeTextActiveInfo;

interface TreeKeywordActiveInfo extends TreeKeywordAddInfoWithType {
    id: string;
}
interface FreeTextActiveInfo extends FreeTextAddInfoWithType {
    id: string;
}

export interface TreeKeywordAddInfoWithType extends TreeKeywordAddInfo {
    type: TreeKeyword;
}
export interface FreeTextAddInfoWithType extends FreeTextAddInfo {
    type: FreeTextSearchKeyword;
}
export type TreeKeywordAddInfo = {
    name: string;
    value: string;
    displayName: string;
};

export type FreeTextAddInfo = {
    value: string;
};

export const TREE_KEYWORD = "treeKeyword" as const;
export type TreeKeyword = typeof TREE_KEYWORD;

export const FREE_TEXT_SEARCH_KEYWORD = "freeTextKeyword" as const;
export type FreeTextSearchKeyword = typeof FREE_TEXT_SEARCH_KEYWORD;

export type KeywordType = TreeKeyword | FreeTextSearchKeyword;