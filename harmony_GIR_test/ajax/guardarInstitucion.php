<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		
		$idInst = $_POST['idInst'];
		$idUsuario = $_POST['idUsuario'];
		$tipoInst = $_POST['tipoInst'];
		$tipoInstInt = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from INST_TYPE where inst_type_snr = '".$tipoInst."'"))['INST_TYPE'];
		//echo "select * from INST_TYPE where inst_type_snr = '".$tipoInst."'";
		$subtipo = (strtoupper($_POST['subtipo']) == '') ? '00000000-0000-0000-0000-000000000000' : strtoupper($_POST['subtipo']);
		$formato = (strtoupper($_POST['formato']) == '') ? '00000000-0000-0000-0000-000000000000' : strtoupper($_POST['formato']);
		$estatus = (strtoupper($_POST['estatus']) == '') ? '00000000-0000-0000-0000-000000000000' : strtoupper($_POST['estatus']);
		$categoria = (strtoupper($_POST['categoria']) == '') ? '00000000-0000-0000-0000-000000000000' : strtoupper($_POST['categoria']);
		$frecuencia = (strtoupper($_POST['frecuencia']) == '') ? '00000000-0000-0000-0000-000000000000' : strtoupper($_POST['frecuencia']);		
		$inst = strtoupper(utf8_decode($_POST['inst']));
		$calle = strtoupper(utf8_decode($_POST['calle']));
		$numExt = strtoupper(utf8_decode($_POST['numExt']));
		$cp = $_POST['cp'];
		$colonia = strtoupper(utf8_decode($_POST['colonia']));
		$city = $_POST['city'];
		$tel1 = $_POST['tel1'];
		$tel2 = $_POST['tel2'];
		$celular = $_POST['celular'];
		$email = $_POST['email'];
		$web = $_POST['web'];
		$comentarios = strtoupper(utf8_decode($_POST['comentarios']));
		$sucursal = strtoupper(utf8_decode($_POST['sucursal']));
		
		$field01InstNueva = strtoupper($_POST['field01InstNueva']);
		$field02InstNueva = strtoupper($_POST['field02InstNueva']);
		$field03InstNueva = strtoupper($_POST['field03InstNueva']);
		$field04InstNueva = strtoupper($_POST['field04InstNueva']);
		$field05InstNueva = strtoupper($_POST['field05InstNueva']);
		$field06InstNueva = strtoupper($_POST['field06InstNueva']);
		$field07InstNueva = strtoupper($_POST['field07InstNueva']);
		
		$field01InstNueva_snr = (strtoupper($_POST['field01InstNueva_snr']) == '') ? '00000000-0000-0000-0000-000000000000' : strtoupper($_POST['field01InstNueva_snr']);
		$field02InstNueva_snr = strtoupper($_POST['field02InstNueva_snr']);
		$field03InstNueva_snr = (strtoupper($_POST['field03InstNueva_snr']) == '') ? '00000000-0000-0000-0000-000000000000' : strtoupper($_POST['field03InstNueva_snr']);
		$field04InstNueva_snr = (strtoupper($_POST['field04InstNueva_snr']) == '') ? '00000000-0000-0000-0000-000000000000' : strtoupper($_POST['field04InstNueva_snr']);
		$field05InstNueva_snr = (strtoupper($_POST['field05InstNueva_snr']) == '') ? '00000000-0000-0000-0000-000000000000' : strtoupper($_POST['field05InstNueva_snr']);
		$field06InstNueva_snr = strtoupper($_POST['field06InstNueva_snr']);
		$field07InstNueva_snr = strtoupper($_POST['field07InstNueva_snr']);
		$field08InstNueva_snr = (strtoupper($_POST['field08InstNueva_snr']) == '') ? '00000000-0000-0000-0000-000000000000' : strtoupper($_POST['field08InstNueva_snr']);
		$field09InstNueva_snr = strtoupper($_POST['field09InstNueva_snr']);
		$field10InstNueva_snr = (strtoupper($_POST['field10InstNueva_snr']) == '') ? '00000000-0000-0000-0000-000000000000' : strtoupper($_POST['field10InstNueva_snr']);
		
		$field01InstNuevaHosp = strtoupper($_POST['field01InstNuevaHosp']);
		$field02InstNuevaHosp = strtoupper($_POST['field02InstNuevaHosp']);
		$field03InstNuevaHosp = strtoupper($_POST['field03InstNuevaHosp']);
		$field04InstNuevaHosp = strtoupper($_POST['field04InstNuevaHosp']);
		$field05InstNuevaHosp = strtoupper($_POST['field05InstNuevaHosp']);
		$field06InstNuevaHosp = strtoupper($_POST['field06InstNuevaHosp']);
		/*$field07InstNuevaHosp = strtoupper($_POST['field07InstNuevaHosp']);
		$field08InstNuevaHosp = strtoupper($_POST['field08InstNuevaHosp']);
		$field09InstNuevaHosp = strtoupper($_POST['field09InstNuevaHosp']);
		$field10InstNuevaHosp = strtoupper($_POST['field10InstNuevaHosp']);
		$field11InstNuevaHosp = strtoupper($_POST['field11InstNuevaHosp']);
		$field12InstNuevaHosp = strtoupper($_POST['field12InstNuevaHosp']);*/
		
		$field01InstNuevaHosp_snr = strtoupper($_POST['field01InstNuevaHosp_snr']);
		$field02InstNuevaHosp_snr = strtoupper($_POST['field02InstNuevaHosp_snr']);
		/*$field03InstNuevaHosp_snr = (strtoupper($_POST['field03InstNuevaHosp_snr']) == '') ? '00000000-0000-0000-0000-000000000000' : strtoupper($_POST['field03InstNuevaHosp_snr']);
		$field04InstNuevaHosp_snr = (strtoupper($_POST['field04InstNuevaHosp_snr']) == '') ? '00000000-0000-0000-0000-000000000000' : strtoupper($_POST['field04InstNuevaHosp_snr']);*/
		
		$tipoUsuario = $_POST['tipoUsuario'];
		
		$rsIdInstApproval = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idInstApproval from INST_APPROVAL where INST_APPROVAL_SNR = '00000000-0000-0000-0000-000000000000'"));
		//echo "select NEWID() as idInstApproval from INST_APPROVAL where INST_APPROVAL_SNR = '00000000-0000-0000-0000-000000000000'";
		$idInstApproval = $rsIdInstApproval['idInstApproval'];
		$rsNR = sqlsrv_fetch_array(sqlsrv_query($conn, "select max(I_NR)+1 as nr from INST_APPROVAL"));
		$nr = $rsNR['nr'];
		$rsIdApprovalSatus = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idApprovalStatus from APPROVAL_STATUS where INST_APPROVAL_SNR = '00000000-0000-0000-0000-000000000000'"));
		$idApprovalStatus = $rsIdApprovalSatus['idApprovalStatus'];
		
		//echo "<script>alert('".$estatus."');</script>";
		
		if($idInst != ''){
			if($tipoUsuario == 4){//es repre
				$tipoMovimiento = "C";
				
				/*grabo approval changes */
				$queryCampos = "select rc.TABLE_NR, rc.COLUMN_NR, upper(rc.name) as name, rc.approval
				from CONFIG_TABLE rt, CONFIG_FIELD rc
				where rt.name = 'inst'
				and rt.TABLE_NR = rc.TABLE_NR ";
				
				$rsCampos = sqlsrv_query($conn, $queryCampos);
				
				$rcol = array();
				$campos = array();
				$approval = array();
				
				while($campo = sqlsrv_fetch_array($rsCampos)){
					$rtab = $campo['TABLE_NR'];
					$rcol[] = $campo['COLUMN_NR'];
					$campos[] = $campo['name'];
					$approval[] = $campo['approval'];
				}
				
				$queryAnt = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from INST where INST_SNR = '$idInst'"));
				
				$insertaApproval = 0;
				$actualizaInst = 0;
				
				$queryInstActualiza = "update inst set sync = 0 ";
				
				if($queryAnt['INST_TYPE'] != $tipoInstInt){
					$idCampo = array_search ( "INST_TYPE" , $campos );
					if($approval[$idCampo] == 1){
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(), '".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab.",'00000000-0000-0000-0000-000000000000','".$tipoInstInt."',0,0)";
						$insertaApproval = 1;
						if(! sqlsrv_query($conn, $insertaChange)){
							echo 'INST_TYPE: '.$insertaChange."<br><br>";
						}
						///tambien cambio el type_snr
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(), '".$idApprovalStatus."', '2', '14','".$tipoInst."','',0,0)";
						if(! sqlsrv_query($conn, $insertaChange)){
							echo 'TYPE_SNR: '.$insertaChange."<br><br>";
						}
					}else{
						$queryInstActualiza .= ",INST_TYPE = '".$tipoInstInt."' ";
						$queryInstActualiza .= ",TYPE_SNR = '".$tipoInst."' ";
						$actualizaInst = 1;
					}
				}

				if($queryAnt['SUBTYPE_SNR'] != $subtipo){
					$idCampo = array_search ( "SUBTYPE_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaApproval = 1;
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(), '".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab.",'".$subtipo."','',0,0)";
						if(! sqlsrv_query($conn, $insertaChange)){
							echo 'SUBTYPE_SNR:'.$insertaChange."<br><br>";
						}
					}else{
						$queryInstActualiza .= ",SUBTYPE_SNR = '".$subtipo."' ";
						$actualizaInst = 1;
					}
					
				}
				
				if($queryAnt['FORMAT_SNR'] != $formato){
					$idCampo = array_search ( "FORMAT_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaApproval = 1;
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(), '".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab.",'".$formato."','',0,0)";
						if(! sqlsrv_query($conn, $insertaChange)){
							echo 'FORMAT_SNR:'.$insertaChange."<br><br>";
						}
					}else{
						$queryInstActualiza .= ",FORMAT_SNR = '".$formato."' ";
						$actualizaInst = 1;
					}
				}
				
				if($queryAnt['CATEGORY_SNR'] != $categoria){
					$idCampo = array_search ( "CATEGORY_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaApproval = 1;
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(), '".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab.",'".$categoria."','',0,0)";
						if(! sqlsrv_query($conn, $insertaChange)){
							echo "CATEGORY_SNR: ".$insertaChange."<br><br>";
						}
					}else{
						$queryInstActualiza .= ",CATEGORY_SNR = '".$categoria."' ";
						$actualizaInst = 1;
					}
				}
				
				if($queryAnt['FRECVIS_SNR'] != $frecuencia){
					$idCampo = array_search ( "FRECVIS_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaApproval = 1;
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(), '".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab.",'".$frecuencia."','',0,0)";
						if(! sqlsrv_query($conn, $insertaChange)){
							echo "FRECVIS_SNR: ".$insertaChange."<br><br>";
						}
					}else{
						$queryInstActualiza .= ",FRECVIS_SNR = '".$frecuencia."' ";
						$actualizaInst = 1;
					}
				}
				//echo $queryAnt['NAME']." ::: ".$inst."<br>";
				if($queryAnt['NAME'] != $inst){
					$idCampo = array_search ( "NAME" , $campos );
					if($approval[$idCampo] == 1){
						$insertaApproval = 1;
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(), '".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab.",'00000000-0000-0000-0000-000000000000','".$inst."',0,0)";
						if(! sqlsrv_query($conn, $insertaChange)){
							echo "name: ".$insertaChange."<br><br>";
						}
					}else{
						$queryInstActualiza .= ",NAME = '".$inst."' ";
						$actualizaInst = 1;
					}	
				}
				if($queryAnt['STREET1'] != $calle){
					$idCampo = array_search ( "STREET1" , $campos );
					if($approval[$idCampo] == 1){
						$insertaApproval = 1;
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(), '".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab.",'00000000-0000-0000-0000-000000000000','".$calle."',0,0)";
						if(! sqlsrv_query($conn, $insertaChange)){
							echo $insertaChange."<br><br>";
						}
					}else{
						$queryInstActualiza .= ",STREET1 = '".$calle."' ";
						$actualizaInst = 1;
					}
				}
				if($queryAnt['NUM_EXT'] != $numExt){
					$idCampo = array_search ( "NUM_EXT" , $campos );
					if($approval[$idCampo] == 1){
						$insertaApproval = 1;
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(), '".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab.",'00000000-0000-0000-0000-000000000000','".$numExt."',0,0)";
						if(! sqlsrv_query($conn, $insertaChange)){
							echo $insertaChange."<br><br>";
						}
					}else{
						$queryInstActualiza .= ",NUM_EXT = '".$numExt."' ";
						$actualizaInst = 1;
					}
				}
				if($queryAnt['CITY_SNR'] != $city){
					$idCampo = array_search ( "CITY_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaApproval = 1;
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(), '".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab.",'".$city."','',0,0)";
						if(! sqlsrv_query($conn, $insertaChange)){
							echo $insertaChange."<br><br>";
						}
					}else{
						$queryInstActualiza .= ",CITY_SNR = '".$city."' ";
						$actualizaInst = 1;
					}
				}
				if($queryAnt['TEL1'] != $tel1){
					$idCampo = array_search ( "TEL1" , $campos );
					if($approval[$idCampo] == 1){
						$insertaApproval = 1;
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(), '".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab.",'00000000-0000-0000-0000-000000000000','".$tel1."',0,0)";
						if(! sqlsrv_query($conn, $insertaChange)){
							echo $insertaChange."<br><br>";
						}
					}else{
						$queryInstActualiza .= ",TEL1 = '".$tel1."' ";
						$actualizaInst = 1;
					}
				}
				if($queryAnt['TEL2'] != $tel2){
					$idCampo = array_search ( "TEL2" , $campos );
					if($approval[$idCampo] == 1){
						$insertaApproval = 1;
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(), '".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab.",'00000000-0000-0000-0000-000000000000','".$tel2."',0,0)";
						if(! sqlsrv_query($conn, $insertaChange)){
							echo $insertaChange."<br><br>";
						}
					}else{
						$queryInstActualiza .= ",TEL2 = '".$tel2."' ";
						$actualizaInst = 1;
					}
				}
				
				if($queryAnt['EMAIL1'] != $email){
					$idCampo = array_search ( "EMAIL1" , $campos );
					if($approval[$idCampo] == 1){
						$insertaApproval = 1;
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(), '".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab.",'00000000-0000-0000-0000-000000000000','".$email."',0,0)";
						if(! sqlsrv_query($conn, $insertaChange)){
							echo $insertaChange."<br><br>";
						}
					}else{
						$queryInstActualiza .= ",EMAIL1 = '".$email."' ";
						$actualizaInst = 1;
					}
				}
				if($queryAnt['WEB'] != $web){
					$idCampo = array_search ( "WEB" , $campos );
					if($approval[$idCampo] == 1){
						$insertaApproval = 1;
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(), '".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab.",'00000000-0000-0000-0000-000000000000','".$web."',0,0)";
						if(! sqlsrv_query($conn, $insertaChange)){
							echo $insertaChange."<br><br>";
						}
					}else{
						$queryInstActualiza .= ",WEB = '".$web."' ";
						$actualizaInst = 1;
					}
				}
				if($queryAnt['INFO'] != $comentarios){
					$idCampo = array_search ( "INFO" , $campos );
					if($approval[$idCampo] == 1){
						$insertaApproval = 1;
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(), '".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab.",'00000000-0000-0000-0000-000000000000','".$comentarios."',0,0)";
						if(! sqlsrv_query($conn, $insertaChange)){
							echo $insertaChange."<br><br>";
						}
					}else{
						$queryInstActualiza .= ",INFO = '".$comentarios."' ";
						$actualizaInst = 1;
					}
				}
				
				$queryInstActualiza .= " where inst_snr = '".$idInst."' ";
				
				if($actualizaInst == 1){
					if(! sqlsrv_query($conn, $queryInstActualiza)){
						echo "inst: ".$queryInstActualiza;
					}
				}
			}else{//no es repre
				$qActualizaInst = "update INST set
				TYPE_SNR = '".$tipoInst."',
				INST_TYPE = '".$tipoInstInt."',
				SUBTYPE_SNR = '".$subtipo."',
				FORMAT_SNR = '".$formato."',
				NAME = '".$inst."',	
				STREET1 = '".$calle."',
				NUM_EXT = '".$numExt."',
				CITY_SNR = '".$city."',
				TEL1 = '".$tel1."',
				TEL2 = '".$tel2."',
				WEB = '".$web."',
				EMAIL1 = '".$email."',
				CATEGORY_SNR = '".$categoria."',
				FRECVIS_SNR = '".$frecuencia."',
				INFO = '".$comentarios."',
				SYNC = 0,
				MOBILE = '".$celular."'  
				where INST_SNR = '".$idInst."' ";
				
				if(! sqlsrv_query($conn, $qActualizaInst)){
					echo $qActualizaInst."<br>";
				}
			}
			
			/*$qInstUD = "update inst_ud set
				FIELD_01 = '".$field01InstNueva."',
				FIELD_01_SNR = '".$field01InstNueva_snr."',
				SYNC = 0,
				CHANGED_TIMESTAMP = getdate() 
				where INST_SNR = '".$idInst."' ";
				
			if(! sqlsrv_query($conn, $qInstUD)){
				echo $qInstUD."<br>";
			}*/
			
			if($tipoInstInt == 1){//hospitales
				$queryInst_ud = "update inst_hospital set
					FIELD_01 = '".$field01InstNuevaHosp."',
					FIELD_02 = '".$field02InstNuevaHosp."',
					FIELD_03 = '".$field03InstNuevaHosp."', 
					FIELD_04 = '".$field04InstNuevaHosp."',
					FIELD_05 = '".$field05InstNuevaHosp."',
					FIELD_06 = '".$field06InstNuevaHosp."',
					FIELD_01_SNR = '".$field01InstNuevaHosp_snr."',
					FIELD_02_SNR = '".$field02InstNuevaHosp_snr."',
					SYNC = 0,
					CHANGED_TIMESTAMP = getdate() 
					where INST_SNR = '".$idInst."' ";
					
				if(! sqlsrv_query($conn, $queryInst_ud)){
					echo $queryInst_ud."<br>";
				}
			}else if($tipoInstInt == 2){//farmacias
				$queryInst_ud = "update inst_pharmacy set
					FIELD_01 = '".$field01InstNueva."',
					FIELD_02 = '".$field02InstNueva."',
					FIELD_03 = '".$field03InstNueva."', 
					FIELD_04 = '".$field04InstNueva."',
					FIELD_05 = '".$field05InstNueva."',
					FIELD_06 = '".$field06InstNueva."',
					FIELD_07 = '".$field07InstNueva."',
					FIELD_01_SNR = '".$field01InstNueva_snr."',
					FIELD_02_SNR = '".$field02InstNueva_snr."',
					FIELD_03_SNR = '".$field03InstNueva_snr."',
					FIELD_04_SNR = '".$field04InstNueva_snr."',
					FIELD_05_SNR = '".$field05InstNueva_snr."',
					FIELD_06_SNR = '".$field06InstNueva_snr."',
					FIELD_07_SNR = '".$field07InstNueva_snr."',
					FIELD_08_SNR = '".$field08InstNueva_snr."',
					FIELD_09_SNR = '".$field09InstNueva_snr."',
					FIELD_10_SNR = '".$field10InstNueva_snr."',
					SYNC = 0,
					CHANGED_TIMESTAMP = getdate() 
					where INST_SNR = '".$idInst."' ";
					
				if(! sqlsrv_query($conn, $queryInst_ud)){
					echo $queryInst_ud."<br>";
				}
			}
					
		}else{
			$rsIdInst = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idInst from INST where INST_SNR = '00000000-0000-0000-0000-000000000000'"));
			$idInst = $rsIdInst['idInst'];
			$tipoMovimiento = "N";
			$idPwork = '00000000-0000-0000-0000-000000000000';
			$insertaApproval = 1;
			
			if($tipoInstInt == 1){
				$queryInst_ud = "insert into inst_hospital (  
					INST_SNR, 
					FIELD_01,
					FIELD_02,
					FIELD_03,
					FIELD_04,
					FIELD_05,
					FIELD_06,
					FIELD_01_SNR,
					FIELD_02_SNR,					
					SYNC,
					CREATION_TIMESTAMP,
					REC_STAT 
				) values (
					'".$idInst."', 
					'".$field01InstNuevaHosp."', 
					'".$field02InstNuevaHosp."', 
					'".$field03InstNuevaHosp."', 
					'".$field04InstNuevaHosp."', 
					'".$field05InstNuevaHosp."', 
					'".$field06InstNuevaHosp."',  
					'".$field01InstNuevaHosp_snr."',
					'".$field02InstNuevaHosp_snr."',
					0,
					getdate(), 
					0 
				)";
			}else if($tipoInstInt == 2){
				$queryInst_ud = "insert into inst_pharmacy (  
					INST_SNR, 
					FIELD_01,
					FIELD_02,
					FIELD_03,
					FIELD_04,
					FIELD_05,
					FIELD_06,
					FIELD_07,
					FIELD_01_SNR,
					FIELD_02_SNR,					
					FIELD_03_SNR,
					FIELD_04_SNR,
					FIELD_05_SNR,
					FIELD_06_SNR,					
					FIELD_07_SNR,
					FIELD_08_SNR,
					FIELD_09_SNR,
					FIELD_10_SNR,					
					SYNC,
					CREATION_TIMESTAMP,
					REC_STAT 
				) values (
					'".$idInst."', 
					'".$field01InstNueva."', 
					'".$field02InstNueva."', 
					'".$field03InstNueva."', 
					'".$field04InstNueva."', 
					'".$field05InstNueva."', 
					'".$field06InstNueva."', 
					'".$field07InstNueva."', 
					'".$field01InstNueva_snr."', 
					'".$field02InstNueva_snr."', 
					'".$field03InstNueva_snr."', 
					'".$field04InstNueva_snr."', 
					'".$field05InstNueva_snr."', 
					'".$field06InstNueva_snr."',
					'".$field07InstNueva_snr."',
					'".$field08InstNueva_snr."',
					'".$field09InstNueva_snr."',
					'".$field10InstNueva_snr."',
					0,
					getdate(), 
					0 
				)";
			}
			
			
				
			if(! sqlsrv_query($conn, $queryInst_ud)){
				echo $queryInst_ud."<br>";
			}
		}
		
		if(isset($_POST['PersonaNueva']) && $_POST['PersonaNueva'] == 'si'){
			$idInst = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idInst from INST where INST_SNR = '00000000-0000-0000-0000-000000000000'"))['idInst'];
			$nr = sqlsrv_fetch_array(sqlsrv_query($conn, "select max(NR)+1 as nr from INST"))['nr'];
			$queryInst = "insert into INST (
				INST_SNR,
				REC_STAT,
				NAME,
				TYPE_SNR,
				SUBTYPE_SNR,
				FORMAT_SNR,
				NR,
				INST_TYPE,
				INFO,
				CITY_SNR,
				STREET1,
				NUM_EXT,
				TEL1,
				TEL2,
				WEB,
				EMAIL1,
				CATEGORY_SNR,
				FRECVIS_SNR,
				SYNC,
				STATUS_SNR,
				BRANCH
			) values (
				'$idInst',
				0,
				'".$inst."',
				'".$tipoInst."',
				'".$subtipo."',				
				'".$formato."',
				'".$nr."',
				'".$tipoInstInt."',
				'".$comentarios."',
				'".$city."',
				'".$calle."',
				'".$numExt."',
				'".$tel1."',
				'".$tel2."',
				'".$web."',
				'".$email."',
				'".$categoria."',
				'".$frecuencia."',
				0,
				'".$estatus."',
				'".$sucursal."'
				)
				";
		}else{
			if($tipoUsuario == 4){
				if($tipoInstInt == 3  || $tipoInstInt == 5){
					//echo "aquiiiiiii<br>";
					//$idInst = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idInst from INST where INST_SNR = '00000000-0000-0000-0000-000000000000'"))['idInst'];
					if($tipoMovimiento == 'N'){
						$nr = sqlsrv_fetch_array(sqlsrv_query($conn, "select max(NR)+1 as nr from INST"))['nr'];
						$queryInst = "insert into INST (
							INST_SNR,
							REC_STAT,
							NAME,
							TYPE_SNR,
							SUBTYPE_SNR,
							FORMAT_SNR,
							NR,
							INST_TYPE,
							INFO,
							CITY_SNR,
							STREET1,
							NUM_EXT,
							TEL1,
							TEL2,
							WEB,
							EMAIL1,
							CATEGORY_SNR,
							FRECVIS_SNR,
							SYNC,
							STATUS_SNR,
							BRANCH
						) values (
							'$idInst',
							0,
							'".$inst."',
							'".$tipoInst."',
							'".$subtipo."',
							'".$formato."',
							'".$nr."',
							'".$tipoInstInt."',
							'".$comentarios."',
							'".$city."',
							'".$calle."',							
							'".$numExt."',	
							'".$tel1."',
							'".$tel2."',
							'".$web."',
							'".$email."',
							'".$categoria."',
							'".$frecuencia."',
							0,
							'".$estatus."',
							'".$sucursal."'
							)
							";
						if(! sqlsrv_query($conn, $queryInst)){
							echo $queryInst."<br>";
						}else{
							$qUserTerrit = "insert into user_territ (
								UTER_SNR,
								INST_SNR,
								USER_SNR,
								REC_STAT,
								SYNC,
								CREATION_TIMESTAMP) values (
								NEWID(),
								'".$idInst."',
								'".$idUsuario."',
								0,
								0,
								getdate()
								)";
							if(! sqlsrv_query($conn, $qUserTerrit)){
								echo $qUserTerrit."<br>";
							}
						}
					}
				}else{
					if($insertaApproval == 1){
						$queryInstApp = "insert into INST_APPROVAL (
							INST_APPROVAL_SNR,
							I_REC_STAT,
							REC_STAT,
							I_INST_SNR,
							I_NAME,
							I_TYPE_SNR,
							I_SUBTYPE_SNR,
							I_FORMAT_SNR,
							I_NR,
							I_INST_TYPE,
							I_INFO,
							I_CITY_SNR,
							I_STREET1,						
							I_NUM_EXT,
							I_TEL1,
							I_TEL2,
							I_WEB,
							I_EMAIL1,
							I_STATUS_SNR,
							I_CATEGORY_SNR,
							I_FRECVIS_SNR,
							I_MOVEMENT_TYPE,
							SYNC,
							CREATION_TIMESTAMP,
							I_BRANCH
						) values (
							'$idInstApproval',
							0,
							0,
							'".$idInst."',
							'".$inst."',
							'".$tipoInst."',
							'".$subtipo."',
							'".$formato."',
							'".$nr."',
							'".$tipoInstInt."',
							'".$comentarios."',
							'".$city."',
							'".$calle."',
							'".$numExt."',						
							'".$tel1."',
							'".$tel2."',
							'".$web."',
							'".$email."',
							'".$estatus."',
							'".$categoria."',
							'".$frecuencia."',
							'".$tipoMovimiento."',
							0,
							getdate(),
							'".$sucursal."'
							)
							";
						
						$queryApprovalStatus = "insert into APPROVAL_STATUS (
							APPROVAL_STATUS_SNR,
							TABLE_NR,
							RECORD_KEY,
							DATE_CHANGE,
							CHANGE_USER_SNR,
							APPROVED_DATE,
							APPROVED_USER_SNR,
							APPROVED_STATUS,
							REC_STAT,
							PERS_APPROVAL_SNR,
							PWORK_SNR,
							INST_APPROVAL_SNR,
							REJECT_REASON_SNR,
							RECORD_KEY_OLD,
							PWORK_OLD_SNR,
							SYNC
							)
							values (
							'$idApprovalStatus',
							492,
							'$idInst',
							getdate(),
							'$idUsuario',
							NULL,
							NULL,
							1,
							0,
							'00000000-0000-0000-0000-000000000000',
							'00000000-0000-0000-0000-000000000000',
							'$idInstApproval',
							'00000000-0000-0000-0000-000000000000',
							'00000000-0000-0000-0000-000000000000',
							'00000000-0000-0000-0000-000000000000',
							0
							)";
							
						if(! sqlsrv_query($conn, $queryInstApp)){
							echo "queryInstApp: ".$queryInstApp."<br>";
						}
						
						if(! sqlsrv_query($conn, $queryApprovalStatus)){
							echo "queryApprovalStatus: ".$queryApprovalStatus."<br>";
						}
					}
				}
			}else{///no es repre
				if($idInst != ''){//actualiza
					$queryInst = "update INST set 
						REC_STAT = 0,
						NAME = '".$inst."',
						TYPE_SNR = '".$tipoInst."',
						SUBTYPE_SNR = '".$subtipo."',
						FORMAT_SNR = '".$formato."',
						INST_TYPE = '".$tipoInstInt."',
						INFO = '".$comentarios."',
						CITY_SNR = '".$city."',
						STREET1 = '".$calle."',
						NUM_EXT = '".$numExt."',
						TEL1 = '".$tel1."',
						TEL2 = '".$tel2."',
						WEB = '".$web."',
						EMAIL1 = '".$email."',
						CATEGORY_SNR = '".$categoria."',
						FRECVIS_SNR = '".$frecuencia."',
						SYNC = 0,
						BRANCH = '".$sucursal."'						
						where INST_SNR = '$idInst' ";
				}else{
					
				}
			}
		}
		
		if(isset($_POST['PersonaNueva']) && $_POST['PersonaNueva'] == 'si'){
			if(sqlsrv_query($conn, $queryInst)){
				
				$qUserTerrit = "insert into user_territ (
					UTER_SNR,
					INST_SNR,
					USER_SNR,
					REC_STAT,
					SYNC,
					CREATION_TIMESTAMP) values (
					NEWID(),
					'".$idInst."',
					'".$idUsuario."',
					0,
					0,
					getdate()
					)";
				if(! sqlsrv_query($conn, $qUserTerrit)){
					echo $qUserTerrit."<br>";
				}
				
				$queryEdo = "select d.name as delegacion, STATE.NAME as estado, 
					bri.name as brick
					from CITY c
					inner join DISTRICT d on d.DISTR_SNR = c.DISTR_SNR
					inner join STATE on state.STATE_SNR = c.STATE_SNR
					inner join BRICK bri on bri.BRICK_SNR = c.BRICK_SNR
					where c.CITY_SNR = '".$city."'";
					
				//echo $queryEdo."<br>";
					
				$rsEstado = sqlsrv_fetch_array(sqlsrv_query($conn, $queryEdo));
				
				echo "<script>
					$('#hdnIdInstPersonaNuevo').val('".$idInst."');
					$('#hdnPersonaNueva').val('no');
					$('#txtNombreInstPersonaNuevo').val('".$inst."');
					$('#txtCalleInstPersonaNuevo').val('".$calle."');
					$('#txtNumExtInstPersonaNuevo').val('".$numExt."');
					$('#txtNumIntInstPersonaNuevo').val('');
					$('#txtCPInstPersonaNuevo').val('".$cp."');
					$('#txtColoniaInstPersonaNuevo').val('".$colonia."');
					$('#txtCiudadInstPersonaNuevo').val('".$rsEstado['delegacion']."');
					$('#txtEstadoInstPersonaNuevo').val('".$rsEstado['estado']."');
					$('#txtBrickInstPersonaNuevo').val('".$rsEstado['brick']."');
					$('#txtTelefonoInstPersonaNuevo').val('".$tel1."');
					$('#txtTelefono1InstPersonaNuevo').val('".$celular."');
					$('#txtEmailInstPersonaNuevo').val('".$email."');
					$('#txtSucursalInstNueva').val('".$sucursal."');
					$('#btnCerrarBuscarInst').click();
					$('#divInstitucion').hide();
					$('#divPersona').show();
					$('#divBusqueda').hide();
				</script>";
			}else{
				echo "<script>alertErrorGuardarRegistro();</script>";
				echo $queryInst."<br>";
			}
		}else{
			echo "<script>
				$('#divInstitucion').hide();
				$('#divCapa3').hide();
				notificationInstRegistro();
			</script>";
		}
	}
?>