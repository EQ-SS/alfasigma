
<?php

include "../conexion.php";
$tipoUsuario=$_POST['tipoUsuario'];
//$idUsuario='D2F21D53-45D2-4DD5-AD4A-ECFA85EBDA68';
	
	$idUsuario=$_POST['idUsuario'];
	//REGRESAR ESTADO DE LA RUTA
	
	$query="select distinct u.USER_SNR, u.USER_NR,u.LNAME,st.NAME from users u
inner join USER_TERRIT ut on u.USER_SNR = ut.USER_SNR and u.rec_stat = 0 and ut.REC_STAT = 0
inner join inst i on ut.INST_SNR = i.INST_SNR and i.REC_STAT = 0
inner join city c on c.CITY_SNR = i.CITY_SNR and c.REC_STAT = 0
inner join state st on st.STATE_SNR = c.STATE_SNR ";

if($idUsuario != ''){
		$query .= " where u.USER_SNR = '".$idUsuario."' ";
		
	}
	//echo $query;
	
	$rs = sqlsrv_query($conn, $query);

 $estado="";
 $user="";
 $user_nr="";
 
while($est = sqlsrv_fetch_array($rs)){
		$estado=$est['NAME'];
		$user=$est['USER_SNR'];
		$user_nr=$est['USER_NR'];
	}
	
	//echo $user_nr;


