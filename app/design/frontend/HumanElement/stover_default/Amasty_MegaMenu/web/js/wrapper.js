/**
 *  Extend Amasty MegaMenuLite Wrapper UI Component
 */

define([
    'jquery',
    'ammenu_helpers'
], function ($, helper) {
    'use strict';

    return function (Wrapper) {
        return Wrapper.extend({
            defaults: {
                menuPosition: 150,
                classes: {
                    sticky: '-sticky'
                },
                nodes: {
                    header: {}
                },
                selectors: {
                    header: '#ammenu-header-container'
                }
            },

            /**
             * Slick Slider Initialization
             *
             * @desc Slick slider init and generating options
             * @return {Object}
             */
            initialize: function () {
                this._super();

                this.nodes.header = $(this.selectors.header);
                this._initSticky();

                return this;
            },

            /**
             * Init Sticky menu method
             * @return {void | Boolean}
             */
            _initSticky: function () {
                var self = this,
                    isUnderMenu,
                    $window = $(window);

                if (
                    !self.is_sticky
                    || self.is_sticky === 2 && !self.isMobile
                    || self.is_sticky === 1 && self.isMobile
                ) {
                    return false;
                }

                self.menuPosition = self.nodes.header.height() + self.nodes.header.position().top;

                if (self.isMobile && (self.is_sticky === 2 || self.is_sticky === 3)) {
                    self.isSticky.subscribe(function (value) {
                        if (value) {
                            self.nodes.header.addClass(self.classes.sticky);
                        } else {
                            self.nodes.header.removeClass(self.classes.sticky);
                        }
                    });
                }

                $window.scroll(_.throttle(function () {
                    isUnderMenu = $window.scrollTop() > self.menuPosition;

                    if (isUnderMenu && !self.isSticky()) {
                        self.isSticky(true);
                        self.nodes.header.addClass(self.classes.sticky);
                    }

                    if (!isUnderMenu && self.isSticky()) {
                        self.isSticky(false);
                        self.nodes.header.removeClass(self.classes.sticky);
                    }
                }, 200));
            }
        });
    };
});
