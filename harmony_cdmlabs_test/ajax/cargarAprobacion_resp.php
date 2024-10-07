<?php
	include "../conexion.php";
	
	$id = $_POST['id'];
	$tipo = $_POST['tipo'];
	
	if($tipo == 'p'){
	
		$queryCampos = "select rc.name, rt.LONG_TEXT 
			from rep_columns rc, REP_TEXT rt
			where rc.RTAB_SNR = 14296
			and rc.RTAB_SNR = rt.TBL_NR
			and rt.TEXT_SNR = rc.COL_NR
			and rt.REPLANG_SNR = 8
			order by CAST(SHORT_TEXT AS INT) ASC ";
		
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
			
		//echo $queryPersApp."<br><br>";
		
		$regPersApproval = sqlsrv_fetch_array(sqlsrv_query($conn, $queryPersApp));
		
		$arrApprovalStatus = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from person_approval where pers_approval_snr = '".$id."'" ));
		
		$approvalStatus = $arrApprovalStatus['P_APPROVAL_STATUS'];
		$pwork = $arrApprovalStatus['PLW_PWLOC_SNR'];
		$pers_snr = $arrApprovalStatus['P_PERS_SNR'];
		
		if($approvalStatus == 'C'){
			$queryPers = "select 
				FNAME as P_FNAME,
				LNAME AS P_LNAME,
				sexo.name as P_SEX_SNR,
				p.INFO as P_INFO,
				frec.name as P_TITEL_SNR,
				esp.name as P_SPEC_SNR,
				p.BIRTHDATE as P_BIRTHDATE,
				tipo.name as P_PERSTYPE_SNR,
				categ.name as P_OPINION_SNR,
				p.TEL1 as P_TEL1,
				p.EMAIL as P_EMAIL,
				p.NAME_FATHER as P_NAME_FATHER,
				p.COMPETITION_REL as P_COMPETITION_REL,
				plw.TEL as PLW_TEL,
				plw.FAX as PLW_FAX,
				plw.EMAIL as PLW_EMAIL,
				plw.GSM as PLW_GSM,
				p.LONGTERMGOALS as P_LONGTERMGOALS,
				p.LICENCENR as P_LICENCENR,
				subesp.name as P_KOL_SNR,
				plw.TORRE as P_TORRE,
				plw.PISO as P_PISO,
				plw.CONSULTORIO as P_CONSULTORIO
				from  person p
				inner join PERSLOCWORK plw on p.pers_snr = plw.pers_snr
				inner join CODELIST sexo on sexo.CLIST_SNR = p.SEX_SNR
				inner join CODELIST frec on frec.CLIST_SNR = p.TITEL_SNR
				inner join CODELIST esp on esp.CLIST_SNR = p.SPEC_SNR
				inner join CODELIST tipo on tipo.CLIST_SNR = p.PERSTYPE_SNR
				inner join CODELIST categ on categ.CLIST_SNR = p.OPINION_SNR
				inner join CODELIST subesp on subesp.CLIST_SNR = p.KOL_SNR
				where p.pers_snr = '".$pers_snr."' ";
				
				$rsExistePLW = sqlsrv_query($conn, "select * from PERSLOCWORK where pwloc_snr = '".$pwork."' and rec_stat = 0", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
				if(sqlsrv_num_rows($rsExistePLW) > 0){
					$queryPers .= "and plw.rec_stat = 0 
					and plw.pwloc_snr = '".$pwork."'";
				}
				
				
			//echo $queryPers;
		
			$regPers = sqlsrv_fetch_array(sqlsrv_query($conn, $queryPers));
			
			echo "<script>
				$('#tblAprobacion').empty();
				$('#tblAprobacion').append('<tr><td><b>Nombre del Campo</b></td><td><b>Campos Anteriores</b></td><td><b>Campos Nuevos</b></td></tr>');
				";
			for($i=0;$i<count($arrayNombres);$i++){
				if(is_object($regPersApproval[$arrayNombres[$i]])){
					foreach ($regPersApproval[$arrayNombres[$i]] as $key => $val) {
						if(strtolower($key) == 'date'){
							$fechaApp = substr($val, 0, 10);
						}
					}
					if(is_object($regPers[$arrayNombres[$i]])){
						foreach($regPers[$arrayNombres[$i]] as $key => $val){
							if(strtolower($key) == 'date'){
								$fechaPers = substr($val, 0, 10);
							}
						}
					}else{
						$fechaPers = "";
					}
					if($fechaApp != $fechaPers){
						echo "$('#tblAprobacion').append('<tr><td><b>".$arrayValores[$i]."</b></td><td><font color=\"#0000FF\">".$fechaPers."</font></td><td>".$fechaApp."</td></tr>');";
					}else{
						echo "$('#tblAprobacion').append('<tr><td><b>".$arrayValores[$i]."</b></td><td>".$fechaPers."</td><td>".$fechaApp."</td></tr>');";
					}
				}else{
					if($regPersApproval[$arrayNombres[$i]] != $regPers[$arrayNombres[$i]]){
						echo "$('#tblAprobacion').append('<tr><td><b>".$arrayValores[$i]."</b></td><td>".utf8_encode($regPers[$arrayNombres[$i]])."</td><td><font color=\"#0000FF\">".utf8_encode($regPersApproval[$arrayNombres[$i]])."</font></td></tr>');";
					}else{
						echo "$('#tblAprobacion').append('<tr><td><b>".$arrayValores[$i]."</b></td><td>".utf8_encode($regPers[$arrayNombres[$i]])."</td><td>".utf8_encode($regPersApproval[$arrayNombres[$i]])."</td></tr>');";
					}
				}
			}
			echo "</script>";
		}else if($approvalStatus == 'N'){
		
			echo "<script>
				$('#tblAprobacion').empty();";
			for($i=0;$i<count($arrayNombres);$i++){
				if(is_object($regPersApproval[$arrayNombres[$i]])){
					foreach ($regPersApproval[$arrayNombres[$i]] as $key => $val) {
						if(strtolower($key) == 'date'){
							echo "$('#tblAprobacion').append('<tr><td width=\"50%\"><b>".$arrayValores[$i]."</b></td><td>".substr($val, 0, 10)."</td></tr>');";
						}
					}
				}else{
					echo "$('#tblAprobacion').append('<tr><td width=\"50%\"><b>".$arrayValores[$i]."</b></td><td>".utf8_encode($regPersApproval[$arrayNombres[$i]])."</td></tr>');";
				}
			}
			echo "</script>";
		}else if($approvalStatus == 'D'){
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
				p.P_CONSULTORIO, 
				p.PLW_DEL_REASON as comentariosBaja, 
				motivo.NAME as motivo
				from  person_approval p
				inner join CODELIST sexo on sexo.CLIST_SNR = p.P_SEX_SNR
				inner join CODELIST frec on frec.CLIST_SNR = p.P_TITEL_SNR
				inner join CODELIST esp on esp.CLIST_SNR = p.P_SPEC_SNR
				inner join CODELIST tipo on tipo.CLIST_SNR = p.P_PERSTYPE_SNR
				inner join CODELIST categ on categ.CLIST_SNR = p.P_OPINION_SNR
				inner join CODELIST subesp on subesp.CLIST_SNR = p.P_KOL_SNR
				left join CODELIST motivo on motivo.CLIST_SNR = p.PLW_DEL_STATUS_SNR
				where pers_approval_snr = '".$id."'";
			
			$regPersApproval = sqlsrv_fetch_array(sqlsrv_query($conn, $queryPersApp));
			
			echo "<script>
				$('#tblAprobacion').empty();";
			
			for($i=0;$i<10;$i++){
				if(is_object($regPersApproval[$arrayNombres[$i]])){
					foreach ($regPersApproval[$arrayNombres[$i]] as $key => $val) {
						if(strtolower($key) == 'date'){
							echo "$('#tblAprobacion').append('<tr><td width=\"50%\"><b>".$arrayValores[$i]."</b></td><td>".substr($val, 0, 10)."</td></tr>');";
						}
					}
				}else{
					echo "$('#tblAprobacion').append('<tr><td width=\"50%\"><b>".$arrayValores[$i]."</b></td><td>".$regPersApproval[$arrayNombres[$i]]."</td></tr>');";
				}
			}
			echo "$('#tblAprobacion').append('<tr><td width=\"50%\"><b>Motivo</b></td><td>".utf8_encode($regPersApproval['motivo'])."</td></tr>');";
			echo "$('#tblAprobacion').append('<tr><td width=\"50%\"><b>Comentarios</b></td><td>".utf8_encode($regPersApproval['comentariosBaja'])."</td></tr>');";
			echo "</script>";
		}
	}else if($tipo == 'i'){
		$queryCampos = "select rc.name, rt.LONG_TEXT 
			from rep_columns rc, REP_TEXT rt
			where rc.RTAB_SNR = 14332
			and rc.RTAB_SNR = rt.TBL_NR
			and rt.TEXT_SNR = rc.COL_NR
			and rt.REPLANG_SNR = 8
			order by CAST(SHORT_TEXT AS INT) ASC ";
		
		$rsCampos = sqlsrv_query($conn, $queryCampos);
		
		$arrayNombres = array();
		$arrayValores = array();
		
		while($reg = sqlsrv_fetch_array($rsCampos)){
			$arrayNombres[] = strtoupper($reg['name']);
			$arrayValores[] = utf8_encode($reg['LONG_TEXT']);
		}
		
		/*print_r($arrayNombres);
		echo "<br><br>";
		print_r($arrayValores);
		echo "<br><br>";*/
		
		$queryInstApp = "select
			i.I_EMAIL,
			i.I_HTTP,
			i.I_INFO,
			tipo.name as I_INST_TYPE,
			categ.name as I_KLASS_SNR,
			i.I_NAME,
			status.name as I_STATUS_SNR,
			i.I_STREET1,
			i.I_STREET2,
			i.I_TEL1,
			i.I_TEL2
			from inst_approval i
			inner join INST_TYPE tipo on tipo.INST_TYPE = i.i_inst_type 
			inner join CODELIST categ on categ.CLIST_SNR = i.i_klass_snr
			inner join CODELIST status on status.CLIST_SNR = i.i_status_snr
			where i.inst_approval_snr = '".$id."'";
			
			
			
		//echo $queryInstApp."<br><br>";
		
		$regInstApproval = sqlsrv_fetch_array(sqlsrv_query($conn, $queryInstApp));
		
		//print_r($regInstApproval);
		
		//echo "<br><br>";
		
		$arrApprovalStatus = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from inst_approval where inst_approval_snr = '".$id."'" ));
		
		$approvalStatus = $arrApprovalStatus['I_APPROVAL_STATUS'];
		$inst_snr = $arrApprovalStatus['I_INST_SNR'];
		
		//echo "approvalStatus: ".$approvalStatus."<br><br>";
		
		if($approvalStatus == 'C'){
			
			$queryInst = "select email as I_EMAIL,
				http as I_HTTP,
				info as I_INFO,
				tipo.name as I_INST_TYPE,
				categ.name as I_KLASS_SNR,
				i.name as I_NAME,
				status.name as I_STATUS_SNR,
				street1 as I_STREET1,
				street2 as I_STREET2,
				tel1 as I_TEL1,
				tel2 as I_TEL2
				from inst i
				inner join INST_TYPE tipo on tipo.INST_TYPE = i.inst_type 
				inner join CODELIST categ on categ.CLIST_SNR = i.klass_snr 
				inner join CODELIST status on status.CLIST_SNR = i.status_snr 
				where inst_snr = '$inst_snr'";
				
				
			//echo $queryInst;
		
			$regInst = sqlsrv_fetch_array(sqlsrv_query($conn, $queryInst));
			
			//print_r($regInst);
			
			echo "<script>
			$('#tblAprobacion').empty();";
			for($i=0;$i<count($arrayNombres);$i++){
				if($regInstApproval[$arrayNombres[$i]] != $regInst[$arrayNombres[$i]]){
					echo "$('#tblAprobacion').append('<tr><td><b>".$arrayValores[$i]."</b></td><td><font color=\"#0000FF\">".$regInstApproval[$arrayNombres[$i]]."</font></td><td>".$regInst[$arrayNombres[$i]]."</td></tr>');";
				}else{
					echo "$('#tblAprobacion').append('<tr><td><b>".$arrayValores[$i]."</b></td><td>".$regInstApproval[$arrayNombres[$i]]."</td><td>".$regInst[$arrayNombres[$i]]."</td></tr>');";
				}
			}
			echo "</script>";
		}else{
			echo "<script>
				$('#tblAprobacion').empty();";
			for($i=0;$i<count($arrayNombres);$i++){
				echo "$('#tblAprobacion').append('<tr><td width=\"50%\"><b>".$arrayValores[$i]."</b></td><td>".$regInstApproval[$arrayNombres[$i]]."</td></tr>');";
			}
			echo "</script>";
		}
	}
?>