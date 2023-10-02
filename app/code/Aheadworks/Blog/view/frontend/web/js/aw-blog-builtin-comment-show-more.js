define([
    'jquery'
], function ($) {
    'use strict';

    $.widget('mage.awBlogBuiltinCommentShowMore', {
        options: {
            buttonSelector: '#aw-blog-builtin-comment-show-more',
            commentRootListSelector: '#comment-listing',
            commentReplyListSelector: '.comment-reply-list',
            commentIdSelector: '#comment-item-',
            errorMessagesSelector: '#aw-blog-post-comment-error-massages',
            direction: null,
            currentPage: 1,
            postId: null,
            parentCommentId: null,
            lastPage: 1,
            url: ''
        },

        /**
         * Initialize widget
         */
        _create: function() {
            this._initEventHandlers();
        },

        /**
         * hide button
         */
        _hideButton: function() {
            $(this.element).parent('div').addClass('no-display');
        },

        /**
         * Init event handlers
         *
         * @private
         */
        _initEventHandlers: function () {
            var me = this;
            this.element.on('click', function () {
                $.ajax({
                    url: me.options.url,
                    type: 'get',
                    async: true,
                    data: {
                        currentPage: ++me.options.currentPage,
                        post_id: me.options.postId,
                        parentCommentId: me.options.parentCommentId,
                        direction: me.options.direction
                    },

                    beforeSend: function () {
                        $('body').trigger('processStart');
                    },

                    success: function (response) {
                        if (response) {
                            response = $(response);
                            response.applyBindings();
                            var selector = me.options.parentCommentId
                                ? '#comment-item-' + me.options.parentCommentId + ' .comment-reply-list'
                                : me.options.commentRootListSelector;
                            $(selector).append(response);
                            $(selector).trigger('contentUpdated');
                            me._hideButton();
                            $("body").trigger('processStop');
                        }
                    },

                    error: function (jqXHR, status, error) {
                        $(me.options.errorMessagesSelector).append($.mage.__('Sorry, something went wrong. Please try again later.'));
                    },

                    complete: function () {
                        $('body').trigger('processStop');
                    }
                });
            });
        }
    });

    return $.mage.awBlogBuiltinCommentShowMore;
});
