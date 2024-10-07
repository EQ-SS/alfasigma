<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$tipo = $_POST['tipo'];
		//echo "tipo: ".$tipo."<br>";
		$numPagina = $_POST['pagina'];
		$ids = str_replace(",","','",$_POST['ids']);
		$hoy = $_POST['hoy'];
		
		if(isset($_POST['nombre']) && $_POST['nombre'] != ''){
			$nombre = $_POST['nombre'];
		}else{
			$nombre = '';
		}
		
		if(isset($_POST['calle']) && $_POST['calle'] != ''){
			$calle = $_POST['calle'];
		}else{
			$calle = '';
		}
		
		if(isset($_POST['colonia']) && $_POST['colonia'] != ''){
			$colonia = $_POST['colonia'];
		}else{
			$colonia = '';
		}
		
		if(isset($_POST['ciudad']) && $_POST['ciudad'] != ''){
			$ciudad = $_POST['ciudad'];
		}else{
			$ciudad = '';
		}
		
		if(isset($_POST['estado']) && $_POST['estado'] != ''){
			$estado = $_POST['estado'];
		}else{
			$estado = '';
		}
		
		if(isset($_POST['cp']) && $_POST['cp'] != ''){
			$cp = $_POST['cp'];
		}else{
			$cp = '';
		}
		
		if(isset($_POST['visitados']) && $_POST['visitados'] != ''){
			$visitados = $_POST['visitados'];
		}else{
			$visitados = '';
		}
		if(isset($_POST['repre']) && $_POST['repre'] != ''){
			$repre = str_replace(",","','",substr($_POST['repre'], 0, -1));
		}else{
			$repre = '';
		}
		
		if(isset($_POST['geolocalizados']) && $_POST['geolocalizados'] != ''){
			$geolocalizados = $_POST['geolocalizados'];
		}else{
			$geolocalizados = '';
		}
		
		echo "<script>$('#hdnFiltrosExportarInst').val('".$visitados."');</script>";
		
		$registrosPorPagina = 20;
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
			LEFT OUTER JOIN IMS_BRICK bri on bri.IMSBRICK_SNR = IMSBRICK_SNR
			WHERE I.REC_STAT=0 
			AND INST_TYPE.INST_TYPE=I.INST_TYPE 
			AND I.CITY_SNR=CP.CITY_SNR 
			AND PAIS.CTRY_SNR=CP.CTRY_SNR 
			AND POB.DISTR_SNR=CP.DISTR_SNR 
			AND EDO.STATE_SNR=CP.STATE_SNR 
			AND UT.TER_SNR=I.INST_SNR 
			AND UT.REC_STAT=0 
			AND U.USER_SNR=UT.USER_SNR 
			AND I.INST_SNR<>'00000000-0000-0000-0000-000000000000' 
			and u.USER_SNR in ('".$ids."') ";
			
		if($tipo != '' && $tipo != '00000000-0000-0000-0000-000000000000'){
			$queryInst .= " and INST_TYPE.INST_TYPE_SNR = '".$tipo."' ";
		}
		
		if($nombre != ''){
			$queryInst .= " and I.NAME like '%".$nombre."%' ";
		}
		
		if($calle != ''){
			$queryInst .= " and I.STREET1 like '%".$calle."%' ";
		}
		
		if($colonia != ''){
			$queryInst .= " and CP.NAME like '%".$colonia."%' ";
		}
		
		if($ciudad != ''){
			$queryInst .= " and POB.NAME like '%".$ciudad."%' ";
		}
		
		if($estado != ''){
			$queryInst .= " and EDO.NAME like '%".$estado."%' ";
		}
		
		if($cp != ''){
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
		
		if($repre != ''){
			$queryInst .= " and u.USER_SNR in ('".$repre."') ";
		}
		
		$queryInst .= " order by TIPO_INST, NOMBRE, DIRECCION ";
		
		//echo $queryInst;
				
		$tope = "OFFSET ".$registroIni." ROWS 
				FETCH NEXT ".$registrosPorPagina." ROWS ONLY ";
					
		$rsInst = sqlsrv_query($conn, $queryInst, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			
		$totalRegistros = sqlsrv_num_rows($rsInst);
			
		$queryPersonas20 = sqlsrv_query($conn, $queryInst.$tope);
			
		$paginas = ceil($totalRegistros / $registrosPorPagina);
		
		$tabla = '<table width="100%" class="grid">
				<thead>
					<tr>
						<td>Tipo</td>
						<td>Nombre</td>
						<td>Dirección</td>
						<td>Colonia</td>
						<td>C.P.</td>
						<td>Municipio</td>
						<td>Estado</td>
						<td>Brick</td>
						<td align="center" width="5%">Modificar</td>
						<td align="center" width="5%">Borrar</td>
					</tr>
				</thead>
				<tbody>';

		while($inst = sqlsrv_fetch_array($queryPersonas20)){
			$tabla .= "<tr>
				<td onclick='presentaDatos(\"".$inst['INST_SNR']."\",\"divDatosInstituciones\");'>
					".$inst['TIPO_INST']."
				</td>
				<td onclick='presentaDatos(\"".$inst['INST_SNR']."\",\"divDatosInstituciones\");'>
					".$inst['NOMBRE']."
				</td>
				<td onclick='presentaDatos(\"".$inst['INST_SNR']."\",\"divDatosInstituciones\");'>
					".$inst['DIRECCION']."
				</td>
				<td onclick='presentaDatos(\"".$inst['INST_SNR']."\",\"divDatosInstituciones\");'>
					".$inst['COLONIA']."
				</td>
				<td onclick='presentaDatos(\"".$inst['INST_SNR']."\",\"divDatosInstituciones\");'>
					".$inst['CPOSTAL']."
				</td>
				<td onclick='presentaDatos(\"".$inst['INST_SNR']."\",\"divDatosInstituciones\");'>
					".$inst['POBLACION']."
				</td>
				<td onclick='presentaDatos(\"".$inst['INST_SNR']."\",\"divDatosInstituciones\");'>
					".$inst['ESTADO']."
				</td>
				<td onclick='presentaDatos(\"".$inst['INST_SNR']."\",\"divDatosInstituciones\");'>
					".$inst['BRICK']."
				</td>
				<td width='5%' align='center'><img src='iconos/editar.png' title='Modificar' width='20px' onClick='editarInst(\"".$inst['INST_SNR']."\");'/></td>
				<td width='5%' align='center'><img src='iconos/eliminar.png' title='Eliminar' width='20px' onClick='eliminarInst(\"".$inst['INST_SNR']."\");'/></td>
			</tr>";
		}
		$tabla .= '</tbody>';
		//if($totalRegistros > $registrosPorPagina){
			$tabla .= "<tfoot><tr><td colspan='14' align='center'>";
			$idsEnviar = str_replace("'","",$ids);
			if($numPagina > 1){
				$anterior = $numPagina - 1;
				$tabla .= "<a href='#' onClick='nuevaPaginaInst(1,\"".$hoy."\",\"".$idsEnviar."\",\"".$tipo."\");'>inicio</a>&nbsp;&nbsp;";
				$tabla .= "<a href='#' onClick='nuevaPaginaInst(".$anterior.",\"".$hoy."\",\"".$idsEnviar."\",\"".$tipo."\");'>anterior</a>&nbsp;&nbsp;";
			}
			for($i=1;$i<=$paginas;$i++){
				if($i == $numPagina){
					$tabla .= $i."&nbsp;&nbsp;";
				}else{
					//$tabla .= "<a href='#' onClick='nuevaPaginaInst(".$i.",\"".$hoy."\",\"".$idsEnviar."\",\"".$tipo."\");'>".$i."</a>&nbsp;&nbsp;";
				}
			}
			if($numPagina < $paginas){
				$siguiente = $numPagina + 1;
				$tabla .= "<a href='#' onClick='nuevaPaginaInst(".$siguiente.",\"".$hoy."\",\"".$idsEnviar."\",\"".$tipo."\");'>Siguiente</a>&nbsp;&nbsp;";
				$tabla .= "<a href='#' onClick='nuevaPaginaInst(".$paginas.",\"".$hoy."\",\"".$idsEnviar."\",\"".$tipo."\");'>Fin</a>&nbsp;&nbsp;";
			}
			$tabla .= "Pag. ".$numPagina." de ".$paginas."&nbsp;&nbsp;&nbsp; Registros : ".$totalRegistros;
			$tabla .= "</td></tr></tfoot>";
		//}
		$tabla .= "</table>";
		echo $tabla;
	}
	
?>