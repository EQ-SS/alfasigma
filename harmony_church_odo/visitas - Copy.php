<!--<div id="resp" style="display:none;"></div>-->
<table width="70%" border="0" valign="center">
	<tr>
		<td>
			<h1>Visitas</h1>
		</td>
		<td align="right">
			<table>
				<tr>
					<td>
						<button id="btnSiguienteVisita" type="button">
							Planear Suiguiente Visita
						</button>
					</td>
					<td>
						<!--<button id="btnEncuesta" type="button">
							<img src="iconos/encuesta.png" width="15px"> Encuesta
						</button>-->
					</td>
					<td>
						<button id="btnGuardarVisitas" type="button">
							<!--<img src="iconos/ok.png" width="15px">--> Guardar
						</button>
					</td>
					<td>
						<button id="btnCancelarVisitas" type="button">
							<!--<img src="iconos/tache.png" width="15px">--> Cancelar					 
						</button>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<hr>
<div id="divEncuesta" style="display:none;">
	<table width="100%" bgcolor="#FFFFFF" border="0">
		<tr>
			<td class="titulo" colspan="2">
				Encuesta de visita médica
			</td>
		</tr>
		<tr>
			<td colspan="2" class="negrita">
				1.	Dr(a) por favor mencione 3 médicos que para usted, desde el punto de vista científico, sean los más respetados en el área de Diabetes en su país.
			</td>
		</tr>
		<tr>
			<td width="20%">
				Médico 1:
			</td>
			<td>
				<input type="text" size="50" />
			</td>
		</tr>
		<tr>
			<td width="20%">
				Médico 2:
			</td>
			<td>
				<input type="text" size="50" />
			</td>
		</tr>
		<tr>
			<td width="20%">
				Médico 3:
			</td>
			<td>
				<input type="text" size="50" />
			</td>
		</tr>
		<tr>
			<td colspan="2"><br>
				<hr>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="negrita"><br>
				2.	Dr(a) ¿Cuál es su pasatiempo favorito?
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="text" size="50" />
			</td>
		</tr>
		<tr>
			<td colspan="2"><br>
				<hr>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="negrita"><br>
				3.	Dr(a) ¿Cuáles son las 3 fechas más importantes que usted celebra?
			</td>
		</tr>
		<tr>
			<td width="10%">
				Fecha 1:
			</td>
			<td>
				<input type="text" size="50" />
			</td>
		</tr>
		<tr>
			<td width="10%">
				Fecha 2:
			</td>
			<td>
				<input type="text" size="50" />
			</td>
		</tr>
		<tr>
			<td width="10%">
				Fecha 3:
			</td>
			<td>
				<input type="text" size="50" />
			</td>
		</tr>
		<tr>
			<td colspan="2"><br>
				<hr>
			</td>
		</tr>
		<tr>
			<td colspan="2"><br>
				<form enctype="multipart/form-data" action="subir-archivos.php" method="POST" class="negrita">
					<input type="hidden" name="MAX_FILE_SIZE" value="250000" />
					Subir fotografía:
					<input name="archivo-a-subir" type="file" />
					<button id="btnGuardarEncuesta" type="button">
						<img src="iconos/upload.png" width="15px"> Subir Imagen
					</button>&nbsp;&nbsp;&nbsp;
				</form>
			</td>
		</tr>
		<tr>
			<td width="10%" colspan="2"><br>
				<button id="btnGuardarEncuesta" type="button">
					<img src="iconos/ok.png" width="15px"> Guardar Encuesta
				</button>
				<button id="btnCancelarEncuesta" type="button">
					<img src="iconos/tache.png" width="15px"> Cancelar Encuesta				 
				</button>
			</td>
		</tr>
	</table>
</div>
		
