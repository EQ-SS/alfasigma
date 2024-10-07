	<?php
	/*** cobertura de medicos ***/
	include "../conexion.php";
	
	$ids = (substr($_POST['hdnIDS'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDS'], ",")) : $_POST['hdnIDS'] ;
	$tipo = $_POST['hdnTipoReporte'];
	$ciclo = $_POST['hdnCicloReporte'];
	
	$qMedicos = "DECLARE @CICLO as VARCHAR(36)
		DECLARE @DIAS_IN as DATE
		DECLARE @DIAS_FIN as DATE
		DECLARE @DIAS_IN2 as DATE
		DECLARE @DIAS_FIN2 as DATE
		DECLARE @DIAS_IN3 as DATE
		DECLARE @DIAS_FIN3 as DATE
		DECLARE @FECHA_ACT as DATE
		DECLARE @Dias_ciclo as FLOAT
		DECLARE @STATUS as VARCHAR(36)
		DECLARE @CAT1 as VARCHAR(36)
		DECLARE @CAT2 as VARCHAR(36)
		DECLARE @CAT3 as VARCHAR(36)
		DECLARE @CAT4 as VARCHAR(36)
		DECLARE @CATNC as VARCHAR(36)
		DECLARE @PRESENCIAL as VARCHAR(36)
		DECLARE @FALLIDA as VARCHAR(36)
		DECLARE @COMPLEMENT as VARCHAR(36)
		
		SET @CICLO = '".$ciclo."'
		/*SET @CICLO = (select CYCLE_SNR from CYCLES where rec_stat=0 and name = '2023-03') */
		SET @DIAS_IN = (select START_DATE from CYCLES where CYCLE_SNR = @CICLO)
		SET @DIAS_FIN = (select FINISH_DATE from CYCLES where CYCLE_SNR = @CICLO)
		SET @DIAS_IN2 = (select top 1 START_DATE from CYCLES where cast(FINISH_DATE as DATE) < @DIAS_IN and rec_stat=0 order by name desc)
		SET @DIAS_FIN2 = (select top 1 cast(FINISH_DATE as DATE) from CYCLES where cast(FINISH_DATE as DATE) < @DIAS_IN and rec_stat=0 order by name desc)
		SET @DIAS_IN3 = isnull((select top 1 START_DATE from CYCLES where cast(FINISH_DATE as DATE) < @DIAS_IN2 and rec_stat=0 order by name desc), dateadd(dd,-30,@DIAS_IN2))
		SET @DIAS_FIN3 = (select top 1 cast(FINISH_DATE as DATE) from CYCLES where cast(FINISH_DATE as DATE) < @DIAS_IN2 and rec_stat=0 order by name desc)
		SET @FECHA_ACT = (case when cast(getdate() as date) <= @DIAS_FIN then cast(getdate() as date) else @DIAS_FIN end)
		SET @Dias_ciclo = (Select cast(DAYS as int) from CYCLES where CYCLE_SNR = @CICLO)
		SET @STATUS = '19205DEC-F9F6-441A-9482-DB08D3394057'
		SET @CAT1 = 'A23008D4-E873-4335-9A0E-F4FFC3D68A35' /*A*/
		SET @CAT2 = '183A3C90-B876-4148-BBBA-52DE4D8BB851' /*B*/
		SET @CAT3 = '69E26C7C-B4F1-410E-9241-C2D0B112A7E3' /*C*/
		SET @CAT4 = '14FC1F87-4B38-45F4-9DC7-582983EE6987' /*D*/
		SET @CATNC = 'ED08AF79-A9C8-4A1C-82A9-08A598625C3E' /*SC*/
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
		
		select 
		LINEA.name as Linea,
		klr.REG_SNR,
		DM.lname + ' ' + DM.fname as RM,
		DM.USER_NR as Ruta_Gte,
		MR.USER_NR as Ruta,
		MR.lname + ' ' + MR.fname as SR,
		MR.TEL1 as CUOTA,
		@DIAS_IN as Inicio_Ciclo,
		@DIAS_FIN as Fin_Ciclo,
		@Dias_ciclo as Dias_Ciclo,
		(select count(fecha) from FECHAS) NDiasTransc,
		
		'A' as Cat1,
		'B' as Cat2,
		'C' as Cat3,
		'D' as Cat4,
		'SC' as CatNC,
		
		(select count(distinct PLW.pwork_snr) from perslocwork PLW, person P, pers_srep_work PSW
		where PLW.pwork_snr <> '00000000-0000-0000-0000-000000000000'
		and PLW.rec_stat = 0
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and P.status_snr = @STATUS
		and PSW.user_snr = MR.user_snr
		and PLW.pers_snr = P.pers_snr
		and PSW.pwork_snr = PLW.pwork_snr
		) as DR_NR,
		
		(select count(distinct VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS 
		and VP.visit_code_snr in ('5655BC78-6002-4097-82CC-8BA7E1FBD5FC','2B3A7099-AC7D-47A3-A274-F0B029791801')
		) as One_Vis,
		
		(select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS 
		and VP.visit_code_snr in ('5655BC78-6002-4097-82CC-8BA7E1FBD5FC','2B3A7099-AC7D-47A3-A274-F0B029791801')
		) as VisTot,
		
		(select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS 
		and VP.NOVIS_SNR = @FALLIDA
		) as Vis_Fallida,
		
		(select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS 
		and VP.visit_code_snr = @COMPLEMENT
		) as VisAcc_Complem,
		
		(select count(distinct VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS 
        and P.category_snr = @CAT1
		and VP.visit_code_snr in ('5655BC78-6002-4097-82CC-8BA7E1FBD5FC','2B3A7099-AC7D-47A3-A274-F0B029791801')
		) as One_Vis_Cat1,
		
		(select count(distinct PLW.pwork_snr) from perslocwork PLW, person P, pers_srep_work PSW
		where PLW.pwork_snr <> '00000000-0000-0000-0000-000000000000'
		and PLW.rec_stat = 0
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and P.status_snr = @STATUS
		and PSW.user_snr = MR.user_snr
		and PLW.pers_snr = P.pers_snr
		and PSW.pwork_snr = PLW.pwork_snr 
        and P.category_snr = @CAT1
		) as DR_NR_Cat1,
		
		(select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS 
        and P.category_snr = @CAT1
		and VP.visit_code_snr in ('5655BC78-6002-4097-82CC-8BA7E1FBD5FC','2B3A7099-AC7D-47A3-A274-F0B029791801')
		) as VisTot_Cat1,
		
		(select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS 
		and VP.NOVIS_SNR = @FALLIDA
        and P.category_snr = @CAT1
		) as Vis_Fallida_Cat1,
		
		(select count(VP.pers_snr) from visitpers VP, person P
		where VP.pers_snr = P.pers_snr
		and VP.rec_stat = 0
		and VP.user_snr = MR.user_snr
		and VP.visit_date between @DIAS_IN and @DIAS_FIN
		and P.status_snr = @STATUS 
		and VP.visit_code_snr = @COMPLEMENT
        and P.category_snr = @CAT1
		) as VisAcc_Complem_Cat1,
		
		(select count(distinct PLW.pwork_snr) from perslocwork PLW, person P, pers_srep_work PSW
		where PLW.pwork_snr <> '00000000-0000-0000-0000-000000000000'
		and PLW.rec_stat = 0
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and P.status_snr = @STATUS
		and PSW.user_snr = MR.user_snr
		and PLW.pers_snr = P.pers_snr
		and PSW.pwork_snr = PLW.pwork_snr 
        and P.category_snr = @CAT2
		) as DR_NR_Cat2,
		
		(select count(distinct PLW.pwork_snr) from perslocwork PLW, person P, pers_srep_work PSW
		where PLW.pwork_snr <> '00000000-0000-0000-0000-000000000000'
		and PLW.rec_stat = 0
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and P.status_snr = @STATUS
		and PSW.user_snr = MR.user_snr
		and PLW.pers_snr = P.pers_snr
		and PSW.pwork_snr = PLW.pwork_snr 
        and P.category_snr = @CAT3
		) as DR_NR_Cat3,
		
		(select count(distinct PLW.pwork_snr) from perslocwork PLW, person P, pers_srep_work PSW
		where PLW.pwork_snr <> '00000000-0000-0000-0000-000000000000'
		and PLW.rec_stat = 0
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and P.status_snr = @STATUS
		and PSW.user_snr = MR.user_snr
		and PLW.pers_snr = P.pers_snr
		and PSW.pwork_snr = PLW.pwork_snr 
        and P.category_snr = @CAT4
		) as DR_NR_Cat4,
		
		(select count(distinct PLW.pwork_snr) from perslocwork PLW, person P, pers_srep_work PSW
		where PLW.pwork_snr <> '00000000-0000-0000-0000-000000000000'
		and PLW.rec_stat = 0
		and PSW.rec_stat = 0
		and P.rec_stat = 0
		and P.status_snr = @STATUS
		and PSW.user_snr = MR.user_snr
		and PLW.pers_snr = P.pers_snr
		and PSW.pwork_snr = PLW.pwork_snr 
        and P.category_snr not in (@CATNC, '00000000-0000-0000-0000-000000000000')
		) as DR_NR_CatNC,
		
		(select count(distinct PLW.pwork_snr) from PERSON P
		inner join PERSLOCWORK PLW on PLW.rec_stat=0 and PLW.pers_snr = P.pers_snr
		inner join PERS_SREP_WORK PSW on PSW.pwork_snr = PLW.pwork_snr and PSW.pers_snr = P.pers_snr and PSW.rec_stat=0
		where P.rec_stat=0
		and PSW.user_snr=MR.user_snr
		and (
		(P.pers_snr in (select pers_snr from VISITPERS VPA where VPA.pers_snr = P.pers_snr and VPA.visit_date between @DIAS_IN and @DIAS_FIN and MR.user_snr = VPA.user_snr) 
		and P.pers_snr not in (select pers_snr from VISITPERS VPA2 where VPA2.pers_snr = P.pers_snr and VPA2.visit_date between @DIAS_IN2 and @DIAS_FIN2 and MR.user_snr = VPA2.user_snr) 
		and P.pers_snr not in (select pers_snr from VISITPERS VPA3 where VPA3.pers_snr = P.pers_snr and VPA3.visit_date between @DIAS_IN3 and @DIAS_FIN3 and MR.user_snr = VPA3.user_snr)
		)
		or
		(P.pers_snr not in (select pers_snr from VISITPERS VPA where VPA.pers_snr = P.pers_snr and VPA.visit_date between @DIAS_IN and @DIAS_FIN  and MR.user_snr = VPA.user_snr) 
		and P.pers_snr not in (select pers_snr from VISITPERS VPA2 where VPA2.pers_snr = P.pers_snr and VPA2.visit_date between @DIAS_IN2 and @DIAS_FIN2 and MR.user_snr = VPA2.user_snr) 
		and P.pers_snr in (select pers_snr from VISITPERS VPA3 where VPA3.pers_snr = P.pers_snr and VPA3.visit_date between @DIAS_IN3 and @DIAS_FIN3 and MR.user_snr = VPA3.user_snr)
		)
		or
		(P.pers_snr not in (select pers_snr from VISITPERS VPA where VPA.pers_snr = P.pers_snr and VPA.visit_date between @DIAS_IN and @DIAS_FIN and MR.user_snr = VPA.user_snr) 
		and P.pers_snr  in (select pers_snr from VISITPERS VPA2 where VPA2.pers_snr = P.pers_snr and VPA2.visit_date between @DIAS_IN2 and @DIAS_FIN2 and MR.user_snr = VPA2.user_snr) 
		and P.pers_snr not in (select pers_snr from VISITPERS VPA3 where VPA3.pers_snr = P.pers_snr and VPA3.visit_date between @DIAS_IN3 and @DIAS_FIN3 and MR.user_snr = VPA3.user_snr)
		) ) ) as SEC_1_3,
		
		(select count(distinct PLW.pwork_snr) from PERSON P
		inner join PERSLOCWORK PLW on PLW.rec_stat=0 and PLW.pers_snr = P.pers_snr
		inner join PERS_SREP_WORK PSW on PSW.pwork_snr = PLW.pwork_snr and PSW.pers_snr = P.pers_snr and PSW.rec_stat=0
		where P.rec_stat=0
		and PSW.user_snr=MR.user_snr
		and (
		(P.pers_snr in (select pers_snr from VISITPERS VPA where VPA.pers_snr = P.pers_snr and VPA.visit_date between @DIAS_IN and @DIAS_FIN and MR.user_snr = VPA.user_snr) 
		and P.pers_snr in (select pers_snr from VISITPERS VPA2 where VPA2.pers_snr = P.pers_snr and VPA2.visit_date between @DIAS_IN2 and @DIAS_FIN2 and MR.user_snr = VPA2.user_snr) 
		and P.pers_snr not in (select pers_snr from VISITPERS VPA3 where VPA3.pers_snr = P.pers_snr and VPA3.visit_date between @DIAS_IN3 and @DIAS_FIN3 and MR.user_snr = VPA3.user_snr)
		)
		or
		(P.pers_snr  in (select pers_snr from VISITPERS VPA where VPA.pers_snr = P.pers_snr and VPA.visit_date between @DIAS_IN and @DIAS_FIN and MR.user_snr = VPA.user_snr) 
		and P.pers_snr not in (select pers_snr from VISITPERS VPA2 where VPA2.pers_snr = P.pers_snr and VPA2.visit_date between @DIAS_IN2 and @DIAS_FIN2 and MR.user_snr = VPA2.user_snr) 
		and P.pers_snr in (select pers_snr from VISITPERS VPA3 where VPA3.pers_snr = P.pers_snr and VPA3.visit_date between @DIAS_IN3 and @DIAS_FIN3 and MR.user_snr = VPA3.user_snr)
		)
		or
		(P.pers_snr not in (select pers_snr from VISITPERS VPA where VPA.pers_snr = P.pers_snr and VPA.visit_date between @DIAS_IN and @DIAS_FIN and MR.user_snr = VPA.user_snr) 
		and P.pers_snr in (select pers_snr from VISITPERS VPA2 where VPA2.pers_snr = P.pers_snr and VPA2.visit_date between @DIAS_IN2 and @DIAS_FIN2 and MR.user_snr = VPA2.user_snr) 
		and P.pers_snr in (select pers_snr from VISITPERS VPA3 where VPA3.pers_snr = P.pers_snr and VPA3.visit_date between @DIAS_IN3 and @DIAS_FIN3 and MR.user_snr = VPA3.user_snr)
		) )	) as SEC_2_3,
		
		(select count(distinct PLW.pwork_snr) from PERSON P
		inner join PERSLOCWORK PLW on PLW.rec_stat=0 and PLW.pers_snr = P.pers_snr
		inner join PERS_SREP_WORK PSW on PSW.pwork_snr = PLW.pwork_snr and PSW.pers_snr = P.pers_snr and PSW.rec_stat=0
		inner join VISITPERS VPA on VPA.pers_snr = P.pers_snr and VPA.visit_date between @DIAS_IN and @DIAS_FIN and VPA.user_snr = PSW.user_snr
		inner join VISITPERS VPA2 on VPA2.pers_snr = P.pers_snr and VPA2.visit_date between @DIAS_IN2 and @DIAS_FIN2 and VPA2.user_snr = PSW.user_snr
		inner join VISITPERS VPA3 on VPA3.pers_snr = P.pers_snr and VPA3.visit_date between @DIAS_IN3 and @DIAS_FIN3 and VPA3.user_snr = PSW.user_snr	
		where P.rec_stat=0
		and MR.user_snr = PSW.user_snr
		) as SEC_3_3,
		
		(select count(distinct PLW.pwork_snr) from PERSON P
		inner join PERSLOCWORK PLW on PLW.rec_stat=0 and PLW.pers_snr = P.pers_snr
		inner join PERS_SREP_WORK PSW on PSW.pwork_snr = PLW.pwork_snr and PSW.pers_snr = P.pers_snr and PSW.rec_stat=0
		where P.rec_stat=0
		and PSW.user_snr = MR.user_snr
		and P.pers_snr not in (select pers_snr from VISITPERS VPA where VPA.rec_stat=0 and VPA.pers_snr = P.pers_snr and VPA.visit_date between @DIAS_IN3 and @DIAS_FIN and MR.user_snr = VPA.user_snr) 
		) as SEC_0_3,
		
		(select count(distinct PLW.pwork_snr) from PERSON P
		inner join PERSLOCWORK PLW on PLW.rec_stat=0 and PLW.pers_snr = P.pers_snr
		inner join PERS_SREP_WORK PSW on PSW.pwork_snr = PLW.pwork_snr and PSW.pers_snr = P.pers_snr and PSW.rec_stat=0
		where P.rec_stat=0
		and PSW.user_snr = MR.user_snr
        and P.category_snr = @CAT1
		and (
		(P.pers_snr in (select pers_snr from VISITPERS VPA where VPA.pers_snr = P.pers_snr and VPA.visit_date between @DIAS_IN and @DIAS_FIN and MR.user_snr = VPA.user_snr) 
		and P.pers_snr not in (select pers_snr from VISITPERS VPA2 where VPA2.pers_snr = P.pers_snr and VPA2.visit_date between @DIAS_IN2 and @DIAS_FIN2 and MR.user_snr = VPA2.user_snr) 
		and P.pers_snr not in (select pers_snr from VISITPERS VPA3 where VPA3.pers_snr = P.pers_snr and VPA3.visit_date between @DIAS_IN3 and @DIAS_FIN3 and MR.user_snr = VPA3.user_snr)
		)
		or
		(P.pers_snr not in (select pers_snr from VISITPERS VPA where VPA.pers_snr = P.pers_snr and VPA.visit_date between @DIAS_IN and @DIAS_FIN and MR.user_snr = VPA.user_snr) 
		and P.pers_snr not in (select pers_snr from VISITPERS VPA2 where VPA2.pers_snr = P.pers_snr and VPA2.visit_date between @DIAS_IN2 and @DIAS_FIN2 and MR.user_snr = VPA2.user_snr) 
		and P.pers_snr in (select pers_snr from VISITPERS VPA3 where VPA3.pers_snr = P.pers_snr and VPA3.visit_date between @DIAS_IN3 and @DIAS_FIN3 and MR.user_snr = VPA3.user_snr)
		)
		or
		(P.pers_snr not in (select pers_snr from VISITPERS VPA where VPA.pers_snr = P.pers_snr and VPA.visit_date between @DIAS_IN and @DIAS_FIN and MR.user_snr = VPA.user_snr) 
		and P.pers_snr  in (select pers_snr from VISITPERS VPA2 where VPA2.pers_snr = P.pers_snr and VPA2.visit_date between @DIAS_IN2 and @DIAS_FIN2 and MR.user_snr = VPA2.user_snr) 
		and P.pers_snr not in (select pers_snr from VISITPERS VPA3 where VPA3.pers_snr = P.pers_snr and VPA3.visit_date between @DIAS_IN3 and @DIAS_FIN3 and MR.user_snr = VPA3.user_snr)
		) ) ) as SEC_1_3_Cat1,
		
		(select count(distinct PLW.pwork_snr) from PERSON P
		inner join PERSLOCWORK PLW on PLW.rec_stat=0 and PLW.pers_snr = P.pers_snr
		inner join PERS_SREP_WORK PSW on PSW.pwork_snr = PLW.pwork_snr and PSW.pers_snr = P.pers_snr and PSW.rec_stat=0
		where P.rec_stat=0
		and PSW.user_snr = MR.user_snr
        and P.category_snr = @CAT1
		and (
		(P.pers_snr in (select pers_snr from VISITPERS VPA where VPA.pers_snr = P.pers_snr and VPA.visit_date between @DIAS_IN and @DIAS_FIN and MR.user_snr = VPA.user_snr) 
		and P.pers_snr in (select pers_snr from VISITPERS VPA2 where VPA2.pers_snr = P.pers_snr and VPA2.visit_date between @DIAS_IN2 and @DIAS_FIN2 and MR.user_snr = VPA2.user_snr) 
		and P.pers_snr not in (select pers_snr from VISITPERS VPA3 where VPA3.pers_snr = P.pers_snr and VPA3.visit_date between @DIAS_IN3 and @DIAS_FIN3 and MR.user_snr = VPA3.user_snr)
		) 
		or
		(P.pers_snr  in (select pers_snr from VISITPERS VPA where VPA.pers_snr = P.pers_snr and VPA.visit_date between @DIAS_IN and @DIAS_FIN and MR.user_snr = VPA.user_snr) 
		and P.pers_snr not in (select pers_snr from VISITPERS VPA2 where VPA2.pers_snr = P.pers_snr and VPA2.visit_date between @DIAS_IN2 and @DIAS_FIN2 and MR.user_snr = VPA2.user_snr) 
		and P.pers_snr in (select pers_snr from VISITPERS VPA3 where VPA3.pers_snr = P.pers_snr and VPA3.visit_date between @DIAS_IN3 and @DIAS_FIN3 and MR.user_snr = VPA3.user_snr)
		)
		or
		(P.pers_snr not in (select pers_snr from VISITPERS VPA where VPA.pers_snr = P.pers_snr and VPA.visit_date between @DIAS_IN and @DIAS_FIN and MR.user_snr = VPA.user_snr) 
		and P.pers_snr  in (select pers_snr from VISITPERS VPA2 where VPA2.pers_snr = P.pers_snr and VPA2.visit_date between @DIAS_IN2 and @DIAS_FIN2 and MR.user_snr = VPA2.user_snr) 
		and P.pers_snr  in (select pers_snr from VISITPERS VPA3 where VPA3.pers_snr = P.pers_snr and VPA3.visit_date between @DIAS_IN3 and @DIAS_FIN3 and MR.user_snr = VPA3.user_snr)
		) ) ) as SEC_2_3_Cat1,
		
		(select count(distinct PLW.pwork_snr) from PERSON P
		inner join PERSLOCWORK PLW on PLW.rec_stat=0 and PLW.pers_snr = P.pers_snr
		inner join PERS_SREP_WORK PSW on PSW.pwork_snr = PLW.pwork_snr and PSW.pers_snr = P.pers_snr and PSW.rec_stat=0
		inner join VISITPERS VPA on VPA.pers_snr = P.pers_snr and VPA.visit_date between @DIAS_IN and @DIAS_FIN and VPA.user_snr = PSW.user_snr
		inner join VISITPERS VPA2 on VPA2.pers_snr = P.pers_snr and VPA2.visit_date between @DIAS_IN2 and @DIAS_FIN2 and VPA2.user_snr = PSW.user_snr
		inner join VISITPERS VPA3 on VPA3.pers_snr = P.pers_snr and VPA3.visit_date between @DIAS_IN3 and @DIAS_FIN3 and VPA3.user_snr = PSW.user_snr	
		where P.rec_stat=0
		and MR.user_snr = PSW.user_snr
        and P.category_snr = @CAT1
		) as SEC_3_3_Cat1,
		
		(select count(distinct PLW.pwork_snr) from PERSON P
		inner join PERSLOCWORK PLW on PLW.rec_stat=0 and PLW.pers_snr = P.pers_snr
		inner join PERS_SREP_WORK PSW on PSW.pwork_snr = PLW.pwork_snr and PSW.pers_snr = P.pers_snr and PSW.rec_stat=0
		where P.rec_stat=0
		and PSW.user_snr = MR.user_snr
        and P.category_snr = @CAT1
		and P.pers_snr not in (select pers_snr from VISITPERS VPA where VPA.rec_stat=0 and VPA.pers_snr = P.pers_snr and VPA.visit_date between @DIAS_IN3 and @DIAS_FIN and MR.user_snr = VPA.user_snr) 
		) as SEC_0_3_Cat1
		
		
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
		
		order by DM.user_nr,DM.lname,DM.fname,MR.user_nr,MR.lname,MR.fname,klr.reg_snr ";
	
	//echo $qMedicos."<br>";
		
	if($tipo == 1 || $tipo == 2){//excel
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=ResumenEjecutivoRuta.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else if($tipo == 3){
		ob_start();
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array(1300,1750));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('Resumen Ejecutivo por Ruta'));
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

	$tamTabla = 3500;
	$tabla = '';
	if( $tipo != 3){
			$tabla .= '<table border="0">
				<tr>
					<td>
						<table>
							<tr>
								<td colspan="10" class="nombreReporte">Resumen Ejecutivo por Ruta</td>
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
	$totalVisUni = 0;
	$totalVis = 0;
	$totalRevis = 0;
	$totalVisFallida = 0;
	$totalAccionComp = 0;
	$totalAlcance = 0;
	$totalCob = 0;
	$totalMeds1 = 0;
	$totalVisUni1 = 0;
	$totalVis1 = 0;
	$totalRevis1 = 0;
	$totalVisFallida1 = 0;
	$totalAccionComp1 = 0;
	$totalAlcance1 = 0;
	$totalMeds2 = 0;
	$totalMeds3 = 0;
	$totalMeds4 = 0;
	$totalMeds5 = 0;
	$totalMedsNC = 0;
	$totalPart1 = 0;
	$totalPart2 = 0;
	$totalPart3 = 0;
	$totalPart4 = 0;
	$totalPartNC = 0;
	$totalS33 = 0;
	$totalS23 = 0;
	$totalS13 = 0;
	$totalS03 = 0;
	$totalPorcS33 = 0;
	$totalPorcS23 = 0;
	$totalPorcS13 = 0;
	$totalPorcS03 = 0;
	$totalS33Cat1 = 0;
	$totalS23Cat1 = 0;
	$totalS13Cat1 = 0;
	$totalS03Cat1 = 0;
	$totalPorcS33Cat1 = 0;
	$totalPorcS23Cat1 = 0;
	$totalPorcS13Cat1 = 0;
	$totalPorcS03Cat1 = 0;
	
	while($reg = sqlsrv_fetch_array($rsMedicos)){
		////suma nacional
		$totalMeds += $reg['DR_NR'];
		$totalVisUni += $reg['One_Vis'];
		$totalVis += $reg['VisTot'];
		$totalRevis += $reg['VisTot'] - $reg['One_Vis'];
		$totalVisFallida += $reg['Vis_Fallida'];
		$totalAccionComp += $reg['VisAcc_Complem'];
		if ($totalVisUni > 0 && $totalMeds > 0 ){
			$totalAlcance = ($totalVisUni / $totalMeds) * 100;
		}else{ 
			$totalAlcance = 0;
		}
		if ($totalVisUni > 0 && $totalVis > 0 ){
			$totalCob = ($totalVisUni / $totalVis) * 100 ;
		}else{
			$totalCob = 0;
		}
		$totalMeds1 += $reg['DR_NR_Cat1'];
		$totalVisUni1 += $reg['One_Vis_Cat1'];
		$totalVis1 += $reg['VisTot_Cat1'];
		$totalRevis1 += $reg['VisTot_Cat1'] - $reg['One_Vis_Cat1'];
		$totalVisFallida1 += $reg['Vis_Fallida_Cat1'];
		$totalAccionComp1 += $reg['VisAcc_Complem_Cat1'];
		if ($totalVisUni1 > 0 && $totalMeds1 > 0 ){
			$totalAlcance1 = ($totalVisUni1 / $totalMeds1) * 100;
		}else{ 
			$totalAlcance1 = 0;
		}
		$totalMeds2 += $reg['DR_NR_Cat2'];
		$totalMeds3 += $reg['DR_NR_Cat3'];
		$totalMeds4 += $reg['DR_NR_Cat4'];
		$totalMedsNC += $reg['DR_NR_CatNC'];
		if ($totalMeds1 > 0 && $totalMeds > 0 ){
			$totalPart1 = ($totalMeds1 / $totalMeds) * 100;
		}else{
			$totalPart1 = 0;
		}
		if ($totalMeds2 > 0 && $totalMeds > 0 ){
			$totalPart2 = ($totalMeds2 / $totalMeds) * 100;
		}else{
			$totalPart2 = 0;
		}
		if ($totalMeds3 > 0 && $totalMeds > 0 ){
			$totalPart3 = ($totalMeds3 / $totalMeds) * 100;
		}else{
			$totalPart3 = 0;
		}
		if ($totalMeds4 > 0 && $totalMeds > 0 ){
			$totalPart4 = ($totalMeds4 / $totalMeds) * 100;
		}else{
			$totalPart4 = 0;
		}
		if ($totalMedsNC > 0 && $totalMeds > 0 ){
			$totalPartNC = ($totalMedsNC / $totalMeds) * 100;
		}else{
			$totalPartNC = 0;
		}
		$totalS33 += $reg['SEC_3_3'];
		$totalS23 += $reg['SEC_2_3'];
		$totalS13 += $reg['SEC_1_3'];
		$totalS03 += $reg['SEC_0_3'];
		if ($totalS33 > 0 && $totalMeds > 0 ){
			$totalPorcS33 = ($totalS33 / $totalMeds) * 100;
		}else{
			$totalPorcS33 = 0;
		}
		if ($totalS23 > 0 && $totalMeds > 0 ){
			$totalPorcS23 = ($totalS23 / $totalMeds) * 100;
		}else{
			$totalPorcS23 = 0;
		}
		if ($totalS13 > 0 && $totalMeds > 0 ){
			$totalPorcS13 = ($totalS13 / $totalMeds) * 100;
		}else{
			$totalPorcS13 = 0;
		}
		if ($totalS03 > 0 && $totalMeds > 0 ){
			$totalPorcS03 = ($totalS03 / $totalMeds) * 100;
		}else{
			$totalPorcS03 = 0;
		}
		$totalS33Cat1 += $reg['SEC_3_3_Cat1'];
		$totalS23Cat1 += $reg['SEC_2_3_Cat1'];
		$totalS13Cat1 += $reg['SEC_1_3_Cat1'];
		$totalS03Cat1 += $reg['SEC_0_3_Cat1'];
		if ($totalS33Cat1 > 0 && $totalMeds1 > 0 ){
			$totalPorcS33Cat1 = ($totalS33Cat1 / $totalMeds1) * 100;
		}else{
			$totalPorcS33Cat1 = 0;
		}
		if ($totalS23Cat1 > 0 && $totalMeds1 > 0 ){
			$totalPorcS23Cat1 = ($totalS23Cat1 / $totalMeds1) * 100;
		}else{
			$totalPorcS23Cat1 = 0;
		}
		if ($totalS13Cat1 > 0 && $totalMeds1 > 0 ){
			$totalPorcS13Cat1 = ($totalS13Cat1 / $totalMeds1) * 100;
		}else{
			$totalPorcS13Cat1 = 0;
		}
		if ($totalS03Cat1 > 0 && $totalMeds1 > 0 ){
			$totalPorcS03Cat1 = ($totalS03Cat1 / $totalMeds1) * 100;
		}else{
			$totalPorcS03Cat1 = 0;
		}
		
		if($i == 1){
			////imprimir encabezados
			if($tipo != 3){
				$tabla .= '<td '.$estilocabecera.' rowspan="2" width="100px">Linea</td>';
				$tabla .= '<td '.$estilocabecera.' rowspan="2" width="100px">Ruta</td>';
				$tabla .= '<td '.$estilocabecera.' rowspan="2" width="400px">Nombre</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="8" width="800px" align="center">Cobertura y Alcance (Solo Medicos)</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="7" width="700px" align="center">Medicos Categoria A</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="5" width="500px" align="center">Distribucion de Medicos por Categoria</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="4" width="400px" align="center">Secuencia Total</td>';
				$tabla .= '<td '.$estilocabecera.' colspan="4" width="400px" align="center">Secuencia en Categoria A</td>';
				$tabla .= '<tr><td '.$estilocabecera.' width="100px" align="center">Medicos</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Unicas</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Revisitas</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Totales</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visita Fallida</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Accion Complemen</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Alcance</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Cobertura</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Medicos</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Unicas</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Revisitas</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visitas Totales</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Visita Fallida</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Accion Complemen</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">Alcance</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$reg['Cat1'].'</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$reg['Cat2'].'</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$reg['Cat3'].'</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$reg['Cat4'].'</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">'.$reg['CatNC'].'</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">3 de 3</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">2 de 3</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">1 de 3</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">No Visitados</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">3 de 3</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">2 de 3</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">1 de 3</td>';
				$tabla .= '<td '.$estilocabecera.' width="100px" align="center">No Visitados</td>';
			}else{
				$pdf->Ln();			
				$pdf->Cell(50,10,'','LTR',0,'L',1);
				$pdf->Cell(50,10,'','LTR',0,'L',1);
				$pdf->Cell(200,10,'','LTR',0,'L',1);
				$pdf->Cell(400,10,'Cobertura y Alcance (Solo Medicos)','LTR',0,'C',1);
				$pdf->Cell(350,10,'Medicos Categoria A','LTR',0,'C',1);
				$pdf->Cell(250,10,'Distribucion de Medicos por Categoria','LTR',0,'C',1);
				$pdf->Cell(200,10,'Secuencia Total','LTR',0,'C',1);
				$pdf->Cell(200,10,'Secuencia en Categoria A','LTR',0,'C',1);
				$pdf->Ln();
				$pdf->Cell(50,10,'Linea','LRB',0,'L',1);
				$pdf->Cell(50,10,'Ruta','LRB',0,'L',1);
				$pdf->Cell(200,10,'Nombre','LRB',0,'L',1);
				$pdf->Cell(50,10,'Medicos',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Unicas',1,0,'C',1);
				$pdf->Cell(50,10,'Revisitas',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Totales',1,0,'C',1);
				$pdf->Cell(50,10,'Visita Fallida',1,0,'C',1);
				$pdf->Cell(50,10,'Accion Complemen',1,0,'C',1);
				$pdf->Cell(50,10,'Alcance',1,0,'C',1);
				$pdf->Cell(50,10,'Cobertura',1,0,'C',1);
				$pdf->Cell(50,10,'Medicos',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Unicas',1,0,'C',1);
				$pdf->Cell(50,10,'Revisitas',1,0,'C',1);
				$pdf->Cell(50,10,'Visitas Totales',1,0,'C',1);
				$pdf->Cell(50,10,'Visita Fallida',1,0,'C',1);
				$pdf->Cell(50,10,'Accion Complemen',1,0,'C',1);
				$pdf->Cell(50,10,'Alcance',1,0,'C',1);
				$pdf->Cell(50,10,$reg['Cat1'],1,0,'C',1);
				$pdf->Cell(50,10,$reg['Cat2'],1,0,'C',1);
				$pdf->Cell(50,10,$reg['Cat3'],1,0,'C',1);
				$pdf->Cell(50,10,$reg['Cat4'],1,0,'C',1);
				$pdf->Cell(50,10,$reg['CatNC'],1,0,'C',1);
				$pdf->Cell(50,10,'3 de 3',1,0,'C',1);
				$pdf->Cell(50,10,'2 de 3',1,0,'C',1);
				$pdf->Cell(50,10,'1 de 3',1,0,'C',1);
				$pdf->Cell(50,10,'No Visitados',1,0,'C',1);
				$pdf->Cell(50,10,'3 de 3',1,0,'C',1);
				$pdf->Cell(50,10,'2 de 3',1,0,'C',1);
				$pdf->Cell(50,10,'1 de 3',1,0,'C',1);
				$pdf->Cell(50,10,'No Visitados',1,0,'C',1);
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
			$rutaGte = $reg['Ruta_Gte'];
			$nombreGte = $reg['RM'];
			$gteMeds = $reg['DR_NR'];
			$gteVisUni = $reg['One_Vis'];
			$gteVis = $reg['VisTot'];
			$gteRevis = $reg['VisTot'] - $reg['One_Vis'];
			$gteVisFallida = $reg['Vis_Fallida'];
			$gteAccionComp = $reg['VisAcc_Complem'];
			if ($reg['One_Vis'] > 0 && $reg['DR_NR'] > 0 ){
				$gteAlcance = ($reg['One_Vis'] / $reg['DR_NR']) * 100;
			}else{ 
				$gteAlcance = 0;
			}
			if ($gteVisUni > 0 && $gteVis > 0){
				$gteCob = ($gteVisUni / $gteVis) * 100 ;
			}else{
				$gteCob = 0;
			}
			$gteMeds1 = $reg['DR_NR_Cat1'];
			$gteVisUni1 = $reg['One_Vis_Cat1'];
			$gteVis1 = $reg['VisTot_Cat1'];
			$gteRevis1 = $reg['VisTot_Cat1'] - $reg['One_Vis_Cat1'];
			$gteVisFallida1 = $reg['Vis_Fallida_Cat1'];
			$gteAccionComp1 = $reg['VisAcc_Complem_Cat1'];
			if ($reg['One_Vis_Cat1'] > 0 && $reg['DR_NR_Cat1'] > 0 ){
				$gteAlcance1 = ($reg['One_Vis_Cat1'] / $reg['DR_NR_Cat1']) * 100;
			}else{ 
				$gteAlcance1 = 0;
			}
			$gteMeds2 = $reg['DR_NR_Cat2'];
			$gteMeds3 = $reg['DR_NR_Cat3'];
			$gteMeds4 = $reg['DR_NR_Cat4'];
			$gteMedsNC = $reg['DR_NR_CatNC'];
			$gtePart1 = 0;
			$gtePart2 = 0;
			$gtePart3 = 0;
			$gtePart4 = 0;
			$gtePartNC = 0;
			$gteS33 = $reg['SEC_3_3'];
			$gteS23 = $reg['SEC_2_3'];
			$gteS13 = $reg['SEC_1_3'];
			$gteS03 = $reg['SEC_0_3'];
			$gtePorcS33 = 0;
			$gtePorcS23 = 0;
			$gtePorcS13 = 0;
			$gtePorcS03 = 0;
			$gteS33Cat1 = $reg['SEC_3_3_Cat1'];
			$gteS23Cat1 = $reg['SEC_2_3_Cat1'];
			$gteS13Cat1 = $reg['SEC_1_3_Cat1'];
			$gteS03Cat1 = $reg['SEC_0_3_Cat1'];
			$gtePorcS33Cat1 = 0;
			$gtePorcS23Cat1 = 0;
			$gtePorcS13Cat1 = 0;
			$gtePorcS03Cat1 = 0;
			
		}else{
			////sumas gerentes
			$gerente = $reg['REG_SNR'];
			if($tempGerente == $gerente){
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
				if ($gteVisUni > 0 && $gteMeds > 0 ){
					$gteAlcance = ($gteVisUni / $gteMeds) * 100;
				}else{ 
					$gteAlcance = 0;
				}
				if ($gteVisUni > 0 && $gteVis > 0){
					$gteCob = ($gteVisUni / $gteVis) * 100 ;
				}else{
					$gteCob = 0;
				}
				$sumMeds1 = $reg['DR_NR_Cat1'];
				$gteMeds1 += $sumMeds1;
				$sumVisUni1 = $reg['One_Vis_Cat1'];
				$gteVisUni1 += $sumVisUni1;
				$sumRev1 = $reg['VisTot_Cat1'] - $reg['One_Vis_Cat1'];
				$gteRevis1 += $sumRev1;
				$sumVis1 = $reg['VisTot_Cat1'];
				$gteVis1 += $sumVis1;
				$sumVisFallida1 = $reg['Vis_Fallida_Cat1'];
				$gteVisFallida1 += $sumVisFallida1;
				$sumAccionComp1 = $reg['VisAcc_Complem_Cat1'];
				$gteAccionComp1 += $sumAccionComp1;
				if ($gteVisUni1 > 0 && $gteMeds1 > 0 ){
					$gteAlcance1 = ($gteVisUni1 / $gteMeds1) * 100;
				}else{ 
					$gteAlcance1 = 0;
				}
				$sumMeds2 = $reg['DR_NR_Cat2'];
				$gteMeds2 += $sumMeds2;
				$sumMeds3 = $reg['DR_NR_Cat3'];
				$gteMeds3 += $sumMeds3;
				$sumMeds4 = $reg['DR_NR_Cat4'];
				$gteMeds4 += $sumMeds4;
				$sumMedsNC = $reg['DR_NR_CatNC'];
				$gteMedsNC += $sumMedsNC;
				if ($gteMeds1 > 0 && $gteMeds > 0 ){
					$gtePart1 = ($gteMeds1 / $gteMeds) * 100;
				}else{
					$gtePart1 = 0;
				}
				if ($gteMeds2 > 0 && $gteMeds > 0){
					$gtePart2 = ($gteMeds2 / $gteMeds) * 100 ;
				}else{
					$gtePart2 = 0;
				}
				if ($gteMeds3 > 0 && $gteMeds > 0){
					$gtePart3 = ($gteMeds3 / $gteMeds) * 100 ;
				}else{
					$gtePart3 = 0;
				}
				if ($gteMeds4 > 0 && $gteMeds > 0){
					$gtePart4 = ($gteMeds4 / $gteMeds) * 100 ;
				}else{
					$gtePart4 = 0;
				}
				if ($gteMedsNC > 0 && $gteMeds > 0){
					$gtePartNC = ($gteMedsNC / $gteMeds) * 100 ;
				}else{
					$gtePartNC = 0;
				}
				$sumS33 = $reg['SEC_3_3'];
				$gteS33 += $sumS33;
				$sumS23 = $reg['SEC_2_3'];
				$gteS23 += $sumS23;
				$sumS13 = $reg['SEC_1_3'];
				$gteS13 += $sumS13;
				$sumS03 = $reg['SEC_0_3'];
				$gteS03 += $sumS03;
				if ($gteS33 > 0 && $gteMeds > 0){
					$gtePorcS33 = ($gteS33 / $gteMeds) * 100;
				}else{
					$gtePorcS33 = 0;
				}
				if ($gteS23 > 0 && $gteMeds > 0 ){
					$gtePorcS23 = ($gteS23 / $gteMeds) * 100;
				}else{
					$gtePorcS23 = 0;
				}
				if ($gteS13 > 0 && $gteMeds > 0 ){
					$gtePorcS13 = ($gteS13 / $gteMeds) * 100;
				}else{
					$gtePorcS13 = 0;
				}
				if ($gteS03 > 0 && $gteMeds > 0 ){
					$gtePorcS03 = ($gteS03 / $gteMeds) * 100;
				}else{
					$gtePorcS03 = 0;
				}
				$sumS33Cat1 = $reg['SEC_3_3_Cat1'];
				$gteS33Cat1 += $sumS33Cat1;
				$sumS23Cat1 = $reg['SEC_2_3_Cat1'];
				$gteS23Cat1 += $sumS23Cat1;
				$sumS13Cat1 = $reg['SEC_1_3_Cat1'];
				$gteS13Cat1 += $sumS13Cat1;
				$sumS03Cat1 = $reg['SEC_0_3_Cat1'];
				$gteS03Cat1 += $sumS03Cat1;				
				if ($gteS33Cat1 > 0 && $gteMeds1 > 0 ){
					$gtePorcS33Cat1 = ($gteS33Cat1 / $gteMeds1) * 100;
				}else{
					$gtePorcS33Cat1 = 0;
				}
				if ($gteS23Cat1 > 0 && $gteMeds1 > 0 ){
					$gtePorcS23Cat1 = ($gteS23Cat1 / $gteMeds1) * 100;
				}else{
					$gtePorcS23Cat1 = 0;
				}
				if ($gteS13Cat1 > 0 && $gteMeds1 > 0 ){
					$gtePorcS13Cat1 = ($gteS13Cat1 / $gteMeds1) * 100;
				}else{
					$gtePorcS13Cat1 = 0;
				}
				if ($gteS03Cat1 > 0 && $gteMeds1 > 0 ){
					$gtePorcS03Cat1 = ($gteS03Cat1 / $gteMeds1) * 100;
				}else{
					$gtePorcS03Cat1 = 0;
				}				
				
			}else{	
				////imprimir gerentes
				if($tipo != 3){				
					$tabla .= '<tr><td '.$estilogte.' width="100px"> </td>';
					$tabla .= '<td '.$estilogte.' width="100px">'.$rutaGte.'</td>';	
					$tabla .= '<td '.$estilogte.' width="200px">'.$nombreGte.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteMeds).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisUni).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteRevis).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVis).'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallida.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionComp.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcance, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteCob, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteMeds1.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisUni1.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteRevis1.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVis1.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallida1.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionComp1.'</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcance1, 2).'%</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePart1, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePart2, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePart3, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePart4, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePartNC, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePorcS33, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePorcS23, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePorcS13, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePorcS03, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePorcS33Cat1, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePorcS23Cat1, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePorcS13Cat1, 2).' %</td>';
					$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePorcS03Cat1, 2).' %</td>';
					$tabla .= '</tr>';
				}else{ 
					$pdf->Cell(50,10,'',1,0,'L',1);
					$pdf->Cell(50,10,$rutaGte,1,0,'L',1);
					$pdf->Cell(200,10,$nombreGte,1,0,'L',1);
					$pdf->Cell(50,10,number_format($gteMeds),1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteVisUni),1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteRevis),1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteVis),1,0,'C',1);
					$pdf->Cell(50,10,$gteVisFallida,1,0,'C',1);
					$pdf->Cell(50,10,$gteAccionComp,1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteAlcance, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gteCob, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gteMeds1),1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteVisUni1),1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteRevis1),1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteVis1),1,0,'C',1);
					$pdf->Cell(50,10,$gteVisFallida1,1,0,'C',1);
					$pdf->Cell(50,10,$gteAccionComp1,1,0,'C',1);
					$pdf->Cell(50,10,number_format($gteAlcance1, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtePart1, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtePart2, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtePart3, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtePart4, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtePartNC, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtePorcS33, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtePorcS23, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtePorcS13, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtePorcS03, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtePorcS33Cat1, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtePorcS23Cat1, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtePorcS13Cat1, 2).' %',1,0,'R',1);
					$pdf->Cell(50,10,number_format($gtePorcS03Cat1, 2).' %',1,1,'R',1);
				}
	
				////inicia var gerente
				$tempGerente = $reg['REG_SNR'];
				$rutaGte = $reg['Ruta_Gte'];
				$nombreGte = $reg['RM'];
				$gteMeds = $reg['DR_NR'];
				$gteVisUni = $reg['One_Vis'];
				$gteVis = $reg['VisTot'];
				$gteRevis = $reg['VisTot'] - $reg['One_Vis'];
				$gteVisFallida = $reg['Vis_Fallida'];
				$gteAccionComp = $reg['VisAcc_Complem'];
				if ($reg['One_Vis'] > 0 && $reg['DR_NR'] > 0){
					$gteAlcance = ($reg['One_Vis'] / $reg['DR_NR']) * 100;
				}else{ 
					$gteAlcance = 0;
				}
				if ($gteVisUni > 0 && $gteVis > 0){
					$gteCob = ($gteVisUni / $gteVis) * 100 ;
				}else{
					$gteCob = 0;
				}
				$gteMeds1 = $reg['DR_NR_Cat1'];
				$gteVisUni1 = $reg['One_Vis_Cat1'];
				$gteRevis1 = $reg['VisTot_Cat1'] - $reg['One_Vis_Cat1'];
				$gteVis1 = $reg['VisTot_Cat1'];
				$gteVisFallida1 = $reg['Vis_Fallida_Cat1'];
				$gteAccionComp1 = $reg['VisAcc_Complem_Cat1'];
				if ($reg['DR_NR_Cat1'] > 0 && $reg['One_Vis_Cat1'] > 0){
					$gteAlcance1 = ($reg['One_Vis_Cat1'] / $reg['DR_NR_Cat1']) * 100;
				}else{ 
					$gteAlcance1 = 0;
				}
				$gteMeds2 = $reg['DR_NR_Cat2'];
				$gteMeds3 = $reg['DR_NR_Cat3'];
				$gteMeds4 = $reg['DR_NR_Cat4'];
				$gteMedsNC = $reg['DR_NR_CatNC'];
				$gtePart1 = 0;
				$gtePart2 = 0;
				$gtePart3 = 0;
				$gtePart4 = 0;
				$gtePartNC = 0;
				$gteS33 = $reg['SEC_3_3'];
				$gteS23 = $reg['SEC_2_3'];
				$gteS13 = $reg['SEC_1_3'];
				$gteS03 = $reg['SEC_0_3'];
				$gtePorcS33 = 0;
				$gtePorcS23 = 0;
				$gtePorcS13 = 0;
				$gtePorcS03 = 0;
				$gteS33Cat1 = $reg['SEC_3_3_Cat1'];
				$gteS23Cat1 = $reg['SEC_2_3_Cat1'];
				$gteS13Cat1 = $reg['SEC_1_3_Cat1'];
				$gteS03Cat1 = $reg['SEC_0_3_Cat1'];
				$gtePorcS33Cat1 = 0;
				$gtePorcS23Cat1 = 0;
				$gtePorcS13Cat1 = 0;
				$gtePorcS03Cat1 = 0;
			}
		}
		
		////formulas repres
		$tabla .= '<tr>';
			$Rev = $reg['VisTot'] - $reg['One_Vis'];
			if ($reg['One_Vis'] > 0 && $reg['DR_NR'] > 0 ){
				$Alcance = ($reg['One_Vis'] / $reg['DR_NR']) * 100;
			}else{ 
				$Alcance = 0;
			}
			if ($reg['One_Vis'] > 0 && $reg['VisTot'] > 0 ){
				$Cob = ($reg['One_Vis'] / $reg['VisTot']) * 100;
			}else{
				$Cob = 0;
			}
			$Rev1 = $reg['VisTot_Cat1'] - $reg['One_Vis_Cat1'];
			if ($reg['One_Vis_Cat1'] > 0 && $reg['DR_NR_Cat1'] > 0 ){
				$Alcance1 = ($reg['One_Vis_Cat1'] / $reg['DR_NR_Cat1']) * 100;
			}else{ 
				$Alcance1 = 0;
			}
			if ($reg['DR_NR_Cat1'] > 0 && $reg['DR_NR'] > 0){
				$Part1 = ($reg['DR_NR_Cat1'] / $reg['DR_NR']) * 100;
			}else{
				$Part1 = 0;
			}
			if ($reg['DR_NR_Cat2']>0 && $reg['DR_NR']>0){
				$Part2 = ($reg['DR_NR_Cat2'] / $reg['DR_NR']) * 100 ;
			}else{
				$Part2 = 0;
			}
			if ($reg['DR_NR_Cat3']>0 && $reg['DR_NR']>0){
				$Part3 = ($reg['DR_NR_Cat3'] / $reg['DR_NR']) * 100 ;
			}else{
				$Part3 = 0;
			}
			if ($reg['DR_NR_Cat4']>0 && $reg['DR_NR']>0){
				$Part4 = ($reg['DR_NR_Cat4'] / $reg['DR_NR']) * 100 ;
			}else{
				$Part4 = 0;
			}
			if ($reg['DR_NR_CatNC']>0 && $reg['DR_NR']>0){
				$PartNC = ($reg['DR_NR_CatNC'] / $reg['DR_NR']) * 100 ;
			}else{
				$PartNC = 0;
			}
			if ($reg['SEC_0_3'] > 0 && $reg['DR_NR'] > 0){
				$PorcS03 = ($reg['SEC_0_3'] / $reg['DR_NR']) * 100;
			}else{
				$PorcS03 = 0;
			}
			if ($reg['SEC_1_3'] > 0 && $reg['DR_NR'] > 0){
				$PorcS13 = ($reg['SEC_1_3'] / $reg['DR_NR']) * 100;
			}else{
				$PorcS13 = 0;
			}
			if ($reg['SEC_2_3'] > 0 && $reg['DR_NR'] > 0){
				$PorcS23 = ($reg['SEC_2_3'] / $reg['DR_NR']) * 100;
			}else{
				$PorcS23 = 0;
			}
			if ($reg['SEC_3_3'] > 0 && $reg['DR_NR'] > 0){
				$PorcS33 = ($reg['SEC_3_3'] / $reg['DR_NR']) * 100;
			}else{
				$PorcS33 = 0;
			}
			if ($reg['SEC_0_3_Cat1'] > 0 && $reg['DR_NR_Cat1'] > 0){
				$PorcS03Cat1 = ($reg['SEC_0_3_Cat1'] / $reg['DR_NR_Cat1']) * 100;
			}else{
				$PorcS03Cat1 = 0;
			}
			if ($reg['SEC_1_3_Cat1'] > 0 && $reg['DR_NR_Cat1'] > 0){
				$PorcS13Cat1 = ($reg['SEC_1_3_Cat1'] / $reg['DR_NR_Cat1']) * 100;
			}else{
				$PorcS13Cat1 = 0;
			}
			if ($reg['SEC_2_3_Cat1'] > 0 && $reg['DR_NR_Cat1'] > 0){
				$PorcS23Cat1 = ($reg['SEC_2_3_Cat1'] / $reg['DR_NR_Cat1']) * 100;
			}else{
				$PorcS23Cat1 = 0;
			}
			if ($reg['SEC_3_3_Cat1'] > 0 && $reg['DR_NR_Cat1'] > 0){
				$PorcS33Cat1 = ($reg['SEC_3_3_Cat1'] / $reg['DR_NR_Cat1']) * 100;
			}else{
				$PorcS33Cat1 = 0;
			}
		
		////imprimir repres
		if($tipo != 3){
			$tabla .= '<td '.$estilorepre.' width="100px">'.$reg['Linea'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px">'.$reg['Ruta'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="400px">'.$reg['SR'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['DR_NR'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['One_Vis'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$Rev.'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisTot'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Fallida'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisAcc_Complem'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($Alcance, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($Cob, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['DR_NR_Cat1'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['One_Vis_Cat1'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$Rev1.'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisTot_Cat1'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['Vis_Fallida_Cat1'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="center">'.$reg['VisAcc_Complem_Cat1'].'</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($Alcance1, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($Part1, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($Part2, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($Part3, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($Part4, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($PartNC, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($PorcS33, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($PorcS23, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($PorcS13, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($PorcS03, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($PorcS33Cat1, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($PorcS23Cat1, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($PorcS13Cat1, 2).' %</td>';
			$tabla .= '<td '.$estilorepre.' width="100px" align="right">'.number_format($PorcS03Cat1, 2).' %</td>';
			$tabla .= '</tr>';
		}else{
			$pdf->Cell(50,10,$reg['Linea'],1,0,'L',0);
			$pdf->Cell(50,10,$reg['Ruta'],1,0,'L',0);
			$pdf->Cell(200,10,$reg['SR'],1,0,'L',0);
			$pdf->Cell(50,10,$reg['DR_NR'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['One_Vis'],1,0,'C',0);
			$pdf->Cell(50,10,$Rev,1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisTot'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Fallida'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisAcc_Complem'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($Alcance, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($Cob, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,$reg['DR_NR_Cat1'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['One_Vis_Cat1'],1,0,'C',0);
			$pdf->Cell(50,10,$Rev1,1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisTot_Cat1'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['Vis_Fallida_Cat1'],1,0,'C',0);
			$pdf->Cell(50,10,$reg['VisAcc_Complem_Cat1'],1,0,'C',0);
			$pdf->Cell(50,10,number_format($Alcance1, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($Part1, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($Part2, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($Part3, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($Part4, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($PartNC, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($PorcS33, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($PorcS23, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($PorcS13, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($PorcS03, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($PorcS33Cat1, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($PorcS23Cat1, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($PorcS13Cat1, 2).' %',1,0,'R',0);
			$pdf->Cell(50,10,number_format($PorcS03Cat1, 2).' %',1,1,'R',0);
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
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteRevis).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVis).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallida.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionComp.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcance, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteCob, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteMeds1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVisUni1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteRevis1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($gteVis1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteVisFallida1.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.$gteAccionComp1.'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gteAlcance1, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePart1, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePart2, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePart3, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePart4, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePartNC, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePorcS33, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePorcS23, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePorcS13, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePorcS03, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePorcS33Cat1, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePorcS23Cat1, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePorcS13Cat1, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($gtePorcS03Cat1, 2).' %</td>';
		$tabla .= '</tr>';
	}else{ 
		$pdf->Cell(50,10,'',1,0,'L',1);
		$pdf->Cell(50,10,$rutaGte,1,0,'L',1);
		$pdf->Cell(200,10,$nombreGte,1,0,'L',1);
		$pdf->Cell(50,10,number_format($gteMeds),1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteVisUni),1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteRevis),1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteVis),1,0,'C',1);
		$pdf->Cell(50,10,$gteVisFallida,1,0,'C',1);
		$pdf->Cell(50,10,$gteAccionComp,1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteAlcance, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gteCob, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gteMeds1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteVisUni1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteRevis1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteVis1),1,0,'C',1);
		$pdf->Cell(50,10,$gteVisFallida1,1,0,'C',1);
		$pdf->Cell(50,10,$gteAccionComp1,1,0,'C',1);
		$pdf->Cell(50,10,number_format($gteAlcance1, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtePart1, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtePart2, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtePart3, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtePart4, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtePartNC, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtePorcS33, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtePorcS23, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtePorcS13, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtePorcS03, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtePorcS33Cat1, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtePorcS23Cat1, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtePorcS13Cat1, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($gtePorcS03Cat1, 2).' %',1,1,'R',1);
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
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalAlcance, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalCob, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalMeds1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisUni1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalRevis1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVis1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalVisFallida1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="center">'.number_format($totalAccionComp1).'</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalAlcance1, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalPart1, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalPart2, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalPart3, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalPart4, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalPartNC, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalPorcS33, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalPorcS23, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalPorcS13, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalPorcS03, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalPorcS33Cat1, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalPorcS23Cat1, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalPorcS13Cat1, 2).' %</td>';
		$tabla .= '<td '.$estilogte.' width="100px" align="right">'.number_format($totalPorcS03Cat1, 2).' %</td>';
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
		$pdf->Cell(50,10,number_format($totalAlcance, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalCob, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalMeds1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisUni1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalRevis1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVis1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalVisFallida1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAccionComp1),1,0,'C',1);
		$pdf->Cell(50,10,number_format($totalAlcance1, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalPart1, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalPart2, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalPart3, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalPart4, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalPartNC, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalPorcS33, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalPorcS23, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalPorcS13, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalPorcS03, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalPorcS33Cat1, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalPorcS23Cat1, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalPorcS13Cat1, 2).' %',1,0,'R',1);
		$pdf->Cell(50,10,number_format($totalPorcS03Cat1, 2).' %',1,1,'R',1);
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