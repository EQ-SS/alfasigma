<?php
	include "../conexion.php";
	
	echo "<script>
	
	$('#divMap').hide();
	$('#divTblRep').hide();
	$('#divmarque').hide();
	$('#mapaRepre').show();
	
	$('#btnLocalizaRepreLocalizadorRepres').removeClass('bg-red');
	$('#btnLocalizaRepreLocalizadorRepres').addClass('bg-green');
	
	$('#btnTrackingLocalizadorRepres').removeClass('bg-green');
	$('#btnTrackingLocalizadorRepres').addClass('bg-red');
	$('#btnHistorialVisitasLocalizadorRepres').removeClass('bg-green');
	$('#btnHistorialVisitasLocalizadorRepres').addClass('bg-red');
	
	</script>";
	
	$fecha = date("Y-m-d");
	//$fecha = "2021-08-25";
	if(isset($_POST['idUsuario']) && $_POST['idUsuario']){
		$idUsuario = $_POST['idUsuario'];
	}else{
		$idUsuario = "";
	}
		
	$queryP = "select top 1 u.lname + ' ' + u.fname as nombre, 
		ut.LATITUDE, 
		ut.LONGITUDE, 
		convert(char(5), ut.CREATION_TIMESTAMP, 108) as hora
		from USER_TRACKING ut
		inner join users u on u.USER_SNR = ut.USER_SNR
		where CREATION_TIMESTAMP between '".$fecha." 00:00:00' and '".$fecha." 23:59:59' 
		AND ut.REC_STAT=0 ";
	if($idUsuario != ''){
		$queryP .= " and u.user_snr = '".$idUsuario."' ";
		$zoom = 16;
	}else{
		$zoom = 5;
	}
	$queryP .= " order by CREATION_TIMESTAMP desc";
	
	//echo $queryP;
	
	$rs = sqlsrv_query($conn, $queryP);

	$lugares = array();
	$lineas = "";
	
	while($reg = sqlsrv_fetch_array($rs)){
		$lugares[] = array($reg['LATITUDE'], $reg['LONGITUDE'], $reg['nombre'], $reg['hora']);
		$lineas .= "{lat: ".$reg['LATITUDE'].", lng: ".$reg['LONGITUDE']."},";
	}
	//print_r($lugares);
	echo "<script>
		deleteMarkersRuteo(markersRadar, lineasRuteo);
 
		function closeInfoWindow() {
			infoWindow.close();
		}
 
		function openInfoWindow(marker, content) {
			var markerLatLng = marker.getPosition();
			infoWindow.setContent([content].join(''));
			infoWindow.open(map, marker);
		}";
	
	if(count($lugares)>0){
		echo "
			var map = new google.maps.Map(document.getElementById('mapaRepre'), {
				zoom: 20,
				center: new google.maps.LatLng(".$lugares[0][0].", ".$lugares[0][1]."),
			});
			
			infoWindow = new google.maps.InfoWindow();
 
			google.maps.event.addListener(map, 'click', function(){
				closeInfoWindow();
			});";
			
			for($i=0;$i<count($lugares);$i++){
				
				echo "var pinColor = '2471a3';";
				
				echo "
					var pinImage = new google.maps.MarkerImage('https://www.smart-scale.net/markers/marker_marine.png');
					
					marker".$i." = new google.maps.Marker({
						position: new google.maps.LatLng(".$lugares[$i][0].", ".$lugares[$i][1]."),
						map: map,
						icon: pinImage,
						title: '".$lugares[$i][2]."'
					});
					
					google.maps.event.addListener(marker".$i.", 'click', function(){
						openInfoWindow(marker".$i.", '<b>".$lugares[$i][2]."</b><br>".$lugares[$i][3]."');
					});
					
					markersRadar.push(marker".$i.");
				";
			}
			
		if($idUsuario != ""){
			$lineas = substr($lineas, 0, strlen($lineas) -1);
			echo "
				flightPlanCoordinates = [".$lineas."];
				flightPath = new google.maps.Polyline({
					path: flightPlanCoordinates,
					geodesic: true,
					strokeColor: '#0000FF',
					strokeOpacity: 1.0,
					strokeWeight: 2
				});

				flightPath.setMap(map);
				lineasRuteo.push(flightPath);
				";
		}
	}
	echo "</script>";
	//echo $iconos;
	//print_r($lugares);
?>