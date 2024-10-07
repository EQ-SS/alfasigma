<?php
	$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
	$fechaCompleta = array();
	$fechaPlanVisita = array();
	$week = date("W");
	
	$day = date('w');
	$fechaCompleta["Domingo"] = date('d/m/Y', strtotime('-'.$day.' days'));
	$fechaCompleta["Lunes"] = date('d/m/Y', strtotime('+'.(1-$day).' days'));
	$fechaCompleta["Martes"] = date('d/m/Y', strtotime('+'.(2-$day).' days'));
	$fechaCompleta["Miercoles"] = date('d/m/Y', strtotime('+'.(3-$day).' days'));
	$fechaCompleta["Jueves"] = date('d/m/Y', strtotime('+'.(4-$day).' days'));
	$fechaCompleta["Viernes"] = date('d/m/Y', strtotime('+'.(5-$day).' days'));
	$fechaCompleta["Sábado"] = date('d/m/Y', strtotime('+'.(6-$day).' days'));
	$fechaPlanVisita["Domingo"] = date('Y-m-d', strtotime('-'.$day.' days'));
	$fechaPlanVisita["Lunes"] = date('Y-m-d', strtotime('+'.(1-$day).' days'));
	$fechaPlanVisita["Martes"] = date('Y-m-d', strtotime('+'.(2-$day).' days'));
	$fechaPlanVisita["Miercoles"] = date('Y-m-d', strtotime('+'.(3-$day).' days'));
	$fechaPlanVisita["Jueves"] = date('Y-m-d', strtotime('+'.(4-$day).' days'));
	$fechaPlanVisita["Viernes"] = date('Y-m-d', strtotime('+'.(5-$day).' days'));
	$fechaPlanVisita["Sábado"] = date('Y-m-d', strtotime('+'.(6-$day).' days'));
	//print_r($fechaPlanVisita);
	
?>
<input id="hdnFechaCalendario" type="hidden" value="<?= date('Y-m-d') ?>" />
<table width="100%">
	<tr>
		<td>
			<table>
				<tr>
					<td>
						<img src="iconos/calendario.png" title="Inicio" class="imgTitulo"/>
					</td>
					<td><label class="nombreModulos">
						Calendario
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<hr>
<table width="100%" border="1">
	<tr>
		<td colspan="2" valign="bottom">
			<label id="lblRep">&nbsp;&nbsp;&nbsp;Ruta&nbsp;&nbsp;&nbsp;</label>
				<select id="sltRepreCalendario">
<?php
				if($tipoUsuario != 4){
					echo '<option value="<?= $idUsuario ?>"></option>';
				}
				
				$repre = sqlsrv_query($conn, "select user_snr, lname + ' ' + fname as nombre from users where user_snr in ('".$ids."')");
				while($rep = sqlsrv_fetch_array($repre)){
					echo '<option value="'.$rep['user_snr'].'">'.$rep['nombre'].'</option>';
				}
?>
			</select>		
		</td>
		<td>
			<!--<select>
				<option value="0">Seleccione</option>
<?php
				for($i=1;$i<13;$i++){
					if(date("m") == $i){
						echo '<option value="'.$i.'" selected>'.$i.'</option>';
					}else{
						echo '<option value="'.$i.'">'.$i.'</option>';
					}
				}
?>			</select>
			<select>
				<option value="0">Seleccione</option>
<?php
				for($i=1997;$i<=date("Y");$i++){
					if(date("Y") == $i){
						echo '<option value="'.$i.'" selected>'.$i.'</option>';
					}else{
						echo '<option value="'.$i.'">'.$i.'</option>';
					}
				}
