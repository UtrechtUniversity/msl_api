import { assertNotNull } from "../helpers";
import { LEFT_ARROW_ICON, RIGHT_ARROW_ICON, type Paginator } from "./utils";

export class Pagination {
    paginateElement: HTMLElement;
    paginator: Paginator | null = null;
    range: {
        rangeUnilateral: number;
        lowerRange: number;
        upperRange: number;
        rangeShown: number;
        count: number;
        currentPage: number;
    } | null = null;
    constructor() {
        const paginateElement = document.getElementById("results-pagination");
        assertNotNull(
            paginateElement,
            `There is not element for pagination of results. This is a bug.`,
        );
        this.paginateElement = paginateElement;
    }

    public setArgs(paginator: Paginator) {
        this.paginator = paginator;
        this.getRange();
    }

    populate() {
        assertNotNull(this.range, `Range should not be null. This is a bug.`);

        this.setButton(
            "pagination-button pagination-button-last-left",
            LEFT_ARROW_ICON,
        );
        if (this.range.count <= this.range.rangeShown + 2) {
            for (let i = 1; i < this.range.count; i++) {
                if (i === this.range.currentPage) {
                    const button = this.setButton(
                        "pagination-button pagination-button-active-page",
                        i + "",
                    );
                } else {
                    const button = this.setButton("pagination-button", i + "");
                }
            }
        } else {
            if (this.range.currentPage === 1) {
                const button = this.setButton(
                    "pagination-button pagination-button-active-page",
                    "1",
                );
            } else {
                this.setButton("pagination-button", "1");
            }
            if (
                this.range.currentPage - this.range.lowerRange <
                this.range.lowerRange
            ) {
                this.setButton(
                    "pagination-button btn-disabled !bg-primary-200",
                    "...",
                );
            }
            for (
                let i = this.range.lowerRange;
                i < this.range.upperRange + 1;
                i++
            ) {
                if (!(i <= 1) && !(i >= this.range.count)) {
                    if (i == this.range.currentPage) {
                        this.setButton(
                            "pagination-button pagination-button-active-page",
                            i + "",
                        );
                    } else {
                        this.setButton("pagination-button", i + "");
                    }
                }
            }
            // if the range is close to the count dont show the "..." otherwise show --}}
            if (
                this.range.currentPage + this.range.rangeUnilateral <=
                this.range.count - this.range.rangeUnilateral
            ) {
                this.setButton(
                    "pagination-button btn-disabled !bg-primary-200",
                    "...",
                );
            }

            if (this.range.count == this.range.currentPage) {
                this.setButton(
                    "pagination-button pagination-button-active-page",
                    this.range.count + "",
                );
            } else {
                this.setButton("pagination-button", this.range.count + "");
            }
        }
        this.setButton(
            "pagination-button pagination-button-last-right",
            RIGHT_ARROW_ICON,
        );
    }

    private setButton(classAttribute: string, text: string): HTMLButtonElement {
        const a = document.createElement("a");
        const button = document.createElement("button");
        button.setAttribute("class", classAttribute);
        button.innerHTML = text;
        a.appendChild(button);
        this.paginateElement.append(a);
        return button;
    }
    public clear() {
        //TODO clean up range and paginator?
        this.paginator = null;
        this.range = null;
        while (this.paginateElement.firstChild) {
            this.paginateElement.firstChild.remove();
        }
    }
    private getRange() {
        assertNotNull(
            this.paginator,
            `Paginator arguments should have been populated. This is a bug.`,
        );
        const rangeUnilateral = 2;

        this.range = {
            rangeUnilateral: rangeUnilateral,
            lowerRange: this.paginator.currentPage - rangeUnilateral,
            upperRange: this.paginator.currentPage + rangeUnilateral,
            rangeShown: rangeUnilateral * 2 + 1,
            count: this.paginator.lastPage,
            currentPage: this.paginator.currentPage,
        };
    }
}
