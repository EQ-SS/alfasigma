<?php
	/*** listado de farmacias ***/
	include "../conexion.php";
	
	$tamanio = array(1,4,4,4,2,2,2,2,3,4,6,2,2,2,4,4,2,3,3,2,2,3,3,2,2,2,2,2,2,2,2,2,2,1,4,2,4,2,4,3,2,2,2,2,2,2,5,2,2,2,2,1,2,2,2,2,2,2,1,2,2,2,1,2,3,2,2,2,2,2,3,2,2,2,3,2,2,2,1,2,1,2,1,2,1,2,1,2,1,2,2,2);
	$tam = array(100,350,350,250,250,150,100,200,850,150, 300,150,100,200,100,200,450,450,550,550, 450,100,450,100,100,100,200,200);//,100,100,100, 100,100,150,250,100);//,100,150,100,100,100, 350,100,100,100,100,100,100,100); //,100,100,50,100,100,100,100,100,100,50,100,100,100,50,100,150,100,100,100,100,100,150,100,100,100,150,100,100,100,50,100,50,100,50,100,50,100,50,100,50,100,100,100,100);
	//$estatus = $_POST['hdnEstatusInstListado'];
	$ids = (substr($_POST['hdnIDSListado'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDSListado'], ",")) : $_POST['hdnIDSListado'] ;
	$tipo = $_POST['hdnTipoListado'];
	$fechaI = $_POST['hdnFechaIListado'];
	$fechaF = $_POST['hdnFechaFListado'];
	
	$query = "Select
		/*aps.APPROVAL_STATUS_SNR,*/
		cl.name as Linea,
		upper(U.lname)+' '+upper(U.fname) as Representante,
		'{'+CAST((case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then ia.I_INST_SNR else I.inst_snr end) as VARCHAR(36))+'}' as Cod_Inst,
		(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then Dsta.name else Dst.name end) as Ciudad,
		(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then Statea.name else State.name end) as Estado,
		isnull(IA.creation_timestamp,' ') as 'Fecha Alta', 
		IA.I_movement_type as 'Tipo mov',
		(case when aps.APPROVED_STATUS in (1,4) then 'Pendiente' when aps.APPROVED_STATUS=2 then 'Aprobado' when aps.APPROVED_STATUS=3 then 'Rechazado' when aps.APPROVED_STATUS=4 then 'Pendiente' end) as 'Estatus mov',
		isnull(IA.I_DEL_REASON,' ') as 'Motivo Baja',
		isnull(aps.date_change,' ') as 'Fecha Solicitud Cambio',
		isnull(usa.LNAME+' '+usa.FNAME,' ') as 'Persona Aprueba',
		(case when aps.APPROVED_DATE='1900-01-01' then null else aps.APPROVED_DATE end) as 'Fecha aprobacion cambio',
		(case when I.INST_TYPE=1 then 'HOSPITAL' when I.INST_TYPE=2 then 'FARMACIA' when I.INST_TYPE=3 then 'CONSULTORIO' when I.INST_TYPE=5 then 'OTRO' end) as 'Tipo Inst',
		upper(ST.name) as SubTipo,
		(case when IA.I_INST_TYPE=1 then 'HOSPITAL' when IA.I_INST_TYPE=2 then 'FARMACIA' when IA.I_INST_TYPE=3 then 'CONSULTORIO' when IA.I_INST_TYPE=5 then 'OTRO' end) as 'Tipo Inst Nvo',
		upper(STa.name) as 'SubTipo Nvo',

		(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(Ia.I_name) else upper(I.NAME) end) as Nombre,
		(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then ' ' else isnull(Ia.I_name,' ') end) as Nombre_Nvo,
		(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(Ia.I_street1) else upper(I.street1) end) as Direccion,
		(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then ' ' else isnull(Ia.I_street1,' ') end) as Direccion_Nva,
		(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(Citya.NAME) else upper(City.NAME) end) as Colonia,
		(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(Citya.zip) else upper(City.zip) end) as CP,
		(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then ' ' else isnull(Citya.NAME,' ') end) as Colonia_Nva,
		(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then ' ' else isnull(Citya.zip,' ') end) as CP_Nvo, 
		(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(CATa.NAME) else upper(CAT.NAME) end) as CATEG,
		(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then ' ' else isnull(CATa.NAME,' ') end) as CATEG_Nvo,
		(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(Statusa.name) else upper(Status.name) end) as Estatus,
		(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then ' ' else isnull(Statusa.name,' ') end) as Estatus_Nvo

		
		from APPROVAL_STATUS APS
		Inner join INST_APPROVAL IA on IA.INST_APPROVAL_SNR=APS.INST_APPROVAL_SNR and IA.REC_STAT=0
		left outer join Users as U on U.user_snr = APS.change_user_snr and U.rec_stat=0
		left outer join inst I on I.inst_snr = IA.I_INST_SNR and I.rec_stat=0
		left outer join User_Territ as UT on UT.inst_snr =I.inst_snr and UT.rec_stat=0
		left outer join compline as cl on U.cline_snr=cl.cline_snr
		left outer join City on City.city_snr=I.city_snr
		left outer join District as Dst on city.distr_snr=Dst.distr_snr
		left outer join State on Dst.state_snr = State.state_snr
		left outer join Brick as IMS on IMS.brick_snr = City.brick_snr
		left outer join codelist as T on I.type_snr = T.clist_snr
		left outer join codelist as ST on I.subtype_snr = ST.clist_snr
		left outer join codelist Status on I.status_snr=Status.clist_snr
		left outer join codelist CAT on I.category_snr=CAT.clist_snr

		left outer join Users usa on usa.USER_SNR = aps.approved_user_snr and usa.REC_STAT=0
		left outer join City Citya on Citya.city_snr = IA.I_city_snr
		left outer join District as Dsta on citya.distr_snr = Dsta.distr_snr
		left outer join State Statea on Dsta.state_snr = Statea.state_snr
		left outer join Brick as IMSa on IMSa.brick_snr = Citya.brick_snr
		left outer join codelist as Ta on IA.I_type_snr = Ta.clist_snr
		left outer join codelist as STa on IA.I_subtype_snr = STa.clist_snr
		left outer join codelist Statusa on IA.I_status_snr=Statusa.clist_snr
		left outer join codelist CATa on IA.I_category_snr=CATa.clist_snr

		 
		where APS.rec_stat=0
		and U.status=1
		and U.user_type=4
		/*and I.status_snr in ({?status})*/
		and APS.CHANGE_USER_SNR in ('".$ids."')
		and cast(aps.date_change as date) between '".$fechaI."' and '".$fechaF."'
		/*and cast(aps.date_change as date) between '2018-01-02' and '2018-02-12'*/
		and aps.APPROVAL_STATUS_SNR<>'00000000-0000-0000-0000-000000000000'
		and IA.I_INST_SNR<>'00000000-0000-0000-0000-000000000000'
		and aps.TABLE_NR=492
		 
		order by Cl.name,U.lname,U.fname,I.name ";
	
	//echo $query."<br>";
		
	if($tipo == 1 || $tipo == 2){//excel
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=listadoAprobacionesInstituciones.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else if($tipo == 3){
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array((array_sum($tam)/2),150));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('LISTADO DE APROBACIONES DE INSTITUCIONES COMPARATIVO'));
		$pdf->Ln();
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(40,5,'Consorcio Dermatológico de México');
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
	
	$tamTabla = array_sum($tam) + 20;
	if( $tipo != 3){
		$tabla = '<table border="0">
			<tr>
				<td>
					<table>
						<tr>
							<td colspan="10" class="nombreReporte">LISTADO DE APROBACIONES DE INSTITUCIONES COMPARATIVO</td>
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
			$pdf->Cell($tam[$i]/2,8,utf8_encode($field['Name']),1,0,'C',1);
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
		for($j=0;$j<sqlsrv_num_fields($rs);$j++){
			if(is_object($registro[$j])){
				foreach ($registro[$j] as $key => $val) {
					if(strtolower($key) == 'date'){
						$registro[$j] = substr($val, 0, 10);
					}
				}
			}
			if($tipo != 3){
				if($tipo == 2){
					$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.utf8_encode($registro[$j]).'</td>';
				}else{
					if($tipo == 1){
						$tabla .= '<td style="border: 1px solid #000;white-space:nowrap;min-width:'.$tam[$j].'px;">'.utf8_encode($registro[$j]).'</td>';
					}else{
						$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.utf8_encode($registro[$j]).'</td>';
					}
				}
			}else{
				$pdf->Cell($tam[$j]/2,8,utf8_encode($registro[$j]),1,0,'L',$fill);
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