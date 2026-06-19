import { assertNotNull } from "../helpers";
import {
    LEFT_ARROW_ICON,
    RIGHT_ARROW_ICON,
    throwWhenCallBackNotInitialized,
    type Paginator,
} from "./utils";

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
    public onPageChange: (page: number) => void =
        throwWhenCallBackNotInitialized;
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

    public setHandlerfn({
        onPageChange,
    }: {
        onPageChange: (page: number) => void;
    }): void {
        this.onPageChange = onPageChange;
    }

    public populate() {
        assertNotNull(this.range, `Range should not be null. This is a bug.`);

        this.setButton(
            "dp-pagination-button dp-pagination-button-last-left",
            LEFT_ARROW_ICON,
            { toPage: this.range.currentPage - 1 },
        );

        // if total count is less than the given range
        // plus the last and first page
        // then just display all
        if (this.range.count <= this.range.rangeShown + 2) {
            for (let i = 1; i < this.range.count; i++) {
                if (i === this.range.currentPage) {
                    this.setButton(
                        "dp-pagination-button dp-pagination-button-active-page",
                        i + "",
                        { toPage: i },
                    );
                } else {
                    this.setButton("dp-pagination-button", i + "", {
                        toPage: i,
                    });
                }
            }
            // Then we don't want to display all
        } else {
            if (this.range.currentPage === 1) {
                const button = this.setButton(
                    "dp-pagination-button dp-pagination-button-active-page",
                    "1",
                    { toPage: 1 },
                );
            } else {
                this.setButton("dp-pagination-button", "1", { toPage: 1 });
            }

            //    if the range is close the first page dont show "..." otherwise show

            if (
                this.range.currentPage - this.range.lowerRange <
                this.range.lowerRange
            ) {
                this.setButton(
                    "dp-pagination-button btn-disabled !bg-primary-200",
                    "...",
                    { toPage: undefined },
                );
            }

            // show the range

            for (
                let i = this.range.lowerRange;
                i < this.range.upperRange + 1;
                i++
            ) {
                //      if the count is not equal or over or under the first and last page then show
                // (because we substract and add to a number over/undercount will be the case)

                if (!(i <= 1) && !(i >= this.range.count)) {
                    if (i == this.range.currentPage) {
                        this.setButton(
                            "dp-pagination-button dp-pagination-button-active-page",
                            i + "",
                            { toPage: i },
                        );
                    } else {
                        this.setButton("dp-pagination-button", i + "", {
                            toPage: i,
                        });
                    }
                }
            }
            // if the range is close to the count dont show the "..." otherwise show
            // 14+2 < 18-2
            if (
                this.range.currentPage + this.range.rangeUnilateral <=
                this.range.count - this.range.rangeUnilateral
            ) {
                this.setButton(
                    "dp-pagination-button btn-disabled !bg-primary-200",
                    "...",
                    { toPage: undefined },
                );
            }

            if (this.range.count == this.range.currentPage) {
                this.setButton(
                    "dp-pagination-button dp-pagination-button-active-page",
                    this.range.count + "",
                    {
                        toPage: this.range.count,
                    },
                );
            } else {
                this.setButton("dp-pagination-button", this.range.count + "", {
                    toPage: this.range.count,
                });
            }
        }
        this.setButton(
            "dp-pagination-button dp-pagination-button-last-right",
            RIGHT_ARROW_ICON,
            { toPage: this.range.currentPage + 1 },
        );
    }

    private setButton(
        classAttribute: string,
        text: string,
        { toPage }: { toPage: number | undefined },
    ): HTMLButtonElement {
        assertNotNull(this.range, `Range should not be null. This is a bug.`);
        const a = document.createElement("a");
        const button = document.createElement("button");
        button.setAttribute("class", classAttribute);
        button.innerHTML = text;
        if (toPage)
            button.addEventListener("click", () => {
                this.onPageChange(toPage);
            });
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
        const rangeUnilateral = 1;

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
