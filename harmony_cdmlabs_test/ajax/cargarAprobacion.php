<?php
	include "../conexion.php";
	
	$id = $_POST['id'];
	$tipo = $_POST['tipo'];
	//echo "tipo: ".$tipo."<br>";
	
	if($tipo == 'p'){
		$queryCampos = "SELECT CFT.SORT_NUM AS ORDEN, 
		CASE WHEN CF.NAME = 'NAME' THEN CFT.LONG_TEXT ELSE CF.NAME END AS NAME, 
		CFT.LONG_TEXT,
		CFT.SHORT_TEXT
		FROM CONFIG_FIELD_TRANSLATION CFT
		INNER JOIN CONFIG_TABLE CT ON CFT.TABLE_NR = CT.TABLE_NR
		INNER JOIN CONFIG_FIELD CF ON CT.TABLE_NR = CF.TABLE_NR AND CFT.COLUMN_NR = CF.COLUMN_NR
		WHERE CFT.TABLE_NR IN (456, 13, 8, 7, 481)
		AND CFT.LANG_NR = 8
		AND CFT.SORT_NUM > 0 
		UNION
		SELECT CFT.SORT_NUM AS ORDEN, 
		CASE WHEN CF.NAME = 'NAME' THEN CFT.LONG_TEXT ELSE CF.NAME END AS NAME, 
		CFT.LONG_TEXT, 
		CASE WHEN CF.NAME = 'I_STREET1' THEN 18 
		WHEN CF.NAME = 'I_NUM_EXT' THEN 19 
		WHEN CF.NAME = 'I_NAME' THEN 17	
		ELSE SHORT_TEXT END AS SHORT_TEXT 
		FROM CONFIG_FIELD_TRANSLATION CFT
		INNER JOIN CONFIG_TABLE CT ON CT.TABLE_NR=CFT.TABLE_NR
		INNER JOIN CONFIG_FIELD CF ON CT.TABLE_NR = CF.TABLE_NR AND CFT.COLUMN_NR = CF.COLUMN_NR
		WHERE CFT.TABLE_NR IN (492)
		AND CFT.LANG_NR = 8
		AND CFT.SORT_NUM > 0
		AND CF.NAME IN ('I_STREET1','I_NUM_EXT', 'I_NAME') 
		ORDER BY ORDEN";
			
			//echo $queryCampos."<br><br>";
		
		$rsCampos = sqlsrv_query($conn, $queryCampos);
		
		$arrayNombres = array();
		$arrayValores = array();
		
		while($reg = sqlsrv_fetch_array($rsCampos)){
			$arrayNombres[] = strtoupper($reg['NAME']);
			$arrayValores[] = utf8_encode($reg['LONG_TEXT']);
		}
		//print_r($arrayNombres);
		
		$queryPersApp = "select 
			tipo.name as P_PERSTYPE_SNR, 
			p.P_MOVEMENT_TYPE,
			p.P_FNAME,
			p.P_LNAME,
			p.P_MOTHERS_LNAME,
			sexo.name as P_SEX_SNR,
			esp.name as P_SPEC_SNR,
			subesp.name as P_SUBSPEC_SNR,
			pacporsem.name as P_PATPERWEEK_SNR,
			honorarios.name as P_FEE_TYPE_SNR,
			p.P_BIRTHDATE,
			categ.name as P_CATEGORY_SNR,
			p.P_PROF_ID AS P_PROF_ID,
			freq.name as P_FRECVIS_SNR,
			dif.name as P_PERS_DIFFIC_SNR,	
			P_EMAIL1,
			P_TEL1,
			/*case when i.name is null then ia.I_NAME else i.NAME end as PLW_INST_SNR, */
			case when i.name is null then ia.I_NAME else i.NAME end as I_NAME,
			case when i.STREET1 is null then ia.I_STREET1 else i.STREET1 end as I_STREET1,
			case when i.NUM_EXT is null then ia.I_NUM_EXT else i.NUM_EXT end as I_NUM_EXT,
			PLW_DEPARTMENT,
			PLW_TOWER,
			PLW_FLOOR,
			PLW_OFFICE,
			case when city.zip is null then appc.ZIP else city.ZIP end as ZIP, 
			case when city.name is null then appc.NAME else CITY.NAME end as COLONIA, 
			case when dis.name is null then appd.NAME else dis.NAME end as 'DELEG/MPIO', 
			case when edo.NAME is null then appe.name else edo.NAME end as ESTADO,
			estatus.name as P_STATUS_SNR
			from  person_approval p
			left outer join person_ud pud on pud.PERS_SNR = p.P_PERS_SNR
			left outer join CODELIST sexo on sexo.CLIST_SNR = p.P_SEX_SNR 
			left outer join CODELIST esp on esp.CLIST_SNR = p.P_SPEC_SNR 
			left outer join CODELIST subesp on subesp.CLIST_SNR = p.P_SUBSPEC_SNR 
			left outer join CODELIST pacporsem on pacporsem.CLIST_SNR = p.P_PATPERWEEK_SNR 
			left outer join CODELIST honorarios on honorarios.CLIST_SNR = p.P_FEE_TYPE_SNR 
			left outer join CODELIST categ on categ.CLIST_SNR = p.P_CATEGORY_SNR 
			left outer join CODELIST freq on freq.CLIST_SNR = p.P_FRECVIS_SNR 
			left outer join CODELIST dif on dif.CLIST_SNR = p.p_DIFFVIS_SNR 
			left outer join CODELIST tipoEmp on tipoEmp.CLIST_SNR = p.PLW_EMPLOYEESTAT 
			left outer join CODELIST estatus on estatus.CLIST_SNR = p.P_STATUS_SNR 
			left outer join inst i on i.INST_SNR = p.plw_INST_SNR 
			left outer join CITY city on city.CITY_SNR = i.CITY_SNR
			left outer join DISTRICT dis on dis.DISTR_SNR = CITY.DISTR_SNR
			left outer join STATE edo on edo.STATE_SNR = city.STATE_SNR
			left outer join INST_APPROVAL ia on ia.I_INST_SNR = p.PLW_INST_SNR
			left outer join city appc on appc.CITY_SNR = ia.I_CITY_SNR
			left outer join DISTRICT appd on appd.DISTR_SNR = appc.DISTR_SNR 
			left outer join STATE appe on appe.STATE_SNR = appc.STATE_SNR 
			left outer join CODELIST tipo on tipo.clist_snr = p.P_PERSTYPE_SNR 
			where pers_approval_snr = '".$id."'";
			
		//echo $queryPersApp."<br><br>";
		
		$regPersApproval = sqlsrv_fetch_array(sqlsrv_query($conn, $queryPersApp));
		
		$qApprovalStatus = "select * from person_approval pa, APPROVAL_STATUS at where pa.PERS_APPROVAL_SNR = at.PERS_APPROVAL_SNR and pa.PERS_APPROVAL_SNR <> '00000000-0000-0000-0000-000000000000' and at.pers_approval_snr = '".$id."'";
		
		$arrApprovalStatus = sqlsrv_fetch_array(sqlsrv_query($conn, $qApprovalStatus));
		
		//echo $qApprovalStatus."<br><br>";
		//echo "<BR><BR>";
		$approvalStatus = $arrApprovalStatus['P_MOVEMENT_TYPE'];
		$idApproval_status = $arrApprovalStatus['APPROVAL_STATUS_SNR'];
		$pwork = $arrApprovalStatus['PLW_PWORK_SNR'];
		$pers_snr = $arrApprovalStatus['P_PERS_SNR'];
		
		if($approvalStatus == 'C'){
				
			$queryPers = "select 
				FNAME as P_FNAME,
				LNAME AS P_LNAME,
				p.MOTHERS_LNAME as P_NAME_FATHER,
				sexo.name as P_SEX_SNR,
				esp.name as P_SPEC_SNR,
				subesp.name as P_SUBSPEC_SNR,
				pacporsem.name as P_PATPERWEEK_SNR,
				honorarios.name as P_FEE_TYPE_SNR,
				p.BIRTHDATE AS P_BIRTHDATE,
				categ.name as P_CATEGORY_SNR,
				P.PROF_ID as P_PROF_ID,
				freq.name as P_FRECVIS_SNR,
				dif.name as P_DIFFVIS_SNR,		
				lider.name as FIELD_02_SNR,
				p.EMAIL1 AS P_EMAIL,
				p.TEL1 AS P_TEL1,
				/*i.name as PLW_INST_SNR,*/
				i.name as I_NAME,
				i.STREET1 as P_STREET1,
				plw.DEPARTMENT as P_DEPARTAMENTO,
				plw.TOWER as P_TORRE,
				plw.FLOOR as P_PISO,
				plw.OFFICE as P_CONSULTORIO,
				city.zip as ZIP,
				city.name as COLONIA,
				dis.name as 'DELEG/MPIO',
				edo.NAME as ESTADO,
				estatus.name as P_STATUS_SNR
				from  person p
				left outer join person_ud pud on pud.pers_snr = p.PERS_SNR
				left outer join CODELIST sexo on sexo.CLIST_SNR = p.SEX_SNR
				left outer join CODELIST esp on esp.CLIST_SNR = p.SPEC_SNR
				left outer join CODELIST subesp on subesp.CLIST_SNR = p.SUBSPEC_SNR
				left outer join CODELIST pacporsem on pacporsem.CLIST_SNR = p.PATPERWEEK_SNR
				left outer join CODELIST honorarios on honorarios.CLIST_SNR = p.FEE_TYPE_SNR
				left outer join CODELIST categ on categ.CLIST_SNR = p.CATEGORY_SNR
				left outer join CODELIST freq on freq.CLIST_SNR = p.FRECVIS_SNR
				left outer join CODELIST dif on dif.CLIST_SNR = p.DIFFVIS_SNR
				left outer join CODELIST lider on lider.CLIST_SNR = pud.FIELD_02_SNR
				left outer join CODELIST estatus on estatus.CLIST_SNR = p.status_snr
				left outer join PERSLOCWORK plw on plw.pers_snr = p.PERS_SNR
				left outer join inst i on i.INST_SNR = plw.INST_SNR
				left outer join CITY city on city.CITY_SNR = i.CITY_SNR
				left outer join DISTRICT dis on dis.DISTR_SNR = CITY.DISTR_SNR
				left outer join STATE edo on edo.STATE_SNR = city.STATE_SNR
				where p.pers_snr = '".$pers_snr."' 
				and plw.REC_STAT = 0
				and i.REC_STAT = 0";
				
	//echo "---".$queryPers."<br><br>";
				
				$rsExistePLW = sqlsrv_query($conn, "select * from PERSLOCWORK where pwork_snr = '".$pwork."' and rec_stat = 0", array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
				if(sqlsrv_num_rows($rsExistePLW) > 0){
					$queryPers .= "and plw.rec_stat = 0 
					and plw.pwork_snr = '".$pwork."'";
				}
		
			$regPers = sqlsrv_fetch_array(sqlsrv_query($conn, $queryPers));
			
			//echo $queryPers."<br>";
			
			echo "<script>
				$('#tblAprobacion').empty();
				$('#tblAprobacion').append('<tr><td><b>Nombre del Campo</b></td><td><b>Valor Anterior</b></td><td><b>Valor Nuevo</b></td></tr>');
				";
			
			/*$queryAppChage = "select * 
				from APPROVAL_CHANGES ac, REP_COLUMNS rc
				where APPROVAL_STATUS_snr = '".$idApproval_status."'
				and ac.RCOL_SNR = rc.RCOL_SNR ";*/
				
			$queryAppChage = "select ac.*, 
				(case rc.name when 'name' then (case rc.TABLE_NR when 13 then 'colonia'  
				when 8 then 'DELEG/MPIO' 
				when 7 then 'estado' else rc.NAME end) 
				else rc.name end)
				as NAME
				from APPROVAL_CHANGES ac, CONFIG_FIELD rc 
				where APPROVAL_STATUS_snr = '".$idApproval_status."' 
				and ac.RCOL_SNR = rc.COLUMN_NR and ac.RTAB_SNR = rc.TABLE_NR ";
			
			//echo $queryAppChage."<br><br>";
			
			$rsAppChange = sqlsrv_query($conn, $queryAppChage, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			
			//echo"entro: ".sqlsrv_num_rows($rsAppChange)."<br>";
			
			if(sqlsrv_num_rows($rsAppChange) == 0){//entro por android
				
				$qPerson = "select 
					p.FNAME  as P_FNAME, 
					p.LNAME as P_LNAME, 
					p.MOTHERS_LNAME as P_MOTHERS_LNAME, 
					esp.name as P_SPEC_SNR, 
					categ.NAME as P_CATEGORY_SNR, 
					estatus.NAME as P_STATUS_SNR, 
					p.prof_id as P_PROF_ID,
					p.BIRTHDATE as P_BIRTHDATE,
					pacientes.name as P_PATPERWEEK_SNR,
					honorarios.name as P_FEE_TYPE_SNR,
					sexo.name as P_SEX_SNR,
					tipo.name as P_PERSTYPE_SNR,
					i.name as I_NAME,
					i.STREET1 as I_STREET1,
					city.zip as ZIP,
					city.name as COLONIA,
					dis.name as 'DELEG/MPIO',
					edo.NAME as ESTADO
					from person p
					inner join PERSON_APPROVAL pa on pa.P_PERS_SNR = p.PERS_SNR
					inner join APPROVAL_STATUS at on at.PERS_APPROVAL_SNR = pa.PERS_APPROVAL_SNR
					left outer join CODELIST esp on esp.CLIST_SNR = p.spec_snr
					left outer join CODELIST categ on categ.CLIST_SNR = p.CATEGORY_SNR
					left outer join CODELIST estatus on estatus.CLIST_SNR = p.STATUS_SNR
					left outer join CODELIST pacientes on pacientes.CLIST_SNR = p.patperweek_snr
					left outer join CODELIST honorarios on honorarios.CLIST_SNR = p.FEE_TYPE_SNR
					left outer join CODELIST sexo on sexo.CLIST_SNR = p.sex_snr
					left outer join CODELIST tipo on tipo.CLIST_SNR = p.perstype_snr
					left outer join PERSLOCWORK plw on plw.pers_snr = p.PERS_SNR
					left outer join inst i on i.INST_SNR = plw.INST_SNR
					left outer join CITY city on city.CITY_SNR = i.CITY_SNR
					left outer join DISTRICT dis on dis.DISTR_SNR = CITY.DISTR_SNR
					left outer join STATE edo on edo.STATE_SNR = city.STATE_SNR
					where at.APPROVAL_STATUS_SNR = '".$idApproval_status."'";
				
				//echo "<br><br>";
				
				$qPersApproval = "select 
					pa.P_FNAME, 
					pa.P_LNAME, 
					pa.P_MOTHERS_LNAME, 
					esp.NAME as P_SPEC_SNR, 
					categ.NAME as P_CATEGORY_SNR, 
					estatus.NAME as P_STATUS_SNR,
					pa.P_PROF_ID,
					pa.P_BIRTHDATE,
					pacientes.name as P_PATPERWEEK_SNR,
					honorarios.name as P_FEE_TYPE_SNR,
					sexo.name as P_SEX_SNR,
					tipo.name as P_PERSTYPE_SNR,
					inst.name as I_NAME,
					inst.STREET1 as I_STREET1,
					city.name as COLONIA,
					city.zip as ZIP,
					dis.name as 'DELEG/MPIO',
					edo.name as ESTADO
					from APPROVAL_STATUS at 
					inner join person_approval pa on at.PERS_APPROVAL_SNR = pa.PERS_APPROVAL_SNR
					left outer join CODELIST esp on esp.CLIST_SNR = pa.P_SPEC_SNR
					left outer join CODELIST categ on categ.CLIST_SNR = pa.P_CATEGORY_SNR
					left outer join CODELIST estatus on estatus.CLIST_SNR = pa.P_STATUS_SNR
					left outer join CODELIST pacientes on pacientes.CLIST_SNR = pa.p_patperweek_snr
					left outer join CODELIST honorarios on honorarios.CLIST_SNR = pa.p_FEE_TYPE_SNR
					left outer join CODELIST sexo on sexo.CLIST_SNR = pa.p_sex_snr
					left outer join CODELIST tipo on tipo.CLIST_SNR = pa.p_perstype_snr
					inner join inst on inst.INST_SNR = pa.PLW_INST_SNR
					inner join city on city.CITY_SNR = INST.CITY_SNR
					inner join DISTRICT dis on dis.DISTR_SNR = CITY.DISTR_SNR
					inner join state edo on edo.STATE_SNR = city.STATE_SNR
					where at.APPROVAL_STATUS_SNR = '".$idApproval_status."'";
					
				$arrPersApproval = sqlsrv_fetch_array(sqlsrv_query($conn, $qPersApproval));
				$arrPerson = sqlsrv_fetch_array(sqlsrv_query($conn, $qPerson));
				
				/*print_r($arrayNombres);
				echo "<br><br>";
				print_r($arrayValores);
				echo "<br><br>";
				print_r($arrPersApproval);
				echo "<br><br>";
				print_r($arrPerson);*/
				
				for($m=0;$m<count($arrayNombres);$m++){
					if(array_key_exists($arrayNombres[$m], $arrPersApproval)){
						if($arrPerson[$arrayNombres[$m]] != $arrPersApproval[$arrayNombres[$m]]){
							if( is_object($arrPerson[$arrayNombres[$m]]) || is_object($arrPersApproval[$arrayNombres[$m]])){
								if( is_object($arrPerson[$arrayNombres[$m]])){
									foreach ($arrPerson[$arrayNombres[$m]] as $key => $val) {
										if(strtolower($key) == 'date'){
											$fechaPers = substr($val, 0, 10);
										}
									}
								} else {
									$fechaPers = '';
								}
								if(is_object($arrPersApproval[$arrayNombres[$m]])){
									foreach ($arrPersApproval[$arrayNombres[$m]] as $key => $val) {
										if(strtolower($key) == 'date'){
											$fechaApp = substr($val, 0, 10);
										}
									}
								}else {
									$fechaApp = '';
								}
								echo "$('#tblAprobacion').append('<tr><td><b>".$arrayValores[$m]."</b></td><td>".$fechaPers."</td><td><font color=\"#3F51B5\">".$fechaApp."</font></td></tr>');";
							}else{
								//echo is_object($arrPerson[$arrayNombres[$m]])." - ".is_object($arrPersApproval[$arrayNombres[$m]]);
								echo "$('#tblAprobacion').append('<tr><td><b>".$arrayValores[$m]."</b></td><td>".utf8_decode($arrPerson[$arrayNombres[$m]])."</td><td><font color=\"#3F51B5\">".utf8_encode($arrPersApproval[$arrayNombres[$m]])."</font></td></tr>');";
							}
						}
					}
				}

			}else{
				/*print_r($arrayNombres);
				echo "<br><br>";*/
				while($regAppChange = sqlsrv_fetch_array($rsAppChange)){
					if(strtoupper($regAppChange['NAME']) != 'NAME' && strtoupper($regAppChange['NAME']) != 'COLONIA' && strtoupper($regAppChange['NAME']) != 'ZIP' && strtoupper($regAppChange['NAME']) != 'DELEG/MPIO' && strtoupper($regAppChange['NAME']) != 'ESTADO'){
						$indice = array_search('P_'.strtoupper($regAppChange['NAME']), $arrayNombres);
						//echo $indice." ::: ".'P_'.strtoupper($regAppChange['NAME'])."<br>";
					}else{
						//echo "nombreeee: ".strtoupper($regAppChange['NAME'])."<br>";
						$indice = array_search('I_'.strtoupper($regAppChange['NAME']), $arrayNombres);
						//echo $indice." ::: ".strtoupper($regAppChange['NAME'])."<br>";
					}
					//echo "i: ".$indice."<br>";
					if($indice > -1){
						if(is_object($regPersApproval[$arrayNombres[$indice]])){
							foreach ($regPersApproval[$arrayNombres[$indice]] as $key => $val) {
								if(strtolower($key) == 'date'){
									$fechaApp = substr($val, 0, 10);
								}
							}
							if(is_object($regPers[$arrayNombres[$indice]])){
								foreach($regPers[$arrayNombres[$indice]] as $key => $val){
									if(strtolower($key) == 'date'){
										$fechaPers = substr($val, 0, 10);
									}
								}
							}else{
								$fechaPers = "";
							}
							if($fechaApp != $fechaPers){
								echo "$('#tblAprobacion').append('<tr><td><b>".$arrayValores[$indice]."</b></td><td>".$fechaPers."</td><td><font color=\"#3F51B5\">".$fechaApp."</font></td></tr>');";
							}else{
								//echo "$('#tblAprobacion').append('<tr><td><b>".$arrayValores[$indice]."</b></td><td>".$fechaPers."</td><td>".$fechaApp."</td></tr>');";
							}
						}else{
							if($regPersApproval[$arrayNombres[$indice]] != $regPers[$arrayNombres[$indice]]){
								echo "$('#tblAprobacion').append('<tr><td><b>".$arrayValores[$indice]."</b></td><td>".utf8_encode($regPers[$arrayNombres[$indice]])."</td><td><font color=\"#3F51B5\">".utf8_encode($regPersApproval[$arrayNombres[$indice]])."</font></td></tr>');";
							}else{
								//echo "$('#tblAprobacion').append('<tr><td><b>".$arrayValores[$indice]."</b></td><td>".utf8_encode($regPers[$arrayNombres[$indice]])."</td><td>".utf8_encode($regPersApproval[$arrayNombres[$indice]])."</td></tr>');";
							}
						}
					}
				}
			}
			echo "</script>";
		}else if($approvalStatus == 'N'){
			//echo " ::: ".count($arrayNombres);
			echo "<script>
				$('#tblAprobacion').empty();";
			/*echo "<br>";
			print_r($arrayNombres);
			echo "<br>";
			print_r($regPersApproval);
			echo "<br>";*/
			for($i=0;$i<count($arrayNombres);$i++){
				if(array_key_exists($arrayNombres[$i], $regPersApproval)){
					if(is_object($regPersApproval[$arrayNombres[$i]])){
						foreach ($regPersApproval[$arrayNombres[$i]] as $key => $val) {
							if(strtolower($key) == 'date'){
								echo "$('#tblAprobacion').append('<tr><td width=\"50%\"><b>".$arrayValores[$i]."</b></td><td>".substr($val, 0, 10)."</td></tr>');";
							}
						}
					}else{
						echo "$('#tblAprobacion').append('<tr><td width=\"50%\"><b>".$arrayValores[$i]."</b></td><td>".utf8_encode(strtoupper(str_replace("\r\n", " ",$regPersApproval[$arrayNombres[$i]])))."</td></tr>');";
					}
				}
			}
			echo "</script>";
		}else if($approvalStatus == 'D'){
			
			$queryPersApp = "select 
			p.P_FNAME,
			p.P_LNAME,
			p.P_MOTHERS_LNAME,
			sexo.name as P_SEX_SNR,
			esp.name as P_SPEC_SNR,
			subesp.name as P_SUBSPEC_SNR,
			pacporsem.name as P_PATPERWEEK_SNR,
			honorarios.name as P_FEE_TYPE_SNR,
			p.P_BIRTHDATE,
			categ.name as P_CATEGORY_SNR,
			p.P_PROF_ID AS P_PROF_ID,
			freq.name as P_FRECVIS_SNR,
			P_DIFFVIS_SNR as P_DIFFVIS_SNR,		
			P_EMAIL1,
			P_TEL1,
			/*P_ABIERTO1,
			P_ABIERTO2,
			P_ABIERTO3,*/
			i.name as I_NAME,
			i.STREET1 as I_STREET1,
			i.NUM_EXT as I_NUM_EXT,
			PLW_DEPARTMENT,
			PLW_TOWER,
			PLW_FLOOR,
			PLW_OFFICE,
			city.zip as ZIP,
			city.name as COLONIA,
			dis.name as CIUDAD,
			edo.NAME as ESTADO,
			p.PLW_DEL_REASON as comentariosBaja, 
			motivo.NAME as motivo
			from  person_approval p
			left outer join CODELIST sexo on sexo.CLIST_SNR = p.P_SEX_SNR
			left outer join CODELIST esp on esp.CLIST_SNR = p.P_SPEC_SNR
			left outer join CODELIST subesp on subesp.CLIST_SNR = p.P_SUBSPEC_SNR
			left outer join CODELIST pacporsem on pacporsem.CLIST_SNR = p.P_PATPERWEEK_SNR 
			left outer join CODELIST honorarios on honorarios.CLIST_SNR = p.P_FEE_TYPE_SNR
			left outer join CODELIST categ on categ.CLIST_SNR = p.P_CATEGORY_SNR
			left outer join CODELIST freq on freq.CLIST_SNR = p.P_FRECVIS_SNR
			/*inner join CODELIST botiquin on botiquin.CLIST_SNR = p.P_RX_TYPE_SNR*/
			left outer join person_ud pud on pud.pers_snr = p.p_pers_snr
			left outer join PERSLOCWORK plw on plw.pers_snr = p.P_PERS_SNR
			left outer join inst i on i.INST_SNR = plw.INST_SNR
			left outer join CITY city on city.CITY_SNR = i.CITY_SNR
			left outer join DISTRICT dis on dis.DISTR_SNR = CITY.DISTR_SNR
			left outer join STATE edo on edo.STATE_SNR = city.STATE_SNR
			left join CODELIST motivo on motivo.CLIST_SNR = p.PLW_DEL_STATUS_SNR
			where pers_approval_snr = '".$id."'";
				
			//echo $queryPersApp;
			
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
					echo "$('#tblAprobacion').append('<tr><td width=\"50%\"><b>".$arrayValores[$i]."</b></td><td>".utf8_encode($regPersApproval[$arrayNombres[$i]])."</td></tr>');";
				}
			}
			echo "$('#tblAprobacion').append('<tr><td width=\"50%\"><b>Motivo de la baja</b></td><td>".utf8_encode($regPersApproval['motivo'])."</td></tr>');";
			echo "$('#tblAprobacion').append('<tr><td width=\"50%\"><b>Notas adicionales</b></td><td>".utf8_encode(str_replace("\n", " ",$regPersApproval['comentariosBaja']))."</td></tr>');";
			echo "</script>";
		}
	}else if($tipo == 'i'){
		$queryCampos = "select UPPER(case when rc.name = 'name' then rt.LONG_TEXT else rc.name end) as name, rt.LONG_TEXT, rt.SHORT_TEXT 
			from CONFIG_FIELD_TRANSLATION rt, CONFIG_TABLE rb, CONFIG_FIELD rc 
			where rt.TABLE_NR = rb.TABLE_NR
			and rt.TABLE_NR in (492,13,8,7)
			and rt.LANG_NR = 8
			and rc.TABLE_NR = rb.TABLE_NR
			and rt.COLUMN_NR = rc.COLUMN_NR
			and rt.SORT_NUM > 0
			order by CAST(SHORT_TEXT AS INT) ASC ";
			
		//echo $queryCampos."<br>";
		
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
			tipo.name as I_INST_TYPE,
			subtipo.name I_SUBTYPE_SNR,
			formato.name I_FORMAT_SNR,
			i.I_EMAIL1,
			i.I_WEB,
			i.I_INFO,
			categ.name as I_CATEGORY_SNR,
			i.I_NAME,
			status.name as I_STATUS_SNR,
			i.I_STREET1,
			i.I_NUM_EXT,
			i.I_TEL1,
			i.I_TEL2,
			colonia.name as COLONIA,
			colonia.ZIP as ZIP,
			dis.name as 'DELEG/MPIO',
			edo.NAME as ESTADO,
			i.i_del_reason as comentariosBaja,
			baja.name as motivo,
			frecvis.name as I_FRECVIS_SNR
			from inst_approval i
			LEFT OUTER join CODELIST tipo on tipo.CLIST_SNR = i.i_type_snr
			LEFT OUTER join CODELIST categ on categ.CLIST_SNR = i.i_category_snr
			left outer join CODELIST status on status.CLIST_SNR = i.i_status_snr
			LEFT OUTER join CODELIST subtipo on subtipo.CLIST_SNR = i.I_SUBTYPE_SNR 
			LEFT OUTER join CODELIST formato on formato.CLIST_SNR = i.I_FORMAT_SNR 
			LEFT OUTER join city colonia on colonia.city_snr = i.i_city_snr
			left outer join DISTRICT dis on dis.DISTR_SNR = colonia.DISTR_SNR
			left outer join STATE edo on edo.STATE_SNR = colonia.STATE_SNR
			left outer join codelist baja on baja.clist_snr = i.i_del_status_snr
			left outer join codelist frecvis on frecvis.clist_snr = i.I_FRECVIS_SNR
			where i.inst_approval_snr = '".$id."'";
			
		//echo "queryInstApp: ".$queryInstApp."<br><br>";
		
		$regInstApproval = sqlsrv_fetch_array(sqlsrv_query($conn, $queryInstApp));
		
		//print_r($regInstApproval);
		
		//echo "<br><br>";
		
		$queryApprovalStatus = "select * from inst_approval ia, APPROVAL_STATUS at where ia.INST_APPROVAL_SNR = at.INST_APPROVAL_SNR and ia.INST_APPROVAL_SNR <> '00000000-0000-0000-0000-000000000000' and at.inst_approval_snr = '".$id."'";
		
		$arrApprovalStatus = sqlsrv_fetch_array(sqlsrv_query($conn, $queryApprovalStatus));
		
		$approvalStatus = $arrApprovalStatus['I_MOVEMENT_TYPE'];
		$inst_snr = $arrApprovalStatus['I_INST_SNR'];
		$idApproval_status = $arrApprovalStatus['APPROVAL_STATUS_SNR'];
		
		//echo "approvalStatus: ".$approvalStatus."<br><br>";
		
		if($approvalStatus == 'C'){
			
			$queryInst = "select 
				tipo.name as I_INST_TYPE,
				subtipo.name I_SUBTYPE_SNR,
				formato.name I_FORMAT_SNR,
				email1 as I_EMAIL1,
				web as I_WEB,
				info as I_INFO,
				categ.name as I_CATEGORY_SNR,
				i.name as I_NAME,
				status.name as I_STATUS_SNR,
				street1 as I_STREET1,
				num_ext as I_NUM_EXT,
				tel1 as I_TEL1,
				tel2 as I_TEL2,
				colonia.name as COLONIA,
				colonia.ZIP as ZIP,
				dis.name as 'DELEG/MPIO',
				edo.NAME as ESTADO,
				frecvis.name as I_FRECVIS_SNR
				from inst i
				LEFT OUTER join CODELIST tipo on tipo.CLIST_SNR = i.TYPE_SNR
				left outer join CODELIST categ on categ.CLIST_SNR = i.category_snr 
				left outer join CODELIST status on status.CLIST_SNR = i.status_snr 
				left outer join CODELIST subtipo on subtipo.CLIST_SNR = i.SUBTYPE_SNR 
				left outer join CODELIST formato on formato.CLIST_SNR = i.FORMAT_SNR 
				left outer join city colonia on colonia.city_snr = i.city_snr
				left outer join DISTRICT dis on dis.DISTR_SNR = colonia.DISTR_SNR
				left outer join STATE edo on edo.STATE_SNR = colonia.STATE_SNR
				left outer join CODELIST frecvis on frecvis.clist_snr = i.frecvis_snr
				where inst_snr = '$inst_snr'";
				
				
			//echo $queryInst;
		
			$regInst = sqlsrv_fetch_array(sqlsrv_query($conn, $queryInst));
			
			/*print_r($arrayValores);
			echo "<br><br>";
			print_r($regInst);
			echo "<br><br>";
			print_r($regInstApproval);
			echo "<br>";*/
			
			$queryAppChage = "select * 
				from APPROVAL_CHANGES ac, CONFIG_FIELD rc
				where APPROVAL_STATUS_snr = '".$idApproval_status."'
				and ac.RCOL_SNR = rc.COLUMN_NR 
				and ac.RTAB_SNR = rc.TABLE_NR ";
				
			//echo $queryAppChage;
			
			echo "<script>
			$('#tblAprobacion').empty();
			$('#tblAprobacion').append('<tr><td><b>Nombre del Campo</b></td><td><b>Valor Anterior</b></td><td><b>Valor Nuevo</b></td></tr>');";
			
			$rsAppChange = sqlsrv_query($conn, $queryAppChage, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			
			//echo "num: ".sqlsrv_num_rows($rsAppChange);
			
			if(sqlsrv_num_rows($rsAppChange) == 0){//entro por android
				//echo "netra";
				for($i=0;$i<count($arrayNombres);$i++){
					if($regInstApproval[$arrayNombres[$i]] != $regInst[$arrayNombres[$i]]){
						echo "$('#tblAprobacion').append('<tr><td><b>".$arrayValores[$i]."</b></td><td>".$regInst[$arrayNombres[$i]]."</td><td><font color=\"#0000FF\">".$regInstApproval[$arrayNombres[$i]]."</font></td></tr>');";
					}else{
						//echo "$('#tblAprobacion').append('<tr><td><b>".$arrayValores[$i]."</b></td><td>".$regInstApproval[$arrayNombres[$i]]."</td><td>".$regInst[$arrayNombres[$i]]."</td></tr>');";
					}
				}
			
			}else{
			
				$domicilio = true;
				while($regAppChange = sqlsrv_fetch_array($rsAppChange)){
					//print_r($regAppChange);
					//echo "<br><br>";
					$indice = array_search('I_'.strtoupper($regAppChange['NAME']), $arrayNombres);
					//echo $indice." ::: ".'I_'.strtoupper($regAppChange['NAME'])."<br>";
					if($indice > -1){
						//echo $regInstApproval[$arrayNombres[$indice]]." = ".$regInst[$arrayNombres[$indice]]."<br>";
						if(strtoupper($regInstApproval[$arrayNombres[$indice]]) != strtoupper($regInst[$arrayNombres[$indice]])){
							echo "$('#tblAprobacion').append('<tr><td><b>".$arrayValores[$indice]."</b></td><td>".strtoupper($regInst[$arrayNombres[$indice]])."</td><td><font color=\"#3F51B5\">".strtoupper($regInstApproval[$arrayNombres[$indice]])."</font></td></tr>');";
						}else{
							//echo "$('#tblAprobacion').append('<tr><td><b>".$arrayValores[$indice]."</b></td><td>".utf8_encode($regPers[$arrayNombres[$indice]])."</td><td>".utf8_encode($regPersApproval[$arrayNombres[$indice]])."</td></tr>');";
						}
					}else{
						if($domicilio){
							if($regInstApproval['COLONIA'] != $regInst['COLONIA']){
								echo "$('#tblAprobacion').append('<tr><td><b>Colonia</b></td><td>".$regInst['COLONIA']."</td><td><font color=\"#3F51B5\">".$regInstApproval['COLONIA']."</font></td></tr>');";
							}
							if($regInstApproval['ZIP'] != $regInst['ZIP']){
								echo "$('#tblAprobacion').append('<tr><td><b>Zip</b></td><td>".$regInst['ZIP']."</td><td><font color=\"#3F51B5\">".$regInstApproval['ZIP']."</font></td></tr>');";
							}
							if($regInstApproval['DELEG/MPIO'] != $regInst['DELEG/MPIO']){
								echo "$('#tblAprobacion').append('<tr><td><b>Deleg/Mpio</b></td><td>".$regInst['DELEG/MPIO']."</td><td><font color=\"#3F51B5\">".$regInstApproval['DELEG/MPIO']."</font></td></tr>');";
							}
							if($regInstApproval['ESTADO'] != $regInst['ESTADO']){
								echo "$('#tblAprobacion').append('<tr><td><b>Estado</b></td><td>".$regInst['ESTADO']."</td><td><font color=\"#3F51B5\">".$regInstApproval['ESTADO']."</font></td></tr>');";
							}
							$domicilio = false;
						}
					}
				}
			}
			
			echo "</script>";
		}else{
			/*print_r($arrayNombres);
			echo "<br>";
			print_r($regInstApproval);*/
			echo "<script>
				$('#tblAprobacion').empty();";
			for($i=0;$i<count($arrayNombres);$i++){
				echo "$('#tblAprobacion').append('<tr><td width=\"50%\"><b>".$arrayValores[$i]."</b></td><td>".utf8_encode($regInstApproval[$arrayNombres[$i]])."</td></tr>');";
			}
			if($approvalStatus == 'D'){
				echo "$('#tblAprobacion').append('<tr><td width=\"50%\"><b>Motivo de la baja</b></td><td>".utf8_encode($regInstApproval['motivo'])."</td></tr>');";
				echo "$('#tblAprobacion').append('<tr><td width=\"50%\"><b>Notas adicionales</b></td><td>".utf8_encode(str_replace("\n", " ",$regInstApproval['comentariosBaja']))."</td></tr>');";
			}
			echo "</script>";
		}
	}
	echo "<script>
			$('#sltMotivoRechazo').val('00000000-0000-0000-0000-000000000000');
			$('#divMotivoRechazo').hide();
			$('#btnAceptarAprobacion').prop('disabled', false);
		</script>";
		
	//echo $queryAppChage;
?>