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
	$tam = array(100,350,350,350,150,150,200,200,350,550, 150,300,450,100,350,250,250,150,150,150, 200,200,150,100,100,100,100,150,100,100, 200,150,200,400,150,100,100,150,150,150, 750,150,150,150,150,150,150,100,100,100, 100,100,100,100,100,150,150); 
	$registrosPorPagina = 20;
	
	$ids = (substr($_POST['hdnIDSListado'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDSListado'], ",")) : $_POST['hdnIDSListado'] ;
	$tipo = $_POST['hdnTipoListado'];
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
	
	$qMedicos = "Select 
		cl.name as Linea, 
		upper(U.lname)+' '+upper(U.fname) as Representante, 
		'{'+cast(P.pers_snr as varchar(36))+'}' as Codigo_Med, 
		'{'+cast(I.inst_snr as varchar(36))+'}' as Codigo_Inst, 
		upper(IT.name) as Tipo_Inst, 
		upper(type.name) as Tipo_Pers, 
		upper(P.lname) as Paterno, 
		upper(P.mothers_lname) as Materno, 
		upper(P.fname) as Nombre, 
		upper(I.street1) as Calle, 
		(case when I.num_ext='0' then '' else I.num_ext end) as Num_Ext, 
		PLW.num_int as Num_Int, 
		City.name as Colonia, 
		City.zip as Cod_Postal, 
		IMS.name as Brick, 
		Dst.name as Poblacion, 
		State.name as Estado, 
		PLW.latitude, 
		PLW.longitude, 
		HON.name as Honorarios, 
		ESP.name as Especialidad, 
		ESP2.name as Sub_Esp, 
		ST.name as Estatus, 
		cast(cast(p.BIRTHDATE as DATE) as varchar(10)) as Fecha_Nac, 
		SEXO.name as Sexo, 
		FrecVisita.name as Frec_Vis, 
		CATAW.name as Categ_AW, 
		PT.name as PacsxSem, 
		P.prof_id as Cedula, 
		(case when year(P.BIRTHDATE) > 0 then year(getdate())-year(P.BIRTHDATE) else '' end) as Edad, 
		P.tel1 as Tel1, 
		P.tel2 as Tel2, 
		P.mobile as Celular,  
		P.email1 as Email, 
		Estilo_disc.name as Estilo_disc, 
		Lider_op.name as Lider_opinion, 
		Botiquin.name as Botiquin, 
		 
		cast(cast(isnull(isnull((select top 1 kt.t_date from kupdatelog ku, kommtran kt 
		where ku.table_nr = 19
		and ku.operation=1
		and ku.ktran_snr = kt.ktran_snr
		and ku.rec_stat=0
		and ku.rec_key = P.pers_snr
		order by kt.t_date desc
		), VP.create_date), P.creation_timestamp) as DATE) as varchar(10)) as Fecha_Alta, 
		 
		cast(cast(isnull((select top 1 kt.t_date from kupdatelog ku, kommtran kt 
		where ku.table_nr = 19
		and ku.operation=2
		and ku.ktran_snr = kt.ktran_snr
		and ku.rec_stat=0
		and ku.rec_key = P.pers_snr
		order by kt.t_date desc
		), P.changed_timestamp) as DATE) as varchar(10)) as Fecha_Mod, 
		 
		cast(cast(isnull((case when PSW.changed_timestamp is not null then 
		(case when PSW.creation_timestamp is not null then 
		(case when PSW.creation_timestamp>PSW.changed_timestamp then cast(PSW.creation_timestamp as DATE) else cast(PSW.changed_timestamp as DATE) end)
		else cast(PSW.changed_timestamp as DATE) end)
		else cast(PSW.creation_timestamp as DATE) end), '2017-01-01')
		as DATE) as varchar(10)) as Fecha_Ruta,
		 
		'' as Suma_Aseguradoras, 
		P.Nombre_CU, 
		SegAteka.name as Segmento_Ateka, 
		SegFlonorm.name as Segmento_Flonorm, 
		SegVessel.name as Segmento_Vessel, 
		SegZirfos.name as Segmento_Zirfos,
		SegEsoxx.name as Segmento_Esoxx,
		CatAudit.name as Categ_Audit,
		CatAS.name as Categ_AS,
		CatFlonorm.name as Categ_Flonorm,
		CatVessel.name as Categ_Vessel,
		CatAteka.name as Categ_Ateka,
		CatEsoxx.name as Categ_Esoxx,
		CatZirfos.name as Categ_Zirfos,
		(case when P.rec_stat = 0 then '' else 'BAJA' end) as Med_Baja,
		 
		cast(cast((select top 1 approved_date from approval_status aps
		where aps.record_key = P.pers_snr 
		and P.rec_stat=2
		and aps.rec_stat=0
		and aps.table_nr=456
		and aps.approved_status=2
		and aps.movement_type='D'
		order by approved_date desc
		) as DATE) as varchar(10)) as Fecha_Baja,
		 
		cast(cast((select top 1 reactivation_timestamp from PERSON_HISTORY ph 
		where ph.pers_snr = P.pers_snr 
		order by reactivation_timestamp desc
		) as DATE) as varchar(10)) as Fecha_Reactivacion 
		 
		 
		from person P
		inner join pers_srep_work PSW on PSW.pers_snr = P.pers_snr 
		inner join perslocwork PLW on PSW.pwork_snr = PLW.pwork_snr 
		inner join inst I on I.inst_snr = PSW.inst_snr and I.rec_stat=0 
		inner join user_Territ UT on PSW.user_snr = UT.user_snr and I.inst_snr = UT.inst_snr 
		inner join city on City.city_snr = I.city_snr 
		inner join users U on U.user_snr = PSW.user_snr 
		inner join compline cl on U.cline_snr = cl.cline_snr 
		left outer join inst_Type IT on IT.inst_type = I.inst_type 
		left outer join District Dst on city.distr_snr = Dst.distr_snr 
		left outer join State on Dst.state_snr = State.state_snr 
		left outer join Brick IMS on IMS.brick_snr = City.brick_snr 
		left outer join Smart_Fechas_Med VP on VP.pers_snr = P.pers_snr 
		left outer join codelist type on P.perstype_snr = type.clist_snr 
		left outer join codelist ST on P.status_snr = ST.clist_snr 
		left outer join codelist SEXO on P.sex_snr = SEXO.clist_snr 
		left outer join codelist CATAW on P.category_snr = CATAW.clist_snr 
		left outer join codelist PT on P.patperweek_snr = PT.clist_snr 
		left outer join codelist ESP on P.spec_snr = ESP.clist_snr 
		left outer join codelist ESP2 on P.subspec_snr = ESP2.clist_snr 
		left outer join codelist HON on P.fee_type_snr = HON.clist_snr 
		left outer join codelist Botiquin on P.first_aid_kit_snr = Botiquin.clist_snr 
		/*left outer join codelist FV on P.frecvis_snr = FV.clist_snr */
		left outer join CYCLE_PERS_CATEG_SPEC FrecVis on P.spec_snr = FrecVis.spec_snr and P.category_snr = FrecVis.category_snr and FrecVis.rec_stat=0 and FrecVis.CYCLE_SNR = (select CYCLE_SNR from CYCLES where GETDATE() between START_DATE and FINISH_DATE and rec_stat=0)
		left outer join codelist FrecVisita on FrecVis.frecvis_snr = FrecVisita.clist_snr 
		 
		left outer join person_ud PUD on PUD.pers_snr = P.pers_snr and PUD.rec_stat=0 
		left outer join codelist Estilo_disc on PUD.field_01_snr = Estilo_disc.clist_snr 
		left outer join codelist Lider_op on PUD.field_02_snr = Lider_op.clist_snr 
		left outer join codelist Paraest on PUD.field_03_snr = Paraest.clist_snr 
		left outer join codelist SegFlonorm on PUD.field_04_snr = SegFlonorm.clist_snr 
		left outer join codelist SegVessel on PUD.field_05_snr = SegVessel.clist_snr 
		left outer join codelist SegZirfos on PUD.field_06_snr = SegZirfos.clist_snr 
		left outer join codelist SegAteka on PUD.field_07_snr = SegAteka.clist_snr 
		left outer join codelist SegEsoxx on PUD.field_19_snr = SegEsoxx.clist_snr 
		left outer join codelist CatAudit on PUD.field_12_snr = CatAudit.clist_snr 
		left outer join codelist CatAS on PUD.field_13_snr = CatAS.clist_snr 
		left outer join codelist CatFlonorm on PUD.field_14_snr = CatFlonorm.clist_snr 
		left outer join codelist CatVessel on PUD.field_15_snr = CatVessel.clist_snr 
		left outer join codelist CatAteka on PUD.field_16_snr = CatAteka.clist_snr 
		left outer join codelist CatEsoxx on PUD.field_17_snr = CatEsoxx.clist_snr 
		left outer join codelist CatZirfos on PUD.field_18_snr = CatZirfos.clist_snr 
		 
		where
		P.pers_snr <> '00000000-0000-0000-0000-000000000000'
		and P.rec_stat in (0,2)
		and PSW.rec_stat=0
		and PLW.rec_stat=0
		and UT.rec_stat=0 
		and U.rec_stat=0
		and U.status=1
		and U.user_type=4
		and U.user_snr in ('".$ids."') ";

		if($estatus != ''){
			$qMedicos .= "and P.status_snr in ('".$estatus."') ";
		}
		
		$qMedicos .= "order by U.lname,U.fname,P.lname,P.mothers_lname,P.fname ";
		
		
		//echo $qMedicos."<br>";
		//echo $estatus."<br>";
		
	if($tipo == 0){
		$registroIni = $numPagina * $registrosPorPagina - $registrosPorPagina;
		
		$tope = "OFFSET ".$registroIni." ROWS 
			FETCH NEXT ".$registrosPorPagina." ROWS ONLY ";
				
		$rsMedicosTotal = sqlsrv_query($conn, $qMedicos, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

		//$stmt = sqlsrv_query( $conn, $sql );
		/*if( $rsMedicosTotal === false ) {
		    if( ($errors = sqlsrv_errors() ) != null) {
		        foreach( $errors as $error ) {
		            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
		            echo "code: ".$error[ 'code']."<br />";
		            echo "message: ".$error[ 'message']."<br />";
		        }
		    }
		}*/

		
		$totalRegistros = sqlsrv_num_rows($rsMedicosTotal);

		$rsMedicos = sqlsrv_query($conn, utf8_decode($qMedicos.$tope));
			
		$paginas = ceil($totalRegistros / $registrosPorPagina);
			
		//echo $qMedicos.$tope;

		//echo "<br><br>";
			
	}else{
		$rsMedicos = sqlsrv_query($conn, utf8_decode($qMedicos), array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	}

	if($tipo == 2){//excel sin formato
		$nombreArchivo = "../archivos/ListadoMedicos".date("dmYHis").".xlsx";
		$spread = new Spreadsheet();
	
		$spread->getProperties()
					->setCreator("Smart-Scale")
					->setTitle("Listado")
					->setDescription("Listado de Medicos");
	}
	if($tipo == 1){//excel con formato
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=listadoMedicos.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
	if($tipo == 3){
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array((array_sum($tam)/1.98),150));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('LISTADO DE MÉDICOS'));
		$pdf->Ln();
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(40,5,'ALFASIGMA');
		$pdf->Ln();
		$pdf->SetFont('Arial',10);
		$pdf->Cell(40,5,'Fecha: '.date("d/m/Y h:i:s"));
		$pdf->Ln();	
	}
	
	//$rsMedicos = sqlsrv_query($conn, utf8_decode($qMedicos), array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
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
							<td colspan="10" class="nombreReporte">LISTADO DE MÉDICOS</td>
						</tr>
						<tr>
							<td colspan="10" class="clienteReporte">ALFASIGMA</td>
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
							$tabla .= '<table id="tblListadoMedicos" width="'.$tamTabla.'px" class="tablaReportes" >';
						}else{
							$tabla .= '<table width="'.$tamTabla.'px" style="border-collapse: collapse;">';
						}
	}
	
	if($tipo == 2){
		$spread->setActiveSheetIndex(0)
            ->setCellValue('A1', 'LISTADO DE MÉDICOS')
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
	foreach(sqlsrv_field_metadata($rsMedicos) as $field){
		$celda = columna($i)."4";
		//echo "celda: ".$celda."<br>";
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
		$tabla .= '<tbody style="height:340px;">';
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
		$aseguradoras = array();
		for($j=0;$j<sqlsrv_num_fields($rsMedicos);$j++){
			//echo "col: ".$j;
			if($j==57){
				break;
			}
			if(is_object($regMedico[$j])){
				foreach ($regMedico[$j] as $key => $val) {
					if(strtolower($key) == 'date'){
						$regMedico[$j] = substr($val, 0, 10);
					}
				}
			}
			if($j == 2){//pers_snr
				$qAdeguradoras = "select c.NAME from PERSON_BANK p, CODELIST c
					where p.bank_snr = c.CLIST_SNR
					and p.REC_STAT = 0
					and p.PERS_SNR = '".$regMedico[$j]."'
					order by c.SORT_NUM";
					//echo $qAdeguradoras."<br><br>";
				$rsAseguradoras = sqlsrv_query($conn, $qAdeguradoras);
				$arrAseguradoras = array();
				while($aseguradora = sqlsrv_fetch_array($rsAseguradoras)){
					$arrAseguradoras[] = $aseguradora['NAME'];
				}
			}
			if($j == 40){
				if($tipo != 3){
					if($tipo == 2){
						$row = $i + 4;
						$spread->setActiveSheetIndex(0)
							->setCellValue(columna($j).$row, implode(",",$arrAseguradoras));
					}else{
						if($tipo == 1){
							$tabla .= '<td style="border: 1px solid #000;white-space:nowrap;min-width:'.$tam[$j].'px;">'.implode(",",$arrAseguradoras).'</td>';
						}else{
							$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.implode(",",$arrAseguradoras).'</td>';
						}
					}
				}else{
					$pdf->Cell($tam[$j]/2,8,implode(",",$arrAseguradoras),1,0,'L',$fill);
				}
			}else{
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

	//echo "Total: ".$totalRegistros."<br>";
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
	//echo 'A'.$row;
	if($tipo == 2){
		$spread->setActiveSheetIndex(0)
            ->setCellValue('A'.$row, 'Total registros: '.sqlsrv_num_rows($rsMedicos))
			->setTitle('ListadoMedicos');

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