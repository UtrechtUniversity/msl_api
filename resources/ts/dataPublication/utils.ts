import { assertNotUndefined } from "../helpers.js";
import {
    EXCLUSIVE,
    INCLUSIVE,
    INSIDE,
    OVERLAPPING,
    type GeoFeatureResultSet,
    type GeoFeatureResultSetMapping,
} from "../types/map.js";

export const LEFT_ARROW_ICON = `
<svg class="chevron-icon" viewBox="0 0 24 24" fill="currentColor">
    <path d="M10.8284 12.0007L15.7782 16.9504L14.364 18.3646L8 12.0007L14.364 5.63672L15.7782 7.05093L10.8284 12.0007Z"/>
</svg>
`;

export const RIGHT_ARROW_ICON = `<svg class="chevron-icon" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M13.1717 12.0007L8.22192 7.05093L9.63614 5.63672L16.0001 12.0007L9.63614 18.3646L8.22192 16.9504L13.1717 12.0007Z"></path></svg>`;
const EXCLUSIVE_ICON = `<img src='images/map/exclusive_icon.svg' width="30px"></img>`;
const INCLUSIVE_ICON = `<img src='images/map/inclusive_icon.svg' width="30px"></img>`;
export function getGeoFeatureResultSetMappingObj<T>(
    factory: () => T,
): GeoFeatureResultSetMapping<T> {
    return { [OVERLAPPING]: factory(), [INSIDE]: factory() };
}
export const TAB_CONFIG = {
    [OVERLAPPING]: {
        label: OVERLAPPING,
        icon: EXCLUSIVE_ICON,
        active: true,
        filterOnDataPublications: EXCLUSIVE,
    },
    [INSIDE]: {
        label: INSIDE,
        icon: INCLUSIVE_ICON,
        active: false,
        filterOnDataPublications: INCLUSIVE,
    },
} as const;

export type Entries<T> = Array<
    {
        [K in keyof T]: [K, T[K]];
    }[keyof T]
>;

export const LAT_LONG_RANGE = {
    MAX: { LAT: 90, LONG: 180 },
    MIN: { LAT: -90, LONG: -180 },
} as const;

export function assertSingleArray<T>(
    arr: ArrayLike<T>,
    message: string,
): asserts arr is ArrayLike<T> & { 0: T; length: 1 } {
    if (arr.length !== 1) {
        throw new Error(message);
    }
}

export function throwWhenCallBackNotInitialized() {
    throw new Error(
        "Initialization of a callback did not happen. This is a bug.",
    );
}

export function getDefaultTab(): GeoFeatureResultSet {
    for (const [tabName, tabInfo] of Object.entries(TAB_CONFIG) as Entries<
        typeof TAB_CONFIG
    >) {
        if (tabInfo.active) {
            return tabInfo.label;
        }
    }
    throw new Error("No default tab in config. This is a bug.");
}

export type Paginator = {
    resultsCount: number;
    totalCount: number;
    currentPage: number;
    lastPage: number;
    perPage: number;
};

export type TreeNode = {
    text: string;
    state: NodeState;
    extra: NodeExtra;
    children: TreeSubNode[];
};

export type TreeSubNode = {
    id: string;
    text: string;
    state: SubNodeState;
    extra: SubTreeExtra;
    children: TreeSubNode[];
};

interface SubTreeExtra {
    type: "filter";
    filterName: "msl_enriched_keyword_uri" | "msl_original_keyword_uri";
    filterValue: string;
}

interface SubNodeState {
    disabled: boolean;
}
interface NodeExtraWithoutFacet {
    type: "filter";
    url: string;
    filterName: string;
    filterValue: string;
}

interface NodeExtraWithFacet {
    type: "filter";
    url: string;
    filterName: string;
    filterValue: string;
    includeFacet: boolean;
    facetName: string;
}

type NodeExtra = NodeExtraWithFacet | NodeExtraWithoutFacet;
interface NodeState {
    opened: boolean;
    disabled: boolean;
    selected: boolean;
    checked: boolean;
}

export function processNodes(
    nodes: (TreeNode | TreeSubNode)[],
    original = false,
    {
        activeFilters,
        activeNodes,
        facets,
    }: {
        activeFilters: { [key: string]: string[] };
        activeNodes: Array<string>;
        facets: { [key: string]: string[] };
    },
) {
    for (let i = nodes.length - 1; i >= 0; i--) {
        const node = nodes[i];
        assertNotUndefined(node, "Node is undefined. This is a bug.");
        if (node.extra.type == "filter") {
            const filterInActiveFilters = activeFilters[node.extra.filterName];

            if (
                filterInActiveFilters !== undefined &&
                filterInActiveFilters.includes(node.extra.filterValue)
            ) {
                node.state.checked = true;
                activeNodes.push(node.id);
            }
            const filterInFacets = facets[node.extra.filterName];
            if (filterInFacets) {
                const result = filterInFacets.items.find((obj) => {
                    return obj.name == node.extra.filterValue;
                });

                if (result) {
                    node.state.disabled = false;
                    node.text =
                        node.text +
                        ' <span class="badge bg-primary text-primary-800 rounded-pill">' +
                        result.count +
                        "</span>";
                }
            }
        }

        if ("includeFacet" in node.extra) {
            const facetInFacets = facets[node.extra.facetName];

            if (facetInFacets) {
                for (let x = 0; x < facetInFacets.items.length; x++) {
                    const newNode: TreeSubNode = {
                        text: facetInFacets.items[x].display_name,
                        id: node.extra.facetName + "-" + x,
                        state: {
                            opened: false,
                            disabled: false,
                            selected: false,
                            checked: false,
                        },
                        extra: {
                            type: "filter",
                            url: "",
                            filterName: node.extra.facetName,
                            filterValue: facetInFacets.items[x].name,
                        },
                        children: [],
                    };

                    node.children.push(newNode);
                }
            }
        }

        if (node.children.length > 0) {
            processNodes(node.children, original, {
                activeFilters,
                activeNodes,
                facets,
            });
        }
    }
}

export type Facets = { [key: string]: { [key: string]: string[] } };
