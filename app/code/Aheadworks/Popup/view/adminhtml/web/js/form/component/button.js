define([
    'jquery',
    'button'
], function ($, button) {
    'use strict';

    $.widget('mage.awPopupButton', button, {
        _click: function () {
            var form = $('#edit_form');
            var self = this;
            if(form.validation('isValid') !== false){
                $('body').trigger('processStart');
                if (this.options.isSlowLoadEnable) {
                    setTimeout(this._super.bind(this), 12000);
                } else {
                    this._super();
                }
            }
        }
    });

    return $.mage.awPopupButton;
});
