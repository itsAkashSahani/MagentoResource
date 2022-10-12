define(['jquery'], function (
    $
) {
    'use strict';

    return {

        getGoogleMap: function () {
			var self = this;
			var map = new google.maps.Map(document.getElementById('map'), {
				center: { lat: 23.8859, lng: 45.0792 },
				zoom: 14,
				disableDefaultUI: true,
				mapTypeId: "roadmap",
			});

			const input = document.getElementById("searchBar");
			const options = {
				componentRestrictions: { country: "sa" },
			};

			const searchBox = new google.maps.places.SearchBox(input, options);

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
					// Create a marker for each place.
					markers.push(
						new google.maps.Marker({
							map,
							title: place.name,
							position: place.geometry.location,
						})
					);

					if(place.name){
						if($(".city-option option[value='"+place.name+"']").length) {
							$('.city-option').val(place.name);
							$('.city-option').trigger('change');
						}
					}

					if (place.geometry.viewport) {
						// Only geocodes have viewport.
						bounds.union(place.geometry.viewport);
					} else {
						bounds.extend(place.geometry.location);
					}
				});
				map.fitBounds(bounds);
			});

			return map;
        },
		getCurrentLoction: function () {
			var self = this;
			var map = self.getGoogleMap();
			let infoWindow = new google.maps.InfoWindow();
			var marker = "";
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(
				e => {
					const pos = {
						lat: e.coords.latitude,
						lng: e.coords.longitude,
					};
					map.setCenter(pos);
					marker = new google.maps.Marker(
					{
						map:map,
						draggable:true,
						animation: google.maps.Animation.DROP,
						position: { lat: e.coords.latitude, lng: e.coords.longitude }
					});
				  return marker;
				},
				 () => {
				  return self.handleLocationError(true, infoWindow, !map.getCenter());
				}
			  );
			} else {
			  return self.handleLocationError(false, infoWindow, !map.getCenter());
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
		  return infoWindow.open(map);
		}
    };
});
