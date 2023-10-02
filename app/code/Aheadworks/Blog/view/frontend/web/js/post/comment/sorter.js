define([
    'jquery'
], function ($) {
    'use strict';

    $.widget('mage.awBlogBuiltinCommentSorter', {
        options: {
            commentListSelector: '.aw-blog-comment-list',
            buttonSelector: '#aw-blog-comment-sorter',
            errorMessagesSelector: '#sorter-error-massages',
            currentPage: 1,
            direction: null,
            url: ''
        },

        /**
         * Initialize widget
         */
        _create: function () {
            this.bindButton();
        },

        /**
         * Send ajax
         */
        _sendAjax: function (direction) {
            var me = this;
            $.ajax({
                url: me.options.url,
                type: 'get',
                async: true,
                data: {
                    post_id: me.options.postId,
                    currentPage: me.options.currentPage,
                    direction: direction
                },

                beforeSend: function () {
                    $('body').trigger('processStart');
                },

                success: function (response) {
                    if (response) {
                        response = $(response);
                        $(me.options.commentListSelector).empty().append(response);
                        $('#comment-listing').applyBindings();
                        $('body').trigger('contentUpdated')
                    }
                },

                error: function (jqXHR, status, error) {
                    $(me.options.errorMessagesSelector).append($.mage.__('Sorry, something went wrong. Please try again later.'));
                },

                complete: function () {
                    $('body').trigger('processStop');
                }
            });

            return true;
        },

        /**
         * Action on click
         */
        onClick: function (event) {
            var me = this;
            if ($(event.currentTarget).hasClass('active')) {
                me._sendAjax('DESC');
                $(event.currentTarget).removeClass('active');
            }
            else {
                me._sendAjax('ASC');
                $(event.currentTarget).addClass('active');
            }
        },

        /**
         * Bind button
         */
        bindButton: function () {
            $(this.options.buttonSelector).on('click', this.onClick.bind(this));
        }
    });

    return $.mage.awBlogBuiltinCommentSorter;
});
