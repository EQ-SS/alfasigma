<?php
	include "../conexion.php";
	
	function queryInstituciones ($tipo, $ids, $filtros, $motivoBaja){
		$ids = str_replace("''","'",$ids);
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
			I.EMAIL1 AS EMAIL,
			I.INFO AS COMENTARIOS,
			I.LATITUDE AS LATITUD,
			I.LONGITUDE AS LONGITUD,
			U.USER_SNR AS USUARIO_ID, 
			U.LNAME+' '+U.FNAME AS REPRESENTANTE,
			(SELECT COUNT(*) FROM VISITINST VP, CYCLES CICLOS 
			WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND I.INST_SNR=VP.INST_SNR 
			AND GETDATE() BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
			AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE
			AND vp.USER_SNR in ('".$ids."')) AS VISITAS, 
			K.NAME AS RUTA 
			From INST I
			left outer join  CITY  CP on CP.CITY_SNR = i.CITY_SNR 
			left outer join DISTRICT POB on POB.DISTR_SNR=CP.DISTR_SNR
			left outer join STATE EDO on EDO.STATE_SNR=CP.STATE_SNR
			left outer join INST_TYPE on INST_TYPE.INST_TYPE=I.INST_TYPE
			left outer join COUNTRY PAIS on PAIS.CTRY_SNR=CP.CTRY_SNR 
			left outer join USER_TERRIT UT on UT.INST_SNR=I.INST_SNR and UT.REC_STAT=0
			left outer join USERS U on U.USER_SNR=UT.USER_SNR
			left outer join KOMMLOC K on u.USER_SNR = K.KLOC_SNR
			LEFT OUTER JOIN BRICK bri on bri.BRICK_SNR = CP.BRICK_SNR
			LEFT OUTER JOIN CODELIST ESTATUS ON ESTATUS.CLIST_SNR=I.STATUS_SNR ";
		if($motivoBaja != ''){
			$queryInst .= "left outer join inst_approval ia on ia.i_inst_snr = i.inst_snr 
				inner join approval_status aps on aps.inst_approval_snr = ia.inst_approval_snr ";
		}
		
		$queryInst .= "WHERE I.INST_SNR<>'00000000-0000-0000-0000-000000000000' 
			and u.USER_SNR in ('".$ids."') ";
		
		if($motivoBaja == ''){
			$queryInst .= " AND I.REC_STAT=0 ";
		}

		if($motivoBaja != ''){
			$queryInst .= " and ia.i_movement_type = 'D' 
				and aps.approved_status = 2 
				and ia.i_del_status_snr = '".$motivoBaja."' ";
		}		
		
			
		//echo "tipoFuncion:::::".$tipo."::::<br>";
		if($tipo != ''){
			//echo "ntre";
			$queryInst .= " and INST_TYPE.NAME = '".$tipo."' ";
		}
		if($filtros != ''){
			$queryInst .= $filtros;
		}
		$queryInst .= " order by TIPO_INST, NOMBRE, DIRECCION ";	
		//echo "<br><br>".$queryInst."<br><br>";
		return $queryInst;
	}
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$tipo = $_POST['tipo'];
		//echo "tipo: ".$tipo."<br>";
		$numPagina = $_POST['pagina'];
		$ids = str_replace(",","','",$_POST['ids']);
		$hoy = $_POST['hoy'];
		
		$tabActivo = $_POST['tabActivo'];
		
		
		
		if(isset($_POST['nombre']) && $_POST['nombre'] != ''){
			$nombre = $_POST['nombre'];
		}else{
			$nombre = '';
		}

		if(isset($_POST['nombreList']) && $_POST['nombreList'] != ''){
			$nombreList = $_POST['nombreList'];
		}else{
			$nombreList = '';
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
		
		if(isset($_POST['tipoUsuario']) && $_POST['tipoUsuario'] != ''){
			$tipoUsuario = $_POST['tipoUsuario'];
		}else{
			$tipoUsuario = '';
		}
		
		if(isset($_POST['geolocalizados']) && $_POST['geolocalizados'] != ''){
			$geolocalizados = $_POST['geolocalizados'];
		}else{
			$geolocalizados = '';
		}
		
		if(isset($_POST['estatus']) && $_POST['estatus'] != ''){
			$estatus = $_POST['estatus'];
		}else{
			$estatus = '';
		}
		
		if(isset($_POST['motivoBaja']) && $_POST['motivoBaja'] != ''){
			$motivoBaja = $_POST['motivoBaja'];
		}else{
			$motivoBaja = '';
		}
		
		$registrosPorPagina = 5;
		$registroIni = $numPagina * $registrosPorPagina - $registrosPorPagina;
		
		$filtros = '';
			
		if($tipo != '' && $tipo != '00000000-0000-0000-0000-000000000000' && $tipo != 0){
			$filtros .= " and INST_TYPE.INST_TYPE = '".$tipo."' ";
		}
		
		if($nombre != ''){
			$filtros .= " and I.NAME like '%".$nombre."%' ";
		}

		if($nombreList != ''){
			$filtros .= " and I.NAME like '%".$nombreList."%' ";
		}
		
		if($calle != ''){
			$filtros .= " and I.STREET1 like '%".$calle."%' ";
		}
		
		if($colonia != ''){
			$filtros .= " and CP.NAME like '%".$colonia."%' ";
		}
		
		if($ciudad != ''){
			$filtros .= " and POB.NAME like '%".$ciudad."%' ";
		}
		
		if($estado != ''){
			$filtros .= " and EDO.NAME like '%".$estado."%' ";
		}
		
		if($cp != ''){
			$filtros .= " and CP.ZIP = '".$cp."' ";
		}
		
		if($visitados == 'visitados'){
			$filtros .= " and (SELECT COUNT(*) FROM VISITINST VP, CYCLES CICLOS 
			WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND I.INST_SNR=VP.INST_SNR 
			AND GETDATE() BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
			AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE) > 0 ";
		}else if($visitados == 'no'){
			$filtros .= " and (SELECT COUNT(*) FROM VISITINST VP, CYCLES CICLOS 
			WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND I.INST_SNR=VP.INST_SNR 
			AND GETDATE() BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
			AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE) = 0 ";
		}else if($visitados == 're'){
			$filtros .= " and (SELECT COUNT(*) FROM VISITINST VP, CYCLES CICLOS 
			WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND I.INST_SNR=VP.INST_SNR 
			AND GETDATE() BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
			AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE) > 1 ";
		}
		
		if($repre != ''){
			$filtros .= " and u.USER_SNR in ('".$repre."') ";
		}
		
		if($geolocalizados == 'GeoSi'){
			$filtros .= " and latitude <> '0' and longitude <> '0' and latitude <> '0.0' and longitude <> '0.0' and latitude <> '' and longitude <> '' and latitude is not null and longitude is not null ";
		}else if($geolocalizados == 'GeoNo'){
			$filtros .= " and (latitude = '0' or longitude = '0' or latitude = '0.0' or longitude = '0.0' or latitude = '' or longitude = '' or latitude is null or longitude is null) ";
		}
		
		if($estatus != ''){
			$filtros .= " and status_snr = '".$estatus."' ";
		}else{
			if($motivoBaja == ''){
				$filtros .= " and status_snr = 'B405F75D-499E-4EB8-AAC8-C89F2CA080A4' ";
			}
		}
		
		/*if($motivoBaja != ''){
			
		}*/
		
		//$filtros .= " order by TIPO_INST, NOMBRE, DIRECCION ";
		echo "TAB: ".$tabActivo."<br>";
		switch ($tabActivo){
			case 0:
				$queryInst = queryInstituciones ('', $ids, $filtros, $motivoBaja);
				break;
			case 1:
				$queryInst = queryInstituciones ('HOSPITALES', $ids, $filtros, $motivoBaja);
				break;
			case 2:
				$queryInst = queryInstituciones ('FARMACIAS', $ids, $filtros, $motivoBaja);
				break;
			case 3:
				$queryInst = queryInstituciones ('CONSULTORIOS', $ids, $filtros, $motivoBaja);
				break;
			case 4:
				$queryInst = queryInstituciones ('CLIENTES', $ids, $filtros, $motivoBaja);
				break;
		}
		//echo $queryInst;
		$tope = "OFFSET ".$registroIni." ROWS 
				FETCH NEXT ".$registrosPorPagina." ROWS ONLY ";
					
		$rsInst = sqlsrv_query($conn, $queryInst, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			
		$totalRegistros = sqlsrv_num_rows($rsInst);

		if($totalRegistros == 0){
			echo "<script>
				$('#cardInfInst').hide();
				$('#sinResultadosInst').show();
			</script>";
		}
		else{
			echo "<script>
				$('#cardInfInst').show();
				$('#sinResultadosInst').hide();
			</script>";
		}
			
		$queryPersonas20 = sqlsrv_query($conn, $queryInst.$tope);
		//echo $queryPersonas20;
			
		$paginas = ceil($totalRegistros / $registrosPorPagina);
		
		echo "<script>$('#hdnFiltrosExportarInst').val('".$visitados."');";
		switch ($tabActivo){
			case 0:
				$tabla = 'tblTodas';
				$tipoInstTabla = '';
				$idTabla = 'inst';
				break;
			case 1:
				$tabla = 'tblHospitales';
				$tipoInstTabla = 'HOSPITALES';
				$idTabla = 'hosp';
				break;
			case 2:
				$tabla = 'tblFarmacias';
				$tipoInstTabla = 'FARMACIAS';
				$idTabla = 'far';
				break;
			case 3:
				$tabla = 'tblConsultorios';
				$tipoInstTabla = 'CONSULTORIOS';
				break;
			case 4:
				$tabla = 'tblClientes';
				$tipoInstTabla = 'CLIENTES';
				break;
		}
		
		echo "$('#".$tabla." tbody').empty();
			$('#".$tabla." tfoot').empty();
			$('#tblCambiarRutaInst tbody').empty();
			$('#tblCambiarRutaInst tfoot').empty();";
		
		echo "$('#".$tabla."numReg').empty();
			$('#tblCambiarRutaInstnumReg').empty();";
		
		$i=1;
		$idContGnrl=0;
		
		while($inst = sqlsrv_fetch_array($queryPersonas20)){
			if($inst['VISITAS'] == 0){
				$circulo = 'circuloRojo';
			}else{
				$circulo = 'circuloVerde';
			}
			$datosInst = "<b>Dirección: </b><span>".$inst['DIRECCION'].", ";
			$datosInst .= $inst['CPOSTAL'].", COLONIA: ".$inst['COLONIA'].", ";
			$datosInst .= $inst['POBLACION'].", ";
			$datosInst .= $inst['ESTADO']."</span>";

			$idContGnrl++;
			
			
			if($tipoUsuario == '' || $tipoUsuario == 4){
				//echo "$('#".$tabla." tbody').append('<tr><td><div class=\"".$circulo."\"></div></td><td onClick=\"presentaDatos(\'".$inst['INST_SNR']."\',\'divDatosInstituciones\',\'".$tipoInstTabla."\',\'\',\'".$inst['USUARIO_ID']."\');\" style=\"width:100px;\">".$inst['TIPO_INST']."</td><td onClick=\"presentaDatos(\'".$inst['INST_SNR']."\',\'divDatosInstituciones\',\'".$tipoInstTabla."\',\'\',\'".$inst['USUARIO_ID']."\');\" style=\"width:200px;\">".$inst['NOMBRE']."</td><td onClick=\"presentaDatos(\'".$inst['INST_SNR']."\',\'divDatosInstituciones\',\'".$tipoInstTabla."\',\'\',\'".$inst['USUARIO_ID']."\');\" style=\"width:200px;\">".$inst['DIRECCION']."</td><td onClick=\"presentaDatos(\'".$inst['INST_SNR']."\',\'divDatosInstituciones\',\'".$tipoInstTabla."\',\'\',\'".$inst['USUARIO_ID']."\');\" style=\"width:200px;\">".$inst['COLONIA']."</td><td onClick=\"presentaDatos(\'".$inst['INST_SNR']."\',\'divDatosInstituciones\',\'".$tipoInstTabla."\',\'\',\'".$inst['USUARIO_ID']."\');\" style=\"width:100px;\">".$inst['CPOSTAL']."</td><td onClick=\"presentaDatos(\'".$inst['INST_SNR']."\',\'divDatosInstituciones\',\'".$tipoInstTabla."\',\'\',\'".$inst['USUARIO_ID']."\');\" style=\"width:200px;\">".$inst['POBLACION']."</td><td onClick=\"presentaDatos(\'".$inst['INST_SNR']."\',\'divDatosInstituciones\',\'".$tipoInstTabla."\',\'\',\'".$inst['USUARIO_ID']."\');\" style=\"width:200px;\">".$inst['ESTADO']."</td><td onClick=\"presentaDatos(\'".$inst['INST_SNR']."\',\'divDatosInstituciones\',\'".$tipoInstTabla."\',\'\',\'".$inst['USUARIO_ID']."\');\" style=\"width:100px;\">".$inst['BRICK']."</td><td width=\"5%\" align=\"center\" style=\"width:50px;\"><img src=\"iconos/editar.png\" title=\"Modificar\" width=\"20px\" onClick=\"editarInst(\'".$inst['INST_SNR']."\');\"/></td><td width=\"5%\" align=\"center\" style=\"width:50px;\"><img src=\"iconos/eliminar.png\" title=\"Eliminar\" width=\"20px\" onClick=\"eliminarInst(\'".$inst['INST_SNR']."\',\'".$datosInst."\',\'".$inst['USUARIO_ID']."\');\"/></td></tr>');";
				if($tabActivo == 0){
					if($inst['TIPO_INST'] == 'Consultorios'){
						echo "$('#".$tabla." tbody').append('<tr id=\"tr".$idTabla."".$idContGnrl."\" onClick=\"enviaIdTrInst(this.id);\"><td><div class=\"row\"><div id=\"".$idTabla."".$idContGnrl."\" class=\"col-lg-10 col-md-10 col-sm-10 col-xs-10 pointer margin-0\" onClick=\"presentaDatos(\'".$idTabla."".$idContGnrl."\',\'".$inst['INST_SNR']."\',\'divDatosInstituciones\',\'".$tipoInstTabla."\',\'\',\'".$inst['USUARIO_ID']."\');\"><div class=\"row\"><div class=\"col-md-12 text-overflowA font-bold\"><span style=\"display:inline-flex;\"><i class=\"material-icons\">local_pharmacy</i><span style=\"margin: 5px 0 5px 6px;\">".$inst['NOMBRE']."</span></span></div></div><div class=\"row\"><div class=\"col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0\"><div class=\"".$circulo."\"></div></div><div class=\"col-lg-10 col-md-10 col-sm-10 col-xs-10 text-overflowA p-r-0 margin-0\">".$inst['DIRECCION'].", ".$inst['POBLACION'].",".$inst['ESTADO']."  </div></div></div><div class=\"col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0\" style=\"padding-top: 4%;\"><button type=\"button\" class=\"btn bg-indigo waves-effect add-margin-bottom little-button\" title=\"Modificar\" onClick=\"editarInst(\'".$inst['INST_SNR']."\');\"><i class=\"material-icons pointer\">edit</i></button><button type=\"button\" class=\"btn bg-indigo btn bg-indigo waves-effect little-button\" title=\"Eliminar\" onClick=\"eliminarInst(\'".$inst['INST_SNR']."\',\'".$inst['NOMBRE']."\',\'".$inst['TIPO_INST']."\',\'".$datosInst."\',\'".$inst['USUARIO_ID']."\');\"><i class=\"material-icons pointer\">delete</i></button></div></div></td></tr>');";
					}
					if($inst['TIPO_INST'] == 'Hospitales'){
						echo "$('#".$tabla." tbody').append('<tr id=\"tr".$idTabla."".$idContGnrl."\" onClick=\"enviaIdTrInst(this.id);\"><td><div class=\"row\"><div id=\"".$idTabla."".$idContGnrl."\" class=\"col-lg-10 col-md-10 col-sm-10 col-xs-10 pointer margin-0\" onClick=\"presentaDatos(\'".$idTabla."".$idContGnrl."\',\'".$inst['INST_SNR']."\',\'divDatosInstituciones\',\'".$tipoInstTabla."\',\'\',\'".$inst['USUARIO_ID']."\');\"><div class=\"row\"><div class=\"col-md-12 text-overflowA font-bold\"><span style=\"display:inline-flex;\"><i class=\"fas fa-hospital\"></i><span style=\"margin: 4px 0 5px 8px;\">".$inst['NOMBRE']."</span></span></div></div><div class=\"row\"><div class=\"col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0\"><div class=\"".$circulo."\"></div></div><div class=\"col-lg-10 col-md-10 col-sm-10 col-xs-10 text-overflowA p-r-0 margin-0\">".$inst['DIRECCION'].", ".$inst['POBLACION'].",".$inst['ESTADO']."  </div></div></div><div class=\"col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0\" style=\"padding-top: 4%;\"><button type=\"button\" class=\"btn bg-indigo waves-effect add-margin-bottom little-button\" title=\"Modificar\" onClick=\"editarInst(\'".$inst['INST_SNR']."\');\"><i class=\"material-icons pointer\">edit</i></button><button type=\"button\" class=\"btn bg-indigo btn bg-indigo waves-effect little-button\" title=\"Eliminar\" onClick=\"eliminarInst(\'".$inst['INST_SNR']."\',\'".$inst['NOMBRE']."\',\'".$inst['TIPO_INST']."\',\'".$datosInst."\',\'".$inst['USUARIO_ID']."\');\"><i class=\"material-icons pointer\">delete</i></button></div></div></td></tr>');";
					}
					if($inst['TIPO_INST'] == 'Farmacias'){
						echo "$('#".$tabla." tbody').append('<tr id=\"tr".$idTabla."".$idContGnrl."\" onClick=\"enviaIdTrInst(this.id);\"><td><div class=\"row\"><div id=\"".$idTabla."".$idContGnrl."\" class=\"col-lg-10 col-md-10 col-sm-10 col-xs-10 pointer margin-0\" onClick=\"presentaDatos(\'".$idTabla."".$idContGnrl."\',\'".$inst['INST_SNR']."\',\'divDatosInstituciones\',\'".$tipoInstTabla."\',\'\',\'".$inst['USUARIO_ID']."\');\"><div class=\"row\"><div class=\"col-md-12 text-overflowA font-bold\"><span style=\"display:inline-flex;\"><i class=\"fas fa-pills\"></i><span style=\"margin: 4px 0 5px 8px;\">".$inst['NOMBRE']."</span></span></div></div><div class=\"row\"><div class=\"col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0\"><div class=\"".$circulo."\"></div></div><div class=\"col-lg-10 col-md-10 col-sm-10 col-xs-10 text-overflowA p-r-0 margin-0\">".$inst['DIRECCION'].", ".$inst['POBLACION'].",".$inst['ESTADO']."  </div></div></div><div class=\"col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0\" style=\"padding-top: 4%;\"><button type=\"button\" class=\"btn bg-indigo waves-effect add-margin-bottom little-button\" title=\"Modificar\" onClick=\"editarInst(\'".$inst['INST_SNR']."\');\"><i class=\"material-icons pointer\">edit</i></button><button type=\"button\" class=\"btn bg-indigo btn bg-indigo waves-effect little-button\" title=\"Eliminar\" onClick=\"eliminarInst(\'".$inst['INST_SNR']."\',\'".$inst['NOMBRE']."\',\'".$inst['TIPO_INST']."\',\'".$datosInst."\',\'".$inst['USUARIO_ID']."\');\"><i class=\"material-icons pointer\">delete</i></button></div></div></td></tr>');";
					}
					if($inst['TIPO_INST'] == 'Otras instituciones'){
						echo "$('#".$tabla." tbody').append('<tr id=\"tr".$idTabla."".$idContGnrl."\" onClick=\"enviaIdTrInst(this.id);\"><td><div class=\"row\"><div id=\"".$idTabla."".$idContGnrl."\" class=\"col-lg-10 col-md-10 col-sm-10 col-xs-10 pointer margin-0\" onClick=\"presentaDatos(\'".$idTabla."".$idContGnrl."\',\'".$inst['INST_SNR']."\',\'divDatosInstituciones\',\'".$tipoInstTabla."\',\'\',\'".$inst['USUARIO_ID']."\');\"><div class=\"row\"><div class=\"col-md-12 text-overflowA font-bold\"><span style=\"display:inline-flex;\"><i class=\"fas fa-building\"></i><span style=\"margin: 4px 0 5px 8px;\">".$inst['NOMBRE']."</span></span></div></div><div class=\"row\"><div class=\"col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0\"><div class=\"".$circulo."\"></div></div><div class=\"col-lg-10 col-md-10 col-sm-10 col-xs-10 text-overflowA p-r-0 margin-0\">".$inst['DIRECCION'].", ".$inst['POBLACION'].",".$inst['ESTADO']."  </div></div></div><div class=\"col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0\" style=\"padding-top: 4%;\"><button type=\"button\" class=\"btn bg-indigo waves-effect add-margin-bottom little-button\" title=\"Modificar\" onClick=\"editarInst(\'".$inst['INST_SNR']."\');\"><i class=\"material-icons pointer\">edit</i></button><button type=\"button\" class=\"btn bg-indigo btn bg-indigo waves-effect little-button\" title=\"Eliminar\" onClick=\"eliminarInst(\'".$inst['INST_SNR']."\',\'".$inst['NOMBRE']."\',\'".$inst['TIPO_INST']."\',\'".$datosInst."\',\'".$inst['USUARIO_ID']."\');\"><i class=\"material-icons pointer\">delete</i></button></div></div></td></tr>');";
					}
				}else{
					echo "$('#".$tabla." tbody').append('<tr id=\"tr".$idTabla."".$idContGnrl."\" onClick=\"enviaIdTrInst(this.id);\"><td><div class=\"row\"><div id=\"".$idTabla."".$idContGnrl."\" class=\"col-lg-10 col-md-10 col-sm-10 col-xs-10 pointer margin-0\" onClick=\"presentaDatos(\'".$idTabla."".$idContGnrl."\',\'".$inst['INST_SNR']."\',\'divDatosInstituciones\',\'".$tipoInstTabla."\',\'\',\'".$inst['USUARIO_ID']."\');\"><div class=\"row\"><div class=\"col-md-12 text-overflowA font-bold\">".$inst['NOMBRE']."</div></div><div class=\"row\"><div class=\"col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0\"><div class=\"".$circulo."\"></div></div><div class=\"col-lg-10 col-md-10 col-sm-10 col-xs-10 text-overflowA p-r-0 margin-0\">".$inst['DIRECCION'].", ".$inst['POBLACION'].",".$inst['ESTADO']."  </div></div></div><div class=\"col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0\" style=\"padding-top: 4%;\"><button type=\"button\" class=\"btn bg-indigo waves-effect add-margin-bottom little-button\" title=\"Modificar\" onClick=\"editarInst(\'".$inst['INST_SNR']."\');\"><i class=\"material-icons pointer\">edit</i></button><button type=\"button\" class=\"btn bg-indigo btn bg-indigo waves-effect little-button\" title=\"Eliminar\" onClick=\"eliminarInst(\'".$inst['INST_SNR']."\',\'".$inst['NOMBRE']."\',\'".$inst['TIPO_INST']."\',\'".$datosInst."\',\'".$inst['USUARIO_ID']."\');\"><i class=\"material-icons pointer\">delete</i></button></div></div></td></tr>');";
				}

			}else{
			
				if($tabActivo == 0){
					if($inst['TIPO_INST'] == 'Consultorios'){
						echo "$('#".$tabla." tbody').append('<tr id=\"tr".$idTabla."".$idContGnrl."\" onClick=\"enviaIdTrInst(this.id);\"><td><div class=\"row\"><div id=\"".$idTabla."".$idContGnrl."\" class=\"col-lg-10 col-md-10 col-sm-10 col-xs-10 pointer margin-0\" onClick=\"presentaDatos(\'".$idTabla."".$idContGnrl."\',\'".$inst['INST_SNR']."\',\'divDatosInstituciones\',\'".$tipoInstTabla."\',\'\',\'".$inst['USUARIO_ID']."\');\"><div class=\"row\"><div class=\"col-md-12 text-overflowA font-bold\"><span style=\"display:inline-flex;\"><i class=\"material-icons\">local_pharmacy</i><span style=\"margin: 5px 0 5px 6px;\">".$inst['NOMBRE']."</span></span></div></div><div class=\"row\"><div class=\"col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0\"><div class=\"".$circulo."\"></div></div><div class=\"col-lg-10 col-md-10 col-sm-10 col-xs-10 text-overflowA p-r-0 margin-0\">".$inst['DIRECCION'].", ".$inst['POBLACION'].",".$inst['ESTADO']."  </div></div></div><div class=\"col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0\" style=\"padding-top: 4%;\"><button type=\"button\" class=\"btn bg-indigo waves-effect add-margin-bottom little-button\" title=\"Modificar\" onClick=\"editarInst(\'".$inst['INST_SNR']."\');\"><i class=\"material-icons pointer\">edit</i></button><button type=\"button\" class=\"btn bg-indigo btn bg-indigo waves-effect little-button\" title=\"Eliminar\" onClick=\"eliminarInst(\'".$inst['INST_SNR']."\',\'".$inst['NOMBRE']."\',\'".$inst['TIPO_INST']."\',\'".$datosInst."\',\'".$inst['USUARIO_ID']."\');\"><i class=\"material-icons pointer\">delete</i></button></div></div></td></tr>');";
					}
					if($inst['TIPO_INST'] == 'Hospitales'){
						echo "$('#".$tabla." tbody').append('<tr id=\"tr".$idTabla."".$idContGnrl."\" onClick=\"enviaIdTrInst(this.id);\"><td><div class=\"row\"><div id=\"".$idTabla."".$idContGnrl."\" class=\"col-lg-10 col-md-10 col-sm-10 col-xs-10 pointer margin-0\" onClick=\"presentaDatos(\'".$idTabla."".$idContGnrl."\',\'".$inst['INST_SNR']."\',\'divDatosInstituciones\',\'".$tipoInstTabla."\',\'\',\'".$inst['USUARIO_ID']."\');\"><div class=\"row\"><div class=\"col-md-12 text-overflowA font-bold\"><span style=\"display:inline-flex;\"><i class=\"fas fa-hospital\"></i><span style=\"margin: 4px 0 5px 8px;\">".$inst['NOMBRE']."</span></span></div></div><div class=\"row\"><div class=\"col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0\"><div class=\"".$circulo."\"></div></div><div class=\"col-lg-10 col-md-10 col-sm-10 col-xs-10 text-overflowA p-r-0 margin-0\">".$inst['DIRECCION'].", ".$inst['POBLACION'].",".$inst['ESTADO']."  </div></div></div><div class=\"col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0\" style=\"padding-top: 4%;\"><button type=\"button\" class=\"btn bg-indigo waves-effect add-margin-bottom little-button\" title=\"Modificar\" onClick=\"editarInst(\'".$inst['INST_SNR']."\');\"><i class=\"material-icons pointer\">edit</i></button><button type=\"button\" class=\"btn bg-indigo btn bg-indigo waves-effect little-button\" title=\"Eliminar\" onClick=\"eliminarInst(\'".$inst['INST_SNR']."\',\'".$inst['NOMBRE']."\',\'".$inst['TIPO_INST']."\',\'".$datosInst."\',\'".$inst['USUARIO_ID']."\');\"><i class=\"material-icons pointer\">delete</i></button></div></div></td></tr>');";
					}
					if($inst['TIPO_INST'] == 'Farmacias'){
						echo "$('#".$tabla." tbody').append('<tr id=\"tr".$idTabla."".$idContGnrl."\" onClick=\"enviaIdTrInst(this.id);\"><td><div class=\"row\"><div id=\"".$idTabla."".$idContGnrl."\" class=\"col-lg-10 col-md-10 col-sm-10 col-xs-10 pointer margin-0\" onClick=\"presentaDatos(\'".$idTabla."".$idContGnrl."\',\'".$inst['INST_SNR']."\',\'divDatosInstituciones\',\'".$tipoInstTabla."\',\'\',\'".$inst['USUARIO_ID']."\');\"><div class=\"row\"><div class=\"col-md-12 text-overflowA font-bold\"><span style=\"display:inline-flex;\"><i class=\"fas fa-pills\"></i><span style=\"margin: 4px 0 5px 8px;\">".$inst['NOMBRE']."</span></span></div></div><div class=\"row\"><div class=\"col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0\"><div class=\"".$circulo."\"></div></div><div class=\"col-lg-10 col-md-10 col-sm-10 col-xs-10 text-overflowA p-r-0 margin-0\">".$inst['DIRECCION'].", ".$inst['POBLACION'].",".$inst['ESTADO']."  </div></div></div><div class=\"col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0\" style=\"padding-top: 4%;\"><button type=\"button\" class=\"btn bg-indigo waves-effect add-margin-bottom little-button\" title=\"Modificar\" onClick=\"editarInst(\'".$inst['INST_SNR']."\');\"><i class=\"material-icons pointer\">edit</i></button><button type=\"button\" class=\"btn bg-indigo btn bg-indigo waves-effect little-button\" title=\"Eliminar\" onClick=\"eliminarInst(\'".$inst['INST_SNR']."\',\'".$inst['NOMBRE']."\',\'".$inst['TIPO_INST']."\',\'".$datosInst."\',\'".$inst['USUARIO_ID']."\');\"><i class=\"material-icons pointer\">delete</i></button></div></div></td></tr>');";
					}
					if($inst['TIPO_INST'] == 'Otras instituciones'){
						echo "$('#".$tabla." tbody').append('<tr id=\"tr".$idTabla."".$idContGnrl."\" onClick=\"enviaIdTrInst(this.id);\"><td><div class=\"row\"><div id=\"".$idTabla."".$idContGnrl."\" class=\"col-lg-10 col-md-10 col-sm-10 col-xs-10 pointer margin-0\" onClick=\"presentaDatos(\'".$idTabla."".$idContGnrl."\',\'".$inst['INST_SNR']."\',\'divDatosInstituciones\',\'".$tipoInstTabla."\',\'\',\'".$inst['USUARIO_ID']."\');\"><div class=\"row\"><div class=\"col-md-12 text-overflowA font-bold\"><span style=\"display:inline-flex;\"><i class=\"fas fa-building\"></i><span style=\"margin: 4px 0 5px 8px;\">".$inst['NOMBRE']."</span></span></div></div><div class=\"row\"><div class=\"col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0\"><div class=\"".$circulo."\"></div></div><div class=\"col-lg-10 col-md-10 col-sm-10 col-xs-10 text-overflowA p-r-0 margin-0\">".$inst['DIRECCION'].", ".$inst['POBLACION'].",".$inst['ESTADO']."  </div></div></div><div class=\"col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0\" style=\"padding-top: 4%;\"><button type=\"button\" class=\"btn bg-indigo waves-effect add-margin-bottom little-button\" title=\"Modificar\" onClick=\"editarInst(\'".$inst['INST_SNR']."\');\"><i class=\"material-icons pointer\">edit</i></button><button type=\"button\" class=\"btn bg-indigo btn bg-indigo waves-effect little-button\" title=\"Eliminar\" onClick=\"eliminarInst(\'".$inst['INST_SNR']."\',\'".$inst['NOMBRE']."\',\'".$inst['TIPO_INST']."\',\'".$datosInst."\',\'".$inst['USUARIO_ID']."\');\"><i class=\"material-icons pointer\">delete</i></button></div></div></td></tr>');";
					}
				}else{
					echo "$('#".$tabla." tbody').append('<tr id=\"tr".$idTabla."".$idContGnrl."\" onClick=\"enviaIdTrInst(this.id);\"><td><div class=\"row\"><div id=\"".$idTabla."".$idContGnrl."\" class=\"col-lg-10 col-md-10 col-sm-10 col-xs-10 pointer margin-0\" onClick=\"presentaDatos(\'".$idTabla."".$idContGnrl."\',\'".$inst['INST_SNR']."\',\'divDatosInstituciones\',\'".$tipoInstTabla."\',\'\',\'".$inst['USUARIO_ID']."\');\"><div class=\"row\"><div class=\"col-md-12 text-overflowA font-bold\">".$inst['NOMBRE']."</div></div><div class=\"row\"><div class=\"col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0\"><div class=\"".$circulo."\"></div></div><div class=\"col-lg-10 col-md-10 col-sm-10 col-xs-10 text-overflowA p-r-0 margin-0\">".$inst['DIRECCION'].", ".$inst['POBLACION'].",".$inst['ESTADO']."  </div></div></div><div class=\"col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0\" style=\"padding-top: 4%;\"><button type=\"button\" class=\"btn bg-indigo waves-effect add-margin-bottom little-button\" title=\"Modificar\" onClick=\"editarInst(\'".$inst['INST_SNR']."\');\"><i class=\"material-icons pointer\">edit</i></button><button type=\"button\" class=\"btn bg-indigo btn bg-indigo waves-effect little-button\" title=\"Eliminar\" onClick=\"eliminarInst(\'".$inst['INST_SNR']."\',\'".$inst['NOMBRE']."\',\'".$inst['TIPO_INST']."\',\'".$datosInst."\',\'".$inst['USUARIO_ID']."\');\"><i class=\"material-icons pointer\">delete</i></button></div></div></td></tr>');";
				}
			}

			if($inst['TIPO_INST'] == 'Consultorios'){
				echo "$('#tblCambiarRutaInst tbody').append('<tr id=\"trinstR".$idContGnrl."\" onClick=\"enviaIdTrInst(this.id);\"><td><div class=\"row\"><div id=\"instR".$idContGnrl."\" class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12 pointer margin-0\" onclick=\"presentaDatos(\'instR".$idContGnrl."\',\'".$inst['INST_SNR']."\',\'divDatosInstituciones\',\'".$tipoInstTabla."\',\'\',\'".$inst['USUARIO_ID']."\');\"><div class=\"row\"><div class=\"col-lg-10 col-md-10 col-sm-10 col-xs-10 text-overflowA font-bold\"><span style=\"display:inline-flex;\"><i class=\"material-icons\">local_pharmacy</i><span style=\"margin: 5px 0 5px 6px;\">".$inst['NOMBRE']."</span></span></div><div class=\"col-lg-2 col-md-2 col-sm-2 col-xs-2\"><input onClick=\"seleccionaCambiarRutaInst(\'".$inst['INST_SNR']."\',\'chkCambiarRutaInst".$i."\');\" type=\"checkbox\" class=\"filled-in chk-col-indigo\" id=\"chkCambiarRutaInst".$i."\"/><label for=\"chkCambiarRutaInst".$i."\" ></label></div></div><div class=\"row\"><div class=\"col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0\"><div class=\"".$circulo."\"></div></div><div class=\"col-lg-8 col-md-8 col-sm-8 col-xs-8 text-overflowA p-r-0 margin-0\">".$inst['DIRECCION'].", ".$inst['POBLACION'].",".$inst['ESTADO']."</div><div class=\"col-lg-2 col-md-2 col-sm-2 col-xs-2 margin-0\">".$inst['RUTA']."</div></div></div></div></td></tr>');";
			}
			if($inst['TIPO_INST'] == 'Hospitales'){
				echo "$('#tblCambiarRutaInst tbody').append('<tr id=\"trinstR".$idContGnrl."\" onClick=\"enviaIdTrInst(this.id);\"><td><div class=\"row\"><div id=\"instR".$idContGnrl."\" class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12 pointer margin-0\" onclick=\"presentaDatos(\'instR".$idContGnrl."\',\'".$inst['INST_SNR']."\',\'divDatosInstituciones\',\'".$tipoInstTabla."\',\'\',\'".$inst['USUARIO_ID']."\');\"><div class=\"row\"><div class=\"col-lg-10 col-md-10 col-sm-10 col-xs-10 text-overflowA font-bold\"><span style=\"display:inline-flex;\"><i class=\"fas fa-hospital\"></i><span style=\"margin: 4px 0 5px 8px;\">".$inst['NOMBRE']."</span></span></div><div class=\"col-lg-2 col-md-2 col-sm-2 col-xs-2\"><input onClick=\"seleccionaCambiarRutaInst(\'".$inst['INST_SNR']."\',\'chkCambiarRutaInst".$i."\');\" type=\"checkbox\" class=\"filled-in chk-col-indigo\" id=\"chkCambiarRutaInst".$i."\"/><label for=\"chkCambiarRutaInst".$i."\" ></label></div></div><div class=\"row\"><div class=\"col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0\"><div class=\"".$circulo."\"></div></div><div class=\"col-lg-8 col-md-8 col-sm-8 col-xs-8 text-overflowA p-r-0 margin-0\">".$inst['DIRECCION'].", ".$inst['POBLACION'].",".$inst['ESTADO']."</div><div class=\"col-lg-2 col-md-2 col-sm-2 col-xs-2 margin-0\">".$inst['RUTA']."</div></div></div></div></td></tr>');";
			}
			if($inst['TIPO_INST'] == 'Farmacias'){
				echo "$('#tblCambiarRutaInst tbody').append('<tr id=\"trinstR".$idContGnrl."\" onClick=\"enviaIdTrInst(this.id);\"><td><div class=\"row\"><div id=\"instR".$idContGnrl."\" class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12 pointer margin-0\" onclick=\"presentaDatos(\'instR".$idContGnrl."\',\'".$inst['INST_SNR']."\',\'divDatosInstituciones\',\'".$tipoInstTabla."\',\'\',\'".$inst['USUARIO_ID']."\');\"><div class=\"row\"><div class=\"col-lg-10 col-md-10 col-sm-10 col-xs-10 text-overflowA font-bold\"><span style=\"display:inline-flex;\"><i class=\"fas fa-pills\"></i><span style=\"margin: 4px 0 5px 8px;\">".$inst['NOMBRE']."</span></span></div><div class=\"col-lg-2 col-md-2 col-sm-2 col-xs-2\"><input onClick=\"seleccionaCambiarRutaInst(\'".$inst['INST_SNR']."\',\'chkCambiarRutaInst".$i."\');\" type=\"checkbox\" class=\"filled-in chk-col-indigo\" id=\"chkCambiarRutaInst".$i."\"/><label for=\"chkCambiarRutaInst".$i."\" ></label></div></div><div class=\"row\"><div class=\"col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0\"><div class=\"".$circulo."\"></div></div><div class=\"col-lg-8 col-md-8 col-sm-8 col-xs-8 text-overflowA p-r-0 margin-0\">".$inst['DIRECCION'].", ".$inst['POBLACION'].",".$inst['ESTADO']."</div><div class=\"col-lg-2 col-md-2 col-sm-2 col-xs-2 margin-0\">".$inst['RUTA']."</div></div></div></div></td></tr>');";
			}
			if($inst['TIPO_INST'] == 'Otras instituciones'){
				echo "$('#tblCambiarRutaInst tbody').append('<tr id=\"trinstR".$idContGnrl."\" onClick=\"enviaIdTrInst(this.id);\"><td><div class=\"row\"><div id=\"instR".$idContGnrl."\" class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12 pointer margin-0\" onclick=\"presentaDatos(\'instR".$idContGnrl."\',\'".$inst['INST_SNR']."\',\'divDatosInstituciones\',\'".$tipoInstTabla."\',\'\',\'".$inst['USUARIO_ID']."\');\"><div class=\"row\"><div class=\"col-lg-10 col-md-10 col-sm-10 col-xs-10 text-overflowA font-bold\"><span style=\"display:inline-flex;\"></i><span style=\"margin: 4px 0 5px 8px;\">".$inst['NOMBRE']."</span></span></div><div class=\"col-lg-2 col-md-2 col-sm-2 col-xs-2\"><input onClick=\"seleccionaCambiarRutaInst(\'".$inst['INST_SNR']."\',\'chkCambiarRutaInst".$i."\');\" type=\"checkbox\" class=\"filled-in chk-col-indigo\" id=\"chkCambiarRutaInst".$i."\"/><label for=\"chkCambiarRutaInst".$i."\" ></label></div></div><div class=\"row\"><div class=\"col-lg-1 col-md-1 col-sm-1 col-xs-1 margin-0\"><div class=\"".$circulo."\"></div></div><div class=\"col-lg-8 col-md-8 col-sm-8 col-xs-8 text-overflowA p-r-0 margin-0\">".$inst['DIRECCION'].", ".$inst['POBLACION'].",".$inst['ESTADO']."</div><div class=\"col-lg-2 col-md-2 col-sm-2 col-xs-2 margin-0\">".$inst['RUTA']."</div></div></div></div></td></tr>');";
			}

			$i++;
		}
		
		$foot = '';
		//if($totalRegistros > $registrosPorPagina){
			$idsEnviar = str_replace("'","",$ids);
			$foot .= '<ul class="pagination margin-0">';
			if($numPagina > 1){
				$anterior = $numPagina - 1;
				$foot .= "<li><a href=\"#\" class=\"waves-effect font-14\" onClick=\"nuevaPaginaInst(1,\'".$hoy."\',\'".$idsEnviar."\',\'".$tipo."\');\">Inicio</a></li>";
				$foot .= "<li><a href=\"#\" class=\"waves-effect font-14\" onClick=\"nuevaPaginaInst(".$anterior.",\'".$hoy."\',\'".$idsEnviar."\',\'".$tipo."\');\">Anterior</a></li>";
			}
			for($i=1;$i<=$paginas;$i++){
				if($i == $numPagina){
					$foot .= "<li class=\"active\"><a>".$i."</a></li>";
				}else{
					//$foot .= "<a href='#' onClick='nuevaPaginaInst(".$i.",\"".$hoy."\",\"".$idsEnviar."\",\"".$tipo."\");'>".$i."</a>&nbsp;&nbsp;";
				}
			}
			if($numPagina < $paginas){
				$siguiente = $numPagina + 1;
				$foot .= "<li><a href=\"#\" class=\"waves-effect font-14\" onClick=\"nuevaPaginaInst(".$siguiente.",\'".$hoy."\',\'".$idsEnviar."\',\'".$tipo."\');\">Siguiente</a></li>";
				$foot .= "<li><a href=\"#\" class=\"waves-effect font-14\" onClick=\"nuevaPaginaInst(".$paginas.",\'".$hoy."\',\'".$idsEnviar."\',\'".$tipo."\');\">Fin</a></li>";
			}
			$foot .= "</ul><p class=\"margin-0 font-12\">Pag. ".$numPagina." de ".$paginas."</p>";
		//}
		echo "$('#".$tabla." tfoot').append('<tr><td class=\"align-center\">".$foot."</td></tr>');";
		echo "$('#tblCambiarRutaInst tfoot').append('<tr><td class=\"align-center\">".$foot."</td></tr>');";
		echo "$('#".$tabla."numReg').append('".$totalRegistros."');";
		echo "$('#tblCambiarRutaInstnumReg').append('".$totalRegistros."');";
	}
	echo "</script>";

	//echo "<script>$('#' + idTrInst).addClass('div-slt-lista');</script>";
	echo '<script>$("#'.$idTabla.'1").click();</script>'; //quitar si no se quiere mostrar el primero de la lista al cambiar de página
?>