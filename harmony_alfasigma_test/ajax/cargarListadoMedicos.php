<?php
	include "../conexion.php";
	$tam = array(100,350,350,350,150,150,200,200,350,550, 100,300,350,100,300,250,250,150,150,150, 150,150,100,100,100,100,100,100,100,100, 250,150,250,400,210,250,150,100,100,100, 750,100,100,100,100,100,100,100); //,100,100,50,100,100,100,100,100,100,50,100,100,100,50,100,150,100,100,100,100,100,150,100,100,100,150,100,100,100,50,100,50,100,50,100,50,100,50,100,50,100,100,100,100);
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$registrosPorPagina = 20;
		//$tipoUsuario = $_POST['tipoUsuario'];
		$numPagina = $_POST['pagina'];
		//$hoy = $_POST['hoy'];
		$ids = str_replace(",","','",$_POST['ids']);
		$ids = str_replace("'',''","','",$ids);
		//echo "ids: ".$ids."<br>";
		$estatus = $_POST['estatus'];
		$registroIni = $numPagina * $registrosPorPagina - $registrosPorPagina;
		//echo $numPagina."-".$registrosPorPagina."-".$registrosPorPagina;
		
		$qMedicos = "Select 
		cl.name as Linea, 
		upper(U.lname)+' '+upper(U.fname) as Representante, 
		'{'+CAST(P.pers_snr AS VARCHAR(36))+'}' as 'Código Médico', 
		'{'+CAST(I.inst_snr AS VARCHAR(36))+'}' as 'Código Inst', 
		upper(IT.NAME) AS 'Tipo Inst', 
		upper(type.name) as 'Tipo Pers', 
		upper(P.lname) as Paterno, 
		upper(P.MOTHERS_LNAME) as Materno, 
		upper(P.fname) as Nombre, 
		upper(I.street1) as Calle, 
		case when I.num_ext='0' then '' else I.num_ext end as 'Num Ext', 
		plw.num_int as 'Num Int', 
		City.name as Colonia, 
		City.zip as 'C.P.', 
		IMS.name as Brick, 
		Dst.name as Población, 
		State.name as Estado, 
		I.latitude, 
		I.longitude, 
		HON.name as Honorarios, 
		ESP.name as Especialidad, 
		ESP2.name as 'Sub Especialidad', 
		ST.name as Status, 
		cast(p.BIRTHDATE as DATE) as 'Fecha Nac', 
		SEXO.name as Sexo, 
		FV.name as 'Frec vis', 
		CATAW.NAME AS CATEG_AW, 
		PT.name as 'Pacs por Sem', 
		P.prof_id as Cedula, 
		case when year(P.BIRTHDATE) > 0 then year(getdate())-year(P.BIRTHDATE) else '' end  as Edad, 
		P.tel1 as Tel1, 
		I.tel2 as Tel2, 
		P.mobile as Celular,  
		PLW.email, 
		'' as 'Div. Med. Int' , 
		PERFIL7.name as 'Lider de opinion', 
		'' as 'notas / Otras Inversiones', 
			
		isnull((select TOP 1 kt.T_DATE from kupdatelog ku, kommtran kt where
		ku.table_nr = 19
		and ku.OPERATION=1
		and ku.kTrAN_SNR = KT.KTRAN_SNR
		AND KU.REC_STAT=0
		AND KU.REC_KEY=P.PERS_SNR
		), ISNULL(VP.create_date,
		CASE WHEN ((SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR ORDER BY VISIT_DATE) IS NOT NULL
		AND CAST(VP.change_date AS DATE) IS NOT NULL)
		THEN
		CASE
		WHEN (SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR ORDER BY VISIT_DATE)
		>CAST(VP.change_date AS DATE)
		THEN VP.change_date
		WHEN (SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR ORDER BY VISIT_DATE)
		<=
		CAST(VP.change_date AS DATE)
		THEN (SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR ORDER BY VISIT_DATE)
		ELSE
		CASE WHEN P.STATUS_SNR='37DFDA3B-29AB-47D3-87A9-62F0CC2D9B72' THEN CAST('2015-11-12' AS DATETIME)
		ELSE CAST('2015-11-12' AS DATETIME) END
		END
		WHEN ((SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR ORDER BY VISIT_DATE) IS NOT NULL
		AND CAST(VP.change_date AS DATE) IS NULL)
		THEN
		(SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR ORDER BY VISIT_DATE)
		WHEN ((SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR ORDER BY VISIT_DATE) IS NULL
		AND CAST(VP.change_date AS DATE) IS NOT NULL)
		THEN
		CAST(VP.change_date AS DATE)
		WHEN ((SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR ORDER BY VISIT_DATE) IS NULL
		AND CAST(VP.change_date AS DATE) IS NULL)
		THEN
		CAST('2015-11-12' AS DATETIME)
		END) )
		as Fecha_Alta, 

		isnull((select top 1 kt.T_DATE from kupdatelog ku, kommtran kt where
		ku.table_nr = 19
		and ku.OPERATION=2
		and ku.kTrAN_SNR = KT.KTRAN_SNR
		AND KU.REC_STAT=0 
		AND KU.REC_KEY=P.PERS_SNR
		order by KT.T_DATE DESC
		),VP.change_date) as Fecha_Mod, 
			
		CASE WHEN CAST(VP.fecha_ruta AS DATE)<>'2017-01-01' THEN
		cast(ISNULL((SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR AND USER_SNR=U.USER_SNR
		ORDER BY VISIT_DATE),cast(vp.fecha_ruta as datetime)) as datetime)
		ELSE
		cast(ISNULL((SELECT TOP 1 CAST(VISIT_DATE AS DATE) FROM VISITPERS WHERE REC_STAT=0 AND PERS_SNR=P.PERS_SNR AND USER_SNR=U.USER_SNR
		ORDER BY VISIT_DATE),cast('2017-01-01' as datetime)) as datetime)
		END
		as Fecha_Ruta, 
			
		'' as Suma_Aseguradoras, 
		P.Nombre_CU, 
		SegAteka.name as Seg_Ateka, 
		SegFlonon.name as Segment_Flonorm, 
		SegVessel.name as Segment_Vessel, 
		SegZirfos.name as Segment_Zirfos 
			
		from person P
		inner join perslocwork PLW on P.pers_snr = PLW.pers_snr and PLW.rec_stat=0
		inner join inst I on I.inst_snr = PLW.INST_SNR and I.rec_stat=0
		left outer join inst_Type IT on IT.inst_type=I.inst_type and IT.rec_Stat=0
		inner join pers_srep_work PSW on PSW.pwork_snr=PLW.pwork_SNR and PSW.rec_Stat=0
		INNER join City on City.city_snr = I.city_snr
		LEFT OUTer join District as Dst on city.distr_snr = Dst.distr_snr
		LEFT OUTer join State on Dst.state_snr = State.state_snr
		left outer join Brick as IMS on IMS.brick_snr = City.brick_snr
		left outer join Smart_Fechas_Med VP on VP.pers_snr = P.pers_snr
		inner join User_Territ as UT on psw.user_snr= ut.user_snr and i.inst_snr = ut.inst_snr and ut.rec_stat=0 
		inner join Users as U on U.user_snr = UT.user_snr and U.rec_stat=0
		inner join compline as cl on U.cline_snr = cl.cline_snr
		left outer join codelist type on P.PERSTYPE_SNR = type.clist_snr and type.rec_stat=0
		left outer join codelist ST on P.status_snr = ST.clist_snr
		left outer join codelist SEXO on P.sex_snr = SEXO.clist_snr
		LEFT OUTER JOIN CODELIST CATAW ON CATAW.CLIST_SNR=P.category_snr AND P.REC_STAT=0
		left outer join codelist PT on P.patperweek_snr = PT.clist_snr AND PT.REC_STAT=0 AND PT.STATUS=1
		left outer join codelist ESP on P.spec_snr = ESP.clist_snr
		left outer join codelist ESP2 on P.subSpec_snr = ESP2.clist_snr AND ESP2.STATUS=1 AND ESP2.REC_STAT=0
		left outer join codelist HON on P.FEE_TYPE_SNR = HON.clist_snr and HON.STATUS=1 and HON.REC_STAT=0
		left outer join codelist FV on P.frecvis_snr=FV.clist_snr and FV.rec_stat=0 and FV.status=1
		LEFT OUTER JOIN PERSON_UD PUD ON PUD.PERS_SNR=P.PERS_SNR AND PUD.REC_STAT=0
			 
		left outer join codelist SegAteka on PUD.field_07_snr = SegAteka.clist_snr and SegAteka.rec_stat=0 and SegAteka.status=1
		left outer join codelist SegFlonon on PUD.field_04_snr = SegFlonon.clist_snr and SegFlonon.rec_stat=0 and SegFlonon.status=1
		left outer join codelist SegVessel on PUD.field_05_snr = SegVessel.clist_snr and SegVessel.rec_stat=0 and SegVessel.status=1
		left outer join codelist SegZirfos on PUD.field_06_snr = SegZirfos.clist_snr and SegZirfos.rec_stat=0 and SegZirfos.status=1
			 
		left outer join codelist PERFIL7 on PUD.field_02_snr = PERFIL7.clist_snr and PERFIL7.status=1

		where
		P.pers_snr <> '00000000-0000-0000-0000-000000000000'
		and P.rec_stat=0
		and U.status=1
		and U.user_type=4 
		and U.user_snr in ('".$ids."') ";

		if($estatus != ''){
			$qMedicos .= "and P.status_snr in ('".$estatus."') ";
		}
		
		$qMedicos .= "order by U.lname,U.fname,P.lname,P.mothers_lname,P.fname ";
	
		$registroIni = $numPagina * $registrosPorPagina - $registrosPorPagina;
		
		$tope = "OFFSET ".$registroIni." ROWS 
			FETCH NEXT ".$registrosPorPagina." ROWS ONLY ";
				
		$rsMedicosTotal = sqlsrv_query($conn, utf8_decode($qMedicos), array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		
		$totalRegistros = sqlsrv_num_rows($rsMedicosTotal);

		$rsMedicos = sqlsrv_query($conn, utf8_decode($qMedicos.$tope));
		
		$paginas = ceil($totalRegistros / $registrosPorPagina);
		
		//echo $qMedicos.$tope;
		
		echo "<script>
			$('#tblListadoMedicos tbody').empty();
			$('#tblListadoMedicos tfoot').empty();
			$('#tblPaginasListadoMedicos').empty();";
		//$row = '';
		while($regMedico = sqlsrv_fetch_array($rsMedicos)){
			$row = '<tr>';
			$aseguradoras = array();
			for($j=0;$j<sqlsrv_num_fields($rsMedicos);$j++){
				if(is_object($regMedico[$j])){
					foreach ($regMedico[$j] as $key => $val) {
						if(strtolower($key) == 'date'){
							$regMedico[$j] = substr($val, 0, 10);
						}
					}
				}
				if($j == 2){//pers_snr
					$qAdeguradoras = "select c.NAME from PERSON_BANK p, CODELIST c
						where p.bank_snr = c.CLIST_SNR
						and p.REC_STAT = 0
						and p.PERS_SNR = '".$regMedico[$j]."'
						order by c.SORT_NUM";
						//echo $qAdeguradoras."<br><br>";
					$rsAseguradoras = sqlsrv_query($conn, $qAdeguradoras);
					$arrAseguradoras = array();
					while($aseguradora = sqlsrv_fetch_array($rsAseguradoras)){
						$arrAseguradoras[] = $aseguradora['NAME'];
					}
				}
				
				if($j == 40){
					$row .= '<td style="min-width:'.$tam[$j].'px;">'.implode(",",$arrAseguradoras).'</td>';
				}else{
					$row .= '<td style="min-width:'.$tam[$j].'px;">'.$regMedico[$j].'</td>';
				}
			}
			$row .= '</tr>';
			echo "$('#tblListadoMedicos tbody').append('".$row."');
			";	
		}
	}
	//$pie = '';
	//echo "hola: ".$totalRegistros." > ".$registrosPorPagina."<br>";
	if($totalRegistros > $registrosPorPagina){
		$pie = "<tr><td align='center' width='1400px'>";
		$idsEnviar = str_replace("'","",$ids);
		if($numPagina > 1){
			$anterior = $numPagina - 1;
			$pie .= "<a href='#' onClick='nuevaPaginaListados(1,\"".$idsEnviar."\",\"listadoMedicos\",\"".$estatus."\");'>inicio</a>&nbsp;&nbsp;";
			$pie .= "<a href='#' onClick='nuevaPaginaListados(".$anterior.",\"".$idsEnviar."\",\"listadoMedicos\",\"".$estatus."\");'>anterior</a>&nbsp;&nbsp;";
		}
		$antes = $numPagina-5;
		$despues = $numPagina+5;
		for($i=1;$i<=$paginas;$i++){
			if($i == $numPagina){
				$pie .= $i."&nbsp;&nbsp;";
			}else{
				if($i > $despues || $i < $antes){
					//$tabla .= "<a href='#' onClick='nuevaPagina(".$i.",\"".$hoy."\",\"".$idsEnviar."\",\"".$visitados."\");'>".$i."</a>&nbsp;&nbsp;";
				}
			}
		}
		if($numPagina < $paginas){
			$siguiente = $numPagina + 1;
			$pie .= "<a href='#' onClick='nuevaPaginaListados(".$siguiente.",\"".$idsEnviar."\",\"listadoMedicos\",\"".$estatus."\");'>Siguiente</a>&nbsp;&nbsp;";
			$pie .= "<a href='#' onClick='nuevaPaginaListados(".$paginas.",\"".$idsEnviar."\",\"listadoMedicos\",\"".$estatus."\");'>Fin</a>&nbsp;&nbsp;";
		}
		$pie .= "Pag. ".$numPagina." de ".$paginas."&nbsp;&nbsp;&nbsp; Registros : ".$totalRegistros;
		$pie .= "</td>";
		$pie .= "</tr>";
	}else{
		$pie .= "<tr><td colspan='16' align='center'>";
		$pie .= "Registros : ".$totalRegistros;
		$pie .= "</td></tr>";
	}
	echo "$('#tblListadoMedicos tfoot').append('".str_replace("'","\'",$pie)."');
	$('#divCargando').hide();
	";
	echo "</script>";
?>