<?php
	include "../conexion.php";

	$arrDiaDeCiclo = sqlsrv_fetch_array(sqlsrv_query($conn,"SELECT * FROM CYCLE_DETAILS WHERE CYCLE_SNR IN (SELECT CYCLE_SNR FROM CYCLES WHERE  '".date("Y-m-d")."' BETWEEN START_DATE AND FINISH_DATE) AND CAST(GETDATE() AS DATE)=CYCLE_DETAILS.C_DATE "));
	$diaDelCiclo = $arrDiaDeCiclo['C_DAY'];
	$nombreDiaCiclo=$arrDiaDeCiclo['C_DAY_NAME'];

	$sltCobDiaAnterior=$_POST['sltDiaAnterior'];

	if(isset($_POST['valorC'])){
		$sltRepre=$_POST['valorC'];
	}else{
		$sltRepre="";
	}

	
	
	$v1=0;


	if($sltCobDiaAnterior !='0'){

		if($nombreDiaCiclo=="LUNES"){
		
		$v1=-3;
		
		
		}else if($nombreDiaCiclo=="DOMINGO"){
		$v1=-2;
		
		}else{
		$v1=-1;
		
		}
		
		}
		

	
		$fechaI = date("Y-m-d");

		$fecha=date("Y-m-d",strtotime($fechaI."".$v1." days")); 

	
		


	//$fecha = "2021-08-25";
	if(isset($_POST['idUsuario']) && $_POST['idUsuario']){
		$idUsuario = $_POST['idUsuario'];
		
	}else{
		$idUsuario = "";
	}
	
	
	
	//REGRESAR ESTADO DE LA RUTA
	
	$query="select distinct u.USER_SNR,u.USER_NR,u.LNAME,st.NAME from users u
inner join USER_TERRIT ut on u.USER_SNR = ut.USER_SNR and u.rec_stat = 0 and ut.REC_STAT = 0
inner join inst i on ut.INST_SNR = i.INST_SNR and i.REC_STAT = 0
inner join city c on c.CITY_SNR = i.CITY_SNR and c.REC_STAT = 0
inner join state st on st.STATE_SNR = c.STATE_SNR ";

if($idUsuario != ''){
		$query .= " where u.USER_SNR = '".$idUsuario."' ";
		
	}
	//echo $query;
	
	$rs = sqlsrv_query($conn, $query);

 $estado="";
 $user="";
 $user_nr="";
 
while($est = sqlsrv_fetch_array($rs)){
		$estado=$est['NAME'];
		$user=$est['USER_SNR'];
		$user_nr=$est['USER_NR'];
	}
	

	if(isset($_POST['inicio']) && $_POST['inicio']){
		$inicio = $_POST['inicio'];
	}else{
		$inicio = "";
	}
	
	if($inicio==1){
	$estado="";
}
	//END REGRESAR EL ESTADO
	
	
	if($user==""){
		$user="00000000-0000-0000-0000-000000000000";
	}
	
	
	
	//FUNCION PINTAR POR ESTADO 
	function ColorearEstado($Estado,$Color){
		echo "$('.".$Estado."').css({'fill':'".$Color."'});";
	}	
	

$queryPintaEstado="SELECT
CL.NAME_SHORT LINEA, U.LNAME+' '+U.FNAME REPRESENTANTE,
COUNT(DISTINCT VP.PERS_SNR) VISITAS, COUNT(DISTINCT VPP.PERS_SNR) VISITAS_PRES,
COUNT(DISTINCT VPV.PERS_SNR) VISITAS_VIRT,
CAST(COUNT(PLW.PERS_SNR) AS FLOAT) UNIVERSO,
SUM(DISTINCT U.PHYSICIANS_GOAL/20) CUOTA, EDO.NAME ESTADO,
ROUND(COUNT(DISTINCT VP.PERS_SNR) /CAST( CL.DAILY_CONTACTS AS FLOAT) * 100,2 ) as PORCENTAJE
FROM VISITPERS VP 
INNER JOIN PERSLOCWORK PLW ON VP.PWORK_SNR=PLW.PWORK_SNR 
INNER JOIN INST I ON I.INST_SNR=PLW.INST_SNR 
INNER JOIN CITY CY ON CY.CITY_SNR=I.CITY_SNR 
INNER JOIN DISTRICT DTO ON CY.DISTR_SNR=DTO.DISTR_SNR 
INNER JOIN STATE EDO ON EDO.STATE_SNR=DTO.STATE_SNR
INNER JOIN USERS U ON U.USER_SNR=VP.USER_SNR AND U.REC_STAT=0 AND U.USER_TYPE IN (1,4) 
INNER JOIN COMPLINE CL ON CL.CLINE_SNR=U.CLINE_SNR 
LEFT OUTER JOIN VISITPERS VPP ON VP.PERS_SNR=VPP.PERS_SNR AND VPP.REC_STAT=0 AND VPP.VISIT_DATE = CAST(dateadd(day,".$v1.",GETDATE()) AS DATE) AND VPP.VISIT_CODE_SNR='2B3A7099-AC7D-47A3-A274-F0B029791801' 
LEFT OUTER JOIN VISITPERS VPV ON VP.PERS_SNR=VPV.PERS_SNR AND VPV.REC_STAT=0 AND VPV.VISIT_DATE =CAST(dateadd(DAY,".$v1.",GETDATE()) AS DATE) AND VPV.VISIT_CODE_SNR='5655BC78-6002-4097-82CC-8BA7E1FBD5FC' 
WHERE VP.REC_STAT=0 AND VP.VISIT_DATE = CAST(dateadd(DAY,".$v1.",GETDATE()) AS DATE) AND EDO.NAME<>'' 
AND U.USER_SNR = (SELECT USER_SNR FROM USERS WHERE USER_NR='".$user_nr."') 
GROUP BY CL.NAME_SHORT, U.LNAME, U.FNAME, EDO.NAME,CL.DAILY_CONTACTS ORDER BY EDO.NAME ";