$queryTablaRepre="WITH TOTAL AS (SELECT 
	EDO.NAME ESTADO,
	U.USER_NR+' - '+ U.LNAME+' '+U.FNAME AS REPRESENTANTE,
	EDO.STATE_SNR,
	ISNULL(COUNT(DISTINCT VPP.PERS_SNR) + COUNT(DISTINCT VPV.PERS_SNR),0) VISITAS, 
	ISNULL(COUNT(DISTINCT VPP.PERS_SNR),0) VISITAS_PRES, ISNULL(COUNT(DISTINCT VPV.PERS_SNR),0) VISITAS_VIRT, 
	LINEA.DAILY_CONTACTS * COUNT(DISTINCT VP.USER_SNR) AS CONTACTOS,
	ISNULL((SELECT NAME_SHORT FROM CODELIST WHERE CODELIST.CLIST_SNR=DRC.DAY_CODE_SNR),'') INCIDENCIA,
	COUNT(DISTINCT DR.USER_SNR) *  LINEA.DAILY_CONTACTS  CONTACTOS_TFT
	FROM PERSON P 
	INNER JOIN CYCLES CYC ON CYC.REC_STAT=0 AND CAST(dateadd(DAY,0,GETDATE()) AS DATE) BETWEEN CYC.START_DATE AND CAST(dateadd(DAY,0,CYC.FINISH_DATE) AS DATE)
	LEFT OUTER JOIN VISITPERS VP ON P.PERS_SNR=VP.PERS_SNR AND VP.REC_STAT=0 AND VISIT_DATE = CAST(dateadd(DAY,0, GETDATE()) AS DATE)
	INNER JOIN PERS_SREP_WORK PSW ON P.PERS_SNR=PSW.PERS_SNR AND PSW.REC_STAT=0 
	INNER JOIN INST I ON I.INST_SNR=PSW.INST_SNR AND I.REC_STAT=0 
	INNER JOIN USERS U ON U.USER_SNR=PSW.USER_SNR AND U.REC_STAT=0 AND U.STATUS=1 AND U.USER_TYPE=4 
	INNER JOIN COMPLINE LINEA ON U.CLINE_SNR=LINEA.CLINE_SNR 
	INNER JOIN CITY CY ON CY.CITY_SNR=I.CITY_SNR AND CY.REC_STAT=0 
	INNER JOIN DISTRICT DTO ON CY.DISTR_SNR=DTO.DISTR_SNR AND DTO.REC_STAT=0 
	INNER JOIN STATE EDO ON EDO.STATE_SNR=DTO.STATE_SNR AND EDO.REC_STAT=0 
	INNER JOIN CODELIST ESTATUS ON ESTATUS.CLIST_SNR=P.STATUS_SNR
	LEFT OUTER JOIN VISITPERS VPP ON P.PERS_SNR=VPP.PERS_SNR AND VPP.REC_STAT=0 AND VPP.VISIT_DATE = CAST(dateadd(DAY,0, GETDATE()) AS DATE) AND VPP.VISIT_CODE_SNR in ('2B3A7099-AC7D-47A3-A274-F0B029791801','E9A14663-1F3E-4707-915B-1891CC34AD1B') 
	LEFT OUTER JOIN VISITPERS VPV ON P.PERS_SNR=VPV.PERS_SNR AND VPV.REC_STAT=0 AND VPV.VISIT_DATE = CAST(dateadd(DAY,0, GETDATE()) AS DATE) AND VPV.VISIT_CODE_SNR in ('5655BC78-6002-4097-82CC-8BA7E1FBD5FC') 
	LEFT OUTER JOIN DAY_REPORT DR ON DR.USER_SNR=U.USER_SNR AND DR.REC_STAT=0 AND DR.START_DATE = CAST(dateadd(DAY,0, GETDATE()) AS DATE)
	LEFT OUTER JOIN DAY_REPORT_CODE DRC ON DRC.DAYREPORT_SNR=DR.DAYREPORT_SNR AND DRC.REC_STAT=0
	WHERE LINEA.CLINE_SNR in ('AB553EB3-5051-4812-9277-0467BC6E9A8B','4E74577D-89EA-4110-A307-5517299BD8A2','D8B27845-01EB-4E14-BA37-107CFE9776F3', '036AC44A-3326-4F09-8A1E-CF12282E214C','EF565E9A-0374-430A-B276-4AA189F01137','A6192204-9C60-49C2-B25F-3D0B05A4F2ED','109EF54F-AAD0-4D22-9483-2CB5D688F2FA', 'B4564E23-B1CD-46B5-92E2-036B16C295F8','0062EF5E-DA92-4BA2-B9DA-040D9C64EF7B','31BC2694-306E-4703-A312-7EAA22083993','AA6C0306-5C52-4A15-A644-7551332B18D5') 
	AND P.REC_STAT=0 AND EDO.NAME<>'' AND ESTATUS.NAME='ACTIVO' AND LINEA.MAP=1
	AND U.USER_SNR = (SELECT USER_SNR FROM USERS WHERE USER_NR='".$user_nr."') 
	GROUP BY EDO.NAME,EDO.STATE_SNR,LINEA.DAILY_CONTACTS,U.LNAME,U.USER_NR,U.FNAME,DRC.DAY_CODE_SNR
	)SELECT 
		ESTADO,
		REPRESENTANTE,
		SUM(VISITAS) VISITAS,
		SUM(VISITAS_PRES) VISITAS_PRES,
		SUM(VISITAS_VIRT) VISITAS_VIRT,
		CAST(CASE WHEN SUM(VISITAS) > 0 AND (SUM(CONTACTOS) - SUM(CONTACTOS_TFT)) > 0 THEN ROUND((CAST(SUM(VISITAS) AS FLOAT) / CAST((SUM(CONTACTOS) - SUM(CONTACTOS_TFT)) AS FLOAT)) * 100, 1) ELSE 0 END AS NUMERIC(5,1)) PORCIENTO,
		INCIDENCIA TFT
		FROM TOTAL 
		GROUP BY ESTADO,STATE_SNR,REPRESENTANTE,INCIDENCIA";

//echo $queryTablaRepre;
$rsTablaRepre = sqlsrv_query($conn, $queryTablaRepre);

