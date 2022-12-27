// @codekit-prepend "../../../lib/select2/_select2.full.min.js";

(function ($, root, undefined) {

    $(window).on('load', function() { 

        var atmCurrencyEl = $('#atm_currency_sel');

        atmCurrencyEl.select2({
            placeholder: "Select a currency",
            data: atm_ajax_object.currencies,
            templateResult: formatDropdown,
            templateSelection: formatSelection
        });

        atmCurrencyEl.on('select2:open', function(e) {
            $('input.select2-search__field').prop('placeholder', 'Search for a currency');
            $('input.select2-search__field').trigger('click');
        });

        onLoad();
        
    });

    function onLoad() {

        var queryString = window.location.search;
        var urlParams = new URLSearchParams(queryString);
        var currency = atm_ajax_object.currency_iso ?? urlParams.get('currency');
        var mode = atm_ajax_object.mode ?? urlParams.get('currency');

        $('#atm_currency_sel').val(currency??'eur');
        $('#atm_currency_sel').trigger('change');

        if(mode === 'sell') {
            handleModeChange($('input[name="mode"][value="sell"]'));
        }

        $('input[name="mode"]').on('change', function(e) {
            handleModeChange($(this));
        });

        $('#atm_form').on('submit', function(e) {
            $('.atm-form-input button span').html($().spinner(30));
        });

        if(!currency) {
            var url = window.location.href.split('?')[0];
            console.log(url);
            console.log(url.substring(url.lastIndexOf('/') + 1));
        }

    }
    
    function formatDropdown(currency) {
        if(currency.id === undefined) { return; }
        return $(
            '<div class="atm-select-option">'+
                '<span class="flag"><img src="'+atm_ajax_object.imgPath+'flags/'+currency.id+'.svg" alt="'+currency.text+'" /></span>' +
                '<span class="label">'+currency.text+'</span>'+
            '</div>'
        );
    }

    function formatSelection(state) {
        if(state.id === undefined) { return; }

        var mode = $('.atm-form-switch input:checked').val();
        var currency = state.text.replace(/\s+/g, '-').toLowerCase();
        var url = atm_ajax_object.siteUrl+'/'+mode+'/'+currency;
        $('#atm_form').attr('action', url);
        
        handleCurrencySelection(state, mode);

        return $(
            '<div class="atm-select-option">'+
                (state.id ? '<span class="flag"><img src="'+atm_ajax_object.imgPath+'flags/'+state.id+'.svg" alt="'+state.text+'"/></span>' : '') +
                '<span class="label">'+state.text+'</span>'+
            '</div>'
        );
    }

    function handleModeChange(el) {
        $('#atm_currency_sel').trigger('change');
        var sel2data = $('#atm_currency_sel').select2('data')[0];

        handleCurrencySelection(sel2data, el.val());

        $('.label-text').text('Amount to '+el.val());
        $('.atm-form-input--join').toggleClass('reverse');
    }

    function handleCurrencySelection(data, mode) {
        if(mode === 'sell') {
            $('.currency-icon').text(data.symbol);
        } else {
            $('.currency-icon').text('£')
        }
    }

})(jQuery, this);