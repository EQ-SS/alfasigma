<?php
	include "../conexion.php";
	//echo "pantalla".$_POST['pantalla'];
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$idProdForm = $_POST['idProdForm'];
		$cantidad = $_POST['cantidad'];
		$catidadAceptada = $_POST['catidadAceptada'];
		$motivo = $_POST['motivo'];
		$movimiento = $_POST['movimiento'];
		
		if($movimiento == 'noRecibido'){
			$query = "update STOCK_PRODFORM_USER set 
				QUANTITY = '$catidadAceptada',
				QUANTITY_CHANGE = '$cantidad',
				REASON_SNR = '$motivo',
				ACCEPTED = 1,
				SYNC = 0 
				where STPRODF_USER_SNR = '$idProdForm'";
		}else if($movimiento == 'aceptado'){
			$query = "update STOCK_PRODFORM_USER set 
				ACCEPTED = 1,
				SYNC = 0 
				where STPRODF_USER_SNR = '$idProdForm'";
		}else if($movimiento == 'rechazado'){
			$query = "update STOCK_PRODFORM_USER set 
				ACCEPTED = 2,
				REASON_SNR = '$motivo',
				SYNC = 0 
				where STPRODF_USER_SNR = '$idProdForm'";
		}
		if(sqlsrv_query($conn, $query)){
			echo "<script>";
			if($movimiento == 'noRecibido'){
				echo '$("#divAjusteMuestra").hide();
					$("#divCapa3").hide();';
			}
			echo "$('#btnPendienteAprobacion').click();
			$('#cerrarInformacion').click();
			</script>";
		}else{
			//echo $query;
			echo "<script>alertErrorGuardarRegistro();</script>";
		}
		//echo $query;
	}
	
?>