define([
    'jquery',
    'underscore',
    'uiComponent',
    'ko',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/step-navigator',
    'Magento_Checkout/js/model/payment-service',
    'Magento_Checkout/js/model/payment/method-converter',
    'Magento_Checkout/js/action/get-payment-information',
    'Magento_Checkout/js/model/checkout-data-resolver',
    'Magento_Checkout/js/action/get-totals',
    'mage/translate',
    'mage/url',
    'Magento_Catalog/js/price-utils',
    'Magento_Checkout/js/model/totals',
    'Magento_Checkout/js/model/cart/totals-processor/default',
    'Magento_Customer/js/model/customer' 
    ], function (
    $,
    _,
    Component,
    ko,
    quote,
    stepNavigator,
    paymentService,
    methodConverter,
    getPaymentInformation,
    checkoutDataResolver,
    getTotalsAction,
    $t,
    url,
    priceUtils,
    totals,
    totalsDefaultProvider,
    customer
) { 
    'use strict';
     
    return Component.extend({
         /**
         * Check if customer is logged in
         *
         * @return {boolean}
         */
        isLoggedInnot: function () {
            return customer.isLoggedIn();
        },
        // isCustomerLoggedIn: customer.isLoggedIn(), /*return  boolean true/false */
        initialize: function () {
                $(function() {
         
                $("button.action.proceed-payment.proceed-payment-main.primary").hide();
                var linkUrl = url.build('customer/index/loyalty');
                    var isLoggedIn = customer.isLoggedIn();
                    var monumber = customer.customerData.custom_attributes['mobilenumber'];
                    var mobilenumber='';
                    if(monumber){
                        
                      var mobilenumber =customer.customerData.custom_attributes['mobilenumber'].value;
                       //alert(mobilenumber);
                       var selfpoint = '';
                       console.log("mobilenumber====>"+mobilenumber);
                        $.ajax({
                                showLoader: false,
                                url: linkUrl,
                                data: {"getbalance":mobilenumber},
                                type: "POST",
                                dataType: 'json' 
                            }).done(function (response) {
                                    $("#blanece-point").empty();
                                    $("#blanece-point").append(response.success);
                                    var selfpoint = response.success;
                                    localStorage.setItem("selfpoint", selfpoint);
                            });

                            var handle = setInterval(function () {
                            var url = window.location.href;
                            var result = url.split('#');
                                if(result[1] == 'payment')
                                {   
                                    selfpoint = localStorage.getItem("selfpoint");
                                    $("#blanece-point").empty();
                                    $("#blanece-point").append(selfpoint);
                                    $("#blanece-point").css("margin-left","auto");
                                    $("#blanece-point").css("font-weight","400");
                                    $("#blanece-point").css("font-size","initial");
                                    if($('#blanece-point').text().length > 0) {  
                                        clearInterval(handle);
                                    }
                                    //localStorage.setItem("selfpoint","");
                                    
                                }
                            }, 5000);

                            $(".loyalty-points").append(selfpoint);
                            $("#loyalty-point-custom").attr("width",selfpoint);
                            // $("#blanece-point").append(response.success);
                    }

                 $('body').on("click", 'input#loyalty-point-custom', function () {
                    var isLoggedIn = customer.isLoggedIn();
                    var monumber = customer.customerData.custom_attributes['mobilenumber'];
                    var mobilenumber='';
                    if(!monumber){
                       alert("please add mobilenumber");
                       return;
                    }else{
                        var mobilenumber =customer.customerData.custom_attributes['mobilenumber'].value;
                    }
                    //console.log(mobilenumber);
                    var linkUrl = url.build('customer/index/loyalty');
                        if($(this).prop("checked") == true){
                             $.ajax({
                                showLoader: true,
                                url: linkUrl,
                                data: {"mobilenumber":mobilenumber,fee:"1"},
                                type: "POST",
                                dataType: 'json'
                            }).done(function (response) {
                                //console.log(data);
                                //console.log("data");
                                var deferred = $.Deferred();
                                    getTotalsAction([], deferred);
                                  // jQuery("input#payfort_fort_cc").click();
                            });
                        }else{
                             $.ajax({
                                showLoader: true,
                                url: linkUrl,
                                data: {"mobilenumber":mobilenumber,fee:"0"},
                                type: "POST",
                                dataType: 'json'
                            }).done(function (response) { 
                                var deferred = $.Deferred();
                                getTotalsAction([], deferred);   
                                //jQuery("input#payfort_fort_cc").click();                                 
                            });
                            
                        }
                        $("input#payfort_fort_cc").prop('checked', false);
                        jQuery("input#payfort_fort_cc").click();
                    });
            
                });
                var self = this;
                this._super();
            }
    
    });
    
});