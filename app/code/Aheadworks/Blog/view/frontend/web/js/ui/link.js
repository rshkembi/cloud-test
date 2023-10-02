define([
    'uiComponent'
], function (Component) {
    'use strict';

    return Component.extend({

        defaults: {
            template: 'Aheadworks_Blog/ui/link',
            linkCss: '',
            url: '',
            label: ''
        },

        /**
         * Retrieve link css
         *
         * @returns {String}
         */
        getLinkCss: function() {
            return this.linkCss;
        },

        /**
         * Retrieve link label
         *
         * @returns {String}
         */
        getLabel: function() {
            return this.label;
        },

        /**
         * Retrieve link url
         *
         * @returns {String}
         */
        getUrl: function() {
            return this.url;
        }
    });
});
