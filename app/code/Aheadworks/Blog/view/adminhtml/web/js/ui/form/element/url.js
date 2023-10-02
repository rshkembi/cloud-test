define([
    'Magento_Ui/js/form/element/abstract'
], function (Abstract) {
    'use strict';

    return Abstract.extend({
        defaults: {
            elementTmpl: 'Aheadworks_Blog/ui/form/element/url',
            urlLabel: '',
            urlValue: '',
            links: {
                urlLabel: '${ $.provider }:${ $.dataScope }_label',
                urlValue: '${ $.provider }:${ $.dataScope }_url'
            }
        },

        /**
         * @inheritDoc
         */
        initObservable: function () {
            this._super();
            this.observe(
                [
                    'urlLabel',
                    'urlValue'
                ]
            );
            return this;
        }
    });
});
