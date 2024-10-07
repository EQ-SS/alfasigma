<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		$idPersona = $_POST['idPersona'];
		/*$motivoReactivacion = $_POST['motivoReactivacion'];*/
		
		$q = "select * 
			from APPROVAL_STATUS
			where APPROVED_STATUS = '2'
			and MOVEMENT_TYPE = 'D'
			and TABLE_NR = 456
			and RECORD_KEY = '".$idPersona."'";
		
		$idPersonApproval = sqlsrv_fetch_array(sqlsrv_query($conn, $q))['PERS_APPROVAL_SNR'];
		
		$actualiza = "update person_approval set 
			sync = 0, 
			changed_timestamp = getdate(), 
			plw_del_status_snr = '00000000-0000-0000-0000-000000000000' 
			where pers_approval_snr = '".$idPersonApproval."' ";
		
		/*$actualizaPerson = "update person set 
			rec_stat = 0, 
			sync = 0, 
			reactivate_snr = '".$motivoReactivacion."',
			pers_approval_snr = '".$idPersonApproval."'
			where pers_snr = '".$idPersona."'"; */
			
		$actualizaPerson = "update person set 
			rec_stat = 0, 
			sync = 0, 
			pers_approval_snr = '".$idPersonApproval."'
			where pers_snr = '".$idPersona."'";
		
		if(! sqlsrv_query($conn, $actualizaPerson)){
			echo $actualizaPerson;
		}
		
		echo "<script>";
		if(sqlsrv_query($conn, $actualiza)){
			echo "notificationMedActivado();
				$('#btnActualizarPers').click();
				$('#divPersona').hide();
				$('#divCapa3').hide();
			";
		}else{
			echo "alertErrorReactivar();";
		}
		echo "</script>";
		//echo $actualiza."<br>";
	}
	
?>