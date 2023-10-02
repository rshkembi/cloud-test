define([
    'ko',
    'Magento_Ui/js/grid/columns/multiselect'
], function (ko, MultiSelect) {
    'use strict';

    return MultiSelect.extend({
        defaults: {
            totalSelected: 0,
            headerTmpl: 'Aheadworks_Blog/ui/grid/columns/single-select',
            bodyTmpl: 'Aheadworks_Blog/ui/grid/columns/cells/single-select',
            preserveSelectionsOnFilter: true
        },

        /**
         * Initializes observable properties of instance
         *
         * @returns {SingleSelect} Chainable
         */
        initObservable: function () {
            this._super();

            this._selected = ko.pureComputed({
                read: function () {
                    return this.selected().length != 0
                        ? this.selected()[0]
                        : undefined;
                },

                /**
                 * Validates input field prior to updating 'qty' property
                 */
                write: function (value) {
                    this.selected([value]);
                },

                owner: this
            });

            return this;
        }
    });
});
