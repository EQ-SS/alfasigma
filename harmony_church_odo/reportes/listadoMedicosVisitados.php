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
	$tam = array(150,350,350,350,150,150,250,250,250,600, 250,150,350,100,250,300,200,150,100,100, 250,250,150,150,100,200,200,300,150,100, 150,150,150,150,200,150,150,150,150,100, 200,150,100,700,700);
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
		'{'+cast(VP.pers_snr as varchar(36))+'}' as Cod_Med,
		'{'+cast(I.inst_snr as varchar(36))+'}' as Cod_Inst,
		upper(IT.name) as Tipo_Inst,
		upper(type.name) as Tipo_Pers,
		upper(P.lname) as Paterno,
		upper(P.mothers_lname) as Materno,
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
		(case when year(P.birthdate) > 0 then year(getdate()) - year(P.birthdate) else '' end) as Edad,
		/*DIFVIS.name as Difvis,*/
		P.tel1 as Tel1,
		P.tel2 as Tel2,
		P.mobile as Celular,
		cast(cast(VP.visit_date as DATE) as varchar(10)) as Fecha_Visita,
		cast(VP.time as varchar(10)) as Hora_Visita,
		/*(select PLAN_DATE from VISPERSPLAN where vispersplan_snr = VP.NEXTVPP_SNR and rec_stat=0) as Fecha_Sig_Vis,*/
		convert(varchar(16), VP.creation_timestamp, 120) as Fecha_Creacion,
		cast(cast(VP.CHANGED_TIMESTAMP as DATE) as varchar(10)) as 'Fecha Modificación',
		cast(cast(VP.SYNC_TIMESTAMP as DATE) as varchar(10)) as 'Fecha Sincronización',
		/*VA1.name as 'Visita Acompañada',*/
		(case when VA2.name <> NULL then VA1.name+' '+VA2.name else VA1.name end) as 'Visita Acompañada',
		VisCode.name as 'Tipo Visita'
		,VP.latitude as Lat_Vis
		,VP.longitude as Long_Vis
		,PLW.Latitude as Lat_Dir
		,PLW.Longitude as Long_Dir
		,isnull((case when isnumeric(PLW.Latitude)>0 and PLW.latitude<>'' and isnumeric(PLW.longitude)>0 and PLW.longitude<>'' then 
		(case when isnumeric(VP.latitude)>0 and VP.latitude<>'' and VP.latitude<>'0.0' and PLW.latitude<>'0.0' and isnumeric(VP.longitude)>0 and VP.longitude<>'' then 
		(case when ABS(cast(PLW.latitude as float))-ABS(cast(VP.latitude as float))+ABS(cast(PLW.longitude as float))-ABS(cast(VP.longitude as float))=0 then '0' 
		else cast((ACOS(round((COS(RADIANS(90-cast(isnull(VP.latitude,'0') as float)))*COS(RADIANS(90-cast(isnull(PLW.latitude, '0') as float))))+(SIN(RADIANS(90-cast(isnull(VP.latitude,'0') as float)))*SIN(RADIANS(90-cast(isnull(PLW.latitude,'0') as float)))*COS(RADIANS( cast(isnull(PLW.longitude,'0') as float) -cast(isnull(VP.longitude,'0') as float) ))),7))*6371)*1000 as varchar(30))
		end) end)
		end),'N/A') as Diferencia_gps
		,(case when ABS(cast(PLW.latitude as float))-ABS(cast(VP.latitude as float))+ABS(cast(PLW.longitude as float))-ABS(cast(VP.longitude as float))=0 and PLW.latitude<>'' and VP.latitude<>'' then 'SIMILAR' else 
		(case when isnumeric(PLW.latitude)>0 and PLW.latitude<>'' and isnumeric(PLW.longitude)>0 and PLW.longitude<>'' then 
		(case when isnumeric(VP.latitude)>0 and VP.latitude<>'' and VP.latitude<> '0.0' then 
		(case when (ACOS(round((COS(RADIANS(90-cast(isnull(VP.latitude,'0') as float)))*COS(RADIANS(90-cast(isnull(PLW.latitude, '0') as float))))+(SIN(RADIANS(90-cast(isnull(VP.latitude,'0') as float)))*SIN(RADIANS(90-cast(isnull(PLW.latitude,'0') as float)))*COS(RADIANS( cast(isnull(PLW.longitude,'0') as float) -cast(isnull(VP.longitude,'0') as float) ))),7))*6371)*1000 <=100.999999 then 'SIMILAR' 
		when (ACOS(round((COS(RADIANS(90-cast(isnull(VP.latitude,'0') as float)))*COS(RADIANS(90-cast(isnull(PLW.latitude, '0') as float))))+(SIN(RADIANS(90-cast(isnull(VP.latitude,'0') as float)))*SIN(RADIANS(90-cast(isnull(PLW.latitude,'0') as float)))*COS(RADIANS( cast(isnull(PLW.longitude,'0') as float) -cast(isnull(VP.longitude,'0') as float) ))),7))*6371)*1000 between 101 and 200.999999 then 'DIFERENTE' 
		when (ACOS(round((COS(RADIANS(90-cast(isnull(VP.latitude,'0') as float)))*COS(RADIANS(90-cast(isnull(PLW.latitude, '0') as float))))+(SIN(RADIANS(90-cast(isnull(VP.latitude,'0') as float)))*SIN(RADIANS(90-cast(isnull(PLW.latitude,'0') as float)))*COS(RADIANS( cast(isnull(PLW.longitude,'0') as float) -cast(isnull(VP.longitude,'0') as float) ))),7))*6371)*1000 between 201 and 300.999999 then 'MUY DIFERENTE' 
		when (ACOS(round((COS(RADIANS(90-cast(isnull(VP.latitude,'0') as float)))*COS(RADIANS(90-cast(isnull(PLW.latitude, '0') as float))))+(SIN(RADIANS(90-cast(isnull(VP.latitude,'0') as float)))*SIN(RADIANS(90-cast(isnull(PLW.latitude,'0') as float)))*COS(RADIANS( cast(isnull(PLW.longitude,'0') as float) -cast(isnull(VP.longitude,'0') as float) ))),7))*6371)*1000 >=301 then 'EXTREMADAMENTE DIFERENTE' 
		else 'SIN GEOLOCALIZAR MEDICO' end) 
		else 'SIN GEOLOCALIZAR VISITA' end)
		else 'SIN GEOLOCALIZAR MEDICO' end) end) Estatus_Coordenadas 
		/*,(case when VPLAN.PLAN_DATE <> ' ' then 'SI' else 'NO' end) as Cumplio_Plan */
		,cast(cast(VPLAN.PLAN_DATE as DATE) as varchar(10)) as Fecha_Plan
		,(case when P.rec_stat = 0 then '' else 'Baja' end) as Med_Baja
		,VP.info_nextvisit as 'Info Siguiente Visita'
		,VP.info as 'Comentario Resultado de la Visita'
		 
		from visitpers VP
		inner join person P on VP.pers_snr = P.pers_snr
		inner join perslocwork PLW on P.pers_snr = PLW.pers_snr 
		inner join pers_srep_work PSW on PSW.pwork_snr = PLW.pwork_snr 
		inner join inst I on I.inst_snr = PLW.inst_snr
		left outer join City on City.city_snr = I.city_snr
		inner join District Dst on city.distr_snr = Dst.distr_snr
		inner join State on Dst.state_snr = State.state_snr
		left outer join Brick IMS on IMS.brick_snr = City.brick_snr
		inner join User_territ UT on UT.inst_snr = I.inst_snr 
		inner join Users U on U.user_snr = VP.user_snr
		inner join compline cl on U.cline_snr = cl.cline_snr
		 
		left outer join inst_Type IT on IT.inst_type = I.inst_type 
		left outer join codelist type on P.perstype_snr = type.clist_snr
		left outer join codelist ST on P.status_snr = ST.clist_snr
		left outer join codelist SEXO on P.sex_snr = SEXO.clist_snr
		left outer join codelist ESP on P.spec_snr = ESP.clist_snr
		left outer join codelist ESP2 on P.subspec_snr = ESP2.clist_snr
		left outer join codelist HON on P.fee_type_snr = HON.clist_snr
		left outer join codelist PT on P.patperweek_snr = PT.clist_snr
		left outer join codelist DIFVIS on P.diffvis_snr = DIFVIS.clist_snr
		left outer join codelist FV on P.frecvis_snr = FV.clist_snr
		left outer join codelist VA1 on cast(VP.escort_snr as varchar(36)) = cast(VA1.clist_snr as varchar(36))
		left outer join codelist VA2 on cast(substring(VP.escort_snr,38,36) as varchar(36)) = cast(VA2.clist_snr as varchar(36))
		left outer join VISPERSPLAN VPLAN on VPLAN.vispersplan_snr = VP.vispersplan_snr
		left outer join codelist CATEG on P.category_snr = CATEG.clist_snr 
		left outer join codelist VisCode on VisCode.clist_snr = VP.visit_code_snr and VisCode.rec_stat=0 
		 
		where
		VP.pers_snr <> '00000000-0000-0000-0000-000000000000'
		and P.pers_snr <> '00000000-0000-0000-0000-000000000000'
		and I.inst_snr <> '00000000-0000-0000-0000-000000000000'
		/*and P.rec_stat=0*/
		and VP.rec_stat=0
		and PLW.rec_stat=0
		and PSW.rec_stat=0
		and UT.rec_stat=0
		and U.rec_stat=0
		and U.status=1
		and U.user_type in (4,5)
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
		$pdf->Cell(40,5,'Church & Dwight');
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
			
			if($tipo != 3){
				if($tipo == 2){
					$row = $i + 4;
					$spread->setActiveSheetIndex(0)
						->setCellValue(columna($j).$row, utf8_encode($regMedico[$j]));
				}else{
					if($tipo == 1){
						$tabla .= '<td style="border: 1px solid #000;white-space:nowrap;min-width:'.$tam[$j].'px;">'.utf8_encode($regMedico[$j]).'</td>';
					}else{
						if($j < 42){
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