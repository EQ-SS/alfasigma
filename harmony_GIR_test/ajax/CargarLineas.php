
<?php

	include "../conexion.php";
	
	$tipoUsuario=2;

	$sltLinea=$_POST['sltLinea'];
	$sltEstado=$_POST['sltEstado'];
	$sltCobDiaAnterior=$_POST['sltCobDiaAnterior']; // 1-rojo , 2-naranja , 3-amarillo , 4-verde
	$sltCobDiaria=$_POST['sltCobDiaria'];
	$sltCobCiclo=$_POST['sltCobCiclo'];
	$sltRepre=$_POST['sltRepre'];

	$banderaH=0;

	if(	$sltCobDiaria !='0' && $sltCobDiaria !='5'){
		$banderaH=$sltCobDiaria;
	}else if($sltCobDiaAnterior !='0' && $sltCobDiaAnterior !='5'){
		$banderaH=$sltCobDiaAnterior;
	}else if($sltCobCiclo !='0' && $sltCobCiclo !='5'){
		$banderaH=$sltCobCiclo;
	}

	$color= array();
	
	$arrDiaDeCiclo = sqlsrv_fetch_array(sqlsrv_query($conn,"SELECT * FROM CYCLE_DETAILS WHERE CYCLE_SNR IN (SELECT CYCLE_SNR FROM CYCLES WHERE  '".date("Y-m-d")."' BETWEEN START_DATE AND FINISH_DATE) AND CAST(GETDATE() AS DATE)=CYCLE_DETAILS.C_DATE "));
	$diaDelCiclo = $arrDiaDeCiclo['C_DAY'];
	$nombreDiaCiclo=$arrDiaDeCiclo['C_DAY_NAME'];

	$v1=0;

	if($sltCobDiaAnterior !='0'){

		if($nombreDiaCiclo=="LUNES"){
			$v1=-3;
		}else if($nombreDiaCiclo=="DOMINGO"){
			$v1=-2;
		}else{
			$v1=-1;
		}
	}

	$grupo="";
	$select2="";
	$fecha="";
	$condicion="";
	$fechaTft="";
	$lineaOEstado="";
	$grupoLineaOEst="";

	$grupo="GROUP BY LINEA.SORT_NUM,U.CLINE_SNR,LINEA.NAME_SHORT,LINEA.DAILY_CONTACTS,EDO.STATE_SNR,EDO.NAME,U.USER_SNR,PLW.PWORK_SNR,U.FNAME,U.LNAME,U.MOTHERS_LNAME,U.USER_NR ";

	$lineacontactos="";

	if($sltRepre !="0"){
		$condicion.=" and U.USER_SNR='".$sltRepre."' ";
	}

	if($sltLinea=='0' || $sltLinea !='' ){

		//EN ESTE HICE EL CAMBIO
		
		if($sltRepre!='0'){
			$lineaOEstado=" ESTADO AS LINEA,";
			$grupoLineaOEst=" ,TOTAL.ESTADO "; 

		}else{
			$lineaOEstado=" TOTAL.LINEA,";
			$grupoLineaOEst=""; 
		}

		$select2=" SELECT  ";
		$select2.=$lineaOEstado;
		$select2.="
		SUM(TOTAL.VISITAS) VISITAS, 
		COUNT(DISTINCT TOTAL.REPRES_VIS) REPRES_VIS,
		TOTAL.REPRES_TOTAL,
		TOTAL.DAILY_CONTACTS, 
		ISNULL((SELECT COUNT(DISTINCT CAST(USER_SNR AS VARCHAR(40)) + CAST(CAST(VISIT_DATE AS DATE) AS VARCHAR(10)) ) 
		FROM TOTAL_DIAS 
		WHERE TOTAL_DIAS.USER_SNR=TOTAL.USER_SNR 
		AND TOTAL_DIAS.DAILY_CONTACTS=TOTAL.DAILY_CONTACTS 
		GROUP BY NAME_SHORT,DAILY_CONTACTS),0) DIAS_VIS, ";

		if($sltCobCiclo !=0){
			$select2.=" (TOTAL.DAILY_CONTACTS * COUNT(DISTINCT TOTAL.USER_SNR)) * '".$diaDelCiclo."' - COUNT(DISTINCT TOTAL.contactos) * DAILY_CONTACTS  CONTACTS,";
			
		}else{
			$select2.=" TOTAL.DAILY_CONTACTS * COUNT(DISTINCT TOTAL.USER_SNR) - COUNT(DISTINCT TOTAL.contactos) * DAILY_CONTACTS  CONTACTS,";
		}

		$select2.=" ISNULL(TOTAL.contactos,'') TFT, 
		USER_SNR AS ID 
		FROM TOTAL
		GROUP BY TOTAL.LINEA_ORDEN,TOTAL.LINEA,TOTAL.DAILY_CONTACTS,TOTAL.REPRES_TOTAL,TOTAL.USER_SNR,TOTAL.contactos,USER_SNR ";
		$select2.=$grupoLineaOEst;
		$select2.="ORDER BY TOTAL.LINEA_ORDEN,TOTAL.LINEA,contactos";

	}else{

		$select2=" SELECT  
		TOTAL.LINEA,
		SUM(TOTAL.VISITAS) VISITAS, 
		COUNT(DISTINCT TOTAL.REPRES_VIS) REPRES_VIS,
		COUNT(DISTINCT TOTAL.USER_SNR) REPRES_TOTAL,  
		TOTAL.DAILY_CONTACTS, 
		ISNULL((SELECT COUNT(DISTINCT CAST(USER_SNR AS VARCHAR(40)) + CAST(CAST(VISIT_DATE AS DATE) AS VARCHAR(10)) ) 
		FROM TOTAL_DIAS 
		WHERE TOTAL_DIAS.NAME_SHORT=TOTAL.LINEA 
		AND TOTAL_DIAS.DAILY_CONTACTS=TOTAL.DAILY_CONTACTS 
		GROUP BY NAME_SHORT,DAILY_CONTACTS),0) DIAS_VIS, ";
		if($sltCobCiclo !=0){
			$select2.=" (TOTAL.DAILY_CONTACTS * COUNT(DISTINCT TOTAL.USER_SNR)) * '".$diaDelCiclo."' - COUNT(DISTINCT TOTAL.contactos) * DAILY_CONTACTS  CONTACTS,";
			
		}else{
			$select2.="TOTAL.DAILY_CONTACTS * COUNT(DISTINCT TOTAL.USER_SNR) - COUNT(DISTINCT TOTAL.contactos) * DAILY_CONTACTS  CONTACTS,";
		}
		$select2.=" COUNT(DISTINCT TOTAL.contactos) TFT 
		FROM TOTAL 
		GROUP BY TOTAL.LINEA_ORDEN,TOTAL.LINEA,TOTAL.DAILY_CONTACTS
		ORDER BY TOTAL.LINEA_ORDEN,TOTAL.LINEA";

	}

	if($sltLinea != "" && $sltLinea !='0'){

		$condicion.="  and LINEA.CLINE_SNR = '".$sltLinea."' ";
		
		$lineacontactos="(SELECT DISTINCT CD.NAME FROM DAY_REPORT DR, DAY_REPORT_CODE DRC, CODELIST CD WHERE DR.USER_SNR=U.USER_SNR AND DR.REC_STAT=0 AND DR.DAYREPORT_SNR=DRC.DAYREPORT_SNR AND DRC.REC_STAT=0 AND DRC.DAY_CODE_SNR=CD.CLIST_SNR AND CD.REC_STAT=0 AND ";

	}else{

		if($sltRepre !='0'){

			$lineacontactos="(SELECT DISTINCT CD.NAME FROM DAY_REPORT DR, DAY_REPORT_CODE DRC, CODELIST CD WHERE DR.USER_SNR=U.USER_SNR AND DR.REC_STAT=0 AND DR.DAYREPORT_SNR=DRC.DAYREPORT_SNR AND DRC.REC_STAT=0 AND DRC.DAY_CODE_SNR=CD.CLIST_SNR AND CD.REC_STAT=0 AND ";

		}else{
			$lineacontactos="(SELECT DISTINCT DR.USER_SNR FROM DAY_REPORT DR WHERE DR.USER_SNR=U.USER_SNR AND ";
		}

	}


	if($sltEstado !='0' ){
		$condicion.=" and EDO.STATE_SNR='".$sltEstado."' ";
		
	}


	if($sltCobCiclo !=0){

		$fechaTft="DR.DATE BETWEEN (SELECT CAST(START_DATE AS date) FROM CYCLES WHERE CAST(START_DATE AS date) <= CAST(GETDATE() AS DATE) AND CAST(FINISH_DATE AS date) >= CAST(GETDATE() AS DATE)) 
		AND (SELECT CAST(FINISH_DATE AS date) FROM CYCLES WHERE CAST(START_DATE AS date) <= CAST(GETDATE() AS DATE) AND CAST(FINISH_DATE AS date) >= CAST(GETDATE() AS DATE) )  ";

		
		$fecha=" VISIT_DATE BETWEEN (SELECT CAST(START_DATE AS date) FROM CYCLES WHERE CAST(START_DATE AS date) <= CAST(GETDATE() AS DATE) 
		AND CAST(FINISH_DATE AS date) >= CAST(GETDATE() AS DATE)) AND (SELECT CAST(FINISH_DATE AS date) FROM CYCLES WHERE CAST(START_DATE AS date) <= CAST(GETDATE() AS DATE) 
		AND CAST(FINISH_DATE AS date) >= CAST(GETDATE() AS DATE)) ";

	}else{

		$fechaTft=" DR.DATE = CAST(dateadd(DAY,".$v1.", GETDATE()) AS DATE) ";

		$fecha=" VISIT_DATE = CAST(dateadd(DAY,".$v1.", GETDATE()) AS DATE)";
	}



	$queryGlobalMapa="WITH TOTAL AS ( 
		SELECT 
		LINEA.SORT_NUM LINEA_ORDEN,
		U.CLINE_SNR,
		LINEA.NAME_SHORT LINEA,
		LINEA.DAILY_CONTACTS,
		U.USER_SNR,
		EDO.STATE_SNR,
		EDO.NAME ESTADO,
		
		(SELECT COUNT(DISTINCT VP1.PERS_SNR) FROM VISITPERS VP1 
		WHERE VP1.USER_SNR=U.USER_SNR AND VP1.PWORK_SNR=PLW.PWORK_SNR AND VP1.REC_STAT=0 AND VISIT_CODE_SNR NOT in ('73253003-55D7-4B25-929F-0F4A452E6F6B','B7606EC6-498C-4075-928D-8A5ECBE8737A') 
		AND "; $queryGlobalMapa.=$fecha; $queryGlobalMapa.="  
		) VISITAS, 
		
		(SELECT DISTINCT VP1.USER_SNR FROM VISITPERS VP1 WHERE VP1.USER_SNR=U.USER_SNR 
		AND VP1.PWORK_SNR=PLW.PWORK_SNR 
		AND VP1.REC_STAT=0 AND VISIT_CODE_SNR NOT in ('73253003-55D7-4B25-929F-0F4A452E6F6B','B7606EC6-498C-4075-928D-8A5ECBE8737A') 
		AND "; $queryGlobalMapa.=$fecha; $queryGlobalMapa.="
		) REPRES_VIS 
		
		FROM VISITPERS VP 
		INNER JOIN PERSLOCWORK PLW ON PLW.PWORK_SNR=VP.PWORK_SNR 
		AND PLW.REC_STAT=0 
		INNER JOIN INST I ON I.INST_SNR=PLW.INST_SNR 
		INNER JOIN CITY CY ON CY.CITY_SNR=I.CITY_SNR AND CY.REC_STAT=0 
		INNER JOIN DISTRICT DTO ON CY.DISTR_SNR=DTO.DISTR_SNR AND DTO.REC_STAT=0 
		INNER JOIN STATE EDO ON EDO.STATE_SNR=DTO.STATE_SNR AND EDO.REC_STAT=0 
		INNER JOIN USERS U ON U.USER_SNR=VP.USER_SNR AND U.REC_STAT=0 AND U.STATUS=1 AND U.USER_TYPE=4 
		INNER JOIN COMPLINE LINEA ON U.CLINE_SNR=LINEA.CLINE_SNR WHERE VP.REC_STAT=0 AND EDO.NAME<>'' AND LINEA.MAP=1 ";
		$queryGlobalMapa.=$condicion;
		$queryGlobalMapa.="
		GROUP BY LINEA.SORT_NUM,U.CLINE_SNR,LINEA.NAME_SHORT,LINEA.DAILY_CONTACTS,EDO.STATE_SNR,EDO.NAME,U.USER_SNR,PLW.PWORK_SNR 
		), 
		
		TOTAL_DIAS AS ( 
		SELECT VISIT_DATE,
		VP1.USER_SNR,
		EDO.NAME AS ESTADO,
		LINEA.DAILY_CONTACTS 
		FROM VISITPERS VP1 
		INNER JOIN PERSLOCWORK PLW ON PLW.PWORK_SNR=VP1.PWORK_SNR 
		AND PLW.REC_STAT=0 
		INNER JOIN INST I ON I.INST_SNR=PLW.INST_SNR 
		INNER JOIN CITY CY ON CY.CITY_SNR=I.CITY_SNR AND CY.REC_STAT=0 
		INNER JOIN DISTRICT DTO ON CY.DISTR_SNR=DTO.DISTR_SNR AND DTO.REC_STAT=0 
		INNER JOIN STATE EDO ON EDO.STATE_SNR=DTO.STATE_SNR AND EDO.REC_STAT=0 
		INNER JOIN USERS U ON U.USER_SNR=VP1.USER_SNR AND U.REC_STAT=0 AND U.STATUS=1 AND U.USER_TYPE=4 
		INNER JOIN COMPLINE LINEA ON U.CLINE_SNR=LINEA.CLINE_SNR 
		WHERE VP1.REC_STAT=0 AND EDO.NAME<>'' AND LINEA.MAP=1 ";
		$queryGlobalMapa.=$condicion;
		$queryGlobalMapa.=" 
		AND VP1.USER_SNR=U.USER_SNR AND VP1.PWORK_SNR=PLW.PWORK_SNR AND VP1.REC_STAT=0 AND VISIT_CODE_SNR NOT in ('73253003-55D7-4B25-929F-0F4A452E6F6B','B7606EC6-498C-4075-928D-8A5ECBE8737A') 
		AND "; 
		$queryGlobalMapa.=$fecha;
		$queryGlobalMapa.="
		GROUP BY VISIT_DATE,VP1.USER_SNR,EDO.NAME,LINEA.DAILY_CONTACTS 
		) 
		SELECT TOTAL.ESTADO, 
		SUM(TOTAL.VISITAS) VISITAS, 
		COUNT(DISTINCT TOTAL.REPRES_VIS) REPRES_VIS, 
		TOTAL.DAILY_CONTACTS, 
		ISNULL((SELECT COUNT(DISTINCT CAST(USER_SNR AS VARCHAR(40)) + CAST(CAST(VISIT_DATE AS DATE) AS VARCHAR(10)) ) 
		FROM TOTAL_DIAS 
		WHERE TOTAL_DIAS.ESTADO=TOTAL.ESTADO 
		AND TOTAL_DIAS.DAILY_CONTACTS=TOTAL.DAILY_CONTACTS 
		GROUP BY ESTADO,DAILY_CONTACTS),0) DIAS_VIS, 
		ISNULL((SELECT COUNT(DISTINCT CAST(USER_SNR AS VARCHAR(40)) + CAST(CAST(VISIT_DATE AS DATE) AS VARCHAR(10)) ) * DAILY_CONTACTS 
		FROM TOTAL_DIAS 
		WHERE TOTAL_DIAS.ESTADO=TOTAL.ESTADO 
		AND TOTAL_DIAS.DAILY_CONTACTS=TOTAL.DAILY_CONTACTS 
		GROUP BY ESTADO,DAILY_CONTACTS),0) CONTACTS ";
		
		
		$queryGlobalMapa.=" FROM TOTAL 
		GROUP BY TOTAL.ESTADO,TOTAL.DAILY_CONTACTS 
		ORDER BY TOTAL.ESTADO ";

	



		

		

		//EJECUTA QUERY MAPA Y TABLA



		
		$rsPintaEstados=sqlsrv_query($conn,$queryGlobalMapa, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		if($rsPintaEstados === false){
			if( ($errors = sqlsrv_errors() ) != null) {
				foreach( $errors as $error ) {
					echo "<script>";
					echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
					echo "code: ".$error[ 'code']."<br />";
					echo "message: ".$error[ 'message']."<br />";

					echo $queryGlobalMapa;

					echo "</script>";
				}
			}
		}
		$rows=sqlsrv_num_rows($rsPintaEstados);
		$rsEstados= sqlsrv_query($conn, $queryGlobalMapa);

		//END EJECUTA QUERY MAPA Y TABLA 




		//CARGA MAPA ------------------


			$arrEstados = array('AGUASCALIENTES' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0, 'color' => ''),
	'BAJA CALIFORNIA' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'BAJA CALIFORNIA SUR' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'CAMPECHE' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'CHIAPAS' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'CHIHUAHUA' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'CIUDAD DE MEXICO' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'COAHUILA' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'COLIMA' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'DURANGO' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'GUANAJUATO' => array('visitas' => 0,'contactos' =>0 ,'porcentaje' => 0,'color' => ''),
	'GUERRERO' => array('visitas' => 0,'contactos' =>0 ,'porcentaje' => 0,'color' => ''),
	'HIDALGO' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'JALISCO' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'MEXICO' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'MICHOACAN' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'MORELOS' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'NAYARIT' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'NUEVO LEON' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'OAXACA' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'PUEBLA' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'QUERETARO' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'QUINTANA ROO' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'SAN LUIS POTOSI' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'SINALOA' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'SONORA' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'TABASCO' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'TAMAULIPAS' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'TLAXCALA' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'VERACRUZ' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'YUCATAN' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''),
	'ZACATECAS' => array('visitas' => 0,'contactos' => 0,'porcentaje' => 0,'color' => ''));

	$arrEstadosRepre=array();
	$i=0;

	$porcRepre=0;

	while($reg = sqlsrv_fetch_array($rsPintaEstados)){
		$arrEstados[$reg ['ESTADO']]['visitas'] += $reg ['VISITAS'];
		$arrEstados[$reg ['ESTADO']]['contactos'] += $reg ['CONTACTS'];


		if($sltRepre !='0' && $banderaH >0 && $banderaH<5){

			if($reg['CONTACTS'] > 0){
				$porcRepre=($reg['VISITAS']/$reg['CONTACTS']) *100;
			}else{
				$porcRepre=0;
			}

			if($banderaH==1){

				if($porcRepre <= 50){
					$arrEstadosRepre[$i]=$reg['ESTADO'];
				}

			}
			if($banderaH==2){
				if($porcRepre > 50 && $porcRepre <= 77){

					$arrEstadosRepre[$i]=$reg['ESTADO'];
				}

			}

			if($banderaH==3){
				if($porcRepre > 77 && $porcRepre < 95){

					$arrEstadosRepre[$i]=$reg['ESTADO'];
				}

			}

			if($banderaH==4){
				if($porcRepre >= 95){

					$arrEstadosRepre[$i]=$reg['ESTADO'];
				}

			}
		}else{
			$arrEstadosRepre[$i]=$reg['ESTADO'];
		}

			
	
			
	
			
			


		


	
		$i++;
	
	}



	

	$estadosColores=array();



	foreach($arrEstados as $clave => $valores){

		if($arrEstados[$clave]['contactos'] > 0){
			$arrEstados[$clave]['porcentaje']=($arrEstados[$clave]['visitas'] / $arrEstados[$clave]['contactos']) * 100;

		}else{
			$arrEstados[$clave]['porcentaje']=0;
		}


		


		//rojo
		if($arrEstados[$clave]['porcentaje'] <= 50 ){
		 
			$arrEstados[$clave]['color']="red"; 

			if($banderaH==1){
				$estadosColores[]=$clave;
				
			}

			
		}
		//naranja
	
		if($arrEstados[$clave]['porcentaje'] > 50 && $arrEstados[$clave]['porcentaje'] <=  77){
			$arrEstados[$clave]['color']="orange";

			if($banderaH==2){
				$estadosColores[]=$clave;
				
			}
	
		}
		//amarillo
	
		if($arrEstados[$clave]['porcentaje'] > 77 && $arrEstados[$clave]['porcentaje'] < 95 ){
	
			$arrEstados[$clave]['color']="yellow";

			if($banderaH==3){
				$estadosColores[]=$clave;
				
			}
	
		}
		//verde
		if($arrEstados[$clave]['porcentaje'] >= 95){
			$arrEstados[$clave]['color']="green";

			if($banderaH==4){
				$estadosColores[]=$clave;
				
			}
	
		}





	}

	//echo "<br><br> arreGLONEW<br><br>";
	//print_r($estadosColores);

