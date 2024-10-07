<?php
	include "../conexion.php";
	
	function cambioDomicilio($ruta, $idPersona, $idInst, $existe, $conn, $email, $telefono,$torre,$piso,$consultorio,$departamento,$num_int){
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
				$regUserTerrit = sqlsrv_fetch_array($rsUserTerrit);
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
			DEPARTMENT = '$departamento',
			NUM_INT = '$num_int'			
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
		$nombre = strtoupper(utf8_decode($_POST['nombre']));
		$paterno = strtoupper(utf8_decode($_POST['paterno']));
		$materno = strtoupper(utf8_decode($_POST['materno']));
		$sexo = ($_POST['sexo'] == '') ? '00000000-0000-0000-0000-000000000000' : $_POST['sexo'];
		$especialidad = $_POST['especialidad'];
		$subespecialidad = ($_POST['subespecialidad'] == '') ? '00000000-0000-0000-0000-000000000000' : $_POST['subespecialidad'];
		$cedula = $_POST['cedula'];
		$categoria = ($_POST['categoria'] == '') ? '00000000-0000-0000-0000-000000000000' : $_POST['categoria'];
		$estatusPersona = ($_POST['estatusPersona'] == '') ? '00000000-0000-0000-0000-000000000000' : $_POST['estatusPersona'];
		$pacientesSemana = ($_POST['pacientesSemana'] == '') ? '00000000-0000-0000-0000-000000000000' : $_POST['pacientesSemana'];
		$honorarios = ($_POST['honorarios'] == '') ? '00000000-0000-0000-0000-000000000000' : $_POST['honorarios'];
		$fecha = (strpos($_POST['fecha'], 'null') !== false) ? 'null' : $_POST['fecha'];
		$telPersonal = $_POST['telPersonal'];
		$telPersonal2 = $_POST['telPersonal2'];
		$mailPersonal = $_POST['mailPersonal'];
		$mailPersonal2 = $_POST['mailPersonal2'];
		$celular = $_POST['celular'];
		$nombreAsistente = $_POST['nombreAsistente'];
		$telAsistente = $_POST['telAsistente'];
		$mailAsistente = $_POST['mailAsistente'];
		$frecuencia = ($_POST['frecuencia'] == '') ? '00000000-0000-0000-0000-000000000000' : $_POST['frecuencia'];
		$dificultadVisita = ($_POST['dificultadVisita'] == '') ? '00000000-0000-0000-0000-000000000000' : $_POST['dificultadVisita'];
		$idInst = $_POST['idInst'];
		$num_int = $_POST['interior'];
		
		/*$nombreHospital = $_POST['nombreHospital'];
		$preferenciaContacto = ($_POST['preferenciaContacto'] == '') ? '00000000-0000-0000-0000-000000000000' : $_POST['preferenciaContacto'];
		$aceptaApoyo = ($_POST['aceptaApoyo'] == '') ? '00000000-0000-0000-0000-000000000000' : $_POST['aceptaApoyo'];
		$porqueAceptaApoyo = $_POST['porqueAceptaApoyo'];
		$botiquin = ($_POST['botiquin'] == '') ? '00000000-0000-0000-0000-000000000000' : $_POST['botiquin'];
		$compraDirecta = ($_POST['compraDirecta'] == '') ? '00000000-0000-0000-0000-000000000000' : $_POST['compraDirecta'];*/
		$liderOpinion = ($_POST['liderOpinion'] == '') ? '00000000-0000-0000-0000-000000000000' : $_POST['liderOpinion'];
		$speaker_snr = ($_POST['speaker_snr'] == '') ? '00000000-0000-0000-0000-000000000000' : $_POST['speaker_snr'];
		//$tipoConsulta = ($_POST['tipoConsulta'] == '') ? '00000000-0000-0000-0000-000000000000' : $_POST['tipoConsulta'];

		$estadoCivil = ($_POST['estadoCivil'] == '') ? '00000000-0000-0000-0000-000000000000' : $_POST['estadoCivil'];
		$padecimiemtosMedicos = ($_POST['padecimientosMedicos'] == '') ? '00000000-0000-0000-0000-000000000000' : $_POST['padecimientosMedicos'];

		$telefono = '';//$_POST['telefono'];//PLW
		$email = '';//strtolower($_POST['email']);//PLW
		$corto = strtoupper(utf8_decode($_POST['corto']));
		$largo = strtoupper(utf8_decode($_POST['largo']));
		$generales = strtoupper(utf8_decode($_POST['generales']));

		$tipoUsuario=$_POST['tipoUsuario'];

		if($tipoUsuario ==4){
			$idUsuario = $_POST['idUsuario'];

		}else{
			$idUsuario = $_POST['representante'];
		}
		
		$pasatiempo = str_replace(",",";",$_POST['pasatiempo']);
		
		$torre = strtoupper(utf8_decode($_POST['torre']));
		$piso = strtoupper(utf8_decode($_POST['piso']));
		$consultorio = strtoupper(utf8_decode($_POST['consultorio']));
		$departamento = strtoupper(utf8_decode($_POST['departamento']));
		
		/*$torre = '';
		$piso = '';
		$consultorio = '';
		$departamento = '';*/
		
		$tipoUsuario = $_POST['tipoUsuario'];
		$ruta = $_POST['ruta'];

		

		
		$idPworkNuevo = '';
		
		$actualizaPerson = 0;
		$insertaApproval = 0;
		
		//echo "email: ".$email."<br>";
		
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
		
		
		if($idPersona != ''){
			$tipoMovimiento = "C";
			//echo "tipoUsuario: ".$tipoUsuario."<br>";
			
			/*grabo approval changes */
			$queryCampos = "select rc.rtab_snr, rc.rcol_snr, upper(rc.name) as name
				from rep_tables rt, rep_columns rc
				where rt.name = 'person'
				and rt.RTAB_SNR = rc.RTAB_SNR ";
			if($tipoUsuario == 4){
				// revisamos si hay cambio de dirección 
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
					case 
						when upper(rc.name) = 'TEL1' then 'TEL1_INST' 
						when upper(rc.name) = 'TEL2' then 'TEL2_INST'
						when upper(rc.name) = 'EMAIL1' then 'EMAIL1_INST' 
						when upper(rc.name) = 'FRECVIS_SNR' then 'FRECVIS_SNR_INST'
						else upper(rc.NAME) end as name, 
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
				
				//$queryAnt = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from person where PERS_SNR = '$idPersona'"));
				$qAnt = "select p.*, i.NAME, i.STREET1, c.ZIP, 
					c.name as COLONIA, 
					d.NAME as CIUDAD, edo.NAME as ESTADO, 
					plw.*,
					plw.tel as TEL_PLW
					from person p, PERS_SREP_WORK psw, inst i, city c, 
					DISTRICT d, state edo, PERSLOCWORK plw
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
						$queryPersonApproval .= ",MOTHERS_LNAME = '$materno' ";
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
				
				if($queryAnt['PATPERWEEK_SNR'] != $pacientesSemana){
					$idCampo = array_search ( "PATPERWEEK_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$pacientesSemana."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",PATPERWEEK_SNR = '$pacientesSemana' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['FEE_TYPE_SNR'] != $honorarios){
					$idCampo = array_search ( "FEE_TYPE_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$honorarios."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",FEE_TYPE_SNR = '$honorarios' ";
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
				
				if($queryAnt['TEL1'] != $telPersonal){
					$idCampo = array_search ( "TEL1" , $campos );
					//echo $approval[$idCampo]."<br>";
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
				
				if($queryAnt['TEL2'] != $telPersonal2){
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
				
				if($queryAnt['ASSISTANT_NAME'] != $nombreAsistente){
					$idCampo = array_search ( "ASSISTANT_NAME" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$nombreAsistente."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",ASSISTANT_NAME = '$nombreAsistente' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['ASSISTANT_TEL'] != $telAsistente){
					$idCampo = array_search ( "ASSISTANT_TEL" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$telAsistente."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",ASSISTANT_TEL = '$telAsistente' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['ASSISTANT_EMAIL'] != $mailAsistente){
					$idCampo = array_search ( "ASSISTANT_EMAIL" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$mailAsistente."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",ASSISTANT_EMAIL = '$mailAsistente' ";
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
				
				/*if($queryAnt['HOSPITAL_NAME'] != $nombreHospital){
					$idCampo = array_search ( "HOSPITAL_NAME" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$nombreHospital."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",HOSPITAL_NAME = '$nombreHospital' ";
						$actualizaPerson = 1;
					}
				}
				if($queryAnt['PREFERRED_CONTACT_SNR'] != $preferenciaContacto){
					$idCampo = array_search ( "PREFERRED_CONTACT_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$preferenciaContacto."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",PREFERRED_CONTACT_SNR = '$preferenciaContacto' ";
						$actualizaPerson = 1;
					}
				}
				if($queryAnt['ACCEPT_SUPPORT_SNR'] != $aceptaApoyo){
					$idCampo = array_search ( "ACCEPT_SUPPORT_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$aceptaApoyo."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",ACCEPT_SUPPORT_SNR = '$aceptaApoyo' ";
						$actualizaPerson = 1;
					}
				}
				if($queryAnt['ACCEPT_SUPPORT_INFO'] != $porqueAceptaApoyo){
					$idCampo = array_search ( "ACCEPT_SUPPORT_INFO" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$porqueAceptaApoyo."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",ACCEPT_SUPPORT_INFO = '$porqueAceptaApoyo' ";
						$actualizaPerson = 1;
					}
				}
				if($queryAnt['AID_KIT_SNR'] != $botiquin){
					$idCampo = array_search ( "AID_KIT_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$botiquin."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",AID_KIT_SNR = '$botiquin' ";
						$actualizaPerson = 1;
					}
				}
				if($queryAnt['DIRECT_PURCHASE_SNR'] != $compraDirecta){
					$idCampo = array_search ( "DIRECT_PURCHASE_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$compraDirecta."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",DIRECT_PURCHASE_SNR = '$compraDirecta' ";
						$actualizaPerson = 1;
					}
				}*/
				if($queryAnt['KOL_SNR'] != $liderOpinion){
					$idCampo = array_search ( "KOL_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$liderOpinion."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",KOL_SNR = '$liderOpinion' ";
						$actualizaPerson = 1;
					}
				}

				if($queryAnt['SPEAKER_SNR'] != $speaker_snr){
					$idCampo = array_search ( "SPEAKER_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$speaker_snr."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",SPEAKER_SNR = '$speaker_snr' ";
						$actualizaPerson = 1;
					}
				}
				/*
				
				if($queryAnt['CONSULTATION_TYPE_SNR'] != $tipoConsulta){
					$idCampo = array_search ( "CONSULTATION_TYPE_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$tipoConsulta."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",CONSULTATION_TYPE_SNR = '$tipoConsulta' ";
						$actualizaPerson = 1;
					}
				}*/

				if($queryAnt['AILMENT_SNR'] != $padecimiemtosMedicos){
					$idCampo = array_search ( "AILMENT_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$padecimiemtosMedicos."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",AILMENT_SNR = '$padecimiemtosMedicos' ";
						$actualizaPerson = 1;
					}
				}

				if($queryAnt['MARITAL_STATUS_SNR'] != $estadoCivil){
					$idCampo = array_search ( "MARITAL_STATUS_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'".$estadoCivil."','',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",MARITAL_STATUS_SNR = '$estadoCivil' ";
						$actualizaPerson = 1;
					}
				}

				if($queryAnt['BASIC_LIST_SNR'] != $pasatiempo){
					$idCampo = array_search ( "BASIC_LIST_SNR" , $campos );
					//echo "generales: ".$idCampo."<br>";
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$pasatiempo."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPersonApproval .= ",BASIC_LIST_SNR = '$pasatiempo' ";
						$actualizaPerson = 1;
					}
				}
				
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
				
				$queryPersonApproval .= "where PERS_SNR = '$idPersona'";
				
				//echo $queryPersonApproval;
				
				if($actualizaPerson == 1){
					if(! sqlsrv_query($conn, $queryPersonApproval)){
						echo "person: ".$queryPersonApproval;
					}
				}
				/////
				//actualizar perslocwork
				//plw.FUNCTION_SNR as puesto, 
				//	plw.EMPLOYEESTAT as tipoTrabajo, plw.pwloc_snr as idPLW
					$actualizaplw = 0;
					$queryPLW = '';
				
				if($num_int != $queryAnt['NUM_INT']){
					$idCampo = array_search ( "NUM_INT" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$num_int."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPLW .= ",NUM_INT = '$num_int' ";
						$actualizaplw = 1;
					}
				}
				
				if($torre != $queryAnt['TOWER']){
					$idCampo = array_search ( "TOWER" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$torre."',0,0)";
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
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$piso."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPLW .= ",FLOOR = '$piso' ";
						$actualizaplw = 1;
					}
				}
				
				if($departamento != $queryAnt['DEPARTMENT']){
					$idCampo = array_search ( "DEPARTMENT" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$departamento."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPLW .= ",DEPARTMENT = '$departamento' ";
						$actualizaplw = 1;
					}
				}
				
				if($consultorio != $queryAnt['OFFICE']){
					$idCampo = array_search ( "OFFICE" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(),'".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab[$idCampo].",'00000000-0000-0000-0000-000000000000','".$consultorio."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							$insertaChange."<br><br>";
						}
					}else{
						$queryPLW .= ",OFFICE = '$consultorio' ";
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
				}
				
				//echo $updatePLW;
				
				
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
				mothers_lname = '$materno',
				SEX_SNR = '$sexo',
				SPEC_SNR = '$especialidad',
				subSpec_snr = '$subespecialidad',
				prof_id = '$cedula',
				category_snr = '$categoria',
				STATUS_SNR = '$estatusPersona',
				PATPERWEEK_SNR = '$pacientesSemana',
				FEE_TYPE_SNR = '$honorarios',
				BIRTHDATE = $fecha,
				TEL1 = '$telPersonal',
				TEL2 = '$telPersonal2',
				MOBILE = '$celular',
				EMAIL1 = '".strtolower($mailPersonal)."',
				EMAIL2 = '".strtolower($mailPersonal2)."',
				ASSISTANT_NAME = '$nombreAsistente',
				ASSISTANT_TEL = '$telAsistente',
				ASSISTANT_EMAIL = '$mailAsistente',
				FRECVIS_SNR = '$frecuencia',
				DIFFVIS_SNR = '$dificultadVisita',
				KOL_SNR = '$liderOpinion',
				INFO_SHORTTIME = '$corto',
				INFO_LONGTIME = '$largo',
				INFO = '$generales',
				SYNC = 0,
				CHANGED_TIMESTAMP = getdate(),
				BASIC_LIST_SNR = '$pasatiempo',
				SPEAKER_SNR='$speaker_snr' 
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
						$idPwork = cambioDomicilio($ruta, $idPersona, $idInst, 1, $conn, $email, $telefono,$torre,$piso,$consultorio,$departamento,$num_int);
					}else{
						$idPwork = $regPwork['PWORK_SNR'];
						
						$qActualizaPLWDatos2 = "update PERSLOCWORK set 
						rec_stat = 0, 
						sync = 0, 
						TOWER = '$torre',
						FLOOR = '$piso',
						OFFICE = '$consultorio',
						DEPARTMENT = '$departamento', 
						NUM_INT = '$num_int',
						CHANGED_TIMESTAMP = getdate() 
						where PERS_SNR = '$idPersona'  and INST_SNR = '$idInst'";

						if(! sqlsrv_query($conn, $qActualizaPLWDatos2)){
							echo "error al actualizar PERSLOCWORK: ".$qActualizaPLWDatos2."<br>";
						}
					}
				}else{//la dirección cambio
					$idPwork = cambioDomicilio($ruta, $idPersona, $idInst, 0, $conn, $email, $telefono,$torre,$piso,$consultorio,$departamento,$num_int);
				} 
			}
			
			/*$existePersonUD = sqlsrv_num_rows(sqlsrv_query($conn, "select * from person_ud where pers_snr = '".$idPersona."'", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET )));
			if($existePersonUD){
				$actualizaPersonUD = "update PERSON_UD SET 					
					SYNC = 0,
					CHANGED_TIMESTAMP = getdate(),
					where PERS_SNR = '".$idPersona."' ";
			}else{
				$actualizaPersonUD = "insert into PERSON_UD (PERS_SNR, SYNC, CHANGED_TIMESTAMP, REC_STAT )
					values('".$idPersona."',0,getdate(),0)";
			}
				
			if(! sqlsrv_query($conn, $actualizaPersonUD)){
				echo "personUD: ".$actualizaPersonUD."<br>";
			}else{
				//echo "guardo personUD: ".$actualizaPersonUD."<br>";
			}*/
			
			/*if($pasatiempo != ''){
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
			*/
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

			if($tipoUsuario==4){
				$insertaApproval = 1;
			}else{
				$insertaApproval = 2;
			}
			
			
			$rsIdPers = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idPers from PERSON where PERS_SNR = '00000000-0000-0000-0000-000000000000'"));
			$idPersona = $rsIdPers['idPers'];
			$rsPwork = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idPwork from PERSON_APPROVAL where PERS_APPROVAL_SNR = '00000000-0000-0000-0000-000000000000'"));
			$idPwork = $rsPwork['idPwork'];
			$tipoMovimiento = "N";
			
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
			
			/*$qPersonUD = "insert into PERSON_UD( 
				PERS_SNR,
				REC_STAT,
				SYNC,
				CREATION_TIMESTAMP,
				CHANGED_TIMESTAMP,
				SYNC_TIMESTAMP) values (
				'$idPersona', 
				'0', 				
				'0', 
				getdate(), 
				null, 
				null) ";
				
				if(! sqlsrv_query($conn, $qPersonUD)){
					echo "no se guardo peson ud: ".$qPersonUD."<br>";
				}*/
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
				P_PERS_SNR,
				P_PERSTYPE_SNR,
				P_FNAME,
				P_LNAME,
				P_MOTHERS_LNAME,
				P_SEX_SNR,
				P_SPEC_SNR,
				P_SUBSPEC_SNR,
				P_PROF_ID,
				P_CATEGORY_SNR,
				P_STATUS_SNR,
				P_PATPERWEEK_SNR, 
				P_FEE_TYPE_SNR,
				P_BIRTHDATE,
				P_TEL1,
				P_TEL2,
				P_MOBILE,
				P_EMAIL1,
				P_EMAIL2,
				P_ASSISTANT_NAME,
				P_ASSISTANT_TEL,
				P_ASSISTANT_EMAIL,
				P_FRECVIS_SNR,
				P_DIFFVIS_SNR,
				PLW_INST_SNR,
				PLW_PWORK_SNR,
				PLW_NUM_INT,
				P_KOL_SNR,
				P_INFO,
				P_INFO_SHORTTIME,
				P_INFO_LONGTIME,
				REC_STAT,
				CREATION_TIMESTAMP,
				SYNC,
				P_MOVEMENT_TYPE,
				P_NR,
				PLW_DEL_REASON,
				PLW_DEL_STATUS_SNR,
				PLW_TOWER,
				PLW_FLOOR,
				PLW_OFFICE,
				PLW_DEPARTMENT,
				P_BASIC_LIST_SNR,
				P_MARITAL_STATUS_SNR,
				P_AILMENT_SNR,
				P_SPEKER_SNR 
			) values(
				'$idPersApproval',
				'$idPersona',
				'$tipoPersona',
				'$nombre',
				'$paterno',
				'$materno',
				'$sexo',
				'$especialidad',
				'$subespecialidad',
				'$cedula',
				'$categoria',
				'$estatusPersona',
				'$pacientesSemana',
				'$honorarios',
				$fecha,
				'$telPersonal',
				'$telPersonal2',
				'$celular',
				'$mailPersonal',
				'$mailPersonal2',
				'$nombreAsistente',
				'$telAsistente',
				'$mailAsistente',
				'$frecuencia',
				'$dificultadVisita',
				'$idInst',
				'$idPwork',
				'$num_int',
				'$liderOpinion',
				'$generales',
				'$corto',
				'$largo',
				'0',
				getdate(),
				0,
				'$tipoMovimiento',
				'$nr',
				NULL,
				'00000000-0000-0000-0000-000000000000',
				'$torre',
				'$piso',
				'$consultorio',
				'$departamento',
				'$pasatiempo',
				'$estadoCivil',
				'$padecimiemtosMedicos',
				'$speaker_snr'
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
			//echo "tipo:".$tipoMovimiento;
			if($tipoMovimiento == 'N'){
				$idUsuario=explode ( ',', $idUsuario );

			for($i=0;$i<count($idUsuario);$i++){

				$rsIdPers = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idPers from PERSON where PERS_SNR = '00000000-0000-0000-0000-000000000000'"));
				$idPersona = $rsIdPers['idPers'];
				//aqui empieza inserta Admin

				$nr = sqlsrv_fetch_array(sqlsrv_query($conn, "select max(nr)+1 as nr from person"))["nr"];
				$queryPerson = "insert into PERSON (
					PERS_SNR,
					PERSTYPE_SNR,
					FNAME,
					LNAME,
					MOTHERS_LNAME,
					SEX_SNR,
					SPEC_SNR,
					SUBSPEC_SNR,
					PROF_ID,
					CATEGORY_SNR,
					STATUS_SNR,
					PATPERWEEK_SNR, 
					FEE_TYPE_SNR,
					BIRTHDATE,
					TEL1,
					TEL2,
					MOBILE,
					EMAIL1,
					EMAIL2,
					ASSISTANT_NAME,
					ASSISTANT_TEL,
					ASSISTANT_EMAIL,
					FRECVIS_SNR,
					DIFFVIS_SNR,
					KOL_SNR,
					INFO,
					INFO_SHORTTIME,
					INFO_LONGTIME,
					REC_STAT,
					CREATION_TIMESTAMP,
					SYNC,
					NR,
					BASIC_LIST_SNR,
					SPEAKER_SNR
				) values(
					'$idPersona',
					'$tipoPersona',
					'$nombre',
					'$paterno',
					'$materno',
					'$sexo',
					'$especialidad',
					'$subespecialidad',
					'$cedula',
					'$categoria',
					'$estatusPersona',
					'$pacientesSemana',
					'$honorarios',
					$fecha,
					'$telPersonal',
					'$telPersonal2',
					'$celular',
					'$mailPersonal',
					'$mailPersonal2',
					'$nombreAsistente',
					'$telAsistente',
					'$mailAsistente',
					'$frecuencia',
					'$dificultadVisita',
					'$liderOpinion',
					'$generales',
					'$corto',
					'$largo',
					'0',
					getdate(),
					0,
					'$nr',
					'$pasatiempo',
					'$speaker_snr'
				)";
				
				if(! sqlsrv_query($conn, $queryPerson)){
					echo "inserta persona: ".$queryPerson."<br><br>";
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
					CREATION_TIMESTAMP
					) values ( 
					'$idPwork',
					'$idPersona',
					'$idInst',
					'$num_int',
					0,
					0,
					'$consultorio',
					'$piso',
					'$torre',
					'$departamento',
					getdate()
					) ";
					
				$queryValidaExistPlw = "select pwork_snr from perslocwork where pwork_snr = '$idPwork' ";
				$rsExistPlw = sqlsrv_query($conn, $queryValidaExistPlw, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
				if(sqlsrv_num_rows($rsExistPlw) == 0 ){//es un plw nuevo
					if (! sqlsrv_query($conn, $queryPLW)) {
						echo "Error: queryPLW ::: ".$queryPLW."<br>";
					} else {
						//echo $queryPLW."<br>";
					}
				} else {
					$queryUpdatePlw = "update perslocwork set rec_stat=0, sync=0 where pwork_snr='$idPwork' "; 
					if(! sqlsrv_query($conn, $queryUpdatePlw)){
						echo "Error: queryUpdatePlw ::: ".$queryUpdatePlw."<br><br>";
					}
				}
					
				$queryPSW = "insert into PERS_SREP_WORK (
						PERSREP_SNR,
						PWORK_SNR,
						USER_SNR,
						PERS_SNR,
						INST_SNR,
						SYNC,
						REC_STAT,
						CREATION_TIMESTAMP
					) values (
						NEWID(),
						'$idPwork',
						'".$idUsuario[$i]."',
						'$idPersona',
						'$idInst',
						0,
						0,
						getdate()
					) ";
					
				if(! sqlsrv_query($conn, $queryPSW)){
					echo "Error: queryPSW ::: ".$queryPSW."<br>";
				}
					
				$queryUT = "select user_snr,INST_SNR,UTER_SNR,REC_STAT from user_territ where user_snr = '".$idUsuario[$i]."' and INST_snr = '".$idInst."'";
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
							SYNC,
							CREATION_TIMESTAMP
						) values (
							NEWID(),
							'$idInst',
							'".$idUsuario[$i]."',
							0,
							0,
							getdate()
						)";
				}
				
				if($actualizaUT != ""){
					if(! sqlsrv_query($conn, $actualizaUT)){
						echo "Error: actualizaUT ::: ".$actualizaUT."<br><br>";
					}
					
				}
			}
			}else{
				$qPwork = "select * from PERS_SREP_WORK where USER_SNR = '$ruta' and PERS_SNR = '$idPersona' and INST_SNR = '$idInst'  ";
				//echo $qPwork;
				$rsPwork = sqlsrv_query($conn, $qPwork, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
				if(sqlsrv_num_rows($rsPwork) > 0){//existe
					$regPwork = sqlsrv_fetch_array($rsPwork);
					if($regPwork['REC_STAT'] != 0){///la direccion cambió
						$idPwork = cambioDomicilio($ruta, $idPersona, $idInst, 1, $conn, $email, $telefono,$torre,$piso,$consultorio,$departamento,$num_int);
					}/*else{
						$idPwork = $regPwork['PWORK_SNR'];
						
						$qActualizaPLWDatos2 = "update PERSLOCWORK set 
						rec_stat = 0, 
						sync = 0, 
						TOWER = '$torre',
						FLOOR = '$piso',
						OFFICE = '$consultorio',
						DEPARTMENT = '$departamento', 
						NUM_INT = '$num_int'
						where PERS_SNR = '$idPersona'  and INST_SNR = '$idInst'";

						if(! sqlsrv_query($conn, $qActualizaPLWDatos2)){
							echo "error al actualizar PERSLOCWORK: ".$qActualizaPLWDatos2."<br>";
						}
					}*/
				}else{//la dirección cambio
					$email = '';//email del plw
					$telefono = '';//telefono del plw
					$idPwork = cambioDomicilio($ruta, $idPersona, $idInst, 0, $conn, $email, $telefono,$torre,$piso,$consultorio,$departamento,$num_int);
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
	}
	
?>