define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote',
    'ShipperHQ_Calendar/js/model/config'
], function ($, wrapper, quote, calendarConfig) {
    'use strict';

    return function (setShippingInformationAction) {

        return wrapper.wrap(setShippingInformationAction, function (originalAction) {
            var shippingAddress = quote.shippingAddress();
            if (shippingAddress['extension_attributes'] === undefined) {
                shippingAddress['extension_attributes'] = {};
            }
            var locationId = $('input[name="shipperhq_location"]').val();

            shippingAddress['extension_attributes']['delivery_date'] = calendarConfig.show_calendar() ? $('#shipperhq_calendar').val() : "";
            shippingAddress['extension_attributes']['time_slot'] = calendarConfig.show_calendar() ? $('#shipperhq_timeslots').val(): "";
            shippingAddress['extension_attributes']['location_id'] = locationId;
            // pass execution to original action ('Magento_Checkout/js/action/set-shipping-information')
            var outcome = originalAction();

            if(locationId) {
                var populateAddressUrl = window.checkoutConfig.shipperhq_pickup.populate_location_address_url;
                var shipping_carrier_code = quote.shippingMethod().carrier_code;
                $.ajax({
                    type: 'GET',
                    url: populateAddressUrl,
                    data: {
                        'carrier': shipping_carrier_code,
                        'location_id': locationId
                    },
                    context: $('body'),
                    success: function (data) {

                        if (data.address) {
                            shippingAddress['extension_attributes']['location_address'] = data.address.location_address;
                            quote.shippingAddress(shippingAddress);

                        }
                    }
                });

            } else {
                shippingAddress['extension_attributes']['location_address'] = '';
            }
            quote.shippingAddress(shippingAddress);

            return outcome;

        });

    };
});
