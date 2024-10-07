<?php
	include ("conexion.php");
	require('calendario/calendario.php');
	$dias = array("","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado","Domingo");
	$fechaCompleta = array();
	$fecha = $_POST['fecha'];
	$planVisita = $_POST['planVisita'];
	$idUsuario = $_POST['idUsuario'];
	$ids = $_POST['ids'];
	
	if(isset($_POST['repre']) && $_POST['repre'] != ''){
		$repre = str_replace(",","','",substr($_POST['repre'], 0, -1));
	}else{
		$repre = $ids;
	}
	
	if(isset($_POST['repreNombres']) && $_POST['repreNombres'] != ''){
		$repreNombres = $_POST['repreNombres'];
	}else{
		$repreNombres = "Seleccione";
	}
	
	//$tipoUsuario = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from users where user_snr = '".$idUsuario."'"))['USER_TYPE'];
	
	//echo "fecha: ".$fecha;
	$tipoUsuario = $_POST['tipoUsuario'];

	$week = date("W",mktime(0,0,0,substr($fecha, 5, 2), substr($fecha, 8, 2), substr($fecha, 0, 4))) - 1;
	
	for($i=-1; $i<6; $i++){
		//echo date('D N  Y-m-d', strtotime('01/01 +' . ($week ) . ' weeks first day +' . $i . ' day')) . '<br />';
		$fechaCompleta[$dias[date('N', strtotime('01/01 +' . ($week ) . ' weeks first day +' . $i . ' day'))]] = date('d/m/Y', strtotime('01/01 +' . ($week ) . ' weeks first day +' . $i . ' day'));
		$fechaPlanVisita[$dias[date('N', strtotime('01/01 +' . ($week ) . ' weeks first day +' . $i . ' day'))]] = date('Y-m-d', strtotime('01/01 +' . ($week ) . ' weeks first day +' . $i . ' day'));
	}
	
	$arrLunes = planesVisitasCalendario($fechaPlanVisita["Lunes"], $planVisita, $conn, $idUsuario, $repre);
	$arrMartes = planesVisitasCalendario($fechaPlanVisita["Martes"], $planVisita, $conn, $idUsuario, $repre);
	$arrMiercoles = planesVisitasCalendario($fechaPlanVisita["Miercoles"], $planVisita, $conn, $idUsuario, $repre);
	$arrJueves = planesVisitasCalendario($fechaPlanVisita["Jueves"], $planVisita, $conn, $idUsuario, $repre);
	$arrViernes = planesVisitasCalendario($fechaPlanVisita["Viernes"], $planVisita, $conn, $idUsuario, $repre);
	$arrSabado = planesVisitasCalendario($fechaPlanVisita["Sábado"], $planVisita, $conn, $idUsuario, $repre);
echo "<script>
		$('#lblTituloDiaUno').text('Lunes: ".$fechaCompleta['Lunes']."');
		$('#lblTituloDiaDos').text('Martes: ".$fechaCompleta['Martes']."');
		$('#lblTituloDiaTres').text('Miércoles: ".$fechaCompleta['Miercoles']."');
		$('#lblTituloDiaCuatro').text('Jueves: ".$fechaCompleta['Jueves']."');
		$('#lblTituloDiaCinco').text('Viernes: ".$fechaCompleta['Viernes']."');
		$('#totalPersonasDiaUno').text('".$arrLunes[1]."');
		$('#totalInstDiaUno').text('".$arrLunes[2]."');
		$('#totalOtrasActividadesDiaUno').text('".$arrLunes[3]."');
		$('#totalPersonasDiaDos').text('".$arrMartes[1]."');
		$('#totalInstDiaDos').text('".$arrMartes[2]."');
		$('#totalOtrasActividadesDiaDos').text('".$arrMartes[3]."');
		$('#totalPersonasDiaTres').text('".$arrMiercoles[1]."');
		$('#totalInstDiaTres').text('".$arrMiercoles[2]."');
		$('#totalOtrasActividadesDiaTres').text('".$arrMiercoles[3]."');
		$('#totalPersonasDiaCuatro').text('".$arrJueves[1]."');
		$('#totalInstDiaCuatro').text('".$arrJueves[2]."');
		$('#totalOtrasActividadesDiaCuatro').text('".$arrJueves[3]."');
		$('#totalPersonasDiaCinco').text('".$arrViernes[1]."');
		$('#totalInstDiaCinco').text('".$arrViernes[2]."');
		$('#totalOtrasActividadesDiaCinco').text('".$arrViernes[3]."');
	</script>";
	print_r($arrLunes[0]);
?>
<!--<table width="100%" border="0" bgcolor="#FFFFFF">
	<tr>
		<td width="33%">
			<table border="0">
				<tr>
					<td>
						<label id="lblRep">&nbsp;&nbsp;&nbsp;Ruta&nbsp;&nbsp;&nbsp;</label>
