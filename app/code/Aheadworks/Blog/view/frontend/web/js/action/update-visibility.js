define([], function () {
    'use strict';

    var animationMethodsMap = {
        'show': 'slideDown',
        'hide': 'slideUp',
        'toggle-visibility': 'slideToggle'
    };

    return function (target, animationType, animationDuration, elementToFocusOnComplete) {
        var animationMethod = animationMethodsMap[animationType];

        if (animationMethod) {
            target[animationMethod](
                animationDuration,
                function ()
                {
                    if (elementToFocusOnComplete
                        && target.is(':visible')
                    ) {
                        elementToFocusOnComplete.focus();
                    }
                }.bind({ target: target, elementToFocusOnComplete: elementToFocusOnComplete})
            );
            return true;
        }
        return false;
    };
});
