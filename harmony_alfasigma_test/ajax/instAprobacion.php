<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		
		$idInstApproval = $_POST['idInstApproval'];
		$idUserApproved = $_POST['idUser'];
		
		$queryInstApproval = "select * from INST_APPROVAL where INST_APPROVAL_SNR = '".$idInstApproval."'";
		//echo $queryInstApproval."<br><br>";
		$arrInstApproval = sqlsrv_fetch_array(sqlsrv_query($conn, $queryInstApproval));
		
		$arrApprovalStatus = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from APPROVAL_STATUS where INST_APPROVAL_SNR = '".$idInstApproval."'"));
		
		$idApprovalStatus = $arrApprovalStatus['APPROVAL_STATUS_SNR'];
		$idUser = $arrApprovalStatus['CHANGE_USER_SNR'];
		$idInst = $arrApprovalStatus['RECORD_KEY'];
		
		$tipoMovimiento = $arrInstApproval['I_MOVEMENT_TYPE'];
		
		$idInstApproval = $arrInstApproval['INST_APPROVAL_SNR'];
		
		//$idInst = $arrInstApproval['I_INST_SNR'];
		$inst = $arrInstApproval['I_NAME'];
		$tipo = $arrInstApproval['I_TYPE_SNR'];
		$subtipo = $arrInstApproval['I_SUBTYPE_SNR'];
		$formato = $arrInstApproval['I_FORMAT_SNR'];
		$tipoInst = $arrInstApproval['I_INST_TYPE'];
		$comentarios = $arrInstApproval['I_INFO'];
		$city = $arrInstApproval['I_CITY_SNR'];
		$calle = $arrInstApproval['I_STREET1'];
		$num_ext = $arrInstApproval['I_NUM_EXT'];
		$tel1 = $arrInstApproval['I_TEL1'];
		$tel2 = $arrInstApproval['I_TEL2'];
		$web = $arrInstApproval['I_WEB'];
		$email = $arrInstApproval['I_EMAIL1'];
		$estatus = $arrInstApproval['I_STATUS_SNR'];
		$categoria = $arrInstApproval['I_CATEGORY_SNR'];
		
		/*if($tipoInst == 1){//hospital
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
		}*/
		
		if($tipoMovimiento == 'N'){
			$queryInsertaInst = "insert into inst ( 
				INST_SNR,
				NAME,
				TYPE_SNR,
				SUBTYPE_SNR,
				FORMAT_SNR,
				INST_TYPE,
				INFO,
				CITY_SNR,
				STREET1,
				NUM_EXT,
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
				'$tipo',
				'$subtipo',
				'$formato',
				'$tipoInst',
				'$comentarios',
				'$city',
				'$calle',				
				'$num_ext',
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
			
			//echo $queryInsertaInst;
			
			if(! sqlsrv_query($conn, $queryInsertaInst)){
				//print_r(sqlsrv_errors());
				//echo "Error: queryInsertaInst ::: ".$queryInsertaInst."<br><br>";
			}
			
			//revisa user territ
			$queryUT = "select * from user_territ where user_snr = '".$idUser."' and inst_snr = '".$idInst."'";
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
						SYNC
					) values (
						NEWID(),
						'$idInst',
						'$idUser',
						0,
						0
					)";
				if(! sqlsrv_query($conn, $actualizaUT)){
					echo "Error: actualizaUT ::: ".$actualizaUT."<br><br>";
				}
			}
			
			/*if($tipoInst == 1){//hospital
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
			}*/
			
			/*if($tipoInst == 2){//farmacia
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
					echo "Error: queryInsertaFarm ::: ".$queryInsertaFarm."<br><br>";
				}
			}*/
			
			
			
		}else if($tipoMovimiento == 'C'){
			/*$queryInsertaInst = "update inst set  
				NAME = '$inst',
				MM_NAME1 = '$subtipo',
				MM_NAME2 = '$representantesUnidos',
				INST_TYPE = '$tipoInst',
				INFO = '$comentarios',
				CITY_SNR = '$city',
				STREET1 = '$calle',
				STREET2 = '$sucursal',
				TEL1 = '$tel1',
				TEL2 = '$tel2',
				FAX = '$celular',
				HTTP = '$web',
				EMAIL = '$email',
				STATUS_SNR = '$estatus',
				KLASS_SNR = '$categoria',
				SYNC = 0
				where INST_SNR = '$idInst'";*/
				
			$queryCamposChage = "select ac.rcol_snr, rc.name, ac.value_snr, ac.value 
				from APPROVAL_CHANGES ac, CONFIG_FIELD rc
				where ac.RCOL_SNR = rc.COLUMN_NR
				and ac.RTAB_SNR = rc.TABLE_NR
				and APPROVAL_STATUS_snr = '".$idApprovalStatus."'";
				
			$rsCamposChange = sqlsrv_query($conn, $queryCamposChage);
				
			$queryInsertaInst = "update INST set SYNC = 0, CHANGED_TIMESTAMP = getdate() ";
			
			while($campo = sqlsrv_fetch_array($rsCamposChange)){
				if($campo['value_snr'] == '00000000-0000-0000-0000-000000000000'){
					$queryInsertaInst .= ",".$campo['name']." = '".$campo['value']."'";
				}else{
					$queryInsertaInst .= ",".$campo['name']." = '".$campo['value_snr']."'";
				}
			}
			
			$queryInsertaInst .= " where INST_SNR = '".$idInst."'";
				
			if(! sqlsrv_query($conn, $queryInsertaInst)){
				echo "Error: queryInsertaInst ::: ".$queryInsertaInst."<br><br>";
			}
			
			/*if($tipoInst == 1){//hospital
			// verificamos que la farmacia existe en la tabal de perfil del hospital
				$rsPerfilHosp = sqlsrv_query($conn, "select * from INST_HOSP where inst_snr = '".$idInst."'", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
				if(sqlsrv_num_rows($rsPerfilHosp) > 0){
					$queryActualizaHospital = "update INST_HOSP set
						NumCamas = '".$numCamas."',
						NumQuirofanos = '".$numQuirofano."',
						NumSalasExpulsion = '".$numSalasExpulsion."',
						NumCunas = '".$numCunas."',
						NumIncubadoras = '".$numIncubadoras."',
						TerapiaIntensiva = '".$terapiaIntensiva."',
						UnidadCuidadosIntensivos = '".$unidadCuidadosIntensivos."',
						Infectologia = '".$infectologia."',
						Laboratorio = '".$laboratorio."',
						Urgencias = '".$urgencias."',
						RayosX = '".$rayosx."',
						Farmacia = '".$farmacia."',
						Botiquin = '".$botiquin."',
						Endoscopia = '".$endoscopia."',
						ConsultaExterna = '".$consultaExterna."',
						CirugiaAmbulatoria = '".$cirugiaAmbulatoria."',
						Scanner = '".$scanner."',
						Ultrasonido = '".$ultrasonido."',
						Dialisis = '".$dialisis."',
						Hemodialisis = '".$hemodialisis."',
						ResonanciaMagnetica = '".$resonanciaMagnetica."',
						SYNC = 0
						where INST_SNR = '".$idInst."'";
				}else{
					$queryActualizaHospital = "insert into INST_HOSP (
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
				}
				
				
				if(! sqlsrv_query($conn, $queryActualizaHospital)){
					echo "Error: queryActualizaHospital ::: ".$queryActualizaHospital."<br><br>";
				}
				
			}*/
			
			/*if($tipoInst == 2){//farmacia
				 verificamos que la farmacia existe en la tabal de perfil de farmacia
				$rsPerfilFarma = sqlsrv_query($conn, "select * from inst_ud where inst_snr = '".$idInst."'", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
				if(sqlsrv_num_rows($rsPerfilFarma) > 0){
					$queryActualizaFarm = "update inst_ud set
						RotacionSilanes = '".$rotacion."',
						TipodePacientes = '".$tipoPaciente."',
						NumEmp = '".$numEmpleados."',
						CtesporDia = '".$numCtesxDia."',
						NiveldeVentas = '".$nivelVentas."',
						NombComerFarm = '".$nombreComercialFarmacia."',
						AccesFarm = '".$accesibilidad."',
						NumMostAtiendenCtes = '".$numCtes."',
						NumMedAlFarm = '".$numMedFarm."',
						RecibeVendedores = '".$recibeVendedores."',
						VentasdeGenericos = '".$ventaGenericos."',
						Mayorista1 = '".$mayorista1."',
						Mayorista2 = '".$mayorista2."',
						NumdeAnaquel = '".$numAnaqueles."',
						NumVisitasCiclo = '".$numVisitasXciclo."',
						TurnosVisita = '".$turnos."',
						UbicFarmacia = '".$ubicacion."',
						TrabInstPublicas = '".$trabajaInstPublica."',
						TamanoFarmacia = '".$tamanoFarmacia."',
						FrecVis = '".$frecuenciaVisita."'
						where INST_SNR = '".$idInst."'";
				}else{
					$queryActualizaFarm = "insert into inst_ud (
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
				}
				
				if(! sqlsrv_query($conn, $queryActualizaFarm)){
					echo "Error: queryActualizaFarm ::: ".$queryActualizaFarm."<br><br>";
				}
			}*/
		}else if($tipoMovimiento == 'D'){
			$queryDelInst = "update inst set rec_stat = 2, sync = 0 where inst_snr = '$idInst'";
			//echo $queryDelInst."<br><br>";
			if(! sqlsrv_query($conn, $queryDelInst)){
				echo "Error: queryDelInst ::: ".$queryDelInst."<br><br>";
			}
		}
		
		$queryApprovalStatus = "update APPROVAL_STATUS set 
			APPROVED_DATE = getdate(),
			APPROVED_USER_SNR = '$idUserApproved',
			APPROVED_STATUS = 2,
			sync = 0
			where APPROVAL_STATUS_SNR = '$idApprovalStatus' ";
			
		if(! sqlsrv_query($conn, $queryApprovalStatus)){
			echo "Error: queryApprovalStatus ::: ".$queryApprovalStatus."<br><br>";
		}
		
		echo "<script>
			$(\"#cerrarInformacion\").click();
			$(\"#imgAprobaciones\").click();
			</script>";
		
	}
?>