// import { processNodes, type TreeNode, type TreeSubNode } from "./utils";
// import "jstree";
// const activeFilters: { [key: string]: string[] } = {};
// const activeNodes: Array<string> = [];
// const facets: { [key: string]: string[] } = {};

// const interpretedJsonResponse = await fetch("/interpreted.json");
// //TODO Throw if there is error.
// const dataInterpreted = await interpretedJsonResponse.json();

// const originalJsonResponse = await fetch("/original.json");
// //TODO Throw if there is error.
// const dataOriginal = await originalJsonResponse.json();

// processNodes(dataInterpreted);
// processNodes(dataOriginal, true);

// const TREES = {
//     interpreted: {
//         id: "#jstree-interpreted",
//         name: "jstree-interpreted",
//         filterToggle: "#filterTreeToggleInterpreted",
//     },
//     original: {
//         id: "#jstree-original",
//         name: "jstree-original",
//         filterToggle: "#filterTreeToggleOriginal",
//     },
// } as const;
// function createTreeOptions(data: (TreeNode | TreeSubNode)[]) {
//     return {
//         core: {
//             data: data,
//             check_callback: false,
//             themes: {
//                 dots: false,
//                 icons: false,
//             },
//         },
//         checkbox: {
//             three_state: false, // to avoid that fact that checking a node also check others
//             whole_node: false, // to avoid checking the box just clicking the node
//             tie_selection: false, // for checking without selecting and selecting without checking
//         },
//         plugins: ["checkbox", "search", "state"],
//         search: {
//             case_sensitive: false,
//             show_only_matches: true,
//         },
//     };
// }

// function handleFilterChange(e, data) {
//     if (data.node.original.extra.type == "filter") {
//         if (e.type == "check_node") {
//             var url = new URL(window.location.href);
//             var urlParams = new URLSearchParams(url.search);

//             if (urlParams.has("page")) {
//                 urlParams.set("page", "1");
//             }

//             urlParams.append(
//                 data.node.original.extra.filterName + "[]",
//                 data.node.original.extra.filterValue,
//             );
//             url.search = urlParams.toString();
//             window.location.href = url.toString();
//         } else if (e.type == "uncheck_node") {
//             var url = new URL(window.location.href);
//             var urlParams = new URLSearchParams(url.search);

//             urlParams.delete(
//                 data.node.original.extra.filterName + "[]",
//                 data.node.original.extra.filterValue,
//             );
//             url.search = urlParams.toString();
//             window.location.href = url.toString();
//         }
//     }
// }

// const interpretedTree = $(TREES.interpreted.id);
// const interpretedToggle = $(TREES.interpreted.filterToggle);
// const originalTree = $(TREES.original.id);
// const originalToggle = $(TREES.original.filterToggle);
// //A. Initialize
// function initTree(tree: JQuery<HTMLElement>, { name }: { name: string }) {
//     tree.jstree({
//         ...treeOptions,

//         state: {
//             key: name,
//             filter: function (state) {
//                 delete state.checkbox;
//                 return state;
//             },
//         },
//     })
//         .on("state_ready.jstree", () => {
//             tree.on(
//                 "check_node.jstree uncheck_node.jstree",
//                 handleFilterChange,
//             );
//         })
//         .on("ready.jstree", (_, { instance }) => {
//             activeNodes.forEach(instance._open_to.bind(instance));
//         });
// }

// initTree(interpretedTree, TREES.interpreted);
// initTree(originalTree, TREES.original);
// //B. Toggle between trees

// function toggleToAnotherTree(type: "interpreted" | "original") {
//     const tree = type === "interpreted" ? interpretedToggle : originalToggle;
//     tree.on("change", function () {
//         if (this.checked) {
//             localStorage.setItem(
//                 "interpretedFilters",
//                 type === "interpreted" ? this.checked : !this.checked,
//             );
//             let searchParams = new URLSearchParams(window.location.search);

