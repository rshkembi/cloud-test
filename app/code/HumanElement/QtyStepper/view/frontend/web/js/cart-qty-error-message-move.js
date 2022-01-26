define([
    'jquery',
    'mage/validation'
    ], function ($) {
    'use strict';

    let dataForm = $('#form-validate');
    dataForm.mage('validation', {
        errorPlacement: function(error, element) {
            let errorActionId = element.data('action');
            let targetId = element.data('target');
            if (errorActionId === 'qty') {
                error.appendTo(targetId);
            } else {
                element.after(error);
            }
        },
    });
});
