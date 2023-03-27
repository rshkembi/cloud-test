define([
    'mage/utils/wrapper',
    'jquery'
],function (wrapper, $) {
    'use strict';

    return function (config) {
        config.reloadConfig = wrapper.wrapSuper(config.reloadConfig, function (callback, carrier) {
            var self = this,
                selecteddate = '';

            if ($('#shipperhq_calendar').length > 0) {
                selecteddate = $('#shipperhq_calendar').val();
            }
            $.ajax({
                type: 'GET',
                url: self.loadConfigUrl,
                showLoader: true,
                data: {
                    'carrier': carrier,
                    'date_selected': selecteddate
                },
                context: $('body'),
                success: function (data) {
                    if (data.config) {
                        self.updateConfig(data.config.shipperhq_pickup);
                    }
                    callback();
                }
            });
        });

        return config;
    };
});
