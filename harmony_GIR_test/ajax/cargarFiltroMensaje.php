<?php
	include "../conexion.php";
	
	$idRemitente = $_POST['idRemitente'];
	$palabra = $_POST['palabra'];
	
	$queryContactos = "select user_contact_snr
		from user_mailing_contact 
		where user_snr = '".$idRemitente."'
		and REC_STAT = 0 ";
		
	$rsContactos = sqlsrv_query($conn, $queryContactos);
	
	$ids = '';
	while($contacto = sqlsrv_fetch_array($rsContactos)){
		$ids .= $contacto['user_contact_snr'].",";
	}
	
	$ids = str_replace(",","','",substr($ids, 0, -1));
	
	//echo "ids: ".$ids;
	
	if(isset($_POST['seleccionarTodo']) && $_POST['seleccionarTodo'] != ''){
		$seleccionarTodo = $_POST['seleccionarTodo'];
	}else{
		$seleccionarTodo = '';
	}
	if(isset($_POST['idsFiltros']) && $_POST['idsFiltros']){
		$idsNotIn = str_replace(",","','",substr($_POST['idsFiltros'], 0, -1));
	}else{
		$idsNotIn = "";
	}
	if(isset($_POST['idUser']) && $_POST['idUser']){
		$ids .= "','".$_POST['idUser'];
	}
	$queryUsuarios = "select * from users where user_snr in ('".$ids."')";
	if($palabra != ''){
		$queryUsuarios .= " and USER_NR + ' ' + LNAME + ' ' + FNAME like '%".$palabra."%'";
	}
	if($idsNotIn != ''){
		$queryUsuarios .= " and user_snr not in ('".$idsNotIn."') ";
	}
	$queryUsuarios .= " order by USER_NR, LNAME, FNAME ";
	$rsUsuario = sqlsrv_query($conn, $queryUsuarios);
	//echo $queryUsuarios;
	echo "<script>
		$('#tblMensajeFiltros').empty();";
	$i = 0;
	$nombres = '';
	while($usuario = sqlsrv_fetch_array($rsUsuario)){
		$nombreRepre = utf8_encode($usuario['USER_NR'].' - '.$usuario['LNAME'].' '.$usuario['FNAME']);
		if($seleccionarTodo != ''){
			echo "$('#tblUsuariosSeleccionadosFiltrosMensajes').append('<tr class=\"pointer\" id=\"trUsuarioSeleccionadoMensaje".$i."\" onclick=\"eliminarSeleccionadoMensaje(\'trUsuarioSeleccionadoMensaje".$i."\',\'".$usuario['USER_SNR']."\',\'".$nombreRepre."\');\"><td>".$nombreRepre."</td></tr>');";
			$nombres .= $nombreRepre.",";
			$i++;
		}else{
			echo "$('#tblMensajeFiltros').append('<tr class=\"pointer\" onclick=\"usuarioSeleccionadoMensaje(\'".$usuario['USER_SNR']."\',\'".$nombreRepre."\');\"><td>".$nombreRepre."</td></tr>');";
		}
	}
	if($seleccionarTodo != ''){
		echo "$('#hdnIdsFiltroUsuariosMensajes').val('".str_replace("'","",$ids).",');
			$('#hdnNombresFiltroUsuariosMensaje').val('".$nombres.",');";
	}
	echo "</script>";//tblUsuariosSeleccionadosFiltrosMensajes
?>