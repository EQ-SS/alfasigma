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
		
		SET @CICLO = '".$ciclo."'
		/*SET @CICLO = (Select CYCLE_SNR from CYCLES where rec_stat=0 and name = '2023-03') */
		SET @DIAS_IN = (Select START_DATE from CYCLES where CYCLE_SNR = @CICLO)
		SET @DIAS_FIN = (Select FINISH_DATE from CYCLES where CYCLE_SNR = @CICLO)
		SET @FECHA_ACT = (case when cast(getdate() as date) <= @DIAS_FIN then cast(getdate() as date) else @DIAS_FIN end)
		
		;with FECHAS (fecha, num_fecha) as (
		select  DATEADD(DAY, nbr - 1, @DIAS_IN) fecha, ROW_NUMBER() OVER (ORDER BY DATEADD(DAY, nbr - 1, @DIAS_IN)) num_fecha
		from    ( select ROW_NUMBER() OVER (ORDER BY c.object_id) as Nbr
				  from  sys.columns c) nbrs
		where   nbr - 1 <= DATEDIFF(DAY, @DIAS_IN, @FECHA_ACT)
		and datepart(DW,DATEADD(DAY, nbr - 1, @DIAS_IN)) not in (1,7)
		and DATEADD(DAY, nbr - 1, @DIAS_IN) not in (select c_date from CYCLE_DETAILS where c_date between @DIAS_IN and @FECHA_ACT and rec_stat=0 and c_day=0)
		)
		
		select 
		LINEA.name as LINEA,
		klr.REG_SNR,
		DM.USER_NR as Gte_ruta,
		DM.lname + ' ' + DM.fname as RM,
		MR.USER_NR as Ruta,
		MR.lname + ' ' + MR.fname as SR,
		(select count(fecha) from FECHAS) as NDiasTransc,
		
		/* DESPLEGADO DE FECHAS POR DIA */
		(case when @FECHA_ACT >= F1.FECHA then cast(F1.FECHA as char(10)) else 'NO' end) as DIA_1,
		(case when @FECHA_ACT >= F2.FECHA then cast(F2.FECHA as char(10)) else 'NO' end) as DIA_2,
		(case when @FECHA_ACT >= F3.FECHA then cast(F3.FECHA as char(10)) else 'NO' end) as DIA_3,
		(case when @FECHA_ACT >= F4.FECHA then cast(F4.FECHA as char(10)) else 'NO' end) as DIA_4,
		(case when @FECHA_ACT >= F5.FECHA then cast(F5.FECHA as char(10)) else 'NO' end) as DIA_5,
		(case when @FECHA_ACT >= F6.FECHA then cast(F6.FECHA as char(10)) else 'NO' end) as DIA_6,
		(case when @FECHA_ACT >= F7.FECHA then cast(F7.FECHA as char(10)) else 'NO' end) as DIA_7,
		(case when @FECHA_ACT >= F8.FECHA then cast(F8.FECHA as char(10)) else 'NO' end) as DIA_8,
		(case when @FECHA_ACT >= F9.FECHA then cast(F9.FECHA as char(10)) else 'NO' end) as DIA_9,
		(case when @FECHA_ACT >= F10.FECHA then cast(F10.FECHA as char(10)) else 'NO' end) as DIA_10,
		(case when @FECHA_ACT >= F11.FECHA then cast(F11.FECHA as char(10)) else 'NO' end) as DIA_11,
		(case when @FECHA_ACT >= F12.FECHA then cast(F12.FECHA as char(10)) else 'NO' end) as DIA_12,
		(case when @FECHA_ACT >= F13.FECHA then cast(F13.FECHA as char(10)) else 'NO' end) as DIA_13,
		(case when @FECHA_ACT >= F14.FECHA then cast(F14.FECHA as char(10)) else 'NO' end) as DIA_14,
		(case when @FECHA_ACT >= F15.FECHA then cast(F15.FECHA as char(10)) else 'NO' end) as DIA_15,
		(case when @FECHA_ACT >= F16.FECHA then cast(F16.FECHA as char(10)) else 'NO' end) as DIA_16,
		(case when @FECHA_ACT >= F17.FECHA then cast(F17.FECHA as char(10)) else 'NO' end) as DIA_17,
		(case when @FECHA_ACT >= F18.FECHA then cast(F18.FECHA as char(10)) else 'NO' end) as DIA_18,
		(case when @FECHA_ACT >= F19.FECHA then cast(F19.FECHA as char(10)) else 'NO' end) as DIA_19,
		(case when @FECHA_ACT >= F20.FECHA then cast(F20.FECHA as char(10)) else 'NO' end) as DIA_20,
		(case when @FECHA_ACT >= F21.FECHA then cast(F21.FECHA as char(10)) else 'NO' end) as DIA_21,
		(case when @FECHA_ACT >= F22.FECHA then cast(F22.FECHA as char(10)) else 'NO' end) as DIA_22,
		(case when @FECHA_ACT >= F23.FECHA then cast(F23.FECHA as char(10)) else 'NO' end) as DIA_23,
		(case when @FECHA_ACT >= F24.FECHA then cast(F24.FECHA as char(10)) else 'NO' end) as DIA_24,
		(case when @FECHA_ACT >= F25.FECHA then cast(F25.FECHA as char(10)) else 'NO' end) as DIA_25,
		(case when @FECHA_ACT >= F26.FECHA then cast(F26.FECHA as char(10)) else 'NO' end) as DIA_26,
		(case when @FECHA_ACT >= F27.FECHA then cast(F27.FECHA as char(10)) else 'NO' end) as DIA_27,
		(case when @FECHA_ACT >= F28.FECHA then cast(F28.FECHA as char(10)) else 'NO' end) as DIA_28,
		(case when @FECHA_ACT >= F29.FECHA then cast(F29.FECHA as char(10)) else 'NO' end) as DIA_29,
		(case when @FECHA_ACT >= F30.FECHA then cast(F30.FECHA as char(10)) else 'NO' end) as DIA_30,
		
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F1.FECHA) as Check_1,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F2.FECHA) as Check_2,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F3.FECHA) as Check_3,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F4.FECHA) as Check_4,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F5.FECHA) as Check_5,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F6.FECHA) as Check_6,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F7.FECHA) as Check_7,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F8.FECHA) as Check_8,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F9.FECHA) as Check_9,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F10.FECHA) as Check_10,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F11.FECHA) as Check_11,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F12.FECHA) as Check_12,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F13.FECHA) as Check_13,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F14.FECHA) as Check_14,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F15.FECHA) as Check_15,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F16.FECHA) as Check_16,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F17.FECHA) as Check_17,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F18.FECHA) as Check_18,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F19.FECHA) as Check_19,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F20.FECHA) as Check_20,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F21.FECHA) as Check_21,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F22.FECHA) as Check_22,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F23.FECHA) as Check_23,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F24.FECHA) as Check_24,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F25.FECHA) as Check_25,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F26.FECHA) as Check_26,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F27.FECHA) as Check_27,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F28.FECHA) as Check_28,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F29.FECHA) as Check_29,
		(Select top 1 time from user_check UC1 where UC1.user_snr = MR.user_snr and UC1.rec_stat=0 and cast(UC1.date as DATE) = F30.FECHA) as Check_30
		
		
		from users DM, (select distinct reg_snr, kloc_snr, rec_stat from KLOC_REG) klr, users MR, company CIA, compline LINEA
		left outer join FECHAS F1 on F1.num_fecha=1
		left outer join FECHAS F2 on F2.num_fecha=2
		left outer join FECHAS F3 on F3.num_fecha=3
		left outer join FECHAS F4 on F4.num_fecha=4
		left outer join FECHAS F5 on F5.num_fecha=5
		left outer join FECHAS F6 on F6.num_fecha=6
		left outer join FECHAS F7 on F7.num_fecha=7
		left outer join FECHAS F8 on F8.num_fecha=8
		left outer join FECHAS F9 on F9.num_fecha=9
		left outer join FECHAS F10 on F10.num_fecha=10
		left outer join FECHAS F11 on F11.num_fecha=11
		left outer join FECHAS F12 on F12.num_fecha=12
		left outer join FECHAS F13 on F13.num_fecha=13
		left outer join FECHAS F14 on F14.num_fecha=14
		left outer join FECHAS F15 on F15.num_fecha=15
		left outer join FECHAS F16 on F16.num_fecha=16
		left outer join FECHAS F17 on F17.num_fecha=17
		left outer join FECHAS F18 on F18.num_fecha=18
		left outer join FECHAS F19 on F19.num_fecha=19
		left outer join FECHAS F20 on F20.num_fecha=20
		left outer join FECHAS F21 on F21.num_fecha=21
		left outer join FECHAS F22 on F22.num_fecha=22
		left outer join FECHAS F23 on F23.num_fecha=23
		left outer join FECHAS F24 on F24.num_fecha=24
		left outer join FECHAS F25 on F25.num_fecha=25
		left outer join FECHAS F26 on F26.num_fecha=26
		left outer join FECHAS F27 on F27.num_fecha=27
		left outer join FECHAS F28 on F28.num_fecha=28
		left outer join FECHAS F29 on F29.num_fecha=29
		left outer join FECHAS F30 on F30.num_fecha=30
		
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
		
		order by DM.user_nr,DM.lname,DM.fname,MR.user_nr,MR.lname,MR.fname,klr.reg_snr ";
	
	//echo $qMedicos."<br>";
		
	if($tipo == 1 || $tipo == 2){//excel
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=BotonInicioDia.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else if($tipo == 3){
		ob_start();
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array(1300,1400));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('Botón Inicio del Día'));
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

	$tamTabla = 2800;
	$tabla = '';
	if( $tipo != 3){
			$tabla .= '<table border="0">
				<tr>
					<td>
						<table>
							<tr>
								<td colspan="10" class="nombreReporte">Botón Inicio del Día</td>
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
	$totalVisCK1 = '';
	$totalVisCK2 = '';
	$totalVisCK3 = '';
	$totalVisCK4 = '';
	$totalVisCK5 = '';
	$totalVisCK6 = '';
	$totalVisCK7 = '';
	$totalVisCK8 = '';
	$totalVisCK9 = '';
	$totalVisCK10 = '';
	$totalVisCK11 = '';
	$totalVisCK12 = '';
	$totalVisCK13 = '';
	$totalVisCK14 = '';
	$totalVisCK15 = '';
	$totalVisCK16 = '';
	$totalVisCK17 = '';
	$totalVisCK18 = '';
	$totalVisCK19 = '';
	$totalVisCK20 = '';
	$totalVisCK21 = '';
	$totalVisCK22 = '';
	$totalVisCK23 = '';
	$totalVisCK24 = '';
	$totalVisCK25 = '';
	$totalVisCK26 = '';
	$totalVisCK27 = '';
	$totalVisCK28 = '';
	$totalVisCK29 = '';
	$totalVisCK30 = '';

	while($reg = sqlsrv_fetch_array($rsMedicos)){
		////suma nacional	
		$totalVisCK1 = '';
		$totalVisCK2 = '';
		$totalVisCK3 = '';
		$totalVisCK4 = '';
		$totalVisCK5 = '';
		$totalVisCK6 = '';
		$totalVisCK7 = '';
		$totalVisCK8 = '';
		$totalVisCK9 = '';
		$totalVisCK10 = '';
		$totalVisCK11 = '';
		$totalVisCK12 = '';
		$totalVisCK13 = '';
		$totalVisCK14 = '';
		$totalVisCK15 = '';
		$totalVisCK16 = '';
		$totalVisCK17 = '';
		$totalVisCK18 = '';
		$totalVisCK19 = '';
		$totalVisCK20 = '';
		$totalVisCK21 = '';
		$totalVisCK22 = '';
		$totalVisCK23 = '';
		$totalVisCK24 = '';
		$totalVisCK25 = '';
		$totalVisCK26 = '';
		$totalVisCK27 = '';
		$totalVisCK28 = '';
		$totalVisCK29 = '';
		$totalVisCK30 = '';
		
		$Dia1 = $reg['DIA_1'];
		$Dia2 = $reg['DIA_2'];
		$Dia3 = $reg['DIA_3'];
		$Dia4 = $reg['DIA_4'];
		$Dia5 = $reg['DIA_5'];
		$Dia6 = $reg['DIA_6'];
		$Dia7 = $reg['DIA_7'];
		$Dia8 = $reg['DIA_8'];
		$Dia9 = $reg['DIA_9'];
		$Dia10 = $reg['DIA_10'];
		$Dia11 = $reg['DIA_11'];
		$Dia12 = $reg['DIA_12'];
		$Dia13 = $reg['DIA_13'];
		$Dia14 = $reg['DIA_14'];
		$Dia15 = $reg['DIA_15'];
		$Dia16 = $reg['DIA_16'];
		$Dia17 = $reg['DIA_17'];
		$Dia18 = $reg['DIA_18'];
		$Dia19 = $reg['DIA_19'];
		$Dia20 = $reg['DIA_20'];
		$Dia21 = $reg['DIA_21'];
		$Dia22 = $reg['DIA_22'];
		$Dia23 = $reg['DIA_23'];
		$Dia24 = $reg['DIA_24'];
		$Dia25 = $reg['DIA_25'];
		$Dia26 = $reg['DIA_26'];
		$Dia27 = $reg['DIA_27'];
		$Dia28 = $reg['DIA_28'];
		$Dia29 = $reg['DIA_29'];
		$Dia30 = $reg['DIA_30'];

		if($i == 1){
			////imprimir encabezados
			if($tipo != 3){
				$tabla .= '<td '.$estilocabecera.' width="100px">Linea</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px">Ruta</td>';
				$tabla .= '<td '.$estilocabecera.' width="400px">Nombre</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia1.'</td>';
				if($Dia2 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia2.'</td>';
				if($Dia3 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia3.'</td>';
				if($Dia4 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia4.'</td>';
				if($Dia5 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia5.'</td>';
				if($Dia6 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia6.'</td>';
				if($Dia7 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia7.'</td>';
				if($Dia8 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia8.'</td>';
				if($Dia9 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia9.'</td>';
				if($Dia10 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia10.'</td>';
				if($Dia11 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia11.'</td>';
				if($Dia12 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia12.'</td>';
				if($Dia13 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia13.'</td>';
				if($Dia14 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia14.'</td>';
				if($Dia15 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia15.'</td>';
				if($Dia16 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia16.'</td>';
				if($Dia17 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia17.'</td>';
				if($Dia18 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia18.'</td>';
				if($Dia19 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia19.'</td>';
				if($Dia20 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia20.'</td>';
				if($Dia21 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia21.'</td>';
				if($Dia22 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia22.'</td>';
				if($Dia23 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia23.'</td>';
				if($Dia24 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia24.'</td>';
				if($Dia25 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia25.'</td>';
				if($Dia26 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia26.'</td>';
				if($Dia27 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia27.'</td>';
				if($Dia28 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia28.'</td>';
				if($Dia29 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia29.'</td>';
				if($Dia30 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia30.'</td>';
			}else{
				$pdf->Ln();	
				$pdf->Cell(50,10,'Linea',1,0,'L',1);
				$pdf->Cell(50,10,'Ruta',1,0,'L',1);
				$pdf->Cell(200,10,'Nombre',1,0,'L',1);
				$pdf->Cell(50,10,$Dia1,1,0,'C',1);
				if($Dia2 != 'NO') $pdf->Cell(50,10,$Dia2,1,0,'C',1);
				if($Dia3 != 'NO') $pdf->Cell(50,10,$Dia3,1,0,'C',1);
				if($Dia4 != 'NO') $pdf->Cell(50,10,$Dia4,1,0,'C',1);
				if($Dia5 != 'NO') $pdf->Cell(50,10,$Dia5,1,0,'C',1);
				if($Dia6 != 'NO') $pdf->Cell(50,10,$Dia6,1,0,'C',1);
				if($Dia7 != 'NO') $pdf->Cell(50,10,$Dia7,1,0,'C',1);
				if($Dia8 != 'NO') $pdf->Cell(50,10,$Dia8,1,0,'C',1);
				if($Dia9 != 'NO') $pdf->Cell(50,10,$Dia9,1,0,'C',1);
				if($Dia10 != 'NO') $pdf->Cell(50,10,$Dia10,1,0,'C',1);
				if($Dia11 != 'NO') $pdf->Cell(50,10,$Dia11,1,0,'C',1);
				if($Dia12 != 'NO') $pdf->Cell(50,10,$Dia12,1,0,'C',1);
				if($Dia13 != 'NO') $pdf->Cell(50,10,$Dia13,1,0,'C',1);
				if($Dia14 != 'NO') $pdf->Cell(50,10,$Dia14,1,0,'C',1);
				if($Dia15 != 'NO') $pdf->Cell(50,10,$Dia15,1,0,'C',1);
				if($Dia16 != 'NO') $pdf->Cell(50,10,$Dia16,1,0,'C',1);
				if($Dia17 != 'NO') $pdf->Cell(50,10,$Dia17,1,0,'C',1);
				if($Dia18 != 'NO') $pdf->Cell(50,10,$Dia18,1,0,'C',1);
				if($Dia19 != 'NO') $pdf->Cell(50,10,$Dia19,1,0,'C',1);
				if($Dia20 != 'NO') $pdf->Cell(50,10,$Dia20,1,0,'C',1);
				if($Dia21 != 'NO') $pdf->Cell(50,10,$Dia21,1,0,'C',1);
				if($Dia22 != 'NO') $pdf->Cell(50,10,$Dia22,1,0,'C',1);
				if($Dia23 != 'NO') $pdf->Cell(50,10,$Dia23,1,0,'C',1);
				if($Dia24 != 'NO') $pdf->Cell(50,10,$Dia24,1,0,'C',1);
				if($Dia25 != 'NO') $pdf->Cell(50,10,$Dia25,1,0,'C',1);
				if($Dia26 != 'NO') $pdf->Cell(50,10,$Dia26,1,0,'C',1);
				if($Dia27 != 'NO') $pdf->Cell(50,10,$Dia27,1,0,'C',1);
				if($Dia28 != 'NO') $pdf->Cell(50,10,$Dia28,1,0,'C',1);
				if($Dia29 != 'NO') $pdf->Cell(50,10,$Dia29,1,0,'C',1);
				if($Dia30 != 'NO') $pdf->Cell(50,10,$Dia30,1,0,'C',1);
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
			$rutaGte = $reg['Gte_ruta'];
			$nombreGte = $reg['RM'];
			$gteCK1 = '';
			$gteCK2 = '';
			$gteCK3 = '';
			$gteCK4 = '';
			$gteCK5 = '';
			$gteCK6 = '';
			$gteCK7 = '';
			$gteCK8 = '';
			$gteCK9 = '';
			$gteCK10 = '';
			$gteCK11 = '';
			$gteCK12 = '';
			$gteCK13 = '';
			$gteCK14 = '';
			$gteCK15 = '';
			$gteCK16 = '';
			$gteCK17 = '';
			$gteCK18 = '';
			$gteCK19 = '';
			$gteCK20 = '';
			$gteCK21 = '';
			$gteCK22 = '';
			$gteCK23 = '';
			$gteCK24 = '';
			$gteCK25 = '';
			$gteCK26 = '';
			$gteCK27 = '';
			$gteCK28 = '';
			$gteCK29 = '';
			$gteCK30 = '';

		}else{
			////sumas gerentes
			$gerente = $reg['REG_SNR'];		
			if($tempGerente == $gerente){
				$gteVisCK1 = '';
				$gteVisCK2 = '';
				$gteVisCK3 = '';
				$gteVisCK4 = '';
				$gteVisCK5 = '';
				$gteVisCK6 = '';
				$gteVisCK7 = '';
				$gteVisCK8 = '';
				$gteVisCK9 = '';
				$gteVisCK10 = '';
				$gteVisCK11 = '';
				$gteVisCK12 = '';
				$gteVisCK13 = '';
				$gteVisCK14 = '';
				$gteVisCK15 = '';
				$gteVisCK16 = '';
				$gteVisCK17 = '';
				$gteVisCK18 = '';
				$gteVisCK19 = '';
				$gteVisCK20 = '';
				$gteVisCK21 = '';
				$gteVisCK22 = '';
				$gteVisCK23 = '';
				$gteVisCK24 = '';
				$gteVisCK25 = '';
				$gteVisCK26 = '';
				$gteVisCK27 = '';
				$gteVisCK28 = '';
				$gteVisCK29 = '';
				$gteVisCK30 = '';

			}else{	
				////imprimir gerentes
				if($tipo != 3){	
					/*
					$tabla .= '<tr><td '.$estilogte.' width="100px"> </td>';
					$tabla .= '<td '.$estilogte.' width="100px">'.$rutaGte.'</td>';
					$tabla .= '<td '.$estilogte.' width="400px">'.$nombreGte.'</td>';
					$tabla .= '</tr>';
					*/
				}else{ 
					/*
					$pdf->Cell(100,10,$rutaGte,'LT',0,'L',1);
					$pdf->Cell(200,10,$nombreGte,'T',0,'L',1);
					$pdf->Cell(50,10,'','T',0,'L',1);
					$pdf->Cell(50,10,number_format($gtePresen),1,1,'C',1);
					*/
				}
	
				////inicia var gerente
				$tempGerente = $reg['REG_SNR'];
				$rutaGte = $reg['Gte_ruta'];
				$nombreGte = $reg['RM'];
				$gteCK1 = '';
				$gteCK2 = '';
				$gteCK3 = '';
				$gteCK4 = '';
				$gteCK5 = '';
				$gteCK6 = '';
				$gteCK7 = '';
				$gteCK8 = '';
				$gteCK9 = '';
				$gteCK10 = '';
				$gteCK11 = '';
				$gteCK12 = '';
				$gteCK13 = '';
				$gteCK14 = '';
				$gteCK15 = '';
				$gteCK16 = '';
				$gteCK17 = '';
				$gteCK18 = '';
				$gteCK19 = '';
				$gteCK20 = '';
				$gteCK21 = '';
				$gteCK22 = '';
				$gteCK23 = '';
				$gteCK24 = '';
				$gteCK25 = '';
				$gteCK26 = '';
				$gteCK27 = '';
				$gteCK28 = '';
				$gteCK29 = '';
				$gteCK30 = '';
			}
		}
		
		////formulas repres
		$tabla .= '<tr>';

		////imprimir repres
		if($tipo != 3){
			$tabla .= '<td '.$estilorepre.' width="100px">'.$reg['LINEA'].'</td>';	
			$tabla .= '<td '.$estilorepre.' width="100px">'.$reg['Ruta'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="400px">'.$reg['SR'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_1'].'</td>';
			if($Dia2 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_2'].'</td>';
			if($Dia3 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_3'].'</td>';
			if($Dia4 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_4'].'</td>';
			if($Dia5 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_5'].'</td>';
			if($Dia6 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_6'].'</td>';
			if($Dia7 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_7'].'</td>';
			if($Dia8 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_8'].'</td>';
			if($Dia9 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_9'].'</td>';
			if($Dia10 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_10'].'</td>';
			if($Dia11 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_11'].'</td>';
			if($Dia12 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_12'].'</td>';
			if($Dia13 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_13'].'</td>';
			if($Dia14 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_14'].'</td>';
			if($Dia15 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_15'].'</td>';
			if($Dia16 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_16'].'</td>';
			if($Dia17 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_17'].'</td>';
			if($Dia18 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_18'].'</td>';
			if($Dia19 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_19'].'</td>';
			if($Dia20 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_20'].'</td>';
			if($Dia21 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_21'].'</td>';
			if($Dia22 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_22'].'</td>';
			if($Dia23 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_23'].'</td>';
			if($Dia24 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_24'].'</td>';
			if($Dia25 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_25'].'</td>';
			if($Dia26 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_26'].'</td>';
			if($Dia27 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_27'].'</td>';
			if($Dia28 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_28'].'</td>';
			if($Dia29 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_29'].'</td>';
			if($Dia30 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Check_30'].'</td>';
			$tabla .= '</tr>';
		}else{
			$pdf->Cell(50,10,$reg['LINEA'],1,0,'L',0);
			$pdf->Cell(50,10,$reg['Ruta'],1,0,'L',0);
			$pdf->Cell(200,10,$reg['SR'],1,0,'L',0);
			$pdf->Cell(50,10,$reg['Check_1'],1,0,'C',0);
			if($Dia2 != 'NO') $pdf->Cell(50,10,$reg['Check_2'],1,0,'C',0);
			if($Dia3 != 'NO') $pdf->Cell(50,10,$reg['Check_3'],1,0,'C',0);
			if($Dia4 != 'NO') $pdf->Cell(50,10,$reg['Check_4'],1,0,'C',0);
			if($Dia5 != 'NO') $pdf->Cell(50,10,$reg['Check_5'],1,0,'C',0);
			if($Dia6 != 'NO') $pdf->Cell(50,10,$reg['Check_6'],1,0,'C',0);
			if($Dia7 != 'NO') $pdf->Cell(50,10,$reg['Check_7'],1,0,'C',0);
			if($Dia8 != 'NO') $pdf->Cell(50,10,$reg['Check_8'],1,0,'C',0);
			if($Dia9 != 'NO') $pdf->Cell(50,10,$reg['Check_9'],1,0,'C',0);
			if($Dia10 != 'NO') $pdf->Cell(50,10,$reg['Check_10'],1,0,'C',0);
			if($Dia11 != 'NO') $pdf->Cell(50,10,$reg['Check_11'],1,0,'C',0);
			if($Dia12 != 'NO') $pdf->Cell(50,10,$reg['Check_12'],1,0,'C',0);
			if($Dia13 != 'NO') $pdf->Cell(50,10,$reg['Check_13'],1,0,'C',0);
			if($Dia14 != 'NO') $pdf->Cell(50,10,$reg['Check_14'],1,0,'C',0);
			if($Dia15 != 'NO') $pdf->Cell(50,10,$reg['Check_15'],1,0,'C',0);
			if($Dia16 != 'NO') $pdf->Cell(50,10,$reg['Check_16'],1,0,'C',0);
			if($Dia17 != 'NO') $pdf->Cell(50,10,$reg['Check_17'],1,0,'C',0);
			if($Dia18 != 'NO') $pdf->Cell(50,10,$reg['Check_18'],1,0,'C',0);
			if($Dia19 != 'NO') $pdf->Cell(50,10,$reg['Check_19'],1,0,'C',0);
			if($Dia20 != 'NO') $pdf->Cell(50,10,$reg['Check_20'],1,0,'C',0);
			if($Dia21 != 'NO') $pdf->Cell(50,10,$reg['Check_21'],1,0,'C',0);
			if($Dia22 != 'NO') $pdf->Cell(50,10,$reg['Check_22'],1,0,'C',0);
			if($Dia23 != 'NO') $pdf->Cell(50,10,$reg['Check_23'],1,0,'C',0);
			if($Dia24 != 'NO') $pdf->Cell(50,10,$reg['Check_24'],1,0,'C',0);
			if($Dia25 != 'NO') $pdf->Cell(50,10,$reg['Check_25'],1,0,'C',0);
			if($Dia26 != 'NO') $pdf->Cell(50,10,$reg['Check_26'],1,0,'C',0);
			if($Dia27 != 'NO') $pdf->Cell(50,10,$reg['Check_27'],1,0,'C',0);
			if($Dia28 != 'NO') $pdf->Cell(50,10,$reg['Check_28'],1,0,'C',0);
			if($Dia29 != 'NO') $pdf->Cell(50,10,$reg['Check_29'],1,0,'C',0);
			if($Dia30 != 'NO') $pdf->Cell(50,10,$reg['Check_30'],1,1,'C',0);
			$pdf->Ln();	
		}
		$i++;
	}
	
	////imprimir ultimo gerente
	if($tipo != 3){
		/*
		$tabla .= '<tr><td '.$estilogte.' width="100px"> </td>';
		$tabla .= '<td '.$estilogte.' width="100px">'.$rutaGte.'</td>';
		$tabla .= '<td '.$estilogte.' width="400px">'.$nombreGte.'</td>';
		$tabla .= '</tr>';
		*/
	}else{
		/*
		$pdf->Cell(100,10,$rutaGte,'LT',0,'L',1);
		$pdf->Cell(200,10,$nombreGte,'T',0,'L',1);
		$pdf->Cell(50,10,'','T',0,'L',1);
		$pdf->Cell(50,10,number_format($gtePresen),1,1,'C',1);
		*/
	}	

	////imprimir nacional
	if($tipo != 3){
		/*
		$tabla .= '<tr><td '.$estilogte.' width="100px"> </td>';
		$tabla .= '<td '.$estilogte.' width="100px"> </td>';
		$tabla .= '<td '.$estilogte.' width="400px">Total General</td>';
		$tabla .= '</tr>';
		*/
	}else{
		/*
		$pdf->Cell(100,10,'','LTB',0,'L',1);
		$pdf->Cell(200,10,'Total General','TB',0,'L',1);
		$pdf->Cell(50,10,'','TB',0,'L',1);
		$pdf->Cell(50,10,number_format($totalPresen),1,1,'C',1);
		*/
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