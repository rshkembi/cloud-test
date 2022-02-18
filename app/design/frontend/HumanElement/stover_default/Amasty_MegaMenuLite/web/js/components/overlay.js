/**
 *  Amasty Menu Overlay UI Component
 */

define([
    'jquery',
    'ko',
    'uiComponent'
], function ($, ko, Component) {
    'use strict';

    return Component.extend({
        defaults: {
            nodes: {
                body: $('body')
            },
            template: 'Amasty_MegaMenuLite/components/overlay'
        },

        /**
         * @inheritDoc
         */
        initialize: function () {
            var self = this;

            self._super();

            if (self.source) {
                self.source.isOpen.subscribe(function (value) {
                    self.isVisible(value);
                    self.nodes.body.css({
                        'overflow': value ? 'hidden' : ''
                    });
                });
                $('.ammenu-button').removeClass("loading");
            }

            return self;
        },

        /**
         * @inheritDoc
         */
        initObservable: function () {
            this._super()
                .observe({
                    'isVisible': false
                });

            return this;
        },

        /**
         * Hamburger button toggling method
         * @return {void}
         */
        toggling: function () {
            this.source.isOpen(!this.source.isOpen());
        }
    });
});
