<?php
	include "conexion.php";
?>
<html>
	<head>
	</head>
	<body>
		<?php
			header("Content-type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: filename=instituciones.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			$hoy = $_GET['hoy'];
			$ids = str_replace(",","','",$_GET['ids']);
			$visitados = $_GET['visitados'];
		
			if(isset($_GET['tipoInst']) && $_GET['tipoInst'] != '' && $_GET['tipoInst'] != '00000000-0000-0000-0000-000000000000' && $_GET['tipoInst'] != '0'){
				$tipoInst = $_GET['tipoInst'];
			}else{
				$tipoInst = '';
			}
			if(isset($_GET['nombreInst']) && $_GET['nombreInst'] != ''){
				$nombreInst = $_GET['nombreInst'];
			}else{
				$nombreInst = '';
			}
			if(isset($_GET['calleInst']) && $_GET['calleInst'] != '' ){
				$calleInst = $_GET['calleInst'];
			}else{
				$calleInst = '';
			}
			if(isset($_GET['coloniaInst']) && $_GET['coloniaInst'] != '' ){
				$coloniaInst = $_GET['coloniaInst'];
			}else{
				$coloniaInst = '';
			}
			if(isset($_GET['ciudadInst']) && $_GET['ciudadInst'] != '' ){
				$ciudadInst = $_GET['ciudadInst'];
			}else{
				$ciudadInst = '';
			}
			if(isset($_GET['estadoInst']) && $_GET['estadoInst'] != '' ){
				$estadoInst = $_GET['estadoInst'];
			}else{
				$estadoInst = '';
			}
			if(isset($_GET['cpInst']) && $_GET['cpInst'] != '' ){
				$cpInst = $_GET['cpInst'];
			}else{
				$cpInst = '';
			}
			
			$queryInst = "SELECT 
			I.INST_SNR,
			INST_TYPE.NAME AS TIPO_INST,
			I.NAME AS NOMBRE,
			I.STREET1 AS DIRECCION,
			CP.NAME AS COLONIA,
			CP.ZIP AS CPOSTAL,
			POB.NAME AS POBLACION,
			EDO.NAME AS ESTADO,
			PAIS.NAME AS PAIS,
			bri.name as BRICK,
			I.TEL1,
			I.TEL2,
			I.WEB,
			I.EMAIL1 as EMAIL,
			I.INFO AS COMENTARIOS,
			I.LATITUDE AS LATITUD,
			I.LONGITUDE AS LONGITUD,
			UT.USER_SNR AS USUARIO_ID, 
			U.LNAME+' '+U.FNAME AS REPRESENTANTE,
			(SELECT COUNT(*) FROM VISITINST VP, CYCLES CICLOS 
			WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND I.INST_SNR=VP.INST_SNR 
			AND GETDATE() BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
			AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE) AS VISITAS/*, 
			I.STREET2 AS SUCURSAL */
			From INST I, CITY AS CP, DISTRICT POB, STATE EDO, INST_TYPE, COUNTRY AS PAIS, USER_TERRIT AS UT,
			USERS U,  BRICK bri 
			WHERE I.REC_STAT=0 
			AND INST_TYPE.INST_TYPE=I.INST_TYPE 
			AND I.CITY_SNR=CP.CITY_SNR 
			AND PAIS.CTRY_SNR=CP.CTRY_SNR 
			AND POB.DISTR_SNR=CP.DISTR_SNR 
			AND EDO.STATE_SNR=CP.STATE_SNR 
			AND UT.INST_SNR=I.INST_SNR 
			AND UT.REC_STAT=0 
			AND U.USER_SNR=UT.USER_SNR 
			AND I.INST_SNR<>'00000000-0000-0000-0000-000000000000' 
			and u.USER_SNR in ('".$ids."') 
			and bri.BRICK_SNR = CP.BRICK_SNR";
			
		if($tipoInst != '' && $tipoInst != '00000000-0000-0000-0000-000000000000'){
			$queryInst .= " and INST_TYPE.INST_TYPE = '".$tipoInst."' ";
		}
		
		if($nombreInst != ''){
			$queryInst .= " and I.NAME like '%".$nombre."%' ";
		}
		
		if($calleInst != ''){
			$queryInst .= " and I.STREET1 like '%".$calle."%' ";
		}
		
		if($coloniaInst != ''){
			$queryInst .= " and CP.NAME like '%".$colonia."%' ";
		}
		
		if($ciudadInst != ''){
			$queryInst .= " and POB.NAME like '%".$ciudad."%' ";
		}
		
		if($estadoInst != ''){
			$queryInst .= " and EDO.NAME like '%".$estado."%' ";
		}
		
		if($cpInst != ''){
			$queryInst .= " and CP.ZIP = '".$cp."' ";
		}
		
		if($visitados == 'visitados'){
			$queryInst .= " and (SELECT COUNT(*) FROM VISITINST VP, CYCLES CICLOS 
			WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND I.INST_SNR=VP.INST_SNR 
			AND GETDATE() BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
			AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE) > 0 ";
		}else if($visitados == 'no'){
			$queryInst .= " and (SELECT COUNT(*) FROM VISITINST VP, CYCLES CICLOS 
			WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND I.INST_SNR=VP.INST_SNR 
			AND GETDATE() BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
			AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE) = 0 ";
		}else if($visitados == 're'){
			$queryInst .= " and (SELECT COUNT(*) FROM VISITINST VP, CYCLES CICLOS 
			WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND I.INST_SNR=VP.INST_SNR 
			AND GETDATE() BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
			AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE) > 1 ";
		}
		
		$queryInst .= " order by TIPO_INST, NOMBRE, DIRECCION ";
		
		//echo $queryInst;
					
		$rsInst = sqlsrv_query($conn, $queryInst, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
?>
		<table width="100%" class="grid">
			<tr>
				<td>Tipo</td>
				<td>Nombre</td>
				<td>Dirección</td>
				<td>Colonia</td>
				<td>C.P.</td>
				<td>Municipio</td>
				<td>Estado</td>
				<td>Brick</td>
			</tr>
<?php

		while($inst = sqlsrv_fetch_array($rsInst)){
?>
			<tr>
				<td><?= $inst['TIPO_INST'] ?></td>
				<td><?= $inst['NOMBRE'] ?></td>
				<td><?= $inst['DIRECCION'] ?></td>
				<td><?= $inst['COLONIA'] ?></td>
				<td><?= $inst['CPOSTAL'] ?></td>
				<td><?= $inst['POBLACION'] ?></td>
				<td><?= $inst['ESTADO'] ?></td>
				<td><?= $inst['BRICK'] ?></td>
			</tr>
<?php
		}
?>
		</table>
	</body>
</head>