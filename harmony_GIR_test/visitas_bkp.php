<input type="hidden" id="hdnIdVisita" value="" />
<input type="hidden" id="hdnPantallaVisitas" value="" />
<table width="95%" border="0">
	<tr>
		<td>
			<h3>Visitas</h3>
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
					<!--<button id="btnGuardarEncuesta" type="button">-->
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
			<li><a href="#tabVisita">Visita</a></li>
			<li><a href="#tabProductos">Productos</a></li>
			<li><a href="#tabMuestras">Muestras</a></li>
		</ul>
	
		<div id="tabVisita">		
			<div id="datosVisitas" >
				<p style="margin-left:1em;font-family:Arial;font-size:10px;text-align:justify;">
					<b><label id="lblPersonaVisita"></label></b><br>
					<label id="lblEspecialidadVisita"></label><br>
					<label id="lblDireccionVisita"></label><br>
					Brick: <label id="lblBrickVisita"></label>
				</p>
			</div>
			<!--<div id="divVisita">-->
				<table border="0" id="tblVisita" cellspacing="5" width="95%">
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
						<td class="negrita">
							Hora de la visita:<br>
							<select id="lstHoraVisita">
<?php
								for($i=0;$i<24;$i++){
									echo '<option value="'.str_pad($i,2,'0', STR_PAD_LEFT).'">'.str_pad ($i,2,'0', STR_PAD_LEFT).'</option>';
								}
?>
							</select>
							<span id="spnPuntosHora">:</span>
							<select id="lstMinutosVisita">
<?php
								for($i=0;$i<60;$i++){
									echo '<option value="'.str_pad ($i,2,'0', STR_PAD_LEFT).'">'.str_pad ($i,2,'0', STR_PAD_LEFT).'</option>';
								}
?>
							</select>
						</td>
					</tr>
					<tr>
						<td class="rojo">
							Código de la visita:<br>
							<select id="lstCodigoVisita">
								<option value="0">Seleccione</option>
								<?php
									$queryCodigo = "select * from codelist where CLIB_SNR = '9FE706F6-0B6E-4697-9602-1B4060ABB9C2' and status = 1 order by name";
									$rsCodigo = sqlsrv_query($conn, $queryCodigo);
									while($codigo = sqlsrv_fetch_array($rsCodigo)){
										echo '<option value="'.$codigo['CLIST_SNR'].'" >'.utf8_encode($codigo['NAME']).'</option>';
									}
								?>
							</select>
						</td>
						<td class="negrita" colspan="2" rowspan="2" valign="top">
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
											echo '<label for="'.$idChk.'"><input onclick="agregaDesVisAcompa(\''.$acom['NAME'].'\',\''.$idChk.'\');" type="checkbox" id="'.$idChk.'" value="'.$acom['CLIST_SNR'].'" />'.$acom['NAME'].'</label>';
											//echo $contadorChecks."<br>";
										}
?>
									</div>
								</div>
								<input type="hidden" id="hdnTotalChecksVisitas" value="<?= $contadorChecks ?>">
								<input type="hidden" id="hdnDescripcionChkVisitas" value="<?= ($descripcionesCheck == '') ? "Seleccione" : $descripcionesCheck ?>" />
							</form>
						</td>
					</tr>
					<tr>
						<td class="rojo">
							Comentario de la visita:<br>
							<textarea rows="4" cols="30" id="txtComentariosVisita" ></textarea>
						</td>
						
					</tr>
					<tr>
						<td class="negrita">
							Objetivo de la siguiente visita:<br>
							<textarea rows="4" cols="30" id="txtInfoSiguienteVisita" ></textarea>
						</td>
						<td class="negrita" colspan="2">
							Enfermedades que atiende:<br>
							<textarea rows="4" cols="40" id="txtComentariosMedico"></textarea>
						</td>
					</tr>
					<tr>
						
					</tr>
				</table>
			<!--</div>-->
		</div>
	
		<div id="tabProductos" style="overflow:auto; height:350px;">
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
			
		<div id="tabMuestras">
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
						<tr>
							<td>
								<input type="hidden" id="hdnTotalPromociones" value=""/>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
			<br>
			<table border="0" id="tblFirma" style="display:none;">
				<tr>
					<td colspan="3" align="center">
						<canvas id="canvasFirmaVisitas" width="550" height="100"></canvas>
					</td>
				</tr>
				<tr>
					<td align="center">
						<button id="btnLimpiarFirma" onClick="limpiar();">Limpiar</button>
					</td>
					<td align="center">
						Firmar sin salir del recuadro.
					</td>
					<td align="center">
						<button id="btnGuardarFirma" onClick="guardarFirma();">Guardar</button>
						<input type="hidden" id="hdnFirma" value="" />
					</td>
				</tr>
			</table>
			<img id="imgFirma" width="550" height="100" style="border: solid black thin;" />
		</div>
	</div>
</center>