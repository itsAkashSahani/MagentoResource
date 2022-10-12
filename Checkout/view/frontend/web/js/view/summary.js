define(
    [
        'jquery',
        'ko',
        'Magento_Checkout/js/view/summary',
        'Magento_Checkout/js/model/step-navigator',
        'Magento_Checkout/js/checkout-data'
    ],
    function(
        $,
        ko,
        Component,
        stepNavigator,
        checkoutData
    ) {
        'use strict';

        return Component.extend({

            isVisible: function () {
                return stepNavigator.isProcessed('shipping');
            },
            initialize: function () {
                $(function() {
                    $('body').on("click", '#place-order-trigger', function () {
                        $(".payment-method._active").find('.action.primary.checkout').trigger( 'click' );
                    });

                    // $('body').on("click", '#checkout-payment-method-load [name="payment[method]"]', function () {
                    //     $('#place-order-trigger').removeClass('disabled')
                    // });

                    $(document).ready(function(){
                        if(checkoutData.getSelectedPaymentMethod()) {
                            console.log(checkoutData.getSelectedPaymentMethod());
                            $('#place-order-trigger').removeClass('disabled')
                        }
                    })
                });

                var self = this;
                this._super();
            },
            isPaymentSelected: function () {
                if(checkoutData.getSelectedPaymentMethod() != null) {
                    return true;
                }

                return false;
            }

        });
    }
);