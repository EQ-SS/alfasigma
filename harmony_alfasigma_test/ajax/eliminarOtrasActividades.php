<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$id = $_POST['id'];
		$atualiza = "update DAY_REPORT set sync = 0, rec_stat = 2 where DAYREPORT_SNR = '".$id."'";
		$actualizaDRC = "update DAY_REPORT_CODE set sync = 0, rec_stat = 2 where DAYREPORT_SNR = '".$id."'";
		echo "<script>";
		if(sqlsrv_query($conn, $atualiza) && sqlsrv_query($conn, $actualizaDRC)){
			echo "alert('Registro eliminado');
				actualizaCalendario();
				$('#divReportarOtrasActividades').hide();
				$('#fade').hide();
				$('#over2').hide();
			";
		}else{
			echo "alert('El registro no se elimino');";
		}
		echo "</script>";
	}
	
?>