<center>
	<div id="tabsVisitas">
		<ul>
			<li><a href="#tabs-1">Visita</a></li>
			<li><a href="#tabs-2">Productos</a></li>
			<li><a href="#tabs-3">Muestras</a></li>
		</ul>
	
		<div id="tabs-1">		
			<div id="datosVisitas" >
				<p style="margin-left:1em;font-family:Arial;font-size:10px;text-align:justify;">
					<b><label id="lblPersonaVisita"></label></b><br>
					<label id="lblEspecialidadVisita"></label><br>
					<label id="lblDireccionVisita"></label><br>
					Brick: <label id="lblBrickVisita"></label>
				</p>
			</div><br><br>
			<table border="0" id="tblVisita" cellspacing="5">
				<tr>
					<td class="rojo">
						Representante:<br>
						<select id="sltRepreVisita">
						</select>
					</td>
					<td class="negrita">
						Fecha de la visita:<br>
						<input type="text" id="txtFechaVisita" value="" />
					</td>
				</tr>
				<tr>
					<td class="negrita">
						Hora de la visita:<br>
						<select id="lstHoraVisita">
<?php
							for($i=0;$i<24;$i++){
								/*if($hora == str_pad($i,2,'0', STR_PAD_LEFT)){
									echo '<option value="'.str_pad($i,2,'0', STR_PAD_LEFT).'" selected>'.str_pad ($i,2,'0', STR_PAD_LEFT).'</option>';
								}else{*/
									echo '<option value="'.str_pad($i,2,'0', STR_PAD_LEFT).'">'.str_pad ($i,2,'0', STR_PAD_LEFT).'</option>';
								//}
							}
?>
						</select>
						<span id="spnPuntosHora">:</span>
						<select id="lstMinutosVisita">
<?php
							for($i=0;$i<60;$i++){
								/*if($minutos == str_pad ($i,2,'0')){
									echo '<option value="'.str_pad ($i,2,'0', STR_PAD_LEFT).'" selected>'.str_pad ($i,2,'0', STR_PAD_LEFT).'</option>';
								}else{*/
									echo '<option value="'.str_pad ($i,2,'0', STR_PAD_LEFT).'">'.str_pad ($i,2,'0', STR_PAD_LEFT).'</option>';
								//}
							}
?>
						</select>
					</td>
					<td class="rojo">
						Código de la visita:<br>
						<select id="lstCodigoVisita">
							<option value="0">Seleccione</option>
							<?php
								$queryCodigo = "select * from codelist where CLIB_SNR = '9FE706F6-0B6E-4697-9602-1B4060ABB9C2' and status = 1 order by name";
								$rsCodigo = sqlsrv_query($conn, $queryCodigo);
								while($codigo = sqlsrv_fetch_array($rsCodigo)){
									/*if($visitCode == $codigo['CLIST_SNR']){
										echo '<option value="'.$codigo['CLIST_SNR'].'" selected>'.utf8_encode($codigo['NAME']).'</option>';
									}else{*/
										echo '<option value="'.$codigo['CLIST_SNR'].'" >'.utf8_encode($codigo['NAME']).'</option>';
									//}
								}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="rojo">
						Comentario de la visita:<br>
						<textarea rows="4" cols="50" id="txtComentariosVisita" ></textarea>
					</td>
					<td class="negrita" rowspan="3" valign="top">
						Visita acompañada:<br>
						<form>
							<div class="multiselect">
								<div class="selectBox" onclick="showCheckboxes()">
									<select>
										<option id="sltMultiSelect">Selecciona</option>
									</select>
									<div class="overSelect"></div>
								</div>
								<div id="checkboxesVisitas">
<?php
									$queryAcom = "select * from codelist where CLIb_SNR = 'F10D9FEA-F694-4299-89CE-63DE6842598B' and status = 1 order by name";
									$rsAcom = sqlsrv_query($conn, $queryAcom);
									$contadorChecks = 0;
									$idChecks = '';
									$descripcionesCheck = '';
									while($acom = sqlsrv_fetch_array($rsAcom)){
										$contadorChecks++;
										$idChk = "acompa".$contadorChecks;
										/*if(in_array($acom['CLIST_SNR'],$visitAcom)){
											echo '<label for="'.$idChk.'"><input onclick="agregaDesVisAcompa(\''.$acom['NAME'].'\',\''.$idChk.'\');" type="checkbox" id="'.$idChk.'" value="'.$acom['CLIST_SNR'].'" checked />'.$acom['NAME'].'</label>';
											$descripcionesCheck .= $acom['NAME'].";";
										}else{*/
											echo '<label for="'.$idChk.'"><input onclick="agregaDesVisAcompa(\''.$acom['NAME'].'\',\''.$idChk.'\');" type="checkbox" id="'.$idChk.'" value="'.$acom['CLIST_SNR'].'" />'.$acom['NAME'].'</label>';
										//}
									}
