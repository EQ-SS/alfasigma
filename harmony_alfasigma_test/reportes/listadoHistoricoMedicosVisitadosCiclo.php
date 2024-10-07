<?php
	set_time_limit(0);
	//ini_set("memory_limit", "2056M");
	/*** listado de medicos ***/
	include "../conexion.php";
	$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
	$reemplazar=array(" ", " ", " ", " ");
	require ("../vendor/autoload.php");
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	
	$tamanio = array(1,4,4,4,2,2,2,2,3,4,6,2,2,2,4,4,2,3,3,2,2,3,3,2,2,2,2,2,2,2,2,2,2,1,4,2,4,2,4,3,2,2,2,2,2,2,5,2,2,2,2,1,2,2,2,2,2,2,1,2,2,2,1,2,3,2,2,2,2,2,3,2,2,2,3,2,2,2,1,2,1,2,1,2,1,2,1,2,1,2,2,2);
	$tam = array(100,350,350,350,150,150,200,200,300,150, 100,200,150,150,100,100,100,100,100,100, 100,100,100,100,100,100,100,100,100,100 ,100,100,100,100,100,100,100,100,100,100 ,100,100,100,100,100,100,100,100,100,100 ,100,100,100,100,100,100,100,100,100,100 ,100,100,100,100,100);
	$registrosPorPagina = 20;
	
	$ids = (substr($_POST['hdnIDSListado'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDSListado'], ",")) : $_POST['hdnIDSListado'] ;
	$tipo = $_POST['hdnTipoListado'];
	//$fechaI = $_POST['hdnFechaIListado'];
	//$fechaF = $_POST['hdnFechaFListado'];
	$ciclo = $_POST['hdnCicloListado'];
	if(isset($_POST['pagina']) && $_POST['pagina'] != ''){
		$numPagina = $_POST['pagina'];
	}else{
		$numPagina = 1;
	}
	
	if(isset($_POST['hdnEstatusListado']) && $_POST['hdnEstatusListado'] != ''){
		$estatus = $_POST['hdnEstatusListado'];
	}else{
		$estatus = '';
	}

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
	 	 
		SET @DIAS_IN=(select START_DATE from CYCLES where CYCLE_SNR = '".$ciclo."' )
		/* SET @DIAS_IN=(select START_DATE from CYCLES where CYCLE_SNR = '153C2124-9746-4C55-B3CE-9B990CF4CBC4') */
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
		 
		;WITH Ciclos as (select CYCLE_SNR, NAME, START_DATE, FINISH_DATE, ROW_NUMBER() OVER (ORDER BY NAME desc) as numero from CYCLES
		where CYCLE_SNR<>'00000000-0000-0000-0000-000000000000' and rec_stat=0)
		 
		Select /*DM.lname+' '+M.fname as RM,*/
		cl.name as Linea,
		upper(U.lname)+' '+upper(U.fname) as Representante,
		'{'+CAST(P.pers_snr as VARCHAR(36))+'}' as 'Código Médico',
		'{'+CAST(I.inst_snr as VARCHAR(36))+'}' as 'Código Institución',
		upper(IT.NAME) as 'Tipo Inst',
		upper(type.name) as 'Tipo Pers',
		upper(P.lname) Paterno,
		upper(P.mothers_lname) Materno,
		upper(P.fname) as Nombre,
		ST.name as Estatus,
		CATEG.name as 'Categ AW',
		ESP.name as Especialidad,
		'' as 'Segmentación Inicial',
		'' as 'Segmentación Actual',
		'' as 'Div Med Int',
		 
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr=U.user_snr and VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B')
		and VP1.visit_date between C13.Start_Date and C1.Finish_Date) as Total,
		 
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
		year(p.birthdate) as Ano_Nac,
		FV.name as Frec_vis,
		P.gsm as Celular,
		P.tel1 as Tel1,
		P.tel2 as Tel2,*/
		 
		/* --- DESPLEGADO DE DIAS Y VISITAS ULTIMO CICLO */
		/* CONVERT(CHAR(10), C1.FINISH_DATE, 23) as DIA_FIN, */
		(CASE WHEN C1.FINISH_DATE >= @DIAS_35 THEN CONVERT(CHAR(10), @DIAS_35, 23) ELSE 'A' END ) as DIA_35,
		(CASE WHEN C1.FINISH_DATE >= @DIAS_35 
			THEN (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
			and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
			and VP1.visit_date = @DIAS_35 )
			ELSE 9 END ) as Vis_Dia_35,
		 
		(CASE WHEN C1.FINISH_DATE >= @DIAS_34 THEN CONVERT(CHAR(10), @DIAS_34, 23) ELSE 'A' END ) as DIA_34,
		(CASE WHEN C1.FINISH_DATE >= @DIAS_34 
			THEN (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
			and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
			and VP1.visit_date = @DIAS_34 )
			ELSE 9 END ) as Vis_Dia_34,
		 
		(CASE WHEN C1.FINISH_DATE >= @DIAS_33 THEN CONVERT(CHAR(10), @DIAS_33, 23) ELSE 'A' END ) as DIA_33,
		(CASE WHEN C1.FINISH_DATE >= @DIAS_33 
			THEN (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
			and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
			and VP1.visit_date = @DIAS_33 )
			ELSE 9 END ) as Vis_Dia_33,
		 
		(CASE WHEN C1.FINISH_DATE >= @DIAS_32 THEN CONVERT(CHAR(10), @DIAS_32, 23) ELSE 'A' END ) as DIA_32,
		(CASE WHEN C1.FINISH_DATE >= @DIAS_32 
			THEN (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
			and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
			and VP1.visit_date = @DIAS_32 )
			ELSE 9 END ) as Vis_Dia_32,
		 
		(CASE WHEN C1.FINISH_DATE >= @DIAS_31 THEN CONVERT(CHAR(10), @DIAS_31, 23) ELSE 'A' END ) as DIA_31,
		(CASE WHEN C1.FINISH_DATE >= @DIAS_31 
			THEN (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
			and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
			and VP1.visit_date = @DIAS_31 )
			ELSE 9 END ) as Vis_Dia_31,
		 
		(CASE WHEN C1.FINISH_DATE >= @DIAS_30 THEN CONVERT(CHAR(10), @DIAS_30, 23) ELSE 'A' END ) as DIA_30,
		(CASE WHEN C1.FINISH_DATE >= @DIAS_30 
			THEN (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
			and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
			and VP1.visit_date = @DIAS_30 )
			ELSE 9 END ) as Vis_Dia_30,
		 
		(CASE WHEN C1.FINISH_DATE >= @DIAS_29 THEN CONVERT(CHAR(10), @DIAS_29, 23) ELSE 'A' END ) as DIA_29,
		(CASE WHEN C1.FINISH_DATE >= @DIAS_29 
			THEN (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
			and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
			and VP1.visit_date = @DIAS_29 )
			ELSE 9 END ) as Vis_Dia_29,
		 
		(CASE WHEN C1.FINISH_DATE >= @DIAS_28 THEN CONVERT(CHAR(10), @DIAS_28, 23) ELSE 'A' END ) as DIA_28,
		(CASE WHEN C1.FINISH_DATE >= @DIAS_28 
			THEN (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
			and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
			and VP1.visit_date = @DIAS_28 )
			ELSE 9 END ) as Vis_Dia_28,
		 
		(CASE WHEN C1.FINISH_DATE >= @DIAS_27 THEN CONVERT(CHAR(10), @DIAS_27, 23) ELSE 'A' END ) as DIA_27,
		(CASE WHEN C1.FINISH_DATE >= @DIAS_27 
			THEN (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
			and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
			and VP1.visit_date = @DIAS_27 )
			ELSE 9 END ) as Vis_Dia_27,
		 
		(CASE WHEN C1.FINISH_DATE >= @DIAS_26 THEN CONVERT(CHAR(10), @DIAS_26, 23) ELSE 'A' END ) as DIA_26,
		(CASE WHEN C1.FINISH_DATE >= @DIAS_26 
			THEN (Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
			and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
			and VP1.visit_date = @DIAS_26 )
			ELSE 9 END ) as Vis_Dia_26,		
		 
		CONVERT(CHAR(10), @DIAS_25, 23) as DIA_25,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
		and VP1.visit_date = @DIAS_25 ) as Vis_Dia_25,
		CONVERT(CHAR(10), @DIAS_24, 23) as DIA_24,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
		and VP1.visit_date = @DIAS_24 ) as Vis_Dia_24,
		CONVERT(CHAR(10), @DIAS_23, 23) as DIA_23,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
		and VP1.visit_date = @DIAS_23 ) as Vis_Dia_23,
		CONVERT(CHAR(10), @DIAS_22, 23) as DIA_22,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
		and VP1.visit_date = @DIAS_22 ) as Vis_Dia_22,
		CONVERT(CHAR(10), @DIAS_21, 23) as DIA_21,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
		and VP1.visit_date = @DIAS_21 ) as Vis_Dia_21,
		CONVERT(CHAR(10), @DIAS_20, 23) as DIA_20,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
		and VP1.visit_date = @DIAS_20 ) as Vis_Dia_20,
		CONVERT(CHAR(10), @DIAS_19, 23) as DIA_19,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
		and VP1.visit_date = @DIAS_19 ) as Vis_Dia_19,
		CONVERT(CHAR(10), @DIAS_18, 23) as DIA_18,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
		and VP1.visit_date = @DIAS_18 ) as Vis_Dia_18,
		CONVERT(CHAR(10), @DIAS_17, 23) as DIA_17,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
		and VP1.visit_date = @DIAS_17 ) as Vis_Dia_17,
		CONVERT(CHAR(10), @DIAS_16, 23) as DIA_16,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
		and VP1.visit_date = @DIAS_16 ) as Vis_Dia_16,
		CONVERT(CHAR(10), @DIAS_15, 23) as DIA_15,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
		and VP1.visit_date = @DIAS_15 ) as Vis_Dia_15,
		CONVERT(CHAR(10), @DIAS_14, 23) as DIA_14,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
		and VP1.visit_date = @DIAS_14 ) as Vis_Dia_14,
		CONVERT(CHAR(10), @DIAS_13, 23) as DIA_13,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
		and VP1.visit_date = @DIAS_13 ) as Vis_Dia_13,
		CONVERT(CHAR(10), @DIAS_12, 23) as DIA_12,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
		and VP1.visit_date = @DIAS_12 ) as Vis_Dia_12,
		CONVERT(CHAR(10), @DIAS_11, 23) as DIA_11,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
		and VP1.visit_date = @DIAS_11 ) as Vis_Dia_11,
		CONVERT(CHAR(10), @DIAS_10, 23) as DIA_10,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
		and VP1.visit_date = @DIAS_10 ) as Vis_Dia_10,
		CONVERT(CHAR(10), @DIAS_9, 23) as DIA_9,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
		and VP1.visit_date = @DIAS_9 ) as Vis_Dia_9,
		CONVERT(CHAR(10), @DIAS_8, 23) as DIA_8,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
		and VP1.visit_date = @DIAS_8 ) as Vis_Dia_8,
		CONVERT(CHAR(10), @DIAS_7, 23) as DIA_7,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
		and VP1.visit_date = @DIAS_7 ) as Vis_Dia_7,
		CONVERT(CHAR(10), @DIAS_6, 23) as DIA_6,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
		and VP1.visit_date = @DIAS_6 ) as Vis_Dia_6,
		CONVERT(CHAR(10), @DIAS_5, 23) as DIA_5,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
		and VP1.visit_date = @DIAS_5 ) as Vis_Dia_5,
		CONVERT(CHAR(10), @DIAS_4, 23) as DIA_4,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
		and VP1.visit_date = @DIAS_4 ) as Vis_Dia_4,
		CONVERT(CHAR(10), @DIAS_3, 23) as DIA_3,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
		and VP1.visit_date = @DIAS_3 ) as Vis_Dia_3,
		CONVERT(CHAR(10), @DIAS_2, 23) as DIA_2,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
		and VP1.visit_date = @DIAS_2 ) as Vis_Dia_2,
		CONVERT(CHAR(10), @DIAS_IN, 23) as DIA_IN, 
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 and VP1.user_snr=U.user_snr 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B') 
		and VP1.visit_date = @DIAS_IN ) as Vis_Dia_In,
		 
		 
		/* --- DESPLEGADO DE CICLOS */
		'Ciclo '+C13.NAME as Ciclo13,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr=U.user_snr and VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B')
		and VP1.visit_date between C13.Start_Date and C13.Finish_Date) as Visitas_C13,		 
		'Ciclo '+C12.NAME as Ciclo12,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr=U.user_snr and VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B')
		and VP1.visit_date between C12.Start_Date and C12.Finish_Date) as Visitas_C12,		 
		'Ciclo '+C11.NAME as Ciclo11,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr=U.user_snr and VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B')
		and VP1.visit_date between C11.Start_Date and C11.Finish_Date) as Visitas_C11,	 
		'Ciclo '+C10.NAME as Ciclo10,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr=U.user_snr and VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B')
		and VP1.visit_date between C10.Start_Date and C10.Finish_Date) as Visitas_C10,		 
		'Ciclo '+C9.NAME as Ciclo9,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr=U.user_snr and VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B')
		and VP1.visit_date between C9.Start_Date and C9.Finish_Date) as Visitas_C9,		 
		'Ciclo '+C8.NAME as Ciclo8,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr=U.user_snr and VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B')
		and VP1.visit_date between C8.Start_Date and C8.Finish_Date) as Visitas_C8,		 
		'Ciclo '+C7.NAME as Ciclo7,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr=U.user_snr and VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B')
		and VP1.visit_date between C7.Start_Date and C7.Finish_Date) as Visitas_C7,		 
		'Ciclo '+C6.NAME as Ciclo6,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr=U.user_snr and VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B')
		and VP1.visit_date between C6.Start_Date and C6.Finish_Date) as Visitas_C6,
		'Ciclo '+C5.NAME as Ciclo5,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr=U.user_snr and VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B')
		and VP1.visit_date between C5.Start_Date and C5.Finish_Date) as Visitas_C5,		 
		'Ciclo '+C4.NAME as Ciclo4,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr=U.user_snr and VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B')
		and VP1.visit_date between C4.Start_Date and C4.Finish_Date) as Visitas_C4,		 
		'Ciclo '+C3.NAME as Ciclo3,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr=U.user_snr and VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B')
		and VP1.visit_date between C3.Start_Date and C3.Finish_Date) as Visitas_C3,		 
		'Ciclo '+C2.NAME as Ciclo2,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr=U.user_snr and VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B')
		and VP1.visit_date between C2.Start_Date and C2.Finish_Date) as Visitas_C2,		 
		'Ciclo '+C1.NAME as Ciclo1,
		(Select count(VP1.pers_snr) from visitpers VP1 where VP1.user_snr=U.user_snr and VP1.pers_snr=P.pers_snr and VP1.rec_stat=0 
		and VP1.visit_code_snr in ('636CD9F2-2A40-419F-A7EC-DA0EFC8DBB9F','F4BA358C-EDF3-439F-AB0E-C145CC02B875','01A87BCB-57BD-4180-A2CB-8DB4C464F6A3','DF24E0B0-552C-435E-B40D-BC0B472F995B')
		and VP1.visit_date between C1.Start_Date and C1.Finish_Date) as Visitas_C1
		 
		 
		from person P
		inner join perslocwork PLW on P.pers_snr = PLW.pers_snr 
		inner join inst I on I.inst_snr = PLW.inst_snr and I.rec_stat=0
		/*inner join inst I on VP.inst_snr = I.inst_snr*/
		inner join pers_srep_work PSW on PSW.pwork_snr = PLW.pwork_SNR 
		inner join User_Territ UT on psw.user_snr = ut.user_snr and i.inst_snr = ut.inst_snr 
		inner join Users U on U.user_snr = UT.user_snr 
		inner join compline cl on U.cline_snr = cl.cline_snr 
		left outer join City on City.city_snr = I.city_snr
		inner join District Dst on city.distr_snr = Dst.distr_snr
		inner join State on Dst.state_snr = State.state_snr
		left outer join Brick IMS on IMS.brick_snr = City.brick_snr
		left outer join inst_Type IT on IT.inst_type = I.inst_type 
		left outer join codelist type on P.PERSTYPE_SNR = type.clist_snr
		left outer join codelist ST on P.status_snr = ST.clist_snr
		left outer join codelist SEXO on P.sex_snr = SEXO.clist_snr
		left outer join codelist CATEG on P.category_snr = CATEG.clist_snr 
		/*left outer join person_ud PUD on PUD.pers_snr = P.pers_snr and PUD.rec_stat=0*/
		left outer join codelist ESP on P.spec_snr = ESP.clist_snr
		left outer join codelist ESP2 on P.subspec_snr = ESP2.clist_snr
		left outer join codelist HON on P.fee_type_snr = HON.clist_snr
		left outer join codelist PT on P.patperweek_snr = PT.clist_snr
		left outer join codelist DIFVIS on P.diffvis_snr = DIFVIS.clist_snr
		left outer join codelist FV on P.frecvis_snr = FV.clist_snr
		 
		left outer join CODELIST SEGM_INIC on P.patperweek_snr = SEGM_INIC.clist_snr 
		left outer join CODELIST SEGM_ACT on P.perstype_snr = SEGM_ACT.clist_snr 
		left outer join codelist DIV_MED_INT on P.diffvis_snr = DIV_MED_INT.clist_snr 
		 
		left outer join Ciclos C1 on C1.CYCLE_SNR='".$ciclo."'
		/* left outer join Ciclos C1 on C1.CYCLE_SNR='153C2124-9746-4C55-B3CE-9B990CF4CBC4' */
		left outer join Ciclos C2 on C2.numero=C1.numero+1
		left outer join Ciclos C3 on C3.numero=C1.numero+2
		left outer join Ciclos C4 on C4.numero=C1.numero+3
		left outer join Ciclos C5 on C5.numero=C1.numero+4
		left outer join Ciclos C6 on C6.numero=C1.numero+5
		left outer join Ciclos C7 on C7.numero=C1.numero+6
		left outer join Ciclos C8 on C8.numero=C1.numero+7
		left outer join Ciclos C9 on C9.numero=C1.numero+8
		left outer join Ciclos C10 on C10.numero=C1.numero+9
		left outer join Ciclos C11 on C11.numero=C1.numero+10
		left outer join Ciclos C12 on C12.numero=C1.numero+11
		left outer join Ciclos C13 on C13.numero=C1.numero+12
		 
		 
		where
		P.pers_snr <> '00000000-0000-0000-0000-000000000000'
		and I.inst_snr <> '00000000-0000-0000-0000-000000000000'
		and P.rec_stat=0
		and PLW.rec_stat=0
		and PSW.rec_stat=0 
		and UT.rec_stat=0
		and U.rec_stat=0 
		and U.status=1 
		and U.user_type=4
		and U.user_snr in ('".$ids."') ";
		
		if($estatus != ''){
			$qMedicos .= "and P.status_snr in ('".$estatus."') ";
		}
		
		$qMedicos .= "order by U.lname,P.lname,P.mothers_lname,P.fname ";

		
	//echo $qMedicos."<br>";
	
	if($tipo == 0){
		$registroIni = $numPagina * $registrosPorPagina - $registrosPorPagina;
		
		$tope = "OFFSET ".$registroIni." ROWS 
			FETCH NEXT ".$registrosPorPagina." ROWS ONLY ";
				
		$rsMedicosTotal = sqlsrv_query($conn, utf8_decode($qMedicos), array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		
		$totalRegistros = sqlsrv_num_rows($rsMedicosTotal);

		$rsMedicos = sqlsrv_query($conn, utf8_decode($qMedicos.$tope));
			
		$paginas = ceil($totalRegistros / $registrosPorPagina);
			
		//echo $qMedicos.$tope."<br>";
			
	}else{
		$rsMedicos = sqlsrv_query($conn, utf8_decode($qMedicos), array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	}
	
	if($tipo == 2){//excel sin formato
		$nombreArchivo = "../archivos/listadoHistoricoMedicosVisitadosCiclo".date("dmYHis").".xlsx";
		$spread = new Spreadsheet();	
		$spread->getProperties()
					->setCreator("Smart-Scale")
					->setTitle("Listado")
					->setDescription("Listado Historico de Medicos Visitados por Ciclo");
	}
	if($tipo == 1){//excel con formato
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=listadoHistoricoMedicosVisitadosCiclo.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
	if($tipo == 3){
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array((array_sum($tam)/2),150));
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('LISTADO HISTÓRICO DE MÉDICOS VISITADOS POR CICLO'));
		$pdf->Ln();
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(40,5,'ALFASIGMA');
		$pdf->Ln();
		$pdf->SetFont('Arial',10);
		$pdf->Cell(40,5,'Fecha: '.date("d/m/Y h:i:s"));
		$pdf->Ln();		
	}
	
	//$rsMedicos = sqlsrv_query($conn, utf8_decode($qMedicos));
	if( $rsMedicos === false ) {
		if( ($errors = sqlsrv_errors() ) != null) {
			foreach( $errors as $error ) {
				echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
				echo "code: ".$error[ 'code']."<br />";
				echo "message: ".$error[ 'message']."<br />";
			}
		}
	}
		
	$tamTabla = array_sum($tam) + 20;
	if($tipo == 0 || $tipo == 1){
		$tabla = '<table border="0">
			<tr>
				<td>
					<table>
						<tr>
							<td colspan="10" class="nombreReporte">LISTADO HISTÓRICO DE MÉDICOS VISITADOS POR CICLO</td>
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
					<div id="divListadoMedicos">';
						if($tipo == 0){
							$tabla .= '<table id="tblListadoMedicos" width="'.$tamTabla.'px" class="tablaReportes" >';
						}else{
							$tabla .= '<table width="'.$tamTabla.'px" style="border-collapse: collapse;">';
						}
	}
	
	if($tipo == 2){
		$spread->setActiveSheetIndex(0)
            ->setCellValue('A1', 'LISTADO HISTÓRICO DE MÉDICOS VISITADOS POR CICLO')
			->setCellValue('A2', 'ALFASIGMA')
			->setCellValue('A3', 'Fecha: '. date("d/m/Y h:i:s"));
	}	
	
	if($tipo != 3){
		if($tipo == 1){
			$tabla .= '<thead style="background-color: #A9BCF5;font-weight:bold;border: 1px solid #000;padding: 5px 5px 5px 5px;color:#000"><tr>';
		}
		if($tipo == 0){
			$tabla .= '<thead><tr>';
		}
	}else{
		$pdf->SetFillColor(169,188,245);
			$pdf->SetTextColor(0);
			$pdf->SetDrawColor(0);
			$pdf->SetLineWidth(1);
			$pdf->SetFont('','B');
	}
					
	$i = 0;
	$cabeceras = array();
	foreach(sqlsrv_field_metadata($rsMedicos) as $field){
		$celda = columna($i)."4";
		if($i <= 15){
			if($tipo != 3){
				if($tipo == 2){
					$spread->setActiveSheetIndex(0)
						->setCellValue($celda, utf8_encode($field['Name']));
				}else{
					if($tipo == 1){
						$tabla .= '<td style="background-color: #A9BCF5;border: 1px solid #000;min-width:'.$tam[$i].'px;">'.utf8_encode($field['Name']).'</td>';
					}else{
						$tabla .= '<td style="min-width:'.$tam[$i].'px;">'.utf8_encode($field['Name']).'</td>';
					}
				}	
			}else{
				$pdf->Cell($tam[$i]/2,8,utf8_encode($field['Name']),1,0,'C',1);
			}
			$k = $i;
		}else{
			if($i % 2 == 0){
				$cabeceras[] = utf8_encode($field['Name']);
			}
		}
		$i++;
	}
	$k++;
	
	$i=1;
	while($regMedico = sqlsrv_fetch_array($rsMedicos)){
		if($i == 1){
			$k++;
			for($l = count($cabeceras)-1; $l >= 0; $l--){ 
				if ($regMedico[$cabeceras[$l]] != 'A'){
					if($tipo != 3){
						if($tipo == 2){
							$row = $i + 4;
							$spread->setActiveSheetIndex(0)
								->setCellValue(columna($j).$row, utf8_encode($regMedico[$cabeceras[$l]]));
						}else{
							if($tipo == 1){
								$tabla .= '<td style="background-color: #A9BCF5;border: 1px solid #000;min-width:'.$tam[$k].'px;">'.utf8_encode($regMedico[$cabeceras[$l]]).'</td>';
							}else{
								$tabla .= '<td style="min-width:'.$tam[$k].'px;">'.utf8_encode($regMedico[$cabeceras[$l]]).'</td>';
							}
						}
					}else{
						$pdf->Cell($tam[$k]/2,8,utf8_encode($regMedico[$cabeceras[$l]]),1,0,'C',1);
					}
					$k++;					
				}
			}
			
			if($tipo == 0 || $tipo == 1){
				$tabla .= '</tr></thead>';
				$tabla .= '<tbody style="height:345px;">';
			}
			if($tipo == 3){
				$pdf->Ln();
				//Restauración de colores y fuentes
				$pdf->SetFillColor(224,235,255);
				$pdf->SetTextColor(0);
				$pdf->SetFont('');
				//Datos
				$fill=false;
			}			
		}
		
		if($tipo == 0 || $tipo == 1){
			$tabla .= '<tr>';
		}
		 
		$visitasArr = array();
		for($j=0;$j<sqlsrv_num_fields($rsMedicos);$j++){
			if(is_object($regMedico[$j])){
				foreach ($regMedico[$j] as $key => $val) {
					if(strtolower($key) == 'date'){
						$regMedico[$j] = substr($val, 0, 10);
					}
				}
			}
			
			if($j <= 15){
				if($tipo != 3){
					if($tipo == 2){
						$row = $i + 4;
						$spread->setActiveSheetIndex(0)
							->setCellValue(columna($j).$row, utf8_encode($regMedico[$j]));
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
				if($j % 2 != 0){
					$visitasArr[] = $regMedico[$j];
					$k=17;
				}
			}
		}
		
		//print_r($visitasArr);
		//echo array_sum($visitasArr).'<br>';
		
		for($m = count($visitasArr)-1; $m >= 0; $m--){
			if ($visitasArr[$m] != 9){
				if($tipo != 3){
					if($tipo == 2){
						$row = $i + 4;
						$spread->setActiveSheetIndex(0)
							->setCellValue(columna($j).$row, $visitasArr[$m]);
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
		
		if($tipo == 0 || $tipo == 1){
			$tabla .= '</tr>';
		}
		if($tipo == 3){
			$pdf->Ln();
			if($fill == true){
				$fill = false;
			}else{
				$fill = true;
			}
		}
		$i++;
	}
	
	if($tipo == 0){
		$numRegs = $i - 1;
		$tabla .= '<table width="100%" id="tblPaginasListadoMedicos"><tr style="background-color: #A9BCF5;font-weight:bold;border: 1px solid #000;padding: 5px 5px 5px 5px;color:#000;"><td align="center">';
		if($totalRegistros > $registrosPorPagina){
			$idsEnviar = str_replace("'","",$ids);
			if($numPagina > 1){
				$anterior = $numPagina - 1;
				$tabla .= "<a href='#' onClick='nuevaPaginaListados(1,\"".$idsEnviar."\",\"listadoMedicos\",\"".$estatus."\");'>inicio</a>&nbsp;&nbsp;";
				$tabla .= "<a href='#' onClick='nuevaPaginaListados(".$anterior.",\"".$idsEnviar."\",\"listadoMedicos\",\"".$estatus."\");'>anterior</a>&nbsp;&nbsp;";
			}
			$antes = $numPagina-5;
			$despues = $numPagina+5;
			for($i=1;$i<=$paginas;$i++){
				if($i == $numPagina){
					$tabla .= $i."&nbsp;&nbsp;";
				}else{
					if($i > $despues || $i < $antes){
						//$tabla .= "<a href='#' onClick='nuevaPagina(".$i.",\"".$hoy."\",\"".$idsEnviar."\",\"".$visitados."\");'>".$i."</a>&nbsp;&nbsp;";
					}
				}
			}
			if($numPagina < $paginas){
				$siguiente = $numPagina + 1;
				$tabla .= "<a href='#' onClick='nuevaPaginaListados(".$siguiente.",\"".$idsEnviar."\",\"listadoMedicos\",\"".$estatus."\");'>Siguiente</a>&nbsp;&nbsp;";
				$tabla .= "<a href='#' onClick='nuevaPaginaListados(".$paginas.",\"".$idsEnviar."\",\"listadoMedicos\",\"".$estatus."\");'>Fin</a>&nbsp;&nbsp;";
			}
			$tabla .= "Pag. ".$numPagina." de ".$paginas."&nbsp;&nbsp;&nbsp; Registros : ".$totalRegistros;
			//$tabla .= "</td></tr></tfoot>";
		}else{
			//$tabla .= "<tfoot><tr><td colspan='16' align='center'>";
			$tabla .= "Registros : ".$totalRegistros;
			//$tabla .= "</td></tr></tfoot>";
		}						
		$tabla .= '</td></tr>
		<tr>
			<td colspan="10" class="derechosReporte">© Smart-Scale</td>
		</tr>
	</table>';
		echo $tabla;
	}
	
	$row = $i+4;
	if($tipo == 2){
		$spread->setActiveSheetIndex(0)
			->setCellValue('A'.$row, 'Total registros: '.sqlsrv_num_rows($rsMedicos))
			->setTitle('listadoHistoricoMedicosVisitadosCiclo');

		$spread->setActiveSheetIndex(0);

		$objWriter = new Xlsx($spread);

		$objWriter->save($nombreArchivo);
		//header("Location: ".$nombreArchivo);
		echo "fin";
	}
	if($tipo == 1){
		$tabla .= '</tbody> ';
		$tabla .= '<tfoot style="background-color: #A9BCF5;font-weight:bold;border: 1px solid #000;padding: 5px 5px 5px 5px;color:#000;">';

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
	}
	if($tipo == 3){
		$pdf->Output();
	}	
	if($tipo == 0){
		echo '<script>
			$("#divCargando").hide();
			$("#hdnQueryListado").val("'.str_replace("'","\'",str_ireplace($buscar,$reemplazar,$qMedicos)).'");
		</script>';
		//echo str_replace("'","\'",$qMedicos);
	}
?>

<style>
	#divListadoMedicos{
		overflow:scroll;
		height:440px;
		width:1330px;
	}
</style>