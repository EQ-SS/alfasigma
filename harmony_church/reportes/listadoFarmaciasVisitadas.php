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
	$tam = array(100,350,350,100,200,250,450,550,450,100, 300,250,200,100,250,250,150,150,150,150 ,100,200,150,100,150,250,250,550);
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
	
	if(isset($_POST['hdnEstatusInstListado']) && $_POST['hdnEstatusInstListado'] != ''){
		$estatus = $_POST['hdnEstatusInstListado'];
	}else{
		$estatus = '';
	}
	
	$query = "Select
		cl.name as Linea,
		upper(U.lname)+' '+upper(U.fname) as Representante,
		'{'+CAST(I.inst_snr as VARCHAR(36))+'}' as Codigo_inst,
		upper(T.name) as Tipo,
		upper(ST.name) as Sub_Tipo,
		upper(formato.name) as Formato,
		upper(I.name) as Nombre,
		upper(I.street1) as Direccion,
		City.name as Colonia,
		City.zip as Cod_Postal,
		Dst.name as Ciudad,
		State.name as Estado,
		status.name as Estatus,
		cat.name as Categoria,
		I.tel1 as Tel1,
		I.tel2 as Tel2,
		I.Latitude as Lat_Dir,
		I.Longitude as Long_Dir,
		VI.latitude as Lat_Vis,
		VI.longitude as Long_Vis,
		isnull((case when isnumeric(I.Latitude)>0 and I.latitude<>'' and isnumeric(I.longitude)>0 and I.longitude<>'' then 
		(case when isnumeric(VI.latitude)>0 and VI.latitude<>'' and VI.latitude<>'0.0' and I.latitude<>'0.0' and isnumeric(VI.longitude)>0 and VI.longitude<>'' then 
		(case when ABS(cast(I.latitude as float))-ABS(cast(VI.latitude as float))+ABS(cast(I.longitude as float))-ABS(cast(VI.longitude as float))=0 then '0' 
		else cast((ACOS(round((COS(RADIANS(90-cast(isnull(VI.latitude,'0') as float)))*COS(RADIANS(90-cast(isnull(I.latitude, '0') as float))))+(SIN(RADIANS(90-cast(isnull(VI.latitude,'0') as float)))*SIN(RADIANS(90-cast(isnull(I.latitude,'0') as float)))*COS(RADIANS( cast(isnull(I.longitude,'0') as float) -cast(isnull(VI.longitude,'0') as float) ))),7))*6371)*1000 as varchar(30))
		end) end)
		end),'N/A') as Diferencia_gps,
		(case when ABS(cast(I.latitude as float))-ABS(cast(VI.latitude as float))+ABS(cast(I.longitude as float))-ABS(cast(VI.longitude as float))=0 and I.latitude<>'' and VI.latitude<>'' then 'SIMILAR' else 
		(case when isnumeric(I.latitude)>0 and I.latitude<>'' and isnumeric(I.longitude)>0 and I.longitude<>'' then 
		(case when isnumeric(VI.latitude)>0 and VI.latitude<>'' and VI.latitude<> '0.0' then 
		(case when (ACOS(round((COS(RADIANS(90-cast(isnull(VI.latitude,'0') as float)))*COS(RADIANS(90-cast(isnull(I.latitude, '0') as float))))+(SIN(RADIANS(90-cast(isnull(VI.latitude,'0') as float)))*SIN(RADIANS(90-cast(isnull(I.latitude,'0') as float)))*COS(RADIANS( cast(isnull(I.longitude,'0') as float) -cast(isnull(VI.longitude,'0') as float) ))),7))*6371)*1000 <=100.999999 then 'SIMILAR' 
		when (ACOS(round((COS(RADIANS(90-cast(isnull(VI.latitude,'0') as float)))*COS(RADIANS(90-cast(isnull(I.latitude, '0') as float))))+(SIN(RADIANS(90-cast(isnull(VI.latitude,'0') as float)))*SIN(RADIANS(90-cast(isnull(I.latitude,'0') as float)))*COS(RADIANS( cast(isnull(I.longitude,'0') as float) -cast(isnull(VI.longitude,'0') as float) ))),7))*6371)*1000 between 101 and 200.999999 then 'DIFERENTE' 
		when (ACOS(round((COS(RADIANS(90-cast(isnull(VI.latitude,'0') as float)))*COS(RADIANS(90-cast(isnull(I.latitude, '0') as float))))+(SIN(RADIANS(90-cast(isnull(VI.latitude,'0') as float)))*SIN(RADIANS(90-cast(isnull(I.latitude,'0') as float)))*COS(RADIANS( cast(isnull(I.longitude,'0') as float) -cast(isnull(VI.longitude,'0') as float) ))),7))*6371)*1000 between 201 and 300.999999 then 'MUY DIFERENTE' 
		when (ACOS(round((COS(RADIANS(90-cast(isnull(VI.latitude,'0') as float)))*COS(RADIANS(90-cast(isnull(I.latitude, '0') as float))))+(SIN(RADIANS(90-cast(isnull(VI.latitude,'0') as float)))*SIN(RADIANS(90-cast(isnull(I.latitude,'0') as float)))*COS(RADIANS( cast(isnull(I.longitude,'0') as float) -cast(isnull(VI.longitude,'0') as float) ))),7))*6371)*1000 >=301 then 'EXTREMADAMENTE DIFERENTE' 
		else 'SIN GEOLOCALIZAR MEDICO' end) 
		else 'SIN GEOLOCALIZAR VISITA' end)
		else 'SIN GEOLOCALIZAR MEDICO' end) end) Estatus_Coordenadas,
		cast(cast(VI.visit_date as DATE) as nvarchar(10)) as Fecha_Visita,
		cast(VI.time as nvarchar(10)) as Hora_Visita,
		CONVERT(nvarchar(16), VI.creation_timestamp, 120) as Fecha_Ceacion,
		TipoVis.name as Tipo_Visita,
		VisAcomp.name as Visit_Acompa,
		VI.info as Coment_Vis
		
		
		from Inst I
		inner join Visitinst VI on VI.inst_snr =I.inst_snr
		inner join Users as U on U.user_snr =VI.user_snr
		inner join compline as cl on U.cline_snr=cl.cline_snr
		left outer join City on City.city_snr=I.city_snr
		inner join District as Dst on city.distr_snr=Dst.distr_snr
		inner join State on Dst.state_snr = State.state_snr
		left outer join codelist as T on I.type_snr = T.clist_snr
		left outer join codelist as ST on I.subtype_snr = ST.clist_snr	
		left outer join codelist as formato on I.format_snr = formato.clist_snr
		left outer join codelist status on I.status_snr=status.clist_snr
		left outer join codelist cat on I.category_snr=cat.clist_snr
		left outer join codelist TipoVis on TipoVis.clist_snr=VI.visit_code_SNR and TipoVis.rec_stat=0 and TipoVis.status=1
		left outer join codelist VisAcomp on cast(VisAcomp.clist_snr as nvarchar(36))=cast(VI.escort_snr as nvarchar(36)) and VisAcomp.rec_stat=0 and VisAcomp.status=1
		 
		 
		where
		I.inst_snr <> '00000000-0000-0000-0000-000000000000'
		and I.rec_stat=0
		and I.inst_type=2
		and VI.rec_stat=0
		and U.rec_stat=0
		and U.status=1
		/*and U.user_type=4*/
		and I.status_snr in ('".$estatus."')
		and U.user_snr in ('".$ids."')
		and VI.visit_date between '".$fechaI."' and '".$fechaF."' 
		 
		order by Cl.name,U.lname,U.fname,VI.visit_date,I.name,status.name ";


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
		$nombreArchivo = "../archivos/ListadoFarmaciasVisitadas".date("dmYHis").".xlsx";
		$spread = new Spreadsheet();
	
		$spread->getProperties()
					->setCreator("Smart-Scale")
					->setTitle("Listado")
					->setDescription("Listado de Farmacias Visitadas");
	}
	if($tipo == 1){//excel con formato
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=listadoFarmaciasVisitadas.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
	if($tipo == 3){
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array((array_sum($tam)/1.98),150));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('LISTADO DE FARMACIAS VISITADAS'));
		$pdf->Ln();
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(40,5,'Church & Dwight');
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
							<td colspan="10" class="nombreReporte">LISTADO DE FARMACIAS VISITADAS</td>
						</tr>
						<tr>
							<td colspan="10" class="clienteReporte">Church & Dwight</td>
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
            ->setCellValue('A1', 'LISTADO DE FARMACIAS VISITADAS')
			->setCellValue('A2', 'Church & Dwight')
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
						if($j < 43){
							$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.utf8_encode($registro[$j]).'</td>';
						}else{
							if(strlen($registro[$j]) > 80){
								$registro[$j] = substr($registro[$j], 0, 79)."...";
							}
							$tabla .= '<td style="min-width:'.$tam[$j].'px;max-width:'.$tam[$j].'px;text-overflow: ellipsis;">'.utf8_encode($registro[$j]).'</td>';
						}	
					}
				}
			}else{
				if(strlen($registro[$j]) > 80){
					$registro[$j] = substr($registro[$j], 0, 79)."...";
				}
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
			->setTitle('ListadoFarmaciasVisitadas');

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