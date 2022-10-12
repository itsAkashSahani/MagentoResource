define(['mage/translate', "jquery"], function($t, $) {
    'use strict';

    return function(rules) {
        rules['arabicletters'] = {
            handler: function (value) {
                console.log(value);
                return /^[a-zA-Z \u0600-\u06ff]+$/i.test(value); 
            },
            message: $t('Please enter valid name.')
        };
        return rules;
    };
});

