define([
    'jquery',
    'underscore',
    'uiComponent',
    'Magento_Customer/js/customer-data'
], function ($, _, Component, customerData) {
    'use strict';

    return Component.extend({
        default: {
            productIds: [],
        },

        initialize: function () {
            this._super();
            this.initWishlistProduct();
        },

        initObservable: function () {
            return this._super().observe(['productIds']);
        },

        initWishlistProduct: function () {
            this.wishlist = customerData.get('wishlist');

            this.wishlist.subscribe(function (value) {
                this.productIds(value.wishlist_ids)
            }, this);
            this.productIds(this.wishlist().wishlist_ids);
        },

        beforeSendAjaxEvent: function (ajaxWishlist, event) {
            $(event.currentTarget).addClass('loading');
        },

        completeAjaxEvent: function (ajaxWishlist, event) {
            $(event.currentTarget).removeClass('loading');
        },

        getProductIds: function () {
            return this.productIds();
        },

        added: function (productId) {
            return _.contains(this.productIds(), productId.toString());
        },

        addToWishlist: function (element, event) {
            var self   = this,
            post   = $(element).data('post'),
            url    = post.action,
            data   = $.extend(post.data, {form_key: $('input[name="form_key"]').val()});

            $.ajax(url, {
                type: 'POST',
                data: data,
                showLoader: true,
                success: function (response) {
                    console.log("Successfully Added");
                    // var messages = $.cookieStorage.get('mage-messages');
                    // console.log(messages);
                    // // if (!_.isEmpty(messages)) {
                    // //     customerData.set('messages', {messages: messages});
                    // //     $.cookieStorage.set('mage-messages', '');
                    // // }
                    // var messages_section = ['messages'];
                    // customerData.invalidate(messages_section);
                    // customerData.reload(messages_section);
                },
            })

            event.stopPropagation();
            event.preventDefault();
        }
    });
});