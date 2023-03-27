var config = {
    config: {
        mixins: {
            'ShipperHQ_Pickup/js/model/config': {
                'ShipperHQ_Pickup/js/model/config-mixin': true
            },
            'Magento_Checkout/js/action/set-shipping-information': {
                "ShipperHQ_Pickup/js/action/set-shipping-information": false,
                "ShipperHQ_Pickup/js/action/set-shipping-information-mixin": true,
            }
        }
    }
};
