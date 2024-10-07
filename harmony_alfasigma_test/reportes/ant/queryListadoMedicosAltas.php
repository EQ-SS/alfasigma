<?php

	$qMedicos = "Select
		cl.name as Linea,
		upper(U.lname)+' '+upper(U.fname) as Representante,
		I.inst_snr as Cod_Inst,
		P.pers_snr as Cod_Med,
		upper(type.name) as Tipo_Cons,
		upper(P.lname) as Paterno,
		upper(P.name_father) as Materno,
		upper(P.fname) as Nombre,
		upper(P.lname)+' '+upper(P.name_father)+' '+upper(P.fname) as Medico,
		upper(I.street1) as Direccion,
		ST.name as Status,
		IMS.name as Brick,
		City.name as Colonia,
		City.zip as Cod_Postal,
		Dst.name as Ciudad,
		State.name as Estado,
		SEXO.name as Sexo,
		CATEG.name as Categ,
		ESP.name as Esp,
		ESP2.name as Sub_Esp,
		HON.name as Hon,
		PT.name as Pac,
		DIFVIS.name as Difvis,
		ESTILO.name as Estilo,
		P.birth_year as Ano_Nac,
		FV.name as Frec_vis,
		P.gsm as Celular,
		I.tel1 as Tel1,
		I.tel2 as Tel2,
		p.creation_timestamp as Fecha_Alta
 
		from person P
		inner join perslocwork PLW on P.pers_snr = PLW.pers_snr
		inner join inst I on I.inst_snr = PLW.loc_snr
		left outer join City on  City.city_snr = I.city_snr
		inner join  District as Dst on city.distr_snr = Dst.distr_snr
		inner join State on Dst.state_snr = State.state_snr
		left outer join Ims_brick as IMS on IMS.imsbrick_snr = City.imsbrick_snr
		inner join User_Territ as UT on UT.ter_snr = I.inst_snr
		inner join Users as U on U.user_snr = UT.user_snr
		inner join compline as cl on U.cline_snr = cl.cline_snr
		left outer join codelist type on I.type_snr = type.clist_snr
		left outer join codelist ST on P.status_snr = ST.clist_snr
		left outer join codelist SEXO on P.sex_snr = SEXO.clist_snr
		 
		left outer join codelist CATEG on P.opinion_snr= CATEG.clist_snr AND CATEG.REC_STAT=0
		left outer join codelist ESP on P.spec_snr = ESP.clist_snr
		left outer join codelist ESP2 on P.spec2_snr = ESP2.clist_snr
		left outer join codelist HON on P.fee_type_snr = HON.clist_snr
		left outer join codelist PT on P.patperweek_snr = PT.clist_snr
		left outer join codelist DIFVIS on P.pers_diffic_snr = DIFVIS.clist_snr
		left outer join codelist ESTILO on P.pers_style_snr = ESTILO.clist_snr
		left outer join codelist FV on P.subspec_snr = FV.clist_snr
		left outer join codelist PUESTO on P.pers_position_snr = PUESTO.clist_snr
 
		where
		P.pers_snr <> '00000000-0000-0000-0000-000000000000'
		and P.rec_stat=0
		and PLW.rec_stat=0
		and UT.rec_stat=0
		and U.rec_stat=0
		and U.status=1
		and U.user_type=4
		and P.status_snr in ('".$estatus."')
		and U.user_snr in ('".$ids."')
		and p.creation_timestamp between '".$fechaI."' and '".$fechaF."'
		 
		order by Cl.name,U.lname,U.fname,P.lname,P.name_father,P.fname ";

?>