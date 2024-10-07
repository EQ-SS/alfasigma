<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyD-tf5PgJGx6iHEtZ-4W0ynr-Fgfzarch0"></script>
<script>
	window.onload = function() {
		function initialize() {
 
			var latlng = new google.maps.LatLng(41.652393,1.691895);
			var mapOptions = {
				zoom: 8,
				center: latlng,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
			}
			var map = new google.maps.Map(document.getElementById('mapa'), mapOptions);
			setMarkers(map, marcadores);
		}
 
		var marcadores = [
			['Barcelona',41.385064,2.173404,'Barcelona'],
			['Tarragona',41.119019,1.245212,'Tarragona'],
			['Girona',41.9794,2.821426,'Girona'],
			['Lleida',41.60034,0.609762,'Lleida'],
		];

		var infowindow;
		function setMarkers(map, marcadores) {
			for (var i = 0; i < marcadores.length; i++) {
				var myLatLng = new google.maps.LatLng(marcadores[i][1], marcadores[i][2]);
				var marker = new google.maps.Marker({
					position: myLatLng,
					map: map,
					title: marcadores[i][0],
				});
				(function(i, marker) {
					google.maps.event.addListener(marker,'click',function() {
						if (!infowindow) {
							infowindow = new google.maps.InfoWindow();
						}
						infowindow.setContent(marcadores[i][3]);
						infowindow.open(map, marker);
					});
				})(i, marker);
			}
		};
		initialize();
	}
</script>
<div id="mapa" style="width:1300px;height:400px;"></div>