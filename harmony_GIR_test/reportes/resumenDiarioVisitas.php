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
		DECLARE @PRESENCIAL as VARCHAR(36)
		DECLARE @FALLIDA as VARCHAR(36)
		DECLARE @COMPLEMENT as VARCHAR(36)
		
		SET @CICLO = '".$ciclo."'
		/*SET @CICLO = (Select CYCLE_SNR from CYCLES where rec_stat=0 and name = '2023-03') */
		SET @DIAS_IN = (Select START_DATE from CYCLES where CYCLE_SNR = @CICLO)
		SET @DIAS_FIN = (Select FINISH_DATE from CYCLES where CYCLE_SNR = @CICLO)
		SET @FECHA_ACT = (case when cast(getdate() as date) <= @DIAS_FIN then cast(getdate() as date) else @DIAS_FIN end)
		SET @PRESENCIAL = '2B3A7099-AC7D-47A3-A274-F0B029791801'
		SET @FALLIDA = '73253003-55D7-4B25-929F-0F4A452E6F6B'
		SET @COMPLEMENT = '036ED0CF-35F0-4F1A-9DF7-0E782B1C3D1F'
		
		;with FECHAS (fecha, num_fecha) as (
		select  DATEADD(DAY, nbr - 1, @DIAS_IN) fecha, ROW_NUMBER() OVER (ORDER BY DATEADD(DAY, nbr - 1, @DIAS_IN)) num_fecha
		from    ( select ROW_NUMBER() OVER (ORDER BY c.object_id) as Nbr
				  from  sys.columns c) nbrs
		where   nbr - 1 <= DATEDIFF(DAY, @DIAS_IN, @FECHA_ACT)
		and datepart(DW,DATEADD(DAY, nbr - 1, @DIAS_IN)) not in (1,7)
		and DATEADD(DAY, nbr - 1, @DIAS_IN) not in (select c_date from CYCLE_DETAILS where c_date between @DIAS_IN and @FECHA_ACT and rec_stat=0 and c_day=0)
		)
		
		Select 
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
		
		/* DESPLEGADO DE VISITAS POR DIA */
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F1.FECHA) as VisEfectiva_1,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F2.FECHA) as VisEfectiva_2,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F3.FECHA) as VisEfectiva_3,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F4.FECHA) as VisEfectiva_4,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F5.FECHA) as VisEfectiva_5,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F6.FECHA) as VisEfectiva_6,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F7.FECHA) as VisEfectiva_7,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F8.FECHA) as VisEfectiva_8,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F9.FECHA) as VisEfectiva_9,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F10.FECHA) as VisEfectiva_10,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F11.FECHA) as VisEfectiva_11,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F12.FECHA) as VisEfectiva_12,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F13.FECHA) as VisEfectiva_13,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F14.FECHA) as VisEfectiva_14,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F15.FECHA) as VisEfectiva_15,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F16.FECHA) as VisEfectiva_16,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F17.FECHA) as VisEfectiva_17,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F18.FECHA) as VisEfectiva_18,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F19.FECHA) as VisEfectiva_19,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F20.FECHA) as VisEfectiva_20,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F21.FECHA) as VisEfectiva_21,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F22.FECHA) as VisEfectiva_22,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F23.FECHA) as VisEfectiva_23,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F24.FECHA) as VisEfectiva_24,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F25.FECHA) as VisEfectiva_25,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F26.FECHA) as VisEfectiva_26,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F27.FECHA) as VisEfectiva_27,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F28.FECHA) as VisEfectiva_28,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F29.FECHA) as VisEfectiva_29,
		(select count(pers_snr) from VISITPERS VEfec where VEfec.user_snr = MR.user_snr and VEfec.rec_stat=0 and VEfec.visit_code_snr = @PRESENCIAL and VEfec.visit_date = F30.FECHA) as VisEfectiva_30,
		
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F1.FECHA) as VisFallida_1,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F2.FECHA) as VisFallida_2,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F3.FECHA) as VisFallida_3,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F4.FECHA) as VisFallida_4,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F5.FECHA) as VisFallida_5,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F6.FECHA) as VisFallida_6,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F7.FECHA) as VisFallida_7,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F8.FECHA) as VisFallida_8,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F9.FECHA) as VisFallida_9,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F10.FECHA) as VisFallida_10,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F11.FECHA) as VisFallida_11,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F12.FECHA) as VisFallida_12,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F13.FECHA) as VisFallida_13,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F14.FECHA) as VisFallida_14,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F15.FECHA) as VisFallida_15,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F16.FECHA) as VisFallida_16,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F17.FECHA) as VisFallida_17,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F18.FECHA) as VisFallida_18,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F19.FECHA) as VisFallida_19,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F20.FECHA) as VisFallida_20,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F21.FECHA) as VisFallida_21,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F22.FECHA) as VisFallida_22,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F23.FECHA) as VisFallida_23,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F24.FECHA) as VisFallida_24,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F25.FECHA) as VisFallida_25,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F26.FECHA) as VisFallida_26,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F27.FECHA) as VisFallida_27,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F28.FECHA) as VisFallida_28,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F29.FECHA) as VisFallida_29,
		(select count(pers_snr) from VISITPERS VFall where VFall.user_snr = MR.user_snr and VFall.rec_stat=0 and VFall.visit_code_snr = @FALLIDA and VFall.visit_date = F30.FECHA) as VisFallida_30,
		
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F1.FECHA) as VisComplement_1,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F2.FECHA) as VisComplement_2,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F3.FECHA) as VisComplement_3,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F4.FECHA) as VisComplement_4,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F5.FECHA) as VisComplement_5,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F6.FECHA) as VisComplement_6,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F7.FECHA) as VisComplement_7,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F8.FECHA) as VisComplement_8,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F9.FECHA) as VisComplement_9,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F10.FECHA) as VisComplement_10,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F11.FECHA) as VisComplement_11,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F12.FECHA) as VisComplement_12,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F13.FECHA) as VisComplement_13,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F14.FECHA) as VisComplement_14,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F15.FECHA) as VisComplement_15,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F16.FECHA) as VisComplement_16,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F17.FECHA) as VisComplement_17,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F18.FECHA) as VisComplement_18,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F19.FECHA) as VisComplement_19,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F20.FECHA) as VisComplement_20,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F21.FECHA) as VisComplement_21,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F22.FECHA) as VisComplement_22,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F23.FECHA) as VisComplement_23,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F24.FECHA) as VisComplement_24,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F25.FECHA) as VisComplement_25,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F26.FECHA) as VisComplement_26,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F27.FECHA) as VisComplement_27,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F28.FECHA) as VisComplement_28,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F29.FECHA) as VisComplement_29,
		(select count(pers_snr) from VISITPERS VComp where VComp.user_snr = MR.user_snr and VComp.rec_stat=0 and VComp.visit_code_snr = @COMPLEMENT and VComp.visit_date = F30.FECHA) as VisComplement_30 
		
		
		from users DM, /*(select distinct reg_snr, kloc_snr, rec_stat from KLOC_REG) klr, users MR, */
		company CIA, compline LINEA 
		inner join (select distinct reg_snr, kloc_snr, rec_stat from KLOC_REG) klr on klr.rec_stat=0
		inner join users MR on MR.user_snr = klr.kloc_snr
		
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
		
		
		where klr.reg_snr = DM.user_snr
		/*and klr.kloc_snr = MR.user_snr*/
		and klr.rec_stat=0
		and MR.rec_stat=0
		and DM.rec_stat=0
		and MR.status in (1,2)
		and DM.status in (1,2)
		and MR.user_type = 4
		and DM.user_type = 5
		and MR.cline_snr = LINEA.cline_snr
		and CIA.comp_snr = LINEA.comp_snr
		and CIA.rec_stat=0
		and LINEA.rec_stat=0
		and MR.user_snr in ('".$ids."') 
		
		order by DM.user_nr,DM.lname,DM.fname,MR.user_nr,MR.lname,MR.fname,klr.reg_snr ";
	
	//echo $qMedicos."<br>";
		
	if($tipo == 1 || $tipo == 2){//excel
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=resumenDiarioVisitas.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else if($tipo == 3){
		ob_start();
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array(1300,3700));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('Resumen Diario de Visitas'));
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

	$tamTabla = 7400;
	$tabla = '';
	if( $tipo != 3){
			$tabla .= '<table border="0">
				<tr>
					<td>
						<table>
							<tr>
								<td colspan="10" class="nombreReporte">Resumen Diario de Visitas</td>
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
	$totalVisEfe1 = 0;
	$totalVisEfe2 = 0;
	$totalVisEfe3 = 0;
	$totalVisEfe4 = 0;
	$totalVisEfe5 = 0;
	$totalVisEfe6 = 0;
	$totalVisEfe7 = 0;
	$totalVisEfe8 = 0;
	$totalVisEfe9 = 0;
	$totalVisEfe10 = 0;
	$totalVisEfe11 = 0;
	$totalVisEfe12 = 0;
	$totalVisEfe13 = 0;
	$totalVisEfe14 = 0;
	$totalVisEfe15 = 0;
	$totalVisEfe16 = 0;
	$totalVisEfe17 = 0;
	$totalVisEfe18 = 0;
	$totalVisEfe19 = 0;
	$totalVisEfe20 = 0;
	$totalVisEfe21 = 0;
	$totalVisEfe22 = 0;
	$totalVisEfe23 = 0;
	$totalVisEfe24 = 0;
	$totalVisEfe25 = 0;
	$totalVisEfe26 = 0;
	$totalVisEfe27 = 0;
	$totalVisEfe28 = 0;
	$totalVisEfe29 = 0;
	$totalVisEfe30 = 0;
	$totalVisEfeTot = 0;

	$totalVisFall1 = 0;
	$totalVisFall2 = 0;
	$totalVisFall3 = 0;
	$totalVisFall4 = 0;
	$totalVisFall5 = 0;
	$totalVisFall6 = 0;
	$totalVisFall7 = 0;
	$totalVisFall8 = 0;
	$totalVisFall9 = 0;
	$totalVisFall10 = 0;
	$totalVisFall11 = 0;
	$totalVisFall12 = 0;
	$totalVisFall13 = 0;
	$totalVisFall14 = 0;
	$totalVisFall15 = 0;
	$totalVisFall16 = 0;
	$totalVisFall17 = 0;
	$totalVisFall18 = 0;
	$totalVisFall19 = 0;
	$totalVisFall20 = 0;
	$totalVisFall21 = 0;
	$totalVisFall22 = 0;
	$totalVisFall23 = 0;
	$totalVisFall24 = 0;
	$totalVisFall25 = 0;
	$totalVisFall26 = 0;
	$totalVisFall27 = 0;
	$totalVisFall28 = 0;
	$totalVisFall29 = 0;
	$totalVisFall30 = 0;
	$totalVisFallTot = 0;

	$totalVisComp1 = 0;
	$totalVisComp2 = 0;
	$totalVisComp3 = 0;
	$totalVisComp4 = 0;
	$totalVisComp5 = 0;
	$totalVisComp6 = 0;
	$totalVisComp7 = 0;
	$totalVisComp8 = 0;
	$totalVisComp9 = 0;
	$totalVisComp10 = 0;
	$totalVisComp11 = 0;
	$totalVisComp12 = 0;
	$totalVisComp13 = 0;
	$totalVisComp14 = 0;
	$totalVisComp15 = 0;
	$totalVisComp16 = 0;
	$totalVisComp17 = 0;
	$totalVisComp18 = 0;
	$totalVisComp19 = 0;
	$totalVisComp20 = 0;
	$totalVisComp21 = 0;
	$totalVisComp22 = 0;
	$totalVisComp23 = 0;
	$totalVisComp24 = 0;
	$totalVisComp25 = 0;
	$totalVisComp26 = 0;
	$totalVisComp27 = 0;
	$totalVisComp28 = 0;
	$totalVisComp29 = 0;
	$totalVisComp30 = 0;
	$totalVisCompTot = 0;
	
	$totalVisEfeT = 0;
	$totalVisFallT = 0;
	$totalVisCompT = 0;

	while($reg = sqlsrv_fetch_array($rsMedicos)){
		////suma nacional
		$totalVisEfe1 += $reg['VisEfectiva_1'];
		$totalVisEfe2 += $reg['VisEfectiva_2'];
		$totalVisEfe3 += $reg['VisEfectiva_3'];
		$totalVisEfe4 += $reg['VisEfectiva_4'];
		$totalVisEfe5 += $reg['VisEfectiva_5'];
		$totalVisEfe6 += $reg['VisEfectiva_6'];
		$totalVisEfe7 += $reg['VisEfectiva_7'];
		$totalVisEfe8 += $reg['VisEfectiva_8'];
		$totalVisEfe9 += $reg['VisEfectiva_9'];
		$totalVisEfe10 += $reg['VisEfectiva_10'];
		$totalVisEfe11 += $reg['VisEfectiva_11'];
		$totalVisEfe12 += $reg['VisEfectiva_12'];
		$totalVisEfe13 += $reg['VisEfectiva_13'];
		$totalVisEfe14 += $reg['VisEfectiva_14'];
		$totalVisEfe15 += $reg['VisEfectiva_15'];
		$totalVisEfe16 += $reg['VisEfectiva_16'];
		$totalVisEfe17 += $reg['VisEfectiva_17'];
		$totalVisEfe18 += $reg['VisEfectiva_18'];
		$totalVisEfe19 += $reg['VisEfectiva_19'];
		$totalVisEfe20 += $reg['VisEfectiva_20'];
		$totalVisEfe21 += $reg['VisEfectiva_21'];
		$totalVisEfe22 += $reg['VisEfectiva_22'];
		$totalVisEfe23 += $reg['VisEfectiva_23'];
		$totalVisEfe24 += $reg['VisEfectiva_24'];
		$totalVisEfe25 += $reg['VisEfectiva_25'];
		$totalVisEfe26 += $reg['VisEfectiva_26'];
		$totalVisEfe27 += $reg['VisEfectiva_27'];
		$totalVisEfe28 += $reg['VisEfectiva_28'];
		$totalVisEfe29 += $reg['VisEfectiva_29'];
		$totalVisEfe30 += $reg['VisEfectiva_30'];

		$totalVisFall1 += $reg['VisFallida_1'];
		$totalVisFall2 += $reg['VisFallida_2'];
		$totalVisFall3 += $reg['VisFallida_3'];
		$totalVisFall4 += $reg['VisFallida_4'];
		$totalVisFall5 += $reg['VisFallida_5'];
		$totalVisFall6 += $reg['VisFallida_6'];
		$totalVisFall7 += $reg['VisFallida_7'];
		$totalVisFall8 += $reg['VisFallida_8'];
		$totalVisFall9 += $reg['VisFallida_9'];
		$totalVisFall10 += $reg['VisFallida_10'];
		$totalVisFall11 += $reg['VisFallida_11'];
		$totalVisFall12 += $reg['VisFallida_12'];
		$totalVisFall13 += $reg['VisFallida_13'];
		$totalVisFall14 += $reg['VisFallida_14'];
		$totalVisFall15 += $reg['VisFallida_15'];
		$totalVisFall16 += $reg['VisFallida_16'];
		$totalVisFall17 += $reg['VisFallida_17'];
		$totalVisFall18 += $reg['VisFallida_18'];
		$totalVisFall19 += $reg['VisFallida_19'];
		$totalVisFall20 += $reg['VisFallida_20'];
		$totalVisFall21 += $reg['VisFallida_21'];
		$totalVisFall22 += $reg['VisFallida_22'];
		$totalVisFall23 += $reg['VisFallida_23'];
		$totalVisFall24 += $reg['VisFallida_24'];
		$totalVisFall25 += $reg['VisFallida_25'];
		$totalVisFall26 += $reg['VisFallida_26'];
		$totalVisFall27 += $reg['VisFallida_27'];
		$totalVisFall28 += $reg['VisFallida_28'];
		$totalVisFall29 += $reg['VisFallida_29'];
		$totalVisFall30 += $reg['VisFallida_30'];

		$totalVisComp1 += $reg['VisComplement_1'];
		$totalVisComp2 += $reg['VisComplement_2'];
		$totalVisComp3 += $reg['VisComplement_3'];
		$totalVisComp4 += $reg['VisComplement_4'];
		$totalVisComp5 += $reg['VisComplement_5'];
		$totalVisComp6 += $reg['VisComplement_6'];
		$totalVisComp7 += $reg['VisComplement_7'];
		$totalVisComp8 += $reg['VisComplement_8'];
		$totalVisComp9 += $reg['VisComplement_9'];
		$totalVisComp10 += $reg['VisComplement_10'];
		$totalVisComp11 += $reg['VisComplement_11'];
		$totalVisComp12 += $reg['VisComplement_12'];
		$totalVisComp13 += $reg['VisComplement_13'];
		$totalVisComp14 += $reg['VisComplement_14'];
		$totalVisComp15 += $reg['VisComplement_15'];
		$totalVisComp16 += $reg['VisComplement_16'];
		$totalVisComp17 += $reg['VisComplement_17'];
		$totalVisComp18 += $reg['VisComplement_18'];
		$totalVisComp19 += $reg['VisComplement_19'];
		$totalVisComp20 += $reg['VisComplement_20'];
		$totalVisComp21 += $reg['VisComplement_21'];
		$totalVisComp22 += $reg['VisComplement_22'];
		$totalVisComp23 += $reg['VisComplement_23'];
		$totalVisComp24 += $reg['VisComplement_24'];
		$totalVisComp25 += $reg['VisComplement_25'];
		$totalVisComp26 += $reg['VisComplement_26'];
		$totalVisComp27 += $reg['VisComplement_27'];
		$totalVisComp28 += $reg['VisComplement_28'];
		$totalVisComp29 += $reg['VisComplement_29'];
		$totalVisComp30 += $reg['VisComplement_30'];

		$totalVisEfeT = $totalVisEfe1+$totalVisEfe2+$totalVisEfe3+$totalVisEfe4+$totalVisEfe5+$totalVisEfe6+$totalVisEfe7+$totalVisEfe8+$totalVisEfe9+$totalVisEfe10
			+ $totalVisEfe11+$totalVisEfe12+$totalVisEfe13+$totalVisEfe14+$totalVisEfe15+$totalVisEfe16+$totalVisEfe17+$totalVisEfe18+$totalVisEfe19+$totalVisEfe20
			+ $totalVisEfe21+$totalVisEfe22+$totalVisEfe23+$totalVisEfe24+$totalVisEfe25+$totalVisEfe26+$totalVisEfe27+$totalVisEfe28+$totalVisEfe29+$totalVisEfe30;		

		$totalVisFallT = $totalVisFall1+$totalVisFall2+$totalVisFall3+$totalVisFall4+$totalVisFall5+$totalVisFall6+$totalVisFall7+$totalVisFall8+$totalVisFall9+$totalVisFall10
			+ $totalVisFall11+$totalVisFall12+$totalVisFall13+$totalVisFall14+$totalVisFall15+$totalVisFall16+$totalVisFall17+$totalVisFall18+$totalVisFall19+$totalVisFall20
			+ $totalVisFall21+$totalVisFall22+$totalVisFall23+$totalVisFall24+$totalVisFall25+$totalVisFall26+$totalVisFall27+$totalVisFall28+$totalVisFall29+$totalVisFall30;		

		$totalVisCompT  = $totalVisComp1+$totalVisComp2+$totalVisComp3+$totalVisComp4+$totalVisComp5+$totalVisComp6+$totalVisComp7+$totalVisComp8+$totalVisComp9+$totalVisComp10
			+ $totalVisComp11+$totalVisComp12+$totalVisComp13+$totalVisComp14+$totalVisComp15+$totalVisComp16+$totalVisComp17+$totalVisComp18+$totalVisComp19+$totalVisComp20
			+ $totalVisComp21+$totalVisComp22+$totalVisComp23+$totalVisComp24+$totalVisComp25+$totalVisComp26+$totalVisComp27+$totalVisComp28+$totalVisComp29+$totalVisComp30;
		
		$NumDia = $reg['NDiasTransc'] + 1;
		$NumDiaT = ($reg['NDiasTransc'] + 1) * 3;
		$NumDiapdf = ($NumDia * 50);
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
				$tabla .= '<td '.$estilocabecera.' rowspan="3" width="100px">Linea</td>';
				$tabla .= '<td '.$estilocabecera.' rowspan="3" width="100px">Ruta</td>';
				$tabla .= '<td '.$estilocabecera.' rowspan="3" width="400px">Nombre</td>';
				$tabla .= '<td '.$estilocabecera.' colspan='.$NumDiaT.' width="100px" align="center">Numero de Visitas por Fecha</td></tr>';
				$tabla .= '<td '.$estilocabecera.' colspan='.$NumDia.' width="100px" align="center">Visita Presencial</td>';
				$tabla .= '<td '.$estilocabecera.' colspan='.$NumDia.' width="100px" align="center">Visita Fallida</td>';
				$tabla .= '<td '.$estilocabecera.' colspan='.$NumDia.' width="100px" align="center">Accion Complementaria</td></tr>';
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

				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia1.'</td>';
				if($Dia2 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="cente0r">'.$Dia2.'</td>';
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

				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$Dia1.'</td>';
				if($Dia2 != 'NO') $tabla .= '<td '.$estilocabecera.' width="100px" align="cente0r">'.$Dia2.'</td>';
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
				/*
				$pdf->Cell(100,10,'','LT',0,'L',1);
				$pdf->Cell(200,10,'','LTR',0,'L',1);
				$pdf->Cell(50,10,'','LTR',0,'L',1);
				$pdf->Cell(200,10,'','LTR',0,'L',1);
				$pdf->Cell(400,10,'Numero de Visitas por Tipo de Visita / Canal','LTR',0,'C',1);
				$pdf->Ln();	
				$pdf->Cell(100,10,'Distrito','LR',0,'L',1);
				$pdf->Cell(200,10,'Gerente','LR',0,'L',1);
				$pdf->Cell(50,10,'Ruta','LR',0,'L',1);
				$pdf->Cell(200,10,'Nombre','LR',0,'L',1);
				$pdf->Cell(200,10,'Virtual','LTR',0,'C',1);
				$pdf->Cell(50,10,'Total',1,0,'C',1);
				*/
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
			$gteVisEfe1 = $reg['VisEfectiva_1'];
			$gteVisEfe2 = $reg['VisEfectiva_2'];
			$gteVisEfe3 = $reg['VisEfectiva_3'];
			$gteVisEfe4 = $reg['VisEfectiva_4'];
			$gteVisEfe5 = $reg['VisEfectiva_5'];
			$gteVisEfe6 = $reg['VisEfectiva_6'];
			$gteVisEfe7 = $reg['VisEfectiva_7'];
			$gteVisEfe8 = $reg['VisEfectiva_8'];
			$gteVisEfe9 = $reg['VisEfectiva_9'];
			$gteVisEfe10 = $reg['VisEfectiva_10'];
			$gteVisEfe11 = $reg['VisEfectiva_11'];
			$gteVisEfe12 = $reg['VisEfectiva_12'];
			$gteVisEfe13 = $reg['VisEfectiva_13'];
			$gteVisEfe14 = $reg['VisEfectiva_14'];
			$gteVisEfe15 = $reg['VisEfectiva_15'];
			$gteVisEfe16 = $reg['VisEfectiva_16'];
			$gteVisEfe17 = $reg['VisEfectiva_17'];
			$gteVisEfe18 = $reg['VisEfectiva_18'];
			$gteVisEfe19 = $reg['VisEfectiva_19'];
			$gteVisEfe20 = $reg['VisEfectiva_20'];
			$gteVisEfe21 = $reg['VisEfectiva_21'];
			$gteVisEfe22 = $reg['VisEfectiva_22'];
			$gteVisEfe23 = $reg['VisEfectiva_23'];
			$gteVisEfe24 = $reg['VisEfectiva_24'];
			$gteVisEfe25 = $reg['VisEfectiva_25'];
			$gteVisEfe26 = $reg['VisEfectiva_26'];
			$gteVisEfe27 = $reg['VisEfectiva_27'];
			$gteVisEfe28 = $reg['VisEfectiva_28'];
			$gteVisEfe29 = $reg['VisEfectiva_29'];
			$gteVisEfe30 = $reg['VisEfectiva_30'];

			$gteVisFall1 = $reg['VisFallida_1'];
			$gteVisFall2 = $reg['VisFallida_2'];
			$gteVisFall3 = $reg['VisFallida_3'];
			$gteVisFall4 = $reg['VisFallida_4'];
			$gteVisFall5 = $reg['VisFallida_5'];
			$gteVisFall6 = $reg['VisFallida_6'];
			$gteVisFall7 = $reg['VisFallida_7'];
			$gteVisFall8 = $reg['VisFallida_8'];
			$gteVisFall9 = $reg['VisFallida_9'];
			$gteVisFall10 = $reg['VisFallida_10'];
			$gteVisFall11 = $reg['VisFallida_11'];
			$gteVisFall12 = $reg['VisFallida_12'];
			$gteVisFall13 = $reg['VisFallida_13'];
			$gteVisFall14 = $reg['VisFallida_14'];
			$gteVisFall15 = $reg['VisFallida_15'];
			$gteVisFall16 = $reg['VisFallida_16'];
			$gteVisFall17 = $reg['VisFallida_17'];
			$gteVisFall18 = $reg['VisFallida_18'];
			$gteVisFall19 = $reg['VisFallida_19'];
			$gteVisFall20 = $reg['VisFallida_20'];
			$gteVisFall21 = $reg['VisFallida_21'];
			$gteVisFall22 = $reg['VisFallida_22'];
			$gteVisFall23 = $reg['VisFallida_23'];
			$gteVisFall24 = $reg['VisFallida_24'];
			$gteVisFall25 = $reg['VisFallida_25'];
			$gteVisFall26 = $reg['VisFallida_26'];
			$gteVisFall27 = $reg['VisFallida_27'];
			$gteVisFall28 = $reg['VisFallida_28'];
			$gteVisFall29 = $reg['VisFallida_29'];
			$gteVisFall30 = $reg['VisFallida_30'];

			$gteVisComp1 = $reg['VisComplement_1'];
			$gteVisComp2 = $reg['VisComplement_2'];
			$gteVisComp3 = $reg['VisComplement_3'];
			$gteVisComp4 = $reg['VisComplement_4'];
			$gteVisComp5 = $reg['VisComplement_5'];
			$gteVisComp6 = $reg['VisComplement_6'];
			$gteVisComp7 = $reg['VisComplement_7'];
			$gteVisComp8 = $reg['VisComplement_8'];
			$gteVisComp9 = $reg['VisComplement_9'];
			$gteVisComp10 = $reg['VisComplement_10'];
			$gteVisComp11 = $reg['VisComplement_11'];
			$gteVisComp12 = $reg['VisComplement_12'];
			$gteVisComp13 = $reg['VisComplement_13'];
			$gteVisComp14 = $reg['VisComplement_14'];
			$gteVisComp15 = $reg['VisComplement_15'];
			$gteVisComp16 = $reg['VisComplement_16'];
			$gteVisComp17 = $reg['VisComplement_17'];
			$gteVisComp18 = $reg['VisComplement_18'];
			$gteVisComp19 = $reg['VisComplement_19'];
			$gteVisComp20 = $reg['VisComplement_20'];
			$gteVisComp21 = $reg['VisComplement_21'];
			$gteVisComp22 = $reg['VisComplement_22'];
			$gteVisComp23 = $reg['VisComplement_23'];
			$gteVisComp24 = $reg['VisComplement_24'];
			$gteVisComp25 = $reg['VisComplement_25'];
			$gteVisComp26 = $reg['VisComplement_26'];
			$gteVisComp27 = $reg['VisComplement_27'];
			$gteVisComp28 = $reg['VisComplement_28'];
			$gteVisComp29 = $reg['VisComplement_29'];
			$gteVisComp30 = $reg['VisComplement_30'];
			
			$gteVisEfeT = $gteVisEfe1+$gteVisEfe2+$gteVisEfe3+$gteVisEfe4+$gteVisEfe5+$gteVisEfe6+$gteVisEfe7+$gteVisEfe8+$gteVisEfe9+$gteVisEfe10
				+ $gteVisEfe11+$gteVisEfe12+$gteVisEfe13+$gteVisEfe14+$gteVisEfe15+$gteVisEfe16+$gteVisEfe17+$gteVisEfe18+$gteVisEfe19+$gteVisEfe20
				+ $gteVisEfe21+$gteVisEfe22+$gteVisEfe23+$gteVisEfe24+$gteVisEfe25+$gteVisEfe26+$gteVisEfe27+$gteVisEfe28+$gteVisEfe29+$gteVisEfe30;

			$gteVisFallT = $gteVisFall1+$gteVisFall2+$gteVisFall3+$gteVisFall4+$gteVisFall5+$gteVisFall6+$gteVisFall7+$gteVisFall8+$gteVisFall9+$gteVisFall10
				+ $gteVisFall11+$gteVisFall12+$gteVisFall13+$gteVisFall14+$gteVisFall15+$gteVisFall16+$gteVisFall17+$gteVisFall18+$gteVisFall19+$gteVisFall20
				+ $gteVisFall21+$gteVisFall22+$gteVisFall23+$gteVisFall24+$gteVisFall25+$gteVisFall26+$gteVisFall27+$gteVisFall28+$gteVisFall29+$gteVisFall30;

			$gteVisCompT = $gteVisComp1+$gteVisComp2+$gteVisComp3+$gteVisComp4+$gteVisComp5+$gteVisComp6+$gteVisComp7+$gteVisComp8+$gteVisComp9+$gteVisComp10
				+ $gteVisComp11+$gteVisComp12+$gteVisComp13+$gteVisComp14+$gteVisComp15+$gteVisComp16+$gteVisComp17+$gteVisComp18+$gteVisComp19+$gteVisComp20
				+ $gteVisComp21+$gteVisComp22+$gteVisComp23+$gteVisComp24+$gteVisComp25+$gteVisComp26+$gteVisComp27+$gteVisComp28+$gteVisComp29+$gteVisComp30;
			
		}else{
			////sumas gerentes
			$gerente = $reg['REG_SNR'];		
			if($tempGerente == $gerente){
				$sumVisEfe1 = $reg['VisEfectiva_1'];
				$gteVisEfe1 += $sumVisEfe1;
				$sumVisEfe2 = $reg['VisEfectiva_2'];
				$gteVisEfe2 += $sumVisEfe2;
				$sumVisEfe3 = $reg['VisEfectiva_3'];
				$gteVisEfe3 += $sumVisEfe3;
				$sumVisEfe4 = $reg['VisEfectiva_4'];
				$gteVisEfe4 += $sumVisEfe4;
				$sumVisEfe5 = $reg['VisEfectiva_5'];
				$gteVisEfe5 += $sumVisEfe5;
				$sumVisEfe6 = $reg['VisEfectiva_6'];
				$gteVisEfe6 += $sumVisEfe6;
				$sumVisEfe7 = $reg['VisEfectiva_7'];
				$gteVisEfe7 += $sumVisEfe7;
				$sumVisEfe8 = $reg['VisEfectiva_8'];
				$gteVisEfe8 += $sumVisEfe8;
				$sumVisEfe9 = $reg['VisEfectiva_9'];
				$gteVisEfe9 += $sumVisEfe9;
				$sumVisEfe10 = $reg['VisEfectiva_10'];
				$gteVisEfe10 += $sumVisEfe10;
				$sumVisEfe11 = $reg['VisEfectiva_11'];
				$gteVisEfe11 += $sumVisEfe11;
				$sumVisEfe12 = $reg['VisEfectiva_12'];
				$gteVisEfe12 += $sumVisEfe12;
				$sumVisEfe13 = $reg['VisEfectiva_13'];
				$gteVisEfe13 += $sumVisEfe13;
				$sumVisEfe14 = $reg['VisEfectiva_14'];
				$gteVisEfe14 += $sumVisEfe14;
				$sumVisEfe15 = $reg['VisEfectiva_15'];
				$gteVisEfe15 += $sumVisEfe15;
				$sumVisEfe16 = $reg['VisEfectiva_16'];
				$gteVisEfe16 += $sumVisEfe16;
				$sumVisEfe17 = $reg['VisEfectiva_17'];
				$gteVisEfe17 += $sumVisEfe17;
				$sumVisEfe18 = $reg['VisEfectiva_18'];
				$gteVisEfe18 += $sumVisEfe18;
				$sumVisEfe19 = $reg['VisEfectiva_19'];
				$gteVisEfe19 += $sumVisEfe19;
				$sumVisEfe20 = $reg['VisEfectiva_20'];
				$gteVisEfe20 += $sumVisEfe20;
				$sumVisEfe21 = $reg['VisEfectiva_21'];
				$gteVisEfe21 += $sumVisEfe21;
				$sumVisEfe22 = $reg['VisEfectiva_22'];
				$gteVisEfe22 += $sumVisEfe22;
				$sumVisEfe23 = $reg['VisEfectiva_23'];
				$gteVisEfe23 += $sumVisEfe23;
				$sumVisEfe24 = $reg['VisEfectiva_24'];
				$gteVisEfe24 += $sumVisEfe24;
				$sumVisEfe25 = $reg['VisEfectiva_25'];
				$gteVisEfe25 += $sumVisEfe25;
				$sumVisEfe26 = $reg['VisEfectiva_26'];
				$gteVisEfe26 += $sumVisEfe26;
				$sumVisEfe27 = $reg['VisEfectiva_27'];
				$gteVisEfe27 += $sumVisEfe27;
				$sumVisEfe28 = $reg['VisEfectiva_28'];
				$gteVisEfe28 += $sumVisEfe28;
				$sumVisEfe29 = $reg['VisEfectiva_29'];
				$gteVisEfe29 += $sumVisEfe29;
				$sumVisEfe30 = $reg['VisEfectiva_30'];
				$gteVisEfe30 += $sumVisEfe30;

				$sumVisFall1 = $reg['VisFallida_1'];
				$gteVisFall1 += $sumVisFall1;
				$sumVisFall2 = $reg['VisFallida_2'];
				$gteVisFall2 += $sumVisFall2;
				$sumVisFall3 = $reg['VisFallida_3'];
				$gteVisFall3 += $sumVisFall3;
				$sumVisFall4 = $reg['VisFallida_4'];
				$gteVisFall4 += $sumVisFall4;
				$sumVisFall5 = $reg['VisFallida_5'];
				$gteVisFall5 += $sumVisFall5;
				$sumVisFall6 = $reg['VisFallida_6'];
				$gteVisFall6 += $sumVisFall6;
				$sumVisFall7 = $reg['VisFallida_7'];
				$gteVisFall7 += $sumVisFall7;
				$sumVisFall8 = $reg['VisFallida_8'];
				$gteVisFall8 += $sumVisFall8;
				$sumVisFall9 = $reg['VisFallida_9'];
				$gteVisFall9 += $sumVisFall9;
				$sumVisFall10 = $reg['VisFallida_10'];
				$gteVisFall10 += $sumVisFall10;
				$sumVisFall11 = $reg['VisFallida_11'];
				$gteVisFall11 += $sumVisFall11;
				$sumVisFall12 = $reg['VisFallida_12'];
				$gteVisFall12 += $sumVisFall12;
				$sumVisFall13 = $reg['VisFallida_13'];
				$gteVisFall13 += $sumVisFall13;
				$sumVisFall14 = $reg['VisFallida_14'];
				$gteVisFall14 += $sumVisFall14;
				$sumVisFall15 = $reg['VisFallida_15'];
				$gteVisFall15 += $sumVisFall15;
				$sumVisFall16 = $reg['VisFallida_16'];
				$gteVisFall16 += $sumVisFall16;
				$sumVisFall17 = $reg['VisFallida_17'];
				$gteVisFall17 += $sumVisFall17;
				$sumVisFall18 = $reg['VisFallida_18'];
				$gteVisFall18 += $sumVisFall18;
				$sumVisFall19 = $reg['VisFallida_19'];
				$gteVisFall19 += $sumVisFall19;
				$sumVisFall20 = $reg['VisFallida_20'];
				$gteVisFall20 += $sumVisFall20;
				$sumVisFall21 = $reg['VisFallida_21'];
				$gteVisFall21 += $sumVisFall21;
				$sumVisFall22 = $reg['VisFallida_22'];
				$gteVisFall22 += $sumVisFall22;
				$sumVisFall23 = $reg['VisFallida_23'];
				$gteVisFall23 += $sumVisFall23;
				$sumVisFall24 = $reg['VisFallida_24'];
				$gteVisFall24 += $sumVisFall24;
				$sumVisFall25 = $reg['VisFallida_25'];
				$gteVisFall25 += $sumVisFall25;
				$sumVisFall26 = $reg['VisFallida_26'];
				$gteVisFall26 += $sumVisFall26;
				$sumVisFall27 = $reg['VisFallida_27'];
				$gteVisFall27 += $sumVisFall27;
				$sumVisFall28 = $reg['VisFallida_28'];
				$gteVisFall28 += $sumVisFall28;
				$sumVisFall29 = $reg['VisFallida_29'];
				$gteVisFall29 += $sumVisFall29;
				$sumVisFall30 = $reg['VisFallida_30'];
				$gteVisFall30 += $sumVisFall30;

				$sumVisComp1 = $reg['VisComplement_1'];
				$gteVisComp1 += $sumVisComp1;
				$sumVisComp2 = $reg['VisComplement_2'];
				$gteVisComp2 += $sumVisComp2;
				$sumVisComp3 = $reg['VisComplement_3'];
				$gteVisComp3 += $sumVisComp3;
				$sumVisComp4 = $reg['VisComplement_4'];
				$gteVisComp4 += $sumVisComp4;
				$sumVisComp5 = $reg['VisComplement_5'];
				$gteVisComp5 += $sumVisComp5;
				$sumVisComp6 = $reg['VisComplement_6'];
				$gteVisComp6 += $sumVisComp6;
				$sumVisComp7 = $reg['VisComplement_7'];
				$gteVisComp7 += $sumVisComp7;
				$sumVisComp8 = $reg['VisComplement_8'];
				$gteVisComp8 += $sumVisComp8;
				$sumVisComp9 = $reg['VisComplement_9'];
				$gteVisComp9 += $sumVisComp9;
				$sumVisComp10 = $reg['VisComplement_10'];
				$gteVisComp10 += $sumVisComp10;
				$sumVisComp11 = $reg['VisComplement_11'];
				$gteVisComp11 += $sumVisComp11;
				$sumVisComp12 = $reg['VisComplement_12'];
				$gteVisComp12 += $sumVisComp12;
				$sumVisComp13 = $reg['VisComplement_13'];
				$gteVisComp13 += $sumVisComp13;
				$sumVisComp14 = $reg['VisComplement_14'];
				$gteVisComp14 += $sumVisComp14;
				$sumVisComp15 = $reg['VisComplement_15'];
				$gteVisComp15 += $sumVisComp15;
				$sumVisComp16 = $reg['VisComplement_16'];
				$gteVisComp16 += $sumVisComp16;
				$sumVisComp17 = $reg['VisComplement_17'];
				$gteVisComp17 += $sumVisComp17;
				$sumVisComp18 = $reg['VisComplement_18'];
				$gteVisComp18 += $sumVisComp18;
				$sumVisComp19 = $reg['VisComplement_19'];
				$gteVisComp19 += $sumVisComp19;
				$sumVisComp20 = $reg['VisComplement_20'];
				$gteVisComp20 += $sumVisComp20;
				$sumVisComp21 = $reg['VisComplement_21'];
				$gteVisComp21 += $sumVisComp21;
				$sumVisComp22 = $reg['VisComplement_22'];
				$gteVisComp22 += $sumVisComp22;
				$sumVisComp23 = $reg['VisComplement_23'];
				$gteVisComp23 += $sumVisComp23;
				$sumVisComp24 = $reg['VisComplement_24'];
				$gteVisComp24 += $sumVisComp24;
				$sumVisComp25 = $reg['VisComplement_25'];
				$gteVisComp25 += $sumVisComp25;
				$sumVisComp26 = $reg['VisComplement_26'];
				$gteVisComp26 += $sumVisComp26;
				$sumVisComp27 = $reg['VisComplement_27'];
				$gteVisComp27 += $sumVisComp27;
				$sumVisComp28 = $reg['VisComplement_28'];
				$gteVisComp28 += $sumVisComp28;
				$sumVisComp29 = $reg['VisComplement_29'];
				$gteVisComp29 += $sumVisComp29;
				$sumVisComp30 = $reg['VisComplement_30'];
				$gteVisComp30 += $sumVisComp30;
				
				$gteVisEfeT = $gteVisEfe1+$gteVisEfe2+$gteVisEfe3+$gteVisEfe4+$gteVisEfe5+$gteVisEfe6+$gteVisEfe7+$gteVisEfe8+$gteVisEfe9+$gteVisEfe10
					+ $gteVisEfe11+$gteVisEfe12+$gteVisEfe13+$gteVisEfe14+$gteVisEfe15+$gteVisEfe16+$gteVisEfe17+$gteVisEfe18+$gteVisEfe19+$gteVisEfe20
					+ $gteVisEfe21+$gteVisEfe22+$gteVisEfe23+$gteVisEfe24+$gteVisEfe25+$gteVisEfe26+$gteVisEfe27+$gteVisEfe28+$gteVisEfe29+$gteVisEfe30;

				$gteVisFallT = $gteVisFall1+$gteVisFall2+$gteVisFall3+$gteVisFall4+$gteVisFall5+$gteVisFall6+$gteVisFall7+$gteVisFall8+$gteVisFall9+$gteVisFall10
					+ $gteVisFall11+$gteVisFall12+$gteVisFall13+$gteVisFall14+$gteVisFall15+$gteVisFall16+$gteVisFall17+$gteVisFall18+$gteVisFall19+$gteVisFall20
					+ $gteVisFall21+$gteVisFall22+$gteVisFall23+$gteVisFall24+$gteVisFall25+$gteVisFall26+$gteVisFall27+$gteVisFall28+$gteVisFall29+$gteVisFall30;

				$gteVisCompT = $gteVisComp1+$gteVisComp2+$gteVisComp3+$gteVisComp4+$gteVisComp5+$gteVisComp6+$gteVisComp7+$gteVisComp8+$gteVisComp9+$gteVisComp10
					+ $gteVisComp11+$gteVisComp12+$gteVisComp13+$gteVisComp14+$gteVisComp15+$gteVisComp16+$gteVisComp17+$gteVisComp18+$gteVisComp19+$gteVisComp20
					+ $gteVisComp21+$gteVisComp22+$gteVisComp23+$gteVisComp24+$gteVisComp25+$gteVisComp26+$gteVisComp27+$gteVisComp28+$gteVisComp29+$gteVisComp30;

			}else{	
				////imprimir gerentes
				if($tipo != 3){	
					$tabla .= '<tr><td '.$estilogte.' width="100px"> </td>';
					$tabla .= '<td '.$estilogte.' width="100px">'.$rutaGte.'</td>';
					$tabla .= '<td '.$estilogte.' width="400px">'.$nombreGte.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe1).'</td>';
					if($Dia2 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe2).'</td>';
					if($Dia3 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe3).'</td>';
					if($Dia4 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe4).'</td>';
					if($Dia5 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe5).'</td>';
					if($Dia6 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe6).'</td>';
					if($Dia7 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe7).'</td>';
					if($Dia8 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe8).'</td>';
					if($Dia9 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe9).'</td>';
					if($Dia10 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe10).'</td>';
					if($Dia11 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe11).'</td>';
					if($Dia12 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe12).'</td>';
					if($Dia13 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe13).'</td>';
					if($Dia14 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe14).'</td>';
					if($Dia15 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe15).'</td>';
					if($Dia16 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe16).'</td>';
					if($Dia17 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe17).'</td>';
					if($Dia18 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe18).'</td>';
					if($Dia19 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe19).'</td>';
					if($Dia20 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe20).'</td>';
					if($Dia21 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe21).'</td>';
					if($Dia22 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe22).'</td>';
					if($Dia23 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe23).'</td>';
					if($Dia24 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe24).'</td>';
					if($Dia25 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe25).'</td>';
					if($Dia26 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe26).'</td>';
					if($Dia27 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe27).'</td>';
					if($Dia28 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe28).'</td>';
					if($Dia29 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe29).'</td>';
					if($Dia30 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe30).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfeT).'</td>';

					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall1).'</td>';
					if($Dia2 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall2).'</td>';
					if($Dia3 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall3).'</td>';
					if($Dia4 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall4).'</td>';
					if($Dia5 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall5).'</td>';
					if($Dia6 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall6).'</td>';
					if($Dia7 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall7).'</td>';
					if($Dia8 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall8).'</td>';
					if($Dia9 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall9).'</td>';
					if($Dia10 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall10).'</td>';
					if($Dia11 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall11).'</td>';
					if($Dia12 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall12).'</td>';
					if($Dia13 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall13).'</td>';
					if($Dia14 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall14).'</td>';
					if($Dia15 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall15).'</td>';
					if($Dia16 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall16).'</td>';
					if($Dia17 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall17).'</td>';
					if($Dia18 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall18).'</td>';
					if($Dia19 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall19).'</td>';
					if($Dia20 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall20).'</td>';
					if($Dia21 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall21).'</td>';
					if($Dia22 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall22).'</td>';
					if($Dia23 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall23).'</td>';
					if($Dia24 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall24).'</td>';
					if($Dia25 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall25).'</td>';
					if($Dia26 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall26).'</td>';
					if($Dia27 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall27).'</td>';
					if($Dia28 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall28).'</td>';
					if($Dia29 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall29).'</td>';
					if($Dia30 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall30).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFallT).'</td>';

					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp1).'</td>';
					if($Dia2 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp2).'</td>';
					if($Dia3 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp3).'</td>';
					if($Dia4 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp4).'</td>';
					if($Dia5 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp5).'</td>';
					if($Dia6 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp6).'</td>';
					if($Dia7 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp7).'</td>';
					if($Dia8 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp8).'</td>';
					if($Dia9 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp9).'</td>';
					if($Dia10 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp10).'</td>';
					if($Dia11 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp11).'</td>';
					if($Dia12 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp12).'</td>';
					if($Dia13 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp13).'</td>';
					if($Dia14 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp14).'</td>';
					if($Dia15 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp15).'</td>';
					if($Dia16 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp16).'</td>';
					if($Dia17 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp17).'</td>';
					if($Dia18 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp18).'</td>';
					if($Dia19 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp19).'</td>';
					if($Dia20 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp20).'</td>';
					if($Dia21 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp21).'</td>';
					if($Dia22 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp22).'</td>';
					if($Dia23 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp23).'</td>';
					if($Dia24 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp24).'</td>';
					if($Dia25 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp25).'</td>';
					if($Dia26 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp26).'</td>';
					if($Dia27 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp27).'</td>';
					if($Dia28 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp28).'</td>';
					if($Dia29 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp29).'</td>';
					if($Dia30 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp30).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisCompT).'</td>';
					$tabla .= '</tr>';
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
				$gteVisEfe1 = $reg['VisEfectiva_1'];
				$gteVisEfe2 = $reg['VisEfectiva_2'];
				$gteVisEfe3 = $reg['VisEfectiva_3'];
				$gteVisEfe4 = $reg['VisEfectiva_4'];
				$gteVisEfe5 = $reg['VisEfectiva_5'];
				$gteVisEfe6 = $reg['VisEfectiva_6'];
				$gteVisEfe7 = $reg['VisEfectiva_7'];
				$gteVisEfe8 = $reg['VisEfectiva_8'];
				$gteVisEfe9 = $reg['VisEfectiva_9'];
				$gteVisEfe10 = $reg['VisEfectiva_10'];
				$gteVisEfe11 = $reg['VisEfectiva_11'];
				$gteVisEfe12 = $reg['VisEfectiva_12'];
				$gteVisEfe13 = $reg['VisEfectiva_13'];
				$gteVisEfe14 = $reg['VisEfectiva_14'];
				$gteVisEfe15 = $reg['VisEfectiva_15'];
				$gteVisEfe16 = $reg['VisEfectiva_16'];
				$gteVisEfe17 = $reg['VisEfectiva_17'];
				$gteVisEfe18 = $reg['VisEfectiva_18'];
				$gteVisEfe19 = $reg['VisEfectiva_19'];
				$gteVisEfe20 = $reg['VisEfectiva_20'];
				$gteVisEfe21 = $reg['VisEfectiva_21'];
				$gteVisEfe22 = $reg['VisEfectiva_22'];
				$gteVisEfe23 = $reg['VisEfectiva_23'];
				$gteVisEfe24 = $reg['VisEfectiva_24'];
				$gteVisEfe25 = $reg['VisEfectiva_25'];
				$gteVisEfe26 = $reg['VisEfectiva_26'];
				$gteVisEfe27 = $reg['VisEfectiva_27'];
				$gteVisEfe28 = $reg['VisEfectiva_28'];
				$gteVisEfe29 = $reg['VisEfectiva_29'];
				$gteVisEfe30 = $reg['VisEfectiva_30'];

				$gteVisFall1 = $reg['VisFallida_1'];
				$gteVisFall2 = $reg['VisFallida_2'];
				$gteVisFall3 = $reg['VisFallida_3'];
				$gteVisFall4 = $reg['VisFallida_4'];
				$gteVisFall5 = $reg['VisFallida_5'];
				$gteVisFall6 = $reg['VisFallida_6'];
				$gteVisFall7 = $reg['VisFallida_7'];
				$gteVisFall8 = $reg['VisFallida_8'];
				$gteVisFall9 = $reg['VisFallida_9'];
				$gteVisFall10 = $reg['VisFallida_10'];
				$gteVisFall11 = $reg['VisFallida_11'];
				$gteVisFall12 = $reg['VisFallida_12'];
				$gteVisFall13 = $reg['VisFallida_13'];
				$gteVisFall14 = $reg['VisFallida_14'];
				$gteVisFall15 = $reg['VisFallida_15'];
				$gteVisFall16 = $reg['VisFallida_16'];
				$gteVisFall17 = $reg['VisFallida_17'];
				$gteVisFall18 = $reg['VisFallida_18'];
				$gteVisFall19 = $reg['VisFallida_19'];
				$gteVisFall20 = $reg['VisFallida_20'];
				$gteVisFall21 = $reg['VisFallida_21'];
				$gteVisFall22 = $reg['VisFallida_22'];
				$gteVisFall23 = $reg['VisFallida_23'];
				$gteVisFall24 = $reg['VisFallida_24'];
				$gteVisFall25 = $reg['VisFallida_25'];
				$gteVisFall26 = $reg['VisFallida_26'];
				$gteVisFall27 = $reg['VisFallida_27'];
				$gteVisFall28 = $reg['VisFallida_28'];
				$gteVisFall29 = $reg['VisFallida_29'];
				$gteVisFall30 = $reg['VisFallida_30'];

				$gteVisComp1 = $reg['VisComplement_1'];
				$gteVisComp2 = $reg['VisComplement_2'];
				$gteVisComp3 = $reg['VisComplement_3'];
				$gteVisComp4 = $reg['VisComplement_4'];
				$gteVisComp5 = $reg['VisComplement_5'];
				$gteVisComp6 = $reg['VisComplement_6'];
				$gteVisComp7 = $reg['VisComplement_7'];
				$gteVisComp8 = $reg['VisComplement_8'];
				$gteVisComp9 = $reg['VisComplement_9'];
				$gteVisComp10 = $reg['VisComplement_10'];
				$gteVisComp11 = $reg['VisComplement_11'];
				$gteVisComp12 = $reg['VisComplement_12'];
				$gteVisComp13 = $reg['VisComplement_13'];
				$gteVisComp14 = $reg['VisComplement_14'];
				$gteVisComp15 = $reg['VisComplement_15'];
				$gteVisComp16 = $reg['VisComplement_16'];
				$gteVisComp17 = $reg['VisComplement_17'];
				$gteVisComp18 = $reg['VisComplement_18'];
				$gteVisComp19 = $reg['VisComplement_19'];
				$gteVisComp20 = $reg['VisComplement_20'];
				$gteVisComp21 = $reg['VisComplement_21'];
				$gteVisComp22 = $reg['VisComplement_22'];
				$gteVisComp23 = $reg['VisComplement_23'];
				$gteVisComp24 = $reg['VisComplement_24'];
				$gteVisComp25 = $reg['VisComplement_25'];
				$gteVisComp26 = $reg['VisComplement_26'];
				$gteVisComp27 = $reg['VisComplement_27'];
				$gteVisComp28 = $reg['VisComplement_28'];
				$gteVisComp29 = $reg['VisComplement_29'];
				$gteVisComp30 = $reg['VisComplement_30'];
				
				$gteVisEfeT = $gteVisEfe1+$gteVisEfe2+$gteVisEfe3+$gteVisEfe4+$gteVisEfe5+$gteVisEfe6+$gteVisEfe7+$gteVisEfe8+$gteVisEfe9+$gteVisEfe10
					+ $gteVisEfe11+$gteVisEfe12+$gteVisEfe13+$gteVisEfe14+$gteVisEfe15+$gteVisEfe16+$gteVisEfe17+$gteVisEfe18+$gteVisEfe19+$gteVisEfe20
					+ $gteVisEfe21+$gteVisEfe22+$gteVisEfe23+$gteVisEfe24+$gteVisEfe25+$gteVisEfe26+$gteVisEfe27+$gteVisEfe28+$gteVisEfe29+$gteVisEfe30;

				$gteVisFallT = $gteVisFall1+$gteVisFall2+$gteVisFall3+$gteVisFall4+$gteVisFall5+$gteVisFall6+$gteVisFall7+$gteVisFall8+$gteVisFall9+$gteVisFall10
					+ $gteVisFall11+$gteVisFall12+$gteVisFall13+$gteVisFall14+$gteVisFall15+$gteVisFall16+$gteVisFall17+$gteVisFall18+$gteVisFall19+$gteVisFall20
					+ $gteVisFall21+$gteVisFall22+$gteVisFall23+$gteVisFall24+$gteVisFall25+$gteVisFall26+$gteVisFall27+$gteVisFall28+$gteVisFall29+$gteVisFall30;

				$gteVisCompT = $gteVisComp1+$gteVisComp2+$gteVisComp3+$gteVisComp4+$gteVisComp5+$gteVisComp6+$gteVisComp7+$gteVisComp8+$gteVisComp9+$gteVisComp10
					+ $gteVisComp11+$gteVisComp12+$gteVisComp13+$gteVisComp14+$gteVisComp15+$gteVisComp16+$gteVisComp17+$gteVisComp18+$gteVisComp19+$gteVisComp20
					+ $gteVisComp21+$gteVisComp22+$gteVisComp23+$gteVisComp24+$gteVisComp25+$gteVisComp26+$gteVisComp27+$gteVisComp28+$gteVisComp29+$gteVisComp30;
			}
		}
		
		////formulas repres
		$tabla .= '<tr>';
		$TVisEfec = $reg['VisEfectiva_1']+$reg['VisEfectiva_2']+$reg['VisEfectiva_3']+$reg['VisEfectiva_4']+$reg['VisEfectiva_5']+$reg['VisEfectiva_6']+$reg['VisEfectiva_7']+$reg['VisEfectiva_8']+$reg['VisEfectiva_9']+$reg['VisEfectiva_10']
			+$reg['VisEfectiva_11']+$reg['VisEfectiva_12']+$reg['VisEfectiva_13']+$reg['VisEfectiva_14']+$reg['VisEfectiva_15']+$reg['VisEfectiva_16']+$reg['VisEfectiva_17']+$reg['VisEfectiva_18']+$reg['VisEfectiva_19']+$reg['VisEfectiva_20']
			+$reg['VisEfectiva_21']+$reg['VisEfectiva_22']+$reg['VisEfectiva_23']+$reg['VisEfectiva_24']+$reg['VisEfectiva_25']+$reg['VisEfectiva_26']+$reg['VisEfectiva_27']+$reg['VisEfectiva_28']+$reg['VisEfectiva_29']+$reg['VisEfectiva_30'];

		$TVisFall = $reg['VisFallida_1']+$reg['VisFallida_2']+$reg['VisFallida_3']+$reg['VisFallida_4']+$reg['VisFallida_5']+$reg['VisFallida_6']+$reg['VisFallida_7']+$reg['VisFallida_8']+$reg['VisFallida_9']+$reg['VisFallida_10']
			+$reg['VisFallida_11']+$reg['VisFallida_12']+$reg['VisFallida_13']+$reg['VisFallida_14']+$reg['VisFallida_15']+$reg['VisFallida_16']+$reg['VisFallida_17']+$reg['VisFallida_18']+$reg['VisFallida_19']+$reg['VisFallida_20']
			+$reg['VisFallida_21']+$reg['VisFallida_22']+$reg['VisFallida_23']+$reg['VisFallida_24']+$reg['VisFallida_25']+$reg['VisFallida_26']+$reg['VisFallida_27']+$reg['VisFallida_28']+$reg['VisFallida_29']+$reg['VisFallida_30'];

		$TVisComp = $reg['VisComplement_1']+$reg['VisComplement_2']+$reg['VisComplement_3']+$reg['VisComplement_4']+$reg['VisComplement_5']+$reg['VisComplement_6']+$reg['VisComplement_7']+$reg['VisComplement_8']+$reg['VisComplement_9']+$reg['VisComplement_10']
			+$reg['VisComplement_11']+$reg['VisComplement_12']+$reg['VisComplement_13']+$reg['VisComplement_14']+$reg['VisComplement_15']+$reg['VisComplement_16']+$reg['VisComplement_17']+$reg['VisComplement_18']+$reg['VisComplement_19']+$reg['VisComplement_20']
			+$reg['VisComplement_21']+$reg['VisComplement_22']+$reg['VisComplement_23']+$reg['VisComplement_24']+$reg['VisComplement_25']+$reg['VisComplement_26']+$reg['VisComplement_27']+$reg['VisComplement_28']+$reg['VisComplement_29']+$reg['VisComplement_30'];

		////imprimir repres
		if($tipo != 3){
			$tabla .= '<td '.$estilorepre.' width="100px">'.$reg['LINEA'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px">'.$reg['Ruta'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="400px">'.$reg['SR'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_1'].'</td>';
			if($Dia2 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_2'].'</td>';
			if($Dia3 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_3'].'</td>';
			if($Dia4 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_4'].'</td>';
			if($Dia5 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_5'].'</td>';
			if($Dia6 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_6'].'</td>';
			if($Dia7 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_7'].'</td>';
			if($Dia8 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_8'].'</td>';
			if($Dia9 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_9'].'</td>';
			if($Dia10 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_10'].'</td>';
			if($Dia11 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_11'].'</td>';
			if($Dia12 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_12'].'</td>';
			if($Dia13 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_13'].'</td>';
			if($Dia14 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_14'].'</td>';
			if($Dia15 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_15'].'</td>';
			if($Dia16 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_16'].'</td>';
			if($Dia17 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_17'].'</td>';
			if($Dia18 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_18'].'</td>';
			if($Dia19 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_19'].'</td>';
			if($Dia20 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_20'].'</td>';
			if($Dia21 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_21'].'</td>';
			if($Dia22 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_22'].'</td>';
			if($Dia23 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_23'].'</td>';
			if($Dia24 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_24'].'</td>';
			if($Dia25 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_25'].'</td>';
			if($Dia26 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_26'].'</td>';
			if($Dia27 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_27'].'</td>';
			if($Dia28 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_28'].'</td>';
			if($Dia29 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_29'].'</td>';
			if($Dia30 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisEfectiva_30'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$TVisEfec.'</td>';

			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_1'].'</td>';
			if($Dia2 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_2'].'</td>';
			if($Dia3 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_3'].'</td>';
			if($Dia4 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_4'].'</td>';
			if($Dia5 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_5'].'</td>';
			if($Dia6 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_6'].'</td>';
			if($Dia7 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_7'].'</td>';
			if($Dia8 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_8'].'</td>';
			if($Dia9 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_9'].'</td>';
			if($Dia10 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_10'].'</td>';
			if($Dia11 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_11'].'</td>';
			if($Dia12 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_12'].'</td>';
			if($Dia13 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_13'].'</td>';
			if($Dia14 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_14'].'</td>';
			if($Dia15 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_15'].'</td>';
			if($Dia16 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_16'].'</td>';
			if($Dia17 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_17'].'</td>';
			if($Dia18 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_18'].'</td>';
			if($Dia19 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_19'].'</td>';
			if($Dia20 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_20'].'</td>';
			if($Dia21 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_21'].'</td>';
			if($Dia22 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_22'].'</td>';
			if($Dia23 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_23'].'</td>';
			if($Dia24 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_24'].'</td>';
			if($Dia25 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_25'].'</td>';
			if($Dia26 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_26'].'</td>';
			if($Dia27 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_27'].'</td>';
			if($Dia28 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_28'].'</td>';
			if($Dia29 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_29'].'</td>';
			if($Dia30 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisFallida_30'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$TVisFall.'</td>';

			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_1'].'</td>';
			if($Dia2 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_2'].'</td>';
			if($Dia3 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_3'].'</td>';
			if($Dia4 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_4'].'</td>';
			if($Dia5 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_5'].'</td>';
			if($Dia6 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_6'].'</td>';
			if($Dia7 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_7'].'</td>';
			if($Dia8 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_8'].'</td>';
			if($Dia9 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_9'].'</td>';
			if($Dia10 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_10'].'</td>';	
			if($Dia11 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_11'].'</td>';
			if($Dia12 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_12'].'</td>';
			if($Dia13 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_13'].'</td>';
			if($Dia14 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_14'].'</td>';
			if($Dia15 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_15'].'</td>';
			if($Dia16 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_16'].'</td>';
			if($Dia17 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_17'].'</td>';
			if($Dia18 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_18'].'</td>';
			if($Dia19 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_19'].'</td>';
			if($Dia20 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_20'].'</td>';	
			if($Dia21 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_21'].'</td>';
			if($Dia22 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_22'].'</td>';
			if($Dia23 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_23'].'</td>';
			if($Dia24 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_24'].'</td>';
			if($Dia25 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_25'].'</td>';
			if($Dia26 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_26'].'</td>';
			if($Dia27 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_27'].'</td>';
			if($Dia28 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_28'].'</td>';
			if($Dia29 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_29'].'</td>';
			if($Dia30 != 'NO') $tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisComplement_30'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$TVisComp.'</td>';
			$tabla .= '</tr>';
		}else{
			/*
			$pdf->Cell(100,10,$reg['Gte_ruta'],1,0,'L',0);
			$pdf->Cell(200,10,$reg['RM'],1,0,'L',0);
			$pdf->Cell(50,10,$reg['Ruta'],1,0,'L',0);
			$pdf->Cell(200,10,$reg['SR'],1,0,'L',0);
			$pdf->Cell(50,10,$reg['VisComplementPers'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($Presen),1,1,'C',0);
			*/
		}
		$i++;
	}
	
	////imprimir ultimo gerente
	if($tipo != 3){
		$tabla .= '<tr><td '.$estilogte.' width="100px"> </td>';
		$tabla .= '<td '.$estilogte.' width="100px">'.$rutaGte.'</td>';
		$tabla .= '<td '.$estilogte.' width="400px">'.$nombreGte.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe1).'</td>';
		if($Dia2 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe2).'</td>';
		if($Dia3 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe3).'</td>';
		if($Dia4 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe4).'</td>';
		if($Dia5 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe5).'</td>';
		if($Dia6 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe6).'</td>';
		if($Dia7 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe7).'</td>';
		if($Dia8 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe8).'</td>';
		if($Dia9 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe9).'</td>';
		if($Dia10 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe10).'</td>';
		if($Dia11 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe11).'</td>';
		if($Dia12 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe12).'</td>';
		if($Dia13 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe13).'</td>';
		if($Dia14 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe14).'</td>';
		if($Dia15 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe15).'</td>';
		if($Dia16 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe16).'</td>';
		if($Dia17 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe17).'</td>';
		if($Dia18 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe18).'</td>';
		if($Dia19 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe19).'</td>';
		if($Dia20 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe20).'</td>';
		if($Dia21 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe21).'</td>';
		if($Dia22 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe22).'</td>';
		if($Dia23 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe23).'</td>';
		if($Dia24 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe24).'</td>';
		if($Dia25 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe25).'</td>';
		if($Dia26 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe26).'</td>';
		if($Dia27 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe27).'</td>';
		if($Dia28 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe28).'</td>';
		if($Dia29 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe29).'</td>';
		if($Dia30 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfe30).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisEfeT).'</td>';

		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall1).'</td>';
		if($Dia2 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall2).'</td>';
		if($Dia3 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall3).'</td>';
		if($Dia4 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall4).'</td>';
		if($Dia5 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall5).'</td>';
		if($Dia6 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall6).'</td>';
		if($Dia7 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall7).'</td>';
		if($Dia8 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall8).'</td>';
		if($Dia9 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall9).'</td>';
		if($Dia10 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall10).'</td>';
		if($Dia11 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall11).'</td>';
		if($Dia12 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall12).'</td>';
		if($Dia13 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall13).'</td>';
		if($Dia14 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall14).'</td>';
		if($Dia15 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall15).'</td>';
		if($Dia16 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall16).'</td>';
		if($Dia17 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall17).'</td>';
		if($Dia18 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall18).'</td>';
		if($Dia19 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall19).'</td>';
		if($Dia20 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall20).'</td>';
		if($Dia21 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall21).'</td>';
		if($Dia22 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall22).'</td>';
		if($Dia23 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall23).'</td>';
		if($Dia24 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall24).'</td>';
		if($Dia25 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall25).'</td>';
		if($Dia26 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall26).'</td>';
		if($Dia27 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall27).'</td>';
		if($Dia28 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall28).'</td>';
		if($Dia29 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall29).'</td>';
		if($Dia30 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFall30).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisFallT).'</td>';

		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp1).'</td>';
		if($Dia2 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp2).'</td>';
		if($Dia3 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp3).'</td>';
		if($Dia4 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp4).'</td>';
		if($Dia5 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp5).'</td>';
		if($Dia6 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp6).'</td>';
		if($Dia7 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp7).'</td>';
		if($Dia8 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp8).'</td>';
		if($Dia9 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp9).'</td>';
		if($Dia10 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp10).'</td>';
		if($Dia11 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp11).'</td>';
		if($Dia12 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp12).'</td>';
		if($Dia13 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp13).'</td>';
		if($Dia14 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp14).'</td>';
		if($Dia15 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp15).'</td>';
		if($Dia16 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp16).'</td>';
		if($Dia17 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp17).'</td>';
		if($Dia18 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp18).'</td>';
		if($Dia19 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp19).'</td>';
		if($Dia20 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp20).'</td>';
		if($Dia21 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp21).'</td>';
		if($Dia22 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp22).'</td>';
		if($Dia23 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp23).'</td>';
		if($Dia24 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp24).'</td>';
		if($Dia25 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp25).'</td>';
		if($Dia26 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp26).'</td>';
		if($Dia27 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp27).'</td>';
		if($Dia28 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp28).'</td>';
		if($Dia29 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp29).'</td>';
		if($Dia30 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisComp30).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisCompT).'</td>';
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
		$tabla .= '<tr><td '.$estilogte.' width="100px"> </td>';
		$tabla .= '<td '.$estilogte.' width="100px"> </td>';
		$tabla .= '<td '.$estilogte.' width="400px">Total General</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe1).'</td>';
		if($Dia2 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe2).'</td>';
		if($Dia3 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe3).'</td>';
		if($Dia4 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe4).'</td>';
		if($Dia5 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe5).'</td>';
		if($Dia6 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe6).'</td>';
		if($Dia7 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe7).'</td>';
		if($Dia8 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe8).'</td>';
		if($Dia9 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe9).'</td>';
		if($Dia10 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe10).'</td>';
		if($Dia11 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe11).'</td>';
		if($Dia12 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe12).'</td>';
		if($Dia13 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe13).'</td>';
		if($Dia14 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe14).'</td>';
		if($Dia15 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe15).'</td>';
		if($Dia16 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe16).'</td>';
		if($Dia17 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe17).'</td>';
		if($Dia18 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe18).'</td>';
		if($Dia19 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe19).'</td>';
		if($Dia20 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe20).'</td>';
		if($Dia21 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe21).'</td>';
		if($Dia22 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe22).'</td>';
		if($Dia23 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe23).'</td>';
		if($Dia24 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe24).'</td>';
		if($Dia25 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe25).'</td>';
		if($Dia26 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe26).'</td>';
		if($Dia27 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe27).'</td>';
		if($Dia28 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe28).'</td>';
		if($Dia29 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe29).'</td>';
		if($Dia30 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfe30).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisEfeT).'</td>';

		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall1).'</td>';
		if($Dia2 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall2).'</td>';
		if($Dia3 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall3).'</td>';
		if($Dia4 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall4).'</td>';
		if($Dia5 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall5).'</td>';
		if($Dia6 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall6).'</td>';
		if($Dia7 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall7).'</td>';
		if($Dia8 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall8).'</td>';
		if($Dia9 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall9).'</td>';
		if($Dia10 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall10).'</td>';
		if($Dia11 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall11).'</td>';
		if($Dia12 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall12).'</td>';
		if($Dia13 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall13).'</td>';
		if($Dia14 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall14).'</td>';
		if($Dia15 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall15).'</td>';
		if($Dia16 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall16).'</td>';
		if($Dia17 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall17).'</td>';
		if($Dia18 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall18).'</td>';
		if($Dia19 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall19).'</td>';
		if($Dia20 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall20).'</td>';
		if($Dia21 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall21).'</td>';
		if($Dia22 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall22).'</td>';
		if($Dia23 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall23).'</td>';
		if($Dia24 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall24).'</td>';
		if($Dia25 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall25).'</td>';
		if($Dia26 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall26).'</td>';
		if($Dia27 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall27).'</td>';
		if($Dia28 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall28).'</td>';
		if($Dia29 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall29).'</td>';
		if($Dia30 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFall30).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFallT).'</td>';

		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp1).'</td>';
		if($Dia2 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp2).'</td>';
		if($Dia3 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp3).'</td>';
		if($Dia4 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp4).'</td>';
		if($Dia5 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp5).'</td>';
		if($Dia6 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp6).'</td>';
		if($Dia7 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp7).'</td>';
		if($Dia8 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp8).'</td>';
		if($Dia9 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp9).'</td>';
		if($Dia10 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp10).'</td>';
		if($Dia11 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp11).'</td>';
		if($Dia12 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp12).'</td>';
		if($Dia13 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp13).'</td>';
		if($Dia14 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp14).'</td>';
		if($Dia15 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp15).'</td>';
		if($Dia16 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp16).'</td>';
		if($Dia17 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp17).'</td>';
		if($Dia18 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp18).'</td>';
		if($Dia19 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp19).'</td>';
		if($Dia20 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp20).'</td>';
		if($Dia21 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp21).'</td>';
		if($Dia22 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp22).'</td>';
		if($Dia23 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp23).'</td>';
		if($Dia24 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp24).'</td>';
		if($Dia25 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp25).'</td>';
		if($Dia26 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp26).'</td>';
		if($Dia27 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp27).'</td>';
		if($Dia28 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp28).'</td>';
		if($Dia29 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp29).'</td>';
		if($Dia30 != 'NO') $tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisComp30).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisCompT).'</td>';
		$tabla .= '</tr>';
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