<?php
	include "../conexion.php";
	
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$palabra = $_POST['palabra'];
		$idUsuario = $_POST['idUsuario'];
		$ids = $_POST['ids'];
		/*if(isset($_POST['dia']) && $_POST['dia'] != ''){
			$dia = $_POST['dia'];
		}else{
			$dia = '';
		}*/
		
		if(isset($_POST['fecha']) && $_POST['fecha'] != ''){
			$fecha = $_POST['fecha'];
		}else{
			$fecha = '';
		}
		
		if(isset($_POST['tipoUsuario']) && $_POST['tipoUsuario'] != ''){
			$tipoUsuario = $_POST['tipoUsuario'];
		}else{
			$tipoUsuario = 0;
		}
		if(isset($_POST['repre']) && $_POST['repre'] != '' && $_POST['repre'] != '00000000-0000-0000-0000-000000000000'){
			$repre = $_POST['repre'];
		}else{
			$repre = '';
		}
		
		$instituciones = "select top 10 i.inst_snr, it.name as tipo, i.name as nombre, i.STREET1 as calle, 
			city.name as colonia, d.NAME as delegacion, state.NAME as estado, city.zip as cp,
			u.lname as ruta, u.user_snr
			from inst i 
			left outer join USER_TERRIT ut on i.INST_SNR = ut.inst_SNR and ut.REC_STAT = 0
			left outer join INST_TYPE it on it.INST_TYPE = i.INST_TYPE 
			left outer join city on city.CITY_SNR = i.CITY_SNR 
			left outer join DISTRICT d on d.DISTR_SNR = city.DISTR_SNR 
			left outer join STATE on state.STATE_SNR = city.STATE_SNR 
			left outer join BRICK bri on bri.BRICK_SNR = city.BRICK_SNR 
			left outer join USERS u on u.user_snr = ut.user_snr
			where u.user_snr in ('".$ids."') 
			and i.rec_stat = 0
			and i.status_snr = 'B405F75D-499E-4EB8-AAC8-C89F2CA080A4' 
			";

		$instFiltradas = "select i.inst_snr, it.name as tipo, i.name as nombre, i.STREET1 as calle, 
			city.name as colonia, d.NAME as delegacion, state.NAME as estado, city.zip as cp,
			u.lname as ruta, u.user_snr
			from inst i 
			left outer join USER_TERRIT ut on i.INST_SNR = ut.inst_SNR and ut.REC_STAT = 0
			left outer join INST_TYPE it on it.INST_TYPE = i.INST_TYPE 
			left outer join city on city.CITY_SNR = i.CITY_SNR 
			left outer join DISTRICT d on d.DISTR_SNR = city.DISTR_SNR 
			left outer join STATE on state.STATE_SNR = city.STATE_SNR 
			left outer join BRICK bri on bri.BRICK_SNR = city.BRICK_SNR 
			left outer join USERS u on u.user_snr = ut.user_snr
			where u.user_snr in ('".$ids."') 
			and i.rec_stat = 0
			and i.status_snr = 'B405F75D-499E-4EB8-AAC8-C89F2CA080A4' 
			";

		if($palabra == ''){
			//$instituciones .= " and i.name like '%".$palabra."%' or i.STREET1 like '%".$palabra."%'";
			//$instituciones .= " and i.STREET1 like '%".$palabra."%'";
			
			if($repre != ''){
				$instituciones .= " and u.user_snr = '".$repre."'";
			}
			
			$instituciones .= " order by nombre";
			
			//echo $instituciones."<br>";
			//echo "tipoUsuario: ".$tipoUsuario."<br>";
			echo "<script>
				$('#tblBuscarInst tbody').empty();
				$('#totalInstBuscados').empty();
				$('#totalInstMedBuscados').empty();";
			
			$rsInst = sqlsrv_query($conn, $instituciones);
			while($inst = sqlsrv_fetch_array($rsInst)){
				//if($fecha == '' && $dia == ''){
				if($fecha == ''){
					if($tipoUsuario == 4){
						echo "$('#tblBuscarInst tbody').append('<tr onClick=\"instSeleccionada(\'".$inst['inst_snr']."\');\"><td style=\"width:12%;\">".$inst['tipo']."</td><td style=\"width:16%;\">".$inst['nombre']."</td><td style=\"width:16%;\">".$inst['calle']."</td><td style=\"width:16%;\">".$inst['colonia']."</td><td style=\"width:9%;\">".$inst['cp']."</td><td style=\"width:16%;\">".$inst['delegacion']."</td><td style=\"width:15%;\">".$inst['estado']."</td></tr>');";
					}else{
						echo "$('#tblBuscarInst tbody').append('<tr onClick=\"instSeleccionada(\'".$inst['inst_snr']."\');\"><td style=\"width:8%;\">".explode(" ",$inst['ruta'])[0]."</td><td style=\"width:11%;\">".$inst['tipo']."</td><td style=\"width:15%;\">".$inst['nombre']."</td><td style=\"width:15%;\">".$inst['calle']."</td><td style=\"width:15%;\">".$inst['colonia']."</td><td style=\"width:8%;\">".$inst['cp']."</td><td style=\"width:15%;\">".$inst['delegacion']."</td><td style=\"width:13%;\">".$inst['estado']."</td></tr>');";
					}
				}else{
					if($tipoUsuario == 4){
						echo "$('#tblBuscarInst tbody').append('<tr onClick=\"nuevoPlanInst(\'".$inst['inst_snr']."\',\'".$fecha."\',\'\');\"><td style=\"width:12%;\">".$inst['tipo']."</td><td style=\"width:16%;\">".$inst['nombre']."</td><td style=\"width:16%;\">".$inst['calle']."</td><td style=\"width:16%;\">".$inst['colonia']."</td><td style=\"width:9%;\">".$inst['cp']."</td><td style=\"width:16%;\">".$inst['delegacion']."</td><td style=\"width:15%;\">".$inst['estado']."</td></tr>');";
					}else{
						echo "$('#tblBuscarInst tbody').append('<tr onClick=\"nuevoPlanInst(\'".$inst['inst_snr']."\',\'".$fecha."\',\'".$inst['user_snr']."\');\"><td style=\"width:8%;\">".explode(" ",$inst['ruta'])[0]."</td><td style=\"width:11%;\">".$inst['tipo']."</td><td style=\"width:15%;\">".$inst['nombre']."</td><td style=\"width:15%;\">".$inst['calle']."</td><td style=\"width:15%;\">".$inst['colonia']."</td><td style=\"width:8%;\">".$inst['cp']."</td><td style=\"width:15%;\">".$inst['delegacion']."</td><td style=\"width:13%;\">".$inst['estado']."</td></tr>');";
					}
				}
			}
			echo "</script>";

		}else{
			
			$instFiltradas .= " and (i.name like '%".$palabra."%' or i.STREET1 like '%".$palabra."%')";
			//$instituciones .= " and i.STREET1 like '%".$palabra."%'";
			
			if($repre != ''){
				$instFiltradas .= " and u.user_snr = '".$repre."'";
			}
			
			$instFiltradas .= " order by nombre";
			
			//echo $instituciones."<br>";
			//echo "tipoUsuario: ".$tipoUsuario."<br>";
			echo "<script>
				$('#tblBuscarInst tbody').empty();
				$('#totalInstBuscados').empty();
				$('#totalInstMedBuscados').empty();";
			
			$rsInst = sqlsrv_query($conn, $instFiltradas, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			$totalRegistros = sqlsrv_num_rows($rsInst);

			while($inst = sqlsrv_fetch_array($rsInst)){
				//if($fecha == '' && $dia == ''){
				if($fecha == ''){
					if($tipoUsuario == 4){
						echo "$('#tblBuscarInst tbody').append('<tr onClick=\"instSeleccionada(\'".$inst['inst_snr']."\');\"><td style=\"width:12%;\">".$inst['tipo']."</td><td style=\"width:16%;\">".$inst['nombre']."</td><td style=\"width:16%;\">".$inst['calle']."</td><td style=\"width:16%;\">".$inst['colonia']."</td><td style=\"width:9%;\">".$inst['cp']."</td><td style=\"width:16%;\">".$inst['delegacion']."</td><td style=\"width:15%;\">".$inst['estado']."</td></tr>');";
					}else{
						echo "$('#tblBuscarInst tbody').append('<tr onClick=\"instSeleccionada(\'".$inst['inst_snr']."\');\"><td style=\"width:8%;\">".explode(" ",$inst['ruta'])[0]."</td><td style=\"width:11%;\">".$inst['tipo']."</td><td style=\"width:15%;\">".$inst['nombre']."</td><td style=\"width:15%;\">".$inst['calle']."</td><td style=\"width:15%;\">".$inst['colonia']."</td><td style=\"width:8%;\">".$inst['cp']."</td><td style=\"width:15%;\">".$inst['delegacion']."</td><td style=\"width:13%;\">".$inst['estado']."</td></tr>');";
					}
				}else{
					if($tipoUsuario == 4){
						echo "$('#tblBuscarInst tbody').append('<tr onClick=\"nuevoPlanInst(\'".$inst['inst_snr']."\',\'".$fecha."\',\'\');\"><td style=\"width:12%;\">".$inst['tipo']."</td><td style=\"width:16%;\">".$inst['nombre']."</td><td style=\"width:16%;\">".$inst['calle']."</td><td style=\"width:16%;\">".$inst['colonia']."</td><td style=\"width:9%;\">".$inst['cp']."</td><td style=\"width:16%;\">".$inst['delegacion']."</td><td style=\"width:15%;\">".$inst['estado']."</td></tr>');";
					}else{
						echo "$('#tblBuscarInst tbody').append('<tr onClick=\"nuevoPlanInst(\'".$inst['inst_snr']."\',\'".$fecha."\',\'".$inst['user_snr']."\');\"><td style=\"width:8%;\">".explode(" ",$inst['ruta'])[0]."</td><td style=\"width:11%;\">".$inst['tipo']."</td><td style=\"width:15%;\">".$inst['nombre']."</td><td style=\"width:15%;\">".$inst['calle']."</td><td style=\"width:15%;\">".$inst['colonia']."</td><td style=\"width:8%;\">".$inst['cp']."</td><td style=\"width:15%;\">".$inst['delegacion']."</td><td style=\"width:13%;\">".$inst['estado']."</td></tr>');";
					}
				}
			}
			echo "$('#totalInstBuscados').append('Instituciones encontradas: ".$totalRegistros."');";
			echo "$('#totalInstMedBuscados').append('Instituciones encontradas: ".$totalRegistros."');";
			echo "</script>";
		}
		//echo $instituciones;
	}
	
?>