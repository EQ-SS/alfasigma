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

		$rsPersonA = sqlsrv_query($conn, $q, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

		if(sqlsrv_num_rows($rsPersonA) > 0){//existe en person approval
			$idPersonApproval = sqlsrv_fetch_array($rsPersonA)['PERS_APPROVAL_SNR'];
			$actualiza = "update person_approval set 
				sync = 0, 
				changed_timestamp = getdate(), 
				plw_del_status_snr = '00000000-0000-0000-0000-000000000000' 
				where pers_approval_snr = '".$idPersonApproval."' ";

			if(! sqlsrv_query($conn, $actualiza)){
				echo "no se grabo actualiza: ".$actualiza;
			}
		}
		
		/* guarda el historial de cambios */

		$qInsertaHistorial = "INSERT INTO PERSON_HISTORY
			SELECT *,getdate() FROM PERSON WHERE PERS_SNR = '$idPersona'";

		if(! sqlsrv_query($conn, $qInsertaHistorial)){
			echo "No se guardo el historial: ".$qInsertaHistorial;
		}
			
		$actualizaPerson = "update person set 
			rec_stat = 0, 
			sync = 0,
			changed_timestamp = getdate()
			where pers_snr = '".$idPersona."'";
		
		echo "<script>";
		if(sqlsrv_query($conn, $actualizaPerson)){
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