<?php
include "../conexion.php";

$idProducto = $_POST['idProducto'];
$producto = $_POST['producto'];

/*$queryCompetidores = "select prodform_snr, name 
	from PRODFORM 
	where TYPE = '133' 
	and REC_STAT = 0 
	and PROD_SNR = '".$idProducto."' order by name";*/
	
$queryCompetidores = "select pc.prodform_snr, pc.name 
	from PRODFORM_COMPETITOR pc
	inner join PRODFORM pf on pc.PRODFORM_SNR = pf.PRODFORM_SNR
	and pc.REC_STAT = 0
	and pf.REC_STAT = 0
	and pf.PROD_SNR = '".$idProducto."' order by pc.name";
		
$rs = sqlsrv_query($conn, $queryCompetidores);

echo "<script>
	$('#lblProductoCompetidor').text('".$producto."');
	$('#tblCompetidor tbody').empty();";
	$i=0;
	while($producto = sqlsrv_fetch_array($rs)){
		$i++;
		echo "$('#tblCompetidor tbody').append('<tr><td width=\"250px\" ><input type=\"hidden\" id=\"hdnIdProdFormCompetidor".$i."\" value=\"".$producto['prodform_snr']."\"/>".$producto['name']."</td><td width=\"100px\" align=\"center\"><input type=\"text\" id=\"txtExistenciaStockCompetidor".$i."\" size=\"4\" value=\"0\" style=\"text-align:right;\"/></td><td width=\"100px\" align=\"center\"><input type=\"text\" id=\"txtPrecioCompetidor".$i."\" size=\"4\" value=\"0\" style=\"text-align:right;\"/></td></tr>');";
	}
	echo "</script>";
?>