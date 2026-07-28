import { assertNotUndefined } from "../helpers";
import "jstree";
import {
    throwWhenCallBackNotInitialized,
    type FacetItem,
    type Facets,
    type KeywordFilters,
} from "./utils";
import { omit } from "lodash";

const INTERPRETED = "interpreted" as const;
type Interpreted = typeof INTERPRETED;

const ORIGINAL = "original" as const;
type Original = typeof ORIGINAL;

type TreeNodeWithParent = (TreeNode | TreeSubNode) & {
    parent: TreeNodeWithParent;
};
export type TreeNode = {
    id: string;
    text: string;
    originalText: string;
    state: NodeState;
    extra: NodeExtra;
    children: TreeSubNode[];
};

export type TreeSubNode = {
    id: string;
    text: string;
    originalText: string;
    state: SubNodeState;
    extra: SubTreeExtra;
    children: TreeSubNode[];
};

interface SubTreeExtra {
    type: "filter";
    url: string;
    //"msl_enriched_keyword_uri" | "msl_original_keyword_uri"
    filterName: string;
    filterValue: string;
}

interface SubNodeState {
    opened: boolean;
    disabled: boolean;
    selected: boolean;
    checked: boolean;
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

interface JsTreeCheckEventData {
    instance: JSTree;
    node: { original: TreeNode | TreeSubNode };
    selected: string[];
    event?: Event;
}

const TREES = {
    interpreted: {
        id: "#jstree-interpreted",
        name: "jstree-interpreted",
        filterToggle: "#filterTreeToggleInterpreted",
        type: INTERPRETED,
    },
    original: {
        id: "#jstree-original",
        name: "jstree-original",
        filterToggle: "#filterTreeToggleOriginal",
        type: ORIGINAL,
    },
} as const;

const IS_INTERPRETED_FILTER_ENABLED =
    "datapublicationMapInterpretedFilters" as const;
// @Decision:
// The state of tree, interpreted or original, is not of relevant in MapController.
// The filtering works the same for any keyword, since original is a subtree of interpreted.
// Also, the main tree is the interpreted one,
// and we might want to get rid of the original one sooner or later.
export class KeywordTree {
    // TODO do I need this?
    private activeNodes: Array<string> = [];
    private facets: Facets = {};
    private treeOptions = { interpreted: {}, original: {} };
    private dataInterpreted: (TreeNode | TreeSubNode)[] = [];
    private dataOriginal: (TreeNode | TreeSubNode)[] = [];
    private interpretedTree: JQuery<HTMLElement> = $(TREES.interpreted.id);
    private interpretedToggle: JQuery<HTMLInputElement> = $(
        TREES.interpreted.filterToggle,
    );
    private originalTree: JQuery<HTMLElement> = $(TREES.original.id);
    private originalToggle: JQuery<HTMLInputElement> = $(
        TREES.original.filterToggle,
    );

    private onKeywordFilterUpdate: (
        type: "remove" | "add",
        filter: {
            key: string;
            value: string;
        },
    ) => Promise<void> | void = throwWhenCallBackNotInitialized;

    public setHandlerfn({
        onKeywordFilterUpdate,
    }: {
        onKeywordFilterUpdate: (
            type: "remove" | "add",
            filter: {
                key: string;
                value: string;
            },
        ) => Promise<void>;
    }) {
        this.onKeywordFilterUpdate = onKeywordFilterUpdate;
    }

    public async init(facets: Facets) {
        this.facets = facets;
        const interpretedJsonResponse = await fetch("/interpreted.json");
        //TODO Throw if there is error.
        this.dataInterpreted = await interpretedJsonResponse.json();

        const originalJsonResponse = await fetch("/original.json");
        //TODO Throw if there is error.
        this.dataOriginal = await originalJsonResponse.json();
        this.preProcessNodes(this.dataInterpreted);
        this.processNodes(this.dataInterpreted);
        this.preProcessNodes(this.dataOriginal);
        this.processNodes(this.dataOriginal, true);
        this.treeOptions = {
            interpreted: createTreeOptions(this.dataInterpreted),
            original: createTreeOptions(this.dataOriginal),
        };
        this.createTrees();
    }

