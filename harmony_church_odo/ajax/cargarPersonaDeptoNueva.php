<?php
	include "../conexion.php";
	echo "<script>
		var numero = $('#tblPersonasDepartamentoInstituciones tbody tr').length + 1;
		var renglon = '<tr id=\"trDeptoPersonaNueva' + numero + '\">';
		renglon += '<td width=\"50px\">' + numero + '</td>';
		renglon += '<td width=\"250px\"><input type=\"text\" id=\"txtPaternoPersonaDepto\" value=\"\" /></td>';
		renglon += '<td width=\"250px\"><input type=\"text\" id=\"txtMaternoPersonaDepto\" value=\"\" /></td>';
		renglon += '<td width=\"250px\"><input type=\"text\" id=\"txtNombrePersonaDepto\" value=\"\" /></td>';
		renglon += '<td width=\"200px\"><select id=\"sltEspecialidadPersonaDepto\"><option value=\"00000000-0000-0000-0000-000000000000\">Seleccione</option>';";
	$rsEsp = llenaCombo($conn, 19, 1);
	while($esp = sqlsrv_fetch_array($rsEsp)){
		echo "var id = '".$esp['id']."';
			var des = '".$esp['nombre']."';
		";
		echo "renglon += '<option value=\"' + id + '\">' + des + '</option>';";
	}
	echo "renglon += '</select>';
		renglon += '<td width=\"50px\" align=\"center\"><button type=\"button\" onclick=\"guardarPersonaDepto();\">Guardar</button></td>';
		renglon += '</td></tr>';
		$('#tblPersonasDepartamentoInstituciones tbody').append(renglon);
		$('#btnAgregarPersonaDepartamentoDatosInstituciones').html('Cancelar');
		$('#hdnIdpersonaDeptoEdit').val('');
	</script>
	";
?>