define(function () {
    'use strict';

    var mixin = {
        /**
         * Add error message.
         *
         * @param {Object} message
         * @return {*|Boolean}
         */
        addErrorMessage: function (message) {
            // Scroll to the top of the page to ensure the message will be seen.
            window.scrollTo({ top: 0, behavior: 'smooth' });

            return this.add(message, this.errorMessages);
        }
    };

    return function (target) {
        return target.extend(mixin);
    };
});
