define([
    'Magento_Ui/js/form/element/abstract',
    'Magento_Customer/js/customer-data',
    'domReady!'
], function (Element, customerData) {
    'use strict';

    return Element.extend({

        /**
         * Get initial value
         *
         * @return {Element}
         */
        getInitialValue: function () {
            var customer = customerData.get('customer');
            var customerFullName = customer().fullname;

            if (customerFullName !== undefined) {
                this.default = customerFullName;
            } else {
                customer.subscribe(function (updatedCustomer) {
                    this.initialValue = updatedCustomer.fullname;
                    this.reset();
                }, this);
            }

            return this._super();
        },
    });
});