//echo "<br><br> arraeglo estados <br><br>";
	



	


	if($sltRepre !='0' && $banderaH==0  ){
		

		$i=0;
		foreach($arrEstados as $clave => $valores){

				if($arrEstadosRepre[$i]==$clave){
					$i++;
				}else{
					unset($arrEstados[$clave]);
				}
		
			
		}
		
		//print_r($arrEstados);
		
	}
	





	if($sltEstado !='0' && $banderaH >= 0 ){

		$arrNomEst = sqlsrv_fetch_array(sqlsrv_query($conn,"SELECT NAME FROM STATE WHERE STATE_SNR='".$sltEstado."'"));
		$nombreEstU = $arrNomEst['NAME'];

		foreach($arrEstados as $clave => $valores){
			if($nombreEstU !=$clave ){
				unset($arrEstados[$clave]);
			}
		}

		$i=0;
		foreach($estadosColores as $clave => $valores){
			if($valores !=$nombreEstU)
			{
				unset($estadosColores[$i]);
			}
			$i++;
		}




	

		

		




	}

	//echo "esto es  arreglo est col: <br><br>";
	//print_r($estadosColores);

	//echo "Esto es antes de mandar : <br>";
	//print_r($arrEstadosRepre);

	if($sltRepre !='0' && $banderaH >0 && $banderaH < 5  ){
		

		$i=0;
		foreach($arrEstados as $clave => $valores){

				if($arrEstadosRepre[$i]==$clave){
					$i++;
				}else{
					unset($arrEstados[$clave]);
				}
		
			
		}
		
		//print_r($arrEstados);
		
	}

	//echo "Esto es despues de procesar : <br>";




	//print_r($arrEstados);

	