    private createTrees() {
        // A. Initialize trees
        this.initTree(this.interpretedTree, TREES.interpreted);
        this.initTree(this.originalTree, TREES.original);
        const self = this;
        // Jqueries when document is ready
        $(function () {
            const interpretedInStorage = localStorage.getItem(
                IS_INTERPRETED_FILTER_ENABLED,
            );
            if (interpretedInStorage !== null) {
                self.setActiveTree(
                    interpretedInStorage === "false" ? ORIGINAL : INTERPRETED,
                );
            }

            $("#search-filters").keyup(function () {
                const searchString = $(this).val();
                self.interpretedToggle.is(":checked")
                    ? self.interpretedTree.jstree("search", searchString)
                    : self.originalTree.jstree("search", searchString);
            });

            self.toggleToAnotherTree(INTERPRETED);
            self.toggleToAnotherTree(ORIGINAL);

            $("#expand_all").on("click", function () {
                self.interpretedToggle.is(":checked")
                    ? self.interpretedTree.jstree("open_all")
                    : self.originalTree.jstree("open_all");
            });

            $("#close_all").on("click", function () {
                self.interpretedToggle.is(":checked")
                    ? self.interpretedTree.jstree("close_all")
                    : self.originalTree.jstree("close_all");
            });

            self.hideEmptyTerms();
        });
    }

    private initTree(
        tree: JQuery<HTMLElement>,
        { name, type }: { name: string; type: Original | Interpreted },
    ) {
        const options =
            type === INTERPRETED
                ? this.treeOptions.interpreted
                : this.treeOptions.original;
        tree.jstree({
            ...options,
            state: {
                key: name,
                // We use this function as filter,
                // so that when we reload the page,
                // the checks are removed as fefault state
                filter: function (state: JSTreeStaticDefaults) {
                    state = omit(state, "checkbox");
                    return state;
                },
            },
        })
            .on("state_ready.jstree", async () => {
                tree.on(
                    "check_node.jstree uncheck_node.jstree",
                    await this.handleFilterChange(),
                );
            })
            .on(
                "ready.jstree",
                (_: Event, { instance }: JsTreeCheckEventData) => {
                    this.activeNodes.forEach(instance._open_to.bind(instance));
                },
            );
    }

    private async handleFilterChange(): Promise<
        (e: JQuery.Event, data: JsTreeCheckEventData) => void
    > {
        return async (e: JQuery.Event, data: JsTreeCheckEventData) => {
            if (data.node.original.extra.type == "filter") {
                const key = data.node.original.extra.filterName;
                const value = data.node.original.extra.filterValue;
                if (e.type == "check_node") {
                    await this.onKeywordFilterUpdate("add", {
                        key,
                        value,
                    });
                } else if (e.type == "uncheck_node") {
                    await this.onKeywordFilterUpdate("remove", {
                        key,
                        value,
                    });
                }
            }
        };
    }

