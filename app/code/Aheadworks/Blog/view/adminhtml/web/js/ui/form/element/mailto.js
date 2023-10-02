define([
    'Magento_Ui/js/form/element/abstract'
], function (Abstract) {
    'use strict';

    return Abstract.extend({
        defaults: {
            elementTmpl: 'Aheadworks_Blog/ui/form/element/mailto',
            defaultLabel: ''
        },

        /**
         * Retrieve default label for field
         *
         * @returns {String}
         */
        getDefaultLabel: function() {
            return this.defaultLabel;
        }
    });
});
