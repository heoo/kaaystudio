jQuery(".works-archive .site-inner a").click(function(){
    event.preventDefault();
    if (jQuery(window).width() >= 480 && jQuery(window).height() >= 480) {
        var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
        $currentImg = jQuery(this);

        jQuery( window ).height();
        jQuery(".modal-wrapper").show();
        jQuery(".modal-wrapper").css('line-height', jQuery( window ).height() + 'px');
        jQuery(".modal-inner").append('<img src="' + jQuery(this).attr('href') + '"/>');

        jQuery(".modal-inner img").css('max-height', jQuery( window ).height() - 250);
        jQuery(".modal-inner").append('<div class="modal-title"></div>');
        jQuery('.modal-title').append('<p class="modal-title-top">' + jQuery(this).children('.work-title').text() + '</p>');
        jQuery('.modal-title').append('<p class="modal-middle">-</p>');
        jQuery('.modal-title').append('<p class="modal-author">' + jQuery(this).children('.work-author').text() + '</p>');
        jQuery('.modal-title').append('<p class="modal-description">' + jQuery(this).children('.work-description').text() + '</p>');
        setTimeout(
            function(){
                jQuery('.modal-title').css('visibility', 'visible');
            }, 100);
        jQuery('.close-this-page').show();
    }
});
function hideModal() {
    jQuery(".modal-wrapper img").remove();
    jQuery(".modal-title-top").remove();
    jQuery(".modal-middle").remove();
    jQuery(".modal-title").remove();
    jQuery(".modal-author").remove();
    jQuery(".modal-wrapper").hide();
    jQuery('.title_photo_bg').removeAttr('display');
    jQuery('.close-this-page').hide();
}


jQuery(".modal-wrapper, .close-this-page").click(function(){
    hideModal();
});
jQuery(".modal-nav.next").click(function(){
    modal_next();
    event.stopPropagation();
});

function modal_next() {
    if ($currentImg.parent().is('.lastWork')) {
        $currentImg = jQuery('.firstWork').children();
    }
    else {
        $currentImg = $currentImg.parent().next().children();
    }
    jQuery(".modal-inner img").attr('src', $currentImg.attr('href'));
    jQuery('.modal-title p.modal-description').text($currentImg.children('.work-description').text());
    jQuery('.modal-title p.modal-title-top').text($currentImg.children('.work-title').text());
    jQuery('.modal-title p.modal-author').text($currentImg.children('.work-author').text());
}
jQuery(".modal-nav.prev").click(function(){
    modal_prev();
    event.stopPropagation();
});

function modal_prev(){
    if ($currentImg.parent().is('.firstWork')) {
        $currentImg = jQuery('.lastWork').children();
    }
    else {
        $currentImg = $currentImg.parent().prev().children();
    }
    jQuery(".modal-inner img").attr('src', $currentImg.attr('href'));
    jQuery('.modal-title p.modal-description').text($currentImg.children('.work-description').text());
    jQuery('.modal-title p.modal-title-top').text($currentImg.children('.work-title').text());
    jQuery('.modal-title p.modal-author').text($currentImg.children('.work-author').text());
    if (window.getSelection) {window.getSelection().removeAllRanges();}
    else if (document.selection) {document.selection.empty();}
}