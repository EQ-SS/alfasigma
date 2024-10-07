<style>
	#table_listado th{
		background-color:#337ab7;
		color:white;
	}

	#table_listado>tbody>tr>td{
		vertical-align:middle;
	}
</style>

<input type="hidden" id="hdnQuery">
<input type="hidden" id="hdnTitulos">
<input type="hidden" id="hdnNombreListado">

<?php
	include "../conexion.php";
	
	$ids = (substr($_POST['hdnIDSListado'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDSListado'], ",")) : $_POST['hdnIDSListado'] ;
	$tipo = $_POST['hdnTipoListado'];
	if(isset($_POST['pagina']) && $_POST['pagina'] != ''){
		$numPagina = $_POST['pagina'];
	}else{
		$numPagina = 1;
	}
	
	$ciclo = $_POST['hdnCicloListado'];
	if(isset($_POST['hdnEstatusListado']) && $_POST['hdnEstatusListado'] != ''){
		$estatus = $_POST['hdnEstatusListado'];
	}else{
		$estatus = '';
	}

	$nombreListado="Listado_Medicos_Visitados_Historico_Ciclo";
	
	$qMedicos = "DECLARE @DIAS_IN as DATE
	DECLARE @DIAS_2 as DATE
	DECLARE @DIAS_3 as DATE
	DECLARE @DIAS_4 as DATE
	DECLARE @DIAS_5 as DATE
	DECLARE @DIAS_6 as DATE
	DECLARE @DIAS_7 as DATE
	DECLARE @DIAS_8 as DATE
	DECLARE @DIAS_9 as DATE
	DECLARE @DIAS_10 as DATE
	DECLARE @DIAS_11 as DATE
	DECLARE @DIAS_12 as DATE
	DECLARE @DIAS_13 as DATE
	DECLARE @DIAS_14 as DATE
	DECLARE @DIAS_15 as DATE
	DECLARE @DIAS_16 as DATE
	DECLARE @DIAS_17 as DATE
	DECLARE @DIAS_18 as DATE
	DECLARE @DIAS_19 as DATE
	DECLARE @DIAS_20 as DATE
	DECLARE @DIAS_21 as DATE
	DECLARE @DIAS_22 as DATE
	DECLARE @DIAS_23 as DATE
	DECLARE @DIAS_24 as DATE
	DECLARE @DIAS_25 as DATE
	DECLARE @DIAS_26 as DATE
	DECLARE @DIAS_27 as DATE
	DECLARE @DIAS_28 as DATE
	DECLARE @DIAS_29 as DATE
	DECLARE @DIAS_30 as DATE
	DECLARE @DIAS_31 as DATE
	DECLARE @DIAS_32 as DATE
	DECLARE @DIAS_33 as DATE
	DECLARE @DIAS_34 as DATE
	DECLARE @DIAS_35 as DATE
	
	SET @DIAS_IN=(select START_DATE from CYCLES where cycle_snr = '".$ciclo."' )
	/* SET @DIAS_IN=(select START_DATE from CYCLES where cycle_snr = '153C2124-9746-4C55-B3CE-9B990CF4CBC4') */
	SET @DIAS_2=DATEADD(day,1,@DIAS_IN) /*-- suma 1 al dia */
	SET @DIAS_3=DATEADD(day,2,@DIAS_IN)
	SET @DIAS_4=DATEADD(day,3,@DIAS_IN)
	SET @DIAS_5=DATEADD(day,4,@DIAS_IN)
	SET @DIAS_6=DATEADD(day,5,@DIAS_IN)
	SET @DIAS_7=DATEADD(day,6,@DIAS_IN)
	SET @DIAS_8=DATEADD(day,7,@DIAS_IN)
	SET @DIAS_9=DATEADD(day,8,@DIAS_IN)
	SET @DIAS_10=DATEADD(day,9,@DIAS_IN)
	SET @DIAS_11=DATEADD(day,10,@DIAS_IN)
	SET @DIAS_12=DATEADD(day,11,@DIAS_IN)
	SET @DIAS_13=DATEADD(day,12,@DIAS_IN)
	SET @DIAS_14=DATEADD(day,13,@DIAS_IN)
	SET @DIAS_15=DATEADD(day,14,@DIAS_IN)
	SET @DIAS_16=DATEADD(day,15,@DIAS_IN)
	SET @DIAS_17=DATEADD(day,16,@DIAS_IN)
	SET @DIAS_18=DATEADD(day,17,@DIAS_IN)
	SET @DIAS_19=DATEADD(day,18,@DIAS_IN)
	SET @DIAS_20=DATEADD(day,19,@DIAS_IN)
	SET @DIAS_21=DATEADD(day,20,@DIAS_IN)
	SET @DIAS_22=DATEADD(day,21,@DIAS_IN)
	SET @DIAS_23=DATEADD(day,22,@DIAS_IN)
	SET @DIAS_24=DATEADD(day,23,@DIAS_IN)
	SET @DIAS_25=DATEADD(day,24,@DIAS_IN)
	SET @DIAS_26=DATEADD(day,25,@DIAS_IN)
	SET @DIAS_27=DATEADD(day,26,@DIAS_IN)
	SET @DIAS_28=DATEADD(day,27,@DIAS_IN)
	SET @DIAS_29=DATEADD(day,28,@DIAS_IN)
	SET @DIAS_30=DATEADD(day,29,@DIAS_IN)
	SET @DIAS_31=DATEADD(day,30,@DIAS_IN)
	SET @DIAS_32=DATEADD(day,31,@DIAS_IN)
	SET @DIAS_33=DATEADD(day,32,@DIAS_IN)
	SET @DIAS_34=DATEADD(day,33,@DIAS_IN)
	SET @DIAS_35=DATEADD(day,34,@DIAS_IN)
	
	;with Ciclos as (select cycle_snr, name, START_DATE, FINISH_DATE, ROW_NUMBER() OVER (ORDER BY name desc) as numero from CYCLES
	where cycle_snr<>'00000000-0000-0000-0000-000000000000' and rec_stat=0)
	
	Select /*DM.lname+' '+M.fname as RM,*/
	cl.name as Linea,
	Ugte.user_nr as Gte,
	U.user_nr as Ruta,
	upper(U.lname)+' '+upper(U.fname) as Representante,
	'{'+cast(P.pers_snr as varchar(36))+'}' as Codigo_Med,
	'{'+cast(I.inst_snr as varchar(36))+'}' as Codigo_Inst,
	upper(P.lname) Paterno,
	upper(P.mothers_lname) Materno,
	upper(P.fname) as Nombre,
	ST.name as Estatus,
	CAT.name as Categoria,
	ESP.name as Esp,
	FV.name as Frec_Vis,
	HON.name as Honorarios,
	PT.name as PacxSem,
	
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.pers_snr = P.pers_snr and VP1.rec_stat=0
	and VP1.visit_date between C13.Start_Date and C1.Finish_Date) as Total,
	
	/* --- DESPLEGADO DE DIAS Y VISITAS ULTIMO CICLO */
	/* convert(char(10), C1.FINISH_DATE, 23) as DIA_FIN, */
	(case when C1.FINISH_DATE >= @DIAS_35 then convert(char(10), @DIAS_35, 23) else 'A' end ) as DIA_35,
	(case when C1.FINISH_DATE >= @DIAS_35 
		then (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_35 )
		else 9 end ) as Vis_Dia_35,
	
	(case when C1.FINISH_DATE >= @DIAS_34 then convert(char(10), @DIAS_34, 23) else 'A' end ) as DIA_34,
	(case when C1.FINISH_DATE >= @DIAS_34 
		then (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_34 )
		else 9 end ) as Vis_Dia_34,
	
	(case when C1.FINISH_DATE >= @DIAS_33 then convert(char(10), @DIAS_33, 23) else 'A' end ) as DIA_33,
	(case when C1.FINISH_DATE >= @DIAS_33 
		then (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_33 )
		else 9 end ) as Vis_Dia_33,
	
	(case when C1.FINISH_DATE >= @DIAS_32 then convert(char(10), @DIAS_32, 23) else 'A' end ) as DIA_32,
	(case when C1.FINISH_DATE >= @DIAS_32 
		then (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_32 )
		else 9 end ) as Vis_Dia_32,
	
	(case when C1.FINISH_DATE >= @DIAS_31 then convert(char(10), @DIAS_31, 23) else 'A' end ) as DIA_31,
	(case when C1.FINISH_DATE >= @DIAS_31 
		then (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_31 )
		else 9 end ) as Vis_Dia_31,
	
	(case when C1.FINISH_DATE >= @DIAS_30 then convert(char(10), @DIAS_30, 23) else 'A' end ) as DIA_30,
	(case when C1.FINISH_DATE >= @DIAS_30 
		then (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_30 )
		else 9 end ) as Vis_Dia_30,
	
	(case when C1.FINISH_DATE >= @DIAS_29 then convert(char(10), @DIAS_29, 23) else 'A' end ) as DIA_29,
	(case when C1.FINISH_DATE >= @DIAS_29 
		then (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_29 )
		else 9 end ) as Vis_Dia_29,
	
	(case when C1.FINISH_DATE >= @DIAS_28 then convert(char(10), @DIAS_28, 23) else 'A' end ) as DIA_28,
	(case when C1.FINISH_DATE >= @DIAS_28 
		then (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_28 )
		else 9 end ) as Vis_Dia_28,
	
	(case when C1.FINISH_DATE >= @DIAS_27 then convert(char(10), @DIAS_27, 23) else 'A' end ) as DIA_27,
	(case when C1.FINISH_DATE >= @DIAS_27 
		then (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_27 )
		else 9 end ) as Vis_Dia_27,
	
	(case when C1.FINISH_DATE >= @DIAS_26 then convert(char(10), @DIAS_26, 23) else 'A' end ) as DIA_26,
	(case when C1.FINISH_DATE >= @DIAS_26 
		then (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_26 )
		else 9 end ) as Vis_Dia_26,		
	
	convert(char(10), @DIAS_25, 23) as DIA_25,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_25 ) as Vis_Dia_25,
	convert(char(10), @DIAS_24, 23) as DIA_24,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_24 ) as Vis_Dia_24,
	convert(char(10), @DIAS_23, 23) as DIA_23,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_23 ) as Vis_Dia_23,
	convert(char(10), @DIAS_22, 23) as DIA_22,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_22 ) as Vis_Dia_22,
	convert(char(10), @DIAS_21, 23) as DIA_21,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_21 ) as Vis_Dia_21,
	convert(char(10), @DIAS_20, 23) as DIA_20,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_20 ) as Vis_Dia_20,
	convert(char(10), @DIAS_19, 23) as DIA_19,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_19 ) as Vis_Dia_19,
	convert(char(10), @DIAS_18, 23) as DIA_18,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_18 ) as Vis_Dia_18,
	convert(char(10), @DIAS_17, 23) as DIA_17,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_17 ) as Vis_Dia_17,
	convert(char(10), @DIAS_16, 23) as DIA_16,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_16 ) as Vis_Dia_16,
	convert(char(10), @DIAS_15, 23) as DIA_15,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_15 ) as Vis_Dia_15,
	convert(char(10), @DIAS_14, 23) as DIA_14,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_14 ) as Vis_Dia_14,
	convert(char(10), @DIAS_13, 23) as DIA_13,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_13 ) as Vis_Dia_13,
	convert(char(10), @DIAS_12, 23) as DIA_12,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_12 ) as Vis_Dia_12,
	convert(char(10), @DIAS_11, 23) as DIA_11,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_11 ) as Vis_Dia_11,
	convert(char(10), @DIAS_10, 23) as DIA_10,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_10 ) as Vis_Dia_10,
	convert(char(10), @DIAS_9, 23) as DIA_9,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_9 ) as Vis_Dia_9,
	convert(char(10), @DIAS_8, 23) as DIA_8,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_8 ) as Vis_Dia_8,
	convert(char(10), @DIAS_7, 23) as DIA_7,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_7 ) as Vis_Dia_7,
	convert(char(10), @DIAS_6, 23) as DIA_6,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_6 ) as Vis_Dia_6,
	convert(char(10), @DIAS_5, 23) as DIA_5,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_5 ) as Vis_Dia_5,
	convert(char(10), @DIAS_4, 23) as DIA_4,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_4 ) as Vis_Dia_4,
	convert(char(10), @DIAS_3, 23) as DIA_3,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_3 ) as Vis_Dia_3,
	convert(char(10), @DIAS_2, 23) as DIA_2,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_2 ) as Vis_Dia_2,
	convert(char(10), @DIAS_IN, 23) as DIA_IN, 
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr = P.pers_snr and VP1.rec_stat=0 and VP1.user_snr = U.user_snr and VP1.visit_date = @DIAS_IN ) as Vis_Dia_In,
	
	/* --- DESPLEGADO DE CICLOS */
	'Ciclo '+C13.name as Ciclo13,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.pers_snr = P.pers_snr and VP1.rec_stat=0
	and VP1.visit_date between C13.Start_Date and C13.Finish_Date) as Visitas_C13,
	'Ciclo '+C12.name as Ciclo12,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.pers_snr = P.pers_snr and VP1.rec_stat=0
	and VP1.visit_date between C12.Start_Date and C12.Finish_Date) as Visitas_C12,
	'Ciclo '+C11.name as Ciclo11,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.pers_snr = P.pers_snr and VP1.rec_stat=0
	and VP1.visit_date between C11.Start_Date and C11.Finish_Date) as Visitas_C11,
	'Ciclo '+C10.name as Ciclo10,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.pers_snr = P.pers_snr and VP1.rec_stat=0
	and VP1.visit_date between C10.Start_Date and C10.Finish_Date) as Visitas_C10,
	'Ciclo '+C9.name as Ciclo9,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.pers_snr = P.pers_snr and VP1.rec_stat=0
	and VP1.visit_date between C9.Start_Date and C9.Finish_Date) as Visitas_C9,
	'Ciclo '+C8.name as Ciclo8,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.pers_snr = P.pers_snr and VP1.rec_stat=0
	and VP1.visit_date between C8.Start_Date and C8.Finish_Date) as Visitas_C8,
	'Ciclo '+C7.name as Ciclo7,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.pers_snr = P.pers_snr and VP1.rec_stat=0
	and VP1.visit_date between C7.Start_Date and C7.Finish_Date) as Visitas_C7,
	'Ciclo '+C6.name as Ciclo6,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.pers_snr = P.pers_snr and VP1.rec_stat=0
	and VP1.visit_date between C6.Start_Date and C6.Finish_Date) as Visitas_C6,
	'Ciclo '+C5.name as Ciclo5,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.pers_snr = P.pers_snr and VP1.rec_stat=0
	and VP1.visit_date between C5.Start_Date and C5.Finish_Date) as Visitas_C5,
	'Ciclo '+C4.name as Ciclo4,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.pers_snr = P.pers_snr and  VP1.rec_stat=0
	and VP1.visit_date between C4.Start_Date and C4.Finish_Date) as Visitas_C4,
	'Ciclo '+C3.name as Ciclo3,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.pers_snr = P.pers_snr and VP1.rec_stat=0
	and VP1.visit_date between C3.Start_Date and C3.Finish_Date) as Visitas_C3,
	'Ciclo '+C2.name as Ciclo2,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.pers_snr = P.pers_snr and VP1.rec_stat=0
	and VP1.visit_date between C2.Start_Date and C2.Finish_Date) as Visitas_C2,
	'Ciclo '+C1.name as Ciclo1,
	(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr = U.user_snr and VP1.pers_snr = P.pers_snr and VP1.rec_stat=0
	and VP1.visit_date between C1.Start_Date and C1.Finish_Date) as Visitas_C1
	
	from person P
	inner join pers_srep_work PSW on P.pers_snr = PSW.pers_snr 
	inner join perslocwork PLW on PSW.pwork_snr = PLW.pwork_snr 
	inner join inst I on I.inst_snr = PLW.inst_snr 
	inner join User_Territ UT on PSW.user_snr = UT.user_snr and I.inst_snr = UT.inst_snr 
	inner join Users U on U.user_snr = PSW.user_snr 
	inner join compline cl on U.cline_snr = cl.cline_snr 
	inner join kloc_reg repre on repre.kloc_snr = U.user_snr
	inner join users Ugte on Ugte.user_snr = repre.reg_snr and Ugte.user_type=5
	left outer join City on City.city_snr = I.city_snr
	inner join District Dst on city.distr_snr = Dst.distr_snr
	inner join State on Dst.state_snr = State.state_snr
	left outer join Brick IMS on IMS.brick_snr = City.brick_snr
	left outer join inst_Type IT on IT.inst_type = I.inst_type 
	left outer join codelist PersT on P.perstype_snr = PersT.clist_snr
	left outer join codelist ST on P.status_snr = ST.clist_snr
	left outer join codelist SEXO on P.sex_snr = SEXO.clist_snr
	left outer join codelist CAT on P.category_snr = CAT.clist_snr 
	left outer join codelist ESP on P.spec_snr = ESP.clist_snr
	left outer join codelist ESP2 on P.subspec_snr = ESP2.clist_snr
	left outer join codelist HON on P.fee_type_snr = HON.clist_snr
	left outer join codelist PT on P.patperweek_snr = PT.clist_snr
	left outer join codelist FV on P.frecvis_snr = FV.clist_snr
	
	left outer join Ciclos C1 on C1.cycle_snr = '".$ciclo."'
	/*left outer join Ciclos C1 on C1.cycle_snr = '153C2124-9746-4C55-B3CE-9B990CF4CBC4' */
	left outer join Ciclos C2 on C2.numero = C1.numero+1
	left outer join Ciclos C3 on C3.numero = C1.numero+2
	left outer join Ciclos C4 on C4.numero = C1.numero+3
	left outer join Ciclos C5 on C5.numero = C1.numero+4
	left outer join Ciclos C6 on C6.numero = C1.numero+5
	left outer join Ciclos C7 on C7.numero = C1.numero+6
	left outer join Ciclos C8 on C8.numero = C1.numero+7
	left outer join Ciclos C9 on C9.numero = C1.numero+8
	left outer join Ciclos C10 on C10.numero = C1.numero+9
	left outer join Ciclos C11 on C11.numero = C1.numero+10
	left outer join Ciclos C12 on C12.numero = C1.numero+11
	left outer join Ciclos C13 on C13.numero = C1.numero+12
	
	where
	P.pers_snr <> '00000000-0000-0000-0000-000000000000'
	and I.inst_snr <> '00000000-0000-0000-0000-000000000000'
	and P.rec_stat=0
	and PSW.rec_stat=0
	and PLW.rec_stat=0
	and UT.rec_stat=0
	and U.rec_stat=0
	and U.status=1
	and U.user_type=4
	and repre.rec_stat=0
	and Ugte.rec_stat=0
	and U.user_snr in ('".$ids."') ";
	
	if($estatus != ''){
		$qMedicos .= "and P.status_snr in ('".$estatus."') ";
	}
	
	$qMedicos .= "order by U.user_nr,U.lname,U.fname,P.lname,P.mothers_lname,P.fname ";
		
	$qMedicos2 = addslashes($qMedicos);
	$qMedicos2 = str_replace(array("\r", "\n","\r\n",""), " ", $qMedicos2);
		
	$rsQueryExport = sqlsrv_query($conn, utf8_decode($qMedicos), array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

	$tabla="";
	$tabla="<table id=\"table_listado\" class=\"table table-bordered table-hover tabled-striped  nowrap\" style=\"width:100%\" >
				<thead><tr>";

	$titulo=array();
	$j=0;
	foreach(sqlsrv_field_metadata($rsQueryExport) as $field){
		$tabla.="<th>".$field['Name']."</th>";
		$titulo[$j]=$field['Name'];
		$j++;
	}
	$titulos=implode(",", $titulo);
	$tabla.="</tr></thead><tbody></tbody></table>";

	echo "<div id=\"divListado\" style=\"border:0px\">";
	echo $tabla;
	echo "</div>";
	echo "<script>
    
    $(document).ready(function () {
		queryListado=$('#hdnQuery').val('".$qMedicos2."');
		titulos=$('#hdnTitulos').val('".$titulos."');
		$('#hdnNombreListado').val('".$nombreListado."');
		
		queryListado=$('#hdnQuery').val();
		
        table= $(\"#table_listado\").DataTable();
        table.destroy();
		
        $(\"#table_listado\").DataTable({
			processing: true,
			serverSide: true,
  			ajax:{
              url:\"ajax/consultaListado.php\",
              data:{queryListado:queryListado},
              type:\"post\"
          },\"dom\": \"Birtl\",
          \"lengthMenu\": [[10, 25, 50], [10, 25, 50]],
		
          \"buttons\": [
                  {
                      \"extend\": 'excel',
                    //  \"text\": '<button class=\"btn\"><i class=\"fa fa-file-excel-o\" style=\"color: green;\"></i>  Excel</button>',
                      \"text\": '<i class=\"fa fa-file-excel-o\" style=\"color: green;\"> <span >Excel</span></i>   ',
                      \"titleAttr\": 'Excel',
                      \"action\":expExcel
                  },
				  {
                    \"extend\": 'csv',
                  //  \"text\": '<button class=\"btn\"><i class=\"fa fa-file-csv-o\" style=\"color: green;\"></i>  CSV</button>',
                    \"text\": '<i class=\"fa fa-file-text-o\" style=\"color: green;\"> <span >CSV</span></i>   ',
                    \"titleAttr\": 'CSV',
                    \"action\":expCsv
                },
              ] ,
              'language': {
                'url': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json'
            },
            columnDefs:[{
                targets: \"_all\",
                sortable: false
            }],
});

$('#btnExportarListadoExcelPlano').hide();
$('#btnExportarListadoExcel').hide();
$('#btnExportarListadoPDF').hide();

function expExcel(){
    alertListados('LISTADO', 'Generando listado, esto puede tardar unos minutos dependiendo de su conexion a internet.', 'info');
	
	queryListado=$('#hdnQuery').val();
	titulos=$('#hdnTitulos').val();
	nombreListado=$('#hdnNombreListado').val();
	
	// crea un nuevo objeto `Date`
	var today = new Date();
	
	// obtener la fecha y la hora
	var now = today.toISOString();
	
    var form = $(document.createElement('form'));
    $(form).attr('action', 'ajax/listadoExcel.php');
    $(form).attr('method', 'POST');
	$(form).attr('id', 'form1');
    $(form).css('display', 'none');
	
	var input_queryListado = $('<input>')
    .attr('type', 'text')
    .attr('name', 'query')
    .val(queryListado);
	
	var input_titulos = $('<input>')
    .attr('type', 'text')
    .attr('name', 'titulos')
    .val(titulos);
	
	var input_nombreListado = $('<input>')
    .attr('type', 'text')
    .attr('name', 'nombreListado')
    .val(nombreListado);
	
	$(form).append($(input_queryListado));
	$(form).append($(input_titulos));
	$(form).append($(input_nombreListado));
    form.appendTo( document.body );
	
    $(form).submit();
}

function expCsv(){
    alertListados('LISTADO', 'Generando listado, esto puede tardar unos minutos dependiendo de su conexion a internet.', 'info');
	queryListado=$('#hdnQuery').val();
	titulos=$('#hdnTitulos').val();
	nombreListado=$('#hdnNombreListado').val();
	
    var form = $(document.createElement('form'));
    $(form).attr('action', 'ajax/listadoCsv.php');
    $(form).attr('method', 'POST');
    $(form).css('display', 'none');
	
	var input_queryListado = $('<input>')
    .attr('type', 'text')
    .attr('name', 'query')
    .val(queryListado);
	
	var input_titulos = $('<input>')
    .attr('type', 'text')
    .attr('name', 'titulos')
    .val(titulos);
	
	var input_nombreListado = $('<input>')
    .attr('type', 'text')
    .attr('name', 'nombreListado')
    .val(nombreListado);
	
	$(form).append($(input_queryListado));
	$(form).append($(input_titulos));
	$(form).append($(input_nombreListado));
	
    form.appendTo( document.body );
    $(form).submit();
}
});</script>";

?>
<style>
	#divListado{
		overflow:scroll;
		width:1330px;
	}
</style>

<script>
	$('#table_listado').DataTable().on("draw", function(){
		$('#divCargando').hide();
	});
</script>
