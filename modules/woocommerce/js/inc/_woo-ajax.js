function wc_ajax(clicked_element) {

    var $ = jQuery;

    wc_product_id = $('.wc__single__product').attr('data-product-id');
    wc_is_gift = $('.wc__single__product').attr('data-wc-is-gift');

    if(!clicked_element) {
        wc_buy_type = wc_buy_type;
    } else {
        wc_buy_type = $(clicked_element).attr('data-wc-buy-type');
    }

    $('.wc__ajax__loading').addClass('is__loading');

    $.ajax({
        url: wc_ajax_object.ajax_url,
        dataType: 'html',
        type: 'POST',
        cache: true,
        data: ({
            'action' : 'wc_ajax',
            'wc_security' : wc_ajax_object.ajax_nonce,
            'wc_ajax_check' : 'true',
            'wc_product_id_data': wc_product_id,
            'wc_buy_type_data': wc_buy_type,
            'wc_is_gift_data': wc_is_gift
        }),

        success: function(data) {
            $('.wc__ajax__loading').removeClass('is__loading');

            period = $('.wcs__subscription li input:checked').attr('data-period');
            $('.wcs__duration li').find('small span').text(period);

            $('#wc_add_to_cart').html(data);

            wc_variations();
            wc_subscriptions();
            wc_delivery();

            $('.wc__add__to__cart__form').validate({
                messages: {
                    wc_gift_message: 'Oops! No message? :)'
                }
            });

            $('textarea[name="wc_gift_message"]').rules("add", {
                required: true
            });

            $('input[name="wc_variation"]').rules("add", {
                required: true
            });

            $('.wc__is__gift').click(function(e) {
                $('.wc__gift__message textarea').prop('disabled', function(i, v) { return !v; });
        	});
        }

    });

}

jQuery(document).ready(function($) {

    if($('#wc_add_to_cart').length > 0) {
        wc_ajax();
    }

    // Clicks
    jQuery(document).on('click', '.wc__ajax__trigger', function(e) {
        e.preventDefault();

        clicked_element = $(this);

        $('.wc__ajax__trigger').removeClass('active');
        clicked_element.addClass('active');

        wc_ajax(clicked_element);
    });

});
