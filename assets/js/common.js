tinymce.init({
    selector: "#input_type_text",
    height: 300,
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime table contextmenu paste"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
});

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
    $(".bs-callout").slideDown();
    $(".datepicker").datepicker({
        format: "yyyy-mm-dd",
        todayHighlight: true,
        todayBtn: "linked",
        startDate: new Date()
    });
})