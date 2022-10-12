/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'underscore',
    'Magento_Ui/js/form/form',
    'ko',
    'Magento_Customer/js/model/customer',
    'Magento_Customer/js/model/address-list',
    'Magento_Checkout/js/model/address-converter',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/action/create-shipping-address',
    'Magento_Checkout/js/action/select-shipping-address',
    'Magento_Checkout/js/action/create-billing-address',
    'Magento_Checkout/js/action/select-billing-address',
    'Magento_Checkout/js/model/shipping-rates-validator',
    'Magento_Checkout/js/model/shipping-address/form-popup-state',
    'Magento_Checkout/js/model/shipping-service',
    'Magento_Checkout/js/action/select-shipping-method',
    'Magento_Checkout/js/model/shipping-rate-registry',
    'Magento_Checkout/js/action/set-shipping-information',
    'Magento_Checkout/js/model/step-navigator',
    'Magento_Ui/js/modal/modal',
    'Magento_Checkout/js/model/checkout-data-resolver',
    'Magento_Checkout/js/checkout-data',
    'uiRegistry',
    'mage/translate',
	'Magento_Customer/js/customer-data',
    'Magento_Checkout/js/model/shipping-rate-service',
    'Magento_InventoryInStorePickupFrontend/js/model/pickup-locations-service'
], function (
    $,
    _,
    Component,
    ko,
    customer,
    addressList,
    addressConverter,
    quote,
    createShippingAddress,
    selectShippingAddress,
    createBillingAddress,
    selectBillingAddress,
    shippingRatesValidator,
    formPopUpState,
    shippingService,
    selectShippingMethodAction,
    rateRegistry,
    setShippingInformationAction,
    stepNavigator,
    modal,
    checkoutDataResolver,
    checkoutData,
    registry,
    $t,
	customerData,
    shippingRateService,
    pickupLocationsService
) {
    'use strict';

    var popUp = null;
	var lastSelectedShippingAddress = null,
        addressUpadated = false,
        addressEdited = false,
        countryData = customerData.get('directory-data'),
        addressOptions = addressList().filter(function (address) {
            return address.getType() === 'customer-address';
        });
    return Component.extend({
        defaults: {
            template: 'Magento_Checkout/shipping',
            shippingFormTemplate: 'Magento_Checkout/shipping-address/form',
            shippingMethodListTemplate: 'Magento_Checkout/shipping-address/shipping-method-list',
            shippingMethodItemTemplate: 'Magento_Checkout/shipping-address/shipping-method-item',
            imports: {
                countryOptions: '${ $.parentName }.shippingAddress.shipping-address-fieldset.country_id:indexedOptions'
            }
        },
        isAddressSameAsShipping: ko.observable(true),
        isShowBillingForm: ko.observable(false),
        visible: ko.observable(!quote.isVirtual()),
        errorValidationMessage: ko.observable(false),
        isCustomerLoggedIn: customer.isLoggedIn,
        isFormPopUpVisible: formPopUpState.isVisible,
        isFormInline: addressList().length === 0,
        isNewAddressAdded: ko.observable(false),
        saveInAddressBook: 1,
        quoteIsVirtual: quote.isVirtual(),
		currentshippingAddress: quote.shippingAddress,
        customerHasAddresses: addressOptions.length > 0,

        /**
         * @return {exports}
         */
        initialize: function () {
            var self = this,
                hasNewAddress,
                fieldsetName = 'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset';

            this._super();
            var guest = localStorage.getItem('guest-email');
            $("input#customer-email").val(guest);

            if (!quote.isVirtual()) {
                stepNavigator.registerStep(
                    'shipping',
                    '',
                    $t('Shipping'),
                    this.visible, _.bind(this.navigate, this),
                    this.sortOrder
                );
            }
            checkoutDataResolver.resolveShippingAddress();

            hasNewAddress = addressList.some(function (address) {
                return address.getType() == 'new-customer-address'; //eslint-disable-line eqeqeq
            });

            this.isNewAddressAdded(hasNewAddress);

            this.isFormPopUpVisible.subscribe(function (value) {
                if (value) {
                    self.getPopUp().openModal();
                }
            });

            quote.shippingMethod.subscribe(function () {
                self.errorValidationMessage(false);
            });

            registry.async('checkoutProvider')(function (checkoutProvider) {
                var shippingAddressData = checkoutData.getShippingAddressFromData();

                if (shippingAddressData) {
                    checkoutProvider.set(
                        'shippingAddress',
                        $.extend(true, {}, checkoutProvider.get('shippingAddress'), shippingAddressData)
                    );
                }
                checkoutProvider.on('shippingAddress', function (shippingAddrsData) {
                    if (shippingAddrsData.street && !_.isEmpty(shippingAddrsData.street[0])) {
                        checkoutData.setShippingAddressFromData(shippingAddrsData);
                    }
                });
                shippingRatesValidator.initFields(fieldsetName);
            });

            return this;
        },

		initChildren: function () {
            this.messageContainer = new Messages();
            this.createMessagesComponent();
            return this;
        },


        /**
         * @return bollean
        */
        showShippingAddress: function () {
			if((quote.shippingMethod() != null ) && !quote.isVirtual()){
				if(quote.shippingMethod()['method_code'] != 'pickup'){
					return true;
				}
			}
			
			return false;
        },
		
		/**
         * @return bollean
         */
        getShippingMethodName: function () {
			if((quote.shippingMethod() != null ) && !quote.isVirtual()){
				return quote.shippingMethod()['method_code'];
			}
			return "";
        },
		
        placeOrderWithStore: function () {
			//$('#checkout-step-store-selector .form-continue .continue.primary').trigger('click');
        },
		
        /**
         * Navigator change hash handler.
         *
         * @param {Object} step - navigation step
         */
        navigate: function (step) {
            step && step.isVisible(true);
        },

        /**
         * @return {*}
         */
        getPopUp: function () {
            var self = this,
                buttons;

            if (!popUp) {
                buttons = this.popUpForm.options.buttons;
                this.popUpForm.options.buttons = [
                    {
                        text: buttons.save.text ? buttons.save.text : $t('Save Address'),
                        class: buttons.save.class ? buttons.save.class : 'action primary action-save-address',
                        click: self.saveNewAddress.bind(self)
                    },
                    {
                        text: buttons.cancel.text ? buttons.cancel.text : $t('Cancel'),
                        class: buttons.cancel.class ? buttons.cancel.class : 'action secondary action-hide-popup',

                        /** @inheritdoc */
                        click: this.onClosePopUp.bind(this)
                    }
                ];

                /** @inheritdoc */
                this.popUpForm.options.closed = function () {
                    self.isFormPopUpVisible(false);
                };

                this.popUpForm.options.modalCloseBtnHandler = this.onClosePopUp.bind(this);
                this.popUpForm.options.keyEventHandlers = {
                    escapeKey: this.onClosePopUp.bind(this)
                };

                /** @inheritdoc */
                this.popUpForm.options.opened = function () {
                    // Store temporary address for revert action in case when user click cancel action
                    self.temporaryAddress = $.extend(true, {}, checkoutData.getShippingAddressFromData());
                };
                popUp = modal(this.popUpForm.options, $(this.popUpForm.element));
            }

            return popUp;
        },

        /**
         * Revert address and close modal.
         */
        onClosePopUp: function () {
            checkoutData.setShippingAddressFromData($.extend(true, {}, this.temporaryAddress));
            this.getPopUp().closeModal();
        },

        /**
         * Show address form popup
         */
        showFormPopUp: function () {
            this.isFormPopUpVisible(true);
        },

        /**
         * Save new shipping address
         */
        saveNewAddress: function () {
            var addressData,
                newShippingAddress;
            this.source.set('params.invalid', false);
            this.triggerShippingDataValidateEvent();

            if (!this.source.get('params.invalid')) {
                addressData = this.source.get('shippingAddress');
                // if user clicked the checkbox, its value is true or false. Need to convert.
                addressData['save_in_address_book'] = this.saveInAddressBook ? 1 : 0;

                // New address must be selected as a shipping address
                newShippingAddress = createShippingAddress(addressData);
                selectShippingAddress(newShippingAddress);
                checkoutData.setSelectedShippingAddress(newShippingAddress.getKey());
                checkoutData.setNewCustomerShippingAddress($.extend(true, {}, addressData));
                //this.getPopUp().closeModal();
                this.isNewAddressAdded(true);
				this.setShippingInformation();
            }else{
				return false;
			}
        },

        /**
         * Shipping Method View
         */
        rates: shippingService.getShippingRates(),
        isLoading: shippingService.isLoading,
        isSelected: ko.computed(function () {
            return quote.shippingMethod() ?
                quote.shippingMethod()['carrier_code'] + '_' + quote.shippingMethod()['method_code'] :
                null;
        }),

        /**
         * @param {Object} shippingMethod
         * @return {Boolean}
         */
        selectShippingMethod: function (shippingMethod) {
            selectShippingMethodAction(shippingMethod);
            checkoutData.setSelectedShippingRate(shippingMethod['carrier_code'] + '_' + shippingMethod['method_code']);

            return true;
        },

        createMessagesComponent: function () {
            var messagesComponent = {
                parent: this.name,
                name: this.name + '.messages',
                displayArea: 'messages',
                component: 'Magento_Ui/js/view/messages',
                config: {
                    messageContainer: this.messageContainer
                }
            };

            layout([messagesComponent]);

            return this;
        },

        setShippingInformation: function () {
            if (this.validateShippingInformation()
                && this.validateBillingInformation()) {
                setShippingInformationAction().done(function () {
                    stepNavigator.next();
                });
            }
        },

        /**
         * @return {Boolean}
         */
        validateShippingInformation: function () {
            var shippingAddress,
                addressData,
                loginFormSelector = 'form[data-role=email-with-possible-login]',
                emailValidationResult = customer.isLoggedIn(),
                field;

            if (!quote.shippingMethod()) {
                this.errorValidationMessage($t('Please specify a shipping method.'));

                return false;
            }

            if (!customer.isLoggedIn()) {
                var guest = localStorage.getItem('guest-email');
                $("input#customer-email").val(guest);
                $(loginFormSelector).validation();
                emailValidationResult = Boolean(guest);
                
                // $(loginFormSelector).validation();
                // emailValidationResult = Boolean($(loginFormSelector + ' input[name=username]').valid());
            }

            //if (this.isFormInline) {
                this.source.set('params.invalid', false);
                this.triggerShippingDataValidateEvent();

                if (emailValidationResult &&
                    this.source.get('params.invalid') ||
                    !quote.shippingMethod()['method_code'] ||
                    !quote.shippingMethod()['carrier_code']
                ) {
                    this.focusInvalid();

                    return false;
                }

                shippingAddress = quote.shippingAddress();
                addressData = addressConverter.formAddressDataToQuoteAddress(
                    this.source.get('shippingAddress')
                );

                //Copy form data to quote shipping address object
                for (field in addressData) {
                    if (addressData.hasOwnProperty(field) &&  //eslint-disable-line max-depth
                        shippingAddress.hasOwnProperty(field) &&
                        typeof addressData[field] != 'function' &&
                        _.isEqual(shippingAddress[field], addressData[field])
                    ) {
                        shippingAddress[field] = addressData[field];
                    } else if (typeof addressData[field] != 'function' &&
                        !_.isEqual(shippingAddress[field], addressData[field])) {
                        shippingAddress = addressData;
                        break;
                    }
                }

                if (customer.isLoggedIn()) {
                    var save_addre_book = $('#new-ship-save-book').is(":checked")?1:0;
                    shippingAddress['save_in_address_book'] = save_addre_book;
                }
                selectShippingAddress(shippingAddress);
            //}

            if (!emailValidationResult) {
                $(loginFormSelector + ' input[name=username]').focus();

                return false;
            }

            return true;
        },

        validateBillingInformation: function () {
            var addressData, newBillingAddress;

            if ($('[name="billing-address-same-as-shipping"]').is(":checked")) {
                if (this.isFormInline) {
                    var shippingAddress = quote.shippingAddress();
                    addressData = addressConverter.formAddressDataToQuoteAddress(
                        this.source.get('shippingAddress')
                    );
                    //Copy form data to quote shipping address object
                    for (var field in addressData) {
                        if (addressData.hasOwnProperty(field) &&
                            shippingAddress.hasOwnProperty(field) &&
                            typeof addressData[field] !== 'function' &&
                            _.isEqual(shippingAddress[field], addressData[field])
                        ) {
                            shippingAddress[field] = addressData[field];
                        } else if (typeof addressData[field] !== 'function' &&
                            !_.isEqual(shippingAddress[field], addressData[field])) {
                            shippingAddress = addressData;
                            break;
                        }
                    }

                    if (customer.isLoggedIn()) {
                        shippingAddress.save_in_address_book = 1;
                    }
                    newBillingAddress = createBillingAddress(shippingAddress);
                    selectBillingAddress(newBillingAddress);
                } else {
                    var billingAddress = quote.shippingAddress();
                    selectBillingAddress(billingAddress);
                }

                return true;
            }

            /*var selectedAddress = quote.billingAddress();
            if (selectedAddress) {
                if (selectedAddress.customerAddressId) {
                    return addressList.some(function (address) {
                        if (selectedAddress.customerAddressId === address.customerAddressId) {
                            selectBillingAddress(address);
                            return true;
                        }
                        return false;
                    });
                } else if (selectedAddress.getType() === 'new-customer-address' || selectedAddress.getType() === 'new-billing-address') {
                    return true;
                }
            }*/

            this.source.set('params.invalid', false);
            this.source.trigger('billingAddress.data.validate');

            if (this.source.get('billingAddress.custom_attributes')) {
                this.source.trigger('billingAddress.custom_attributes.data.validate');
            }

            if (this.source.get('params.invalid')) {
                return false;
            }

            addressData = this.source.get('billingAddress');

            if ($('#billing-save-in-address-book').is(":checked")) {
                addressData.save_in_address_book = 1;
            }
            newBillingAddress = createBillingAddress(addressData);

            selectBillingAddress(newBillingAddress);

            return true;
        },

        /**
         * Trigger Shipping data Validate Event.
         */
         triggerShippingDataValidateEvent: function () {
            this.source.trigger('shippingAddress.data.validate');

            if (this.source.get('shippingAddress.custom_attributes')) {
                this.source.trigger('shippingAddress.custom_attributes.data.validate');
            }
        },
		
		/**
         * proceed to payment step.
         */
        proceedToPayment: function () {
			var  messageContainer = registry.get('checkout.errors').messageContainer,
				 shippingAddress = quote.shippingAddress();

                 var selectedLocation = pickupLocationsService.selectedLocation();

                if(quote.billingAddress() != null) {
                    selectBillingAddress(quote.billingAddress());
                }else {
                    selectBillingAddress(quote.shippingAddress());
                }
                //  console.log(quote.billingAddress());
				//  console.log(quote.shippingAddress());
			// if((shippingAddress.regionId == undefined) || (shippingAddress.city == undefined)){
			// 	messageContainer.addErrorMessage({
			// 		message: $t('Please select store')
			// 	});
            if(pickupLocationsService.selectedLocation() == null){
                messageContainer.addErrorMessage({
                    message: $t('Please select store')
                });
            }
			else{
				$('#checkout-step-store-selector .form-continue button').trigger('click');
			}
        },
		
		/**
         * hide next button.
         */
        displayDefaultButton: function () {
           return false
        },
		
        quoteHaveNotEmail: function () {
			if(!customer.isLoggedIn() && !quote.guestEmail){
			   return true;
			}
			return false;
        },

        /**
         * @return {Boolean}
         */
        useShippingAddress: function () {
            if (this.isAddressSameAsShipping()) {
                this.isShowBillingForm(false);
            } else {
                this.isShowBillingForm(true);
            }
            return true;
        },

        getBaseUrl: function(){
            return 'working';
        }
    });
});
