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
                if($(this).hasClass('active')){
                    $(this).removeClass('active');
                }

                if(!$(this).hasClass('active')){
                    $(this).addClass('active');
                }
            });
        }
    }

    helpers.domReady();
});