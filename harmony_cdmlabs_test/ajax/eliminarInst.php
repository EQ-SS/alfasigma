<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		
		$idInst = $_POST['idInst'];
		$idUsuario = $_POST['idUsuario'];
		$tipoUsuario = $_POST['tipoUsuario'];
		
		if($tipoUsuario == 4){//es repre
			$motivo = $_POST['motivo'];
			$comentarios = $_POST['comentarios'];
			
			/* checa si no existe el registro */
			$queryValida = "select * 
				from INST_APPROVAL pa, APPROVAL_STATUS at 
				where pa.I_INST_SNR = '".$idInst."' 
				and I_MOVEMENT_TYPE = 'D'
				and at.CHANGE_USER_SNR = '".$idUsuario."'
				and at.APPROVED_STATUS = 1 ";
			//echo $queryValida;
			$rsValida = sqlsrv_query($conn, $queryValida , array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if(sqlsrv_num_rows($rsValida) > 0){
				echo "<script>alert('Movimiento existente!!!');</script>";
				return;
			}
			/*********************************/
			
			$queryInst = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from inst where inst_snr = '$idInst'"));
			
			$nr = sqlsrv_fetch_array(sqlsrv_query($conn, "select max(i_nr) as maximo from INST_APPROVAL"))['maximo']+1;
			
			$idInstApproval = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idInstApproval from INST_APPROVAL where inst_approval_snr = '00000000-0000-0000-0000-000000000000'"))['idInstApproval'];
			
			$queryInstApp = "insert into INST_APPROVAL (
				INST_APPROVAL_SNR,
				REC_STAT,
				I_INST_SNR,
				I_NAME,
				I_NR,
				I_INST_TYPE,
				I_INFO,
				I_CITY_SNR,
				I_STREET1,
				I_TEL1,
				I_TEL2,
				I_WEB,
				I_EMAIL1,
				I_STATUS_SNR,
				I_CATEGORY_SNR,
				I_MOVEMENT_TYPE,
				SYNC,
				CREATION_TIMESTAMP,
				I_DEL_REASON,
				I_DEL_STATUS_SNR,
				I_TYPE_SNR,
				I_SUBTYPE_SNR,
				I_FORMAT_SNR
			) values (
				'$idInstApproval',
				0,
				'".$idInst."',
				'".$queryInst['NAME']."',
				'".$nr."',
				'".$queryInst['INST_TYPE']."',
				'".$queryInst['INFO']."',
				'".$queryInst['CITY_SNR']."',
				'".$queryInst['STREET1']."',
				'".$queryInst['TEL1']."',
				'".$queryInst['TEL2']."',
				'".$queryInst['WEB']."',
				'".$queryInst['EMAIL1']."',
				'".$queryInst['STATUS_SNR']."',
				'".$queryInst['CATEGORY_SNR']."',
				'D',
				0,
				getdate(),
				'$comentarios',
				'$motivo',
				'".$queryInst['TYPE_SNR']."',
				'".$queryInst['SUBTYPE_SNR']."',
				'".$queryInst['FORMAT_SNR']."'
			)";

			if(! sqlsrv_query($conn, $queryInstApp)){
				echo "Error: queryInstApp ::: ".$queryInstApp."<br><br>";
			}
			
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
				492,
				'$idInst',
				getdate(),
				'$idUsuario',
				NULL,
				NULL,
				1,
				0,
				'00000000-0000-0000-0000-000000000000',
				'00000000-0000-0000-0000-000000000000',
				'$idInstApproval',
				'00000000-0000-0000-0000-000000000000',
				'00000000-0000-0000-0000-000000000000',
				'00000000-0000-0000-0000-000000000000',
				0,
				0)";
				
			if(! sqlsrv_query($conn, $queryApprovalStatus)){
				echo "Error: queryApprovalStatus ::: ".$queryApprovalStatus."<br><br>";
			}

			echo "<script>alertEliminarInstRepre();</script>";
		}else{
			$qEliminaInst = "update INST set rec_stat = 2, sync = 0 where inst_snr = '".$idInst."' ";
			if(! sqlsrv_query($conn, $qEliminaInst)){
				echo "qEliminaInst: ".$qEliminaInst."<br>";
			}
			echo "<script>$('#btnActualizarInst').click();</script>";
		}
	}
	
?>