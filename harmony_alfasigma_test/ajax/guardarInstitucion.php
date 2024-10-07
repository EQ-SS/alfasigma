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
		//$sucursal = strtoupper($_POST['sucursal']);
		$cp = $_POST['cp'];
		$colonia = strtoupper(utf8_decode($_POST['colonia']));
		$city = $_POST['city'];
		$tel1 = $_POST['tel1'];
		$tel2 = $_POST['tel2'];
		//$celular = $_POST['celular'];
		$email = $_POST['email'];
		$web = $_POST['web'];
		$comentarios = strtoupper(utf8_decode($_POST['comentarios']));
		$rutaAsignada = $_POST['rutaAsignada'];
		/*
		//$representantesUnidos = $_POST['representantesUnidos'];
		$nivelVentas = $_POST['nivelVentas'];
		$nombreComercialFarmacia = strtoupper($_POST['nombreComercialFarmacia']);
		$rotacion = $_POST['rotacion'];
		$tipoPaciente = $_POST['tipoPaciente'];
		$numEmpleados = $_POST['numEmpleados'];
		$numCtesxDia = $_POST['numCtesxDia'];
		$numMedFarm = $_POST['numMedFarm'];
		$accesibilidad = $_POST['accesibilidad'];
		$numCtes = $_POST['numCtes'];
		$recibeVendedores = $_POST['recibeVendedores'];
		$ventaGenericos = $_POST['ventaGenericos'];
		$tamanoFarmacia = $_POST['tamanoFarmacia'];
		$mayorista1 = $_POST['mayorista1'];
		$mayorista2 = $_POST['mayorista2'];
		$numAnaqueles = $_POST['numAnaqueles'];
		
		$numVisitasXciclo = $_POST['numVisitasXciclo'];
		$turnos = $_POST['turnos'];
		$trabajaInstPublica = $_POST['trabajaInstPublica'];
		$ubicacion = $_POST['ubicacion'];
		$numCamas = $_POST['numCamas'];
		$numQuirofano = $_POST['numQuirofano'];
		$numSalasExpulsion = $_POST['numSalasExpulsion'];
		$numCunas = $_POST['numCunas'];
		$numIncubadoras = $_POST['numIncubadoras'];
		$terapiaIntensiva = $_POST['terapiaIntensiva'];
		$unidadCuidadosIntensivos = $_POST['unidadCuidadosIntensivos'];
		$infectologia = $_POST['infectologia'];
		$laboratorio = $_POST['laboratorio'];
		$urgencias = $_POST['urgencias'];
		$rayosx = $_POST['rayosx'];
		$farmacia = $_POST['farmacia'];
		$botiquin = $_POST['botiquin'];
		$endoscopia = $_POST['endoscopia'];
		$consultaExterna = $_POST['consultaExterna'];
		$cirugiaAmbulatoria = $_POST['cirugiaAmbulatoria'];
		$scanner = $_POST['scanner'];
		$ultrasonido = $_POST['ultrasonido'];
		$dialisis = $_POST['dialisis'];
		$hemodialisis = $_POST['hemodialisis'];
		$resonanciaMagnetica = $_POST['resonanciaMagnetica'];
		*/
		$flonorm = $_POST['flonorm'];
		$vessel = $_POST['vessel'];
		$ateka = $_POST['ateka'];
		$zirfos = $_POST['zirfos'];
		$esoxx = $_POST['esoxx'];
		$mayoristas = $_POST['mayoristas'];
		$catAlfa=$_POST['catAlfa'];
		
		$tipoUsuario = $_POST['tipoUsuario'];
		
		$rsIdInstApproval = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idInstApproval from INST_APPROVAL where INST_APPROVAL_SNR = '00000000-0000-0000-0000-000000000000'"));
		$idInstApproval = $rsIdInstApproval['idInstApproval'];
		$rsNR = sqlsrv_fetch_array(sqlsrv_query($conn, "select max(I_NR)+1 as nr from INST_APPROVAL"));
		$nr = $rsNR['nr'];
		$rsIdApprovalSatus = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idApprovalStatus from APPROVAL_STATUS where INST_APPROVAL_SNR = '00000000-0000-0000-0000-000000000000'"));
		$idApprovalStatus = $rsIdApprovalSatus['idApprovalStatus'];
		
		$actualizaInst = 0;
		$insertaApproval = 0;
		
		if($idInst != ''){
			$queryBuscaInst = "select * from INST_PHARMACY WHERE INST_SNR = '".$idInst."'";
			$queryResult = sqlsrv_query($conn, $queryBuscaInst, array(), array( "Scrollable" => 'static' ));
			$cuenta = 0;
			while($registros = sqlsrv_fetch_array($queryResult)){
					$cuenta++;
			}
			
			if($cuenta > 0){
				$updateInstUd = "update INST_PHARMACY set REC_STAT = 0, SYNC = 0, CHANGED_TIMESTAMP = getdate(), FIELD_01_SNR = '".$mayoristas."',
				FIELD_02_SNR = '".$flonorm."', FIELD_03_SNR = '".$vessel."', FIELD_04_SNR = '".$ateka."', FIELD_05_SNR = '".$zirfos."', 
				FIELD_06_SNR = '".$esoxx."',FIELD_07_SNR='".$catAlfa."' where INST_SNR = '".$idInst."'";
				$executeActua = sqlsrv_query($conn, $updateInstUd);
				
				if( $executeActua === false) {
					
					$err = die( print_r( sqlsrv_errors(), true) );
					echo "<script> alert(".$err."); </script>";
					exit;
				}
			}else{
				if($tipoInst=="986B6229-F56F-4D8B-9B77-47FCDC072E87"){

				$queryGuardarInstUd = "insert into INST_PHARMACY (INST_SNR, REC_STAT, SYNC, CREATION_TIMESTAMP, CHANGED_TIMESTAMP, SYNC_TIMESTAMP, FIELD_01_SNR, FIELD_02_SNR,
							FIELD_03_SNR, FIELD_04_SNR, FIELD_05_SNR, FIELD_06_SNR,FIELD_07_SNR) values (
							'".$idInst."',
							0,
							0,
							getdate(),
							getdate(),
							getdate(),
							'".$mayoristas."',
							'".$flonorm."',
							'".$vessel."',
							'".$ateka."',
							'".$zirfos."',
							'".$esoxx."',
							'".$catAlfa."'
							)";
				$executeActua = sqlsrv_query($conn, $queryGuardarInstUd);
				if( $executeActua === false) {
					
					$err = die( print_r( sqlsrv_errors(), true) );
					echo "<script> alert(".$err."); </script>";
					exit;
				}
			}
			}
			
			if($tipoUsuario == 4){//es repre
				$tipoMovimiento = "C";
				echo "ESTO ES TO¡IPO :".$tipoInst."<br>";

				//tipo hospital
				if($tipoInst == '6B8CF7C2-CB9F-40EE-8478-AA671EE9A14D'){
					echo "entro";
					$qActualizaInst = "update INST set
				NAME = '".$inst."',
				TYPE_SNR = '".$tipoInst."',
				SUBTYPE_SNR = '".$subtipo."',
				FORMAT_SNR = '".$formato."',				
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
				SYNC = 0
				where INST_SNR = '".$idInst."' ";
				
				if(! sqlsrv_query($conn, $qActualizaInst)){
					echo $qActualizaInst."<br>";
				}
				echo "<script>
				$('#divInstitucion').hide();
				$('#divCapa3').hide();
				notificationInstRegistro();
				</script>";
				return true;

				}else{
				echo "tambien entro a aprobacion";
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
				
				$queryInstApproval = "update INST set SYNC = 0 ";
				
				if($queryAnt['TYPE_SNR'] != $tipoInst){
					$idCampo = array_search ( "TYPE_SNR" , $campos );
					if($approval[$idCampo] == 1){
						$insertaApproval = 1;
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(), '".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab.",'00000000-0000-0000-0000-000000000000','".$tipoInst."',0,0)";
						if(! sqlsrv_query($conn, $insertaChange)){
							echo 'TYPE_SNR: '.$insertaChange."<br><br>";
						}
					}else{
						$queryInstApproval .= ",PERSTYPE_SNR = '$tipoPersona' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['INST_TYPE'] != $tipoInstInt){
					$idCampo = array_search ( "INST_TYPE" , $campos );
					if($approval[$idCampo] == 1){
						$insertaApproval = 1;
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(), '".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab.",'00000000-0000-0000-0000-000000000000','".$tipoInstInt."',0,0)";
						if(! sqlsrv_query($conn, $insertaChange)){
							echo 'INST_TYPE: '.$insertaChange."<br><br>";
						}
					}else{
						$queryInstApproval .= ",INST_TYPE = '$tipoInstInt' ";
						$actualizaPerson = 1;
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
						$queryInstApproval .= ",SUBTYPE_SNR = '$subtipo' ";
						$actualizaPerson = 1;
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
						$queryInstApproval .= ",FORMAT_SNR = '$formato' ";
						$actualizaPerson = 1;
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
						$queryInstApproval .= ",CATEGORY_SNR = '$categoria' ";
						$actualizaPerson = 1;
					}
				}
				
				if($queryAnt['NAME'] != $inst){
					$idCampo = array_search ( "NAME" , $campos );
					if($approval[$idCampo] == 1){
						$insertaApproval = 1;
						$insertaChange = "insert into APPROVAL_CHANGES values (NEWID(), '".$idApprovalStatus."', ".$rcol[$idCampo].",".$rtab.",'00000000-0000-0000-0000-000000000000','".$inst."',0,0)";
						if(! sqlsrv_query($conn, $insertaChange)){
							echo "name: ".$insertaChange."<br><br>";
						}
					}else{
						$queryInstApproval .= ",NAME = '$inst' ";
						$actualizaPerson = 1;
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
						$queryInstApproval .= ",STREET1 = '$calle' ";
						$actualizaPerson = 1;
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
						$queryInstApproval .= ",NUM_EXT = '$numExt' ";
						$actualizaPerson = 1;
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
						$queryInstApproval .= ",CITY_SNR = '$city' ";
						$actualizaPerson = 1;
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
						$queryInstApproval .= ",TEL1 = '$tel1' ";
						$actualizaPerson = 1;
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
						$queryInstApproval .= ",TEL2 = '$tel2' ";
						$actualizaPerson = 1;
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
						$queryInstApproval .= ",EMAIL1 = '$email' ";
						$actualizaPerson = 1;
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
						$queryInstApproval .= ",WEB = '$web' ";
						$actualizaPerson = 1;
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
						$queryInstApproval .= ",INFO = '$comentarios' ";
						$actualizaPerson = 1;
					}
				}
				
				$queryInstApproval .= "where INST_SNR = '$idInst'";
				
				if(! sqlsrv_query($conn, $queryInstApproval)){
					echo "queryInstApproval: ".$queryInstApproval;
				}
			}
			}else{//no es repre
				$qActualizaInst = "update INST set
				NAME = '".$inst."',
				TYPE_SNR = '".$tipoInst."',
				SUBTYPE_SNR = '".$subtipo."',
				FORMAT_SNR = '".$formato."',				
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
				SYNC = 0
				where INST_SNR = '".$idInst."' ";
				
				if(! sqlsrv_query($conn, $qActualizaInst)){
					echo $qActualizaInst."<br>";
				}
			}
		}else{
			$rsIdInst = sqlsrv_fetch_array(sqlsrv_query($conn, "select NEWID() as idInst from INST where INST_SNR = '00000000-0000-0000-0000-000000000000'"));
			$idInst = $rsIdInst['idInst'];
			$tipoMovimiento = "N";
			$idPwork = '00000000-0000-0000-0000-000000000000';
			
			$nr = sqlsrv_fetch_array(sqlsrv_query($conn, "select max(NR)+1 as nr from INST"))['nr'];
			$queryInsertaInst = "insert into inst ( 
				INST_SNR,
				NAME,
				TYPE_SNR,
				SUBTYPE_SNR,
				FORMAT_SNR,
				INST_TYPE,
				INFO,
				CITY_SNR,
				STREET1,
				NUM_EXT,
				TEL1,
				TEL2,
				WEB,
				EMAIL1,
				STATUS_SNR,
				CATEGORY_SNR,
				REC_STAT,
				SYNC,
				CREATION_TIMESTAMP,
				NR
			) values ( 
				'$idInst',
				'$inst',
				'$tipoInst',
				'$subtipo',
				'$formato',
				'$tipoInstInt',
				'$comentarios',
				'$city',
				'$calle',				
				'$numExt',
				'$tel1',
				'$tel2',
				'$web',
				'$email',
				'$estatus',
				'$categoria',
				0,
				0,
				getdate(),
				'$nr'
			)";
					
			//echo $queryInsertaInst;
			
			if(! sqlsrv_query($conn, $queryInsertaInst)){
				echo "Error: queryInsertaInst ::: ".$queryInsertaInst."<br><br>";
			}else{
				$queryGuardarInstUd = "insert into INST_PHARMACY (INST_SNR, REC_STAT, SYNC, CREATION_TIMESTAMP, CHANGED_TIMESTAMP, SYNC_TIMESTAMP, FIELD_01_SNR, FIELD_02_SNR,
							FIELD_03_SNR, FIELD_04_SNR, FIELD_05_SNR, FIELD_06_SNR, FIELD_07_SNR) values (
							'".$idInst."',
							0,
							0,
							getdate(),
							getdate(),
							getdate(),
							'".$mayoristas."',
							'".$flonorm."',
							'".$vessel."',
							'".$ateka."',
							'".$zirfos."',
							'".$esoxx."',
							'".$catAlfa."'
							)";
				$executeActua = sqlsrv_query($conn, $queryGuardarInstUd);
				if(!sqlsrv_query($conn, $queryGuardarInstUd)){
					echo "NO ENTRO";
				}
			}
					
			//revisa user territ
			$queryUT = "select * from user_territ where user_snr = '".$rutaAsignada."' and inst_snr = '".$idInst."'";
			$rsUT = sqlsrv_query($conn, $queryUT, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			$actualizaUT = "";
			if(sqlsrv_num_rows($rsUT) > 0){
				$idUT = sqlsrv_fetch_array($rsUT)['UTER_SNR'];
				if($rsUT['REC_STAT'] != 0){
					$actualizaUT = "update user_territ SET REC_STAT = 0 WHERE UTER_SNR = '$idUT'";
					if(! sqlsrv_query($conn, $actualizaUT)){
						echo "Error: actualizaUT ::: ".$actualizaUT."<br><br>";
					}
				}
			}else{
				$actualizaUT = "insert into user_territ (
						UTER_SNR,
						INST_SNR,
						USER_SNR,
						REC_STAT,
						SYNC
					) values (
						NEWID(),
						'$idInst',
						'$rutaAsignada',
						0,
						0
					)";
				if(! sqlsrv_query($conn, $actualizaUT)){
					echo "Error: actualizaUT ::: ".$actualizaUT."<br><br>";
				}
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
				SYNC
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
				0
				)
				";
		}else{
			if($tipoUsuario == 4){
				if($tipoInst == 3){
					//echo "aquiiiiiii<br>";
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
						/*FAX,*/
						WEB,
						EMAIL1,
						CATEGORY_SNR,
						FRECVIS_SNR,
						SYNC
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
						/*'".$celular."',*/
						'".$web."',
						'".$email."',
						'".$categoria."',
						'".$frecuencia."',
						0
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
				}else{
					
					$queryInstApp = "insert into INST_APPROVAL (
						INST_APPROVAL_SNR,
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
						CREATION_TIMESTAMP 
					) values (
						'$idInstApproval',
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
						getdate()
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
						SYNC = 0 
						where INST_SNR = '$idInst' ";
				}else{//inserta
					
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
					$('#txtEmailInstPersonaNuevo').val('".$email."');
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