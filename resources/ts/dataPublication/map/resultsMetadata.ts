import { assertNotNull, getElementOrThrow } from "../../helpers";
import type { Paginator } from "../utils";

export class ResultsMetadata {
    resultsMetadataElement: HTMLElement;
    totalCount: HTMLParagraphElement;
    currentCount: HTMLParagraphElement;
    constructor() {
        this.resultsMetadataElement = getElementOrThrow("results-metadata");
        this.totalCount = this.createElement("total-count");
        this.currentCount = this.createElement("current-count");
        this.setDefaultState();
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
        this.setDefaultState();
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
    private setDefaultState() {
        this.totalCount.textContent = "";
        this.currentCount.textContent = "";
    }
}