echo "<br>";




echo "<script>limpia();</script>";

$colorPinta=array('','red','orange','yellow','green');


if($banderaH==5 || $banderaH==0 ){
	

	foreach($arrEstados as $clave => $valores){

		if($clave=="AGUASCALIENTES"){
			echo "<script>pintaAguascalientes('".$valores['color']."',LimiteAguascalientes,miMapa);</script>";
		}
		
		if($clave=="BAJA CALIFORNIA"){
			echo "<script>pintaBajaCalifornia('".$valores['color']."',LimiteBajCalifornia,miMapa);</script>";
		}
		
		if($clave=="BAJA CALIFORNIA SUR"){
			echo "<script>pintaBajaCaliforniaSur('".$valores['color']."',LimiteBajCaliforniaSur,miMapa);</script>";
		}
		
		if($clave=="CAMPECHE"){
			echo "<script>pintaCampeche('".$valores['color']."',LimiteCampeche,miMapa);</script>";
		}
		
		if($clave=="CHIAPAS"){
			echo "<script>pintaChiapas('".$valores['color']."',LimiteChiapas,miMapa);</script>";
		}
		
		if($clave=="CHIHUAHUA"){
			echo "<script>pintaChihuahua('".$valores['color']."',LimiteChihuahua,miMapa);</script>";
		}
		
		if($clave=="CIUDAD DE MEXICO"){
			echo "<script>pintaDistritoFederal('".$valores['color']."',LimiteDistritoFederal,miMapa);</script>";
		}
		
		if($clave=="COAHUILA"){
			echo "<script>pintaCoahuila('".$valores['color']."',LimiteCoahuila,miMapa);</script>";
		}
		
		if($clave=="COLIMA"){
			echo "<script>pintaColima('".$valores['color']."',LimiteColima,miMapa);</script>";
		}
		
		if($clave=="DURANGO"){
			echo "<script>pintaDurango('".$valores['color']."',LimiteDurango,miMapa);</script>";
		}
		
		if($clave=="GUANAJUATO"){
			echo "<script>pintaGuanajuato('".$valores['color']."',LimiteGuanajuato,miMapa);</script>";
		}
		
		if($clave=="GUERRERO"){
			echo "<script>pintaGuerrero('".$valores['color']."',LimiteGuerrero,miMapa);</script>";
		}
		
		if($clave=="HIDALGO"){
			echo "<script>pintaHidalgo('".$valores['color']."',LimiteHidalgo,miMapa);</script>";
		}
		
		if($clave=="JALISCO"){
			echo "<script>pintaJalisco('".$valores['color']."',LimiteJalisco,miMapa);</script>";
		}
		
		if($clave=="MEXICO"){
			echo "<script>pintaEstadoDeMexico('".$valores['color']."',LimiteEstadoDeMexico,miMapa);</script>";
		}
		
		if($clave=="MICHOACAN"){
			echo "<script>pintaMichoacan('".$valores['color']."',LimiteMichoacan,miMapa);</script>";
		}
		
		if($clave=="MORELOS"){
			echo "<script>pintaMorelos('".$valores['color']."',LimiteMorelos,miMapa);</script>";
		}
		
		if($clave=="NAYARIT"){
			echo "<script>pintaNayarit('".$valores['color']."',LimiteNayarit,miMapa);</script>";
		}
		
		if($clave=="NUEVO LEON"){
			echo "<script>pintaNuevoLeon('".$valores['color']."',LimiteNuevoLeon,miMapa);</script>";
		}
		
		if($clave=="OAXACA"){
			echo "<script>pintaOaxaca('".$valores['color']."',LimiteOaxaca,miMapa);</script>";
		}
		
		if($clave=="PUEBLA"){
			echo "<script>pintaPuebla('".$valores['color']."',LimitePuebla,miMapa);</script>";
		}
		
		if($clave=="QUERETARO"){
			echo "<script>pintaQueretaro('".$valores['color']."',LimiteQueretaro,miMapa);</script>";
		}
		
		if($clave=="QUINTANA ROO"){
			echo "<script>pintaQuintanaRoo('".$valores['color']."',LimiteQuintanaRoo,miMapa);</script>";
		}
		
		if($clave=="SAN LUIS POTOSI"){
			echo "<script>pintaSanLuisPotosi('".$valores['color']."',LimiteSanLuisPotosi,miMapa);</script>";
		}
		
		if($clave=="SINALOA"){
			echo "<script>pintaSinaloa('".$valores['color']."',LimiteSinaloa,miMapa);</script>";
		}
		
		if($clave=="SONORA"){
			echo "<script>pintaSonora('".$valores['color']."',LimiteSonora,miMapa);</script>";
		}
		
		if($clave=="TABASCO"){
			echo "<script>pintaTabasco('".$valores['color']."',LimiteTabasco,miMapa);</script>";
		}
		
		if($clave=="TAMAULIPAS"){
			echo "<script>pintaTamaulipas('".$valores['color']."',LimiteTamaulipas,miMapa);</script>";
		}
		
		if($clave=="TLAXCALA"){
			echo "<script>pintaTlaxcala('".$valores['color']."',LimiteTlaxcala,miMapa);</script>";
		}
		
		if($clave=="VERACRUZ"){
			echo "<script>pintaVeracruz('".$valores['color']."',LimiteVeracruz,miMapa);</script>";
		}
		
		if($clave=="YUCATAN"){
			echo "<script>pintaYucatan('".$valores['color']."',LimiteYucatan,miMapa);</script>";
		}
		
		if($clave=="ZACATECAS"){
			echo "<script>pintaZacatecas('".$valores['color']."',LimiteZacatecas,miMapa);</script>";
		}
	
		
		
	
	
	}

	//echo "<script>alert('entro primer if');</script>";


}else if( $sltLinea==""  && $banderaH==1 ||$banderaH==2 ||$banderaH==3 ||$banderaH==4){

	foreach($estadosColores as $clave => $valores){

		if($valores=="AGUASCALIENTES"){
			echo "<script>pintaAguascalientes('".$colorPinta[$banderaH]."',LimiteAguascalientes,miMapa);</script>";
		}
		
		if($valores=="BAJA CALIFORNIA"){
			echo "<script>pintaBajaCalifornia('".$colorPinta[$banderaH]."',LimiteBajCalifornia,miMapa);</script>";
		}
		
		if($valores=="BAJA CALIFORNIA SUR"){
			echo "<script>pintaBajaCaliforniaSur('".$colorPinta[$banderaH]."',LimiteBajCaliforniaSur,miMapa);</script>";
		}
		
		if($valores=="CAMPECHE"){
			echo "<script>pintaCampeche('".$colorPinta[$banderaH]."',LimiteCampeche,miMapa);</script>";
		}
		
		if($valores=="CHIAPAS"){
			echo "<script>pintaChiapas('".$colorPinta[$banderaH]."',LimiteChiapas,miMapa);</script>";
		}
		
		if($valores=="CHIHUAHUA"){
			echo "<script>pintaChihuahua('".$colorPinta[$banderaH]."',LimiteChihuahua,miMapa);</script>";
		}
		
		if($valores=="CIUDAD DE MEXICO"){
			echo "<script>pintaDistritoFederal('".$colorPinta[$banderaH]."',LimiteDistritoFederal,miMapa);</script>";
		}
		
		if($valores=="COAHUILA"){
			echo "<script>pintaCoahuila('".$colorPinta[$banderaH]."',LimiteCoahuila,miMapa);</script>";
		}
		
		if($valores=="COLIMA"){
			echo "<script>pintaColima('".$colorPinta[$banderaH]."',LimiteColima,miMapa);</script>";
		}
		
		if($valores=="DURANGO"){
			echo "<script>pintaDurango('".$colorPinta[$banderaH]."',LimiteDurango,miMapa);</script>";
		}
		
		if($valores=="GUANAJUATO"){
			echo "<script>pintaGuanajuato('".$colorPinta[$banderaH]."',LimiteGuanajuato,miMapa);</script>";
		}
		
		if($valores=="GUERRERO"){
			echo "<script>pintaGuerrero('".$colorPinta[$banderaH]."',LimiteGuerrero,miMapa);</script>";
		}
		
		if($valores=="HIDALGO"){
			echo "<script>pintaHidalgo('".$colorPinta[$banderaH]."',LimiteHidalgo,miMapa);</script>";
		}
		
		if($valores=="JALISCO"){
			echo "<script>pintaJalisco('".$colorPinta[$banderaH]."',LimiteJalisco,miMapa);</script>";
		}
		
		if($valores=="MEXICO"){
			echo "<script>pintaEstadoDeMexico('".$colorPinta[$banderaH]."',LimiteEstadoDeMexico,miMapa);</script>";
		}
		
		if($valores=="MICHOACAN"){
			echo "<script>pintaMichoacan('".$colorPinta[$banderaH]."',LimiteMichoacan,miMapa);</script>";
		}
		
		if($valores=="MORELOS"){
			echo "<script>pintaMorelos('".$colorPinta[$banderaH]."',LimiteMorelos,miMapa);</script>";
		}
		
		if($valores=="NAYARIT"){
			echo "<script>pintaNayarit('".$colorPinta[$banderaH]."',LimiteNayarit,miMapa);</script>";
		}
		
		if($valores=="NUEVO LEON"){
			echo "<script>pintaNuevoLeon('".$colorPinta[$banderaH]."',LimiteNuevoLeon,miMapa);</script>";
		}
		
		if($valores=="OAXACA"){
			echo "<script>pintaOaxaca('".$colorPinta[$banderaH]."',LimiteOaxaca,miMapa);</script>";
		}
		
		if($valores=="PUEBLA"){
			echo "<script>pintaPuebla('".$colorPinta[$banderaH]."',LimitePuebla,miMapa);</script>";
		}
		
		if($valores=="QUERETARO"){
			echo "<script>pintaQueretaro('".$colorPinta[$banderaH]."',LimiteQueretaro,miMapa);</script>";
		}
		
		if($valores=="QUINTANA ROO"){
			echo "<script>pintaQuintanaRoo('".$colorPinta[$banderaH]."',LimiteQuintanaRoo,miMapa);</script>";
		}
		
		if($valores=="SAN LUIS POTOSI"){
			echo "<script>pintaSanLuisPotosi('".$colorPinta[$banderaH]."',LimiteSanLuisPotosi,miMapa);</script>";
		}
		
		if($valores=="SINALOA"){
			echo "<script>pintaSinaloa('".$colorPinta[$banderaH]."',LimiteSinaloa,miMapa);</script>";
		}
		
		if($valores=="SONORA"){
			echo "<script>pintaSonora('".$colorPinta[$banderaH]."',LimiteSonora,miMapa);</script>";
		}
		
		if($valores=="TABASCO"){
			echo "<script>pintaTabasco('".$colorPinta[$banderaH]."',LimiteTabasco,miMapa);</script>";
		}
		
		if($valores=="TAMAULIPAS"){
			echo "<script>pintaTamaulipas('".$colorPinta[$banderaH]."',LimiteTamaulipas,miMapa);</script>";
		}
		
		if($valores=="TLAXCALA"){
			echo "<script>pintaTlaxcala('".$colorPinta[$banderaH]."',LimiteTlaxcala,miMapa);</script>";
		}
		
		if($valores=="VERACRUZ"){
			echo "<script>pintaVeracruz('".$colorPinta[$banderaH]."',LimiteVeracruz,miMapa);</script>";
		}
		
		if($valores=="YUCATAN"){
			echo "<script>pintaYucatan('".$colorPinta[$banderaH]."',LimiteYucatan,miMapa);</script>";
		}
		
		if($valores=="ZACATECAS"){
			echo "<script>pintaZacatecas('".$colorPinta[$banderaH]."',LimiteZacatecas,miMapa);</script>";
		}

		

		

	}
	//echo "<script>alert('entro segundo if'+'".$sltRepre."');</script>";

}else if($sltRepre !='0' && $banderaH !=0 && $banderaH !=5) {

	
	//echo "<script>alert('entro ultimo if');</script>";

	foreach($arrEstadosRepre as $clave => $valores){

		if($valores=="AGUASCALIENTES"){
			echo "<script>pintaAguascalientes('".$colorPinta[$banderaH]."',LimiteAguascalientes,miMapa);</script>";
		}
		
		if($valores=="BAJA CALIFORNIA"){
			echo "<script>pintaBajaCalifornia('".$colorPinta[$banderaH]."',LimiteBajCalifornia,miMapa);</script>";
		}
		
		if($valores=="BAJA CALIFORNIA SUR"){
			echo "<script>pintaBajaCaliforniaSur('".$colorPinta[$banderaH]."',LimiteBajCaliforniaSur,miMapa);</script>";
		}
		
		if($valores=="CAMPECHE"){
			echo "<script>pintaCampeche('".$colorPinta[$banderaH]."',LimiteCampeche,miMapa);</script>";
		}
		
		if($valores=="CHIAPAS"){
			echo "<script>pintaChiapas('".$colorPinta[$banderaH]."',LimiteChiapas,miMapa);</script>";
		}
		
		if($valores=="CHIHUAHUA"){
			echo "<script>pintaChihuahua('".$colorPinta[$banderaH]."',LimiteChihuahua,miMapa);</script>";
		}
		
		if($valores=="CIUDAD DE MEXICO"){
			echo "<script>pintaDistritoFederal('".$colorPinta[$banderaH]."',LimiteDistritoFederal,miMapa);</script>";
		}
		
		if($valores=="COAHUILA"){
			echo "<script>pintaCoahuila('".$colorPinta[$banderaH]."',LimiteCoahuila,miMapa);</script>";
		}
		
		if($valores=="COLIMA"){
			echo "<script>pintaColima('".$colorPinta[$banderaH]."',LimiteColima,miMapa);</script>";
		}
		
		if($valores=="DURANGO"){
			echo "<script>pintaDurango('".$colorPinta[$banderaH]."',LimiteDurango,miMapa);</script>";
		}
		
		if($valores=="GUANAJUATO"){
			echo "<script>pintaGuanajuato('".$colorPinta[$banderaH]."',LimiteGuanajuato,miMapa);</script>";
		}
		
		if($valores=="GUERRERO"){
			echo "<script>pintaGuerrero('".$colorPinta[$banderaH]."',LimiteGuerrero,miMapa);</script>";
		}
		
		if($valores=="HIDALGO"){
			echo "<script>pintaHidalgo('".$colorPinta[$banderaH]."',LimiteHidalgo,miMapa);</script>";
		}
		
		if($valores=="JALISCO"){
			echo "<script>pintaJalisco('".$colorPinta[$banderaH]."',LimiteJalisco,miMapa);</script>";
		}
		
		if($valores=="MEXICO"){
			echo "<script>pintaEstadoDeMexico('".$colorPinta[$banderaH]."',LimiteEstadoDeMexico,miMapa);</script>";
		}
		
		if($valores=="MICHOACAN"){
			echo "<script>pintaMichoacan('".$colorPinta[$banderaH]."',LimiteMichoacan,miMapa);</script>";
		}
		
		if($valores=="MORELOS"){
			echo "<script>pintaMorelos('".$colorPinta[$banderaH]."',LimiteMorelos,miMapa);</script>";
		}
		
		if($valores=="NAYARIT"){
			echo "<script>pintaNayarit('".$colorPinta[$banderaH]."',LimiteNayarit,miMapa);</script>";
		}
		
		if($valores=="NUEVO LEON"){
			echo "<script>pintaNuevoLeon('".$colorPinta[$banderaH]."',LimiteNuevoLeon,miMapa);</script>";
		}
		
		if($valores=="OAXACA"){
			echo "<script>pintaOaxaca('".$colorPinta[$banderaH]."',LimiteOaxaca,miMapa);</script>";
		}
		
		if($valores=="PUEBLA"){
			echo "<script>pintaPuebla('".$colorPinta[$banderaH]."',LimitePuebla,miMapa);</script>";
		}
		
		if($valores=="QUERETARO"){
			echo "<script>pintaQueretaro('".$colorPinta[$banderaH]."',LimiteQueretaro,miMapa);</script>";
		}
		
		if($valores=="QUINTANA ROO"){
			echo "<script>pintaQuintanaRoo('".$colorPinta[$banderaH]."',LimiteQuintanaRoo,miMapa);</script>";
		}
		
		if($valores=="SAN LUIS POTOSI"){
			echo "<script>pintaSanLuisPotosi('".$colorPinta[$banderaH]."',LimiteSanLuisPotosi,miMapa);</script>";
		}
		
		if($valores=="SINALOA"){
			echo "<script>pintaSinaloa('".$colorPinta[$banderaH]."',LimiteSinaloa,miMapa);</script>";
		}
		
		if($valores=="SONORA"){
			echo "<script>pintaSonora('".$colorPinta[$banderaH]."',LimiteSonora,miMapa);</script>";
		}
		
		if($valores=="TABASCO"){
			echo "<script>pintaTabasco('".$colorPinta[$banderaH]."',LimiteTabasco,miMapa);</script>";
		}
		
		if($valores=="TAMAULIPAS"){
			echo "<script>pintaTamaulipas('".$colorPinta[$banderaH]."',LimiteTamaulipas,miMapa);</script>";
		}
		
		if($valores=="TLAXCALA"){
			echo "<script>pintaTlaxcala('".$colorPinta[$banderaH]."',LimiteTlaxcala,miMapa);</script>";
		}
		
		if($valores=="VERACRUZ"){
			echo "<script>pintaVeracruz('".$colorPinta[$banderaH]."',LimiteVeracruz,miMapa);</script>";
		}
		
		if($valores=="YUCATAN"){
			echo "<script>pintaYucatan('".$colorPinta[$banderaH]."',LimiteYucatan,miMapa);</script>";
		}
		
		if($valores=="ZACATECAS"){
			echo "<script>pintaZacatecas('".$colorPinta[$banderaH]."',LimiteZacatecas,miMapa);</script>";
		}

		



	}
	
}