//echo $queryPintaEstado;

	
	
	if($idUsuario != ''){
		
	}
	
	//Query US
	
	$queryUs = "select top(1) u.lname + ' ' + u.fname as nombre, 
		ut.LATITUDE, 
		ut.LONGITUDE, 
		convert(char(5), ut.CREATION_TIMESTAMP, 108) as hora,
		CREATION_TIMESTAMP,
		CONVERT(VARCHAR,CREATION_TIMESTAMP,113) AS CREATION_TIMESTAMP2 
		from USER_TRACKING ut
		inner join users u on u.USER_SNR = ut.USER_SNR
		where CREATION_TIMESTAMP between '".$fecha." 00:00:00' and '".$fecha." 23:59:59' ";
	if($idUsuario != ''){
		$queryUs .= " and u.user_snr = '".$idUsuario."' ";
		
	}
	$queryUs .= " order by CREATION_TIMESTAMP desc";
	
	echo $queryUs;
	
	$rs2 = sqlsrv_query($conn, $queryUs, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));


		$rows=sqlsrv_num_rows($rs2);
	if($rows>0){
		while($uSinc = sqlsrv_fetch_array($rs2)){
		
		$us=$uSinc['CREATION_TIMESTAMP2'];
			
		
		
		
		
	}
	}else{
		$us=" ";
	}
	
	

	
	
	echo "<script>$('#lblUltimaSinc').text('".$us."');</script>";
	
	
	
	//End Query US
	
	

	
	$queryP = "select distinct u.lname + ' ' + u.fname as nombre, 
		ut.LATITUDE, 
		ut.LONGITUDE, 
		convert(char(5), ut.CREATION_TIMESTAMP, 108) as hora
		from USER_TRACKING ut
		inner join users u on u.USER_SNR = ut.USER_SNR
		where CREATION_TIMESTAMP between '".$fecha." 00:00:00' and '".$fecha." 23:59:59'";
		
		
	if($idUsuario != ''){
		$queryP .= " and u.user_snr = '".$idUsuario."' ";
		$zoom = 12;
	}else{
		$zoom = 5;
	}
	$queryP .= " order by hora asc";
	//echo $queryP;
	//echo $zoom;
	
	$rs = sqlsrv_query($conn, $queryP);

	$lugares = array();
	$lineas = "";
	
	while($reg = sqlsrv_fetch_array($rs)){
		$lugares[] = array($reg['LATITUDE'], $reg['LONGITUDE'], $reg['nombre'], $reg['hora']);
		$lineas .= "{lat: ".$reg['LATITUDE'].", lng: ".$reg['LONGITUDE']."},";
	}
	
	
	echo "<script>";
	
	
	echo "deleteMarkersRuteo(markersRadar, lineasRuteo);
 
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
				zoom: ".$zoom.",
				center: new google.maps.LatLng(".$lugares[0][0].", ".$lugares[0][1]."),
			});
			
			infoWindow = new google.maps.InfoWindow();
 
			google.maps.event.addListener(map, 'click', function(){
				closeInfoWindow();
			});";
			
			for($i=0;$i<count($lugares);$i++){
				echo "var pinColor = '0854f8';";
				if($i-1==0){
					echo "var pinImage = new google.maps.MarkerImage('https://www.smart-scale.net/markers/marker_red.png');";
				}else if($i+1==count($lugares)){
					echo "var pinImage = new google.maps.MarkerImage('https://www.smart-scale.net/markers/marker_green.png');";
				}else{
				echo "	var pinImage = new google.maps.MarkerImage('https://www.smart-scale.net/markers/marker_marine.png');";
				}
				
				echo "
					var pinShadow = new google.maps.MarkerImage('https://www.smart-scale.net/markers/marker_marine.png',
						new google.maps.Size(40, 37),
						new google.maps.Point(0, 0),
						new google.maps.Point(12, 35));

					
					marker".$i." = new google.maps.Marker({
						position: new google.maps.LatLng(".$lugares[$i][0].", ".$lugares[$i][1]."),
						map: map,
						icon: pinImage, 
						shadow: pinShadow,
						title: '".utf8_encode($lugares[$i][2])."'
					});
					
					google.maps.event.addListener(marker".$i.", 'click', function(){
						openInfoWindow(marker".$i.", '<b>".utf8_encode($lugares[$i][2])."</b><br>".$lugares[$i][3]."');
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
	//echo "<script>$('#screen').hide();</script>";
	//echo $iconos;
	//print_r($lugares);
/*
	if($sltRepre==0){
		echo "<script>$('#lblUltimaSinc2').hide();
		$('#lblUltimaSinc').hide();</script>";
	}
	*/
?>