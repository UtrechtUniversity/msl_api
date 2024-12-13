$(document).ready(function() {

    $('[data-toggle=domain-highlight]').hover(        
        function() {
            $("div[data-associated-subdomains*='\"" + this.dataset.domain + "\"']").addClass("wordCardHighlighted");
        }, function() {
            $("div[data-associated-subdomains*='\"" + this.dataset.domain + "\"']").removeClass("wordCardHighlighted");
        }
    )

    $('[data-highlight=tag]').hover(
        function() {
            if(this.dataset.uris !== undefined) {
                let matchedUris = JSON.parse(this.dataset.uris);
                if(Array.isArray(matchedUris)) {
                    matchedUris.forEach((uri) => {
                        $("div[data-uri=\"" + uri + "\"]").addClass("wordCardHighlighted");
                    });
                }
            }
        }, function() {
            if(this.dataset.uris !== undefined) {
                let matchedUris = JSON.parse(this.dataset.uris);    
                if(Array.isArray(matchedUris)) {
                    matchedUris.forEach((uri) => {
                        $("div[data-uri=\"" + uri + "\"]").removeClass("wordCardHighlighted");
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
                        tag.classList.add('wordCardHighlighted');
                        tagsMatched = true;
                    }
                });                                
            });
    
            $("span[data-uris*=\"" + this.dataset.uri + "\"]").addClass("wordCardHighlighted");
    
            $("div[data-uri=\"" + this.dataset.uri + "\"]").addClass("wordCardHighlighted");
            if($("#corresponding-keywords-panel div[data-uri=\"" + this.dataset.uri + "\"]").length > 0) {
                originalKeywordsMatched = true;
            }
    
            if(this.dataset.matchedChildUris !== undefined) {
                let matchedChildUris = JSON.parse(this.dataset.matchedChildUris);
    
                if(Array.isArray(matchedChildUris)) {
                    matchedChildUris.forEach((childUri) => {
                        $("div[data-uri=\"" + childUri + "\"]").addClass("wordCardHighlighted");
                        if(!originalKeywordsMatched) {
                            if($("#corresponding-keywords-panel div[data-uri=\"" + childUri + "\"]").length > 0) {
                                originalKeywordsMatched = true;
                            }
                        }
    
                        $("div[data-uris*='\"" + childUri + "\"']").addClass("wordCardHighlighted");
                        if(!tagsMatched) {
                            if($("div[data-uris*='\"" + childUri + "\"']").length > 0) {
                                tagsMatched = true;
                            }
                        }
    
                        $("span[data-uris*='\"" + childUri + "\"']").addClass("wordCardHighlighted");
                    });
                }
            }
                
            if(tagsMatched) {
                if($('#original-keywords-panel').attr('open') !== 'open') {
                    $('#original-keywords-panel').addClass("wordCardHighlighted");
                }
            }

            if(originalKeywordsMatched) {                        
                if($('#corresponding-keywords-panel').attr('open') !== 'open') {
                    $('#corresponding-keywords-panel').addClass("wordCardHighlighted");
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
                        tag.classList.remove('wordCardHighlighted');
                        tagsMatched = true;
                    }
                });                                
            });
    
            $("span[data-uris*=\"" + this.dataset.uri + "\"]").removeClass("wordCardHighlighted");
    
            $("div[data-uri=\"" + this.dataset.uri + "\"]").removeClass("wordCardHighlighted");
            if($("#corresponding-keywords-panel div[data-uri=\"" + this.dataset.uri + "\"]").length > 0) {
                originalKeywordsMatched = true;
            }
    
            if(this.dataset.matchedChildUris !== undefined) {
                let matchedChildUris = JSON.parse(this.dataset.matchedChildUris);
    
                if(Array.isArray(matchedChildUris)) {
                    matchedChildUris.forEach((childUri) => {
                        $("div[data-uri=\"" + childUri + "\"]").removeClass("wordCardHighlighted");
                        if(!originalKeywordsMatched) {
                            if($("#corresponding-keywords-panel div[data-uri=\"" + childUri + "\"]").length > 0) {
                                originalKeywordsMatched = true;
                            }
                        }
    
                        $("div[data-uris*='\"" + childUri + "\"']").removeClass("wordCardHighlighted");
                        if(!tagsMatched) {
                            if($("div[data-uris*='\"" + childUri + "\"']").length > 0) {
                                tagsMatched = true;
                            }
                        }
    
                        $("span[data-uris*='\"" + childUri + "\"']").removeClass("wordCardHighlighted");
                    });
                }
            }
    
            if(tagsMatched) {
                $('#original-keywords-panel').removeClass("wordCardHighlighted");
            }
    
            if(originalKeywordsMatched) {
                $('#corresponding-keywords-panel').removeClass("wordCardHighlighted");
            }
        }
      )
});