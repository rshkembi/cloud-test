define([
    'jquery',
    'mage/template'
], function ($, template) {
    'use strict';

    return function (config, element) {
        let $element = $(element),
            stepInterval = parseInt(config.step_interval),
            decrementTemplate = template('#qty-decrement-template'),
            incrementTemplate = template('#qty-increment-template');

        let decrementElement = decrementTemplate({
            data: {}
        });
        let incrementElement = incrementTemplate({
            data: {}
        });

        let $increment = $(incrementElement).insertAfter($element);
        let $decrement = $(decrementElement).insertBefore($element);

        $decrement.on('click', function () {
            let currentValue = parseInt($element.val());

            if (currentValue <= 0) {
                return false;
            }

            let newValue = parseInt($element.val()) - stepInterval;
            $element.val(newValue);
        });

        $increment.on('click', function () {
            let newValue = parseInt($element.val()) + stepInterval;
            $element.val(newValue);
        });
    }
});
