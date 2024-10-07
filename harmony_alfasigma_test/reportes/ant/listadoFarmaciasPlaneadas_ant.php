<?php
	/*** listado de farmacias ***/
	include "../conexion.php";
	
	$tamanio = array(1,4,4,4,2,2,2,2,3,4,6,2,2,2,4,4,2,3,3,2,2,3,3,2,2,2,2,2,2,2,2,2,2,1,4,2,4,2,4,3,2,2,2,2,2,2,5,2,2,2,2,1,2,2,2,2,2,2,1,2,2,2,1,2,3,2,2,2,2,2,3,2,2,2,3,2,2,2,1,2,1,2,1,2,1,2,1,2,1,2,2,2);
	$tam = array(100,300,250,150,250,250,350,200,300,100, 200,200,150,100,100,150,150,150,100,150, 150,150,150,150,150,150,150,100,100,100, 100,100,150,250);//,100,100,150,100,100,100, 350,100,100,100,100,100,100,100); //,100,100,50,100,100,100,100,100,100,50,100,100,100,50,100,150,100,100,100,100,100,150,100,100,100,150,100,100,100,50,100,50,100,50,100,50,100,50,100,50,100,100,100,100);
	$estatus = $_POST['hdnEstatusInst'];
	$ids = (substr($_POST['hdnIDS'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDS'], ",")) : $_POST['hdnIDS'] ;
	$tipo = $_POST['hdnTipoReporte'];
	$fechaI = $_POST['hdnFechaI'];
	$fechaF = $_POST['hdnFechaF'];
	
	$query = "Select
		cl.name as Linea,
		upper(U.lname)+' '+upper(U.fname) as Representante,
		'{'+CAST(I.inst_snr AS VARCHAR(36))+'}' as Codigo_inst,
		upper(T.name) as Tipo,
		upper(ST.name) as Sub_Tipo,
		upper(I.name) as Nombre,
		/*upper(I.branch) as Sucursal,*/
		upper(I.street1) as Direccion,
		/*upper(I.numint) as NumInt,*/
		upper(I.num_ext) as NumExt,
		City.name as Colonia,
		City.zip as Cod_Postal,
		Dst.name as Ciudad,
		State.name as Estado,
		upper(stat_type.name) as Status,
		upper(ubic.name) as Ubicacion,
		upper(lst_emp.name) as NoEmpleados,
		upper(calif_rep.name) as 'Valor para el rep',
		UPPER(FLONORM28.NAME) AS FLONORM28,
		UPPER(FLONORM12.NAME) AS FLONORM12,
		UPPER(FLONORMS.NAME) AS FLONORM_SUSP,
		UPPER(ZIRFOS.NAME) AS ZIRFOS,
		upper(mayo_1.name) as Mayorista1,
		upper(num_turnos.name) as NumeroTurnos,
		upper(cat_AW.name) as CategoriaAW,
		upper(I.tel1) as Telefono,
		upper(I.tel2) as Telefono2,
		/*upper(I.street2) as Referencia,*/
		/*upper(I.fax) as Fax,*/
		cat_type.name as Categ,
		VP.plan_date as Fecha_Plan,
		case when VI.visit_date is null then 'Planeado no visitado' else 'Visitado' end as planeado,
		VI.visit_date as Fecha_visita, 
		upper(I.info) as Info
		/*
		upper(I.rfc) as Rfc,
		upper(code.name) as VolUMesRX,
		upper(timelist.name) as Horarios,
		upper(tipac.name) as TipodePacientes,
		upper(VC.name) as Plan_Visita,*/
		
		from Inst I
		left outer join City on  City.city_snr=I.city_snr
		left outer join codelist cat_type on I.category_snr=cat_type.clist_snr
		left outer join codelist stat_type on I.status_snr=stat_type.clist_snr
		left outer join codelist type on I.type_snr=type.clist_snr
		 
		left outer join inst_profile profile on I.inst_snr=profile.inst_snr
		left outer join codelist code on profile.saleqtymnthrx_snr=code.clist_snr
		 
		left outer join inst_profile_UD profile2 on PROFILE.instPROFILE_snr=profile2.instPROFILE_snr
		left outer join codelist lst_emp on profile2.NUMERO_DE_EMPLEADOS=lst_emp.clist_snr
		 
		left outer join inst_profile profile3 on I.inst_snr=profile3.inst_snr
		left outer join codelist timelist on profile3.worktime_snr=timelist.clist_snr
		 
		left outer join inst_profile_ud profile4 on PROFILE.instPROFILE_snr=profile4.instPROFILE_snr
		left outer join codelist calif_rep on profile4.Valor_para_el_Representante=calif_rep.clist_snr
		 
		left outer join inst_profile_UD profile5 on PROFILE.instPROFILE_snr=profile5.instPROFILE_snr
		left outer join codelist ubic on profile5.UBICACION=ubic.clist_snr
		 
		left outer join inst_profile profile6 on I.inst_snr=profile6.inst_snr
		left outer join codelist tipac on profile6.PATIENTTYPE_SNR=tipac.clist_snr
		 
		left outer join inst_profile_UD profile28 on PROFILE.INSTPROFILE_snr=profile28.INSTPROFILE_snr
		left outer join codelist FLONORM28 on profile28.EXISTENCIA_FLONORM_28=FLONORM28.clist_snr
		 
		left outer join inst_profile_UD profile12 on PROFILE.INSTPROFILE_snr=profile12.INSTPROFILE_snr
		left outer join codelist FLONORM12 on profile12.EXISTENCIA_FLONORM_12=FLONORM12.clist_snr
		 
		left outer join inst_profile_UD profileS on PROFILE.INSTPROFILE_snr=profileS.INSTPROFILE_snr
		left outer join codelist FLONORMS on profileS.EXISTENCIA_FLONORM_SUSP=FLONORMS.clist_snr
		 
		left outer join inst_profile_UD profileZ on PROFILE.INSTPROFILE_snr=profileZ.INSTPROFILE_snr
		left outer join codelist ZIRFOS on profileZ.EXISTENCIA_ZIRFOS=ZIRFOS.clist_snr
		 
		left outer join inst_profile_UD profile7 on PROFILE.instPROFILE_snr=profile7.instPROFILE_snr
		left outer join codelist mayo_1 on profile7.mayorista_principal=mayo_1.clist_snr
		 
		left outer join inst_profile_UD profile8 on PROFILE.instPROFILE_snr=profile8.instPROFILE_snr
		left outer join codelist num_turnos on profile8.Numero_De_Turnos=num_turnos.clist_snr
		 
		left outer join inst_profile_UD profile9 on PROFILE.instPROFILE_snr=profile9.instPROFILE_snr
		left outer join codelist cat_AW on profile9.Categoria_AW=cat_AW.clist_snr
		 
		inner join  District as Dst on city.distr_snr=Dst.distr_snr
		inner join State on Dst.state_snr = State.state_snr
		inner join Visinstplan VP on VP.inst_snr = I.inst_snr
		inner join Users as U on U.user_snr = VP.user_snr
		inner join compline as CL on CL.cline_snr = U.cline_snr
		inner join codelist as ST on ST.clist_snr = I.type_snr
		inner join clist_categ as T on T.clcat_snr = ST.clcat_snr
		/*left outer join codelist as VC on VC.clist_snr = VP.plansuggest_snr*/
		left outer join visitinst VI on VI.visinst_snr = VP.visinst_snr
		 
		where
		I.inst_snr <> '00000000-0000-0000-0000-000000000000'
		and I.rec_stat=0
		and I.inst_type=2
		and VP.rec_stat=0
		and U.rec_stat=0
		and U.status=1
		and U.user_type=4
		and I.status_snr in ('".$estatus."')
		and U.user_snr in ('".$ids."')
		and VP.PLAN_DATE between '".$fechaI."' and '".$fechaF."' 
		order by Cl.name,U.lname,U.fname,VP.plan_date,I.name,stat_type.name";
	
	//echo $query."<br>";
		
	if($tipo == 1 || $tipo == 2){//excel
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=listadoFarmaciasPlaneadas.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else if($tipo == 3){
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array((array_sum($tam)/2),150));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('LISTADO DE FARMACIAS PLANEADAS'));
		$pdf->Ln();
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(40,5,'Alfa Wassermann');
		$pdf->Ln();
		$pdf->SetFont('Arial',10);
		$pdf->Cell(40,5,'Fecha: '.date("d/m/Y h:i:s"));
		$pdf->Ln();
		
	}
	
	$rs = sqlsrv_query($conn, utf8_decode($query));
	if( $rs === false ) {
		if( ($errors = sqlsrv_errors() ) != null) {
			foreach( $errors as $error ) {
				echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
				echo "code: ".$error[ 'code']."<br />";
				echo "message: ".$error[ 'message']."<br />";
			}
		}
	}
	
	$tamTabla = array_sum($tam) + 450;
	if( $tipo != 3){
		$tabla = '<table border="0">
			<tr>
				<td>
					<table>
						<tr>
							<td colspan="10" class="nombreReporte">LISTADO DE FARMACIAS PLANEADAS</td>
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
					<div id="divReportesRepo">';
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
	foreach(sqlsrv_field_metadata($rs) as $field){
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
	while($registro = sqlsrv_fetch_array($rs)){
		if($tipo != 3){
			$tabla .= '<tr>';
		}
		//$aseguradoras = array();
		for($j=0;$j<sqlsrv_num_fields($rs);$j++){
			if(is_object($registro[$j])){
				foreach ($registro[$j] as $key => $val) {
					if(strtolower($key) == 'date'){
						$registro[$j] = substr($val, 0, 10);
					}
				}
			}
			/*if($j == 2){//pers_snr
				$qAdeguradoras = "select c.NAME from PERSIMAGE p, CODELIST c
					where p.IMAGE_SNR = c.CLIST_SNR
					and p.REC_STAT = 0
					and p.PERS_SNR = '".$registro[$j]."'
					order by c.SORT_NUM";
				$rsAseguradoras = sqlsrv_query($conn, $qAdeguradoras);
				$arrAseguradoras = array();
				while($aseguradora = sqlsrv_fetch_array($rsAseguradoras)){
					$arrAseguradoras[] = $aseguradora['NAME'];
				}
			}
			if($j == 40){
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
						$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.$registro[$j].'</td>';
					}else{
						if($tipo == 1){
							$tabla .= '<td style="border: 1px solid #000;white-space:nowrap;min-width:'.$tam[$j].'px;">'.$registro[$j].'</td>';
						}else{
							$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.$registro[$j].'</td>';
						}
					}
				}else{
					$pdf->Cell($tam[$j]/2,8,$registro[$j],1,0,'L',$fill);
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
	}else{
		$pdf->Output();
	}
	if($tipo == 0){
		echo '<script>
			$("#divCargando").hide();
		</script>';
	}
?>