<?php
	/*** listado de medicos ***/
	include "../conexion.php";
	
	/*$serverName = "216.152.129.67"; //serverName\instanceName
	$connectionInfo = array( "Database"=>"AW_MX_PH_NET_TEST", "UID"=>"sa", "PWD"=>"saf");
	$conn = sqlsrv_connect( $serverName, $connectionInfo);*/
	
	header("Content-type: application/vnd.ms-excel; name='excel'");
	header("Content-Disposition: filename=listadoMedicos.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	/*$estatus = $_POS['estatus'];
	$ids = $_POST['ids'];*/
	$tamanio = array(1,4,4,4,2,2,2,2,3,4,6,2,2,2,4,4,2,3,3,2,2,3,3,2,2,2,2,2,2,2,2,2,2,1,4,2,4,2,4,3,2,2,2,2,2,2,5,2,2,2,2,1,2,2,2,2,2,2,1,2,2,2,1,2,3,2,2,2,2,2,3,2,2,2,3,2,2,2,1,2,1,2,1,2,1,2,1,2,1,2,2,2);
	$tam = array(100,250,250,250,100,100,100,100,150,250,350,100,100,100,250,250,100,150,150,100,50,150,150,100,100,100,100,100,100,100,100,100,100,50,200,100,200,100,200,150,100,100,100,100,100,100,300,100,100,100,100,50,100,100,100,100,100,100,50,100,100,100,50,100,150,100,100,100,100,100,150,100,100,100,150,100,100,100,50,100,50,100,50,100,50,100,50,100,50,100,100,100);
	$estatus = 'B426FB78-8498-4185-882D-E0DC381460E8';
	$ids = '64CA5A0A-8B3F-494B-B8A2-0EEFE8984293';
	
	include "queryListadoMedicos.php";
		
	$rsMedicos = sqlsrv_query($conn, utf8_decode($qMedicos));
		
	//echo $qMedicos;
		

?>
<table width="1000" border="0">
	<tr>
		<td>
			<table>
				<tr>
					<td colspan="10" style="font-size:16px;font-weight:bold;">LISTADO DE MÉDICOS</td>
				</tr>
				<tr>
					<td colspan="10" style="font-size:14px;font-weight:bold;">Alfa Wassermann</td>
				</tr>
				<tr>
					<td colspan="10" style="font-size:12px;">Fecha: <?= date("d/m/Y h:i:s") ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<div id="divListadoMedicos">
<?php
			
				$cabeceras = '<table class="tablaReportes" width="7000px"><thead><tr>';
				$i = 0;
				foreach(sqlsrv_field_metadata($rsMedicos) as $field){
					if($i < 47){
						$cabeceras .= '<td style="min-width:'.$tam[$i].'px;font-weight:bold;background-color: #A9BCF5;border: 1px solid #000;">'.$field['Name'].'</td>'; 
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
							$cabeceras .= '<td style="min-width:'.$tam[$j].'px;border: 1px solid #000;white-space:nowrap;">'.$regMedico[$j].'</td>';
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
							<td style="background-color: #A9BCF5;font-weight:bold;border: 1px solid #000;" colspan="'.sqlsrv_num_fields($rsMedicos).'">Total registros: '.$i.'</td>
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
