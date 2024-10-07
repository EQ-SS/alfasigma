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
	$tam = array(100,350,250,250,150,150,200,200,350,550, 100,300,350,100,300,250,250,150,150,150, 150,150,100,100,100,100,100,100,100,100, 250,150,250,400,210,250,150,100,100,100, 750,100,100,100,100,100,100,100); //,100,100,50,100,100,100,100,100,100,50,100,100,100,50,100,150,100,100,100,100,100,150,100,100,100,150,100,100,100,50,100,50,100,50,100,50,100,50,100,50,100,100,100,100);
	$registrosPorPagina = 20;
	
	$ids = (substr($_POST['hdnIDS'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDS'], ",")) : $_POST['hdnIDS'] ;
	$tipo = $_POST['hdnTipoReporte'];
	if(isset($_POST['pagina']) && $_POST['pagina'] != ''){
		$numPagina = $_POST['pagina'];
	}else{
		$numPagina = 1;
	}
	
	if(isset($_POST['hdnEstatus']) && $_POST['hdnEstatus'] != ''){
		$estatus = $_POST['hdnEstatus'];
	}else{
		$estatus = '';
	}
	
	$qMedicos = "Select 
		cl.name as Linea, 
		upper(U.lname)+' '+upper(U.fname) as Representante, 
		'{'+CAST(P.pers_snr AS VARCHAR(36))+'}' as 'Código Médico', 
		'{'+CAST(I.inst_snr AS VARCHAR(36))+'}' as 'Código Inst', 
		upper(IT.NAME) AS 'Tipo Inst', 
		upper(type.name) as 'Tipo Pers', 
		upper(P.lname) as Paterno, 
		upper(P.MOTHERS_LNAME) as Materno, 
		upper(P.fname) as Nombre, 
		upper(I.street1) as Calle, 
		case when I.num_ext='0' then '' else I.num_ext end as 'Num Ext', 
		plw.num_int as 'Num Int', 
		City.name as Colonia, 
		City.zip as 'C.P.', 
		IMS.name as Brick, 
		Dst.name as Población, 
		State.name as Estado, 
		I.latitude, 
		I.longitude, 
		HON.name as Honorarios, 
		ESP.name as Especialidad, 
		ESP2.name as 'Sub Especialidad', 
		ST.name as Estatus, 
		CAST(cast(p.BIRTHDATE as DATE) AS VARCHAR(10)) as 'Fecha Nac', 
		SEXO.name as Sexo, 
		FV.name as 'Frec vis', 
		CATAW.NAME AS CATEG_AW, 
		PT.name as 'Pacs por Sem', 
		P.prof_id as Cedula, 
		case when year(P.BIRTHDATE) > 0 then year(getdate())-year(P.BIRTHDATE) else '' end  as Edad, 
		P.tel1 as Tel1, 
		I.tel2 as Tel2, 
		P.mobile as Celular,  
		PLW.email, 
		'' as 'Div. Med. Int' , 
		PERFIL7.name as 'Lider de opinion', 
		'' as 'notas / Otras Inversiones', 
			
		CAST(CAST(isnull(isnull((select TOP 1 kt.T_DATE from kupdatelog ku, kommtran kt where
		ku.table_nr = 19
		and ku.OPERATION=1
		and ku.kTrAN_SNR = KT.KTRAN_SNR
		AND KU.REC_STAT=0
		AND KU.REC_KEY=P.PERS_SNR
		order by KT.T_DATE DESC
		), VP.create_date), P.CREATION_TIMESTAMP) AS DATE) AS VARCHAR(10)) as Fecha_Alta, 

		CAST(CAST(isnull((select top 1 kt.T_DATE from kupdatelog ku, kommtran kt where
		ku.table_nr = 19
		and ku.OPERATION=2
		and ku.kTrAN_SNR = KT.KTRAN_SNR
		AND KU.REC_STAT=0 
		AND KU.REC_KEY=P.PERS_SNR
		order by KT.T_DATE DESC
		),P.CHANGED_TIMESTAMP) AS DATE) AS VARCHAR(10)) as Fecha_Mod, 
			
		CAST(CAST(ISNULL((CASE WHEN PSW.CHANGED_TIMESTAMP IS NOT NULL THEN 
		(CASE WHEN PSW.CREATION_TIMESTAMP IS NOT NULL THEN 
		(CASE WHEN PSW.CREATION_TIMESTAMP>PSW.CHANGED_TIMESTAMP THEN CAST(PSW.CREATION_TIMESTAMP AS DATE) ELSE CAST(PSW.CHANGED_TIMESTAMP AS DATE) END)
		ELSE CAST(PSW.CHANGED_TIMESTAMP AS DATE) END)
		ELSE PSW.CREATION_TIMESTAMP END),'2017-01-01')
		AS DATE) AS VARCHAR(10)) as Fecha_Ruta,
			
		'' as Suma_Aseguradoras, 
		P.Nombre_CU, 
		SegAteka.name as Seg_Ateka, 
		SegFlonon.name as Segment_Flonorm, 
		SegVessel.name as Segment_Vessel, 
		SegZirfos.name as Segment_Zirfos 
			
		from person P
		inner join perslocwork PLW on P.pers_snr = PLW.pers_snr and PLW.rec_stat=0
		inner join inst I on I.inst_snr = PLW.INST_SNR and I.rec_stat=0
		left outer join inst_Type IT on IT.inst_type=I.inst_type and IT.rec_Stat=0
		inner join pers_srep_work PSW on PSW.pwork_snr=PLW.pwork_SNR and PSW.rec_Stat=0
		INNER join City on City.city_snr = I.city_snr
		LEFT OUTer join District as Dst on city.distr_snr = Dst.distr_snr
		LEFT OUTer join State on Dst.state_snr = State.state_snr
		left outer join Brick as IMS on IMS.brick_snr = City.brick_snr
		left outer join Smart_Fechas_Med VP on VP.pers_snr = P.pers_snr
		inner join User_Territ as UT on psw.user_snr= ut.user_snr and i.inst_snr = ut.inst_snr and ut.rec_stat=0 
		inner join Users as U on U.user_snr = UT.user_snr and U.rec_stat=0
		inner join compline as cl on U.cline_snr = cl.cline_snr
		left outer join codelist type on P.PERSTYPE_SNR = type.clist_snr and type.rec_stat=0
		left outer join codelist ST on P.status_snr = ST.clist_snr
		left outer join codelist SEXO on P.sex_snr = SEXO.clist_snr
		LEFT OUTER JOIN CODELIST CATAW ON CATAW.CLIST_SNR=P.category_snr AND P.REC_STAT=0
		left outer join codelist PT on P.patperweek_snr = PT.clist_snr AND PT.REC_STAT=0 AND PT.STATUS=1
		left outer join codelist ESP on P.spec_snr = ESP.clist_snr
		left outer join codelist ESP2 on P.subSpec_snr = ESP2.clist_snr AND ESP2.STATUS=1 AND ESP2.REC_STAT=0
		left outer join codelist HON on P.FEE_TYPE_SNR = HON.clist_snr and HON.STATUS=1 and HON.REC_STAT=0
		left outer join codelist FV on P.frecvis_snr=FV.clist_snr and FV.rec_stat=0 and FV.status=1
		LEFT OUTER JOIN PERSON_UD PUD ON PUD.PERS_SNR=P.PERS_SNR AND PUD.REC_STAT=0
			 
		left outer join codelist SegAteka on PUD.field_07_snr = SegAteka.clist_snr and SegAteka.rec_stat=0 and SegAteka.status=1
		left outer join codelist SegFlonon on PUD.field_04_snr = SegFlonon.clist_snr and SegFlonon.rec_stat=0 and SegFlonon.status=1
		left outer join codelist SegVessel on PUD.field_05_snr = SegVessel.clist_snr and SegVessel.rec_stat=0 and SegVessel.status=1
		left outer join codelist SegZirfos on PUD.field_06_snr = SegZirfos.clist_snr and SegZirfos.rec_stat=0 and SegZirfos.status=1
			 
		left outer join codelist PERFIL7 on PUD.field_02_snr = PERFIL7.clist_snr and PERFIL7.status=1

		where
		P.pers_snr <> '00000000-0000-0000-0000-000000000000'
		and P.rec_stat=0
		and U.status=1
		and U.user_type=4 
		and U.user_snr in ('".$ids."') ";

		if($estatus != ''){
			$qMedicos .= "and P.status_snr in ('".$estatus."') ";
		}
		
		$qMedicos .= "order by U.lname,U.fname,P.lname,P.mothers_lname,P.fname ";
		
		//echo $qMedicos."<br>";
		
	$tabla = '';
	
	if($tipo == 0){
		$registroIni = $numPagina * $registrosPorPagina - $registrosPorPagina;
		
		$tope = "OFFSET ".$registroIni." ROWS 
			FETCH NEXT ".$registrosPorPagina." ROWS ONLY ";
				
		$rsMedicosTotal = sqlsrv_query($conn, utf8_decode($qMedicos), array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		
		$totalRegistros = sqlsrv_num_rows($rsMedicosTotal);

		$rsMedicos = sqlsrv_query($conn, utf8_decode($qMedicos.$tope));
			
		$paginas = ceil($totalRegistros / $registrosPorPagina);
			
		//echo $qMedicos.$tope;
			
	}else{
		$rsMedicos = sqlsrv_query($conn, utf8_decode($qMedicos), array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	}
		
	if($tipo == 1 || $tipo == 2){//excel
		$nombreArchivo = "../archivos/ListadoMedicos".date("dmYHis").".xlsx";
		$spread = new Spreadsheet();
	
		$spread->getProperties()
					->setCreator("Smart-Scale")
					->setTitle("Listado")
					->setDescription("Listado de medicos");
	}else if($tipo == 3){
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array((array_sum($tam)/2),150));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('LISTADO DE MÉDICOS'));
		$pdf->Ln();
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(40,5,'Alfa Wassermann');
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
	$tamTabla = array_sum($tam) + 600;
	if( $tipo == 0){
		$tabla = '<table border="0">
			<tr>
				<td>
					<table>
						<tr>
							<td colspan="10" class="nombreReporte">LISTADO DE MÉDICOS</td>
						</tr>
						<tr>
							<td colspan="10" class="clienteReporte">Alfa Wassermann</td>
						</tr>
						<tr>
							<td colspan="10" class="fechaReporte">Fecha: '. date("d/m/Y h:i:s") .'</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<div id="divListadoMedicos">
						<table id="tblListadoMedicos" width="'.$tamTabla.'px" class="tablaReportes" >';
	}
	
	if($tipo == 1 || $tipo == 2){
		$spread->setActiveSheetIndex(0)
            ->setCellValue('A1', 'LISTADO DE MÉDICOS')
			->setCellValue('A2', 'Alfa Wassermann')
			->setCellValue('A3', 'Fecha: '. date("d/m/Y h:i:s"));
	}
	
	if($tipo != 3){
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
		if($i < 48){
			if($tipo != 3){
				if($tipo == 2){
					$spread->setActiveSheetIndex(0)
						->setCellValue($celda, utf8_encode($field['Name']));
				}else{
					if($tipo == 1){
						$spread->setActiveSheetIndex(0)
							->setCellValue($celda, utf8_encode($field['Name']));
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
	
	if($tipo == 0){
		$tabla .= '</tr></thead>';
		$tabla .= '<tbody style="height:340px;">';
	}else if($tipo == 3){
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
		if($tipo == 0){
			$tabla .= '<tr>';
		}
		$aseguradoras = array();
		for($j=0;$j<sqlsrv_num_fields($rsMedicos);$j++){
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
							$row = $i + 4;
							$spread->setActiveSheetIndex(0)
								->setCellValue(columna($j).$row, implode(",",$arrAseguradoras));
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
								->setCellValue(columna($j).$row, $regMedico[$j]);
					}else{
						if($tipo == 1){
							$row = $i + 4;
							$spread->setActiveSheetIndex(0)
								->setCellValue(columna($j).$row, $regMedico[$j]);
						}else{
							$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.$regMedico[$j].'</td>';
						}
					}
				}else{
					$pdf->Cell($tam[$j]/2,8,utf8_encode($regMedico[$j]),1,0,'L',$fill);
				}
			}
		}
		if($tipo == 0){
			$tabla .= '</tr>';
		}else if($tipo == 3){
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
	$tabla .= '</tbody></table></div>';
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
	if($tipo == 1 || $tipo == 2){
		$spread->setActiveSheetIndex(0)
            ->setCellValue('A'.$row, 'Total registros: '.sqlsrv_num_rows($rsMedicos))
			->setTitle('ListadoMedicos');

		$spread->setActiveSheetIndex(0);

		$objWriter = new Xlsx($spread);

		$objWriter->save($nombreArchivo);
		//header("Location: ".$nombreArchivo);
		echo "fin";
	}
	if($tipo == 3){
		$pdf->Output();
	}
	if($tipo == 0){
		echo '<script>
			$("#divCargando").hide();
			$("#hdnQuery").val("'.str_replace("'","\'",str_ireplace($buscar,$reemplazar,$qMedicos)).'");
		</script>';
		//echo str_replace("'","\'",$qMedicos);
	}
?>