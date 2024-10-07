<script src="js/pages/ui/dialogs.js"></script>
<?php
	/*** cobertura de medicos ***/
	include "../conexion.php";
	
	$tipo = $_POST['hdnTipoReporte'];
	$ciclo = $_POST['hdnCicloReporte'];
	if(isset($_POST['hdnLinea'])){
		$_POST['linea'] = str_replace(",","','",$_POST['hdnLinea']);
	}
	if(is_array($_POST['linea'])){
		$linea = implode("','",$_POST['linea']);
	}else{
		$linea = $_POST['linea'];
	}
	
	$qMedicos = "DECLARE @CICLO as VARCHAR(36)
		DECLARE @DIAS_IN as DATE
		DECLARE @DIAS_FIN as DATE
		DECLARE @FECHA_ACT as DATE
		DECLARE @LINEA as VARCHAR(120)
		
		SET @CICLO = '".$ciclo."'
		/*SET @CICLO = (Select CYCLE_SNR from CYCLES where rec_stat=0 and name = '2023-03') */
		SET @DIAS_IN = (Select START_DATE from CYCLES where CYCLE_SNR = @CICLO)
		SET @DIAS_FIN = (Select FINISH_DATE from CYCLES where CYCLE_SNR = @CICLO)
		SET @FECHA_ACT = (case when cast(getdate() as date) <= @DIAS_FIN then cast(getdate() as date) else @DIAS_FIN end)
		/*SET @LINEA = '".$linea."' */
		/*SET @LINEA = (Select CLINE_SNR from COMPLINE where rec_stat=0 and name in ('SOMA')) */
		
		;with FECHAS (fecha, num_fecha) as (
		select  DATEADD(DAY, nbr - 1, @DIAS_IN) fecha, ROW_NUMBER() OVER (ORDER BY DATEADD(DAY, nbr - 1, @DIAS_IN)) num_fecha
		from    ( select ROW_NUMBER() OVER (ORDER BY c.object_id) as Nbr
				  from  sys.columns c) nbrs
		where   nbr - 1 <= DATEDIFF(DAY, @DIAS_IN, @FECHA_ACT)
		and datepart(DW,DATEADD(DAY, nbr - 1, @DIAS_IN)) not in (1,7)
		and DATEADD(DAY, nbr - 1, @DIAS_IN) not in (select c_date from CYCLE_DETAILS where c_date between @DIAS_IN and @FECHA_ACT and rec_stat=0 and c_day=0)
		)
		
		select 
		Prod.name as Producto,
		@DIAS_IN as Fecha_Inicial,
		@DIAS_FIN as Fecha_Final,
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
		
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F1.fecha ),0) as ProdDia_1,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4 and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F2.fecha ),0) as ProdDia_2,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F3.fecha ),0) as ProdDia_3,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F4.fecha ),0) as ProdDia_4,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F5.fecha ),0) as ProdDia_5,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F6.fecha ),0) as ProdDia_6,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F7.fecha ),0) as ProdDia_7,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F8.fecha ),0) as ProdDia_8,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F9.fecha ),0) as ProdDia_9,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F10.fecha ),0) as ProdDia_10,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F11.fecha ),0) as ProdDia_11,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F12.fecha ),0) as ProdDia_12,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F13.fecha ),0) as ProdDia_13,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F14.fecha ),0) as ProdDia_14,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F15.fecha ),0) as ProdDia_15,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F16.fecha ),0) as ProdDia_16,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F17.fecha ),0) as ProdDia_17,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F18.fecha ),0) as ProdDia_18,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F19.fecha ),0) as ProdDia_19,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F20.fecha ),0) as ProdDia_20,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F21.fecha ),0) as ProdDia_21,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F22.fecha ),0) as ProdDia_22,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F23.fecha ),0) as ProdDia_23,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F24.fecha ),0) as ProdDia_24,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F25.fecha ),0) as ProdDia_25,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F26.fecha ),0) as ProdDia_26,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F27.fecha ),0) as ProdDia_27,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F28.fecha ),0) as ProdDia_28,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4	and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F29.fecha ),0) as ProdDia_29,
		isnull((select count(vpp.prod_snr) from visitpers_prod vpp, visitpers vp, users u
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4 and vpp.prod_snr = Prod.prod_snr 
		and u.cline_snr in ('".$linea."') and vp.visit_date = F30.fecha ),0) as ProdDia_30
		
		
		from product Prod 
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
		
		
		where Prod.prod_snr<>'00000000-0000-0000-0000-000000000000'
		and Prod.rec_stat=0
		and Prod.prod_snr in (select vpp.prod_snr from visitpers_prod vpp, visitpers vp, users u 
		where vpp.vispers_snr = vp.vispers_snr and vp.user_snr = u.user_snr and vpp.rec_stat=0 and vp.rec_stat=0 and u.rec_stat=0 and u.user_type=4 /*and vpp.prod_snr = Prod.prod_snr */
		and u.cline_snr in ('".$linea."') and vp.visit_date between @DIAS_IN and @DIAS_FIN)
		
		order by Prod.name ";
	
	//echo $qMedicos."<br>";
		
	if($tipo == 1 || $tipo == 2){//excel
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=ContactosPorProducto.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else if($tipo == 3){
		ob_start();
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array(1300,1400));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('Contactos por Producto'));
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
								<td colspan="10" class="nombreReporte">Contactos por Producto</td>
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
	$totalVisPD1 = 0;
	$totalVisPD2 = 0;
	$totalVisPD3 = 0;
	$totalVisPD4 = 0;
	$totalVisPD5 = 0;
	$totalVisPD6 = 0;
	$totalVisPD7 = 0;
	$totalVisPD8 = 0;
	$totalVisPD9 = 0;
	$totalVisPD10 = 0;
	$totalVisPD11 = 0;
	$totalVisPD12 = 0;
	$totalVisPD13 = 0;
	$totalVisPD14 = 0;
	$totalVisPD15 = 0;
	$totalVisPD16 = 0;
	$totalVisPD17 = 0;
	$totalVisPD18 = 0;
	$totalVisPD19 = 0;
	$totalVisPD20 = 0;
	$totalVisPD21 = 0;
	$totalVisPD22 = 0;
	$totalVisPD23 = 0;
	$totalVisPD24 = 0;
	$totalVisPD25 = 0;
	$totalVisPD26 = 0;
	$totalVisPD27 = 0;
	$totalVisPD28 = 0;
	$totalVisPD29 = 0;
	$totalVisPD30 = 0;

	while($reg = sqlsrv_fetch_array($rsMedicos)){
		////suma nacional
		$totalVisPD1 += $reg['ProdDia_1'];
		$totalVisPD2 += $reg['ProdDia_2'];
		$totalVisPD3 += $reg['ProdDia_3'];
		$totalVisPD4 += $reg['ProdDia_4'];
		$totalVisPD5 += $reg['ProdDia_5'];
		$totalVisPD6 += $reg['ProdDia_6'];
		$totalVisPD7 += $reg['ProdDia_7'];
		$totalVisPD8 += $reg['ProdDia_8'];
		$totalVisPD9 += $reg['ProdDia_9'];
		$totalVisPD10 += $reg['ProdDia_10'];
		$totalVisPD11 += $reg['ProdDia_11'];
		$totalVisPD12 += $reg['ProdDia_12'];
		$totalVisPD13 += $reg['ProdDia_13'];
		$totalVisPD14 += $reg['ProdDia_14'];
		$totalVisPD15 += $reg['ProdDia_15'];
		$totalVisPD16 += $reg['ProdDia_16'];
		$totalVisPD17 += $reg['ProdDia_17'];
		$totalVisPD18 += $reg['ProdDia_18'];
		$totalVisPD19 += $reg['ProdDia_19'];
		$totalVisPD20 += $reg['ProdDia_20'];
		$totalVisPD21 += $reg['ProdDia_21'];
		$totalVisPD22 += $reg['ProdDia_22'];
		$totalVisPD23 += $reg['ProdDia_23'];
		$totalVisPD24 += $reg['ProdDia_24'];
		$totalVisPD25 += $reg['ProdDia_25'];
		$totalVisPD26 += $reg['ProdDia_26'];
		$totalVisPD27 += $reg['ProdDia_27'];
		$totalVisPD28 += $reg['ProdDia_28'];
		$totalVisPD29 += $reg['ProdDia_29'];
		$totalVisPD30 += $reg['ProdDia_30'];
		$totalVisPDT = $totalVisPD1+$totalVisPD2+$totalVisPD3+$totalVisPD4+$totalVisPD5+$totalVisPD6+$totalVisPD7+$totalVisPD8+$totalVisPD9+$totalVisPD10
			+ $totalVisPD11+$totalVisPD12+$totalVisPD13+$totalVisPD14+$totalVisPD15+$totalVisPD16+$totalVisPD17+$totalVisPD18+$totalVisPD19+$totalVisPD20
			+ $totalVisPD21+$totalVisPD22+$totalVisPD23+$totalVisPD24+$totalVisPD25+$totalVisPD26+$totalVisPD27+$totalVisPD28+$totalVisPD29+$totalVisPD30;	

		$NumDia = $reg['NDiasTransc'] + 0;
		$NumDiaT = ($reg['NDiasTransc'] + 1);
		$NumDiaTpdf = ($NumDiaT * 50);
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
				$tabla .= '<td '.$estilocabecera.' width="400px"> </td>';
				$tabla .= '<td '.$estilocabecera.' colspan='.$NumDiaT.' width="100px" align="center">Contactos por Dia</td></tr>';
				$tabla .= '<td '.$estilocabecera.' width="400px" align="center">Producto</td>';
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
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Total</td>';
			}else{
				$pdf->Ln();	
				$pdf->Cell(200,10,'','LTR',0,'L',1);
				$pdf->Cell($NumDiaTpdf,10,'Contactos por Dia','LTR',0,'C',1);
				$pdf->Ln();	
				$pdf->Cell(200,10,'Producto','LRB',0,'L',1);
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
				$pdf->Cell(50,10,'Total',1,0,'C',1);
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
			//$tempGerente = $reg['REG_SNR'];
			//$gerente = $reg['REG_SNR'];
			//$rutaGte = $reg['Gte_ruta'];
			//$nombreGte = $reg['RM'];
			$tempProducto = $reg['Producto'];
			$producto = $reg['Producto'];
			$gteVisPD1 = $reg['ProdDia_1'];
			$gteVisPD2 = $reg['ProdDia_2'];
			$gteVisPD3 = $reg['ProdDia_3'];
			$gteVisPD4 = $reg['ProdDia_4'];
			$gteVisPD5 = $reg['ProdDia_5'];
			$gteVisPD6 = $reg['ProdDia_6'];
			$gteVisPD7 = $reg['ProdDia_7'];
			$gteVisPD8 = $reg['ProdDia_8'];
			$gteVisPD9 = $reg['ProdDia_9'];
			$gteVisPD10 = $reg['ProdDia_10'];
			$gteVisPD11 = $reg['ProdDia_11'];
			$gteVisPD12 = $reg['ProdDia_12'];
			$gteVisPD13 = $reg['ProdDia_13'];
			$gteVisPD14 = $reg['ProdDia_14'];
			$gteVisPD15 = $reg['ProdDia_15'];
			$gteVisPD16 = $reg['ProdDia_16'];
			$gteVisPD17 = $reg['ProdDia_17'];
			$gteVisPD18 = $reg['ProdDia_18'];
			$gteVisPD19 = $reg['ProdDia_19'];
			$gteVisPD20 = $reg['ProdDia_20'];
			$gteVisPD21 = $reg['ProdDia_21'];
			$gteVisPD22 = $reg['ProdDia_22'];
			$gteVisPD23 = $reg['ProdDia_23'];
			$gteVisPD24 = $reg['ProdDia_24'];
			$gteVisPD25 = $reg['ProdDia_25'];
			$gteVisPD26 = $reg['ProdDia_26'];
			$gteVisPD27 = $reg['ProdDia_27'];
			$gteVisPD28 = $reg['ProdDia_28'];
			$gteVisPD29 = $reg['ProdDia_29'];
			$gteVisPD30 = $reg['ProdDia_30'];

		}else{
			////sumas gerentes
			//$gerente = $reg['REG_SNR'];
			//if($tempGerente == $gerente){
			$producto = $reg['Producto'];		
			if($tempProducto == $producto){
				$sumVisPD1 = $reg['ProdDia_1'];
				$gteVisPD1 += $sumVisPD1;
				$sumVisPD2 = $reg['ProdDia_2'];
				$gteVisPD2 += $sumVisPD2;
				$sumVisPD3 = $reg['ProdDia_3'];
				$gteVisPD3 += $sumVisPD3;
				$sumVisPD4 = $reg['ProdDia_4'];
				$gteVisPD4 += $sumVisPD4;
				$sumVisPD5 = $reg['ProdDia_5'];
				$gteVisPD5 += $sumVisPD5;
				$sumVisPD6 = $reg['ProdDia_6'];
				$gteVisPD6 += $sumVisPD6;
				$sumVisPD7 = $reg['ProdDia_7'];
				$gteVisPD7 += $sumVisPD7;
				$sumVisPD8 = $reg['ProdDia_8'];
				$gteVisPD8 += $sumVisPD8;
				$sumVisPD9 = $reg['ProdDia_9'];
				$gteVisPD9 += $sumVisPD9;
				$sumVisPD10 = $reg['ProdDia_10'];
				$gteVisPD10 += $sumVisPD10;
				$sumVisPD11 = $reg['ProdDia_11'];
				$gteVisPD11 += $sumVisPD11;
				$sumVisPD12 = $reg['ProdDia_12'];
				$gteVisPD12 += $sumVisPD12;
				$sumVisPD13 = $reg['ProdDia_13'];
				$gteVisPD13 += $sumVisPD13;
				$sumVisPD14 = $reg['ProdDia_14'];
				$gteVisPD14 += $sumVisPD14;
				$sumVisPD15 = $reg['ProdDia_15'];
				$gteVisPD15 += $sumVisPD15;
				$sumVisPD16 = $reg['ProdDia_16'];
				$gteVisPD16 += $sumVisPD16;
				$sumVisPD17 = $reg['ProdDia_17'];
				$gteVisPD17 += $sumVisPD17;
				$sumVisPD18 = $reg['ProdDia_18'];
				$gteVisPD18 += $sumVisPD18;
				$sumVisPD19 = $reg['ProdDia_19'];
				$gteVisPD19 += $sumVisPD19;
				$sumVisPD20 = $reg['ProdDia_20'];
				$gteVisPD20 += $sumVisPD20;
				$sumVisPD21 = $reg['ProdDia_21'];
				$gteVisPD21 += $sumVisPD21;
				$sumVisPD22 = $reg['ProdDia_22'];
				$gteVisPD22 += $sumVisPD22;
				$sumVisPD23 = $reg['ProdDia_23'];
				$gteVisPD23 += $sumVisPD23;
				$sumVisPD24 = $reg['ProdDia_24'];
				$gteVisPD24 += $sumVisPD24;
				$sumVisPD25 = $reg['ProdDia_25'];
				$gteVisPD25 += $sumVisPD25;
				$sumVisPD26 = $reg['ProdDia_26'];
				$gteVisPD26 += $sumVisPD26;
				$sumVisPD27 = $reg['ProdDia_27'];
				$gteVisPD27 += $sumVisPD27;
				$sumVisPD28 = $reg['ProdDia_28'];
				$gteVisPD28 += $sumVisPD28;
				$sumVisPD29 = $reg['ProdDia_29'];
				$gteVisPD29 += $sumVisPD29;
				$sumVisPD30 = $reg['ProdDia_30'];
				$gteVisPD30 += $sumVisPD30;
				$gteVisPDT = $gteVisPD1+$gteVisPD2+$gteVisPD3+$gteVisPD4+$gteVisPD5+$gteVisPD6+$gteVisPD7+$gteVisPD8+$gteVisPD9+$gteVisPD10
					+ $gteVisPD11+$gteVisPD12+$gteVisPD13+$gteVisPD14+$gteVisPD15+$gteVisPD16+$gteVisPD17+$gteVisPD18+$gteVisPD19+$gteVisPD20
					+ $gteVisPD21+$gteVisPD22+$gteVisPD23+$gteVisPD24+$gteVisPD25+$gteVisPD26+$gteVisPD27+$gteVisPD28+$gteVisPD29+$gteVisPD30;		

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
					$pdf->Cell(50,10,'','LT',0,'L',1);
					$pdf->Cell(50,10,$rutaGte,'LT',0,'L',1);
					$pdf->Cell(200,10,$nombreGte,'T',0,'L',1);
					$pdf->Cell(50,10,number_format($gtePresen),1,1,'C',1);
					*/
				}
	
				////inicia var gerente
				//$tempGerente = $reg['REG_SNR'];
				//$rutaGte = $reg['Gte_ruta'];
				//$nombreGte = $reg['RM'];
				$gteVisPD1 = $reg['ProdDia_1'];
				$gteVisPD2 = $reg['ProdDia_2'];
				$gteVisPD3 = $reg['ProdDia_3'];
				$gteVisPD4 = $reg['ProdDia_4'];
				$gteVisPD5 = $reg['ProdDia_5'];
				$gteVisPD6 = $reg['ProdDia_6'];
				$gteVisPD7 = $reg['ProdDia_7'];
				$gteVisPD8 = $reg['ProdDia_8'];
				$gteVisPD9 = $reg['ProdDia_9'];
				$gteVisPD10 = $reg['ProdDia_10'];
				$gteVisPD11 = $reg['ProdDia_11'];
				$gteVisPD12 = $reg['ProdDia_12'];
				$gteVisPD13 = $reg['ProdDia_13'];
				$gteVisPD14 = $reg['ProdDia_14'];
				$gteVisPD15 = $reg['ProdDia_15'];
				$gteVisPD16 = $reg['ProdDia_16'];
				$gteVisPD17 = $reg['ProdDia_17'];
				$gteVisPD18 = $reg['ProdDia_18'];
				$gteVisPD19 = $reg['ProdDia_19'];
				$gteVisPD20 = $reg['ProdDia_20'];
				$gteVisPD21 = $reg['ProdDia_21'];
				$gteVisPD22 = $reg['ProdDia_22'];
				$gteVisPD23 = $reg['ProdDia_23'];
				$gteVisPD24 = $reg['ProdDia_24'];
				$gteVisPD25 = $reg['ProdDia_25'];
				$gteVisPD26 = $reg['ProdDia_26'];
				$gteVisPD27 = $reg['ProdDia_27'];
				$gteVisPD28 = $reg['ProdDia_28'];
				$gteVisPD29 = $reg['ProdDia_29'];
				$gteVisPD30 = $reg['ProdDia_30'];
			}
		}
		
		////formulas repres
		$tabla .= '<tr>';
		$TVisPD = $reg['ProdDia_1']+$reg['ProdDia_2']+$reg['ProdDia_3']+$reg['ProdDia_4']+$reg['ProdDia_5']+$reg['ProdDia_6']+$reg['ProdDia_7']+$reg['ProdDia_8']+$reg['ProdDia_9']+$reg['ProdDia_10']
			+$reg['ProdDia_11']+$reg['ProdDia_12']+$reg['ProdDia_13']+$reg['ProdDia_14']+$reg['ProdDia_15']+$reg['ProdDia_16']+$reg['ProdDia_17']+$reg['ProdDia_18']+$reg['ProdDia_19']+$reg['ProdDia_20']
			+$reg['ProdDia_21']+$reg['ProdDia_22']+$reg['ProdDia_23']+$reg['ProdDia_24']+$reg['ProdDia_25']+$reg['ProdDia_26']+$reg['ProdDia_27']+$reg['ProdDia_28']+$reg['ProdDia_29']+$reg['ProdDia_30'];

		////imprimir repres
		if($tipo != 3){
			$tabla .= '<td '.$estilorepre.' width="400px">'.$reg['Producto'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_1'].'</td>';
			if($Dia2 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_2'].'</td>';
			if($Dia3 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_3'].'</td>';
			if($Dia4 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_4'].'</td>';
			if($Dia5 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_5'].'</td>';
			if($Dia6 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_6'].'</td>';
			if($Dia7 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_7'].'</td>';
			if($Dia8 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_8'].'</td>';
			if($Dia9 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_9'].'</td>';
			if($Dia10 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_10'].'</td>';
			if($Dia11 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_11'].'</td>';
			if($Dia12 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_12'].'</td>';
			if($Dia13 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_13'].'</td>';
			if($Dia14 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_14'].'</td>';
			if($Dia15 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_15'].'</td>';
			if($Dia16 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_16'].'</td>';
			if($Dia17 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_17'].'</td>';
			if($Dia18 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_18'].'</td>';
			if($Dia19 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_19'].'</td>';
			if($Dia20 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_20'].'</td>';
			if($Dia21 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_21'].'</td>';
			if($Dia22 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_22'].'</td>';
			if($Dia23 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_23'].'</td>';
			if($Dia24 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_24'].'</td>';
			if($Dia25 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_25'].'</td>';
			if($Dia26 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_26'].'</td>';
			if($Dia27 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_27'].'</td>';
			if($Dia28 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_28'].'</td>';
			if($Dia29 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_29'].'</td>';
			if($Dia30 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['ProdDia_30'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.number_format($TVisPD).'</td>';
			$tabla .= '</tr>';
		}else{
			$pdf->Cell(200,10,$reg['Producto'],1,0,'L',0);
			$pdf->Cell(50,10,$reg['ProdDia_1'],1,0,'C',0);
			if($Dia2 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_2'],1,0,'C',0);
			if($Dia3 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_3'],1,0,'C',0);
			if($Dia4 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_4'],1,0,'C',0);
			if($Dia5 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_5'],1,0,'C',0);
			if($Dia6 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_6'],1,0,'C',0);
			if($Dia7 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_7'],1,0,'C',0);
			if($Dia8 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_8'],1,0,'C',0);
			if($Dia9 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_9'],1,0,'C',0);
			if($Dia10 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_10'],1,0,'C',0);
			if($Dia11 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_11'],1,0,'C',0);
			if($Dia12 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_12'],1,0,'C',0);
			if($Dia13 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_13'],1,0,'C',0);
			if($Dia14 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_14'],1,0,'C',0);
			if($Dia15 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_15'],1,0,'C',0);
			if($Dia16 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_16'],1,0,'C',0);
			if($Dia17 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_17'],1,0,'C',0);
			if($Dia18 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_18'],1,0,'C',0);
			if($Dia19 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_19'],1,0,'C',0);
			if($Dia20 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_20'],1,0,'C',0);
			if($Dia21 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_21'],1,0,'C',0);
			if($Dia22 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_22'],1,0,'C',0);
			if($Dia23 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_23'],1,0,'C',0);
			if($Dia24 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_24'],1,0,'C',0);
			if($Dia25 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_25'],1,0,'C',0);
			if($Dia26 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_26'],1,0,'C',0);
			if($Dia27 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_27'],1,0,'C',0);
			if($Dia28 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_28'],1,0,'C',0);
			if($Dia29 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_29'],1,0,'C',0);
			if($Dia30 != 'NO') $pdf->Cell(50,10,$reg['ProdDia_30'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($TVisPD),1,1,'C',0);
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
		$pdf->Cell(50,10,'','LT',0,'L',1);
		$pdf->Cell(50,10,$rutaGte,'LT',0,'L',1);
		$pdf->Cell(200,10,$nombreGte,'T',0,'L',1);
		$pdf->Cell(50,10,number_format($gtePresen),1,1,'C',1);
		*/
	}	

	////imprimir nacional
	if($tipo != 3){
		$tabla .= '<td '.$estilogte.' width="400px">Total General</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD1).'</td>';
		if($Dia2 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD2).'</td>';
		if($Dia3 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD3).'</td>';
		if($Dia4 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD4).'</td>';
		if($Dia5 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD5).'</td>';
		if($Dia6 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD6).'</td>';
		if($Dia7 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD7).'</td>';
		if($Dia8 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD8).'</td>';
		if($Dia9 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD9).'</td>';
		if($Dia10 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD10).'</td>';
		if($Dia11 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD11).'</td>';
		if($Dia12 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD12).'</td>';
		if($Dia13 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD13).'</td>';
		if($Dia14 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD14).'</td>';
		if($Dia15 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD15).'</td>';
		if($Dia16 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD16).'</td>';
		if($Dia17 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD17).'</td>';
		if($Dia18 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD18).'</td>';
		if($Dia19 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD19).'</td>';
		if($Dia20 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD20).'</td>';
		if($Dia21 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD21).'</td>';
		if($Dia22 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD22).'</td>';
		if($Dia23 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD23).'</td>';
		if($Dia24 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD24).'</td>';
		if($Dia25 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD25).'</td>';
		if($Dia26 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD26).'</td>';
		if($Dia27 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD27).'</td>';
		if($Dia28 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD28).'</td>';
		if($Dia29 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD29).'</td>';
		if($Dia30 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPD30).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisPDT).'</td>';
		$tabla .= '</tr>';
	}else{
		$pdf->Cell(200,10,'Total General',1,0,'L',1);
		$pdf->Cell(50,10,number_format($totalVisPD1),1,0,'C',1);
		if($Dia2 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD2),1,0,'C',1);
		if($Dia3 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD3),1,0,'C',1);
		if($Dia4 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD4),1,0,'C',1);
		if($Dia5 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD5),1,0,'C',1);
		if($Dia6 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD6),1,0,'C',1);
		if($Dia7 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD7),1,0,'C',1);
		if($Dia8 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD8),1,0,'C',1);
		if($Dia9 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD9),1,0,'C',1);
		if($Dia10 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD10),1,0,'C',1);
		if($Dia11 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD11),1,0,'C',1);
		if($Dia12 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD12),1,0,'C',1);
		if($Dia13 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD13),1,0,'C',1);
		if($Dia14 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD14),1,0,'C',1);
		if($Dia15 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD15),1,0,'C',1);
		if($Dia16 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD16),1,0,'C',1);
		if($Dia17 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD17),1,0,'C',1);
		if($Dia18 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD18),1,0,'C',1);
		if($Dia19 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD19),1,0,'C',1);
		if($Dia20 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD20),1,0,'C',1);
		if($Dia21 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD21),1,0,'C',1);
		if($Dia22 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD22),1,0,'C',1);
		if($Dia23 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD23),1,0,'C',1);
		if($Dia24 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD24),1,0,'C',1);
		if($Dia25 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD25),1,0,'C',1);
		if($Dia26 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD26),1,0,'C',1);
		if($Dia27 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD27),1,0,'C',1);
		if($Dia28 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD28),1,0,'C',1);
		if($Dia29 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD29),1,0,'C',1);
		if($Dia30 != 'NO') $pdf->Cell(50,10,number_format($totalVisPD30),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisPDT),1,1,'C',1);
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