<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		$idMensaje = $_POST['idMensaje'];
		$tabMsj = $_POST['tabMsj'];
		
		$actualiza = "update USER_MAILING_DESTINATION 
			set sync = 0, 
			rec_stat = 2 
			where USER_MAILING_DESTINATION_SNR = '".$idMensaje."'";
			
		echo "<script>";
		if(! sqlsrv_query($conn, $actualiza)){
			echo "alertEliminarMensajeError();";
			//echo "actualiza: ".$actualiza
		}else{
			echo "alertEliminarMensajeOk();";
			if($tabMsj == 1){//entrada
				echo "$('#tabEntradaCabecera').click();";
			}else if($tabMsj == 2){//todos
				echo "$('#tabTodosCabecera').click();";
			}else if($tabMsj == 3){//enviados
				echo "$('#tabEnviadosCabecera').click();";
			}
		}
		echo "</script>";
	}
	
?>