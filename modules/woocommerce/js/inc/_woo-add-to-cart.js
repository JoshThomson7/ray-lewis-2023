/**
* wc_add_to_cart()
*
* Handles form submission via AJAX
*
* @param {obj} submitted_form [subitted form element]
*/

(function ($, root, undefined) {


    if($('.wc__header__cart').length > 0) {
        wc_update_header_cart_ajax();
    }

    // Variable product quantities
    $('input[name=wc_variation_quantity]').on('change paste keyup', function(e) {
        var qty_el = $(this);
        var qty_val = $(this).val();

        qty_el.closest('.wc__variation').find('input[name=wc_variation]').attr('data-variation-qty', qty_val);
    });

    // Submit form
    $(document).on('submit', '.wc__add__to__cart__form', function(e) {
        e.preventDefault();

        var submitted_form = $(this);

        wc_add_to_cart(submitted_form);
    });

    function wc_add_to_cart(submitted_form) {

        var cart_data = {};
        var product_type = submitted_form.data('wc-product-type');
        var is_gift = $('#wc_is_gift').prop("checked");
        var notice = $('.wc__add__to__cart__notice');
        var message;
        var timeout = null;
        var validate = true;    
    
        // remove errors
        notice.removeClass('display has__errors');
    
        // Terms
        var wc_terms = $('input[name=wc_terms]');
    
        // Variable
        if(product_type === 'variable' || product_type === 'variable-subscription') {
    
            var checked_inputs = $('input[name=wc_variation]:checked');
    
            // Any checked inputs?
            if(checked_inputs.length > 0) {
    
                checked_inputs.each(function(index) {
                    var variation_el = $(this);
                    var variation_id = variation_el.data('variation-id');
    
                    cart_data[variation_id] = {
                        'product_id': variation_el.data('product-id'),
                        'variation_id': variation_el.data('variation-id'),
                        'quantity': variation_el.data('variation-qty'),
                        'variation_name': variation_el.data('variation-name'),
                        'variation_slug': variation_el.data('variation-slug'),
                        'variation_price': variation_el.data('variation-price')
                    }
                    
                    // Handle gift for variations.
                    if(is_gift) {
    
                        if($('input[name=recipient_name]').val() != '' || $('input[name=recipient_email]').val() != '') {
    
                            cart_data[variation_id]['is_gift'] = true;
                            cart_data[variation_id]['recipient_name'] = $('input[name=recipient_name]').val();
                            cart_data[variation_id]['recipient_email'] = $('input[name=recipient_email]').val();
                            cart_data[variation_id]['recipient_message'] = $('textarea[name=recipient_message]').val();
    
                        } else {
                            
                            $('.wc__add__to__cart__button').removeClass('disable').html('Add to cart');
    
                            message = 'Gifting a voucher requires a name and an email address'
                            validate = false;
    
                        }
                    }
    
                });
    
            } else { 
    
                message = 'Please select at least one option from the above';
                validate = false;
    
            } // end checked inputs
    
            // Validat terms
            if(wc_terms.length > 0) {
                if(!wc_terms.is(':checked')) {
                    message = 'Please ensure you have read and agree to the<br>Terms &amp; Conditions by ticking the box above';
                    validate = false;
                }
            }
        
        // Simple.
        } else if(product_type === 'simple') {
    
            var simple_el = $('input[name=wc_simple]');
            var product_id = simple_el.data('product-id');
            var product_qty = simple_el.val();
    
            if(product_qty != 0) {
    
                cart_data[product_id] = {
                    'product_id': product_id,
                    'quantity': product_qty
                }
    
                // Handle gift for variations.
                if(is_gift) {
    
                    if($('input[name=recipient_name]').val() != '' || $('input[name=recipient_email]').val() != '') {
    
                        cart_data[product_id]['is_gift'] = true;
                        cart_data[product_id]['recipient_name'] = $('input[name=recipient_name]').val();
                        cart_data[product_id]['recipient_email'] = $('input[name=recipient_email]').val();
                        cart_data[product_id]['recipient_message'] = $('textarea[name=recipient_message]').val();
    
                        console.log(cart_data);
                        
    
                    } else {
    
                        message = 'Gifting a voucher requires a name and an email address'
                        validate = false;
    
                    }
    
                }
    
            } else { 
    
                message = 'Quantity must be at least 1';
                validate = false;
    
            }
    
            //console.log(cart_data);
        
        }
    
        //console.log(product_type);
    
        // Everything correct?
        if(validate) {
    
            // Show spinner.
            $('.wc__add__to__cart__button').addClass('disable').html('<i class="fal fa-spinner-third fa-spin"></i>');
    
            // Fire!!!
            $.ajax({
                url: wc_ajax_object.ajax_url,
                dataType: 'html',
                type: 'POST',
                contentType: 'application/x-www-form-urlencoded; charset=utf-8',
                data: ({
                    'action' : 'wc_ajax_add_to_cart',
                    'wc_security' : wc_ajax_object.ajax_nonce,
                    'wc_product_type' : product_type,
                    'wc_cart_data' : cart_data
                }),
    
                success: function(data) {
    
                    console.log(data);
    
                    // add to cart button
                    $('.wc__add__to__cart__button').removeClass('disable').html('<i class="fas fa-check-circle"></i> Added');
    
                    // show continue buttons
                    $('.wc__deal__continue').addClass('on');

                    //wc_update_header_cart_ajax();
    
                }
    
            });
    
        } else {
    
            notice.addClass('display has__errors').html(message);

            clearTimeout(timeout);
    
            timeout = setTimeout(function() {
                notice.removeClass('display has__errors').html('');
            }, 3000);
    
        }
    
    }
    
    
    function wc_update_header_cart_ajax() {
    
        //$('.wc__header__cart').html('<a href="#"><span>&bull; &bull; &bull;</span></a>');
    
        $.ajax({
            url: wc_ajax_object.ajax_url,
            dataType: 'html',
            type: 'POST',
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            data: ({
                'action' : 'wc_update_header_cart_ajax',
                'wc_security' : wc_ajax_object.ajax_nonce
            }),
    
            success: function(data) {
                $('.wc__header__cart').html(data);
            }
    
        });
    
    }

})(jQuery, this);
