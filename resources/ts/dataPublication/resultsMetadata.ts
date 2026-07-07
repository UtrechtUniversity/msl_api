import { assertNotNull } from "../helpers";
import type { Paginator } from "./utils";

export class ResultsMetadata {
    resultsMetadataElement: HTMLElement;
    totalCount: HTMLParagraphElement;
    currentCount: HTMLParagraphElement;
    constructor() {
        const overArchingElement = document.getElementById("results-metadata");
        assertNotNull(
            overArchingElement,
            `Element about results metadata was not found. This is a bug`,
        );
        this.resultsMetadataElement = overArchingElement;
        this.totalCount = this.createElement("total-count");
        this.currentCount = this.createElement("current-count");
    }

    private createElement(name: string): HTMLParagraphElement {
        const metadataElement = document.createElement("p");
        metadataElement.className = "metadata-text";
        const divElement = document.createElement("div");
        divElement.id = name;
        divElement.appendChild(metadataElement);
        this.resultsMetadataElement.append(divElement);
        return metadataElement;
    }
    public updateMetadata(paginator: Paginator) {
        if (paginator.totalCount === 0) {
            this.totalCount.textContent = this.createValueInTotalCount(0);
            this.currentCount.textContent = "";
            return;
        }

        this.totalCount.textContent = this.createValueInTotalCount(
            paginator.totalCount,
        );

        this.currentCount.textContent = this.createValueInCurrentCount({
            min: (paginator.currentPage - 1) * paginator.perPage + 1,
            max:
                (paginator.currentPage - 1) * paginator.perPage +
                paginator.resultsCount,
        });
    }
    public removeMetadata() {
        this.totalCount.textContent = "";
        this.currentCount.textContent = "";
    }
    private createValueInTotalCount(value: number) {
        return `Data publications found: ${value}`;
    }
    private createValueInCurrentCount({
        min,
        max,
    }: {
        min: number;
        max: number;
    }) {
        return `Currently displayed: ${min} - ${max}`;
    }
}
