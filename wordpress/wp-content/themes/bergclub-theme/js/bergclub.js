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

    lightbox.option({
        albumLabel: "Bild %1 von %2"
    })
});

$('#tourenart').change(function(){
    var url = '//' + window.location.hostname + window.location.pathname;
    if($(this).val() != ''){
        url += '?type=' + $(this).val();
    }
    document.location.href = url;
});
