define([
    'jquery',
    'jquery-ui-modules/widget'
], function ($) {
    'use strict';

    $.widget('mage.productReview', {

        /** @inheritdoc */
        _create: function () {
            $(".yotpo.bottomLine").on('click' , function() {
                $("#tab-label-yotpo_widget_div").trigger('click');
            });
            $(".yotpo-review").on('click' , function() {
                $("#tab-label-yotpo_widget_div").trigger('click');
            });
        }
    });

    return $.mage.productReview;
});
