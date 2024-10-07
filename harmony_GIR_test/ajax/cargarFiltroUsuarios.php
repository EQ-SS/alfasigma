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
		$idUser = $_POST['idUser'];
	} else {
		$idUser = "";
	}
	if(isset($_POST['tipoUsuario']) && $_POST['tipoUsuario']){
		$tipoUsuario = $_POST['tipoUsuario'];
	} else {
		$tipoUsuario = "";
	}
	$queryUsuarios = "
	select users.USER_SNR, users.USER_NR, users.LNAME, users.MOTHERS_LNAME, users.FNAME 
	from users 
	inner join compline line on line.cline_snr=users.cline_snr
	where users.rec_stat=0 
	and users.status=1 
	and users.user_type in (1,4,5) ";
	//and user_snr in ('".$ids."') ";
	if($palabra != ''){
		$queryUsuarios .= " and users.USER_NR + ' ' + users.LNAME + ' ' + users.MOTHERS_LNAME + ' ' + users.FNAME like '%".$palabra."%'";
	}
	if($idsNotIn != ''){
		$queryUsuarios .= " and user_snr not in ('".$idsNotIn."') ";
	}
	if ($tipoUsuario == 5) {
		$queryUsuarios .= " and users.user_snr in (select kloc_snr from kloc_reg where reg_snr='".$idUser."') ";
	}
	$queryUsuarios .= " order by users.USER_NR, line.NAME, users.LNAME, users.MOTHERS_LNAME, users.FNAME ";
	$rsUsuario = sqlsrv_query($conn, $queryUsuarios);
	
	//echo $queryUsuarios;
	
	echo "<script>
		$('#tblUsuariosFiltros').empty();";
	$i = 0;
	$nombres = '';
	while($usuario = sqlsrv_fetch_array($rsUsuario)){
		$nombreRepre = utf8_encode($usuario['USER_NR']." - ".$usuario['LNAME'].' '.$usuario['MOTHERS_LNAME'].' '.$usuario['FNAME']);
		if($seleccionarTodo != ''){
			echo "$('#tblUsuariosSeleccionados').append('<tr class=\"pointer\" id=\"trUsuarioSeleccionado".$i."\" onclick=\"eliminarSeleccionado(\'trUsuarioSeleccionado".$i."\',\'".$usuario['USER_SNR']."\',\'".$nombreRepre."\');\"><td>".$nombreRepre."</td></tr>');";
			$nombres .= $nombreRepre.",";
			$i++;
		}else{
			echo "$('#tblUsuariosFiltros').append('<tr class=\"pointer\" onclick=\"usuarioSeleccionado(\'".$usuario['USER_SNR']."\',\'".$nombreRepre."\');\"><td>".$nombreRepre."</td></tr>');";
		}
	}
	if($seleccionarTodo != ''){
		echo "$('#hdnIdsFiltroUsuarios').val('".str_replace("'","",$ids).",');
			$('#hdnNombresFiltroUsuarios').val('".$nombres.",');";
	}
	echo "</script>";
?>