import { assertNotUndefined } from "../helpers";
import "jstree";
import {
    getIdForTreeKeyword,
    throwWhenCallBackNotInitialized,
    type FacetItem,
    type Facets,
    type TreeKeywordAddInfo,
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
    "dataPublicationMapInterpretedFilters" as const;
// @Decision:
// The state of tree, interpreted or original, is not of relevant in MapController.
// The filtering works the same for any keyword, since original is a subtree of interpreted.
// Also, the main tree is the interpreted one,
// and we might want to get rid of the original one sooner or later.
export class KeywordTree {
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
    private hideEmptyTermsToggle: JQuery<HTMLInputElement> =
        $("#hide_empty_terms");
    private suppressChangeEvents: boolean = false;

    private onKeywordFilterAdd: (
        filter: TreeKeywordAddInfo,
    ) => Promise<void> | void = throwWhenCallBackNotInitialized;
    private onKeywordFilterRemove: (opts: {
        id: string;
    }) => Promise<void> | void = throwWhenCallBackNotInitialized;
    public setHandlerfn({
        onTreeKeywordFilterAdd: onKeywordFilterAdd,
        onTreeKeywordFilterRemove: onKeywordFilterRemove,
    }: {
        onTreeKeywordFilterAdd: (opts: TreeKeywordAddInfo) => Promise<void>;
        onTreeKeywordFilterRemove: (opts: { id: string }) => Promise<void>;
    }) {
        this.onKeywordFilterAdd = onKeywordFilterAdd;
        this.onKeywordFilterRemove = onKeywordFilterRemove;
    }

    public async init(facets: Facets) {
        this.facets = facets;

        this.dataInterpreted = await getJson("interpreted");
        this.processNodes(this.dataInterpreted);

        this.dataOriginal = await getJson("original");
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

            self.setActiveTree(
                interpretedInStorage === "false"
                    ? ORIGINAL
                    : // if the value is "true" or null
                      INTERPRETED,
            );
            $("#search-filters").on("keyup", function () {
                const searchString = $(this).val();
                self.interpretedToggle.is(":checked")
                    ? self.interpretedTree.jstree("search", searchString)
                    : self.originalTree.jstree("search", searchString);
            });

            self.attachToggleToAnotherTreeListener(INTERPRETED);
            self.attachToggleToAnotherTreeListener(ORIGINAL);

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

            self.attachHideEmptyTermsListener();
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
                key: "map-view-" + name,
                // We use this function as filter,
                // so that when we reload the page,
                // the checks are removed as default state
                filter: function (state: JSTreeStaticDefaults) {
                    state = omit(state, "checkbox");
                    return state;
                },
            },
        }).on("state_ready.jstree", async () => {
            tree.on(
                "check_node.jstree uncheck_node.jstree",
                await this.handleFilterChange(),
            );
        });
    }

    private async handleFilterChange(): Promise<
        (e: JQuery.Event, data: JsTreeCheckEventData) => void
    > {
        return async (e: JQuery.Event, data: JsTreeCheckEventData) => {
            if (this.suppressChangeEvents) return;
            if (data.node.original.extra.type == "filter") {
                const name = data.node.original.extra.filterName;
                const value = data.node.original.extra.filterValue;
                const displayName = data.node.original.originalText;
                if (e.type == "check_node") {
                    await this.onKeywordFilterAdd({
                        name,
                        value,
                        displayName,
                    });
                } else if (e.type == "uncheck_node") {
                    await this.onKeywordFilterRemove({
                        id: getIdForTreeKeyword({ name, value }),
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
            // We want to have checked the active filters and unchecked all the rest of the nodes.
            const activeFilterNode = activeFilters[node.extra.filterName];
            // We are now removing keywords from outside the current element, too.
            // For this reason, we want to update checked/unchecked nodes independently of jstree change events.
            // Unfortunately, jstree doesn't have an option to check/uncheck node without firing the relevant event.
            // For this reason, we have to use a custom flag.
            this.suppressChangeEvents = true;

            if (!activeFilterNode) tree.uncheck_node(node.id, "");

            if (activeFilterNode) {
                activeFilterNode.includes(node.extra.filterValue)
                    ? tree.check_node(node.id, "")
                    : tree.uncheck_node(node.id, "");
            }

            this.suppressChangeEvents = false;

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
    private attachToggleToAnotherTreeListener(type: Interpreted | Original) {
        const self = this;
        const toggle =
            type === INTERPRETED ? self.interpretedToggle : self.originalToggle;
        toggle.on("change", function () {
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
    private hideEmptyTerms(treeType: Interpreted | Original) {
        const self = this;
        const tree =
            treeType === INTERPRETED ? self.interpretedTree : self.originalTree;

        tree.jstree()
            .get_json("#", {
                flat: true,
            })
            .forEach((element: TreeNode | TreeSubNode) => {
                if (element.state.disabled) {
                    tree.jstree().hide_node(element, false);
                }
            });

        if (treeType === ORIGINAL) {
            tree.jstree()
                .get_json("#", {
                    flat: true,
                })
                .forEach((element: TreeNodeWithParent) => {
                    if (!element.state.disabled && "parent" in element) {
                        let parent = element.parent;

                        if (parent) {
                            while (parent) {
                                this.originalTree
                                    .jstree()
                                    .show_node(parent, false);
                                parent = parent.parent;
                            }
                        }

                        this.originalTree.jstree().show_node(element, false);
                    }
                });
        }
    }
    private unhideEmptyTerms(treeType: Interpreted | Original) {
        const self = this;
        const tree =
            treeType === INTERPRETED ? self.interpretedTree : self.originalTree;

        tree.jstree()
            .get_json("#", {
                flat: true,
            })
            .forEach((element: TreeNode | TreeSubNode) => {
                if (element.state.disabled) {
                    tree.jstree().show_node(element, false);
                }
            });
    }
    private hideEmptyTermsInTrees() {
        this.hideEmptyTerms(INTERPRETED);
        this.hideEmptyTerms(ORIGINAL);
    }

    private unhideEmptyTermsInTrees() {
        this.unhideEmptyTerms(INTERPRETED);
        this.unhideEmptyTerms(ORIGINAL);
    }
    private attachHideEmptyTermsListener() {
        const self = this;
        self.hideEmptyTermsToggle.on("click", function () {
            if (this.checked) {
                self.hideEmptyTermsInTrees();
                return;
            }

            self.unhideEmptyTermsInTrees();
        });
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
            this.originalToggle.prop("checked", true);
            this.interpretedToggle.prop("checked", false);
            this.interpretedTree.hide();
            this.originalTree.show();
            return;
        }

        this.originalToggle.prop("checked", false);
        this.interpretedToggle.prop("checked", true);
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

function throwForReadingJson(response: Response, name: string): void {
    if (!response.ok) throw new Error(`Could not read ${name} json file .`);
}
async function getJson(name: "interpreted" | "original") {
    const jsonResponse = await fetch(`/${name}.json`);
    throwForReadingJson(jsonResponse, name);
    return jsonResponse.json();
}
