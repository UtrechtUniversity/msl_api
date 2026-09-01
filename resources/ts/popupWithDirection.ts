import { DomEvent, DomUtil, Point, Popup } from "leaflet";

// Inspired by : https://github.com/erictheise/rrose/blob/master/leaflet.rrose-src.js

/*
  Copyright (c) 2012 Eric S. Theise
  
  Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated 
  documentation files (the "Software"), to deal in the Software without restriction, including without limitation the 
  rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit 
  persons to whom the Software is furnished to do so, subject to the following conditions:
  
  The above copyright notice and this permission notice shall be included in all copies or substantial portions of the 
  Software.
  
  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE 
  WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR 
  COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR 
  OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
export const PopupWithDirection: typeof Popup = Popup.extend({
    _containerPrefix: "leaflet-popupWithDirection",
    options: {
        // We want this number as a default for the popup to work nicely
        // We can always change this number during instantiation
        offset: new Point(0, -10),
    },
    _initLayout: function () {
        this._container = DomUtil.create(
            "div",
            this._containerPrefix +
                " " +
                this.options.className +
                " leaflet-zoom-animated",
        );
        this._setPositionInOptions();
        const contentWrapperClasses =
            this._createClassName("-content-wrapper") +
            " " +
            this._createClassName("-content-wrapper", { position: true });

        const tipContainerClasses =
            this._createClassName("-tip-container") +
            " " +
            this._createClassName("-tip-container", { position: true });

        const tipClasses =
            this._createClassName("-tip") +
            " " +
            this._createClassName("-tip", { position: true });
        //The order of instantiation plays a role in how the pop up will look like.
        if (this.options.position.startsWith("s")) {
            this._tipContainer = DomUtil.create(
                "div",
                tipContainerClasses,
                this._container,
            );
            this._wrapper = DomUtil.create(
                "div",
                contentWrapperClasses,
                this._container,
            );
        } else {
            this._wrapper = DomUtil.create(
                "div",
                contentWrapperClasses,
                this._container,
            );
            this._tipContainer = DomUtil.create(
                "div",
                tipContainerClasses,
                this._container,
            );
        }
        this._contentNode = DomUtil.create(
            "div",
            this._createClassName("-content"),
            this._wrapper,
        );
        this._tip = DomUtil.create("div", tipClasses, this._tipContainer);

        DomEvent.disableClickPropagation(this._wrapper);
        DomEvent.disableScrollPropagation(this._container);
        this._setCloseButton();
    },

    _updatePosition: function () {
        const pos = this._map.latLngToLayerPoint(this._latlng);
        const offset = this.options.offset;

        DomUtil.setPosition(this._container, pos);

        this._containerBottom = this.options.position.startsWith("s")
            ? -this._container.offsetHeight + offset.y
            : -offset.y;

        this._containerLeft = this.options.position.endsWith("e")
            ? offset.x
            : -Math.round(this._containerWidth) + offset.x;

        this._container.style.bottom = this._containerBottom + "px";
        this._container.style.left = this._containerLeft + "px";
    },

    _setPositionInOptions() {
        const centerOfView = (this._map as L.Map).getCenter();
        const y_diff = this._latlng.lat - centerOfView.lat;
        this.options.position = y_diff > 0 ? "s" : "n";

        var x_diff = this._latlng.lng - centerOfView.lng;
        this.options.position += x_diff > 0 ? "w" : "e";
    },
    _setCloseButton() {
        if (this.options.closeButton) {
            let closeButtonClass = this._createClassName("-close-button");

            if (this.options.position.startsWith("s")) {
                closeButtonClass +=
                    " " + this._createClassName("-close-button-s");
            }
            this._closeButton = DomUtil.create(
                "a",
                closeButtonClass,
                this._container,
            );
            this._closeButton.href = "#close";
            this._closeButton.innerHTML = "&#215;";
        }
    },
    _createClassName(
        addition: string,
        { position }: { position: true | false } = { position: false },
    ) {
        return (
            this._containerPrefix +
            addition +
            (position ? "-" + this.options.position : "")
        );
    },
});