?>			</select>-->
		</td>
		<td>
			<button class="seleccionado" id="btnPlanCalendario">
				&nbsp;&nbsp;&nbsp;&nbsp;Plan&nbsp;&nbsp;&nbsp;&nbsp;
			</button>
			<button class="noSeleccionado" id="btnVisitaCalendario">
				&nbsp;&nbsp;&nbsp;&nbsp;Visita&nbsp;&nbsp;&nbsp;&nbsp;
			</button>
			<button id="btnRuteo">
				&nbsp;&nbsp;&nbsp;&nbsp;Ruteo&nbsp;&nbsp;&nbsp;&nbsp;
			</button>
			<!--<button>
				Semana
			</button>
			<button>
				2 Días
			</button>
			<button>
				Nota Semanal
			</button>-->
		</td>
		
		<td align="right">
			<img src="iconos/triangulo.png" width="15%" id="imgAbrirOpcionesCalendario" >
			<img src="iconos/triangulo2.png" width="15%" id="imgCerrarOpcionesCalendario" style="display:none;" >
		</td>
		
	</tr>
</table>


<table width="100%" border="0" id="tblOpcionesCalendario" style="display:none;">
	<tr>
		<td width="50%" align="center">
			<input type="hidden" name="fecha" id="fecha"  /> 
			<input type="hidden" name="anterior" id="anterior"  />
			<div id="calendario" >
				<?php calendar_html(1); ?>
			</div>
		</td>
		<td align="center">
			<!--<img src="http://chart.apis.google.com/chart?cht=p&chs=450x300&chd=t:60,30,10&chxr=1,0,40&chds=0,40&chco=0072c6|ef3886|ff9900&chbh=65,0,35&chxt=x,y&chl=Médico1|Médico2|Médico3&chts=000000,20,20&chg=0,25,5,5" width="300" height="200">-->
		</td>
	</tr>
	<tr>
		<td align="center">
			<table border="0" style="border-collapse: separate;border-spacing:  15px 5px;">
				<tr>
					<td class="negrita">
						<img src="iconos/punto_rojo.jpg" width="25px" >Visitas
					</td>
					<td class="negrita">
						<img src="iconos/punto_verde.png" width="10px" > Visitas planeadas
					</td>
					<td class="negrita">
						<img src="iconos/punto_azul.png" width="10px" > Reportar otras actividades
					</td>
					<!--<td class="negrita">
						<img src="iconos/punto_anaranjado.jpg" width="15px" > Eventos
					</td>-->
				</tr>
			</table>
		</td>
		<td>
		</td>
	</tr>
</table>

<div id="divCalendarioCambia">
<table width="100%" border="0" bgcolor="#FFFFFF">
	<tr>
		<td width="33%">
			<table width="100%" border="0">
				<tr>
					<td style="border-bottom: 2px solid #557a95">
					<label class="titulosDias">
<?php 
						echo "Lunes: ".$fechaCompleta['Lunes']; 
						$arrLunes = planesVisitasCalendario($fechaPlanVisita['Lunes'], 'plan', $conn, $idUsuario);
?>
					</label></td>
				</tr>
				<tr>
					<td align="center">
						<table width="50%">
							<tr>
								<!--onclick="abreOtrasActividades();"-->
								<td><img src='iconos/otras_actividades.png' title="Otras Actividades" onclick="abreOtrasActividades('DiaUno','<?= $fechaPlanVisita['Lunes'] ?>');" ><label id="totalOtrasActividadesDiaUno"><?= $arrLunes[3] ?></label></td>
								<td><img src='iconos/personas24.png' title="Personas" onclick="abreBuscarPersona('DiaUno','<?= $fechaPlanVisita['Lunes'] ?>');"> <label id="totalPersonasDiaUno"><?= $arrLunes[1] ?></label></td>
								<td><img src='iconos/instituciones24.png' title="Instituciones" onclick="abreBuscarInst('DiaUno','<?= $fechaPlanVisita['Lunes'] ?>');" > <label id="totalInstDiaUno"><?= $arrLunes[2] ?></label></td>
								<!--<td><img src='iconos/otras_actividades.png' title="Eventos" > 0</td>
								<td><img src='iconos/otras_actividades.png' title="Supervisión"  onclick="abreSupervision();" > 0</td>
								<td><img src='iconos/Radar24.png' title="Localizador" > 0</td>-->
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
<?php 
						echo "Martes: ".$fechaCompleta['Martes']; 
						$arrMartes = planesVisitasCalendario($fechaPlanVisita['Martes'], 'plan', $conn, $idUsuario);