?>
								</div>
							</div>
							<input type="hidden" id="hdnTotalChecksVisitas" value="<?= $contadorChecks ?>"/>
							<input type="hidden" id="hdnDescripcionChkVisitas" value="<?= ($descripcionesCheck == '') ? "Seleccione" : $descripcionesCheck ?>" />
						</form>
						<!--<select id="lstVisitaAcompa">
							<option value="0">Seleccione</option>
<?php
							/*$queryAcom = "select * from codelist where CLIb_SNR = 'F10D9FEA-F694-4299-89CE-63DE6842598B' and status = 1 order by name";
							$rsAcom = sqlsrv_query($conn, $queryAcom);
							while($acom = sqlsrv_fetch_array($rsAcom)){
								if($visAcomp == $acom['CLIST_SNR']){
									echo '<option value="'.$acom['CLIST_SNR'].'" selected>'.$acom['NAME'].'</option>';
								}else{
									echo '<option value="'.$acom['CLIST_SNR'].'">'.$acom['NAME'].'</option>';
								}
							}*/
?>
						</select>-->
					</td>
				</tr>
				<tr>
					<td class="negrita">
						Objetivo de la siguiente visita:<br>
						<textarea rows="4" cols="50" id="txtInfoSiguienteVisita" ></textarea>
					</td>
				</tr>
				<tr>
					<td class="negrita">
						Enfermedades que atiende:<br>
						<textarea rows="4" cols="50" id="txtComentariosMedico"></textarea>
					</td>
				</tr>
			</table>
		</div>
	
		<div id="tabs-2" style="overflow:auto; height:350px;">
			<table border="0" id="tblProductosVisitas" width="100%" class="grid">
				<thead>
					<tr>
						<td>Posición</td>
						<td class="negrita">Producto</td>
						<td class="negrita">% tiempo</td>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
			
		<div id="tabs-3">
			<div style="height:200px;overflow:auto;">
				<table border="0" id="tblMuestras" width="100%" >
					<thead>
						<tr align="center">
							<td class="negrita">&nbsp;Producto</td>
							<td class="negrita">&nbsp;Presentación</td>
							<td class="negrita">&nbsp;Lote</td>
							<td class="negrita">&nbsp;Cantidad</td>
						</tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot>
						<input type="hidden" id="hdnTotalPromociones" value="<?= $numTexto-1 ?>"/>
					</tfoot>
				</table>
			</div>
			<br>
			<table width="100%" id="tblFirma" border="1">
				<tr>
					<td align="center">
<?php
						//if($firma == ''){
?>
							<div id="signature-pad" class="m-signature-pad">
								<div class="m-signature-pad--body">
									<canvas id="firma"></canvas>
								</div>
								<div class="m-signature-pad--footer">
									<div class="description">Firma sin salir del recuadro.</div>
										<button id="btnBorrarFirma" type="button" class="button clear" data-action="clear">Borrar</button>
										<button type="button" class="button save" data-action="save" style="display:none">Guardar</button>
										<button id="btnGuardarFirma" class="button save" type="button">Guardar Firma</button>
									</div>
							</div>	
							<input type="hidden" id="hdnFima" />
<?php
						//}else{
?>
							<!--<img style="border: black solid 1px;" src="data:image/jpeg;base64,<?php //$firma ?>" width="400" height="100"  />-->
<?php
						//}
?>
					</td>
				</tr>
			</table>
		</div>
	</div>
</center>