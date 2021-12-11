define([
    'jquery',
    'domReady!',
], function ($) {
    'use strict';

    var helpers = {
        domReady: function () {
            $('.minisearch.button').click(
                function (){
                    $('.form.minisearch').toggleClass('active');
                    $('.field.search .label').toggleClass('active');
                    $('.header-search').toggleClass('active');
                    $(this).toggleClass('active');
                }
            );

            $('.ammenu-button.-hamburger').click(function (){
                $(this).toggleClass('active');
            });
        }
    }

    helpers.domReady();
});