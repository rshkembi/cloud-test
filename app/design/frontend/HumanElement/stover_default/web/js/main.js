define([
    'jquery',
    'domReady!',
], function ($) {
    'use strict';

    var helpers = {
        domReady: function () {
            $('div.minisearch.button').click(
                function (){
                    $('.form.minisearch').toggleClass('active');
                    $('.field.search .label').toggleClass('active');
                    $('.header-search').toggleClass('active');
                    $('.header.content').toggleClass('search-active');
                    $('.ammenu-main-container').toggleClass('search-active');
                    $(this).toggleClass('active');
                }
            );

            $('.minisearch button.search').on('click', function (){
                    if ($('.minisearch.button').hasClass('active')) {
                        $('.minisearch.button').removeClass('active');
                    }
                    if ($('.header-search').hasClass('active')) {
                        $('.header-search').removeClass('active');
                    }
                }
            );

            $('.ammenu-button.-hamburger').click(function (){
                $(this).toggleClass('active');
            });
        }
    }

    helpers.domReady();
});
