<table width="700px" bgcolor="#FFFFFF" align="center" border="0">
	<tr>
		<td colspan="2" class="titulo">Periodo</td>
	</tr>
	<tr>
		<td colspan="2">
			<button id="btnGuardar" type="button">
				<img src="iconos/ok.png" width="15px"> Guardar
			</button>
			<button name="btnCancelar" type="button">
				<img src="iconos/tache.png" width="15px"> Cancelar					 
			</button>
			<button name="btnCancelar" type="button">
				<img src="iconos/buscar.ico" width="15px"> Mostrar					 
			</button>
		</td>
	</tr>
	<tr>
		<td class="negrita" width="20%">
			Ciclo:<br>
			<input type="text" size="4" value="<?= date('Y') ?>"/>-<input type="text" size="4" />
		</td>
		<td class="negrita">
			Descripci&oacute;n:<br>
			<input type="text" size="30" />
		</td>
	</tr>
	<tr>
		<td class="negrita">
			fecha de inicio:<br>
			<input type="text" id="txtFechaOtrasActividades" size="10" >
			<label for="txtFechaOtrasActividades"><img src="iconos/cal2.png" /></label>
		</td>
		<td class="negrita">
			A fecha:<br>
			<input type="text" id="txtFechaOtrasActividadesFinal" size="10" >
			<label for="txtFechaOtrasActividadesFinal"><img src="iconos/cal2.png" /></label>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table border="1" class="grid" width="100%">
				<thead>
					<tr>
						<td>A&ntilde;o</td>
						<td>N&uacute;mero</td>
						<td>D&iacute;a de la semana</td>
						<td>Fecha</td>
						<td>Semana n&uacute;mero</td>
						<td>D&iacute;a de la Semana (n&uacute;mero)</td>
						<td>D&iacute;a peri&oacute;do</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>2016</td>
						<td>01-01</td>
						<td>Domingo</td>
						<td>01/05/2016</td>
						<td>0</td>
						<td>0</td>
						<td>1</td>
					</tr>
					<tr>
						<td>2016</td>
						<td>01-01</td>
						<td>Lunes</td>
						<td>01/05/2016</td>
						<td>0</td>
						<td>0</td>
						<td>1</td>
					</tr>
					<tr>
						<td>2016</td>
						<td>01-01</td>
						<td>Martes</td>
						<td>01/05/2016</td>
						<td>0</td>
						<td>0</td>
						<td>1</td>
					</tr>
					<tr>
						<td>2016</td>
						<td>01-01</td>
						<td>Miercoles</td>
						<td>01/05/2016</td>
						<td>0</td>
						<td>0</td>
						<td>1</td>
					</tr>
					<tr>
						<td>2016</td>
						<td>01-01</td>
						<td>Jueves</td>
						<td>01/05/2016</td>
						<td>0</td>
						<td>0</td>
						<td>1</td>
					</tr>
					<tr>
						<td>2016</td>
						<td>01-01</td>
						<td>Viernes</td>
						<td>01/05/2016</td>
						<td>0</td>
						<td>0</td>
						<td>1</td>
					</tr>
					<tr>
						<td>2016</td>
						<td>01-01</td>
						<td>Sabado</td>
						<td>01/05/2016</td>
						<td>0</td>
						<td>0</td>
						<td>1</td>
					</tr>					
				</tbody>
				<tfoot>
					<tr>
						<td colspan="7">
							&nbsp;
						</td>
					</tr>
				</tfoot>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="negrita" valign="top">
			Comentarios<br>
			<textarea cols="60" rows="5"></textarea>
		</td>
	</tr>
</table>