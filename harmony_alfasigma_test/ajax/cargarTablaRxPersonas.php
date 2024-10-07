
<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		$id = $_POST['idPersona'];
		$producto = $_POST['producto'];
		$periodo = str_replace(",", "','", substr($_POST['periodos'], 0, -1));
		$mercado = str_replace(",", "','", substr($_POST['mercados'], 0, -1));
		
		$queryPrescripciones = "select *, p.NAME as producto,
			pp.MARKET_SHARE as MARKET_SHARE,
			per.NAME as periodo,
			mdo.NAME as mdo,
			cat.NAME as categoria,
			m.MARKET_SHARE as ms,
			m.NUM_RX 
			from PERSON_RX_PRODUCT pp
			inner join CODELIST p on p.CLIST_SNR = pp.PRODUCT_SNR
			inner join PERSON_RX_MARKET m on m.PERSRXMARKET_SNR = pp.PERSRXMARKET_SNR
			inner join CODELIST per on per.CLIST_SNR = m.PERIOD_SNR
			inner join CODELIST mdo on mdo.CLIST_SNR = m.MARKET_SNR
			inner join CODELIST cat on cat.CLIST_SNR = m.CATEGORY_SNR
			where pp.pers_snr = '".$id."' 
			and pp.rec_stat = 0 ";
	
		//echo $queryPrescripciones;
			
		if($producto != ''){
			$queryPrescripciones .= " and pp.PRODUCT_SNR = '".$producto."' ";
		}
		
		if($periodo != ''){
			$queryPrescripciones .= " and m.PERIOD_SNR in ('".$periodo."') ";
		}
		
		if($mercado != ''){
			$queryPrescripciones .= " and m.MARKET_SNR in ('".$mercado."') ";
		}
		
		if($periodo == '' && $mercado == ''){
			$queryPrescripciones .= " and pp.REC_STAT=0
				and per.REC_STAT=0
				and mdo.REC_STAT=0
				and cat.REC_STAT=0
				and per.STATUS=1 ";
		}
			
		$queryPrescripciones .= " order by per.NAME, mdo.NAME, cat.NAME, pp.MARKET_SHARE";
			
		//echo $queryPrescripciones;

		$rsPrescripciones = sqlsrv_query($conn, $queryPrescripciones);
		echo "<script>
			$('#tblPrescripciones').empty();";

			$cuentaTablas = '';
			$contadorRx = 0;
			
			// Variables para almacenar el HTML de las tablas
			$htmlTablas = array();
			$currentPeriodo = null;
			$currentMdo = null;
			$htmlTablaActual="";

			// Inicializar la cabecera de la tabla con valores del primer registro
			if ($pres = sqlsrv_fetch_array($rsPrescripciones)) {
				$htmlTablaActual = '<table border="1" class="table table-bordered">
										<tr>
											<th style="background-color: white; color: black; text-align: center;">Periodo: ' .$pres['periodo'].'</th>
											<th style="background-color: white; color: black; text-align: center;">Mdo:  ' .$pres['mdo'].'</th>
											<th style="background-color: white; color: black; text-align: center;">Cat :' .$pres['categoria'].' Ms:' .$pres['ms'].' Pres: ' .$pres['NUM_RX'].' </th>
										</tr>
										<tr>
											<th style="background-color: gray; color: white; text-align: center;">Ranking</th>
											<th style="background-color: gray; color: white; text-align: center;">Producto</th>
											<th style="background-color: gray; color: white; text-align: center;">Market Share</th>
										</tr>';
				$contadorRx++;
				$cadenaTest = $pres['producto'];
				$cadenaTest = str_replace(' ', '_', $cadenaTest);
				$cuentaTablas .= $cadenaTest.",";
				echo '$("#lstProductoDatosPersonales").append(new Option("'.$pres['producto'].'", "'.$pres['PRODUCT_SNR'].'"));';

				// Construir una fila de la tabla con los datos de cada prescripción
				$htmlTablaActual .= '<tr>';
				$htmlTablaActual .= '<td class="text-center">' . $pres['POSITION'] . '</td>';
				$htmlTablaActual .= '<td class="text-center">' . $pres['producto'] . '</td>';
				$htmlTablaActual .= '<td class="text-center">' . $pres['MARKET_SHARE'] . '</td>';
				$htmlTablaActual .= '</tr>';

				// Guardar el periodo y el mdo actual
				$currentPeriodo = $pres['periodo'];
				$currentMdo = $pres['mdo'];
			}

			while($pres = sqlsrv_fetch_array($rsPrescripciones)){
				$contadorRx++;
				$cadenaTest = $pres['producto'];
				$cadenaTest = str_replace(' ', '_', $cadenaTest);
				$cuentaTablas .= $cadenaTest.",";

				// Verificar si el periodo o el mdo han cambiado
				if ($currentPeriodo !== $pres['periodo'] || $currentMdo !== $pres['mdo']) {
					// Si han cambiado, guardar la tabla actual en el array y empezar una nueva
					$htmlTablas[] = $htmlTablaActual;

					$htmlTablaActual = '<table border="1" class="table table-bordered">
											<tr>
												<th style="background-color: white; color: black; text-align: center;">Periodo: ' .$pres['periodo'].'</th>
												<th style="background-color: white; color: black; text-align: center;">Mdo:  ' .$pres['mdo'].'</th>
												<th style="background-color: white; color: black; text-align: center;">Cat :' .$pres['categoria'].' Ms:' .$pres['ms'].' Pres: ' .$pres['NUM_RX'].' </th>
											</tr>
											<tr>
												<th style="background-color: gray; color: white; text-align: center;">Ranking</th>
												<th style="background-color: gray; color: white; text-align: center;">Producto</th>
												<th style="background-color: gray; color: white; text-align: center;">Market Share</th>
											</tr>';
					$currentPeriodo = $pres['periodo'];
					$currentMdo = $pres['mdo'];
				}

				// Construir una fila de la tabla con los datos de cada prescripción
				$htmlTablaActual .= '<tr>';
				$htmlTablaActual .= '<td class="text-center">' . $pres['POSITION'] . '</td>';
				$htmlTablaActual .= '<td class="text-center">' . $pres['producto'] . '</td>';
				$htmlTablaActual .= '<td class="text-center">' . $pres['MARKET_SHARE'] . '</td>';
				$htmlTablaActual .= '</tr>';
			}

			// Cerrar la etiqueta de la última tabla y guardarla en el array de tablas
			$htmlTablaActual .= '</table>';
			$htmlTablas[] = $htmlTablaActual;

			// Imprimir todas las tablas en el mismo contenedor
			echo '$("#tblPrescripciones").html(' . json_encode(implode('<br>', $htmlTablas)) . ');';
		
		if($contadorRx == 0){
			echo "$('#tblPrescripciones').append('<div class=\"row col-indigo font-bold\"><div class=\"col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-0\"><span class=\"col-md-6 p-l-0 margin-0\">Período: </span><span class=\"col-md-6 align-right margin-0\">Mdo: </span></div><div class=\"col-lg-6 col-md-6 col-sm-6 col-xs-12 margin-0\"><span class=\"col-md-4 margin-0 p-l-5\">Categoría: </span><span class=\"col-md-4 align-center margin-0\">MS: </span><span class=\"col-md-4 align-right p-r-5 margin-0\">Prescribe: </span></div></div><div class=\"row\"><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><div class=\"div-tbl-grey\"><table class=\"tblRxPersonas\"><thead class=\"bg-grey\"><tr class=\"align-center\"><td style=\"width:30%;\">Ranking</td><td style=\"width:40%;\">Producto</td><td style=\"width:30%;\">Market Share</td></tr></thead><tbody><tr class=\"align-center\"><td style=\"width:100%;\">Sin datos que mostrar</td></tr></tbody></table></div></div></div>');";
		}

		echo "</script>";		
	}
	
?>

