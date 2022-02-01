var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/sidebar': {
                'HumanElement_QtyStepper/js/sidebar-mixin': true
            }
        }
    },
    map: {
        '*': {
            qtyStepper: 'HumanElement_QtyStepper/js/qty-stepper'
        }
    }
};
