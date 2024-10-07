<?php
	/*** listado de farmacias ***/
	include "../conexion.php";
	
	$tamanio = array(1,4,4,4,2,2,2,2,3,4,6,2,2,2,4,4,2,3,3,2,2,3,3,2,2,2,2,2,2,2,2,2,2,1,4,2,4,2,4,3,2,2,2,2,2,2,5,2,2,2,2,1,2,2,2,2,2,2,1,2,2,2,1,2,3,2,2,2,2,2,3,2,2,2,3,2,2,2,1,2,1,2,1,2,1,2,1,2,1,2,2,2);
	$tam = array(100,350,350,200,350,250,250,150,150,150, 100,150,150,200,400,150,150,150,150,450, 450,550,550,350,100,350,100);
	$estatus = $_POST['hdnEstatusListado'];
	$ids = (substr($_POST['hdnIDSListado'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDSListado'], ",")) : $_POST['hdnIDSListado'] ;
	$tipo = $_POST['hdnTipoListado'];
	$fechaI = $_POST['hdnFechaIListado'];
	$fechaF = $_POST['hdnFechaFListado'];
	
	$query = "Select
		/*aps.APPROVAL_STATUS_SNR,*/
		cl.name as Linea,
		upper(U.lname)+' '+upper(U.fname) as Representante,
		'{'+CAST(I.inst_snr AS VARCHAR(36))+'}' as 'Cod Inst',
		upper(ST.name) as Sub_Tipo,
		IMS.name as Brick,
		Dst.name as Ciudad,
		State.name as Estado,
		estatus.name as Status,
		isnull(IA.creation_timestamp,' ') as 'Fecha Alta', 
		isnull(IA.changed_timestamp,' ') as 'Fecha Mod Baja',
		IA.I_movement_type as 'Tipo mov',
		(case when aps.APPROVED_STATUS in (1,4) then 'Pendiente' when aps.APPROVED_STATUS=2 then 'Aprobado' when aps.APPROVED_STATUS=3 then 'Rechazado' when aps.APPROVED_STATUS=4 then 'Pendiente' end) as 'Estatus mov',
		isnull(aps.date_change,' ') as 'Fecha solicitud',
		isnull(usa.LNAME+' '+usa.FNAME,' ') as 'Persona aprueba',
		isnull(IA.I_DEL_REASON,' ') as 'Motivo Rechazo',
		(case when I.INST_TYPE=1 then 'HOSPITAL' when I.INST_TYPE=2 then 'FARMACIA' when I.INST_TYPE=3 then 'CONSULTORIO' when I.INST_TYPE=5 then 'OTRO' end) as 'Tipo Inst',
		upper(T.name) as Tipo,
		(case when IA.I_INST_TYPE=1 then 'HOSPITAL' when IA.I_INST_TYPE=2 then 'FARMACIA' when IA.I_INST_TYPE=3 then 'CONSULTORIO' when IA.I_INST_TYPE=5 then 'OTRO' end) as 'Tipo Inst Nvo',
		upper(Ta.name) as 'Tipo Nvo',
		upper(I.name) as Nombre,
		upper(IA.I_name) as 'Nombre Nvo',
		upper(I.street1) as Direccion,
		upper(IA.I_street1) as 'Direccion Nva',
		City.name as Colonia,
		City.zip as CPostal,
		Citya.name as Colonia_Nvo,
		Citya.zip as CPostal_Nvo
				
		/*upper(I.branch) as Sucursal,
		categoria.name as Categ,
		I.Tel1 as Tel1,
		I.Tel2 as Tel2,
		I.email as email,*/
		
		/*FARMACIAS*/
		/* VA.name as Visit_Acomp */
		/*PERFIL1.name as Nivel_de_Atencion,
		PERFIL2.name as Tipo_de_Receta,
		PERFIL3.name as Surtido_de_Receta,
		PERFIL4.name as Tipo_de_cuadro_basico,
		PERFIL5.name as Productos_AW_en_CB,
		PERFIL6.name as No_rx_Semanales_Flonorm,
		PERFIL7.name as No_rx_Semanales_Zirfos,
		isnull(PERFIL8.name,' ') as Ubicacion,
		isnull(PERFIL9.name,' ') as Numero_de_Empleados,
		isnull(PERFIL10.name,' ') as Numero_de_Turnos,
		isnull(PERFIL11.name,' ') as Existencia_Flonorm_28,
		isnull(PERFIL12.name,' ') as Existencia_Flonorm_12,
		isnull(PERFIL13.name,' ') as Existencia_Flonorm_susp,
		isnull(PERFIL14.name,' ') as Existencia_Zirfos,
		isnull(PERFIL15.name,' ') as Mayorista_Principal,
		PERFIL16.name as Mayorista_Secundario,
		PERFIL17.name as Mayorista_3ra_opcion*/
		/*HOSPITALES*/
		/*isnull(PERFIL1.name,' ') as Nivel_de_Atencion,
		isnull(PERFIL2.name,' ') as Tipo_de_Receta,
		isnull(PERFIL3.name,' ') as Surtido_de_Receta,
		isnull(PERFIL4.name,' ') as Tipo_de_cuadro_basico,
		isnull(PERFIL5.name,' ') as Productos_AW_en_CB,
		isnull(PERFIL6.name,' ') as No_rx_Semanales_Flonorm,
		isnull(PERFIL7.name,' ') as No_rx_Semanales_Zirfos   ,
		PERFIL8.name as Ubicacion,
		PERFIL9.name as Numero_de_Empleados,
		PERFIL10.name as Valor_para_el_Representante,
		PERFIL11.name as Existencia_Flonorm_28,
		PERFIL12.name as Existencia_Flonorm_12,
		PERFIL13.name as Existencia_Flonorm_susp,
		PERFIL14.name as Existencia_Zirfos,
		PERFIL15.name as Mayorista_Principal,
		PERFIL16.name as Mayorista_Secundario,
		PERFIL17.name as Mayorista_3ra_opcion   
		,isnull(I.latitude,' ') as latitude
		,isnull(I.longitude,' ') as longitude */
		 
		
		/*,upper(STa.name) as Sub_Tipo_Nvo
		,upper(IA.I_branch) as Sucursal_Nva
		,estatusa.name as Status_Nvo
		,categoriaa.name as Categ_Nva
		,IMSa.name as Brick_Nvo
		,Dsta.name as Ciudad_Nvo
		,Statea.name as Estado_Nvo
		,IA.I_Tel1 as Tel1_Nvo
		,IA.I_Tel2 as Tel2_Nvo
		,IA.I_email as email_Nvo
		,isnull(IA.I_latitude,' ') as latitude_Nvo
		,isnull(IA.I_longitude,' ') as longitude_Nvo*/
		/*FARMACIAS*/
		/*,isnull(PERFIL8a.name,' ') as Ubicacion_Nvo
		,isnull(PERFIL9a.name,' ') as Numero_de_Empleados_Nvo
		,isnull(PERFIL10a.name,' ') as Numero_de_Turnos_Nvo
		,isnull(PERFIL11a.name,' ') as Existencia_Flonorm_28_Nvo
		,isnull(PERFIL12a.name,' ') as Existencia_Flonorm_12_Nvo
		,isnull(PERFIL13a.name,' ') as Existencia_Flonorm_susp_Nvo
		,isnull(PERFIL14a.name,' ') as Existencia_Zirfos_Nvo
		,isnull(PERFIL15a.name,' ') as Mayorista_Principal_Nvo*/
		/*HOSPITALES*/
		/*,isnull(PERFIL1a.name,' ') as Nivel_de_Atencion_Nvo
		,isnull(PERFIL2a.name,' ') as Tipo_de_Receta_Nvo
		,isnull(PERFIL3a.name,' ') as Surtido_de_Receta_Nvo
		,isnull(PERFIL4a.name,' ') as Tipo_de_cuadro_basico_Nvo
		,isnull(PERFIL5a.name,' ') as Productos_AW_en_CB_Nvo
		,isnull(PERFIL6a.name,' ') as No_rx_Semanales_Flonorm_Nvo
		,isnull(PERFIL7a.name,' ') as No_rx_Semanales_Zirfos_Nvo*/
		
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
		left outer join codelist as ST on I.type_snr = ST.clist_snr
		left outer join clist_categ as T on ST.clcat_snr = T.clcat_snr
		left outer join codelist estatus on I.status_snr=estatus.clist_snr
		left outer join codelist categoria on I.category_snr=categoria.clist_snr
		/*left outer join vinst on Vinst.inst_snr = I.inst_snr
		left outer join codelist VA on VP.pratnja_user_snr=VA.clist_snr*/
		left outer join inst_profile PER on I.inst_snr = PER.inst_snr
		left outer join inst_profile_ud PERFIL on PER.instprofile_snr = PERFIL.instprofile_snr
		/*FARMACIAS*/
		/*left outer join codelist PERFIL1 on PERFIL.nivel_de_atencion = PERFIL1.clist_snr
		left outer join codelist PERFIL2 on PERFIL.tipo_de_receta = PERFIL2.clist_snr
		left outer join codelist PERFIL3 on PERFIL.surtido_de_receta = PERFIL3.clist_snr
		left outer join codelist PERFIL4 on PERFIL.tipo_de_cuadro_basico = PERFIL4.clist_snr
		left outer join codelist PERFIL5 on PERFIL.productos_aw_en_cb = PERFIL5.clist_snr
		left outer join codelist PERFIL6 on PERFIL.no_rx_semanales_flonorm = PERFIL6.clist_snr
		left outer join codelist PERFIL7 on PERFIL.no_rx_semanales_zirfos = PERFIL7.clist_snr   */
		left outer join codelist PERFIL8 on PERFIL.ubicacion = PERFIL8.clist_snr and Perfil8.rec_stat=0 and Perfil8.status=1
		left outer join codelist PERFIL9 on PERFIL.numero_de_empleados = PERFIL9.clist_snr
		left outer join codelist PERFIL10 on PERFIL.Numero_De_Turnos = PERFIL10.clist_snr
		left outer join codelist PERFIL11 on PERFIL.existencia_flonorm_28 = PERFIL11.clist_snr
		left outer join codelist PERFIL12 on PERFIL.existencia_flonorm_12 = PERFIL12.clist_snr
		left outer join codelist PERFIL13 on PERFIL.existencia_flonorm_susp = PERFIL13.clist_snr
		left outer join codelist PERFIL14 on PERFIL.existencia_zirfos = PERFIL14.clist_snr
		left outer join codelist PERFIL15 on PERFIL.mayorista_principal = PERFIL15.clist_snr
		/*left outer join codelist PERFIL16 on PERFIL.mayorista_secuendario = PERFIL16.clist_snr*/
		/*left outer join codelist PERFIL17 on PERFIL.mayorista_3ra_opcion = PERFIL17.clist_snr*/
		/* HOSPITALES*/
		left outer join codelist PERFIL1 on PERFIL.nivel_de_atencion = PERFIL1.clist_snr
		left outer join codelist PERFIL2 on PERFIL.tipo_de_receta = PERFIL2.clist_snr
		left outer join codelist PERFIL3 on PERFIL.surtido_de_receta = PERFIL3.clist_snr
		left outer join codelist PERFIL4 on PERFIL.tipo_de_cuadro_basico = PERFIL4.clist_snr
		left outer join codelist PERFIL5 on PERFIL.productos_aw_en_cb = PERFIL5.clist_snr
		left outer join codelist PERFIL6 on PERFIL.no_rx_semanales_flonorm = PERFIL6.clist_snr
		left outer join codelist PERFIL7 on PERFIL.no_rx_semanales_zirfos = PERFIL7.clist_snr
		/*  left outer join codelist PERFIL8 on PERFIL.ubicacion = PERFIL8.clist_snr
		left outer join codelist PERFIL9 on PERFIL.numero_de_empleados = PERFIL9.clist_snr
		left outer join codelist PERFIL10 on PERFIL.valor_para_el_representante = PERFIL10.clist_snr
		left outer join codelist PERFIL11 on PERFIL.existencia_flonorm_28 = PERFIL11.clist_snr
		left outer join codelist PERFIL12 on PERFIL.existencia_flonorm_12 = PERFIL12.clist_snr
		left outer join codelist PERFIL13 on PERFIL.existencia_flonorm_susp = PERFIL13.clist_snr
		left outer join codelist PERFIL14 on PERFIL.existencia_zirfos = PERFIL14.clist_snr
		left outer join codelist PERFIL15 on PERFIL.mayorista_principal = PERFIL15.clist_snr
		left outer join codelist PERFIL16 on PERFIL.mayorista_secuendario = PERFIL16.clist_snr
		left outer join codelist PERFIL17 on PERFIL.mayorista_3ra_opcion = PERFIL17.clist_snr  */
		 
		left outer join Users usa on usa.USER_SNR = aps.approved_user_snr and usa.REC_STAT=0
		left outer join City Citya on Citya.city_snr = IA.I_city_snr
		left outer join District as Dsta on citya.distr_snr = Dsta.distr_snr
		left outer join State Statea on Dsta.state_snr = Statea.state_snr
		left outer join Brick as IMSa on IMSa.brick_snr = Citya.brick_snr
		left outer join codelist as STa on IA.I_type_snr = STa.clist_snr
		left outer join clist_categ as Ta on STa.clcat_snr = Ta.clcat_snr
		left outer join codelist estatusa on IA.I_status_snr=estatusa.clist_snr
		left outer join codelist categoriaa on IA.I_category_snr=categoriaa.clist_snr
		left outer join inst_profile PERa on IA.I_inst_snr = PERa.inst_snr
		left outer join inst_profile_ud PERFILa on PERa.instprofile_snr = PERFILa.instprofile_snr
		/* FARMACIAS */
		left outer join codelist PERFIL8a on PERFILa.ubicacion = PERFIL8a.clist_snr and Perfil8a.rec_stat=0 and Perfil8a.status=1
		left outer join codelist PERFIL9a on PERFILa.numero_de_empleados = PERFIL9a.clist_snr
		left outer join codelist PERFIL10a on PERFILa.Numero_De_Turnos = PERFIL10a.clist_snr
		left outer join codelist PERFIL11a on PERFILa.existencia_flonorm_28 = PERFIL11a.clist_snr
		left outer join codelist PERFIL12a on PERFILa.existencia_flonorm_12 = PERFIL12a.clist_snr
		left outer join codelist PERFIL13a on PERFILa.existencia_flonorm_susp = PERFIL13a.clist_snr
		left outer join codelist PERFIL14a on PERFILa.existencia_zirfos = PERFIL14a.clist_snr
		left outer join codelist PERFIL15a on PERFILa.mayorista_principal = PERFIL15a.clist_snr
		/* HOSPITALES */
		left outer join codelist PERFIL1a on PERFILa.nivel_de_atencion = PERFIL1a.clist_snr
		left outer join codelist PERFIL2a on PERFILa.tipo_de_receta = PERFIL2a.clist_snr
		left outer join codelist PERFIL3a on PERFILa.surtido_de_receta = PERFIL3a.clist_snr
		left outer join codelist PERFIL4a on PERFILa.tipo_de_cuadro_basico = PERFIL4a.clist_snr
		left outer join codelist PERFIL5a on PERFILa.productos_aw_en_cb = PERFIL5a.clist_snr
		left outer join codelist PERFIL6a on PERFILa.no_rx_semanales_flonorm = PERFIL6a.clist_snr
		left outer join codelist PERFIL7a on PERFILa.no_rx_semanales_zirfos = PERFIL7a.clist_snr
		 
		 
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
		header("Content-Disposition: filename=listadoAprobacionesInst.xls");
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
		$pdf->Cell(40,5,'ALFASIGMA');
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

<style>
	#divReportesRepo{
		overflow:scroll;
		height:440px;
		width:1330px;
	}
</style>