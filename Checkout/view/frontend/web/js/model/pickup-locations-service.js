/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

 define([
    'jquery',
    'knockout',
    'Magento_InventoryInStorePickupFrontend/js/model/resource-url-manager',
    'mage/storage',
    'Magento_Customer/js/customer-data',
    'Magento_Checkout/js/checkout-data',
    'Magento_Checkout/js/model/address-converter',
    'Magento_Checkout/js/action/select-shipping-address',
    'underscore',
    'mage/translate',
    'mage/url',
    'Magento_InventoryInStorePickupFrontend/js/model/pickup-address-converter'
], function (
    $,
    ko,
    resourceUrlManager,
    storage,
    customerData,
    checkoutData,
    addressConverter,
    selectShippingAddressAction,
    _,
    $t,
    url,
    pickupAddressConverter
) {
    'use strict';

    var websiteCode = window.checkoutConfig.websiteCode,
        countryData = customerData.get('directory-data');

    return {
        isLoading: ko.observable(false),
        selectedLocation: ko.observable(null),
        locationsCache: [],

        /**
         * Get shipping rates for specified address.
         *
         * @param {String} sourceCode
         */
        getLocation: function (sourceCode) {
            var serviceUrl = resourceUrlManager.getUrlForPickupLocation(websiteCode, sourceCode);

            this.isLoading(true);

            return storage
                .get(serviceUrl, {}, false)
                .then(function (result) {
                    var addresses = result.items || [],
                        address = addresses[0] || {};

                    return this.formatAddress(address);
                }.bind(this))
                .fail(function (response) {
                    this.processError(response);

                    return [];
                }.bind(this))
                .always(function () {
                    this.isLoading(false);
                }.bind(this));
        },

        /**
         * Get nearby pickup locations based on given search criteria.
         *
         * @param {Object} searchCriteria - Search criteria object.
         * @see Magento/InventoryInStorePickup/Model/SearchCriteria/GetNearbyLocationsCriteria.php
         */
        getNearbyLocations: function (searchCriteria) {
            var self = this,
                serviceUrl = resourceUrlManager.getUrlForNearbyPickupLocations(websiteCode, searchCriteria);

            if (self.locationsCache[serviceUrl]) {
                return $.Deferred().resolve(self.locationsCache[serviceUrl]).promise();
            }

            self.isLoading(true);

            return storage
                .get(serviceUrl, {}, false)
                .then(function (result) {
                    self.locationsCache[serviceUrl] = _.map(result.items, function (address) {
                        return self.formatAddress(address);
                    });

                    return self.locationsCache[serviceUrl];
                })
                .fail(function (response) {
                    self.processError(response);

                    return [];
                })
                .always(function () {
                    self.isLoading(false);
                });
        },

        /**
         * Get city of store.
         *
         * @param {Object} searchCriteria - Search criteria object.
         */
        getCity: function (searchCriteria) {
            var self = this,
                serviceUrl = resourceUrlManager.getUrlForStoreCity(websiteCode, searchCriteria);

            if (self.locationsCache[serviceUrl]) {
                return $.Deferred().resolve(self.locationsCache[serviceUrl]).promise();
            }

            self.isLoading(true);

            return storage
                .get(serviceUrl, {}, false)
                .then(function (result) {
                    self.locationsCache[serviceUrl] = result.cities;

                    return self.locationsCache[serviceUrl];
                })
                .fail(function (response) {
                    self.processError(response);

                    return [];
                })
                .always(function () {
                    self.isLoading(false);
                });
        },
        
        /**
         * Select location for sipping.
         *
         * @param {Object} location
         * @returns void
         */
        selectForShipping: function (location) {
            var address = $.extend(
                {},
                addressConverter.formAddressDataToQuoteAddress({
                    firstname: location.name,
                    lastname: 'Store',
                    street: location.street,
                    city: location.city,
                    postcode: location.postcode,
                    'country_id': location['country_id'],
                    telephone: location.telephone,
                    'region_id': location['region_id'],
                    'save_in_address_book': 0,
                    'extension_attributes': {
                        'pickup_location_code': location['pickup_location_code']
                    }
                }));

            address = pickupAddressConverter.formatAddressToPickupAddress(address);
            this.selectedLocation(location);
            selectShippingAddressAction(address);
            checkoutData.setSelectedShippingAddress(address.getKey());
            checkoutData.setSelectedPickupAddress(
                addressConverter.quoteAddressToFormAddressData(address)
            );
        },

        /**
         * Formats address returned by REST endpoint to match checkout address field naming.
         *
         * @param {Object} address - Address object returned by REST endpoint.
         */
        formatAddress: function (address) {
            // console.log(address);

            if(address.monday_field != null) {
                var monday_field = address.monday_field.split(",");
            }
            else {
                var monday_field = ['Close', 'Close'];
            }

            if(address.tuesday_field != null) {
                var tuesday_field = address.tuesday_field.split(",");
            }
            else {
                var tuesday_field = ['Close', 'Close'];
            }

            if(address.wednesday_field != null) {
                var wednesday_field = address.wednesday_field.split(",");
            }
            else {
                var wednesday_field = ['Close', 'Close'];
            }

            if(address.thursday_field != null) {
                var thursday_field = address.thursday_field.split(",");
            }
            else {
                var thursday_field = ['Close', 'Close'];
            }

            if(address.friday_field != null) {
                var friday_field = address.friday_field.split(",");
            }
            else {
                var friday_field = ['Close', 'Close'];
            }

            if(address.saturday_field != null) {
                var saturday_field = address.saturday_field.split(",");
            }
            else {
                var saturday_field = ['Close', 'Close'];
            }

            if(address.sunday_field != null) {
                var sunday_field = address.sunday_field.split(",");
            }
            else {
                var sunday_field = ['Not Available', 'Not Available'];
            }
            return {
                name: address.name,
                description: address.description,
                latitude: address.latitude,
                sku: address.sku,
                longitude: address.longitude,
                street: [address.street],
                city: address.city,
                postcode: address.postcode,
                monday_open: monday_field[0],
                monday_close: monday_field[1],
                tuesday_open: tuesday_field[0],
                tuesday_close: tuesday_field[1],
                wednesday_open: wednesday_field[0],
                wednesday_close: wednesday_field[1],
                thursday_open: thursday_field[0],
                thursday_close: thursday_field[1],
                friday_open: friday_field[0],
                friday_close: friday_field[1],
                saturday_open: saturday_field[0],
                saturday_close: saturday_field[1],
                sunday_open: sunday_field[0],
                sunday_close: sunday_field[1],
                'country_id': address['country_id'],
                country: this.getCountryName(address['country_id']),
                telephone: address.phone,
                'region_id': address['region_id'],
                region: this.getRegionName(
                    address['country_id'],
                    address['region_id']
                ),
                'pickup_location_code': address['source_code'],
                'total_product_not_found': address['total_product_not_found']
            };
        },

        /**
         * Get country name by id.
         *
         * @param {*} countryId
         * @return {String}
         */
        getCountryName: function (countryId) {
            return countryData()[countryId] !== undefined ?
                countryData()[countryId].name
                : ''; //eslint-disable-line
        },

        /**
         * Returns region name based on given country and region identifiers.
         *
         * @param {String} countryId - Country identifier.
         * @param {String} regionId - Region identifier.
         */
        getRegionName: function (countryId, regionId) {
            var regions = countryData()[countryId] ?
                countryData()[countryId].regions
                : null;

            return regions && regions[regionId] ? regions[regionId].name : '';
        },

        /**
         * Process response errors.
         *
         * @param {Object} response
         * @returns void
         */
        processError: function (response) {
            var expr = /([%])\w+/g,
                error;

            if (response.status === 401) {
                //eslint-disable-line eqeqeq
                window.location.replace(url.build('customer/account/login/'));

                return;
            }

            try {
                error = JSON.parse(response.responseText);
            } catch (exception) {
                error = $t(
                    'Something went wrong with your request. Please try again later.'
                );
            }

            if (error.hasOwnProperty('parameters')) {
                error = error.message.replace(expr, function (varName) {
                    varName = varName.substr(1);

                    if (error.parameters.hasOwnProperty(varName)) {
                        return error.parameters[varName];
                    }

                    return error.parameters.shift();
                });
            }
        },

        /**
         * Returns selected pick up address from local storage
         *
         * @returns {Object|null}
         */
        getSelectedPickupAddress: function () {
            var shippingAddress,
                pickUpAddress;

            if (checkoutData.getSelectedPickupAddress()) {
                shippingAddress = addressConverter.formAddressDataToQuoteAddress(
                    checkoutData.getSelectedPickupAddress()
                );
                pickUpAddress = pickupAddressConverter.formatAddressToPickupAddress(
                    shippingAddress
                );

                return pickUpAddress;
            }

            return null;
        }
    };
});
