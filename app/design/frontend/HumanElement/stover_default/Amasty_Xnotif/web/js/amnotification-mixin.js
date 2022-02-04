/**
 * Copyright © Human-Element, Inc. All rights reserved.
 */
define(
    [
        'jquery'
    ], function ($) {
        'use strict';
        return function (widget) {
            $.widget(
                'mage.amnotification', widget, {
                    _initialization: function () {
                        this._super();
                        var self = this;
                        $.each(self.options.xnotif, function( option_id, product ) {
                            if (product.is_in_stock == '0') {
                                self.parent.find("div[data-option-id='" + option_id + "']").addClass('attr-out-of-stock');
                            }
                        });
                    }
                }
            );
            return $.mage.amnotification;
        };
    }
);
