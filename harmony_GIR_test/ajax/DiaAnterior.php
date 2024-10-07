<?php
	include "../conexion.php";


	$idUsuario2=$_POST['idUsuario'];
	$combo=$_POST['combo'];


	$tipoUsuario=$_POST['tipoUsuario'];
	$idUsuario=$_POST['idUsuario'];
	$comboL=$_POST['comboL'];

	$mayorq=0;
	$menorq=0;

	$arrDiaDeLaSemana = sqlsrv_fetch_array(sqlsrv_query($conn,"SELECT CYCLE_DETAILS.C_DAY_OF_WEEK FROM CYCLE_DETAILS WHERE CYCLE_SNR IN (SELECT CYCLE_SNR FROM CYCLES WHERE GETDATE() BETWEEN START_DATE AND FINISH_DATE)
				AND CAST(GETDATE() AS DATE)=CYCLE_DETAILS.C_DATE "));
					$diaDelLaSemana = $arrDiaDeLaSemana['C_DAY_OF_WEEK'];
		$v1=0;

	if($diaDelLaSemana==1){

			$v1=-3;


		}else if($diaDelLaSemana==7){
			$v1=-2;

		}else{
			$v1=-1;

			}

			$queryPintaTabla2="";


			$queryGlobal ="WITH TOTAL AS (SELECT 
	LINEA.NAME_SHORT LINEA,
	LINEA.CLINE_SNR,
	LINEA.SORT_NUM LINEA_ORDEN,
	EDO.NAME ESTADO,
	EDO.STATE_SNR,
	ISNULL(COUNT(DISTINCT VPP.PERS_SNR) + COUNT(DISTINCT VPV.PERS_SNR),0) VISITAS, 
	ISNULL(COUNT(DISTINCT VPP.PERS_SNR),0) VISITAS_PRES, ISNULL(COUNT(DISTINCT VPV.PERS_SNR),0) VISITAS_VIRT, 
	LINEA.DAILY_CONTACTS * COUNT(DISTINCT VP.USER_SNR) AS CONTACTOS,
	ISNULL(COUNT(DISTINCT DR.USER_SNR),0) as TFT,
	COUNT(DISTINCT DR.USER_SNR) *  LINEA.DAILY_CONTACTS  CONTACTOS_TFT
	FROM PERSON P 
	INNER JOIN CYCLES CYC ON CYC.REC_STAT=0 AND CAST(dateadd(DAY,0,GETDATE()) AS DATE) BETWEEN CYC.START_DATE AND CAST(dateadd(DAY,0,CYC.FINISH_DATE) AS DATE)
	LEFT OUTER JOIN VISITPERS VP ON P.PERS_SNR=VP.PERS_SNR AND VP.REC_STAT=0 AND VISIT_DATE = CAST(dateadd(DAY,".$v1.", GETDATE()) AS DATE)
	INNER JOIN PERS_SREP_WORK PSW ON P.PERS_SNR=PSW.PERS_SNR AND PSW.REC_STAT=0 
	INNER JOIN INST I ON I.INST_SNR=PSW.INST_SNR AND I.REC_STAT=0 
	INNER JOIN USERS U ON U.USER_SNR=PSW.USER_SNR AND U.REC_STAT=0 AND U.STATUS=1 AND U.USER_TYPE=4 
	INNER JOIN COMPLINE LINEA ON U.CLINE_SNR=LINEA.CLINE_SNR 
	INNER JOIN CITY CY ON CY.CITY_SNR=I.CITY_SNR AND CY.REC_STAT=0 
	INNER JOIN DISTRICT DTO ON CY.DISTR_SNR=DTO.DISTR_SNR AND DTO.REC_STAT=0 
	INNER JOIN STATE EDO ON EDO.STATE_SNR=DTO.STATE_SNR AND EDO.REC_STAT=0 
	INNER JOIN CODELIST ESTATUS ON ESTATUS.CLIST_SNR=P.STATUS_SNR
	LEFT OUTER JOIN VISITPERS VPP ON P.PERS_SNR=VPP.PERS_SNR AND VPP.REC_STAT=0 AND VPP.VISIT_DATE = CAST(dateadd(DAY,".$v1.", GETDATE()) AS DATE) AND VPP.VISIT_CODE_SNR in ('2B3A7099-AC7D-47A3-A274-F0B029791801','E9A14663-1F3E-4707-915B-1891CC34AD1B') 
	LEFT OUTER JOIN VISITPERS VPV ON P.PERS_SNR=VPV.PERS_SNR AND VPV.REC_STAT=0 AND VPV.VISIT_DATE = CAST(dateadd(DAY,".$v1.", GETDATE()) AS DATE) AND VPV.VISIT_CODE_SNR in ('5655BC78-6002-4097-82CC-8BA7E1FBD5FC') 
	LEFT OUTER JOIN DAY_REPORT DR ON DR.USER_SNR=U.USER_SNR AND DR.REC_STAT=0 AND DR.START_DATE = CAST(dateadd(DAY,".$v1.", GETDATE()) AS DATE)";

	if($tipoUsuario==5){
		$queryGlobal=$queryGlobal." WHERE LINEA.CLINE_SNR in ('".$comboL."')";

	}else{
		$queryGlobal=$queryGlobal." WHERE LINEA.CLINE_SNR in ('AB553EB3-5051-4812-9277-0467BC6E9A8B','4E74577D-89EA-4110-A307-5517299BD8A2','D8B27845-01EB-4E14-BA37-107CFE9776F3', '036AC44A-3326-4F09-8A1E-CF12282E214C','EF565E9A-0374-430A-B276-4AA189F01137','A6192204-9C60-49C2-B25F-3D0B05A4F2ED','109EF54F-AAD0-4D22-9483-2CB5D688F2FA', 'B4564E23-B1CD-46B5-92E2-036B16C295F8','0062EF5E-DA92-4BA2-B9DA-040D9C64EF7B','31BC2694-306E-4703-A312-7EAA22083993','AA6C0306-5C52-4A15-A644-7551332B18D5') ";
	}
	$queryGlobal=$queryGlobal." AND P.REC_STAT=0 AND EDO.NAME<>'' AND ESTATUS.NAME='ACTIVO' AND LINEA.MAP=1 
	GROUP BY EDO.NAME,LINEA.SORT_NUM,LINEA.NAME_SHORT,LINEA.CLINE_SNR,EDO.STATE_SNR,LINEA.DAILY_CONTACTS
	)";

	//selecciona todos 
	if($combo==5){


		//QUERY PINTA TABLA
		$QueryGeneralTabla=$queryGlobal." SELECT LINEA,
		(SELECT COUNT(USER_SNR) FROM USERS WHERE USERS.CLINE_SNR=TOTAL.CLINE_SNR AND USER_TYPE=4 AND STATUS=1 AND REC_STAT=0 
		GROUP BY CLINE_SNR) REPRES,
		SUM(VISITAS) VISITAS,
		SUM(VISITAS_PRES) VISITAS_PRES,
		SUM(VISITAS_VIRT) VISITAS_VIRT,
		CAST(CASE WHEN SUM(VISITAS) > 0 AND (SUM(CONTACTOS) - SUM(CONTACTOS_TFT)) > 0 THEN ROUND((CAST(SUM(VISITAS) AS FLOAT) / CAST((SUM(CONTACTOS) - SUM(CONTACTOS_TFT)) AS FLOAT)) * 100, 1) ELSE 0 END AS NUMERIC(5,1)) PORCIENTO,
		SUM(TFT) TFT
		FROM TOTAL 
		GROUP BY LINEA_ORDEN,LINEA,CLINE_SNR 
		ORDER BY LINEA_ORDEN";


		$queryPintaMapa=$queryGlobal."SELECT 
		ESTADO,
		STATE_SNR,
		SUM(VISITAS) VISITAS,
		SUM(VISITAS_PRES) VISITAS_PRES,
		SUM(VISITAS_VIRT) VISITAS_VIRT,
		CAST(CASE WHEN SUM(VISITAS) > 0 AND (SUM(CONTACTOS) - SUM(CONTACTOS_TFT)) > 0 THEN ROUND((CAST(SUM(VISITAS) AS FLOAT) / CAST((SUM(CONTACTOS) - SUM(CONTACTOS_TFT)) AS FLOAT)) * 100, 1) ELSE 0 END AS NUMERIC(5,1)) PORCIENTO,
		SUM(TFT) TFT
		FROM TOTAL 
		GROUP BY ESTADO,STATE_SNR 
		ORDER BY ESTADO";
			

		echo "<script>$('#titu1').empty();
		$('#titu10').empty();
		$('#titu1').text('Linea');
		$('#titu10').text('Repre');</script>";

		
		$rsPintaMapa = sqlsrv_query($conn,$queryPintaMapa, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		$rows=sqlsrv_num_rows($rsPintaMapa);

		//echo $rows;
		if($rows==0){

			echo "<script>alert('sin registros');
				$('#screen').hide();
				$('#tblLocRep tbody').empty();
				limpia();
			</script>";
			return true;
		}

		//START PINTA MAPA
		$porcentaje=array();
		$color= array();
		$estado=array();
		//$estados="";

		
		while($arrDA = sqlsrv_fetch_array($rsPintaMapa)){
				$porcentaje[]=round($arrDA['PORCIENTO']);
				$estado[]=$arrDA['ESTADO'];
				//$estados.=$arrDA['STATE_SNR'].',';
			}


				for($i=0;$i<count($estado);$i++){
		if($porcentaje[$i] >= 95){
			$color[$i]="green"; 
			}
			
		if($porcentaje[$i] >=78 && $porcentaje[$i] <= 94 ){
			$color[$i]="yellow"; 
		
			}
		if($porcentaje[$i] > 50 && $porcentaje[$i] < 78){
			$color[$i]="orange";
		

			}
		if($porcentaje[$i] <= 50 ){
			$color[$i]="red";
		}
			
		}

		
		
			echo "<script>limpia();</script>";
		for($j=0;$j<$rows;$j++){
			
			if($estado[$j]=="AGUASCALIENTES"){
				echo "<script>pintaAguascalientes('".$color[$j]."',LimiteAguascalientes,miMapa);</script>";
			}
			
			if($estado[$j]=="BAJA CALIFORNIA"){
				echo "<script>pintaBajaCalifornia('".$color[$j]."',LimiteBajCalifornia,miMapa);</script>";
			}
			
			if($estado[$j]=="BAJA CALIFORNIA SUR"){
				echo "<script>pintaBajaCaliforniaSur('".$color[$j]."',LimiteBajCaliforniaSur,miMapa);</script>";
			}
			
			if($estado[$j]=="CAMPECHE"){
				echo "<script>pintaCampeche('".$color[$j]."',LimiteCampeche,miMapa);</script>";
			}
			
			if($estado[$j]=="CHIAPAS"){
				echo "<script>pintaChiapas('".$color[$j]."',LimiteChiapas,miMapa);</script>";
			}
			
			if($estado[$j]=="CHIHUAHUA"){
				echo "<script>pintaChihuahua('".$color[$j]."',LimiteChihuahua,miMapa);</script>";
			}
			
			if($estado[$j]=="CIUDAD DE MEXICO"){
				echo "<script>pintaDistritoFederal('".$color[$j]."',LimiteDistritoFederal,miMapa);</script>";
			}
			
			if($estado[$j]=="COAHUILA"){
				echo "<script>pintaCoahuila('".$color[$j]."',LimiteCoahuila,miMapa);</script>";
			}
			
			if($estado[$j]=="COLIMA"){
				echo "<script>pintaColima('".$color[$j]."',LimiteColima,miMapa);</script>";
			}
			
			if($estado[$j]=="DURANGO"){
				echo "<script>pintaDurango('".$color[$j]."',LimiteDurango,miMapa);</script>";
			}
			
			if($estado[$j]=="GUANAJUATO"){
				echo "<script>pintaGuanajuato('".$color[$j]."',LimiteGuanajuato,miMapa);</script>";
			}
			
			if($estado[$j]=="GUERRERO"){
				echo "<script>pintaGuerrero('".$color[$j]."',LimiteGuerrero,miMapa);</script>";
			}
			
			if($estado[$j]=="HIDALGO"){
				echo "<script>pintaHidalgo('".$color[$j]."',LimiteHidalgo,miMapa);</script>";
			}
			
			if($estado[$j]=="JALISCO"){
				echo "<script>pintaJalisco('".$color[$j]."',LimiteJalisco,miMapa);</script>";
			}
			
			if($estado[$j]=="MEXICO"){
				echo "<script>pintaEstadoDeMexico('".$color[$j]."',LimiteEstadoDeMexico,miMapa);</script>";
			}
			
			if($estado[$j]=="MICHOACAN"){
				echo "<script>pintaMichoacan('".$color[$j]."',LimiteMichoacan,miMapa);</script>";
			}
			
			if($estado[$j]=="MORELOS"){
				echo "<script>pintaMorelos('".$color[$j]."',LimiteMorelos,miMapa);</script>";
			}
			
			if($estado[$j]=="NAYARIT"){
				echo "<script>pintaNayarit('".$color[$j]."',LimiteNayarit,miMapa);</script>";
			}
			
			if($estado[$j]=="NUEVO LEON"){
				echo "<script>pintaNuevoLeon('".$color[$j]."',LimiteNuevoLeon,miMapa);</script>";
			}
			
			if($estado[$j]=="OAXACA"){
				echo "<script>pintaOaxaca('".$color[$j]."',LimiteOaxaca,miMapa);</script>";
			}
			
			if($estado[$j]=="PUEBLA"){
				echo "<script>pintaPuebla('".$color[$j]."',LimitePuebla,miMapa);</script>";
			}
			
			if($estado[$j]=="QUERETARO"){
				echo "<script>pintaQueretaro('".$color[$j]."',LimiteQueretaro,miMapa);</script>";
			}
			
			if($estado[$j]=="QUINTANA ROO"){
				echo "<script>pintaQuintanaRoo('".$color[$j]."',LimiteQuintanaRoo,miMapa);</script>";
			}
			
			if($estado[$j]=="SAN LUIS POTOSI"){
				echo "<script>pintaSanLuisPotosi('".$color[$j]."',LimiteSanLuisPotosi,miMapa);</script>";
			}
			
			if($estado[$j]=="SINALOA"){
				echo "<script>pintaSinaloa('".$color[$j]."',LimiteSinaloa,miMapa);</script>";
			}
			
			if($estado[$j]=="SONORA"){
				echo "<script>pintaSonora('".$color[$j]."',LimiteSonora,miMapa);</script>";
			}
			
			if($estado[$j]=="TABASCO"){
				echo "<script>pintaTabasco('".$color[$j]."',LimiteTabasco,miMapa);</script>";
			}
			
			if($estado[$j]=="TAMAULIPAS"){
				echo "<script>pintaTamaulipas('".$color[$j]."',LimiteTamaulipas,miMapa);</script>";
			}
			
			if($estado[$j]=="TLAXCALA"){
				echo "<script>pintaTlaxcala('".$color[$j]."',LimiteTlaxcala,miMapa);</script>";
			}
			
			if($estado[$j]=="VERACRUZ"){
				echo "<script>pintaVeracruz('".$color[$j]."',LimiteVeracruz,miMapa);</script>";
			}
			
			if($estado[$j]=="YUCATAN"){
				echo "<script>pintaYucatan('".$color[$j]."',LimiteYucatan,miMapa);</script>";
			}
			
			if($estado[$j]=="ZACATECAS"){
				echo "<script>pintaZacatecas('".$color[$j]."',LimiteZacatecas,miMapa);</script>";
			}
			
		}


		//END PINTA MAPA

		//START PINTA TABLA
		$rsTabla = sqlsrv_query($conn, $QueryGeneralTabla);
	
		$rsRowsTabla = sqlsrv_query($conn, $QueryGeneralTabla, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		$rowsTabla=sqlsrv_num_rows($rsRowsTabla);

		echo "<script>$('#tblLocRep tbody').empty(); </script>";

		$por=0;
		$aux2=0;
		$aux3=0;
		$aux4=0;
		$countVpT=0;
		$countVp=0;
		$countVv=0;

		while($dataTabla = sqlsrv_fetch_array($rsTabla)){

			$aux2=$dataTabla[2];

			$aux3=$dataTabla[3];

			$aux4=$dataTabla[4];

			$countVpT=$countVpT+$aux2;

			$countVp=$countVp+$aux3;

			$countVv=$countVv+$aux4;

			$formato_vis_presT = number_format($dataTabla[2]);

			$formato_vis_pres = number_format($dataTabla[3]);

			$formato_vis_vi = number_format($dataTabla[4]);
			$por=$dataTabla[5];

			 if($por<50){

			 	if($por<1){
			 		echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color: #ff5232;color:white;\" ><td>".$dataTabla[0]."</td><td>".$dataTabla[1]."</td><td>".$formato_vis_presT."</td><td>".$formato_vis_pres."</td><td>".$formato_vis_vi."</td><td>"."0".$dataTabla[5]."%"."</td><td>".$dataTabla[6]."</td></tr>');</script>";
			 	}else{
			 		echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color: #ff5232;color:white;\" ><td>".$dataTabla[0]."</td><td>".$dataTabla[1]."</td><td>".$formato_vis_presT."</td><td>".$formato_vis_pres."</td><td>".$formato_vis_vi."</td><td>".$dataTabla[5]."%"."</td><td>".$dataTabla[6]."</td></tr>');</script>";
			 	}

			

				
			}

			if($por > 50 && $por < 78){

				if($por<1){
					echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color: #ffa040;color:black;\" ><td>".$dataTabla[1]."</td><td>".$dataTabla[0]."</td><td>".$formato_vis_presT."</td><td>".$formato_vis_pres."</td><td>".$formato_vis_vi."</td><td>"."0".$dataTabla[5]."%"."</td><td>".$dataTabla[6]."</td></tr>');</script>";
			 	}else{
			 		echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color: #ffa040;color:black;\" ><td>".$dataTabla[1]."</td><td>".$dataTabla[0]."</td><td>".$formato_vis_presT."</td><td>".$formato_vis_pres."</td><td>".$formato_vis_vi."</td><td>".$dataTabla[5]."%"."</td><td>".$dataTabla[6]."</td></tr>');</script>";
			 	}
				
 
			}

			if($por >=78 && $por <= 94 ){

				if($por<1){
					echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color:  #ffff90;color:black;\" ><td>".$dataTabla[0]."</td><td>".$dataTabla[1]."</td><td>".$formato_vis_presT."</td><td>".$formato_vis_pres."</td><td>".$formato_vis_vi."</td><td>"."0".$dataTabla[5]."%"."</td><td>".$dataTabla[6]."</td></tr>');</script>";
			 	}else{
			 		echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color:  #ffff90;color:black;\" ><td>".$dataTabla[0]."</td><td>".$dataTabla[1]."</td><td>".$formato_vis_presT."</td><td>".$formato_vis_pres."</td><td>".$formato_vis_vi."</td><td>".$dataTabla[5]."%"."</td><td>".$dataTabla[6]."</td></tr>');</script>";
			 	}
				
			}

			if($por >= 95 ){

				if($por<1){
					echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color: #84e283;color:black;\" ><td>".$dataTabla[0]."</td><td>".$dataTabla[1]."</td><td>".$formato_vis_presT."</td><td>".$formato_vis_pres."</td><td>".$formato_vis_vi."</td><td>"."0".$dataTabla[5]."%"."</td><td>".$dataTabla[6]."</td></tr>');</script>";
			 	}else{
			 		echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color: #84e283;color:black;\" ><td>".$dataTabla[0]."</td><td>".$dataTabla[1]."</td><td>".$formato_vis_presT."</td><td>".$formato_vis_pres."</td><td>".$formato_vis_vi."</td><td>".$dataTabla[5]."%"."</td><td>".$dataTabla[6]."</td></tr>');</script>";
			 	}

				
			}

		}

		 $formato_countVpT = number_format($countVpT,0,"'",",");

		 $formato_countVp = number_format($countVp,0,"'",",");

		 $formato_countVv = number_format($countVv,0,"'",",");

		 echo "<script>

			$('#totalv').text('".$formato_countVpT."');

			$('#totalp').text('".$formato_countVp."');

			$('#totalvi').text('".$formato_countVv."');
	
			total_col5=0;
			total_col4=0;
			$('#tblLocRep tbody').find('tr').each(function (i, el) {
		             
		        //Voy incrementando las variables segun la fila ( .eq(0) representa la fila 1 )     
		       
				total_col5 += parseFloat($(this).find('td').eq(6).text());
				total_col4 += parseFloat($(this).find('td').eq(1).text());
				
		                
		    });


			$('#incidencia').text(total_col5);
			$('#totalrep').text(total_col4);

	
	</script>";



		//END PINTA TABLA

		echo "<script>$('#screen').hide();</script>";




	}else{

		echo "<script>$('#titu1').empty();
				$('#titu10').empty();
				$('#titu1').text('Linea');
				$('#titu10').text('Estado');
				</script>";

		$querySemaforo=$queryGlobal."SELECT LINEA,
		ESTADO,
		STATE_SNR,
		SUM(VISITAS) VISITAS,
		SUM(VISITAS_PRES) VISITAS_PRES,
		SUM(VISITAS_VIRT) VISITAS_VIRT,
		CAST(CASE WHEN SUM(VISITAS) > 0 AND (SUM(CONTACTOS) - SUM(CONTACTOS_TFT)) > 0 THEN ROUND((CAST(SUM(VISITAS) AS FLOAT) / CAST((SUM(CONTACTOS) - SUM(CONTACTOS_TFT)) AS FLOAT)) * 100, 1) ELSE 0 END AS NUMERIC(5,1)) PORCIENTO,
		SUM(TFT) TFT
		FROM TOTAL 
		GROUP BY LINEA_ORDEN,LINEA,ESTADO,STATE_SNR"; 

		$color="";

		if($combo==1){
				$color="red";
				$mayorq=50;

			
			$querySemaforo=$querySemaforo." HAVING CAST(CASE WHEN SUM(VISITAS) > 0 AND (SUM(CONTACTOS) - SUM(CONTACTOS_TFT)) > 0 THEN ROUND((CAST(SUM(VISITAS) AS FLOAT) / CAST((SUM(CONTACTOS) - SUM(CONTACTOS_TFT)) AS FLOAT)) * 100, 1) ELSE 0 END AS NUMERIC(5,1)) <= ".$mayorq."";
			
		
		}else if($combo==2){
			$color="orange";

			$mayorq=50;
			$menorq=78;

					
				

			$querySemaforo=$querySemaforo." HAVING CAST(CASE WHEN SUM(VISITAS) > 0 AND (SUM(CONTACTOS) - SUM(CONTACTOS_TFT)) > 0 THEN ROUND((CAST(SUM(VISITAS) AS FLOAT) / CAST((SUM(CONTACTOS) - SUM(CONTACTOS_TFT)) AS FLOAT)) * 100, 1) ELSE 0 END AS NUMERIC(5,1)) > ".$mayorq." AND CAST(CASE WHEN SUM(VISITAS) > 0 AND (SUM(CONTACTOS) - SUM(CONTACTOS_TFT)) > 0 THEN ROUND((CAST(SUM(VISITAS) AS FLOAT) / CAST((SUM(CONTACTOS) - SUM(CONTACTOS_TFT)) AS FLOAT)) * 100, 1) ELSE 0 END AS NUMERIC(5,1)) < ".$menorq."";
			
			
		}else if($combo==3){
		$color="yellow";

			$mayorq=78;
			$menorq=94;

			

			$querySemaforo=$querySemaforo." HAVING CAST(CASE WHEN SUM(VISITAS) > 0 AND (SUM(CONTACTOS) - SUM(CONTACTOS_TFT)) > 0 THEN ROUND((CAST(SUM(VISITAS) AS FLOAT) / CAST((SUM(CONTACTOS) - SUM(CONTACTOS_TFT)) AS FLOAT)) * 100, 1) ELSE 0 END AS NUMERIC(5,1)) >= ".$mayorq." AND CAST(CASE WHEN SUM(VISITAS) > 0 AND (SUM(CONTACTOS) - SUM(CONTACTOS_TFT)) > 0 THEN ROUND((CAST(SUM(VISITAS) AS FLOAT) / CAST((SUM(CONTACTOS) - SUM(CONTACTOS_TFT)) AS FLOAT)) * 100, 1) ELSE 0 END AS NUMERIC(5,1)) <= ".$menorq."";
			
			
		}else if($combo==4){
			$color="green";
			$mayorq=95;
			$menorq=0;

			$querySemaforo=$querySemaforo." HAVING CAST(CASE WHEN SUM(VISITAS) > 0 AND (SUM(CONTACTOS) - SUM(CONTACTOS_TFT)) > 0 THEN ROUND((CAST(SUM(VISITAS) AS FLOAT) / CAST((SUM(CONTACTOS) - SUM(CONTACTOS_TFT)) AS FLOAT)) * 100, 1) ELSE 0 END AS NUMERIC(5,1)) >= ".$mayorq."";
		}



		$querySemaforo=$querySemaforo."ORDER BY ESTADO,LINEA_ORDEN";

		//echo "MAPA: ".$querySemaforo."<br><br>";

		//START PINTA MAPA 2

		$rsPintaMapa = sqlsrv_query($conn,$querySemaforo, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

		$rows=sqlsrv_num_rows($rsPintaMapa);

		//echo $rows;
		if($rows==0){

			echo "<script>alert('sin registros');
				$('#screen').hide();
				$('#tblLocRep tbody').empty();
				limpia();
			</script>";
			return true;
		}

		//START PINTA MAPA
		$porcentaje=array();
		
		$array_estado=array();
		$array_edo_unico=array();
		$i=0;

		while($arrDA = sqlsrv_fetch_array($rsPintaMapa)){
			
				$array_estado[$i]=$arrDA['ESTADO'];

				$i++;
				
			}

			$array_edo_unico=array_unique($array_estado);
	


				echo "<script>limpia();</script>";
			foreach( $array_edo_unico as $clave => $estado){
					
				if($estado=="AGUASCALIENTES"){
					echo "<script>pintaAguascalientes('".$color."',LimiteAguascalientes,miMapa);</script>";
				}
		
				if($estado=="BAJA CALIFORNIA"){
					echo "<script>pintaBajaCalifornia('".$color."',LimiteBajCalifornia,miMapa);</script>";
				}
				
				if($estado=="BAJA CALIFORNIA SUR"){
					echo "<script>pintaBajaCaliforniaSur('".$color."',LimiteBajCaliforniaSur,miMapa);</script>";
				}
				
				if($estado=="CAMPECHE"){
					echo "<script>pintaCampeche('".$color."',LimiteCampeche,miMapa);</script>";
				}
				
				if($estado=="CHIAPAS"){
					echo "<script>pintaChiapas('".$color."',LimiteChiapas,miMapa);</script>";
				}
				
				if($estado=="CHIHUAHUA"){
					echo "<script>pintaChihuahua('".$color."',LimiteChihuahua,miMapa);</script>";
				}
				
				if($estado=="CIUDAD DE MEXICO"){
					echo "<script>pintaDistritoFederal('".$color."',LimiteDistritoFederal,miMapa);</script>";
				}
				
				if($estado=="COAHUILA"){
					echo "<script>pintaCoahuila('".$color."',LimiteCoahuila,miMapa);</script>";
				}
				
				if($estado=="COLIMA"){
					echo "<script>pintaColima('".$color."',LimiteColima,miMapa);</script>";
				}
				
				if($estado=="DURANGO"){
					echo "<script>pintaDurango('".$color."',LimiteDurango,miMapa);</script>";
				}
				
				if($estado=="GUANAJUATO"){
					echo "<script>pintaGuanajuato('".$color."',LimiteGuanajuato,miMapa);</script>";
				}
				
				if($estado=="GUERRERO"){
					echo "<script>pintaGuerrero('".$color."',LimiteGuerrero,miMapa);</script>";
				}
				
				if($estado=="HIDALGO"){
					echo "<script>pintaHidalgo('".$color."',LimiteHidalgo,miMapa);</script>";
				}
				
				if($estado=="JALISCO"){
					echo "<script>pintaJalisco('".$color."',LimiteJalisco,miMapa);</script>";
				}
				
				if($estado=="MEXICO"){
					echo "<script>pintaEstadoDeMexico('".$color."',LimiteEstadoDeMexico,miMapa);</script>";
				}
				
				if($estado=="MICHOACAN"){
					echo "<script>pintaMichoacan('".$color."',LimiteMichoacan,miMapa);</script>";
				}
				
				if($estado=="MORELOS"){
					echo "<script>pintaMorelos('".$color."',LimiteMorelos,miMapa);</script>";
				}
				
				if($estado=="NAYARIT"){
					echo "<script>pintaNayarit('".$color."',LimiteNayarit,miMapa);</script>";
				}
				
				if($estado=="NUEVO LEON"){
					echo "<script>pintaNuevoLeon('".$color."',LimiteNuevoLeon,miMapa);</script>";
				}
				
				if($estado=="OAXACA"){
					echo "<script>pintaOaxaca('".$color."',LimiteOaxaca,miMapa);</script>";
				}
				
				if($estado=="PUEBLA"){
					echo "<script>pintaPuebla('".$color."',LimitePuebla,miMapa);</script>";
				}
				
				if($estado=="QUERETARO"){
					echo "<script>pintaQueretaro('".$color."',LimiteQueretaro,miMapa);</script>";
				}
				
				if($estado=="QUINTANA ROO"){
					echo "<script>pintaQuintanaRoo('".$color."',LimiteQuintanaRoo,miMapa);</script>";
				}
				
				if($estado=="SAN LUIS POTOSI"){
					echo "<script>pintaSanLuisPotosi('".$color."',LimiteSanLuisPotosi,miMapa);</script>";
				}
				
				if($estado=="SINALOA"){
					echo "<script>pintaSinaloa('".$color."',LimiteSinaloa,miMapa);</script>";
				}
				
				if($estado=="SONORA"){
					echo "<script>pintaSonora('".$color."',LimiteSonora,miMapa);</script>";
				}
				
				if($estado=="TABASCO"){
					echo "<script>pintaTabasco('".$color."',LimiteTabasco,miMapa);</script>";
				}
				
				if($estado=="TAMAULIPAS"){
					echo "<script>pintaTamaulipas('".$color."',LimiteTamaulipas,miMapa);</script>";
				}
				
				if($estado=="TLAXCALA"){
					echo "<script>pintaTlaxcala('".$color."',LimiteTlaxcala,miMapa);</script>";
				}
				
				if($estado=="VERACRUZ"){
					echo "<script>pintaVeracruz('".$color."',LimiteVeracruz,miMapa);</script>";
				}
				
				if($estado=="YUCATAN"){
					echo "<script>pintaYucatan('".$color."',LimiteYucatan,miMapa);</script>";
				}
				
				if($estado=="ZACATECAS"){
					echo "<script>pintaZacatecas('".$color."',LimiteZacatecas,miMapa);</script>";
				}

			}


			
		//END PINTA MAPA 2


		//START PINTA TABLA 2
		$rsTabla = sqlsrv_query($conn, $querySemaforo);
	
		$rsRowsTabla = sqlsrv_query($conn, $querySemaforo, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		$rowsTabla=sqlsrv_num_rows($rsRowsTabla);

		echo "<script>$('#tblLocRep tbody').empty(); </script>";

		$por=0;
		$aux2=0;
		$aux3=0;
		$aux4=0;
		$countVpT=0;
		$countVp=0;
		$countVv=0;

		while($dataTabla = sqlsrv_fetch_array($rsTabla)){

			$aux2=$dataTabla[3];

			$aux3=$dataTabla[4];

			$aux4=$dataTabla[5];

			$countVpT=$countVpT+$aux2;

			$countVp=$countVp+$aux3;

			$countVv=$countVv+$aux4;

			$formato_vis_presT = number_format($dataTabla[3]);

			$formato_vis_pres = number_format($dataTabla[4]);

			$formato_vis_vi = number_format($dataTabla[5]);
			$por=$dataTabla[6];

			if($por<50){

			 	if($por<1){
			 		echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color: #ff5232;color:white;\" ><td>".$dataTabla[0]."</td><td>".$dataTabla[1]."</td><td>".$formato_vis_presT."</td><td>".$formato_vis_pres."</td><td>".$formato_vis_vi."</td><td>"."0".$dataTabla[6]."%"."</td><td>".$dataTabla[7]."</td></tr>');</script>";
			 	}else{
			 		echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color: #ff5232;color:white;\" ><td>".$dataTabla[0]."</td><td>".$dataTabla[1]."</td><td>".$formato_vis_presT."</td><td>".$formato_vis_pres."</td><td>".$formato_vis_vi."</td><td>".$dataTabla[6]."%"."</td><td>".$dataTabla[7]."</td></tr>');</script>";
			 	}

			

				
			}

			if($por > 50 && $por < 78){

				if($por<1){
					echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color: #ffa040;color:black;\" ><td>".$dataTabla[0]."</td><td>".$dataTabla[1]."</td><td>".$formato_vis_presT."</td><td>".$formato_vis_pres."</td><td>".$formato_vis_vi."</td><td>"."0".$dataTabla[6]."%"."</td><td>".$dataTabla[7]."</td></tr>');</script>";
			 	}else{
			 		echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color: #ffa040;color:black;\" ><td>".$dataTabla[0]."</td><td>".$dataTabla[1]."</td><td>".$formato_vis_presT."</td><td>".$formato_vis_pres."</td><td>".$formato_vis_vi."</td><td>".$dataTabla[6]."%"."</td><td>".$dataTabla[7]."</td></tr>');</script>";
			 	}
				
 
			}

			if($por >=78 && $por <= 94 ){

				if($por<1){
					echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color:  #ffff90;color:black;\" ><td>".$dataTabla[0]."</td><td>".$dataTabla[1]."</td><td>".$formato_vis_presT."</td><td>".$formato_vis_pres."</td><td>".$formato_vis_vi."</td><td>"."0".$dataTabla[6]."%"."</td><td>".$dataTabla[7]."</td></tr>');</script>";
			 	}else{
			 		echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color:  #ffff90;color:black;\" ><td>".$dataTabla[0]."</td><td>".$dataTabla[1]."</td><td>".$formato_vis_presT."</td><td>".$formato_vis_pres."</td><td>".$formato_vis_vi."</td><td>".$dataTabla[6]."%"."</td><td>".$dataTabla[7]."</td></tr>');</script>";
			 	}
				
			}

			if($por >= 95 ){

				if($por<1){
					echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color: #84e283;color:black;\" ><td>".$dataTabla[0]."</td><td>".$dataTabla[1]."</td><td>".$formato_vis_presT."</td><td>".$formato_vis_pres."</td><td>".$formato_vis_vi."</td><td>"."0".$dataTabla[6]."%"."</td><td>".$dataTabla[7]."</td></tr>');</script>";
			 	}else{
			 		echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color: #84e283;color:black;\" ><td>".$dataTabla[0]."</td><td>".$dataTabla[1]."</td><td>".$formato_vis_presT."</td><td>".$formato_vis_pres."</td><td>".$formato_vis_vi."</td><td>".$dataTabla[6]."%"."</td><td>".$dataTabla[7]."</td></tr>');</script>";
			 	}

				
			}

		}

		 $formato_countVpT = number_format($countVpT,0,"'",",");

		 $formato_countVp = number_format($countVp,0,"'",",");

		 $formato_countVv = number_format($countVv,0,"'",",");

		 echo "<script>

			$('#totalv').text('".$formato_countVpT."');

			$('#totalp').text('".$formato_countVp."');

			$('#totalvi').text('".$formato_countVv."');
	
			total_col5=0;
		
			$('#tblLocRep tbody').find('tr').each(function (i, el) {
		             
		        //Voy incrementando las variables segun la fila ( .eq(0) representa la fila 1 )     
		       
				total_col5 += parseFloat($(this).find('td').eq(6).text());
				
				
		                
		    });


			$('#incidencia').text(total_col5);
			$('#totalrep').text('".$rowsTabla."');

	
	</script>";


		//END PINTA TABLA

		echo "<script>$('#screen').hide();</script>";

	}


?>