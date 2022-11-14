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
            $("a[href=#tab-label-yotpo_widget_div]").on('click' , function() {
                $("#tab-label-yotpo_widget_div").trigger('click');
            });
        }
    });

    return $.mage.productReview;
});
