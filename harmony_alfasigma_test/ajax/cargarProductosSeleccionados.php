<?php
include "../conexion.php";
$funcion = $_POST['funcion'];
if($funcion == 1){
	$idProducto = $_POST['idProducto'];
	$queryProductos = "select pf.name as presentacion, pf.prodform_snr, p.name as producto
        from PRODFORM pf, product p 
        where pf.REC_STAT = 0 
        and pf.prod_tip = 132 
        and pf.prod_snr =  '" . $idProducto . "' 
        and pf.finbonus = 1 
        and p.PROD_SNR = pf.PROD_SNR 
        order by pf.name ";
		
	$rs = sqlsrv_query($conn, $queryProductos);
	echo "<script>
		$('#tblProductoSeleccionado tbody').empty();";
	$i=0;
	while($producto = sqlsrv_fetch_array($rs)){
		$i++;
		echo "$('#tblProductoSeleccionado tbody').append('<tr><td width=\"300px\" ><input type=\"hidden\" id=\"hdnIdProdForm".$i."\" value=\"".$producto['prodform_snr']."\"/>".$producto['producto']." ".$producto['presentacion']."</td><td width=\"10%\" align=\"center\"><input type=\"text\" id=\"txtExistenciaStock".$i."\" size=\"4\" value=\"0\" style=\"text-align:right;\"/></td><td width=\"10%\" align=\"center\"><input type=\"text\" id=\"txtPrecioStock".$i."\" size=\"4\" value=\"0\" style=\"text-align:right;\"/></td><td width=\"10%\" align=\"center\"><input type=\"text\" id=\"txtPedidoStock".$i."\" size=\"4\" value=\"0\" style=\"text-align:right;\"/></td><td width=\"10%\" align=\"center\"><input type=\"text\" id=\"txtSugerido".$i."\" size=\"4\" value=\"0\" style=\"text-align:right;\"/></td><td width=\"10%\" align=\"center\"><button onClick=\"cargarCompetidor(\'".$idProducto."\', \'".$producto['producto']."\');\">...</button></td></tr>');";
	}
	echo "$('#hdnTotalProdcutosSeleccionadosStock').val('".$i."');
		</script>";
	
}else if($funcion == 2){
	$idProducto = $_POST['idProducto'];
	$idProdForm = explode(",", substr($_POST['idProdForm'], 0, -1));
	$existencia = explode(",", substr($_POST['existencia'], 0, -1));
	$precio = explode(",", substr($_POST['precio'], 0, -1));
	$pedido = explode(",", substr($_POST['pedido'], 0, -1));
	$sugerido = explode(",", substr($_POST['sugerido'], 0, -1));
	echo "<script>
		$('#tblProductoSeleccionado tbody').empty();
		$('#sltProductoStock :selected').remove(); ";
	for($i=0;$i<count($idProdForm);$i++){
		//echo "select * from PRODFORM where prodform_snr = '".$idProdForm[$i]."'<br><br>";
		$rsProdForm = sqlsrv_fetch_array(sqlsrv_query($conn, "select pf.PRODFORM_SNR idProdform, pf.name presentacion, p.name producto from PRODFORM pf, product p where pf.prod_snr = p.PROD_SNR and prodform_snr = '".$idProdForm[$i]."'"));
		echo "idHidden = ($('#hdnTotalProdcutosSeleccionados').val() * 1) + ".$i.";
		$('#tblProductosSeleccionados tbody').append('<tr><td width=\"300px\"><input type=\"hidden\" id=\"hdnIdProdFormS' + idHidden + '\" value=\"".$rsProdForm['idProdform']."\"/>".$rsProdForm['producto']." - ".$rsProdForm['presentacion']."</td><td width=\"10%\" align=\"center\"><input type=\"text\" id=\"txtExistenciaS' + idHidden + '\" size=\"4\" value=\"".$existencia[$i]."\" style=\"text-align:right;\"/></td><td width=\"10%\" align=\"center\"><input type=\"text\" id=\"txtPrecioS' + idHidden + '\" size=\"4\" value=\"".$precio[$i]."\" style=\"text-align:right;\"/></td><td width=\"10%\" align=\"center\"><input type=\"text\" id=\"txtPedidoS' + idHidden + '\" size=\"4\" value=\"".$pedido[$i]."\" style=\"text-align:right;\"/></td><td width=\"10%\" align=\"center\"><input type=\"text\" id=\"txtSugeridoS' + idHidden + '\" size=\"4\" value=\"".$sugerido[$i]."\" style=\"text-align:right;\"/></td><td width=\"10%\" align=\"center\"><img width=\"20\" src=\"iconos/eliminar.png\" /></td></tr>');";
	}
	echo "
	var prodSeleccionados = ($('#hdnTotalProdcutosSeleccionados').val() * 1) + ($('#hdnTotalProdcutosSeleccionadosStock').val() * 1);
		$('#hdnTotalProdcutosSeleccionados').val(prodSeleccionados);
	</script>";
}
?>