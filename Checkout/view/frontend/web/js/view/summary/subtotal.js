/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'Magento_Checkout/js/view/summary/abstract-total',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/totals',
    'mage/translate'
], function (Component, quote, totals, $t) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Magento_Checkout/summary/subtotal'
        },

        /**
         * Get pure value.
         *
         * @return {*}
         */
        getPureValue: function () {
            var totals = quote.getTotals()();

            if (totals) {
                return totals.subtotal;
            }

            return quote.subtotal;
        },

        /**
         * @return {*|String}
         */
        getValue: function () {
            //return this.getFormattedPrice(this.getPureValue());
            return this.getFormattedPrice(totals.totals()['subtotal_incl_tax']);
            /*if (totals.totals()) {
                var subTotal = parseFloat(totals.totals()['subtotal']);
                var taxSegment = totals.getSegment('tax').value;
                var subTotalNew = (subTotal+taxSegment)
                return this.getFormattedPrice(subTotalNew);
            }*/
        },

        getTitle: function () {
            return $t('Subtotal');
        },

        getProductCounts: function () {
            var totals = window.checkoutConfig.totalsData;
            var giftQty = totals.giftCount;
            var itemQty = totals.items_qty - giftQty;
            var prod = $t('product');
            var prods = $t('products');
            var gift = $t('gift');
            var gifts = $t('gifts');
            
            var title = '(';

            if(giftQty > 0) {
                (itemQty == 1) ? title +=  itemQty + " "+ prod + "+ " : title +=  itemQty + " " + prods +"+ ";
                (giftQty == 1) ? title +=  giftQty + " " + gift+")" : title +=  giftQty + " " + gifts+")";
            }
            else {
                (itemQty == 1) ? title +=  itemQty + " "+ prod +")" : title +=  itemQty + " "+ prods+")";
            }

            return title;
        }

    });
});
