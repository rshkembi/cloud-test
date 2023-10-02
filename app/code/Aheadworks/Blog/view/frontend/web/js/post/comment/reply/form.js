define([
    'Aheadworks_Blog/js/ui/form'
], function (Form) {
    'use strict';

    return Form.extend({
        defaults: {
            isNeedToRenderFormFlag: true,
            isDisplayCancel: true
        },

        /**
         * Initializes observable properties
         *
         * @return {Form} Chainable
         */
        initObservable: function () {
            this._super()
                .observe([
                    'isNeedToRenderFormFlag'
                ]);

            return this;
        },

        /**
         * Is need to render form
         *
         * @return boolean
         */
        isNeedToRenderForm: function () {
            return this.isNeedToRenderFormFlag();
        }
    });
});