$rsEst2 = sqlsrv_query($conn,$queryTablaRepre, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
$rows2=sqlsrv_num_rows($rsEst2);


$linea='';
$rep='';
$vis='';
$vpres='';
$vvirtual='';
$cob='';

echo "<script>$('#tblLocRep tbody').empty();</script>";                         								                                                  


$por=0;
$contIncidencia=0;
while($repre = sqlsrv_fetch_array($rsTablaRepre)){
		$rep=$repre['REPRESENTANTE'];
		$vis=$repre['VISITAS'];
		$vpres=$repre['VISITAS_PRES'];
		$vvirtual=$repre['VISITAS_VIRT'];
		$cob=$repre['PORCIENTO'];
		$incidencia=$repre['TFT'];
		$repre2=utf8_encode($rep);
		$edo=$repre['ESTADO'];

		$por=$cob;

		if($incidencia != ""){
			$contIncidencia++;
		}


		if($por<50){
			echo "<script>
				$('#titu1').empty();
				$('#titu1').text('Edo');
			</script>";

			if($por<1){
				echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color: #ff5232;color:white;\" ><td>".$edo."</td><td>".$repre2."</td><td>".$vis."</td><td>".$vpres."</td><td>".$vvirtual."</td><td>"."0".$cob."%"."</td><td>".$incidencia."</td></tr>');</script>";
			}else{
				echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color: #ff5232;color:white;\" ><td>".$edo."</td><td>".$repre2."</td><td>".$vis."</td><td>".$vpres."</td><td>".$vvirtual."</td><td>".$cob."%"."</td><td>".$incidencia."</td></tr>');</script>";
			}
			

			

				
			}

			if($por > 50 && $por < 78){

			echo "<script>
				$('#titu1').empty();
				$('#titu1').text('Edo');
			</script>";

			if($por<1){
				echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color: #ffa040;color:black;\" ><td>".$edo."</td><td>".$repre2."</td><td>".$vis."</td><td>".$vpres."</td><td>".$vvirtual."</td><td>"."0".$cob."%"."</td><td>".$incidencia."</td></tr>');</script>";
			}else{
				echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color: #ffa040;color:black;\" ><td>".$edo."</td><td>".$repre2."</td><td>".$vis."</td><td>".$vpres."</td><td>".$vvirtual."</td><td>".$cob."%"."</td><td>".$incidencia."</td></tr>');</script>";
			}

				
 
			}

			if($por >=78 && $por <= 94 ){

			echo "<script>
				$('#titu1').empty();
				$('#titu1').text('Edo');
			</script>";

			if($por<1){
				echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color: #ffff90;color:black;\" ><td>".$edo."</td><td>".$repre2."</td><td>".$vis."</td><td>".$vpres."</td><td>".$vvirtual."</td><td>"."0".$cob."%"."</td><td>".$incidencia."</td></tr>');</script>";
			}else{
				echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color: #ffff90;color:black;\" ><td>".$edo."</td><td>".$repre2."</td><td>".$vis."</td><td>".$vpres."</td><td>".$vvirtual."</td><td>".$cob."%"."</td><td>".$incidencia."</td></tr>');</script>";
			}

				
			}

			if($por >= 95 ){

			echo "<script>
				$('#titu1').empty();
				$('#titu1').text('Edo');
			</script>";

			if($por<1){
				echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color: #84e283;color:black;\" ><td>".$edo."</td><td>".$repre2."</td><td>".$vis."</td><td>".$vpres."</td><td>".$vvirtual."</td><td>"."0".$cob."%"."</td><td>".$incidencia."</td></tr>');</script>";
			}else{
				echo "<script>$('#tblLocRep tbody').append('<tr style=\"background-color: #84e283;color:black;\" ><td>".$edo."</td><td>".$repre2."</td><td>".$vis."</td><td>".$vpres."</td><td>".$vvirtual."</td><td>".$cob."%"."</td><td>".$incidencia."</td></tr>');</script>";
			}

				
			}
		
		
	}
	
	
	
	echo "<script>
	var total_col1=0;
	var total_col2=0;
	var total_col3=0;
	var total_col4=0;
	var total_col5=0;
	
				$('#tblLocRep tbody').find('tr').each(function (i, el) {
             
        //Voy incrementando las variables segun la fila ( .eq(0) representa la fila 1 )     
        total_col1 += parseFloat($(this).find('td').eq(2).text());
        total_col2 += parseFloat($(this).find('td').eq(3).text());
		total_col3 += parseFloat($(this).find('td').eq(4).text());
		total_col4 += parseFloat($(this).find('td').eq(5).text());
		total_col5 += parseFloat($(this).find('td').eq(6).text());

		
                
    });
	
	$('#totalv').text(total_col1);
	$('#totalp').text(total_col2);
	$('#totalvi').text(total_col3);
	$('#incidencia').text(".$contIncidencia.");
	$('#totalrep').text(".$rows2.");
	$('#totalcob').text('-'+'%');
	$('#screen').hide();
	
	
	</script>";
	
	
	//PINTAR MAPA 

	$rsPintaEstados=sqlsrv_query($conn,$queryTablaRepre, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	$rows=sqlsrv_num_rows($rsPintaEstados);


	$rsEstados= sqlsrv_query($conn, $queryTablaRepre);


$porcentaje=array();
$color= array();
$estado=array();
while($data = sqlsrv_fetch_array($rsEstados)){
	$porcentaje[]=$data['PORCIENTO'];
		$estado[]=$data['ESTADO'];
			 
			
	}
	
	for($i=0;$i<$rows;$i++){
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



?>


