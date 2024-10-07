<center>
	<table width="70%" border="0" bgcolor="#FFFFFF">
		<tr>
			<td class="tituloModulo" colspan="3">Supervisión</td>
		</tr>
		<tr>
			<td colspan="3"><hr></td>
		</tr>
		<tr>
			<td colspan="2">
				<button id="btnGuardar" type="button">
					<img src="iconos/ok.png" width="15px"> Guardar
				</button>
				<button name="btnCancelar" type="button">
					<img src="iconos/tache.png" width="15px"> Cancelar					 
				</button>
			</td>
			<td align="right">
				<button type="button" style="font-size:10px;">
					Copia de la última supervisión realizada
				</button>
			</td>
		</tr>
		<tr>
			<td width="33%" class="negrita">
				Supervisor<br>
				<select>
					<option>Seleccione</option>
				</select>
			</td>
			<td width="33%" class="negrita">
				Representante<br>
				<select>
					<option>Seleccione</option>
				</select>
			</td>
			<td class="negrita">
				IMS o ATV Brick<br>
				<select>
					<option>Seleccione</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="33%" class="negrita">
				Fecha<br>
				<input type="text" id="txtFechaSupervision">
				<label for="txtFechaSupervision"><img src="iconos/cal2.png" ></label>
			</td>
			<td width="33%" class="negrita">
				Siguiente Supervisión<br>
				<input type="text" id="txtFechaSiguienteSupervision">
				<label for="txtFechaSiguienteSupervision"><img src="iconos/cal2.png" ></label>
			</td>
			<td class="negrita">
				Productos<br>
				<input type="text">
				<button id="btnAgregarProductosSupervision">...</button>
			</td>
		</tr>
		<tr>
			<td class="negrita">
				<input type="checkbox">Mostrar el Reporte<br>
				<input type="checkbox">Mostrar el Plan
			</td>
			<td class="negrita">
				<input type="checkbox">Mostrar siguiente supervisión en plan<br>
				<input type="checkbox">No anunciado
			</td>
			<td>
				Promedio de todas las notas
				<input type="text" />
			</td>
		</tr>
		<tr>
			<td colspan="3" class="negrita">
				Comentarios<br>
				<textarea cols="80" rows="5"></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<div id="tabSupervision" >
					<ul>
						<li><a href="#conclusiones">Conclusiones</a></li>
						<li><a href="#comunicaciones">Habilidades de Comunicación</a></li>
					</ul>
					
					<div id="conclusiones">
						<table width="100%" border="0">
							<tr>
								<td width="50%">
									Que necesito mejorar<br>
									<textarea cols="45" rows="5"></textarea>
								</td>
								<td>
									Que necesito planear para mejorar<br>
									<textarea cols="45" rows="5"></textarea>
								</td>
							</tr>
							<tr>
								<td>
									Siguiente Supervisión<br>
									<input type="text" id="txtFechaConclusiones">
									<label for="txtFechaConclusiones"><img src="iconos/cal2.png" ></label>
								</td>
								<td>&nbsp;</td>
							</tr>
						</table>
					</div>
					<div id="comunicaciones">
						<table width="100%">
							<tr>
								<td>
									¿cómo son las habilidades de comunicación?
								</td>
								<td>
									<button>Comentarios</button>
								</td>
								<td>
									<select>
										<option>Seleccione</option>
										<option>1</option>
										<option>2</option>
										<option>3</option>
										<option>4</option>
										<option>5</option>
									</select>
								</td>
								<td>
									<input type="text" />
								</td>
							</tr>
							<tr>
								<td>
									Palabras de apertura
								</td>
								<td>
									<button>Comentarios</button>
								</td>
								<td>
									<select>
										<option>Seleccione</option>
										<option>1</option>
										<option>2</option>
										<option>3</option>
									</select>
								</td>
								<td>
									<input type="text" />
								</td>
							</tr>
						</table>
					</div>
				</div>
			</td>
		</tr>
	</table>
</center>