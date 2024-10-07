<table width="100%" border="0">
	<tr>
		<td class="tituloModulo">Mensajes</td>
		<td align="right">
			<button id="btnNuevoMensje">
				Nuevo mensaje
			</button>
			<button>
				Re-enviar
			</button>
		</td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
	<tr>
		<td class="negrita">Buscar</td>
		<td align="right">
			<img src="iconos/triangulo.png" id="imgApareceBuscar" width="20px" />
			<img src="iconos/triangulo2.png" id="imgOcultaBuscar" width="20px" style="display:none;" />
		</td>
	</tr>
	<tr id="trBuscarMensajes" style="display:none;">
		<td colspan="2" align="center">
			<table width="80%" style="border: #000000 1px solid;">
				<tr>
					<td class="negrita">
						Mensaje de:<br>
						<select>
							<option value="0">Seleccione</option>
						</select>
					</td>
					<td class="negrita">
						Inicio:<br>
						<input type="text" id="fechaInicioMensajes" size="10" />
						<label for="fechaInicioMensajes"><img src="iconos/cal2.png" /></label>
					</td>
					<td class="negrita">
						Término:<br>
						<input type="text" id="fechaTerminoMensajes" size="10" />
						<label for="fechaTerminoMensajes"><img src="iconos/cal2.png" /></label>
					</td>
					<td class="negrita">
						Estatus:<br>
						<select>
							<option value="0">Seleccione</option>
							<option value="0">No leído</option>
							<option value="0">Leer</option>
						</select>
					</td>
					<td>
						<button>
							Actualizar
						</button>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<button>Bandeja de entrada</button>
			<button>Mensajes enviados</button>
		</td>
	</tr>
	<tr>
		<td colspan="2" valign="top" style="border:#0000000 1px solid;" height="280px">
			<table width="100%" class="grid">
				<thead>
					<tr>
						<td>Mensajes de</td>
						<td>Objetivo</td>
						<td>Fecha</td>
					<tr>
				</thead>
				<tbody>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
				</tfoot>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="negrita">
			Texto del Mensaje:<br>
			<textarea cols="100" rows="5">
			</textarea>
		</td>
	</tr>
</table>