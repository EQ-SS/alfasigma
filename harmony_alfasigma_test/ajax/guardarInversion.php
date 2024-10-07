<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		$idInversion = $_POST['idInversion'];
		$concepto = strtoupper($_POST['concepto']);
		$tipoInversion = (empty($_POST['codigoInversion'])) ? '00000000-0000-0000-0000-000000000000' : $_POST['codigoInversion'] ;
		$producto = $_POST['producto'];
		$fechaInversion = $_POST['fechaInversion'];
		$cantidadIvertida = $_POST['cantidadInversion'];
		$comentarios = strtoupper($_POST['comentarios']);
		
		$idPersona = $_POST['idPersona'];
		$idUsuario = $_POST['idUsuario'];
		
		$pantalla = $_POST['pantalla'];
		
		if($idInversion != ''){
			$query = "update USER_INVESTMENT set 
				NAME = '$concepto',
				TYPE_SNR = '$tipoInversion',
				PROD_SNR = '$producto',
				DATE = '$fechaInversion',
				AMOUNT_INVESTED = '$cantidadIvertida',
				INFO = '$comentarios',
				SYNC = 0,
				CHANGED_TIMESTAMP = getdate() 
				where USER_INVESTMENT_SNR = '$idInversion'";
		}else{
			$query = "insert into USER_INVESTMENT (
				USER_INVESTMENT_SNR,
				USER_SNR,
				PERS_SNR,
				NAME,
				TYPE_SNR,
				PROD_SNR,
				DATE,
				AMOUNT_INVESTED,
				INFO,
				REC_STAT,
				SYNC,
				CREATION_TIMESTAMP
			)
			values(
				NEWID(),
				'$idUsuario',
				'$idPersona',
				'$concepto',
				'$tipoInversion',
				'$producto',
				'$fechaInversion',
				'$cantidadIvertida',
				'$comentarios',
				0,
				0,
				getdate()
			)";
			//$idUsuario = $rsDatos['USER_SNR'];
			
		}
		
		//echo $query;
		
		echo "<script>";
		
		if(sqlsrv_query($conn, $query)){
			if($pantalla == 'personas'){
				$qInversiones = "select ui.USER_INVESTMENT_SNR,
					c.name as ciclo,
					u.USER_NR,
					CAST(year(ui.date) as nvarchar) + '-' + right('00' + Ltrim(Rtrim(cast(month(ui.date) as nvarchar))),2) + '-' + right('00' + Ltrim(Rtrim(cast(day(ui.date) as nvarchar))),2) as date,
					ui.name,
					ui.PROD_SNR,
					ui.info
					from USER_INVESTMENT ui
					left outer join person p on ui.PERS_SNR = p.PERS_SNR
					left outer join CYCLES c on ui.date between c.START_DATE and c.FINISH_DATE
					left outer join users u on u.USER_SNR = ui.USER_SNR
					where ui.REC_STAT = 0 
					and p.REC_STAT = 0
					and ui.PERS_SNR = '".$idPersona."'";
			
				$rsInversiones = sqlsrv_query($conn, $qInversiones);
					
				echo "$('#tblInversion tbody').empty();";
				while($inversion = sqlsrv_fetch_array($rsInversiones)){
					$idInversion = $inversion['USER_INVESTMENT_SNR'];
					$ciclo = $inversion['ciclo'];
					$ruta = $inversion['USER_NR'];
					$fecha = $inversion['date'];
					$nombre = $inversion['name'];
					$idProducto = str_replace(";", "','",$inversion['PROD_SNR']);
					$comentarios = $inversion['info'];
					
					$productos = '';
					if($idProducto != ''){
						$rsProductos = sqlsrv_query($conn, "select name from product where prod_snr in ('".$idProducto."')");
						//echo "select name from product where prod_snr in ('".$idProducto."')";
						while($producto = sqlsrv_fetch_array($rsProductos)){
							$productos .= $producto['name'].", ";
						}
					}
					echo "$('#tblInversion tbody').append('<tr onClick=\"muestraInversion(\'".$idInversion."\');\"><td style=\"width:15%\">".$ciclo."</td><td style=\"width:10%\">".$ruta."</td><td style=\"width:15%\">".$fecha."</td><td style=\"width:15%\">".$nombre."</td><td style=\"width:20%\">".$productos."</td><td style=\"width:25%\">".$comentarios."</td></tr>');";
				}
					
				$qTotalInversiones = "select cast(c.NUMBER as int)  as numero_ciclo, 
					count(c.NUMBER) as total
					from USER_INVESTMENT ui
					inner join cycles c on ui.DATE between c.START_DATE and c.FINISH_DATE
					where ui.PERS_SNR = '".$idPersona."'
					and ui.REC_STAT = 0
					group by c.NUMBER";
		
				$rsTotalInversiones = sqlsrv_query($conn, $qTotalInversiones);
				
				for($i=1;$i<14;$i++){
					echo "$('#cicloInversion".$i."').text('0');";
				}
				$totaInversiones = 0;
				while($totalInversion = sqlsrv_fetch_array($rsTotalInversiones)){
					$totaInversiones += $totalInversion['total'];
					echo "$('#cicloInversion".$totalInversion['numero_ciclo']."').text('".$totalInversion['total']."');";
				}
				
				echo "$('#acumuladoInversion').text('".$totaInversiones."');";
			}else if($pantalla == 'inversiones'){
				echo "$('#imgInversiones').click();";
			}
			
			echo "$('#btnGuardarInversion').prop('disabled',false);
				notificationInversionGuardada();";
		}else{
			echo "alertErrorGuardarRegistro();";
		}
		echo "$('#divCapa3').hide();
			$('#divInversiones').hide();
			</script>";
	}
	
?>