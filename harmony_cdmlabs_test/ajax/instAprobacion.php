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
		$frecuenciaVisita = $arrInstApproval['I_FRECVIS_SNR'];
		
		if($tipoMovimiento == 'N'){
			$queryInsertaInst = "insert into inst ( 
				INST_SNR,
				FRECVIS_SNR,
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
				'$frecuenciaVisita',
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
				echo "Error: queryInsertaInst ::: ".$queryInsertaInst."<br><br>";
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
			
			
			
		}else if($tipoMovimiento == 'C'){
				
			/*$queryCamposChage = "select ac.rcol_snr, rc.name, ac.value_snr, ac.value 
				from APPROVAL_CHANGES ac, REP_COLUMNS_IOS rc
				where ac.RCOL_SNR = rc.RCOL_SNR
				and APPROVAL_STATUS_snr = '".$idApprovalStatus."'";*/
				
			$queryCamposChage = "select ac.rcol_snr, rc.name, ac.value_snr, ac.value 
				from APPROVAL_CHANGES ac, CONFIG_FIELD rc
				where ac.RCOL_SNR = rc.COLUMN_NR
				and ac.RTAB_SNR = rc.TABLE_NR
				and APPROVAL_STATUS_snr = '".$idApprovalStatus."'";
				
			$rsCamposChange = sqlsrv_query($conn, $queryCamposChage, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
				
			$queryInsertaInst = "update INST set SYNC = 0, CHANGED_TIMESTAMP = getdate() ";
			
			if(sqlsrv_num_rows($rsCamposChange) == 0){//entro por android/IOS
				$qInst = "select inst_type as i_inst_type, 
					type_snr as i_type_snr, 
					subtype_snr as i_subtype_snr,
					format_snr as i_format_snr, 
					STATUS_SNR as i_status_snr, 
					name as i_name, 
					STREET1 as i_street1, 
					NUM_EXT as i_num_ext, 
					city_snr as i_city_snr 
					from inst i
					inner join INST_APPROVAL pa on pa.I_INST_SNR = i.INST_SNR
					inner join APPROVAL_STATUS at on at.INST_APPROVAL_SNR = pa.INST_APPROVAL_SNR
					where at.APPROVAL_STATUS_SNR = '".$idApprovalStatus."' ";
					
				$qInstA = "select ia.i_inst_type, 
					ia.i_type_snr, 
					ia.i_subtype_snr,
					ia.i_format_snr, 
					ia.i_status_snr, 
					ia.i_name, 
					ia.i_street1, 
					ia.i_num_ext,
					ia.i_city_snr
					from inst_approval ia
					inner join APPROVAL_STATUS at on at.INST_APPROVAL_SNR = ia.INST_APPROVAL_SNR
					where at.APPROVAL_STATUS_SNR = '".$idApprovalStatus."'";
				
				$arrInst = sqlsrv_fetch_array(sqlsrv_query($conn, $qInst));
				$arrInstA = sqlsrv_fetch_array(sqlsrv_query($conn, $qInstA));
				
				foreach ($arrInstA as $clave => $valor){
					if(strlen($clave) > 2){
						if(strtoupper($arrInstA[$clave]) != strtoupper($arrInst[$clave])){
							$queryInsertaInst .= ",".substr($clave, 2)." = '".$valor."' ";
							//echo $clave." ::: ".substr($clave, 2)." ::: ".$valor."<br>";
						}
					}
				}
			}else{
				while($campo = sqlsrv_fetch_array($rsCamposChange)){
					if($campo['value_snr'] == '00000000-0000-0000-0000-000000000000'){
						$queryInsertaInst .= ",".$campo['name']." = '".$campo['value']."'";
					}else{
						$queryInsertaInst .= ",".$campo['name']." = '".$campo['value_snr']."'";
					}
				}
			}
			$queryInsertaInst .= " where INST_SNR = '".$idInst."'";
				
			if(! sqlsrv_query($conn, $queryInsertaInst)){
				echo "Error: queryInsertaInst ::: ".$queryInsertaInst."<br><br>";
			}
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