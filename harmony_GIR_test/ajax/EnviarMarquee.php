<?php
include "../conexion.php";
$tipoUsuario=$_POST['tipoUsuario'];
$idUsuario = $_POST['idUsuario'];
							
							if($tipoUsuario == 5){
	
	$qLineaG = "select distinct users.cline_snr,COMPLINE.name from users
				inner join compline on COMPLINE.CLINE_SNR=users.CLINE_SNR
				where user_type=4 and user_snr in (select kloc_snr from KLOC_REG where reg_snr='".$idUsuario."') ";
				$rsLineaG = sqlsrv_query($conn, $qLineaG);
				$lineas='';
								while($lineaG = sqlsrv_fetch_array($rsLineaG)){
											$lineas.=$lineaG['cline_snr'].',';
										}
										$lineasF=substr($lineas, 0, -1);
										$idsLineas=str_replace(",","','",$lineasF);
	
	
	//Consulta General GERENTE
	$QueryGeneral="SELECT 
LINEA.NAME AS LINEA,U.USER_NR+' - '+ U.LNAME+' '+U.FNAME AS REPRESENTANTE, 
COUNT(DISTINCT VP.PERS_SNR) VISITAS, 
COUNT(DISTINCT VPP.PERS_SNR) VISITAS_PRES,
COUNT(DISTINCT VPV.PERS_SNR) VISITAS_VIRT,
count(distinct U.USER_SNR) REPRES, CYC.CONTACTS CUOTA_DIA, CYC.DAYS DIAS_CICLO, count(distinct U.USER_SNR)* CYC.CONTACTS * CYC.DAYS DIAS_EDO, 
U.PHYSICIANS_GOAL CUOTA_USUARIO,
EDO.NAME ESTADO 
,ROUND(COUNT(DISTINCT VP.PERS_SNR) /CAST( SUM(DISTINCT CYC.CONTACTS) AS FLOAT) * 100,2 ) as PORCIENTO
FROM PERSON P ";
$QueryGeneral.='INNER JOIN CYCLES CYC ON CYC.REC_STAT=0 AND CAST(dateadd("d",0,GETDATE()) AS DATE) BETWEEN CYC.START_DATE AND CAST(dateadd("d",0,CYC.FINISH_DATE) AS DATE)
LEFT OUTER JOIN VISITPERS VP ON P.PERS_SNR=VP.PERS_SNR AND VP.REC_STAT=0 AND VISIT_DATE = CAST(dateadd("d",0,GETDATE()) AS DATE)
INNER JOIN PERS_SREP_WORK PSW ON P.PERS_SNR=PSW.PERS_SNR AND PSW.REC_STAT=0
INNER JOIN INST I ON I.INST_SNR=PSW.INST_SNR AND I.REC_STAT=0
INNER JOIN USERS U ON U.USER_SNR=PSW.USER_SNR AND U.REC_STAT=0 AND U.STATUS=1 AND U.USER_TYPE=4
INNER JOIN COMPLINE LINEA ON U.CLINE_SNR=LINEA.CLINE_SNR 
INNER JOIN CITY CY ON CY.CITY_SNR=I.CITY_SNR AND CY.REC_STAT=0
INNER JOIN DISTRICT DTO ON CY.DISTR_SNR=DTO.DISTR_SNR AND DTO.REC_STAT=0
INNER JOIN STATE EDO ON EDO.STATE_SNR=DTO.STATE_SNR AND EDO.REC_STAT=0 
INNER JOIN CODELIST STUS  ON STUS.CLIST_SNR=P.STATUS_SNR ';
$QueryGeneral=$QueryGeneral.'
LEFT OUTER JOIN VISITPERS VPP ON P.PERS_SNR=VPP.PERS_SNR AND VPP.REC_STAT=0 AND VPP.VISIT_DATE = CAST(dateadd("d",0,GETDATE()) AS DATE)
LEFT OUTER JOIN VISITPERS VPV ON P.PERS_SNR=VPV.PERS_SNR AND VPV.REC_STAT=0 AND VPV.VISIT_DATE =CAST(dateadd("d",0,GETDATE()) AS DATE)';
$QueryGeneral=$QueryGeneral." AND VPV.VISIT_CODE_SNR<>'2B3A7099-AC7D-47A3-A274-F0B029791801'
WHERE
LINEA.CLINE_SNR in ('".$idsLineas."')
AND
P.REC_STAT=0
AND EDO.NAME<>''
AND STUS.NAME='ACTIVO'
GROUP BY  LINEA.NAME, U.LNAME, U.FNAME, U.PHYSICIANS_GOAL, EDO.NAME,CYC.CONTACTS, CYC.DAYS,U.USER_NR
ORDER BY EDO.NAME, LINEA.NAME, U.LNAME, U.FNAME";

	//echo $QueryGeneral;
	$rs = sqlsrv_query($conn, $QueryGeneral);
	$rsRows = sqlsrv_query($conn,$QueryGeneral, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	$rows=sqlsrv_num_rows($rsRows);
	
	//End Consulta General GERENTE
}else{
	//Consulta General ADMIN
	
	$QueryGeneral="SELECT 
LINEA.NAME AS LINEA,U.USER_NR+' - '+ U.LNAME+' '+U.FNAME AS REPRESENTANTE, 
COUNT(DISTINCT VP.PERS_SNR) VISITAS, 
COUNT(DISTINCT VPP.PERS_SNR) VISITAS_PRES,
COUNT(DISTINCT VPV.PERS_SNR) VISITAS_VIRT,
count(distinct U.USER_SNR) REPRES, CYC.CONTACTS CUOTA_DIA, CYC.DAYS DIAS_CICLO, count(distinct U.USER_SNR)* CYC.CONTACTS * CYC.DAYS DIAS_EDO, 
U.PHYSICIANS_GOAL CUOTA_USUARIO,
EDO.NAME ESTADO 
,ROUND(COUNT(DISTINCT VP.PERS_SNR) /CAST( SUM(DISTINCT CYC.CONTACTS) AS FLOAT) * 100,2 ) as PORCIENTO
FROM PERSON P ";
$QueryGeneral.='INNER JOIN CYCLES CYC ON CYC.REC_STAT=0 AND CAST(dateadd("d",0,GETDATE()) AS DATE) BETWEEN CYC.START_DATE AND CAST(dateadd("d",0,CYC.FINISH_DATE) AS DATE)
LEFT OUTER JOIN VISITPERS VP ON P.PERS_SNR=VP.PERS_SNR AND VP.REC_STAT=0 AND VISIT_DATE = CAST(dateadd("d",0,GETDATE()) AS DATE)
INNER JOIN PERS_SREP_WORK PSW ON P.PERS_SNR=PSW.PERS_SNR AND PSW.REC_STAT=0
INNER JOIN INST I ON I.INST_SNR=PSW.INST_SNR AND I.REC_STAT=0
INNER JOIN USERS U ON U.USER_SNR=PSW.USER_SNR AND U.REC_STAT=0 AND U.STATUS=1 AND U.USER_TYPE=4
INNER JOIN COMPLINE LINEA ON U.CLINE_SNR=LINEA.CLINE_SNR 
INNER JOIN CITY CY ON CY.CITY_SNR=I.CITY_SNR AND CY.REC_STAT=0
INNER JOIN DISTRICT DTO ON CY.DISTR_SNR=DTO.DISTR_SNR AND DTO.REC_STAT=0
INNER JOIN STATE EDO ON EDO.STATE_SNR=DTO.STATE_SNR AND EDO.REC_STAT=0 
INNER JOIN CODELIST STUS  ON STUS.CLIST_SNR=P.STATUS_SNR ';
$QueryGeneral=$QueryGeneral.'
LEFT OUTER JOIN VISITPERS VPP ON P.PERS_SNR=VPP.PERS_SNR AND VPP.REC_STAT=0 AND VPP.VISIT_DATE = CAST(dateadd("d",0,GETDATE()) AS DATE)
LEFT OUTER JOIN VISITPERS VPV ON P.PERS_SNR=VPV.PERS_SNR AND VPV.REC_STAT=0 AND VPV.VISIT_DATE =CAST(dateadd("d",0,GETDATE()) AS DATE)';
$QueryGeneral=$QueryGeneral." AND VPV.VISIT_CODE_SNR<>'2B3A7099-AC7D-47A3-A274-F0B029791801'
WHERE
P.REC_STAT=0
AND EDO.NAME<>''
AND STUS.NAME='ACTIVO'
GROUP BY  LINEA.NAME, U.LNAME, U.FNAME, U.PHYSICIANS_GOAL, EDO.NAME,CYC.CONTACTS, CYC.DAYS,U.USER_NR
ORDER BY EDO.NAME, LINEA.NAME, U.LNAME, U.FNAME";
	
	$rs = sqlsrv_query($conn, $QueryGeneral);
	$rsRows = sqlsrv_query($conn,$QueryGeneral, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	$rows=sqlsrv_num_rows($rsRows);
	
	//End Consulta General ADMIN
}
	$colorM="";
	//End Consulta General	
								echo "<marquee>"; 
									while($data = sqlsrv_fetch_array($rs)){
										
										if($data[11] >= 95){
											$colorM="green"; 
															}
		
										if($data[11] >=78 && $data[11] <= 94 ){
											$colorM="yellow"; 
																	}
										if($data[11] > 50 && $data[11] < 78){
											$colorM="orange";
																	}
										if($data[11] <= 50 ){
											$colorM="red";
														}
										
										echo "<font  color='".$colorM."'> Linea:".$data[0]." Representante: ".utf8_encode($data[1])." Porcentaje:".$data[11]."</font>";
										
										
									}
								echo "</marquee>";
								
								?>