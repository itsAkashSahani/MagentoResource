/**
 * Copyright Â© Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

 define([
    'jquery',
    'underscore',
    'uiComponent',
    'uiRegistry',
    'Magento_Ui/js/modal/modal',
    'Magento_Checkout/js/model/quote',
    'Magento_Customer/js/model/customer',
    'Magento_Checkout/js/model/step-navigator',
    'Magento_Checkout/js/model/address-converter',
    'Magento_Checkout/js/action/set-shipping-information',
    'Magento_InventoryInStorePickupFrontend/js/model/pickup-locations-service',
    'Albatool_Checkout/js/model/googleMap',
    'Magento_Checkout/js/action/get-totals',
    'mage/translate'
], function (
    $,
    _,
    Component,
    registry,
    modal,
    quote,
    customer,
    stepNavigator,
    addressConverter,
    setShippingInformationAction,
    pickupLocationsService,
    googleMap,
    getTotalsAction,
    $t
) {
    'use strict';
    // var currentPosition = {lat: 20.5937, lng: 78.9629};

    $(document).ready(function(){
        $('body').click(function(e){
            var target = $(e.target),
                self = this;
            // console.log(e);
            if(target.is(".delivery-method")){
                var map = googleMap.getGoogleMap();
                map.setZoom(5);
            }
    
            if(target.is(".action-select-shipping")){
                $("#pac-input").addClass("shipping-map");
                $("#pac-input").removeClass("pickup-map");
                $(".map-search").addClass("homepickup-map-search");
            }
    
            if(target.is(".action-select-store-pickup")){
                setTimeout(function(){
                    if (!!$.cookie('selected-city')) {
                        var city = $.cookie('selected-city');    
                        $('.city-option').val(city);
                        $('.city-option').trigger('change');
                    }
                }, 2000)

                // setTimeout(function(){
                //     if(!!$.cookie('selected-city') && !!$.cookie('selected-store')) {
                //         var store = $.cookie('selected-store');
                //         $('div.location button[data-storecode="'+ store +'"]').trigger('click'); 
                //     }
                // }, 1000);
            }
            
            // initialize
        })
    })

    var currentPosition;

    navigator.geolocation.getCurrentPosition(
        e => {
          const pos = {
            lat: e.coords.latitude,
            lng: e.coords.longitude,
          };
            currentPosition = pos;
        }
    );

    return Component.extend({
        defaults: {
            template: 'Magento_InventoryInStorePickupFrontend/store-selector',
            storeSelectorPopupTemplate:
                'Magento_InventoryInStorePickupFrontend/store-selector/popup',
            storeSelectorPopupItemTemplate:
                'Magento_InventoryInStorePickupFrontend/store-selector/popup-item',
            loginFormSelector:
                '#store-selector form[data-role=email-with-possible-login]',
            defaultCountryId: window.checkoutConfig.store_country_id,
            delimiter: window.checkoutConfig.storePickupApiSearchTermDelimiter,
            selectedLocation: pickupLocationsService.selectedLocation,
            quoteIsVirtual: quote.isVirtual,
            searchQuery: '',
            nearbyLocations: null,
            noStore: false,
            storecity: null,
            isLoading: pickupLocationsService.isLoading,
            popup: null,
            searchDebounceTimeout: 300,
            selectedCity: null,
            imports: {
                nearbySearchRadius: '${ $.parentName }:nearbySearchRadius',
                nearbySearchLimit: '${ $.parentName }:nearbySearchLimit',
                selectedCity: '${ $.parentName }:selectedCity'
            }
        },

        /**
         * Init component
         *
         * @return {exports}
         */
        initialize: function () {
            var updateNearbyLocations, country;

            this._super();

            updateNearbyLocations = _.debounce(function (searchQuery) {
                country = this.defaultCountryId;
                searchQuery = this.getSearchTerm(searchQuery, country);
                this.updateNearbyLocations(searchQuery);
            }, this.searchDebounceTimeout).bind(this);
            this.searchQuery.subscribe(updateNearbyLocations);
            this.openPopup();
            return this;
        },

        /**
         * Init component observable variables
         *
         * @return {exports}
         */
        initObservable: function () {
            return this._super().observe(['nearbyLocations', 'searchQuery','storecity']);
        },

        /**
         * Set shipping information handler
         */
        setPickupInformation: function () {
            if (this.validatePickupInformation()) {
                setShippingInformationAction().done(function () {
                    stepNavigator.next();
                });
            }
        },

        /**
         * @return {*}
         */
        getPopup: function () {
            if (!this.popup) {
                this.popup = modal(
                    this.popUpList.options,
                    $(this.popUpList.element)
                );
            }

            return this.popup;
        },

        /**
         * @return bollean
         */
        showLocationButtonOnPickup: function () {
            if(quote.shippingMethod() != null ){
                if(quote.shippingMethod()['method_code'] == 'pickup'){
                    return true;
                }
            }
            return false;
        },
        
        /**
         * @return map
         */
        getCurrentLoctionWithStore: function () {
            var self = this,
                map = googleMap.getGoogleMap(),
                infoWindow = new google.maps.InfoWindow();
        
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                e => {
                  const pos = {
                    lat: e.coords.latitude,
                    lng: e.coords.longitude,
                  };
                    currentPosition = pos;

                    console.log("Geo Location" + currentPosition);

                    map.setCenter(pos);
                    self.geocodePosition({ lat: currentPosition.lat, lng: currentPosition.lng });
                    var marker = new google.maps.Marker(
                    {
                        map:map,
                        draggable:true,
                        animation: google.maps.Animation.DROP,
                        position: { lat: currentPosition.lat, lng: currentPosition.lng }
                    });
                },
                () => {
                    $("#myModal #message").html("Please Enable Location Settings For The Website <br/>To Use Store Locator Services.");
                    $("#header").css({
                        zIndex: 0
                    });
                    $("#myModal").modal("openModal");
                    googleMap.handleLocationError(true, infoWindow, !map.getCenter());
                }
              );
            } else {
              // Browser doesn't support Geolocation
              googleMap.handleLocationError(false, infoWindow, !map.getCenter());
            }

        },
        geocodePosition: function(pos) {
            var geocoder = new google.maps.Geocoder(),
                geolocate = new google.maps.LatLng(pos.lat, pos.lng),
                country = '',
                city = '',
                state = '',
                searchTerm = '',
                self =  this;
            geocoder.geocode
            ({
                latLng: geolocate
            }, 
                function(results, status) 
                {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            var addrComp = results[0].address_components;
                            for (var i=addrComp.length-1; i>=0; i--) {
                                if (addrComp[i].types[0] == "locality")
                                {
                                   city = addrComp[i].long_name;
                                }
                                if (addrComp[i].types[0] == "administrative_area_level_1")
                                {
                                   state = addrComp[i].long_name;
                                }
                            }
                            country = 'SA';

                            city = $.trim(city);

                            if(country && city){
                                searchTerm = self.getSearchTerm(city, country);
                            }
                            console.log("City: " + city);
                            
                            if($(".city-option option[value='"+city+"']").length) {
                                // self.getStoreByCity(city)
                                $('.city-option').val(city);
                                $('.city-option').trigger('change');
                            }

                        } else {
                            alert('No Store Found In This Location');
                        }
                    } else {
                        alert('Geocoder failed due to: ' + status);
                    }
                }
            );
        },
        
        /**
         * Get Search Term from search query and country.
         *
         * @param {String} searchQuery
         * @param {String} country
         * @returns {String}
         */
        getSearchTerm: function (searchQuery, country) {
            return searchQuery ? searchQuery + this.delimiter + country : searchQuery;
        },

        /**
         * @returns void
         */
        openPopup: function () {
            var searchTerm = '';
            var self =  this;
            setTimeout(function(){                 
                self.updateCity(searchTerm);
                self.updateNearbyLocations(searchTerm);
            }, 500);
            console.log("Its Working");
        },

        /**
         * @param {Object} location
         * @returns void
         */
        selectPickupLocation: function (location) {
            console.log("Working Selection");
            var map = googleMap.getGoogleMap(),
                image = markericon,
                self = this,
                pos = {
                    lat: parseFloat(location.latitude),
                    lng: parseFloat(location.longitude)
                };
            map.setCenter(pos);
            new google.maps.Marker({
                position: pos,
                map: map,
                title: "Marked Pin",
                icon: image
            });
            pickupLocationsService.selectForShipping(location);

            console.log(location);
            $.cookie("selected-city", location.city, { expires : 1 });
            $.cookie("selected-store", location.pickup_location_code, { expires : 1 });

            sessionStorage.setItem("pickup_sele_sku", location.sku);
            sessionStorage.setItem("pickup_sele_store", location.name);
            //alert(sessionStorage.getItem("pickup_sele_sku"));
            if(location.total_product_not_found == 0) {
                $(".pickup-btn").attr('disabled', false);
                $(".proceed-payment").removeClass("disabled");
                console.log($("#store-pickup").attr('class'));
            }else {
                $(".pickup-btn").attr('disabled', true);
                $(".proceed-payment").addClass("disabled");
            }
            var deferred = $.Deferred();
            getTotalsAction([], deferred);
        },

        /**
         * @param {Object} location
         * @returns {*|Boolean}
         */
        isPickupLocationSelected: function (location) {
            return _.isEqual(this.selectedLocation(), location);
        },

        /**
         * @param {String} searchQuery
         * @returns {*}
         */
        updateNearbyLocations: function (searchQuery) {
            var self = this,
                productsInfo = [],
                items = quote.getItems(),
                searchCriteria;

            // console.log(items);

            _.each(items, function (item) {
                //if (item['qty_options'] === undefined || item['qty_options'].length === 0) {
                    productsInfo.push(
                        {
                            sku: item.sku
                        }
                    );
                //}
            });

            searchCriteria = {
                extensionAttributes: {
                    productsInfo: productsInfo
                },
                pageSize: this.nearbySearchLimit
            };

            if (searchQuery) {
                searchCriteria.area = {
                    radius: this.nearbySearchRadius,
                    searchTerm: searchQuery
                };
            }

            return pickupLocationsService
                .getNearbyLocations(searchCriteria)
                .then(function (locations) {
                    self.markStoreInMap(locations);
                })
                .fail(function () {
                    self.nearbyLocations([]);
                });
        },
        markStoreInMap: function (locations) {
            var gmarkers = [],
                map = googleMap.getGoogleMap(),
                image = markericon,
                storeDistance = '',
                bounds = new google.maps.LatLngBounds(),
                self = this;
            if(currentPosition){            
                map.setCenter(currentPosition);
                console.log(currentPosition);
                var currentMarker = new google.maps.Marker(
                {
                    map:map,
                    draggable:true,
                    animation: google.maps.Animation.DROP,
                    position: { lat: currentPosition.lat, lng: currentPosition.lng }
                });

                google.maps.event.addListener(currentMarker, 'dragend', function(x) 
                {
                    console.log("Dragged");
                    self.geocodePosition({ lat: x.latLng.lat(), lng: x.latLng.lng() });
                });

                if(locations.length == 0) {
                    $(".store-selector-table").hide();
                    $(".no-store-found").show();
                }
                else{
                    $(".no-store-found").hide();
                    $.each(locations, function (key, val) {
                        var pos = {
                            lat: parseFloat(val.latitude),
                            lng: parseFloat(val.longitude)
                        };
                        storeDistance = self.getStoreDistance(val.latitude,val.longitude);
                        var marker = new google.maps.Marker({
                            position: pos,
                            map: map,
                            title: val.name,
                            icon: image
                        });

                        bounds.extend(marker.position);
                        val.distance = storeDistance+' KM';
                        gmarkers.push(marker);

                    });

                    map.fitBounds(bounds);
                    self.nearbyLocations(locations);
                    console.log(locations);
                }
            }else{
                self.nearbyLocations(locations);
            }
            
        },
        getStoreDistance: function (dlat, dlng) {
            var clat = currentPosition.lat;
            var clng = currentPosition.lng ;
            var p1 = new google.maps.LatLng(dlat, dlng);
            var p2 = new google.maps.LatLng(clat, clng);
            var distance = (parseInt(this.getDistance(p1, p2)) / 1000).toFixed(1);
            return distance;
        },
            
        rad: function (x) {
            return x * Math.PI / 180;
        },
        getDistance: function (p1, p2) {
            var R = 6378137; // Earth’s mean radius in meter
            var dLat = this.rad(p2.lat() - p1.lat());
            var dLong = this.rad(p2.lng() - p1.lng());
            var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(this.rad(p1.lat())) * Math.cos(this.rad(p2.lat())) *
                    Math.sin(dLong / 2) * Math.sin(dLong / 2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            var d = R * c;
            return d; // returns the distance in meter
        },
            
        getStoreByCity: function (selectedCity) {
            var searchTerm = '';
            var shippingAddress = quote.shippingAddress(),
                country = this.defaultCountryId ? this.defaultCountryId : shippingAddress.countryId;

            if (selectedCity && (selectedCity != "Select City")) {
                $.cookie("selected-city", selectedCity, { expires : 1 });
                searchTerm = this.getSearchTerm(selectedCity, country);
                this.updateNearbyLocations(searchTerm);
            }
        },
        
        /**
         * @param {Object} city
         * @return {*}
         */
        cityOptionsText: function (selectedCity) {
            var city_new_name = selectedCity;
            if(city_new_name == "Jeddah")
            {
                city_new_name = $t("Jeddah");
            }
            else if(city_new_name == "Al Madeena"){
                city_new_name = $t("Al Madeena");
            }
            else if(city_new_name == "Dahran"){
                city_new_name = $t("Dahran");
            }
            else if(city_new_name == "Dammam"){
                city_new_name = $t("Dammam");
            }
            else if(city_new_name == "Hail"){
                city_new_name = $t("Hail");
            }
            else if(city_new_name == "Hassa"){
                city_new_name = $t("Hassaa");
            }
            else if(city_new_name == "Jizan"){
                city_new_name = $t("Jizan");
            }
            else if(city_new_name == "Jouf"){
                city_new_name = $t("Jouf");
            }
            else if(city_new_name == "Makkah"){
                city_new_name = $t("Makkah");
            }
            else if(city_new_name == "Qasiem"){
                city_new_name = $t("Qasiem");
            }
            else if(city_new_name == "Qassim"){
                city_new_name = $t("Qassim");
            }
            else if(city_new_name == "Riyadh"){
                city_new_name = $t("Riyadh");
            }
            else if(city_new_name == "Tabuk"){
                city_new_name = $t("Tabuk");
            }
            else if(city_new_name == "Taif"){
                city_new_name = $t("Taif");
            }
            else if(city_new_name == "Yanbu"){
                city_new_name = $t("Yanbu");
            }
            else{
                city_new_name = $t(city_new_name);
            }
            return city_new_name;
        },

        cityOptionsValue: function (selectedCity) {
            return selectedCity;
        },

        // testfunction: function (pickupLocationsService) {
        //     console.log(pickupLocationsService);
        // },


        // getCoordinates: function(locations) {
        //     console.log(locations[0].latitude);
        // },


        /**
         * @param {String} searchQuery
         * @returns {*}
         */
        updateCity: function (searchQuery) {
            var self = this,
                productsInfo = [],
                items = quote.getItems(),
                searchCriteria;

            _.each(items, function (item) {
               //if (item['qty_options'] === undefined || item['qty_options'].length === 0) {
                    productsInfo.push(
                        {
                            sku: item.sku
                        }
                    );
                //}
            });

            searchCriteria = {
                extensionAttributes: {
                    productsInfo: productsInfo
                },
                pageSize: this.nearbySearchLimit
            };

            if (searchQuery) {
                searchCriteria.area = {
                    radius: this.nearbySearchRadius,
                    searchTerm: searchQuery
                };
            }

            return pickupLocationsService
                .getCity(searchCriteria)
                .then(function (city) {
                    self.storecity(city);
                    console.log("Success");
                })
                .fail(function () {
                    self.storecity([]);
                    console.log("Failed");
                });
        },

        /**
         * @returns {Boolean}
         */
        validatePickupInformation: function () {
            var emailValidationResult,
                loginFormSelector = this.loginFormSelector;

            if (!customer.isLoggedIn()  && !quote.guestEmail ) {
                $(loginFormSelector).validation();
                emailValidationResult = $(loginFormSelector + ' input[name=username]').valid() ? true : false;

                if (!emailValidationResult) {
                    $(this.loginFormSelector + ' input[name=username]').trigger('focus');

                    return false;
                }
            }

            return true;
        },

        getViewDetailsStore: function (code) {
            // $('.store-details').click(function(){
            $('.store-info-'+code).toggle();
            // });
            console.log(code);
        }
    });
});