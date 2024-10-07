<?php
	
	$queryCampos = "select rc.name, rt.LONG_TEXT 
		from rep_columns rc, REP_TEXT rt
		where rc.RTAB_SNR = 14296
		and rc.RTAB_SNR = rt.TBL_NR
		and rt.TEXT_SNR = rc.COL_NR
		order by COL_NR ";
	
	//echo $queryCampos;
	
	$rsCampos = sqlsrv_query($conn, $queryCampos);
	
	$arrayNombres = array();
	$arrayValores = array();
	
	while($reg = sqlsrv_fetch_array($rsCampos)){
		$arrayNombres[] = strtoupper($reg['name']);
		$arrayValores[] = utf8_encode($reg['LONG_TEXT']);
	}
	
	$queryPersApp = "select 
		p.P_FNAME,
		p.P_LNAME,
		sexo.name as P_SEX_SNR,
		p.P_INFO,
		frec.name as P_TITEL_SNR,
		esp.name as P_SPEC_SNR,
		p.P_BIRTHDATE,
		tipo.name as P_PERSTYPE_SNR,
		categ.name as P_OPINION_SNR,
		p.P_TEL1,
		p.P_EMAIL,
		p.P_NAME_FATHER,
		p.P_COMPETITION_REL,
		p.PLW_TEL,
		p.PLW_FAX,
		p.PLW_EMAIL,
		p.PLW_GSM,
		p.P_LONGTERMGOALS,
		p.P_LICENCENR,
		subesp.name as P_KOL_SNR,
		p.P_TORRE,
		p.P_PISO,
		p.P_CONSULTORIO
		from  person_approval p
		inner join CODELIST sexo on sexo.CLIST_SNR = p.P_SEX_SNR
		inner join CODELIST frec on frec.CLIST_SNR = p.P_TITEL_SNR
		inner join CODELIST esp on esp.CLIST_SNR = p.P_SPEC_SNR
		inner join CODELIST tipo on tipo.CLIST_SNR = p.P_PERSTYPE_SNR
		inner join CODELIST categ on categ.CLIST_SNR = p.P_OPINION_SNR
		inner join CODELIST subesp on subesp.CLIST_SNR = p.P_KOL_SNR
		where pers_approval_snr = '".$id."'";
		
	echo $queryPersApp;
	
	$regPersApproval = sqlsrv_fetch_array(sqlsrv_query($conn, $queryPersApp));
?>
<div style="height:400px;background-color:#FFFFFF;overflow:auto;">
	<table width="50%" border="1" id="tblAprobacion">
<?php
	for($i=0;$i<count($arrayNombres);$i++){
		if(is_object($regPersApproval[$arrayNombres[$i]])){
			foreach ($regPersApproval[$arrayNombres[$i]] as $key => $val) {
				if(strtolower($key) == 'date'){
					echo "<tr><td><b>".$arrayValores[$i]."</b></td><td>".substr($val, 0, 10)."</td></tr>";
				}
			}
		}else{
			echo "<tr><td><b>".$arrayValores[$i]."</b></td><td>".$regPersApproval[$arrayNombres[$i]]."</td></tr>";
		}
	}
?>
	</table>
</div>