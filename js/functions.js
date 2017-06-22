$(document).ready(function () {
    $(".fancybox").fancybox({
        closeBtn: false,
        helpers: {
            title: {
                type: "outside"
            },
            thumbs: {
                width: 50,
                height: 50
            },
            buttons: {}
        },
        beforeLoad: function () {
            this.title = $(this.element).attr("caption");
        },
        padding: 0
    });
    $(".big_fancybox").click(function (e) {
        e.preventDefault();
        $('a[data-ph="' + $(this).attr("rel") + '"]').trigger("click");
    });
    if ($.fn.datepicker) {
        $("#inputDate").datepicker({
            "dateFormat": "dd.mm.yy"
        });

    }
});


function likePhoto(element, photoId) {
    $(element).attr("disabled", true);

    $.post("/like.php", {
        id: photoId
    }, function (response) {

        $("#likes-for-" + photoId).text(response.likes);

        var tyBox = $("#ty-box-" + photoId);
        tyBox.removeClass("hidden");
        setTimeout(function () {
            tyBox.addClass("hidden");
        }, 5000);
    }, "json")
}