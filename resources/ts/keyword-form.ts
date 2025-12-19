let counter = 1;

$('#button-add-custom-keyword').bind("click", function(){
    if($('#search-input').val().length > 2) {
        if(confirm('Are you sure the keyword you want to assign does not exist in the vocabularies? Findablity is greatly improved using shared terminology.')) {
            addFreeTextElements($('#search-input').val());
            $('#search-input').val('');
            $('#sampleKeywords-tree').jstree('search', '');
        }
    }
});

function addFreeTextElements(text) {
    var now = Date.now();
    var svg = '<svg class="info-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M15.7279 9.57627L14.3137 8.16206L5 17.4758V18.89H6.41421L15.7279 9.57627ZM17.1421 8.16206L18.5563 6.74785L17.1421 5.33363L15.7279 6.74785L17.1421 8.16206ZM7.24264 20.89H3V16.6473L16.435 3.21231C16.8256 2.82179 17.4587 2.82179 17.8492 3.21231L20.6777 6.04074C21.0682 6.43126 21.0682 7.06443 20.6777 7.45495L7.24264 20.89Z"></path></svg>';

    $("#sampleKeywords-modal-list-group").append(
        '<li class="" id="sampleKeywords-modal-list-group-item-' + now +'">' + svg + text + ' <a href="#" id="sampleKeywords-modal-list-group-item-delete-' + now + '" title="remove keyword">(remove)</a>' +
        '<input type="hidden" name="sampleKeywordsText[]" value="' + text +'">' +
        '<input type="hidden" name="sampleKeywordsUri[]" value="">' +
        '<input type="hidden" name="sampleKeywordsVocabUri[]" value=""></li>'
    );

    $('#sampleKeywords-modal-list-group-item-delete-' + now).bind("click", function() {
        $('#sampleKeywords-modal-list-group-item-' + now).remove();
        $('#sampleKeywords-form-list-group-item-' + now).remove();
    });

    counter = counter + 1;
}

function addModalElement(node) {
    var svg = '<svg class="info-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M13 21V23.5L10 21.5L7 23.5V21H6.5C4.567 21 3 19.433 3 17.5V5C3 3.34315 4.34315 2 6 2H20C20.5523 2 21 2.44772 21 3V20C21 20.5523 20.5523 21 20 21H13ZM13 19H19V16H6.5C5.67157 16 5 16.6716 5 17.5C5 18.3284 5.67157 19 6.5 19H7V17H13V19ZM19 14V4H6V14.0354C6.1633 14.0121 6.33024 14 6.5 14H19ZM7 5H9V7H7V5ZM7 8H9V10H7V8ZM7 11H9V13H7V11Z"></path></svg>';

    $("#sampleKeywords-modal-list-group").append(
        '<li class="" id="sampleKeywords-modal-list-group-item-' + node.id +'".>' + svg + node.text + ' <a href="#" id="sampleKeywords-modal-list-group-item-delete-' + node.id + '" title="remove keyword">(remove)</a>' +
        '<input type="hidden" name="sampleKeywordsText[]" value="' + node.text +'">' +
        '<input type="hidden" name="sampleKeywordsUri[]" value="' + node.original.extra.uri +'">' +
        '<input type="hidden" name="sampleKeywordsVocabUri[]" value="' + node.original.extra.vocab_uri + '"></li>'
    );

    $('#sampleKeywords-modal-list-group-item-delete-' + node.id).bind("click", function(){
        $('#sampleKeywords-modal-list-group-item-' + node.id).remove();
        $("#sampleKeywords-tree").jstree("uncheck_node", node.id);
    });

    counter = counter + 1;
}

var vocabData;

vocabData = (function() {
    var vocabData = null;
    $.ajax({
    'async': false,
    'global': false,
    'url': "https://raw.githubusercontent.com/UtrechtUniversity/msl_vocabularies/main/vocabularies/combined/editor/1.3/editor_1-3.json",
    'dataType': "json",
    'success': function(data) {
        vocabData = data;
    }
    });
    return vocabData;
})();

$.jstree.defaults.core.themes.responsive = true;
$('#sampleKeywords-tree').jstree({
    plugins: ["checkbox", "wholerow", "search"],
    "types": {
        "file": {
            "icon": "jstree-file"
        }
    },
    'core': {
        'data': vocabData
    },
    checkbox: {
        three_state : false, // to avoid that fact that checking a node also check others
        whole_node : false,  // to avoid checking the box just clicking the node
        tie_selection : false, // for checking without selecting and selecting without checking
        cascade: ''
    },
    "search": {
        "case_sensitive": false,
        "show_only_matches": true
    }
})
.on("check_node.jstree uncheck_node.jstree", function(e, data) {
    if(e.type == "check_node") {
        addModalElement(data.node);
        // check all parent nodes
        data.node.parents.forEach((element) => {
            $('#sampleKeywords-tree').jstree('check_node', element);
        });

    } else if (e.type == "uncheck_node") {
        $('#sampleKeywords-modal-list-group-item-' + data.node.id).remove();
    }
});


$(document).ready(function () {
    $("#search-input").keyup(function () {
        var searchString = $(this).val();
        $('#sampleKeywords-tree').jstree('search', searchString);
    });
});