?>
					</td>
				</tr>
				<tr>
					<td align="center">
						<table width="50%" >
							<tr>
								<td><img src='iconos/otras_actividades.png' title="Otras Actividades" onclick="abreOtrasActividades('DiaDos','<?= $fechaPlanVisita['Martes'] ?>');" ><label id="totalOtrasActividadesDiaDos"><?= $arrMartes[3] ?></label></td>
								<td><img src='iconos/personas24.png' title="Personas" onclick="abreBuscarPersona('DiaDos','<?= $fechaPlanVisita['Martes'] ?>');" > <label id="totalPersonasDiaDos"><?= $arrMartes[1] ?></label></td>
								<td><img src='iconos/instituciones24.png' title="Instituciones" onclick="abreBuscarInst('DiaDos','<?= $fechaPlanVisita['Martes'] ?>');" > <label id="totalInstDiaDos"><?= $arrMartes[2] ?></label></td>
								<!--<td><img src='iconos/otras_actividades.png' title="Eventos" > 0</td>
								<td><img src='iconos/otras_actividades.png' title="Supervisión" > 0</td>
								<td><img src='iconos/Radar24.png' title="Localizador" > 0</td>-->
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
		<td width="33%">
			<table width="100%" border="0">
				<tr>
					<td style="border-bottom: 2px solid #557a95;"><label class="titulosDias">
<?php 
						echo "Miercoles: ".$fechaCompleta['Miercoles']; 
						$arrMiercoles = planesVisitasCalendario($fechaPlanVisita['Miercoles'], 'plan', $conn, $idUsuario);
?>
					</td>
				</tr>
				<tr>
					<td align="center">
						<table width="50%">
							<tr>
								<td><img src='iconos/otras_actividades.png' title="Otras Actividades" onclick="abreOtrasActividades('DiaTres','<?= $fechaPlanVisita['Miercoles'] ?>');" ><label id="totalOtrasActividadesDiaTres"><?= $arrMiercoles[3] ?></label></td>
								<td><img src='iconos/personas24.png' title="Personas" onclick="abreBuscarPersona('DiaTres','<?= $fechaPlanVisita['Miercoles'] ?>');" > <label id="totalPersonasDiaTres"><?= $arrMiercoles[1] ?></label></td>
								<td><img src='iconos/instituciones24.png' title="Instituciones" onclick="abreBuscarInst('DiaTres','<?= $fechaPlanVisita['Miercoles'] ?>');" > <label id="totalInstDiaTres"><?= $arrMiercoles[2] ?></label></td>
								<!--<td><img src='iconos/otras_actividades.png' title="Eventos" > 0</td>
								<td><img src='iconos/otras_actividades.png' title="Supervisión" > 0</td>
								<td><img src='iconos/Radar24.png' title="Localizador" > 0</td>-->
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style="border: 3px solid #557a95;border-radius: 10px" valign="top">
						<div style="height:140px; overflow:auto;">
							<table id="tblDiaTres" width="100%">
								<?= $arrMiercoles[0] ?>
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
					<td style="border-bottom: 2px solid #557a95"><label class="titulosDias">
<?php 
						echo "Jueves: ".$fechaCompleta['Jueves']; 
						$arrJueves = planesVisitasCalendario($fechaPlanVisita['Jueves'], 'plan', $conn, $idUsuario);