    public updateTrees(facets: Facets, activeFilters: KeywordFilters) {
        this.facets = facets;
        this.updateTree(this.dataOriginal, ORIGINAL, activeFilters);
        this.updateTree(this.dataInterpreted, INTERPRETED, activeFilters);
    }
    /**
     * Update the tree. More specifically:
     * - A. The static part of the tree is being updated, by renaming.
     * - B. The dynamic part of the tree gets deleted and recreated.
     *
     * Note: in this method we don't change the default data structures as in 'processNodes()',
     * but only the jstrees themselves
     *
     * @param nodes  Data nodes holding information about a tree
     * @param treeType Type of the tree: original or interpreted
     * @param activeFilters
     * @param { disableByDefault:boolean } Option for disabling subtrees
     */
    private updateTree(
        nodes: (TreeNode | TreeSubNode)[],
        treeType: Original | Interpreted,
        activeFilters: KeywordFilters,
        { disableByDefault }: { disableByDefault: boolean } = {
            disableByDefault: false,
        },
    ) {
        for (let i = nodes.length - 1; i >= 0; i--) {
            const node = nodes[i];
            assertNotUndefined(node, `Node is undefined. This is a bug.`);

            const tree =
                treeType === ORIGINAL
                    ? this.originalTree.jstree(true)
                    : this.interpretedTree.jstree(true);
            // We want nodes from subtrees to be disabled initially
            if (disableByDefault) {
                tree.disable_node(node.id);
            }
            // A.
            const nodeInFacets = this.facets[node.extra.filterName];
            if (!nodeInFacets) {
                tree.rename_node(node.id, `${node.originalText}`);
            } else {
                const result = nodeInFacets.items.find((obj) => {
                    return obj.name == node.extra.filterValue;
                });
                if (!result) {
                    tree.rename_node(node.id, `${node.originalText}`);
                } else {
                    // If current nodes is included in the facets, then rename and enable
                    tree.enable_node(node.id);
                    tree.rename_node(
                        node.id,
                        `${node.originalText} <span class="badge bg-primary text-primary-800 rounded-pill">${result.count}</span>`,
                    );
                }
            }
            const newChildren = [];
            // B.
            // If current node include children nodes that can be a part of the dynamic facets, then  delete them
            // Recreate children nodes, only if there are results relevant to them.
            if ("includeFacet" in node.extra) {
                //The node here is the parent of facets' nodes
                const parent = tree.get_node(node.id);
                tree.delete_node(parent.children);

                const facetInFacets = this.facets[node.extra.facetName];
                if (facetInFacets) {
                    for (const [x, facetItem] of Object.entries(
                        facetInFacets.items,
                    )) {
                        const filterName = node.extra.facetName;
                        const filterValue = facetItem.name;

                        const valuesOfActiveFilter = activeFilters[filterName];

                        const newNode: TreeSubNode = createSubNode({
                            filterName,
                            filterValue,
                            facetItem,
                            facetNumber: x,
                        });
                        // Make sure that the children node is checked if it is an active filter.
                        newNode.state.checked =
                            !!valuesOfActiveFilter &&
                            valuesOfActiveFilter.includes(filterValue);

                        newChildren.push(newNode);

                        tree.create_node(node, newNode);
                    }
                }
            }
            // If we have created new children, then the recursion goes one with them.
            if (newChildren.length > 0) {
                this.updateTree(newChildren, treeType, activeFilters, {
                    disableByDefault: true,
                });
                // Else the recursion goes on with the children as in the default data structure.
            } else if (node.children.length > 0) {
                this.updateTree(node.children, treeType, activeFilters, {
                    disableByDefault: true,
                });
            }
        }
    }

