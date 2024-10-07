<?php
	include "../conexion.php";
	
	$banderaedit=$_POST['banderaedit'];
	$cedulav = $_POST['cedula'];
	$queryduplicado="SELECT PROF_ID FROM PERSON WHERE PROF_ID='".$cedulav."'";
	$rsdupli=sqlsrv_query($conn, $queryduplicado,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	$num_rows=sqlsrv_num_rows($rsdupli);
	
	$queryduplicado2="SELECT P_PROF_ID FROM PERSON_APPROVAL WHERE P_PROF_ID='".$cedulav."'";
	$rsdupli2=sqlsrv_query($conn, $queryduplicado2,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	$num_rows2=sqlsrv_num_rows($rsdupli2);
	
	//echo "BANDERA ES: ".$banderaedit;
	if($banderaedit==0){
		if(sqlsrv_num_rows($rsdupli)> 0 || sqlsrv_num_rows($rsdupli2) > 0){
			echo '<script type="text/javascript">alertMedicoDuplicado('.$cedulav.');</script>';
		
			echo "<script>$('#btnGuardarPersonaNuevo').prop('disabled', false);";
			
		
	}else{
		function cambioDomicilio($ruta, $idPersona, $idInst, $existe, $conn, $email, $telefono,$torre,$piso,$consultorio,$departamento){
	//echo $tipoTrabajo." ,,, ".$puesto."<br>";
		//inhabilitamos pers_srep_work
		$qActualizaPSW = "update PERS_SREP_WORK set sync = 0, rec_stat = 2 
			where USER_SNR = '".$ruta."' and PERS_SNR = '".$idPersona."' and rec_stat = 0";
		if(! sqlsrv_query($conn, $qActualizaPSW)){
			echo "qActualizaPSW: ".$qActualizaPSW."<br>";
		}
		
		if($existe){
			///habilitamos
			$qActualizaPSWNuevo = "update PERS_SREP_WORK set sync = 0, rec_stat = 0 
				where USER_SNR = '".$ruta."' 
				and PERS_SNR = '".$idPersona."' 
				and INST_SNR = '".$idInst."'";
				
			$pwork = sqlsrv_fetch_array(sqlsrv_query($conn, "select pwork_snr from PERS_SREP_WORK where USER_SNR = '".$ruta."' and PERS_SNR = '".$idPersona."' and INST_SNR = '".$idInst."'"))['pwork_snr'];
			
		}else{
			//existe PWORK
			$qPLW = "select * from PERSLOCWORK 
				where PERS_SNR = '".$idPersona."' 
				and INST_SNR = '".$idInst."' ";
			$rsPLW = sqlsrv_query($conn, $qPLW, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if(sqlsrv_num_rows($rsPLW) > 0){//existe
				$regPLW = sqlsrv_fetch_array($rsPLW);
				$pwork = $regPLW['PWORK_SNR'];	
			}else{
				$pwork = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idPlw from PERSLOCWORK where PWORK_SNR <> '00000000-0000-0000-0000-000000000000'"))['idPlw'];
				$qInsertaPLW = "insert into PERSLOCWORK ( 
					PWORK_SNR,
					PERS_SNR,
					INST_SNR) values (
					'$pwork',
					'$idPersona',
					'$idInst') ";
				if(! sqlsrv_query($conn, $qInsertaPLW)){
					echo "qInsertaPLW: ".$qInsertaPLW."<br>";
				}
			}
			
			$qInsertaPSW = "insert into PERS_SREP_WORK ( 
				PERSREP_SNR,
				PWORK_SNR,
				USER_SNR,
				PERS_SNR,
				INST_SNR,
				REC_STAT,
				SYNC ) values ( 
				NEWID(),
				'$pwork',
				'$ruta',
				'$idPersona',
				'$idInst',
				0,
				0) ";
				
			if(! sqlsrv_query($conn, $qInsertaPSW)){
				echo "qInsertaPSW: ".$qInsertaPSW."<br>";
			}
				
			///revisa user_territ
			$qUserTerrit = "select * from USER_TERRIT where INST_SNR = '".$idInst."' and USER_SNR = '".$ruta."' ";
			$rsUserTerrit = sqlsrv_query($conn, $qUserTerrit, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if(sqlsrv_num_rows($rsUserTerrit) > 0){
				$regUserTerrit = sqlsrv_query($conn, $rsUserTerrit);
				if($regUserTerrit['REC_STAT'] != 0){//se activa
					$actualizaUT = "update USER_TERRIT set REC_STAT = 0, sync = 0 where UTER_SNR = '".$regUserTerrit['UTER_SNR']."' ";
					if(! sqlsrv_query($conn, $actualizaUT)){
						echo "actualizaUT: ".$actualizaUT."<br>";
					}
				}
			}else{// lo ingresamos
				$insertaUT = "insert into USER_TERRIT ( 
					UTER_SNR,
					INST_SNR,
					USER_SNR,
					REC_STAT,
					SYNC ) values ( 
					NEWID(),
					'$idInst',
					'$ruta',
					0,
					0 )";
				if(! sqlsrv_query($conn, $insertaUT)){
					echo "insertaUT: ".$insertaUT."<br>";
				}
			}
		}
		$tipoTrabajo = (!isset($tipoTrabajo) || $tipoTrabajo == '') ? '00000000-0000-0000-0000-000000000000' : $tipoTrabajo;
		$puesto = (!isset($puesto) || $puesto == '') ? '00000000-0000-0000-0000-000000000000' : $puesto;
		//actualizamos PLW
		$qActualizaPLWDatos = "update PERSLOCWORK set 
			rec_stat = 0, 
			sync = 0, 
			EMAIL = '$email',
			TEL = '$telefono',
			TOWER = '$torre',
			FLOOR = '$piso',
			OFFICE = '$consultorio',
			DEPARTMENT = '$departamento' 
			where PWORK_SNR = '".$pwork."'";

		if(! sqlsrv_query($conn, $qActualizaPLWDatos)){
			echo "error al actualizar PERSLOCWORK: ".$qActualizaPLWDatos."<br>";
		} 
		
		//echo "pwork: ".$pwork."<br>";
		
		return $pwork;
	}//termina cambio Domicilio
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		
		/*echo $_POST['fecha'];
		echo "<br><br>";
		echo (strpos($_POST['fecha'], 'null') !== false) ? 'encontrado' : 'no encontrado';*/
		$idPersona = $_POST['idPersona'];
		$tipoPersona = ($_POST['tipoPersona'] == '') ? '00000000-0000-0000-0000-000000000000' : $_POST['tipoPersona'];
		$nombre = strtoupper($_POST['nombre']);
		$paterno = strtoupper($_POST['paterno']);
		$materno = strtoupper($_POST['materno']);
		$sexo = ($_POST['sexo'] == '') ? '00000000-0000-0000-0000-000000000000' : $_POST['sexo'];
		$especialidad = $_POST['especialidad'];
		$subespecialidad = ($_POST['subespecialidad'] == '') ? '00000000-0000-0000-0000-000000000000' : $_POST['subespecialidad'];
		$pacientesSemana = ($_POST['pacientesSemana'] == '') ? '00000000-0000-0000-0000-000000000000' : $_POST['pacientesSemana'];
		$honorarios = ($_POST['honorarios'] == '') ? '00000000-0000-0000-0000-000000000000' : $_POST['honorarios'];
		$fecha = (strpos($_POST['fecha'], 'null') !== false) ? 'null' : $_POST['fecha'];
		$categoria = ($_POST['categoria'] == '') ? 'FF4436E8-8FF9-48D4-853F-67CF43A01DDB' : $_POST['categoria'];
		//$categoria = $_POST['categoria'];
		$cedula = $_POST['cedula'];
		$dificultadVisita = $_POST['dificultadVisita'];
		//$botiquin = $_POST['botiquin'];
		/*$iguala = strtoupper($_POST['iguala']);*/
		$idInst = $_POST['idInst'];
		$telefono = $_POST['telefono'];//PLW
		//$telefono1 = $_POST['telefono1'];//PLW
		$email = strtolower($_POST['email']);//PLW
		$corto = strtoupper($_POST['corto']);
		$largo = strtoupper($_POST['largo']);
		$generales = strtoupper($_POST['generales']);
		$idUsuario = $_POST['idUsuario'];
		$numInterior = $_POST['interior'];
		$pasatiempo = $_POST['pasatiempo'];
		/*$horario = $_POST['horario'];
		$lunesComentarios = strtoupper($_POST['lunesComentarios']);
		$martesComentarios = strtoupper($_POST['martesComentarios']);
		$miercolesComentarios = strtoupper($_POST['miercolesComentarios']);
		$juevesComentarios = strtoupper($_POST['juevesComentarios']);
		$viernesComentarios = strtoupper($_POST['viernesComentarios']);
		$sabadoComentarios = strtoupper($_POST['sabadoComentarios']);
		$domingoComentarios = strtoupper($_POST['domingoComentarios']);*/
		/*$abierto1 = strtoupper($_POST['abierto1']);
		$abierto2 = strtoupper($_POST['abierto2']);
		$abierto3 = strtoupper($_POST['abierto3']);*/
		$torre = strtoupper($_POST['torre']);
		$piso = strtoupper($_POST['piso']);
		$consultorio = strtoupper($_POST['consultorio']);
		$departamento = strtoupper($_POST['departamento']);
		//$tipoTrabajo = $_POST['tipoTrabajo'];
		//$puesto = $_POST['puesto'];
		$telPersonal = $_POST['telPersonal'];
		$mailPersonal = $_POST['mailPersonal'];
		$telPersonal2 = $_POST['telPersonal2'];
		$mailPersonal2 = $_POST['mailPersonal2'];
		$celular = $_POST['celular'];
		
		$estatusPersona = $_POST['estatusPersona'];
		//$field_02_SNR = $_POST['field_02_SNR'];
		$prod1what = $_POST['prod1what']; 
		$prod1w = $_POST['prod1w']; 
		$prod1h = $_POST['prod1h']; 
		$prod1a = $_POST['prod1a']; 
		$prod1t = $_POST['prod1t']; 
		$prod2what = $_POST['prod2what']; 
		$prod2w = $_POST['prod2w']; 
		$prod2h = $_POST['prod2h']; 
		$prod2a = $_POST['prod2a']; 
		$prod2t = $_POST['prod2t']; 
		$prod3what = $_POST['prod3what']; 
		$prod3w = $_POST['prod3w']; 
		$prod3h = $_POST['prod3h']; 
		$prod3a = $_POST['prod3a']; 
		$prod3t = $_POST['prod3t']; 
		$prod4what = $_POST['prod4what']; 
		$prod4w = $_POST['prod4w']; 
		$prod4h = $_POST['prod4h']; 
		$prod4a = $_POST['prod4a']; 
		$prod4t = $_POST['prod4t']; 
		$prod5what = $_POST['prod5what']; 
		$prod5w = $_POST['prod5w']; 
		$prod5h = $_POST['prod5h']; 
		$prod5a = $_POST['prod5a']; 
		$prod5t = $_POST['prod5t'];  
		$divmedico = $_POST['divmedico']; 
		$lider = $_POST['lider']; 
		$paraestatales = $_POST['paraestatales']; 
		$segmentacionflonorm = $_POST['segmentacionflonorm']; 
		$segmentacionvessel = $_POST['segmentacionvessel']; 
		$segmentacionzirfos = $_POST['segmentacionzirfos'];
		$segmentacionateka = $_POST['segmentacionateka'];		
		$segmentacionganar = $_POST['segmentacionganar']; 
		$segmentaciondesarrollar = $_POST['segmentaciondesarrollar']; 
		$segmentaciondefender = $_POST['segmentaciondefender']; 
		$segmentacionevaluar = $_POST['segmentacionevaluar']; 
		
		$field_12_SNR = $_POST['field_12'];
		$field_13_SNR = $_POST['field_13'];
		$field_14_SNR = $_POST['field_14'];
		$field_15_SNR = $_POST['field_15'];
		$field_16_SNR = $_POST['field_16'];
		$field_17_SNR = $_POST['field_17'];
		$field_18_SNR = $_POST['field_18'];
		
		//echo $puesto." -- ".$tipoTrabajo."<br>";
		
		$tipoUsuario = $_POST['tipoUsuario'];
		$ruta = $_POST['ruta'];
		
		$idPworkNuevo = '';
		
		$actualizaPerson = 0;
		$insertaApproval = 0;
		
		$cicloActivo = $_POST['cicloActivo'];
		
		/* nuevo calculo de frecuencia */
		$qfrecuencia = "select frecvis_snr 
			from cycle_pers_categ_spec 
			where rec_stat = 0 
			and cycle_snr = (select cycle_snr from cycles where name = '".$cicloActivo."' and rec_stat = 0)
			and spec_snr = '".$especialidad."' 
			and category_snr = '".$categoria."' ";
			
		$frecuencia = sqlsrv_fetch_array(sqlsrv_query($conn, $qfrecuencia))['frecvis_snr'];
		
		if(empty($frecuencia)){
			$frecuencia = '00000000-0000-0000-0000-000000000000';
		}
		
		/* checa si no existe el registro */
		/*if($idPersona != ''){
			$queryValida = "select * 
				from PERSON_APPROVAL pa, APPROVAL_STATUS at 
				where pa.P_PERS_SNR = '".$idPersona."' 
				and P_APPROVAL_STATUS = 'C'
				and at.CHANGE_USER_SNR = '".$idUsuario."'
				and at.APPROVED_STATUS = 1 
				and pa.PERS_APPROVAL_SNR = at.PERS_APPROVAL_SNR";
			echo $queryValida;
			$rsValida = sqlsrv_query($conn, $queryValida , array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if(sqlsrv_num_rows($rsValida) > 0){
				echo "<script>alert('Ya existe un movivmiento para ese médico, debe esperar a que sea liberado!!!');</script>";
				return;
			}
		}*/
		/*********************************/
		
		$rsIdPersonApproval = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idPersApproval from PERSON_APPROVAL where PERS_APPROVAL_SNR = '00000000-0000-0000-0000-000000000000'"));
		$idPersApproval = $rsIdPersonApproval['idPersApproval'];
		$rsNR = sqlsrv_fetch_array(sqlsrv_query($conn, "select max(P_NR)+1 as nr from PERSON_APPROVAL"));
		$nr = $rsNR['nr'];
		$rsIdApprovalSatus = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idApprovalStatus from APPROVAL_STATUS where PERS_APPROVAL_SNR = '00000000-0000-0000-0000-000000000000'"));
		$idApprovalStatus = $rsIdApprovalSatus['idApprovalStatus'];
		
		//echo "idPersona: ".$idPersona;
		if($idPersona != ''){
			$tipoMovimiento = "C";

			//echo "tipoUsuario: ".$tipoUsuario."<br>";
			
			/*grabo approval changes */
			/*$queryCampos = "select rc.rtab_snr, rc.rcol_snr, upper(rc.name) as name
			from rep_tables rt, rep_columns rc
			where rt.name = 'person'
			and rt.RTAB_SNR = rc.RTAB_SNR ";*/
			if($tipoUsuario == 4){
				/* revisamos si hay cambio de dirección */
				//$rsPwork = sqlsrv_fetch_array(sqlsrv_query($conn, "select PWORK_SNR as idPwork from PERS_SREP_WORK where PERS_SNR = '$idPersona' and rec_stat = 0 "));
				$rsPwork = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from PERSLOCWORK where pers_snr = '$idPersona' and REC_STAT = 0"));
				$idPwork = $rsPwork['PWORK_SNR'];
				$idInstPWL = $rsPwork['INST_SNR'];
				
				if($idInstPWL != $idInst){//cambio de direccion
					$idPworkNuevo = $rsPwork = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idPwork from PERSLOCWORK where PWORK_SNR = '00000000-0000-0000-0000-000000000000'"))['idPwork'];
					$qExisteInst = "select * from inst where inst_snr = '".$idInst."' ";
					$rsExisteInst = sqlsrv_query($conn, $qExisteInst, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
					if(sqlsrv_num_rows($rsExisteInst) > 0){
						$qInst = " select i.NAME, i.STREET1, c.NAME as COLONIA, c.ZIP, d.NAME as CIUDAD, edo.NAME as ESTADO  
							from inst i, city c, DISTRICT d, state edo
							where i.INST_SNR = '".$idInst."'
							and i.CITY_SNR = c.CITY_SNR
							and c.DISTR_SNR = d.DISTR_SNR
							and c.STATE_SNR = edo.STATE_SNR ";
					}else{
						$qInst = " select i.I_NAME as NAME, i.I_STREET1 as STREET1, c.NAME as COLONIA, c.ZIP, d.NAME as CIUDAD, edo.NAME as ESTADO  
							from inst_approval i, city c, DISTRICT d, state edo
							where i.I_INST_SNR = '".$idInst."'
							and i.I_CITY_SNR = c.CITY_SNR
							and c.DISTR_SNR = d.DISTR_SNR
							and c.STATE_SNR = edo.STATE_SNR ";
					}
					
				}else{
					$qInst = " select i.NAME, i.STREET1, c.NAME as COLONIA, c.ZIP, d.NAME as CIUDAD, edo.NAME as ESTADO  
							from inst i, city c, DISTRICT d, state edo
							where i.INST_SNR = '".$idInst."'
							and i.CITY_SNR = c.CITY_SNR
							and c.DISTR_SNR = d.DISTR_SNR
							and c.STATE_SNR = edo.STATE_SNR ";
				}
				$rsInst = sqlsrv_fetch_array(sqlsrv_query($conn, $qInst));
				$inst = $rsInst['NAME'];
				$calle = $rsInst['STREET1'];
				$colonia = $rsInst['COLONIA'];
				$zip = $rsInst['ZIP'];
				$ciudad = $rsInst['CIUDAD'];
				$estado = $rsInst['ESTADO'];
				
				$queryCampos = "select rc.TABLE_NR, rc.COLUMN_NR, upper(rc.name) as name, rc.approval
					from CONFIG_TABLE rt, CONFIG_FIELD rc
					where rt.name = 'person'
					and rt.TABLE_NR = rc.TABLE_NR
					union
					select rc.TABLE_NR, rc.COLUMN_NR,   
					case when upper(rc.name) = 'TEL2' then 'TEL2_INST' else upper(rc.name) end as name,
					rc.approval
					from CONFIG_TABLE rt, CONFIG_FIELD rc
					where rt.name = 'inst'
					and rt.TABLE_NR = rc.TABLE_NR
					union
					select rc.TABLE_NR, rc.COLUMN_NR, 
					case when upper(rc.name) = 'NAME' then 'COLONIA' else upper(rc.NAME) end as name, rc.approval
					from CONFIG_TABLE rt, CONFIG_FIELD rc
					where rt.name = 'city'
					and rt.TABLE_NR = rc.TABLE_NR
					and rc.name in ('NAME','ZIP')
					union
					select rc.TABLE_NR, rc.COLUMN_NR, 
					case when upper(rc.name) = 'NAME' then 'CIUDAD' else upper(rc.NAME) end as name, rc.approval
					from CONFIG_TABLE rt, CONFIG_FIELD rc
					where rt.name = 'district'
					and rt.TABLE_NR = rc.TABLE_NR
					and rc.name = 'NAME'
					UNION
					select rc.TABLE_NR, rc.COLUMN_NR, 
					case when upper(rc.name) = 'NAME' then 'ESTADO' else upper(rc.NAME) end as name, rc.approval
					from CONFIG_TABLE rt, CONFIG_FIELD rc
					where rt.name = 'state'
					and rt.TABLE_NR = rc.TABLE_NR
					and rc.name = 'NAME' 
					union 
					select rc.TABLE_NR, rc.COLUMN_NR, upper(rc.name) as name, rc.approval
					from CONFIG_TABLE rt, CONFIG_FIELD rc
					where rt.name = 'perslocwork'
					and rt.TABLE_NR = rc.TABLE_NR";
					
				//echo $queryCampos."<br><br>";
				
				$rsCampos = sqlsrv_query($conn, $queryCampos);
				
				$rcol = array();
				$campos = array();
				$rtab = array();
				$approval = array();
				
				while($campo = sqlsrv_fetch_array($rsCampos)){
					$rtab[] = $campo['TABLE_NR'];
					$rcol[] = $campo['COLUMN_NR'];
					if($campo['name'] == "EMAIL" && $campo['TABLE_NR'] == 14){
						$campos[] = "EMAIL_P";
					}else{
						$campos[] = $campo['name'];
					}
					$approval[] = $campo['approval'];
				}
				
				$qAnt = "select p.*, i.NAME, i.STREET1, c.ZIP, c.name as COLONIA, 
					d.NAME as CIUDAD, edo.NAME as ESTADO, 
					plw.*,
					plw.tel as TEL_PLW,
					plw.email as EMAIL_PLW,
					p.info as info_person
					from person p, PERS_SREP_WORK psw, inst i, city c, DISTRICT d, state edo, PERSLOCWORK plw
					where p.PERS_SNR = psw.PERS_SNR
					and i.INST_SNR = psw.INST_SNR
					and i.CITY_SNR = c.CITY_SNR
					and c.DISTR_SNR = d.DISTR_SNR
					and c.STATE_SNR = edo.STATE_SNR
					and plw.PERS_SNR = p.PERS_SNR
					and i.INST_SNR = plw.INST_SNR
					and psw.rec_stat = 0 
					and plw.rec_stat = 0
					and p.pers_snr = '".$idPersona."' ";
				//echo $qAnt."<br><br>";
				$queryAnt = sqlsrv_fetch_array(sqlsrv_query($conn, $qAnt));
				//echo "select * from person where PERS_SNR = '$idPersona'<br>";
				$queryPersonApproval = "update PERSON set SYNC = 0 ";
				
				if($inst != $queryAnt['NAME']){
					$idCampo = array_search ( "NAME" , $campos );
					$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$inst."',0,0)";
					$insertaApproval = 1;
					if(! sqlsrv_query($conn, $insertaChange)){
						$insertaChange."<br><br>";
					}
				}
				
				if($calle != $queryAnt['STREET1']){
					$idCampo = array_search ( "STREET1" , $campos );
					$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$calle."',0,0)";
					$insertaApproval = 1;
					if(! sqlsrv_query($conn, $insertaChange)){
						$insertaChange."<br><br>";
					}
				}
				
				if($colonia != $queryAnt['COLONIA']){
					$idCampo = array_search ( "COLONIA" , $campos );
					$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$colonia."',0,0)";
					$insertaApproval = 1;
					if(! sqlsrv_query($conn, $insertaChange)){
						$insertaChange."<br><br>";
					}
				}
				
				if($zip != $queryAnt['ZIP']){
					$idCampo = array_search ( "ZIP" , $campos );
					$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$zip."',0,0)";
					$insertaApproval = 1;
					if(! sqlsrv_query($conn, $insertaChange)){
						$insertaChange."<br><br>";
					}
				}
				
				if($ciudad != $queryAnt['CIUDAD']){
					$idCampo = array_search ( "CIUDAD" , $campos );
					$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$ciudad."',0,0)";
					$insertaApproval = 1;
					if(! sqlsrv_query($conn, $insertaChange)){
						$insertaChange."<br><br>";
					}
				}
				
				if($estado != $queryAnt['ESTADO']){
					$idCampo = array_search ( "ESTADO" , $campos );
					$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$estado."',0,0)";
					$insertaApproval = 1;
					if(! sqlsrv_query($conn, $insertaChange)){
						$insertaChange."<br><br>";
					}
				}
				
				if($queryAnt['PERSTYPE_SNR'] != $tipoPersona){
					$idCampo = array_search ( "PERSTYPE_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$tipoPersona."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",PERSTYPE_SNR = '$tipoPersona' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['FNAME'] != $nombre){
					$idCampo = array_search ( "FNAME" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$nombre."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",FNAME = '$nombre' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['LNAME'] != $paterno){
					$idCampo = array_search ( "LNAME" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$paterno."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",LNAME = '$paterno' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['MOTHERS_LNAME'] != $materno){
					$idCampo = array_search ( "MOTHERS_LNAME" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$materno."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{	
						$queryPersonApproval .= ",mothers_lname = '$materno' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['PROF_ID'] != $cedula){
					$idCampo = array_search ( "PROF_ID" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$cedula."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",PROF_ID = '$cedula' ";
						$actualizaPerson = 1;
					}
				}

				if($queryAnt['BIRTHDATE'] != null){
					foreach ($queryAnt['BIRTHDATE'] as $key => $val) {
						if(strtolower($key) == 'date'){
							//echo $fecha." ::: ".substr($val, 0, 10)."<br>";
							if($fecha != substr($val, 0, 10)){
								$idCampo = array_search ( "BIRTHDATE" , $campos );
								if($approval[$idCampo] == 1){
									$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$fecha."',0,0)";
									$insertaApproval = 1;
									if(! sqlsrv_query($conn, $insertaChange)){
										$insertaChange."<br><br>";
									}
								}else{
									$queryPersonApproval .= ",BIRTHDATE = '$fecha' ";
									$actualizaPerson = 1;
								}
							}
						}
					}
				}else{
					if($fecha != '' && $fecha != 'null'){
						$idCampo = array_search ( "BIRTHDATE" , $campos );
						if($approval[$idCampo] == 1){
							$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$fecha."',0,0)";
							$insertaApproval = 1;
							if(! sqlsrv_query($conn, $insertaChange)){
								$insertaChange."<br><br>";
							}
						}else{
							$queryPersonApproval .= ",BIRTHDATE = '$fecha' ";
							$actualizaPerson = 1;
						}
					}
				}
				
				if($queryAnt['SPEC_SNR'] != $especialidad){
					$idCampo = array_search ( "SPEC_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$especialidad."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",SPEC_SNR = '$especialidad' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['SUBSPEC_SNR'] != $subespecialidad){
					$idCampo = array_search ( "SUBSPEC_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$subespecialidad."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",SUBSPEC_SNR = '$subespecialidad' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['PATPERWEEK_SNR'] != $pacientesSemana){
					$idCampo = array_search ( "PATPERWEEK_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$pacientesSemana."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",patperweek_snr = '$pacientesSemana' ";
						$actualizaPerson = 1;
					}
				}
				
				
				if($queryAnt['FEE_TYPE_SNR'] != $honorarios){
					$idCampo = array_search ( "FEE_TYPE_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$honorarios."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",FEE_TYPE_SNR = '$honorarios' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['FRECVIS_SNR'] != $frecuencia){
					$idCampo = array_search ( "FRECVIS_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$frecuencia."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",FRECVIS_SNR = '$frecuencia' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['SEX_SNR'] != $sexo){
					$idCampo = array_search ( "SEX_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$sexo."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",SEX_SNR = '$sexo' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['DIFFVIS_SNR'] != $dificultadVisita){
					$idCampo = array_search ( "DIFFVIS_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$dificultadVisita."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",DIFFVIS_SNR = '$dificultadVisita' ";
						$actualizaPerson = 1;
					}
				}

				
				if($queryAnt['CATEGORY_SNR'] != $categoria){
					$idCampo = array_search ( "CATEGORY_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$categoria."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",CATEGORY_SNR = '$categoria' ";
						$actualizaPerson = 1;
					}
				}

				if($queryAnt['STATUS_SNR'] != $estatusPersona){
					$idCampo = array_search ( "STATUS_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$estatusPersona."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{	
						$queryPersonApproval .= ",STATUS_SNR = '$estatusPersona' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['TEL1'] != $telPersonal){
					$idCampo = array_search ( "TEL1" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$telPersonal."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",TEL1 = '$telPersonal' ";
						$actualizaPerson = 1;
					}
				}
				//echo $queryAnt['TEL2']." != ".$telPersonal2."<br>";
				if($queryAnt['TEL2'] != $telPersonal2){
					//if(){}
					$idCampo = array_search ( "TEL2" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$telPersonal2."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",TEL2 = '$telPersonal2' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['MOBILE'] != $celular){
					$idCampo = array_search ( "MOBILE" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$celular."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",MOBILE = '$celular' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['EMAIL1'] != $mailPersonal){
					$idCampo = array_search ( "EMAIL1" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$mailPersonal."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",EMAIL1 = '$mailPersonal' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['EMAIL2'] != $mailPersonal2){
					$idCampo = array_search ( "EMAIL2" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$mailPersonal2."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",EMAIL2 = '$mailPersonal2' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['INFO_SHORTTIME'] != $corto){
					$idCampo = array_search ( "INFO_SHORTTIME" , $campos );
					//echo "INFO_SHORTTIME: ".$idCampo."<br>";
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$corto."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",INFO_SHORTTIME = '$corto' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['INFO_LONGTIME'] != $largo){
					$idCampo = array_search ( "INFO_LONGTIME" , $campos );
					//echo $idCampo."<br>";
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$largo."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",INFO_LONGTIME = '$largo' ";
						$actualizaPerson = 1;
					}
				}
				//echo "info: ".$queryAnt['INFO']." ::: ".$generales." :: ";
				if($queryAnt['INFO'] != $generales){
					$idCampo = array_search ( "INFO" , $campos );
					//echo "generales: ".$idCampo."<br>";
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$generales."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",INFO = '$generales' ";
						$actualizaPerson = 1;
					}
				}
				
				$queryPersonApproval .= "where PERS_SNR = '$idPersona'";
				
				//echo $queryPersonApproval;
				
				if($actualizaPerson == 1){
					if(! sqlsrv_query($conn, $queryPersonApproval)){
						echo "person: ".$queryPersonApproval;
					}
				}
				/*************************/
				/*actualizar perslocwork*/
				$actualizaplw = 0;
				$queryPLW = '';
				
				if($telefono != $queryAnt['TEL_PLW']){
					$idCampo = array_search ( "TEL" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",''00000000-0000-0000-0000-000000000000'','".$telefono."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPLW .= ",TEL = '$telefono' ";
						$actualizaplw = 1;
					}
				}
				
				if($email != $queryAnt['EMAIL_PLW']){
					$idCampo = array_search ( "EMAIL" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",''00000000-0000-0000-0000-000000000000'','".$email."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPLW .= ",EMAIL = '$email' ";
						$actualizaplw = 1;
					}
				}
				
				if($torre != $queryAnt['TOWER']){
					$idCampo = array_search ( "TOWER" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",''00000000-0000-0000-0000-000000000000'','".$torre."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPLW .= ",TOWER = '$torre' ";
						$actualizaplw = 1;
					}
				}
				
				if($piso != $queryAnt['FLOOR']){
					$idCampo = array_search ( "FLOOR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",''00000000-0000-0000-0000-000000000000'','".$piso."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPLW .= ",FLOOR = '$piso' ";
						$actualizaplw = 1;
					}
				}
				
				if($consultorio != $queryAnt['OFFICE']){
					$idCampo = array_search ( "OFFICE" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",''00000000-0000-0000-0000-000000000000'','".$consultorio."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPLW .= ",OFFICE = '$consultorio' ";
						$actualizaplw = 1;
					}
				}
				
				if($departamento != $queryAnt['DEPARTMENT']){
					$idCampo = array_search ( "DEPARTMENT" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",''00000000-0000-0000-0000-000000000000'','".$departamento."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPLW .= ",DEPARTMENT = '$departamento' ";
						$actualizaplw = 1;
					}
				}
				
				if($actualizaplw){
					$updatePLW = "update PERSLOCWORK set sync = 0 ";
					$updatePLW .= $queryPLW;
					$updatePLW .= " where pwork_snr = '".$queryAnt['PWORK_SNR']."' ";
					
					if(! sqlsrv_query($conn, $updatePLW)){
						echo "uPLW: ".$updatePLW."<br><br>";
					}
					
					//echo $updatePLW;
				}
				
				
				
				/************/
			}else{//no es repre
				if($fecha == 'null'){
					$fecha = $fecha;
				}else{
					$fecha = "'".$fecha."'";
				}
				$queryPerson = "update person set 
				PERSTYPE_SNR = '$tipoPersona', 
				FNAME = '$nombre',
				LNAME = '$paterno',
				SEX_SNR = '$sexo',
				INFO_SHORTTIME = '$corto',
				INFO_LONGTIME = '$largo',
				INFO = '$generales',
				SPEC_SNR = '$especialidad',
				BIRTHDATE = $fecha,
				mothers_lname = '$materno',
				prof_id = '$cedula',
				subSpec_snr = '$subespecialidad',
				SYNC = 0,
				frecvis_snr = '$frecuencia',
				category_snr = '$categoria',
				PATPERWEEK_SNR = '$pacientesSemana',
				EMAIL1 = '".strtolower($mailPersonal)."',
				EMAIL2 = '".strtolower($mailPersonal2)."',
				TEL1 = '$telPersonal',
				TEL2 = '$telPersonal2',
				MOBILE = '$celular',
				fee_type_snr = '$honorarios',
				diffvis_snr = '$dificultadVisita',
				STATUS_SNR = '$estatusPersona',
				REC_STAT=0
				where PERS_SNR = '$idPersona' ";
				
				if(! sqlsrv_query($conn, $queryPerson)){
					echo "queryPerson: ".$queryPerson."<br>";
				}
				
				/*valida dir */
				/*$rsPwork = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from PERSLOCWORK where pers_snr = '$idPersona' and REC_STAT = 0"));
				$idPwork = $rsPwork['PWLOC_SNR'];
				$idInstPWL = $rsPwork['LOC_SNR'];*/
				
				$qPwork = "select * from PERS_SREP_WORK where USER_SNR = '$ruta' and PERS_SNR = '$idPersona' and INST_SNR = '$idInst'  ";
				
				$rsPwork = sqlsrv_query($conn, $qPwork, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
				if(sqlsrv_num_rows($rsPwork) > 0){//existe
					$regPwork = sqlsrv_fetch_array($rsPwork);
					if($regPwork['REC_STAT'] != 0){///la direccion cambió
						$idPwork = cambioDomicilio($ruta, $idPersona, $idInst, 1, $conn, $email, $telefono,$torre,$piso,$consultorio,$departamento);
					}else{
						$idPwork = $regPwork['PWORK_SNR'];
						
						$qActualizaPLWDatos2 = "update PERSLOCWORK set 
						rec_stat = 0, 
						sync = 0, 
						EMAIL = '$email',
						TEL = '$telefono',
						TOWER = '$torre',
						FLOOR = '$piso',
						OFFICE = '$consultorio',
						DEPARTMENT = '$departamento' 
						where PERS_SNR = '$idPersona'  and INST_SNR = '$idInst'";

						if(! sqlsrv_query($conn, $qActualizaPLWDatos2)){
							echo "error al actualizar PERSLOCWORK: ".$qActualizaPLWDatos2."<br>";
						}
					}
				}else{//la dirección cambio
					$idPwork = cambioDomicilio($ruta, $idPersona, $idInst, 0, $conn, $email, $telefono,$torre,$piso,$consultorio,$departamento);
				} 
			}
			
			$actualizaPersonUD = "update PERSON_UD SET
				Prod1What_SNR = '".$prod1what."', 
				Prod1W = '".$prod1w."', 
				Prod1H = '".$prod1h."', 
				Prod1A = '".$prod1a."', 
				Prod1T = '".$prod1t."',  
				Prod2What_SNR = '".$prod2what."',  
				Prod2W = '".$prod2w."', 
				Prod2H = '".$prod2h."', 
				Prod2A = '".$prod2a."', 
				Prod2T = '".$prod2t."', 
				Prod3What_SNR = '".$prod3what."',  
				Prod3W = '".$prod3w."', 
				Prod3H = '".$prod3h."', 
				Prod3A = '".$prod3a."', 
				Prod3T = '".$prod3t."', 
				Prod4What_SNR = '".$prod4what."',  
				Prod4W = '".$prod4w."', 
				Prod4H = '".$prod4h."', 
				Prod4A = '".$prod4a."', 
				Prod4T = '".$prod4t."', 
				Prod5What_SNR = '".$prod5what."',  
				Prod5W = '".$prod5w."', 
				Prod5H = '".$prod5h."', 
				Prod5A = '".$prod5a."', 
				Prod5T = '".$prod5t."', 
				
				field_01_SNR = '".$divmedico."', 
				field_02_SNR = '".$lider."', 
				field_03_SNR = '".$paraestatales."',  
				field_04_SNR = '".$segmentacionflonorm."',  
				field_05_SNR = '".$segmentacionvessel."', 
				field_06_SNR = '".$segmentacionzirfos."',  
				field_07_SNR = '".$segmentacionateka."', 
				field_08_SNR = '".$segmentacionganar."',  
				field_09_SNR = '".$segmentaciondesarrollar."',  
				field_10_SNR = '".$segmentaciondefender."', 
				field_11_SNR = '".$segmentacionevaluar."', 
				field_12_SNR = '".$field_12_SNR."', 
				field_13_SNR = '".$field_13_SNR."',
				field_14_SNR = '".$field_14_SNR."', 
				field_15_SNR = '".$field_15_SNR."',
				field_16_SNR = '".$field_16_SNR."', 
				field_17_SNR = '".$field_17_SNR."',
				field_18_SNR = '".$field_18_SNR."', 				
				SYNC = 0,
				CHANGED_TIMESTAMP = getdate() 
				where PERS_SNR = '".$idPersona."' ";
				
			if(! sqlsrv_query($conn, $actualizaPersonUD)){
				echo "personUD: ".$actualizaPersonUD."<br>";
			}
			
			if($pasatiempo != ''){
				$arrpasatiempoNuevo = explode(",", $pasatiempo);
			}else{
				$arrpasatiempoNuevo = array();
			}
			$arrPasatiempoViejo = array();
			$queryPasatiempos = "select * from PERSON_BANK where pers_snr = '$idPersona'";
			$rsPasatiempos = sqlsrv_query($conn, $queryPasatiempos, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if(sqlsrv_num_rows($rsPasatiempos) > 0){
				while($pasatiempo = sqlsrv_fetch_array($rsPasatiempos)){
					$arrPasatiempoViejo[] = $pasatiempo['BANK_SNR'];
					if(in_array($pasatiempo['BANK_SNR'], $arrpasatiempoNuevo)){
						if($pasatiempo['REC_STAT'] != 0){
							if(! sqlsrv_query($conn, "update PERSON_BANK set rec_stat = 0 where pers_bank_snr = '".$pasatiempo['PERS_BANK_SNR']."'")){
								echo "<script>alert('Problemas en PERSON_BANK');</script>";
							}
						}
					}else{
						if($pasatiempo['REC_STAT'] == 0){
							if(! sqlsrv_query($conn, "update PERSON_BANK set rec_stat = 2 where pers_bank_snr = '".$pasatiempo['PERS_BANK_SNR']."'")){
								echo "<script>alert('Problemas en PERSON_BANK');</script>";
							}
						}
					}
				}
			}
			for($i=0;$i<count($arrpasatiempoNuevo);$i++){
				//echo "<br><br>";
				if(! in_array($arrpasatiempoNuevo[$i], $arrPasatiempoViejo)){
					$queryPN = "insert into PERSON_BANK values(NEWID(), '".$idPersona."','".$arrpasatiempoNuevo[$i]."',0, 0)";
					if(! sqlsrv_query($conn, $queryPN)){
						echo "Error: ".$queryPN."</script>";
						
					}
				}
			}
			
			/*$queryHorario = "select * from persworktime where pers_snr = '$idPersona' and REC_STAT = 0";
			$rsHorario = sqlsrv_query($conn, $queryHorario, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			
			if(sqlsrv_num_rows($rsHorario) > 0 ){
				$arrHorario = sqlsrv_fetch_array($rsHorario);
				$queryHorario = "update persworktime set 
					PON_AM = ".substr($horario, 0, 1).",
					PON_PM = ".substr($horario, 1, 1).",
					FREE1 = ".substr($horario, 3, 1).",
					BEST1 = ".substr($horario, 4, 1).",
					UT_AM = ".substr($horario, 5, 1).",
					UT_PM = ".substr($horario, 6, 1).",
					FREE2 = ".substr($horario, 8, 1).",
					BEST2 = ".substr($horario, 9, 1).",
					SR_AM = ".substr($horario, 10, 1).",
					SR_PM = ".substr($horario, 11, 1).",
					FREE3 = ".substr($horario, 13, 1).",
					BEST3 = ".substr($horario, 14, 1).",
					CET_AM = ".substr($horario, 15, 1).",
					CET_PM = ".substr($horario, 16, 1).",
					FREE4 = ".substr($horario, 18, 1).",
					BEST4 = ".substr($horario, 19, 1).",
					PET_AM = ".substr($horario, 20, 1).",
					PET_PM = ".substr($horario, 21, 1).",
					FREE5 = ".substr($horario, 23, 1).",
					BEST5 = ".substr($horario, 24, 1).",
					SUB_AM = ".substr($horario, 25, 1).",
					SUB_PM = ".substr($horario, 26, 1).",
					FREE6 = ".substr($horario, 28, 1).",
					BEST6 = ".substr($horario, 29, 1).",
					NED_AM = ".substr($horario, 30, 1).",
					NED_PM = ".substr($horario, 31, 1).",
					FREE7 = ".substr($horario, 33, 1).",
					BEST7 = ".substr($horario, 34, 1).",
					COMENT1 = '$lunesComentarios',
					COMENT2 = '$martesComentarios',
					COMENT3 = '$miercolesComentarios',
					COMENT4 = '$juevesComentarios',
					COMENT5 = '$viernesComentarios',
					COMENT6 = '$sabadoComentarios',
					COMENT7 = '$domingoComentarios',
					SYNC = 0 
					where PWTIM_SNR = '".$arrHorario['PWTIM_SNR']."'";
			}else{
				//echo "horario: ".$horario;
				if($horario != '00000000000000000000000000000000000'){
					$queryHorario = "insert into persworktime ( PWTIM_SNR,
					PERS_SNR,
					PWADR_SNR,
					PON_AM,
					PON_PM,
					FREE1,
					BEST1,
					UT_AM,
					UT_PM,
					FREE2,
					BEST2,
					SR_AM,
					SR_PM,
					FREE3,
					BEST3,
					CET_AM,
					CET_PM,
					FREE4,
					BEST4,
					PET_AM,
					PET_PM,
					FREE5,
					BEST5,
					SUB_AM,
					SUB_PM,
					FREE6,
					BEST6,
					NED_AM,
					NED_PM,
					FREE7,
					BEST7,
					REC_STAT,
					COMENT1,
					COMENT2,
					COMENT3,
					COMENT4,
					COMENT5,
					COMENT6,
					COMENT7,
					SYNC )
					values(NEWID(),
					'$idPersona',
					'$idPwork',
					".substr($horario, 0, 1).",
					".substr($horario, 1, 1).",
					".substr($horario, 3, 1).",
					".substr($horario, 4, 1).",
					".substr($horario, 5, 1).",
					".substr($horario, 6, 1).",
					".substr($horario, 8, 1).",
					".substr($horario, 9, 1).",
					".substr($horario, 10, 1).",
					".substr($horario, 11, 1).",
					".substr($horario, 13, 1).",
					".substr($horario, 14, 1).",
					".substr($horario, 15, 1).",
					".substr($horario, 16, 1).",
					".substr($horario, 18, 1).",
					".substr($horario, 19, 1).",
					".substr($horario, 20, 1).",
					".substr($horario, 21, 1).",
					".substr($horario, 23, 1).",
					".substr($horario, 24, 1).",
					".substr($horario, 25, 1).",
					".substr($horario, 26, 1).",
					".substr($horario, 28, 1).",
					".substr($horario, 29, 1).",
					".substr($horario, 30, 1).",
					".substr($horario, 31, 1).",
					".substr($horario, 33, 1).",
					".substr($horario, 34, 1).",
					0,
					'$lunesComentarios',
					'$martesComentarios',
					'$miercolesComentarios',
					'$juevesComentarios',
					'$viernesComentarios',
					'$sabadoComentarios',
					'$domingoComentarios',
					0
					)";
				//echo $queryHorario;
					if(! sqlsrv_query($conn, $queryHorario)){
						echo "<script>alert('Problemas query horario');</script>";
					}
				}
			}*/
			
		}else{
			
			$rsIdPers = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idPers from PERSON where PERS_SNR = '00000000-0000-0000-0000-000000000000'"));
			$idPersona = $rsIdPers['idPers'];
			$rsPwork = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idPwork from PERSON_APPROVAL where PERS_APPROVAL_SNR = '00000000-0000-0000-0000-000000000000'"));
			$idPwork = $rsPwork['idPwork'];
			$tipoMovimiento = "N";
			
			//echo "tipoUsuario: ".$tipoUsuario;
			if($tipoUsuario == 4){//repre
				$insertaApproval = 1;
			}else{
				$tipoUsuario = 0;
			}
			
			$Arrpasatiempo = explode(",", $pasatiempo);
			//print_r($Arrpasatiempo);
			for($i=0;$i<count($Arrpasatiempo);$i++){
				if($Arrpasatiempo[$i] != ''){
					$query = "insert into PERSON_BANK values(NEWID(), '".$idPersona."','".$Arrpasatiempo[$i]."',0, 0)";
					if(! sqlsrv_query($conn, $query)){
						//echo $query."<br>";
						echo "<script>alert('error PERSON_BANK');</script>";
					}else{
						
					}
				}
			}
			
			/*if($horario != '00000000000000000000000000000000000'){
				$queryHorario = "insert into persworktime ( PWTIM_SNR,
					PERS_SNR,
					PWADR_SNR,
					PON_AM,
					PON_PM,
					FREE1,
					BEST1,
					UT_AM,
					UT_PM,
					FREE2,
					BEST2,
					SR_AM,
					SR_PM,
					FREE3,
					BEST3,
					CET_AM,
					CET_PM,
					FREE4,
					BEST4,
					PET_AM,
					PET_PM,
					FREE5,
					BEST5,
					SUB_AM,
					SUB_PM,
					FREE6,
					BEST6,
					NED_AM,
					NED_PM,
					FREE7,
					BEST7,
					REC_STAT,
					COMENT1,
					COMENT2,
					COMENT3,
					COMENT4,
					COMENT5,
					COMENT6,
					COMENT7,
					SYNC )
					values(NEWID(),
					'$idPersona',
					'$idPwork',
					".substr($horario, 0, 1).",
					".substr($horario, 1, 1).",
					".substr($horario, 3, 1).",
					".substr($horario, 4, 1).",
					".substr($horario, 5, 1).",
					".substr($horario, 6, 1).",
					".substr($horario, 8, 1).",
					".substr($horario, 9, 1).",
					".substr($horario, 10, 1).",
					".substr($horario, 11, 1).",
					".substr($horario, 13, 1).",
					".substr($horario, 14, 1).",
					".substr($horario, 15, 1).",
					".substr($horario, 16, 1).",
					".substr($horario, 18, 1).",
					".substr($horario, 19, 1).",
					".substr($horario, 20, 1).",
					".substr($horario, 21, 1).",
					".substr($horario, 23, 1).",
					".substr($horario, 24, 1).",
					".substr($horario, 25, 1).",
					".substr($horario, 26, 1).",
					".substr($horario, 28, 1).",
					".substr($horario, 29, 1).",
					".substr($horario, 30, 1).",
					".substr($horario, 31, 1).",
					".substr($horario, 33, 1).",
					".substr($horario, 34, 1).",
					0,
					'$lunesComentarios',
					'$martesComentarios',
					'$miercolesComentarios',
					'$juevesComentarios',
					'$viernesComentarios',
					'$sabadoComentarios',
					'$domingoComentarios',
					0
					)";
				
				if(! sqlsrv_query($conn, $queryHorario)){
					echo "<script>alert('problemas query horario');</script>";
				}
			}*/
			
			$qPersonUD = "insert into PERSON_UD( 
				PERS_SNR,
				Prod1What_SNR,
				Prod1W,
				Prod1H,
				Prod1A,
				Prod1T,
				Prod2What_SNR,
				Prod2W,
				Prod2H,
				Prod2A,
				Prod2T,
				Prod3What_SNR,
				Prod3W,
				Prod3H,
				Prod3A,
				Prod3T,
				Prod4What_SNR,
				Prod4W,
				Prod4H,
				Prod4A,
				Prod4T,
				Prod5What_SNR,
				Prod5W,
				Prod5H,
				Prod5A,
				Prod5T,
				field_01_SNR,
				field_02_SNR,
				field_03_SNR,
				field_04_SNR,
				field_05_SNR,
				field_06_SNR,
				field_07_SNR,
				field_08_SNR,
				field_09_SNR,
				field_10_SNR,
				field_11_SNR,
				field_12_SNR,
				field_13_SNR,
				field_14_SNR,
				field_15_SNR,
				field_16_SNR,
				field_17_SNR,
				field_18_SNR,
				field_19_SNR,
				REC_STAT,
				SYNC,
				CREATION_TIMESTAMP,
				CHANGED_TIMESTAMP,
				SYNC_TIMESTAMP ) values (
				'".$idPersona."', 
				'".$prod1what."', 
				'".$prod1w."', 
				'".$prod1h."', 
				'".$prod1a."', 
				'".$prod1t."', 
				'".$prod2what."', 
				'".$prod2w."', 
				'".$prod2h."', 
				'".$prod2a."', 
				'".$prod2t."', 
				'".$prod3what."', 
				'".$prod3w."', 
				'".$prod3h."', 
				'".$prod3a."', 
				'".$prod3t."', 
				'".$prod4what."', 
				'".$prod4w."', 
				'".$prod4h."', 
				'".$prod4a."', 
				'".$prod4t."', 
				'".$prod5what."', 
				'".$prod5w."', 
				'".$prod5h."', 
				'".$prod5a."', 
				'".$prod5t."',  
				'".$divmedico."', 
				'".$lider."', 
				'".$paraestatales."', 
				'".$segmentacionflonorm."', 
				'".$segmentacionvessel."', 
				'".$segmentacionzirfos."', 
				'".$segmentacionateka."', 
				'".$segmentacionganar."', 
				'".$segmentaciondesarrollar."', 
				'".$segmentaciondefender."', 
				'".$segmentacionevaluar."',
				'".$field_12_SNR."', 
				'".$field_13_SNR."',
				'".$field_14_SNR."', 
				'".$field_15_SNR."',
				'".$field_16_SNR."', 
				'".$field_17_SNR."',
				'".$field_18_SNR."',
				'".$field_18_SNR."',
				0,
				0, 
				getdate(), 
				null, 
				null) ";
				
				if(sqlsrv_query($conn, $qPersonUD) === false){
					$error = print_r( sqlsrv_errors(), true);
					echo "<script>alert('".$error."');</script>";
				}
		}
		
		if($idPworkNuevo != ''){
			$idPwork = $idPworkNuevo;
		}
		
		if($fecha != 'null'){
			$fecha = "'".$fecha."'";
		}
		
		//echo "ia: ".$insertaApproval."<br>";
		
		if($insertaApproval == 1){
			$queryPersonApproval = "insert into PERSON_APPROVAL (
				PERS_APPROVAL_SNR,
				REC_STAT,
				P_PERS_SNR,
				P_FNAME,
				P_LNAME,
				P_SEX_SNR,
				P_INFO_SHORTTIME,
				P_SPEC_SNR,
				P_SUBSPEC_SNR,
				P_BIRTHDATE,
				P_STATUS_SNR,
				CREATION_TIMESTAMP,
				P_MOTHERS_LNAME,
				P_INFO,
				P_INFO_LONGTIME,
				P_prof_id,
				SYNC,
				P_movement_type,
				PLW_PWORK_SNR,
				PLW_INST_SNR,
				P_NR,
				PLW_DEL_REASON,
				PLW_DEL_STATUS_SNR,
				P_frecvis_snr,
				P_category_snr,
				PLW_NUM_INT,
				p_tel1,
				p_email1,
				p_tel2,
				p_email2,
				p_mobile,
				plw_tower,
				plw_floor,
				plw_office,
				plw_department,
				p_diffvis_snr,
				P_patperweek_snr, 
				P_FEE_TYPE_SNR,
				P_PERSTYPE_SNR
			) values(
				'$idPersApproval',
				'0',
				'$idPersona',
				'$nombre',
				'$paterno',
				'$sexo',
				'$corto',
				'$especialidad',
				'$subespecialidad',
				$fecha,
				'$estatusPersona',
				getdate(),
				'$materno',
				'$generales',
				'$largo',
				'$cedula',
				0,
				'$tipoMovimiento',
				'$idPwork',
				'$idInst',
				'$nr',
				NULL,
				'00000000-0000-0000-0000-000000000000',
				'$frecuencia',
				'$categoria',
				'$numInterior',
				'$telPersonal',
				'$mailPersonal',
				'$telPersonal2',
				'$mailPersonal2',
				'$celular',
				'$torre',
				'$piso',
				'$consultorio',
				'$departamento',
				'$dificultadVisita',
				'$pacientesSemana',
				'$honorarios',
				'$tipoPersona'
			)";
		
			$queryApprovalStatus = "insert into APPROVAL_STATUS (
				APPROVAL_STATUS_SNR,
				TABLE_NR,
				RECORD_KEY,
				DATE_CHANGE,
				CHANGE_USER_SNR,
				APPROVED_DATE,
				APPROVED_USER_SNR,
				APPROVED_STATUS,
				REC_STAT,
				PERS_APPROVAL_SNR,
				PWORK_SNR,
				INST_APPROVAL_SNR,
				REJECT_REASON_SNR,
				RECORD_KEY_OLD,
				PWORK_OLD_SNR,
				SYNC,
				MOVEMENT_TYPE
				)
				values (
				'$idApprovalStatus',
				456,
				'$idPersona',
				getdate(),
				'$idUsuario',
				NULL,
				NULL,
				1,
				0,
				'$idPersApproval',
				'$idInst',
				'00000000-0000-0000-0000-000000000000',
				'00000000-0000-0000-0000-000000000000',
				'00000000-0000-0000-0000-000000000000',
				'00000000-0000-0000-0000-000000000000',
				0,
				0
				)";
			
			if(!sqlsrv_query($conn, $queryPersonApproval) || !sqlsrv_query($conn, $queryApprovalStatus)){
				echo "<script>alertErrorAgregarMedico();</script>";
				echo "<br><br>".$queryPersonApproval."<br><br>";
			}
			//echo $queryPersonApproval."<br><br>".$queryApprovalStatus;
		}else{
			//echo "ip: ".$tipoMovimiento;
			if($tipoMovimiento == 'N'){
				$rutaAsignada = $_POST['rutaAsignada'];
				$idPersona = sqlsrv_fetch_array(sqlsrv_query($conn, "select newid() as idPersona from person where PERS_SNR = '00000000-0000-0000-0000-000000000000'"))['idPersona'];
				$queryPerson = "insert into person ( 
					PERS_SNR, 
					PERSTYPE_SNR, 
					FNAME,
					LNAME,
					SEX_SNR,
					INFO_SHORTTIME,
					INFO_LONGTIME,
					INFO,
					SPEC_SNR,
					BIRTHDATE,
					mothers_lname,
					prof_id,
					subSpec_snr,
					SYNC,
					frecvis_snr,
					category_snr,
					PATPERWEEK_SNR,
					EMAIL1,
					EMAIL2,
					TEL1,
					TEL2,
					MOBILE,
					fee_type_snr,
					diffvis_snr,
					STATUS_SNR,
					CREATION_TIMESTAMP,
					REC_STAT) 
					values ('$idPersona', 
					'$tipoPersona',
					'$nombre',
					'$paterno',
					'$sexo',
					'$corto',
					'$largo',
					'$generales',
					'$especialidad',
					$fecha,
					'$materno',
					'$cedula',
					'$subespecialidad',
					0,
					'$frecuencia',
					'$categoria',
					'$pacientesSemana',
					'".strtolower($mailPersonal)."',
					'".strtolower($mailPersonal2)."',
					'$telPersonal',
					'$telPersonal2',
					'$celular',
					'$honorarios',
					'$dificultadVisita',
					'$estatusPersona',
					GETDATE(),
					0
					)";
					
				if(! sqlsrv_query($conn, $queryPerson)){
					echo "queryPerson: ".$queryPerson."<br>";
				}
				
				$queryPLW = "insert into PERSLOCWORK( 
					PWORK_SNR, 
					PERS_SNR,	
					INST_SNR,	
					NUM_INT,
					SYNC,
					REC_STAT,
					OFFICE,
					FLOOR,
					TOWER,
					DEPARTMENT,
					TEL,
					EMAIL
					) values ( 
					'$idPwork',
					'$idPersona',
					'$idInst',
					'$numInterior',
					0,
					0,
					'$consultorio',
					'$piso',
					'$torre',
					'$departamento',
					'$telefono',
					'$email'
					) ";
					
				$queryPSW = "insert into PERS_SREP_WORK (
						PERSREP_SNR,
						PWORK_SNR,
						USER_SNR,
						PERS_SNR,
						INST_SNR,
						SYNC,
						REC_STAT
					) values (
						NEWID(),
						'$idPwork',
						'$rutaAsignada',
						'$idPersona',
						'$idInst',
						0,
						0
					) ";
				$queryUT = "select * from user_territ where user_snr = '".$rutaAsignada."' and INST_snr = '".$idInst."'";
				$rsUT = sqlsrv_query($conn, $queryUT, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
				$actualizaUT = "";
				if(sqlsrv_num_rows($rsUT) > 0){
					$idUT = sqlsrv_fetch_array($rsUT)['UTER_SNR'];
					if($rsUT['REC_STAT'] != 0){
						$actualizaUT = "update user_territ SET REC_STAT = 0 WHERE UTER_SNR = '$idUT'";
					}
				}else{
					$actualizaUT = "insert into user_territ (
							UTER_SNR,
							INST_SNR,
							USER_SNR,
							REC_STAT,
							SYNC
						) values (
							NEWID(),
							'$idInst',
							'$rutaAsignada',
							0,
							0
						)";
				}
				
				$queryValidaExistPlw = "select pwork_snr from perslocwork where pwork_snr = '$idPwork' ";
				$rsExistPlw = sqlsrv_query($conn, $queryValidaExistPlw, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
				if(sqlsrv_num_rows($rsExistPlw) == 0 ){//es un plw nuevo
					if (! sqlsrv_query($conn, $queryPLW)) {
						echo "Error: queryPLW ::: ".$queryPLW."<br>";
					} 
				} else {
					$queryUpdatePlw = "update perslocwork set rec_stat=0, sync=0 where pwork_snr='$idPwork' "; 
					if(! sqlsrv_query($conn, $queryUpdatePlw)){
						echo "Error: queryUpdatePlw ::: ".$queryUpdatePlw."<br><br>";
					}
				}
				
				
				if(! sqlsrv_query($conn, $queryPSW)){
					echo "Error: queryPSW ::: ".$queryPSW."<br>";
				}
				if($actualizaUT != ""){
					if(! sqlsrv_query($conn, $actualizaUT)){
						echo "Error: actualizaUT ::: ".$actualizaUT."<br><br>";
					}
					
				}
			}
		}
		
		echo "<script>
				$('#divPersona').hide();
				$('#divCapa3').hide();
				notificationPersonaRegistro();
				$('#' + idmedico).click();
				$('#' + idTrMedico).addClass('div-slt-lista');
			</script>";
		//echo "<script>$('#btnGuardarPersonaNuevo').prop('disabled', false);";
	}
	}
	}else{
		
		
	function cambioDomicilio($ruta, $idPersona, $idInst, $existe, $conn, $email, $telefono,$torre,$piso,$consultorio,$departamento){
	//echo $tipoTrabajo." ,,, ".$puesto."<br>";
		//inhabilitamos pers_srep_work
		$qActualizaPSW = "update PERS_SREP_WORK set sync = 0, rec_stat = 2 
			where USER_SNR = '".$ruta."' and PERS_SNR = '".$idPersona."' and rec_stat = 0";
		if(! sqlsrv_query($conn, $qActualizaPSW)){
			echo "qActualizaPSW: ".$qActualizaPSW."<br>";
		}
		
		if($existe){
			///habilitamos
			$qActualizaPSWNuevo = "update PERS_SREP_WORK set sync = 0, rec_stat = 0 
				where USER_SNR = '".$ruta."' 
				and PERS_SNR = '".$idPersona."' 
				and INST_SNR = '".$idInst."'";
				
			$pwork = sqlsrv_fetch_array(sqlsrv_query($conn, "select pwork_snr from PERS_SREP_WORK where USER_SNR = '".$ruta."' and PERS_SNR = '".$idPersona."' and INST_SNR = '".$idInst."'"))['pwork_snr'];
			
		}else{
			//existe PWORK
			$qPLW = "select * from PERSLOCWORK 
				where PERS_SNR = '".$idPersona."' 
				and INST_SNR = '".$idInst."' ";
			$rsPLW = sqlsrv_query($conn, $qPLW, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if(sqlsrv_num_rows($rsPLW) > 0){//existe
				$regPLW = sqlsrv_fetch_array($rsPLW);
				$pwork = $regPLW['PWORK_SNR'];	
			}else{
				$pwork = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idPlw from PERSLOCWORK where PWORK_SNR <> '00000000-0000-0000-0000-000000000000'"))['idPlw'];
				$qInsertaPLW = "insert into PERSLOCWORK ( 
					PWORK_SNR,
					PERS_SNR,
					INST_SNR) values (
					'$pwork',
					'$idPersona',
					'$idInst') ";
				if(! sqlsrv_query($conn, $qInsertaPLW)){
					echo "qInsertaPLW: ".$qInsertaPLW."<br>";
				}
			}
			
			$qInsertaPSW = "insert into PERS_SREP_WORK ( 
				PERSREP_SNR,
				PWORK_SNR,
				USER_SNR,
				PERS_SNR,
				INST_SNR,
				REC_STAT,
				SYNC ) values ( 
				NEWID(),
				'$pwork',
				'$ruta',
				'$idPersona',
				'$idInst',
				0,
				0) ";
				
			if(! sqlsrv_query($conn, $qInsertaPSW)){
				echo "qInsertaPSW: ".$qInsertaPSW."<br>";
			}
				
			///revisa user_territ
			$qUserTerrit = "select * from USER_TERRIT where INST_SNR = '".$idInst."' and USER_SNR = '".$ruta."' ";
			$rsUserTerrit = sqlsrv_query($conn, $qUserTerrit, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if(sqlsrv_num_rows($rsUserTerrit) > 0){
				$regUserTerrit = sqlsrv_query($conn, $rsUserTerrit);
				if($regUserTerrit['REC_STAT'] != 0){//se activa
					$actualizaUT = "update USER_TERRIT set REC_STAT = 0, sync = 0 where UTER_SNR = '".$regUserTerrit['UTER_SNR']."' ";
					if(! sqlsrv_query($conn, $actualizaUT)){
						echo "actualizaUT: ".$actualizaUT."<br>";
					}
				}
			}else{// lo ingresamos
				$insertaUT = "insert into USER_TERRIT ( 
					UTER_SNR,
					INST_SNR,
					USER_SNR,
					REC_STAT,
					SYNC ) values ( 
					NEWID(),
					'$idInst',
					'$ruta',
					0,
					0 )";
				if(! sqlsrv_query($conn, $insertaUT)){
					echo "insertaUT: ".$insertaUT."<br>";
				}
			}
		}
		$tipoTrabajo = (!isset($tipoTrabajo) || $tipoTrabajo == '') ? '00000000-0000-0000-0000-000000000000' : $tipoTrabajo;
		$puesto = (!isset($puesto) || $puesto == '') ? '00000000-0000-0000-0000-000000000000' : $puesto;
		//actualizamos PLW
		$qActualizaPLWDatos = "update PERSLOCWORK set 
			rec_stat = 0, 
			sync = 0, 
			EMAIL = '$email',
			TEL = '$telefono',
			TOWER = '$torre',
			FLOOR = '$piso',
			OFFICE = '$consultorio',
			DEPARTMENT = '$departamento' 
			where PWORK_SNR = '".$pwork."'";

		if(! sqlsrv_query($conn, $qActualizaPLWDatos)){
			echo "error al actualizar PERSLOCWORK: ".$qActualizaPLWDatos."<br>";
		} 
		
		//echo "pwork: ".$pwork."<br>";
		
		return $pwork;
	}//termina cambio Domicilio
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		
		/*echo $_POST['fecha'];
		echo "<br><br>";
		echo (strpos($_POST['fecha'], 'null') !== false) ? 'encontrado' : 'no encontrado';*/
		$idPersona = $_POST['idPersona'];
		$tipoPersona = ($_POST['tipoPersona'] == '') ? '00000000-0000-0000-0000-000000000000' : $_POST['tipoPersona'];
		$nombre = strtoupper($_POST['nombre']);
		$paterno = strtoupper($_POST['paterno']);
		$materno = strtoupper($_POST['materno']);
		$sexo = ($_POST['sexo'] == '') ? '00000000-0000-0000-0000-000000000000' : $_POST['sexo'];
		$especialidad = $_POST['especialidad'];
		$subespecialidad = ($_POST['subespecialidad'] == '') ? '00000000-0000-0000-0000-000000000000' : $_POST['subespecialidad'];
		$pacientesSemana = ($_POST['pacientesSemana'] == '') ? '00000000-0000-0000-0000-000000000000' : $_POST['pacientesSemana'];
		$honorarios = ($_POST['honorarios'] == '') ? '00000000-0000-0000-0000-000000000000' : $_POST['honorarios'];
		$fecha = (strpos($_POST['fecha'], 'null') !== false) ? 'null' : $_POST['fecha'];
		$categoria = ($_POST['categoria'] == '') ? 'FF4436E8-8FF9-48D4-853F-67CF43A01DDB' : $_POST['categoria'];
		//$categoria = $_POST['categoria'];
		$cedula = $_POST['cedula'];
		$dificultadVisita = $_POST['dificultadVisita'];
		//$botiquin = $_POST['botiquin'];
		/*$iguala = strtoupper($_POST['iguala']);*/
		$idInst = $_POST['idInst'];
		$telefono = $_POST['telefono'];//PLW
		//$telefono1 = $_POST['telefono1'];//PLW
		$email = strtolower($_POST['email']);//PLW
		$corto = strtoupper($_POST['corto']);
		$largo = strtoupper($_POST['largo']);
		$generales = strtoupper($_POST['generales']);
		$idUsuario = $_POST['idUsuario'];
		$numInterior = $_POST['interior'];
		$pasatiempo = $_POST['pasatiempo'];
		/*$horario = $_POST['horario'];
		$lunesComentarios = strtoupper($_POST['lunesComentarios']);
		$martesComentarios = strtoupper($_POST['martesComentarios']);
		$miercolesComentarios = strtoupper($_POST['miercolesComentarios']);
		$juevesComentarios = strtoupper($_POST['juevesComentarios']);
		$viernesComentarios = strtoupper($_POST['viernesComentarios']);
		$sabadoComentarios = strtoupper($_POST['sabadoComentarios']);
		$domingoComentarios = strtoupper($_POST['domingoComentarios']);*/
		/*$abierto1 = strtoupper($_POST['abierto1']);
		$abierto2 = strtoupper($_POST['abierto2']);
		$abierto3 = strtoupper($_POST['abierto3']);*/
		$torre = strtoupper($_POST['torre']);
		$piso = strtoupper($_POST['piso']);
		$consultorio = strtoupper($_POST['consultorio']);
		$departamento = strtoupper($_POST['departamento']);
		//$tipoTrabajo = $_POST['tipoTrabajo'];
		//$puesto = $_POST['puesto'];
		$telPersonal = $_POST['telPersonal'];
		$mailPersonal = $_POST['mailPersonal'];
		$telPersonal2 = $_POST['telPersonal2'];
		$mailPersonal2 = $_POST['mailPersonal2'];
		$celular = $_POST['celular'];
		
		$estatusPersona = $_POST['estatusPersona'];
		//$field_02_SNR = $_POST['field_02_SNR'];
		$prod1what = $_POST['prod1what']; 
		$prod1w = $_POST['prod1w']; 
		$prod1h = $_POST['prod1h']; 
		$prod1a = $_POST['prod1a']; 
		$prod1t = $_POST['prod1t']; 
		$prod2what = $_POST['prod2what']; 
		$prod2w = $_POST['prod2w']; 
		$prod2h = $_POST['prod2h']; 
		$prod2a = $_POST['prod2a']; 
		$prod2t = $_POST['prod2t']; 
		$prod3what = $_POST['prod3what']; 
		$prod3w = $_POST['prod3w']; 
		$prod3h = $_POST['prod3h']; 
		$prod3a = $_POST['prod3a']; 
		$prod3t = $_POST['prod3t']; 
		$prod4what = $_POST['prod4what']; 
		$prod4w = $_POST['prod4w']; 
		$prod4h = $_POST['prod4h']; 
		$prod4a = $_POST['prod4a']; 
		$prod4t = $_POST['prod4t']; 
		$prod5what = $_POST['prod5what']; 
		$prod5w = $_POST['prod5w']; 
		$prod5h = $_POST['prod5h']; 
		$prod5a = $_POST['prod5a']; 
		$prod5t = $_POST['prod5t'];  
		$divmedico = $_POST['divmedico']; 
		$lider = $_POST['lider']; 
		$paraestatales = $_POST['paraestatales']; 
		$segmentacionflonorm = $_POST['segmentacionflonorm']; 
		$segmentacionvessel = $_POST['segmentacionvessel']; 
		$segmentacionzirfos = $_POST['segmentacionzirfos'];
		$segmentacionateka = $_POST['segmentacionateka'];		
		$segmentacionganar = $_POST['segmentacionganar']; 
		$segmentaciondesarrollar = $_POST['segmentaciondesarrollar']; 
		$segmentaciondefender = $_POST['segmentaciondefender']; 
		$segmentacionevaluar = $_POST['segmentacionevaluar']; 
		
		$field_12_SNR = $_POST['field_12'];
		$field_13_SNR = $_POST['field_13'];
		$field_14_SNR = $_POST['field_14'];
		$field_15_SNR = $_POST['field_15'];
		$field_16_SNR = $_POST['field_16'];
		$field_17_SNR = $_POST['field_17'];
		$field_18_SNR = $_POST['field_18'];
		
		//echo $puesto." -- ".$tipoTrabajo."<br>";
		
		$tipoUsuario = $_POST['tipoUsuario'];
		$ruta = $_POST['ruta'];
		
		$idPworkNuevo = '';
		
		$actualizaPerson = 0;
		$insertaApproval = 0;
		
		$cicloActivo = $_POST['cicloActivo'];
		
		/* nuevo calculo de frecuencia */
		$qfrecuencia = "select frecvis_snr 
			from cycle_pers_categ_spec 
			where rec_stat = 0 
			and cycle_snr = (select cycle_snr from cycles where name = '".$cicloActivo."' and rec_stat = 0)
			and spec_snr = '".$especialidad."' 
			and category_snr = '".$categoria."' ";
			
		$frecuencia = sqlsrv_fetch_array(sqlsrv_query($conn, $qfrecuencia))['frecvis_snr'];
		
		if(empty($frecuencia)){
			$frecuencia = '00000000-0000-0000-0000-000000000000';
		}
		
		/* checa si no existe el registro */
		/*if($idPersona != ''){
			$queryValida = "select * 
				from PERSON_APPROVAL pa, APPROVAL_STATUS at 
				where pa.P_PERS_SNR = '".$idPersona."' 
				and P_APPROVAL_STATUS = 'C'
				and at.CHANGE_USER_SNR = '".$idUsuario."'
				and at.APPROVED_STATUS = 1 
				and pa.PERS_APPROVAL_SNR = at.PERS_APPROVAL_SNR";
			echo $queryValida;
			$rsValida = sqlsrv_query($conn, $queryValida , array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if(sqlsrv_num_rows($rsValida) > 0){
				echo "<script>alert('Ya existe un movivmiento para ese médico, debe esperar a que sea liberado!!!');</script>";
				return;
			}
		}*/
		/*********************************/
		
		$rsIdPersonApproval = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idPersApproval from PERSON_APPROVAL where PERS_APPROVAL_SNR = '00000000-0000-0000-0000-000000000000'"));
		$idPersApproval = $rsIdPersonApproval['idPersApproval'];
		$rsNR = sqlsrv_fetch_array(sqlsrv_query($conn, "select max(P_NR)+1 as nr from PERSON_APPROVAL"));
		$nr = $rsNR['nr'];
		$rsIdApprovalSatus = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idApprovalStatus from APPROVAL_STATUS where PERS_APPROVAL_SNR = '00000000-0000-0000-0000-000000000000'"));
		$idApprovalStatus = $rsIdApprovalSatus['idApprovalStatus'];
		
		//echo "idPersona: ".$idPersona;
		if($idPersona != ''){
			$tipoMovimiento = "C";

			/* guarda el historial de cambios */

			$qInsertaHistorial = "INSERT INTO PERSON_HISTORY
				SELECT * FROM PERSON WHERE PERS_SNR = '$idPersona'";

			if(! sqlsrv_query($conn, $qInsertaHistorial)){
				echo "No se guardo el historial: ".$qInsertaHistorial;
			}

			//echo "tipoUsuario: ".$tipoUsuario."<br>";
			
			/*grabo approval changes */
			/*$queryCampos = "select rc.rtab_snr, rc.rcol_snr, upper(rc.name) as name
			from rep_tables rt, rep_columns rc
			where rt.name = 'person'
			and rt.RTAB_SNR = rc.RTAB_SNR ";*/
			if($tipoUsuario == 4){
				/* revisamos si hay cambio de dirección */
				//$rsPwork = sqlsrv_fetch_array(sqlsrv_query($conn, "select PWORK_SNR as idPwork from PERS_SREP_WORK where PERS_SNR = '$idPersona' and rec_stat = 0 "));
				$rsPwork = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from PERSLOCWORK where pers_snr = '$idPersona' and REC_STAT = 0"));
				$idPwork = $rsPwork['PWORK_SNR'];
				$idInstPWL = $rsPwork['INST_SNR'];
				
				if($idInstPWL != $idInst){//cambio de direccion
					$idPworkNuevo = $rsPwork = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idPwork from PERSLOCWORK where PWORK_SNR = '00000000-0000-0000-0000-000000000000'"))['idPwork'];
					$qExisteInst = "select * from inst where inst_snr = '".$idInst."' ";
					$rsExisteInst = sqlsrv_query($conn, $qExisteInst, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
					if(sqlsrv_num_rows($rsExisteInst) > 0){
						$qInst = " select i.NAME, i.STREET1, c.NAME as COLONIA, c.ZIP, d.NAME as CIUDAD, edo.NAME as ESTADO  
							from inst i, city c, DISTRICT d, state edo
							where i.INST_SNR = '".$idInst."'
							and i.CITY_SNR = c.CITY_SNR
							and c.DISTR_SNR = d.DISTR_SNR
							and c.STATE_SNR = edo.STATE_SNR ";
					}else{
						$qInst = " select i.I_NAME as NAME, i.I_STREET1 as STREET1, c.NAME as COLONIA, c.ZIP, d.NAME as CIUDAD, edo.NAME as ESTADO  
							from inst_approval i, city c, DISTRICT d, state edo
							where i.I_INST_SNR = '".$idInst."'
							and i.I_CITY_SNR = c.CITY_SNR
							and c.DISTR_SNR = d.DISTR_SNR
							and c.STATE_SNR = edo.STATE_SNR ";
					}
					
				}else{
					$qInst = " select i.NAME, i.STREET1, c.NAME as COLONIA, c.ZIP, d.NAME as CIUDAD, edo.NAME as ESTADO  
							from inst i, city c, DISTRICT d, state edo
							where i.INST_SNR = '".$idInst."'
							and i.CITY_SNR = c.CITY_SNR
							and c.DISTR_SNR = d.DISTR_SNR
							and c.STATE_SNR = edo.STATE_SNR ";
				}
				$rsInst = sqlsrv_fetch_array(sqlsrv_query($conn, $qInst));
				$inst = $rsInst['NAME'];
				$calle = $rsInst['STREET1'];
				$colonia = $rsInst['COLONIA'];
				$zip = $rsInst['ZIP'];
				$ciudad = $rsInst['CIUDAD'];
				$estado = $rsInst['ESTADO'];
				
				$queryCampos = "select rc.TABLE_NR, rc.COLUMN_NR, upper(rc.name) as name, rc.approval
					from CONFIG_TABLE rt, CONFIG_FIELD rc
					where rt.name = 'person'
					and rt.TABLE_NR = rc.TABLE_NR
					union
					select rc.TABLE_NR, rc.COLUMN_NR,   
					case when upper(rc.name) = 'TEL2' then 'TEL2_INST' else upper(rc.name) end as name,
					rc.approval
					from CONFIG_TABLE rt, CONFIG_FIELD rc
					where rt.name = 'inst'
					and rt.TABLE_NR = rc.TABLE_NR
					union
					select rc.TABLE_NR, rc.COLUMN_NR, 
					case when upper(rc.name) = 'NAME' then 'COLONIA' else upper(rc.NAME) end as name, rc.approval
					from CONFIG_TABLE rt, CONFIG_FIELD rc
					where rt.name = 'city'
					and rt.TABLE_NR = rc.TABLE_NR
					and rc.name in ('NAME','ZIP')
					union
					select rc.TABLE_NR, rc.COLUMN_NR, 
					case when upper(rc.name) = 'NAME' then 'CIUDAD' else upper(rc.NAME) end as name, rc.approval
					from CONFIG_TABLE rt, CONFIG_FIELD rc
					where rt.name = 'district'
					and rt.TABLE_NR = rc.TABLE_NR
					and rc.name = 'NAME'
					UNION
					select rc.TABLE_NR, rc.COLUMN_NR, 
					case when upper(rc.name) = 'NAME' then 'ESTADO' else upper(rc.NAME) end as name, rc.approval
					from CONFIG_TABLE rt, CONFIG_FIELD rc
					where rt.name = 'state'
					and rt.TABLE_NR = rc.TABLE_NR
					and rc.name = 'NAME' 
					union 
					select rc.TABLE_NR, rc.COLUMN_NR, upper(rc.name) as name, rc.approval
					from CONFIG_TABLE rt, CONFIG_FIELD rc
					where rt.name = 'perslocwork'
					and rt.TABLE_NR = rc.TABLE_NR";
					
				//echo $queryCampos."<br><br>";
				
				$rsCampos = sqlsrv_query($conn, $queryCampos);
				
				$rcol = array();
				$campos = array();
				$rtab = array();
				$approval = array();
				
				while($campo = sqlsrv_fetch_array($rsCampos)){
					$rtab[] = $campo['TABLE_NR'];
					$rcol[] = $campo['COLUMN_NR'];
					if($campo['name'] == "EMAIL" && $campo['TABLE_NR'] == 14){
						$campos[] = "EMAIL_P";
					}else{
						$campos[] = $campo['name'];
					}
					$approval[] = $campo['approval'];
				}
				/*print_r($rtab);
				echo "<br>";
				print_r($rcol);
				echo "<br>";
				print_r ($approval);
				echo "<br>";
				print_r ($campos);
				echo "<br>";*/
				
				//$queryAnt = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from person where PERS_SNR = '$idPersona'"));
				$qAnt = "select p.*, i.NAME, i.STREET1, c.ZIP, c.name as COLONIA, 
					d.NAME as CIUDAD, edo.NAME as ESTADO, 
					plw.*,
					plw.tel as TEL_PLW,
					plw.email as EMAIL_PLW,
					p.info as info_person
					from person p, PERS_SREP_WORK psw, inst i, city c, DISTRICT d, state edo, PERSLOCWORK plw
					where p.PERS_SNR = psw.PERS_SNR
					and i.INST_SNR = psw.INST_SNR
					and i.CITY_SNR = c.CITY_SNR
					and c.DISTR_SNR = d.DISTR_SNR
					and c.STATE_SNR = edo.STATE_SNR
					and plw.PERS_SNR = p.PERS_SNR
					and i.INST_SNR = plw.INST_SNR
					and psw.rec_stat = 0 
					and plw.rec_stat = 0
					and p.pers_snr = '".$idPersona."' ";
				//echo $qAnt."<br><br>";
				$queryAnt = sqlsrv_fetch_array(sqlsrv_query($conn, $qAnt));
				//echo "select * from person where PERS_SNR = '$idPersona'<br>";
				$queryPersonApproval = "update PERSON set SYNC = 0 ";
				
				/*if($telefono != $queryAnt['TEL_PLW']){
					$idCampo = array_search ( "TEL_PLW" , $campos );
					$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$telefono."',0,0)";
					$insertaApproval = 1;
					if(! sqlsrv_query($conn, $insertaChange)){
						$insertaChange."<br><br>";
					}
				}
				
				if($telefono != $queryAnt['TEL_PLW']){
					$idCampo = array_search ( "TEL_PLW" , $campos );
					$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$telefono."',0,0)";
					$insertaApproval = 1;
					if(! sqlsrv_query($conn, $insertaChange)){
						$insertaChange."<br><br>";
					}
				}*/
				
				if($inst != $queryAnt['NAME']){
					$idCampo = array_search ( "NAME" , $campos );
					$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$inst."',0,0)";
					$insertaApproval = 1;
					if(! sqlsrv_query($conn, $insertaChange)){
						$insertaChange."<br><br>";
					}
				}
				
				if($calle != $queryAnt['STREET1']){
					$idCampo = array_search ( "STREET1" , $campos );
					$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$calle."',0,0)";
					$insertaApproval = 1;
					if(! sqlsrv_query($conn, $insertaChange)){
						$insertaChange."<br><br>";
					}
				}
				
				if($colonia != $queryAnt['COLONIA']){
					$idCampo = array_search ( "COLONIA" , $campos );
					$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$colonia."',0,0)";
					$insertaApproval = 1;
					if(! sqlsrv_query($conn, $insertaChange)){
						$insertaChange."<br><br>";
					}
				}
				
				if($zip != $queryAnt['ZIP']){
					$idCampo = array_search ( "ZIP" , $campos );
					$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$zip."',0,0)";
					$insertaApproval = 1;
					if(! sqlsrv_query($conn, $insertaChange)){
						$insertaChange."<br><br>";
					}
				}
				
				if($ciudad != $queryAnt['CIUDAD']){
					$idCampo = array_search ( "CIUDAD" , $campos );
					$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$ciudad."',0,0)";
					$insertaApproval = 1;
					if(! sqlsrv_query($conn, $insertaChange)){
						$insertaChange."<br><br>";
					}
				}
				
				if($estado != $queryAnt['ESTADO']){
					$idCampo = array_search ( "ESTADO" , $campos );
					$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$estado."',0,0)";
					$insertaApproval = 1;
					if(! sqlsrv_query($conn, $insertaChange)){
						$insertaChange."<br><br>";
					}
				}
				
				if($queryAnt['PERSTYPE_SNR'] != $tipoPersona){
					$idCampo = array_search ( "PERSTYPE_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$tipoPersona."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",PERSTYPE_SNR = '$tipoPersona' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['FNAME'] != $nombre){
					$idCampo = array_search ( "FNAME" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$nombre."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",FNAME = '$nombre' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['LNAME'] != $paterno){
					$idCampo = array_search ( "LNAME" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$paterno."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",LNAME = '$paterno' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['MOTHERS_LNAME'] != $materno){
					$idCampo = array_search ( "MOTHERS_LNAME" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$materno."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{	
						$queryPersonApproval .= ",mothers_lname = '$materno' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['PROF_ID'] != $cedula){
					$idCampo = array_search ( "PROF_ID" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$cedula."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",PROF_ID = '$cedula' ";
						$actualizaPerson = 1;
					}
				}

				if($queryAnt['BIRTHDATE'] != null){
					foreach ($queryAnt['BIRTHDATE'] as $key => $val) {
						if(strtolower($key) == 'date'){
							//echo $fecha." ::: ".substr($val, 0, 10)."<br>";
							if($fecha != substr($val, 0, 10)){
								$idCampo = array_search ( "BIRTHDATE" , $campos );
								if($approval[$idCampo] == 1){
									$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$fecha."',0,0)";
									$insertaApproval = 1;
									if(! sqlsrv_query($conn, $insertaChange)){
										$insertaChange."<br><br>";
									}
								}else{
									$queryPersonApproval .= ",BIRTHDATE = '$fecha' ";
									$actualizaPerson = 1;
								}
							}
						}
					}
				}else{
					if($fecha != '' && $fecha != 'null'){
						$idCampo = array_search ( "BIRTHDATE" , $campos );
						if($approval[$idCampo] == 1){
							$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$fecha."',0,0)";
							$insertaApproval = 1;
							if(! sqlsrv_query($conn, $insertaChange)){
								$insertaChange."<br><br>";
							}
						}else{
							$queryPersonApproval .= ",BIRTHDATE = '$fecha' ";
							$actualizaPerson = 1;
						}
					}
				}
				
				if($queryAnt['SPEC_SNR'] != $especialidad){
					$idCampo = array_search ( "SPEC_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$especialidad."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",SPEC_SNR = '$especialidad' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['SUBSPEC_SNR'] != $subespecialidad){
					$idCampo = array_search ( "SUBSPEC_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$subespecialidad."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",SUBSPEC_SNR = '$subespecialidad' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['PATPERWEEK_SNR'] != $pacientesSemana){
					$idCampo = array_search ( "PATPERWEEK_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$pacientesSemana."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",patperweek_snr = '$pacientesSemana' ";
						$actualizaPerson = 1;
					}
				}
				
				
				if($queryAnt['FEE_TYPE_SNR'] != $honorarios){
					$idCampo = array_search ( "FEE_TYPE_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$honorarios."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",FEE_TYPE_SNR = '$honorarios' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['FRECVIS_SNR'] != $frecuencia){
					$idCampo = array_search ( "FRECVIS_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$frecuencia."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",FRECVIS_SNR = '$frecuencia' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['SEX_SNR'] != $sexo){
					$idCampo = array_search ( "SEX_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$sexo."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",SEX_SNR = '$sexo' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['DIFFVIS_SNR'] != $dificultadVisita){
					$idCampo = array_search ( "DIFFVIS_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$dificultadVisita."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",DIFFVIS_SNR = '$dificultadVisita' ";
						$actualizaPerson = 1;
					}
				}

				
				if($queryAnt['CATEGORY_SNR'] != $categoria){
					$idCampo = array_search ( "CATEGORY_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$categoria."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",CATEGORY_SNR = '$categoria' ";
						$actualizaPerson = 1;
					}
				}

				if($queryAnt['STATUS_SNR'] != $estatusPersona){
					$idCampo = array_search ( "STATUS_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$estatusPersona."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{	
						$queryPersonApproval .= ",STATUS_SNR = '$estatusPersona' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['TEL1'] != $telPersonal){
					$idCampo = array_search ( "TEL1" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$telPersonal."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",TEL1 = '$telPersonal' ";
						$actualizaPerson = 1;
					}
				}
				//echo $queryAnt['TEL2']." != ".$telPersonal2."<br>";
				if($queryAnt['TEL2'] != $telPersonal2){
					//if(){}
					$idCampo = array_search ( "TEL2" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$telPersonal2."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",TEL2 = '$telPersonal2' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['MOBILE'] != $celular){
					$idCampo = array_search ( "MOBILE" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$celular."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",MOBILE = '$celular' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['EMAIL1'] != $mailPersonal){
					$idCampo = array_search ( "EMAIL1" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$mailPersonal."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",EMAIL1 = '$mailPersonal' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['EMAIL2'] != $mailPersonal2){
					$idCampo = array_search ( "EMAIL2" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$mailPersonal2."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",EMAIL2 = '$mailPersonal2' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['INFO_SHORTTIME'] != $corto){
					$idCampo = array_search ( "INFO_SHORTTIME" , $campos );
					//echo "INFO_SHORTTIME: ".$idCampo."<br>";
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$corto."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",INFO_SHORTTIME = '$corto' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['INFO_LONGTIME'] != $largo){
					$idCampo = array_search ( "INFO_LONGTIME" , $campos );
					//echo $idCampo."<br>";
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$largo."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",INFO_LONGTIME = '$largo' ";
						$actualizaPerson = 1;
					}
				}
				//echo "info: ".$queryAnt['INFO']." ::: ".$generales." :: ";
				if($queryAnt['INFO'] != $generales){
					$idCampo = array_search ( "INFO" , $campos );
					//echo "generales: ".$idCampo."<br>";
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$generales."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",INFO = '$generales' ";
						$actualizaPerson = 1;
					}
				}
				
				$queryPersonApproval .= "where PERS_SNR = '$idPersona'";
				
				//echo $queryPersonApproval;
				
				if($actualizaPerson == 1){
					if(! sqlsrv_query($conn, $queryPersonApproval)){
						echo "person: ".$queryPersonApproval;
					}
				}
				/*************************/
				/*actualizar perslocwork*/
				/*plw.FUNCTION_SNR as puesto, 
					plw.EMPLOYEESTAT as tipoTrabajo, plw.pwloc_snr as idPLW*/
					$actualizaplw = 0;
					$queryPLW = '';
				/*
				if($queryAnt['FUNCTION_SNR'] != $puesto){
					$idCampo = array_search ( "FUNCTION_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$puesto."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPLW .= ",FUNCTION_SNR = '$puesto' ";
						$actualizaplw = 1;
					}
				}
				
				if($queryAnt['EMPLOYEESTAT'] != $tipoTrabajo){
					$idCampo = array_search ( "EMPLOYEESTAT" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$tipoTrabajo."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPLW .= ",EMPLOYEESTAT = '$tipoTrabajo' ";
						$actualizaplw = 1;
					}
				}*/
				//echo $telefono." !=". $queryAnt['TEL_PLW'];
				if($telefono != $queryAnt['TEL_PLW']){
					$idCampo = array_search ( "TEL" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",''00000000-0000-0000-0000-000000000000'','".$telefono."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPLW .= ",TEL = '$telefono' ";
						$actualizaplw = 1;
					}
				}
				
				//echo $telefono1." ::: ".$queryAnt['FAX_PLW']."<br>";
				/*
				if($telefono1 != $queryAnt['FAX_PLW']){
					$idCampo = array_search ( "FAX_PLW" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",''00000000-0000-0000-0000-000000000000'','".$telefono1."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPLW .= ",FAX = '$telefono1' ";
						$actualizaplw = 1;
					}
				}*/
				
				if($email != $queryAnt['EMAIL_PLW']){
					$idCampo = array_search ( "EMAIL" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",''00000000-0000-0000-0000-000000000000'','".$email."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPLW .= ",EMAIL = '$email' ";
						$actualizaplw = 1;
					}
				}
				
				if($torre != $queryAnt['TOWER']){
					$idCampo = array_search ( "TOWER" , $campos );
					if($approval[$idCampo] == 1){
						print_r("rcol: ".$rcol[$idCampo]);
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",''00000000-0000-0000-0000-000000000000'','".$torre."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPLW .= ",TOWER = '$torre' ";
						$actualizaplw = 1;
					}
				}
				
				if($piso != $queryAnt['FLOOR']){
					$idCampo = array_search ( "FLOOR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",''00000000-0000-0000-0000-000000000000'','".$piso."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPLW .= ",FLOOR = '$piso' ";
						$actualizaplw = 1;
					}
				}
				
				if($consultorio != $queryAnt['OFFICE']){
					$idCampo = array_search ( "OFFICE" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",''00000000-0000-0000-0000-000000000000'','".$consultorio."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPLW .= ",OFFICE = '$consultorio' ";
						$actualizaplw = 1;
					}
				}
				
				if($departamento != $queryAnt['DEPARTMENT']){
					$idCampo = array_search ( "DEPARTMENT" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",''00000000-0000-0000-0000-000000000000'','".$departamento."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPLW .= ",DEPARTMENT = '$departamento' ";
						$actualizaplw = 1;
					}
				}
				
				if($actualizaplw){
					$updatePLW = "update PERSLOCWORK set sync = 0 ";
					$updatePLW .= $queryPLW;
					$updatePLW .= " where pwork_snr = '".$queryAnt['PWORK_SNR']."' ";
					
					if(! sqlsrv_query($conn, $updatePLW)){
						echo "uPLW: ".$updatePLW."<br><br>";
					}
					
					//echo $updatePLW;
				}
			
				
				
				
				/************/
			}else{//no es repre
				if($fecha == 'null'){
					$fecha = $fecha;
				}else{
					$fecha = "'".$fecha."'";
				}
				$queryPerson = "update person set 
				PERSTYPE_SNR = '$tipoPersona', 
				FNAME = '$nombre',
				LNAME = '$paterno',
				SEX_SNR = '$sexo',
				INFO_SHORTTIME = '$corto',
				INFO_LONGTIME = '$largo',
				INFO = '$generales',
				SPEC_SNR = '$especialidad',
				BIRTHDATE = $fecha,
				mothers_lname = '$materno',
				prof_id = '$cedula',
				subSpec_snr = '$subespecialidad',
				SYNC = 0,
				frecvis_snr = '$frecuencia',
				category_snr = '$categoria',
				PATPERWEEK_SNR = '$pacientesSemana',
				EMAIL1 = '".strtolower($mailPersonal)."',
				EMAIL2 = '".strtolower($mailPersonal2)."',
				TEL1 = '$telPersonal',
				TEL2 = '$telPersonal2',
				MOBILE = '$celular',
				fee_type_snr = '$honorarios',
				diffvis_snr = '$dificultadVisita',
				STATUS_SNR = '$estatusPersona',
				CHANGED_TIMESTAMP = GETDATE()
				where PERS_SNR = '$idPersona' ";
				
				if(! sqlsrv_query($conn, $queryPerson)){
					echo "queryPerson: ".$queryPerson."<br>";
				}
				
				/*valida dir */
				/*$rsPwork = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from PERSLOCWORK where pers_snr = '$idPersona' and REC_STAT = 0"));
				$idPwork = $rsPwork['PWLOC_SNR'];
				$idInstPWL = $rsPwork['LOC_SNR'];*/
				
				$qPwork = "select * from PERS_SREP_WORK where USER_SNR = '$ruta' and PERS_SNR = '$idPersona' and INST_SNR = '$idInst'  ";
				
				$rsPwork = sqlsrv_query($conn, $qPwork, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
				if(sqlsrv_num_rows($rsPwork) > 0){//existe
					$regPwork = sqlsrv_fetch_array($rsPwork);
					if($regPwork['REC_STAT'] != 0){///la direccion cambió
						$idPwork = cambioDomicilio($ruta, $idPersona, $idInst, 1, $conn, $email, $telefono,$torre,$piso,$consultorio,$departamento);
					}else{
						$idPwork = $regPwork['PWORK_SNR'];
						
						$qActualizaPLWDatos2 = "update PERSLOCWORK set 
						rec_stat = 0, 
						sync = 0, 
						EMAIL = '$email',
						TEL = '$telefono',
						TOWER = '$torre',
						FLOOR = '$piso',
						OFFICE = '$consultorio',
						DEPARTMENT = '$departamento' 
						where PERS_SNR = '$idPersona'  and INST_SNR = '$idInst'";

						if(! sqlsrv_query($conn, $qActualizaPLWDatos2)){
							echo "error al actualizar PERSLOCWORK: ".$qActualizaPLWDatos2."<br>";
						}
					}
				}else{//la dirección cambio
					$idPwork = cambioDomicilio($ruta, $idPersona, $idInst, 0, $conn, $email, $telefono,$torre,$piso,$consultorio,$departamento);
				} 
			}
			
			$actualizaPersonUD = "update PERSON_UD SET
				Prod1What_SNR = '".$prod1what."', 
				Prod1W = '".$prod1w."', 
				Prod1H = '".$prod1h."', 
				Prod1A = '".$prod1a."', 
				Prod1T = '".$prod1t."',  
				Prod2What_SNR = '".$prod2what."',  
				Prod2W = '".$prod2w."', 
				Prod2H = '".$prod2h."', 
				Prod2A = '".$prod2a."', 
				Prod2T = '".$prod2t."', 
				Prod3What_SNR = '".$prod3what."',  
				Prod3W = '".$prod3w."', 
				Prod3H = '".$prod3h."', 
				Prod3A = '".$prod3a."', 
				Prod3T = '".$prod3t."', 
				Prod4What_SNR = '".$prod4what."',  
				Prod4W = '".$prod4w."', 
				Prod4H = '".$prod4h."', 
				Prod4A = '".$prod4a."', 
				Prod4T = '".$prod4t."', 
				Prod5What_SNR = '".$prod5what."',  
				Prod5W = '".$prod5w."', 
				Prod5H = '".$prod5h."', 
				Prod5A = '".$prod5a."', 
				Prod5T = '".$prod5t."', 
				
				field_01_SNR = '".$divmedico."', 
				field_02_SNR = '".$lider."', 
				field_03_SNR = '".$paraestatales."',  
				field_04_SNR = '".$segmentacionflonorm."',  
				field_05_SNR = '".$segmentacionvessel."', 
				field_06_SNR = '".$segmentacionzirfos."',  
				field_07_SNR = '".$segmentacionateka."', 
				field_08_SNR = '".$segmentacionganar."',  
				field_09_SNR = '".$segmentaciondesarrollar."',  
				field_10_SNR = '".$segmentaciondefender."', 
				field_11_SNR = '".$segmentacionevaluar."', 
				field_12_SNR = '".$field_12_SNR."', 
				field_13_SNR = '".$field_13_SNR."',
				field_14_SNR = '".$field_14_SNR."', 
				field_15_SNR = '".$field_15_SNR."',
				field_16_SNR = '".$field_16_SNR."', 
				field_17_SNR = '".$field_17_SNR."',
				field_18_SNR = '".$field_18_SNR."', 				
				SYNC = 0,
				CHANGED_TIMESTAMP = getdate() 
				where PERS_SNR = '".$idPersona."' ";
				
			if(! sqlsrv_query($conn, $actualizaPersonUD)){
				echo "personUD: ".$actualizaPersonUD."<br>";
			}
			
			if($pasatiempo != ''){
				$arrpasatiempoNuevo = explode(",", $pasatiempo);
			}else{
				$arrpasatiempoNuevo = array();
			}
			$arrPasatiempoViejo = array();
			$queryPasatiempos = "select * from PERSON_BANK where pers_snr = '$idPersona'";
			$rsPasatiempos = sqlsrv_query($conn, $queryPasatiempos, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if(sqlsrv_num_rows($rsPasatiempos) > 0){
				while($pasatiempo = sqlsrv_fetch_array($rsPasatiempos)){
					$arrPasatiempoViejo[] = $pasatiempo['BANK_SNR'];
					if(in_array($pasatiempo['BANK_SNR'], $arrpasatiempoNuevo)){
						if($pasatiempo['REC_STAT'] != 0){
							if(! sqlsrv_query($conn, "update PERSON_BANK set rec_stat = 0 where pers_bank_snr = '".$pasatiempo['PERS_BANK_SNR']."'")){
								echo "<script>alert('Problemas en PERSON_BANK');</script>";
							}
						}
					}else{
						if($pasatiempo['REC_STAT'] == 0){
							if(! sqlsrv_query($conn, "update PERSON_BANK set rec_stat = 2 where pers_bank_snr = '".$pasatiempo['PERS_BANK_SNR']."'")){
								echo "<script>alert('Problemas en PERSON_BANK');</script>";
							}
						}
					}
				}
			}
			for($i=0;$i<count($arrpasatiempoNuevo);$i++){
				//echo "<br><br>";
				if(! in_array($arrpasatiempoNuevo[$i], $arrPasatiempoViejo)){
					$queryPN = "insert into PERSON_BANK values(NEWID(), '".$idPersona."','".$arrpasatiempoNuevo[$i]."',0, 0)";
					if(! sqlsrv_query($conn, $queryPN)){
						echo "Error: ".$queryPN."</script>";
						
					}
				}
			}
			
			/*$queryHorario = "select * from persworktime where pers_snr = '$idPersona' and REC_STAT = 0";
			$rsHorario = sqlsrv_query($conn, $queryHorario, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			
			if(sqlsrv_num_rows($rsHorario) > 0 ){
				$arrHorario = sqlsrv_fetch_array($rsHorario);
				$queryHorario = "update persworktime set 
					PON_AM = ".substr($horario, 0, 1).",
					PON_PM = ".substr($horario, 1, 1).",
					FREE1 = ".substr($horario, 3, 1).",
					BEST1 = ".substr($horario, 4, 1).",
					UT_AM = ".substr($horario, 5, 1).",
					UT_PM = ".substr($horario, 6, 1).",
					FREE2 = ".substr($horario, 8, 1).",
					BEST2 = ".substr($horario, 9, 1).",
					SR_AM = ".substr($horario, 10, 1).",
					SR_PM = ".substr($horario, 11, 1).",
					FREE3 = ".substr($horario, 13, 1).",
					BEST3 = ".substr($horario, 14, 1).",
					CET_AM = ".substr($horario, 15, 1).",
					CET_PM = ".substr($horario, 16, 1).",
					FREE4 = ".substr($horario, 18, 1).",
					BEST4 = ".substr($horario, 19, 1).",
					PET_AM = ".substr($horario, 20, 1).",
					PET_PM = ".substr($horario, 21, 1).",
					FREE5 = ".substr($horario, 23, 1).",
					BEST5 = ".substr($horario, 24, 1).",
					SUB_AM = ".substr($horario, 25, 1).",
					SUB_PM = ".substr($horario, 26, 1).",
					FREE6 = ".substr($horario, 28, 1).",
					BEST6 = ".substr($horario, 29, 1).",
					NED_AM = ".substr($horario, 30, 1).",
					NED_PM = ".substr($horario, 31, 1).",
					FREE7 = ".substr($horario, 33, 1).",
					BEST7 = ".substr($horario, 34, 1).",
					COMENT1 = '$lunesComentarios',
					COMENT2 = '$martesComentarios',
					COMENT3 = '$miercolesComentarios',
					COMENT4 = '$juevesComentarios',
					COMENT5 = '$viernesComentarios',
					COMENT6 = '$sabadoComentarios',
					COMENT7 = '$domingoComentarios',
					SYNC = 0 
					where PWTIM_SNR = '".$arrHorario['PWTIM_SNR']."'";
			}else{
				//echo "horario: ".$horario;
				if($horario != '00000000000000000000000000000000000'){
					$queryHorario = "insert into persworktime ( PWTIM_SNR,
					PERS_SNR,
					PWADR_SNR,
					PON_AM,
					PON_PM,
					FREE1,
					BEST1,
					UT_AM,
					UT_PM,
					FREE2,
					BEST2,
					SR_AM,
					SR_PM,
					FREE3,
					BEST3,
					CET_AM,
					CET_PM,
					FREE4,
					BEST4,
					PET_AM,
					PET_PM,
					FREE5,
					BEST5,
					SUB_AM,
					SUB_PM,
					FREE6,
					BEST6,
					NED_AM,
					NED_PM,
					FREE7,
					BEST7,
					REC_STAT,
					COMENT1,
					COMENT2,
					COMENT3,
					COMENT4,
					COMENT5,
					COMENT6,
					COMENT7,
					SYNC )
					values(NEWID(),
					'$idPersona',
					'$idPwork',
					".substr($horario, 0, 1).",
					".substr($horario, 1, 1).",
					".substr($horario, 3, 1).",
					".substr($horario, 4, 1).",
					".substr($horario, 5, 1).",
					".substr($horario, 6, 1).",
					".substr($horario, 8, 1).",
					".substr($horario, 9, 1).",
					".substr($horario, 10, 1).",
					".substr($horario, 11, 1).",
					".substr($horario, 13, 1).",
					".substr($horario, 14, 1).",
					".substr($horario, 15, 1).",
					".substr($horario, 16, 1).",
					".substr($horario, 18, 1).",
					".substr($horario, 19, 1).",
					".substr($horario, 20, 1).",
					".substr($horario, 21, 1).",
					".substr($horario, 23, 1).",
					".substr($horario, 24, 1).",
					".substr($horario, 25, 1).",
					".substr($horario, 26, 1).",
					".substr($horario, 28, 1).",
					".substr($horario, 29, 1).",
					".substr($horario, 30, 1).",
					".substr($horario, 31, 1).",
					".substr($horario, 33, 1).",
					".substr($horario, 34, 1).",
					0,
					'$lunesComentarios',
					'$martesComentarios',
					'$miercolesComentarios',
					'$juevesComentarios',
					'$viernesComentarios',
					'$sabadoComentarios',
					'$domingoComentarios',
					0
					)";
				//echo $queryHorario;
					if(! sqlsrv_query($conn, $queryHorario)){
						echo "<script>alert('Problemas query horario');</script>";
					}
				}
			}*/
			
		}else{
			
			$rsIdPers = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idPers from PERSON where PERS_SNR = '00000000-0000-0000-0000-000000000000'"));
			$idPersona = $rsIdPers['idPers'];
			$rsPwork = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idPwork from PERSON_APPROVAL where PERS_APPROVAL_SNR = '00000000-0000-0000-0000-000000000000'"));
			$idPwork = $rsPwork['idPwork'];
			$tipoMovimiento = "N";
			
			//echo "tipoUsuario: ".$tipoUsuario;
			if($tipoUsuario == 4){//repre
				$insertaApproval = 1;
			}else{
				$tipoUsuario = 0;
			}
			
			$Arrpasatiempo = explode(",", $pasatiempo);
			//print_r($Arrpasatiempo);
			for($i=0;$i<count($Arrpasatiempo);$i++){
				if($Arrpasatiempo[$i] != ''){
					$query = "insert into PERSON_BANK values(NEWID(), '".$idPersona."','".$Arrpasatiempo[$i]."',0, 0)";
					if(! sqlsrv_query($conn, $query)){
						//echo $query."<br>";
						echo "<script>alert('error PERSON_BANK');</script>";
					}else{
						
					}
				}
			}
			
			/*if($horario != '00000000000000000000000000000000000'){
				$queryHorario = "insert into persworktime ( PWTIM_SNR,
					PERS_SNR,
					PWADR_SNR,
					PON_AM,
					PON_PM,
					FREE1,
					BEST1,
					UT_AM,
					UT_PM,
					FREE2,
					BEST2,
					SR_AM,
					SR_PM,
					FREE3,
					BEST3,
					CET_AM,
					CET_PM,
					FREE4,
					BEST4,
					PET_AM,
					PET_PM,
					FREE5,
					BEST5,
					SUB_AM,
					SUB_PM,
					FREE6,
					BEST6,
					NED_AM,
					NED_PM,
					FREE7,
					BEST7,
					REC_STAT,
					COMENT1,
					COMENT2,
					COMENT3,
					COMENT4,
					COMENT5,
					COMENT6,
					COMENT7,
					SYNC )
					values(NEWID(),
					'$idPersona',
					'$idPwork',
					".substr($horario, 0, 1).",
					".substr($horario, 1, 1).",
					".substr($horario, 3, 1).",
					".substr($horario, 4, 1).",
					".substr($horario, 5, 1).",
					".substr($horario, 6, 1).",
					".substr($horario, 8, 1).",
					".substr($horario, 9, 1).",
					".substr($horario, 10, 1).",
					".substr($horario, 11, 1).",
					".substr($horario, 13, 1).",
					".substr($horario, 14, 1).",
					".substr($horario, 15, 1).",
					".substr($horario, 16, 1).",
					".substr($horario, 18, 1).",
					".substr($horario, 19, 1).",
					".substr($horario, 20, 1).",
					".substr($horario, 21, 1).",
					".substr($horario, 23, 1).",
					".substr($horario, 24, 1).",
					".substr($horario, 25, 1).",
					".substr($horario, 26, 1).",
					".substr($horario, 28, 1).",
					".substr($horario, 29, 1).",
					".substr($horario, 30, 1).",
					".substr($horario, 31, 1).",
					".substr($horario, 33, 1).",
					".substr($horario, 34, 1).",
					0,
					'$lunesComentarios',
					'$martesComentarios',
					'$miercolesComentarios',
					'$juevesComentarios',
					'$viernesComentarios',
					'$sabadoComentarios',
					'$domingoComentarios',
					0
					)";
				
				if(! sqlsrv_query($conn, $queryHorario)){
					echo "<script>alert('problemas query horario');</script>";
				}
			}*/
			
			$qPersonUD = "insert into PERSON_UD( 
				PERS_SNR,
				Prod1What_SNR,
				Prod1W,
				Prod1H,
				Prod1A,
				Prod1T,
				Prod2What_SNR,
				Prod2W,
				Prod2H,
				Prod2A,
				Prod2T,
				Prod3What_SNR,
				Prod3W,
				Prod3H,
				Prod3A,
				Prod3T,
				Prod4What_SNR,
				Prod4W,
				Prod4H,
				Prod4A,
				Prod4T,
				Prod5What_SNR,
				Prod5W,
				Prod5H,
				Prod5A,
				Prod5T,
				field_01_SNR,
				field_02_SNR,
				field_03_SNR,
				field_04_SNR,
				field_05_SNR,
				field_06_SNR,
				field_07_SNR,
				field_08_SNR,
				field_09_SNR,
				field_10_SNR,
				field_11_SNR,
				field_12_SNR,
				field_13_SNR,
				field_14_SNR,
				field_15_SNR,
				field_16_SNR,
				field_17_SNR,
				field_18_SNR,
				field_19_SNR,
				REC_STAT,
				SYNC,
				CREATION_TIMESTAMP,
				CHANGED_TIMESTAMP,
				SYNC_TIMESTAMP ) values (
				'".$idPersona."', 
				'".$prod1what."', 
				'".$prod1w."', 
				'".$prod1h."', 
				'".$prod1a."', 
				'".$prod1t."', 
				'".$prod2what."', 
				'".$prod2w."', 
				'".$prod2h."', 
				'".$prod2a."', 
				'".$prod2t."', 
				'".$prod3what."', 
				'".$prod3w."', 
				'".$prod3h."', 
				'".$prod3a."', 
				'".$prod3t."', 
				'".$prod4what."', 
				'".$prod4w."', 
				'".$prod4h."', 
				'".$prod4a."', 
				'".$prod4t."', 
				'".$prod5what."', 
				'".$prod5w."', 
				'".$prod5h."', 
				'".$prod5a."', 
				'".$prod5t."',  
				'".$divmedico."', 
				'".$lider."', 
				'".$paraestatales."', 
				'".$segmentacionflonorm."', 
				'".$segmentacionvessel."', 
				'".$segmentacionzirfos."', 
				'".$segmentacionateka."', 
				'".$segmentacionganar."', 
				'".$segmentaciondesarrollar."', 
				'".$segmentaciondefender."', 
				'".$segmentacionevaluar."',
				'".$field_12_SNR."', 
				'".$field_13_SNR."',
				'".$field_14_SNR."', 
				'".$field_15_SNR."',
				'".$field_16_SNR."', 
				'".$field_17_SNR."',
				'".$field_18_SNR."',
				'".$field_18_SNR."',
				0,
				0, 
				getdate(), 
				null, 
				null) ";
				
				if(sqlsrv_query($conn, $qPersonUD) === false){
					$error = print_r( sqlsrv_errors(), true);
					echo "<script>alert('".$error."');</script>";
				}
		}
		
		if($idPworkNuevo != ''){
			$idPwork = $idPworkNuevo;
		}
		
		if($fecha != 'null'){
			$fecha = "'".$fecha."'";
		}
		
		//echo "ia: ".$insertaApproval."<br>";
		
		if($insertaApproval == 1){
			$queryPersonApproval = "insert into PERSON_APPROVAL (
				PERS_APPROVAL_SNR,
				REC_STAT,
				P_PERS_SNR,
				P_FNAME,
				P_LNAME,
				P_SEX_SNR,
				P_INFO_SHORTTIME,
				P_SPEC_SNR,
				P_SUBSPEC_SNR,
				P_BIRTHDATE,
				P_STATUS_SNR,
				CREATION_TIMESTAMP,
				P_MOTHERS_LNAME,
				P_INFO,
				P_INFO_LONGTIME,
				P_prof_id,
				SYNC,
				P_movement_type,
				PLW_PWORK_SNR,
				PLW_INST_SNR,
				P_NR,
				PLW_DEL_REASON,
				PLW_DEL_STATUS_SNR,
				P_frecvis_snr,
				P_category_snr,
				PLW_NUM_INT,
				p_tel1,
				p_email1,
				p_tel2,
				p_email2,
				p_mobile,
				plw_tower,
				plw_floor,
				plw_office,
				plw_department,
				p_diffvis_snr,
				P_patperweek_snr, 
				P_FEE_TYPE_SNR,
				P_PERSTYPE_SNR
			) values(
				'$idPersApproval',
				'0',
				'$idPersona',
				'$nombre',
				'$paterno',
				'$sexo',
				'$corto',
				'$especialidad',
				'$subespecialidad',
				$fecha,
				'$estatusPersona',
				getdate(),
				'$materno',
				'$generales',
				'$largo',
				'$cedula',
				0,
				'$tipoMovimiento',
				'$idPwork',
				'$idInst',
				'$nr',
				NULL,
				'00000000-0000-0000-0000-000000000000',
				'$frecuencia',
				'$categoria',
				'$numInterior',
				'$telPersonal',
				'$mailPersonal',
				'$telPersonal2',
				'$mailPersonal2',
				'$celular',
				'$torre',
				'$piso',
				'$consultorio',
				'$departamento',
				'$dificultadVisita',
				'$pacientesSemana',
				'$honorarios',
				'$tipoPersona'
			)";
		
			$queryApprovalStatus = "insert into APPROVAL_STATUS (
				APPROVAL_STATUS_SNR,
				TABLE_NR,
				RECORD_KEY,
				DATE_CHANGE,
				CHANGE_USER_SNR,
				APPROVED_DATE,
				APPROVED_USER_SNR,
				APPROVED_STATUS,
				REC_STAT,
				PERS_APPROVAL_SNR,
				PWORK_SNR,
				INST_APPROVAL_SNR,
				REJECT_REASON_SNR,
				RECORD_KEY_OLD,
				PWORK_OLD_SNR,
				SYNC,
				MOVEMENT_TYPE
				)
				values (
				'$idApprovalStatus',
				456,
				'$idPersona',
				getdate(),
				'$idUsuario',
				NULL,
				NULL,
				1,
				0,
				'$idPersApproval',
				'$idInst',
				'00000000-0000-0000-0000-000000000000',
				'00000000-0000-0000-0000-000000000000',
				'00000000-0000-0000-0000-000000000000',
				'00000000-0000-0000-0000-000000000000',
				0,
				0
				)";
			
			if(!sqlsrv_query($conn, $queryPersonApproval) || !sqlsrv_query($conn, $queryApprovalStatus)){
				echo "<script>alertErrorAgregarMedico();</script>";
				echo "<br><br>".$queryPersonApproval."<br><br>";
			}
			//echo $queryPersonApproval."<br><br>".$queryApprovalStatus;
		}else{
			//echo "ip: ".$tipoMovimiento;
			if($tipoMovimiento == 'N'){
				$rutaAsignada = $_POST['rutaAsignada'];
				$idPersona = sqlsrv_fetch_array(sqlsrv_query($conn, "select newid() as idPersona from person where PERS_SNR = '00000000-0000-0000-0000-000000000000'"))['idPersona'];
				$queryPerson = "insert into person ( 
					PERS_SNR, 
					PERSTYPE_SNR, 
					FNAME,
					LNAME,
					SEX_SNR,
					INFO_SHORTTIME,
					INFO_LONGTIME,
					INFO,
					SPEC_SNR,
					BIRTHDATE,
					mothers_lname,
					prof_id,
					subSpec_snr,
					SYNC,
					frecvis_snr,
					category_snr,
					PATPERWEEK_SNR,
					EMAIL1,
					EMAIL2,
					TEL1,
					TEL2,
					MOBILE,
					fee_type_snr,
					diffvis_snr,
					STATUS_SNR,
					CREATION_TIMESTAMP,
					REC_STAT) 
					values ('$idPersona', 
					'$tipoPersona',
					'$nombre',
					'$paterno',
					'$sexo',
					'$corto',
					'$largo',
					'$generales',
					'$especialidad',
					$fecha,
					'$materno',
					'$cedula',
					'$subespecialidad',
					0,
					'$frecuencia',
					'$categoria',
					'$pacientesSemana',
					'".strtolower($mailPersonal)."',
					'".strtolower($mailPersonal2)."',
					'$telPersonal',
					'$telPersonal2',
					'$celular',
					'$honorarios',
					'$dificultadVisita',
					'$estatusPersona',
					GETDATE(),
					0
					)";
					
				if(! sqlsrv_query($conn, $queryPerson)){
					echo "queryPerson: ".$queryPerson."<br>";
				}
				
				$queryPLW = "insert into PERSLOCWORK( 
					PWORK_SNR, 
					PERS_SNR,	
					INST_SNR,	
					NUM_INT,
					SYNC,
					REC_STAT,
					OFFICE,
					FLOOR,
					TOWER,
					DEPARTMENT,
					TEL,
					EMAIL
					) values ( 
					'$idPwork',
					'$idPersona',
					'$idInst',
					'$numInterior',
					0,
					0,
					'$consultorio',
					'$piso',
					'$torre',
					'$departamento',
					'$telefono',
					'$email'
					) ";
					
				$queryPSW = "insert into PERS_SREP_WORK (
						PERSREP_SNR,
						PWORK_SNR,
						USER_SNR,
						PERS_SNR,
						INST_SNR,
						SYNC,
						REC_STAT
					) values (
						NEWID(),
						'$idPwork',
						'$rutaAsignada',
						'$idPersona',
						'$idInst',
						0,
						0
					) ";
				$queryUT = "select * from user_territ where user_snr = '".$rutaAsignada."' and INST_snr = '".$idInst."'";
				$rsUT = sqlsrv_query($conn, $queryUT, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
				$actualizaUT = "";
				if(sqlsrv_num_rows($rsUT) > 0){
					$idUT = sqlsrv_fetch_array($rsUT)['UTER_SNR'];
					if($rsUT['REC_STAT'] != 0){
						$actualizaUT = "update user_territ SET REC_STAT = 0 WHERE UTER_SNR = '$idUT'";
					}
				}else{
					$actualizaUT = "insert into user_territ (
							UTER_SNR,
							INST_SNR,
							USER_SNR,
							REC_STAT,
							SYNC
						) values (
							NEWID(),
							'$idInst',
							'$rutaAsignada',
							0,
							0
						)";
				}
				
				$queryValidaExistPlw = "select pwork_snr from perslocwork where pwork_snr = '$idPwork' ";
				$rsExistPlw = sqlsrv_query($conn, $queryValidaExistPlw, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
				if(sqlsrv_num_rows($rsExistPlw) == 0 ){//es un plw nuevo
					if (! sqlsrv_query($conn, $queryPLW)) {
						echo "Error: queryPLW ::: ".$queryPLW."<br>";
					} 
				} else {
					$queryUpdatePlw = "update perslocwork set rec_stat=0, sync=0 where pwork_snr='$idPwork' "; 
					if(! sqlsrv_query($conn, $queryUpdatePlw)){
						echo "Error: queryUpdatePlw ::: ".$queryUpdatePlw."<br><br>";
					}
				}
				
				
				if(! sqlsrv_query($conn, $queryPSW)){
					echo "Error: queryPSW ::: ".$queryPSW."<br>";
				}
				if($actualizaUT != ""){
					if(! sqlsrv_query($conn, $actualizaUT)){
						echo "Error: actualizaUT ::: ".$actualizaUT."<br><br>";
					}
					
				}
			}
		}
		
		echo "<script>
				$('#divPersona').hide();
				$('#divCapa3').hide();
				notificationPersonaRegistro();
				$('#' + idmedico).click();
				$('#' + idTrMedico).addClass('div-slt-lista');
			</script>";
		//echo "<script>$('#btnGuardarPersonaNuevo').prop('disabled', false);";
	}
}
?>

	