?>
					</td>
				</tr>
				<tr>
					<td align="center">
						<table width="50%">
							<tr>
								<td><img src='iconos/otras_actividades.png' title="Otras Actividades" onclick="abreOtrasActividades('DiaCuatro','<?= $fechaPlanVisita['Jueves'] ?>');" ><label id="totalOtrasActividadesDiaCuatro"><?= $arrJueves[3] ?></label></td>
								<td><img src='iconos/personas24.png' title="Personas" onclick="abreBuscarPersona('DiaCuatro','<?= $fechaPlanVisita['Jueves'] ?>');" > <label id="totalPersonasDiaCuatro"><?= $arrJueves[1] ?></label></td>
								<td><img src='iconos/instituciones24.png' title="Instituciones" onclick="abreBuscarInst('DiaCuatro','<?= $fechaPlanVisita['Jueves'] ?>');" > <label id="totalInstDiaCuatro"><?= $arrJueves[2] ?></label></td>
								<!--<td><img src='iconos/otras_actividades.png' title="Eventos" > 0</td>
								<td><img src='iconos/otras_actividades.png' title="Supervisión" > 0</td>
								<td><img src='iconos/Radar24.png' title="Localizador" > 0</td>-->
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style="border: 3px solid#557a95;border-radius: 10px" valign="top">
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
					<td style="border-bottom: 2px solid#557a95"><label class="titulosDias">
<?php 
						echo "Viernes: ".$fechaCompleta['Viernes'];
						$arrViernes = planesVisitasCalendario($fechaPlanVisita['Viernes'], 'plan', $conn, $idUsuario);
?>
					</td>
				</tr>
				<tr>
					<td align="center">
						<table width="50%">
							<tr>
								<td><img src='iconos/otras_actividades.png' title="Otras Actividades" onclick="abreOtrasActividades('DiaCinco','<?= $fechaPlanVisita['Viernes'] ?>');" ><label id="totalOtrasActividadesDiaCinco"><?= $arrViernes[3] ?></label></td>
								<td><img src='iconos/personas24.png' title="Personas" onclick="abreBuscarPersona('DiaCinco','<?= $fechaPlanVisita['Viernes'] ?>');"> <label id="totalPersonasDiaCinco"><?= $arrViernes[1] ?></label></td>
								<td><img src='iconos/instituciones24.png' title="Instituciones" onclick="abreBuscarInst('DiaCinco','<?= $fechaPlanVisita['Viernes'] ?>');" > <label id="totalInstDiaCinco"><?= $arrViernes[2] ?></label></td>
								<!--<td><img src='iconos/otras_actividades.png' title="Eventos" > 0</td>
								<td><img src='iconos/otras_actividades.png' title="Supervisión" > 0</td>
								<td><img src='iconos/Radar24.png' title="Localizador" > 0</td>-->
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style="border: 3px solid #557a95;border-radius: 10px" valign="top">
						<div style="height:140px; overflow:auto;">
							<table id="tblDiaCinco" width="100%">
								<?= $arrViernes[0] ?>
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
<?php 
						echo "Sábado: ".$fechaCompleta['Sábado'];
						$arrSabado = planesVisitasCalendario($fechaPlanVisita['Sábado'], 'plan', $conn, $idUsuario);
?>
					</td>
				</tr>
				<tr>
					<td align="center">
						<table width="50%">
							<tr>
								<td><img src='iconos/otras_actividades.png' title="Otras Actividades" onclick="abreOtrasActividades('DiaSeis','<?= $fechaPlanVisita['Sábado'] ?>');" ><label id="totalOtrasActividadesDiaSeis"><?= $arrSabado[3] ?></label></td>
								<td><img src='iconos/personas24.png' title="Personas" onclick="abreBuscarPersona('DiaSeis','<?= $fechaPlanVisita['Sábado'] ?>');"> <label id="totalPersonasDiaSeis"><?= $arrSabado[1] ?></label></td>
								<td><img src='iconos/instituciones24.png' title="Instituciones" onclick="abreBuscarInst('DiaUno','<?= $fechaPlanVisita['Sábado'] ?>');" > <label id="totalInstDiaSeis"><?= $arrSabado[2] ?></label></td>
								<!--<td><img src='iconos/otras_actividades.png' title="Eventos" > 0</td>
								<td><img src='iconos/otras_actividades.png' title="Supervisión" > 0</td>
								<td><img src='iconos/Radar24.png' title="Localizador" > 0</td>-->
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style="border: 3px solid #557a95;border-radius: 10px" valign="top">
						<div style="height:140px;overflow:auto" > 
							<table id="tblDiaSeis" width="100%">
								<?= $arrSabado[0] ?>
							</table>
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</div>