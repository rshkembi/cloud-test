define([
    'jquery',
    'underscore',
    'Magento_Ui/js/form/form',
    'mage/translate',
    'Aheadworks_Blog/js/ui/form/visibility-button',
    'uiRegistry',
    'Aheadworks_Blog/js/action/update-visibility',
], function ($, _, Component, $t, VisibilityButtonComponent, registry, updateVisibilityAction) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Aheadworks_Blog/ui/form',
            formId: '',
            formCss: '',
            buttonLabel: $t('Submit'),
            isDisplayCancel: false,
            isVisible: true,
            isNeedToRender: true,
            additionalDataFormPartSelectorList: []
        },

        /**
         * Initializes properties
         *
         * @return {Component}
         */
        initialize: function () {
            this._super()
                ._addFormKeyIfNotSet()
                ._addAdditionalDataFormPart();

            return this;
        },

        /**
         * Add form key to window object if form key is not added earlier
         * Used for submit request validation
         *
         * @returns {Form} Chainable
         */
        _addFormKeyIfNotSet: function () {
            if (!window.FORM_KEY) {
                window.FORM_KEY = $.mage.cookies.get('form_key');
            }
            return this;
        },

        /**
         * Add data-form-part attribute to additional form elements
         *
         * @returns {Form} Chainable
         */
        _addAdditionalDataFormPart: function () {
            var self = this;

            _.each(this.additionalDataFormPartSelectorList, function (field) {
                $.async('#' + self.getFormId() + ' ' + field, function (node) {
                    $(node).attr('data-form-part', self.namespace);
                });
            });
            return this;
        },

        /**
         * Retrieve form id
         *
         * @returns {String}
         */
        getFormId: function() {
            return this.formId;
        },

        /**
         * Retrieve form css
         *
         * @returns {String}
         */
        getFormCss: function() {
            return this.formCss;
        },

        /**
         * Cancel event
         */
        onCancel: function () {
            var visibilityButtonComponent = this.getVisibilityButtonComponent(),
                 form = $('#' + this.getFormId()),
                 formUiElementToFocus = visibilityButtonComponent.getFormUiElementToFocus(),
                 fieldToFocus = $('#' + formUiElementToFocus.uid)
            ;
            updateVisibilityAction(
                form,
                'hide',
                visibilityButtonComponent.animationSpeed,
                fieldToFocus
            );
            visibilityButtonComponent.visible(true);
        },

        /**
         * Get visibility button component
         */
        getVisibilityButtonComponent:  function () {
            var formName = this.getFormId();
            if (formName === 'comment-form') {
                return registry.get('awBlogCommentFormVisibilityButton');
            }
            var formId = formName.replace('comment-reply-form-', '');

            return registry.get('awBlogCommentReplyFormVisibilityButton-' + formId);
        },

        /**
         * Check if need to render form
         */
        isNeedToRenderForm: function () {
            return this.isNeedToRender;
        }
    });
});
