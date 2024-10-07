<?php
	set_time_limit(0);
	//ini_set("memory_limit", "2056M");
	/*** listado de farmacias ***/
	include "../conexion.php";
	$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
	$reemplazar=array(" ", " ", " ", " ");
	require ("../vendor/autoload.php");
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	
	$tamanio = array(1,4,4,4,2,2,2,2,3,4,6,2,2,2,4,4,2,3,3,2,2,3,3,2,2,2,2,2,2,2,2,2,2,1,4,2,4,2,4,3,2,2,2,2,2,2,5,2,2,2,2,1,2,2,2,2,2,2,1,2,2,2,1,2,3,2,2,2,2,2,3,2,2,2,3,2,2,2,1,2,1,2,1,2,1,2,1,2,1,2,2,2);
	$tam = array(100,350,350,100,200,450,550,450,100,250, 250,200,100,150);
	$registrosPorPagina = 20;

	$ids = (substr($_POST['hdnIDSListado'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDSListado'], ",")) : $_POST['hdnIDSListado'] ;
	$tipo = $_POST['hdnTipoListado'];
	$fechaI = $_POST['hdnFechaIListado'];
	$fechaF = $_POST['hdnFechaFListado'];
	if(isset($_POST['pagina']) && $_POST['pagina'] != ''){
		$numPagina = $_POST['pagina'];
	}else{
		$numPagina = 1;
	}
	
	$estatus = $_POST['hdnEstatusInstListado'];
	
	$query = "Select
		cl.name as Linea,
		upper(U.lname)+' '+upper(U.fname) as Representante,
		'{'+CAST(I.inst_snr as VARCHAR(36))+'}' as Codigo_Inst,
		upper(T.name) as Tipo,
		upper(ST.name) as Sub_Tipo,
		upper(I.name) as Nombre,
		/*upper(I.branch) as Sucursal,*/
		upper(I.street1) as Direccion,
		City.name as Colonia,
		City.zip as Cod_Postal,
		/*IMS.name as Brick,*/
		Dst.name as Ciudad,
		State.name as Estado,
		status.name as Estatus,
		cat.name as Categ,
		CAST(CAST(aps.approved_date as DATE) as VARCHAR(10)) as Fecha_Baja
		 
		from Inst I
		inner join Approval_status as aps on aps.record_key=I.inst_snr 
		inner join User_Territ as UT on UT.inst_snr =I.inst_snr
		inner join Users as U on U.user_snr =UT.user_snr
		inner join compline as cl on U.cline_snr=cl.cline_snr
		left outer join City on City.city_snr=I.city_snr
		inner join District as Dst on city.distr_snr=Dst.distr_snr
		inner join State on Dst.state_snr = State.state_snr
		inner join Brick as IMS on IMS.brick_snr = City.brick_snr
		left outer join codelist as T on I.type_snr = T.clist_snr
		left outer join codelist as ST on I.subtype_snr = ST.clist_snr
		left outer join codelist as formato on I.format_snr = formato.clist_snr
		left outer join codelist status on I.status_snr=status.clist_snr
		left outer join codelist cat on I.category_snr=cat.clist_snr
		 
		where
		I.inst_snr <> '00000000-0000-0000-0000-000000000000'
		and I.rec_stat=2
		and I.inst_type=1
		and UT.rec_stat=0
		and U.rec_stat=0
		and U.status=1
		and U.user_type=4
		/*and I.status_snr = 'F01844BD-3288-4F2A-9C56-382C3B78D6F4' */
		and U.user_snr in ('".$ids."')
		and aps.approved_date between '".$fechaI."' and '".$fechaF."' 
		and aps.rec_stat=0 
		and aps.table_nr=492
		and aps.approved_status=2
		and aps.movement_type='D'
		
		order by Cl.name,U.lname,U.fname,I.name ";
	
	
	//echo $query."<br>";
		
	if($tipo == 0){
		$registroIni = $numPagina * $registrosPorPagina - $registrosPorPagina;
		
		$tope = "OFFSET ".$registroIni." ROWS 
			FETCH NEXT ".$registrosPorPagina." ROWS ONLY ";
				
		$rsInstTotal = sqlsrv_query($conn, utf8_decode($query), array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		
		$totalRegistros = sqlsrv_num_rows($rsInstTotal);

		$rsInst = sqlsrv_query($conn, utf8_decode($query.$tope));
			
		$paginas = ceil($totalRegistros / $registrosPorPagina);
			
		//echo $query.$tope;
			
	}else{
		$rsInst = sqlsrv_query($conn, utf8_decode($query), array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	}

	if($tipo == 2){//excel sin formato
		$nombreArchivo = "../archivos/ListadoHospitalesBajas".date("dmYHis").".xlsx";
		$spread = new Spreadsheet();
	
		$spread->getProperties()
					->setCreator("Smart-Scale")
					->setTitle("Listado")
					->setDescription("Listado Bajas de Hospitales");
	}
	if($tipo == 1){//excel con formato
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=listadoHospitalesBajas.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
	if($tipo == 3){
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array((array_sum($tam)/1.95),150));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('LISTADO BAJAS DE HOSPITALES'));
		$pdf->Ln();
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(40,5,'Consorcio Dermatológico de México');
		$pdf->Ln();
		$pdf->SetFont('Arial',10);
		$pdf->Cell(40,5,'Fecha: '.date("d/m/Y h:i:s"));
		$pdf->Ln();
	}
	
	//$rsInst = sqlsrv_query($conn, utf8_decode($query));
	if( $rsInst === false ) {
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
							<td colspan="10" class="nombreReporte">LISTADO BAJAS DE HOSPITALES</td>
						</tr>
						<tr>
							<td colspan="10" class="clienteReporte">Consorcio Dermatológico de México</td>
						</tr>
						<tr>
							<td colspan="10" class="fechaReporte">Fecha: '. date("d/m/Y h:i:s") .'</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<div id="divReportesRepo">';
						if($tipo == 0){
							$tabla .= '<table id="tblListadoInst" width="'.$tamTabla.'px" class="tablaReportes">';
						}else{
							$tabla .= '<table width="'.$tamTabla.'px" style="border-collapse: collapse;">';
						}
	}

	if($tipo == 2){
		$spread->setActiveSheetIndex(0)
            ->setCellValue('A1', 'LISTADO BAJAS DE HOSPITALES')
			->setCellValue('A2', 'Consorcio Dermatológico de México')
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
	foreach(sqlsrv_field_metadata($rsInst) as $field){
		$celda = columna($i)."4";
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
			$pdf->Cell($tam[$i]/2,8,$field['Name'],1,0,'C',1);
		}
		$i++;
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

	$i=1;
	while($registro = sqlsrv_fetch_array($rsInst)){
		//echo "registro: ".$i."<br>";
		if($tipo == 0 || $tipo == 1){
			$tabla .= '<tr>';
		}

		for($j=0;$j<sqlsrv_num_fields($rsInst);$j++){
			if(is_object($registro[$j])){
				foreach ($registro[$j] as $key => $val) {
					if(strtolower($key) == 'date'){
						$registro[$j] = substr($val, 0, 10);
					}
				}
			}

			if($tipo != 3){
				if($tipo == 2){
					$row = $i + 4;
					$spread->setActiveSheetIndex(0)
						->setCellValue(columna($j).$row, utf8_encode($registro[$j]));
				}else{
					if($tipo == 1){
						$tabla .= '<td style="border: 1px solid #000;white-space:nowrap;min-width:'.$tam[$j].'px;">'.utf8_encode($registro[$j]).'</td>';
					}else{
						$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.utf8_encode($registro[$j]).'</td>';
					}
				}
			}else{
				$pdf->Cell($tam[$j]/2,8,utf8_encode($registro[$j]),1,0,'L',$fill);
			}
		}

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
				$tabla .= "<a href='#' onClick='nuevaPaginaListados(1,\"".$idsEnviar."\",\"listadoInst\",\"".$estatus."\");'>inicio</a>&nbsp;&nbsp;";
				$tabla .= "<a href='#' onClick='nuevaPaginaListados(".$anterior.",\"".$idsEnviar."\",\"listadoInst\",\"".$estatus."\");'>anterior</a>&nbsp;&nbsp;";
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
				$tabla .= "<a href='#' onClick='nuevaPaginaListados(".$siguiente.",\"".$idsEnviar."\",\"listadoInst\",\"".$estatus."\");'>Siguiente</a>&nbsp;&nbsp;";
				$tabla .= "<a href='#' onClick='nuevaPaginaListados(".$paginas.",\"".$idsEnviar."\",\"listadoInst\",\"".$estatus."\");'>Fin</a>&nbsp;&nbsp;";
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
	//echo 'A'.$row;
	if($tipo == 2){
		$spread->setActiveSheetIndex(0)
            ->setCellValue('A'.$row, 'Total registros: '.sqlsrv_num_rows($rsInst))
			->setTitle('ListadoHospitalesBajas');

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
			$("#hdnQueryListado").val("'.str_replace("'","\'",str_ireplace($buscar,$reemplazar,$query)).'");
		</script>';
		//echo str_replace("'","\'",$query);
	}
?>