/*
-----------------------------------------------------
    ___                            ___
   /   | ______________  _________/ (_)___  ____
  / /| |/ ___/ ___/ __ \/ ___/ __  / / __ \/ __ \
 / ___ / /__/ /__/ /_/ / /  / /_/ / / /_/ / / / /
/_/  |_\___/\___/\____/_/   \__,_/_/\____/_/ /_/

-----------------------------------------------------
Accordion
*/

jQuery(document).ready(function($) {

    // get url hash
    var hash = window.location.hash;
    if(hash && hash.indexOf('#fc-accordion') > -1) {
        var accordionEl = $('#' + hash.replace('#', ''));
        accordionEl.addClass('active');
    }

    $('.accordion__wrap h3.toggle').click(function() {

        var accWrap = $('.accordion__wrap');
        var parent = $(this).parent();

        if(parent.hasClass('active')) {
            $(this).removeClass('active');
        } else {
            accWrap.removeClass('active');
        }

        parent.toggleClass('active');

        // Scroll to clicked accordion
        var destination = $(this).offset().top;
        $('html:not(:animated),body:not(:animated)').animate({
            scrollTop: destination - 170
        }, 200);

    });

});