//             if (searchParams.size > 0) {
//                 redirect();
//             } else {
//                 setActiveTree(
//                     type === "interpreted" ? "interpreted" : "original",
//                 );
//             }
//         }
//         if (!this.checked) {
//             localStorage.setItem(
//                 "interpretedFilters",
//                 type === "interpreted" ? !this.checked : this.checked,
//             );
//             let searchParams = new URLSearchParams(window.location.search);

//             if (searchParams.size > 0) {
//                 redirect();
//             } else {
//                 setActiveTree(
//                     type === "interpreted" ? "original" : "interpreted",
//                 );
//             }
//         }
//     });
// }
// // C. Hide elements
// function hideNodesForTree(treeType: "interpreted" | "original", hide: boolean) {
//     const tree = treeType === "interpreted" ? interpretedTree : originalTree;
//     tree.jstree()
//         .get_json("#", {
//             flat: true,
//         })
//         .forEach((element) => {
//             if (element.state.disabled) {
//                 hide
//                     ? tree.jstree().hide_node(element)
//                     : tree.jstree().show_node(element);
//             }
//         });
// }
// function hideEmptyTerms() {
//     $("#hide_empty_terms").on("change", function () {
//         if (this.checked) {
//             localStorage.setItem("hideEmptyTerms", this.checked);

//             //set interpreted/enriched tree
//             hideNodesForTree("interpreted", true);

//             //set original tree
//             hideNodesForTree("original", true);

//             // TODO do we need this?
//             originalTree
//                 .jstree()
//                 .get_json("#", {
//                     flat: true,
//                 })
//                 .forEach((element) => {
//                     if (!element.state.disabled) {
//                         var parent = element.parent;

//                         if (parent) {
//                             while (parent) {
//                                 originalTree.jstree().show_node(parent);
//                                 parent = parent.parent;
//                             }
//                         }

//                         $("#jstree-original").jstree().show_node(element);
//                     }
//                 });
//         }
//         if (!this.checked) {
//             localStorage.setItem("hideEmptyTerms", false);

//             //set interpreted/enriched tree
//             hideNodesForTree("interpreted", false);

//             //set original tree
//             hideNodesForTree("original", false);
//         }
//     });
// }

// // Jqueries when document is ready
// $(function () {
//     const interpretedInStorage = localStorage.getItem("interpretedFilters");
//     if (interpretedInStorage !== null) {
//         setActiveTree(
//             interpretedInStorage === "false" ? "original" : "interpreted",
//         );
//     }

//     $("#search-filters").keyup(function () {
//         const searchString = $(this).val();
//         interpretedToggle.is(":checked")
//             ? interpretedTree.jstree("search", searchString)
//             : originalTree.jstree("search", searchString);
//     });

//     toggleToAnotherTree("interpreted");
//     toggleToAnotherTree("original");

//     $("#expand_all").on("click", function () {
//         interpretedToggle.is(":checked")
//             ? interpretedTree.jstree("open_all")
//             : originalTree.jstree("open_all");
//     });

//     $("#close_all").on("click", function () {
//         interpretedToggle.is(":checked")
//             ? interpretedTree.jstree("close_all")
//             : originalTree.jstree("close_all");
//     });

//     hideEmptyTerms();
// });

// function redirect() {
//     const text =
//         "Your currently selected filters will be removed when you switch trees.";
//     if (confirm(text)) {
//         window.location.href = "../data-access";
//     }
// }

// function setActiveTree(type: "interpreted" | "original") {
//     if (type === "original") {
//         originalToggle.prop("checked", "checked");
//         interpretedToggle.prop("checked", false);
//         interpretedTree.hide();
//         originalTree.show();
//         return;
//     }

//     originalToggle.prop("checked", false);
//     interpretedToggle.prop("checked", "checked");
//     interpretedTree.show();
//     originalTree.hide();
// }
