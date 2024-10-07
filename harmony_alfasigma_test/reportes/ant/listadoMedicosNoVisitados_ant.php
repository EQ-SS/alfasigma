<?php
	/*** listado de medicos ***/
	include "../conexion.php";
	
	$tamanio = array(1,4,4,4,2,2,2,2,3,4,6,2,2,2,4,4,2,3,3,2,2,3,3,2,2,2,2,2,2,2,2,2,2,1,4,2,4,2,4,3,2,2,2,2,2,2,5,2,2,2,2,1,2,2,2,2,2,2,1,2,2,2,1,2,3,2,2,2,2,2,3,2,2,2,3,2,2,2,1,2,1,2,1,2,1,2,1,2,1,2,2,2);
	//$tam = array(100,250,250,250,100,100,100,100,200,350, 100,100,250,100,300,150,150,150,100,150, 150,150,100,100,100,100,100,100,100,100, 100,100,150,250,100,100,150,100,100,100, 150,100,100,100,100,100,100,100); //,100,100,50,100,100,100,100,100,100,50,100,100,100,50,100,150,100,100,100,100,100,150,100,100,100,150,100,100,100,50,100,50,100,50,100,50,100,50,100,50,100,100,100,100);
	$tam = array(100,250,250,250,200,120,250,150,350,300, 100,200,100,100,100,100,150,150,100,100, 100,100,100,100,100,100,100);//,100,100,100, 300,100,100,100,100,200,150,150,150,100, 100,100,100,100,500,500);//,100,100,100,100);//,50 ,100,100,100,100,100,100,50,100,100,100,50,100,150,100,100,100,100,100,150,100,100,100,150,100,100,100,50,100,50,100,50,100,50,100,50,100,50,100,100,100,100);
	//echo "sum: ".array_sum($tam);
	$estatus = $_POST['hdnEstatus'];
	$ids = (substr($_POST['hdnIDS'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDS'], ",")) : $_POST['hdnIDS'] ;
	$tipo = $_POST['hdnTipoReporte'];
	$fechaI = $_POST['hdnFechaI'];
	$fechaF = $_POST['hdnFechaF'];
	
	$qMedicos = "Select
		cl.name as Linea,
		upper(U.lname)+' '+upper(U.fname) as Representante,
		'{'+CAST(I.inst_snr AS VARCHAR(36))+'}' as Cod_Inst,
		'{'+CAST(P.pers_snr AS VARCHAR(36))+'}' as Cod_Med,
		upper(P.lname) as Paterno,
		upper(P.mothers_lname) as Materno,
		upper(P.fname) as Nombre,
		upper(type.name) as Tipo_Cons,
		upper(I.street1) as Direccion,
		/*IMS.name as Brick,*/
		City.name as Colonia,
		City.zip as Cod_Postal,
		Dst.name as Ciudad,
		State.name as Estado,
		ST.name as Status,
		SEXO.name as Sexo,
		CATEG.name as Categ,
		ESP.name as Esp,
		ESP2.name as Sub_Esp,
		HON.name as Hon,
		PT.name as Pac,
		DIFVIS.name as Difvis,
		/*ESTILO.name as Estilo,*/
		FV.name as Frec_vis,
		case when year(P.birthdate) > 0 then year(getdate()) - year(P.birthdate) else '' end as Edad,
		P.tel1 as Tel1,
		P.tel2 as Tel2,
		P.mobile as Celular
		/*VP.change_date as Fecha_Mod_Baja*/
		from person P
		inner join perslocwork PLW on P.pers_snr = PLW.pers_snr
		inner join inst I on I.inst_snr = PLW.INST_SNR
		inner join pers_srep_work PSW on PSW.pwork_snr=PLW.pwork_SNR 
		left outer join City on  City.city_snr = I.city_snr
		inner join  District as Dst on city.distr_snr = Dst.distr_snr
		inner join State on Dst.state_snr = State.state_snr
		left outer join Brick as IMS on IMS.brick_snr = City.brick_snr
		--left outer join vperson VP on VP.pers_snr = P.pers_snr
		inner join User_Territ as UT on psw.user_snr= ut.user_snr and UT.inst_snr = I.inst_snr and i.inst_snr = ut.inst_snr and ut. rec_stat=0
		inner join Users as U on U.user_snr = UT.user_snr
		inner join compline as cl on U.cline_snr = cl.cline_snr
		left outer join codelist type on I.type_snr = type.clist_snr
		left outer join codelist ST on P.status_snr = ST.clist_snr
		left outer join codelist SEXO on P.sex_snr = SEXO.clist_snr
		 
		--left outer join pers_line_profile PLP on PLP.pers_snr=P.pers_snr and PLP.rec_stat=0 and PLP.cline_snr= '{00000000-0000-0000-0000-000000000000}'
		left outer join codelist CATEG on P.category_snr= CATEG.clist_snr AND CATEG.REC_STAT=0 and CATEG.status=1
		 
		left outer join codelist ESP on P.spec_snr = ESP.clist_snr
		left outer join codelist ESP2 on P.subspec_snr = ESP2.clist_snr
		left outer join codelist HON on P.fee_type_snr = HON.clist_snr
		left outer join codelist PT on P.patperweek_snr = PT.clist_snr
		left outer join codelist DIFVIS on P.diffvis_snr = DIFVIS.clist_snr
		/*left outer join codelist ESTILO on P.pers_style_snr = ESTILO.clist_snr*/
		left outer join codelist FV on P.frecvis_snr = FV.clist_snr
		/*left outer join codelist PUESTO on P.pers_position_snr = PUESTO.clist_snr*/
		 
		where
		P.pers_snr <> '00000000-0000-0000-0000-000000000000'
		and P.rec_stat=0
		and PLW.rec_stat=0
		and PSW.rec_Stat=0
		and UT.rec_stat=0
		and U.rec_stat=0
		and U.status=1
		and U.user_type=4
		/*--and P.status_snr in ('{B426FB78-8498-4185-882D-E0DC381460E8}', '{00000000-0000-0000-0000-000000000000}')*/
		and U.user_snr in ('".$ids."')
		/*--and P.STATUS_SNR IN ()*/
		and (P.pers_snr not in (select distinct VISPERS.pers_snr from visitpers VISPERS where VISPERS.visit_date between '".$fechaI."' and '".$fechaF."' and VISPERS.rec_stat=0 and U.user_snr=VISPERS.user_snr))
		 
		order by Cl.name,U.lname,U.fname,P.lname,P.mothers_lname,P.fname ";
		
	if($tipo == 1 || $tipo == 2){//excel
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=listadoMedicosNoVisitados.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else if($tipo == 3){
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array((array_sum($tam)/1.99),150));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('LISTADO DE MÉDICOS NO VISITADSO'));
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
	$tamTabla = array_sum($tam) + 350;
	if( $tipo != 3){
		$tabla = '<table border="0">
			<tr>
				<td>
					<table>
						<tr>
							<td colspan="10" class="nombreReporte">LISTADO DE MÉDICOS NO VISITADOS</td>
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
						$regMedico[$j] = substr($val, 0, 10);
					}
				}
			}
			
			/*if($j == 2){//pers_SNR
				$qAdeguradoras = "select c.NAME from PERSIMAGE p, CODELIST c
					where p.IMAGE_SNR = c.CLIST_SNR
					and p.REC_STAT = 0
					and p.PERS_SNR = '".$regMedico[$j]."'
					order by c.SORT_NUM";
				$rsAseguradoras = sqlsrv_query($conn, $qAdeguradoras);
				$arrAseguradoras = array();
				while($aseguradora = sqlsrv_fetch_array($rsAseguradoras)){
					$arrAseguradoras[] = $aseguradora['NAME'];
				}
			}*/
			
			/*if($j == 32){
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
			}else{*/
				if($tipo != 3){
					if($tipo == 2){
						$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.$regMedico[$j].'</td>';
					}else{
						if($tipo == 1){
							$tabla .= '<td style="border: 1px solid #000;white-space:nowrap;min-width:'.$tam[$j].'px;">'.$regMedico[$j].'</td>';
						}else{
							if($j < 44){
								$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.$regMedico[$j].'</td>';
							}else{
								if(strlen($regMedico[$j]) > 80){
									$regMedico[$j] = substr($regMedico[$j], 0, 79)."...";
								}
								$tabla .= '<td style="min-width:'.$tam[$j].'px;max-width:'.$tam[$j].'px;text-overflow: ellipsis;">'.$regMedico[$j].'</td>';
							}
						}
					}
				}else{
					$pdf->Cell($tam[$j]/2,8,$regMedico[$j],1,0,'L',$fill);
				}
			//}
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