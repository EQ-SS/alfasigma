<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		
		$idPersApproval = $_POST['idPersApproval'];
		$idUserApproved = $_POST['idUser'];
		
		$queryPersApproval = "select * from PERSON_APPROVAL pa LEFT OUTER JOIN PERSON_UD pud ON pa.P_PERS_SNR = pud.PERS_SNR where PERS_APPROVAL_SNR = '".$idPersApproval."' ";
		
		$arrPersApproval = sqlsrv_fetch_array(sqlsrv_query($conn, $queryPersApproval));
		
		$arrApprovalStatus = sqlsrv_fetch_array(sqlsrv_query($conn, "select APPROVAL_STATUS_SNR,CHANGE_USER_SNR,RECORD_KEY from APPROVAL_STATUS where PERS_APPROVAL_SNR = '".$idPersApproval."'"));
		
		$idApprovalStatus = $arrApprovalStatus['APPROVAL_STATUS_SNR'];
		$idUser = $arrApprovalStatus['CHANGE_USER_SNR'];
		$pers_snr = $arrApprovalStatus['RECORD_KEY'];
		
		$tipoMovimiento = $arrPersApproval['P_MOVEMENT_TYPE'];
		//echo "tipo: ".$tipoMovimiento."<br>";
		if($arrPersApproval['P_BIRTHDATE'] != null){
			foreach ($arrPersApproval['P_BIRTHDATE'] as $key => $val) {
				if(strtolower($key) == 'date'){
					$fechaNacimiento = substr($val, 0, 10);
				}
			}
		}else{
			$fechaNacimiento = '';
		}
		
		$approval_status = $arrPersApproval['P_MOVEMENT_TYPE'];
		$comentarios = $arrPersApproval['P_INFO'];//generales
		$largo = $arrPersApproval['P_INFO_LONGTIME'];
		$competition = $arrPersApproval['P_INFO_SHORTTIME'];//corto
		$mail = $arrPersApproval['P_EMAIL1'];
		$nombre = $arrPersApproval['P_FNAME'];
		$subespecialidad = ($arrPersApproval['P_SUBSPEC_SNR'] == '') ?  '00000000-0000-0000-0000-000000000000' : $arrPersApproval['P_SUBSPEC_SNR'];
		$cedula = $arrPersApproval['P_PROF_ID'];
		$paterno = $arrPersApproval['P_LNAME'];
		$materno = $arrPersApproval['P_MOTHERS_LNAME'];
		$categoria = $arrPersApproval['P_CATEGORY_SNR'];
		$tipoPersona = $arrPersApproval['P_PERSTYPE_SNR'];
		$sexo = $arrPersApproval['P_SEX_SNR'];
		$especialidad = $arrPersApproval['P_SPEC_SNR'];
		$status = $arrPersApproval['P_STATUS_SNR'];
		$tel = $arrPersApproval['P_TEL1'];
		$frecuencia = $arrPersApproval['P_FRECVIS_SNR'];
		$dificultadVisita = ($arrPersApproval['P_DIFFVIS_SNR'] == '') ? '00000000-0000-0000-0000-000000000000' : $arrPersApproval['P_DIFFVIS_SNR'];
		$pacientesSemana = $arrPersApproval['P_PATPERWEEK_SNR'];
		$honorarios = $arrPersApproval['P_FEE_TYPE_SNR'];
		
		$tel2 = $arrPersApproval['P_TEL2'];
		$mail2 = $arrPersApproval['P_EMAIL2'];
		$celular = $arrPersApproval['P_MOBILE'];
		
		$idPLW = $arrPersApproval['PLW_PWORK_SNR'];
		$idInst = $arrPersApproval['PLW_INST_SNR'];
		$telPLW = $arrPersApproval['PLW_TEL'];
		$interior = $arrPersApproval['PLW_NUM_INT'];
		$mailPLW = $arrPersApproval['PLW_EMAIL'];
		$piso = $arrPersApproval['PLW_FLOOR'];
		$torre = $arrPersApproval['PLW_TOWER'];
		$consultorio = $arrPersApproval['PLW_OFFICE'];
		$departamento = $arrPersApproval['PLW_DEPARTMENT'];
		
		//$tipoConsulta = $arrPersApproval['P_CONS_TYPE_SNR'];
		 
		/*$D1VXT_SNR = $arrPersApproval['P_D1VXT_SNR'];
		$PROD_D1VXT_SNR = $arrPersApproval['P_PROD_D1VXT_SNR'];
		$POTENTIAL_SNR = $arrPersApproval['P_POTENTIAL_SNR'];
		$KOL_SNR = $arrPersApproval['P_KOL_SNR'];
		$CONTACT_TYPE_SNR = $arrPersApproval['P_CONTACT_TYPE_SNR'];
		$SPEAKER_SNR = $arrPersApproval['P_SPEAKER_SNR'];
		$CATEGORY_AUDIT_SNR = $arrPersApproval['P_CATEGORY_AUDIT_SNR'];
		$CONS_DESC_SNR = $arrPersApproval['P_CONS_DESC_SNR'];
		$VITAMEDICA_SNR = $arrPersApproval['P_VITAMEDICA_SNR'];
		$CONECTION_CRM_SNR = $arrPersApproval['P_CONECTION_CRM_SNR'];
		$VIRTUAL_CONTACT_SNR = $arrPersApproval['P_VIRTUAL_CONTACT_SNR'];
		$PAYMENT_SNR = $arrPersApproval['P_PAYMENT_SNR'];
		$MEDICAL_KIT_SNR = $arrPersApproval['P_MEDICAL_KIT_SNR'];
		$PAYMENT_TYPE_SNR = $arrPersApproval['P_PAYMENT_TYPE_SNR'];*/
		
		if($tipoMovimiento == 'N'){
			$nr = sqlsrv_fetch_array(sqlsrv_query($conn, "select max(nr) as maximo from person"))['maximo']+1;
			
			$queryPersonApproval = "insert into PERSON (
				PERSTYPE_SNR,
				MOVEMENT_TYPE,
				BIRTHDATE,
				INFO_SHORTTIME,
				EMAIL1,
				FNAME,
				INFO,
				SUBSPEC_SNR,
				PROF_ID,
				LNAME,
				INFO_LONGTIME,
				MOTHERS_LNAME,
				NR,
				CATEGORY_SNR,
				PERS_SNR,
				SEX_SNR,
				SPEC_SNR,
				STATUS_SNR,
				TEL1,
				FRECVIS_SNR,
				REC_STAT,
				SYNC,
				DIFFVIS_SNR,
				PATPERWEEK_SNR,
				FEE_TYPE_SNR,
				CREATION_TIMESTAMP,
				TEL2,
				EMAIL2,
				MOBILE
			) values(
				'$tipoPersona',
				'$approval_status',
				'$fechaNacimiento',
				'$competition',
				'$mail',
				'$nombre',
				'$comentarios',
				'$subespecialidad',
				'$cedula',
				'$paterno',
				'$largo',
				'$materno',
				'$nr',
				'$categoria',
				'$pers_snr',
				'$sexo',
				'$especialidad',
				'$status',
				'$tel',
				'$frecuencia',
				0,
				0,
				'$dificultadVisita',
				'$pacientesSemana',
				'$honorarios',
				getdate(),
				'$tel2',
				'$mail2',
				'$celular'
				)";
			
			$qPersonUD = "update person_ud set sync = 0 
				where pers_snr = '".$pers_snr."'";
			
			if(!sqlsrv_query($conn, $qPersonUD)){
				echo "qPU: ".$qPersonUD."<br>";
			}
			
			$queryApprovalStatus = "update APPROVAL_STATUS set 
				APPROVED_DATE = getdate(),
				APPROVED_USER_SNR = '$idUserApproved',
				APPROVED_STATUS = 2,
				SYNC = 0
				where APPROVAL_STATUS_SNR = '$idApprovalStatus' ";
				
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
				'$idPLW',
				'$pers_snr',
				'$idInst',
				'$interior',
				0,
				0,
				'$consultorio',
				'$piso',
				'$torre',
				'$departamento',
				'$telPLW',
				'$mailPLW'
				) ";
				
			$queryPSW = "insert into PERS_SREP_WORK (
					PERSREP_SNR,
					PWORK_SNR,
					USER_SNR,
					PERS_SNR,
					INST_SNR,
					LAST_VISIT,
					NEXT_VISIT,
					PLAN_NEXT_VISIT_DATE,
					PLAN_NEXT_VISIT_INFO,
					SYNC,
					REC_STAT
				) values (
					NEWID(),
					'$idPLW',
					'$idUser',
					'$pers_snr',
					'$idInst',
					'',
					'',
					'',
					'',
					0,
					0
				) ";
			$queryUT = "select user_snr,INST_SNR,UTER_SNR,REC_STAT from user_territ where user_snr = '".$idUser."' and INST_snr = '".$idInst."'";
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
						'$idUser',
						0,
						0
					)";
			}
			
			$queryValidaExistPlw = "select pwork_snr from perslocwork where pwork_snr = '$idPLW' ";
			$rsExistPlw = sqlsrv_query($conn, $queryValidaExistPlw, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if(sqlsrv_num_rows($rsExistPlw) == 0 ){//es un plw nuevo
				if (! sqlsrv_query($conn, $queryPLW)) {
					echo "Error: queryPLW ::: ".$queryPLW."<br>";
				} else {
					//echo $queryPLW."<br>";
				}
			} else {
				$queryUpdatePlw = "update perslocwork set rec_stat=0, sync=0 where pwork_snr='$idPLW' "; 
				if(! sqlsrv_query($conn, $queryUpdatePlw)){
					//echo "Error: queryUpdatePlw ::: ".$queryUpdatePlw."<br><br>";
				}
			}
			
			
			if(! sqlsrv_query($conn, $queryPSW)){
				echo "Error: queryPSW ::: ".$queryPSW."<br>";
			}
			if($actualizaUT != ""){
				if(! sqlsrv_query($conn, $actualizaUT)){
					//echo "Error: actualizaUT ::: ".$actualizaUT."<br><br>";
				}
				
			}
			
			/* aprobar inst en caso de que también sea nueva */
			$queryExistInst = "select inst_snr from inst where inst_snr = '".$idInst."'";
			$rsExistInst = sqlsrv_query($conn, $queryExistInst, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if(sqlsrv_num_rows($rsExistInst) == 0 ){//es una inst nueva
				$regInst = sqlsrv_fetch_array($rsExistInst);
				
				$idApprovalStatusInst = sqlsrv_fetch_array(sqlsrv_query($conn, "select APPROVAL_STATUS_SNR from APPROVAL_STATUS where RECORD_KEY = '".$idInst."'"))['APPROVAL_STATUS_SNR'];
				
				$regInstApproval = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from INST_APPROVAL where I_INST_SNR = '".$idInst."'"));
				
				//echo "select * from INST_APPROVAL where I_INST_SNR = '".$idInst."'<br><br>";
				
				$idInstApproval = $regInstApproval['INST_APPROVAL_SNR'];
				$idInst = $regInstApproval['I_INST_SNR'];
				$inst = $regInstApproval['I_NAME'];
				$subtipo = $regInstApproval['I_SUBTYPE_SNR'];
				//$representantesUnidos = $regInstApproval['I_MM_NAME2'];
				$tipoInst = $regInstApproval['I_INST_TYPE'];
				$comentarios = $regInstApproval['I_INFO'];
				$city = $regInstApproval['I_CITY_SNR'];
				$calle = $regInstApproval['I_STREET1'];
				//$sucursal = $regInstApproval['I_STREET2'];
				$tel1 = $regInstApproval['I_TEL1'];
				$tel2 = $regInstApproval['I_TEL2'];
				//$NUM = $regInstApproval['I_FAX'];
				$web = $regInstApproval['I_WEB'];
				$email = $regInstApproval['I_EMAIL1'];
				$estatus = $regInstApproval['I_STATUS_SNR'];
				$categoria = $regInstApproval['I_CATEGORY_SNR'];
				
				if($tipoInst == 1){//hospital
					$queryHosApp = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from INST_HOSP_APPROVAL WHERE I_INST_APPROVAL = '".$idInstApproval."'"));
					
					//$idInst = ['I_INST_SNR'];
					$numCamas = $queryHosApp['I_NumCamas'];
					$numQuirofano = $queryHosApp['I_NumQuirofanos'];
					$numSalasExpulsion = $queryHosApp['I_NumSalasExpulsion'];
					$numCunas = $queryHosApp['I_NumCunas'];
					$numIncubadoras = $queryHosApp['I_NumIncubadoras'];
					$terapiaIntensiva = $queryHosApp['I_TerapiaIntensiva'];
					$unidadCuidadosIntensivos = $queryHosApp['I_UnidadCuidadosIntensivos'];
					$infectologia = $queryHosApp['I_Infectologia'];
					$laboratorio = $queryHosApp['I_Laboratorio'];
					$urgencias = $queryHosApp['I_Urgencias'];
					$rayosx = $queryHosApp['I_RayosX'];
					$farmacia = $queryHosApp['I_Farmacia'];
					$botiquin = $queryHosApp['I_Botiquin'];
					$endoscopia = $queryHosApp['I_Endoscopia'];
					$consultaExterna = $queryHosApp['I_ConsultaExterna'];
					$cirugiaAmbulatoria = $queryHosApp['I_CirugiaAmbulatoria'];
					$scanner = $queryHosApp['I_Scanner'];
					$ultrasonido = $queryHosApp['I_Ultrasonido'];
					$dialisis = $queryHosApp['I_Dialisis'];
					$hemodialisis = $queryHosApp['I_Hemodialisis'];
					$resonanciaMagnetica = $queryHosApp['I_ResonanciaMagnetica'];
					
				}
		
				if($tipoInst == 2){//farmacia
					$queryFarmApp = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from INST_UD_APPROVAL WHERE P_INST_APPROVAL = '".$idInstApproval."'"));
					
					//$idInst = ['P_INST_SNR'];
					$rotacion = $queryFarmApp['P_RotacionSilanes'];
					$tipoPaciente = $queryFarmApp['P_TipodePacientes'];
					$numEmpleados = $queryFarmApp['P_NumEmp'];
					$numCtesxDia = $queryFarmApp['P_CtesporDia'];
					$nivelVentas = $queryFarmApp['P_NiveldeVentas'];
					$nombreComercialFarmacia = $queryFarmApp['P_NombComerFarm'];
					$accesibilidad = $queryFarmApp['P_AccesFarm'];
					$numCtes = $queryFarmApp['P_NumMostAtiendenCtes'];
					$numMedFarm = $queryFarmApp['P_NumMedAlFarm'];
					$recibeVendedores = $queryFarmApp['P_RecibeVendedores'];
					$ventaGenericos = $queryFarmApp['P_VentasdeGenericos'];
					$mayorista1 = $queryFarmApp['P_Mayorista1'];
					$mayorista2 = $queryFarmApp['P_Mayorista2'];
					$numAnaqueles = $queryFarmApp['P_NumdeAnaquel'];
					$numVisitasXciclo = $queryFarmApp['P_NumVisitasCiclo'];
					$turnos = $queryFarmApp['P_TurnosVisita'];
					$ubicacion = $queryFarmApp['P_UbicFarmacia'];
					$trabajaInstPublica = $queryFarmApp['P_TrabInstPublicas'];
					$tamanoFarmacia = $queryFarmApp['P_TamanoFarmacia'];
					$frecuenciaVisita = $queryFarmApp['P_FrecVis'];
				}
				
				$queryInsertaInst = "insert into inst ( 
					INST_SNR,
					NAME,
					SUBTYPE_SNR,
					INST_TYPE,
					INFO,
					CITY_SNR,
					STREET1,
					TEL1,
					TEL2,
					WEB,
					EMAIL1,
					STATUS_SNR,
					CATEGORY_SNR,
					REC_STAT,
					SYNC,
					CREATION_TIMESTAMP
				) values ( 
					'$idInst',
					'$inst',
					'$subtipo',
					'$tipoInst',
					'$comentarios',
					'$city',
					'$calle',
					'$tel1',
					'$tel2',
					'$web',
					'$email',
					'$estatus',
					'$categoria',
					0,
					0,
					getdate()
				)";
				
				if(! sqlsrv_query($conn, $queryInsertaInst)){
					echo "Error: queryInsertaInst ::: ".$queryInsertaInst."<br><br>";
				}
				
				if($tipoInst == 1){//hospital
					$queryInsertaHospital = "insert into INST_HOSP (
						INST_HOSP_SNR,
						INST_SNR,
						NumCamas,
						NumQuirofanos,
						NumSalasExpulsion,
						NumCunas,
						NumIncubadoras,
						TerapiaIntensiva,
						UnidadCuidadosIntensivos,
						Infectologia,
						Laboratorio,
						Urgencias,
						RayosX,
						Farmacia,
						Botiquin,
						Endoscopia,
						ConsultaExterna,
						CirugiaAmbulatoria,
						Scanner,
						Ultrasonido,
						Dialisis,
						Hemodialisis,
						ResonanciaMagnetica,
						REC_STAT,
						SYNC,
						Datum
					) values (
						NEWID(),
						'".$idInst."',
						'".$numCamas."',
						'".$numQuirofano."',
						'".$numSalasExpulsion."',
						'".$numCunas."',
						'".$numIncubadoras."',
						'".$terapiaIntensiva."',
						'".$unidadCuidadosIntensivos."',
						'".$infectologia."',
						'".$laboratorio."',
						'".$urgencias."',
						'".$rayosx."',
						'".$farmacia."',
						'".$botiquin."',
						'".$endoscopia."',
						'".$consultaExterna."',
						'".$cirugiaAmbulatoria."',
						'".$scanner."',
						'".$ultrasonido."',
						'".$dialisis."',
						'".$hemodialisis."',
						'".$resonanciaMagnetica."',
						0,
						0,
						getdate()
					)";
					
					if(! sqlsrv_query($conn, $queryInsertaHospital)){
						echo "Error: queryInsertaHospital ::: ".$queryInsertaHospital."<br><br>";
					}
				}
				
				if($tipoInst == 2){//farmacia
					$queryInsertaFarm = "insert into inst_ud (
						INST_SNR,
						REC_STAT,
						RotacionSilanes,
						TipodePacientes,
						NumEmp,
						CtesporDia,
						NiveldeVentas,
						NombComerFarm,
						AccesFarm,
						NumMostAtiendenCtes,
						NumMedAlFarm,
						RecibeVendedores,
						VentasdeGenericos,
						Mayorista1,
						Mayorista2,
						NumdeAnaquel,
						NumVisitasCiclo,
						TurnosVisita,
						UbicFarmacia,
						TrabInstPublicas,
						TamanoFarmacia,
						FrecVis
					) values (
						'".$idInst."',
						0,
						'".$rotacion."',
						'".$tipoPaciente."',
						'".$numEmpleados."',
						'".$numCtesxDia."',
						'".$nivelVentas."',
						'".$nombreComercialFarmacia."',
						'".$accesibilidad."',
						'".$numCtes."',
						'".$numMedFarm."',
						'".$recibeVendedores."',
						'".$ventaGenericos."',
						'".$mayorista1."',
						'".$mayorista2."',
						'".$numAnaqueles."',
						'".$numVisitasXciclo."',
						'".$turnos."',
						'".$ubicacion."',
						'".$trabajaInstPublica."',
						'".$tamanoFarmacia."',
						'".$frecuenciaVisita."'
					)";
					if(! sqlsrv_query($conn, $queryInsertaFarm)){
						//echo "Error: queryInsertaFarm ::: ".$queryInsertaFarm."<br><br>";
					}
				}
		
				$queryApprovalStatusInst = "update APPROVAL_STATUS set 
					APPROVED_DATE = getdate(),
					APPROVED_USER_SNR = '$idUserApproved',
					APPROVED_STATUS = 2,
					sync = 0
					where APPROVAL_STATUS_SNR = '$idApprovalStatusInst' ";
					
				if(! sqlsrv_query($conn, $queryApprovalStatusInst)){
					echo "Error: queryApprovalStatus ::: ".$queryApprovalStatusInst."<br><br>";
				}
			}
			/* termina aprobar inst */
		}else if($tipoMovimiento == 'C'){
			/* revisamos si hubo cambio de direccion */
			
			$idPLWOld = sqlsrv_fetch_array(sqlsrv_query($conn, "select PWORK_SNR from PERSLOCWORK where pers_snr = '$pers_snr' and REC_STAT = 0"))['PWORK_SNR'];
			
			//echo "select PWORK_SNR from PERSLOCWORK where pers_snr = '$pers_snr' and REC_STAT = 0<br>";
			
			if($idPLW != $idPLWOld){//cambio de dirección
				$queryCambioPLW = "update PERSLOCWORK set rec_stat = 2, sync = 0 where PWORK_SNR = '".$idPLWOld."'";
				
				$queryInsertaPLW = "insert into PERSLOCWORK( 
					PWORK_SNR, 
					PERS_SNR,	
					INST_SNR,	
					TEL,
					NUM_INT,
					EMAIL,	
					SYNC,
					REC_STAT,
					OFFICE,
					FLOOR,
					TOWER,
					DEPARTMENT
					) values ( 
					'$idPLW',
					'$pers_snr',
					'$idInst',
					'$telPLW',
					'$interior',
					'$mailPLW',
					0,
					0,
					'$consultorio',
					'$piso',
					'$torre',
					'$departamento'
				) ";
				
				$queryCambioPSW = "update PERS_SREP_WORK set rec_stat = 2, sync = 0 where pers_snr = '".$pers_snr."' and REC_STAT = 0";

				$queryPSW = "insert into PERS_SREP_WORK (
					PERSREP_SNR,
					PWORK_SNR,
					USER_SNR,
					PERS_SNR,
					INST_SNR,
					LAST_VISIT,
					NEXT_VISIT,
					KLASS_MT_SNR,
					PLAN_NEXT_VISIT_DATE,
					PLAN_NEXT_VISIT_INFO,
					KLASS2_SNR,
					SYNC,
					REC_STAT
				) values (
					NEWID(),
					'$idPLW',
					'$idUser',
					'$pers_snr',
					'$idInst',
					'',
					'',
					'00000000-0000-0000-0000-000000000000',
					'',
					'',
					'00000000-0000-0000-0000-000000000000',
					0,
					0
				) ";
				
				$queryUT = "select user_snr,inst_snr,UTER_SNR,REC_STAT from user_territ where user_snr = '".$idUser."' and inst_snr = '".$idInst."'";
				$rsUT = sqlsrv_query($conn, $queryUT, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
				$actualizaUT = "";
				if(sqlsrv_num_rows($rsUT) > 0){
					$idUT = sqlsrv_fetch_array($rsUT)['UTER_SNR'];
					if($rsUT['REC_STAT'] != 0){
						$actualizaUT = "update user_territ SET REC_STAT = 0 WHERE UTER_SNR = '$idUT'";
						if(! sqlsrv_query($conn, $actualizaUT)){
							echo "Error: actualizaUT ::: ".$actualizaUT."<br><br>";
						}
					}
				}else{
					$actualizaUT = "insert into user_territ (
							UTER_SNR,
							INST_SNR,
							USER_SNR,
							REC_STAT,
							TERR_CHANG,
							SYNC
						) values (
							NEWID(),
							'$idInst',
							'$idUser',
							0,
							1,
							0
						)";
					if(! sqlsrv_query($conn, $actualizaUT)){
						echo "Error: actualizaUT ::: ".$actualizaUT."<br><br>";
					}
				}
				
				if(! sqlsrv_query($conn, $queryCambioPLW)){
					echo "Error: queryCambioPLW ::: ".$queryCambioPLW."<br><br>";
				}
				if(! sqlsrv_query($conn, $queryInsertaPLW)){
					echo "Error: queryInsertaPLW ::: ".$queryInsertaPLW."<br><br>";
				}
				if(! sqlsrv_query($conn, $queryCambioPSW)){
					echo "Error: queryCambioPSW ::: ".$queryCambioPSW."<br><br>";
				}
				if(! sqlsrv_query($conn, $queryPSW)){
					echo "Error: queryPSW ::: ".$queryPSW."<br><br>";
				}
				
			}else{
				/*$queryPLW = "update PERSLOCWORK set  	
					TEL = '$telPLW',
					FAX = '$interior',
					EMAIL = '$mailPLW',	
					GSM = '$cel',
					SYNC = 0,
					OFFICE = '$consultorio',
					FLOOR = '$piso',
					TOWER = '$torre',
					DEPARTMENT = '$departamento'
					where PWLOC_SNR = '$idPLW'
					and REC_STAT = 0";
					
				if(! sqlsrv_query($conn, $queryPLW)){
					echo "Error: queryPLW ::: ".$queryPLW."<br><br>";
				}*/
			}
			/* revisamos campo por campo cual cambio*/
			
			//$queryAnt = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from person where PERS_SNR = '$pers_snr'"));
			
			$queryCamposChage = "select ac.rcol_snr, rc.name, ac.value_snr, ac.value 
				from APPROVAL_CHANGES ac, CONFIG_FIELD rc
				where ac.RCOL_SNR = rc.COLUMN_NR
				and ac.RTAB_SNR = rc.TABLE_NR
				and APPROVAL_STATUS_snr = '".$idApprovalStatus."'";

			//echo $queryCamposChage."<br>";
				
			$rsCamposChange = sqlsrv_query($conn, $queryCamposChage, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
				
			$queryPersonApproval = "update PERSON set SYNC = 0, CHANGED_TIMESTAMP = getdate() ";
			//echo sqlsrv_num_rows($rsCamposChange)."<br>";
			if(sqlsrv_num_rows($rsCamposChange) == 0){//entro por android
				$qCamposPersonas = "select name as campo 
					from CONFIG_FIELD 
					where TABLE_NR = 19 
					and APPROVAL = 1";
				$rsCamposPersonas = sqlsrv_query($conn, $qCamposPersonas);
				$camposPersonas = '';
				$camposPersonA = '';
				while($campoPersonas = sqlsrv_fetch_array($rsCamposPersonas)){
					$camposPersonas .= 'p.'.strtoupper($campoPersonas['campo']).' as P_'.strtoupper($campoPersonas['campo']).',';
					$camposPersonA .= 'pa.P_'.strtoupper($campoPersonas['campo']).',';
				}
				$camposPersonas = substr($camposPersonas, 0, -1);
				$camposPersonA = substr($camposPersonA, 0, -1);
					
				$qPerson = "SELECT ";
				$qPerson .= $camposPersonas;
				$qPerson .= " FROM person p ";
				$qPerson .= "inner join PERSON_APPROVAL pa on pa.P_PERS_SNR = p.PERS_SNR ";
				$qPerson .= "inner join APPROVAL_STATUS at on at.PERS_APPROVAL_SNR = pa.PERS_APPROVAL_SNR ";
				$qPerson .= "where at.APPROVAL_STATUS_SNR = '".$idApprovalStatus."' ";
					
				//echo "$qPerson <br><br>";
					
				$qPersApproval = "SELECT ";
				$qPersApproval .= $camposPersonA;
				$qPersApproval .= " FROM APPROVAL_STATUS at ";
				$qPersApproval .= "inner join person_approval pa on at.PERS_APPROVAL_SNR = pa.PERS_APPROVAL_SNR ";
				$qPersApproval .= "where at.APPROVAL_STATUS_SNR = '".$idApprovalStatus."' ";
						
				//echo $qPersApproval;
					
				$arrPersApproval = sqlsrv_fetch_array(sqlsrv_query($conn, $qPersApproval));
				$arrPerson = sqlsrv_fetch_array(sqlsrv_query($conn, $qPerson));
				foreach ($arrPersApproval as $clave => $valor){
					if(strlen($clave) > 2){
						if(is_object($arrPersApproval[$clave])){
							foreach ($arrPersApproval[$clave] as $key => $val) {
								if(strtolower($key) == 'date'){
									$fechaApp = substr($val, 0, 10);
								}
							}
							foreach ($arrPerson[$clave] as $key => $val) {
								if(strtolower($key) == 'date'){
									$fechaPers = substr($val, 0, 10);
								}
							}
							if($fechaApp != $fechaPers){
								$queryPersonApproval .= ",".substr($clave, 2)." = '".$fechaApp."' ";
								//echo $clave." ::: ".substr($clave, 2)." ::: ".$valor."<br>";
							}
						}else{
							if(strtoupper($arrPersApproval[$clave]) != strtoupper($arrPerson[$clave])){
								$queryPersonApproval .= ",".substr($clave, 2)." = '".$valor."' ";
								//echo $clave." ::: ".substr($clave, 2)." ::: ".$valor."<br>";
							}
						}
					}
				}
			}else{
			
				while($campo = sqlsrv_fetch_array($rsCamposChange)){
					//print_r($campo);
					//echo "<br><br>";
					if(strtoupper($campo['name']) != 'NAME' && strtoupper($campo['name']) != 'STREET1' && strtoupper($campo['name']) != 'ZIP'){
						if($campo['value_snr'] == '00000000-0000-0000-0000-000000000000'){
							if($campo['value'] == 'null'){
								$queryPersonApproval .= ",".$campo['name']." = ".$campo['value'];
							}else{
								$queryPersonApproval .= ",".$campo['name']." = '".$campo['value']."'";
							}
						}else{
							$queryPersonApproval .= ",".$campo['name']." = '".$campo['value_snr']."'";
						}
					}
				}
			}
			
			$queryPersonApproval .= " where PERS_SNR = '$pers_snr'";
			
			//echo $queryPersonApproval."<br>";
			
			$queryApprovalStatus = "update APPROVAL_STATUS set 
				APPROVED_DATE = getdate(),
				APPROVED_USER_SNR = '$idUserApproved',
				APPROVED_STATUS = 2,
				sync = 0
				where APPROVAL_STATUS_SNR = '$idApprovalStatus' ";
				
		}else if($tipoMovimiento == 'D'){
			
			$queryApprovalStatus = "update APPROVAL_STATUS set 
				APPROVED_DATE = getdate(),
				APPROVED_USER_SNR = '$idUserApproved',
				APPROVED_STATUS = 2,
				sync = 0
				where APPROVAL_STATUS_SNR = '$idApprovalStatus' ";
				
			//echo $queryApprovalStatus."<br>";
			
			$queryPersonas = "update person set rec_stat = 2, sync = 0 where pers_snr = '$pers_snr'";
			
			//echo $queryPersonas."<br>";
			
			if(! sqlsrv_query($conn, $queryApprovalStatus)){
				echo "Error: queryApprovalStatus ::: ".$queryApprovalStatus."<br><br>";
			}
			if(! sqlsrv_query($conn, $queryPersonas)){
				echo "Error: queryPersonas ::: ".$queryPersonas."<br><br>";
			}
		}
		//echo "tm: ".$tipoMovimiento."<br>";
		if($tipoMovimiento != 'D'){
			if(! sqlsrv_query($conn, $queryPersonApproval)){
				echo "Error: queryPersonApproval ::: ".$queryPersonApproval."<br><br>";
			}
			if(! sqlsrv_query($conn, $queryApprovalStatus)){
				echo "Error: queryApprovalStatus ::: ".$queryApprovalStatus."<br><br>";
			}
		}
		echo "<script>
				$(\"#cerrarInformacion\").click();
				$(\"#imgAprobaciones\").click();
			</script>";	
	}
?>