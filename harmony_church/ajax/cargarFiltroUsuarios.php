<?php
	include "../conexion.php";
	$ids = $_POST['ids'];
	$palabra = $_POST['palabra'];
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
		$queryUsuarios .= " and LNAME + ' ' + FNAME like '%".$palabra."%'";
	}
	if($idsNotIn != ''){
		$queryUsuarios .= " and user_snr not in ('".$idsNotIn."') ";
	}
	$queryUsuarios .= " order by LNAME, FNAME ";
	$rsUsuario = sqlsrv_query($conn, $queryUsuarios);
	//echo $queryUsuarios;
	echo "<script>
		$('#tblUsuariosFiltros').empty();";
	$i = 0;
	$nombres = '';
	while($usuario = sqlsrv_fetch_array($rsUsuario)){
		$nombreRepre = utf8_encode($usuario['LNAME'].' '.$usuario['FNAME']);
		if($seleccionarTodo != ''){
			echo "$('#tblUsuariosSeleccionados').append('<tr class=\"pointer\" id=\"trUsuarioSeleccionado".$i."\" onclick=\"eliminarSeleccionado(\'trUsuarioSeleccionado".$i."\',\'".$usuario['USER_SNR']."\',\'".$nombreRepre."\');\"><td>".$nombreRepre."</td></tr>');";
			$nombres .= $nombreRepre.",";
			$i++;
		}else{
			echo "$('#tblUsuariosFiltros').append('<tr class=\"pointer\" onclick=\"usuarioSeleccionado(\'".$usuario['USER_SNR']."\',\'".$nombreRepre."\');\"><td>".$nombreRepre."</td></tr>');";
			//echo "$('#tblUsuariosFiltros').append('<option onClick=\"usuarioSeleccionado(\'".$usuario['USER_SNR']."\',\'".$nombreRepre."\');\">".$nombreRepre."</option>');";
		}
	}
	if($seleccionarTodo != ''){
		echo "$('#hdnIdsFiltroUsuarios').val('".str_replace("'","",$ids).",');
			$('#hdnNombresFiltroUsuarios').val('".$nombres.",');";
	}
	echo "</script>";
?>