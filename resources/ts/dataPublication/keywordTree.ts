import { assertNotUndefined } from "../helpers";
import "jstree";
import { throwWhenCallBackNotInitialized, type Facets } from "./utils";

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
        type: "interpreted",
    },
    original: {
        id: "#jstree-original",
        name: "jstree-original",
        filterToggle: "#filterTreeToggleOriginal",
        type: "original",
    },
} as const;

export class KeywordTree {
    // TODO do I need this?
    private activeFilters: { [key: string]: string[] } = {};
    private activeNodes: Array<string> = [];
    private facets: Facets = {};
    private treeOptions = { interpreted: {}, original: {} };
    private dataInterpreted: (TreeNode | TreeSubNode)[] = [];
    private dataOriginal: (TreeNode | TreeSubNode)[] = [];
    private interpretedTree = $(TREES.interpreted.id);
    private interpretedToggle = $(TREES.interpreted.filterToggle);
    private originalTree = $(TREES.original.id);
    private originalToggle = $(TREES.original.filterToggle);
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
    public recreateFacets(facets: Facets) {
        this.facets = facets;
        this.createFacets(this.dataOriginal, "original");
        this.createFacets(this.dataInterpreted, "interpreted");
    }
    private createFacets(
        nodes: (TreeNode | TreeSubNode)[],
        treeType: "original" | "interpreted",
    ) {
        for (let i = nodes.length - 1; i >= 0; i--) {
            const node = nodes[i];
            //TODO
            if (!node) throw new Error("");
            const tree =
                treeType === "original"
                    ? this.originalTree.jstree(true)
                    : this.interpretedTree.jstree(true);

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
                    tree.disable_node(node.id);
                    tree.rename_node(node.id, `${node.originalText}`);
                }
            } else {
                // In case we have no facets, we don't want to disable everything.
                // We want the user to be able to use the keywords as filters
                tree.enable_node(node.id);
                tree.rename_node(node.id, `${node.originalText}`);
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
                        if (newNode.extra.filterName in this.activeFilters) {
                            //A. An einai to node sta active filters sta activeFilters as einai included sta active nodes.
                            if (
                                this.activeFilters[
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
            if (node.children.length > 0) {
                this.createFacets(node.children, treeType);
            }
        }
    }

    private createTrees() {
        this.initTree(this.interpretedTree, TREES.interpreted);
        this.initTree(this.originalTree, TREES.original);
        const self = this;
        // Jqueries when document is ready
        $(function () {
            const interpretedInStorage =
                localStorage.getItem("interpretedFilters");
            if (interpretedInStorage !== null) {
                self.setActiveTree(
                    interpretedInStorage === "false"
                        ? "original"
                        : "interpreted",
                );
            }

            $("#search-filters").keyup(function () {
                const searchString = $(this).val();
                self.interpretedToggle.is(":checked")
                    ? self.interpretedTree.jstree("search", searchString)
                    : self.originalTree.jstree("search", searchString);
            });

            self.toggleToAnotherTree("interpreted");
            self.toggleToAnotherTree("original");

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
        { name, type }: { name: string; type: "original" | "interpreted" },
    ) {
        const options =
            type === "interpreted"
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
                    //TODO this should be moved to mapController probably
                    this.activeFilters[key] = [
                        ...(this.activeFilters[key] ?? []),
                        value,
                    ];
                    await self.onKeywordFilterUpdate("add", {
                        key,
                        value,
                    });
                } else if (e.type == "uncheck_node") {
                    //TODO this should be moved to mapController probably
                    delete this.activeFilters[key];
                    await self.onKeywordFilterUpdate("remove", {
                        key,
                        value,
                    });
                }
            }
        };
    }

    //B. Toggle between trees

    private toggleToAnotherTree(type: "interpreted" | "original") {
        const self = this;
        const tree =
            type === "interpreted"
                ? self.interpretedToggle
                : self.originalToggle;
        tree.on("change", function () {
            if (this.checked) {
                localStorage.setItem(
                    "datapublicationMapInterpretedFilters",
                    type === "interpreted" ? this.checked : !this.checked,
                );

                self.setActiveTree(
                    type === "interpreted" ? "interpreted" : "original",
                );
            }
            if (!this.checked) {
                localStorage.setItem(
                    "datapublicationMapInterpretedFilters",
                    type === "interpreted" ? !this.checked : this.checked,
                );

                self.setActiveTree(
                    type === "interpreted" ? "original" : "interpreted",
                );
            }
        });
    }
    // C. Hide elements
    private hideNodesForTree(
        treeType: "interpreted" | "original",
        hide: boolean,
    ) {
        const self = this;

        const tree =
            treeType === "interpreted"
                ? self.interpretedTree
                : self.originalTree;
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
                self.hideNodesForTree("interpreted", true);

                //set original tree
                self.hideNodesForTree("original", true);

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
                self.hideNodesForTree("interpreted", false);

                //set original tree
                self.hideNodesForTree("original", false);
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
            //TODO remove
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
                            node.originalText +
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
    private setActiveTree(type: "interpreted" | "original") {
        if (type === "original") {
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
