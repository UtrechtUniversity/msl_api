$(document).ready(function() {

    $('[data-toggle=domain-highlight]').hover(
        function() {
            $("div[data-associated-subdomains*='\"" + this.dataset.domain + "\"']").addClass("word-card-highlighted");
        }, function() {
            $("div[data-associated-subdomains*='\"" + this.dataset.domain + "\"']").removeClass("word-card-highlighted");
        }
    )

    $('[data-highlight=tag]').hover(
        function() {
            if(this.dataset.uris !== undefined) {
                let matchedUris = JSON.parse(this.dataset.uris);
                if(Array.isArray(matchedUris)) {
                    matchedUris.forEach((uri) => {
                        $("div[data-uri=\"" + uri + "\"]").addClass("word-card-highlighted");
                    });
                }
            }
        }, function() {
            if(this.dataset.uris !== undefined) {
                let matchedUris = JSON.parse(this.dataset.uris);
                if(Array.isArray(matchedUris)) {
                    matchedUris.forEach((uri) => {
                        $("div[data-uri=\"" + uri + "\"]").removeClass("word-card-highlighted");
                    });
                }
            }
        }
    )

    $('[data-highlight=text-keyword]').hover(
        function() {
            let tagsMatched = false;
            let originalKeywordsMatched = false;
            let tags;

            tags = document.querySelectorAll('[data-highlight="tag"]');
            tags.forEach((tag) => {
                let tagData = JSON.parse(tag.dataset.uris);
                tagData.forEach((uri) => {
                    if(uri == this.dataset.uri) {
                        tag.classList.add('word-card-highlighted');
                        tagsMatched = true;
                    }
                });
            });

            $("span[data-uris*=\"" + this.dataset.uri + "\"]").addClass("word-card-highlighted");

            $("div[data-uri=\"" + this.dataset.uri + "\"]").addClass("word-card-highlighted");
            if($("#corresponding-keywords-panel div[data-uri=\"" + this.dataset.uri + "\"]").length > 0) {
                originalKeywordsMatched = true;
            }

            if(this.dataset.matchedChildUris !== undefined) {
                let matchedChildUris = JSON.parse(this.dataset.matchedChildUris);

                if(Array.isArray(matchedChildUris)) {
                    matchedChildUris.forEach((childUri) => {
                        $("div[data-uri=\"" + childUri + "\"]").addClass("word-card-highlighted");
                        if(!originalKeywordsMatched) {
                            if($("#corresponding-keywords-panel div[data-uri=\"" + childUri + "\"]").length > 0) {
                                originalKeywordsMatched = true;
                            }
                        }

                        $("div[data-uris*='\"" + childUri + "\"']").addClass("word-card-highlighted");
                        if(!tagsMatched) {
                            if($("div[data-uris*='\"" + childUri + "\"']").length > 0) {
                                tagsMatched = true;
                            }
                        }

                        $("span[data-uris*='\"" + childUri + "\"']").addClass("word-card-highlighted");
                    });
                }
            }

            if(tagsMatched) {
                if($('#original-keywords-panel').attr('open') !== 'open') {
                    $('#original-keywords-panel').addClass("word-card-highlighted");
                }
            }

            if(originalKeywordsMatched) {
                if($('#corresponding-keywords-panel').attr('open') !== 'open') {
                    $('#corresponding-keywords-panel').addClass("word-card-highlighted");
                }
            }
        }, function() {
            let tagsMatched = false;
            let originalKeywordsMatched = false;
            let tags;

            tags = document.querySelectorAll('[data-highlight="tag"]');
            tags.forEach((tag) => {
                let tagData = JSON.parse(tag.dataset.uris);
                tagData.forEach((uri) => {
                    if(uri == this.dataset.uri) {
                        tag.classList.remove('word-card-highlighted');
                        tagsMatched = true;
                    }
                });
            });

            $("span[data-uris*=\"" + this.dataset.uri + "\"]").each(function() {
                if(! $(this)[0].hasAttribute("data-force-highlight")) {
                    $(this).removeClass("word-card-highlighted");
                }
            });

            $("div[data-uri=\"" + this.dataset.uri + "\"]").each(function() {
                if(! $(this)[0].hasAttribute("data-force-highlight")) {
                    $(this).removeClass("word-card-highlighted");
                }
            });


            if($("#corresponding-keywords-panel div[data-uri=\"" + this.dataset.uri + "\"]").length > 0) {
                originalKeywordsMatched = true;
            }

            if(this.dataset.matchedChildUris !== undefined) {
                let matchedChildUris = JSON.parse(this.dataset.matchedChildUris);

                if(Array.isArray(matchedChildUris)) {
                    matchedChildUris.forEach((childUri) => {
                        $("div[data-uri=\"" + childUri + "\"]").each(function() {
                            if(! $(this)[0].hasAttribute("data-force-highlight")) {
                                $(this).removeClass("word-card-highlighted");
                            }
                        });

                        if(!originalKeywordsMatched) {
                            if($("#corresponding-keywords-panel div[data-uri=\"" + childUri + "\"]").length > 0) {
                                originalKeywordsMatched = true;
                            }
                        }

                        $("div[data-uris*='\"" + childUri + "\"']").each(function() {
                            if(! $(this)[0].hasAttribute("data-force-highlight")) {
                                $(this).removeClass("word-card-highlighted");
                            }
                        });
                        if(!tagsMatched) {
                            if($("div[data-uris*='\"" + childUri + "\"']").length > 0) {
                                tagsMatched = true;
                            }
                        }

                        $("span[data-uris*='\"" + childUri + "\"']").each(function() {
                            if(! $(this)[0].hasAttribute("data-force-highlight")) {
                                $(this).removeClass("word-card-highlighted");
                            }
                        });
                    });
                }
            }

            if(tagsMatched) {
                if(! $('#original-keywords-panel')[0].hasAttribute("data-force-highlight")) {
                    $('#original-keywords-panel').removeClass("word-card-highlighted");
                }
            }

            if(originalKeywordsMatched) {
                if(! $('#corresponding-keywords-panel')[0].hasAttribute("data-force-highlight")) {
                    $('#corresponding-keywords-panel').removeClass("word-card-highlighted");
                }
            }
        }
      )
});
