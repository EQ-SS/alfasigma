<?php
	include "conexion.php";
?>
<html>
	<head>
	</head>
	<body>
		<?php
			header("Content-type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: filename=personas.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			$hoy = $_GET['hoy'];
			$ids = str_replace(",","','",$_GET['ids']);
			$visitados = $_GET['visitados'];
		
			if(isset($_GET['tipoPersona']) && $_GET['tipoPersona'] != '' && $_GET['tipoPersona'] != '00000000-0000-0000-0000-000000000000' && $_GET['tipoPersona'] != '0'){
				$tipoPersona = $_GET['tipoPersona'];
			}else{
				$tipoPersona = '';
			}
			if(isset($_GET['nombre']) && $_GET['nombre'] != ''){
				$nombre = $_GET['nombre'];
			}else{
				$nombre = '';
			}
			if(isset($_GET['paterno']) && $_GET['paterno'] != '' ){
				$paterno = $_GET['paterno'];
			}else{
				$paterno = '';
			}
			if(isset($_GET['materno']) && $_GET['materno'] != '' ){
				$materno = $_GET['materno'];
			}else{
				$materno = '';
			}
			/*if(isset($_GET['sexo']) && $_GET['sexo'] != '' && $_GET['sexo'] != '00000000-0000-0000-0000-000000000000'){
				$sexo = $_GET['sexo'];
			}else{
				$sexo = '';
			}*/
			if(isset($_GET['especialidad']) && $_GET['especialidad'] != '' && $_GET['especialidad'] != '00000000-0000-0000-0000-000000000000'){
				$especialidad = $_GET['especialidad'];
			}else{
				$especialidad = '';
			}
			if(isset($_GET['categoria']) && $_GET['categoria'] != '' && $_GET['categoria'] != '00000000-0000-0000-0000-000000000000'){
				$categoria = $_GET['categoria'];
			}else{
				$categoria = '';
			}
			if(isset($_GET['inst']) && $_GET['inst'] != '' ){
				$inst = $_GET['inst'];
			}else{
				$inst = '';
			}
			if(isset($_GET['dir']) && $_GET['dir'] != '' ){
				$dir = $_GET['dir'];
			}else{
				$dir = '';
			}
			if(isset($_GET['colonia']) && $_GET['colonia'] != '' ){
				$colonia = $_GET['colonia'];
			}else{
				$colonia = '';
			}
			if(isset($_GET['cp']) && $_GET['cp'] != '' ){
				$cpostal = $_GET['cp'];
			}else{
				$cpostal = '';
			}
			if(isset($_GET['del']) && $_GET['del'] != '' ){
				$poblacion = $_GET['del'];
			}else{
				$poblacion = '';
			}
			if(isset($_GET['estado']) && $_GET['estado'] != '' ){
				$estado = $_GET['estado'];
			}else{
				$estado = '';
			}
				
			$queryPersonas = "select 
				p.pers_snr PERS_SNR, 
				U.USER_NR RUTA,
				TIPO_PERS.NAME TIPO_PERS,
				p.LNAME,
				P.MOTHERS_LNAME,
				P.FNAME, 
				P.PROF_ID CEDULA,
				ESP.NAME as ESPECIALIDAD, 
				CATEG.NAME as CATEGORIA, 
				i.NAME as INSTITUCION, 
				i.STREET1 as CALLE,
				CPOSTAL.name as COLONIA, 
				CPOSTAL.ZIP AS CPOSTAL,
				POBLACION.NAME as POBLACION, 
				ESTADO.NAME as ESTADO,
				BRICKS.name as BRICK,
				FREC.name as FREC, 
				ESTATUS.NAME as ESTATUS,
				(SELECT COUNT(*) FROM VISITPERS VP, CYCLES CICLOS 
				WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND P.PERS_SNR=VP.PERS_SNR 
				AND '".$hoy."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
				AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE) as VISITAS
				from person P
				inner join PERS_SREP_WORK psw on P.pers_snr = psw.PERS_SNR
				left outer join USERS U ON U.USER_SNR=PSW.USER_SNR
				left outer join CODELIST ESP on p.SPEC_SNR = ESP.CLIST_SNR
				left outer join CODELIST CATEG on p.CATEGORY_SNR = categ.CLIST_SNR
				left outer join INST I on i.INST_SNR = psw.INST_SNR
				left outer join CITY CPOSTAL on CPOSTAL.CITY_SNR = i.CITY_SNR
				left outer join DISTRICT POBLACION on POBLACION.DISTR_SNR = CPOSTAL.DISTR_SNR
				left outer join STATE ESTADO on ESTADO.STATE_SNR = POBLACION.STATE_SNR
				left outer join BRICK BRICKS on BRICKS.BRICK_SNR = CPOSTAL.BRICK_SNR
				left outer join CODELIST FREC on FREC.CLIST_SNR = p.FRECVIS_SNR
				left outer join CODELIST ESTATUS on ESTATUS.CLIST_SNR = p.STATUS_SNR
				left outer join CODELIST TIPO_PERS on TIPO_PERS.CLIST_SNR=P.PERSTYPE_SNR
				where psw.USER_SNR in ('".$ids."')
				and p.REC_STAT = 0
				and psw.REC_STAT = 0
				and i.REC_STAT = 0";
				
		if($visitados == 'visitados'){
			$queryPersonas .= " and (SELECT COUNT(*) FROM VISITPERS VP, CYCLES CICLOS 
				WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND P.PERS_SNR=VP.PERS_SNR 
				AND '".$hoy."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
				AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE) > 0 ";
		}else if($visitados == 'no'){
			$queryPersonas .= " and (SELECT COUNT(*) FROM VISITPERS VP, CYCLES CICLOS 
				WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND P.PERS_SNR=VP.PERS_SNR 
				AND '".$hoy."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
				AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE) = 0 ";
		}else if($visitados == 're'){
			$queryPersonas .= " and (SELECT COUNT(*) FROM VISITPERS VP, CYCLES CICLOS 
				WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND P.PERS_SNR=VP.PERS_SNR 
				AND '".$hoy."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
				AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE) > 1 ";
		}
		
		if($tipoPersona != ''){
			$queryPersonas .= " and p.perstype_snr = '".$tipoPersona."' ";
		}
		if($nombre != ''){
			$queryPersonas .= " and p.fname like '%".$nombre."%' ";
		}
		if($paterno != '' ){
			$paterno .= " and p.lname like '%".$paterno."%' ";
		}
		if($materno != '' ){
			$queryPersonas .= " and P.mothers_lname like '%".$materno."%' ";
		}
		/*if($sexo != ''){
			$queryPersonas .= " and p.sex_snr = '".$sexo."' ";
		}*/
		if($especialidad != ''){
			$queryPersonas .= " and p.spec_snr = '".$especialidad."' ";
		}
		if($categoria != ''){
			$queryPersonas .= " and p.category_snr = '".$categoria."' ";
		}
		if($inst != '' ){
			$queryPersonas .= " and i.name like '%".$inst."%' ";
		}
		if($dir != '' ){
			$queryPersonas .= " and i.street1 like '%".$dir."%' ";
		}
		if($colonia != '' ){
			$queryPersonas .= " and CPOSTAL.NAME like '%".$colonia."%' ";
		}
		if($cpostal != '' ){
			$queryPersonas .= " and CPOSTAL.ZIP like '%".$cpostal."%' ";
		}
		if($poblacion != '' ){
			$queryPersonas .= " and POBLACION.NAME like '%".$poblacion."%' ";
		}
		if($estado != '' ){
			$queryPersonas .= " and ESTADO.NAME like '%".$estado."%' ";
		}
		
		$queryPersonas .= " order by RUTA, P.lname, P.mothers_lname, P.fname ";
		
		//echo $queryPersonas."<br>";

		$rsPersonas = sqlsrv_query($conn, $queryPersonas, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
?>
		<table width="100%" class="grid">
			<thead>
				<tr>
					<td>Ruta</td>
					<td>Codigo_Medico</td>
					<td>Tipo</td>
					<td>Apellido Paterno</td>
					<td>Apellido Materno</td>
					<td>Nombre</td>
					<td>Esp Primaria</td>
					<td>Categoria</td>
					<td>Cedula</td>
					<td>Calle</td>
					<td>Colonia</td>
					<td>CPostal</td>
					<td>Del/Mun</td>
					<td>Estado</td>
					<td>Brick</td>
					<td>Frec</td>
					<td>Vis</td>
					<td>Estatus</td>
				</tr>
			</thead>
			<tbody>
<?php

				while($persona = sqlsrv_fetch_array($rsPersonas)){
					$nombre = $persona['LNAME']." ".$persona['MOTHERS_LNAME']." ".$persona['FNAME'];
					$cargo = $persona['ESPECIALIDAD'];
					if($persona['FREC'] == $persona['VISITAS']){
						$circulo = 'circuloVerde';
					}else if($persona['VISITAS'] == 0){
						$circulo = 'circuloRojo';
					}else if($persona['VISITAS'] > 0 && $persona['VISITAS'] < $persona['FREC']){
						$circulo = 'circuloAmarillo';
					}
?>
					<tr>
						<td><?= $persona['RUTA'] ?></td>
						<td><?= $persona['PERS_SNR'] ?></td>
						<td><?= $persona['TIPO_PERS'] ?></td>
						<td><?= $persona['LNAME'] ?></td>
						<td><?= $persona['MOTHERS_LNAME'] ?></td>
						<td><?= $persona['FNAME'] ?></td>
						<td><?= $persona['ESPECIALIDAD'] ?></td>
						<td><?= $persona['CATEGORIA'] ?></td>
						<td><?= $persona['CEDULA'] ?></td>
						<td><?= $persona['CALLE'] ?></td>
						<td><?= $persona['COLONIA'] ?></td>
						<td><?= $persona['CPOSTAL'] ?></td>
						<td><?= $persona['POBLACION'] ?></td>
						<td><?= $persona['ESTADO'] ?></td>
						<td><?= $persona['BRICK'] ?></td>
						<td><?= $persona['FREC'] ?></td>
						<td><?= $persona['VISITAS'] ?></td>
						<td><?= $persona['ESTATUS'] ?></td>
					</tr>
<?php
				}
?>
			</tbody>
		</table>
	</body>
</head>