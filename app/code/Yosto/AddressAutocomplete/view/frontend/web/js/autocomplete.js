define([
    'jquery',
    'uiComponent',
    'Yosto_Opc/js/google_maps_loader',
    'Magento_Checkout/js/checkout-data' ,
    'uiRegistry'
], function(
    $, Component, GoogleMapsLoader, checkoutData, uiRegistry
){

    var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'long_name',
        country: 'short_name',
        postal_code: 'short_name'
    };

    var lookupElement = {
        street_number: 'street_1',
        route: 'street_2',
        locality: 'city',
        administrative_area_level_1: 'region',
        country: 'country_id',
        postal_code: 'postcode'
    };


    GoogleMapsLoader.done(function(){
        var enabled = window.checkoutConfig.yosto_autocomplete.active;
        var geocoder = new google.maps.Geocoder();
        if(enabled == '1') {
            var checkAddressStreetLines = setInterval(function () {

                var streetLines = uiRegistry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.street').elems();
                if (streetLines.length > 0 && document.getElementById(streetLines[0].uid) !== null){
                    for (i = 0; i <  streetLines.length; i++) {
                        if (i == 0) {
                            shippingAddressAutoComplete = new google.maps.places.Autocomplete(
                                document.getElementById(streetLines[i].uid),
                                {types: ['geocode']});

                            shippingAddressAutoComplete.addListener('place_changed', function() {
                                fillInAddress('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset',shippingAddressAutoComplete);
                            });

                            $('#' + streetLines[i].uid).focus(geolocate);
                        } else {
                            new google.maps.places.Autocomplete(
                                document.getElementById(streetLines[i].uid),
                                {types: ['geocode']}
                            );
                            $('#' + streetLines[i].uid).focus(geolocate);
                        }
                    }

                    $("input[id^='billing-address-same-as-shipping']").on('click', function () {
                        if ($(this).is(":checked") == false) {
                            var checkboxId = $(this).attr('id');
                            var currentForm = checkboxId.replace('billing-address-same-as-shipping-', '') + "-form";
                            var billingStreetLines = uiRegistry.get('checkout.steps.billing-step.payment.payments-list.' + currentForm + '.form-fields.street').elems();

                            for (j=0; j < billingStreetLines.length; j++) {

                                if (j==0) {
                                    billingAddressAutoComplete = new google.maps.places.Autocomplete(
                                        document.getElementById(billingStreetLines[j].uid),
                                        {types: ['geocode']}
                                    );
                                    $('#' + billingStreetLines[j].uid).focus(geolocate);
                                    billingAddressAutoComplete.addListener('place_changed', function() {
                                        fillInAddress('checkout.steps.billing-step.payment.payments-list.' + currentForm + ".form-fields", billingAddressAutoComplete)
                                    });
                                } else {
                                    new google.maps.places.Autocomplete(
                                        document.getElementById(billingStreetLines[j].uid),
                                        {types: ['geocode']}
                                    );
                                    $('#' + billingStreetLines[j].uid).focus(geolocate);
                                }
                            }
                        }
                    });

                    clearInterval(checkAddressStreetLines);
                }

            }, 2000);


        }
    }).fail(function(){
        console.error("ERROR: Google maps library failed to load");
    });

    function fillInAddress(parentElement, currentAutoCompleteObject) {
        var place = currentAutoCompleteObject.getPlace();

        var street = [];
        var region  = '';

        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
            if (componentForm[addressType]) {
                var value = place.address_components[i][componentForm[addressType]];
                if(addressType == 'street_number') {
                    street[0] = value;
                }
                else if(addressType == 'route') {
                    street[1] = value;
                }
                else if (addressType == 'administrative_area_level_1') {
                    region = value;
                }
                else {
                    var elementId = lookupElement[addressType];
                    var currentItem = $("#" + uiRegistry.get(parentElement + '.'+ elementId).uid);
                    if(currentItem) {
                        currentItem.val(value);
                        currentItem.trigger('change');
                    }
                }
            }
        }
        if(street.length > 0) {
            var street0 = $("#" + uiRegistry.get(parentElement + '.street').elems()[0].uid);
            var streetString = street.join(' ');
            if(street0) {
                street0.val(streetString);
                street0.trigger('change');

            }
        }

        if(region != '') {
            var regionDom = uiRegistry.get(parentElement + '.region_id');
            if(regionDom) {
                var regionDomJq = $("#" + regionDom.uid);
                if(regionDomJq) {
                    //search for and select region using text
                    $('#' + regionDom.uid +' option')
                        .filter(function() {return $.trim( $(this).text() ) == region; })
                        .attr('selected',true);
                    regionDomJq.trigger('change');
                }
            }
            if(uiRegistry.get(parentElement + '.region_id_input')) {
                var regionInput = $("#" + uiRegistry.get(parentElement + '.region_id_input').uid);
                if(regionInput) {
                    regionInput.val(region);
                    regionInput.trigger('change');
                }
            }
        }
    };

    geolocate = function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var geolocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                var circle = new google.maps.Circle({
                    center: geolocation,
                    radius: position.coords.accuracy
                });
                autocomplete.setBounds(circle.getBounds());
            });
        }
    };
    return Component;

});
