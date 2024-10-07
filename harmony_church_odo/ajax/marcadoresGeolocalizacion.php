<?php
	include "../conexion.php";
	
	function distancia($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo){
		$theta = $longitudeFrom - $longitudeTo;
		$dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		//$unit = strtoupper($unit);
		//if ($unit == "K") {
			return ($miles * 1.609344);
		/*} else if ($unit == "N") {
			return ($miles * 0.8684).' nm';
		} else {
			return $miles.' mi';
		}*/
	}
	
	$fecha = date("Y-m-d");
	$visitas = $_POST['planVisita'];
	
	$km = $_POST['km'];
	//$vis = $_POST['vis'];
	$esp = $_POST['esp'];
	$tipo = $_POST['tipo'];
	$tipoIns = $_POST['tipoIns'];
	$lat = $_POST['lat'];
	$lon = $_POST['lon'];
	$persona = $_POST['persona'];
	$inst = $_POST['inst'];
	
	$carpetaIconos = explode("/",$_SERVER['PHP_SELF'])[1];
	
	$ids = str_replace(",","','",$_POST['ids']);
	$ids = str_replace("'',''","','",$ids);
	
	if(isset($_POST['repre']) && $_POST['repre']){
		$repre = str_replace(",","','",substr($_POST['repre'], 0, -1));
	}else{
		$repre = '';
	}
	//echo $repre;
	//echo $km."<br>".$visitas."<br>".$esp."<br>".$tipo."<br>".$tipoIns."<br>".$lat."<br>".$lon;
	
	$queryP = "select psw.pers_snr, 
		'P '+p.lname+' '+p.mothers_lname+' '+p.fname as nombre, 
		esp.name as esp, 
		i.latitude as latitude, 
		i.longitude as longitude, 
		(SELECT COUNT(*) FROM VISITPERS VP, CYCLES CICLOS 
		WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND p.pers_snr=VP.PERS_SNR 
		AND P.REC_STAT=0 AND ESTATUS.NAME='ACTIVO' AND VP.USER_SNR in ('".$ids."') 
		AND '".$fecha."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
		AND VP.VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE) AS VISITAS 
		from pers_srep_work psw   
		left outer join person p on p.pers_snr = psw.pers_snr
		left outer join perslocwork plw on plw.pwork_snr=psw.pwork_snr
		left outer join inst i on psw.inst_snr=i.inst_snr
		left outer join codelist esp on esp.clist_snr=p.spec_snr 
		left outer join codelist estatus on estatus.clist_snr=p.status_snr 
		where psw.rec_stat=0 
		and plw.rec_stat=0 
		and i.latitude<>'' and i.latitude<>'0.0'    
		and estatus.name='ACTIVO' 
		and psw.user_snr in ('".$ids."') ";
		
		if($visitas == '0'){
			$queryP .= " and (SELECT COUNT(*) FROM VISITPERS VP, CYCLES CICLOS 
			WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND p.pers_snr=VP.PERS_SNR 
			AND P.REC_STAT=0 AND ESTATUS.NAME='ACTIVO' AND VP.USER_SNR in ('".$ids."') 
			AND '".$fecha."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
			AND VP.VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE) = 0 ";
		}else if($visitas == '1'){
			$queryP .= " and (SELECT COUNT(*) FROM VISITPERS VP, CYCLES CICLOS 
			WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND p.pers_snr=VP.PERS_SNR 
			AND P.REC_STAT=0 AND ESTATUS.NAME='ACTIVO' AND VP.USER_SNR in ('".$ids."') 
			AND '".$fecha."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
			AND VP.VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE) > 0 ";
		}
		
		if($esp != '00000000-0000-0000-0000-000000000000'){
			$queryP .= " and p.spec_snr = '".$esp."' ";
		}
		
		if($repre != ''){
			$queryP .= "AND psw.user_snr in ('".$repre."') ";
		}
		
		if($persona != ''){
			$queryP .= "and p.lname like '%".$persona."%' ";
		}
    
	$queryI = "select i.inst_snr,
		'I '+i.name as nombre, 
		'' as esp, 
		i.latitude as latitude,
		i.longitude as longitude, 
		(SELECT COUNT(*) FROM VISITINST VP, CYCLES CICLOS 
		WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND i.inst_snr=VP.INST_SNR 
		AND '".$fecha."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
		AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE) AS VISITAS 
		from inst i
		left outer join user_territ ut on i.inst_snr=ut.inst_snr
		left outer join inst_type on i.inst_type = inst_type.inst_type
		left outer join codelist estatus on estatus.clist_snr=i.status_snr 
		where i.rec_stat=0 and i.inst_type<>3 
		and i.latitude<>'' and i.latitude<>'0.0' 
		and ut.user_snr in ('".$ids."') 
		and estatus.name='ACTIVO'";
		
	if($visitas == '0'){
		$queryI .= " and (SELECT COUNT(*) FROM VISITINST VP, CYCLES CICLOS 
			WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND i.inst_snr=VP.INST_SNR 
			AND '".$fecha."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
			AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE) = 0 ";
	}else if($visitas == '1'){
		$queryI .= " and (SELECT COUNT(*) FROM VISITINST VP, CYCLES CICLOS 
			WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND i.inst_snr=VP.INST_SNR 
			AND '".$fecha."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
			AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE) > 0 ";
	}
	
	if($tipoIns != ''){
		$queryI .= " and i.inst_type = '".$tipoIns."' ";
	}
	
	if($repre != ''){
		$queryI .= " AND ut.user_snr in ('".$repre."') ";
	}
	
	if($inst != ''){
		$queryI .= " AND i.name like '%".$inst."%' ";
	}
	
	$query = '';
	
	if($tipo == "p"){
		$query = $queryP;
	}else if($tipo == "i"){
		$query = $queryI;
	}else if($tipo == ""){
		$query = $queryP." union ".$queryI;
	}
    
	$query .= "order by nombre ";
	
	//echo $query;
		
	$rs = sqlsrv_query($conn, $query);

	$lugares = array();
	while($reg = sqlsrv_fetch_array($rs)){
		$dist = floor(distancia($lat, $lon, $reg['latitude'], $reg['longitude']));
		//echo $km." ::: ".$dist."<br>";
		if($km * 1000 >= $dist){
			$lugares[] = array($reg['latitude'], $reg['longitude'], $reg['nombre'], $reg['esp']);
		}
	}
	//print_r($lugares);
	echo "<script>
		deleteMarkersRadar(markersRadar);
 
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
			var map = new google.maps.Map(document.getElementById('mapa'), {
				zoom: 5,
				center: new google.maps.LatLng(".$lugares[0][0].", ".$lugares[0][1]."),
			});
			
			infoWindow = new google.maps.InfoWindow();
 
			google.maps.event.addListener(map, 'click', function(){
				closeInfoWindow();
			});";
			
			for($i=0;$i<count($lugares);$i++){
				if(substr($lugares[$i][2], 0, 1) == 'P'){
					$icono = "https://smart-scale.net/".$carpetaIconos."/iconos/Doctor32.png";
				}else if(substr($lugares[$i][2], 0, 1) == 'I'){
					$icono = "https://smart-scale.net/".$carpetaIconos."/iconos/Hospital32.png";
				}else{
					$icono = "https://smart-scale.net/".$carpetaIconos."/iconos/Hospital32.png";
				}
				
				echo "
					var imagen = '".$icono."';
					marker".$i." = new google.maps.Marker({
						position: new google.maps.LatLng(".$lugares[$i][0].", ".$lugares[$i][1]."),
						map: map,
						icon: imagen,
						title: '".$lugares[$i][2]."'
					});
					
					google.maps.event.addListener(marker".$i.", 'click', function(){
						openInfoWindow(marker".$i.", '<b>".$lugares[$i][2]."</b><br>".$lugares[$i][3]."');
					});
					
					markersRadar.push(marker".$i.");
				";
			}
	}
	echo "</script>";
	//echo $iconos;
	//print_r($lugares);
?>