    //B. Toggle between trees
    private toggleToAnotherTree(type: Interpreted | Original) {
        const self = this;
        const tree =
            type === INTERPRETED ? self.interpretedToggle : self.originalToggle;
        tree.on("change", function () {
            if (this.checked) {
                localStorage.setItem(
                    IS_INTERPRETED_FILTER_ENABLED,
                    "" + (type === INTERPRETED ? this.checked : !this.checked),
                );

                self.setActiveTree(
                    type === INTERPRETED ? INTERPRETED : ORIGINAL,
                );
            }
            if (!this.checked) {
                localStorage.setItem(
                    IS_INTERPRETED_FILTER_ENABLED,
                    "" + (type === INTERPRETED ? !this.checked : this.checked),
                );

                self.setActiveTree(
                    type === INTERPRETED ? ORIGINAL : INTERPRETED,
                );
            }
        });
    }
    // C. Hide elements
    private hideNodesForTree(treeType: Interpreted | Original, hide: boolean) {
        const self = this;

        const tree =
            treeType === INTERPRETED ? self.interpretedTree : self.originalTree;
        tree.jstree()
            .get_json("#", {
                flat: true,
            })
            .forEach((element: TreeNode | TreeSubNode) => {
                if (element.state.disabled) {
                    hide
                        ? tree.jstree().hide_node(element, false)
                        : tree.jstree().show_node(element, false);
                }
            });
    }
    private hideEmptyTerms() {
        const self = this;
        ($("#hide_empty_terms") as JQuery<HTMLInputElement>).on(
            "change",
            function () {
                if (this.checked) {
                    localStorage.setItem(
                        "datapublicationMapHideEmptyTerms",
                        "" + this.checked,
                    );

                    //set interpreted/enriched tree
                    self.hideNodesForTree(INTERPRETED, true);

                    //set original tree
                    self.hideNodesForTree(ORIGINAL, true);

                    //TODO do we need this?
                    self.originalTree
                        .jstree()
                        .get_json("#", {
                            flat: true,
                        })
                        .forEach((element: TreeNodeWithParent) => {
                            if (
                                !element.state.disabled &&
                                "parent" in element
                            ) {
                                let parent = element.parent;

                                if (parent) {
                                    while (parent) {
                                        self.originalTree
                                            .jstree()
                                            .show_node(parent, false);
                                        parent = parent.parent;
                                    }
                                }

                                self.originalTree
                                    .jstree()
                                    .show_node(element, false);
                            }
                        });
                }
                if (!this.checked) {
                    localStorage.setItem(
                        "datapublicationMapHideEmptyTerms",
                        "" + false,
                    );

                    //set interpreted/enriched tree
                    self.hideNodesForTree(INTERPRETED, false);

                    //set original tree
                    self.hideNodesForTree(ORIGINAL, false);
                }
            },
        );
    }
    private preProcessNodes(nodes: (TreeNode | TreeSubNode)[]) {
        for (let i = nodes.length - 1; i >= 0; i--) {
            const node = nodes[i];
            assertNotUndefined(node, "Node is undefined. This is a bug.");
            node.originalText = node.text;
            if (!node["id"]) node.id = "keyword_" + node.originalText;
            if (node.children.length > 0) {
                this.preProcessNodes(node.children);
            }
        }
    }
    private processNodes(nodes: (TreeNode | TreeSubNode)[], original = false) {
        for (let i = nodes.length - 1; i >= 0; i--) {
            const node = nodes[i];
            assertNotUndefined(node, "Node is undefined. This is a bug.");
            // A. If node is part of facets, then populate it and enable it.
            const nodeInFacets = this.facets[node.extra.filterName];
            if (nodeInFacets) {
                const result = nodeInFacets.items.find((obj) => {
                    return obj.name == node.extra.filterValue;
                });
                if (result) {
                    node.state.disabled = false;
                    node.text =
                        node.originalText +
                        ' <span class="badge bg-primary text-primary-800 rounded-pill">' +
                        result.count +
                        "</span>";
                }
            }
            //B.  If current node include children nodes that can be a part of the dynamic facets,
            // then create and store them in the default data structure.
            if ("includeFacet" in node.extra) {
                const facetParentInFacets = this.facets[node.extra.facetName];
                if (facetParentInFacets) {
                    for (const [x, facetItem] of Object.entries(
                        facetParentInFacets.items,
                    )) {
                        const filterName = node.extra.facetName;
                        const filterValue = facetItem.name;

                        const newNode: TreeSubNode = createSubNode({
                            filterName,
                            filterValue,
                            facetItem,
                            facetNumber: x,
                        });
                        node.children.push(newNode);
                    }
                }
            }
            // Do the same procedure for the children of the nodes, if they exist.
            if (node.children.length > 0) {
                this.processNodes(node.children, original);
            }
        }
    }
    private setActiveTree(type: Interpreted | Original) {
        if (type === ORIGINAL) {
            this.originalToggle.prop("checked", "checked");
            this.interpretedToggle.prop("checked", false);
            this.interpretedTree.hide();
            this.originalTree.show();
            return;
        }

        this.originalToggle.prop("checked", false);
        this.interpretedToggle.prop("checked", "checked");
        this.interpretedTree.show();
        this.originalTree.hide();
    }
}

function createTreeOptions(data: (TreeNode | TreeSubNode)[]) {
    return {
        core: {
            data,
            //Allows renaming of nodes
            check_callback: true,
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

function createSubNode({
    facetItem,
    facetNumber,
    filterName,
    filterValue,
}: {
    facetItem: FacetItem;
    facetNumber: string;
    filterName: string;
    filterValue: string;
}): TreeSubNode {
    return {
        text: facetItem.display_name,
        originalText: facetItem.display_name,
        id: filterName + "-" + facetNumber,
        state: {
            opened: false,
            disabled: false,
            selected: false,
            checked: false,
        },
        extra: {
            type: "filter",
            url: "",
            filterName,
            filterValue,
        },
        children: [],
    };
}
