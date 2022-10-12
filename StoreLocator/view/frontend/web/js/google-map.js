require([
	'jquery',
	'Albatool_StoreLocator/js/model/googleMap'
], function ($, googleMap) {
	
	$(document).ready(function() {
		onElementRender()
	});

	function onElementRender() {
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
				console.log(pos);
			//   self.geocodePosition({ lat: e.coords.latitude, lng: e.coords.longitude });
				var marker = new google.maps.Marker(
				{
					map:map,
					draggable:true,
					animation: google.maps.Animation.DROP,
					position: { lat: e.coords.latitude, lng: e.coords.longitude }
				});
				google.maps.event.addListener(marker, 'dragend', function() 
				{
					// self.geocodePosition(marker.getPosition());
				});
			},
				() => {
				handleLocationError(true, infoWindow, !map.getCenter());
			}
			);
		} else {
			// Browser doesn't support Geolocation
			handleLocationError(false, infoWindow, !map.getCenter());
		}
		
		return map;
	}

	function handleLocationError(
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
});