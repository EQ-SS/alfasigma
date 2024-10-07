<?php
	/*** cobertura de medicos ***/
	include "../conexion.php";
			
	$ids = (substr($_POST['hdnIDS'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDS'], ",")) : $_POST['hdnIDS'] ;
	$tipo = $_POST['hdnTipoReporte'];
	$ciclo = $_POST['hdnCicloReporte'];
	
	$qMedicos = "DECLARE @CICLO as VARCHAR(36)
		DECLARE @DIAS_IN as DATE
		DECLARE @DIAS_FIN as DATE
		DECLARE @Dias_ciclo as FLOAT
		DECLARE @Cuota as FLOAT
		DECLARE @STATUS as VARCHAR(36)
		DECLARE @STATUS_INST as VARCHAR(36)
		DECLARE @Esp1 as VARCHAR(36)
		DECLARE @Esp2 as VARCHAR(36)
		DECLARE @Esp3 as VARCHAR(36)
		DECLARE @Esp4 as VARCHAR(36)
		DECLARE @Esp5 as VARCHAR(36)
		
		SET @CICLO = '".$ciclo."'
		/*SET @CICLO = (Select CYCLE_SNR from CYCLES where rec_stat=0 and NAME = '2023-02') */
		SET @DIAS_IN = (Select START_DATE from CYCLES where CYCLE_SNR = @CICLO)
		SET @DIAS_FIN = (Select FINISH_DATE from CYCLES where CYCLE_SNR = @CICLO)
		SET @Dias_ciclo = (Select cast(DAYS as int) from CYCLES where CYCLE_SNR = @CICLO)
		SET @Cuota = (Select cast(CONTACTS as int) from CYCLES where CYCLE_SNR = @CICLO)
		SET @STATUS = '19205DEC-F9F6-441A-9482-DB08D3394057'
		SET @STATUS_INST = 'C1141A15-E7AD-4099-A8D4-26C571298B21'
		SET @Esp1 = 'AB75797B-C553-4F7E-9666-3C946FD693A9' /*GERIATRIA*/
		SET @Esp2 = '53FF031D-6AA2-420E-A91C-08E0A1EC6AD6' /*MEDICINA GENERAL*/
		SET @Esp3 = '584B2C3E-41B6-4590-9357-B68FD6857F9A' /*MEDICINA INTERNA*/
		SET @Esp4 = '4DCB4BF8-B95A-4923-BF64-62EFAB13F6EA' /*NEUROLOGIA*/
		SET @Esp5 = 'ED8FC701-264F-4E91-BC99-1E11C4F218A7' /*PSIQUIATRIA*/
		
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
		
		'GERIATRIA' as Esp1,
		'MEDICINA GENERAL' as Esp2,
		'MEDICINA INTERNA' as Esp3,
		'NEUROLOGIA' as Esp4,
		'PSIQUIATRIA' as Esp5,
		
		(Select count(distinct PLW.pwork_snr) from perslocwork PLW, person P, pers_srep_work PSW
		where PLW.pwork_snr <> '00000000-0000-0000-0000-000000000000'
		and PLW.rec_stat = 0
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and P.status_snr = @STATUS
		and P.spec_snr = @Esp1
		and P.pers_snr = PLW.pers_snr
		and PLW.pwork_snr = PSW.pwork_snr
		and PSW.user_snr = MR.user_snr
		) as DR_NR_Esp1,
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS 
		and P.spec_snr = @Esp1
		and VP.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as VisTot_Esp1, 
		
		(Select count(distinct VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.spec_snr = @Esp1
		and VP.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as Vis_Esp1, 
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.spec_snr = @Esp1
		and VP.novis_snr = 'E3196F15-900A-4852-95B2-101A6EA2D748' /*FALLIDA*/
		) as Vis_Fallida_Esp1,
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.spec_snr = @Esp1
		and VP.visit_code_snr in ('036ED0CF-35F0-4F1A-9DF7-0E782B1C3D1F') /*ACCION COMPLEMENTARIA*/
		) as VisAcc_Complem_Esp1, 
		
		(Select count(distinct PLW.pwork_snr) from perslocwork PLW, person P, pers_srep_work PSW
		where PLW.pwork_snr <> '00000000-0000-0000-0000-000000000000'
		and PLW.rec_stat = 0
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and P.status_snr = @STATUS
		and P.spec_snr = @Esp2
		and P.pers_snr = PLW.pers_snr
		and PLW.pwork_snr = PSW.pwork_snr
		and PSW.user_snr = MR.user_snr
		) as DR_NR_Esp2,
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS 
		and P.spec_snr = @Esp2
		and VP.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as VisTot_Esp2, 
		
		(Select count(distinct VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.spec_snr = @Esp2
		and VP.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as Vis_Esp2, 
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.spec_snr = @Esp2
		and VP.novis_snr = 'E3196F15-900A-4852-95B2-101A6EA2D748' /*FALLIDA*/
		) as Vis_Fallida_Esp2,
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.spec_snr = @Esp2
		and VP.visit_code_snr in ('036ED0CF-35F0-4F1A-9DF7-0E782B1C3D1F') /*ACCION COMPLEMENTARIA*/
		) as VisAcc_Complem_Esp2, 
		
		(Select count(distinct PLW.pwork_snr) from perslocwork PLW, person P, pers_srep_work PSW
		where PLW.pwork_snr <> '00000000-0000-0000-0000-000000000000'
		and PLW.rec_stat = 0
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and P.status_snr = @STATUS
		and P.spec_snr = @Esp3
		and P.pers_snr = PLW.pers_snr
		and PLW.pwork_snr = PSW.pwork_snr
		and PSW.user_snr = MR.user_snr
		) as DR_NR_Esp3,
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS 
		and P.spec_snr = @Esp3
		and VP.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as VisTot_Esp3, 
		
		(Select count(distinct VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.spec_snr = @Esp3
		and VP.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as Vis_Esp3, 
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.spec_snr = @Esp3
		and VP.novis_snr = 'E3196F15-900A-4852-95B2-101A6EA2D748' /*FALLIDA*/
		) as Vis_Fallida_Esp3,
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.spec_snr = @Esp3
		and VP.visit_code_snr in ('036ED0CF-35F0-4F1A-9DF7-0E782B1C3D1F') /*ACCION COMPLEMENTARIA*/
		) as VisAcc_Complem_Esp3, 
		
		(Select count(distinct PLW.pwork_snr) from perslocwork PLW, person P, pers_srep_work PSW
		where PLW.pwork_snr <> '00000000-0000-0000-0000-000000000000'
		and PLW.rec_stat = 0
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and P.status_snr = @STATUS
		and P.spec_snr = @Esp4
		and P.pers_snr = PLW.pers_snr
		and PLW.pwork_snr = PSW.pwork_snr
		and PSW.user_snr = MR.user_snr
		) as DR_NR_Esp4,
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS 
		and P.spec_snr = @Esp4
		and VP.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as VisTot_Esp4, 
		
		(Select count(distinct VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.spec_snr = @Esp4
		and VP.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as Vis_Esp4, 
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.spec_snr = @Esp4
		and VP.novis_snr = 'E3196F15-900A-4852-95B2-101A6EA2D748' /*FALLIDA*/
		) as Vis_Fallida_Esp4,
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.spec_snr = @Esp4
		and VP.visit_code_snr in ('036ED0CF-35F0-4F1A-9DF7-0E782B1C3D1F') /*ACCION COMPLEMENTARIA*/
		) as VisAcc_Complem_Esp4, 
		
		(Select count(distinct PLW.pwork_snr) from perslocwork PLW, person P, pers_srep_work PSW
		where PLW.pwork_snr <> '00000000-0000-0000-0000-000000000000'
		and PLW.rec_stat = 0
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and P.status_snr = @STATUS
		and P.spec_snr = @Esp5
		and P.pers_snr = PLW.pers_snr
		and PLW.pwork_snr = PSW.pwork_snr
		and PSW.user_snr = MR.user_snr
		) as DR_NR_Esp5,
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS 
		and P.spec_snr = @Esp5
		and VP.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as VisTot_Esp5, 
		
		(Select count(distinct VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.spec_snr = @Esp5
		and VP.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as Vis_Esp5, 
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.spec_snr = @Esp5
		and VP.novis_snr = 'E3196F15-900A-4852-95B2-101A6EA2D748' /*FALLIDA*/
		) as Vis_Fallida_Esp5,
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and P.spec_snr = @Esp5
		and VP.visit_code_snr in ('036ED0CF-35F0-4F1A-9DF7-0E782B1C3D1F') /*ACCION COMPLEMENTARIA*/
		) as VisAcc_Complem_Esp5, 
		
		(Select count(distinct PLW.pwork_snr) from perslocwork PLW, person P, pers_srep_work PSW
		where PLW.pwork_snr <> '00000000-0000-0000-0000-000000000000'
		and PLW.rec_stat = 0
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and P.status_snr = @STATUS
		and P.pers_snr = PLW.pers_snr
		and PLW.pwork_snr = PSW.pwork_snr
		and PSW.user_snr = MR.user_snr
		) as DR_NR,
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS 
		and VP.visit_code_snr in ('146AA26A-502A-407A-A486-18470C9E7F23','2B3A7099-AC7D-47A3-A274-F0B029791801') /*CONTACTO VIRTUAL - PRESENCIAL*/
		) as VisTot, 
		
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
		and VP.novis_snr = 'E3196F15-900A-4852-95B2-101A6EA2D748' /*FALLIDA*/
		) as Vis_Fallida,
		
		(Select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS
		and VP.visit_code_snr in ('036ED0CF-35F0-4F1A-9DF7-0E782B1C3D1F') /*ACCION COMPLEMENTARIA*/
		) as VisAcc_Complem 
		
		
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
		header("Content-Disposition: filename=reporteCoberturaEspec.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else if($tipo == 3){
		ob_start();
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array(1300,2700));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('Reporte de Cobertura por Especialidad'));
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

	$tamTabla = 5400;
	$tabla = '';
	if( $tipo != 3){
			$tabla .= '<table border="0">
				<tr>
					<td>
						<table>
							<tr>
								<td colspan="10" class="nombreReporte">Reporte de Cobertura por Especialidad</td>
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
			$pdf->SetLineWidth(1);
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
	$totalMeds = 0;
	$totalVis = 0;
	$totalVisTot = 0;
	$totalRevis = 0;
	$totalVisFallida = 0;
	$totalAccionComp = 0;
	$totalAlcance = 0;
	$totalMedsEsp1 = 0;
	$totalVisEsp1 = 0;
	$totalVisTotEsp1 = 0;
	$totalRevisEsp1 = 0;
	$totalVisFallidaEsp1 = 0;
	$totalAccionCompEsp1 = 0;
	$totalAlcanceEsp1 = 0;
	$totalPartEsp1 = 0;
	$totalMedsEsp2 = 0;
	$totalVisEsp2 = 0;
	$totalVisTotEsp2 = 0;
	$totalRevisEsp2 = 0;
	$totalVisFallidaEsp2 = 0;
	$totalAccionCompEsp2 = 0;
	$totalAlcanceEsp2 = 0;
	$totalPartEsp2 = 0;
	$totalMedsEsp3 = 0;
	$totalVisEsp3 = 0;
	$totalVisTotEsp3 = 0;
	$totalRevisEsp3 = 0;
	$totalVisFallidaEsp3 = 0;
	$totalAccionCompEsp3 = 0;
	$totalAlcanceEsp3 = 0;
	$totalPartEsp3 = 0;
	$totalMedsEsp4 = 0;
	$totalVisEsp4 = 0;
	$totalVisTotEsp4 = 0;
	$totalRevisEsp4 = 0;
	$totalVisFallidaEsp4 = 0;
	$totalAccionCompEsp4 = 0;
	$totalAlcanceEsp4 = 0;
	$totalPartEsp4 = 0;
	$totalMedsEsp5 = 0;
	$totalVisEsp5 = 0;
	$totalVisTotEsp5 = 0;
	$totalRevisEsp5 = 0;
	$totalVisFallidaEsp5 = 0;
	$totalAccionCompEsp5 = 0;
	$totalAlcanceEsp5 = 0;
	$totalPartEsp5 = 0;

	while($reg = sqlsrv_fetch_array($rsMedicos)){
		////suma nacional
		$totalMeds += $reg['DR_NR'];
		$totalVis += $reg['One_Vis'];
		$totalVisTot += $reg['VisTot'];
		$totalRevis += $reg['VisTot'] - $reg['One_Vis'];
		$totalVisFallida += $reg['Vis_Fallida'];
		$totalAccionComp += $reg['VisAcc_Complem'];
		if ($totalMeds > 0 && $totalVis > 0 ){
			$totalAlcance = ($totalVis / $totalMeds) * 100;
		}else{ 
			$totalAlcance = 0;
		}
		$totalMedsEsp1 += $reg['DR_NR_Esp1'];
		$totalVisEsp1 += $reg['Vis_Esp1'];
		$totalVisTotEsp1 += $reg['VisTot_Esp1'];
		$totalRevisEsp1 += $reg['VisTot_Esp1'] - $reg['Vis_Esp1'];
		$totalVisFallidaEsp1 += $reg['Vis_Fallida_Esp1'];
		$totalAccionCompEsp1 += $reg['VisAcc_Complem_Esp1'];
		if ($totalMedsEsp1 > 0 && $totalVisEsp1 > 0 ){
			$totalAlcanceEsp1 = ($totalVisEsp1 / $totalMedsEsp1) * 100;
		}else{ 
			$totalAlcanceEsp1 = 0;
		}
		if ($totalMedsEsp1 > 0 && $totalMeds > 0 ){
			$totalPartEsp1 = ($totalMedsEsp1 / $totalMeds) * 100;
		}else{ 
			$totalPartEsp1 = 0;
		}
		$totalMedsEsp2 += $reg['DR_NR_Esp2'];
		$totalVisEsp2 += $reg['Vis_Esp2'];
		$totalVisTotEsp2 += $reg['VisTot_Esp2'];
		$totalRevisEsp2 += $reg['VisTot_Esp2'] - $reg['Vis_Esp2'];
		$totalVisFallidaEsp2 += $reg['Vis_Fallida_Esp2'];
		$totalAccionCompEsp2 += $reg['VisAcc_Complem_Esp2'];
		if ($totalMedsEsp2 > 0 && $totalVisEsp2 > 0 ){
			$totalAlcanceEsp2 = ($totalVisEsp2 / $totalMedsEsp2) * 100;
		}else{ 
			$totalAlcanceEsp2 = 0;
		}
		if ($totalMedsEsp2 > 0 && $totalMeds > 0 ){
			$totalPartEsp2 = ($totalMedsEsp2 / $totalMeds) * 100;
		}else{ 
			$totalPartEsp2 = 0;
		}
		$totalMedsEsp3 += $reg['DR_NR_Esp3'];
		$totalVisEsp3 += $reg['Vis_Esp3'];
		$totalVisTotEsp3 += $reg['VisTot_Esp3'];
		$totalRevisEsp3 += $reg['VisTot_Esp3'] - $reg['Vis_Esp3'];
		$totalVisFallidaEsp3 += $reg['Vis_Fallida_Esp3'];
		$totalAccionCompEsp3 += $reg['VisAcc_Complem_Esp3'];
		if ($totalMedsEsp3 > 0 && $totalVisEsp3 > 0 ){
			$totalAlcanceEsp3 = ($totalVisEsp3 / $totalMedsEsp3) * 100;
		}else{ 
			$totalAlcanceEsp3 = 0;
		}
		if ($totalMedsEsp3 > 0 && $totalMeds > 0 ){
			$totalPartEsp3 = ($totalMedsEsp3 / $totalMeds) * 100;
		}else{ 
			$totalPartEsp3 = 0;
		}
		$totalMedsEsp4 += $reg['DR_NR_Esp4'];
		$totalVisEsp4 += $reg['Vis_Esp4'];
		$totalVisTotEsp4 += $reg['VisTot_Esp4'];
		$totalRevisEsp4 += $reg['VisTot_Esp4'] - $reg['Vis_Esp4'];
		$totalVisFallidaEsp4 += $reg['Vis_Fallida_Esp4'];
		$totalAccionCompEsp4 += $reg['VisAcc_Complem_Esp4'];
		if ($totalMedsEsp4 > 0 && $totalVisEsp4 > 0 ){
			$totalAlcanceEsp4 = ($totalVisEsp4 / $totalMedsEsp4) * 100;
		}else{ 
			$totalAlcanceEsp4 = 0;
		}
		if ($totalMedsEsp4 > 0 && $totalMeds > 0 ){
			$totalPartEsp4 = ($totalMedsEsp4 / $totalMeds) * 100;
		}else{ 
			$totalPartEsp4 = 0;
		}
		$totalMedsEsp5 += $reg['DR_NR_Esp5'];
		$totalVisEsp5 += $reg['Vis_Esp5'];
		$totalVisTotEsp5 += $reg['VisTot_Esp5'];
		$totalRevisEsp5 += $reg['VisTot_Esp5'] - $reg['Vis_Esp5'];
		$totalVisFallidaEsp5 += $reg['Vis_Fallida_Esp5'];
		$totalAccionCompEsp5 += $reg['VisAcc_Complem_Esp5'];
		if ($totalMedsEsp5 > 0 && $totalVisEsp5 > 0 ){
			$totalAlcanceEsp5 = ($totalVisEsp5 / $totalMedsEsp5) * 100;
		}else{ 
			$totalAlcanceEsp5 = 0;
		}
		if ($totalMedsEsp5 > 0 && $totalMeds > 0 ){
			$totalPartEsp5 = ($totalMedsEsp5 / $totalMeds) * 100;
		}else{ 
			$totalPartEsp5 = 0;
		}
		
		if($i == 1){
			////imprimir encabezados
			if($tipo != 3){
				$tabla .= '<td '.$estilocabecera.' rowspan="2" width="100px">Linea</td>';
				$tabla .= '<td '.$estilocabecera.' rowspan="2" width="100px">Ruta</td>';
				$tabla .= '<td '.$estilocabecera.' rowspan="2" width="400px">Nombre</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="8" width="800px" align="center">'.$reg['Esp1'].'</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="8" width="800px" align="center">'.$reg['Esp2'].'</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="8" width="800px" align="center">'.$reg['Esp3'].'</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="8" width="800px" align="center">'.$reg['Esp4'].'</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="8" width="800px" align="center">'.$reg['Esp5'].'</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="7" width="700px" align="center">Total</td></tr>';
				$tabla .= '<tr><td '.$estilocabecera.' width="100px" align="center">Medicos</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Unica</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Revisita</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Total</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visita Fallida</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Accion Complemen</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Alcance</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Participacion</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Medicos</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Unica</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Revisita</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Total</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visita Fallida</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Accion Complemen</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Alcance</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Participacion</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Medicos</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Unica</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Revisita</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Total</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visita Fallida</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Accion Complemen</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Alcance</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Participacion</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Medicos</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Unica</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Revisita</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Total</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visita Fallida</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Accion Complemen</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Alcance</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Participacion</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Medicos</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Unica</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Revisita</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Total</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visita Fallida</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Accion Complemen</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Alcance</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Participacion</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Medicos</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Unica</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Revisita</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Total</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visita Fallida</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Accion Complemen</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Alcance</td>';
			}else{
				$pdf->Ln();
				$pdf->Cell(50,10,'','LRT',0,'L',1);
				$pdf->Cell(50,10,'','LRT',0,'L',1);
				$pdf->Cell(200,10,'','LRT',0,'L',1);
				$pdf->Cell(400,10,$reg['Esp1'],1,0,'C',1);
				$pdf->Cell(400,10,$reg['Esp2'],1,0,'C',1);
				$pdf->Cell(400,10,$reg['Esp3'],1,0,'C',1);
				$pdf->Cell(400,10,$reg['Esp4'],1,0,'C',1);
				$pdf->Cell(400,10,$reg['Esp5'],1,0,'C',1);
				$pdf->Cell(350,10,'Total',1,0,'C',1);
				$pdf->Ln();	
				$pdf->Cell(50,10,'Linea','LRB',0,'L',1);
				$pdf->Cell(50,10,'Ruta','LRB',0,'L',1);
				$pdf->Cell(200,10,'Nombre','LRB',0,'L',1);
				$pdf->Cell(50,10,'Medicos',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Unica',1,0,'C',1);
				$pdf->Cell(50,10,'Revisita',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Total',1,0,'C',1);
				$pdf->Cell(50,10,'Visita Fallida',1,0,'C',1);
				$pdf->Cell(50,10,'Accion Complemen',1,0,'C',1);
				$pdf->Cell(50,10,'Alcance',1,0,'C',1);
				$pdf->Cell(50,10,'% Participacion',1,0,'C',1);
				$pdf->Cell(50,10,'Medicos',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Unica',1,0,'C',1);
				$pdf->Cell(50,10,'Revisita',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Total',1,0,'C',1);
				$pdf->Cell(50,10,'Visita Fallida',1,0,'C',1);
				$pdf->Cell(50,10,'Accion Complemen',1,0,'C',1);
				$pdf->Cell(50,10,'Alcance',1,0,'C',1);
				$pdf->Cell(50,10,'% Participacion',1,0,'C',1);
				$pdf->Cell(50,10,'Medicos',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Unica',1,0,'C',1);
				$pdf->Cell(50,10,'Revisita',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Total',1,0,'C',1);
				$pdf->Cell(50,10,'Visita Fallida',1,0,'C',1);
				$pdf->Cell(50,10,'Accion Complemen',1,0,'C',1);
				$pdf->Cell(50,10,'Alcance',1,0,'C',1);
				$pdf->Cell(50,10,'% Participacion',1,0,'C',1);
				$pdf->Cell(50,10,'Medicos',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Unica',1,0,'C',1);
				$pdf->Cell(50,10,'Revisita',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Total',1,0,'C',1);
				$pdf->Cell(50,10,'Visita Fallida',1,0,'C',1);
				$pdf->Cell(50,10,'Accion Complemen',1,0,'C',1);
				$pdf->Cell(50,10,'Alcance',1,0,'C',1);
				$pdf->Cell(50,10,'% Participacion',1,0,'C',1);
				$pdf->Cell(50,10,'Medicos',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Unica',1,0,'C',1);
				$pdf->Cell(50,10,'Revisita',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Total',1,0,'C',1);
				$pdf->Cell(50,10,'Visita Fallida',1,0,'C',1);
				$pdf->Cell(50,10,'Accion Complemen',1,0,'C',1);
				$pdf->Cell(50,10,'Alcance',1,0,'C',1);
				$pdf->Cell(50,10,'% Participacion',1,0,'C',1);
				$pdf->Cell(50,10,'Medicos',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Unica',1,0,'C',1);
				$pdf->Cell(50,10,'Revisita',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Total',1,0,'C',1);
				$pdf->Cell(50,10,'Visita Fallida',1,0,'C',1);
				$pdf->Cell(50,10,'Accion Complemen',1,0,'C',1);
				$pdf->Cell(50,10,'Alcance',1,0,'C',1);
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
			$gteMeds = $reg['DR_NR'];
			$gteVis = $reg['One_Vis'];
			$gteVisTot = $reg['VisTot'];
			$gteRevis = $reg['VisTot'] - $reg['One_Vis'];
			$gteVisFallida = $reg['Vis_Fallida'];
			$gteAccionComp = $reg['VisAcc_Complem'];
			if ($reg['One_Vis'] > 0 && $reg['DR_NR'] > 0 ){
				$gteAlcance = ($reg['One_Vis'] / $reg['DR_NR']) * 100;
			}else{ 
				$gteAlcance = 0;
			}
			$gteMedsEsp1 = $reg['DR_NR_Esp1'];
			$gteVisEsp1 = $reg['Vis_Esp1'];
			$gteVisTotEsp1 = $reg['VisTot_Esp1'];
			$gteRevisEsp1 = $reg['VisTot_Esp1'] - $reg['Vis_Esp1'];
			$gteVisFallidaEsp1 = $reg['Vis_Fallida_Esp1'];
			$gteAccionCompEsp1 = $reg['VisAcc_Complem_Esp1'];
			if ($reg['Vis_Esp1'] > 0 && $reg['DR_NR_Esp1'] > 0 ){
				$gteAlcanceEsp1 = ($reg['Vis_Esp1'] / $reg['DR_NR_Esp1']) * 100;
			}else{ 
				$gteAlcanceEsp1 = 0;
			}
			$gtePartEsp1 = 0;
			$gteMedsEsp2 = $reg['DR_NR_Esp2'];
			$gteVisEsp2 = $reg['Vis_Esp2'];
			$gteVisTotEsp2 = $reg['VisTot_Esp2'];
			$gteRevisEsp2 = $reg['VisTot_Esp2'] - $reg['Vis_Esp2'];
			$gteVisFallidaEsp2 = $reg['Vis_Fallida_Esp2'];
			$gteAccionCompEsp2 = $reg['VisAcc_Complem_Esp2'];
			if ($reg['Vis_Esp2'] > 0 && $reg['DR_NR_Esp2'] > 0 ){
				$gteAlcanceEsp2 = ($reg['Vis_Esp2'] / $reg['DR_NR_Esp2']) * 100;
			}else{ 
				$gteAlcanceEsp2 = 0;
			}
			$gtePartEsp2 = 0;
			$gteMedsEsp3 = $reg['DR_NR_Esp3'];
			$gteVisEsp3 = $reg['Vis_Esp3'];
			$gteVisTotEsp3 = $reg['VisTot_Esp3'];
			$gteRevisEsp3 = $reg['VisTot_Esp3'] - $reg['Vis_Esp3'];
			$gteVisFallidaEsp3 = $reg['Vis_Fallida_Esp3'];
			$gteAccionCompEsp3 = $reg['VisAcc_Complem_Esp3'];
			if ($reg['Vis_Esp3'] > 0 && $reg['DR_NR_Esp3'] > 0 ){
				$gteAlcanceEsp3 = ($reg['Vis_Esp3'] / $reg['DR_NR_Esp3']) * 100;
			}else{ 
				$gteAlcanceEsp3 = 0;
			}
			$gtePartEsp3 = 0;
			$gteMedsEsp4 = $reg['DR_NR_Esp4'];
			$gteVisEsp4 = $reg['Vis_Esp4'];
			$gteVisTotEsp4 = $reg['VisTot_Esp4'];
			$gteRevisEsp4 = $reg['VisTot_Esp4'] - $reg['Vis_Esp4'];
			$gteVisFallidaEsp4 = $reg['Vis_Fallida_Esp4'];
			$gteAccionCompEsp4 = $reg['VisAcc_Complem_Esp4'];
			if ($reg['Vis_Esp4'] > 0 && $reg['DR_NR_Esp4'] > 0 ){
				$gteAlcanceEsp4 = ($reg['Vis_Esp4'] / $reg['DR_NR_Esp4']) * 100;
			}else{ 
				$gteAlcanceEsp4 = 0;
			}
			$gtePartEsp4 = 0;
			$gteMedsEsp5 = $reg['DR_NR_Esp5'];
			$gteVisEsp5 = $reg['Vis_Esp5'];
			$gteVisTotEsp5 = $reg['VisTot_Esp5'];
			$gteRevisEsp5 = $reg['VisTot_Esp5'] - $reg['Vis_Esp5'];
			$gteVisFallidaEsp5 = $reg['Vis_Fallida_Esp5'];
			$gteAccionCompEsp5 = $reg['VisAcc_Complem_Esp5'];
			if ($reg['Vis_Esp5'] > 0 && $reg['DR_NR_Esp5'] > 0 ){
				$gteAlcanceEsp5 = ($reg['Vis_Esp5'] / $reg['DR_NR_Esp5']) * 100;
			}else{ 
				$gteAlcanceEsp5 = 0;
			}
			$gtePartEsp5 = 0;
			
		}else{
			////sumas gerentes
			$gerente = $reg['REG_SNR'];
			if($tempGerente == $gerente){
				$sumMedsEsp = $reg['DR_NR'];
				$gteMeds += $sumMedsEsp;
				$sumVisEsp = $reg['One_Vis'];
				$gteVis += $sumVisEsp;
				$sumVisTotEsp = $reg['VisTot'];
				$gteVisTot += $sumVisTotEsp;
				$sumRevis = $reg['VisTot'] - $reg['One_Vis'];
				$gteRevis += $sumRevis;
				$sumVisFallidaEsp = $reg['Vis_Fallida'];
				$gteVisFallida += $sumVisFallidaEsp;
				$sumAccionCompEsp = $reg['VisAcc_Complem'];
				$gteAccionComp += $sumAccionCompEsp;
				if ($gteVis > 0 && $gteMeds > 0 ){
					$gteAlcance = ($gteVis / $gteMeds) * 100;
				}else{ 
					$gteAlcance = 0;
				}
				$sumMedsEsp1 = $reg['DR_NR_Esp1'];
				$gteMedsEsp1 += $sumMedsEsp1;
				$sumVisEsp1 = $reg['Vis_Esp1'];
				$gteVisEsp1 += $sumVisEsp1;
				$sumVisTotEsp1 = $reg['VisTot_Esp1'];
				$gteVisTotEsp1 += $sumVisTotEsp1;
				$sumRevisEsp1 = $reg['VisTot_Esp1'] - $reg['Vis_Esp1'];
				$gteRevisEsp1 += $sumRevisEsp1;
				$sumVisFallidaEsp1 = $reg['Vis_Fallida_Esp1'];
				$gteVisFallidaEsp1 += $sumVisFallidaEsp1;
				$sumAccionCompEsp1 = $reg['VisAcc_Complem_Esp1'];
				$gteAccionCompEsp1 += $sumAccionCompEsp1;
				if ($gteVisEsp1 > 0 && $gteMedsEsp1 > 0 ){
					$gteAlcanceEsp1 = ($gteVisEsp1 / $gteMedsEsp1) * 100;
				}else{ 
					$gteAlcanceEsp1 = 0;
				}
				if ($gteMedsEsp1 > 0 && $gteMeds > 0){
					$gtePartEsp1 = ($gteMedsEsp1 / $gteMeds) * 100;
				}else{
					$gtePartEsp1 = 0;
				}
				$sumMedsEsp2 = $reg['DR_NR_Esp2'];
				$gteMedsEsp2 += $sumMedsEsp2;
				$sumVisEsp2 = $reg['Vis_Esp2'];
				$gteVisEsp2 += $sumVisEsp2;
				$sumVisTotEsp2 = $reg['VisTot_Esp2'];
				$gteVisTotEsp2 += $sumVisTotEsp2;
				$sumRevisEsp2 = $reg['VisTot_Esp2'] - $reg['Vis_Esp2'];
				$gteRevisEsp2 += $sumRevisEsp2;
				$sumVisFallidaEsp2 = $reg['Vis_Fallida_Esp2'];
				$gteVisFallidaEsp2 += $sumVisFallidaEsp2;
				$sumAccionCompEsp2 = $reg['VisAcc_Complem_Esp2'];
				$gteAccionCompEsp2 += $sumAccionCompEsp2;
				if ($gteVisEsp2 > 0 && $gteMedsEsp2 > 0 ){
					$gteAlcanceEsp2 = ($gteVisEsp2 / $gteMedsEsp2) * 100;
				}else{ 
					$gteAlcanceEsp2 = 0;
				}
				if ($gteMedsEsp2 > 0 && $gteMeds > 0){
					$gtePartEsp2 = ($gteMedsEsp2 / $gteMeds) * 100;
				}else{
					$gtePartEsp2 = 0;
				}
				$sumMedsEsp3 = $reg['DR_NR_Esp3'];
				$gteMedsEsp3 += $sumMedsEsp3;
				$sumVisEsp3 = $reg['Vis_Esp3'];
				$gteVisEsp3 += $sumVisEsp3;
				$sumVisTotEsp3 = $reg['VisTot_Esp3'];
				$gteVisTotEsp3 += $sumVisTotEsp3;
				$sumRevisEsp3 = $reg['VisTot_Esp3'] - $reg['Vis_Esp3'];
				$gteRevisEsp3 += $sumRevisEsp3;
				$sumVisFallidaEsp3 = $reg['Vis_Fallida_Esp3'];
				$gteVisFallidaEsp3 += $sumVisFallidaEsp3;
				$sumAccionCompEsp3 = $reg['VisAcc_Complem_Esp3'];
				$gteAccionCompEsp3 += $sumAccionCompEsp3;
				if ($gteVisEsp3 > 0 && $gteMedsEsp3 > 0 ){
					$gteAlcanceEsp3 = ($gteVisEsp3 / $gteMedsEsp3) * 100;
				}else{ 
					$gteAlcanceEsp3 = 0;
				}
				if ($gteMedsEsp3 > 0 && $gteMeds > 0){
					$gtePartEsp3 = ($gteMedsEsp3 / $gteMeds) * 100;
				}else{
					$gtePartEsp3 = 0;
				}
				$sumMedsEsp4 = $reg['DR_NR_Esp4'];
				$gteMedsEsp4 += $sumMedsEsp4;
				$sumVisEsp4 = $reg['Vis_Esp4'];
				$gteVisEsp4 += $sumVisEsp4;
				$sumVisTotEsp4 = $reg['VisTot_Esp4'];
				$gteVisTotEsp4 += $sumVisTotEsp4;
				$sumRevisEsp4 = $reg['VisTot_Esp4'] - $reg['Vis_Esp4'];
				$gteRevisEsp4 += $sumRevisEsp4;
				$sumVisFallidaEsp4 = $reg['Vis_Fallida_Esp4'];
				$gteVisFallidaEsp4 += $sumVisFallidaEsp4;
				$sumAccionCompEsp4 = $reg['VisAcc_Complem_Esp4'];
				$gteAccionCompEsp4 += $sumAccionCompEsp4;
				if ($gteVisEsp4 > 0 && $gteMedsEsp4 > 0 ){
					$gteAlcanceEsp4 = ($gteVisEsp4 / $gteMedsEsp4) * 100;
				}else{ 
					$gteAlcanceEsp4 = 0;
				}
				if ($gteMedsEsp4 > 0 && $gteMeds > 0){
					$gtePartEsp4 = ($gteMedsEsp4 / $gteMeds) * 100;
				}else{
					$gtePartEsp4 = 0;
				}
				$sumMedsEsp5 = $reg['DR_NR_Esp5'];
				$gteMedsEsp5 += $sumMedsEsp5;
				$sumVisEsp5 = $reg['Vis_Esp5'];
				$gteVisEsp5 += $sumVisEsp5;
				$sumVisTotEsp5 = $reg['VisTot_Esp5'];
				$gteVisTotEsp5 += $sumVisTotEsp5;
				$sumRevisEsp5 = $reg['VisTot_Esp5'] - $reg['Vis_Esp5'];
				$gteRevisEsp5 += $sumRevisEsp5;
				$sumVisFallidaEsp5 = $reg['Vis_Fallida_Esp5'];
				$gteVisFallidaEsp5 += $sumVisFallidaEsp5;
				$sumAccionCompEsp5 = $reg['VisAcc_Complem_Esp5'];
				$gteAccionCompEsp5 += $sumAccionCompEsp5;
				if ($gteVisEsp5 > 0 && $gteMedsEsp5 > 0 ){
					$gteAlcanceEsp5 = ($gteVisEsp5 / $gteMedsEsp5) * 100;
				}else{ 
					$gteAlcanceEsp5 = 0;
				}
				if ($gteMedsEsp5 > 0 && $gteMeds > 0){
					$gtePartEsp5 = ($gteMedsEsp5 / $gteMeds) * 100;
				}else{
					$gtePartEsp5 = 0;
				}
				
			}else{	
				////imprimir gerentes
				if($tipo != 3){				
					$tabla .= '<tr><td '.$estilogte.' width="100px"> </td>';
					$tabla .= '<td '.$estilogte.' width="100px">'.$rutaGte.'</td>';	
					$tabla .= '<td '.$estilogte.' width="400px">'.$nombreGte.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteMedsEsp1.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisEsp1.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevisEsp1.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisTotEsp1.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallidaEsp1.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionCompEsp1.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcanceEsp1,2).'%</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePartEsp1, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteMedsEsp2.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisEsp2.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevisEsp2.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisTotEsp2.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallidaEsp2.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionCompEsp2.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcanceEsp2,2).'%</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePartEsp2, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteMedsEsp3.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisEsp3.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevisEsp3.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisTotEsp3.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallidaEsp3.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionCompEsp3.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcanceEsp3,2).'%</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePartEsp3, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteMedsEsp4.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisEsp4.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevisEsp4.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisTotEsp4.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallidaEsp4.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionCompEsp4.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcanceEsp4,2).'%</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePartEsp4, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteMedsEsp5.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisEsp5.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevisEsp5.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisTotEsp5.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallidaEsp5.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionCompEsp5.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcanceEsp5,2).'%</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePartEsp5, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteMeds).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVis).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevis.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisTot).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallida.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionComp.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcance,2).'%</td>';
					$tabla .= '</tr>';
				}else{ 
					$pdf->Cell(50,10,'',1,0,'L',1);
					$pdf->Cell(50,10,$rutaGte,1,0,'L',1);
					$pdf->Cell(200,10,$nombreGte,1,0,'L',1);
					$pdf->Cell(50,10,$gteMedsEsp1,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisEsp1,1,0,'C',1);
					$pdf->Cell(50,10,$gteRevisEsp1,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisTotEsp1,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisFallidaEsp1,1,0,'C',1);
					$pdf->Cell(50,10,$gteAccionCompEsp1,1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteAlcanceEsp1, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtePartEsp1, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,$gteMedsEsp2,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisEsp2,1,0,'C',1);
					$pdf->Cell(50,10,$gteRevisEsp2,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisTotEsp2,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisFallidaEsp2,1,0,'C',1);
					$pdf->Cell(50,10,$gteAccionCompEsp2,1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteAlcanceEsp2, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtePartEsp2, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,$gteMedsEsp3,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisEsp3,1,0,'C',1);
					$pdf->Cell(50,10,$gteRevisEsp3,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisTotEsp3,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisFallidaEsp3,1,0,'C',1);
					$pdf->Cell(50,10,$gteAccionCompEsp3,1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteAlcanceEsp3, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtePartEsp3, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,$gteMedsEsp4,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisEsp4,1,0,'C',1);
					$pdf->Cell(50,10,$gteRevisEsp4,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisTotEsp4,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisFallidaEsp4,1,0,'C',1);
					$pdf->Cell(50,10,$gteAccionCompEsp4,1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteAlcanceEsp4, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtePartEsp4, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,$gteMedsEsp5,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisEsp5,1,0,'C',1);
					$pdf->Cell(50,10,$gteRevisEsp5,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisTotEsp5,1,0,'C',1);
					$pdf->Cell(50,10,$gteVisFallidaEsp5,1,0,'C',1);
					$pdf->Cell(50,10,$gteAccionCompEsp5,1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteAlcanceEsp5, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtePartEsp5, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gteMeds),1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteVis),1,0,'C',1);
					$pdf->Cell(50,10,$gteRevis,1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteVisTot),1,0,'C',1);
					$pdf->Cell(50,10,$gteVisFallida,1,0,'C',1);
					$pdf->Cell(50,10,$gteAccionComp,1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteAlcance, 2).' %',1,1,'R',1);
				}
	
				////inicia var gerente
				$tempGerente = $reg['REG_SNR'];
				$nombreGte = $reg['RM'];
				$rutaGte = $reg['Ruta_Gte'];
				$gteMeds = $reg['DR_NR'];
				$gteVis = $reg['One_Vis'];
				$gteVisTot = $reg['VisTot'];
				$gteRevis = $reg['VisTot'] - $reg['One_Vis'];
				$gteVisFallida = $reg['Vis_Fallida'];
				$gteAccionComp = $reg['VisAcc_Complem'];
				if ($reg['DR_NR'] > 0 && $reg['One_Vis'] > 0) {
					$gteAlcance = ($reg['One_Vis'] / $reg['DR_NR']) * 100;
				}else{ 
					$gteAlcance = 0;
				}
				$gteMedsEsp1 = $reg['DR_NR_Esp1'];
				$gteVisEsp1 = $reg['Vis_Esp1'];
				$gteVisTotEsp1 = $reg['VisTot_Esp1'];
				$gteRevisEsp1 = $reg['VisTot_Esp1'] - $reg['Vis_Esp1'];
				$gteVisFallidaEsp1 = $reg['Vis_Fallida_Esp1'];
				$gteAccionCompEsp1 = $reg['VisAcc_Complem_Esp1'];
				if ($reg['DR_NR_Esp1'] > 0 && $reg['Vis_Esp1'] > 0) {
					$gteAlcanceEsp1 = ($reg['Vis_Esp1'] / $reg['DR_NR_Esp1']) * 100;
				}else{ 
					$gteAlcanceEsp1 = 0;
				}
				$gtePartEsp1 = 0;
				$gteMedsEsp2 = $reg['DR_NR_Esp2'];
				$gteVisEsp2 = $reg['Vis_Esp2'];
				$gteVisTotEsp2 = $reg['VisTot_Esp2'];
				$gteRevisEsp2 = $reg['VisTot_Esp2'] - $reg['Vis_Esp2'];
				$gteVisFallidaEsp2 = $reg['Vis_Fallida_Esp2'];
				$gteAccionCompEsp2 = $reg['VisAcc_Complem_Esp2'];
				if ($reg['DR_NR_Esp2'] > 0 && $reg['Vis_Esp2'] > 0) {
					$gteAlcanceEsp2 = ($reg['Vis_Esp2'] / $reg['DR_NR_Esp2']) * 100;
				}else{ 
					$gteAlcanceEsp2 = 0;
				}
				$gtePartEsp2 = 0;
				$gteMedsEsp3 = $reg['DR_NR_Esp3'];
				$gteVisEsp3 = $reg['Vis_Esp3'];
				$gteVisTotEsp3 = $reg['VisTot_Esp3'];
				$gteRevisEsp3 = $reg['VisTot_Esp3'] - $reg['Vis_Esp3'];
				$gteVisFallidaEsp3 = $reg['Vis_Fallida_Esp3'];
				$gteAccionCompEsp3 = $reg['VisAcc_Complem_Esp3'];
				if ($reg['DR_NR_Esp3'] > 0 && $reg['Vis_Esp3'] > 0) {
					$gteAlcanceEsp3 = ($reg['Vis_Esp3'] / $reg['DR_NR_Esp3']) * 100;
				}else{ 
					$gteAlcanceEsp3 = 0;
				}
				$gtePartEsp3 = 0;
				$gteMedsEsp4 = $reg['DR_NR_Esp4'];
				$gteVisEsp4 = $reg['Vis_Esp4'];
				$gteVisTotEsp4 = $reg['VisTot_Esp4'];
				$gteRevisEsp4 = $reg['VisTot_Esp4'] - $reg['Vis_Esp4'];
				$gteVisFallidaEsp4 = $reg['Vis_Fallida_Esp4'];
				$gteAccionCompEsp4 = $reg['VisAcc_Complem_Esp4'];
				if ($reg['DR_NR_Esp4'] > 0 && $reg['Vis_Esp4'] > 0) {
					$gteAlcanceEsp4 = ($reg['Vis_Esp4'] / $reg['DR_NR_Esp4']) * 100;
				}else{ 
					$gteAlcanceEsp4 = 0;
				}
				$gtePartEsp4 = 0;
				$gteMedsEsp5 = $reg['DR_NR_Esp5'];
				$gteVisEsp5 = $reg['Vis_Esp5'];
				$gteVisTotEsp5 = $reg['VisTot_Esp5'];
				$gteRevisEsp5 = $reg['VisTot_Esp5'] - $reg['Vis_Esp5'];
				$gteVisFallidaEsp5 = $reg['Vis_Fallida_Esp5'];
				$gteAccionCompEsp5 = $reg['VisAcc_Complem_Esp5'];
				if ($reg['DR_NR_Esp5'] > 0 && $reg['Vis_Esp5'] > 0) {
					$gteAlcanceEsp5 = ($reg['Vis_Esp5'] / $reg['DR_NR_Esp5']) * 100;
				}else{ 
					$gteAlcanceEsp5 = 0;
				}
				$gtePartEsp5 = 0;
			}
		}
		
		////formulas repres
		$tabla .= '<tr>';
		$Revis = $reg['VisTot'] - $reg['One_Vis'];
		$RevisEsp1 = $reg['VisTot_Esp1'] - $reg['Vis_Esp1'];
		$RevisEsp2 = $reg['VisTot_Esp2'] - $reg['Vis_Esp2'];
		$RevisEsp3 = $reg['VisTot_Esp3'] - $reg['Vis_Esp3'];
		$RevisEsp4 = $reg['VisTot_Esp4'] - $reg['Vis_Esp4'];
		$RevisEsp5 = $reg['VisTot_Esp5'] - $reg['Vis_Esp5'];
		if ($reg['DR_NR'] > 0 && $reg['One_Vis'] > 0) {
			$Alcance = ($reg['One_Vis'] / $reg['DR_NR']) * 100;
		}else{ 
			$Alcance = 0;
		}
		if ($reg['DR_NR_Esp1'] > 0 && $reg['Vis_Esp1'] > 0) {
			$AlcanceEsp1 = ($reg['Vis_Esp1'] / $reg['DR_NR_Esp1']) * 100;
		}else{ 
			$AlcanceEsp1 = 0;
		}
		if ($reg['DR_NR_Esp1'] > 0 && $reg['DR_NR'] > 0){
			$PartEsp1 = ($reg['DR_NR_Esp1'] / $reg['DR_NR']) * 100;
		}else{
			$PartEsp1 = 0;
		}
		if ($reg['DR_NR_Esp2'] > 0 && $reg['Vis_Esp2'] > 0) {
			$AlcanceEsp2 = ($reg['Vis_Esp2'] / $reg['DR_NR_Esp2']) * 100;
		}else{ 
			$AlcanceEsp2 = 0;
		}
		if ($reg['DR_NR_Esp2'] > 0 && $reg['DR_NR'] > 0){
			$PartEsp2 = ($reg['DR_NR_Esp2'] / $reg['DR_NR']) * 100;
		}else{
			$PartEsp2 = 0;
		}
		if ($reg['DR_NR_Esp3'] > 0 && $reg['Vis_Esp3'] > 0) {
			$AlcanceEsp3 = ($reg['Vis_Esp3'] / $reg['DR_NR_Esp3']) * 100;
		}else{ 
			$AlcanceEsp3 = 0;
		}
		if ($reg['DR_NR_Esp3'] > 0 && $reg['DR_NR'] > 0){
			$PartEsp3 = ($reg['DR_NR_Esp3'] / $reg['DR_NR']) * 100;
		}else{
			$PartEsp3 = 0;
		}
		if ($reg['DR_NR_Esp4'] > 0 && $reg['Vis_Esp4'] > 0) {
			$AlcanceEsp4 = ($reg['Vis_Esp4'] / $reg['DR_NR_Esp4']) * 100;
		}else{ 
			$AlcanceEsp4 = 0;
		}
		if ($reg['DR_NR_Esp4'] > 0 && $reg['DR_NR'] > 0){
			$PartEsp4 = ($reg['DR_NR_Esp4'] / $reg['DR_NR']) * 100;
		}else{
			$PartEsp4 = 0;
		}
		if ($reg['DR_NR_Esp5'] > 0 && $reg['Vis_Esp5'] > 0) {
			$AlcanceEsp5 = ($reg['Vis_Esp5'] / $reg['DR_NR_Esp5']) * 100;
		}else{ 
			$AlcanceEsp5 = 0;
		}
		if ($reg['DR_NR_Esp5'] > 0 && $reg['DR_NR'] > 0){
			$PartEsp5 = ($reg['DR_NR_Esp5'] / $reg['DR_NR']) * 100;
		}else{
			$PartEsp5 = 0;
		}
		
		////imprimir repres
		if($tipo != 3){
			$tabla .= '<td '.$estilorepre.' width="100px">'.$reg['Linea'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px">'.$reg['Ruta'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="400px">'.$reg['SR'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['DR_NR_Esp1'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Esp1'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$RevisEsp1.'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisTot_Esp1'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Fallida_Esp1'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisAcc_Complem_Esp1'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($AlcanceEsp1, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($PartEsp1, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['DR_NR_Esp2'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Esp2'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$RevisEsp2.'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisTot_Esp2'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Fallida_Esp2'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisAcc_Complem_Esp2'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($AlcanceEsp2, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($PartEsp2, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['DR_NR_Esp3'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Esp3'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$RevisEsp3.'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisTot_Esp3'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Fallida_Esp3'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisAcc_Complem_Esp3'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($AlcanceEsp3, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($PartEsp3, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['DR_NR_Esp4'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Esp4'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$RevisEsp4.'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisTot_Esp4'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Fallida_Esp4'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisAcc_Complem_Esp4'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($AlcanceEsp4, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($PartEsp4, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['DR_NR_Esp5'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Esp5'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$RevisEsp5.'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisTot_Esp5'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Fallida_Esp5'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisAcc_Complem_Esp5'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($AlcanceEsp5, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($PartEsp5, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['DR_NR'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['One_Vis'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$Revis.'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisTot'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Fallida'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisAcc_Complem'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($Alcance, 2).' %</td>';
			$tabla .= '</tr>';
		}else{
			$pdf->Cell(50,10,$reg['Linea'],1,0,'L',0);
			$pdf->Cell(50,10,$reg['Ruta'],1,0,'L',0);
			$pdf->Cell(200,10,$reg['SR'],1,0,'L',0);
			$pdf->Cell(50,10,$reg['DR_NR_Esp1'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Esp1'],1,0,'C',0);
			$pdf->Cell(50,10,$RevisEsp1,1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisTot_Esp1'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Fallida_Esp1'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisAcc_Complem_Esp1'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($AlcanceEsp1, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($PartEsp1, 2),1,0,'R',0);
			$pdf->Cell(50,10,$reg['DR_NR_Esp2'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Esp2'],1,0,'C',0);
			$pdf->Cell(50,10,$RevisEsp2,1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisTot_Esp2'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Fallida_Esp2'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisAcc_Complem_Esp2'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($AlcanceEsp2, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($PartEsp2, 2),1,0,'R',0);
			$pdf->Cell(50,10,$reg['DR_NR_Esp3'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Esp3'],1,0,'C',0);
			$pdf->Cell(50,10,$RevisEsp3,1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisTot_Esp3'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Fallida_Esp3'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisAcc_Complem_Esp3'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($AlcanceEsp3, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($PartEsp3, 2),1,0,'R',0);
			$pdf->Cell(50,10,$reg['DR_NR_Esp4'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Esp4'],1,0,'C',0);
			$pdf->Cell(50,10,$RevisEsp4,1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisTot_Esp4'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Fallida_Esp4'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisAcc_Complem_Esp4'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($AlcanceEsp4, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($PartEsp4, 2),1,0,'R',0);
			$pdf->Cell(50,10,$reg['DR_NR_Esp5'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Esp5'],1,0,'C',0);
			$pdf->Cell(50,10,$RevisEsp5,1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisTot_Esp5'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Fallida_Esp5'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisAcc_Complem_Esp5'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($AlcanceEsp5, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($PartEsp5, 2),1,0,'R',0);
			$pdf->Cell(50,10,$reg['DR_NR'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['One_Vis'],1,0,'C',0);
			$pdf->Cell(50,10,$Revis,1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisTot'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Fallida'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisAcc_Complem'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($Alcance, 2).' %',1,1,'R',0);
		}
		$i++;
	}
	
	////imprimir ultimo gerente
	if($tipo != 3){
		$tabla .= '<tr><td '.$estilogte.' width="100px"></td>';
		$tabla .= '<td '.$estilogte.' width="100px">'.$rutaGte.'</td>';
		$tabla .= '<td '.$estilogte.' width="400px">'.$nombreGte.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteMedsEsp1.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisEsp1.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevisEsp1.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisTotEsp1.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallidaEsp1.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionCompEsp1.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcanceEsp1, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePartEsp1, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteMedsEsp2.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisEsp2.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevisEsp2.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisTotEsp2.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallidaEsp2.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionCompEsp2.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcanceEsp2, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePartEsp2, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteMedsEsp3.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisEsp3.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevisEsp3.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisTotEsp3.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallidaEsp3.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionCompEsp3.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcanceEsp3, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePartEsp3, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteMedsEsp4.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisEsp4.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevisEsp4.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisTotEsp4.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallidaEsp4.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionCompEsp4.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcanceEsp4, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePartEsp4, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteMedsEsp5.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisEsp5.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevisEsp5.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisTotEsp5.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallidaEsp5.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionCompEsp5.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcanceEsp5, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePartEsp5, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteMeds).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVis).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevis.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisTot).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallida.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionComp.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcance, 2).' %</td>';
		$tabla .= '</tr>';
	}else{ 
		$pdf->Cell(50,10,'',1,0,'L',1);
		$pdf->Cell(50,10,$rutaGte,1,0,'L',1);
		$pdf->Cell(200,10,$nombreGte,1,0,'L',1);
		$pdf->Cell(50,10,$gteMedsEsp1,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisEsp1,1,0,'C',1);
		$pdf->Cell(50,10,$gteRevisEsp1,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisTotEsp1,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisFallidaEsp1,1,0,'C',1);
		$pdf->Cell(50,10,$gteAccionCompEsp1,1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteAlcanceEsp1, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtePartEsp1, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,$gteMedsEsp2,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisEsp2,1,0,'C',1);
		$pdf->Cell(50,10,$gteRevisEsp2,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisTotEsp2,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisFallidaEsp2,1,0,'C',1);
		$pdf->Cell(50,10,$gteAccionCompEsp2,1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteAlcanceEsp2, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtePartEsp2, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,$gteMedsEsp3,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisEsp3,1,0,'C',1);
		$pdf->Cell(50,10,$gteRevisEsp3,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisTotEsp3,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisFallidaEsp3,1,0,'C',1);
		$pdf->Cell(50,10,$gteAccionCompEsp3,1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteAlcanceEsp3, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtePartEsp3, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,$gteMedsEsp4,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisEsp4,1,0,'C',1);
		$pdf->Cell(50,10,$gteRevisEsp4,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisTotEsp4,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisFallidaEsp4,1,0,'C',1);
		$pdf->Cell(50,10,$gteAccionCompEsp4,1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteAlcanceEsp4, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtePartEsp4, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,$gteMedsEsp5,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisEsp5,1,0,'C',1);
		$pdf->Cell(50,10,$gteRevisEsp5,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisTotEsp5,1,0,'C',1);
		$pdf->Cell(50,10,$gteVisFallidaEsp5,1,0,'C',1);
		$pdf->Cell(50,10,$gteAccionCompEsp5,1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteAlcanceEsp5, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtePartEsp5, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gteMeds),1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteVis),1,0,'C',1);
		$pdf->Cell(50,10,$gteRevis,1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteVisTot),1,0,'C',1);
		$pdf->Cell(50,10,$gteVisFallida,1,0,'C',1);
		$pdf->Cell(50,10,$gteAccionComp,1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteAlcance, 2).' %',1,1,'R',1);
	}	

	////imprimir nacional
	if($tipo != 3){
		$tabla .= '<tr><td '.$estilogte.' width="100px"> </td>';
		$tabla .= '<td '.$estilogte.' width="100px"> </td>';
		$tabla .= '<td '.$estilogte.' width="400px">Total General</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalMedsEsp1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEsp1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalRevisEsp1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisTotEsp1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFallidaEsp1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalAccionCompEsp1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalAlcanceEsp1, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalPartEsp1, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalMedsEsp2).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEsp2).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalRevisEsp2).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisTotEsp2).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFallidaEsp2).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalAccionCompEsp2).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalAlcanceEsp2, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalPartEsp2, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalMedsEsp3).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEsp3).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalRevisEsp3).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisTotEsp3).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFallidaEsp3).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalAccionCompEsp3).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalAlcanceEsp3, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalPartEsp3, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalMedsEsp4).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEsp4).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalRevisEsp4).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisTotEsp4).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFallidaEsp4).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalAccionCompEsp4).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalAlcanceEsp4, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalPartEsp4, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalMedsEsp5).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEsp5).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalRevisEsp5).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisTotEsp5).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFallidaEsp5).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalAccionCompEsp5).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalAlcanceEsp5, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalPartEsp5, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalMeds).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVis).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalRevis).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisTot).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFallida).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalAccionComp).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalAlcance, 2).' %</td>';
		$tabla .= '</tr>';
	}else{ 
		$pdf->Cell(50,10,'',1,0,'L',1);
		$pdf->Cell(50,10,'',1,0,'L',1);
		$pdf->Cell(200,10,'Total General',1,0,'L',1);
		$pdf->Cell(50,10,number_format($totalMedsEsp1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisEsp1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalRevisEsp1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisTotEsp1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisFallidaEsp1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAccionCompEsp1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAlcanceEsp1, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalPartEsp1, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalMedsEsp2),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisEsp2),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalRevisEsp2),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisTotEsp2),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisFallidaEsp2),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAccionCompEsp2),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAlcanceEsp2, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalPartEsp2, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalMedsEsp3),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisEsp3),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalRevisEsp3),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisTotEsp3),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisFallidaEsp3),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAccionCompEsp3),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAlcanceEsp3, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalPartEsp3, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalMedsEsp4),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisEsp4),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalRevisEsp4),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisTotEsp4),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisFallidaEsp4),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAccionCompEsp4),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAlcanceEsp4, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalPartEsp4, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalMedsEsp5),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisEsp5),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalRevisEsp5),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisTotEsp5),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisFallidaEsp5),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAccionCompEsp5),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAlcanceEsp5, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalPartEsp5, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalMeds),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVis),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalRevis),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisTot),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisFallida),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAccionComp),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAlcance, 2).' %',1,1,'R',1);
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