var config = {

	'mixins': {
	   'Magento_Checkout/js/view/shipping': {
		   'Albatool_Checkout/js/view/shipping-payment-mixin': true
	   },
	   'Magento_Checkout/js/view/payment': {
		   'Albatool_Checkout/js/view/shipping-payment-mixin': true
	   },
	   'Magento_Checkout/js/action/set-shipping-information': {
                'Albatool_Checkout/js/action/set-shipping-information-mixin': true
        }
	},

    config: {
        mixins: {
            'Magento_Ui/js/lib/validation/rules': {
                'Albatool_Checkout/js/arabiclettersValidation': true
            }
        }
    },

    map: {
        '*': {
            'Magento_Checkout/js/model/checkout-data-resolver': 'Albatool_Checkout/js/model/checkout-data-resolver',
			'Magento_Checkout/js/view/shipping': 'Albatool_Checkout/js/view/shipping',
			'Magento_Checkout/js/view/progress-bar': 'Albatool_Checkout/js/view/progress-bar',
			'Magento_Checkout/js/view/shipping-address/list': 'Albatool_Checkout/js/view/shipping-address/list',
			'Magento_Checkout/js/view/minicart': 'Albatool_Checkout/js/view/minicart',
			'Magento_Checkout/js/model/customer-email-validator': 'Albatool_Checkout/js/model/customer-email-validator',
			'Magento_InventoryInStorePickupFrontend/js/view/form/element/email': 'Albatool_Checkout/js/view/form/element/email',
			'Magento_InventoryInStorePickupFrontend/js/view/store-selector': 'Albatool_Checkout/js/view/store-selector',
			'Magento_InventoryInStorePickupFrontend/js/model/resource-url-manager': 'Albatool_Checkout/js/model/resource-url-manager',
			'Magento_InventoryInStorePickupFrontend/js/model/pickup-locations-service': 'Albatool_Checkout/js/model/pickup-locations-service',
			'Magento_Checkout/js/view/sidebar': 'Albatool_Checkout/js/view/sidebar'
		}

    },
	paths: {
        'Magento_Checkout/template/shipping': 'Albatool_Checkout/template/shipping',
		'Magento_Checkout/template/progress-bar': 'Albatool_Checkout/template/view/progress-bar',
		'Magento_Checkout/template/shipping-address/list': 'Albatool_Checkout/template/shipping-address/list',
		'Magento_Checkout/template/shipping-address/form': 'Albatool_Checkout/template/shipping-address/form',
		'Magento_InventoryInStorePickupFrontend/template/store-selector/popup-item': 'Albatool_Checkout/template/store-selector/popup-item',
		'Magento_InventoryInStorePickupFrontend/template/store-selector': 'Albatool_Checkout/template/store-selector',
		'Magento_InventoryInStorePickupFrontend/template/form/element/email': 'Albatool_Checkout/template/form/element/email',
		'Magento_Checkout/template/sidebar': 'Albatool_Checkout/template/sidebar',
		'Magento_InventoryInStorePickupFrontend/template/store-pickup': 'Albatool_Checkout/template/store-pickup',
		'Magento_Checkout/template/summary/cart-items': 'Albatool_Checkout/template/summary/cart-items',
		'Magento_GiftMessage/template/gift-message-form': 'Albatool_Checkout/template/gift-message-form'
    }
};