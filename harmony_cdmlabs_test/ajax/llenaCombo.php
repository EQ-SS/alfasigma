<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$combo = $_POST['combo'];
		echo "<script>";
		switch ($combo) {
			case 1 :
				echo "$('#txtProdPos1').val('100');";
				break;
			case 2 :
				echo "$('#txtProdPos1').val('80');";
				echo "$('#txtProdPos2').val('20');";
				break;
			case 3 :
				echo "$('#txtProdPos1').val('70');";
				echo "$('#txtProdPos2').val('20');";
				echo "$('#txtProdPos3').val('10');";
				break;
			case 4 :
				echo "$('#txtProdPos1').val('60');";
				echo "$('#txtProdPos2').val('25');";
				echo "$('#txtProdPos3').val('10');";
				echo "$('#txtProdPos4').val('5');";
				break;
			case 5 :
				echo "$('#txtProdPos1').val('50');";
				echo "$('#txtProdPos2').val('20');";
				echo "$('#txtProdPos3').val('15');";
				echo "$('#txtProdPos4').val('10');";
				echo "$('#txtProdPos5').val('5');";
				break;
		   case 6 :
				echo "$('#txtProdPos1').val('30');";
				echo "$('#txtProdPos2').val('25');";
				echo "$('#txtProdPos3').val('20');";
				echo "$('#txtProdPos4').val('15');";
				echo "$('#txtProdPos5').val('10');";
				echo "$('#txtProdPos6').val('5');";
				break;
			case 7 :
				echo "$('#txtProdPos1').val('25');";
				echo "$('#txtProdPos2').val('20');";
				echo "$('#txtProdPos3').val('15');";
				echo "$('#txtProdPos4').val('10');";
				echo "$('#txtProdPos5').val('10');";
				echo "$('#txtProdPos6').val('5');";
				echo "$('#txtProdPos7').val('5');";
				break;
			case 8 :
				echo "$('#txtProdPos1').val('25');";
				echo "$('#txtProdPos2').val('20');";
				echo "$('#txtProdPos3').val('15');";
				echo "$('#txtProdPos4').val('10');";
				echo "$('#txtProdPos5').val('10');";
				echo "$('#txtProdPos6').val('10');";
				echo "$('#txtProdPos7').val('10');";
				echo "$('#txtProdPos8').val('5');";
				break;
			case 9 :
				echo "$('#txtProdPos1').val('20');";
				echo "$('#txtProdPos2').val('15');";
				echo "$('#txtProdPos3').val('10');";
				echo "$('#txtProdPos4').val('10');";
				echo "$('#txtProdPos5').val('10');";
				echo "$('#txtProdPos6').val('10');";
				echo "$('#txtProdPos7').val('10');";
				echo "$('#txtProdPos8').val('10');";
				echo "$('#txtProdPos9').val('5');";
				break;
			case 10 :
				echo "$('#txtProdPos1').val('20');";
				echo "$('#txtProdPos2').val('15');";
				echo "$('#txtProdPos3').val('10');";
				echo "$('#txtProdPos4').val('10');";
				echo "$('#txtProdPos5').val('10');";
				echo "$('#txtProdPos6').val('10');";
				echo "$('#txtProdPos7').val('10');";
				echo "$('#txtProdPos8').val('5');";
				echo "$('#txtProdPos9').val('5');";
				echo "$('#txtProdPos10').val('5');";
				break;
		}
		$combo++;
		$productos = substr($_POST['productos'], 0, -1);
		$queryProductos = "select * from product where REC_STAT = 0 and prod_snr not in ('".str_replace(",","','",$productos)."') order by name";
		$rsProdcutos = sqlsrv_query($conn, $queryProductos);
		echo "$('#lstProducto".$combo."').empty();";
		while($producto = sqlsrv_fetch_array($rsProdcutos)){
			echo "$('#lstProducto".$combo."').append('<option value=\"".$producto['PROD_SNR']."\">".$producto['NAME']."</option>');";
		}
		echo "</script>";
		//echo $queryProductos;
	}
?>