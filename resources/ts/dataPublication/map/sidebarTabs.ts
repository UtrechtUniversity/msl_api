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
                tabElement: getElementByQueryOrThrow(
                    '#sidebar-tabs [data-tab="Results"]',
                ),
                contentElement: getElementByQueryOrThrow(
                    ' #sidebar-content [data-content="Results"]',
                ),
            },
            [KEYWORDS]: {
                tabElement: getElementByQueryOrThrow(
                    '#sidebar-tabs [data-tab="Keywords"]',
                ),
                contentElement: getElementByQueryOrThrow(
                    ' #sidebar-content [data-content="Keywords"]',
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
    public activateSidebarTab(tabName: SidebarTabName) {
        showSidebarTabInfo(this.sidebarInfoPerTab[RESULTS], {
            show: tabName === RESULTS,
        });
        showSidebarTabInfo(this.sidebarInfoPerTab[KEYWORDS], {
            show: tabName === KEYWORDS,
        });
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
