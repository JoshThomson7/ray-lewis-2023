function wc_easyzoom() {

    var $ = jQuery;

    // Instantiate EasyZoom instances
    var $easyzoom = $('.easyzoom').easyZoom();

    // Setup thumbnails example
    var api1 = $easyzoom.filter('.easyzoom--with-thumbnails').data('easyZoom');

    $('.thumbnails').on('click', 'a', function(e) {
        var $this = $(this);

        e.preventDefault();

        // Use EasyZoom's `swap` method
        api1.swap($this.data('standard'), $this.attr('href'));
    });

    $("a.product__video").click(function(e){
        e.preventDefault();

        $('.wc__product__gallery__images video').toggleClass('on').get(0).play();
        $('.wc__product__gallery__images easyzoom').toggleClass('off');
    });

    $(".thumbnails li a").click(function(e){
        e.preventDefault();

        $(".thumbnails li a").removeClass('active');
        $(this).addClass('active');

        video = $('.wc__product__gallery__images video');

        if(video.length > 0) {
            video.removeClass('on')
            video.get(0).pause();
            video.get(0).currentTime = 0;
            $('.wc__product__gallery__images easyzoom').removeClass('off');
        }
    });

}
