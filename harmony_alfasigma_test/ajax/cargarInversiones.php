<?php
	include "../conexion.php";
	$buscar = array(chr(13).chr(10), "\r\n", "\n", "\r");
	$reemplazar = array(" ", " ", " ", " ");
	$reemplazar1 = array("", "", "", "");
	$reemplazar2 = array("<br>", "<br>", "<br>", "<br>");
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$ids = $_POST['ids'];
		echo "<script>
				$('#tblInversiones tbody').empty();";

		$queryTabla = "select ui.USER_INVESTMENT_SNR as idInversion,
			p.lname + ' ' + p.mothers_lname + ' ' + p.fname as nombre,
			CAST(year(ui.date) as nvarchar) + '-' + right('00' + Ltrim(Rtrim(cast(month(ui.date) as nvarchar))),2) + '-' + right('00' + Ltrim(Rtrim(cast(day(ui.date) as nvarchar))),2) as fecha,
			c.name as ciclo,
			tipo.name as tipo,
			ui.AMOUNT_INVESTED as cantidad,
			ui.PROD_SNR as idProdcutos
			from USER_INVESTMENT ui
			inner join person p on ui.PERS_SNR = p.PERS_SNR
			inner join CYCLES c on ui.date between c.START_DATE and c.FINISH_DATE
			inner join users u on u.USER_SNR = ui.USER_SNR
			left outer join codelist tipo on tipo.clist_snr = ui.type_snr
			where ui.REC_STAT = 0 
			and p.REC_STAT = 0 
			and ui.user_snr in ('".$ids."') 
			order by nombre, date";
		//echo $queryTabla."<br>";
		$rsTabla = sqlsrv_query($conn, $queryTabla, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		//$trEventos = 0;
		while($registro = sqlsrv_fetch_array($rsTabla)){
			$id = $registro['idInversion'];
			//$tipo = utf8_encode(str_replace("'", "\'", $registro['tipo']));
			$nombre = str_replace("'", "\'", $registro['nombre']);	
			$fecha = $registro['fecha'];
			$ciclo = $registro['ciclo'];
			$tipo = $registro['tipo'];
			$cantidad = $registro['cantidad'];
			$idProductos = str_replace(";", "','",$registro['idProdcutos']);
			$productos = '';
			//echo "\t\nidProdcutos: ".$idProductos."\t\n";
			if($idProductos != ''){
				$rsProductos = sqlsrv_query($conn, "select name from product where prod_snr in ('".$idProductos."')");
				//echo "select name from product where prod_snr in ('".$idProductos."')";
				while($producto = sqlsrv_fetch_array($rsProductos)){
					$productos .= $producto['name'].", ";
				}
			}
			$productos = substr($productos, 0, -2);
			//$productos = "---".$productos."---";
			echo "$('#tblInversiones tbody').append('<tr><td style=\"width:35%;\">".$nombre."</td><td style=\"width:10%;\">".$fecha."</td><td style=\"width:10%;\">".$ciclo."</td><td style=\"width:15%;\">".$tipo."</td><td style=\"width:10%;\">".$cantidad."</td><td style=\"width:20%;\">".$productos."</td><td style=\"width:5%;\"><button type=\"button\" class=\"btn bg-indigo btn bg-indigo waves-effect btn-indigo little-button\" onClick=\"editarInversion(\'".$id."\');\"><i class=\"material-icons pointer\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"Editar\">edit</i></button></td><td style=\"width:5%;\"><button type=\"button\" class=\"btn bg-indigo btn bg-indigo waves-effect btn-indigo little-button\" onClick=\"alertEliminarInversion(\'".$id."\');\"><i class=\"material-icons pointer\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"Eliminar\">delete</i></button></td></tr>');\r\n";
			
			//$trEventos++;
		}
		echo "</script>";
	}
?>