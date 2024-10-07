<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$year = $_POST['year'];
		$idPersona = $_POST['idPersona'];
		
		$queryMuestras = "select vp.VISIT_DATE as fecha, pf.type as tipo, p.NAME as producto,
			pf.name as presentacion, pfb.NAME as lote, vpm.quantity as cantidad
			from VISITPERS_PRODBATCH vpm
			inner join VISITPERS vp on vp.VISPERS_SNR = vpm.VISPERS_SNR
			left outer join PRODFORMBATCH pfb on pfb.PRODFBATCH_SNR = vpm.PRODFBATCH_SNR
			inner join prodform pf on pf.PRODFORM_SNR = pfb.PRODFORM_SNR
			inner join product p on p.PROD_SNR = pf.PROD_SNR
			where vp.PERS_SNR = '".$idPersona."'
			and ".$year." = year(vp.VISIT_DATE)
			order by vp.VISIT_DATE ";
		$rsMuestras = sqlsrv_query($conn, $queryMuestras);
		echo "<script>
			$('#tblMuestraMedica tbody').empty();";
		
		//echo $queryMuestras;
		/*echo '<table border="0" width="100%" class="grid" id="tblMuestraMedica">
			<thead>
				<tr>
					<td>Fecha de Entrega</td>
					<td>Tipo de Material</td>
					<td>Producto</td>
					<td>Presentación</td>
					<td>Lote</td>
					<td>Cantidad</td>
				</tr>
			</thead>
			<tbody>';*/
		
		while($muestra = sqlsrv_fetch_array($rsMuestras)){
			foreach ($muestra['fecha'] as $key => $val) {
				if(strtolower($key) == 'date'){
					$fechaEntrega = substr($val, 0, 10);
				}
			}
			if($muestra['tipo'] == 132){
				$tipoMaterial = 'Muestra Médica';
			}else if($muestra['tipo'] == 133){
				$tipoMaterial = 'Competencia';
			}else if($muestra['tipo'] == 136){
				$tipoMaterial = 'Material';
			}
			echo "$('#tblMuestraMedica tbody').append('<tr><td style=\"width:13%;\">".$fechaEntrega."</td><td style=\"width:15%;\">".$tipoMaterial."</td><td style=\"width:15%;\">".$muestra['producto']."</td><td style=\"width:25%;\">".$muestra['presentacion']."</td><td style=\"width:20%;text-transform:capitalize;\">".$muestra['lote']."</td><td style=\"width:12%;\">".$muestra['cantidad']."</td></tr>');";
			/*echo '<tr>
					<td>'.$fechaEntrega.'</td>
					<td>'.$tipoMaterial.'</td>
					<td>'.$muestra['producto'].'</td>
					<td>'.$muestra['presentacion'].'</td>
					<td>'.$muestra['lote'].'</td>
					<td>'.$muestra['cantidad'].'</td>
				</tr>';*/
		}
			
		/*echo '</tbody>
		</table>';*/
		echo "</script>";
	}
?>