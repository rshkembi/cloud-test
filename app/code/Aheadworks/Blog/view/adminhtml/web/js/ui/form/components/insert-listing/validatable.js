define([
    'ko',
    'Magento_Ui/js/form/components/insert-listing',
    'Magento_Ui/js/modal/alert',
    'mage/translate'
], function (ko, InsertListing, alertModal, $t) {
    'use strict';

    return InsertListing.extend({

        /**
         * Validates itself
         *
         * @returns {Object} Validate information.
         */
        validate: function () {
            var isValid = (this.selections().getSelections().total > 0);

            if (!isValid) {
                alertModal({
                    content: $t('Please select post')
                });
            }

            return {
                valid: isValid
            };
        }
    });
});