echo "<script>
$('#hdnEstadosPintados').val('".implode(",",$estadosColores)."');

var estados=$('#hdnEstadosPintados').val();

</script>";

 $nomresEstado=implode(",",$estadosColores);


	
$etsadosName=str_replace ( ",","','", $nomresEstado );

//echo "<br><br>esto es estados name: ".$etsadosName."<br><br>";



$condicionTabla="";

if($sltRepre !="0"){
	$condicionTabla.=" and U.USER_SNR='".$sltRepre."' ";
}

if($sltLinea != "" && $sltLinea !='0'){
	
	$condicionTabla.="  and LINEA.CLINE_SNR = '".$sltLinea."' ";

	
}



if($banderaH > 0 && $banderaH < 5 ){

	$condicionTabla.=" and EDO.name in ('".$etsadosName."') ";

}

if($banderaH == 0 && $sltEstado !='0'){
	$condicionTabla.="and EDO.STATE_SNR='".$sltEstado."' ";
}


$queryGlobalTabla="WITH TOTAL AS ( 
	SELECT 
	LINEA.SORT_NUM LINEA_ORDEN,
	U.CLINE_SNR,
	LINEA.NAME_SHORT LINEA,
	LINEA.DAILY_CONTACTS,
	U.USER_SNR,
	U.USER_NR +' - '+U.FNAME+' '+U.LNAME+' '+U.MOTHERS_LNAME AS REPRES_TOTAL,
	EDO.STATE_SNR,
	EDO.NAME ESTADO, ";
	$queryGlobalTabla.=$lineacontactos;
	
	$queryGlobalTabla.=$fechaTft;
	$queryGlobalTabla.="
	) contactos, 
	
	(SELECT COUNT(DISTINCT VP1.PERS_SNR) FROM VISITPERS VP1 
	WHERE VP1.USER_SNR=U.USER_SNR AND VP1.PWORK_SNR=PLW.PWORK_SNR AND VP1.REC_STAT=0 AND VISIT_CODE_SNR NOT in ('73253003-55D7-4B25-929F-0F4A452E6F6B','B7606EC6-498C-4075-928D-8A5ECBE8737A') 
	AND "; $queryGlobalTabla.=$fecha;
	$queryGlobalTabla.="
	) VISITAS,
	
	(SELECT DISTINCT VP1.USER_SNR FROM VISITPERS VP1 WHERE VP1.USER_SNR=U.USER_SNR 
	AND VP1.PWORK_SNR=PLW.PWORK_SNR 
	AND VP1.REC_STAT=0 AND VISIT_CODE_SNR NOT in ('73253003-55D7-4B25-929F-0F4A452E6F6B','B7606EC6-498C-4075-928D-8A5ECBE8737A') 
	AND "; $queryGlobalTabla.=$fecha;
	$queryGlobalTabla.="
	) REPRES_VIS 
	
	FROM VISITPERS VP 
	INNER JOIN PERSLOCWORK PLW ON PLW.PWORK_SNR=VP.PWORK_SNR 
	AND PLW.REC_STAT=0 
	INNER JOIN INST I ON I.INST_SNR=PLW.INST_SNR 
	INNER JOIN CITY CY ON CY.CITY_SNR=I.CITY_SNR AND CY.REC_STAT=0 
	INNER JOIN DISTRICT DTO ON CY.DISTR_SNR=DTO.DISTR_SNR AND DTO.REC_STAT=0 
	INNER JOIN STATE EDO ON EDO.STATE_SNR=DTO.STATE_SNR AND EDO.REC_STAT=0 
	INNER JOIN USERS U ON U.USER_SNR=VP.USER_SNR AND U.REC_STAT=0 AND U.STATUS=1 AND U.USER_TYPE=4 
	INNER JOIN COMPLINE LINEA ON U.CLINE_SNR=LINEA.CLINE_SNR WHERE VP.REC_STAT=0 AND EDO.NAME<>'' AND LINEA.MAP=1 ";

	$queryGlobalTabla.=$condicionTabla;

	$queryGlobalTabla.=$grupo;

	$queryGlobalTabla.="
	), 
	TOTAL_DIAS AS ( 
	SELECT VISIT_DATE,
	VP1.USER_SNR,
	LINEA.NAME_SHORT,
	LINEA.DAILY_CONTACTS 
	FROM VISITPERS VP1 
	INNER JOIN PERSLOCWORK PLW ON PLW.PWORK_SNR=VP1.PWORK_SNR 
	AND PLW.REC_STAT=0 
	INNER JOIN INST I ON I.INST_SNR=PLW.INST_SNR 
	INNER JOIN CITY CY ON CY.CITY_SNR=I.CITY_SNR AND CY.REC_STAT=0 
	INNER JOIN DISTRICT DTO ON CY.DISTR_SNR=DTO.DISTR_SNR AND DTO.REC_STAT=0 
	INNER JOIN STATE EDO ON EDO.STATE_SNR=DTO.STATE_SNR AND EDO.REC_STAT=0 
	INNER JOIN USERS U ON U.USER_SNR=VP1.USER_SNR AND U.REC_STAT=0 AND U.STATUS=1 AND U.USER_TYPE=4 
	INNER JOIN COMPLINE LINEA ON U.CLINE_SNR=LINEA.CLINE_SNR 
	WHERE VP1.REC_STAT=0 AND EDO.NAME<>'' AND LINEA.MAP=1 ";
	$queryGlobalTabla.=$condicionTabla;
	
	$queryGlobalTabla.="AND VP1.USER_SNR=U.USER_SNR AND VP1.PWORK_SNR=PLW.PWORK_SNR AND VP1.REC_STAT=0 AND VISIT_CODE_SNR NOT in ('73253003-55D7-4B25-929F-0F4A452E6F6B','B7606EC6-498C-4075-928D-8A5ECBE8737A') 
	AND "; $queryGlobalTabla.=$fecha;
	$queryGlobalTabla.=" 
	GROUP BY VISIT_DATE,VP1.USER_SNR,LINEA.NAME_SHORT,LINEA.DAILY_CONTACTS 
	) ";
	$queryGlobalTabla.=$select2;









		//END CARGA MAPA ---------------

		//$rsTabla = sqlsrv_query($conn, $queryGlobalTabla);	
		$rsRowsTabla = sqlsrv_query($conn, $queryGlobalTabla, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		if($rsRowsTabla === false){
			if( ($errors = sqlsrv_errors() ) != null) {
        		foreach( $errors as $error ) {
        			echo "<script>";
            		echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            		echo "code: ".$error[ 'code']."<br />";
            		echo "message: ".$error[ 'message']."<br />";

            		echo $queryGlobalTabla;

            		echo "</script>";
        		}
    		}
		}
		$rsTabla = $rsRowsTabla;
		$rowsTabla=sqlsrv_num_rows($rsRowsTabla);

		if($rowsTabla==0){
			echo "<script>alert('sin registros');
					$('#screen').hide();
					$('#tblLocRep tbody').empty();
					$('#totalrep').text('0');
					$('#totalv').text('0');
					$('#totalm').text('0');
					$('#totalcob').text('0%');
					$('#incidencia').text('0');
					limpia();
				</script>";
				return true;
		}



	
	
	echo "<script>$('#tblLocRep tbody').empty(); </script>";

	$porF=0;


	$conVis=0;
	$conObj=0;
	$contTft=0;
	$contRep=0;
	$varLineaOEstado="";

	while($dataTabla = sqlsrv_fetch_array($rsTabla)){

		if($dataTabla['CONTACTS']>0){
			$porF=round(($dataTabla['VISITAS'] / $dataTabla['CONTACTS']) * 100,2);

		}else{
			$porF=0;
		}

		$conVis=$conVis+$dataTabla['VISITAS'];
		$conObj=$conObj+$dataTabla['CONTACTS'];


		$formato_vis_presT = number_format($dataTabla['VISITAS']);


		$formato_contacts=number_format($dataTabla['CONTACTS']);

		if($sltLinea!=""){

			if($dataTabla['TFT'] !=""){
				$contTft++;
			}

			if($dataTabla['REPRES_TOTAL'] !=""){
				$contRep++;
			}


		}else{
			$contTft=$contTft+$dataTabla['TFT'];

			$contRep=$contRep+$dataTabla['REPRES_TOTAL'];
		}



		$id=$dataTabla['ID'];
		//style=\"display:none\"

		if($porF <= 50){

					echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color: #e75f5f;color:white;\" ><td>".$dataTabla['LINEA']."</td><td class=\"valorRepre\">".utf8_encode($dataTabla['REPRES_TOTAL'])."</td><td>".$formato_contacts."</td><td>".$formato_vis_presT."</td><td>".$porF."%"."</td><td>".$dataTabla['TFT']."</td><td style=\"display:none\">".$id."</td></tr>');</script>";
			
					}

		if($porF > 50 && $porF <= 77){

				echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color:#ffddba;color:black;\" ><td>".$dataTabla['LINEA']."</td><td class=\"valorRepre\">".utf8_encode($dataTabla['REPRES_TOTAL'])."</td><td>".$formato_contacts."</td><td>".$formato_vis_presT."</td><td>".$porF."%"."</td><td>".$dataTabla['TFT']."</td><td style=\"display:none\">".$id."</td></tr>');</script>";
			
					}

		if($porF > 77 && $porF < 95 ){
			
				echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color:#fcfccd;color:black;\" ><td>".$dataTabla['LINEA']."</td><td class=\"valorRepre\">".utf8_encode($dataTabla['REPRES_TOTAL'])."</td><td>".$formato_contacts."</td><td>".$formato_vis_presT."</td><td>".$porF."%"."</td><td>".$dataTabla['TFT']."</td><td style=\"display:none\">".$id."</td></tr>');</script>";
					}

		if($porF >= 95 ){

				echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color:#c7dfc7 ;color:black;\" ><td>".$dataTabla['LINEA']."</td><td class=\"valorRepre\">".utf8_encode($dataTabla['REPRES_TOTAL'])."</td><td>".$formato_contacts."</td><td>".$formato_vis_presT."</td><td>".$porF."%"."</td><td>".$dataTabla['TFT']."</td><td style=\"display:none\">".$id."</td></tr>');</script>";	   

			
					}
				
	}

	$conVisF=number_format($conVis);
	$conObjF=number_format($conObj);

	$porcentajeF=round(($conVis/$conObj)*100,2);

	//CARGAR TABLA 
	echo "<script>
	

	$('#totalm').text('".$conObjF."');
	$('#totalv').text('".$conVisF."');
	$('#totalcob').text('".$porcentajeF."'+'%');
	$('#incidencia').text('".$contTft."');
	$('#totalrep').text('".$contRep."');
	

	


	</script>";


	//END CARGAR TABLA
















	
	echo "<script>$('#screen').hide();</script>";






//echo "query tabla :".$queryGlobalTabla."<br>";
//echo "query mapa :".$queryGlobalMapa."<br>";

	?>

<script>

$(".valorRepre").click(function() {

//valores obtendra el dato del td por posciones [0]
var valores = $(this).parents("tr").find("td")[1].innerHTML;
var id = $(this).parents("tr").find("td")[6].innerHTML;
	   //console.log(id);

	   valores= valores.substring(0 , 9);

	   document.querySelector('#sltRepreRadar').setValue(id);
	   $('.vscomp-toggle-button').css('background-color', '#3a4a9a');
										
		$('.vscomp-toggle-button').css('color', 'white');

	   $('#hdnIdnr').val(id);
	  // alert(valores);
	   

	 
					

   					$('#divTblRep').show();
				 	$('#lblUltimaSinc2').show();
					$('#lblUltimaSinc').show();
					$('#btnHistorialVisitasLocalizadorRepres').attr("disabled", false);
					$('#btnLocalizaRepreLocalizadorRepres').attr("disabled", false);
					$('#btnTrackingLocalizadorRepres').attr("disabled", false);
					var sltDiaAnterior=$('#sltCobDiaAnterior').val();
					var valorC=$('#sltRepreRadar').val();
					if(valorC==""){
						valorC=0;
					}
					$('#divRespuesta').load("ajax/marcadoresGeolocalizacionRepres.php",{idUsuario:id,sltDiaAnterior:sltDiaAnterior,valorC:valorC});
					//$('#divRespuesta').load("ajax/AvanceRepre2.php",{idUsuario:valores,sltDiaAnterior:sltDiaAnterior});

				

					$("#screen").show();

					tipoUsuario = $("#hdnTipoUsuario").val();
			var idUsuario = $('#hdnIdUser').val();
			var sltLinea=$('#sltLinea').val();
			var sltEstado=$('#sltEstado').val();
			var sltCobDiaAnterior=$('#sltCobDiaAnterior').val();
			var sltCobDiaria=$('#sltCobDiaria').val();
			var sltCobCiclo=$('#sltCobCiclo').val();
			var sltRepre =$('#sltRepreRadar').val();;

			$('#divRespuesta').load("ajax/CargarLineas.php",{sltLinea:sltLinea,idUsuario:idUsuario,tipoUsuario:tipoUsuario,sltEstado:sltEstado,sltCobDiaAnterior:sltCobDiaAnterior,sltCobDiaria:sltCobDiaria,sltCobCiclo:sltCobCiclo,sltRepre:sltRepre});
	   

	 
					//REINICIAR RELOJ
					count=$('#hdnval').val();
						clearInterval(count);
						reiniciar();
					//END REINICIAR RELOJ



				$('#sltCobCiclo').prop('selectedIndex',0);
		
				$('#sltCobCiclo').css('color', 'black');
		
				$('#sltCobCiclo').css('background', '#FFFFFF');
					   


});

$('#divMap').show();
$('#divTblRep').show();
$('#divmarque').show();
$('#mapaRepre').hide();




</script>