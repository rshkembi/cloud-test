define([
    'mage/utils/wrapper',
    'jquery'
],function (wrapper, $) {
    'use strict';

    return function (config) {
        config.reloadConfig = wrapper.wrapSuper(config.reloadConfig, function (callback, carrier) {
            var self = this;
            $.ajax({
                type: 'GET',
                url: self.loadConfigUrl,
                data: {
                    'carrier': carrier
                },
                context: $('body'),
                success: function (data) {
                    if (data.config) {
                        if (typeof data.config.shipperhq_option !== 'undefined') {
                            self.updateConfig(data.config.shipperhq_option);
                        }
                    }
                    callback();
                }
            });
        });

        return config;
    };
});
