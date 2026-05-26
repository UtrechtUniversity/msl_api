import type { Sidebar } from "../types/sidebar";
import { DataPublicationMap } from "./map";
import { sideBar } from "./sidebar";



class DataPublicationMapAccess {
    map: DataPublicationMap;
    resultsSidebar: Sidebar;
    constructor() {
        this.map = new DataPublicationMap();
        this.resultsSidebar = new sideBar();
    }

}