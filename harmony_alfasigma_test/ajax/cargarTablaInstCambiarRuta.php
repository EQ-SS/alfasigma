<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$registrosPorPagina = 20;
		$numPagina = $_POST['pagina'];
		$hoy = $_POST['hoy'];
		$ids = str_replace(",","','",$_POST['ids']);
		$ids = str_replace("'',''","','",$ids);
		$visitados = $_POST['visitados'];
		$seleccionadosCambiaRuta = explode(",",substr($_POST['seleccionadosCambiaRuta'],0,-1));
		//echo "ids: ".$ids."<br>";
		echo "<script>$('#hdnFiltrosExportar').val('".$visitados."');</script>";
		if(isset($_POST['tipoPersona']) && $_POST['tipoPersona'] != '' && $_POST['tipoPersona'] != '00000000-0000-0000-0000-000000000000' && $_POST['tipoPersona'] != '0'){
			$tipoPersona = $_POST['tipoPersona'];
		}else{
			$tipoPersona = '';
		}
		if(isset($_POST['nombre']) && $_POST['nombre'] != ''){
			$nombre = $_POST['nombre'];
		}else{
			$nombre = '';
		}
		if(isset($_POST['apellidos']) && $_POST['apellidos'] != '' ){
			$apellidos = $_POST['apellidos'];
		}else{
			$apellidos = '';
		}
		if(isset($_POST['especialidad']) && $_POST['especialidad'] != '' && $_POST['especialidad'] != '00000000-0000-0000-0000-000000000000'){
			$especialidad = $_POST['especialidad'];
		}else{
			$especialidad = '';
		}
		if(isset($_POST['categoria']) && $_POST['categoria'] != '' && $_POST['categoria'] != '00000000-0000-0000-0000-000000000000'){
			$categoria = $_POST['categoria'];
		}else{
			$categoria = '';
		}
		if(isset($_POST['inst']) && $_POST['inst'] != '' ){
			$inst = $_POST['inst'];
		}else{
			$inst = '';
		}
		if(isset($_POST['dir']) && $_POST['dir'] != '' ){
			$dir = $_POST['dir'];
		}else{
			$dir = '';
		}
		if(isset($_POST['del']) && $_POST['del'] != '' ){
			$del = $_POST['del'];
		}else{
			$del = '';
		}
		if(isset($_POST['estado']) && $_POST['estado'] != '' ){
			$estado = $_POST['estado'];
		}else{
			$estado = '';
		}
		if(isset($_POST['repre']) && $_POST['repre'] != ''){
			$repre = str_replace(",","','",substr($_POST['repre'], 0, -1));
		}else{
			$repre = '';
		}
		//echo "repre: ".$repre."<br>";
		$registroIni = $numPagina * $registrosPorPagina - $registrosPorPagina;

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
			I.HTTP AS WEB,
			I.EMAIL,
			I.INFO AS COMENTARIOS,
			I.LATITUDE AS LATITUD,
			I.LONGITUDE AS LONGITUD,
			UT.USER_SNR AS USUARIO_ID, 
			U.LNAME+' '+U.FNAME AS REPRESENTANTE,
			(SELECT COUNT(*) FROM VISITINST VP, CYCLES CICLOS 
			WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND I.INST_SNR=VP.INST_SNR 
			AND GETDATE() BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
			AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE) AS VISITAS, 
			I.STREET2 AS SUCURSAL 
			From INST I, CITY AS CP, DISTRICT POB, STATE EDO, INST_TYPE, COUNTRY AS PAIS, USER_TERRIT AS UT,
			USERS U 
			LEFT OUTER JOIN BRICK bri on bri.BRICK_SNR = BRICK_SNR
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
			and u.USER_SNR in ('".$ids."') ";
			
		if($tipo != ''){
			$queryInst .= " and INST_TYPE.NAME = '".$tipo."' ";
		}
		if($filtros != ''){
			$queryInst .= $filtros;
		}
		$queryInst .= " order by TIPO_INST, NOMBRE, DIRECCION ";
				
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
		if($apellidos != '' ){
			$queryPersonas .= " and p.lname + ' ' + p.mothers_lname like '%".$apellidos."%' ";
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
		if($del != '' ){
			$queryPersonas .= " and d.name like '%".$del."%' ";
		}
		if($estado != '' ){
			$queryPersonas .= " and state.name like '%".$estado."%' ";
		}
		if($repre != ''){
			$queryPersonas .= " and psw.USER_SNR in ('".$repre."')";
		}
		
		$queryPersonas .= " order by lname, p.mothers_lname, fname ";
		
		//echo $queryPersonas."<br>";
		
		//echo $queryText = addslashes($queryPersonas);
		
				
		$tope = "OFFSET ".$registroIni." ROWS 
				FETCH NEXT ".$registrosPorPagina." ROWS ONLY ";
					
		$rsPersonas = sqlsrv_query($conn, $queryPersonas, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			
		$totalRegistros = sqlsrv_num_rows($rsPersonas);
			
		$queryPersonas20 = sqlsrv_query($conn, $queryPersonas.$tope);
			
		$paginas = ceil($totalRegistros / $registrosPorPagina);
		
		$tabla = '<table width="100%" class="grid" border="0">
				<thead>
					<tr>
						<td width="1%">&nbsp;</td>
						<td>Seleccione</td>
						<td>Paterno</td>
						<td>Materno</td>
						<td>Nombre</td>
						<td>Especialidad</td>
						<td>Categ.</td>
						<td width="15%">Dirección</td>
						<td>Colonia</td>
						<td>Del./Mun.</td>
						<td>Estado</td>
						<td align="center">frec.</td>
						<td align="center">Visitas</td>
						<td align="right">Ruta</td>
					</tr>
				</thead>
				<tbody>';
					$i = 1;
					while($persona = sqlsrv_fetch_array($queryPersonas20)){
						$nombre = $persona['LNAME']." ".$persona['MOTHERS_LNAME']." ".$persona['FNAME'];
						$cargo = $persona['especialidad'];
						//echo "freq: ".$persona['freq']." ::: ".$persona['visitas']."<br>";
						if($persona['freq'] == $persona['visitas'] && $persona['visitas'] > 0){
							$circulo = 'circuloVerde';
						}else if($persona['visitas'] == 0){
							$circulo = 'circuloRojo';
						}else if($persona['visitas'] > 0 && $persona['visitas'] < $persona['freq']){
							$circulo = 'circuloAmarillo';
						}else{
							$circulo = 'circuloRojo';
						}
						$datosMedico = "<b>".$nombre."</b><br>".$cargo."<br>";
						$datosMedico .= $persona['institucion']." CALLE: ".$persona['calle'].", ";
						$datosMedico .= "C.P.: ".$persona['cp'].", COLONIA: ".$persona['colonia'].", ";
						$datosMedico .= "POBLACIÓN: ".$persona['delegacion'].", ";
						$datosMedico .= "ESTADO: ".$persona['estado'].".<br>";
						$datosMedico .= "BRICK: ".$persona['brick'];
						$tabla .= "<tr>
							<td onclick='presentaDatos(\"".$persona['pers_snr']."\",\"divDatosPersonales\",\"".$nombre."\",\"".$cargo."\");'>
							<div class='".$circulo."'></div>
						</td>
						<td>";
						if(in_array($persona['pers_snr'], $seleccionadosCambiaRuta)){
							$tabla .= "<input onClick=\"seleccionaCambiarRutaPersona('".$persona['pers_snr']."','chkCambiarRutaPersona".$i."');\" type=\"checkbox\" id=\"chkCambiarRutaPersona".$i."\" checked />";
						}else{
							$tabla .= "<input onClick=\"seleccionaCambiarRutaPersona('".$persona['pers_snr']."','chkCambiarRutaPersona".$i."');\" type=\"checkbox\" id=\"chkCambiarRutaPersona".$i."\" />";
						}
						$tabla .= "</td>
						<td onclick='presentaDatos(\"".$persona['pers_snr']."\",\"divDatosPersonales\",\"".$nombre."\",\"".$cargo."\");'>
							".$persona['LNAME']."
						</td>
						<td onclick='presentaDatos(\"".$persona['pers_snr']."\",\"divDatosPersonales\",\"".$nombre."\",\"".$cargo."\");'>
							".$persona['MOTHERS_LNAME']."
						</td>
						<td onclick='presentaDatos(\"".$persona['pers_snr']."\",\"divDatosPersonales\",\"".$nombre."\",\"".$cargo."\");'>
							".$persona['FNAME']."
						</td>
						<td onclick='presentaDatos(\"".$persona['pers_snr']."\",\"divDatosPersonales\",\"".$nombre."\",\"".$cargo."\");'>
							".$persona['especialidad']."
						</td>
						<td onclick='presentaDatos(\"".$persona['pers_snr']."\",\"divDatosPersonales\",\"".$nombre."\",\"".$cargo."\");'>
							".$persona['categoria']."
						</td>
						<td onclick='presentaDatos(\"".$persona['pers_snr']."\",\"divDatosPersonales\",\"".$nombre."\",\"".$cargo."\");'>
							".$persona['calle']."
						</td>
						<td onclick='presentaDatos(\"".$persona['pers_snr']."\",\"divDatosPersonales\",\"".$nombre."\",\"".$cargo."\");'>
							".$persona['colonia']."
						</td>
						<td onclick='presentaDatos(\"".$persona['pers_snr']."\",\"divDatosPersonales\",\"".$nombre."\",\"".$cargo."\");'>
							".$persona['delegacion']."
						</td>
						<td onclick='presentaDatos(\"".$persona['pers_snr']."\",\"divDatosPersonales\",\"".$nombre."\",\"".$cargo."\");'>
							".$persona['estado']."
						</td>
						<td align='center' onclick='presentaDatos(\"".$persona['pers_snr']."\",\"divDatosPersonales\",\"".$nombre."\",\"".$cargo."\");'>
							".$persona['freq']."
						</td>
						<td align='center' onclick='presentaDatos(\"".$persona['pers_snr']."\",\"divDatosPersonales\",\"".$nombre."\",\"".$cargo."\");'>
							".$persona['visitas']."
						</td>
						<td align='right' onclick='presentaDatos(\"".$persona['pers_snr']."\",\"divDatosPersonales\",\"".$nombre."\",\"".$cargo."\");'>
							".$persona['ruta']."
						</td>
					</tr>
						";
						$i++;
					}
					
				$tabla .= '</tbody>';
				if($totalRegistros > $registrosPorPagina){
					$tabla .= "<tfoot><tr><td colspan='15' align='center'>";
					$idsEnviar = str_replace("'","",$ids);
					if($numPagina > 1){
						$anterior = $numPagina - 1;
						$tabla .= "<a href='#' onClick='nuevaPagina(1,\"".$hoy."\",\"".$idsEnviar."\",\"".$visitados."\");'>inicio</a>&nbsp;&nbsp;";
						$tabla .= "<a href='#' onClick='nuevaPagina(".$anterior.",\"".$hoy."\",\"".$idsEnviar."\",\"".$visitados."\");'>anterior</a>&nbsp;&nbsp;";
					}
					$antes = $numPagina-5;
					$despues = $numPagina+5;
					for($i=1;$i<=$paginas;$i++){
						if($i == $numPagina){
							$tabla .= $i."&nbsp;&nbsp;";
						}else{
							if($i > $despues || $i < $antes){
								//$tabla .= "<a href='#' onClick='nuevaPagina(".$i.",\"".$hoy."\",\"".$idsEnviar."\",\"".$visitados."\");'>".$i."</a>&nbsp;&nbsp;";
							}
						}
					}
					if($numPagina < $paginas){
						$siguiente = $numPagina + 1;
						$tabla .= "<a href='#' onClick='nuevaPagina(".$siguiente.",\"".$hoy."\",\"".$idsEnviar."\",\"".$visitados."\");'>Siguiente</a>&nbsp;&nbsp;";
						$tabla .= "<a href='#' onClick='nuevaPagina(".$paginas.",\"".$hoy."\",\"".$idsEnviar."\",\"".$visitados."\");'>Fin</a>&nbsp;&nbsp;";
					}
					$tabla .= "Pag. ".$numPagina." de ".$paginas."&nbsp;&nbsp;&nbsp; Registros : ".$totalRegistros;
					$tabla .= "</td></tr></tfoot>";
				}else{
					$tabla .= "<tfoot><tr><td colspan='15' align='center'>";
					$tabla .= "Registros : ".$totalRegistros;
					$tabla .= "</td></tr></tfoot>";
				}
				$tabla .= "</table>";
				echo $tabla;
	}
	
?>