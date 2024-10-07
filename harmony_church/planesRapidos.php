<?php
	$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");
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
	$fechaCompleta["Sabado"] = date('d/m/Y', strtotime('+'.(6-$day).' days'));
	$fechaPlanVisita["Domingo"] = date('Y-m-d', strtotime('-'.$day.' days'));
	$fechaPlanVisita["Lunes"] = date('Y-m-d', strtotime('+'.(1-$day).' days'));
	$fechaPlanVisita["Martes"] = date('Y-m-d', strtotime('+'.(2-$day).' days'));
	$fechaPlanVisita["Miercoles"] = date('Y-m-d', strtotime('+'.(3-$day).' days'));
	$fechaPlanVisita["Jueves"] = date('Y-m-d', strtotime('+'.(4-$day).' days'));
	$fechaPlanVisita["Viernes"] = date('Y-m-d', strtotime('+'.(5-$day).' days'));
	$fechaPlanVisita["Sabado"] = date('Y-m-d', strtotime('+'.(6-$day).' days'));
	//print_r($fechaPlanVisita);
	//echo "id: ".$ids;
	
?>
<input type="hidden" name="fecha" id="fecha"  /> 
<input type="hidden" name="anterior" id="anterior"  />
<input type="hidden" id="hdnFechaCalendario" value="<?= date('Y-m-d') ?>" />
<input type="hidden" name="hdnDiaUno" id="hdnDiaUno" value="<?= date('Y-m-d', strtotime('+'.(1-$day).' days')) ?>"/>
<input type="hidden" name="hdnDiaDos" id="hdnDiaDos" value="<?= date('Y-m-d', strtotime('+'.(2-$day).' days')) ?>" />
<input type="hidden" name="hdnDiaTres" id="hdnDiaTres" value="<?= date('Y-m-d', strtotime('+'.(3-$day).' days')) ?>" />
<input type="hidden" name="hdnDiaCuatro" id="hdnDiaCuatro" value="<?= date('Y-m-d', strtotime('+'.(4-$day).' days')) ?>" />
<input type="hidden" name="hdnDiaCinco" id="hdnDiaCinco"  value="<?= date('Y-m-d', strtotime('+'.(5-$day).' days')) ?>"/>
<table width="100%">
	<tr>
		<td>
			<table width="100%" border="0">
				<tr>
					<td>
						<label class="nombreModulos">
							Planeación Rápida
						</label>
					</td>
					<td align="center" width="400px" >
					<table><tr><td>
						<label id="lblRep">Ruta</label></td><td>
						
<?php
						if($tipoUsuario != 4){
							echo "<div class=\"selectBox\" onclick=\"filtrosUsuarios('cal');\">
									<select style=\"width:200px\">
										<option id=\"sltMultiSelectCal\">Seleccione</option>
									</select>
								</div>";
						}else{
							echo '<div class="select"><select id="sltRepreCalendario" style="width:250px;">';
							$repre = sqlsrv_query($conn, "select user_snr, lname + ' ' + fname as nombre from users where user_snr in ('".$ids."')");
							while($rep = sqlsrv_fetch_array($repre)){
								echo '<option value="'.$rep['user_snr'].'">'.$rep['nombre'].'</option>';
							}
							echo '</select><div class="select_arrow"></div></div>';
						}						
?>
					</td></tr></table></td>
					<td align="right" valign="top">
						<img src="iconos/close.png" onClick="cerrarInformacion();" width="15px" title="Cerrar" />
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<hr>
<table width="100%">
	<tr>
		<td>
<div id="divPersInst">
	<div id="tabsPersonasInst" style="font-size:10px" >
		<ul>
			<li><a href="#personas" id="tabPersonas">Personas</a></li>
			<li><a href="#instituciones" id="tabInstituciones">Instituciones</a></li>
		</ul>
		
		<div id="personas" style="height:350px;overflow:auto;">
			<table id="tblPersonasPlanesRapidos">
