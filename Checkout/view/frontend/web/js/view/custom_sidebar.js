define([
    'jquery',
    'ko',
    'Magento_Checkout/js/model/totals',
    'uiComponent',
    'Magento_Checkout/js/model/step-navigator',
    'Magento_Checkout/js/model/quote',
    'Magento_Catalog/js/price-utils',
    'mage/url',
    'Magento_Checkout/js/action/get-totals',
    'Magento_InventoryInStorePickupFrontend/js/model/pickup-locations-service',
    'Albatool_Checkout/js/view/store-selector'
], function ($,ko, totals, Component, stepNavigator, quote,priceUtils,url, getTotalsAction, pickupLocationsService, storeSelectore) {
    'use strict';

    var useQty = window.checkoutConfig.useQty;
    var imageData = window.checkoutConfig.imageData;

    return Component.extend({
        defaults: {
            template: 'Magento_Checkout/summary/cart-items'
        },
        totals: totals.totals(),
        items: ko.observable([]),
        isPickup: ko.observable(false),
        maxCartItemsToDisplay: window.checkoutConfig.maxCartItemsToDisplay,
        cartUrl: window.checkoutConfig.cartUrl,
        imageData: imageData,

        /**
         * @deprecated Please use observable property (this.items())
         */
        getItems: totals.getItems(),

        /**
         * Returns cart items qty
         *
         * @returns {Number}
         */
        getItemsQty: function () {
            return parseFloat(this.totals['items_qty']);
        },

        /**
         * Returns count of cart line items
         *
         * @returns {Number}
         */
        getCartLineItemsCount: function () {
            return parseInt(totals.getItems()().length, 10);
        },

        /**
         * Returns shopping cart items summary (includes config settings)
         *
         * @returns {Number}
         */
        getCartSummaryItemsCount: function () {
            return useQty ? this.getItemsQty() : this.getCartLineItemsCount();
        },

        /**
         * @inheritdoc
         */
        initialize: function () {
            this._super();
            // Set initial items to observable field
            this.setItems(totals.getItems()());
            // Subscribe for items data changes and refresh items in view
            totals.getItems().subscribe(function (items) {
                _.each(items, function (item) {
                    item.available_home_delivery = 1;
                    item.quote_item_sku = true;
                });
                this.setItems(items);
            }.bind(this));
        },

        /**
         * Set items to observable field
         *
         * @param {Object} items
         */
        setItems: function (items) {
            if (items && items.length > 0) {
                jQuery('.proceed-payment').attr('disabled',false);
                items = items.slice(parseInt(-this.maxCartItemsToDisplay, 10));
                var sel_pickup_vals = sessionStorage.getItem("pickup_sele_sku");
                if(sel_pickup_vals){
                    //alert("EEEEE"+sel_pickup_vals);
                    var sel_pickup_arr = [];
                    sel_pickup_arr = sel_pickup_vals;
                    sel_pickup_arr = sel_pickup_arr.split(",");
                    console.log(sel_pickup_arr);
                    _.each(items, function (item) {
                        item.available_home_delivery = 1;
                        //console.log(item.extension_attributes['sku']);
                        if(jQuery.inArray(item.extension_attributes['sku'], sel_pickup_arr) !== -1){
                            item.quote_item_sku = true;
                        }
                        else{
                            item.quote_item_sku = false;
                            jQuery('.proceed-payment').attr('disabled',true);
                        }
                    });
                    sessionStorage.setItem("pickup_sele_sku", "");  
                }
            }
            console.log(items);
            this.items(items);
        },

        /**
         * Returns bool value for custom sidebar show only shipping step
         *
         * @returns {*|Boolean}
         */
        showCustomSummary: function () {
            if(stepNavigator.getActiveItemIndex() == 0){
                return true;
            }
            return false;
        },
        /**
         * @param {Object} item
         * @return {Array}
         */
        getImageItem: function (item) {
            if (this.imageData[item['item_id']]) {
                return this.imageData[item['item_id']];
            }

            return [];
        },

        /**
         * @param {Object} item
         * @return {null}
         */
        getSrc: function (item) {
            if (this.imageData[item['item_id']]) {
                return this.imageData[item['item_id']].src;
            }

            return null;
        },

        /**
         * @param {Object} item
         * @return {null}
         */
        getWidth: function (item) {
            if (this.imageData[item['item_id']]) {
                return this.imageData[item['item_id']].width;
            }

            return null;
        },

        /**
         * @param {Object} item
         * @return {null}
         */
        getHeight: function (item) {
            if (this.imageData[item['item_id']]) {
                return this.imageData[item['item_id']].height;
            }

            return null;
        },

        /**
         * @param {Object} item
         * @return {null}
         */
        getAlt: function (item) {
            if (this.imageData[item['item_id']]) {
                return this.imageData[item['item_id']].alt;
            }

            return null;
        },
        
         /**
         * Formats the price according to store
         *
         * @param {number} Price to be formatted
         * @return {string} Returns the formatted price
         */
        getFormattedPrice: function (price) {
            return priceUtils.formatPrice(price, quote.getPriceFormat());
        },

        getBaseUrl: function(itemId){
            return url.build('adcheckout/index/remove/id/'+ itemId);
        },

        editQtyFun: function (itemId, itemQty, itemOldprice, itemSpecial, itemPrice) {
            //console.log("thisssssisII::"+itemId+"::::"+itemQty);
            var self = this;
            
                var body = $('body').loader();
                body.loader('show');
                var sel_pickup_name = sessionStorage.getItem("pickup_sele_store");
                if(sel_pickup_name){
                    sel_pickup_name = sel_pickup_name;
                }
                else
                {
                    sel_pickup_name = "";
                }
                sessionStorage.setItem("pickup_sele_store", "");
                jQuery.ajax({
                    url: url.build('customer/index/editQty'),
                    type: 'post',
                    data: {itemId:itemId, itemQty:itemQty, sel_pickup_name:sel_pickup_name},
                    success: function(response) {
                        //console.log(response);
                        body.loader('hide');
                        if(response.message == 'requested quantity not available'){
                            $('#item-qty-not-available_'+itemId).html(response.message);
                            jQuery('.proceed-payment-main').attr('disabled',true);
                            $('#item-qty-not-available_'+itemId).show().delay(5000).fadeOut();
                            $('#item-qty-not-available_'+itemId).css('color','red');
                        }
                        else{
                            $('#item-qty-not-available_'+itemId).html("requested quantity updated");
                            jQuery('.proceed-payment-main').attr('disabled',false);
                            $('#item-qty-not-available_'+itemId).show().delay(5000).fadeOut();
                            $('#item-qty-not-available_'+itemId).css('color','green');
                            $(".old-price-"+itemId).hide();
                            //$(".special-price-"+itemId).hide();
                            $(".discount_price").hide();
                            $(".discount_price-"+itemId).html(priceUtils.formatPrice(itemOldprice*itemQty, quote.getPriceFormat()));
                            $('#item-old-price-val-'+itemId).html(priceUtils.formatPrice(itemOldprice*itemQty, quote.getPriceFormat()));
                            $('#item-special-price-val-'+itemId).html(priceUtils.formatPrice(itemSpecial*itemQty, quote.getPriceFormat()));
                            $('#item-normal-price-val-'+itemId).html(priceUtils.formatPrice(itemPrice*itemQty, quote.getPriceFormat()));
                        }
                    },
                    error: function () {
                        console.log("error here");
                        return false;
                    }
                });
            
        },

        diableContinue: function(value){
            var checkExist = setInterval(function() {
                if ($('.proceed-payment-main').length) {
                   console.log("Exists!");
                   jQuery('.proceed-payment-main').attr('disabled',true) 
                   clearInterval(checkExist);
                }
             }, 100);            
        },

        customDeleteProduct: function(id){
            var self = this;
            $('#cancel-item-'+id).click(function(){
                const deleteUrl = url.build('adcheckout/index/customremove/id/'+ id);
                $.ajax({
                    url: deleteUrl,
                    showLoader: true,
                    success: function(response){
                        if(response.status == 'true') {
                            pickupLocationsService.selectedLocation(null);
                            /*var deferred = $.Deferred();
                            getTotalsAction([], deferred);
                            var temp = storeSelectore([], deferred);*/
                            setTimeout(function(){
                                if (!!$.cookie('selected-city')) {
                                    var city = $.cookie('selected-city');
                                    var searchQuery = storeSelectore().getSearchTerm(city, 'SA');
                                    storeSelectore().updateNearbyLocations(searchQuery);
                                    console.log(searchQuery);
                                    $("button.action.action-select-store-pickup").addClass("selected");
                                    $("button.action.action-select-shipping").removeClass("selected");
                                    $('.city-option').val(city);
                                    $('.city-option').trigger('change');
                                    $.cookie('sele_store_pickup_cookie', 'store_sel_pickup', {expires: 86400});
                                    location.reload();
                                }
                            }, 2000)

                            setTimeout(function(){
                                if(!!$.cookie('selected-city') && !!$.cookie('selected-store')) {
                                    var store = $.cookie('selected-store');
                                    $('div.location button[data-storecode="'+ store +'"]').trigger('click'); 
                                }
                            }, 1000);
                        }
                    }
                })
            })
        },

        // isStorePickupSelected: function(){
        //     var self = this;
        //     console.log('HELLO');

        //     $('.delivery-method').click(function(){
        //         console.log('HELLO');
        //         if(quote.shippingMethod().carrier_code == 'instore') {
        //             self.isPickup(true);
        //         } else {
        //             self.isPickup(false);
        //         }
        //     })

        //     return self.isPickup();
        // }
    });
});