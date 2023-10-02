define([
    'Magento_Ui/js/form/components/button'
], function (Component) {
    'use strict';

    return Component.extend({
        /**
         * Show element
         *
         * @returns {Button} Chainable
         */
        show: function () {
            this.visible(true);

            return this;
        },

        /**
         * Hide element
         *
         * @returns {Button} Chainable
         */
        hide: function () {
            this.visible(false);

            return this;
        }
    });
});

