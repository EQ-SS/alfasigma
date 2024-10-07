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
	$tam = array(100,350,350,350,150,150,250,250,250,600, 100,150,450,100,250,300,200,200,100,100, 250,250,200,200,100,200,200,300,150,100, 150,150,150,150,200,150,150,150,150,150, 250,100,150,2500,2500);
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
	
	if(isset($_POST['hdnEstatusListado']) && $_POST['hdnEstatusListado'] != ''){
		$estatus = $_POST['hdnEstatusListado'];
	}else{
		$estatus = '';
	}
	
	$qMedicos = "Select DISTINCT
		/*vp.VISPERS_SNR,*/
		cl.name as Linea,
		upper(U.lname)+' '+upper(U.fname) as Representante,
		'{'+CAST(VP.pers_snr AS varchar(36))+'}' as Cod_Med,
		'{'+CAST(I.inst_snr AS VARCHAR(36))+'}' as Cod_Inst,
		upper(IT.NAME) AS Tipo_Inst,
		upper(type.name) as Tipo_Cons,
		upper(P.lname) Paterno,
		upper(P.mothers_lname) Materno,
		upper(P.fname) as Nombre,
		upper(I.street1) as Direccion,
		I.num_ext as Num_Ext,
		PLW.num_int as Num_Int,
		/*IMS.name as Brick,*/
		City.name as Colonia,
		City.zip as Cod_Postal,
		Dst.name as Ciudad,
		State.name as Estado,
		ST.name as Estatus,
		SEXO.name as Sexo,
		CATEG.name as Categ,
		FV.name as Frec_vis,
		ESP.name as Esp,
		ESP2.name as Sub_Esp,
		HON.name as Hon,
		PT.name as Pac,
		case when year(P.birthdate) > 0 then year(getdate()) - year(P.birthdate) else '' end as Edad,
		/*DIFVIS.name as Difvis,*/
		P.tel1 as Tel1,
		P.tel2 as Tel2,
		P.mobile as Celular,
		cast(cast(VP.visit_date as DATE) as nvarchar(10)) as Fecha_Visita,
		cast(VP.time as nvarchar(10)) as Hora_Visita,
		/*(SELECT PLAN_DATE FROM VISPERSPLAN WHERE vispersplan_snr=VP.NEXTVPP_SNR AND REC_STAT=0) AS Fecha_Sig_Vis,*/
		CONVERT(nvarchar(16), VP.creation_timestamp, 120) as Fecha_Creacion,
		cast(cast(VP.CHANGED_TIMESTAMP as DATE) as nvarchar(10)) as 'Fecha Modificación',
		cast(cast(VP.SYNC_TIMESTAMP as DATE) as nvarchar(10)) as 'Fecha Sincronización',
		VA2.name as 'Visita Acompañada',
		VisCode.name as 'Tipo de Visita'
		,VP.latitude as Lat_Vis
		,VP.longitude as Long_Vis
		,PLW.Latitude as Lat_Dir
		,PLW.Longitude as Long_Dir
		,'' as Diferencia_gps
		,'' as Estatus_coordenadas
		,case when VPLAN.PLAN_DATE <> ' ' then 'SI' else 'NO' end as Cumplio_Plan
		,cast(cast(VPLAN.PLAN_DATE as DATE) as nvarchar(10)) as Fecha_Plan
		,VP.info_nextvisit as 'Info Siguiente Visita'
		,VP.info as 'Comentario Resultado de la Visita'
				 
		/*(case when CAST(VP.Visit_Date as date) in (select cd.c_date from cycle_details cd
		where cd.rec_stat = 0
		AND CD.C_DATE=VP.Visit_Date and cd.c_day = 0) then 'DIA NO HABIL' else '' end) as Vis_Dia_Habil*/

		from visitpers VP
		inner join person P on VP.pers_snr = P.pers_snr
		inner join perslocwork PLW on P.pers_snr = PLW.pers_snr and PLW.rec_stat=0
		inner join inst I on I.inst_snr = PLW.INST_SNR
		left outer join inst_Type IT on IT.inst_type=I.inst_type and IT.rec_Stat=0
		inner join pers_srep_work PSW on PSW.pwork_snr=PLW.pwork_SNR and PSW.rec_stat=0
		left outer join City on City.city_snr = I.city_snr
		inner join District as Dst on city.distr_snr = Dst.distr_snr
		inner join State on Dst.state_snr = State.state_snr
		left outer join Brick as IMS on IMS.brick_snr = City.brick_snr
		inner join User_territ UT on UT.inst_snr = I.inst_snr and UT.rec_stat=0
		inner join Users as U on U.user_snr = VP.user_snr
		inner join compline as cl on U.cline_snr = cl.cline_snr
		 
		left outer join codelist type on I.type_snr = type.clist_snr
		left outer join codelist ST on P.status_snr = ST.clist_snr
		left outer join codelist SEXO on P.sex_snr = SEXO.clist_snr
		left outer join codelist ESP on P.spec_snr = ESP.clist_snr
		left outer join codelist ESP2 on P.subspec_snr = ESP2.clist_snr
		left outer join codelist HON on P.fee_type_snr = HON.clist_snr
		left outer join codelist PT on P.patperweek_snr = PT.clist_snr
		left outer join codelist DIFVIS on P.diffvis_snr = DIFVIS.clist_snr
		left outer join codelist FV on P.frecvis_snr = FV.clist_snr
		left outer join codelist VA2 on CAST(VP.escort_snr AS VARCHAR(36)) = CAST(VA2.clist_snr AS VARCHAR(36))
		LEFT OUTER JOIN VISPERSPLAN VPLAN ON VPLAN.vispersplan_snr = VP.vispersplan_snr
		 
		left outer join codelist CATEG on P.category_snr = CATEG.clist_snr AND CATEG.STATUS=1
		left outer join codelist VisCode on VisCode.clist_snr=VP.visit_code_SNR and VisCode.rec_stat=0 
		 
		where
		VP.pers_snr <> '00000000-0000-0000-0000-000000000000'
		and P.pers_snr <> '00000000-0000-0000-0000-000000000000'
		and I.inst_snr <> '00000000-0000-0000-0000-000000000000'
		and P.rec_stat=0
		and VP.rec_stat=0
		and VP.user_snr=U.user_snr
		and U.rec_stat=0
		and U.status=1
		and U.user_type=4
		and P.status_snr in ('".$estatus."')
		and U.user_snr in ('".$ids."')
		and VP.visit_date between '".$fechaI."' and '".$fechaF."' ";


	//echo $qMedicos."<br>";

	if($tipo == 0){
		$registroIni = $numPagina * $registrosPorPagina - $registrosPorPagina;
		
		$tope = "OFFSET ".$registroIni." ROWS 
			FETCH NEXT ".$registrosPorPagina." ROWS ONLY ";
				
		$rsMedicosTotal = sqlsrv_query($conn, utf8_decode($qMedicos), array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		
		$totalRegistros = sqlsrv_num_rows($rsMedicosTotal);

		//$rsMedicos = sqlsrv_query($conn, utf8_decode($qMedicos.$tope));
		$rsMedicos = sqlsrv_query($conn, utf8_decode($qMedicos));

		$paginas = ceil($totalRegistros / $registrosPorPagina);
			
		//echo $qMedicos.$tope;
			
	}else{
		$rsMedicos = sqlsrv_query($conn, utf8_decode($qMedicos), array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	}

	if($tipo == 2){//excel sin formato
		$nombreArchivo = "../archivos/ListadoMedicosVisitados".date("dmYHis").".xlsx";
		$spread = new Spreadsheet();
	
		$spread->getProperties()
					->setCreator("Smart-Scale")
					->setTitle("Listado")
					->setDescription("Listado de Medicos Visitados");
	}
	if($tipo == 1){//excel con formato
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=listadoMedicosVisitados.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
	if($tipo == 3){
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array((array_sum($tam)/1.98),150));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('LISTADO DE MÉDICOS VISITADOS'));
		$pdf->Ln();
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(40,5,'Consorcio Dermatológico de México');
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
	//echo $qMedicos;
	$tamTabla = array_sum($tam) + 20;
	if($tipo == 0 || $tipo == 1){
		$tabla = '<table border="0">
			<tr>
				<td>
					<table>
						<tr>
							<td colspan="10" class="nombreReporte">LISTADO DE MÉDICOS VISITADOS</td>
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
					<div id="divListadoMedicos">';
						if($tipo == 0){
							$tabla .= '<table id="tblListadoMedicos" width="'.$tamTabla.'px" class="tablaReportes">';
						}else{
							$tabla .= '<table width="'.$tamTabla.'px" style="border-collapse: collapse;">';
						}
	}

	if($tipo == 2){
		$spread->setActiveSheetIndex(0)
            ->setCellValue('A1', 'LISTADO DE MÉDICOS VISITADOS')
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
	foreach(sqlsrv_field_metadata($rsMedicos) as $field){
		$celda = columna($i)."4";
		if($i < 57){
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
	while($regMedico = sqlsrv_fetch_array($rsMedicos)){
		//echo "registro: ".$i."<br>";
		if($tipo == 0 || $tipo == 1){
			$tabla .= '<tr>';
		}

		for($j=0;$j<sqlsrv_num_fields($rsMedicos);$j++){
			if(is_object($regMedico[$j])){
				foreach ($regMedico[$j] as $key => $val) {
					if(strtolower($key) == 'date'){
						$regMedico[$j] = (substr($val, 0, 10)=='1900-01-01') ? '' : substr($val, 0, 10);
					}
				}
			}
			
			if($j == 39){
				if( ($regMedico[35] != '') && ($regMedico[35] != '0.0') ){
					if( ($regMedico[37] != '') && ($regMedico[37] != '0.0') ){
						$total_latitud = abs( number_format( $regMedico[35] - $regMedico[37] , 4) );
					}else{
						$total_latitud = 0;
					}
				}else{ 
					$total_latitud = 0;
				}
				
				if( ($regMedico[36] != '') && ($regMedico[36] != '0.0') ){
					if( ($regMedico[38] != '') && ($regMedico[38] != '0.0') ){
						$total_langitud = abs( number_format( $regMedico[36] - $regMedico[38] , 4) );
					}else{
						$total_langitud = 0;
					}
				}else{ 
					$total_langitud = 0;
				}
				
				$regMedico[$j] = $total_latitud + $total_langitud;
			}
			if($j == 40){
				if( ($regMedico[35] != '') && ($regMedico[35] != '0.0') && ($regMedico[36] != '') && ($regMedico[36] != '0.0') ){
					if( ($regMedico[37] != '') && ($regMedico[37] != '0.0') && ($regMedico[38] != '') && ($regMedico[38] != '0.0') ){
						if($regMedico[39] < 0.001){ 
							$regMedico[$j] = 'SIMILAR'; 
						}
						if( ($regMedico[39] >= 0.001) && ($regMedico[39] < 0.01) ){ 
							$regMedico[$j] = 'DIFERENTE'; 
						}
						if( ($regMedico[39] >= 0.01) && ($regMedico[39] < 0.1) ){ 
							$regMedico[$j] = 'MUY DIFERENTE'; 
						}
						if($regMedico[39] >= 0.1){ 
							$regMedico[$j] = 'EXTREMADAMENTE DIFERENTE'; 
						}						
					}else{
						$regMedico[$j] = 'SIN GEOLOCALIZAR MEDICO';
					}
				}else{ 
					$regMedico[$j] = 'SIN GEOLOCALIZAR VISITA';
				}				
			}

			if($tipo != 3){
				if($tipo == 2){
					$row = $i + 4;
					$spread->setActiveSheetIndex(0)
						->setCellValue(columna($j).$row, utf8_encode($regMedico[$j]));
				}else{
					if($tipo == 1){
						$tabla .= '<td style="border: 1px solid #000;white-space:nowrap;min-width:'.$tam[$j].'px;">'.utf8_encode($regMedico[$j]).'</td>';
					}else{
						if($j < 44){
							$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.utf8_encode($regMedico[$j]).'</td>';
						}else{
							if(strlen($regMedico[$j]) > 80){
								$regMedico[$j] = substr($regMedico[$j], 0, 79)."...";
							}
							$tabla .= '<td style="min-width:'.$tam[$j].'px;max-width:'.$tam[$j].'px;text-overflow: ellipsis;">'.utf8_encode($regMedico[$j]).'</td>';
						}
					}
				}
			}else{
				if(strlen($regMedico[$j]) > 80){
					$regMedico[$j] = substr($regMedico[$j], 0, 79)."...";
				}
				$pdf->Cell($tam[$j]/2,8,utf8_encode($regMedico[$j]),1,0,'L',$fill);
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
			->setTitle('ListadoMedicosVisitados');

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