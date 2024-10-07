<?php
	//$serverName = "216.157.93.51";
	$serverName = "216.152.129.67";
	$uid = "sa";
	$pwd = "saf";
	$db = "Cedulas";
	
	$connectionInfo = array( "Database"=>$db, "UID"=>$uid, "PWD"=>$pwd);
	$conn = sqlsrv_connect( $serverName, $connectionInfo);
	set_time_limit(0);

	$buscar=array("Á","É","Í","Ó","Ú","Ñ",",");
	$reemplazar=array("A","E","I","O","U","N","");	
	
	$vi = 9400868 + 1;
	$vf = $vi + 599131;
	
	for ($i=$vi; $i<$vf; $i++){
		if (strlen($i) < 7) {
			$ced = str_repeat("0", 7-strlen($i)).$i;
		}else {
			$ced = $i;
		}

		$content = file_get_contents('http://search.sep.gob.mx/solr/cedulasCore/select?q='.$ced.'&rows=10&indent=on&wt=json');
		$json = json_decode($content, true);
		$registros = $json["response"]["docs"];

		for ($c=0; $c<count($registros); $c++){
			$numCed = str_ireplace($buscar,$reemplazar,$registros[$c]["numCedula"]);
			$tipo = str_ireplace($buscar,$reemplazar,$registros[$c]["tipo"]);
			$pat = str_ireplace($buscar,$reemplazar,$registros[$c]["paterno"]);
			$mat = str_ireplace($buscar,$reemplazar,$registros[$c]["materno"]);
			$nomb = str_ireplace($buscar,$reemplazar,$registros[$c]["nombre"]);
			$inst = str_ireplace($buscar,$reemplazar,$registros[$c]["institucion"]);
			$gen = str_ireplace($buscar,$reemplazar,$registros[$c]["genero"]);
			$anio = str_ireplace($buscar,$reemplazar,$registros[$c]["anioRegistro"]);
			$tit = str_ireplace($buscar,$reemplazar,$registros[$c]["titulo"]);
			
			//$ex = "select count(titulo) EXISTE from titulos_meds where titulo='".$tit."' ";
			//$cons = sqlsrv_query($conn, $ex);
			//$cons2 = sqlsrv_fetch_array($cons);
			
			//if ($cons2['EXISTE']==1){
				$qry = "insert into Cedulas_web values ('".$numCed."','".$tipo."','".$pat."','".$mat."','".$nomb."','".$inst."','".$gen."','".$anio."','".$tit."','') ";
				sqlsrv_query($conn, utf8_decode($qry));
			//}

			//echo $qry."\n";
			//echo $registros[$c]["nombre"]."\n";
		}
	}
	echo "\n"."Terminado ".$ced ."\n";
 ?>