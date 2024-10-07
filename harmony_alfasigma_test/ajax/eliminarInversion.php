<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		$idInversion = $_POST['idInversion'];
		$ids = $_POST['ids'];
		
		if(isset($_POST['idPersona']) && $_POST['idPersona'] != ''){
			$idPersona = $_POST['idPersona'];
		}else{
			$idPersona = '';
		}
		
		$qElimina = "update USER_INVESTMENT set sync = 0, rec_stat = 2, changed_timestamp = getdate() where USER_INVESTMENT_SNR = '".$idInversion."'";
		
		echo "<script>";
		
		if(sqlsrv_query($conn, $qElimina)){
			if($idPersona == ''){//viene desde la tabla de todos
				$qTabla = "select ui.USER_INVESTMENT_SNR as idInversion,
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
			} else {//viene desde el perfil del médico
				$qTabla = "select ui.USER_INVESTMENT_SNR as idInversion,
					c.name as ciclo,
					u.USER_NR as ruta,
					CAST(year(ui.date) as nvarchar) + '-' + right('00' + Ltrim(Rtrim(cast(month(ui.date) as nvarchar))),2) + '-' + right('00' + Ltrim(Rtrim(cast(day(ui.date) as nvarchar))),2) as fecha,
					ui.name as nombreProducto,
					ui.PROD_SNR as idProdcutos,
					ui.info
					from USER_INVESTMENT ui
					left outer join person p on ui.PERS_SNR = p.PERS_SNR
					left outer join CYCLES c on ui.date between c.START_DATE and c.FINISH_DATE
					left outer join users u on u.USER_SNR = ui.USER_SNR
					where ui.REC_STAT = 0 
					and p.REC_STAT = 0
					and ui.PERS_SNR = '".$idPersona."'";
			}
			
			$rsTabla = sqlsrv_query($conn, $qTabla);
			
			if($idPersona == ''){
				echo "$('#tblInversiones tbody').empty();";
			}else{
				echo "$('#tblInversion tbody').empty();";
			}
			
			while($registro = sqlsrv_fetch_array($rsTabla)){
				$idInversion = $registro['idInversion'];
				$ciclo = $registro['ciclo'];
				$fecha = $registro['fecha'];
				$idProductos = str_replace(";", "','",$registro['idProdcutos']);
				$productos = '';
				if($idProductos != '' && $idProductos != '00000000-0000-0000-0000-000000000000'){
					$rsProductos = sqlsrv_query($conn, "select name from product where prod_snr in ('".$idProductos."')");
					//echo "select name from product where prod_snr in ('".$idProductos."')";
					while($producto = sqlsrv_fetch_array($rsProductos)){
						$productos .= $producto['name'].", ";
					}
				}
				if($idPersona == ''){
					$nombre = $registro['nombre'];
					$tipo = $registro['tipo'];
					$cantidad = $registro['cantidad'];
					echo "$('#tblInversiones tbody').append('<tr><td style=\"width:35%;\">".$nombre."</td><td style=\"width:10%;\">".$fecha."</td><td style=\"width:10%;\">".$ciclo."</td><td style=\"width:15%;\">".$tipo."</td><td style=\"width:10%;\">".$cantidad."</td><td style=\"width:20%;\">".$productos."</td><td style=\"width:5%;\"><button type=\"button\" class=\"btn bg-indigo btn bg-indigo waves-effect btn-indigo little-button\" onClick=\"editarInversion(\'".$idInversion."\');\"><i class=\"material-icons pointer\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"Editar\">edit</i></button></td><td style=\"width:5%;\"><button type=\"button\" class=\"btn bg-indigo btn bg-indigo waves-effect btn-indigo little-button\" onClick=\"alertEliminarInversion(\'".$idInversion."\');\"><i class=\"material-icons pointer\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"Eliminar\">delete</i></button></td></tr>');\r\n";
				}else{
					$ruta = $registro['ruta'];
					$nombreProducto = $registro['nombreProducto'];
					$comentarios = $registro['info'];
					echo "$('#tblInversion tbody').append('<tr onClick=\"muestraInversion(\'".$idInversion."\');\"><td style=\"width:15%\">".$ciclo."</td><td style=\"width:10%\">".$ruta."</td><td style=\"width:15%\">".$fecha."</td><td style=\"width:15%\">".$nombreProducto."</td><td style=\"width:20%\">".$productos."</td><td style=\"width:25%\">".$comentarios."</td></tr>');
						";
				}
			}
			if($idPersona != ''){
				echo "$('#divCapa3').hide();
					$('#divInversiones').hide();";
			}
			echo "alertEliminarInversionOk();";
		}else{
			echo "alertEliminarInversionError();";
		}
		echo "</script>";
	}
?>