<?php
	/*** cobertura de medicos ***/
	include "../conexion.php";
			
	//$estatus = $_POST['hdnEstatus'];
	$ids = (substr($_POST['hdnIDS'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDS'], ",")) : $_POST['hdnIDS'] ;
	$tipo = $_POST['hdnTipoReporte'];
	$fechaI = $_POST['hdnFechaI'];
	$fechaF = $_POST['hdnFechaF'];
	
	$qMedicos = "DECLARE @STATUS as VARCHAR(36)
		DECLARE @DIAS_IN as DATE
		DECLARE @DIAS_FIN as DATE
		 
		SET @STATUS = 'B426FB78-8498-4185-882D-E0DC381460E8'
		SET @DIAS_IN='".$fechaI."'
		SET @DIAS_FIN='".$fechaF."'
		/*
		SET @DIAS_IN='2019-05-13'
		SET @DIAS_FIN='2019-06-09'
		*/
		 
		;with Horas as (select ROW_NUMBER() over(partition by DAY_REPORT.USER_SNR, DAY_REPORT.DATE, DAY_REPORT_CODE.DAY_CODE_SNR order by DAY_REPORT.USER_SNR, DAY_REPORT.DATE, DAY_REPORT_CODE.DAY_CODE_SNR) as orden,
		DAY_REPORT.USER_SNR, DAY_REPORT.DATE, DAY_REPORT_CODE.DAY_CODE_SNR, DAY_REPORT.CREATOR_USER_SNR, DAY_REPORT_CODE.DAYREPORT_SNR, DAY_REPORT_CODE.DAYREPCOD_SNR/*,COUNT(*)tot*/
		from DAY_REPORT, DAY_REPORT_CODE
		where DAY_REPORT.DAYREPORT_SNR=DAY_REPORT_CODE.DAYREPORT_SNR
		and DAY_REPORT.REC_STAT=0
		and DAY_REPORT_CODE.REC_STAT=0
		and DAY_REPORT.DAYREPORT_SNR<>'00000000-0000-0000-0000-000000000000'
		and DAY_REPORT.date between @DIAS_IN and @DIAS_FIN )
		/* order by DAY_REPORT.USER_SNR, DAY_REPORT.DATE desc, DAY_REPORT_CODE.DAY_CODE_SNR ) */
		 
		select /*DISTINCT*/
		LINEA.name as LINEA,
		klr.REG_SNR,
		DM.lname + ' ' + DM.fname as RM,
		MR.lname + ' ' + MR.fname as SR,
		MR.TEL1 as CUOTA,
		(Select TOP 1 START_DATE from CYCLES where START_DATE <= @DIAS_IN and FINISH_DATE >= @DIAS_IN) as Inicio_Ciclo,
		(Select TOP 1 FINISH_DATE from CYCLES where START_DATE <= @DIAS_IN and FINISH_DATE >= @DIAS_IN) as Fin_Ciclo,
		 
		(CASE when @DIAS_FIN>= Dateadd(d, 7-1,@DIAS_IN) then
		(Select count(*) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and Dateadd(d, 7-1,@DIAS_IN)
		and P.status_snr = @STATUS )
		ELSE
		(Select count(*) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS )
		END) as VP1,
		 
		(CASE when @DIAS_FIN>= Dateadd(d, 7-1,@DIAS_IN) then
		(DATEDIFF(DD, @DIAS_IN, Dateadd(d, 7-1,@DIAS_IN)) ) - 1 
		ELSE 
		DATEDIFF(DD, @DIAS_IN, @DIAS_FIN) 
		END) as Dias_VP1,
		 
		/*DESCUENTO DE DIAS*/
		(CASE when @DIAS_FIN>= Dateadd(d, 7-1,@DIAS_IN) then
		ISNULL((Select SUM(CASE when CAST(DC.value as FLOAT) > 8 then 8 else CAST(DC.value as FLOAT) END) / 8 
		from DAY_REPORT_CODE DC, day_report DR
		where DC.dayreport_snr = DR.dayreport_snr and DR.user_snr = MR.user_snr 
		and DR.DAYREPORT_SNR in (select DAYREPORT_SNR  from Horas where orden=1)
		and DATEPART(DW,DR.Date) not in (1,7) /* No cuenta Sabado Y Domingo */
		and DR.date between @DIAS_IN and Dateadd(d, 7-1,@DIAS_IN)
		and DR.rec_stat = 0
		and DC.rec_Stat = 0
		),0)
		ELSE
		ISNULL((Select SUM(CASE when CAST(DC.value as FLOAT) > 8 then 8 else CAST(DC.value as FLOAT) END) / 8 
		from DAY_REPORT_CODE DC, day_report DR
		where DC.dayreport_snr = DR.dayreport_snr and DR.user_snr = MR.user_snr 
		and DR.DAYREPORT_SNR in (select DAYREPORT_SNR  from Horas where orden=1)
		and DATEPART(DW,DR.Date) not in (1,7) /* No cuenta Sabado Y Domingo */
		and DR.date between @DIAS_IN and @DIAS_FIN
		and DR.rec_stat = 0
		and DC.rec_Stat = 0
		),0)
		END) as OAct1,
		 
		(CASE when @DIAS_FIN>= Dateadd(d, 14-1,@DIAS_IN) then
		(Select count(*) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between Dateadd(d,7,@DIAS_IN) and Dateadd(d, 14-1,@DIAS_IN)
		and P.status_snr = @STATUS )
		ELSE
		(Select count(*) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between Dateadd(d,7,@DIAS_IN) and @DIAS_FIN
		and P.status_snr = @STATUS )
		END) as VP2,
		 
		(CASE when @DIAS_FIN>= Dateadd(d, 14-1,@DIAS_IN) then
		(DATEDIFF(DD, Dateadd(d, 7,@DIAS_IN), Dateadd(d, 14-1,@DIAS_IN)) ) - 1 
		ELSE 
		DATEDIFF(DD, Dateadd(d, 7,@DIAS_IN), @DIAS_FIN)
		END) as Dias_VP2,
		 
		/*DESCUENTO DE DIAS*/
		(CASE when @DIAS_FIN>= Dateadd(d, 7-1,@DIAS_IN) then
		ISNULL((Select SUM(CASE when CAST(DC.value as FLOAT) > 8 then 8 else CAST(DC.value as FLOAT) END) / 8 
		from DAY_REPORT_CODE DC, day_report DR
		where DC.dayreport_snr = DR.dayreport_snr and DR.user_snr = MR.user_snr 
		and DR.DAYREPORT_SNR in (select DAYREPORT_SNR  from Horas where orden=1)
		and DATEPART(DW,DR.Date) not in (1,7) /* No cuenta Sabado Y Domingo */
		and DR.date between Dateadd(d,7,@DIAS_IN) and Dateadd(d, 14-1,@DIAS_IN)
		and DR.rec_stat = 0
		and DC.rec_Stat = 0
		),0)
		ELSE
		ISNULL((Select SUM(CASE when CAST(DC.value as FLOAT) > 8 then 8 else CAST(DC.value as FLOAT) END) / 8 
		from DAY_REPORT_CODE DC, day_report DR
		where DC.dayreport_snr = DR.dayreport_snr and DR.user_snr = MR.user_snr 
		and DR.DAYREPORT_SNR in (select DAYREPORT_SNR  from Horas where orden=1)
		and DATEPART(DW,DR.Date) not in (1,7) /* No cuenta Sabado Y Domingo */
		and DR.date between Dateadd(d,7,@DIAS_IN) and @DIAS_FIN
		and DR.rec_stat = 0
		and DC.rec_Stat = 0
		),0)
		END) as OAct2,
		 
		(CASE when @DIAS_FIN>= Dateadd(d, 21-1,@DIAS_IN) then
		(Select count(*) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between Dateadd(d,14,@DIAS_IN) and Dateadd(d, 21-1,@DIAS_IN)
		and P.status_snr = @STATUS )
		ELSE
		(Select count(*) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between Dateadd(d,14,@DIAS_IN) and @DIAS_FIN
		and P.status_snr = @STATUS )
		END) as VP3,
		 
		(CASE when @DIAS_FIN>= Dateadd(d, 21-1,@DIAS_IN) then
		(DATEDIFF(DD, Dateadd(d, 14,@DIAS_IN), Dateadd(d, 21-1,@DIAS_IN)) ) - 1 
		ELSE 
		DATEDIFF(DD, Dateadd(d, 14,@DIAS_IN), @DIAS_FIN)
		END) as Dias_VP3,
		 
		/*DESCUENTO DE DIAS*/
		(CASE when @DIAS_FIN>= Dateadd(d, 7-1,@DIAS_IN) then
		ISNULL((Select SUM(CASE when CAST(DC.value as FLOAT) > 8 then 8 else CAST(DC.value as FLOAT) END) / 8 
		from DAY_REPORT_CODE DC, day_report DR
		where DC.dayreport_snr = DR.dayreport_snr and DR.user_snr = MR.user_snr 
		and DR.DAYREPORT_SNR in (select DAYREPORT_SNR  from Horas where orden=1)
		and DATEPART(DW,DR.Date) not in (1,7) /* No cuenta Sabado Y Domingo */
		and DR.date between Dateadd(d, 14,@DIAS_IN) and Dateadd(d, 21-1,@DIAS_IN)
		and DR.rec_stat = 0
		and DC.rec_Stat = 0
		),0)
		ELSE
		ISNULL((Select SUM(CASE when CAST(DC.value as FLOAT) > 8 then 8 else CAST(DC.value as FLOAT) END) / 8 
		from DAY_REPORT_CODE DC, day_report DR
		where DC.dayreport_snr = DR.dayreport_snr and DR.user_snr = MR.user_snr 
		and DR.DAYREPORT_SNR in (select DAYREPORT_SNR  from Horas where orden=1)
		and DATEPART(DW,DR.Date) not in (1,7) /* No cuenta Sabado Y Domingo */
		and DR.date between Dateadd(d, 14,@DIAS_IN) and @DIAS_FIN
		and DR.rec_stat = 0
		and DC.rec_Stat = 0
		),0)
		END) as OAct3,
		 
		(CASE when @DIAS_FIN>= Dateadd(d, 28-1,@DIAS_IN) then
		(Select count(*) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between Dateadd(d,21,@DIAS_IN) and Dateadd(d, 28-1,@DIAS_IN)
		and P.status_snr = @STATUS )
		ELSE
		(Select count(*) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between Dateadd(d,21,@DIAS_IN) and @DIAS_FIN
		and P.status_snr = @STATUS )
		END) as VP4,
		 
		(CASE when @DIAS_FIN>= Dateadd(d, 28-1,@DIAS_IN) then
		(DATEDIFF(DD, Dateadd(d, 21,@DIAS_IN), Dateadd(d, 28-1,@DIAS_IN)) ) - 1 
		ELSE 
		DATEDIFF(DD, Dateadd(d, 21,@DIAS_IN), @DIAS_FIN)
		END) as Dias_VP4,
		 
		/*DESCUENTO DE DIAS*/
		(CASE when @DIAS_FIN>= Dateadd(d, 28-1,@DIAS_IN) then
		ISNULL((Select SUM(CASE when CAST(DC.value as FLOAT) > 8 then 8 else CAST(DC.value as FLOAT) END) / 8 
		from DAY_REPORT_CODE DC, day_report DR
		where DC.dayreport_snr = DR.dayreport_snr and DR.user_snr = MR.user_snr 
		and DR.DAYREPORT_SNR in (select DAYREPORT_SNR  from Horas where orden=1)
		and DATEPART(DW,DR.Date) not in (1,7) /* No cuenta Sabado Y Domingo */
		and DR.date between Dateadd(d, 21,@DIAS_IN) and Dateadd(d, 28-1,@DIAS_IN)
		and DR.rec_stat = 0
		and DC.rec_Stat = 0
		),0)
		ELSE
		ISNULL((Select SUM(CASE when CAST(DC.value as FLOAT) > 8 then 8 else CAST(DC.value as FLOAT) END) / 8 
		from DAY_REPORT_CODE DC, day_report DR
		where DC.dayreport_snr = DR.dayreport_snr and DR.user_snr = MR.user_snr 
		and DR.DAYREPORT_SNR in (select DAYREPORT_SNR  from Horas where orden=1)
		and DATEPART(DW,DR.Date) not in (1,7) /* No cuenta Sabado Y Domingo */
		and DR.date between Dateadd(d, 21,@DIAS_IN) and @DIAS_FIN
		and DR.rec_stat = 0
		and DC.rec_Stat = 0
		),0)
		END) as OAct4,
		 
		(CASE when @DIAS_FIN>= Dateadd(d, 35-1,@DIAS_IN) then
		(Select count(*) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between Dateadd(d,28,@DIAS_IN) and Dateadd(d, 35-1,@DIAS_IN)
		and P.status_snr = @STATUS )
		ELSE
		(Select count(*) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between Dateadd(d,28,@DIAS_IN) and @DIAS_FIN
		and P.status_snr = @STATUS )
		END) as VP5,
		 
		(CASE when @DIAS_FIN>= Dateadd(d, 35-1,@DIAS_IN) then
		(DATEDIFF(DD, Dateadd(d, 28,@DIAS_IN), Dateadd(d, 35-1,@DIAS_IN)) ) - 1 
		ELSE 
		DATEDIFF(DD, Dateadd(d, 28,@DIAS_IN), @DIAS_FIN)
		END) as Dias_VP5,
		 
		/*DESCUENTO DE DIAS*/
		(CASE when @DIAS_FIN>= Dateadd(d, 35-1,@DIAS_IN) then
		ISNULL((Select SUM(CASE when CAST(DC.value as FLOAT) > 8 then 8 else CAST(DC.value as FLOAT) END) / 8 
		from DAY_REPORT_CODE DC, day_report DR
		where DC.dayreport_snr = DR.dayreport_snr and DR.user_snr = MR.user_snr 
		and DR.DAYREPORT_SNR in (select DAYREPORT_SNR  from Horas where orden=1)
		and DATEPART(DW,DR.Date) not in (1,7) /* No cuenta Sabado Y Domingo */
		and DR.date between Dateadd(d, 28,@DIAS_IN) and Dateadd(d, 35-1,@DIAS_IN)
		and DR.rec_stat = 0
		and DC.rec_Stat = 0
		),0)
		ELSE
		ISNULL((Select SUM(CASE when CAST(DC.value as FLOAT) > 8 then 8 else CAST(DC.value as FLOAT) END) / 8 
		from DAY_REPORT_CODE DC, day_report DR
		where DC.dayreport_snr = DR.dayreport_snr and DR.user_snr = MR.user_snr 
		and DR.DAYREPORT_SNR in (select DAYREPORT_SNR  from Horas where orden=1)
		and DATEPART(DW,DR.Date) not in (1,7) /* No cuenta Sabado Y Domingo */
		and DR.date between Dateadd(d, 28,@DIAS_IN) and @DIAS_FIN
		and DR.rec_stat = 0
		and DC.rec_Stat = 0
		),0)
		END) as OAct5, 
		 
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
		) as One_Vis,
		 
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date Between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and VP.escort_snr in ('7A50E6CC-A1A5-4495-AB9B-BA22AB92CB4F')
		) as Vis_GteDto,
		 
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date Between @DIAS_IN and @DIAS_FIN 
		and P.status_snr = @STATUS
		and VP.escort_snr='82B876F5-7BF4-440E-A5CA-CC9A7D0411F4'
		) as Vis_GteNac 
		 
		from users DM, (select distinct reg_snr, kloc_snr, rec_stat from KLOC_REG) klr, users MR, company CIA, compline LINEA
		 
		where klr.REG_SNR = DM.USER_SNR
		and klr.kloc_snr = MR.user_snr
		and klr.REC_STAT=0
		and MR.REC_STAT=0
		and DM.REC_STAT=0
		and MR.Status in (1,2)
		and DM.Status in (1,2)
		and MR.User_type = 4
		and DM.User_type = 5
		and MR.User_snr <> '50B63C60-AC79-4258-A54E-097371A61A3B' /*1010999 test*/
		and MR.cline_snr = LINEA.cline_snr
		and CIA.comp_snr = LINEA.comp_snr
		and CIA.rec_stat=0
		and LINEA.rec_stat=0
		and (mr.user_snr in (SELECT kloc_snr FROM kloc_reg 
		where rec_stat=0 and kloc_snr in ('".$ids."') ))
		and exists (select * from Kommloc KL1 where KL1.rec_stat = 0 and kl1.Activated = 1 and kl1.kloc_snr = klr.kloc_snr)
		 
		Order by DM.lname,DM.FNAME,MR.LNAME,MR.FNAME,klr.REG_SNR ";
	
	//echo $qMedicos."<br>";
		
	if($tipo == 1 || $tipo == 2){//excel
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=reporteCoberturaVisitaMedica.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else if($tipo == 3){
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array(1300,1000));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('Reporte de Cobertura de Visita Medica'));
		$pdf->Ln();
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(40,5,'AlfaSigma');
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

	//$tamTabla = 2520;
	$tamTabla = 2500;
	$tabla = '';
	if( $tipo != 3){
			$tabla .= '<table border="0">
				<tr>
					<td>
						<table>
							<tr>
								<td colspan="10" class="nombreReporte">Reporte de Cobertura de Visita Medica</td>
							</tr>
							<tr>
								<td colspan="10" class="clienteReporte">AlfaSigma</td>
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
			$tabla .= '<thead style="background-color: #A9BCF5;font-weight:bold;border: 1px solid #000;padding: 5px 5px 5px 5px;color:#000"><tr>';
		}else{
			$tabla .= '<thead><tr>';
		}
	}else{
		$pdf->SetFillColor(169,188,245);
			$pdf->SetTextColor(0);
			$pdf->SetDrawColor(0);
			$pdf->SetLineWidth(1);
			$pdf->SetFont('','B');
	}
	
	if($tipo == 2){
		$estilorepre = '';
		$estilogte = '';
		$estilocabecera = '';
	}else{
		$estilocabecera = 'style="background-color: #A9BCF5;font-weight:bold;border: 1px solid #000;padding: 5px 5px 5px 5px;color:#000"';
		$estilorepre = 'style="border: 1px solid #000;white-space:nowrap;"';
		$estilogte = 'style="background-color: #A9BCF5;border: 1px solid #000;white-space:nowrap;"';
	}

	
	$i=1;
	//inicia var nacional
	$totalVPP1 = 0;
	$totalVP1 = 0;
	$totalCob1 = 0;
	$totalVPP2 = 0;
	$totalVP2 = 0;
	$totalCob2 = 0;
	$totalVPP3 = 0;
	$totalVP3 = 0;
	$totalCob3 = 0;
	$totalVPP4 = 0;
	$totalVP4 = 0;
	$totalCob4 = 0;
	$totalVPP5 = 0;
	$totalVP5 = 0;
	$totalCob5 = 0;
	$planTotalNal = 0;
	$visTotalNal = 0;
	$cobTotalNal = 0;
	$totalMeds = 0;
	$totalVisUni = 0;
	$cobTotalUniNal = 0;
	$revisTotalNal = 0;
	$cobTotalMedNal = 0;
	$totalVisAcompDto = 0;
	$totalVisAcompNac = 0;
	$totalSumaVisAcomp = 0;

	while($reg = sqlsrv_fetch_array($rsMedicos)){
		////suma nacional
		if ($reg['OAct1'] < $reg['Dias_VP1'] ){
			$totalVPP1 += round($reg['CUOTA']*($reg['Dias_VP1']-$reg['OAct1']));
		}else{ 
			$totalVPP1 += 0;
		}
		$totalVP1 += $reg['VP1'];
		if ($totalVPP1 > 0 && $totalVP1 > 0){
			$totalCob1 = ($totalVP1 / $totalVPP1) * 100;
		}else{
			$totalCob1 = 0;
		}
		
		if ($reg['OAct2'] < $reg['Dias_VP2'] ){
			$totalVPP2 += round($reg['CUOTA']*($reg['Dias_VP2']-$reg['OAct2']));
		}else{ 
			$totalVPP2 += 0;
		}
		$totalVP2 += $reg['VP2'];
		if ($totalVPP2 > 0 && $totalVP2 > 0){
			$totalCob2 = ($totalVP2 / $totalVPP2) * 100;
		}else{
			$totalCob2 = 0;
		}
		
		if ($reg['OAct3'] < $reg['Dias_VP3'] ){
			$totalVPP3 += round($reg['CUOTA']*($reg['Dias_VP3']-$reg['OAct3']));
		}else{ 
			$totalVPP3 += 0;
		}
		$totalVP3 += $reg['VP3'];
		if ($totalVPP3 > 0 && $totalVP3 > 0){
			$totalCob3 = ($totalVP3 / $totalVPP3) * 100;
		}else{
			$totalCob3 = 0;
		}
		
		if ($reg['OAct4'] < $reg['Dias_VP4'] ){
			$totalVPP4 += round($reg['CUOTA']*($reg['Dias_VP4']-$reg['OAct4']));
		}else{ 
			$totalVPP4 += 0;
		}
		$totalVP4 += $reg['VP4'];
		if ($totalVPP4 > 0 && $totalVP4 > 0){
			$totalCob4 = ($totalVP4 / $totalVPP4) * 100;
		}else{
			$totalCob4 = 0;
		}
		
		if ($reg['OAct5'] < $reg['Dias_VP5'] ){
			$totalVPP5 += round($reg['CUOTA']*($reg['Dias_VP5']-$reg['OAct5']));
		}else{ 
			$totalVPP5 += 0;
		}
		$totalVP5 += $reg['VP5'];
		if ($totalVPP5 > 0 && $totalVP5 > 0){
			$totalCob5 = ($totalVP5 / $totalVPP5) * 100;
		}else{
			$totalCob5 = 0;
		}
		
		$planTotalNal = $totalVPP1 + $totalVPP2 + $totalVPP3 + $totalVPP4 + $totalVPP5;
		$visTotalNal = $totalVP1 + $totalVP2 + $totalVP3 + $totalVP4 + $totalVP5;
		if ($visTotalNal > 0 && $planTotalNal > 0){
			$cobTotalNal = ($visTotalNal / $planTotalNal) * 100;
		}else{
			$cobTotalNal = 0;
		}	
		$totalMeds += $reg['DR_NR'];
		$totalVisUni += $reg['One_Vis'];
		if ($totalVisUni > 0 && $totalMeds > 0){
			$cobTotalUniNal = ($totalVisUni / $totalMeds) * 100;
		}else{
			$cobTotalUniNal = 0;
		}
		$revisTotalNal = $visTotalNal - $totalVisUni;
		if ($visTotalNal > 0 && $totalMeds > 0){
			$cobTotalMedNal = ($visTotalNal / $totalMeds) * 100;
		}else{
			$cobTotalMedNal = 0;
		}
		$totalVisAcompDto += $reg['Vis_GteDto'];
		$totalVisAcompNac += $reg['Vis_GteNac'];
		$totalSumaVisAcomp = $totalVisAcompDto + $totalVisAcompNac;
		
		if($i == 1){
			////imprimir encabezados
			if($tipo != 3){
				$tabla .= '<td '.$estilocabecera.' rowspan="2" width="400px">Ruta - Nombre</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="3" width="200px" align="center">'.date("d/m", strtotime($fechaI)).' - '.date("d/m", strtotime('+6 day', strtotime($fechaI))).'<br>Semana 1</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="3" width="200px" align="center">'.date("d/m", strtotime('+7 day', strtotime($fechaI))).' - '.date("d/m", strtotime('+13 day', strtotime($fechaI))).'<br>Semana 2</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="3" width="200px" align="center">'.date("d/m", strtotime('+14 day', strtotime($fechaI))).' - '.date("d/m", strtotime('+20 day', strtotime($fechaI))).'<br>Semana 3</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="3" width="200px" align="center">'.date("d/m", strtotime('+21 day', strtotime($fechaI))).' - '.date("d/m", strtotime('+27 day', strtotime($fechaI))).'<br>Semana 4</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="3" width="200px" align="center">'.date("d/m", strtotime('+28 day', strtotime($fechaI))).' - '.date("d/m", strtotime('+34 day', strtotime($fechaI))).'<br>Semana 5</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="3" width="200px" align="center">Cobertura Total vs Plan</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="6" width="600px" align="center">Cobertura Total vs Universo</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="3" width="300px" align="center">Resumen de Visita Acompañada</td></tr>';
				$tabla .= '<tr><td '.$estilocabecera.' width="50px" align="center">V.P.</td>';
				$tabla .= '<td '.$estilocabecera.' width="50px" align="center">V.R.</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Cob.</td>';
				$tabla .= '<td '.$estilocabecera.' width="50px" align="center">V.P.</td>';
				$tabla .= '<td '.$estilocabecera.' width="50px" align="center">V.R.</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Cob.</td>';
				$tabla .= '<td '.$estilocabecera.' width="50px" align="center">V.P.</td>';
				$tabla .= '<td '.$estilocabecera.' width="50px" align="center">V.R.</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Cob.</td>';
				$tabla .= '<td '.$estilocabecera.' width="50px" align="center">V.P.</td>';
				$tabla .= '<td '.$estilocabecera.' width="50px" align="center">V.R.</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Cob.</td>';
				$tabla .= '<td '.$estilocabecera.' width="50px" align="center">V.P.</td>';
				$tabla .= '<td '.$estilocabecera.' width="50px" align="center">V.R.</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Cob.</td>';
				$tabla .= '<td '.$estilocabecera.' width="50px" align="center">V.P.</td>';
				$tabla .= '<td '.$estilocabecera.' width="50px" align="center">V.R.</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Cob.</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Meds Regis</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Meds Visit Uni</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Cob.</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">ReVisit</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Total Visitas</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Cob. Total</td>';	
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Gte Dto</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Gte Nal</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Total Vis Acomp</td>';	
			}else{
				$pdf->Ln();			
				$pdf->Cell(200,10,'','LTR',0,'L',1);
				$pdf->Cell(100,10,date("d/m", strtotime($fechaI)).' - '.date("d/m", strtotime('+6 day', strtotime($fechaI))),'LTR',0,'C',1);
				$pdf->Cell(100,10,date("d/m", strtotime('+7 day', strtotime($fechaI))).' - '.date("d/m", strtotime('+13 day', strtotime($fechaI))),'LTR',0,'C',1);
				$pdf->Cell(100,10,date("d/m", strtotime('+14 day', strtotime($fechaI))).' - '.date("d/m", strtotime('+20 day', strtotime($fechaI))),'LTR',0,'C',1);
				$pdf->Cell(100,10,date("d/m", strtotime('+21 day', strtotime($fechaI))).' - '.date("d/m", strtotime('+27 day', strtotime($fechaI))),'LTR',0,'C',1);
				$pdf->Cell(100,10,date("d/m", strtotime('+28 day', strtotime($fechaI))).' - '.date("d/m", strtotime('+34 day', strtotime($fechaI))),'LTR',0,'C',1);
				$pdf->Cell(100,10,'','LTR',0,'L',1);
				$pdf->Cell(300,10,'','LTR',0,'L',1);
				$pdf->Cell(150,10,'','LTR',0,'L',1);
				$pdf->Ln();
				$pdf->Cell(200,10,'Ruta - Nombre','LR',0,'L',1);
				$pdf->Cell(100,10,'Semana 1','LR',0,'C',1);
				$pdf->Cell(100,10,'Semana 2','LR',0,'C',1);
				$pdf->Cell(100,10,'Semana 3','LR',0,'C',1);
				$pdf->Cell(100,10,'Semana 4','LR',0,'C',1);
				$pdf->Cell(100,10,'Semana 5','LR',0,'C',1);
				$pdf->Cell(100,10,'Cobertura Total vs Plan','LRB',0,'C',1);
				$pdf->Cell(300,10,'Cobertura Total vs Universo','LRB',0,'C',1);
				$pdf->Cell(150,10,'Resumen de Visita Acompañada','LRB',0,'C',1);
				$pdf->Ln();			
				$pdf->Cell(200,10,'','LRB',0,'L',1);
				$pdf->Cell(25,10,'V.P.',1,0,'C',1);
				$pdf->Cell(25,10,'V.R.',1,0,'C',1);
				$pdf->Cell(50,10,'% Cob.',1,0,'C',1);
				$pdf->Cell(25,10,'V.P.',1,0,'C',1);
				$pdf->Cell(25,10,'V.R.',1,0,'C',1);
				$pdf->Cell(50,10,'% Cob.',1,0,'C',1);
				$pdf->Cell(25,10,'V.P.',1,0,'C',1);
				$pdf->Cell(25,10,'V.R.',1,0,'C',1);
				$pdf->Cell(50,10,'% Cob.',1,0,'C',1);
				$pdf->Cell(25,10,'V.P.',1,0,'C',1);
				$pdf->Cell(25,10,'V.R.',1,0,'C',1);
				$pdf->Cell(50,10,'% Cob.',1,0,'C',1);
				$pdf->Cell(25,10,'V.P.',1,0,'C',1);
				$pdf->Cell(25,10,'V.R.',1,0,'C',1);
				$pdf->Cell(50,10,'% Cob.',1,0,'C',1);
				$pdf->Cell(25,10,'V.P.',1,0,'C',1);
				$pdf->Cell(25,10,'V.R.',1,0,'C',1);
				$pdf->Cell(50,10,'% Cob.',1,0,'C',1);
				$pdf->Cell(50,10,'Meds Regis',1,0,'C',1);
				$pdf->Cell(50,10,'Meds Visit Uni',1,0,'C',1);
				$pdf->Cell(50,10,'% Cob.',1,0,'C',1);
				$pdf->Cell(50,10,'ReVisit',1,0,'C',1);
				$pdf->Cell(50,10,'Total Visitas',1,0,'C',1);
				$pdf->Cell(50,10,'% Cob. Total',1,0,'C',1);
				$pdf->Cell(50,10,'Gte Dto',1,0,'C',1);
				$pdf->Cell(50,10,'Gte Nal',1,0,'C',1);
				$pdf->Cell(50,10,'Total Vis Acomp',1,0,'C',1);
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
			if ($reg['OAct1'] < $reg['Dias_VP1'] ){
				$gteVPP1 = round($reg['CUOTA']*($reg['Dias_VP1']-$reg['OAct1']));
			}else{ 
				$gteVPP1 = 0;
			}
			$gteVP1 = $reg['VP1'];
			//echo $reg['CUOTA'].'*('.$reg['Dias_VP1'].'-'.$reg['OAct1'].')<br>';
			//echo $gteVPP1 .' '.$i.'<br>' ;
			if ($gteVPP1 > 0 && $gteVP1 > 0){
				$gteCob1 = ($gteVP1 / $gteVPP1) * 100;
			}else{
				$gteCob1 = 0;
			}
			
			if ($reg['OAct2'] < $reg['Dias_VP2'] ){
				$gteVPP2 = round($reg['CUOTA']*($reg['Dias_VP2']-$reg['OAct2']));
			}else{ 
				$gteVPP2 = 0;
			}	
			$gteVP2 = $reg['VP2'];
			if ($gteVPP2 > 0 && $gteVP2 > 0){
				$gteCob2 = ($gteVP2 / $gteVPP2) * 100;
			}else{
				$gteCob2 = 0;
			}
			
			if ($reg['OAct3'] < $reg['Dias_VP3'] ){
				$gteVPP3 = round($reg['CUOTA']*($reg['Dias_VP3']-$reg['OAct3']));
			}else{ 
				$gteVPP3 = 0;
			}
			$gteVP3 = $reg['VP3'];
			if ($gteVPP3 > 0 && $gteVP3 > 0){
				$gteCob3 = ($gteVP3 / $gteVPP3) * 100;
			}else{
				$gteCob3 = 0;
			}
			
			if ($reg['OAct4'] < $reg['Dias_VP4'] ){
				$gteVPP4 = round($reg['CUOTA']*($reg['Dias_VP4']-$reg['OAct4']));
			}else{ 
				$gteVPP4 = 0;
			}
			$gteVP4 = $reg['VP4'];
			if ($gteVPP4 > 0 && $gteVP4 > 0){
				$gteCob4 = ($gteVP4 / $gteVPP4) * 100;
			}else{
				$gteCob4 = 0;
			}
			
			if ($reg['OAct5'] < $reg['Dias_VP5'] ){
				$gteVPP5 = round($reg['CUOTA']*($reg['Dias_VP5']-$reg['OAct5']));
			}else{ 
				$gteVPP5 = 0;
			}
			$gteVP5 = $reg['VP5'];
			if ($gteVPP5 > 0 && $gteVP5 > 0){
				$gteCob5 = ($gteVP5 / $gteVPP5) * 100;
			}else{
				$gteCob5 = 0;
			}
			
			$planTotalGte = $gteVPP1 + $gteVPP2 + $gteVPP3 + $gteVPP4 + $gteVPP5;
			$visTotalGte = $gteVP1 + $gteVP2 + $gteVP3 + $gteVP4 + $gteVP5;
			if ($visTotalGte > 0 && $planTotalGte > 0){
				$cobTotalGte = ($visTotalGte / $planTotalGte) * 100;
			}else{
				$cobTotalGte = 0;
			}
			$gteMeds = $reg['DR_NR'];
			$gteVisUni = $reg['One_Vis'];
			if ($gteVisUni > 0){
				$cobTotalUniGte = ($gteVisUni / $gteMeds) * 100;
			}else{
				$cobTotalUniGte = 0;
			}
			$revisTotalGte = 0;
			if ($visTotalGte > 0){
				$cobTotalMedGte = ($visTotalGte / $gteMeds) * 100;
			}else{
				$cobTotalMedGte = 0;
			}
			$gteVisAcompDto = $reg['Vis_GteDto'];
			$gteVisAcompNac = $reg['Vis_GteNac'];
			$gteSumaVisAcomp = $gteVisAcompDto + $gteVisAcompNac;
			
		}else{
			////sumas gerentes
			$gerente = $reg['REG_SNR'];		
			if($tempGerente == $gerente){
				if ($reg['OAct1'] < $reg['Dias_VP1'] ){
					$planSem1GTE = round($reg['CUOTA']*($reg['Dias_VP1']-$reg['OAct1']));
				}else{ 
					$planSem1GTE = 0;
				}
				$gteVPP1 += $planSem1GTE;
				//echo $reg['CUOTA'].'*('.$reg['Dias_VP1'].'-'.$reg['OAct1'].')<br>';
				//echo $gteVPP1 .' '.$i.'<br>' ;
				$visSem1GTE = $reg['VP1'];
				$gteVP1 += $visSem1GTE;
				if ($gteVPP1 > 0 && $gteVP1 > 0){
					$gteCob1 = ($gteVP1 / $gteVPP1) * 100;
				}else{
					$gteCob1 = 0;
				}
				
				if ($reg['OAct2'] < $reg['Dias_VP2'] ){
					$planSem2GTE = round($reg['CUOTA']*($reg['Dias_VP2']-$reg['OAct2']));
				}else{ 
					$planSem2GTE = 0;
				}
				$gteVPP2 += $planSem2GTE;
				$visSem2GTE = $reg['VP2'];
				$gteVP2 += $visSem2GTE;
				if ($gteVPP2 > 0 && $gteVP2 > 0){
					$gteCob2 = ($gteVP2 / $gteVPP2) * 100;
				}else{
					$gteCob2 = 0;
				}
				
				if ($reg['OAct3'] < $reg['Dias_VP3'] ){
					$planSem3GTE = round($reg['CUOTA']*($reg['Dias_VP3']-$reg['OAct3']));
				}else{ 
					$planSem3GTE = 0;
				}
				$gteVPP3 += $planSem3GTE;
				$visSem3GTE = $reg['VP3'];
				$gteVP3 += $visSem3GTE;
				if ($gteVPP3 > 0 && $gteVP3 > 0){
					$gteCob3 = ($gteVP3 / $gteVPP3) * 100;
				}else{
					$gteCob3 = 0;
				}
				
				if ($reg['OAct4'] < $reg['Dias_VP4'] ){
					$planSem4GTE = round($reg['CUOTA']*($reg['Dias_VP4']-$reg['OAct4']));
				}else{ 
					$planSem4GTE = 0;
				}
				$gteVPP4 += $planSem4GTE;
				$visSem4GTE = $reg['VP4'];
				$gteVP4 += $visSem4GTE;
				if ($gteVPP4 > 0 && $gteVP4 > 0){
					$gteCob4 = ($gteVP4 / $gteVPP4) * 100;
				}else{
					$gteCob4 = 0;
				}
				
				if ($reg['OAct5'] < $reg['Dias_VP5'] ){
					$planSem5GTE = round($reg['CUOTA']*($reg['Dias_VP5']-$reg['OAct5']));
				}else{ 
					$planSem5GTE = 0;
				}
				$gteVPP5 += $planSem5GTE;
				$visSem5GTE = $reg['VP5'];
				$gteVP5 += $visSem5GTE;
				if ($gteVPP5 > 0 && $gteVP5 > 0){
					$gteCob5 = ($gteVP5 / $gteVPP5) * 100;
				}else{
					$gteCob5 = 0;
				}
				
				$planTotalGte = $gteVPP1 + $gteVPP2 + $gteVPP3 + $gteVPP4 + $gteVPP5;
				$visTotalGte = $gteVP1 + $gteVP2 + $gteVP3 + $gteVP4 + $gteVP5;
				if ($visTotalGte > 0 && $planTotalGte > 0){
					$cobTotalGte = ($visTotalGte / $planTotalGte) * 100;
				}else{
					$cobTotalGte = 0;
				}
				$sumMeds = $reg['DR_NR'];
				$gteMeds += $sumMeds;
				$sumVisUni = $reg['One_Vis'];
				$gteVisUni += $sumVisUni;
				if ($gteVisUni > 0 && $gteMeds > 0){
					$cobTotalUniGte = ($gteVisUni / $gteMeds) * 100;
				}else{
					$cobTotalUniGte = 0;
				}
				$revisTotalGte = $visTotalGte - $gteVisUni;
				if ($visTotalGte > 0 && $gteMeds > 0){
					$cobTotalMedGte = ($visTotalGte / $gteMeds) * 100;
				}else{
					$cobTotalMedGte = 0;
				}
				$sumVisAcompDto = $reg['Vis_GteDto'];
				$gteVisAcompDto += $sumVisAcompDto;
				$sumVisAcompNac = $reg['Vis_GteNac'];
				$gteVisAcompNac += $sumVisAcompNac;
				$gteSumaVisAcomp = $gteVisAcompDto + $gteVisAcompNac;
				
			}else{	
				////imprimir gerentes
				if($tipo != 3){					
					$tabla .= '<tr><td '.$estilogte.' width="400px">'.$nombreGte.'</td>';
					$tabla .= '<td '.$estilogte.' width="50px" align="center">'.$gteVPP1.'</td>';
					$tabla .= '<td '.$estilogte.' width="50px" align="center">'.$gteVP1.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteCob1, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="50px" align="center">'.$gteVPP2.'</td>';
					$tabla .= '<td '.$estilogte.' width="50px" align="center">'.$gteVP2.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteCob2, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="50px" align="center">'.$gteVPP3.'</td>';
					$tabla .= '<td '.$estilogte.' width="50px" align="center">'.$gteVP3.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteCob3, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="50px" align="center">'.$gteVPP4.'</td>';
					$tabla .= '<td '.$estilogte.' width="50px" align="center">'.$gteVP4.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteCob4, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="50px" align="center">'.$gteVPP5.'</td>';
					$tabla .= '<td '.$estilogte.' width="50px" align="center">'.$gteVP5.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteCob5, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="50px" align="center">'.number_format($planTotalGte).'</td>';
					$tabla .= '<td '.$estilogte.' width="50px" align="center">'.number_format($visTotalGte).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($cobTotalGte, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteMeds).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisUni).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($cobTotalUniGte, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$revisTotalGte.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($visTotalGte).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($cobTotalMedGte, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisAcompDto.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisAcompNac.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteSumaVisAcomp.'</td>';
					$tabla .= '</tr>';
				}else{ 
					$pdf->Cell(200,10,$nombreGte,1,0,'L',1);
					$pdf->Cell(25,10,$gteVPP1,1,0,'C',1);
					$pdf->Cell(25,10,$gteVP1,1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteCob1, 2).' %',1,0,'R',1);
					$pdf->Cell(25,10,$gteVPP2,1,0,'C',1);
					$pdf->Cell(25,10,$gteVP2,1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteCob2, 2).' %',1,0,'R',1);
					$pdf->Cell(25,10,$gteVPP3,1,0,'C',1);
					$pdf->Cell(25,10,$gteVP3,1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteCob3, 2).' %',1,0,'R',1);
					$pdf->Cell(25,10,$gteVPP4,1,0,'C',1);
					$pdf->Cell(25,10,$gteVP4,1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteCob4, 2).' %',1,0,'R',1);
					$pdf->Cell(25,10,$gteVPP5,1,0,'C',1);
					$pdf->Cell(25,10,$gteVP5,1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteCob5, 2).' %',1,0,'R',1);
					$pdf->Cell(25,10,number_format($planTotalGte),1,0,'C',1);
					$pdf->Cell(25,10,number_format($visTotalGte),1,0,'C',1);
					$pdf->Cell(50,10,number_format($cobTotalGte, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gteMeds),1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteVisUni),1,0,'C',1);
					$pdf->Cell(50,10,number_format($cobTotalUniGte, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,$revisTotalGte,1,0,'C',1);
					$pdf->Cell(50,10,number_format($visTotalGte),1,0,'C',1);
					$pdf->Cell(50,10,number_format($cobTotalMedGte, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,$gteVisAcompDto,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisAcompNac,1,0,'C',1);
					$pdf->Cell(50,10,$gteSumaVisAcomp,1,1,'C',1);
				}
	
				////inicia var gerente
				$tempGerente = $reg['REG_SNR'];
				$nombreGte = $reg['RM'];
				if ($reg['OAct1'] < $reg['Dias_VP1'] ){
					$gteVPP1 = round($reg['CUOTA']*($reg['Dias_VP1']-$reg['OAct1']));
				}else{ 
					$gteVPP1 = 0;
				}
				$gteVP1 = $reg['VP1'];
				$gteCob1 = 0;
				
				if ($reg['OAct2'] < $reg['Dias_VP2'] ){
					$gteVPP2 = round($reg['CUOTA']*($reg['Dias_VP2']-$reg['OAct2']));
				}else{ 
					$gteVPP2 = 0;
				}
				$gteVP2 = $reg['VP2'];
				$gteCob2 = 0;
				
				if ($reg['OAct3'] < $reg['Dias_VP3'] ){
					$gteVPP3 = round($reg['CUOTA']*($reg['Dias_VP3']-$reg['OAct3']));
				}else{ 
					$gteVPP3 = 0;
				}
				$gteVP3 = $reg['VP3'];
				$gteCob3 = 0;
				
				if ($reg['OAct4'] < $reg['Dias_VP4'] ){
					$gteVPP4 = round($reg['CUOTA']*($reg['Dias_VP4']-$reg['OAct4']));
				}else{ 
					$gteVPP4 = 0;
				}
				$gteVP4 = $reg['VP4'];
				$gteCob4 = 0;
				
				if ($reg['OAct5'] < $reg['Dias_VP5'] ){
					$gteVPP5 = round($reg['CUOTA']*($reg['Dias_VP5']-$reg['OAct5']));
				}else{ 
					$gteVPP5 = 0;
				}
				$gteVP5 = $reg['VP5'];
				$gteCob5 = 0;
				
				$planTotalGte = 0;
				$visTotalGte = 0;
				$cobTotalGte = 0;
				$gteMeds = $reg['DR_NR'];
				$gteVisUni = $reg['One_Vis'];
				$cobTotalUniGte = 0;
				$revisTotalGte = 0;
				$cobTotalMedGte = 0;
				$gteVisAcompDto = $reg['Vis_GteDto'];
				$gteVisAcompNac = $reg['Vis_GteNac'];
				$gteSumaVisAcomp = 0;
			}
		}
		
		////formulas repres
		$tabla .= '<tr>';
		if ($reg['OAct1'] < $reg['Dias_VP1'] ){
			$planSem1 = round($reg['CUOTA']*($reg['Dias_VP1']-$reg['OAct1']));
		}else{ 
			$planSem1 = 0;
		}
		if ($planSem1 > 0 && $reg['VP1'] > 0){
			$cobSem1 = ($reg['VP1'] / $planSem1) * 100;
		}else{
			$cobSem1 = 0;
		}
		
		if ($reg['OAct2'] < $reg['Dias_VP2'] ){
			$planSem2 = round($reg['CUOTA']*($reg['Dias_VP2']-$reg['OAct2']));
		}else{ 
			$planSem2 = 0;
		}
		if ($planSem2 > 0 && $reg['VP2'] > 0){
			$cobSem2 = ($reg['VP2'] / $planSem2) * 100;
		}else{
			$cobSem2 = 0;
		}
		
		if ($reg['OAct3'] < $reg['Dias_VP3'] ){
			$planSem3 = round($reg['CUOTA']*($reg['Dias_VP3']-$reg['OAct3']));
		}
		else{ 
			$planSem3 = 0;
		}
		if ($planSem3 > 0 && $reg['VP3'] > 0){
			$cobSem3 = ($reg['VP3'] / $planSem3) * 100;
		}else{
			$cobSem3 = 0;
		}
		
		if ($reg['OAct4'] < $reg['Dias_VP4'] ){
			$planSem4 = round($reg['CUOTA']*($reg['Dias_VP4']-$reg['OAct4']));
		}else{ 
			$planSem4 = 0;
		}
		if ($planSem4 > 0 && $reg['VP4'] > 0){
			$cobSem4 = ($reg['VP4'] / $planSem4) * 100;
		}else{
			$cobSem4 = 0;
		}
		
		if ($reg['OAct5'] < $reg['Dias_VP5'] ){
			$planSem5 = round($reg['CUOTA']*($reg['Dias_VP5']-$reg['OAct5']));
		}else{ 
			$planSem5 = 0;
		}
		if ($planSem5 > 0 && $reg['VP5'] > 0){
			$cobSem5 = ($reg['VP5'] / $planSem5) * 100;
		}else{
			$cobSem5 = 0;
		}
		
		$planTotal = $planSem1 + $planSem2 + $planSem3 + $planSem4 + $planSem5 ;
		$visTotal = $reg['VP1'] + $reg['VP2'] + $reg['VP3'] + $reg['VP4'] + $reg['VP5'] ;
		if ($planTotal > 0 && $visTotal > 0){
			$cobTotal = ($visTotal / $planTotal) * 100;
		}else{
			$cobTotal = 0;
		}
		if ($reg['One_Vis'] > 0 && $reg['DR_NR'] > 0){
			$cobTotalUni = ($reg['One_Vis'] / $reg['DR_NR']) * 100;
		}else{
			$cobTotalUni = 0;
		}		
		$revisTotal = $visTotal - $reg['One_Vis'];
		if ($reg['DR_NR'] > 0 && $visTotal > 0){
			$cobTotalMed = ($visTotal / $reg['DR_NR']) * 100;
		}else{
			$cobTotalMed = 0;
		}
		$sumaVisAcomp = $reg['Vis_GteDto'] + $reg['Vis_GteNac'];

		////imprimir repres
		if($tipo != 3){
			$tabla .= '<td '.$estilorepre.' width="400px">'.$reg['SR'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="50px" align="center">'.$planSem1.'</td>';
			$tabla .= '<td '.$estilorepre.' width="50px" align="center">'.$reg['VP1'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($cobSem1, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="50px" align="center">'.$planSem2.'</td>';
			$tabla .= '<td '.$estilorepre.' width="50px" align="center">'.$reg['VP2'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($cobSem2, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="50px" align="center">'.$planSem3.'</td>';
			$tabla .= '<td '.$estilorepre.' width="50px" align="center">'.$reg['VP3'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($cobSem3, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="50px" align="center">'.$planSem4.'</td>';
			$tabla .= '<td '.$estilorepre.' width="50px" align="center">'.$reg['VP4'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($cobSem4, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="50px" align="center">'.$planSem5.'</td>';
			$tabla .= '<td '.$estilorepre.' width="50px" align="center">'.$reg['VP5'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($cobSem5, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="50px" align="center">'.$planTotal.'</td>';
			$tabla .= '<td '.$estilorepre.' width="50px" align="center">'.$visTotal.'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($cobTotal, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['DR_NR'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['One_Vis'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($cobTotalUni, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$revisTotal.'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$visTotal.'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($cobTotalMed, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_GteDto'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_GteNac'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$sumaVisAcomp.'</td>';
			$tabla .= '</tr>';
		}else{
			$pdf->Cell(200,10,$reg['SR'],1,0,'L',0);
			$pdf->Cell(25,10,$planSem1,1,0,'C',0);
			$pdf->Cell(25,10,$reg['VP1'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($cobSem1, 2).' %',1,0,'R',0);
			$pdf->Cell(25,10,$planSem2,1,0,'C',0);
			$pdf->Cell(25,10,$reg['VP2'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($cobSem2, 2).' %',1,0,'R',0);
			$pdf->Cell(25,10,$planSem3,1,0,'C',0);
			$pdf->Cell(25,10,$reg['VP3'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($cobSem3, 2).' %',1,0,'R',0);
			$pdf->Cell(25,10,$planSem4,1,0,'C',0);
			$pdf->Cell(25,10,$reg['VP4'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($cobSem4, 2).' %',1,0,'R',0);
			$pdf->Cell(25,10,$planSem5,1,0,'C',0);
			$pdf->Cell(25,10,$reg['VP5'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($cobSem5, 2).' %',1,0,'R',0);
			$pdf->Cell(25,10,$planTotal,1,0,'C',0);
			$pdf->Cell(25,10,$visTotal,1,0,'C',0);
			$pdf->Cell(50,10,number_format($cobTotal, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,$reg['DR_NR'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['One_Vis'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($cobTotalUni, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,$revisTotal,1,0,'C',0);
			$pdf->Cell(50,10,$visTotal,1,0,'C',0);
			$pdf->Cell(50,10,number_format($cobTotalMed, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,$reg['Vis_GteDto'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_GteNac'],1,0,'C',0);
			$pdf->Cell(50,10,$sumaVisAcomp,1,1,'C',0);
		}
		$i++;
	}
	
	////imprimir ultimo gerente
	if($tipo != 3){
		$tabla .= '<tr><td '.$estilogte.' width="400px">'.$nombreGte.'</td>';
		$tabla .= '<td '.$estilogte.' width="50px" align="center">'.$gteVPP1.'</td>';
		$tabla .= '<td '.$estilogte.' width="50px" align="center">'.$gteVP1.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteCob1, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="50px" align="center">'.$gteVPP2.'</td>';
		$tabla .= '<td '.$estilogte.' width="50px" align="center">'.$gteVP2.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteCob2, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="50px" align="center">'.$gteVPP3.'</td>';
		$tabla .= '<td '.$estilogte.' width="50px" align="center">'.$gteVP3.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteCob3, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="50px" align="center">'.$gteVPP4.'</td>';
		$tabla .= '<td '.$estilogte.' width="50px" align="center">'.$gteVP4.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteCob4, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="50px" align="center">'.$gteVPP5.'</td>';
		$tabla .= '<td '.$estilogte.' width="50px" align="center">'.$gteVP5.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteCob5, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="50px" align="center">'.number_format($planTotalGte).'</td>';
		$tabla .= '<td '.$estilogte.' width="50px" align="center">'.number_format($visTotalGte).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($cobTotalGte, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteMeds).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisUni).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($cobTotalUniGte, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($revisTotalGte).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($visTotalGte).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($cobTotalMedGte, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisAcompDto.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisAcompNac.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteSumaVisAcomp.'</td>';
		$tabla .= '</tr>';
	}else{ 
		$pdf->Cell(200,10,$nombreGte,1,0,'L',1);
		$pdf->Cell(25,10,$gteVPP1,1,0,'C',1);
		$pdf->Cell(25,10,$gteVP1,1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteCob1, 2).' %',1,0,'R',1);
		$pdf->Cell(25,10,$gteVPP2,1,0,'C',1);
		$pdf->Cell(25,10,$gteVP2,1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteCob2, 2).' %',1,0,'R',1);
		$pdf->Cell(25,10,$gteVPP3,1,0,'C',1);
		$pdf->Cell(25,10,$gteVP3,1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteCob3, 2).' %',1,0,'R',1);
		$pdf->Cell(25,10,$gteVPP4,1,0,'C',1);
		$pdf->Cell(25,10,$gteVP4,1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteCob4, 2).' %',1,0,'R',1);
		$pdf->Cell(25,10,$gteVPP5,1,0,'C',1);
		$pdf->Cell(25,10,$gteVP5,1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteCob5, 2).' %',1,0,'R',1);
		$pdf->Cell(25,10,number_format($planTotalGte),1,0,'C',1);
		$pdf->Cell(25,10,number_format($visTotalGte),1,0,'C',1);
		$pdf->Cell(50,10,number_format($cobTotalGte, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gteMeds),1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteVisUni),1,0,'C',1);
		$pdf->Cell(50,10,number_format($cobTotalUniGte, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,$revisTotalGte,1,0,'C',1);
		$pdf->Cell(50,10,number_format($visTotalGte),1,0,'C',1);
		$pdf->Cell(50,10,number_format($cobTotalMedGte, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,$gteVisAcompDto,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisAcompNac,1,0,'C',1);
		$pdf->Cell(50,10,$gteSumaVisAcomp,1,1,'C',1);
	}	

	////imprimir nacional
	if($tipo != 3){
		$tabla .= '<tr><td '.$estilogte.' width="400px">Total General</td>';
		$tabla .= '<td '.$estilogte.' width="50px" align="center">'.number_format($totalVPP1).'</td>';
		$tabla .= '<td '.$estilogte.' width="50px" align="center">'.number_format($totalVP1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalCob1, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="50px" align="center">'.number_format($totalVPP2).'</td>';
		$tabla .= '<td '.$estilogte.' width="50px" align="center">'.number_format($totalVP2).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalCob2, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="50px" align="center">'.number_format($totalVPP3).'</td>';
		$tabla .= '<td '.$estilogte.' width="50px" align="center">'.number_format($totalVP3).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalCob3, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="50px" align="center">'.number_format($totalVPP4).'</td>';
		$tabla .= '<td '.$estilogte.' width="50px" align="center">'.number_format($totalVP4).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalCob4, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="50px" align="center">'.number_format($totalVPP5).'</td>';
		$tabla .= '<td '.$estilogte.' width="50px" align="center">'.number_format($totalVP5).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalCob5, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="50px" align="center">'.number_format($planTotalNal).'</td>';
		$tabla .= '<td '.$estilogte.' width="50px" align="center">'.number_format($visTotalNal).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($cobTotalNal, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalMeds).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisUni).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($cobTotalUniNal, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($revisTotalNal).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($visTotalNal).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($cobTotalMedNal, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$totalVisAcompDto.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$totalVisAcompNac.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$totalSumaVisAcomp.'</td>';
		$tabla .= '</tr>';
	}else{ 
		$pdf->Cell(200,10,'Total General',1,0,'L',1);
		$pdf->Cell(25,10,number_format($totalVPP1),1,0,'C',1);
		$pdf->Cell(25,10,number_format($totalVP1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalCob1, 2).' %',1,0,'R',1);
		$pdf->Cell(25,10,number_format($totalVPP2),1,0,'C',1);
		$pdf->Cell(25,10,number_format($totalVP2),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalCob2, 2).' %',1,0,'R',1);
		$pdf->Cell(25,10,number_format($totalVPP3),1,0,'C',1);
		$pdf->Cell(25,10,number_format($totalVP3),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalCob3, 2).' %',1,0,'R',1);
		$pdf->Cell(25,10,number_format($totalVPP4),1,0,'C',1);
		$pdf->Cell(25,10,number_format($totalVP4),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalCob4, 2).' %',1,0,'R',1);
		$pdf->Cell(25,10,number_format($totalVPP5),1,0,'C',1);
		$pdf->Cell(25,10,number_format($totalVP5),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalCob5, 2).' %',1,0,'R',1);
		$pdf->Cell(25,10,number_format($planTotalNal),1,0,'C',1);
		$pdf->Cell(25,10,number_format($visTotalNal),1,0,'C',1);
		$pdf->Cell(50,10,number_format($cobTotalNal, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalMeds),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisUni),1,0,'C',1);
		$pdf->Cell(50,10,number_format($cobTotalUniNal, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($revisTotalNal),1,0,'C',1);
		$pdf->Cell(50,10,number_format($visTotalNal),1,0,'C',1);
		$pdf->Cell(50,10,number_format($cobTotalMedNal, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,$totalVisAcompDto,1,0,'C',1);
		$pdf->Cell(50,10,$totalVisAcompNac,1,0,'C',1);
		$pdf->Cell(50,10,$totalSumaVisAcomp,1,1,'C',1);
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
 #divListadoMedicos{	
	overflow:scroll;
	height:530px;
    width:1330px;
 }
</style>