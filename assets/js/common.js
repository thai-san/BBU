function handleLayout () {
    var header = $("#header").outerHeight();
    var win = $(window).innerHeight();
    var sidebar = $("#sidebar").innerHeight();
    var content = $("#page-content").innerHeight();
    var height = win - header;
    
    if (content > height) {
        $("#sidebar").css({ minHeight: content});
    } else {
        $("#sidebar").css({ minHeight: height});
    }

}

$(window).resize(function() {
    handleLayout();
});

$("body").scroll(function() {
    handleLayout();
});

$(window).load(function() {
    handleLayout();
});

function load_file_list (post_id, type, element) {
    $.ajax({
        url: "/post/files/" + post_id + "/" + type,
        type: "html"
    })
    .done(function( html ) {
        $(element).html(html);
    });
}

$(function() {
    $('#tab').tab('show');
    $(".bs-callout").slideDown();
    $(".datepicker").datepicker({
        format: "yyyy-mm-dd",
        todayHighlight: true,
        todayBtn: "linked"
    });
})