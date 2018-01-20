function scrollto(selector) {
    if(typeof selector=='string')selector=$(selector);
    return $(window).scrollTop(selector.offset().top);
}
function htmlencode(html=''){
    return $('<div>').text(html).html();
}
function loading_ui(text='Loading, please wait...'){
    return $('<div>',{
        class:'w3-margin w3-center'
    }).append(
        $('<i>',{
            class:'fa fa-spinner fa-spin fa-pulse fa-3x'
        })
    ).append(
        $('<p>',{
            class:'w3-margin-top'
        }).html(text)
    );
}