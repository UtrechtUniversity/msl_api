import { assertNotUndefined } from "../helpers";
import "jstree";
import {
    throwWhenCallBackNotInitialized,
    type Facets,
    type KeywordFilters,
} from "./utils";

const INTERPRETED = "interpreted" as const;
type Interpreted = typeof INTERPRETED;

const ORIGINAL = "original" as const;
type Original = typeof ORIGINAL;

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

interface JsTreeCheckEventData {
    instance: JQuery;
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
    private interpretedToggle: JQuery<HTMLElement> = $(
        TREES.interpreted.filterToggle,
    );
    private originalTree: JQuery<HTMLElement> = $(TREES.original.id);
    private originalToggle: JQuery<HTMLElement> = $(
        TREES.original.filterToggle,
    );

    private onKeywordFilterUpdate: (
        type: "remove" | "add",
        filter: {
            key: string;
            value: string;
        },
    ) => Promise<void> | void = throwWhenCallBackNotInitialized;
    private self = this;
    constructor() {}

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
                filter: function (state) {
                    delete state.checkbox;
                    return state;
                },
            },
        })
            .on("state_ready.jstree", async () => {
                tree.on(
                    "check_node.jstree uncheck_node.jstree",
                    await this.handleFilterChange(this.self),
                );
            })
            .on(
                "ready.jstree",
                (_: Event, { instance }: JsTreeCheckEventData) => {
                    this.activeNodes.forEach(instance._open_to.bind(instance));
                },
            );
    }

    private async handleFilterChange(
        self: KeywordTree,
    ): Promise<(e: JQuery.Event, data: JsTreeCheckEventData) => void> {
        return async (e: JQuery.Event, data: JsTreeCheckEventData) => {
            if (data.node.original.extra.type == "filter") {
                const key = data.node.original.extra.filterName;
                const value = data.node.original.extra.filterValue;
                if (e.type == "check_node") {
                    await self.onKeywordFilterUpdate("add", {
                        key,
                        value,
                    });
                } else if (e.type == "uncheck_node") {
                    await self.onKeywordFilterUpdate("remove", {
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

            //TODO
            if (!node) throw new Error("");
            const tree =
                treeType === ORIGINAL
                    ? this.originalTree.jstree(true)
                    : this.interpretedTree.jstree(true);

            node.state.disabled = disableByDefault;
            if (disableByDefault) {
                const nodeInTree = tree.get_node(node.id);
                tree.disable_node(nodeInTree);
            }
            if (node.originalText === "decane")
                console.log(node.state.disabled);
            //  B. An to node einai sta facets, vres to value to sto facets
            if (node.extra.filterName in this.facets) {
                const result = this.facets[node.extra.filterName]!.items!.find(
                    (obj) => {
                        return obj.name == node.extra.filterValue;
                    },
                );
                // An iparxei ontws match tou node kai twn assets, enable to node kai ftiakse span. Alliws, disable.
                if (result) {
                    tree.enable_node(node.id);

                    tree.rename_node(
                        node.id,
                        `${node.originalText} <span class="badge bg-primary text-primary-800 rounded-pill">${result.count}</span>`,
                    );
                } else {
                    if (node.originalText === "salt brine")
                        console.log(node.state.disabled, "else");
                    tree.rename_node(node.id, `${node.originalText}`);
                }
            } else {
                // In case we have no facets, we don't want to disable everything.
                // We want the user to be able to use the keywords as filters
                // tree.enable_node(node.id);
                tree.rename_node(node.id, `${node.originalText}`);
                if (node.originalText === "salt brine")
                    console.log(node.state.disabled, "elseelse");
            }

            if ("includeFacet" in node.extra) {
                //node here is the parent
                const parent = tree.get_node(node.id);
                tree.delete_node(parent.children);
                node.children = [];
                const facetInFacets = this.facets[node.extra.facetName];
                if (facetInFacets) {
                    for (let x = 0; x < facetInFacets.items!.length; x++) {
                        const newNode: TreeSubNode = {
                            text: facetInFacets.items[x].display_name,
                            originalText: facetInFacets.items[x].display_name,
                            id: node.extra.facetName + "-" + x,
                            state: {
                                opened: false,
                                disabled: true,
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
                        if (newNode.extra.filterName in activeFilters) {
                            //A. An einai to node sta active filters sta activeFilters as einai included sta active nodes.
                            if (
                                activeFilters[
                                    newNode.extra.filterName
                                ]!.includes(newNode.extra.filterValue)
                            ) {
                                newNode.state.checked = true;
                            }
                        }
                        node.children.push(newNode);

                        tree.create_node(node, newNode);
                    }
                }
            }
            if (node.originalText === "salt brine")
                console.log(node.state.disabled);
            if (node.children.length > 0) {
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
                    type === INTERPRETED ? this.checked : !this.checked,
                );

                self.setActiveTree(
                    type === INTERPRETED ? INTERPRETED : ORIGINAL,
                );
            }
            if (!this.checked) {
                localStorage.setItem(
                    IS_INTERPRETED_FILTER_ENABLED,
                    type === INTERPRETED ? !this.checked : this.checked,
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
        $("#hide_empty_terms").on("change", function () {
            if (this.checked) {
                localStorage.setItem(
                    "datapublicationMapHideEmptyTerms",
                    this.checked,
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
                    .forEach((element) => {
                        if (!element.state.disabled) {
                            var parent = element.parent;

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
                localStorage.setItem("datapublicationMapHideEmptyTerms", false);

                //set interpreted/enriched tree
                self.hideNodesForTree(INTERPRETED, false);

                //set original tree
                self.hideNodesForTree(ORIGINAL, false);
            }
        });
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

            const filterInFacets = this.facets[node.extra.filterName];
            if (filterInFacets) {
                const result = filterInFacets.items.find((obj) => {
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

            if ("includeFacet" in node.extra) {
                const facetInFacets = this.facets[node.extra.facetName];
                if (facetInFacets) {
                    for (let x = 0; x < facetInFacets.items.length; x++) {
                        const newNode: TreeSubNode = {
                            text: facetInFacets.items[x].display_name,
                            originalText: facetInFacets.items[x].display_name,
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