<?php
			$queryPersonas = "select p.pers_snr, p.LNAME,  NAME_FATHER, FNAME, c.NAME as especialidad, 
				categ.name as categoria, i.NAME as institucion, i.STREET1 as calle,
				city.name as colonia, d.NAME as delegacion, state.NAME as estado,
				bri.name as brick, freq.name as freq, city.zip as cp,
				(SELECT COUNT(*) FROM VISITPERS VP, CYCLES CICLOS 
				WHERE VP.REC_STAT=0 AND CICLOS.REC_STAT=0 AND P.PERS_SNR=VP.PERS_SNR 
				AND '".$hoy."' BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE 
				AND VISIT_DATE BETWEEN CICLOS.START_DATE AND CICLOS.FINISH_DATE) as visitas
				from person p
				inner join PERS_SREP_WORK psw on p.pers_snr = psw.PERS_SNR
				inner join CODELIST c on p.SPEC_SNR = c.CLIST_SNR
				inner join CODELIST categ on p.OPINION_SNR = categ.CLIST_SNR
				inner join inst i on i.INST_SNR = psw.LOC_SNR
				inner join city on city.CITY_SNR = i.CITY_SNR
				inner join DISTRICT d on d.DISTR_SNR = city.DISTR_SNR
				inner join STATE on state.STATE_SNR = city.STATE_SNR
				inner join IMS_BRICK bri on bri.IMSBRICK_SNR = city.IMSBRICK_SNR
				inner join CODELIST freq on freq.CLIST_SNR = p.TITEL_SNR
				where psw.USER_SNR in ('".$ids."')
				and p.REC_STAT = 0
				and psw.REC_STAT = 0
				and c.REC_STAT = 0
				and categ.REC_STAT = 0
				and i.REC_STAT = 0
				and city.REC_STAT = 0
				and d.REC_STAT = 0
				and STATE.REC_STAT = 0
				and bri.REC_STAT = 0 ";
				
			$rsPersonas = sqlsrv_query($conn, $queryPersonas);
			
			$item = 1;
			while($persona = sqlsrv_fetch_array($rsPersonas)){
				echo "<tr>
						<td>
							<div class=\"item\" id=\"".$persona['pers_snr']."\">
								".$persona['LNAME']." ".$persona['NAME_FATHER']." ".$persona['FNAME']."<br>
								".$persona['especialidad']."<br>
								".$persona['institucion']."
							</div>
						</td>
					</tr>";
				$item++;
			}
?>
			</table>
			<input type="hidden" id="hdnTotalPersonasPlanesRapidos" value="<?= $item ?>"/>
		</div>
	</div>
</div>
		</td>
		<td valign="top">
			<table border="1" class="grid3">
				<thead >
					<tr>
						<td style="width:102px;">Lunes: <br><?= $fechaCompleta['Lunes'] ?></td>
						<td style="width:102px;">Martes: <br><?= $fechaCompleta['Martes'] ?></td>
						<td style="width:102px;">Miercoles: <br><?= $fechaCompleta['Miercoles'] ?></td>
						<td style="width:102px;">Jueves: <br><?= $fechaCompleta['Jueves'] ?></td>
						<td style="width:102px;">Viernes: <br><?= $fechaCompleta['Viernes'] ?></td>
						<td style="width:50px;">&nbsp;</td>
					</tr>
				</thead>
				<tbody style="height:390px">
<?php
				for($i=7;$i<23;$i++){
					for($j=0;$j<2;$j++){
						$min = $j*30;
						echo "<tr><td style=\"width:100px;\"><div class=\"day\" id=\"lunes_".$i.$j."\"></div></td><td style=\"width:100px;\"><div class=\"day\" id=\"martes_".$i.$j."\"></div></td><td style=\"width:100px;\"><div class=\"day\" id=\"miercoles_".$i.$j."\"></div></td><td style=\"width:100px;\"><div class=\"day\" id=\"jueves_".$i.$j."\"></div></td><td style=\"width:100px;\"><div class=\"day\" id=\"viernes_".$i.$j."\"></div></td><td style=\"width:50px;\">".$i.":".str_pad($min, 2, "0", STR_PAD_LEFT)."</td></tr>";
					}
				}
?>
				</tbody>
			</table>
		</td>
	</tr>
</table>