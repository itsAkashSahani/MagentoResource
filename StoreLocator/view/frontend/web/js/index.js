require([
    'jquery',
    'underscore',
    'mage/url',
    'Magento_Ui/js/modal/modal',
    'Albatool_StoreLocator/js/model/googleMap',
    'mage/translate'
], function (
    $,
    _,
    url,
    modal,
    googleMap,
    $t
) {
    // var self = this;
    var currentPosition;

    if(navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            e => {
              const pos = {
                lat: e.coords.latitude,
                lng: e.coords.longitude,
              };
                currentPosition = pos;
            }
        );
    }

    console.log(currentPosition);

    $(document).ready(function() {
        $.ajax({
            url: $('#getCity').val(),
            type: 'GET',
            showLoader: true,
            success: function(data){
                makeDropdown(data.cities);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                var errorMsg = 'Ajax request failed: ' + xhr.responseText;
                console.log(errorMsg);
            }
        });

        $.ajax({
            url: $('#getStoreDetails').val(),
            type: 'GET',
            showLoader: true,
            success: function(data){
                storeListFunction(data.items);
                locations = [];
                data.items.forEach(function(item, index) {
                    if(index > 0) {
                        locations[index - 1] = {lat: Number(item.latitude), lng: Number(item.longitude)}
                    }
                })
                console.log(locations);
                markAllStores(locations);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                var errorMsg = 'Ajax request failed: ' + xhr.responseText;
                console.log(errorMsg);
            }
        });

        // markAllStores(currentPosition);
        // initAutocomplete();
        
    });

    $('#stores').change(function() {
        city = $('option:selected').text();
        // console.log(city);
        fetchStoreData(city)
    });

    $('.store-selector-table').on( 'click', '.location-name', function () {
        source_code = $(this).attr('id');
        $.ajax({
            url: $('#getStoreDetails').val(),
            type: 'GET',
            data: {source_code: source_code},
            success: function(data){
                // storeListFunction(data.items);
                locations = [];
                data.items.forEach(function(item, index) {
                    locations[index] = {lat: Number(item.latitude), lng: Number(item.longitude)}
                })
                console.log(locations);
                markAllStores(locations);

            },
            error: function (xhr, ajaxOptions, thrownError) {
                var errorMsg = 'Ajax request failed: ' + xhr.responseText;
                console.log(errorMsg);
            }
        });
    });

    $("#currentLocation").click(function() {
        getCurrentLoctionWithStore();
    });

    function fetchStoreData(city) {
        $.ajax({
            url: $('#getStoreDetails').val(),
            type: 'GET',
            showLoader: true,
            data: {city: city},
            success: function(data){
                storeListFunction(data.items);
                locations = [];
                data.items.forEach(function(item, index) {
                    locations[index] = {lat: Number(item.latitude), lng: Number(item.longitude)}
                })
                // console.log(locations);
                markAllStores(locations);

            },
            error: function (xhr, ajaxOptions, thrownError) {
                var errorMsg = 'Ajax request failed: ' + xhr.responseText;
                console.log(errorMsg);
            }
        });
    }

    function makeDropdown(cities) {
        option = '';
        cities.forEach(function(item) {
            if(item != null) {
                option += '<option value="' + item + '">' + $t(item) + '</option>';
            }
        })
        $('#stores').append(option);
    }

    function storeListFunction(stores) {
        console.log(stores);
        url.setBaseUrl(BASE_URL);
        $('.store-details').html('');
        storeList = '';

        if(stores.length == 0) {
            storeList += '<p class="no-store">Store Not Found</p>';
            $('.store-details').html(storeList);
            return
        }

        stores.forEach(function(item) {

            address = getStoreAddress(item);
            console.log(item);

            console.log(currentPosition);
            storeDistance = 0;
            if (typeof currentPosition !== 'undefined') {
                storeDistance = getStoreDistance(item.latitude, item.longitude);
            }

            storeList += '<div class="locations"><div class="locations-details">';
            storeList += '<p class="location-name" id="'+ item.source_code +'">'+ $t(item.name) +'</p>';
            storeList += '<p class="location-address">'+ address +'</p>';
            storeList += '</div>';
            storeList += '<div class="locations-actions">';
            storeList += '<div class="location-distance">'+ storeDistance +' Km</div>';
            storeList += '<div class="select-location">';
            storeList += '<button type="button" class="action secondary action-select-store"><a href="'+ url.build('storelocator/details/index') +'?store='+ item.source_code +'">' + $t('VIEW STORE') + '</a></button>';
            storeList += '</div></div></div>';
        });
        $('.store-details').html(storeList);
    }

    function getStoreAddress(item) {
        address = $t(item.street) + ', ' + $t(item.city) + ', ' + $t(item.region) + ', ' + $t('Saudi Arabia')
        return address;
    }

    function markAllStores(locations) {
        console.log(locations.length);
        var gmarkers = [],
            map = googleMap.getGoogleMap(),
            storeDistance = '',
            bounds = new google.maps.LatLngBounds(),
            image = markericon,
            // currentPosition = {lat: 20.5937, lng: 78.9629},
            self = this;
        if(currentPosition){            
            map.setCenter(currentPosition);
            var currentMarker = new google.maps.Marker(
            {
                map:map,
                draggable:true,
                animation: google.maps.Animation.DROP,
                position: { lat: currentPosition.lat, lng: currentPosition.lng }
            });
            $.each(locations, function (key, val) {
                console.log(val);
                var pos = {
                    lat: parseFloat(val.lat),
                    lng: parseFloat(val.lng)
                };
                var marker = new google.maps.Marker({
                    position: pos,
                    map: map,
                    title: "Marked Pin",
                    icon: image
                });

                bounds.extend(marker.position);

                gmarkers.push(marker);

            });

            map.fitBounds(bounds);
        }
    }

    function getStoreDistance(dlat, dlng) {
        var clat = currentPosition.lat;
        var clng = currentPosition.lng ;
        var p1 = new google.maps.LatLng(dlat, dlng);
        var p2 = new google.maps.LatLng(clat, clng);
        var distance = (parseInt(getDistance(p1, p2)) / 1000).toFixed(1);
        return distance;
    }
            
    function rad(x) {
        return x * Math.PI / 180;
    }

    function getDistance(p1, p2) {
        var R = 6378137; // Earth’s mean radius in meter
        var dLat = rad(p2.lat() - p1.lat());
        var dLong = rad(p2.lng() - p1.lng());
        var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(rad(p1.lat())) * Math.cos(rad(p2.lat())) *
                Math.sin(dLong / 2) * Math.sin(dLong / 2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        var d = R * c;
        return d; // returns the distance in meter
    }


    function initAutocomplete() {
        const map = googleMap.getGoogleMap();
        // Create the search box and link it to the UI element.
        const input = document.getElementById("searchBar");
        const searchBox = new google.maps.places.SearchBox(input);

        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
        // Bias the SearchBox results towards current map's viewport.
        map.addListener("bounds_changed", () => {
            searchBox.setBounds(map.getBounds());
        });

        let markers = [];

        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener("places_changed", () => {
            const places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }

            // Clear out the old markers.
            markers.forEach((marker) => {
                marker.setMap(null);
            });
            markers = [];

            // For each place, get the icon, name and location.
            const bounds = new google.maps.LatLngBounds();

            places.forEach((place) => {
                if (!place.geometry || !place.geometry.location) {
                    console.log("Returned place contains no geometry");
                    return;
                }

                const icon = {
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(25, 25),
                };

                // Create a marker for each place.
                markers.push(
                    new google.maps.Marker({
                        map: map,
                        title: place.name,
                        position: place.geometry.location,
                    })
                );
                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            
            map.fitBounds(bounds);
        });
    }

    function getCurrentLoctionWithStore() {
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

                console.log("Geo Location");
                console.log(currentPosition);

                map.setCenter(pos);
                geocodePosition({ lat: currentPosition.lat, lng: currentPosition.lng });
                var marker = new google.maps.Marker(
                {
                    map:map,
                    draggable:true,
                    animation: google.maps.Animation.DROP,
                    position: { lat: currentPosition.lat, lng: currentPosition.lng }
                });
                google.maps.event.addListener(marker, 'dragend', function(x) 
                {
                    geocodePosition({ lat: x.latLng.lat(), lng: x.latLng.lng() });
                });
            },
            () => {
                alert("Please Enable Location Settings For The Website <br/>To Use Store Locator Services.");
                googleMap.handleLocationError(true, infoWindow, !map.getCenter());
            }
            );
        } else {
            // Browser doesn't support Geolocation
            googleMap.handleLocationError(false, infoWindow, !map.getCenter());
        }

    }

    function geocodePosition(pos) {
        var geocoder = new google.maps.Geocoder(),
            geolocate = new google.maps.LatLng(pos.lat, pos.lng),
            city = '';

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
                                fetchStoreData(city);
                            }
                        }
                    } else {
                        alert('No Store Found In This Location');
                    }
                } else {
                    alert('Geocoder failed due to: ' + status);
                }
            }
        );
    }

});