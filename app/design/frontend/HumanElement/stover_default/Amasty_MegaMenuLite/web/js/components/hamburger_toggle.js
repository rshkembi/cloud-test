/**
 *  Amasty Hamburger toggle UI Component
 */

define([
    'jquery',
    'ko',
    'uiComponent',
    'rjsResolver'
], function ($, ko, Component, resolver) {
    'use strict';

    return Component.extend({
        defaults: {
            links: {
                color_settings: 'index = ammenu_wrapper:color_settings'
            }
        },

        /**
         * @inheritDoc
         */
        initialize: function () {
            var self = this;

            self._super();

            resolver(function () {
                self.color(self.color_settings.toggle_icon_color);
            });

            return self;
        },

        /**
         * @inheritDoc
         */
        initObservable: function () {
            this._super()
                .observe({
                    isOpen: false,
                    color: false
                });

            return this;
        },

        /**
         *  Toggling open state method
         *  @return {void}
         */
        toggling: function () {
            var self = this;

            if (self.source) {
                $('.ammenu-button').removeClass("loading");
                if(this.isOpen()) {
                    $('.ammenu-button').removeClass("active");
                } else {
                    $('.ammenu-button').addClass("active");
                }
                this.isOpen(!this.isOpen());
            }
        }
    });
});
