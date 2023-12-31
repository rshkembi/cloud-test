define([
    'jquery',
    'underscore'
], function ($, _) {
    'use strict';

    var buttons = {
        'reset': '#reset',
        'save': '#save',
        'update': '#update',
        'schedule': '#schedule',
        'publish': '#publish',
        'saveAsDraft': '#save_as_draft',
        'saveAsDraftAndDuplicate': '#save_as_draft_and_duplicate',
        'saveAndContinue': '#save_and_continue',
        'preview': '#preview'
    };

    /**
     * Initialize listener
     * @param {Function} callback
     * @param {String} action
     */
    function initListener(callback, action) {
        var selector = buttons[action],
            element = $(selector)[0];

        if (element) {
            if (element.onclick) {
                element.onclick = null;
            }
            $(element).off().on('click', callback);
        }
    }

    return {
        /**
         * Calls callback when name event is triggered
         * @param  {Object} handlers
         */
        on: function (handlers) {
            _.each(handlers, initListener);
        }
    };
});
