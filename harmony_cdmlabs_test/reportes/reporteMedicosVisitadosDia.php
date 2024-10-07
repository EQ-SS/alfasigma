<?php
	/*** cobertura de medicos ***/
	include "../conexion.php";
			
	$estatus = $_POST['hdnEstatus'];
	$ids = (substr($_POST['hdnIDS'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDS'], ",")) : $_POST['hdnIDS'] ;
	$tipo = $_POST['hdnTipoReporte'];
	$fechaI = $_POST['hdnFechaI'];
	$fechaF = $_POST['hdnFechaF'];
	
	$qMedicos = "DECLARE @fecha_inicio Date, 
		@fecha_fin Date, 
		@cad0 as varchar(36), 
		@no_vis as varchar(36), 
		@OActs as varchar(36), 
		@VAcom as varchar(36), 
		@CAM as varchar(36)
		
		set @fecha_inicio = '".$fechaI."'
		set @fecha_fin = '".$fechaF."'
		/*
		set @fecha_inicio = '2019-07-01'
		set @fecha_fin = '2019-07-30'
		*/
		
		set @cad0='00000000-0000-0000-0000-000000000000'
		set @no_vis='DA224658-37AA-4A9F-9154-0E466B0956C2' /*INACTIVO*/
		set @OActs='24919A00-0B53-46ED-980E-BDD151F669A2'
		set @VAcom='FAD471D8-3401-47F9-B03C-897C43D69B12'
		set @CAM='TRABAJO EN CAMPO'
		
		;with FECHAS (fecha, num_fecha) as (
		select  DATEADD(DAY, nbr - 1, @fecha_inicio) fecha, ROW_NUMBER() OVER (order by DATEADD(DAY, nbr - 1, @fecha_inicio)) num_fecha
		from    ( select ROW_NUMBER() OVER (order by c.object_id) as Nbr
				  from  sys.columns c) nbrs
		where   nbr - 1 <= DATEDIFF(DAY, @fecha_inicio, @fecha_fin)
		and datepart(DW,DATEADD(DAY, nbr - 1, @fecha_inicio)) not in (1,7)
		and DATEADD(DAY, nbr - 1, @fecha_inicio) not in (select c_date from CYCLE_DETAILS where c_date between @fecha_inicio and @fecha_fin and rec_stat=0 and c_day=0)
		)
		
		,OACTS (user_snr, DATE, dayreport_snr, day_code_snr, value, name, COLOR) as (
		select DR.user_snr, DR.DATE, DR.dayreport_snr, DC.day_code_snr, DC.value, cl.name,
		 (case when cl.name='VACANTE' then 1 
		when cl.name='VACACIONES' then 2 
		when cl.name='INCAPACIDAD' then 3
		when cl.name='CAPACITACION' then 4 
		when cl.name='JUNTA DE TRABAJO' then 5
		when cl.name='CONGRESOS' then 6
		else 7 end) COLOR
		from DAY_REPORT DR, DAY_REPORT_CODE DC 
		inner join codelist cl on cl.clist_snr = DC.day_code_snr and cl.clib_snr = @OActs and cl.rec_stat=0 and cl.status=1
		where DR.DATE between @fecha_inicio and @fecha_fin and DR.dayreport_snr = DC.dayreport_snr 
		and DC.day_code_snr not in (select clist_snr from CODELIST where clib_snr = @OActs and rec_stat=0 and name = @CAM) 
		and DR.rec_stat=0 and DC.rec_stat=0 and value>=1)
		
		Select 
		klr.REG_SNR,
		upper(DM.lname)+' '+upper(DM.fname) as RM,
		upper(U.lname)+' '+upper(U.fname) as SR,
		U.user_type as Tipo_Repre,
		(case when PATINDEX('%VACANTE%', U.lname)=0 then cast(isnull(U.TEL1,'0') as int) else 0 end) as CUOTA,
		(select count(num_fecha) from fechas) as Num_Tot_Fechas,
		
		/* DESPLEGADO DE FECHAS POR DIA */
		cast(F1.FECHA as CHAR(10)) as DIA_1,
		cast(F2.FECHA as CHAR(10)) as DIA_2,
		cast(F3.FECHA as CHAR(10)) as DIA_3,
		cast(F4.FECHA as CHAR(10)) as DIA_4,
		cast(F5.FECHA as CHAR(10)) as DIA_5,
		cast(F6.FECHA as CHAR(10)) as DIA_6,
		cast(F7.FECHA as CHAR(10)) as DIA_7,
		cast(F8.FECHA as CHAR(10)) as DIA_8,
		cast(F9.FECHA as CHAR(10)) as DIA_9,
		cast(F10.FECHA as CHAR(10)) as DIA_10,
		cast(F11.FECHA as CHAR(10)) as DIA_11,
		cast(F12.FECHA as CHAR(10)) as DIA_12,
		cast(F13.FECHA as CHAR(10)) as DIA_13,
		cast(F14.FECHA as CHAR(10)) as DIA_14,
		cast(F15.FECHA as CHAR(10)) as DIA_15,
		cast(F16.FECHA as CHAR(10)) as DIA_16,
		cast(F17.FECHA as CHAR(10)) as DIA_17,
		cast(F18.FECHA as CHAR(10)) as DIA_18,
		cast(F19.FECHA as CHAR(10)) as DIA_19,
		cast(F20.FECHA as CHAR(10)) as DIA_20,
		cast(F21.FECHA as CHAR(10)) as DIA_21,
		cast(F22.FECHA as CHAR(10)) as DIA_22,
		cast(F23.FECHA as CHAR(10)) as DIA_23,
		cast(F24.FECHA as CHAR(10)) as DIA_24,
		cast(F25.FECHA as CHAR(10)) as DIA_25,
		cast(@fecha_fin as CHAR(10)) as DIA_FIN,
		
		/* DESPLEGADO DE VISITAS POR DIA */
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F1.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 
		/*and VP1.pers_snr not in (select pers_snr from person where fname='AJUSTE' and lname='EXCEDENTE')*/
		) as Vis_Dia_1,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F2.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 
		) as Vis_Dia_2,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F3.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 
		) as Vis_Dia_3,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F4.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 
		) as Vis_Dia_4,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F5.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 
		) as Vis_Dia_5,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F6.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 
		) as Vis_Dia_6,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F7.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 
		) as Vis_Dia_7,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F8.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 
		) as Vis_Dia_8,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F9.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 
		) as Vis_Dia_9,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F10.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 
		) as Vis_Dia_10,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F11.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 
		) as Vis_Dia_11,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F12.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 
		) as Vis_Dia_12,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F13.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 
		) as Vis_Dia_13,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F14.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 
		) as Vis_Dia_14,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F15.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 
		) as Vis_Dia_15,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F16.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 
		) as Vis_Dia_16,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F17.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 
		) as Vis_Dia_17,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F18.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 
		) as Vis_Dia_18,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F19.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 
		) as Vis_Dia_19,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F20.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 
		) as Vis_Dia_20,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F21.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 
		) as Vis_Dia_21,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F22.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 
		) as Vis_Dia_22,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F23.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 
		) as Vis_Dia_23,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F24.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 
		) as Vis_Dia_24,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F25.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 
		) as Vis_Dia_25,
		
		/* DESPLEGADO DE VISITAS ACOMP */
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F1.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 and U.USER_TYPE=4 and (substring(escort_snr,1,36)=@VAcom or substring(escort_snr,38,36)=@VAcom)
		)  as VAcom_1,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F2.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0  and U.USER_TYPE=4 and (substring(escort_snr,1,36)=@VAcom or substring(escort_snr,38,36)=@VAcom)
		) as VAcom_2,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F3.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0  and U.USER_TYPE=4 and (substring(escort_snr,1,36)=@VAcom or substring(escort_snr,38,36)=@VAcom)
		) as VAcom_3,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F4.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 and U.USER_TYPE=4 and (substring(escort_snr,1,36)=@VAcom or substring(escort_snr,38,36)=@VAcom)
		) as VAcom_4,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F5.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 and U.USER_TYPE=4 and (substring(escort_snr,1,36)=@VAcom or substring(escort_snr,38,36)=@VAcom)
		) as VAcom_5,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F6.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 and U.USER_TYPE=4 and (substring(escort_snr,1,36)=@VAcom or substring(escort_snr,38,36)=@VAcom)
		) as VAcom_6,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F7.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 and U.USER_TYPE=4 and (substring(escort_snr,1,36)=@VAcom or substring(escort_snr,38,36)=@VAcom)
		) as VAcom_7,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F8.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 and U.USER_TYPE=4 and (substring(escort_snr,1,36)=@VAcom or substring(escort_snr,38,36)=@VAcom)
		) as VAcom_8,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F9.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 and U.USER_TYPE=4 and (substring(escort_snr,1,36)=@VAcom or substring(escort_snr,38,36)=@VAcom)
		) as VAcom_9,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F10.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 and U.USER_TYPE=4 and (substring(escort_snr,1,36)=@VAcom or substring(escort_snr,38,36)=@VAcom)
		) as VAcom_10,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F11.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 and U.USER_TYPE=4 and (substring(escort_snr,1,36)=@VAcom or substring(escort_snr,38,36)=@VAcom)
		) as VAcom_11,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F12.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 and U.USER_TYPE=4 and (substring(escort_snr,1,36)=@VAcom or substring(escort_snr,38,36)=@VAcom)
		) as VAcom_12,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F13.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 and U.USER_TYPE=4 and (substring(escort_snr,1,36)=@VAcom or substring(escort_snr,38,36)=@VAcom)
		) as VAcom_13,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F14.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 and U.USER_TYPE=4 and (substring(escort_snr,1,36)=@VAcom or substring(escort_snr,38,36)=@VAcom)
		) as VAcom_14,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F15.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 and U.USER_TYPE=4 and (substring(escort_snr,1,36)=@VAcom or substring(escort_snr,38,36)=@VAcom)
		) as VAcom_15,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F16.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 and U.USER_TYPE=4 and (substring(escort_snr,1,36)=@VAcom or substring(escort_snr,38,36)=@VAcom)
		) as VAcom_16,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F17.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 and U.USER_TYPE=4 and (substring(escort_snr,1,36)=@VAcom or substring(escort_snr,38,36)=@VAcom)
		) as VAcom_17,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F18.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 and U.USER_TYPE=4 and (substring(escort_snr,1,36)=@VAcom or substring(escort_snr,38,36)=@VAcom)
		) as VAcom_18,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F19.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 and U.USER_TYPE=4 and (substring(escort_snr,1,36)=@VAcom or substring(escort_snr,38,36)=@VAcom)
		) as VAcom_19,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F20.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 and U.USER_TYPE=4 and (substring(escort_snr,1,36)=@VAcom or substring(escort_snr,38,36)=@VAcom)
		) as VAcom_20,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F21.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 and U.USER_TYPE=4 and (substring(escort_snr,1,36)=@VAcom or substring(escort_snr,38,36)=@VAcom)
		) as VAcom_21,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F22.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 and U.USER_TYPE=4 and (substring(escort_snr,1,36)=@VAcom or substring(escort_snr,38,36)=@VAcom)
		) as VAcom_22,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F23.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 and U.USER_TYPE=4 and (substring(escort_snr,1,36)=@VAcom or substring(escort_snr,38,36)=@VAcom)
		) as VAcom_23,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F24.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 and U.USER_TYPE=4 and (substring(escort_snr,1,36)=@VAcom or substring(escort_snr,38,36)=@VAcom)
		) as VAcom_24,
		(select count(*) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.rec_stat=0 and VP1.visit_date = F25.FECHA and VP1.visit_code_snr<>@no_vis and VP1.pers_snr in (select pers_snr from pers_srep_work where rec_stat=0) and VP1.pers_snr<>@cad0 and U.USER_TYPE=4 and (substring(escort_snr,1,36)=@VAcom or substring(escort_snr,38,36)=@VAcom)
		) as VAcom_25,
		
		/* OTRAS ACTIVIDADES POR DIA */
		isnull((select top 1 COLOR from OACTS where DATE=F1.FECHA and user_snr = U.user_snr),0) as OAct_1,
		isnull((select top 1 COLOR from OACTS where DATE=F2.FECHA and user_snr = U.user_snr),0) as OAct_2,
		isnull((select top 1 COLOR from OACTS where DATE=F3.FECHA and user_snr = U.user_snr),0) as OAct_3,
		isnull((select top 1 COLOR from OACTS where DATE=F4.FECHA and user_snr = U.user_snr),0) as OAct_4,
		isnull((select top 1 COLOR from OACTS where DATE=F5.FECHA and user_snr = U.user_snr),0) as OAct_5,
		isnull((select top 1 COLOR from OACTS where DATE=F6.FECHA and user_snr = U.user_snr),0) as OAct_6,
		isnull((select top 1 COLOR from OACTS where DATE=F7.FECHA and user_snr = U.user_snr),0) as OAct_7,
		isnull((select top 1 COLOR from OACTS where DATE=F8.FECHA and user_snr = U.user_snr),0) as OAct_8,
		isnull((select top 1 COLOR from OACTS where DATE=F9.FECHA and user_snr = U.user_snr),0) as OAct_9,
		isnull((select top 1 COLOR from OACTS where DATE=F10.FECHA and user_snr = U.user_snr),0) as OAct_10,
		isnull((select top 1 COLOR from OACTS where DATE=F11.FECHA and user_snr = U.user_snr),0) as OAct_11,
		isnull((select top 1 COLOR from OACTS where DATE=F12.FECHA and user_snr = U.user_snr),0) as OAct_12,
		isnull((select top 1 COLOR from OACTS where DATE=F13.FECHA and user_snr = U.user_snr),0) as OAct_13,
		isnull((select top 1 COLOR from OACTS where DATE=F14.FECHA and user_snr = U.user_snr),0) as OAct_14,
		isnull((select top 1 COLOR from OACTS where DATE=F15.FECHA and user_snr = U.user_snr),0) as OAct_15,
		isnull((select top 1 COLOR from OACTS where DATE=F16.FECHA and user_snr = U.user_snr),0) as OAct_16,
		isnull((select top 1 COLOR from OACTS where DATE=F17.FECHA and user_snr = U.user_snr),0) as OAct_17,
		isnull((select top 1 COLOR from OACTS where DATE=F18.FECHA and user_snr = U.user_snr),0) as OAct_18,
		isnull((select top 1 COLOR from OACTS where DATE=F19.FECHA and user_snr = U.user_snr),0) as OAct_19,
		isnull((select top 1 COLOR from OACTS where DATE=F20.FECHA and user_snr = U.user_snr),0) as OAct_20,
		isnull((select top 1 COLOR from OACTS where DATE=F21.FECHA and user_snr = U.user_snr),0) as OAct_21,
		isnull((select top 1 COLOR from OACTS where DATE=F22.FECHA and user_snr = U.user_snr),0) as OAct_22,
		isnull((select top 1 COLOR from OACTS where DATE=F23.FECHA and user_snr = U.user_snr),0) as OAct_23,
		isnull((select top 1 COLOR from OACTS where DATE=F24.FECHA and user_snr = U.user_snr),0) as OAct_24,
		isnull((select top 1 COLOR from OACTS where DATE=F25.FECHA and user_snr = U.user_snr),0) as OAct_25,
		
		/* OTRAS ACTIVIDADES TOTAL */
		(select isnull(sum(value),0) from DAY_REPORT DR, DAY_REPORT_CODE DC where DR.DATE between @fecha_inicio and @fecha_fin and datepart(DW,DR.DATE) not in (1,7) and DR.dayreport_snr = DC.dayreport_snr and DR.rec_stat=0 and DC.rec_stat=0 and DR.user_snr = U.user_snr)
		 as OAct_T
		
		
		from users DM, (select distinct reg_snr, kloc_snr, rec_stat from KLOC_REG) klr, users U
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
		
		where klr.REG_SNR = DM.user_snr
		and klr.kloc_snr = U.user_snr
		and klr.rec_stat=0
		and U.rec_stat=0
		and DM.rec_stat=0
		and U.status in (1,2)
		and DM.status in (1,2)
		and U.user_type in (4,5)
		and DM.user_type = 5
		and U.user_snr in ('".$ids."') 
		
		order by DM.user_nr,DM.lname,DM.fname,U.user_type,U.lname,U.fname,klr.REG_SNR ";
	
	//echo $qMedicos."<br>";
		
	if($tipo == 1 || $tipo == 2){//excel
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=reporteMedicosVisitadosDia.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else if($tipo == 3){
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array(1100,1900));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('Reporte de Medicos Visitados por Dia'));
		$pdf->Ln();
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(40,5,'Consorcio Dermatológico de México');
		$pdf->Cell(125,5,'',0,0,'C',0);
		$pdf->SetFillColor(171,235,198);
		$pdf->Cell(10,5,'',1,0,'C',1);
		$pdf->Cell(35,5,'Capacitacion',0,0,'L',0);
		$pdf->SetFillColor(254,245,231);
		$pdf->Cell(10,5,'',1,0,'C',1);
		$pdf->Cell(35,5,'Junta de Trabajo',0,0,'L',0);
		$pdf->SetFillColor(215,189,226);
		$pdf->Cell(10,5,'',1,0,'C',1);
		$pdf->Cell(35,5,'Incapacidad',0,0,'L',0);
		$pdf->SetFillColor(245,183,177);
		$pdf->Cell(10,5,'',1,0,'C',1);
		$pdf->Cell(35,5,'Congresos',0,0,'L',0);
		$pdf->SetFillColor(214,234,248);
		$pdf->Cell(10,5,'',1,0,'C',1);
		$pdf->Cell(35,5,'Vacaciones',0,0,'L',0);
		$pdf->SetFillColor(255,255,0);
		$pdf->Cell(10,5,'',1,0,'C',1);
		$pdf->Cell(35,5,'Vacante',0,0,'L',0);
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

	$tamTabla = 2900;
	$tabla = '';
	if($tipo != 3){
			$tabla .= '<table border="0">
				<tr>
					<td>
						<table>
							<tr>
								<td colspan="2" class="nombreReporte">Reporte de Medicos Visitados por Dia</td>
							</tr>
							<tr>
								<td colspan="2" class="clienteReporte">Consorcio Dermatológico de México</td>';
								if($tipo != 2){								
									$tabla .= '<td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>
									<td style="background-color: #ABEBC6; border: 1px solid #000;" width="20px"> </td><td>Capacitacion &nbsp;</td>
									<td style="background-color: #FEF5E7; border: 1px solid #000;" width="20px"> </td><td>Junta de Trabajo &nbsp;</td>
									<td style="background-color: #D7BDE2; border: 1px solid #000;" width="20px"> </td><td>Incapacidad &nbsp;</td>
									<td style="background-color: #F5B7B1; border: 1px solid #000;" width="20px"> </td><td>Congresos &nbsp;</td>
									<td style="background-color: #D6EAF8; border: 1px solid #000;" width="20px"> </td><td>Vacaciones &nbsp;</td>
									<td style="background-color: #FFFF00; border: 1px solid #000;" width="20px"> </td><td>Vacante &nbsp;</td>
									</tr>';
								}
							$tabla .= '<tr>
								<td colspan="2" class="fechaReporte">Fecha: '. date("d/m/Y h:i:s") .'</td>
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
			$tabla .= '<thead style="background-color: #A9BCF5; font-weight: bold; border: 1px solid #000; padding: 5px 5px 5px 5px; color: #000;"><tr>';
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
		$estilocabecera = '';
		$estilorepre = '"';
		$estilogte = '"';
	}else{
		$estilocabecera = 'style="background-color: #A9BCF5; font-weight: bold; border: 1px solid #000; padding: 5px 5px 5px 5px; color: #000;"';
		$estilorepre = 'style="border: 1px solid #000; white-space: nowrap;';
		$estilogte = 'style="background-color: #A9BCF5; border: 1px solid #000; white-space: nowrap;';
	}
	
	
	$i=1;
	//inicia var nacional
	$totalCuota = 0;
	$totalVisitas = 0;				
	$totalFechas = 0;
	$sumtotalOAct = 0;
	$totalOAct = $sumtotalOAct;
	$sumtotalCuotaT = 0;
	$totalCuotaT = $sumtotalCuotaT;
	$totalPromVis = 0;
	$totalCob = 0;
	$con1d = 32;	//Visitas
	for($l = 1; $l <= 25; $l++){ 
		$sumtotalVisDia[$con1d] = 0;
		$totalVisDia[$con1d] = $sumtotalVisDia[$con1d];
		$totalCobDia[$con1d] = 0;
		$con1d = $con1d + 1;
	}

	while($reg = sqlsrv_fetch_array($rsMedicos)){	
		////suma nacional
		$numDias = $reg['Num_Tot_Fechas'];
		$totalCuota += $reg['CUOTA'];
		$totalVisitas += $reg['Vis_Dia_1']+$reg['Vis_Dia_2']+$reg['Vis_Dia_3']+$reg['Vis_Dia_4']+$reg['Vis_Dia_5']+$reg['Vis_Dia_6']+$reg['Vis_Dia_7']+$reg['Vis_Dia_8']+$reg['Vis_Dia_9']+$reg['Vis_Dia_10']+$reg['Vis_Dia_11']+$reg['Vis_Dia_12']+$reg['Vis_Dia_13']+$reg['Vis_Dia_14']+$reg['Vis_Dia_15']+$reg['Vis_Dia_16']+$reg['Vis_Dia_17']+$reg['Vis_Dia_18']+$reg['Vis_Dia_19']+$reg['Vis_Dia_20']+$reg['Vis_Dia_21']+$reg['Vis_Dia_22']+$reg['Vis_Dia_23']+$reg['Vis_Dia_24']+$reg['Vis_Dia_25'];
		
		$totalFechas += $numDias;
		$sumtotalOAct = $reg['OAct_T'] / 8;
		$totalOAct += $sumtotalOAct;
		$sumtotalCuotaT = ($numDias - $sumtotalOAct) * $reg['CUOTA'];
		$totalCuotaT += $sumtotalCuotaT;
		$totalPromVis = $totalVisitas / $totalFechas;
		$totalCob = ($totalVisitas / $totalCuotaT) * 100;
		$con1d = 32;	//Visitas
		for($l = 1; $l <= $numDias; $l++){ 
			$sumtotalVisDia[$con1d] = $reg[$con1d];
			$totalVisDia[$con1d] += $sumtotalVisDia[$con1d];
			$totalCobDia[$con1d] = ($totalVisDia[$con1d] / $totalCuota) * 100;
			$con1d = $con1d + 1;
		}
		
		if($i == 1){
			////imprimir encabezados
			if($tipo != 3){
				$tabla .= '<td '.$estilocabecera.' width="400px">Gte. Dto.</td>';
				$tabla .= '<td '.$estilocabecera.' width="400px">Representante</td>';
				$tabla .= '<td '.$estilocabecera.' width="60px" align="center">Cuota Diaria</td>';
				$tabla .= '<td '.$estilocabecera.' width="60px" align="center">Total Visitas</td>';
				$tabla .= '<td '.$estilocabecera.' width="60px" align="center">Cuota Total '.$numDias.' dias</td>';
				$tabla .= '<td '.$estilocabecera.' width="60px" align="center">Prom. Visitas al Dia</td>';
				$tabla .= '<td '.$estilocabecera.' width="60px" align="center">TFT</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">% Cob. Total</td>';
				$con1 = 6;	//Fechas
				for($l = 1; $l <= $numDias; $l++){ 
					$tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$reg[$con1].'</td>'; 
					$con1 = $con1 + 1;
				}
			}else{
				$pdf->Ln();	
				$pdf->Cell(200,10,'Gte. Dto.',1,0,'L',1);
				$pdf->Cell(200,10,'Representante',1,0,'L',1);
				$pdf->Cell(30,10,'Cuota Diaria',1,0,'C',1);
				$pdf->Cell(30,10,'Total Visitas',1,0,'C',1);
				$pdf->Cell(30,10,'Cuota Total '.$numDias.' dias',1,0,'C',1);
				$pdf->Cell(30,10,'Prom. Visitas al Dia',1,0,'C',1);
				$pdf->Cell(30,10,'TFT',1,0,'C',1);
				$pdf->Cell(50,10,'% Cob. Total',1,0,'C',1);
				$con1 = 6;	//Fechas
				for($l = 1; $l <= $numDias; $l++){ 
					$pdf->Cell(50,10,$reg[$con1],1,0,'C',1);
					$con1 = $con1 + 1;
				}
			}
			
			if($tipo != 3){
				$tabla .= '</tr></thead>';
				$tabla .= '<tbody style="height:345px;">';
			}else{
				$pdf->Ln();
				//Restauración de colores y fuentes
				$pdf->SetFillColor(169,188,245);
				$pdf->SetTextColor(0);
				$pdf->SetFont('');
				//Datos
				$fill=false;
			}
			
			////inicia var gerente
			$tempGerente = $reg['REG_SNR'];
			$gerente = $reg['REG_SNR'];
			$nombreGte = $reg['RM'];

			$gteCuota = $reg['CUOTA'];
			$gteVisitas = $reg['Vis_Dia_1']+$reg['Vis_Dia_2']+$reg['Vis_Dia_3']+$reg['Vis_Dia_4']+$reg['Vis_Dia_5']+$reg['Vis_Dia_6']+$reg['Vis_Dia_7']+$reg['Vis_Dia_8']+$reg['Vis_Dia_9']+$reg['Vis_Dia_10']+$reg['Vis_Dia_11']+$reg['Vis_Dia_12']+$reg['Vis_Dia_13']+$reg['Vis_Dia_14']+$reg['Vis_Dia_15']+$reg['Vis_Dia_16']+$reg['Vis_Dia_17']+$reg['Vis_Dia_18']+$reg['Vis_Dia_19']+$reg['Vis_Dia_20']+$reg['Vis_Dia_21']+$reg['Vis_Dia_22']+$reg['Vis_Dia_23']+$reg['Vis_Dia_24']+$reg['Vis_Dia_25'];
			
			$gteFechas = $numDias;
			$gteOAct = $reg['OAct_T'] / 8;
			$gteCuotaT = ($numDias - $gteOAct) * $reg['CUOTA'];
			$gtePromVis = 0;
			$gteCob = 0;
			$con1g = 32;	//Visitas
			for($l = 1; $l <= $numDias; $l++){ 
				$gteVisDia[$con1g] = $reg[$con1g];
				$gteCobDia[$con1g] = 0;
				$con1g = $con1g + 1;
			}
			$con1gva = 57;	//Visita Acomp
			for($l = 1; $l <= $numDias; $l++){ 
				$gteVisAcompDia[$con1gva] = $reg[$con1gva];
				$con1gva = $con1gva + 1;
			}

		}else{
			////sumas gerentes
			$gerente = $reg['REG_SNR'];	
			if($tempGerente == $gerente){
				if($reg['RM'] != $reg['SR']){
					$sumCuota = $reg['CUOTA'];
					$gteCuota += $sumCuota;
					$sumVisitas = $reg['Vis_Dia_1']+$reg['Vis_Dia_2']+$reg['Vis_Dia_3']+$reg['Vis_Dia_4']+$reg['Vis_Dia_5']+$reg['Vis_Dia_6']+$reg['Vis_Dia_7']+$reg['Vis_Dia_8']+$reg['Vis_Dia_9']+$reg['Vis_Dia_10']+$reg['Vis_Dia_11']+$reg['Vis_Dia_12']+$reg['Vis_Dia_13']+$reg['Vis_Dia_14']+$reg['Vis_Dia_15']+$reg['Vis_Dia_16']+$reg['Vis_Dia_17']+$reg['Vis_Dia_18']+$reg['Vis_Dia_19']+$reg['Vis_Dia_20']+$reg['Vis_Dia_21']+$reg['Vis_Dia_22']+$reg['Vis_Dia_23']+$reg['Vis_Dia_24']+$reg['Vis_Dia_25'];
					
					$gteVisitas += $sumVisitas;
					$sumFechas = $numDias;
					$gteFechas += $sumFechas;
					$sumOAct = $reg['OAct_T'] / 8;
					$gteOAct += $sumOAct;
					$sumCuotaT = ($numDias - $sumOAct) * $reg['CUOTA'];
					$gteCuotaT += $sumCuotaT;
					$gtePromVis = $gteVisitas / $gteFechas;
					$gteCob = ($gteVisitas / $gteCuotaT) * 100;
					$con1g = 32;	//Visitas
					for($l = 1; $l <= $numDias; $l++){ 
						$sumVisDia[$con1g] = $reg[$con1g];
						$gteVisDia[$con1g] += $sumVisDia[$con1g];
						$gteCobDia[$con1g] = ($gteVisDia[$con1g] / $gteCuota) * 100;
						$con1g = $con1g + 1;
					}
					$con1gva = 57;	//Visita Acomp
						for($l = 1; $l <= $numDias; $l++){ 
						$sumVisAcompDia[$con1gva] = $reg[$con1gva];
						$gteVisAcompDia[$con1gva] += $sumVisAcompDia[$con1gva];
						$con1gva = $con1gva + 1;
					}
				}
			}else{
				////imprimir gerentes
				if($tipo != 3){	
					$tabla .= '<td '.$estilogte.'" width="400px">Distrito - Visitas Acomp con Gte</td>';
					$tabla .= '<td '.$estilogte.'" width="400px"> </td>';
					$tabla .= '<td '.$estilogte.'" width="60px"> </td>';
					$tabla .= '<td '.$estilogte.'" width="60px"> </td>';
					$tabla .= '<td '.$estilogte.'" width="60px"> </td>';
					$tabla .= '<td '.$estilogte.'" width="60px"> </td>';
					$tabla .= '<td '.$estilogte.'" width="60px"> </td>';
					$tabla .= '<td '.$estilogte.'" width="100px"> </td>';
					$con1gva = 57;	//Visita Acomp
					for($l = 1; $l <= $numDias; $l++){ 
						$tabla .= '<td '.$estilogte.'" width="100px" align="center">'.$gteVisAcompDia[$con1gva].'</td>'; 
						$con1gva = $con1gva + 1;
					}
					$tabla .= '</tr>';
					$tabla .= '<tr>';
					$tabla .= '<td '.$estilogte.'" width="400px">Distrito - Cobertura</td>';
					$tabla .= '<td '.$estilogte.'" width="400px">'.$nombreGte.'</td>';
					$tabla .= '<td '.$estilogte.'" width="60px" align="center">'.$gteCuota.'</td>';
					$tabla .= '<td '.$estilogte.'" width="60px" align="center">'.number_format($gteVisitas).'</td>';
					$tabla .= '<td '.$estilogte.'" width="60px" align="center">'.number_format($gteCuotaT, 1).'</td>';
					$tabla .= '<td '.$estilogte.'" width="60px" align="center">'.number_format($gtePromVis, 1).'</td>';
					$tabla .= '<td '.$estilogte.'" width="60px" align="center">'.number_format($gteOAct, 1).'</td>';
					$tabla .= '<td '.$estilogte.'" width="100px" align="right">'.number_format($gteCob, 2).' %</td>';
					$con1g = 32;	//Visitas
					for($l = 1; $l <= $numDias; $l++){ 
						$tabla .= '<td '.$estilogte.'" width="100px" align="right">'.number_format($gteCobDia[$con1g], 2).' %</td>'; 
						$con1g = $con1g + 1;
					}
					$tabla .= '</tr>';
				}else{
					$pdf->Cell(200,10,'Distrito - Visitas Acomp con Gte',1,0,'L',1);
					$pdf->Cell(200,10,'',1,0,'L',1);
					$pdf->Cell(30,10,'',1,0,'C',1);
					$pdf->Cell(30,10,'',1,0,'C',1);
					$pdf->Cell(30,10,'',1,0,'C',1);
					$pdf->Cell(30,10,'',1,0,'C',1);
					$pdf->Cell(30,10,'',1,0,'C',1);
					$pdf->Cell(50,10,'',1,0,'R',1);
					$con1gva = 57;	//Visita Acomp
					for($l = 1; $l <= $numDias; $l++){ 
						$pdf->Cell(50,10,$gteVisAcompDia[$con1gva],1,0,'C',1);
						$con1gva = $con1gva + 1;
					}
					$pdf->Cell(1,10,'',0,1,'C',0);
					$pdf->Cell(200,10,'Distrito - Cobertura',1,0,'L',1);
					$pdf->Cell(200,10,$nombreGte,1,0,'L',1);
					$pdf->Cell(30,10,$gteCuota,1,0,'C',1);
					$pdf->Cell(30,10,number_format($gteVisitas),1,0,'C',1);
					$pdf->Cell(30,10,number_format($gteCuotaT, 1),1,0,'C',1);
					$pdf->Cell(30,10,number_format($gtePromVis, 1),1,0,'C',1);
					$pdf->Cell(30,10,number_format($gteOAct, 1),1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteCob, 2).' %',1,0,'R',1);
					$con1g = 32;	//Visitas
					for($l = 1; $l <= $numDias; $l++){ 
						$pdf->Cell(50,10,number_format($gteCobDia[$con1g], 2).' %',1,0,'R',1);
						$con1g = $con1g + 1;
					}
					$pdf->Cell(1,10,'',0,1,'C',0);
				}
	
				////inicia var gerente
				$tempGerente = $reg['REG_SNR'];
				$nombreGte = $reg['RM'];

				$gteCuota = $reg['CUOTA'];				
				$gteVisitas = $reg['Vis_Dia_1']+$reg['Vis_Dia_2']+$reg['Vis_Dia_3']+$reg['Vis_Dia_4']+$reg['Vis_Dia_5']+$reg['Vis_Dia_6']+$reg['Vis_Dia_7']+$reg['Vis_Dia_8']+$reg['Vis_Dia_9']+$reg['Vis_Dia_10']+$reg['Vis_Dia_11']+$reg['Vis_Dia_12']+$reg['Vis_Dia_13']+$reg['Vis_Dia_14']+$reg['Vis_Dia_15']+$reg['Vis_Dia_16']+$reg['Vis_Dia_17']+$reg['Vis_Dia_18']+$reg['Vis_Dia_19']+$reg['Vis_Dia_20']+$reg['Vis_Dia_21']+$reg['Vis_Dia_22']+$reg['Vis_Dia_23']+$reg['Vis_Dia_24']+$reg['Vis_Dia_25'];
				
				$gteFechas = $numDias;
				$gteOAct = $reg['OAct_T'] / 8;
				$gteCuotaT = ($numDias - $gteOAct) * $reg['CUOTA'];
				$gtePromVis = 0;
				$gteCob = 0;
				$con1g = 32;	//Visitas
					for($l = 1; $l <= $numDias; $l++){ 
					$gteVisDia[$con1g] = $reg[$con1g];
					$gteCobDia[$con1g] = 0;
					$con1g = $con1g + 1;
				}
				$con1gva = 57;	//Visita Acomp
				for($l = 1; $l <= $numDias; $l++){ 
					$gteVisAcompDia[$con1gva] = $reg[$con1gva];
					$con1gva = $con1gva + 1;
				}
			}
		}
		
		////formulas repres
		$tabla .= '<tr>';
		$Visitas = $reg['Vis_Dia_1']+$reg['Vis_Dia_2']+$reg['Vis_Dia_3']+$reg['Vis_Dia_4']+$reg['Vis_Dia_5']+$reg['Vis_Dia_6']+$reg['Vis_Dia_7']+$reg['Vis_Dia_8']+$reg['Vis_Dia_9']+$reg['Vis_Dia_10']+$reg['Vis_Dia_11']+$reg['Vis_Dia_12']+$reg['Vis_Dia_13']+$reg['Vis_Dia_14']+$reg['Vis_Dia_15']+$reg['Vis_Dia_16']+$reg['Vis_Dia_17']+$reg['Vis_Dia_18']+$reg['Vis_Dia_19']+$reg['Vis_Dia_20']+$reg['Vis_Dia_21']+$reg['Vis_Dia_22']+$reg['Vis_Dia_23']+$reg['Vis_Dia_24']+$reg['Vis_Dia_25'];
		
		$OAct = $reg['OAct_T'] / 8;
		$CuotaT = ($numDias - $OAct) * $reg['CUOTA'];
		$PromVis = $Visitas / $numDias;
		if($Visitas != 0 && $CuotaT != 0){
			$Cob = ($Visitas / $CuotaT) * 100;
		}else{ 
			$Cob = 0; 
		}
		
		////imprimir repres
		if($tipo != 3){
			if($reg['RM'] == $reg['SR']){
				$tabla .= '<td '.$estilogte.'" width="400px">Distrito - Visitas Reportadas Gte</td>';
				$tabla .= '<td '.$estilogte.'" width="400px">'.$reg['SR'].'</td>';
				$tabla .= '<td '.$estilogte.'" width="60px" align="center">'.$reg['CUOTA'].'</td>';
				$tabla .= '<td '.$estilogte.'" width="60px" align="center">'.$Visitas.'</td>';
				$tabla .= '<td '.$estilogte.'" width="60px" align="center">'.number_format($CuotaT, 1).'</td>';
				$tabla .= '<td '.$estilogte.'" width="60px" align="center">'.number_format($PromVis, 1).'</td>';
				$tabla .= '<td '.$estilogte.'" width="60px" align="center">'.number_format($OAct, 1).'</td>';
				$tabla .= '<td '.$estilogte.'" width="100px" align="right">'.number_format($Cob, 2).' %</td>';
				$con1r = 32;	//Visitas
				for($l = 1; $l <= $numDias; $l++){
					if($reg[$con1r] < $reg['CUOTA']){
						$tabla .= '<td '.$estilogte.' color:#FF0000;" width="100px" align="center">'.$reg[$con1r].'</td>'; 
					}else{
						$tabla .= '<td '.$estilogte.'" width="100px" align="center">'.$reg[$con1r].'</td>'; 
					}
					$con1r = $con1r + 1;
				}
			}else{ 
				$tabla .= '<td '.$estilorepre.'" width="400px">'.$reg['RM'].'</td>';
				$tabla .= '<td '.$estilorepre.'" width="400px">'.$reg['SR'].'</td>';
				$tabla .= '<td '.$estilorepre.'" width="60px" align="center">'.$reg['CUOTA'].'</td>';
				$tabla .= '<td '.$estilorepre.'" width="60px" align="center">'.$Visitas.'</td>';
				$tabla .= '<td '.$estilorepre.'" width="60px" align="center">'.number_format($CuotaT, 1).'</td>';
				$tabla .= '<td '.$estilorepre.'" width="60px" align="center">'.number_format($PromVis, 1).'</td>';
				$tabla .= '<td '.$estilorepre.'" width="60px" align="center">'.number_format($OAct, 1).'</td>';
				$tabla .= '<td '.$estilorepre.'" width="100px" align="right">'.number_format($Cob, 2).' %</td>';
				$con1r = 32;	//Visitas
				$color = 82;	//Otras Act
				for($l = 1; $l <= $numDias; $l++){ 
					switch($reg[$color]){
						case 0: $estilocolor = '"'; break; //Vacio
						case 1: $estilocolor = 'background-color: #FFFF00;"'; break; //Vacante
						case 2: $estilocolor = 'background-color: #D6EAF8;"'; break; //Vacaciones
						case 3: $estilocolor = 'background-color: #D7BDE2;"'; break; //Incapacidad
						case 4: $estilocolor = 'background-color: #ABEBC6;"'; break; //Capacitacion
						case 5: $estilocolor = 'background-color: #FEF5E7;"'; break; //Junta de Trabajo
						case 6: $estilocolor = 'background-color: #F5B7B1;"'; break; //Congresos
						case 7: $estilocolor = '"'; break; //Vacio
						default: $estilocolor = '"'; break; //Vacio
					}
					if($reg[$con1r] < $reg['CUOTA']){
						$tabla .= '<td '.$estilorepre.' color:#FF0000; '.$estilocolor.' width="100px" align="center">'.$reg[$con1r].'</td>'; 
					}else{
						$tabla .= '<td '.$estilorepre.' '.$estilocolor.' width="100px" align="center">'.$reg[$con1r].'</td>'; 
					}
					$con1r = $con1r + 1;
					$color = $color + 1;
				}
			}
			$tabla .= '</tr>';
		}else{
			if($reg['RM'] == $reg['SR']){
				$pdf->Cell(200,10,'Distrito - Visitas Reportadas Gte',1,0,'L',1);
				$pdf->Cell(200,10,$reg['SR'],1,0,'L',1);
				$pdf->Cell(30,10,$reg['CUOTA'],1,0,'C',1);
				$pdf->Cell(30,10,$Visitas,1,0,'C',1);
				$pdf->Cell(30,10,number_format($CuotaT, 1),1,0,'C',1);
				$pdf->Cell(30,10,number_format($PromVis, 1),1,0,'C',1);
				$pdf->Cell(30,10,number_format($OAct, 1),1,0,'C',1);
				$pdf->Cell(50,10,number_format($Cob, 2).' %',1,0,'R',1);
				$con1r = 32;	//Visitas
				for($l = 1; $l <= $numDias; $l++){ 
					if($reg[$con1r] < $reg['CUOTA']){
						$pdf->SetTextColor(255,0,0);
						$pdf->Cell(50,10,$reg[$con1r],1,0,'C',1);
					}else{
						$pdf->SetTextColor(0);
						$pdf->Cell(50,10,$reg[$con1r],1,0,'C',1);
					}
					$con1r = $con1r + 1;
				}
			}else{ 
				$pdf->Cell(200,10,$reg['RM'],1,0,'L',0);
				$pdf->Cell(200,10,$reg['SR'],1,0,'L',0);
				$pdf->Cell(30,10,$reg['CUOTA'],1,0,'C',0);
				$pdf->Cell(30,10,$Visitas,1,0,'C',0);
				$pdf->Cell(30,10,number_format($CuotaT, 1),1,0,'C',0);
				$pdf->Cell(30,10,number_format($PromVis, 1),1,0,'C',0);
				$pdf->Cell(30,10,number_format($OAct, 1),1,0,'C',0);
				$pdf->Cell(50,10,number_format($Cob, 2).' %',1,0,'R',0);
				$con1r = 32;	//Visitas
				$color = 82;	//Otras Act
				for($l = 1; $l <= $numDias; $l++){
					switch($reg[$color]){
						case 0: $pdf->SetFillColor(255,255,255); break; //Vacio
						case 1: $pdf->SetFillColor(255,255,0); break; //Vacante
						case 2: $pdf->SetFillColor(214,234,248); break; //Vacaciones
						case 3: $pdf->SetFillColor(215,189,226); break; //Incapacidad
						case 4: $pdf->SetFillColor(171,235,198); break; //Capacitacion
						case 5: $pdf->SetFillColor(254,245,231); break; //Junta de Trabajo
						case 6: $pdf->SetFillColor(245,183,177); break; //Congresos
						case 7: $pdf->SetFillColor(255,255,255); break; //Vacio
						default: $pdf->SetFillColor(255,255,255); break; //Vacio
					}
					if($reg[$con1r] < $reg['CUOTA']){
						$pdf->SetTextColor(255,0,0);
						$pdf->Cell(50,10,$reg[$con1r],1,0,'C',1);
					}else{
						$pdf->SetTextColor(0);
						$pdf->Cell(50,10,$reg[$con1r],1,0,'C',1);
					}
					$con1r = $con1r + 1;
					$color = $color + 1;
				}
			}
			$pdf->SetFillColor(169,188,245);
			$pdf->SetTextColor(0);		
			$pdf->Cell(1,10,'',0,1,'C',0);
		}
		$i++;
	}
	
	////imprimir ultimo gerente
	if($tipo != 3){
		$tabla .= '<td '.$estilogte.'" width="400px">Distrito - Visitas Acomp con Gte</td>';
		$tabla .= '<td '.$estilogte.'" width="400px"> </td>';
		$tabla .= '<td '.$estilogte.'" width="60px"> </td>';
		$tabla .= '<td '.$estilogte.'" width="60px"> </td>';
		$tabla .= '<td '.$estilogte.'" width="60px"> </td>';
		$tabla .= '<td '.$estilogte.'" width="60px"> </td>';
		$tabla .= '<td '.$estilogte.'" width="60px"> </td>';
		$tabla .= '<td '.$estilogte.'" width="100px"> </td>';
		$con1gva = 57;	//Visita Acomp
		for($l = 1; $l <= $numDias; $l++){ 
			$tabla .= '<td '.$estilogte.'" width="100px" align="center">'.$gteVisAcompDia[$con1gva].'</td>'; 
			$con1gva = $con1gva + 1;
		}
		$tabla .= '</tr>';
		$tabla .= '<tr>';
		$tabla .= '<td '.$estilogte.'" width="400px">Distrito - Cobertura</td>';
		$tabla .= '<td '.$estilogte.'" width="400px">'.$nombreGte.'</td>';
		$tabla .= '<td '.$estilogte.'" width="60px" align="center">'.$gteCuota.'</td>';
		$tabla .= '<td '.$estilogte.'" width="60px" align="center">'.number_format($gteVisitas).'</td>';
		$tabla .= '<td '.$estilogte.'" width="60px" align="center">'.number_format($gteCuotaT, 1).'</td>';
		$tabla .= '<td '.$estilogte.'" width="60px" align="center">'.number_format($gtePromVis, 1).'</td>';
		$tabla .= '<td '.$estilogte.'" width="60px" align="center">'.number_format($gteOAct, 1).'</td>';
		$tabla .= '<td '.$estilogte.'" width="100px" align="right">'.number_format($gteCob, 2).' %</td>';
		$con1g = 32;	//Visitas
		for($l = 1; $l <= $numDias; $l++){ 
			$tabla .= '<td '.$estilogte.'" width="100px" align="right">'.number_format($gteCobDia[$con1g], 2).' %</td>';
			$con1g = $con1g + 1;
		}
		$tabla .= '</tr>';
	}else{
		$pdf->Cell(200,10,'Distrito - Visitas Acomp con Gte',1,0,'L',1);
		$pdf->Cell(200,10,'',1,0,'L',1);
		$pdf->Cell(30,10,'',1,0,'C',1);
		$pdf->Cell(30,10,'',1,0,'C',1);
		$pdf->Cell(30,10,'',1,0,'C',1);
		$pdf->Cell(30,10,'',1,0,'C',1);
		$pdf->Cell(30,10,'',1,0,'C',1);
		$pdf->Cell(50,10,'',1,0,'R',1);
		$con1gva = 57;	//Visita Acomp
		for($l = 1; $l <= $numDias; $l++){ 
			$pdf->Cell(50,10,$gteVisAcompDia[$con1gva],1,0,'C',1);
			$con1gva = $con1gva + 1;
		}
		$pdf->Cell(1,10,'',0,1,'C',0);
		$pdf->Cell(200,10,'Distrito - Cobertura',1,0,'L',1);
		$pdf->Cell(200,10,$nombreGte,1,0,'L',1);
		$pdf->Cell(30,10,$gteCuota,1,0,'C',1);
		$pdf->Cell(30,10,number_format($gteVisitas),1,0,'C',1);
		$pdf->Cell(30,10,number_format($gteCuotaT, 1),1,0,'C',1);
		$pdf->Cell(30,10,number_format($gtePromVis, 1),1,0,'C',1);
		$pdf->Cell(30,10,number_format($gteOAct, 1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteCob, 2).' %',1,0,'R',1);
		$con1g = 32;	//Visitas
		for($l = 1; $l <= $numDias; $l++){ 
			$pdf->Cell(50,10,number_format($gteCobDia[$con1g], 2).' %',1,0,'R',1);
			$con1g = $con1g + 1;
		}
		$pdf->Cell(1,10,'',0,1,'C',0);
	}	

	////imprimir nacional
	if($tipo != 3){
		$tabla .= '<td '.$estilogte.'" width="400px">Cobertura Total</td>';
		$tabla .= '<td '.$estilogte.'" width="400px">Nacional de Ventas</td>';
		$tabla .= '<td '.$estilogte.'" width="60px" align="center">'.$totalCuota.'</td>';
		$tabla .= '<td '.$estilogte.'" width="60px" align="center">'.number_format($totalVisitas).'</td>';
		$tabla .= '<td '.$estilogte.'" width="60px" align="center">'.number_format($totalCuotaT, 1).'</td>';
		$tabla .= '<td '.$estilogte.'" width="60px" align="center">'.number_format($totalPromVis, 1).'</td>';
		$tabla .= '<td '.$estilogte.'" width="60px" align="center">'.number_format($totalOAct, 1).'</td>';
		$tabla .= '<td '.$estilogte.'" width="100px" align="right">'.number_format($totalCob, 2).' %</td>';
		$con1d = 32;	//Visitas
		for($l = 1; $l <= $numDias; $l++){ 
			$tabla .= '<td '.$estilogte.'" width="100px" align="right">'.number_format($totalCobDia[$con1d], 2).' %</td>';
			$con1d = $con1d + 1;
		}
		$tabla .= '</tr>';
	}else{
		$pdf->Cell(200,10,'Cobertura Total',1,0,'L',1);
		$pdf->Cell(200,10,'Nacional de Ventas',1,0,'L',1);
		$pdf->Cell(30,10,$totalCuota,1,0,'C',1);
		$pdf->Cell(30,10,number_format($totalVisitas),1,0,'C',1);
		$pdf->Cell(30,10,number_format($totalCuotaT, 1),1,0,'C',1);
		$pdf->Cell(30,10,number_format($totalPromVis, 1),1,0,'C',1);
		$pdf->Cell(30,10,number_format($totalOAct, 1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalCob, 2).' %',1,0,'R',1);
		$con1d = 32;	//Visitas
		for($l = 1; $l <= $numDias; $l++){ 
			$pdf->Cell(50,10,number_format($totalCobDia[$con1d], 2).' %',1,0,'R',1);
			$con1d = $con1d + 1;
		}
		$pdf->Cell(1,10,'',0,1,'C',0);
	}	
	
	if($tipo != 3){
		$tabla .= '</tbody> ';
		if($tipo == 2){	
			$tabla .= '<tfoot>';
		}else{
			if($tipo == 1){
				$tabla .= '<tfoot style="background-color: #A9BCF5; font-weight: bold; border: 1px solid #000; padding: 5px 5px 5px 5px; color: #000;">';
			}else{
				$tabla .= '<tfoot>';
			}
		}
		$numRegs = $i - 1;
				$tabla .= '<tr>
								<td colspan="2">Total registros: '.$numRegs.'</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="derechosReporte">© Smart-Scale</td>
		</tr>
	</table>';
		echo $tabla;
	}else{
		$pdf->Ln();
		$pdf->SetFont('Arial',10);
		$pdf->Cell(40,5,'Smart-Scale');
		$pdf->Output();
	}
	if($tipo == 0){
		echo '<script>
			$("#divCargando").hide();
		</script>';
	}
?>