<?php
	include "../conexion.php";
	$tam = array(350,150,150); 
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$registrosPorPagina = 20;
		$numPagina = $_POST['pagina'];
		$ids = str_replace(",","','",$_POST['ids']);
		$ids = str_replace("'',''","','",$ids);
		$estatus = $_POST['estatus'];
		$fechaI = $_POST['hdnFechaIListado'];
		$fechaF = $_POST['hdnFechaFListado'];
		$registroIni = $numPagina * $registrosPorPagina - $registrosPorPagina;
		
		$qMedicos ="select 
		upper(U.lname)+' '+upper(U.fname) as Representante
		,cast( convert(date, UL.start_action_time, 20) as nvarchar(10)) as 'Fecha Sincronizacion'
		,convert(char(5), UL.start_action_time, 108) as 'Hora Sincronizacion'
		 
		from USER_LOGGING UL
		inner join Users as U on U.user_snr = UL.user_snr and U.rec_stat=0
		 
		where U.user_type=4 
		and U.status=1
		and UL.type='1'
		and U.user_snr in ('".$ids."')
		and convert(date,UL.start_action_time,101) between '".$fechaI."' and '".$fechaF."'
		/* and U.user_snr in ('2B6B9502-91B9-42FA-A337-4D98478483BE')
		and convert(date,UL.start_action_time,101) between '2019-08-01' and '2019-08-30' */
		 
		group by U.lname,U.fname,convert(date, UL.start_action_time, 20),convert(char(5), UL.start_action_time, 108) 
		 
		order by U.lname,U.fname,convert(date, UL.start_action_time, 20),convert(char(5), UL.start_action_time, 108) ";
		
		//echo $qMedicos."<br>";
		
		$registroIni = $numPagina * $registrosPorPagina - $registrosPorPagina;
		
		$tope = "OFFSET ".$registroIni." ROWS 
			FETCH NEXT ".$registrosPorPagina." ROWS ONLY ";
				
		$rsMedicosTotal = sqlsrv_query($conn, utf8_decode($qMedicos), array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		/*if(!sqlsrv_query($conn, utf8_decode($qMedicos), array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ))){
			echo "si ejecuto";
		}else{
			echo "no ejecuto";
		}*/
		
		$totalRegistros = sqlsrv_num_rows($rsMedicosTotal);

		$rsMedicos = sqlsrv_query($conn, utf8_decode($qMedicos.$tope));
		
		$paginas = ceil($totalRegistros / $registrosPorPagina);
		
		echo $qMedicos.$tope;
		
		echo "<script>
			$('#tblListadoMedicos tbody').empty();
			$('#tblListadoMedicos tfoot').empty();
			$('#tblPaginasListadoMedicos').empty();";
		//$row = '';
		while($regMedico = sqlsrv_fetch_array($rsMedicos)){
			$row = '<tr>';

			for($j=0;$j<sqlsrv_num_fields($rsMedicos);$j++){
				if(is_object($regMedico[$j])){
					foreach ($regMedico[$j] as $key => $val) {
						if(strtolower($key) == 'date'){
							$regMedico[$j] = substr($val, 0, 10);
						}
					}
				}
				
			}
			$row .= '</tr>';
			echo "$('#tblListadoMedicos tbody').append('".$row."');
			";	
		}
	}
	//$pie = '';
	//echo "hola: ".$totalRegistros." > ".$registrosPorPagina."<br>";
	if($totalRegistros > $registrosPorPagina){
		$pie = "<tr><td align='center' width='1400px'>";
		$idsEnviar = str_replace("'","",$ids);
		if($numPagina > 1){
			$anterior = $numPagina - 1;
			$pie .= "<a href='#' onClick='nuevaPaginaListados(1,\"".$idsEnviar."\",\"listadoSincroniza\",\"".$estatus."\");'>inicio</a>&nbsp;&nbsp;";
			$pie .= "<a href='#' onClick='nuevaPaginaListados(".$anterior.",\"".$idsEnviar."\",\"listadoSincroniza\",\"".$estatus."\");'>anterior</a>&nbsp;&nbsp;";
		}
		$antes = $numPagina-5;
		$despues = $numPagina+5;
		for($i=1;$i<=$paginas;$i++){
			if($i == $numPagina){
				$pie .= $i."&nbsp;&nbsp;";
			}else{
				if($i > $despues || $i < $antes){
					//$tabla .= "<a href='#' onClick='nuevaPagina(".$i.",\"".$hoy."\",\"".$idsEnviar."\",\"".$visitados."\");'>".$i."</a>&nbsp;&nbsp;";
				}
			}
		}
		if($numPagina < $paginas){
			$siguiente = $numPagina + 1;
			$pie .= "<a href='#' onClick='nuevaPaginaListados(".$siguiente.",\"".$idsEnviar."\",\"listadoSincroniza\",\"".$estatus."\");'>Siguiente</a>&nbsp;&nbsp;";
			$pie .= "<a href='#' onClick='nuevaPaginaListados(".$paginas.",\"".$idsEnviar."\",\"listadoSincroniza\",\"".$estatus."\");'>Fin</a>&nbsp;&nbsp;";
		}
		$pie .= "Pag. ".$numPagina." de ".$paginas."&nbsp;&nbsp;&nbsp; Registros : ".$totalRegistros;
		$pie .= "</td>";
		$pie .= "</tr>";
	}else{
		$pie .= "<tr><td colspan='16' align='center'>";
		$pie .= "Registros : ".$totalRegistros;
		$pie .= "</td></tr>";
	}
	echo "$('#tblListadoMedicos tfoot').append('".str_replace("'","\'",$pie)."');
	$('#divCargando').hide();
	";
	echo "</script>";
?>