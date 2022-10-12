define([
	'jquery',
	'uiComponent',
	'ko',
	'Magento_Ui/js/modal/modal',
	'Magento_Customer/js/customer-data',
	'Magento_Customer/js/model/address-list',
	'Magento_Checkout/js/model/quote',
	'Magento_Customer/js/model/customer',
	'Albatool_Checkout/js/model/googleMap'
], function ($, Component, ko, modal,customerData,addressList,quote,customer,googleMap) {
    'use strict';
	var countryId = '';
	var countryName = '';
	var postalCode = '';
	var stateName = '';
	var addressData = '';
	var timer = '';
	var lastSelectedShippingAddress = null,
        addressUpadated = false,
        addressEdited = false,
        countryData = customerData.get('directory-data'),
        addressOptions = addressList().filter(function (address) {
            return address.getType() === 'customer-address';
        });
    return Component.extend({
        defaults: {
            template: 'Albatool_Checkout/view/checkout/shipping/google-map'
        },
		currentshippingAddress: quote.shippingAddress,
        customerHasAddresses: addressOptions.length > 0,
		isCustomerLoggedIn: customer.isLoggedIn,
		showLocationButton: function () {
			if(quote.shippingMethod() != null){
				if(quote.shippingMethod()['method_code'] != 'pickup'){
					return true;
				}
			}
			return false;
		},
		onElementRender: function () {
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

				  map.setCenter(pos);
				  self.geocodePosition({ lat: e.coords.latitude, lng: e.coords.longitude });
				  var marker = new google.maps.Marker(
					{
						map:map,
						draggable:true,
						animation: google.maps.Animation.DROP,
						position: { lat: e.coords.latitude, lng: e.coords.longitude }
					});
					google.maps.event.addListener(marker, 'dragend', function(x) 
					{
						self.geocodePosition({ lat: x.latLng.lat(), lng: x.latLng.lng() });
					});
				},
				 () => {
				  self.handleLocationError(true, infoWindow, !map.getCenter());
				}
			  );
			} else {
			  // Browser doesn't support Geolocation
			  self.handleLocationError(false, infoWindow, !map.getCenter());
			}
			
			return map;
		},
		useCurrentLoction: function () {
			var self = this;
			var map = googleMap.getGoogleMap();
			let infoWindow = new google.maps.InfoWindow();

			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(
				e => {
				  const pos = {
					lat: e.coords.latitude,
					lng: e.coords.longitude,
				  };

				  // infoWindow.setPosition(pos);
				  // infoWindow.setContent("Location found.");
				  // infoWindow.open(map);
				  map.setCenter(pos);
				  //self.successFunction(e);
				  self.geocodePosition({ lat: e.coords.latitude, lng: e.coords.longitude });
				  var marker = new google.maps.Marker(
					{
						map:map,
						draggable:true,
						animation: google.maps.Animation.DROP,
						position: { lat: e.coords.latitude, lng: e.coords.longitude }
					});
					google.maps.event.addListener(marker, 'dragend', function(x) 
					{
						self.geocodePosition({ lat: x.latLng.lat(), lng: x.latLng.lng() });
					});
				},
				 () => {
					$("#myModal #message").html("Please Enable Location Settings For The Website <br/>To Use Store Locator Services.");
					$("#header").css({
						zIndex: 0
					});
					$("#myModal").modal("openModal");
					self.handleLocationError(true, infoWindow, !map.getCenter());
				}
			  );
			} else {
			  // Browser doesn't support Geolocation
			  self.handleLocationError(false, infoWindow, !map.getCenter());
			}

		},
		handleLocationError: function (
		  browserHasGeolocation,
		  infoWindow,
		  pos
		) {
		  infoWindow.setPosition(pos);
		  infoWindow.setContent(
			browserHasGeolocation
			  ? "Error: The Geolocation service failed."
			  : "Error: Your browser doesn't support geolocation."
		  );
		  infoWindow.open(map);
		},
		successFunction: function(position) {
			var lat = position.coords.latitude;
			var lng = position.coords.longitude;
			var latlng = new google.maps.LatLng(lat, lng);
			var geocoder = new google.maps.Geocoder();
			geocoder.geocode({
				'latLng': latlng
			}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {                                   
					if (results[1]) {
						var indice = 0;
						for (var j = 0; j < results.length; j++) {
							if (results[j].types[0] == 'locality') {
								indice = j;
								break;
							}
						}
						var indice_k = 0;
						for (var k = 0; k < results.length; k++) {
							if (results[k].types[0] == 'postal_code') {
								indice_k = k;
								break;
							}
						}
						for (var i = 0; i < results[j].address_components.length; i++) {
							if (results[j].address_components[i].types[0] == "locality") {
								var city = results[j].address_components[i];
							}
							if (results[j].address_components[i].types[0] == "country") {
								var country = results[j].address_components[i];
							}
			
						}
			
						for (var i = 0; i < results[k].address_components.length; i++) {
							if (results[k].address_components[i].types[0] == "postal_code") {
								var postal_code = results[k].address_components[i];
							}
			
						}
						var city = city.long_name;
						var state = region.long_name;
						var country = country.short_name;
						var pin_code = postal_code.long_name;

						var dd = document.getElementsByName('region_id')[0];
						for (var i = 0; i < dd.options.length; i++) {
							if (dd.options[i].text === state) {
								dd.selectedIndex = i;
								break;
							}
						}
						dd.dispatchEvent(new Event('change'));

						var dd2 = document.getElementsByName('country_id')[0];
						for (var i = 0; i < dd2.options.length; i++) {
							if (dd2.options[i].text === state) {
								dd2.selectedIndex = i;
								break;
							}
						}
						dd2.dispatchEvent(new Event('change'));

						var cityEle = document.getElementsByName('city')[0];
						cityEle.value = city;
						cityEle.dispatchEvent(new Event('change'));
						
						var postcodeEle = document.getElementsByName('postcode')[0];
						postcodeEle.value = pin_code;
						postcodeEle.dispatchEvent(new Event('change'));
						
					} else {
						alert("No results found");
					}
			
				} else {
					alert("Geocoder failed due to: " + status);
				}
			});
		},
		geocodePosition: function(pos) {
		   var geocoder = new google.maps.Geocoder();
		   var streetAddress = '';
		   geocoder.geocode
			({
				latLng: pos
			}, 
				function(results, status) 
				{
					if (status ==
                        google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
							console.log(results[0].address_components);
							var addrComp = results[0].address_components;
                              for (var i=addrComp.length-1;i>=0;i--) {
                                if (addrComp[i].types[0] == "locality")
                                {
									var city = addrComp[i].long_name;
									console.log(city);

									if($("div[name ='shippingAddress.city'] select[name='city'] option[value='"+city+"']").length) {
										// self.getStoreByCity(city)
										$('div[name ="shippingAddress.city"] select[name="city"]').val(city);
										$('div[name ="shippingAddress.city"] select[name="city"]').trigger('change');
									}
                                }
                                else if (addrComp[i].types[0] == 'street_number') {
									streetAddress = addrComp[i].long_name +", "+ streetAddress;
								} else if (addrComp[i].types[0] == 'route') {
									streetAddress = addrComp[i].long_name +", "+ streetAddress;
								} else if (addrComp[i].types[0] == 'political') {
									streetAddress = addrComp[i].long_name +", "+ streetAddress;
								} else if (addrComp[i].types[0] == 'neighborhood') {
									streetAddress = addrComp[i].long_name +", "+ streetAddress;
								} else if (addrComp[i].types[0] == 'sublocality_level_3') {
									streetAddress = addrComp[i].long_name +", "+ streetAddress;
								} else if (addrComp[i].types[0] == 'sublocality_level_2') {
									streetAddress = addrComp[i].long_name +", "+ streetAddress;
								} else if (addrComp[i].types[0] == 'sublocality_level_1') {
									streetAddress = addrComp[i].long_name +", "+ streetAddress;
								} else if (addrComp[i].types[0] == 'locality') {
									// streetAddress = addrComp[i].long_name+", "+ streetAddress;
								}
                                  
                              }
                              if (streetAddress) {
                                  $(document).find("div[name = 'shippingAddress.street.0'] input[name = 'street[0]']").val(streetAddress)
                                  $(document).find("div[name = 'shippingAddress.street.0'] input[name = 'street[0]']").trigger('keyup');
                              }
                        } else {
                            alert('No results found');
                        }
                    } else {
                        alert('Geocoder failed due to: ' + status);
                    }
				}
			);
		},

		initAutocomplete: function() {
			const map = googleMap.getGoogleMap();
		}
    });
});