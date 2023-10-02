define([
    './url',
    'uiRegistry'
], function (Url, registry) {
    'use strict';

    return Url.extend({
        defaults: {
            listens: {
                selectedPostData: 'processSelectedPostData'
            },
            postIdFormFieldQuery: '${ $.ns }.${ $.ns }.comment_details.post_id',
            selectPostFormFieldQuery: '${ $.ns }.${ $.ns }.comment_details.select_post',
            isNeedToHideSelectPostButton: true,

            links: {
                selectedPostData: '${ $.provider }:data.post_selected'
            }
        },

        /**
         * @inheritDoc
         */
        initObservable: function () {
            this._super();
            this.observe(
                [
                    'selectedPostData'
                ]
            );
            return this;
        },

        /**
         * Process selected post data
         */
        processSelectedPostData: function() {
            this.updatePostIdValue();
            if (this.isNeedToHideSelectPostButton && this.getCurrentSelectedPostData().name_url) {
                this.hideSelectPostButton();
                this.updatePostName();
                this.updatePostUrl();
                this.visible(true);
            }
        },

        /**
         * Update post name
         */
        updatePostName: function() {
            this.urlLabel(this.getCurrentSelectedPostData().title);
        },

        /**
         * Update post url
         */
        updatePostUrl: function() {
            this.urlValue(this.getCurrentSelectedPostData().name_url);
        },

        /**
         * Set post_id value to hidden field
         */
        updatePostIdValue: function() {
            var newPostId = this.getCurrentSelectedPostData().id;

            if (this.postIdFormFieldQuery) {
                var postIdField = registry.get(this.postIdFormFieldQuery);

                postIdField.value(newPostId);
            } else {
                this.value(newPostId);
            }
        },

        /**
         * Hide "Select Post" button
         */
        hideSelectPostButton: function() {
            var selectPostField = registry.get(this.selectPostFormFieldQuery);

            selectPostField.hide();
        },

        /**
         * Get current selected post data
         * @returns {Array}
         */
        getCurrentSelectedPostData: function() {
            var preparedData = [];

            if (this.selectedPostData().length) {
                preparedData = this.selectedPostData()[0];
            }
            return preparedData;
        }
    });
});
