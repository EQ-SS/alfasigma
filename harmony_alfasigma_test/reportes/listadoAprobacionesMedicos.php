<?php
	/*** listado de medicos ***/
	include "../conexion.php";
	
	$tamanio = array(1,4,4,4,2,2,2,2,3,4,6,2,2,2,4,4,2,3,3,2,2,3,3,2,2,2,2,2,2,2,2,2,2,1,4,2,4,2,4,3,2,2,2,2,2,2,5,2,2,2,2,1,2,2,2,2,2,2,1,2,2,2,1,2,3,2,2,2,2,2,3,2,2,2,3,2,2,2,1,2,1,2,1,2,1,2,1,2,1,2,2,2);
	$tam = array(100,350,350,350,450,550,200,450,100,250, 250,150,200,150,150,100,150,150,150,100, 150,1250,150,200,200,200,200,300,200,200, 300,250,250,100,100,150,150);
	$estatus = $_POST['hdnEstatusListado'];
	$ids = (substr($_POST['hdnIDSListado'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDSListado'], ",")) : $_POST['hdnIDSListado'] ;
	$tipo = $_POST['hdnTipoListado'];
	$fechaI = $_POST['hdnFechaIListado'];
	$fechaF = $_POST['hdnFechaFListado'];
	
	$qMedicos = "Select
		/*aps.APPROVAL_STATUS_SNR,*/
		cl.name as Linea,
		upper(U.lname)+' '+upper(U.fname) as Representante,
		'{'+CAST((case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then pa.p_pers_snr else P.pers_snr end) AS VARCHAR(36))+'}' as Cod_Med,
		'{'+CAST((case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then pa.plw_INST_SNR else I.inst_snr end) AS VARCHAR(36))+'}' as Cod_Inst,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(pa.P_LNAME)+' '+upper(pa.P_MOTHERS_LNAME)+' '+upper(pa.P_FNAME) else upper(P.lname)+' '+upper(P.MOTHERS_LNAME)+' '+upper(P.fname) end) as Medico,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(insta.STREET1) else upper(I.street1) end) as Direccion,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then (case when insta.num_ext='0' then '' else insta.num_ext end)
		else (case when I.num_ext='0' then '' else I.num_ext end) end) as Num_Ext,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then Citya.name else City.name end) as Colonia,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then Citya.zip else City.zip end) as Cod_Postal,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then Dsta.name else Dst.name end) as Ciudad,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then Statea.name else State.name end) as Estado,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then isnull(SEXOa.name,' ') else SEXO.name end) as Sexo,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then isnull(ESP2a.name,' ') else isnull(ESP2.name,' ') end) as Sub_Esp,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then isnull(HONa.name,' ') else HON.name end) as Hon,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then isnull(PTa.name,' ') else isnull(PT.name,' ') end) as Pac,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then isnull(Pa.p_prof_id,' ') else P.prof_id end) as Cedula,
		
		CAST(CAST(isnull(isnull((select TOP 1 kt.T_DATE from kupdatelog ku, kommtran kt where
		ku.table_nr = 19
		and ku.OPERATION=1
		and ku.kTrAN_SNR = KT.KTRAN_SNR
		AND KU.REC_STAT=0
		AND KU.REC_KEY=P.PERS_SNR
		order by KT.T_DATE DESC
		), VP.create_date), P.CREATION_TIMESTAMP) AS DATE) AS VARCHAR(10)) as Fecha_Alta,
		
		CAST(CAST(isnull((select top 1 kt.T_DATE from kupdatelog ku, kommtran kt where
		ku.table_nr = 19
		and ku.OPERATION=2
		and ku.kTrAN_SNR = KT.KTRAN_SNR
		AND KU.REC_STAT=0 
		AND KU.REC_KEY=P.PERS_SNR
		order by KT.T_DATE DESC
		),P.CHANGED_TIMESTAMP) AS DATE) AS VARCHAR(10)) as 'Fecha Mod Baja',		
		
		CAST(CAST(ISNULL((CASE WHEN PSW.CHANGED_TIMESTAMP IS NOT NULL THEN 
		(CASE WHEN PSW.CREATION_TIMESTAMP IS NOT NULL THEN 
		(CASE WHEN PSW.CREATION_TIMESTAMP>PSW.CHANGED_TIMESTAMP THEN CAST(PSW.CREATION_TIMESTAMP AS DATE) ELSE CAST(PSW.CHANGED_TIMESTAMP AS DATE) END)
		ELSE CAST(PSW.CHANGED_TIMESTAMP AS DATE) END)
		ELSE PSW.CREATION_TIMESTAMP END),'2017-01-01')
		AS DATE) AS VARCHAR(10)) as 'Fecha Ruta',

		pa.p_movement_type as Tipo_mov,
		(case when aps.APPROVED_STATUS in (1,4) then 'Pendiente' when aps.APPROVED_STATUS=2 then 'Aprobado' when aps.APPROVED_STATUS=3 then 'Rechazado' when aps.APPROVED_STATUS=4 then 'Pendiente' end) as Estatus_mov,
		isnull(pa.PLW_DEL_REASON,' ') as 'Motivo Baja',
		isnull(aps.date_change,' ') as 'Fecha solicitud cambio',
		isnull(usa.LNAME+' '+usa.FNAME,' ') as 'Persona aprueba',
		(case when aps.APPROVED_DATE='1900-01-01' then null else aps.APPROVED_DATE end) as 'Fecha aprobacion cambio',
		/*isnull(MRa.NAME,' ') AS 'Motivo Rechazo',*/
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(pa.P_LNAME) else upper(P.lname) end) as Paterno,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(pa.P_mothers_lname) else upper(P.mothers_lname) end) as Materno,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(pa.P_FNAME) else upper(P.fname) end) as Nombre,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then ' ' else isnull(pa.P_LNAME,' ') end) as Paterno_Nvo,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then ' ' else isnull(pa.P_mothers_lname,' ') end) as Materno_Nvo,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then ' ' else isnull(pa.P_FNAME,' ') end) as Nombre_Nvo,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(espa.NAME) else upper(ESP.name) end) as Esp,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then ' ' else isnull(espa.NAME,' ') end) as Esp_Nvo,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(CATAWa.NAME) else upper(CATAW.NAME) end) as CATEG_AW,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then ' ' else isnull(CATAWa.NAME,' ') end) as CATEG_AW_Nvo,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(STa.name) else upper(ST.name) end) as Estatus,
		(case when pa.p_movement_type='N' and aps.APPROVED_STATUS in (1,4) then ' ' else isnull(STa.name,' ') end) as Estatus_Nvo

		
		from APPROVAL_STATUS APS
		Inner join PERSON_APPROVAL PA on PA.PERS_APPROVAL_SNR=APS.PERS_APPROVAL_SNR and PA.REC_STAT=0
		left outer join Users as U on U.user_snr = APS.change_user_snr and U.rec_stat=0
		left outer join person P on P.PERS_SNR=PA.P_PERS_SNR /*and P.rec_stat=0*/
		left outer join perslocwork PLW on P.pers_snr = PLW.pers_snr and PLW.rec_stat=0
		left outer join inst I on I.inst_snr = PLW.INST_SNR and I.rec_stat=0
		left outer join pers_srep_work PSW on PSW.pwork_snr=PLW.pwork_SNR and PSW.rec_Stat=0 
		left outer join User_Territ as UT on psw.user_snr= ut.user_snr and i.inst_snr = ut.inst_snr and ut.rec_stat=0
		left outer join City on City.city_snr = I.city_snr
		left outer join District as Dst on city.distr_snr = Dst.distr_snr
		left outer join State on Dst.state_snr = State.state_snr
		left outer join Brick as IMS on IMS.brick_snr = City.brick_snr
		left outer join Smart_Fechas_Med VP on VP.pers_snr = P.pers_snr
		left outer join compline as cl on U.cline_snr = cl.cline_snr
		left outer join inst_Type IT on IT.inst_type=I.inst_type and IT.rec_Stat=0
		left outer join codelist type on I.type_snr = type.clist_snr
		left outer join codelist ST on P.status_snr = ST.clist_snr
		left outer join codelist SEXO on P.sex_snr = SEXO.clist_snr
		left outer JOIN CODELIST CATAW ON CATAW.CLIST_SNR=P.category_snr AND P.REC_STAT=0
		left outer join codelist ESP on P.spec_snr = ESP.clist_snr
		left outer join codelist ESP2 on P.subSpec_snr = ESP2.clist_snr AND ESP2.STATUS=1 AND ESP2.REC_STAT=0
		left outer join codelist HON on P.fee_type_snr = HON.clist_snr
		left outer join codelist FV on P.frecvis_snr=FV.clist_snr and FV.rec_stat=0 and FV.status=1
		left outer join codelist PT on P.patperweek_snr = PT.clist_snr AND PT.REC_STAT=0 AND PT.STATUS=1
		left outer join PERSON_CONTACT PC ON PC.PERS_SNR=P.PERS_SNR AND PC.REC_STAT=0
		left outer join codelist PUESTO on PC.FUNCTION_snr = PUESTO.clist_snr AND PC.PERS_SNR=P.PERS_SNR AND PC.REC_STAT=0 AND PUESTO.STATUS=1
		 
		left outer join PERSON_UD PUD ON PUD.PERS_SNR=P.PERS_SNR AND PUD.REC_STAT=0
		left outer join pers_profile PER on P.pers_snr = PER.pers_snr AND PER.REC_STAT=0
		left outer join pers_profile_ud PERFIL on PER.persprofile_snr = PERFIL.persprofile_snr AND PERFIL.REC_STAT=0
		 
		left outer join codelist PERFIL7 on PUD.field_02_snr = PERFIL7.clist_snr and PERFIL7.status=1
		/*left outer join codelist PERFIL8 on P.PERS_POSITION_SNR = PERFIL8.clist_snr and PERFIL8.status=1*/
		left outer join codelist PERFIL9 on P.diffvis_snr = PERFIL9.clist_snr and PERFIL9.status=1 and PERFIL9.rec_stat=0
		left outer join codelist PERFIL10 on PERFIL.etapa_de_adopcion = PERFIL10.clist_snr and PERFIL10.status=1
		left outer join codelist PERFIL11 on PERFIL.personalidad = PERFIL11.clist_snr and PERFIL11.status=1
		left outer join codelist PERFIL12 on PERFIL.recibe = PERFIL12.clist_snr and PERFIL12.status=1
		left outer join codelist PERFILA on PERFIL.indicacion = PERFILA.clist_snr and PERFILA.status=1
		/*left outer join codelist PERFILB on PERFIL.categ_mdo_sii = PERFILB.clist_snr*/
		/*left outer join codelist PERFILC on PERFIL.categ_mdo_infgast = PERFILC.clist_snr*/
		left outer join codelist PERFILD on PERFIL.Mx_Px_SII = PERFILD.clist_snr and PERFILD.status=1
		left outer join codelist PERFILE on PERFIL.Mx_Px_Inf_Gastrointestinales = PERFILE.clist_snr and PERFILE.status=1
		left outer join codelist PERFILF on PERFIL.Mx_Px_IVC = PERFILF.clist_snr and PERFILF.status=1
		left outer join codelist PERFILG on PERFIL.Mx_Px_ConstipacionEstrenimien = PERFILG.clist_snr and PERFILG.status=1
		left outer join codelist PERFILH on PERFIL.Mx_Px_Encefalopatia = PERFILH.clist_snr and PERFILH.status=1
		 
		left outer join codelist IPartCod1 on IPartCod1.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod1.rec_stat=0 and IPartCod1.status=1 and IPartCod1.sort_num=0
		left outer join PERSON_BANK PerIm1 on PerIm1.bank_snr= IPartCod1.clist_snr and PerIm1.rec_stat=0 and PerIm1.pers_snr=P.pers_snr
		left outer join codelist IPartCod2 on IPartCod2.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod2.rec_stat=0 and IPartCod2.status=1 and IPartCod2.sort_num=1
		left outer join PERSON_BANK PerIm2 on PerIm2.bank_snr= IPartCod2.clist_snr and PerIm2.rec_stat=0 and PerIm2.pers_snr=P.pers_snr
		left outer join codelist IPartCod3 on IPartCod3.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod3.rec_stat=0 and IPartCod3.status=1 and IPartCod3.sort_num=2
		left outer join PERSON_BANK PerIm3 on PerIm3.bank_snr= IPartCod3.clist_snr and PerIm3.rec_stat=0 and PerIm3.pers_snr=P.pers_snr
		left outer join codelist IPartCod4 on IPartCod4.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod4.rec_stat=0 and IPartCod4.status=1 and IPartCod4.sort_num=3
		left outer join PERSON_BANK PerIm4 on PerIm4.bank_snr= IPartCod4.clist_snr and PerIm4.rec_stat=0 and PerIm4.pers_snr=P.pers_snr
		left outer join codelist IPartCod5 on IPartCod5.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod5.rec_stat=0 and IPartCod5.status=1 and IPartCod5.sort_num=4
		left outer join PERSON_BANK PerIm5 on PerIm5.bank_snr= IPartCod5.clist_snr and PerIm5.rec_stat=0 and PerIm5.pers_snr=P.pers_snr
		left outer join codelist IPartCod6 on IPartCod6.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod6.rec_stat=0 and IPartCod6.status=1 and IPartCod6.sort_num=5
		left outer join PERSON_BANK PerIm6 on PerIm6.bank_snr= IPartCod6.clist_snr and PerIm6.rec_stat=0 and PerIm6.pers_snr=P.pers_snr
		left outer join codelist IPartCod7 on IPartCod7.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod7.rec_stat=0 and IPartCod7.status=1 and IPartCod7.sort_num=6
		left outer join PERSON_BANK PerIm7 on PerIm7.bank_snr= IPartCod7.clist_snr and PerIm7.rec_stat=0 and PerIm7.pers_snr=P.pers_snr
		left outer join codelist IPartCod8 on IPartCod8.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod8.rec_stat=0 and IPartCod8.status=1 and IPartCod8.sort_num=7
		left outer join PERSON_BANK PerIm8 on PerIm8.bank_snr= IPartCod8.clist_snr and PerIm8.rec_stat=0 and PerIm8.pers_snr=P.pers_snr
		left outer join codelist IPartCod9 on IPartCod9.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod9.rec_stat=0 and IPartCod9.status=1 and IPartCod9.sort_num=8
		left outer join PERSON_BANK PerIm9 on PerIm9.bank_snr= IPartCod9.clist_snr and PerIm9.rec_stat=0 and PerIm9.pers_snr=P.pers_snr
		left outer join codelist IPartCod10 on IPartCod10.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod10.rec_stat=0 and IPartCod10.status=1 and IPartCod10.sort_num=9
		left outer join PERSON_BANK PerIm10 on PerIm10.bank_snr= IPartCod10.clist_snr and PerIm10.rec_stat=0 and PerIm10.pers_snr=P.pers_snr
		left outer join codelist IPartCod11 on IPartCod11.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod11.rec_stat=0 and IPartCod1.status=1 and IPartCod11.sort_num=10
		left outer join PERSON_BANK PerIm11 on PerIm11.bank_snr= IPartCod11.clist_snr and PerIm11.rec_stat=0 and PerIm11.pers_snr=P.pers_snr
		left outer join codelist IPartCod12 on IPartCod12.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod12.rec_stat=0 and IPartCod12.status=1 and IPartCod12.sort_num=11
		left outer join PERSON_BANK PerIm12 on PerIm12.bank_snr= IPartCod12.clist_snr and PerIm12.rec_stat=0 and PerIm12.pers_snr=P.pers_snr
		left outer join codelist IPartCod13 on IPartCod13.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod13.rec_stat=0 and IPartCod13.status=1 and IPartCod13.sort_num=12
		left outer join PERSON_BANK PerIm13 on PerIm13.bank_snr= IPartCod13.clist_snr and PerIm13.rec_stat=0 and PerIm13.pers_snr=P.pers_snr
		left outer join codelist IPartCod14 on IPartCod14.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod14.rec_stat=0 and IPartCod14.status=1 and IPartCod14.sort_num=13
		left outer join PERSON_BANK PerIm14 on PerIm14.bank_snr= IPartCod14.clist_snr and PerIm14.rec_stat=0 and PerIm14.pers_snr=P.pers_snr
		left outer join codelist IPartCod15 on IPartCod15.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod15.rec_stat=0 and IPartCod15.status=1 and IPartCod15.sort_num=14
		left outer join PERSON_BANK PerIm15 on PerIm15.bank_snr= IPartCod15.clist_snr and PerIm15.rec_stat=0 and PerIm15.pers_snr=P.pers_snr
		left outer join codelist IPartCod16 on IPartCod16.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod16.rec_stat=0 and IPartCod16.status=1 and IPartCod16.sort_num=15
		left outer join PERSON_BANK PerIm16 on PerIm16.bank_snr= IPartCod16.clist_snr and PerIm16.rec_stat=0 and PerIm16.pers_snr=P.pers_snr
		 
		left outer join Users usa on usa.USER_SNR = aps.approved_user_snr and usa.REC_STAT=0
		left outer join Inst insta on pa.plw_INST_SNR = insta.inst_snr and insta.REC_STAT=0
		left outer join City Citya on Citya.city_snr = Insta.city_snr
		left outer join District as Dsta on citya.distr_snr = Dsta.distr_snr
		left outer join State Statea on Dsta.state_snr = Statea.state_snr
		left outer join Brick as IMSa on IMSa.brick_snr = Citya.brick_snr
		left outer join inst_Type ITa on ITa.inst_type=Insta.inst_type and ITa.rec_Stat=0
		left outer join codelist typea on Insta.type_snr = typea.clist_snr
		left outer join codelist STa on Pa.p_status_snr = STa.clist_snr
		left outer join codelist SEXOa on Pa.p_sex_snr = SEXOa.clist_snr
		left outer join CODELIST CATAWa ON CATAWa.CLIST_SNR=Pa.p_category_snr AND CATAWa.REC_STAT=0
		left outer join codelist ESPa on Pa.p_spec_snr = ESPa.clist_snr and espa.REC_STAT=0
		left outer join codelist ESP2a on Pa.plw_spec2_snr = ESP2a.clist_snr AND ESP2a.STATUS=1 AND ESP2a.REC_STAT=0
		left outer join codelist HONa on Pa.p_fee_type_snr = HONa.clist_snr
		left outer join codelist FVa on Pa.p_frecvis_snr=FVa.clist_snr and FVa.rec_stat=0 and FVa.status=1
		left outer join codelist PTa on Pa.p_patperweek_snr = PTa.clist_snr AND PTa.REC_STAT=0 AND PTa.STATUS=1
		left outer join codelist MRa on APS.REJECT_REASON_SNR = MRa.clist_snr AND MRa.REC_STAT=0 AND MRa.STATUS=1
		left outer join PERSON_CONTACT PCa ON PCa.PERS_SNR=Pa.p_PERS_SNR AND PCa.REC_STAT=0
		left outer join codelist PUESTOa on PCa.FUNCTION_snr = PUESTOa.clist_snr AND PCa.PERS_SNR=Pa.p_PERS_SNR AND PCa.REC_STAT=0 AND PUESTOa.STATUS=1
		 
		left outer join PERSON_UD PUDa ON PUDa.PERS_SNR=Pa.P_PERS_SNR AND PUDa.REC_STAT=0
		left outer join pers_profile PERa on Pa.p_pers_snr = PERa.pers_snr AND PERa.REC_STAT=0
		left outer join pers_profile_ud PERFILas on PERa.persprofile_snr = PERFILas.persprofile_snr AND PERFILas.REC_STAT=0
		 
		left outer join codelist PERFIL7a on PUDa.field_02_snr = PERFIL7a.clist_snr and PERFIL7a.status=1
		/*left outer join codelist PERFIL8a on Pa.P_PERS_POSITION_SNR = PERFIL8a.clist_snr and PERFIL8a.status=1*/
		left outer join codelist PERFIL9a on Pa.P_diffvis_snr = PERFIL9a.clist_snr and PERFIL9a.status=1 and PERFIL9a.rec_stat=0
		left outer join codelist PERFIL10a on PERFILas.etapa_de_adopcion = PERFIL10a.clist_snr and PERFIL10a.status=1
		left outer join codelist PERFIL11a on PERFILas.personalidad = PERFIL11a.clist_snr and PERFIL11a.status=1
		left outer join codelist PERFIL12a on PERFILas.recibe = PERFIL12a.clist_snr and PERFIL12a.status=1
		left outer join codelist PERFILAa on PERFILas.indicacion = PERFILAa.clist_snr and PERFILAa.status=1
		left outer join codelist PERFILDa on PERFILas.Mx_Px_SII = PERFILDa.clist_snr and PERFILDa.status=1
		left outer join codelist PERFILEa on PERFILas.Mx_Px_Inf_Gastrointestinales = PERFILEa.clist_snr and PERFILEa.status=1
		left outer join codelist PERFILFa on PERFILas.Mx_Px_IVC = PERFILFa.clist_snr and PERFILFa.status=1
		left outer join codelist PERFILGa on PERFILas.Mx_Px_ConstipacionEstrenimien = PERFILGa.clist_snr and PERFILGa.status=1
		left outer join codelist PERFILHa on PERFILas.Mx_Px_Encefalopatia = PERFILHa.clist_snr and PERFILHa.status=1
		 
		 
		where APS.rec_stat=0
		and U.status=1
		and U.user_type=4
		/*and P.status_snr in ({?persstatus})*/
		and APS.CHANGE_USER_SNR in ('".$ids."')
		and cast(aps.date_change as date) between '".$fechaI."' and '".$fechaF."'
		/*and cast(aps.date_change as date) between '2019-08-01' and '2019-08-12'*/
		/*and aps.APPROVAL_STATUS_SNR<>'00000000-0000-0000-0000-000000000000'*/
		and pa.P_PERS_SNR <> '00000000-0000-0000-0000-000000000000'
		and aps.TABLE_NR=456
		 
		Order by cl.name,U.lname,P.lname,P.mothers_lname,P.fname ";
	
	//echo $qMedicos."<br>";
		
	if($tipo == 1 || $tipo == 2){//excel
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=listadoAprobacionesMedicos.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	}else if($tipo == 3){
		require('../pdf/fpdf.php');
		$pdf=new FPDF('L', 'mm', array((array_sum($tam)/2),150));
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->setDisplayMode(100, 'continuous');
		$pdf->Cell(40,5,utf8_decode('LISTADO DE APROBACIONES DE MÉDICOS'));
		$pdf->Ln();
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(40,5,'ALFASIGMA');
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
	//echo $qMedicos;
	$tamTabla = array_sum($tam) + 20;
	if( $tipo != 3){
		$tabla = '<table border="0">
			<tr>
				<td>
					<table>
						<tr>
							<td colspan="10" class="nombreReporte">LISTADO DE APROBACIONES DE MÉDICOS</td>
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
	foreach(sqlsrv_field_metadata($rsMedicos) as $field){
		//if($i < 47){
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
		//}
		$i++;
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
	$i=1;
	while($regMedico = sqlsrv_fetch_array($rsMedicos)){
		if($tipo != 3){
			$tabla .= '<tr>';
		}
		$aseguradoras = array();
		for($j=0;$j<sqlsrv_num_fields($rsMedicos);$j++){
			if(is_object($regMedico[$j])){
				foreach ($regMedico[$j] as $key => $val) {
					if(strtolower($key) == 'date'){
						$regMedico[$j] = substr($val, 0, 10);
						if($j == 24){
							if(strtoupper($regMedico[20]) == 'PENDIENTE'){
								$regMedico[$j] = '';
							}
						}
						
					}
				}
			}
			
			if($j == 28){
				if(strtoupper($regMedico[19]) == 'C'){
					if(strtoupper($regMedico[25]) == strtoupper($regMedico[28])){
						$regMedico[28] = '';
					}
				}
			}
			if($j == 29){
				if(strtoupper($regMedico[19]) == 'C'){
					if(strtoupper($regMedico[26]) == strtoupper($regMedico[29])){
						$regMedico[29] = '';
					}
				}
			}
			if($j == 30){
				if(strtoupper($regMedico[19]) == 'C'){
					if(strtoupper($regMedico[27]) == strtoupper($regMedico[30])){
						$regMedico[30] = '';
					}
				}
			}
			if($j == 32){
				if(strtoupper($regMedico[19]) == 'C'){
					if(strtoupper($regMedico[31]) == strtoupper($regMedico[32])){
						$regMedico[32] = '';
					}
				}
			}
			if($j == 34){
				if(strtoupper($regMedico[19]) == 'C'){
					if(strtoupper($regMedico[33]) == strtoupper($regMedico[34])){
						$regMedico[34] = '';
					}
				}
			}
			if($j == 36){
				if(strtoupper($regMedico[19]) == 'C'){
					if(strtoupper($regMedico[35]) == strtoupper($regMedico[36])){
						$regMedico[36] = '';
					}
				}
			}
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
			//}
		}
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
<style>
	#divListadoMedicos{
		overflow:scroll;
		height:440px;
		width:1330px;
	}
</style>