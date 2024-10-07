<?php
	/*** listado de medicos ***/
	include "../conexion.php";
	
	$tamanio = array(1,4,4,4,2,2,2,2,3,4,6,2,2,2,4,4,2,3,3,2,2,3,3,2,2,2,2,2,2,2,2,2,2,1,4,2,4,2,4,3,2,2,2,2,2,2,5,2,2,2,2,1,2,2,2,2,2,2,1,2,2,2,1,2,3,2,2,2,2,2,3,2,2,2,3,2,2,2,1,2,1,2,1,2,1,2,1,2,1,2,2,2);
	$tam = array(100,350,250,250,150,150,200,200,300,100, 100,150,150,150,100,100,100,100,100,100, 100,100,100,100,100,100,100,100,100,100 ,100,100,100,100,100,100,100,100,100,100 ,100,100,100,100,100,100,100,100,100,100 ,100,100,100,100,100,100,100,100,100,100 ,100,100,100,100,100);//,100,100,100,100);
	$estatus = $_POST['hdnEstatus'];
	$ids = (substr($_POST['hdnIDS'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDS'], ",")) : $_POST['hdnIDS'] ;
	$tipo = $_POST['hdnTipoReporte'];
	$fechaI = $_POST['hdnFechaI'];
	$fechaF = $_POST['hdnFechaF'];
	$ciclo = $_POST['hdnCicloReporte'];
	
	$qMedicos = "DECLARE @ciclo AS nvarchar(14)
		DECLARE @ciclo2 AS nvarchar(14)
		DECLARE @ciclo3 AS nvarchar(14)
		DECLARE @ciclo4 AS nvarchar(14)
		DECLARE @ciclo5 AS nvarchar(14)
		DECLARE @ciclo6 AS nvarchar(14)
		DECLARE @ciclo7 AS nvarchar(14)
		DECLARE @ciclo8 AS nvarchar(14)
		DECLARE @ciclo9 AS nvarchar(14)
		DECLARE @ciclo10 AS nvarchar(14)
		DECLARE @ciclo11 AS nvarchar(14)
		DECLARE @ciclo12 AS nvarchar(14)
		DECLARE @ciclo13 AS nvarchar(14)
		DECLARE @DIAS_IN AS DATE
		DECLARE @DIAS_FIN AS DATE
		DECLARE @DIAS_2 AS DATE
		DECLARE @DIAS_3 AS DATE
		DECLARE @DIAS_4 AS DATE
		DECLARE @DIAS_5 AS DATE
		DECLARE @DIAS_6 AS DATE
		DECLARE @DIAS_7 AS DATE
		DECLARE @DIAS_8 AS DATE
		DECLARE @DIAS_9 AS DATE
		DECLARE @DIAS_10 AS DATE
		DECLARE @DIAS_11 AS DATE
		DECLARE @DIAS_12 AS DATE
		DECLARE @DIAS_13 AS DATE
		DECLARE @DIAS_14 AS DATE
		DECLARE @DIAS_15 AS DATE
		DECLARE @DIAS_16 AS DATE
		DECLARE @DIAS_17 AS DATE
		DECLARE @DIAS_18 AS DATE
		DECLARE @DIAS_19 AS DATE
		DECLARE @DIAS_20 AS DATE
		DECLARE @DIAS_21 AS DATE
		DECLARE @DIAS_22 AS DATE
		DECLARE @DIAS_23 AS DATE
		DECLARE @DIAS_24 AS DATE
		DECLARE @DIAS_25 AS DATE
		DECLARE @DIAS_26 AS DATE
		DECLARE @DIAS_27 AS DATE
		DECLARE @DIAS_28 AS DATE
		DECLARE @DIAS_29 AS DATE
		DECLARE @DIAS_30 AS DATE
		DECLARE @DIAS_31 AS DATE
		DECLARE @DIAS_32 AS DATE
		DECLARE @DIAS_33 AS DATE
		DECLARE @DIAS_34 AS DATE
		DECLARE @DIAS_35 AS DATE

		 
		set @ciclo=(select name from CYCLES where REC_STAT=0 and CYCLE_SNR = '".$ciclo."' )
		/*set @ciclo=(select name from CYCLES where REC_STAT=0 and CYCLE_SNR=('153C2124-9746-4C55-B3CE-9B990CF4CBC4') )*/
		 
		set @ciclo13=(select top 1 name from CYCLES where CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' AND REC_STAT = 0 and name<=@ciclo
		and name not in (select top ((SELECT COUNT (name) FROM CYCLES WHERE REC_STAT = 0 AND CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' and name<=@ciclo )-13) name from CYCLES
		where CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' AND REC_STAT = 0 and name<=@ciclo order by name) order by name)
		set @ciclo12=(select top 1 name from CYCLES where CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' AND REC_STAT = 0 and name<=@ciclo
		and name not in (select top ((SELECT COUNT (name) FROM CYCLES WHERE REC_STAT = 0 AND CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' and name<=@ciclo )-12) name from CYCLES
		where CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' AND REC_STAT = 0 and name<=@ciclo order by name) order by name)
		set @ciclo11=(select top 1 name from CYCLES where CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' AND REC_STAT = 0 and name<=@ciclo
		and name not in (select top ((SELECT COUNT (name) FROM CYCLES WHERE REC_STAT = 0 AND CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' and name<=@ciclo )-11) name from CYCLES
		where CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' AND REC_STAT = 0 and name<=@ciclo order by name) order by name)
		set @ciclo10=(select top 1 name from CYCLES where CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' AND REC_STAT = 0 and name<=@ciclo
		and name not in (select top ((SELECT COUNT (name) FROM CYCLES WHERE REC_STAT = 0 AND CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' and name<=@ciclo )-10) name from CYCLES
		where CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' AND REC_STAT = 0 and name<=@ciclo order by name) order by name)
		set @ciclo9=(select top 1 name from CYCLES where CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' AND REC_STAT = 0 and name<=@ciclo
		and name not in (select top ((SELECT COUNT (name) FROM CYCLES WHERE REC_STAT = 0 AND CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' and name<=@ciclo )-9) name from CYCLES
		where CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' AND REC_STAT = 0 and name<=@ciclo order by name) order by name)
		set @ciclo8=(select top 1 name from CYCLES where CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' AND REC_STAT = 0 and name<=@ciclo
		and name not in (select top ((SELECT COUNT (name) FROM CYCLES WHERE REC_STAT = 0 AND CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' and name<=@ciclo )-8) name from CYCLES
		where CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' AND REC_STAT = 0 and name<=@ciclo order by name) order by name)
		set @ciclo7=(select top 1 name from CYCLES where CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' AND REC_STAT = 0 and name<=@ciclo
		and name not in (select top ((SELECT COUNT (name) FROM CYCLES WHERE REC_STAT = 0 AND CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' and name<=@ciclo )-7) name from CYCLES
		where CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' AND REC_STAT = 0 and name<=@ciclo order by name) order by name)
		set @ciclo6=(select top 1 name from CYCLES where CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' AND REC_STAT = 0 and name<=@ciclo
		and name not in (select top ((SELECT COUNT (name) FROM CYCLES WHERE REC_STAT = 0 AND CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' and name<=@ciclo )-6) name from CYCLES
		where CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' AND REC_STAT = 0 and name<=@ciclo order by name) order by name)
		set @ciclo5=(select top 1 name from CYCLES where CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' AND REC_STAT = 0 and name<=@ciclo
		and name not in (select top ((SELECT COUNT (name) FROM CYCLES WHERE REC_STAT = 0 AND CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' and name<=@ciclo )-5) name from CYCLES
		where CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' AND REC_STAT = 0 and name<=@ciclo order by name) order by name)
		set @ciclo4=(select top 1 name from CYCLES where CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' AND REC_STAT = 0 and name<=@ciclo
		and name not in (select top ((SELECT COUNT (name) FROM CYCLES WHERE REC_STAT = 0 AND CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' and name<=@ciclo )-4) name from CYCLES
		where CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' AND REC_STAT = 0 and name<=@ciclo order by name) order by name)
		set @ciclo3=(select top 1 name from CYCLES where CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' AND REC_STAT = 0 and name<=@ciclo
		and name not in (select top ((SELECT COUNT (name) FROM CYCLES WHERE REC_STAT = 0 AND CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' and name<=@ciclo )-3) name from CYCLES
		where CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' AND REC_STAT = 0 and name<=@ciclo order by name) order by name)
		set @ciclo2=(select top 1 name from CYCLES where CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' AND REC_STAT = 0 and name<=@ciclo
		and name not in (select top ((SELECT COUNT (name) FROM CYCLES WHERE REC_STAT = 0 AND CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' and name<=@ciclo )-2) name from CYCLES
		where CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' AND REC_STAT = 0 and name<=@ciclo order by name) order by name)
		
		SET @DIAS_IN=(select top 1 START_DATE from CYCLES where CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' AND REC_STAT = 0 
		and name = @ciclo )
		SET @DIAS_FIN=(select top 1 FINISH_DATE from CYCLES where CYCLE_SNR <> '00000000-0000-0000-0000-000000000000' AND REC_STAT = 0 
		and name = @ciclo )
		SET @DIAS_2=DATEADD(day,1,@DIAS_IN) -- suma 1 al dia
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
		 
		 
		Select /*DM.lname+' '+M.fname as RM,*/
		cl.name as Linea,
		upper(U.lname)+' '+upper(U.fname) as Representante,
		'{'+CAST(I.inst_snr AS VARCHAR(36))+'}' as 'Código Institución',
		'{'+CAST(P.pers_snr AS VARCHAR(36))+'}' as 'Código Médico',
		upper(IT.NAME) AS 'Tipo Inst',
		upper(type.name) as Tipo,
		upper(P.lname) Paterno,
		upper(P.mothers_lname) Materno,
		upper(P.fname) as Nombre,
		ST.name as Status,
		CATEG.name as 'Categ AW',
		ESP.name as Especialidad,
		SEGM_INIC.NAME AS 'Segmentación Inicial',
		SEGM_ACT.NAME AS 'Segmentación Actual',
		DIV_MED_INT.NAME AS 'Div Med Int',
			
		/*upper(P.lname)+ ' '+upper(P.mothers_lname)+' '+upper(P.fname) as Nombre_Completo,
		upper(I.street1) as Direccion,
		IMS.name as Brick,
		City.name as Colonia,
		City.zip as Cod_Postal,
		Dst.name as Ciudad,
		State.name as Estado,
		SEXO.name as Sexo,
		ESP2.name as Sub_Esp,
		HON.name as Hon,
		PT.name as Pac,
		DIFVIS.name as Difvis,
		/*ESTILO.name as Estilo,*/
		P.birth_year as Ano_Nac,
		FV.name as Frec_vis,
		P.gsm as Celular,
		I.tel1 as Tel1,
		I.tel2 as Tel2,*/
		
		--- DESPLEGADO DE DIAS Y VISITAS ULTIMO CICLO
		/* CONVERT(CHAR(10), @DIAS_FIN, 23) AS DIA_FIN, */
		
		(CASE WHEN @DIAS_FIN >= @DIAS_35 THEN CONVERT(CHAR(10), @DIAS_35, 23) ELSE 'A' END ) as DIA_35,
		(CASE WHEN @DIAS_FIN >= @DIAS_35 
			THEN (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_35 )
			ELSE 9 END ) as Vis_Dia_35,
		
		(CASE WHEN @DIAS_FIN >= @DIAS_34 THEN CONVERT(CHAR(10), @DIAS_34, 23) ELSE 'A' END ) as DIA_34,
		(CASE WHEN @DIAS_FIN >= @DIAS_34 
			THEN (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_34 )
			ELSE 9 END ) as Vis_Dia_34,

		(CASE WHEN @DIAS_FIN >= @DIAS_33 THEN CONVERT(CHAR(10), @DIAS_33, 23) ELSE 'A' END ) as DIA_33,
		(CASE WHEN @DIAS_FIN >= @DIAS_33 
			THEN (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_33 )
			ELSE 9 END ) as Vis_Dia_33,

		(CASE WHEN @DIAS_FIN >= @DIAS_32 THEN CONVERT(CHAR(10), @DIAS_32, 23) ELSE 'A' END ) as DIA_32,
		(CASE WHEN @DIAS_FIN >= @DIAS_32 
			THEN (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_32 )
			ELSE 9 END ) as Vis_Dia_32,

		(CASE WHEN @DIAS_FIN >= @DIAS_31 THEN CONVERT(CHAR(10), @DIAS_31, 23) ELSE 'A' END ) as DIA_31,
		(CASE WHEN @DIAS_FIN >= @DIAS_31 
			THEN (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_31 )
			ELSE 9 END ) as Vis_Dia_31,
		
		(CASE WHEN @DIAS_FIN >= @DIAS_30 THEN CONVERT(CHAR(10), @DIAS_30, 23) ELSE 'A' END ) as DIA_30,
		(CASE WHEN @DIAS_FIN >= @DIAS_30 
			THEN (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_30 )
			ELSE 9 END ) as Vis_Dia_30,
		
		(CASE WHEN @DIAS_FIN >= @DIAS_29 THEN CONVERT(CHAR(10), @DIAS_29, 23) ELSE 'A' END ) as DIA_29,
		(CASE WHEN @DIAS_FIN >= @DIAS_29 
			THEN (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_29 )
			ELSE 9 END ) as Vis_Dia_29,

		(CASE WHEN @DIAS_FIN >= @DIAS_28 THEN CONVERT(CHAR(10), @DIAS_28, 23) ELSE 'A' END ) as DIA_28,
		(CASE WHEN @DIAS_FIN >= @DIAS_28 
			THEN (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_28 )
			ELSE 9 END ) as Vis_Dia_28,
			
		(CASE WHEN @DIAS_FIN >= @DIAS_27 THEN CONVERT(CHAR(10), @DIAS_27, 23) ELSE 'A' END ) as DIA_27,
		(CASE WHEN @DIAS_FIN >= @DIAS_27 
			THEN (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_27 )
			ELSE 9 END ) as Vis_Dia_27,
			
		(CASE WHEN @DIAS_FIN >= @DIAS_26 THEN CONVERT(CHAR(10), @DIAS_26, 23) ELSE 'A' END ) as DIA_26,
		(CASE WHEN @DIAS_FIN >= @DIAS_26 
			THEN (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_26 )
			ELSE 9 END ) as Vis_Dia_26,		
			
		CONVERT(CHAR(10), @DIAS_25, 23) AS DIA_25,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_25 ) as Vis_Dia_25,
		CONVERT(CHAR(10), @DIAS_24, 23) AS DIA_24,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_24 ) as Vis_Dia_24,
		CONVERT(CHAR(10), @DIAS_23, 23) AS DIA_23,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_23 ) as Vis_Dia_23,
		CONVERT(CHAR(10), @DIAS_22, 23) AS DIA_22,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_22 ) as Vis_Dia_22,
		CONVERT(CHAR(10), @DIAS_21, 23) AS DIA_21,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_21 ) as Vis_Dia_21,
		CONVERT(CHAR(10), @DIAS_20, 23) AS DIA_20,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_20 ) as Vis_Dia_20,
		CONVERT(CHAR(10), @DIAS_19, 23) AS DIA_19,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_19 ) as Vis_Dia_19,
		CONVERT(CHAR(10), @DIAS_18, 23) AS DIA_18,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_18 ) as Vis_Dia_18,
		CONVERT(CHAR(10), @DIAS_17, 23) AS DIA_17,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_17 ) as Vis_Dia_17,
		CONVERT(CHAR(10), @DIAS_16, 23) AS DIA_16,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_16 ) as Vis_Dia_16,
		CONVERT(CHAR(10), @DIAS_15, 23) AS DIA_15,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_15 ) as Vis_Dia_15,
		CONVERT(CHAR(10), @DIAS_14, 23) AS DIA_14,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_14 ) as Vis_Dia_14,
		CONVERT(CHAR(10), @DIAS_13, 23) AS DIA_13,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_13 ) as Vis_Dia_13,
		CONVERT(CHAR(10), @DIAS_12, 23) AS DIA_12,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_12 ) as Vis_Dia_12,
		CONVERT(CHAR(10), @DIAS_11, 23) AS DIA_11,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_11 ) as Vis_Dia_11,
		CONVERT(CHAR(10), @DIAS_10, 23) AS DIA_10,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_10 ) as Vis_Dia_10,
		CONVERT(CHAR(10), @DIAS_9, 23) AS DIA_9,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_9 ) as Vis_Dia_9,
		CONVERT(CHAR(10), @DIAS_8, 23) AS DIA_8,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_8 ) as Vis_Dia_8,
		CONVERT(CHAR(10), @DIAS_7, 23) AS DIA_7,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_7 ) as Vis_Dia_7,
		CONVERT(CHAR(10), @DIAS_6, 23) AS DIA_6,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_6 ) as Vis_Dia_6,
		CONVERT(CHAR(10), @DIAS_5, 23) AS DIA_5,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_5 ) as Vis_Dia_5,
		CONVERT(CHAR(10), @DIAS_4, 23) AS DIA_4,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_4 ) as Vis_Dia_4,
		CONVERT(CHAR(10), @DIAS_3, 23) AS DIA_3,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_3 ) as Vis_Dia_3,
		CONVERT(CHAR(10), @DIAS_2, 23) AS DIA_2,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_2 ) as Vis_Dia_2,
		CONVERT(CHAR(10), @DIAS_IN, 23) AS DIA_IN, 
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0 /*AND VP1.PWORK_SNR=PSW.PWORK_SNR*/ AND VP1.USER_SNR=U.user_snr AND VP1.visit_date = @DIAS_IN ) as Vis_Dia_In,
		
		
		--- DESPLEGADO DE CICLOS
		'Vis '+@Ciclo13 as Ciclo13,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr IN (SELECT user_snr FROM USERS where rec_stat=0 and user_type=4 and status=1) and VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0
		and VP1.visit_date >= (select Start_Date from CYCLES where name=@Ciclo13 and REC_STAT=0)
		and VP1.visit_date <= (select Finish_Date from CYCLES where name=@Ciclo13 and REC_STAT=0) ) as Visitas_C13,		 
		'Vis '+@Ciclo12 as Ciclo12,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr IN (SELECT user_snr FROM USERS where rec_stat=0 and user_type=4 and status=1) and VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0
		and VP1.visit_date >= (select Start_Date from CYCLES where name=@Ciclo12 and REC_STAT=0)
		and VP1.visit_date <= (select Finish_Date from CYCLES where name=@Ciclo12 and REC_STAT=0) ) as Visitas_C12,		 
		'Vis '+@Ciclo11 as Ciclo11,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr IN (SELECT user_snr FROM USERS where rec_stat=0 and user_type=4 and status=1) and VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0
		and VP1.visit_date >= (select Start_Date from CYCLES where name=@Ciclo11 and REC_STAT=0)
		and VP1.visit_date <= (select Finish_Date from CYCLES where name=@Ciclo11 and REC_STAT=0) ) as Visitas_C11,	 
		'Vis '+@Ciclo10 as Ciclo10,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr IN (SELECT user_snr FROM USERS where rec_stat=0 and user_type=4 and status=1) and VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0
		and VP1.visit_date >= (select Start_Date from CYCLES where name=@Ciclo10 and REC_STAT=0)
		and VP1.visit_date <= (select Finish_Date from CYCLES where name=@Ciclo10 and REC_STAT=0) ) as Visitas_C10,		 
		'Vis '+@Ciclo9 as Ciclo9,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr IN (SELECT user_snr FROM USERS where rec_stat=0 and user_type=4 and status=1) and VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0
		and VP1.visit_date >= (select Start_Date from CYCLES where name=@Ciclo9 and REC_STAT=0)
		and VP1.visit_date <= (select Finish_Date from CYCLES where name=@Ciclo9 and REC_STAT=0) ) as Visitas_C9,		 
		'Vis '+@Ciclo8 as Ciclo8,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr IN (SELECT user_snr FROM USERS where rec_stat=0 and user_type=4 and status=1) and VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0
		and VP1.visit_date >= (select Start_Date from CYCLES where name=@Ciclo8 and REC_STAT=0)
		and VP1.visit_date <= (select Finish_Date from CYCLES where name=@Ciclo8 and REC_STAT=0) ) as Visitas_C8,		 
		'Vis '+@Ciclo7 as Ciclo7,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr IN (SELECT user_snr FROM USERS where rec_stat=0 and user_type=4 and status=1) and VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0
		and VP1.visit_date >= (select Start_Date from CYCLES where name=@Ciclo7 and REC_STAT=0)
		and VP1.visit_date <= (select Finish_Date from CYCLES where name=@Ciclo7 and REC_STAT=0) ) as Visitas_C7,		 
		'Vis '+@Ciclo6 as Ciclo6,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr IN (SELECT user_snr FROM USERS where rec_stat=0 and user_type=4 and status=1) and VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0
		and VP1.visit_date >= (select Start_Date from CYCLES where name=@Ciclo6 and REC_STAT=0)
		and VP1.visit_date <= (select Finish_Date from CYCLES where name=@Ciclo6 and REC_STAT=0) ) as Visitas_C6,
		'Vis '+@Ciclo5 as Ciclo5,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr IN (SELECT user_snr FROM USERS where rec_stat=0 and user_type=4 and status=1) and VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0
		and VP1.visit_date >= (select Start_Date from CYCLES where name=@Ciclo5 and REC_STAT=0)
		and VP1.visit_date <= (select Finish_Date from CYCLES where name=@Ciclo5 and REC_STAT=0) ) as Visitas_C5,		 
		'Vis '+@Ciclo4 as Ciclo4,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr IN (SELECT user_snr FROM USERS where rec_stat=0 and user_type=4 and status=1) and VP1.pers_snr=P.pers_snr and  VP1.REC_STAT=0
		and VP1.visit_date >= (select Start_Date from CYCLES where name=@Ciclo4 and REC_STAT=0)
		and VP1.visit_date <= (select Finish_Date from CYCLES where name=@Ciclo4 and REC_STAT=0) ) as Visitas_C4,		 
		'Vis '+@Ciclo3 as Ciclo3,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr IN (SELECT user_snr FROM USERS where rec_stat=0 and user_type=4 and status=1) and VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0
		and VP1.visit_date >= (select Start_Date from CYCLES where name=@Ciclo3 and REC_STAT=0)
		and VP1.visit_date <= (select Finish_Date from CYCLES where name=@Ciclo3 and REC_STAT=0) ) as Visitas_C3,		 
		'Vis '+@Ciclo2 as Ciclo2,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr IN (SELECT user_snr FROM USERS where rec_stat=0 and user_type=4 and status=1) and VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0
		and VP1.visit_date >= (select Start_Date from CYCLES where name=@Ciclo2 and REC_STAT=0)
		and VP1.visit_date <= (select Finish_Date from CYCLES where name=@Ciclo2 and REC_STAT=0) ) as Visitas_C2,		 
		'Vis '+@Ciclo as Ciclo1,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr IN (SELECT user_snr FROM USERS where rec_stat=0 and user_type=4 and status=1) and VP1.pers_snr=P.pers_snr and VP1.REC_STAT=0
		and VP1.visit_date >= (select Start_Date from CYCLES where name=@Ciclo and REC_STAT=0)
		and VP1.visit_date <= (select Finish_Date from CYCLES where name=@Ciclo and REC_STAT=0) ) as Visitas_C1
		 
		
		from person P
		inner join perslocwork PLW on P.pers_snr = PLW.pers_snr and PLW.rec_stat=0
		inner join inst I on I.inst_snr = PLW.INST_SNR and I.rec_stat=0
		/*inner join inst I on VP.inst_snr = I.inst_snr*/
		inner join pers_srep_work PSW on PSW.pwork_snr=PLW.pwork_SNR and PSW.rec_stat=0 
		inner join User_Territ as UT on psw.user_snr= ut.user_snr and i.inst_snr = ut.inst_snr and ut.rec_stat=0
		inner join Users as U on U.user_snr = UT.user_snr and U.rec_stat=0 and U.status=1 and u.user_type=4
		inner join compline as cl on U.cline_snr = cl.cline_snr and cl.rec_Stat=0
		left outer join City on City.city_snr = I.city_snr
		inner join District as Dst on city.distr_snr = Dst.distr_snr
		inner join State on Dst.state_snr = State.state_snr
		left outer join Brick as IMS on IMS.brick_snr = City.brick_snr
		/*inner join Users as DM on DM.User_type = 5*/
		/*inner join compline as cl on U.cline_snr = cl.cline_snr*/
		left outer join inst_Type IT on IT.inst_type=I.inst_type and IT.rec_Stat=0
		left outer join codelist type on I.type_snr = type.clist_snr
		left outer join codelist ST on P.status_snr = ST.clist_snr
		left outer join codelist SEXO on P.sex_snr = SEXO.clist_snr
		left outer join codelist CATEG on P.category_snr = CATEG.clist_snr and CATEG.REC_STAT=0 and CATEG.STATUS=1
		/*left outer join person_ud PUD on PUD.pers_snr=P.pers_snr and PUD.rec_stat=0*/
		left outer join codelist ESP on P.spec_snr = ESP.clist_snr
		left outer join codelist ESP2 on P.subspec_snr = ESP2.clist_snr
		left outer join codelist HON on P.fee_type_snr = HON.clist_snr
		left outer join codelist PT on P.patperweek_snr = PT.clist_snr
		left outer join codelist DIFVIS on P.diffvis_snr = DIFVIS.clist_snr
		/*left outer join codelist ESTILO on P.pers_style_snr = ESTILO.clist_snr*/
		left outer join codelist FV on P.frecvis_snr = FV.clist_snr
		 
		LEFT OUTER JOIN CODELIST SEGM_INIC ON P.patperweek_snr=SEGM_INIC.CLIST_SNR AND SEGM_INIC.REC_STAT=0 AND SEGM_INIC.STATUS=1
		LEFT OUTER JOIN CODELIST SEGM_ACT ON P.perstype_SNR=SEGM_ACT.CLIST_SNR AND SEGM_ACT.REC_STAT=0 AND SEGM_ACT.STATUS=1
		left outer join codelist DIV_MED_INT on P.diffvis_snr = DIV_MED_INT.clist_snr and DIV_MED_INT.status=1 and DIV_MED_INT.rec_stat=0
		 
		 
		where
		P.pers_snr <> '00000000-0000-0000-0000-000000000000'
		and I.inst_snr <> '00000000-0000-0000-0000-000000000000'
		and P.rec_stat=0
		and P.status_snr in ('".$estatus."')
		and U.user_snr in ('".$ids."') 
		
		order by U.lname,P.lname,P.mothers_lname,P.fname ";

		
	//echo $qMedicos."<br>";
		
	if($tipo == 1 || $tipo == 2){//excel
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=listadoMedicosVisitadosCiclo.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else if($tipo == 3){
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array((array_sum($tam)/2),150));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('LISTADO DE MÉDICOS VISITADOS POR CICLO'));
		$pdf->Ln();
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(40,5,'Alfa Wassermann');
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
		
	$tamTabla = array_sum($tam) + 650;
	if( $tipo != 3){
		$tabla = '<table border="0">
			<tr>
				<td>
					<table>
						<tr>
							<td colspan="10" class="nombreReporte">LISTADO HISTÓRICO DE MÉDICOS VISITADOS POR CICLO</td>
						</tr>
						<tr>
							<td colspan="10" class="clienteReporte">Alfa Wassermann</td>
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
	$suma = 0;
	$cabeceras = array();
	foreach(sqlsrv_field_metadata($rsMedicos) as $field){
		if($i < 15){
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
			$k = $i;
		}else{
			if($i % 2 != 0){
				$cabeceras[] = utf8_encode($field['Name']);
			}
		}
		
		$i++;
	}
	$k++;
	if($tipo != 3){
		if($tipo == 2){
			$tabla .= '<td style="min-width:'.$tam[$k].'px;">Total</td>'; 
		}else{
			if($tipo == 1){
				$tabla .= '<td style="background-color: #A9BCF5;border: 1px solid #000;min-width:'.$tam[$k].'px;">Total</td>';
			}else{
			$tabla .= '<td style="min-width:'.$tam[$k].'px;">Total</td>';
			}
		}	
	}else{
		$pdf->Cell($tam[$k]/2,8,'Total',1,0,'C',1);
	}
	
	$i=1;
	while($regMedico = sqlsrv_fetch_array($rsMedicos)){
		if($i == 1){
			$k++;
			for($l = count($cabeceras)-1; $l >= 0; $l--){ 
				if ($regMedico[$cabeceras[$l]] != 'A'){
					if($tipo != 3){
						if($tipo == 2){
							$tabla .= '<td style="min-width:'.$tam[$k].'px;">'.utf8_encode($regMedico[$cabeceras[$l]]).'</td>'; 
						}else{
							if($tipo == 1){
								$tabla .= '<td style="background-color: #A9BCF5;border: 1px solid #000;min-width:'.$tam[$k].'px;">'.utf8_encode($regMedico[$cabeceras[$l]]).'</td>';
							}else{
							$tabla .= '<td style="min-width:'.$tam[$k].'px;">'.utf8_encode($regMedico[$cabeceras[$l]]).'</td>';
							}
						}	
					}else{
						$pdf->Cell($tam[$k]/2,8,$regMedico[$cabeceras[$l]],1,0,'C',1);
					}
					$k++;					
				}
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
		}
		if($tipo != 3){
			$tabla .= '<tr>';
		}
		 
		$visitasArr = array();
		$visitasArrSum = array();
		for($j=0;$j<sqlsrv_num_fields($rsMedicos);$j++){
			if(is_object($regMedico[$j])){
				foreach ($regMedico[$j] as $key => $val) {
					if(strtolower($key) == 'date'){
						$regMedico[$j] = substr($val, 0, 10);
					}
				}
			}
			
			if($j < 15){
				if($tipo != 3){
					if($tipo == 2){
						$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.utf8_encode($regMedico[$j]).'</td>';
					}else{
						if($tipo == 1){
							$tabla .= '<td style="border: 1px solid #000;white-space:nowrap;min-width:'.$tam[$j].'px;">'.utf8_encode($regMedico[$j]).'</td>';
						}else{
							$tabla .= '<td style="min-width:'.$tam[$j].'px;">'.utf8_encode($regMedico[$j]).'</td>';
						}
					}
				}else{
					$pdf->Cell($tam[$j]/2,8,utf8_encode($regMedico[$j]),1,0,'L',$fill);
				}
			}else{
				if($j % 2 == 0){
					if ($j > 85){  
						$visitasArrSum[] = $regMedico[$j];						
					}
					$visitasArr[] = $regMedico[$j];
					$k=16;
				}
			}
		}
		
		//print_r($visitasArr);
		//echo array_sum($visitasArr).'<br>';
		
		if($tipo != 3){
			if($tipo == 2){
				$tabla .= '<td style="min-width:'.$tam[$k].'px;">'.array_sum($visitasArrSum).'</td>';
			}else{
				if($tipo == 1){
					$tabla .= '<td style="border: 1px solid #000;white-space:nowrap;min-width:'.$tam[$k].'px;">'.array_sum($visitasArrSum).'</td>';
				}else{
					$tabla .= '<td style="min-width:'.$tam[$k].'px;">'.array_sum($visitasArrSum).'</td>';
				}
			}
		}else{
			$pdf->Cell($tam[$k]/2,8,array_sum($visitasArrSum),1,0,'L',$fill);
		}
		
		for($m = count($visitasArr)-1; $m >= 0; $m--){
			if ($visitasArr[$m] != 9){
				if($tipo != 3){
					if($tipo == 2){
						$tabla .= '<td style="min-width:'.$tam[$k].'px;">'.$visitasArr[$m].'</td>';
					}else{
						if($tipo == 1){
							$tabla .= '<td style="border: 1px solid #000;white-space:nowrap;min-width:'.$tam[$k].'px;">'.$visitasArr[$m].'</td>';
						}else{
							$tabla .= '<td style="min-width:'.$tam[$k].'px;">'.$visitasArr[$m].'</td>';
						}
					}
				}else{
					$pdf->Cell($tam[$k]/2,8,$visitasArr[$m],1,0,'L',$fill);
				}
				$k++;
			}
		}
		
		//$tabla .= '<td>raton</td>';
		
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