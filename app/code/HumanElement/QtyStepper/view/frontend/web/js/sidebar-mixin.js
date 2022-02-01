/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'jquery/ui'
], function ($) {
    'use strict';

    return function (originalSidebar) {
        $.widget('mage.sidebar', originalSidebar, {
            /**
             * Adds event handlers to make the qty stepper buttons work
             *
             * @private
             */
            _initContent: function () {
                this._super();

                var self = this,
                    stepInterval = 1,
                    events = {};

                /**
                 * @param {jQuery.Event} event
                 */
                events['click :button.cart-item-qty-decrement'] = function (event) {
                    event.stopPropagation();

                    let button = $(event.currentTarget);
                    let itemId = button.data('cart-item');
                    let $element = $('#cart-item-' + itemId + '-qty');
                    let currentValue = parseInt($element.val());

                    if (currentValue <= 0) {
                        return false;
                    }

                    let newValue = currentValue - stepInterval;
                    $element.val(newValue);
                    $element.trigger('change');
                };

                /**
                 * @param {jQuery.Event} event
                 */
                events['click :button.cart-item-qty-increment'] = function (event) {
                    event.stopPropagation();

                    let button = $(event.currentTarget);
                    let itemId = button.data('cart-item');
                    let $element = $('#cart-item-' + itemId + '-qty');
                    let currentValue = parseInt($element.val());

                    let newValue = currentValue + stepInterval;
                    $element.val(newValue);
                    $element.trigger('change');
                };

                this._on(this.element, events);
            }
        });

        return $.mage.sidebar;
    }
});
