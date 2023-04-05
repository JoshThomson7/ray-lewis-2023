function wc_variations() {

    var $ = jQuery;

    if($('.wc__add__to__cart__form').length > 0) {

        init_variation_id = $('input[name="wc_variation"]:checked').attr('data-variation-id');
        init_attribute_value = $('input[name="wc_variation"]:checked').attr('data-attribute-value');

        $('input[name="variation_id"]').val(init_variation_id);
        $('input.wc__attribute').val(init_attribute_value);

        $(document).on('click', '.wc__variation label', function() {
            variation_id = $(this).prev().attr('data-variation-id');
            attribute_value = $(this).prev().attr('data-attribute-value');

            $('input[name="variation_id"]').val(variation_id);
            $('input.wc__attribute').val(attribute_value);
        });

    }

}
