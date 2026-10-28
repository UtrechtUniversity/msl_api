import { getElementByQueryOrThrow } from "../../assertions";
import type { Entries } from "../../types/entries";
import { KEYWORDS, RESULTS, type SidebarTabName } from "./config";

type SidebarTabInfo = {
    tabElement: HTMLElement;
    contentElement: HTMLElement;
};
interface SidebarInfoPerTab {
    [RESULTS]: SidebarTabInfo;
    [KEYWORDS]: SidebarTabInfo;
}
export class SidebarTabs {
    private sidebarInfoPerTab: SidebarInfoPerTab;

    constructor() {
        this.sidebarInfoPerTab = {
            [RESULTS]: {
                tabElement: getElementByQueryOrThrow(getTabQuery(RESULTS)),
                contentElement: getElementByQueryOrThrow(
                    getContentQuery(RESULTS),
                ),
            },
            [KEYWORDS]: {
                tabElement: getElementByQueryOrThrow(getTabQuery(KEYWORDS)),
                contentElement: getElementByQueryOrThrow(
                    getContentQuery(KEYWORDS),
                ),
            },
        };
        this.attachClickListeners();
    }

    private attachClickListeners() {
        for (const [tabName, tabInfo] of Object.entries(
            this.sidebarInfoPerTab,
        ) as Entries<SidebarInfoPerTab>) {
            tabInfo.tabElement.addEventListener("click", () => {
                this.activateSidebarTab(tabName);
            });
        }
    }
    public activateSidebarTab(tabNameToActivate: SidebarTabName) {
        for (const [tabName, tabInfo] of Object.entries(
            this.sidebarInfoPerTab,
        ) as Entries<SidebarInfoPerTab>) {
            showSidebarTabInfo(tabInfo, {
                show: tabNameToActivate === tabName,
            });
        }
        return;
    }
}

function showSidebarTabInfo(
    sidebarTabInfo: SidebarTabInfo,
    { show }: { show: true | false },
) {
    show
        ? sidebarTabInfo.tabElement.classList.add("tab-active")
        : sidebarTabInfo.tabElement.classList.remove("tab-active");

    sidebarTabInfo.contentElement.hidden = !show;
    return;
}

function getTabQuery(tabName: SidebarTabName) {
    return `#sidebar-tabs [data-tab="${tabName}"]`;
}

function getContentQuery(tabName: SidebarTabName) {
    return ` #sidebar-content [data-content="${tabName}"]`;
}
