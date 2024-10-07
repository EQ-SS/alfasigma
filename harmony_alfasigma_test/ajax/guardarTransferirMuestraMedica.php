<?php
	include "../conexion.php";
	//echo "pantalla".$_POST['pantalla'];
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		
		$idUser = $_POST['idUser'];
		$repreTransferir = $_POST['repreTransferir'];
		$repreTransferido = $_POST['repreTransferido'];
		$cantidad = $_POST['cantidad'];
		$idStock = $_POST['idStock'];
		
		$qSPU = "select prodfbatch_snr, cycle_snr 
			from STOCK_PRODFORM_USER 
			where STPRODF_USER_SNR = '".$idStock."'";
			
		$rsSPU = sqlsrv_query($conn, $qSPU);
		
		$arrSPU = sqlsrv_fetch_array($rsSPU);
		
		$qStockNuevo = "select newid() as idStock from STOCK_PRODFORM_USER where STPRODF_USER_SNR = '00000000-0000-0000-0000-000000000000'";
		
		$idStockNuevo = sqlsrv_fetch_array(sqlsrv_query($conn, $qStockNuevo))['idStock'];
		
		$qInsertaDescuento = "insert into STOCK_PRODFORM_USER(
				STPRODF_USER_SNR,
				ENTRYDATE,
				USER_SNR,
				QUANTITY,
				REC_STAT,
				PRODFBATCH_SNR,
				CYCLE_SNR,
				ACCEPTED,
				USER_SNR_CHANGE,
				INFO_CHANGE,
				SPUA_SNR,
				TABLE_NR,
				SYNC
			) values(
				NEWID(),
				getdate(),
				'".$repreTransferir."',
				'-".$cantidad."',
				0,
				'".$arrSPU['prodfbatch_snr']."',
				'".$arrSPU['cycle_snr']."',
				1,
				'".$idUser."',
				'DESCUENTO POR TRANSFERENCIA',
				'".$idStockNuevo."',
				374,
				0
			)";
			
		$qInsertaTransferencia = "insert into STOCK_PRODFORM_USER(
				STPRODF_USER_SNR,
				ENTRYDATE,
				USER_SNR,
				QUANTITY,
				REC_STAT,
				PRODFBATCH_SNR,
				CYCLE_SNR,
				ACCEPTED,
				USER_SNR_CHANGE,
				INFO_CHANGE,
				SPUA_SNR,
				TABLE_NR,
				SYNC
			) values(
				'".$idStockNuevo."',
				getdate(),
				'".$repreTransferido."',
				'".$cantidad."',
				0,
				'".$arrSPU['prodfbatch_snr']."',
				'".$arrSPU['cycle_snr']."',
				0,
				'".$idUser."',
				'TRANSFERIDO',
				'".$idStock."',
				374,
				0
			)";
		
		if(! sqlsrv_query($conn, $qInsertaDescuento)){
			echo "Descuento: ".$qInsertaDescuento;
		}
		
		if(! sqlsrv_query($conn, $qInsertaTransferencia)){
			echo "Descuento: ".$qInsertaTransferencia;
		}
		
		echo "<script>
			$('#btnCerrarTransferirMuestraMedica').click();
			//$('#imgInventario').click();
			$('#btnEjecutarFiltroInv').click();
		</script>";
		
		
	}
	
?>