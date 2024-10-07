<?php
	/*** listado de medicos ***/
	include "../conexion.php";
	
	header("Content-type: application/vnd.ms-excel; name='excel'");
	header("Content-Disposition: filename=listadoMedicos.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	/*$estatus = $_POS['estatus'];
	$ids = $_POST['ids'];*/
	$tamanio = array(1,4,4,4,2,2,2,2,3,4,6,2,2,2,4,4,2,3,3,2,2,3,3,2,2,2,2,2,2,2,2,2,2,1,4,2,4,2,4,3,2,2,2,2,2,2,5,2,2,2,2,1,2,2,2,2,2,2,1,2,2,2,1,2,3,2,2,2,2,2,3,2,2,2,3,2,2,2,1,2,1,2,1,2,1,2,1,2,1,2,2,2);
	$tam = array(100,250,250,250,100,100,100,100,150,250,350,100,100,100,250,250,100,150,150,100,50,150,150,100,100,100,100,100,100,100,100,100,100,50,200,100,200,100,200,150,100,100,100,100,100,100,300,100,100,100,100,50,100,100,100,100,100,100,50,100,100,100,50,100,150,100,100,100,100,100,150,100,100,100,150,100,100,100,50,100,50,100,50,100,50,100,50,100,50,100,100,100);
	$estatus = $_POST['hdnEstatus'];
	$ids = $_POST['hdnIDS'];
	
	include "queryListadoMedicos.php";
		
	$rsMedicos = sqlsrv_query($conn, utf8_decode($qMedicos));
	
	//echo $qMedicos;
?>
<table width="1000" border="0">
	<tr>
		<td>
			<table>
				<tr>
					<td colspan="10" >LISTADO DE MÉDICOS</td>
				</tr>
				<tr>
					<td colspan="10" >Alfa Wassermann</td>
				</tr>
				<tr>
					<td colspan="10" >Fecha: <?= date("d/m/Y h:i:s") ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<div id="divListadoMedicos">
<?php
			
				$cabeceras = '<table width="11800px"><thead><tr>';
				$i = 0;
				foreach(sqlsrv_field_metadata($rsMedicos) as $field){
					if($i < 47){
						$cabeceras .= '<td style="min-width:'.$tam[$i].'px;">'.$field['Name'].'</td>'; 
					}
					$i++;
				}
				$cabeceras .= '</tr></thead>';
				$cabeceras .= '<tbody>';
				$i=1;
				while($regMedico = sqlsrv_fetch_array($rsMedicos)){
					$cabeceras .= '<tr>';
					for($j=0;$j<sqlsrv_num_fields($rsMedicos);$j++){
						if(is_object($regMedico[$j])){
							foreach ($regMedico[$j] as $key => $val) {
								if(strtolower($key) == 'date'){
									$regMedico[$j] = substr($val, 0, 10);
								}
							}
						}
						if($j < 47){
							$cabeceras .= '<td style="min-width:'.$tam[$j].'px;white-space:nowrap;">'.$regMedico[$j].'</td>';
						}else{
							if($regMedico[$j] != '' && $regMedico[$j] != '-' && strtoupper($regMedico[$j]) != 'NINGUNO'){
								$aseguradoras[] = $regMedico[$j];
							}
						}
					
					}
					$cabeceras .= '</tr>';
					$i++;
				}
				$cabeceras .= '</tbody>
					<tfoot>
						<tr>
							<td colspan="'.sqlsrv_num_fields($rsMedicos).'">Total registros: '.$i.'</td>
						</tr>
					</tfoot>
					</table>';
				echo $cabeceras;
?>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="10" class="derechosReporte">© Smart-Scale</td>
	</tr>
</table>
