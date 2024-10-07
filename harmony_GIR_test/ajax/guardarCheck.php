<?php

include "../conexion.php";
	
	if(! $conn){
		echo "<script>alertErrorServidor();</script>";
	}else{
		$idUsuario=$_POST['idUsuario'];	
		$lat=$_POST['lat'];	
		$lon=$_POST['lon'];	
		//$btnPress=$_POST['btnPress'];	
		
		$QueryCheck="SELECT TOP(1) TYPE FROM USER_CHECK WHERE USER_SNR='".$idUsuario."'
ORDER BY CREATION_TIMESTAMP DESC";
$chek =sqlsrv_query($conn, $QueryCheck);
$valchek="";
while($qcheck = sqlsrv_fetch_array($chek)){
				$valchek = $qcheck['TYPE'];
				
			}
		
		
		
		//print_r(localtime());
		
	$hora=date("H:i");
	$fecha=date("Y-m-d");
	
	
	
	
	
	//print_r($fecha);
		
	
	
	$date=$fecha;
	$time=$hora;
	$latitud=$lat;
	$longitud=$lon;
	$rec_stat=0;
	
	
	$type=$_POST['type'];
	
	if($type==1){
		echo "<script>notificationInicioDeLabores();
		
		 document.getElementById('btnCheck1').style.display = 'none';
			
			
		</script>";
	}
	
	
	
	
	
	/*
	else{
		$type=0;
		echo "<script>alert('SE TERMINARON LABORES');
		fotoMostrada='logout';
		</script>";
	}
	
	*/
		
		$query1="INSERT INTO USER_CHECK ( 
	USER_CHECK_SNR,
	USER_SNR,
	TYPE,
	DATE,
	TIME,
	LATITUDE,
	LONGITUDE,
	REC_STAT,
	SYNC,
	CREATION_TIMESTAMP,
	SYNC_TIMESTAMP
		) VALUES (
		NEWID(),
		'".$idUsuario."',
		'".$type."',
		'".$date."',
		'".$time."',
		'".$latitud."',
		'".$longitud."',
		0,
		0,
		getdate(),
		getdate()
		)";
		
		if(! sqlsrv_query($conn, $query1)){
				echo "inserta Check: ".$query1."<br>";
			}
	
		
	}




?>