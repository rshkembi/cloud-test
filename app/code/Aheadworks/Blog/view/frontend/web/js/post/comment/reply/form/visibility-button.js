define([
    'underscore',
    'jquery',
    'uiRegistry',
    'Aheadworks_Blog/js/ui/form/visibility-button',
], function (_, $, registry, VisibilityButtonComponent) {
    'use strict';

    return VisibilityButtonComponent.extend({
        defaults: {
            visible: true,
            commentId: '',
            postId: '',
            isNeedToRenderForm: false
        },

        /**
         * Initializes properties
         *
         * @return {VisibilityButtonComponent}
         */
        initialize: function () {
            this._super();

            this._updateFormRenderFlag(this.isNeedToRenderForm);

            return this;
        },

        /**
         * Update form render flag
         *
         * @private
         * @param isNeedToRenderFormFlag
         */
        _updateFormRenderFlag: function (isNeedToRenderFormFlag) {
            var formUiComponent = this.getFormUiComponent();

            if (formUiComponent) {
                formUiComponent.isNeedToRenderFormFlag(isNeedToRenderFormFlag);
            }
        }
    });
});
