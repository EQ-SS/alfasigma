<?php
	include "../conexion.php";
	
	$idProdForm = $_POST['idProdForm'];
	$fecha = '';
	
	$query = "SELECT SPU.STPRODF_USER_SNR, SPU.ENTRYDATE AS FECHA,PROD.NAME AS PRODUCTO,PF.NAME PRES,LOTE.BNAME AS LOTE,SPU.QUANTITY AS CANTIDAD 
		FROM STOCK_PRODFORM_USER SPU, PRODFORM PF, PRODUCT PROD, PRODFORMBATCH LOTE 
		WHERE SPU.PRODFORM_SNR=PF.PRODFORM_SNR 
		AND PF.PROD_SNR=PROD.PROD_SNR 
		AND SPU.REC_STAT=0  
		AND SPU.TABLE_NR=374 AND SPU.ACCEPTED=0 
		AND SPU.STPRODF_USER_SNR='".$idProdForm."' 
		AND LOTE.PRODFBATCH_SNR=SPU.PRODFBATCH_SNR ";
		
	$arrProdForm = sqlsrv_fetch_array(sqlsrv_query($conn, $query));
	
	//foreach ($arrProdForm['FECHA'] as $key => $val) {
	foreach ($arrProdForm['FECHA'] ? $arrProdForm['FECHA'] : [] as $key => $val) {
		if(strtolower($key) == 'date'){
			$fecha = substr($val, 0, 10);
		}
	}
	$producto = $arrProdForm['PRODUCTO'];
	$presentacion = $arrProdForm['PRES'];
	$lote = $arrProdForm['LOTE'];
	$cantidad = $arrProdForm['CANTIDAD'];

	echo "<script>
		$('#hdnIdProdFormAjuste').val('".$idProdForm."');
		$('#hdnCantidadAjuste').val('".$cantidad."');
		$('#lblProducto').html('Fecha: ".$fecha."<br/>Producto: ".$presentacion."<br/>Cantidad Enviada: ".$cantidad."');
		$('#txtCantidadRecibida').val('');
		$('#sltMotivo').val('00000000-0000-0000-0000-000000000000');
		</script>";
?>