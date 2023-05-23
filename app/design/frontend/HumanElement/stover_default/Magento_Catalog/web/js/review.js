define([
    'jquery',
    'jquery-ui-modules/widget'
], function ($) {
    'use strict';

    $.widget('mage.productReview', {

        /** @inheritdoc */
        _create: function () {
            $('a[href="#tab-label-yotpo_widget_div"]').on('click' , function() {
                $('.yotpoBottomLine .bottomLine').trigger('click');
            });
        }
    });

    return $.mage.productReview;
});
