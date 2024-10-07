<?php

	$qMedicos = "Select ";
	$qMedicos .= "cl.name as Linea, ";//0
	$qMedicos .= "upper(U.lname)+' '+upper(U.fname) as Representante, ";//1
	$qMedicos .= "P.pers_snr as 'Código Médico', ";//2
	$qMedicos .= "I.inst_snr as 'Código Inst', ";//3
	$qMedicos .= "upper(IT.NAME) AS 'Tipo Inst', ";//4
	$qMedicos .= "upper(type.name) as Tipo_Cons, ";//5
	$qMedicos .= "upper(P.lname) as Paterno, ";//6
	$qMedicos .= "upper(P.name_father) as Materno, ";//7
	$qMedicos .= "upper(P.fname) as Nombre, ";//8
	$qMedicos .= "upper(I.street1) as Calle, ";//9
	//$qMedicos .= "--upper(P.lname)+' '+upper(P.name_father)+' '+upper(P.fname) as Medico, ";
	$qMedicos .= "case when I.numext='0' then '' else I.numext end as 'Num Ext', ";//10
	$qMedicos .= "case when I.numint=0 then '' else I.numint end as 'Num Int', ";//11
	$qMedicos .= "City.name as Colonia, ";//12
	$qMedicos .= "City.zip as CP, ";//13
	$qMedicos .= "IMS.name as Brick, ";//14
	$qMedicos .= "Dst.name as Población, ";//15
	$qMedicos .= "State.name as Estado, ";//16
	$qMedicos .= "I.latitude, ";//17
	$qMedicos .= "I.longitude, ";//18
	$qMedicos .= "HON.name as Honorarios, ";//19
	$qMedicos .= "ESP.name as Especialidad, ";//20
	$qMedicos .= "ESP2.name as 'Sub Especialidad', ";//21
	$qMedicos .= "ST.name as Status, ";//22
	$qMedicos .= "cast(p.BIRTHDATE as DATE) as 'Fecha Nac', ";//23
	$qMedicos .= "SEXO.name as Sexo, ";//24
	$qMedicos .= "FV.name as 'Frec vis', ";//25
	$qMedicos .= "CATAW.NAME AS CATEG_AW, ";//26
	$qMedicos .= "PT.name as 'Pacs por Sem', ";//27
	$qMedicos .= "P.licencenr as Cedula, ";//28
	$qMedicos .= "case when BIRTH_YEAR > 0 then year(getdate())-BIRTH_YEAR else '' end  as Edad, ";//29
	//$qMedicos .= "--P.birth_year as Ano_Nac, ";
	$qMedicos .= "PLW.tel as Tel1,  ";//30
	$qMedicos .= "I.tel2 as Tel2, ";//31
	$qMedicos .= "PLW.GSM as Celular, ";//32 
	$qMedicos .= "PLW.email, ";//33
	$qMedicos .= "'' as 'Div. Med. Int' , ";//34
	//$qMedicos .= "--PERFIL9.name as Igualas, ";
	$qMedicos .= "PERFIL7.name as 'Lider de opinion', ";//35
	$qMedicos .= "'' as 'notas / Otras Inversiones', ";//36
	
	$qMedicos .= "isnull((select TOP 1 kt.T_DATE from kupdatelog ku, kommtran kt where ";
	$qMedicos .= "ku.table_nr = 19 ";
	$qMedicos .= "and ku.OPERATION=1 ";
	$qMedicos .= "and ku.kTrAN_SNR = KT.KTRAN_SNR ";
	$qMedicos .= "AND KU.REC_STAT=0  ";
	$qMedicos .= "AND KU.REC_KEY=P.PERS_SNR ";
	$qMedicos .= "), ISNULL(VP.create_date, ";
	$qMedicos .= "CASE WHEN ((SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR ORDER BY VISIT_DATE) IS NOT NULL ";
	$qMedicos .= "AND CAST(VP.change_date AS DATE) IS NOT NULL)  ";
	$qMedicos .= "THEN ";
	$qMedicos .= "CASE ";
	$qMedicos .= "WHEN (SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR ORDER BY VISIT_DATE) ";
	$qMedicos .= ">CAST(VP.change_date AS DATE) ";
	$qMedicos .= "THEN VP.change_date ";
	$qMedicos .= "WHEN (SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR ORDER BY VISIT_DATE) ";
	$qMedicos .= "<= ";
	$qMedicos .= "CAST(VP.change_date AS DATE) ";
	$qMedicos .= "THEN (SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR ORDER BY VISIT_DATE) ";
	$qMedicos .= "ELSE ";
	$qMedicos .= "CASE WHEN P.STATUS_SNR='37DFDA3B-29AB-47D3-87A9-62F0CC2D9B72' THEN CAST('2015-11-12' AS DATETIME) ";
	$qMedicos .= "ELSE CAST('2015-11-12' AS DATETIME) END ";
	$qMedicos .= "END ";
	$qMedicos .= "WHEN ((SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR ORDER BY VISIT_DATE) IS NOT NULL ";
	$qMedicos .= "AND CAST(VP.change_date AS DATE) IS NULL) ";
	$qMedicos .= "THEN ";
	$qMedicos .= "(SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR ORDER BY VISIT_DATE) ";
	$qMedicos .= "WHEN ((SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR ORDER BY VISIT_DATE) IS NULL ";
	$qMedicos .= "AND CAST(VP.change_date AS DATE) IS NOT NULL) ";
	$qMedicos .= "THEN ";
	$qMedicos .= "CAST(VP.change_date AS DATE) ";
	$qMedicos .= "WHEN ((SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR ORDER BY VISIT_DATE) IS NULL ";
	$qMedicos .= "AND CAST(VP.change_date AS DATE) IS NULL) ";
	$qMedicos .= "THEN ";
	$qMedicos .= "CAST('2015-11-12' AS DATETIME) ";
	$qMedicos .= "END) ) ";
	$qMedicos .= "as Fecha_Alta, ";//37
	
	//$qMedicos .= "VP.change_date as Fecha_Mod_Baja, ";
	
	$qMedicos .= "CASE WHEN CAST(VP.fecha_ruta AS DATE)<>'2017-01-01' THEN ";
	$qMedicos .= "cast(ISNULL((SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR AND USER_SNR=U.USER_SNR ";
	$qMedicos .= "ORDER BY VISIT_DATE),cast(vp.fecha_ruta as datetime)) as datetime) ";
	$qMedicos .= "ELSE ";
	$qMedicos .= "cast(ISNULL((SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR AND USER_SNR=U.USER_SNR ";
	$qMedicos .= "ORDER BY VISIT_DATE),cast('2017-01-01' as datetime)) as datetime) ";
	$qMedicos .= "END ";
	$qMedicos .= "as Fecha_Ruta, ";//38
	
	$qMedicos .= "PERFIL8.name as Paraestatales, ";//39
	$qMedicos .= "PERFIL8.name as Suma_Aseguradoras, ";//40
	$qMedicos .= "P.FOTO_NAME AS NOMBRE_CU, ";//41
	$qMedicos .= "PUESTO.NAME AS Puesto, ";//42
	$qMedicos .= "SegAteka.name as Seg_Ateka, ";//43
	$qMedicos .= "SegFlonon.name as Segment_Flonorm, ";//44
	$qMedicos .= "SegVessel.name as Segment_Vessel, ";//45
	$qMedicos .= "SegZirfos.name as Segment_Zirfos ";//46
	/*$qMedicos .= "isnull(VP.create_date, ";
	$qMedicos .= "(select TOP 1 kt.T_DATE from kupdatelog ku, kommtran kt where ";
	$qMedicos .= "ku.table_nr = 19 ";
	$qMedicos .= "and ku.OPERATION=1 ";
	$qMedicos .= "and ku.kTrAN_SNR = KT.KTRAN_SNR ";
	$qMedicos .= "AND KU.REC_STAT=0  ";
	$qMedicos .= "AND KU.REC_KEY=P.PERS_SNR ";
	$qMedicos .= ") ) F_ALTA, ";
	$qMedicos .= "CATEG.name as Categ, ";	
	$qMedicos .= "SEGM_INIC.NAME AS Segment_Inic, ";
	$qMedicos .= "SEGM_ACT.NAME AS Segment_Actual, ";
	$qMedicos .= "PERFIL10.name as Etapa_de_adopcion, ";
	$qMedicos .= "PERFILD.name as Mx_Px_SII, ";
	$qMedicos .= "PERFILE.name as Mx_Px_Inf_Gastrointestinales, ";
	$qMedicos .= "PERFILF.name as Mx_Px_IVC, ";
	$qMedicos .= "PERFILG.name as Mx_Px_ConstipacionEstrenimien, ";
	$qMedicos .= "PERFILH.name as Mx_Px_Encefalopatia, ";
	$qMedicos .= "P.info as Comentarios, ";
	$qMedicos .= "isnull((select top 1 kt.T_DATE from kupdatelog ku, kommtran kt where ";
	$qMedicos .= "ku.table_nr = 19 ";
	$qMedicos .= "and ku.OPERATION=2 ";
	$qMedicos .= "and ku.kTrAN_SNR = KT.KTRAN_SNR ";
	$qMedicos .= "AND KU.REC_STAT=0  ";
	$qMedicos .= "AND KU.REC_KEY=P.PERS_SNR ";
	$qMedicos .= "order by KT.T_DATE DESC ";
	$qMedicos .= "),VP.change_date) F_MODIF, ";
	$qMedicos .= "isnull(VP.fecha_ruta, ";
	$qMedicos .= "(select TOP 1 kt.T_DATE from kupdatelog ku, kommtran kt where ";
	$qMedicos .= "ku.table_nr = 19 ";
	$qMedicos .= "and ku.OPERATION=1 ";
	$qMedicos .= "and ku.kTrAN_SNR = KT.KTRAN_SNR ";
	$qMedicos .= "AND KU.REC_STAT=0  ";
	$qMedicos .= "AND KU.REC_KEY=P.PERS_SNR ";
	$qMedicos .= ") ) AS F_RUTA ";*/
	
	$qMedicos .= ",IPartCod1.name AS IPart1 ";
	$qMedicos .= ",(case when PerIm1.pers_SNR is null then '-' else IPartCod1.name end) as IPart1_SI ";
	$qMedicos .= ",IPartCod2.name AS IPart2 ";
	$qMedicos .= ",(case when PerIm2.pers_SNR is null then '-' else IPartCod2.name end) as IPart2_SI ";
	$qMedicos .= ",IPartCod3.name AS IPart3 ";
	$qMedicos .= ",(case when PerIm3.pers_SNR is null then '-' else IPartCod3.name end) as IPart3_SI ";
	$qMedicos .= ",IPartCod4.name AS IPart4 ";
	$qMedicos .= ",(case when PerIm4.pers_SNR is null then '-' else IPartCod4.name end) as IPart4_SI ";
	$qMedicos .= ",IPartCod5.name AS IPart5 ";
	$qMedicos .= ",(case when PerIm5.pers_SNR is null then '-' else IPartCod5.name end) as IPart5_SI ";
	$qMedicos .= ",IPartCod6.name AS IPart6 ";
	$qMedicos .= ",(case when PerIm6.pers_SNR is null then '-' else IPartCod6.name end) as IPart6_SI ";
	$qMedicos .= ",IPartCod7.name AS IPart7 ";
	$qMedicos .= ",(case when PerIm7.pers_SNR is null then '-' else IPartCod7.name end) as IPart7_SI ";
	$qMedicos .= ",IPartCod8.name AS IPart8 ";
	$qMedicos .= ",(case when PerIm8.pers_SNR is null then '-' else IPartCod8.name end) as IPart8_SI ";
	$qMedicos .= ",IPartCod9.name AS IPart9 ";
	$qMedicos .= ",(case when PerIm9.pers_SNR is null then '-' else IPartCod9.name end) as IPart9_SI ";
	$qMedicos .= ",IPartCod10.name AS IPart10 ";
	$qMedicos .= ",(case when PerIm10.pers_SNR is null then '-' else IPartCod10.name end) as IPart10_SI ";
	$qMedicos .= ",IPartCod11.name AS IPart11 ";
	$qMedicos .= ",(case when PerIm11.pers_SNR is null then '-' else IPartCod11.name end) as IPart11_SI ";
	$qMedicos .= ",IPartCod12.name AS IPart12 ";
	$qMedicos .= ",(case when PerIm12.pers_SNR is null then '-' else IPartCod12.name end) as IPart12_SI ";
	$qMedicos .= ",IPartCod13.name AS IPart13 ";
	$qMedicos .= ",(case when PerIm13.pers_SNR is null then '-' else IPartCod13.name end) as IPart13_SI ";
	$qMedicos .= ",IPartCod14.name AS IPart14 ";
	$qMedicos .= ",(case when PerIm14.pers_SNR is null then '-' else IPartCod14.name end) as IPart14_SI ";
	$qMedicos .= ",IPartCod15.name AS IPart15 ";
	$qMedicos .= ",(case when PerIm15.pers_SNR is null then '-' else IPartCod15.name end) as IPart15_SI ";
	$qMedicos .= ",IPartCod16.name AS IPart16 ";
	$qMedicos .= ",(case when PerIm16.pers_SNR is null then '-' else IPartCod16.name end) as IPart16_SI ";
	
	$qMedicos .= "from person P ";
	$qMedicos .= "inner join perslocwork PLW on P.pers_snr = PLW.pers_snr and PLW.rec_stat=0 ";
	$qMedicos .= "inner join inst I on I.inst_snr = PLW.loc_snr and I.rec_stat=0 ";
	$qMedicos .= "left outer join inst_Type IT on IT.inst_type=I.inst_type and IT.rec_Stat=0 ";
	$qMedicos .= "inner join pers_srep_work PSW on PSW.pwork_snr=PLW.pwloc_snr and PSW.rec_Stat=0 and PSW.visit_status=0 ";
	$qMedicos .= "INNER join City on City.city_snr = I.city_snr ";
	$qMedicos .= "LEFT OUTer join District as Dst on city.distr_snr = Dst.distr_snr ";
	$qMedicos .= "LEFT OUTer join State on Dst.state_snr = State.state_snr ";
	$qMedicos .= "left outer join Ims_brick as IMS on IMS.imsbrick_snr = City.imsbrick_snr ";
	$qMedicos .= "left outer join Smart_Fechas_Med VP on VP.pers_snr = P.pers_snr ";
	$qMedicos .= "inner join User_Territ as UT on psw.user_snr= ut.user_snr and i.inst_snr = ut.ter_snr and ut.rec_stat=0 "; /*UT.ter_snr = I.inst_snr*/
	$qMedicos .= "inner join Users as U on U.user_snr = UT.user_snr and U.rec_stat=0 ";
	$qMedicos .= "inner join compline as cl on U.cline_snr = cl.cline_snr ";
	$qMedicos .= "left outer join codelist type on I.type_snr = type.clist_snr ";
	$qMedicos .= "left outer join codelist ST on P.status_snr = ST.clist_snr ";
	$qMedicos .= "left outer join codelist SEXO on P.sex_snr = SEXO.clist_snr ";
	$qMedicos .= "left outer join codelist CATEG on P.opinion_snr = CATEG.clist_snr ";
	$qMedicos .= "LEFT OUTER JOIN CODELIST CATAW ON CATAW.CLIST_SNR=P.OPINION_SNR AND P.REC_STAT=0 ";
	$qMedicos .= "left outer join codelist PT on P.SCIENCE_SNR = PT.clist_snr AND PT.REC_STAT=0 AND PT.STATUS=1 ";
	$qMedicos .= "left outer join codelist ESP on P.spec_snr = ESP.clist_snr ";
	$qMedicos .= "left outer join codelist ESP2 on PLW.spec2_snr = ESP2.clist_snr AND ESP2.STATUS=1 AND ESP2.REC_STAT=0 ";
	$qMedicos .= "left outer join codelist HON on P.PERSTYPE_SNR = HON.clist_snr ";
	$qMedicos .= "left outer join codelist FV on P.titel_snr=FV.clist_snr and FV.rec_stat=0 and FV.status=1 ";
	$qMedicos .= "LEFT OUTER JOIN PERSON_CONTACT PC ON PC.PERS_SNR=P.PERS_SNR AND PC.REC_STAT=0 ";
	$qMedicos .= "left outer join codelist PUESTO on PC.FUNCTION_snr = PUESTO.clist_snr AND PC.PERS_SNR=P.PERS_SNR AND PC.REC_STAT=0 AND PUESTO.STATUS=1 ";
	 
	$qMedicos .= "LEFT OUTER JOIN PERSON_UD PUD ON PUD.PERS_SNR=P.PERS_SNR AND PUD.REC_STAT=0 ";
	$qMedicos .= "left outer join pers_profile PER on P.pers_snr = PER.pers_snr AND PER.REC_STAT=0 ";
	$qMedicos .= "left outer join pers_profile_ud PERFIL on PER.persprofile_snr = PERFIL.persprofile_snr AND PERFIL.REC_STAT=0 ";
	 
	$qMedicos .= "LEFT OUTER JOIN CODELIST SEGM_INIC ON PUD.SegmentacionInicial=SEGM_INIC.CLIST_SNR AND SEGM_INIC.REC_STAT=0 AND SEGM_INIC.STATUS=1 ";
	$qMedicos .= "LEFT OUTER JOIN CODELIST SEGM_ACT ON PUD.SegmentacionActual=SEGM_ACT.CLIST_SNR AND SEGM_ACT.REC_STAT=0 AND SEGM_ACT.STATUS=1 ";
	$qMedicos .= "left outer join codelist SegAteka on PUD.SegmentacionAteka = SegAteka.clist_snr and SegAteka.rec_stat=0 and SegAteka.status=1 ";
	$qMedicos .= "left outer join codelist SegFlonon on PUD.SegmentacionFlonorm = SegFlonon.clist_snr and SegFlonon.rec_stat=0 and SegFlonon.status=1 ";
	$qMedicos .= "left outer join codelist SegVessel on PUD.SegmentacionVessel = SegVessel.clist_snr and SegVessel.rec_stat=0 and SegVessel.status=1 ";
	$qMedicos .= "left outer join codelist SegZirfos on PUD.SegmentacionZirfos = SegZirfos.clist_snr and SegZirfos.rec_stat=0 and SegZirfos.status=1 ";
	 
	$qMedicos .= "left outer join codelist PERFIL7 on PUD.LiderOpinion = PERFIL7.clist_snr and PERFIL7.status=1 ";
	$qMedicos .= "left outer join codelist PERFIL8 on P.PERS_POSITION_SNR = PERFIL8.clist_snr and PERFIL8.status=1 ";
	$qMedicos .= "left outer join codelist PERFIL9 on P.PERS_DIFFIC_SNR = PERFIL9.clist_snr and PERFIL9.status=1 and PERFIL9.rec_stat=0 ";
	$qMedicos .= "left outer join codelist PERFIL10 on PERFIL.etapa_de_adopcion = PERFIL10.clist_snr and PERFIL10.status=1 ";
	$qMedicos .= "left outer join codelist PERFILD on PERFIL.Mx_Px_SII = PERFILD.clist_snr and PERFILD.status=1 ";
	$qMedicos .= "left outer join codelist PERFILE on PERFIL.Mx_Px_Inf_Gastrointestinales = PERFILE.clist_snr and PERFILE.status=1 ";
	$qMedicos .= "left outer join codelist PERFILF on PERFIL.Mx_Px_IVC = PERFILF.clist_snr and PERFILF.status=1 ";
	$qMedicos .= "left outer join codelist PERFILG on PERFIL.Mx_Px_ConstipacionEstrenimien = PERFILG.clist_snr and PERFILG.status=1 ";
	$qMedicos .= "left outer join codelist PERFILH on PERFIL.Mx_Px_Encefalopatia = PERFILH.clist_snr and PERFILH.status=1 ";
	
	$qMedicos .= "left outer join codelist IPartCod1 on IPartCod1.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod1.rec_stat=0 and IPartCod1.status=1 and IPartCod1.sort_num=0 ";
	$qMedicos .= "left outer join (select distinct pers_snr, image_snr from Persimage where REC_STAT=0) PerIm1 on PerIm1.image_snr= IPartCod1.clist_snr and PerIm1.pers_snr=P.pers_snr ";
	$qMedicos .= "left outer join codelist IPartCod2 on IPartCod2.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod2.rec_stat=0 and IPartCod2.status=1 and IPartCod2.sort_num=1 ";
	$qMedicos .= "left outer join (select distinct pers_snr, image_snr from Persimage where REC_STAT=0) PerIm2 on PerIm2.image_snr= IPartCod2.clist_snr and PerIm2.pers_snr=P.pers_snr ";
	$qMedicos .= "left outer join codelist IPartCod3 on IPartCod3.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod3.rec_stat=0 and IPartCod3.status=1 and IPartCod3.sort_num=2 ";
	$qMedicos .= "left outer join (select distinct pers_snr, image_snr from Persimage where REC_STAT=0) PerIm3 on PerIm3.image_snr= IPartCod3.clist_snr and PerIm3.pers_snr=P.pers_snr ";
	$qMedicos .= "left outer join codelist IPartCod4 on IPartCod4.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod4.rec_stat=0 and IPartCod4.status=1 and IPartCod4.sort_num=3 ";
	$qMedicos .= "left outer join (select distinct pers_snr, image_snr from Persimage where REC_STAT=0) PerIm4 on PerIm4.image_snr= IPartCod4.clist_snr and PerIm4.pers_snr=P.pers_snr ";
	$qMedicos .= "left outer join codelist IPartCod5 on IPartCod5.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod5.rec_stat=0 and IPartCod5.status=1 and IPartCod5.sort_num=4 ";
	$qMedicos .= "left outer join (select distinct pers_snr, image_snr from Persimage where REC_STAT=0) PerIm5 on PerIm5.image_snr= IPartCod5.clist_snr and PerIm5.pers_snr=P.pers_snr ";
	$qMedicos .= "left outer join codelist IPartCod6 on IPartCod6.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod6.rec_stat=0 and IPartCod6.status=1 and IPartCod6.sort_num=5 ";
	$qMedicos .= "left outer join (select distinct pers_snr, image_snr from Persimage where REC_STAT=0) PerIm6 on PerIm6.image_snr= IPartCod6.clist_snr and PerIm6.pers_snr=P.pers_snr ";
	$qMedicos .= "left outer join codelist IPartCod7 on IPartCod7.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod7.rec_stat=0 and IPartCod7.status=1 and IPartCod7.sort_num=6 ";
	$qMedicos .= "left outer join (select distinct pers_snr, image_snr from Persimage where REC_STAT=0) PerIm7 on PerIm7.image_snr= IPartCod7.clist_snr and PerIm7.pers_snr=P.pers_snr ";
	$qMedicos .= "left outer join codelist IPartCod8 on IPartCod8.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod8.rec_stat=0 and IPartCod8.status=1 and IPartCod8.sort_num=7 ";
	$qMedicos .= "left outer join (select distinct pers_snr, image_snr from Persimage where REC_STAT=0) PerIm8 on PerIm8.image_snr= IPartCod8.clist_snr and PerIm8.pers_snr=P.pers_snr ";
	$qMedicos .= "left outer join codelist IPartCod9 on IPartCod9.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod9.rec_stat=0 and IPartCod9.status=1 and IPartCod9.sort_num=8 ";
	$qMedicos .= "left outer join (select distinct pers_snr, image_snr from Persimage where REC_STAT=0) PerIm9 on PerIm9.image_snr= IPartCod9.clist_snr and PerIm9.pers_snr=P.pers_snr ";
	$qMedicos .= "left outer join codelist IPartCod10 on IPartCod10.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod10.rec_stat=0 and IPartCod10.status=1 and IPartCod10.sort_num=9 ";
	$qMedicos .= "left outer join (select distinct pers_snr, image_snr from Persimage where REC_STAT=0) PerIm10 on PerIm10.image_snr= IPartCod10.clist_snr and PerIm10.pers_snr=P.pers_snr ";
	$qMedicos .= "left outer join codelist IPartCod11 on IPartCod11.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod11.rec_stat=0 and IPartCod11.status=1 and IPartCod11.sort_num=10 ";
	$qMedicos .= "left outer join (select distinct pers_snr, image_snr from Persimage where REC_STAT=0) PerIm11 on PerIm11.image_snr= IPartCod11.clist_snr and PerIm11.pers_snr=P.pers_snr ";
	$qMedicos .= "left outer join codelist IPartCod12 on IPartCod12.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod12.rec_stat=0 and IPartCod12.status=1 and IPartCod12.sort_num=11 ";
	$qMedicos .= "left outer join (select distinct pers_snr, image_snr from Persimage where REC_STAT=0) PerIm12 on PerIm12.image_snr= IPartCod12.clist_snr and PerIm12.pers_snr=P.pers_snr ";
	$qMedicos .= "left outer join codelist IPartCod13 on IPartCod13.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod13.rec_stat=0 and IPartCod13.status=1 and IPartCod13.sort_num=12 ";
	$qMedicos .= "left outer join (select distinct pers_snr, image_snr from Persimage where REC_STAT=0) PerIm13 on PerIm13.image_snr= IPartCod13.clist_snr and PerIm13.pers_snr=P.pers_snr ";
	$qMedicos .= "left outer join codelist IPartCod14 on IPartCod14.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod14.rec_stat=0 and IPartCod14.status=1 and IPartCod14.sort_num=13 ";
	$qMedicos .= "left outer join (select distinct pers_snr, image_snr from Persimage where REC_STAT=0) PerIm14 on PerIm14.image_snr= IPartCod14.clist_snr and PerIm14.pers_snr=P.pers_snr ";
	$qMedicos .= "left outer join codelist IPartCod15 on IPartCod15.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod15.rec_stat=0 and IPartCod15.status=1 and IPartCod15.sort_num=14 ";
	$qMedicos .= "left outer join (select distinct pers_snr, image_snr from Persimage where REC_STAT=0) PerIm15 on PerIm15.image_snr= IPartCod15.clist_snr and PerIm15.pers_snr=P.pers_snr ";
	$qMedicos .= "left outer join codelist IPartCod16 on IPartCod16.Clib_snr='D237925C-1377-40F8-9B2F-FB61AE55AD15' and IPartCod16.rec_stat=0 and IPartCod16.status=1 and IPartCod16.sort_num=15 ";
	$qMedicos .= "left outer join (select distinct pers_snr, image_snr from Persimage where REC_STAT=0) PerIm16 on PerIm16.image_snr= IPartCod16.clist_snr and PerIm16.pers_snr=P.pers_snr ";
	
	$qMedicos .= "where ";
	$qMedicos .= "P.pers_snr <> '00000000-0000-0000-0000-000000000000' ";
	$qMedicos .= "and P.rec_stat=0 ";
	$qMedicos .= "and U.status=1 ";
	$qMedicos .= "and U.user_type=4 ";
	$qMedicos .= "and P.status_snr in ('".$estatus."') ";
	$qMedicos .= "and U.user_snr in ('".$ids."') ";
?>