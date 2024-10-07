<?php
	set_time_limit(0);
	//ini_set("memory_limit", "2056M");
	/*** listado de medicos ***/
	include "../conexion.php";
	$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
	$reemplazar=array(" ", " ", " ", " ");
	require ("../vendor/autoload.php");
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	
	$tamanio = array(1,4,4,4,2,2,2,2,3,4,6,2,2,2,4,4,2,3,3,2,2,3,3,2,2,2,2,2,2,2,2,2,2,1,4,2,4,2,4,3,2,2,2,2,2,2,5,2,2,2,2,1,2,2,2,2,2,2,1,2,2,2,1,2,3,2,2,2,2,2,3,2,2,2,3,2,2,2,1,2,1,2,1,2,1,2,1,2,1,2,2,2);
	$tam = array(100,350,350,350,150,150,200,200,300,100, 100,200,100,100,150,150,180,100,100,100, 100,100,100,100,100,100,100,100,100,100 ,100,100,100,100,100,100,100,100,100,100 ,100,100,100,100,100,100,100,100,100,100 ,100,100,100,100,100,100,100,100,100,100 ,100,100,100,100,100,100,100);
	$registrosPorPagina = 20;
	
	$ids = (substr($_POST['hdnIDSListado'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDSListado'], ",")) : $_POST['hdnIDSListado'] ;
	$tipo = $_POST['hdnTipoListado'];
	//$fechaI = $_POST['hdnFechaIListado'];
	//$fechaF = $_POST['hdnFechaFListado'];
	$ciclo = $_POST['hdnCicloListado'];
	if(isset($_POST['pagina']) && $_POST['pagina'] != ''){
		$numPagina = $_POST['pagina'];
	}else{
		$numPagina = 1;
	}
	
	if(isset($_POST['hdnEstatusListado']) && $_POST['hdnEstatusListado'] != ''){
		$estatus = $_POST['hdnEstatusListado'];
	}else{
		$estatus = '';
	}

	$qMedicos = "select Linea,
		Representante,
		Codigo_Med,
		Codigo_Inst,
		Tipo_Inst,
		Tipo_Pers,
		Paterno,
		Materno,
		Nombre,
		Estatus,
		Categ_AW,
		Especialidad,
		Fecha_Alta,
		Fecha_Ruta,
		Ciclo_Fecha_Ruta,
		Fecha_Reactivacion,
		Ciclo_Fecha_Reactivacion,
		Total,
		DIA_35,
		Vis_Dia_35,
		DIA_34,
		Vis_Dia_34,
		DIA_33,
		Vis_Dia_33,
		DIA_32,
		Vis_Dia_32,
		DIA_31,
		Vis_Dia_31,
		DIA_30,
		Vis_Dia_30,
		DIA_29,
		Vis_Dia_29,
		DIA_28,
		Vis_Dia_28,
		DIA_27,
		Vis_Dia_27,
		DIA_26,
		Vis_Dia_26,
		DIA_25,
		Vis_Dia_25,
		DIA_24,
		Vis_Dia_24,
		DIA_23,
		Vis_Dia_23,
		DIA_22,
		Vis_Dia_22,
		DIA_21,
		Vis_Dia_21,
		DIA_20,
		Vis_Dia_20,
		DIA_19,
		Vis_Dia_19,
		DIA_18,
		Vis_Dia_18,
		DIA_17,
		Vis_Dia_17,
		DIA_16,
		Vis_Dia_16,
		DIA_15,
		Vis_Dia_15,
		DIA_14,
		Vis_Dia_14,
		DIA_13,
		Vis_Dia_13,
		DIA_12,
		Vis_Dia_12,
		DIA_11,
		Vis_Dia_11,
		DIA_10,
		Vis_Dia_10,
		DIA_9,
		Vis_Dia_9,
		DIA_8,
		Vis_Dia_8,
		DIA_7,
		Vis_Dia_7,
		DIA_6,
		Vis_Dia_6,
		DIA_5,
		Vis_Dia_5,
		DIA_4,
		Vis_Dia_4,
		DIA_3,
		Vis_Dia_3,
		DIA_2,
		Vis_Dia_2,
		DIA_IN,
		Vis_Dia_In,
		Ciclo13,
		Visitas_C13,
		Ciclo12,
		Visitas_C12,
		Ciclo11,
		Visitas_C11,
		Ciclo10,
		Visitas_C10,
		Ciclo9,
		Visitas_C9,
		Ciclo8,
		Visitas_C8,
		Ciclo7,
		Visitas_C7,
		Ciclo6,
		Visitas_C6,
		Ciclo5,
		Visitas_C5,
		Ciclo4,
		Visitas_C4,
		Ciclo3,
		Visitas_C3,
		Ciclo2,
		Visitas_C2,
		Ciclo1,
		Visitas_C1
		from lst_medicos_vis_hist_total 
		where user_snr in ('".$ids."') ";
		
		if($estatus != ''){
			$qMedicos .= "and status_snr in ('".$estatus."') ";
		}
		
		$qMedicos .= "order by Representante,Paterno,Materno,Nombre ";

		
	//echo $qMedicos."<br>";
	
	if($tipo == 0){
		$registroIni = $numPagina * $registrosPorPagina - $registrosPorPagina;
		
		$tope = "OFFSET ".$registroIni." ROWS 
			FETCH NEXT ".$registrosPorPagina." ROWS ONLY ";
				
		$rsMedicosTotal = sqlsrv_query($conn, utf8_decode($qMedicos), array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		
		$totalRegistros = sqlsrv_num_rows($rsMedicosTotal);

		$rsMedicos = sqlsrv_query($conn, utf8_decode($qMedicos.$tope));
			
		$paginas = ceil($totalRegistros / $registrosPorPagina);
			
		//echo $qMedicos.$tope."<br>";
			
	}else{
		$rsMedicos = sqlsrv_query($conn, utf8_decode($qMedicos), array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	}
	
	if($tipo == 2){//excel sin formato
		$nombreArchivo = "../archivos/listadoHistoricoMedicosVisitadosCicloTotal".date("dmYHis").".xlsx";
		$spread = new Spreadsheet();	
		$spread->getProperties()
					->setCreator("Smart-Scale")
					->setTitle("Listado")
					->setDescription("Listado Historico de Medicos Visitados por Ciclo Contacto Total");
	}
	if($tipo == 1){//excel con formato
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=listadoHistoricoMedicosVisitadosCicloTotal.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
	if($tipo == 3){
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array((array_sum($tam)/2),150));
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('LISTADO HISTÓRICO DE MÉDICOS VISITADOS POR CICLO CONTACTO TOTAL'));
		$pdf->Ln();
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(40,5,'ALFASIGMA');
		$pdf->Ln();
		$pdf->SetFont('Arial',10);
		$pdf->Cell(40,5,'Fecha: '.date("d/m/Y h:i:s"));
		$pdf->Ln();		
	}
	
	//$rsMedicos = sqlsrv_query($conn, utf8_decode($qMedicos));
	if( $rsMedicos === false ) {
		if( ($errors = sqlsrv_errors() ) != null) {
			foreach( $errors as $error ) {
				echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
				echo "code: ".$error[ 'code']."<br />";
				echo "message: ".$error[ 'message']."<br />";
			}
		}
	}
		
	$tamTabla = array_sum($tam) + 20;
	if($tipo == 0 || $tipo == 1){
		$tabla = '<table border="0">
			<tr>
				<td>
					<table>
						<tr>
							<td colspan="10" class="nombreReporte">LISTADO HISTÓRICO DE MÉDICOS VISITADOS POR CICLO CONTACTO TOTAL</td>
						</tr>
						<tr>
							<td colspan="10" class="clienteReporte">ALFASIGMA</td>
						</tr>
						<tr>
							<td colspan="10" class="fechareporte">Fecha: '. date("d/m/Y h:i:s") .'</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<div id="divListadoMedicos">';
						if($tipo == 0){
							$tabla .= '<table id="tblListadoMedicos" width="'.$tamTabla.'px" class="tablaReportes" >';
						}else{
							$tabla .= '<table width="'.$tamTabla.'px" style="border-collapse: collapse;">';
						}
	}
	
	if($tipo == 2){
		$spread->setActiveSheetIndex(0)
            ->setCellValue('A1', 'LISTADO HISTÓRICO DE MÉDICOS VISITADOS POR CICLO CONTACTO TOTAL')
			->setCellValue('A2', 'ALFASIGMA')
			->setCellValue('A3', 'Fecha: '. date("d/m/Y h:i:s"));
	}	
	
	if($tipo != 3){
		if($tipo == 1){
			$tabla .= '<thead style="background-color: #A9BCF5;font-weight:bold;border: 1px solid #000;padding: 5px 5px 5px 5px;color:#000"><tr>';
		}
		if($tipo == 0){
			$tabla .= '<thead><tr>';
		}
	}else{
		$pdf->SetFillColor(169,188,245);
			$pdf->SetTextColor(0);
			$pdf->SetDrawColor(0);
			$pdf->SetLineWidth(1);
			$pdf->SetFont('','B');
	}
					
	$i = 0;
	$cabeceras = array();
	foreach(sqlsrv_field_metadata($rsMedicos) as $field){
		$celda = columna($i)."4";
		if($i <= 17){
			if($tipo != 3){
				if($tipo == 2){
					$spread->setActiveSheetIndex(0)
						->setCellValue($celda, utf8_encode($field['Name']));
				}else{
					if($tipo == 1){
						$tabla .= '<td style="background-color: #A9BCF5;border: 1px solid #000;min-width:'.$tam[$i].'px;">'.utf8_encode($field['Name']).'</td>';
					}else{
						$tabla .= '<td style="min-width:'.$tam[$i].'px;">'.utf8_encode($field['Name']).'</td>';
					}
				}	
			}else{
				$pdf->Cell($tam[$i]/2,8,utf8_encode($field['Name']),1,0,'C',1);
			}
			$k = $i;
		}else{
			if($i % 2 == 0){
				$cabeceras[] = utf8_encode($field['Name']);
			}
		}
		$i++;
	}
	$k++;
	
	$i=1;
	while($regMedico = sqlsrv_fetch_array($rsMedicos)){
		if($i == 1){
			$k++;
			for($l = count($cabeceras)-1; $l >= 0; $l--){ 
				if ($regMedico[$cabeceras[$l]] != 'A'){
					if($tipo != 3){
						if($tipo == 2){
							$row = $i + 4;
							$spread->setActiveSheetIndex(0)
								->setCellValue(columna($j).$row, utf8_encode($regMedico[$cabeceras[$l]]));
						}else{
							if($tipo == 1){
								$tabla .= '<td style="background-color: #A9BCF5;border: 1px solid #000;min-width:'.$tam[$k].'px;">'.utf8_encode($regMedico[$cabeceras[$l]]).'</td>';
							}else{
								$tabla .= '<td style="min-width:'.$tam[$k].'px;">'.utf8_encode($regMedico[$cabeceras[$l]]).'</td>';
							}
						}
					}else{
						$pdf->Cell($tam[$k]/2,8,utf8_encode($regMedico[$cabeceras[$l]]),1,0,'C',1);
					}
					$k++;					
				}
			}
			
			if($tipo == 0 || $tipo == 1){
				$tabla .= '</tr></thead>';
				$tabla .= '<tbody style="height:345px;">';
			}
			if($tipo == 3){
				$pdf->Ln();
				//Restauración de colores y fuentes
				$pdf->SetFillColor(224,235,255);
				$pdf->SetTextColor(0);
				$pdf->SetFont('');
				//Datos
				$fill=false;
			}			
		}
		
		if($tipo == 0 || $tipo == 1){
			$tabla .= '<tr>';
		}
		 
		$visitasArr = array();
		for($j=0;$j<sqlsrv_num_fields($rsMedicos);$j++){
			if(is_object($regMedico[$j])){
				foreach ($regMedico[$j] as $key => $val) {
					if(strtolower($key) == 'date'){
						$regMedico[$j] = substr($val, 0, 10);
					}
				}
			}
			
			if($j <= 17){
				if($tipo != 3){
					if($tipo == 2){
						$row = $i + 4;
						$spread->setActiveSheetIndex(0)
							->setCellValue(columna($j).$row, utf8_encode($regMedico[$j]));
					}else{
						if($tipo == 1){
							$tabla .= '<td style="border: 1px solid #000;white-space:nowrap;min-width:'.$tam[$j].'px;">'.utf8_encode($regMedico[$j]).'</td>';
						}else{
							$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.utf8_encode($regMedico[$j]).'</td>';
						}
					}
				}else{
					$pdf->Cell($tam[$j]/2,8,utf8_encode($regMedico[$j]),1,0,'L',$fill);
				}
			}else{
				if($j % 2 != 0){
					$visitasArr[] = $regMedico[$j];
					$k=19;
				}
			}
		}
		
		//print_r($visitasArr);
		//echo array_sum($visitasArr).'<br>';
		
		for($m = count($visitasArr)-1; $m >= 0; $m--){
			if ($visitasArr[$m] != 9){
				if($tipo != 3){
					if($tipo == 2){
						$row = $i + 4;
						$spread->setActiveSheetIndex(0)
							->setCellValue(columna($j).$row, $visitasArr[$m]);
					}else{
						if($tipo == 1){
							$tabla .= '<td style="border: 1px solid #000;white-space:nowrap;min-width:'.$tam[$k].'px;">'.$visitasArr[$m].'</td>';
						}else{
							$tabla .= '<td style="min-width:'.$tam[$k].'px;">'.$visitasArr[$m].'</td>';
						}
					}
				}else{
					$pdf->Cell($tam[$k]/2,8,$visitasArr[$m],1,0,'L',$fill);
				}
				$k++;
			}
		}
		//$tabla .= '<td>raton</td>';
		
		if($tipo == 0 || $tipo == 1){
			$tabla .= '</tr>';
		}
		if($tipo == 3){
			$pdf->Ln();
			if($fill == true){
				$fill = false;
			}else{
				$fill = true;
			}
		}
		$i++;
	}
	
	if($tipo == 0){
		$numRegs = $i - 1;
		$tabla .= '<table width="100%" id="tblPaginasListadoMedicos"><tr style="background-color: #A9BCF5;font-weight:bold;border: 1px solid #000;padding: 5px 5px 5px 5px;color:#000;"><td align="center">';
		if($totalRegistros > $registrosPorPagina){
			$idsEnviar = str_replace("'","",$ids);
			if($numPagina > 1){
				$anterior = $numPagina - 1;
				$tabla .= "<a href='#' onClick='nuevaPaginaListados(1,\"".$idsEnviar."\",\"listadoMedicos\",\"".$estatus."\");'>inicio</a>&nbsp;&nbsp;";
				$tabla .= "<a href='#' onClick='nuevaPaginaListados(".$anterior.",\"".$idsEnviar."\",\"listadoMedicos\",\"".$estatus."\");'>anterior</a>&nbsp;&nbsp;";
			}
			$antes = $numPagina-5;
			$despues = $numPagina+5;
			for($i=1;$i<=$paginas;$i++){
				if($i == $numPagina){
					$tabla .= $i."&nbsp;&nbsp;";
				}else{
					if($i > $despues || $i < $antes){
						//$tabla .= "<a href='#' onClick='nuevaPagina(".$i.",\"".$hoy."\",\"".$idsEnviar."\",\"".$visitados."\");'>".$i."</a>&nbsp;&nbsp;";
					}
				}
			}
			if($numPagina < $paginas){
				$siguiente = $numPagina + 1;
				$tabla .= "<a href='#' onClick='nuevaPaginaListados(".$siguiente.",\"".$idsEnviar."\",\"listadoMedicos\",\"".$estatus."\");'>Siguiente</a>&nbsp;&nbsp;";
				$tabla .= "<a href='#' onClick='nuevaPaginaListados(".$paginas.",\"".$idsEnviar."\",\"listadoMedicos\",\"".$estatus."\");'>Fin</a>&nbsp;&nbsp;";
			}
			$tabla .= "Pag. ".$numPagina." de ".$paginas."&nbsp;&nbsp;&nbsp; Registros : ".$totalRegistros;
			//$tabla .= "</td></tr></tfoot>";
		}else{
			//$tabla .= "<tfoot><tr><td colspan='16' align='center'>";
			$tabla .= "Registros : ".$totalRegistros;
			//$tabla .= "</td></tr></tfoot>";
		}						
		$tabla .= '</td></tr>
		<tr>
			<td colspan="10" class="derechosReporte">© Smart-Scale</td>
		</tr>
	</table>';
		echo $tabla;
	}
	
	$row = $i+4;
	if($tipo == 2){
		$spread->setActiveSheetIndex(0)
			->setCellValue('A'.$row, 'Total registros: '.sqlsrv_num_rows($rsMedicos))
			->setTitle('listadoHistoricoMedicosVisitadosCicloTotal');

		$spread->setActiveSheetIndex(0);

		$objWriter = new Xlsx($spread);

		$objWriter->save($nombreArchivo);
		//header("Location: ".$nombreArchivo);
		echo "fin";
	}
	if($tipo == 1){
		$tabla .= '</tbody> ';
		$tabla .= '<tfoot style="background-color: #A9BCF5;font-weight:bold;border: 1px solid #000;padding: 5px 5px 5px 5px;color:#000;">';

		$numRegs = $i - 1;
				$tabla .= '<tr>
								<td colspan="10">Total registros: '.$numRegs.'</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="10" class="derechosReporte">© Smart-Scale</td>
		</tr>
	</table>';
		echo $tabla;
	}
	if($tipo == 3){
		$pdf->Output();
	}	
	if($tipo == 0){
		echo '<script>
			$("#divCargando").hide();
			$("#hdnQueryListado").val("'.str_replace("'","\'",str_ireplace($buscar,$reemplazar,$qMedicos)).'");
		</script>';
		//echo str_replace("'","\'",$qMedicos);
	}
?>

<style>
	#divListadoMedicos{
		overflow:scroll;
		height:440px;
		width:1330px;
	}
</style>