<?php
	set_time_limit(0);
	/*** listado de medicos ***/
	include "../conexion.php";
	
	$tamanio = array(1,4,4,4,2,2,2,2,3,4,6,2,2,2,4,4,2,3,3,2,2,3,3,2,2,2,2,2,2,2,2,2,2,1,4,2,4,2,4,3,2,2,2,2,2,2,5,2,2,2,2,1,2,2,2,2,2,2,1,2,2,2,1,2,3,2,2,2,2,2,3,2,2,2,3,2,2,2,1,2,1,2,1,2,1,2,1,2,1,2,2,2);
	$tam = array(100,350,250,250,150,150,200,200,350,550, 100,300,350,100,300,250,250,150,150,150, 150,150,100,100,100,100,100,100,100,100, 250,150,250,400,210,250,150,100,100,100, 750,100,100,100,100,100,100,100); //,100,100,50,100,100,100,100,100,100,50,100,100,100,50,100,150,100,100,100,100,100,150,100,100,100,150,100,100,100,50,100,50,100,50,100,50,100,50,100,50,100,100,100,100);
	$registrosPorPagina = 20;
	
	$ids = (substr($_POST['hdnIDS'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDS'], ",")) : $_POST['hdnIDS'] ;
	$idsEnviar = $ids;
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
		cl.name as Linea, --0
		upper(U.lname)+' '+upper(U.fname) as Representante, --1
		'{'+CAST(P.pers_snr AS VARCHAR(36))+'}' as 'Código Médico', --2
		'{'+CAST(I.inst_snr AS VARCHAR(36))+'}' as 'Código Inst', --3
		upper(IT.NAME) AS 'Tipo Inst', --4
		upper(type.name) as 'Tipo Pers', --5
		upper(P.lname) as Paterno, --6
		upper(P.MOTHERS_LNAME) as Materno, --7
		upper(P.fname) as Nombre, --8
		upper(I.street1) as Calle, --9
		/*upper(P.lname)+' '+upper(P.mothers_lname)+' '+upper(P.fname) as Medico, */
		case when I.num_ext='0' then '' else I.num_ext end as 'Num Ext', --10
		/*case when plw.num_int=0 then '' else plw.num_int end as 'Num Int', */
		plw.num_int as 'Num Int', --11 
		City.name as Colonia, --12
		City.zip as CP, --13
		IMS.name as Brick, --14
		Dst.name as Población, --15
		State.name as Estado, --16
		I.latitude, --17
		I.longitude, --18
		HON.name as Honorarios, --19
		ESP.name as Especialidad, --20
		ESP2.name as 'Sub Especialidad', --21
		ST.name as Status, --22
		cast(p.BIRTHDATE as DATE) as 'Fecha Nac', --23
		SEXO.name as Sexo, --24
		FV.name as 'Frec vis', --25
		CATAW.NAME AS CATEG_AW, --26
		PT.name as 'Pacs por Sem', --27
		P.prof_id as Cedula, --28
		case when year(P.BIRTHDATE) > 0 then year(getdate())-year(P.BIRTHDATE) else '' end  as Edad, --29
		P.tel1 as Tel1, --30
		I.tel2 as Tel2, --31
		P.mobile as Celular, --32 
		PLW.email, --33
		'' as 'Div. Med. Int' , --34
		PERFIL7.name as 'Lider de opinion', --35
		'' as 'notas / Otras Inversiones', --36
			
		isnull((select TOP 1 kt.T_DATE from kupdatelog ku, kommtran kt where
		ku.table_nr = 19
		and ku.OPERATION=1
		and ku.kTrAN_SNR = KT.KTRAN_SNR
		AND KU.REC_STAT=0
		AND KU.REC_KEY=P.PERS_SNR
		), ISNULL(VP.create_date,
		CASE WHEN ((SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR ORDER BY VISIT_DATE) IS NOT NULL
		AND CAST(VP.change_date AS DATE) IS NOT NULL)
		THEN
		CASE
		WHEN (SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR ORDER BY VISIT_DATE)
		>CAST(VP.change_date AS DATE)
		THEN VP.change_date
		WHEN (SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR ORDER BY VISIT_DATE)
		<=
		CAST(VP.change_date AS DATE)
		THEN (SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR ORDER BY VISIT_DATE)
		ELSE
		CASE WHEN P.STATUS_SNR='37DFDA3B-29AB-47D3-87A9-62F0CC2D9B72' THEN CAST('2015-11-12' AS DATETIME)
		ELSE CAST('2015-11-12' AS DATETIME) END
		END
		WHEN ((SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR ORDER BY VISIT_DATE) IS NOT NULL
		AND CAST(VP.change_date AS DATE) IS NULL)
		THEN
		(SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR ORDER BY VISIT_DATE)
		WHEN ((SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR ORDER BY VISIT_DATE) IS NULL
		AND CAST(VP.change_date AS DATE) IS NOT NULL)
		THEN
		CAST(VP.change_date AS DATE)
		WHEN ((SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR ORDER BY VISIT_DATE) IS NULL
		AND CAST(VP.change_date AS DATE) IS NULL)
		THEN
		CAST('2015-11-12' AS DATETIME)
		END) )
		as Fecha_Alta, --37

		isnull((select top 1 kt.T_DATE from kupdatelog ku, kommtran kt where
		ku.table_nr = 19
		and ku.OPERATION=2
		and ku.kTrAN_SNR = KT.KTRAN_SNR
		AND KU.REC_STAT=0 /*AND KT.REC_STAT=0*/
		AND KU.REC_KEY=P.PERS_SNR
		order by KT.T_DATE DESC
		),VP.change_date) as Fecha_Mod, --38
			
		CASE WHEN CAST(VP.fecha_ruta AS DATE)<>'2017-01-01' THEN
		cast(ISNULL((SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR AND USER_SNR=U.USER_SNR
		ORDER BY VISIT_DATE),cast(vp.fecha_ruta as datetime)) as datetime)
		ELSE
		cast(ISNULL((SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR AND USER_SNR=U.USER_SNR
		ORDER BY VISIT_DATE),cast('2017-01-01' as datetime)) as datetime)
		END
		as Fecha_Ruta, --39
			
		/*PERFIL8.name as Paraestatales, */ 
		'' as Suma_Aseguradoras, --40
		P.Nombre_CU, --41
		/*PUESTO.NAME AS Puesto, */ 
		SegAteka.name as Seg_Ateka, --42
		SegFlonon.name as Segment_Flonorm, --43
		SegVessel.name as Segment_Vessel, --44
		SegZirfos.name as Segment_Zirfos --45
			
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
		inner join User_Territ as UT on psw.user_snr= ut.user_snr and i.inst_snr = ut.inst_snr and ut.rec_stat=0 /*UT.inst_snr = I.inst_snr*/
		inner join Users as U on U.user_snr = UT.user_snr and U.rec_stat=0
		inner join compline as cl on U.cline_snr = cl.cline_snr
		left outer join codelist type on P.PERSTYPE_SNR = type.clist_snr and type.rec_stat=0
		left outer join codelist ST on P.status_snr = ST.clist_snr
		left outer join codelist SEXO on P.sex_snr = SEXO.clist_snr
		--left outer join codelist CATEG on P.category_snr = CATEG.clist_snr
		LEFT OUTER JOIN CODELIST CATAW ON CATAW.CLIST_SNR=P.category_snr AND P.REC_STAT=0
		left outer join codelist PT on P.patperweek_snr = PT.clist_snr AND PT.REC_STAT=0 AND PT.STATUS=1
		left outer join codelist ESP on P.spec_snr = ESP.clist_snr
		left outer join codelist ESP2 on P.subSpec_snr = ESP2.clist_snr AND ESP2.STATUS=1 AND ESP2.REC_STAT=0
		left outer join codelist HON on P.FEE_TYPE_SNR = HON.clist_snr and HON.STATUS=1 and HON.REC_STAT=0
		left outer join codelist FV on P.frecvis_snr=FV.clist_snr and FV.rec_stat=0 and FV.status=1
		/* LEFT OUTER JOIN PERSON_CONTACT PC ON PC.PERS_SNR=P.PERS_SNR AND PC.REC_STAT=0
		left outer join codelist PUESTO on PC.FUNCTION_snr = PUESTO.clist_snr AND PC.PERS_SNR=P.PERS_SNR AND PC.REC_STAT=0 AND PUESTO.STATUS=1 */
			 
		LEFT OUTER JOIN PERSON_UD PUD ON PUD.PERS_SNR=P.PERS_SNR AND PUD.REC_STAT=0
		/* left outer join pers_profile PER on P.pers_snr = PER.pers_snr AND PER.REC_STAT=0
		left outer join pers_profile_ud PERFIL on PER.persprofile_snr = PERFIL.persprofile_snr AND PERFIL.REC_STAT=0 */
			 
		/*LEFT OUTER JOIN CODELIST SEGM_INIC ON PUD.SegmentacionInicial=SEGM_INIC.CLIST_SNR AND SEGM_INIC.REC_STAT=0 AND SEGM_INIC.STATUS=1 */
		/*LEFT OUTER JOIN CODELIST SEGM_ACT ON PUD.SegmentacionActual=SEGM_ACT.CLIST_SNR AND SEGM_ACT.REC_STAT=0 AND SEGM_ACT.STATUS=1 */
		left outer join codelist SegAteka on PUD.field_07_snr = SegAteka.clist_snr and SegAteka.rec_stat=0 and SegAteka.status=1
		left outer join codelist SegFlonon on PUD.field_04_snr = SegFlonon.clist_snr and SegFlonon.rec_stat=0 and SegFlonon.status=1
		left outer join codelist SegVessel on PUD.field_05_snr = SegVessel.clist_snr and SegVessel.rec_stat=0 and SegVessel.status=1
		left outer join codelist SegZirfos on PUD.field_06_snr = SegZirfos.clist_snr and SegZirfos.rec_stat=0 and SegZirfos.status=1
			 
		left outer join codelist PERFIL7 on PUD.field_02_snr = PERFIL7.clist_snr and PERFIL7.status=1
		/*left outer join codelist PERFIL8 on P.PERS_POSITION_SNR = PERFIL8.clist_snr and PERFIL8.status=1 
		left outer join codelist PERFIL9 on P.diffvis_snr = PERFIL9.clist_snr and PERFIL9.status=1 and PERFIL9.rec_stat=0
		left outer join codelist PERFIL10 on PERFIL.etapa_de_adopcion = PERFIL10.clist_snr and PERFIL10.status=1
		left outer join codelist PERFILD on PERFIL.Mx_Px_SII = PERFILD.clist_snr and PERFILD.status=1
		left outer join codelist PERFILE on PERFIL.Mx_Px_Inf_Gastrointestinales = PERFILE.clist_snr and PERFILE.status=1
		left outer join codelist PERFILF on PERFIL.Mx_Px_IVC = PERFILF.clist_snr and PERFILF.status=1
		left outer join codelist PERFILG on PERFIL.Mx_Px_ConstipacionEstrenimien = PERFILG.clist_snr and PERFILG.status=1 
		left outer join codelist PERFILH on PERFIL.Mx_Px_Encefalopatia = PERFILH.clist_snr and PERFILH.status=1 */
			
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
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=listadoMedicos.xlsx");
		header("Pragma: no-cache");
		header("Expires: 0");
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
	$tamTabla = array_sum($tam) + 600;
	if( $tipo != 3){
		$tabla = '<div>
					<table>
						<tr>
							<td class="nombreReporte">
								LISTADO DE MÉDICOS
							</td>
						</tr>
						<tr>
							<td class="clienteReporte">
								Alfa Wassermann
							</td>
						</tr>
						<tr>
							<td class="fechaReporte">
								Fecha: '. date("d/m/Y h:i:s") .'
							</td>
						</tr>
					</table>
				</div>
				
				<div id="divListadoMedicos">';
					/*if($tipo == 0){
						$tabla .= '<table style="width:'.$tamTabla.'px;" class="tablaReportes table-striped">';
					}else{*/
						$tabla .= '<table style="width:'.$tamTabla.'px;" class="tablaReportes table-striped">';
					//}

	}
	
	if($tipo != 3){
		if($tipo == 2){
			$tabla .= '<thead><tr>';
		}else{
			if($tipo == 1){
				$tabla .= '<thead style="background-color: #3F51B5;font-weight:bold;border: 1px solid #000;padding:5px;color:#fff"><tr>';
			}else{
				$tabla .= '<thead><tr>';
			}
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
		if($i < 48){
			if($tipo != 3){
				if($tipo == 2){
					$tabla .= '<td style="width:'.$tam[$i].'px;">'.utf8_encode($field['Name']).'</td>'; 
				}else{
					if($tipo == 1){
						$tabla .= '<td style="width:'.$tam[$i].'px;">'.utf8_encode($field['Name']).'</td>';
					}else{
						$tabla .= '<td style="width:'.$tam[$i].'px;">'.utf8_encode($field['Name']).'</td>';
					}
				}	
			}else{
				$pdf->Cell($tam[$i]/2,8,$field['Name'],1,0,'C',1);
			}
		}
		$i++;
	}
	
	if($tipo != 3){
		$tabla .= '</tr></thead>';
		$tabla .= '<tbody style="height:345px;">';
	}else{
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
		if($tipo != 3){
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
						$tabla .= '<td style="width:'.$tam[$j].'px;">'.implode(",",$arrAseguradoras).'</td>';
					}else{
						if($tipo == 1){
							$tabla .= '<td style="width:'.$tam[$j].'px;">'.implode(",",$arrAseguradoras).'</td>';
						}else{
							$tabla .= '<td style="width:'.$tam[$j].'px;">'.implode(",",$arrAseguradoras).'</td>';
						}
					}
				}else{
					$pdf->Cell($tam[$j]/2,8,implode(",",$arrAseguradoras),1,0,'L',$fill);
				}
			}else{
				if($tipo != 3){
					if($tipo == 2){
						$tabla .= '<td style="width:'.$tam[$j].'px;">'.$regMedico[$j].'</td>';
					}else{
						if($tipo == 1){
							$tabla .= '<td style="width:'.$tam[$j].'px;">'.$regMedico[$j].'</td>';
						}else{
							$tabla .= '<td style="width:'.$tam[$j].'px;">'.$regMedico[$j].'</td>';
						}
					}
				}else{
					$pdf->Cell($tam[$j]/2,8,utf8_encode($regMedico[$j]),1,0,'L',$fill);
				}
			}
		}
		if($tipo != 3){
			$tabla .= '</tr>';
		}else{
			$pdf->Ln();
			if($fill == true){
				$fill = false;
			}else{
				$fill = true;
			}
		}
		$i++;
	}
	//echo "tipo: ".$tipo."<br>";
	if($tipo != 3){
		$tabla .= '</tbody> ';
		if($tipo == 2){	
			$tabla .= '<tfoot><tr><td>Registros : '.$totalRegistros.'</td></tr></tfoot>';
		}else{
			if($tipo == 1){
				$tabla .= '<tfoot style="background-color: #A9BCF5;font-weight:bold;border: 1px solid #000;padding: 5px 5px 5px 5px;color:#000;"><tr><td>';
			}
		}
		//$tabla .= "<tfoot><tr><td>hola</td></tr></tfoot>";
		
		
		$tabla .= '</table>
				</div>
				<table width="100%" id="tblPaginasListadoMedicos">';
		if($tipo == 0){
			$tabla .= '<tr style="background-color: #A9BCF5;font-weight:bold;border: 1px solid #000;padding: 5px 5px 5px 5px;color:#000;"><td style="height:20px;" align="center">';
			$numRegs = $i - 1;
			if($numPagina > 1){
				$anterior = $numPagina - 1;
				$tabla .= "<a href='#' onClick='nuevaPaginaListados(1,\"".$idsEnviar."\",\"listadoMedicos\",\"".$estatus."\");'>inicio</a>&nbsp;&nbsp;";
				$tabla .= "<a href='#' onClick='nuevaPaginaListados(".$anterior.",\"".$idsEnviar."\",\"listadoMedicos\",\"".$estatus."\");'>anterior</a>&nbsp;&nbsp;";
			}
			if($numPagina < $paginas){
				$siguiente = $numPagina + 1;
				$tabla .= "<a href='#' onClick='nuevaPaginaListados(".$siguiente.",\"".$idsEnviar."\",\"listadoMedicos\",\"".$estatus."\");'>Siguiente</a>&nbsp;&nbsp;";
				$tabla .= "<a href='#' onClick='nuevaPaginaListados(".$paginas.",\"".$idsEnviar."\",\"listadoMedicos\",\"".$estatus."\");'>Fin</a>&nbsp;&nbsp;";
			}
			$tabla .= "Pag. ".$numPagina." de ".$paginas."&nbsp;&nbsp;&nbsp; Registros : ".$totalRegistros;
			$tabla .= '</td></tr>';
		}
		$tabla .= '<tr>
			<td class="derechosReporte">© Smart-Scale</td>
		</tr>
	</table>';
		echo $tabla;
	}else{
		$pdf->Output();
	}
	if($tipo == 0){
		echo '<script>
			$("#divCargando").hide();
		</script>';
	}
?>