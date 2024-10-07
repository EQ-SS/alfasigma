<?php
	/*** listado de medicos ***/
	include "../conexion.php";
	
	$tamanio = array(1,4,4,4,2,2,2,2,3,4,6,2,2,2,4,4,2,3,3,2,2,3,3,2,2,2,2,2,2,2,2,2,2,1,4,2,4,2,4,3,2,2,2,2,2,2,5,2,2,2,2,1,2,2,2,2,2,2,1,2,2,2,1,2,3,2,2,2,2,2,3,2,2,2,3,2,2,2,1,2,1,2,1,2,1,2,1,2,1,2,2,2);
	//$tam = array(100,250,250,250,100,100,100,100,200,350, 100,100,250,100,300,150,150,150,100,150, 150,150,100,100,100,100,100,100,100,100, 100,100,150,250,100,100,150,100,100,100, 150,100,100,100,100,100,100,100); //,100,100,50,100,100,100,100,100,100,50,100,100,100,50,100,150,100,100,100,100,100,150,100,100,100,150,100,100,100,50,100,50,100,50,100,50,100,50,100,50,100,100,100,100);
	$tam = array(100,300,250,250,100,100,100,150,200,200, 200,550,100,150,300,100,200,200,100,100, 100,100,200,200,100,100,100,100,150,150, 250,400,150,150,100,150,150,150,150,300, 120,120,120,120,550,550);//,100,100,100,100);//,50 ,100,100,100,100,100,100,50,100,100,100,50,100,150,100,100,100,100,100,150,100,100,100,150,100,100,100,50,100,50,100,50,100,50,100,50,100,50,100,100,100,100);
	//echo "sum: ".array_sum($tam);
	$estatus = $_POST['hdnEstatus'];
	$ids = (substr($_POST['hdnIDS'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDS'], ",")) : $_POST['hdnIDS'] ;
	$tipo = $_POST['hdnTipoReporte'];
	$fechaI = $_POST['hdnFechaI'];
	$fechaF = $_POST['hdnFechaF'];

	$qMedicos = "Select DISTINCT
		/*vp.VISPERS_SNR,*/
		cl.name as Linea,
		upper(U.lname)+' '+upper(U.fname) as Representante,
		'{'+CAST(VP.pers_snr AS varchar(36))+'}' as Cod_Med,
		'{'+CAST(I.inst_snr AS VARCHAR(36))+'}' as Cod_Inst,
		SEGM_INIC.NAME AS Segment_Inic,
		SEGM_ACT.NAME AS Tipo_Pers,
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
		/*ESTILO.name as Estilo,*/
		DIV_MED_INT.name as Div_Med_Int,
		P.tel1 as Tel1,
		P.tel2 as Tel2,
		P.mobile as Celular,
		'' as Suma_Aseguradoras,
		VPLAN.PLAN_DATE AS Fecha_Plan,
		VP.visit_date as Fecha_Visita,
		VP.time as Hora_Visita,
		/*(SELECT PLAN_DATE FROM VISPERSPLAN WHERE vispersplan_snr=VP.NEXTVPP_SNR AND REC_STAT=0) AS Fecha_Sig_Vis,*/
		VP.creation_timestamp as Fecha_Creacion,
		VP.CHANGED_TIMESTAMP as 'Fecha Modificación',
		VP.SYNC_TIMESTAMP as 'Fecha Sincronización',
		/*(case when VA1.name='' then VA2.name else VA1.name end) as 'Visita Acompañada',*/
		VA2.name as 'Visita Acompañada',
		VisCode.name as 'Tipo de Visita',
		VP.latitude as Lat_Vis,
		VP.longitude as Long_Vis,
		PLW.Latitude as Lat_Dir,
		PLW.Longitude as Lon_Dir,
		VP.info_nextvisit as 'Info Siguiente Visita',
		VP.info as 'Comentario Resultado de la Visita'
				 
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
		 
		LEFT OUTER JOIN CODELIST SEGM_INIC ON P.patperweek_snr=SEGM_INIC.CLIST_SNR AND SEGM_INIC.REC_STAT=0 AND SEGM_INIC.STATUS=1
		LEFT OUTER JOIN CODELIST SEGM_ACT ON P.perstype_SNR=SEGM_ACT.CLIST_SNR AND SEGM_ACT.REC_STAT=0 AND SEGM_ACT.STATUS=1
		 
		left outer join codelist type on I.type_snr = type.clist_snr
		left outer join codelist ST on P.status_snr = ST.clist_snr
		left outer join codelist SEXO on P.sex_snr = SEXO.clist_snr
		left outer join codelist ESP on P.spec_snr = ESP.clist_snr
		left outer join codelist ESP2 on P.subspec_snr = ESP2.clist_snr
		left outer join codelist HON on P.fee_type_snr = HON.clist_snr
		left outer join codelist PT on P.patperweek_snr = PT.clist_snr
		left outer join codelist DIFVIS on P.diffvis_snr = DIFVIS.clist_snr
		/*left outer join codelist ESTILO on P.pers_style_snr = ESTILO.clist_snr*/
		left outer join codelist FV on P.frecvis_snr = FV.clist_snr
		/*left outer join codelist VA1 on VP.RM_USER_SNR = VA1.clist_snr*/
		left outer join codelist VA2 on CAST(VP.escort_snr AS VARCHAR(36)) = CAST(VA2.clist_snr AS VARCHAR(36))
		LEFT OUTER JOIN VISPERSPLAN VPLAN ON VPLAN.vispersplan_snr = VP.vispersplan_snr
		 
		left outer join codelist DIV_MED_INT on P.diffvis_snr = DIV_MED_INT.clist_snr and DIV_MED_INT.status=1 and DIV_MED_INT.rec_stat=0
		left outer join codelist CATEG on P.category_snr = CATEG.clist_snr AND CATEG.STATUS=1
		left outer join codelist VisCode on VisCode.clist_snr=VP.visit_code_SNR and VisCode.rec_stat=0 and VisCode.clib_snr='93D79107-FDC0-43E5-A946-6A09D24535CF'
		 
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
		and VP.visit_date between '".$fechaI."' and '".$fechaF."'";
		
	if($tipo == 1 || $tipo == 2){//excel
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=listadoMedicosVisitados.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else if($tipo == 3){
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array((array_sum($tam)/1.99),150));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('LISTADO DE MÉDICOS VISITADOS'));
		$pdf->Ln();
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(40,5,'Alfa Wassermann');
		$pdf->Ln();
		$pdf->SetFont('Arial',10);
		$pdf->Cell(40,5,'Fecha: '.date("d/m/Y h:i:s"));
		$pdf->Ln();
		
	}
	
	$rsMedicos = sqlsrv_query($conn, utf8_decode($qMedicos));
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
	$tamTabla = array_sum($tam) + 700;
	if( $tipo != 3){
		$tabla = '<table border="0">
			<tr>
				<td>
					<table>
						<tr>
							<td colspan="10" class="nombreReporte">LISTADO DE MÉDICOS VISITADOS</td>
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
					<div id="divListadoMedicos">';
						if($tipo == 0){
							$tabla .= '<table width="'.$tamTabla.'px" class="tablaReportes">';
						}else{
							$tabla .= '<table width="'.$tamTabla.'px" style="border-collapse: collapse;">';
						}
	}
	
	if($tipo != 3){
		if($tipo == 2){
			$tabla .= '<thead><tr>';
		}else{
			if($tipo == 1){
				$tabla .= '<thead style="background-color: #A9BCF5;font-weight:bold;border: 1px solid #000;padding: 5px 5px 5px 5px;color:#000"><tr>';
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
		if($tipo != 3){
			if($tipo == 2){
				$tabla .= '<td style="min-width:'.$tam[$i].'px;">'.utf8_encode($field['Name']).'</td>'; 
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
						if ($j == 37){
							$regMedico[$j] = (substr($val, 0, 10)=='1900-01-01') ? '' : substr($val, 0, 16);
						}else{
							$regMedico[$j] = (substr($val, 0, 10)=='1900-01-01') ? '' : substr($val, 0, 10);
						}
					}
				}
			}
			
			if($j == 2){//pers_SNR
				$qAdeguradoras = "select c.NAME from PERSON_BANK p, CODELIST c
					where p.bank_snr = c.CLIST_SNR
					and p.REC_STAT = 0
					and p.PERS_SNR = '".$regMedico[$j]."'
					order by c.SORT_NUM";
				$rsAseguradoras = sqlsrv_query($conn, $qAdeguradoras);
				$arrAseguradoras = array();
				while($aseguradora = sqlsrv_fetch_array($rsAseguradoras)){
					$arrAseguradoras[] = $aseguradora['NAME'];
				}
			}
			
			if($j == 31){
				if($tipo != 3){
					if($tipo == 2){
						$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.implode(",",$arrAseguradoras).'</td>';
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
						$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.utf8_encode($regMedico[$j]).'</td>';
					}else{
						if($tipo == 1){
							$tabla .= '<td style="border: 1px solid #000;white-space:nowrap;min-width:'.$tam[$j].'px;">'.utf8_encode($regMedico[$j]).'</td>';
						}else{
							if($j < 44){
								$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.utf8_encode($regMedico[$j]).'</td>';
							}else{
								if(strlen($regMedico[$j]) > 80){
									$regMedico[$j] = substr(utf8_encode($regMedico[$j]), 0, 79)."...";
								}
								$tabla .= '<td style="min-width:'.$tam[$j].'px;max-width:'.$tam[$j].'px;text-overflow: ellipsis;">'.utf8_encode($regMedico[$j]).'</td>';
							}
						}
					}
				}else{
					$pdf->Cell($tam[$j]/2,8,$regMedico[$j],1,0,'L',$fill);
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
	if($tipo != 3){
		$tabla .= '</tbody> ';
		if($tipo == 2){	
			$tabla .= '<tfoot>';
		}else{
			if($tipo == 1){
				$tabla .= '<tfoot style="background-color: #A9BCF5;font-weight:bold;border: 1px solid #000;padding: 5px 5px 5px 5px;color:#000;">';
			}else{
				$tabla .= '<tfoot>';
			}
		}
				$tabla .= '<tr>
								<td colspan="10">Total registros: '.$i.'</td>
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
	}else{
		$pdf->Output();
	}
	if($tipo == 0){
		echo '<script>
			$("#divCargando").hide();
		</script>';
	}
?>