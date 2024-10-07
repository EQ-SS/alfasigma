<?php
include "../conexion.php";

$comboLinea=$_POST['comboLinea'];
$tipoUsuario=$_POST['tipoUsuario'];
$idUsuario2 =$_POST['idUsuario2'];

//echo "<br>TU".$tipoUsuario;
//echo "<br>ID".$idUsuario2;
if($tipoUsuario==5){
	$QueryIdsEstado="SELECT COUNT(DISTINCT VP.PERS_SNR) VISITAS,CAST(COUNT(PSW.PERS_SNR) AS FLOAT) UNIVERSO, 
SUM(DISTINCT CYC.CONTACTS) CUOTA, EDO.NAME ESTADO,
ROUND(COUNT(DISTINCT VP.PERS_SNR) / CAST( count(distinct U.USER_SNR) * SUM(DISTINCT CYC.CONTACTS)   AS FLOAT) * 100,2 )  as PORCENTAJE 
FROM PERSON P ";
$QueryIdsEstado.=' INNER JOIN CYCLES CYC ON CYC.REC_STAT=0 AND CAST(GETDATE() AS DATE) BETWEEN CYC.START_DATE AND CAST(dateadd("d",0,CYC.FINISH_DATE) AS DATE) ';
$QueryIdsEstado.="LEFT OUTER JOIN VISITPERS VP ON P.PERS_SNR=VP.PERS_SNR AND VP.REC_STAT=0 
AND VISIT_DATE = CAST(GETDATE() AS DATE)LEFT OUTER JOIN PERS_SREP_WORK PSW ON P.PERS_SNR=PSW.PERS_SNR AND PSW.REC_STAT=0
LEFT OUTER JOIN INST I ON I.INST_SNR=PSW.INST_SNR AND I.REC_STAT=0
LEFT OUTER JOIN USERS U ON U.USER_SNR=PSW.USER_SNR AND U.REC_STAT=0 AND U.STATUS=1 AND U.USER_TYPE=4
INNER JOIN CITY CY ON CY.CITY_SNR=I.CITY_SNR AND CY.REC_STAT=0
INNER JOIN DISTRICT DTO ON CY.DISTR_SNR=DTO.DISTR_SNR AND DTO.REC_STAT=0
INNER JOIN STATE EDO ON EDO.STATE_SNR=DTO.STATE_SNR AND EDO.REC_STAT=0 ";
$QueryIdsEstado.="INNER JOIN KLOC_REG KR ON KR.KLOC_SNR=U.USER_SNR AND KR.REC_STAT=0 AND KR.REG_SNR= '".$idUsuario2."'";
$QueryIdsEstado.="WHERE
P.REC_STAT=0
AND EDO.NAME<>''
GROUP BY EDO.NAME
ORDER BY EDO.NAME";
//echo $QueryIdsEstado;
$rsRows = sqlsrv_query($conn,$QueryIdsEstado, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
$rows=sqlsrv_num_rows($rsRows);
echo "<br>rows".$rows;

$rsEst = sqlsrv_query($conn,$QueryIdsEstado);



$universo= array();
$visitas= array();
$porcentaje=array();
$color= array();
$estado=array();

while($idsrutas = sqlsrv_fetch_array($rsEst)){
		
		$universo[]=$idsrutas['UNIVERSO'];
		$visitas[]=$idsrutas['VISITAS'];
		$porcentaje[]=round($idsrutas['PORCENTAJE']);
		$estado[]=$idsrutas['ESTADO'];
		
	
	}
	

	for($i=0;$i<$rows;$i++){
		if($porcentaje[$i] >= 95){
		$color[$i]="green"; 
		}
		
	if($porcentaje[$i] >=78 && $porcentaje[$i] <= 94 ){
		$color[$i]="yellow"; 
		}
	if($porcentaje[$i] > 50 && $porcentaje[$i] < 78){
		$color[$i]="orange";
		}
	if($porcentaje[$i] <= 50 ){
		$color[$i]="red";
	}
		
	}
	
	for($c=0;$c<$rows;$c++){
		if($estado[$c]=='BAJA CALIFORNIA'){
			$estado[$c]="BAJA_CALIFORNIA";
		}
		if($estado[$c]=='BAJA CALIFORNIA SUR'){
			$estado[$c]="BAJA_CALIFORNIA_SUR";
		}
		if($estado[$c]=='CIUDAD DE MEXICO'){
			$estado[$c]="CIUDAD_DE_MEXICO";
		}
		if($estado[$c]=='NUEVO LEON'){
			$estado[$c]="NUEVO_LEON";
		}
		if($estado[$c]=='QUINTANA ROO'){
			$estado[$c]="QUINTANA_ROO";
		}
		if($estado[$c]=='SAN LUIS POTOSI'){
			$estado[$c]="SAN_LUIS_POTOSI";
		}
	}
	/*
	$estado[1]="BAJA_CALIFORNIA";
	$estado[2]="BAJA_CALIFORNIA_SUR";
	$estado[6]="CIUDAD_DE_MEXICO";
	$estado[18]="NUEVO_LEON";
	$estado[22]="QUINTANA_ROO";
	$estado[23]="SAN_LUIS_POTOSI";
	*/
	
	
	//print_r($estado);
	
	
	echo "<script>$('.mapadiv path').css({'fill':'#d6eaf8'});</script>";
	for($j=0;$j<$rows;$j++){
		
		echo "<script>$('.".$estado[$j]."').css({'fill':'".$color[$j]."'});</script>";
	}
	
	//echo "<script>$('#screen').hide();</script>";
}else{
	$QueryIdsEstado="SELECT COUNT(DISTINCT VP.PERS_SNR) VISITAS,CAST(COUNT(PSW.PERS_SNR) AS FLOAT) UNIVERSO, 
SUM(DISTINCT U.PHYSICIANS_GOAL/20) CUOTA, EDO.NAME ESTADO,
ROUND(COUNT(DISTINCT VP.PERS_SNR) /CAST( count(distinct P.PERS_SNR ) AS FLOAT) * 100,2 ) as PORCENTAJE FROM PERSON P 
LEFT OUTER JOIN VISITPERS VP ON P.PERS_SNR=VP.PERS_SNR AND VP.REC_STAT=0 
AND VISIT_DATE = CAST(GETDATE() AS DATE)LEFT OUTER JOIN PERS_SREP_WORK PSW ON P.PERS_SNR=PSW.PERS_SNR AND PSW.REC_STAT=0
LEFT OUTER JOIN INST I ON I.INST_SNR=PSW.INST_SNR AND I.REC_STAT=0
LEFT OUTER JOIN USERS U ON U.USER_SNR=PSW.USER_SNR AND U.REC_STAT=0 AND U.STATUS=1 AND U.USER_TYPE=4
INNER JOIN CITY CY ON CY.CITY_SNR=I.CITY_SNR AND CY.REC_STAT=0
INNER JOIN DISTRICT DTO ON CY.DISTR_SNR=DTO.DISTR_SNR AND DTO.REC_STAT=0
INNER JOIN STATE EDO ON EDO.STATE_SNR=DTO.STATE_SNR AND EDO.REC_STAT=0 
WHERE
P.REC_STAT=0
AND EDO.NAME<>''
GROUP BY EDO.NAME
ORDER BY EDO.NAME";

$rsEst = sqlsrv_query($conn,$QueryIdsEstado);

$universo= array();
$visitas= array();
$porcentaje=array();
$color= array();
$estado=array();

while($idsrutas = sqlsrv_fetch_array($rsEst)){
		
		$universo[]=$idsrutas['UNIVERSO'];
		$visitas[]=$idsrutas['VISITAS'];
		$porcentaje[]=round($idsrutas['PORCENTAJE']);
		$estado[]=$idsrutas['ESTADO'];
		
	
	}
	

	for($i=0;$i<32;$i++){
		if($porcentaje[$i] >= 95){
		$color[$i]="green"; 
		}
		
	if($porcentaje[$i] >=78 && $porcentaje[$i] <= 94 ){
		$color[$i]="yellow"; 
		}
	if($porcentaje[$i] > 50 && $porcentaje[$i] < 78){
		$color[$i]="orange";
		}
	if($porcentaje[$i] <= 50 ){
		$color[$i]="red";
	}
		
	}
	
	$estado[1]="BAJA_CALIFORNIA";
	$estado[2]="BAJA_CALIFORNIA_SUR";
	$estado[6]="CIUDAD_DE_MEXICO";
	$estado[18]="NUEVO_LEON";
	$estado[22]="QUINTANA_ROO";
	$estado[23]="SAN_LUIS_POTOSI";
	
	
	//print_r($estado);
	
	
	echo "<script>$('.mapadiv path').css({'fill':'#F0E68C'});</script>";
	for($j=0;$j<32;$j++){
		
		echo "<script>$('.".$estado[$j]."').css({'fill':'".$color[$j]."'});</script>";
	}
	
	
}


	
	
	
	
	
?>