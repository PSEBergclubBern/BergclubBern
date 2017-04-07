$(".add-link").click(function () {
    var href = $(this).find("a").attr("href");
    if (href) {
        window.location = href;
    }
});

$(document).ready(function(){
    $('a.email').each(function(){
        var email = atob($(this).data('id'));
        $(this).attr('href', 'mailto:' + email);
        $(this).html(email);
    });
});
