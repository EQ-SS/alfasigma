<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$combo = $_POST['combo'];
		echo "<script>";
		switch ($combo) {
			case 1 :
				echo "$('#txtProdPosInst1').val('100');";
				break;
			case 2 :
				echo "$('#txtProdPosInst1').val('80');";
				echo "$('#txtProdPosInst2').val('20');";
				break;
			case 3 :
				echo "$('#txtProdPosInst1').val('70');";
				echo "$('#txtProdPosInst2').val('20');";
				echo "$('#txtProdPosInst3').val('10');";
				break;
			case 4 :
				echo "$('#txtProdPosInst1').val('60');";
				echo "$('#txtProdPosInst2').val('25');";
				echo "$('#txtProdPosInst3').val('10');";
				echo "$('#txtProdPosInst4').val('5');";
				break;
			case 5 :
				echo "$('#txtProdPosInst1').val('50');";
				echo "$('#txtProdPosInst2').val('20');";
				echo "$('#txtProdPosInst3').val('15');";
				echo "$('#txtProdPosInst4').val('10');";
				echo "$('#txtProdPosInst5').val('5');";
				break;
		   case 6 :
				echo "$('#txtProdPosInst1').val('30');";
				echo "$('#txtProdPosInst2').val('25');";
				echo "$('#txtProdPosInst3').val('20');";
				echo "$('#txtProdPosInst4').val('15');";
				echo "$('#txtProdPosInst5').val('10');";
				echo "$('#txtProdPosInst6').val('5');";
				break;
			case 7 :
				echo "$('#txtProdPosInst1').val('25');";
				echo "$('#txtProdPosInst2').val('20');";
				echo "$('#txtProdPosInst3').val('15');";
				echo "$('#txtProdPosInst4').val('10');";
				echo "$('#txtProdPosInst5').val('10');";
				echo "$('#txtProdPosInst6').val('5');";
				echo "$('#txtProdPosInst7').val('5');";
				break;
			case 8 :
				echo "$('#txtProdPosInst1').val('25');";
				echo "$('#txtProdPosInst2').val('20');";
				echo "$('#txtProdPosInst3').val('15');";
				echo "$('#txtProdPosInst4').val('10');";
				echo "$('#txtProdPosInst5').val('10');";
				echo "$('#txtProdPosInst6').val('10');";
				echo "$('#txtProdPosInst7').val('10');";
				echo "$('#txtProdPosInst8').val('5');";
				break;
			case 9 :
				echo "$('#txtProdPosInst1').val('20');";
				echo "$('#txtProdPosInst2').val('15');";
				echo "$('#txtProdPosInst3').val('10');";
				echo "$('#txtProdPosInst4').val('10');";
				echo "$('#txtProdPosInst5').val('10');";
				echo "$('#txtProdPosInst6').val('10');";
				echo "$('#txtProdPosInst7').val('10');";
				echo "$('#txtProdPosInst8').val('10');";
				echo "$('#txtProdPosInst9').val('5');";
				break;
			case 10 :
				echo "$('#txtProdPosInst1').val('20');";
				echo "$('#txtProdPosInst2').val('15');";
				echo "$('#txtProdPosInst3').val('10');";
				echo "$('#txtProdPosInst4').val('10');";
				echo "$('#txtProdPosInst5').val('10');";
				echo "$('#txtProdPosInst6').val('10');";
				echo "$('#txtProdPosInst7').val('10');";
				echo "$('#txtProdPosInst8').val('5');";
				echo "$('#txtProdPosInst9').val('5');";
				echo "$('#txtProdPosInst10').val('5');";
				break;
		}
		$combo++;
		$productos = substr($_POST['productos'], 0, -1);
		$queryProductos = "select * from product where REC_STAT = 0 and prod_snr not in ('".str_replace(",","','",$productos)."') order by name";
		$rsProdcutos = sqlsrv_query($conn, $queryProductos);
		echo "$('#lstProductoInst".$combo."').empty();";
		while($producto = sqlsrv_fetch_array($rsProdcutos)){
			echo "$('#lstProductoInst".$combo."').append('<option value=\"".$producto['PROD_SNR']."\">".$producto['NAME']."</option>');";
		}
		echo "</script>";
	}
?>