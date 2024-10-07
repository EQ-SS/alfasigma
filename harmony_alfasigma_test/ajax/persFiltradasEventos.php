<?php
	include "../conexion.php";
	$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
	$reemplazar=array(" ", " ", " ", " ");
	$reemplazar1=array("", "", "", "");
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$palabra = $_POST['palabra'];
		$idUsuario = $_POST['idUsuario'];
		//$fecha = $_POST['fecha'];
		$ids = $_POST['ids'];
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
		
		$personasFiltradas = "select top 100 p.pers_snr, p.LNAME + ' ' +  p.MOTHERS_LNAME + ' ' + p.FNAME as nombre, c.NAME as especialidad, 
			i.NAME as institucion, u.lname as ruta
			from person p
			inner join PERS_SREP_WORK psw on p.pers_snr = psw.PERS_SNR
			inner join CODELIST c on p.SPEC_SNR = c.CLIST_SNR
			inner join CODELIST categ on p.CATEGORY_SNR = categ.CLIST_SNR
			inner join inst i on i.INST_SNR = psw.INST_SNR
			inner join CODELIST freq on freq.CLIST_SNR = p.FRECVIS_SNR
			inner join users u on u.user_snr = psw.user_snr 
			left outer join codelist estatus on estatus.clist_snr = p.STATUS_SNR 
			where psw.USER_SNR in ('".$ids."')
			and p.REC_STAT = 0
			and psw.REC_STAT = 0
			and c.REC_STAT = 0
			and categ.REC_STAT = 0
			and i.REC_STAT = 0 
			and estatus.name = 'ACTIVO' ";
			/*if($tipoUsuario == 4){
				$personas .= "and freq.NAME > 0
				and freq.NAME > (SELECT COUNT(*) FROM VISITPERS VP, CYCLES CICLOS 
				WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND P.PERS_SNR=VP.PERS_SNR 
				AND '".date('Y-m-d')."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
				AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE
				AND vp.user_snr in ('".$ids."'))";
			}*/
		$personas = "select top 100 p.pers_snr, p.LNAME + ' ' +  p.MOTHERS_LNAME + ' ' + p.FNAME as nombre, c.NAME as especialidad, 
		i.NAME as institucion, u.lname as ruta
		from person p
		inner join PERS_SREP_WORK psw on p.pers_snr = psw.PERS_SNR
		inner join CODELIST c on p.SPEC_SNR = c.CLIST_SNR
		inner join CODELIST categ on p.CATEGORY_SNR = categ.CLIST_SNR
		inner join inst i on i.INST_SNR = psw.INST_SNR
		inner join CODELIST freq on freq.CLIST_SNR = p.FRECVIS_SNR
		inner join users u on u.user_snr = psw.user_snr 
		left outer join codelist estatus on estatus.clist_snr = p.STATUS_SNR 
		where psw.USER_SNR in ('".$ids."')
		and p.REC_STAT = 0
		and psw.REC_STAT = 0
		and c.REC_STAT = 0
		and categ.REC_STAT = 0
		and i.REC_STAT = 0 
		and estatus.name = 'ACTIVO'  ";

		if($palabra == ''){
			
			//$personas .= " and p.LNAME + ' ' +  p.MOTHERS_LNAME + ' ' + p.FNAME like '%".$palabra."%' ";

			if($repre != ''){
				$personas .= " and u.user_snr = '".$repre."'";
			}
			$personas .= " order by nombre";
			
			//echo $personas."<br>";
			
			echo "<script>
					$('#tblBuscarPersonasEventos tbody').empty();
					$('#totalMedicosBuscadosEventos').empty();";
			
			$rsPersonas = sqlsrv_query($conn, $personas, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			
			$totalRegistros = sqlsrv_num_rows($rsPersonas);
			$idDiv = 1;
			while($persona = sqlsrv_fetch_array($rsPersonas)){
				$ruta = explode(" ",$persona['ruta'])[0];
				$pers_snr = $persona['pers_snr'];
				$nombre = str_ireplace($buscar,$reemplazar,strtoupper(str_replace("'", "", $persona['nombre'])));
				$especialidad = str_ireplace($buscar,$reemplazar,str_replace("'", "", $persona['especialidad']));
				$institucion = str_ireplace($buscar,$reemplazar,str_replace("'", "", $persona['institucion']));
				if($tipoUsuario == 4){
					echo "$('#tblBuscarPersonasEventos tbody').append('<tr><td><div id=\"divInvitadosEvento".$idDiv."\" class=\"circuloRojo\" onclick=\"invitarEvento(\'".$pers_snr."\',\'divInvitadosEvento".$idDiv."\')\"></div></td><td style=\"width:34%;\">".$nombre."</td><td style=\"width:34%;\">".$especialidad."</td><td style=\"width:32%;\">".$institucion."</td></tr>');";	
				
				}else{
					echo "$('#tblBuscarPersonasEventos tbody').append('<tr><td><div id=\"divInvitadosEvento".$idDiv."\" class=\"circuloRojo\" onclick=\"invitarEvento(\'".$pers_snr."\',\'divInvitadosEvento".$idDiv."\')\"></div></td><td style=\"width:10%;\">".$ruta."</td><td style=\"width:30%;\">".$nombre."</td><td style=\"width:30%;\">".$especialidad."</td><td style=\"width:30%;\">".$institucion."</td></tr>');";	
				}
				$idDiv++;
			}
			echo "</script>";

		}else{

			//if($palabra != ''){
				$personasFiltradas .= " and p.LNAME + ' ' +  p.MOTHERS_LNAME + ' ' + p.FNAME like '%".$palabra."%' ";
			//}
			if($repre != ''){
				$personasFiltradas .= " and u.user_snr = '".$repre."'";
			}
			$personasFiltradas .= " order by nombre";
			
			//echo $personasFiltradas."<br>";
			
			echo "<script>
					$('#tblBuscarPersonasEventos tbody').empty();
					$('#totalMedicosBuscadosEventos').empty();";
			
			$rsPersonas = sqlsrv_query($conn, $personasFiltradas, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
			
			$totalRegistros = sqlsrv_num_rows($rsPersonas);
			$idDiv = 1;
			while($persona = sqlsrv_fetch_array($rsPersonas)){
				$ruta = explode(" ",$persona['ruta'])[0];
				$pers_snr = $persona['pers_snr'];
				if($tipoUsuario == 4){
					echo "$('#tblBuscarPersonasEventos tbody').append('<tr><td><div id=\"divInvitadosEvento".$idDiv."\" class=\"circuloRojo\" onclick=\"invitarEvento(\'".$pers_snr."\',\'divInvitadosEvento".$idDiv."\')\"></div></td><td style=\"width:34%;\">".strtoupper(str_replace("'", "", $persona['nombre']))."</td><td style=\"width:34%;\">".$persona['especialidad']."</td><td style=\"width:32%;\">".$persona['institucion']."</td></tr>');";	
				
				}else{
					echo "$('#tblBuscarPersonasEventos tbody').append('<tr><td><div id=\"divInvitadosEvento".$idDiv."\" class=\"circuloRojo\" onclick=\"invitarEvento(\'".$pers_snr."\',\'divInvitadosEvento".$idDiv."\')\"></div></td><td style=\"width:10%;\">".$ruta."</td><td style=\"width:30%;\">".strtoupper(str_replace("'", "", $persona['nombre']))."</td><td style=\"width:30%;\">".$persona['especialidad']."</td><td style=\"width:30%;\">".$persona['institucion']."</td></tr>');";	
				}
				$idDiv++;
			}
			echo "$('#totalMedicosBuscadosEventos').append('Médicos encontrados: ".$totalRegistros."');";
			echo "</script>";
		}
	}
?>