<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		//echo "holaaaaaaaaaaaaa<br>";
		$motivo = $_POST['motivo'];
		$tabla = $_POST['tabla'];
		$idUsuario = $_POST['idUsuario'];
		
		$queryApprovalStatus = "update APPROVAL_STATUS set 
			APPROVED_DATE = getdate(),
			APPROVED_USER_SNR = '$idUsuario',
			APPROVED_STATUS = 3,
			REJECT_REASON_SNR = '$motivo', 
			SYNC = 0 ";
		
		if($tabla == 'personas'){
			$idPersApproval = $_POST['idPersApproval'];
			$queryApprovalStatus .= "where PERS_APPROVAL_SNR = '$idPersApproval' ";
			/*revisar si es inst nueva*/
			$idInst = sqlsrv_fetch_array(sqlsrv_query($conn, "select PWORK_SNR from APPROVAL_STATUS where PERS_APPROVAL_SNR = '".$idPersApproval."'"))['PWORK_SNR'];
			$rsExisteInst = sqlsrv_query($conn, "select * from inst where inst_snr = '".$idInst."'", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			if(sqlsrv_num_rows($rsExisteInst) == 0){ //es nueva inst
				$queryApprovalStatusInst = "update APPROVAL_STATUS set 
					APPROVED_DATE = getdate(),
					APPROVED_USER_SNR = '$idUsuario',
					APPROVED_STATUS = 3,
					SYNC = 0, 
					REJECT_REASON_SNR = '$motivo' where record_key = '".$idInst."'";
				if(! sqlsrv_query($conn, $queryApprovalStatusInst)){
					echo "queryApprovalStatusInst: ".$queryApprovalStatusInst."<br>";
				}
				//echo $queryApprovalStatusInst."<br>";
			}
			/*termina revisar inst*/
		}else if($tabla == 'inst'){
			$idInstApproval = $_POST['idInstApproval'];
			$queryApprovalStatus .= "where INST_APPROVAL_SNR = '$idInstApproval' ";
		}
		
		//echo $queryApprovalStatus."<br>";
		
		if(! sqlsrv_query($conn, $queryApprovalStatus)){
			echo "Error: queryApprovalStatus ::: ".$queryApprovalStatus."<br><br>";
		}
		
		echo "<script>
			$('#sltMotivoRechazo').val('00000000-0000-0000-0000-000000000000');
			//$('#imgAprobaciones').click();
			</script>";
	}
	
?>