<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		
		$idPersona = $_POST['idPersona'];
		$idUsuario = $_POST['idUsuario'];
		
		if(isset($_POST['tipoUsuario']) && $_POST['tipoUsuario'] != ''){
			$tipoUsuario = $_POST['tipoUsuario'];
		}else{
			$tipoUsuario = 4;
		}
		
		if($tipoUsuario == 4){//es repre
			$motivo = $_POST['motivo'];
			$comentarios = $_POST['comentarios'];
			/* checa si no existe el registro */
			$queryValida = "select * 
				from PERSON_APPROVAL pa, APPROVAL_STATUS at 
				where pa.P_PERS_SNR = '".$idPersona."' 
				and pa.P_MOVEMENT_TYPE = 'D'
				and at.CHANGE_USER_SNR = '".$idUsuario."'
				and at.APPROVED_STATUS = 1 ";
			//echo $queryValida;
			$rsValida = sqlsrv_query($conn, $queryValida , array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if(sqlsrv_num_rows($rsValida) > 0){
				echo "<script>alert('Movimiento existente!!!');</script>";
				return;
			}
			/*********************************/
			
			$queryPersona = "select 
			p.PERSTYPE_SNR, 
			p.FNAME, 
			p.LNAME, 
			p.MOTHERS_LNAME,
			p.SEX_SNR, 
			p.SPEC_SNR, 
			p.BIRTHDATE, 
			p.SUBSPEC_SNR,
			p.CATEGORY_SNR, 
			p.PROF_ID, 
			p.FRECVIS_SNR, 
			p.INFO,
			p.INFO_LONGTIME, 
			p.INFO_SHORTTIME, 
			i.inst_snr as idInst,
			i.name as nombre,
			i.STREET1 as calle, 
			i.NUM_EXT as exterior, 
			city.zip as cp, 
			city.name as colonia,
			d.NAME as del, 
			state.NAME as estado,
			bri.name as brick, 
			plw.EMAIL as mail, 
			plw.tel, 
			plw.NUM_INT,
			p.tel1, 
			p.EMAIL1, 
			plw.TOWER, plw.FLOOR, plw.OFFICE, plw.DEPARTMENT, PLW.PWORK_SNR,
			PATPERWEEK_SNR, FEE_TYPE_SNR
			from person p
			inner join PERS_SREP_WORK ps on ps.PERS_SNR = p.pers_snr
			inner join inst i on ps.INST_SNR = i.INST_SNR
			inner join city on city.CITY_SNR = i.CITY_SNR
			inner join DISTRICT d on d.DISTR_SNR = city.DISTR_SNR
			inner join STATE on state.STATE_SNR = city.STATE_SNR
			inner join BRICK bri on bri.BRICK_SNR = city.BRICK_SNR
			inner join PERSLOCWORK plw on plw.INST_SNR = i.INST_SNR and plw.PERS_SNR = p.PERS_SNR
			where p.pers_snr = '$idPersona'
			and ps.REC_STAT = 0
			and i.REC_STAT = 0
			and plw.REC_STAT = 0";
			
			$rsPersona = sqlsrv_fetch_array(sqlsrv_query($conn, $queryPersona));
			
			if(is_object($rsPersona['BIRTHDATE'])){
				foreach ($rsPersona['BIRTHDATE'] as $key => $val) {
					if(strtolower($key) == 'date'){
						$fecha = substr($val, 0, 10);
					}
				}
			}else{
				$fecha = '';
			}
		
			$tipoPersona = $rsPersona['PERSTYPE_SNR'];
			$nombre = $rsPersona['FNAME'];
			$paterno = $rsPersona['LNAME'];
			$materno = $rsPersona['MOTHERS_LNAME'];
			$sexo = $rsPersona['SEX_SNR'];
			$especialidad = $rsPersona['SPEC_SNR'];
			$subespecialidad = $rsPersona['SUBSPEC_SNR'];
			
			$categoria = $rsPersona['CATEGORY_SNR'];
			$cedula = $rsPersona['PROF_ID'];
			$frecuencia = $rsPersona['FRECVIS_SNR'];
			$idInst = $rsPersona['idInst'];
			$telefono = $rsPersona['tel'];
			$email = $rsPersona['mail'];
			$corto = $rsPersona['INFO'];
			$largo = $rsPersona['INFO_LONGTIME'];
			$generales = $rsPersona['INFO_SHORTTIME'];
			
			$nombreInst = $rsPersona['nombre'];
			$calleInst = $rsPersona['calle'];
			$extInst = $rsPersona['exterior'];
			$cpInst = $rsPersona['cp'];
			$colInst = $rsPersona['colonia'];
			$delInst = $rsPersona['del'];
			$estInst = $rsPersona['estado'];
			$briInst = $rsPersona['brick'];
			
			$mailPersonal = $rsPersona['EMAIL1'];
			$telPersonal = $rsPersona['tel1'];
			
			$torre = $rsPersona['TOWER'];
			$piso = $rsPersona['FLOOR'];
			$consultorio = $rsPersona['OFFICE'];
			$departamento = $rsPersona['DEPARTMENT'];
			
			$idPwork = $rsPersona['PWORK_SNR'];
			
			$pacientesXsemana = $rsPersona['PATPERWEEK_SNR'];
			$honorarios = $rsPersona['FEE_TYPE_SNR'];
			
			$nr = sqlsrv_fetch_array(sqlsrv_query($conn, "select max(p_nr) as maximo from PERSON_APPROVAL"))['maximo']+1;
			
			$idPersApproval = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idPersApproval from PERSON_APPROVAL where pers_approval_snr = '00000000-0000-0000-0000-000000000000'"))['idPersApproval'];
			
			$queryPersonApproval = "insert into PERSON_APPROVAL (
					PERS_APPROVAL_SNR,
					REC_STAT,
					P_PERS_SNR,
					P_FNAME,
					P_LNAME,
					P_SEX_SNR,
					P_INFO,
					P_SPEC_SNR,
					P_BIRTHDATE,
					P_STATUS_SNR,
					P_PERSTYPE_SNR,
					CREATION_TIMESTAMP,
					P_MOTHERS_LNAME,
					P_INFO_SHORTTIME,
					P_INFO_LONGTIME,
					P_PROF_ID,
					P_SUBSPEC_SNR,
					SYNC,
					P_MOVEMENT_TYPE,
					PLW_PWORK_SNR,
					PLW_INST_SNR,
					P_NR,
					PLW_DEL_REASON,
					PLW_DEL_STATUS_SNR,
					P_FRECVIS_SNR,
					P_CATEGORY_SNR,
					PLW_EMAIL,
					PLW_TEL,
					p_tel1,
					p_email1,
					P_PATPERWEEK_SNR, 
					P_FEE_TYPE_SNR
				) values(
					'$idPersApproval',
					'0',
					'$idPersona',
					'$nombre',
					'$paterno',
					'$sexo',
					'$corto',
					'$especialidad',
					'$fecha',
					'00000000-0000-0000-0000-000000000000',
					'$tipoPersona',
					getdate(),
					'$materno',
					'$generales',
					'$largo',
					'$cedula',
					'$subespecialidad',
					0,
					'D',
					'$idPwork',
					'$idInst',
					'$nr',
					'$comentarios',
					'$motivo',
					'$frecuencia',
					'$categoria',
					'$email',
					'$telefono',
					'$telPersonal',
					'$mailPersonal',
					'$pacientesXsemana',
					'$honorarios')";
			
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
				NEWID(),
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
				'D')";
			
			if(! sqlsrv_query($conn, $queryPersonApproval)){
				echo "Error: queryPersonApproval ::: ".$queryPersonApproval."<br><br>";
			}
			
			if(! sqlsrv_query($conn, $queryApprovalStatus)){
				echo "Error: queryApprovalStatus ::: ".$queryApprovalStatus."<br><br>";
			}

			echo "<script>alertEliminarMedicoRepre();</script>";
		}else{
			$ruta = $_POST['ruta'];
			$qActualizaPerson = "update person set rec_stat = 2, sync = 0 where pers_snr = '".$idPersona."'";
			if(! sqlsrv_query($conn, $qActualizaPerson)){
				echo "error: qActualizaPerson ".$qActualizaPerson."<br>";
			}
			/*$qActualizaPSW = "update PERS_SREP_WORK set rec_stat = 2, sync = 0 where rec_stat = 0 and pers_snr = '".$idPersona."' and user_snr = '".$ruta."' ";
			if(! sqlsrv_query($conn, $qActualizaPSW)){
				echo "error: qActualizaPSW ".$qActualizaPSW."<br>";
			}*/
			echo "<script>$('#btnActualizarPers').click();</script>";
		}
	}
	
?>