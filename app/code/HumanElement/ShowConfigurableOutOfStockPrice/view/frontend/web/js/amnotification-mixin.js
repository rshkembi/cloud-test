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
                        self.parent.find('.amxnotif-block').remove();
                    }
                }
            );
            return $.mage.amnotification;
        };
    }
);
