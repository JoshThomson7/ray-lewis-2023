function wc_product_gallery() {

    var $ = jQuery;

    if($('#wc_product_gallery').length > 0) {

        $('#wc_product_gallery').lightSlider({
            item: 1,
            loop: false,
            auto:true,
            pause: 4000,
    	    easing: 'cubic-bezier(0.25, 0, 0.25, 1)',
    	    controls: false,
    	    gallery: true,
            slideMargin: 0,
            enableDrag: false,
            thumbItem: 7,
            thumbMargin:4,
            responsive : [
                {
                    breakpoint:1200,
                    settings: {
                        verticalHeight: 400,
                    }
                },
                {
                    breakpoint: 900,
                    settings: {
                        vertical: false
                    }
                }
            ]
        });

    }

}
