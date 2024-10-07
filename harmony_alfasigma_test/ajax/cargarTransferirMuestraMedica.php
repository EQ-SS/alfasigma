<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$idStockLote = $_POST['idStockLote'];
		$total = $_POST['total'];
		$ids = $_POST['ids'];
		
		$qUsuario = "select u.user_snr, u.LNAME + ' ' + u.FNAME as repre,
			p.NAME as producto,
			pf.NAME as presentacion,
			pfb.NAME as lote
			from STOCK_PRODFORM_USER spu
			inner join users u on u.USER_SNR = spu.USER_SNR
			inner join PRODFORMBATCH pfb on pfb.PRODFBATCH_SNR = spu.PRODFBATCH_SNR
			inner join PRODFORM pf on pf.PRODFORM_SNR = pfb.PRODFORM_SNR
			inner join PRODUCT p on p.PROD_SNR = pf.PROD_SNR
			where spu.STPRODF_USER_SNR = '".$idStockLote."'";
			
		//echo $qUsuario;
			
		$registro = sqlsrv_fetch_array(sqlsrv_query($conn, $qUsuario));
		
		echo "<script>
				$('#hdnPiezasDisponibles').val('".$total."');
				$('#hdnIdRepreTransferirMuestraMedica').val('".$registro['user_snr']."');
				$('#hdnIdStockransferirMuestraMedica').val('".$idStockLote."');
				$('#lblRepreTransferirMuestraMedica').text('".$registro['repre']."');
				$('#lblPiezasTransferirMuestraMedica').text('Piezas disponibles: ".$total."');
				$('#lblProductoTransferirMuestraMedica').text('".$registro['producto']."');
				$('#lblPresentaciónTransferirMuestraMedica').text('".$registro['presentacion']."');
				$('#lblLoteTransferirMuestraMedica').text('".$registro['lote']."');
				$('#txtBuscarRepreTransferirMuestraMedica').val('');
				$('#txtCantidadTransferir').val('');
				$('#lblRestanteMuestraMedica').text('0');
				$('#tblRepresentantesMuestraMedica tbody').empty();";
		
		$qRepres = "select top 20 user_snr, 
			lname + ' ' + mothers_lname + ' ' + fname as nombre
			from users 
			where user_type = 4
			and user_snr in ('".$ids."') ";
		if($registro['user_snr'] != ''){
			$qRepres .= "and user_snr <> '".$registro['user_snr']."' ";
		}
		$qRepres .= "order by lname, mothers_lname, fname";
		
		$rsRepres = sqlsrv_query($conn, $qRepres);
		while($repre = sqlsrv_fetch_array($rsRepres)){
			$registro = "<input class=\"with-gap radio-col-red\" type=\"radio\" id=\"".$repre['user_snr']."\" name=\"optRepres\" value=\"".$repre['user_snr']."\">";
			$registro .= "<label for=\"".$repre['user_snr']."\">".$repre['nombre']."</label>";
			echo "$('#tblRepresentantesMuestraMedica tbody').append('<tr><td>".$registro."</td></tr>');";
		}
		echo "</script>";
	}
	
?>