<?php
							if($tipoUsuario != 4){
								echo "<div class=\"selectBox\" onclick=\"filtrosUsuarios('cal');\">
									<select style=\"width:200px\">
										<option id=\"sltMultiSelectCal\">".$repreNombres."</option>
									</select>
								</div>";
							}else{
								echo '<select id="sltRepreCalendario" onChange="actualizaCalendarioSelect();">';
								$repre = sqlsrv_query($conn, "select user_snr, lname + ' ' + fname as nombre from users where user_snr in ('".$ids."')");
								while($rep = sqlsrv_fetch_array($repre)){
									if($idUsuario == $rep['user_snr']){
										echo '<option value="'.$rep['user_snr'].'" selected>'.$rep['nombre'].'</option>';
									}else{
										echo '<option value="'.$rep['user_snr'].'">'.$rep['nombre'].'</option>';
									}
								}
								echo "</select>";
							}
?>
					</td>
					<td rowspan="2" valign="top">
						<table>
							<tr>
								<td>
									<button class="<?= ($planVisita == 'plan') ? 'seleccionado' : 'noSeleccionado' ?>" id="btnPlanCalendario" onClick="actualizaCalendarioBoton('plan');">
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Plan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									</button>
								</td>
							</tr>
							<tr>
								<td>
									<button class="<?= ($planVisita == 'plan') ? 'noSeleccionado' : 'seleccionado' ?>" id="btnVisitaCalendario" onClick="actualizaCalendarioBoton('visita');">
										&nbsp;&nbsp;&nbsp;&nbsp;Visita&nbsp;&nbsp;&nbsp;&nbsp;
									</button>
								</td>
							</tr>
							<tr>
								<td>
									<button id="btnRuteo" onClick="muestraRuteo();">
										&nbsp;&nbsp;&nbsp;&nbsp;Ruteo&nbsp;&nbsp;&nbsp;&nbsp;
									</button>
								</td>
							</tr>
							<tr>
								<td>
									<button id="btnCopiarPlanes">
										Copiar Planes
									</button>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<input type="hidden" name="fecha" id="fecha" value="<?= $fecha ?>" /> 
						<input type="hidden" name="anterior" id="anterior"  />
						<div id="calendario" >
							<?php 
								calendar_html(1, $conn, $idUsuario, $ids, $fecha); 
							?>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<table border="0" style="border-collapse: separate;border-spacing:  15px 5px;">
							<tr>
								<td class="negrita">
									<img src="iconos/punto_rojo.jpg" width="25px" >Planes
								</td>
								<td class="negrita">
									<img src="iconos/punto_verde.png" width="10px" >Visitas
								</td>
								<td class="negrita">
									<img src="iconos/punto_azul.png" width="10px" title="Tiempo Fuera de Territorio" >TFT
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
		<td width="33%">
			<table width="100%" border="0">
				<tr>
					<td style="border-bottom: 2px solid #557a95"><label class="titulosDias">
						Lunes: 
					</td>
				</tr>
				<tr>
					<td align="center">
						<table width="50%">
							<tr>
								<td><img src='iconos/otras_actividades.png' title="Otras Actividades" onclick="abreOtrasActividades('DiaUno','<?= $fechaPlanVisita['Lunes'] ?>');"><label id="totalOtrasActividadesDiaUno"></label></td>
								<td><img src='iconos/personas24.png' title="Personas" onclick="abreBuscarPersona('DiaUno','<?= $fechaPlanVisita['Lunes'] ?>');" ><label id="totalPersonasDiaUno"></label></td>
								<td><img src='iconos/instituciones24.png' title="Instituciones" onclick="abreBuscarInst('DiaUno','<?= $fechaPlanVisita['Lunes'] ?>');"><label id="totalInstDiaUno"></label></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style="border: 3px solid #557a95;border-radius: 10px;" valign="top">
						<div style="height:140px; overflow:auto;">
							<table id="tblDiaUno" width="100%" border="0">
								<?= $arrLunes[0] ?>
							</table>
						</div>
					</td>
				</tr>
			</table>
		</td>
		<td width="33%">
			<table width="100%" border="0">
				<tr>
					<td style="border-bottom: 2px solid #557a95"><label class="titulosDias">
						Martes: <?= $fechaCompleta['Martes'] ?>
					</td>
				</tr>
				<tr>
					<td align="center">
						<table width="50%">
							<tr>
								<td><img src='iconos/otras_actividades.png' title="Otras Actividades" onclick="abreOtrasActividades('DiaDos','<?= $fechaPlanVisita['Martes'] ?>');" ><label id="totalOtrasActividadesDiaDos"><?= $arrMartes[3] ?></label></td>
								<td><img src='iconos/personas24.png' title="Personas" onclick="abreBuscarPersona('DiaDos','<?= $fechaPlanVisita['Martes'] ?>');" ><label id="totalPersonasDiaDos"><?= $arrMartes[1] ?></label></td>
								<td><img src='iconos/instituciones24.png' title="Instituciones" onclick="abreBuscarInst('DiaDos','<?= $fechaPlanVisita['Martes'] ?>');" ><label id="totalInstDiaDos"><?= $arrMartes[2] ?></label></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style="border: 3px solid #557a95;border-radius: 10px;" valign="top">
					<div style="height:140px; overflow:auto;" >
							<table id="tblDiaDos" width="100%">
								<?= $arrMartes[0] ?>
							</table>
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="33%">
			<table width="100%" border="0">
				<tr>
					<td style="border-bottom: 2px solid #557a95;"><label class="titulosDias">
						Miercoles: <?= $fechaCompleta['Miercoles'] ?>
					</td>
				</tr>
				<tr>
					<td align="center">
						<table width="50%">
							<tr>
								<td><img src='iconos/otras_actividades.png' title="Otras Actividades" onclick="abreOtrasActividades('DiaTres','<?= $fechaPlanVisita['Miercoles'] ?>');" ><label id="totalOtrasActividadesDiaTres"><?= $arrMiercoles[3] ?></label></td>
								<td><img src='iconos/personas24.png' title="Personas" onclick="abreBuscarPersona('DiaTres','<?= $fechaPlanVisita['Miercoles'] ?>');" ><label id="totalPersonasDiaTres"><?= $arrMiercoles[1] ?></label></td>
								<td><img src='iconos/instituciones24.png' title="Instituciones" onclick="abreBuscarInst('DiaTres','<?= $fechaPlanVisita['Miercoles'] ?>');" ><label id="totalInstDiaTres"><?= $arrMiercoles[2] ?></label></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style="border: 3px solid #557a95;border-radius: 10px;" valign="top">
						<div style="height:140px; overflow:auto;">
							<table id="tblDiaTres" width="100%" >
								<?= $arrMiercoles[0] ?>
							</table>
						</div> 
					</td>
				</tr>
			</table>
		</td>
		<td width="33%">
			<table width="100%" border="0">
				<tr>
					<td style="border-bottom: 2px solid #557a95"><label class="titulosDias">
						Jueves: <?= $fechaCompleta['Jueves'] ?>
					</td>
				</tr>
				<tr>
					<td align="center">
						<table width="50%">
							<tr>
								<td><img src='iconos/otras_actividades.png' title="Otras Actividades" onclick="abreOtrasActividades('DiaCuatro','<?= $fechaPlanVisita['Jueves'] ?>');" ><label id="totalOtrasActividadesDiaCuatro"><?= $arrJueves[3] ?></label></td>
								<td><img src='iconos/personas24.png' title="Personas" onclick="abreBuscarPersona('DiaCuatro','<?= $fechaPlanVisita['Jueves'] ?>');" ><label id="totalPersonasDiaCuatro"><?= $arrJueves[1] ?></label></td>
								<td><img src='iconos/instituciones24.png' title="Instituciones" onclick="abreBuscarInst('DiaCuatro','<?= $fechaPlanVisita['Jueves'] ?>');" ><label id="totalInstDiaCuatro"><?= $arrJueves[2] ?></label></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style="border: 3px solid #557a95;border-radius: 10px;" valign="top">
					<div style="height:140px; overflow:auto;">
							<table id="tblDiaCuatro" width="100%">
								<?= $arrJueves[0] ?>
							</table>
						</div>
					</td>
				</tr>
			</table>
		</td>
		<td width="33%">
			<table width="100%" border="0">
				<tr>
					<td style="border-bottom: 2px solid #557a95"><label class="titulosDias">
						Viernes: <?= $fechaCompleta['Viernes'] ?>
					</td>
				</tr>
				<tr>
					<td align="center">
						<table width="50%">
							<tr>
								<td><img src='iconos/otras_actividades.png' title="Otras Actividades" onclick="abreOtrasActividades('DiaCinco','<?= $fechaPlanVisita['Viernes'] ?>');" ><label id="totalOtrasActividadesDiaCinco"><?= $arrViernes[3] ?></label></td>
								<td><img src='iconos/personas24.png' title="Personas" onclick="abreBuscarPersona('DiaCinco','<?= $fechaPlanVisita['Viernes'] ?>');" ><label id="totalPersonasDiaCinco"><?= $arrViernes[1] ?></label></td>
								<td><img src='iconos/instituciones24.png' title="Instituciones" onclick="abreBuscarInst('DiaCinco','<?= $fechaPlanVisita['Viernes'] ?>');" ><label id="totalInstDiaCinco"><?= $arrViernes[2] ?></label></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style="border: 3px solid #557a95;border-radius: 10px;" valign="top">
					<div style="height:140px; overflow:auto;">
							<table id="tblDiaCinco" width="100%">
								<?= $arrViernes[0] ?>
							</table>
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>-->