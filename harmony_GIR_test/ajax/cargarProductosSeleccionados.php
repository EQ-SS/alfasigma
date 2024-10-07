<?php
include "../conexion.php";
$funcion = $_POST['funcion'];
//echo "funcion: ".$funcion."<br>";
if($funcion == 1){
	$idProducto = $_POST['idProducto'];
	$queryProductos = "select pf.name as presentacion, 
		pf.prodform_snr, 
		p.name as producto
        from PRODFORM pf, product p 
        where pf.REC_STAT = 0 
        and pf.type = 132 
        and pf.prod_snr =  '" . $idProducto . "' 
        and pf.STOCK_ENABLED = 1 
        and p.PROD_SNR = pf.PROD_SNR 
        order by pf.name ";
		
	//echo $queryProductos."<br>";
		
	$rs = sqlsrv_query($conn, $queryProductos);
	echo "<script>
		$('#tblProductoSeleccionado tbody').empty();";
	$i=0;
	while($producto = sqlsrv_fetch_array($rs)){
		$i++;
		$registros = "<tr>";
		$registros .= "<td width=\"65%\" ><input type=\"hidden\" id=\"hdnIdProdForm".$i."\" value=\"".$producto['prodform_snr']."\"/>".$producto['producto']." ".$producto['presentacion']."</td>";
		$registros .= "<td width=\"15%\" align=\"center\"><input type=\"text\" id=\"txtDesplazamientoStock".$i."\" size=\"4\" value=\"0\" style=\"text-align:right;\"/></td>";
		//$registros .= "<td width=\"10%\" align=\"center\"><select id=\"sltAgotadoStock".$i."\"></select></td>";
		$registros .= "<td width=\"15%\" align=\"center\"><input type=\"text\" id=\"txtExistenciaStock".$i."\" size=\"4\" value=\"0\" style=\"text-align:right;\"/></td>";
		//$registros .= "<td width=\"10%\" align=\"center\"><select id=\"sltCadenaStock".$i."\"></select></td>";
		//$registros .= "<td width=\"10%\" align=\"center\"><select id=\"sltPromocionesStock".$i."\"></select></td>";
		$registros .= "<td width=\"15%\" align=\"center\"><input type=\"text\" id=\"txtPrecioStock".$i."\" size=\"4\" value=\"0\" style=\"text-align:right;\"/></td>";
		//$registros .= "<td width=\"10%\" align=\"center\"><button onClick=\"cargarCompetidor(\'".$idProducto."\', \'".$producto['producto']."\');\">...</button></td>";
		$registros .= "</tr>";
		echo "$('#tblProductoSeleccionado tbody').append('".$registros."');";
		//echo "$('#tblProductoSeleccionado tbody').append('<tr><td width=\"65%\" ><input type=\"hidden\" id=\"hdnIdProdForm".$i."\" value=\"".$producto['prodform_snr']."\"/>".$producto['producto']." ".$producto['presentacion']."</td><td width=\"10%\" align=\"center\"><input type=\"text\" id=\"txtPedidoStock".$i."\" size=\"4\" value=\"0\" style=\"text-align:right;\"/></td><td width=\"10%\" align=\"center\"><input type=\"text\" id=\"txtExistenciaStock".$i."\" size=\"4\" value=\"0\" style=\"text-align:right;\"/></td><td width=\"10%\" align=\"center\"><input type=\"text\" id=\"txtPrecioStock".$i."\" size=\"4\" value=\"0\" style=\"text-align:right;\"/></td><td width=\"10%\" align=\"center\"><button onClick=\"cargarCompetidor(\'".$idProducto."\', \'".$producto['producto']."\');\">...</button></td></tr>');";
		$rsSiNo = llenaCombo($conn, 395, 1);
		while($siNo = sqlsrv_fetch_array($rsSiNo)){
			echo "$('#sltAgotadoStock".$i."').append('<option value=\"".$siNo['id']."\">".$siNo['nombre']."</option>');";
		}
		
		$rsSiNo = llenaCombo($conn, 395, 2);
		while($siNo = sqlsrv_fetch_array($rsSiNo)){
			echo "$('#sltCadenaStock".$i."').append('<option value=\"".$siNo['id']."\">".$siNo['nombre']."</option>');";
		}
		
		$rsSiNo = llenaCombo($conn, 395, 3);
		while($siNo = sqlsrv_fetch_array($rsSiNo)){
			echo "$('#sltPromocionesStock".$i."').append('<option value=\"".$siNo['id']."\">".$siNo['nombre']."</option>');";
		}
	}
	echo "$('#hdnTotalProdcutosSeleccionadosStock').val('".$i."');
		</script>";
	
}else if($funcion == 2){
	$idProducto = $_POST['idProducto'];
	$idProdForm = explode(",", substr($_POST['idProdForm'], 0, -1));
	$desplazamiento = explode(",", substr($_POST['desplazamiento'], 0, -1));
	$existencia = explode(",", substr($_POST['existencia'], 0, -1));
	//$agotado = explode(",", substr($_POST['agotado'], 0, -1));
	$precio = explode(",", substr($_POST['precio'], 0, -1));
	//$pedido = explode(",", substr($_POST['pedido'], 0, -1));
	//$sugerido = explode(",", substr($_POST['sugerido'], 0, -1));
	//$cadena = explode(",", substr($_POST['cadena'], 0, -1));
	//$promociones = explode(",", substr($_POST['promociones'], 0, -1));
	echo "<script>
		$('#tblProductoSeleccionado tbody').empty();
		$('#sltProductoStock :selected').remove(); ";
	for($i=0;$i<count($idProdForm);$i++){
		$rsProdForm = sqlsrv_fetch_array(sqlsrv_query($conn, "select pf.PRODFORM_SNR idProdform, pf.name presentacion, p.name producto from PRODFORM pf, product p where pf.prod_snr = p.PROD_SNR and prodform_snr = '".$idProdForm[$i]."'"));
		//$arrAgotado = sqlsrv_fetch_array(sqlsrv_query($conn, "select name from codelist where clist_snr = '".$agotado[$i]."'"));
		//$arrCadena = sqlsrv_fetch_array(sqlsrv_query($conn, "select name from codelist where clist_snr = '".$cadena[$i]."'"));
		//$arrPromociones = sqlsrv_fetch_array(sqlsrv_query($conn, "select name from codelist where clist_snr = '".$promociones[$i]."'"));
		echo "idHidden = ($('#hdnTotalProdcutosSeleccionados').val() * 1) + ".$i.";";
		$seleccionados = "<tr>";
		$seleccionados .= "<td width=\"60%\"><input type=\"hidden\" id=\"hdnIdProdFormS' + idHidden + '\" value=\"".$rsProdForm['idProdform']."\"/>".$rsProdForm['producto']." - ".$rsProdForm['presentacion']."</td>";
		$seleccionados .= "<td width=\"15%\" align=\"center\"><input type=\"text\" id=\"txtDesplazamientoS' + idHidden + '\" size=\"4\" value=\"".$desplazamiento[$i]."\" style=\"text-align:right;\"/></td>";
		$seleccionados .= "<td width=\"15%\" align=\"center\"><input type=\"text\" id=\"txtExistenciaS' + idHidden + '\" size=\"4\" value=\"".$existencia[$i]."\" style=\"text-align:right;\"/></td>";
		//$seleccionados .= "<td width=\"15%\" align=\"center\"><input type=\"hidden\" id=\"hdnAgotadoS' + idHidden + '\" value=\"".$agotado[$i]."\"/><input type=\"text\" id=\"txtAgotadoS' + idHidden + '\" size=\"6\" value=\"".$arrAgotado['name']."\"/></td>";
		//$seleccionados .= "<td width=\"15%\" align=\"center\"><input type=\"text\" id=\"txtPedidoS' + idHidden + '\" size=\"4\" value=\"".$pedido[$i]."\" style=\"text-align:right;\"/></td>";
		//$seleccionados .= "<td width=\"10%\" align=\"center\"><input type=\"hidden\" id=\"hdnCadenaS' + idHidden + '\" value=\"".$cadena[$i]."\"/><input type=\"text\" id=\"txtCadenaS' + idHidden + '\" size=\"10\" value=\"".$arrCadena['name']."\"/></td>";
		//$seleccionados .= "<td width=\"10%\" align=\"center\"><input type=\"hidden\" id=\"hdnPromocionesS' + idHidden + '\" value=\"".$promociones[$i]."\"/><input type=\"text\" id=\"txtPromocionesS' + idHidden + '\" size=\"10\" value=\"".$arrPromociones['name']."\"/></td>";
		$seleccionados .= "<td width=\"10%\" align=\"center\"><input type=\"text\" id=\"txtPrecioS' + idHidden + '\" size=\"4\" value=\"".$precio[$i]."\" style=\"text-align:right;\"/></td>";
		$seleccionados .= "<td width=\"10%\" align=\"center\"><img width=\"20\" src=\"iconos/eliminar.png\" /></td>";
		$seleccionados .= "</tr>";
		echo "$('#tblProductosSeleccionados tbody').append('".$seleccionados."');";
	}
	echo "
		var prodSeleccionados = ($('#hdnTotalProdcutosSeleccionados').val() * 1) + ($('#hdnTotalProdcutosSeleccionadosStock').val() * 1);
		$('#hdnTotalProdcutosSeleccionados').val(prodSeleccionados);
	</script>";
}
?>