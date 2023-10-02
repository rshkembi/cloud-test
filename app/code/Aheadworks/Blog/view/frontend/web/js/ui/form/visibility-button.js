define([
    'underscore',
    'jquery',
    'uiRegistry',
    'uiComponent',
    'Aheadworks_Blog/js/action/update-visibility',
], function (_, $, registry, Component, updateVisibilityAction) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Aheadworks_Blog/ui/form/visibility-button',
            visible: true,
            buttonLabel: '',
            formUiComponentName: '',
            formUiElementNameToFocus: '',
            animationSpeed: 500,
            animationType: 'show',
            isNeedToHideButtonAfterClick: true
        },

        /**
         * Initializes properties
         *
         * @return {Component}
         */
        initObservable: function () {
            this._super()
                .observe('visible');

            return this;
        },

        /**
         * Update visibility for connected form
         */
        updateFormVisibility: function () {
            var formUiComponent = this.getFormUiComponent(),
                formUiElementToFocus = this.getFormUiElementToFocus(),
                form,
                fieldToFocus
            ;

            if (formUiComponent
                && formUiElementToFocus
            ) {
                form = $('#' + formUiComponent.getFormId());
                fieldToFocus = $('#' + formUiElementToFocus.uid);

                updateVisibilityAction(
                    form,
                    this.animationType,
                    this.animationSpeed,
                    fieldToFocus
                );

                if (this.isNeedToHideButtonAfterClick) {
                    this.visible(false);
                }
            }
        },

        /**
         * Retrieve UI component of the form to manage
         *
         * @returns {*}
         */
        getFormUiComponent: function () {
            return registry.get(this.formUiComponentName);
        },

        /**
         * Retrieve UI element of the form to focus on
         *
         * @returns {*}
         */
        getFormUiElementToFocus: function () {
            return registry.get(
                this.formUiComponentName + '.' + this.formUiElementNameToFocus
            );
        }
    });
});
