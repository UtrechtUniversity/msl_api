document.addEventListener("DOMContentLoaded", function () {
    const component = document.getElementById("sidebar-tabs");
    if (!component)
        throw new Error(
            "Component 'sidebar-tabs' does not exist. This is a bug.",
        );
    const tabs = component.querySelectorAll<HTMLElement>("[data-tab]");
    const contents = component.querySelectorAll<HTMLElement>("[data-content]");

    tabs.forEach((tab) => {
        tab.addEventListener("click", () => {
            tabs.forEach((t) => t.classList.remove("tab-active"));
            tab.classList.add("tab-active");
            for (const content of contents) {
                content.hidden = !(tab.dataset.tab === content.dataset.content);
            }
        });
    });
});
