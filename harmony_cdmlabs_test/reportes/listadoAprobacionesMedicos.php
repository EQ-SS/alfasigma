<?php
	/*** listado de medicos ***/
	include "../conexion.php";
	
	$tamanio = array(1,4,4,4,2,2,2,2,3,4,6,2,2,2,4,4,2,3,3,2,2,3,3,2,2,2,2,2,2,2,2,2,2,1,4,2,4,2,4,3,2,2,2,2,2,2,5,2,2,2,2,1,2,2,2,2,2,2,1,2,2,2,1,2,3,2,2,2,2,2,3,2,2,2,3,2,2,2,1,2,1,2,1,2,1,2,1,2,1,2,2,2);
	$tam = array(100,350,350,350,350,550,100,450,100,250, 250,200,250,200,200,100,150,100,150,200 ,1250,150,300,150,250,250,300,250,250,300 ,250,250,100,100,200,200);//,150,100,100,100, 350,100,100,100,100,100,100,100); //,100,100,50,100,100,100,100,100,100,50,100,100,100,50,100,150,100,100,100,100,100,150,100,100,100,150,100,100,100,50,100,50,100,50,100,50,100,50,100,50,100,100,100,100);
	$estatus = $_POST['hdnEstatusListado'];
	$ids = (substr($_POST['hdnIDSListado'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDSListado'], ",")) : $_POST['hdnIDSListado'] ;
	$tipo = $_POST['hdnTipoListado'];
	$fechaI = $_POST['hdnFechaIListado'];
	$fechaF = $_POST['hdnFechaFListado'];
	
	$qMedicos = "Select
		/*aps.APPROVAL_STATUS_SNR,*/
		cl.name as Linea,
		upper(U.lname)+' '+upper(U.fname) as Representante,
		'{'+CAST((case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then pa.p_pers_snr else P.pers_snr end) as VARCHAR(36))+'}' as Cod_Med,
		'{'+CAST((case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then pa.plw_INST_SNR else I.inst_snr end) as VARCHAR(36))+'}' as Cod_Inst,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(pa.P_LNAME)+' '+upper(pa.P_MOTHERS_LNAME)+' '+upper(pa.P_FNAME) else upper(P.lname)+' '+upper(P.MOTHERS_LNAME)+' '+upper(P.fname) end) as Medico,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(insta.STREET1) else upper(I.street1) end) as Direccion,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then (case when insta.num_ext='0' then '' else insta.num_ext end)
		else (case when I.num_ext='0' then '' else I.num_ext end) end) as Num_Ext,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then Citya.name else City.name end) as Colonia,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then Citya.zip else City.zip end) as Cod_Postal,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then Dsta.name else Dst.name end) as Ciudad,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then Statea.name else State.name end) as Estado,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then isnull(SEXOa.name,' ') else SEXO.name end) as Sexo,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then isnull(ESP2a.name,' ') else isnull(ESP2.name,' ') end) as Sub_Esp,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then isnull(HONa.name,' ') else HON.name end) as Honorarios,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then isnull(PTa.name,' ') else isnull(PT.name,' ') end) as PacxSem,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then isnull(Pa.p_prof_id,' ') else P.prof_id end) as Cedula,
		pa.creation_timestamp as 'Fecha Alta',	
		pa.p_movement_type as Tipo_mov,
		(case when aps.APPROVED_STATUS in (1,4) then 'Pendiente' when aps.APPROVED_STATUS=2 then 'Aprobado' when aps.APPROVED_STATUS=3 then 'Rechazado' when aps.APPROVED_STATUS=4 then 'Pendiente' end) as Estatus_mov,
		isnull(STab.name,' ') as 'Tipo Baja',
		isnull(pa.PLW_DEL_REASON,' ') as 'Motivo Baja',
		isnull(aps.date_change,' ') as 'Fecha solicitud cambio',
		isnull(usa.LNAME+' '+usa.FNAME,' ') as 'Persona aprueba',
		(case when aps.APPROVED_STATUS='1' then null else aps.APPROVED_DATE end) as 'Fecha aprobacion cambio',
		/*isnull(MRa.NAME,' ') AS 'Motivo Rechazo',*/
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(pa.P_LNAME) else upper(P.lname) end) as Paterno,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(pa.P_mothers_lname) else upper(P.mothers_lname) end) as Materno,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(pa.P_FNAME) else upper(P.fname) end) as Nombre,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then ' ' else isnull(pa.P_LNAME,' ') end) as Paterno_Nvo,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then ' ' else isnull(pa.P_mothers_lname,' ') end) as Materno_Nvo,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then ' ' else isnull(pa.P_FNAME,' ') end) as Nombre_Nvo,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(espa.NAME) else upper(ESP.name) end) as Esp,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then ' ' else isnull(espa.NAME,' ') end) as Esp_Nvo,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(CATa.NAME) else upper(CAT.NAME) end) as CATEG,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then ' ' else isnull(CATa.NAME,' ') end) as CATEG_Nvo,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(STa.name) else upper(ST.name) end) as Estatus,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then ' ' else isnull(STa.name,' ') end) as Estatus_Nvo

		
		from APPROVAL_STATUS APS
		Inner join PERSON_APPROVAL PA on PA.PERS_APPROVAL_SNR=APS.PERS_APPROVAL_SNR and PA.REC_STAT=0
		left outer join Users as U on U.user_snr = APS.change_user_snr and U.rec_stat=0
		left outer join person P on P.PERS_SNR=PA.P_PERS_SNR and P.rec_stat=0
		left outer join perslocwork PLW on P.pers_snr = PLW.pers_snr and PLW.rec_stat=0
		left outer join inst I on I.inst_snr = PLW.INST_SNR and I.rec_stat=0
		left outer join pers_srep_work PSW on PSW.pwork_snr=PLW.pwork_SNR and PSW.rec_Stat=0 
		left outer join User_Territ as UT on psw.user_snr= ut.user_snr and i.inst_snr = ut.inst_snr and ut.rec_stat=0
		left outer join City on City.city_snr = I.city_snr
		left outer join District as Dst on city.distr_snr = Dst.distr_snr
		left outer join State on Dst.state_snr = State.state_snr
		left outer join Brick as IMS on IMS.brick_snr = City.brick_snr
		left outer join compline as cl on U.cline_snr = cl.cline_snr
		left outer join inst_Type IT on IT.inst_type=I.inst_type and IT.rec_Stat=0
		left outer join codelist type on I.type_snr = type.clist_snr
		left outer join codelist ST on P.status_snr = ST.clist_snr
		left outer join codelist SEXO on P.sex_snr = SEXO.clist_snr
		left outer JOIN CODELIST CAT ON CAT.CLIST_SNR=P.category_snr AND P.REC_STAT=0
		left outer join codelist ESP on P.spec_snr = ESP.clist_snr
		left outer join codelist ESP2 on P.subSpec_snr = ESP2.clist_snr AND ESP2.STATUS=1 AND ESP2.REC_STAT=0
		left outer join codelist HON on P.fee_type_snr = HON.clist_snr
		left outer join codelist FV on P.frecvis_snr=FV.clist_snr and FV.rec_stat=0 and FV.status=1
		left outer join codelist PT on P.patperweek_snr = PT.clist_snr AND PT.REC_STAT=0 AND PT.STATUS=1	 
		left outer join PERSON_UD PUD ON PUD.PERS_SNR=P.PERS_SNR AND PUD.REC_STAT=0
		 		 
		left outer join Users usa on usa.USER_SNR = aps.approved_user_snr and usa.REC_STAT=0
		left outer join Inst insta on pa.plw_INST_SNR = insta.inst_snr and insta.REC_STAT=0
		left outer join City Citya on Citya.city_snr = Insta.city_snr
		left outer join District as Dsta on citya.distr_snr = Dsta.distr_snr
		left outer join State Statea on Dsta.state_snr = Statea.state_snr
		left outer join Brick as IMSa on IMSa.brick_snr = Citya.brick_snr
		left outer join inst_Type ITa on ITa.inst_type=Insta.inst_type and ITa.rec_Stat=0
		left outer join codelist typea on Insta.type_snr = typea.clist_snr
		left outer join codelist STa on Pa.p_status_snr = STa.clist_snr
		left outer join codelist SEXOa on Pa.p_sex_snr = SEXOa.clist_snr
		left outer join CODELIST CATa ON CATa.CLIST_SNR=Pa.p_category_snr AND CATa.REC_STAT=0
		left outer join codelist ESPa on Pa.p_spec_snr = ESPa.clist_snr and espa.REC_STAT=0
		left outer join codelist ESP2a on Pa.plw_spec2_snr = ESP2a.clist_snr AND ESP2a.STATUS=1 AND ESP2a.REC_STAT=0
		left outer join codelist HONa on Pa.p_fee_type_snr = HONa.clist_snr
		left outer join codelist FVa on Pa.p_frecvis_snr=FVa.clist_snr and FVa.rec_stat=0 and FVa.status=1
		left outer join codelist PTa on Pa.p_patperweek_snr = PTa.clist_snr AND PTa.REC_STAT=0 AND PTa.STATUS=1
		left outer join codelist MRa on APS.REJECT_REASON_SNR = MRa.clist_snr AND MRa.REC_STAT=0 AND MRa.STATUS=1
		left outer join codelist STab on Pa.plw_del_status_snr = STab.clist_snr AND STab.REC_STAT=0 AND STab.STATUS=1
	
		left outer join PERSON_UD PUDa ON PUDa.PERS_SNR=Pa.P_PERS_SNR AND PUDa.REC_STAT=0
		 		 
		 
		where APS.rec_stat=0
		and U.status=1
		and U.user_type=4
		/*and P.status_snr in ({?persstatus})*/
		and APS.CHANGE_USER_SNR in ('".$ids."')
		and cast(aps.date_change as date) between '".$fechaI."' and '".$fechaF."'
		/*and cast(aps.date_change as date) between '2019-06-01' and '2019-06-12'*/
		/*and aps.APPROVAL_STATUS_SNR<>'00000000-0000-0000-0000-000000000000'*/
		and pa.P_PERS_SNR <> '00000000-0000-0000-0000-000000000000'
		and aps.TABLE_NR=456
		 
		Order by cl.name,U.lname,P.lname,P.mothers_lname,P.fname ";
	
	//echo $qMedicos."<br>";
		
	if($tipo == 1 || $tipo == 2){//excel
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=listadoAprobacionesMedicos.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else if($tipo == 3){
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array((array_sum($tam)/1.98),150));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('LISTADO DE APROBACIONES DE MÉDICOS'));
		$pdf->Ln();
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(40,5,'Consorcio Dermatológico de México');
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
	$tamTabla = array_sum($tam) + 20;
	if( $tipo != 3){
		$tabla = '<table border="0">
			<tr>
				<td>
					<table>
						<tr>
							<td colspan="10" class="nombreReporte">LISTADO DE APROBACIONES DE MÉDICOS</td>
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
		//if($i < 47){
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
				$pdf->Cell($tam[$i]/2,8,utf8_encode($field['Name']),1,0,'C',1);
			}
		//}
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
		for($j=0;$j<sqlsrv_num_fields($rsMedicos);$j++){
			if(is_object($regMedico[$j])){
				foreach ($regMedico[$j] as $key => $val) {
					if(strtolower($key) == 'date'){
						$regMedico[$j] = substr($val, 0, 10);
					}
				}
			}
			
			if($j == 27){
				if(strtoupper($regMedico[17]) == 'C'){
					if(strtoupper($regMedico[24]) == strtoupper($regMedico[27])){
						$regMedico[27] = '';
					}
				}
			}
			if($j == 28){
				if(strtoupper($regMedico[17]) == 'C'){
					if(strtoupper($regMedico[25]) == strtoupper($regMedico[28])){
						$regMedico[28] = '';
					}
				}
			}
			if($j == 29){
				if(strtoupper($regMedico[17]) == 'C'){
					if(strtoupper($regMedico[26]) == strtoupper($regMedico[29])){
						$regMedico[29] = '';
					}
				}
			}
			if($j == 31){
				if(strtoupper($regMedico[17]) == 'C'){
					if(strtoupper($regMedico[30]) == strtoupper($regMedico[31])){
						$regMedico[31] = '';
					}
				}
			}
			if($j == 33){
				if(strtoupper($regMedico[17]) == 'C'){
					if(strtoupper($regMedico[32]) == strtoupper($regMedico[33])){
						$regMedico[33] = '';
					}
				}
			}
			if($j == 35){
				if(strtoupper($regMedico[17]) == 'C'){
					if(strtoupper($regMedico[34]) == strtoupper($regMedico[35])){
						$regMedico[35] = '';
					}
				}
			}
			if($tipo != 3){
				if($tipo == 2){
					$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.utf8_encode($regMedico[$j]).'</td>';
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