<?php
	include "../conexion.php";
	$fecha = $_POST['fecha'];
	$idUsuario = str_replace(",","",$_POST['idUsuario']);
	$planVisita = $_POST['planVisita'];
	//echo "fecha: ".$fecha."<br>";
	/*if(isset($_POST['ids']) && $_POST['ids'] != ''){
		$idUsuario = $_POST['ids']."','".$idUsuario;
	}*/
	$carpetaIconos = explode("/",$_SERVER['PHP_SELF'])[1];
	//$fechaRuteo = $fecha;
	//echo "<script>alert('".$fecha."')</script>";
	if($planVisita == "plan"){
		$query = "select vp.vispersplan_snr as vp_id, 
			'P ' + vp.time + ' ' + p.lname + ' ' + p.mothers_lname + ' ' + p.fname as nombre, 
			vp.time as hora, 
			esp.name as esp, 
			vp.vispers_snr as idVisita,
			i.latitude,
			i.longitude,
			plw.latitude as plw_lat,
			plw.longitude as plw_lon
			from vispersplan vp 
			left outer join person p on vp.pers_snr=p.pers_snr
			left outer join codelist esp on esp.clist_snr=p.spec_snr 
			left outer join PERSLOCWORK plw on plw.pwork_snr = vp.pwork_snr 
			left outer join inst i on plw.inst_SNR = i.INST_SNR
			where vp.rec_stat=0 
			and vp.user_snr in ('".$idUsuario."') 
			and vp.plan_date='".$fecha."' 
			and plw.REC_STAT = 0
			union 
			select vp.visinstplan_snr as vp_id, 
			'I '+ vp.time + ' ' + i.name as nombre, 
			vp.time as hora, 
			'' as esp, 
			vp.visinst_snr as idVisita,
			i.latitude, i.longitude, 
			i.LATITUDE as plw_lat,
			i.LONGITUDE as plw_lon 
			from visinstplan vp, inst i 
			where vp.rec_stat=0 
			and vp.inst_snr=i.inst_snr 
			and vp.plan_date='".$fecha."' 
			and vp.user_snr in ('".$idUsuario."') 
			order by hora,nombre,esp ";
	}else if($planVisita == "visita"){
		$query = "select vp.vispers_snr vp_id, 
			'P ' + vp.time + ' ' + p.lname + ' ' + p.mothers_lname + ' ' + p.fname as nombre, 
			esp.name as esp, 
			vp.vispersplan_snr as idPlan,
			vp.latitude,
			vp.longitude,
			plw.latitude as plw_lat,
			plw.longitude as plw_lon
			from visitpers vp  
			left outer join person p on vp.pers_snr=p.pers_snr
			left outer join codelist esp on esp.clist_snr=p.spec_snr 
			left outer join PERSLOCWORK plw on plw.pwork_SNR = vp.pwork_snr 
			left outer join inst i on plw.inst_SNR = i.INST_SNR
			where vp.rec_stat=0 
			and vp.visit_date='".$fecha."' 
			and vp.user_snr in ('".$idUsuario."') 
			and p.fname <> 'AJUSTE' 
			and plw.REC_STAT = 0
			union 
			select vp.visinst_snr vp_id, 'I: ' + vp.time + ' ' + i.name as nombre,
			cast(i.INST_TYPE as varchar) as esp, 
			vp.visinstplan_snr as idPlan,
			vp.latitude,
			vp.longitude, 
			i.LATITUDE as plw_lat,
			i.LONGITUDE as plw_lon
			from visitinst vp, inst i 
			where vp.rec_stat=0 
			and vp.inst_snr=i.inst_snr 
			and vp.visit_date='".$fecha."' 
			and vp.user_snr in ('".$idUsuario."') 
			order by nombre,esp  ";
		}
	
	//echo $query."<br><br>";
	
	$rs = sqlsrv_query($conn, $query);
	//clearMarkers('map_canvas3');
	echo "<script>
			$('#tblPlanesVisitasRuteo').empty();
			$('#hdnFechaRuteo').val('".$fecha."');
			$('#sltYearCal').val('".substr($fecha, 0, 4)."');
			$('#sltMesCal').val('".(int)substr($fecha, 5, 2)."');
			deleteMarkersRuteo(markersRuteo, lineasRuteo);
			";
	//alert(markersRuteo.length);
		
	$lugares = array();
	$lineas = "";
	$punteos = array();
	$tipos = array();
	
	
	while($registro = sqlsrv_fetch_array($rs)){
		//print_r($registro);
		//echo "<br><br>";
		$registro['nombre'] = utf8_encode($registro['nombre']);
		$nombre = $registro['nombre'];
		if($planVisita == 'plan'){
			if(substr($registro['nombre'], 0, 1) == "I"){
				if($registro['idVisita'] != '00000000-0000-0000-0000-000000000000' && $registro['idVisita'] != null){
					echo "$('#tblPlanesVisitasRuteo').append('<tr onClick=\"muestraPlanInst(\'".$registro['vp_id']."\');\"><td style=\"border-bottom: 1px solid #ddd;color: #04B404;\"><i class=\"fas fa-building col-light-green\"></i>".substr($nombre, (strpos($nombre,'I'))+1)."</td></tr>');";
				}else{
					echo "$('#tblPlanesVisitasRuteo').append('<tr onClick=\"muestraPlanInst(\'".$registro['vp_id']."\');\"><td style=\"border-bottom: 1px solid #ddd;\"><i class=\"fas fa-building col-light-green\"></i>".substr($nombre, (strpos($nombre,'I'))+1)."</td></tr>');";
				}
				
			}else if(substr($registro['nombre'], 0, 1) == "P"){
				if($registro['idVisita'] != '00000000-0000-0000-0000-000000000000'){
					echo "$('#tblPlanesVisitasRuteo').append('<tr onClick=\"muestraPlan(\'".$registro['vp_id']."\');\"><td style=\"border-bottom: 1px solid #ddd;color: #04B404;\"><i class=\"fas fa-user-md col-light-green\"></i>".substr($nombre, (strpos($nombre,'P'))+1)."</td></tr>');";
				}else{
					echo "$('#tblPlanesVisitasRuteo').append('<tr onClick=\"muestraPlan(\'".$registro['vp_id']."\');\"><td style=\"border-bottom: 1px solid #ddd;\"><i <i class=\"fas fa-user-md col-pink\"></i>".substr($nombre, (strpos($nombre,'P'))+1)."</td></tr>');";
				}
			}
		}else if($planVisita == 'visita'){
			if(substr($registro['nombre'], 0, 1) == "I"){
				if($registro['idPlan'] != '00000000-0000-0000-0000-000000000000'){
					echo "$('#tblPlanesVisitasRuteo').append('<tr onClick=\"muestraVisitaInst(\'".$registro['vp_id']."\');\"><td style=\"border-bottom: 1px solid #ddd;color: #04B404;\"><i class=\"fas fa-building col-light-green\"></i>".substr($nombre, (strpos($nombre,'I'))+1)."</td></tr>');";
				}else{
					echo "$('#tblPlanesVisitasRuteo').append('<tr onClick=\"muestraVisitaInst(\'".$registro['vp_id']."\');\"><td style=\"border-bottom: 1px solid #ddd;\"><i class=\"fas fa-building col-light-green\"></i>".substr($nombre, (strpos($nombre,'I'))+1)."</td></tr>');";
				}
			}else if(substr($registro['nombre'], 0, 1) == "P"){
				if($registro['idPlan'] != '00000000-0000-0000-0000-000000000000'){
					echo "$('#tblPlanesVisitasRuteo').append('<tr onClick=\"muestraVisita(\'".$registro['vp_id']."\');\"><td style=\"border-bottom: 1px solid #ddd;color: #04B404;\"><i class=\"fas fa-user-md col-light-green\"></i>".substr($nombre, (strpos($nombre,'P'))+1)."</td></tr>');";
				}else{
					echo "$('#tblPlanesVisitasRuteo').append('<tr onClick=\"muestraVisita(\'".$registro['vp_id']."\');\"><td style=\"border-bottom: 1px solid #ddd;\"><i class=\"fas fa-user-md col-light-green\"></i>".substr($nombre, (strpos($nombre,'P'))+1)."</td></tr>');";
				}
			}
		}
		$lugares[] = array($registro['latitude'], $registro['longitude'], $registro['nombre']);
		$punteos[] = array($registro['plw_lat'], $registro['plw_lon'], $registro['nombre']);
		if((float)$registro['latitude'] > 0 && (float)$registro['longitude'] < 0){
			$lineas .= "{lat: ".$registro['latitude'].", lng: ".$registro['longitude']."},";
		}
		if(substr($registro['nombre'], 0, 1) == "I"){
			$tipos[] = $registro['esp'];
		}else{
			$tipos[] = '';
		}
	}
	
	//print_r($tipos);
	
	if(count($lugares)>0){
		$centrar = 0;
		for($i=0;$i<count($lugares);$i++){
			//echo "---".(float)$lugares[$i][0]."---".(float)$lugares[$i][1]."---<br>";
			//echo 'alert("'.$lugares[$i][0].' ::: '.$lugares[$i][1].'");';
			if($centrar == 0){
				if((float)$lugares[$i][0] > 0 && (float)$lugares[$i][1] < 0 ){
					echo "var map = new google.maps.Map(document.getElementById('map_canvas3'), {
						zoom: 12,
						center: new google.maps.LatLng(".$lugares[$i][0].", ".$lugares[$i][1]."),
					});";
					$centrar = 1;
				}
			}
			if($planVisita == "plan"){
				if(substr($lugares[$i][2], 0, 1) == 'P'){
					$icono = "https://smart-scale.net/".$carpetaIconos."/iconos/Doctor32.png";
					$push = "https://smart-scale.net/".$carpetaIconos."/iconos/Doctor32.png";
				}else if(substr($lugares[$i][2], 0, 1) == 'I'){
					$icono = "https://smart-scale.net/".$carpetaIconos."/iconos/Hospital32.png";
					$push = "https://smart-scale.net/".$carpetaIconos."/iconos/Hospital32.png";
				}
			} else {
				if(substr($lugares[$i][2], 0, 1) == 'P'){
					$icono = "https://smart-scale.net/".$carpetaIconos."/markers/normal/blue.png";
					$push = "https://smart-scale.net/".$carpetaIconos."/markers/pushpin/blue.png";
				}else if(substr($lugares[$i][2], 0, 1) == 'I'){
					if($tipos[$i] == '1'){//hospitales
						$icono = "https://smart-scale.net/".$carpetaIconos."/markers/normal/ltblue.png";
						$push = "https://smart-scale.net/".$carpetaIconos."/markers/pushpin/ltblue.png";
					} else if($tipos[$i] == '2'){//farmacias
						$icono = "https://smart-scale.net/".$carpetaIconos."/markers/normal/green.png";
						$push = "https://smart-scale.net/".$carpetaIconos."/markers/pushpin/green.png";
					}/*else {
						$icono = "https://smart-scale.net/".$carpetaIconos."/markers/normal/pink.png";
					}*/
					
				}
			}
			//echo substr($lugares[$i][2], 0, 1)." ::: ".$lugares[$i][0]." ::: ".$lugares[$i][1];
			if((float)$lugares[$i][0] > 0 && (float)$lugares[$i][1] < 0 ){
				if($planVisita == "visita"){
					if((float)$punteos[$i][0] > 0 && (float)$punteos[$i][1] < 0 ){
						echo "
							var imagen = '".$icono."';
							marker = new google.maps.Marker({
							position: new google.maps.LatLng(".$punteos[$i][0].", ".$punteos[$i][1]."),
							map: map,
							icon: imagen,
							title: '".$punteos[$i][2]."'
						});
						markersRuteo.push(marker);
						
						flightPlanCoordinates = [{lat: ".$lugares[$i][0].", lng: ".$lugares[$i][1]."},{lat: ".$punteos[$i][0].", lng: ".$punteos[$i][1]."}];
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
				echo "
					var imagen = '".$push."';
					marker = new google.maps.Marker({
					position: new google.maps.LatLng(".$lugares[$i][0].", ".$lugares[$i][1]."),
					map: map,
					icon: imagen,
					title: '".$lugares[$i][2]."'
				}); 
				markersRuteo.push(marker);
				";
			}
		}
		
		if($planVisita == "plan"){
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
	echo "
		cambiaMesRuteo();
	</script>";
	//echo $lineas;
	//print_r($lugares);
	
?>