<?php
	include "../conexion.php";

	$arrDiaDeCiclo = sqlsrv_fetch_array(sqlsrv_query($conn,"SELECT * FROM CYCLE_DETAILS WHERE CYCLE_SNR IN (SELECT CYCLE_SNR FROM CYCLES WHERE  '".date("Y-m-d")."' BETWEEN START_DATE AND FINISH_DATE) AND CAST(GETDATE() AS DATE)=CYCLE_DETAILS.C_DATE "));
	$diaDelCiclo = $arrDiaDeCiclo['C_DAY'];
	$nombreDiaCiclo=$arrDiaDeCiclo['C_DAY_NAME'];

	$v1=0;
	
	
	$sltCobDiaAnterior=$_POST['sltDiaAnterior'];

	if($sltCobDiaAnterior !='0'){

		if($nombreDiaCiclo=="LUNES"){
		
		$v1=-3;
		
		
		}else if($nombreDiaCiclo=="DOMINGO"){
		$v1=-2;
		
		}else{
		$v1=-1;
		
		}
		
		}

	$sltCobDiaAnterior=$_POST['sltDiaAnterior'];
	
	if(isset($_POST['sltEstadoVis'])){
		$sltEstadoVis=$_POST['sltEstadoVis'];
	}else{
		$sltEstadoVis="";
	}
	
	echo "<script>
	
	$('#divMap').hide();
	$('#divTblRep').hide();
	$('#divmarque').hide();
	$('#mapaRepre').show();
	
	$('#btnHistorialVisitasLocalizadorRepres').removeClass('bg-red');
	$('#btnHistorialVisitasLocalizadorRepres').addClass('bg-green');
	
	$('#btnTrackingLocalizadorRepres').removeClass('bg-green');
	$('#btnTrackingLocalizadorRepres').addClass('bg-red');
	
	$('#btnLocalizaRepreLocalizadorRepres').removeClass('bg-green');
	$('#btnLocalizaRepreLocalizadorRepres').addClass('bg-red');
	
	</script>";
	
	$fecha = date("Y-m-d");
	//$fecha = "2021-12-13";
	if(isset($_POST['idUsuario']) && $_POST['idUsuario']){
		$idUsuario = $_POST['idUsuario'];
	}else{
		$idUsuario = "";
	}
		
	$queryP = "select CYC.CONTACTS,u.lname + ' ' + u.FNAME as nombre, 
		RIGHT('00' + Ltrim(Rtrim(cast(day(vp.visit_date) as char))), 2) + '/' +
		RIGHT('00' + Ltrim(Rtrim(cast(month(vp.visit_date) as char))), 2) + '/' +
		RIGHT('0000' + Ltrim(Rtrim(cast(year(vp.visit_date) as char))), 2) as fecha,
		vp.time as hora, vp.LATITUDE, vp.LONGITUDE, 
		p.FNAME + ' ' + p.LNAME + ' ' + p.MOTHERS_LNAME as medico,
		ROUND(ROW_NUMBER ( ) OVER(ORDER BY vp.time ASC) /CAST( CYC.CONTACTS AS FLOAT) * 100,2 ) as PORCIENTO 
		from VISITPERS vp
		inner join users u on u.USER_SNR = vp.USER_SNR 
		inner join person p on p.PERS_SNR = vp.PERS_SNR 
		INNER JOIN PERSLOCWORK plw ON vp.pwork_snr=plw.pwork_snr
		inner join inst i on i.inst_snr=plw.inst_snr
		inner join city ci on ci.city_snr=i.city_snr ";

		$queryP=$queryP." INNER JOIN CYCLES CYC ON CYC.REC_STAT=0 AND CAST(dateadd(\"d\",$v1,GETDATE()) AS DATE) BETWEEN CYC.START_DATE AND CAST(dateadd(\"d\",$v1,CYC.FINISH_DATE) AS DATE) "; 
		$queryP=$queryP." where vp.user_snr = '".$idUsuario."' ";
	//echo $sltEstadoVis;
		if($sltEstadoVis !="0"){
			$queryP=$queryP." and ci.state_snr='".$sltEstadoVis."' ";
		}
		

		$queryP=$queryP." and vp.VISIT_DATE = '".$fecha."'
		AND CAST(vp.LATITUDE AS FLOAT) >0
		order by vp.time ";
	
	//echo $queryP;
	
	$rs = sqlsrv_query($conn, $queryP);

	$lugares = array();
	$contactos="";
	$lineas = "";
	$cobertura=array();
	
	while($reg = sqlsrv_fetch_array($rs)){
		$lugares[] = array($reg['LATITUDE'], $reg['LONGITUDE'], $reg['nombre'], $reg['hora'], $reg['medico']);
		$lineas .= "{lat: ".$reg['LATITUDE'].", lng: ".$reg['LONGITUDE']."},";
		$contactos =$reg['CONTACTS'];
		$cobertura[]=$reg['PORCIENTO'];
	}
	//print_r($cobertura);
	//print_r('<br>ROWS'.count($lugares));
	
	
	
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
				zoom: 16,
				center: new google.maps.LatLng(".$lugares[0][0].", ".$lugares[0][1]."),
			});
			
			infoWindow = new google.maps.InfoWindow();
 
			google.maps.event.addListener(map, 'click', function(){
				closeInfoWindow();
			});";
			
			
			for($i=0;$i<count($lugares);$i++){
				
	if($cobertura[$i] >= 95){
		//verde
		echo "var pinImage = new google.maps.MarkerImage('https://www.smart-scale.net/markers/marker_green.png');";
		}
		
	if($cobertura[$i] >=78 && $cobertura[$i] <= 94 ){
		//amarillo
		echo "var pinImage = new google.maps.MarkerImage('https://www.smart-scale.net/markers/marker_yellow.png');";
		}
	if($cobertura[$i] > 50 && $cobertura[$i] < 78){
		//naranja
		
		echo "var pinImage = new google.maps.MarkerImage('https://www.smart-scale.net/markers/marker_orange.png');";
		}
	if($cobertura[$i] <= 50 ){
		//rojo
		echo "var pinImage = new google.maps.MarkerImage('https://www.smart-scale.net/markers/marker_red.png');";
	}
				
				
				echo "
					marker".$i." = new google.maps.Marker({
						position: new google.maps.LatLng(".$lugares[$i][0].", ".$lugares[$i][1]."),
						map: map,
						icon: pinImage,
						title: '".$lugares[$i][2]."'
					});
					
					google.maps.event.addListener(marker".$i.", 'click', function(){
						openInfoWindow(marker".$i.", '<b>".$lugares[$i][4]."</b><br>".$lugares[$i][3]."');
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
	
?>