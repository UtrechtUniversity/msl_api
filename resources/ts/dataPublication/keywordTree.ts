import { assertNotUndefined } from "../helpers";
import "jstree";
import { throwWhenCallBackNotInitialized } from "./utils";

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
    private activeFilters: { [key: string]: string[] } = {};
    private activeNodes: Array<string> = [];
    private facets: { [key: string]: string[] } = {};
    private treeOptions = { interpreted: {}, original: {} };
    //TODO can it query before instantiation?
    private interpretedTree = $(TREES.interpreted.id);
    private interpretedToggle = $(TREES.interpreted.filterToggle);
    private originalTree = $(TREES.original.id);
    private originalToggle = $(TREES.original.filterToggle);
    private onKeywordFilterUpdate: (
        type: "remove" | "add",
        filter: {
            name: string;
        },
    ) => void = throwWhenCallBackNotInitialized;
    private self = this;
    constructor() {}

    public setHandlerfn({
        onKeywordFilterUpdate,
    }: {
        onKeywordFilterUpdate: (
            type: "remove" | "add",
            filter: {
                name: string;
            },
        ) => void;
    }) {
        this.onKeywordFilterUpdate = onKeywordFilterUpdate;
    }

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
        this.createTrees();
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
            .on("state_ready.jstree", () => {
                tree.on(
                    "check_node.jstree uncheck_node.jstree",
                    this.handleFilterChange(this.self),
                );
            })
            .on(
                "ready.jstree",
                (_: Event, { instance }: JsTreeCheckEventData) => {
                    this.activeNodes.forEach(instance._open_to.bind(instance));
                },
            );
    }
    private handleFilterChange(
        self: KeywordTree,
    ): (e: JQuery.Event, data: JsTreeCheckEventData) => void {
        return (e: JQuery.Event, data: JsTreeCheckEventData) => {
            if (data.node.original.extra.type == "filter") {
                if (e.type == "check_node") {
                    self.onKeywordFilterUpdate("add", {
                        name: data.node.original.extra.filterName,
                    });
                } else if (e.type == "uncheck_node") {
                    self.onKeywordFilterUpdate("remove", {
                        name: data.node.original.extra.filterName,
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
                    "interpretedFilters",
                    type === "interpreted" ? this.checked : !this.checked,
                );
                let searchParams = new URLSearchParams(window.location.search);

                if (searchParams.size > 0) {
                    redirect();
                } else {
                    self.setActiveTree(
                        type === "interpreted" ? "interpreted" : "original",
                    );
                }
            }
            if (!this.checked) {
                localStorage.setItem(
                    "interpretedFilters",
                    type === "interpreted" ? !this.checked : this.checked,
                );
                let searchParams = new URLSearchParams(window.location.search);

                if (searchParams.size > 0) {
                    redirect();
                } else {
                    self.setActiveTree(
                        type === "interpreted" ? "original" : "interpreted",
                    );
                }
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
                localStorage.setItem("hideEmptyTerms", this.checked);

                //set interpreted/enriched tree
                self.hideNodesForTree("interpreted", true);

                //set original tree
                self.hideNodesForTree("original", true);

                // TODO do we need this?
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
                localStorage.setItem("hideEmptyTerms", false);

                //set interpreted/enriched tree
                self.hideNodesForTree("interpreted", false);

                //set original tree
                self.hideNodesForTree("original", false);
            }
        });
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

function redirect() {
    const text =
        "Your currently selected filters will be removed when you switch trees.";
    if (confirm(text)) {
        window.location.href = "../data-access";
    }
}
