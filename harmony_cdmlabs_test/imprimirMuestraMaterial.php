<?php
	include "conexion.php";
	$idPersona = $_GET['idPersona'];
	$year = $_GET['year'];
	$queryMuestras = "select vp.VISIT_DATE as fecha, pf.type as tipo, p.NAME as producto,
			pf.name as presentacion, pfb.NAME as lote, vpm.quantity as cantidad,
			per.LNAME + ' ' + per.MOTHERS_LNAME + ' ' + per.FNAME as medico, 
			c.name as especialidad, u.LNAME + ' ' + u.MOTHERS_LNAME + ' ' + u.FNAME as repre
			from VISITPERS_PRODBATCH vpm
			inner join VISITPERS vp on vp.VISPERS_SNR = vpm.VISPERS_SNR
			left outer join PRODFORMBATCH pfb on pfb.PRODFBATCH_SNR = vpm.PRODFBATCH_SNR
			inner join prodform pf on pf.PRODFORM_SNR = pfb.PRODFORM_SNR
			inner join product p on p.PROD_SNR = pf.PROD_SNR
			inner join person per on per.pers_snr = vp.pers_snr
			inner join CODELIST c on c.CLIST_SNR = per.SPEC_SNR 
			inner join users u on u.USER_SNR = vp.USER_SNR
			where vp.PERS_SNR = '".$idPersona."'
			and year(getdate()) = year(vp.VISIT_DATE)
			order by vp.VISIT_DATE ";
		
		$rsMuestras = sqlsrv_query($conn, $queryMuestras, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		if(sqlsrv_num_rows($rsMuestras)){
			$tabla = '';
			while($muestra = sqlsrv_fetch_array($rsMuestras)){
				$medico = $muestra['medico'];
				$especialidad = $muestra['especialidad'];
				$repre = $muestra['repre'];
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
				
				$tabla .= '<tr>
					<td>'.$fechaEntrega.'</td>
					<td>'.$tipoMaterial.'</td>
					<td>'.$muestra['producto'].'</td>
					<td>'.$muestra['presentacion'].'</td>
					<td>'.$muestra['lote'].'</td>
					<td align="center">'.$muestra['cantidad'].'</td>
				</tr>';
			}
?>
			<html>
				<head>
				</head>
				<body>
					MEDICO: <?= $medico ?><br>
					ESPECIALIDAD: <?= $especialidad ?><br>
					REPRESENTANTE: <?= $repre ?><br>
					FECHA: <?= date("d-m-Y") ?>
					<table id="tblPlanesInicio" border="0" >
						<thead>
							<tr>
								<td>Fecha de Entrega</td>
								<td>Tipo material</td>
								<td>Producto</td>
								<td>Presentación</td>
								<td>Lote</td>
								<td>Cantidad</td>
							</tr>
						</thead>
						<tbody>
						<?= $tabla ?>
						</tbody>
					</table>
					<script>
						window.print();
					</script>
				</body>
			</html>
<?php
		}else{
?>
			<script>
				alert('No hay registros que imprimir');
				window.close();
			</script>
<?php	
		}
?>