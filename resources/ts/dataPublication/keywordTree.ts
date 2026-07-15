import { assertNotUndefined } from "../helpers";

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

const TREES = {
    interpreted: {
        id: "#jstree-interpreted",
        name: "jstree-interpreted",
        filterToggle: "#filterTreeToggleInterpreted",
    },
    original: {
        id: "#jstree-original",
        name: "jstree-original",
        filterToggle: "#filterTreeToggleOriginal",
    },
} as const;

export class KeywordTree {
    private activeFilters: { [key: string]: string[] } = {};
    private activeNodes: Array<string> = [];
    private facets: { [key: string]: string[] } = {};
    private treeOptions = {};
    //TODO can it query before instantiation?
    private interpretedTree = $(TREES.interpreted.id);
    private interpretedToggle = $(TREES.interpreted.filterToggle);
    private originalTree = $(TREES.original.id);
    private originalToggle = $(TREES.original.filterToggle);

    constructor() {}

    public async init() {
        const interpretedJsonResponse = await fetch("/interpreted.json");
        //TODO Throw if there is error.
        const dataInterpreted: (TreeNode | TreeSubNode)[] =
            await interpretedJsonResponse.json();

        const originalJsonResponse = await fetch("/original.json");
        //TODO Throw if there is error.
        const dataOriginal: (TreeNode | TreeSubNode)[] =
            await originalJsonResponse.json();
        this.processNodes(dataInterpreted);
        this.processNodes(dataOriginal, true);
        this.treeOptions = {
            interpreted: createTreeOptions(dataInterpreted),
            original: createTreeOptions(dataOriginal),
        };
    }

    private processNodes(nodes: (TreeNode | TreeSubNode)[], original = false) {
        for (let i = nodes.length - 1; i >= 0; i--) {
            const node = nodes[i];
            assertNotUndefined(node, "Node is undefined. This is a bug.");
            if (node.extra.type == "filter") {
                const filterInActiveFilters =
                    this.activeFilters[node.extra.filterName];

                if (
                    filterInActiveFilters !== undefined &&
                    filterInActiveFilters.includes(node.extra.filterValue)
                ) {
                    node.state.checked = true;
                    this.activeNodes.push(node.id);
                }
                const filterInFacets = this.facets[node.extra.filterName];
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
                const facetInFacets = this.facets[node.extra.facetName];

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
                this.processNodes(node.children, original);
            }
        }
    }
}

function createTreeOptions(data: (TreeNode | TreeSubNode)[]) {
    return {
        core: {
            data: dataInterpreted,
            check_callback: false,
            themes: {
                dots: false,
                icons: false,
            },
        },
        checkbox: {
            three_state: false, // to avoid that fact that checking a node also check others
            whole_node: false, // to avoid checking the box just clicking the node
            tie_selection: false, // for checking without selecting and selecting without checking
        },
        plugins: ["checkbox", "search", "state"],
        search: {
            case_sensitive: false,
            show_only_matches: true,
        },
    };
}
