function handleLayout () {
    var header = $("#header").outerHeight();
    var win = $(window).innerHeight();
    var sidebar = $("#sidebar").innerHeight();
    var content = $("#page-content").innerHeight();
    var height = win - header;
    
    if (content > height) {
        $("#sidebar").css({ height: content});
    } else {
        $("#sidebar").css({ height: height});
    }

}

$(window).resize(function() {
    handleLayout();
});

$(window).load(function() {
    handleLayout();
});

$(function() {
    $('#tab').tab('show');
})