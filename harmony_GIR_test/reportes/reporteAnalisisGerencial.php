<?php
	/*** cobertura de medicos ***/
	include "../conexion.php";
			
	$ids = (substr($_POST['hdnIDS'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDS'], ",")) : $_POST['hdnIDS'] ;
	$tipo = $_POST['hdnTipoReporte'];
	$ciclo = $_POST['hdnCicloReporte'];
	
	$qMedicos = "DECLARE @CICLO as VARCHAR(36)
		DECLARE @DIAS_IN as DATE
		DECLARE @DIAS_FIN as DATE
		DECLARE @FECHA_ACT as DATE
		DECLARE @Dias_ciclo as FLOAT
		DECLARE @Cuota as FLOAT
		DECLARE @STATUS as VARCHAR(36)
		DECLARE @STATUS_INST as VARCHAR(36)
		
		SET @CICLO = '".$ciclo."'
		/*SET @CICLO = (Select CYCLE_SNR from CYCLES where rec_stat=0 and NAME = '2023-02') */
		SET @DIAS_IN = (Select START_DATE from CYCLES where CYCLE_SNR = @CICLO)
		SET @DIAS_FIN = (Select FINISH_DATE from CYCLES where CYCLE_SNR = @CICLO)
		SET @FECHA_ACT = (case when cast(getdate() as date)<= @DIAS_FIN then cast(getdate() as date) else @DIAS_FIN end)
		SET @Dias_ciclo = (Select cast(DAYS as int) from CYCLES where CYCLE_SNR = @CICLO)
		SET @Cuota = (Select cast(CONTACTS as int) from CYCLES where CYCLE_SNR = @CICLO)
		SET @STATUS = '19205DEC-F9F6-441A-9482-DB08D3394057'
		SET @STATUS_INST = 'C1141A15-E7AD-4099-A8D4-26C571298B21'
		
		;with Horas as (select ROW_NUMBER() over(partition by DAY_REPORT.user_snr, DAY_REPORT.DATE, DAY_REPORT_CODE.DAY_CODE_SNR order by DAY_REPORT.user_snr, DAY_REPORT.DATE, DAY_REPORT_CODE.DAY_CODE_SNR) as orden,
		DAY_REPORT.USER_SNR, DAY_REPORT.DATE, DAY_REPORT_CODE.DAY_CODE_SNR, DAY_REPORT.CREATOR_USER_SNR, DAY_REPORT_CODE.DAYREPORT_SNR, DAY_REPORT_CODE.DAYREPCOD_SNR/*,COUNT(*)tot*/
		from DAY_REPORT, DAY_REPORT_CODE
		where DAY_REPORT.DAYREPORT_SNR=DAY_REPORT_CODE.DAYREPORT_SNR
		and DAY_REPORT.rec_stat=0
		and DAY_REPORT_CODE.rec_stat=0
		and DAY_REPORT.DAYREPORT_SNR<>'00000000-0000-0000-0000-000000000000'
		and cast(DAY_REPORT.DATE as DATE) not in (select cast(c_date as DATE) from CYCLE_DETAILS where CYCLE_SNR = @CICLO and rec_stat=0 and c_day=0 and datepart(DW,c_date) not in (1,7))
		and DAY_REPORT.date between @DIAS_IN and @DIAS_FIN )
		/* order by DAY_REPORT.user_snr, DAY_REPORT.DATE desc, DAY_REPORT_CODE.DAY_CODE_SNR ) */
		
		,FECHAS (fecha, num_fecha) as (
		select  DATEADD(DAY, nbr - 1, @DIAS_IN) fecha, ROW_NUMBER() OVER (ORDER BY DATEADD(DAY, nbr - 1, @DIAS_IN)) num_fecha
		from    ( select ROW_NUMBER() OVER (ORDER BY c.object_id) as Nbr
				  from  sys.columns c) nbrs
		where   nbr - 1 <= DATEDIFF(DAY, @DIAS_IN, @FECHA_ACT)
		and datepart(DW,DATEADD(DAY, nbr - 1, @DIAS_IN)) not in (1,7)
		and DATEADD(DAY, nbr - 1, @DIAS_IN) not in (select c_date from CYCLE_DETAILS where c_date between @DIAS_IN and @FECHA_ACT and rec_stat=0 and c_day=0)
		)
		
		Select 
		LINEA.name as Linea,
		klr.REG_SNR,
		DM.lname + ' ' + DM.fname as RM,
		DM.user_nr as Ruta_Gte,
		MR.user_nr as Ruta,
		MR.lname + ' ' + MR.fname as SR,
		@DIAS_IN as Inicio_Ciclo,
		@DIAS_FIN as Fin_Ciclo,
		@Dias_ciclo as Dias_Ciclo,
		@Cuota as Cuota,
		(@Cuota * @Dias_ciclo) as Contactos,
		(select count(fecha) from FECHAS) as NDiasTransc,
		MR.tel1 as Cuota_Inst,
		(MR.tel1 * @Dias_ciclo) as Contactos_Inst,
		
		(Select count(distinct PLW.pwork_snr) from perslocwork PLW, person P, pers_srep_work PSW
		where PLW.pwork_snr <> '00000000-0000-0000-0000-000000000000'
		and PLW.rec_stat = 0
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and P.status_snr = @STATUS
		and PSW.user_snr = MR.user_snr
		and PLW.pers_snr = P.pers_snr
		and PSW.pwork_snr = PLW.pwork_snr 
		) as DR_NR, 
		
		(Select count(distinct VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and VP.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as One_Vis, 
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS 
		and VP.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as VisTot, 
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and VP.novis_snr = 'E3196F15-900A-4852-95B2-101A6EA2D748' /*FALLIDA*/
		) as Vis_Fallida,
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and VP.visit_code_snr in ('036ED0CF-35F0-4F1A-9DF7-0E782B1C3D1F') /*ACCION COMPLEMENTARIA*/
		) as VisAcc_Complem, 
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and VP.ESCORT_SNR <> '00000000-0000-0000-0000-000000000000' 
		) as Vis_Acomp, 
		
		(Select count(distinct I.inst_snr) from INST I, USER_TERRIT UT
		where I.inst_snr <> '00000000-0000-0000-0000-000000000000'
		and I.rec_stat = 0
		and UT.rec_stat = 0
		and I.status_snr = @STATUS_INST
		and UT.user_snr = MR.user_snr
		and I.inst_snr = UT.inst_snr
		and I.INST_TYPE = 2
		) as Inst_NR, 
		
		(Select count(distinct VI.inst_snr) from INST I, VISITINST VI
		where I.inst_snr <> '00000000-0000-0000-0000-000000000000'
		and I.rec_stat = 0
		and VI.rec_stat = 0
		and I.status_snr = @STATUS_INST
		and VI.user_snr = MR.user_snr
		and I.inst_snr = VI.inst_snr
		and I.INST_TYPE = 2
		and VI.visit_date between @DIAS_IN and @DIAS_FIN
		) as VisUni_Inst, 
		
		(Select count(VI.inst_snr) from INST I, VISITINST VI
		where I.inst_snr <> '00000000-0000-0000-0000-000000000000'
		and I.rec_stat = 0
		and VI.rec_stat = 0
		and I.status_snr = @STATUS_INST
		and VI.user_snr = MR.user_snr
		and I.inst_snr = VI.inst_snr
		and I.INST_TYPE = 2
		and VI.visit_date between @DIAS_IN and @DIAS_FIN
		) as Vis_Inst, 
		
		(Select count(distinct VP.visit_date) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.user_snr = MR.user_snr
		and VP.rec_stat = 0
		and P.status_snr = @STATUS 
		and DATEPART(DW,VP.visit_date) not in (1,7) /* No cuenta Sabado Y Domingo */
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		) as VP_Fechas,
		
		(Select count(distinct VI.visit_date) from INST I, VISITINST VI
		where I.inst_snr <> '00000000-0000-0000-0000-000000000000'
		and I.inst_snr = VI.inst_snr
		and VI.user_snr = MR.user_snr
		and I.rec_stat = 0
		and VI.rec_stat = 0
		and I.INST_TYPE = 2
		and I.status_snr = @STATUS_INST
		and DATEPART(DW,VI.visit_date) not in (1,7) /* No cuenta Sabado Y Domingo */
		and VI.visit_date between @DIAS_IN and @DIAS_FIN
		) as VI_Fechas,
		
		isnull((Select SUM(case when cast(DC.value as FLOAT) > 8 then 8 else cast(DC.value as FLOAT) end) / 8 
		from DAY_REPORT_CODE DC, day_report DR
		where DC.dayreport_snr = DR.dayreport_snr and DR.user_snr = MR.user_snr 
		and DR.DAYREPORT_SNR in (select DAYREPORT_SNR  from Horas where orden=1)
		and DATEPART(DW,DR.Date) not in (1,7) /* No cuenta Sabado Y Domingo */
		and DR.date between @DIAS_IN and @DIAS_FIN
		and DR.rec_stat = 0
		and DC.rec_Stat = 0
		),0) as OAct 
		
		
		from users DM, (select distinct reg_snr, kloc_snr, rec_stat from KLOC_REG) klr, users MR, company CIA, compline LINEA
		
		where klr.REG_SNR = DM.USER_SNR
		and klr.kloc_snr = MR.user_snr
		and klr.rec_stat=0
		and MR.rec_stat=0
		and DM.rec_stat=0
		and MR.Status in (1,2)
		and DM.Status in (1,2)
		and MR.User_type = 4
		and DM.User_type = 5
		and MR.cline_snr = LINEA.cline_snr
		and CIA.comp_snr = LINEA.comp_snr
		and CIA.rec_stat=0
		and LINEA.rec_stat=0
		and MR.user_snr in ('".$ids."') 
		
		Order by DM.user_nr,DM.lname,DM.fname,MR.user_nr,MR.lname,MR.fname,klr.reg_snr ";
	
	//echo $qMedicos."<br>";
		
	if($tipo == 1 || $tipo == 2){//excel
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=reporteAnalisisGerencial.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else if($tipo == 3){
		ob_start();
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array(1300,1550));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('Reporte Analisis Gerencial'));
		$pdf->Ln();
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(40,5,'Torrent');
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

	$tamTabla = 3100;
	$tabla = '';
	if( $tipo != 3){
			$tabla .= '<table border="0">
				<tr>
					<td>
						<table>
							<tr>
								<td colspan="10" class="nombreReporte">Reporte Analisis Gerencial</td>
							</tr>
							<tr>
								<td colspan="10" class="clienteReporte">Torrent</td>
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
		if($tipo != 2){
			//$tabla .= '<thead style="background-color: #A9BCF5;font-weight:bold;border: 1px solid #000;padding: 5px 5px 5px 5px;color:#000"><tr>';
			$tabla .= '<thead style="background-color: #11308E;font-weight:bold;border: 1px solid #000;padding: 5px 5px 5px 5px;color:#FFF"><tr>';
		}else{
			$tabla .= '<thead><tr>';
		}
	}else{
		$pdf->SetFillColor(25,72,213);
			$pdf->SetTextColor(0);
			$pdf->SetDrawColor(0);
			$pdf->SetLinewidth(1);
			$pdf->SetFont('','B');
	}
	
	if($tipo == 2){
		$estilorepre = '';
		$estilogte = '';
		$estilocabecera = '';
	}else{
		$estilocabecera = 'style="background-color: #11308E;font-weight:bold;border: 1px solid #FFF;padding: 5px 5px 5px 5px;color:#FFF"';
		$estilorepre = 'style="border: 1px solid #000;white-space:nowrap;"';
		$estilogte = 'style="background-color: #11308E;border: 1px solid #FFF;white-space:nowrap;color:#FFF"';
	}

	
	$i=1;
	//inicia var nacional
	$totalNumReps = 0;
	$diasCiclo = 0;
	$nDiasTransc = 0;
	$totalMeds = 0;
	$totalVisUni = 0;
	$totalVis = 0;
	$totalRevis = 0;
	$totalVisFallida = 0;
	$totalAccionComp = 0;
	$totalContacto = 0;
	$totalContAjus = 0;
	$totalAlcance = 0;
	$totalCobReal = 0;
	$totalCobAjus = 0;
	$nalDiasTrab = 0;
	$totalDiasTrab = 0;	
	$totalMedsAjus = 0;
	$totalVisAcomp = 0;
	$totalInst = 0;
	$totalVisUniInst = 0;
	$totalVisInst = 0;
	$totalRevisInst = 0;
	$totalContactoInst = 0;
	$totalAlcanceInst = 0;
	$totalCobRealInst = 0;
	$totalContAjusInst = 0;
	$totalCobAjusInst = 0;
	//$totalDiasNoTrab = 0;
	$totalsumOAct = 0;
	$totalOAct = 0;

	while($reg = sqlsrv_fetch_array($rsMedicos)){
		////suma nacional
		$totalNumReps += 1;
		$diasCiclo = $reg['Dias_Ciclo'];
		$nDiasTransc = $reg['NDiasTransc'];
		$totalMeds += $reg['DR_NR'];
		$totalVisUni += $reg['One_Vis'];
		$totalVis += $reg['VisTot'];
		$totalRevis += $reg['VisTot'] - $reg['One_Vis'];
		$totalVisFallida += $reg['Vis_Fallida'];
		$totalAccionComp += $reg['VisAcc_Complem'];
		$totalContacto += $reg['Contactos'];
		if ($reg['Contactos'] > 0 && $diasCiclo > 0){
			$totalContAjus += ($reg['Contactos'] / $diasCiclo) * ($diasCiclo - $reg['OAct']);
		}else{ 
			$totalContAjus += 0;
		}
		if ($totalVisUni > 0 && $totalMeds > 0 ){
			$totalAlcance = ($totalVisUni / $totalMeds) * 100;
		}else{ 
			$totalAlcance = 0;
		}
		if ($totalVis > 0 && $totalContacto > 0){
			$totalCobReal = ($totalVis / $totalContacto) * 100;
		}else{
			$totalCobReal = 0;
		}
		if ($totalVis > 0 && $totalContAjus > 0 ){
			$totalCobAjus = ($totalVis / $totalContAjus) * 100;	
		}else{
			$totalCobAjus = 0;
		}
		/*if ($reg['VP_Fechas'] >= $reg['VI_Fechas'] ){
			$nalDiasTrab += ($reg['VP_Fechas']);
		}else{ 
			$nalDiasTrab += $reg['VI_Fechas'];
		} */
		$nalDiasTrab += $nDiasTransc - $reg['OAct'];
		$totalDiasTrab = $nalDiasTrab / $totalNumReps;
		if ($totalVisUni > 0 && $totalMeds > 0){
			$totalMedsAjus = ($totalVisUni / (($totalMeds / $diasCiclo) * $totalDiasTrab)) * 100;
		}else{
			$totalMedsAjus = 0;
		}
		$totalVisAcomp += $reg['Vis_Acomp'];
		$totalInst += $reg['Inst_NR'];
		$totalVisUniInst += $reg['VisUni_Inst'];
		$totalVisInst += $reg['Vis_Inst'];
		$totalRevisInst += $reg['Vis_Inst'] - $reg['VisUni_Inst'];
		$totalContactoInst += $reg['Contactos_Inst'];
		if ($totalVisUniInst > 0 && $totalInst > 0 ){
			$totalAlcanceInst = ($totalVisUniInst / $totalInst) * 100;
		}else{ 
			$totalAlcanceInst = 0;
		}
		if ($totalVisInst > 0 && $totalContactoInst > 0){
			$totalCobRealInst = ($totalVisInst / $totalContactoInst) * 100;
		}else{
			$totalCobRealInst = 0;
		}
		if ($reg['Contactos_Inst'] > 0 && $diasCiclo > 0){
			$totalContAjusInst += ($reg['Contactos_Inst'] / $diasCiclo) * ($diasCiclo - $reg['OAct']);
		}else{ 
			$totalContAjusInst += 0;
		}
		if ($totalVisInst > 0 && $totalContAjusInst > 0 ){
			$totalCobAjusInst = ($totalVisInst / $totalContAjusInst) * 100;	
		}else{
			$totalCobAjusInst = 0;
		}
		//$totalDiasNoTrab = $nDiasTransc - $totalDiasTrab;
		$totalsumOAct += $reg['OAct'];
		$totalOAct = $totalsumOAct / $totalNumReps;
		
		if($i == 1){
			////imprimir encabezados
			if($tipo != 3){
				$tabla .= '<td '.$estilocabecera.' rowspan="2" width="100px">Linea</td>';
				$tabla .= '<td '.$estilocabecera.' rowspan="2" width="100px">Ruta</td>';
				$tabla .= '<td '.$estilocabecera.' rowspan="2" width="400px">Nombre</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="13" width="1300px" align="center">Medicos</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="7" width="700px" align="center">Farmacias</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="4" width="400px" align="center">Dias Trabajados</td></tr>';
				$tabla .= '<tr><td '.$estilocabecera.' width="100px" align="center">Medicos</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visita Unica</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Revisita</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visita Total</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visita Fallida</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Accion Complemen</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Contactos Totales</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Contactos Ajustado</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Alcance</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Cobertura Real</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Cobertura Ajustada</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Universo Ajustado</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Acomp</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Farmacias</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visita Unica</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Revisita</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visita Total</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Alcance</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Cobertura Real</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Cobertura Ajustada</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Dias Ciclo</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Dias Trascurridos</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Trabajados</td>';
				//$tabla .= '<td '.$estilocabecera.' width="100px" align="center">No Trabajados</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Dias TFT</td>';	
			}else{
				$pdf->Ln();	
				$pdf->Cell(50,10,'','LRT',0,'L',1);
				$pdf->Cell(50,10,'','LRT',0,'L',1);
				$pdf->Cell(200,10,'','LRT',0,'L',1);
				$pdf->Cell(650,10,'Medicos','LRT',0,'C',1);
				$pdf->Cell(350,10,'Farmacias','LRT',0,'C',1);
				$pdf->Cell(200,10,'Dias Trabajados','LRT',0,'C',1);
				$pdf->Ln();	
				$pdf->Cell(50,10,'Linea','LRB',0,'L',1);
				$pdf->Cell(50,10,'Ruta','LRB',0,'C',1);
				$pdf->Cell(200,10,'Nombre','LRB',0,'C',1);
				$pdf->Cell(50,10,'Medicos',1,0,'C',1);
				$pdf->Cell(50,10,'Visita Unica',1,0,'C',1);
				$pdf->Cell(50,10,'Revisita',1,0,'C',1);
				$pdf->Cell(50,10,'Visita Total',1,0,'C',1);
				$pdf->Cell(50,10,'Visita Fallida',1,0,'C',1);
				$pdf->Cell(50,10,'Accion Complemen',1,0,'C',1);
				$pdf->Cell(50,10,'Contactos Totales',1,0,'C',1);
				$pdf->Cell(50,10,'Contactos Ajustado',1,0,'C',1);
				$pdf->Cell(50,10,'Alcance',1,0,'C',1);
				$pdf->Cell(50,10,'% Cobertura Real',1,0,'C',1);
				$pdf->Cell(50,10,'% Cobertura Ajustada',1,0,'C',1);
				$pdf->Cell(50,10,'% Universo Ajustado',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Acomp',1,0,'C',1);
				$pdf->Cell(50,10,'Farmacias',1,0,'C',1);
				$pdf->Cell(50,10,'Visita Unica',1,0,'C',1);
				$pdf->Cell(50,10,'Revisita',1,0,'C',1);
				$pdf->Cell(50,10,'Visita Total',1,0,'C',1);
				$pdf->Cell(50,10,'Alcance',1,0,'C',1);
				$pdf->Cell(50,10,'% Cobertura Real',1,0,'C',1);
				$pdf->Cell(50,10,'% Cobertura Ajustada',1,0,'C',1);
				$pdf->Cell(50,10,'Dias Ciclo',1,0,'C',1);
				$pdf->Cell(50,10,'Dias Trascurridos',1,0,'C',1);
				$pdf->Cell(50,10,'Trabajados',1,0,'C',1);
				//$pdf->Cell(50,10,'No Trabajados',1,0,'C',1);
				$pdf->Cell(50,10,'Dias TFT',1,0,'C',1);
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
			
			////inicia var gerente
			$tempGerente = $reg['REG_SNR'];
			$gerente = $reg['REG_SNR'];
			$nombreGte = $reg['RM'];
			$rutaGte = $reg['Ruta_Gte'];
			$gteNumReps = 1;
			$gteMeds = $reg['DR_NR'];
			$gteVisUni = $reg['One_Vis'];
			$gteVis = $reg['VisTot'];
			$gteRevis = $reg['VisTot']-$reg['One_Vis'];
			$gteVisFallida = $reg['Vis_Fallida'];
			$gteAccionComp = $reg['VisAcc_Complem'];
			$gteContacto = $reg['Contactos'];
			if ($diasCiclo > 0 && $reg['Contactos'] > 0) {
				$gteContAjus = ($reg['Contactos'] / $diasCiclo) * ($diasCiclo - $reg['OAct']);
			}else{ 
				$gteContAjus = 0;
			}
			if ($gteVisUni > 0 && $gteMeds > 0 ){
				$gteAlcance = ($gteVisUni / $gteMeds) * 100;
			}else{ 
				$gteAlcance = 0;
			}
			if ($gteVis > 0 && $gteContacto > 0){
				$gteCobReal = ($gteVis / $gteContacto) * 100;
			}else{
				$gteCobReal = 0;
			}
			if ($gteVis > 0 && $gteContAjus > 0){
				$gteCobAjus = ($gteVis / $gteContAjus) * 100;
			}else{
				$gteCobAjus = 0;
			}
			/* if ($reg['VP_Fechas'] >= $reg['VI_Fechas'] ){
				$repreDiasTrab = $reg['VP_Fechas'];
			}else{ 
				$repreDiasTrab = $reg['VI_Fechas'];
			}
			$sumDiasTrab = $repreDiasTrab; */
			$sumDiasTrab = $nDiasTransc - $reg['OAct'];
			$gteDiasTrab = $sumDiasTrab; /*/ $gteNumReps;*/
			if ($gteVisUni > 0 && $gteMeds > 0){
				$gteMedsAjus = ($gteVisUni / (($gteMeds / $diasCiclo) * $gteDiasTrab)) * 100;
			}else{
				$gteMedsAjus = 0;
			}
			$gteVisAcomp = $reg['Vis_Acomp'];
			$gteInst = $reg['Inst_NR'];
			$gteVisUniInst = $reg['VisUni_Inst'];
			$gteVisInst = $reg['Vis_Inst'];
			$gteRevisInst = $reg['Vis_Inst'] - $reg['VisUni_Inst'];
			$gteContactoInst = $reg['Contactos_Inst'];
			if ($gteVisUniInst > 0 && $gteInst> 0 ) {
				$gteAlcanceInst = ($gteVisUniInst / $gteInst) * 100;
			}else{ 
				$gteAlcanceInst = 0;
			}
			if ($gteVisInst > 0 && $gteContactoInst > 0 ){
				$gteCobRealInst = ($gteVisInst / $gteContactoInst) * 100;
			}else{
				$gteCobRealInst = 0;
			}
			if ($gteContactoInst > 0 && $diasCiclo > 0 ) {
				$gteContAjusInst = ($reg['Contactos_Inst'] / $diasCiclo) * ($diasCiclo - $reg['OAct']);
			}else{ 
				$gteContAjusInst = 0;
			}
			if ($gteVisInst > 0 && $gteContAjusInst > 0){
				$gteCobAjusInst = ($gteVisInst / $gteContAjusInst) * 100;
			}else{
				$gteCobAjusInst = 0;
			}
			//$gteDiasNoTrab = $nDiasTransc - $gteDiasTrab;
			$sumOAct = $reg['OAct']; 
			$gteOAct = $reg['OAct']; /*/ $gteNumReps;*/
			
		}else{
			////sumas gerentes
			$gerente = $reg['REG_SNR'];
			if($tempGerente == $gerente){
				$gteNumReps += 1;
				$sumMeds = $reg['DR_NR'];
				$gteMeds += $sumMeds;
				$sumVisUni = $reg['One_Vis'];
				$gteVisUni += $sumVisUni;
				$sumVis = $reg['VisTot'];
				$gteVis += $sumVis;
				$sumRevis = $reg['VisTot'] - $reg['One_Vis'];
				$gteRevis += $sumRevis;
				$sumVisFallida = $reg['Vis_Fallida'];
				$gteVisFallida += $sumVisFallida;
				$sumAccionComp = $reg['VisAcc_Complem'];
				$gteAccionComp += $sumAccionComp;
				$sumContacto = $reg['Contactos'];
				$gteContacto += $sumContacto;
				if ($reg['Contactos'] > 0 && $diasCiclo > 0) {
					$sumContAjus = ($reg['Contactos'] / $diasCiclo) * ($diasCiclo - $reg['OAct']);
				}else{ 
					$sumContAjus = 0;
				}
				$gteContAjus += $sumContAjus;
				if ($gteVisUni > 0 && $gteMeds > 0){
					$gteAlcance = ($gteVisUni / $gteMeds) * 100;
				}else{ 
					$gteAlcance = 0;
				}
				if ($gteVis > 0 && $gteContacto > 0){
					$gteCobReal = ($gteVis / $gteContacto) * 100;
				}else{
					$gteCobReal = 0;
				}
				if ($gteVis > 0 && $gteContAjus > 0){
					$gteCobAjus = ($gteVis / $gteContAjus) * 100;
				}else{
					$gteCobAjus = 0;
				}
				/*if ($reg['VP_Fechas'] >= $reg['VI_Fechas']){
					$repreDiasTrab = $reg['VP_Fechas'];
				}else{ 
					$repreDiasTrab = $reg['VI_Fechas'];
				} 
				$sumDiasTrab += $repreDiasTrab; */
				$sumDiasTrab += $nDiasTransc - $reg['OAct'];
				$gteDiasTrab = $sumDiasTrab / $gteNumReps;
				if ($gteVisUni > 0 && $gteMeds > 0){
					$gteMedsAjus = ($gteVisUni / (($gteMeds / $diasCiclo) * $gteDiasTrab)) * 100;
				}else{
					$gteMedsAjus = 0;
				}
				$sumVisAcomp = $reg['Vis_Acomp'];
				$gteVisAcomp += $sumVisAcomp;
				$sumInst = $reg['Inst_NR'];
				$gteInst += $sumInst;
				$sumVisUniInst = $reg['VisUni_Inst'];
				$gteVisUniInst += $sumVisUniInst;
				$sumVisInst = $reg['Vis_Inst'];
				$gteVisInst += $sumVisInst;
				$sumRevisInst = $reg['Vis_Inst'] - $reg['VisUni_Inst'];
				$gteRevisInst += $sumRevisInst;
				$sumContactoInst = $reg['Contactos_Inst'];
				$gteContactoInst += $sumContactoInst;
				if ($gteVisUniInst > 0 && $gteInst> 0 ) {
					$gteAlcanceInst = ($gteVisUniInst / $gteInst) * 100;
				}else{ 
					$gteAlcanceInst = 0;
				}
				if ($gteVisInst > 0 && $gteContactoInst > 0 ){
					$gteCobRealInst = ($gteVisInst / $gteContactoInst) * 100;
				}else{
					$gteCobRealInst = 0;
				}
				if ($reg['Contactos_Inst'] > 0 && $diasCiclo > 0 ) {
					$sumContAjusInst = ($reg['Contactos_Inst'] / $diasCiclo) * ($diasCiclo - $reg['OAct']);
				}else{ 
					$sumContAjusInst = 0;
				}
				$gteContAjusInst += $sumContAjusInst;
				if ($gteVisInst > 0 && $gteContAjusInst > 0){
					$gteCobAjusInst = ($gteVisInst / $gteContAjusInst) * 100;
				}else{
					$gteCobAjusInst = 0;
				}
				//$gteDiasNoTrab = $nDiasTransc - $gteDiasTrab;
				$sumOAct += $reg['OAct'];
				$gteOAct = $sumOAct / $gteNumReps;
				
			}else{	
				////imprimir gerentes
				if($tipo != 3){				
					$tabla .= '<tr><td '.$estilogte.' width="100px"> </td>';
					$tabla .= '<td '.$estilogte.' width="100px">'.$rutaGte.'</td>';	
					$tabla .= '<td '.$estilogte.' width="400px">'.$nombreGte.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteMeds).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisUni).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevis.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVis).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallida.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionComp.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteContacto).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteContAjus, 1).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcance, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteCobReal, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteCobAjus, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteMedsAjus, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisAcomp.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteInst.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisUniInst.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevisInst.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisInst.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcanceInst, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteCobRealInst, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteCobAjusInst, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$diasCiclo.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$nDiasTransc.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteDiasTrab, 1).'</td>';
					//$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteDiasNoTrab, 1).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteOAct, 1).'</td>';
					$tabla .= '</tr>';
				}else{ 
					$pdf->Cell(50,10,'',1,0,'L',1);
					$pdf->Cell(50,10,$rutaGte,1,0,'L',1);
					$pdf->Cell(200,10,$nombreGte,1,0,'L',1);
					$pdf->Cell(50,10,number_format($gteMeds),1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteVisUni),1,0,'C',1);
					$pdf->Cell(50,10,$gteRevis,1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteVis),1,0,'C',1);
					$pdf->Cell(50,10,$gteVisFallida,1,0,'C',1);
					$pdf->Cell(50,10,$gteAccionComp,1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteContacto),1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteContAjus, 1),1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteAlcance, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gteCobReal, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gteCobAjus, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gteMedsAjus, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,$gteVisAcomp,1,0,'C',1);
					$pdf->Cell(50,10,$gteInst,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisUniInst,1,0,'C',1);
					$pdf->Cell(50,10,$gteRevisInst,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisInst,1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteAlcanceInst, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gteCobRealInst, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gteCobAjusInst, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,$diasCiclo,1,0,'C',1);
					$pdf->Cell(50,10,$nDiasTransc,1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteDiasTrab, 1),1,0,'C',1);
					//$pdf->Cell(50,10,number_format($gteDiasNoTrab, 1),1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteOAct, 1),1,1,'C',1);
				}
	
				////inicia var gerente
				$tempGerente = $reg['REG_SNR'];
				$nombreGte = $reg['RM'];
				$rutaGte = $reg['Ruta_Gte'];
				$gteNumReps = 1;
				$gteMeds = $reg['DR_NR'];
				$gteVisUni = $reg['One_Vis'];
				$gteVis = $reg['VisTot'];
				$gteRevis = $reg['VisTot']-$reg['One_Vis'];
				$gteVisFallida = $reg['Vis_Fallida'];
				$gteAccionComp = $reg['VisAcc_Complem'];
				$gteContacto = $reg['Contactos'];
				if ($diasCiclo > 0 && $reg['Contactos'] > 0) {
					$gteContAjus = ($reg['Contactos'] / $diasCiclo) * ($diasCiclo - $reg['OAct']);	
				}else{ 
					$gteContAjus = 0;
				}
				if ($gteMeds > 0 && $gteVisUni > 0) {
					$gteAlcance = ($gteVisUni / $gteMeds) * 100;
				}else{ 
					$gteAlcance = 0;
				}
				if ($gteVis > 0 && $gteContacto > 0){
					$gteCobReal = ($gteVis / $gteContacto) * 100;
				}else{
					$gteCobReal = 0;
				}
				if ($gteVis > 0 && $gteContAjus > 0){
					$gteCobAjus = ($gteVis / $gteContAjus) * 100;
				}else{
					$gteCobAjus = 0;
				}
				/* if ($reg['VP_Fechas'] >= $reg['VI_Fechas']){
					$repreDiasTrab = $reg['VP_Fechas'];
				}else{ 
					$repreDiasTrab = $reg['VI_Fechas'];
				}
				$sumDiasTrab = $repreDiasTrab; */
				$sumDiasTrab = $nDiasTransc - $reg['OAct'];
				$gteDiasTrab = $sumDiasTrab; /*/ $gteNumReps;*/
				if ($gteVisUni > 0 && $gteMeds > 0){
					$gteMedsAjus = ($gteVisUni / (($gteMeds / $diasCiclo) * $gteDiasTrab)) * 100;
				}else{
					$gteMedsAjus = 0;
				}
				$gteVisAcomp = $reg['Vis_Acomp'];
				$gteInst = $reg['Inst_NR'];
				$gteVisUniInst = $reg['VisUni_Inst'];
				$gteVisInst = $reg['Vis_Inst'];
				$gteRevisInst = $reg['Vis_Inst'] - $reg['VisUni_Inst'];
				$gteContactoInst = $reg['Contactos_Inst'];
				if ($gteVisUniInst > 0 && $gteInst> 0 ) {
					$gteAlcanceInst = ($gteVisUniInst / $gteInst) * 100;
				}else{ 
					$gteAlcanceInst = 0;
				}
				if ($gteVisInst > 0 && $gteContactoInst > 0 ){
					$gteCobRealInst = ($gteVisInst / $gteContactoInst) * 100;
				}else{
					$gteCobRealInst = 0;
				}
				if ($gteContactoInst > 0 && $diasCiclo > 0 ) {
					$gteContAjusInst = ($reg['Contactos_Inst'] / $diasCiclo) * ($diasCiclo - $reg['OAct']);
				}else{ 
					$gteContAjusInst = 0;
				}
				if ($gteVisInst > 0 && $gteContAjusInst > 0){
					$gteCobAjusInst = ($gteVisInst / $gteContAjusInst) * 100;
				}else{
					$gteCobAjusInst = 0;
				}
				//$gteDiasNoTrab = $nDiasTransc - $gteDiasTrab;
				$sumOAct = $reg['OAct']; 
				$gteOAct = $reg['OAct']; /*/ $gteNumReps;*/
			}
		}
		
		////formulas repres
		$tabla .= '<tr>';
		$Revis = $reg['VisTot'] - $reg['One_Vis'];
		if ($reg['Contactos'] > 0 && $diasCiclo > 0 ) {
			$ContAjus = ($reg['Contactos'] / $diasCiclo) * ($diasCiclo - $reg['OAct']);
		}else{ 
			$ContAjus = 0;
		}
		if ($reg['One_Vis'] > 0 && $reg['DR_NR'] > 0 ) {
			$Alcance = ($reg['One_Vis'] / $reg['DR_NR']) * 100;
		}else{ 
			$Alcance = 0;
		}
		if ($reg['VisTot'] > 0 && $reg['Contactos'] > 0 ){
			$CobReal = ($reg['VisTot'] / $reg['Contactos']) * 100;
		}else{
			$CobReal = 0;
		}
		if ($reg['VisTot'] > 0 && $ContAjus > 0){
			$CobAjus = ($reg['VisTot'] / $ContAjus) * 100;
		}else{
			$CobAjus = 0;
		}
		/* if ($reg['VP_Fechas'] >= $reg['VI_Fechas']){
			$DiasTrab = $reg['VP_Fechas'];
		}else{
			$DiasTrab = $reg['VI_Fechas'];
		} */
		$DiasTrab = $nDiasTransc - $reg['OAct'];
		if ($reg['One_Vis'] > 0 && $reg['DR_NR'] > 0 && $DiasTrab > 0){
			$MedsAjus = ($reg['One_Vis'] / (($reg['DR_NR'] / $diasCiclo) * $DiasTrab)) * 100;
		}else{
			$MedsAjus = 0;
		}
		$RevisInst = $reg['Vis_Inst'] - $reg['VisUni_Inst'];
		if ($reg['VisUni_Inst'] > 0 && $reg['Inst_NR'] > 0 ) {
			$AlcanceInst = ($reg['VisUni_Inst'] / $reg['Inst_NR']) * 100;
		}else{ 
			$AlcanceInst = 0;
		}
		if ($reg['Vis_Inst'] > 0 && $reg['Contactos_Inst'] > 0 ){
			$CobRealInst = ($reg['Vis_Inst'] / $reg['Contactos_Inst']) * 100;
		}else{
			$CobRealInst = 0;
		}
		if ($reg['Contactos_Inst'] > 0 && $diasCiclo > 0 ) {
			$ContAjusInst = ($reg['Contactos_Inst'] / $diasCiclo) * ($diasCiclo - $reg['OAct']);
		}else{ 
			$ContAjusInst = 0;
		}
		if ($reg['Vis_Inst'] > 0 && $ContAjusInst > 0){
			$CobAjusInst = ($reg['Vis_Inst'] / $ContAjusInst) * 100;
		}else{
			$CobAjusInst = 0;
		}
		//$DiasNoTrab = $nDiasTransc - $DiasTrab;
		$OAct = $reg['OAct'];
		
		////imprimir repres
		if($tipo != 3){
			$tabla .= '<td '.$estilorepre.' width="100px">'.$reg['Linea'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px">'.$reg['Ruta'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="400px">'.$reg['SR'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['DR_NR'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['One_Vis'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$Revis.'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisTot'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Fallida'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisAcc_Complem'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Contactos'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.number_format($ContAjus, 1).'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($Alcance, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($CobReal, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($CobAjus, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($MedsAjus, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Acomp'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Inst_NR'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisUni_Inst'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$RevisInst.'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Inst'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($AlcanceInst, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($CobRealInst, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($CobAjusInst, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$diasCiclo.'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$nDiasTransc.'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$DiasTrab.'</td>';
			//$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$DiasNoTrab.'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$OAct.'</td>';
			$tabla .= '</tr>';
		}else{
			$pdf->Cell(50,10,$reg['Linea'],1,0,'L',0);
			$pdf->Cell(50,10,$reg['Ruta'],1,0,'L',0);
			$pdf->Cell(200,10,$reg['SR'],1,0,'L',0);
			$pdf->Cell(50,10,$reg['DR_NR'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['One_Vis'],1,0,'C',0);
			$pdf->Cell(50,10,$Revis,1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisTot'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Fallida'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisAcc_Complem'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Contactos'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($ContAjus, 1),1,0,'C',0);
			$pdf->Cell(50,10,number_format($Alcance, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($CobReal, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($CobAjus, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($MedsAjus, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,$reg['Vis_Acomp'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Inst_NR'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisUni_Inst'],1,0,'C',0);
			$pdf->Cell(50,10,$RevisInst,1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Inst'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($AlcanceInst, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($CobRealInst, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($CobAjusInst, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,$diasCiclo,1,0,'C',0);
			$pdf->Cell(50,10,$nDiasTransc,1,0,'C',0);
			$pdf->Cell(50,10,$DiasTrab,1,0,'C',0);
			//$pdf->Cell(50,10,$DiasNoTrab,1,0,'C',0);
			$pdf->Cell(50,10,$OAct,1,1,'C',0);
		}
		$i++;
	}
	
	////imprimir ultimo gerente
	if($tipo != 3){
		$tabla .= '<tr><td '.$estilogte.' width="100px"> </td>';
		$tabla .= '<td '.$estilogte.' width="100px">'.$rutaGte.'</td>';
		$tabla .= '<td '.$estilogte.' width="400px">'.$nombreGte.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteMeds).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisUni).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevis.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVis).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallida.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionComp.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteContacto).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteContAjus, 1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcance, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteCobReal, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteCobAjus, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteMedsAjus, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisAcomp.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteInst.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisUniInst.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevisInst.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisInst.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcanceInst, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteCobRealInst, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteCobAjusInst, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$diasCiclo.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$nDiasTransc.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteDiasTrab, 1).'</td>';
		//$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteDiasNoTrab, 1).'</td>';	
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteOAct, 1).'</td>';
		$tabla .= '</tr>';
	}else{ 
		$pdf->Cell(50,10,'',1,0,'L',1);
		$pdf->Cell(50,10,$rutaGte,1,0,'L',1);
		$pdf->Cell(200,10,$nombreGte,1,0,'L',1);
		$pdf->Cell(50,10,number_format($gteMeds),1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteVisUni),1,0,'C',1);
		$pdf->Cell(50,10,$gteRevis,1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteVis),1,0,'C',1);
		$pdf->Cell(50,10,$gteVisFallida,1,0,'C',1);
		$pdf->Cell(50,10,$gteAccionComp,1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteContacto),1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteContAjus, 1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteAlcance, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gteCobReal, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gteCobAjus, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gteMedsAjus, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,$gteVisAcomp,1,0,'C',1);
		$pdf->Cell(50,10,$gteInst,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisUniInst,1,0,'C',1);
		$pdf->Cell(50,10,$gteRevisInst,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisInst,1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteAlcanceInst, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gteCobRealInst, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gteCobAjusInst, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,$diasCiclo,1,0,'C',1);
		$pdf->Cell(50,10,$nDiasTransc,1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteDiasTrab, 1),1,0,'C',1);
		//$pdf->Cell(50,10,number_format($gteDiasNoTrab, 1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteOAct, 1),1,1,'C',1);
	}	

	////imprimir nacional
	if($tipo != 3){
		$tabla .= '<tr><td '.$estilogte.' width="100px"></td>';
		$tabla .= '<td '.$estilogte.' width="100px"></td>';
		$tabla .= '<td '.$estilogte.' width="400px">Total General</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalMeds).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisUni).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalRevis).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVis).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFallida).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalAccionComp).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalContacto).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalContAjus, 1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalAlcance, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalCobReal, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalCobAjus, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalMedsAjus, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisAcomp).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalInst).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisUniInst).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalRevisInst).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisInst).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalAlcanceInst, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalCobRealInst, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalCobAjusInst, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($diasCiclo).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($nDiasTransc).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalDiasTrab, 1).'</td>';
		//$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalDiasNoTrab, 1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalOAct, 1).'</td>';
		$tabla .= '</tr>';
	}else{ 
		$pdf->Cell(50,10,'',1,0,'L',1);
		$pdf->Cell(50,10,'',1,0,'L',1);
		$pdf->Cell(200,10,'Total General',1,0,'L',1);
		$pdf->Cell(50,10,number_format($totalMeds),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisUni),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalRevis),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVis),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisFallida),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAccionComp),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalContacto),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalContAjus, 1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAlcance, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalCobReal, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalCobAjus, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalMedsAjus, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalVisAcomp),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalInst),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisUniInst),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalRevisInst),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisInst),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAlcanceInst, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalCobRealInst, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalCobAjusInst, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($diasCiclo),1,0,'C',1);
		$pdf->Cell(50,10,number_format($nDiasTransc),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalDiasTrab, 1),1,0,'C',1);
		//$pdf->Cell(50,10,number_format($totalDiasNoTrab, 1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalOAct, 1),1,1,'C',1);
	}	
	
	if($tipo != 3){
		$tabla .= '</tbody> ';
		if($tipo == 2){	
			$tabla .= '<tfoot>';
		}else{
			if($tipo == 1){
				//$tabla .= '<tfoot style="background-color: #A9BCF5;font-weight:bold;border: 1px solid #000;padding: 5px 5px 5px 5px;color:#000;">';
				$tabla .= '<tfoot style="background-color: #11308E;font-weight:bold;border: 1px solid #FFF;padding: 5px 5px 5px 5px;color:#FFF;">';
			}else{
				$tabla .= '<tfoot>';
			}
		}
		$numRegs = $i - 1;
				$tabla .= '<tr>
								<td colspan="10">Total regs: '.$numRegs.'</td>
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