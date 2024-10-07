<table border="0" width="100%" >
	<tr>
		<td class="negrita" colspan="3">Representante:
<?php
			if($tipoUsuario != 4){
				echo "<div class=\"selectBox\" onclick=\"filtrosUsuarios('copiarPlanes');\">
						<select style=\"width:200px\">
							<option id=\"sltMultiSelectCopiarPlanes\">Seleccione</option>
						</select>
					</div>";
				}else{
					echo '<div class="select">
						<select id="sltRepreCopiarPlanes" style="width:250px;">';
					$repre = sqlsrv_query($conn, "select user_snr, lname + ' ' + fname as nombre from users where user_snr in ('".$ids."')");
					while($rep = sqlsrv_fetch_array($repre)){
						echo '<option value="'.$rep['user_snr'].'">'.$rep['nombre'].'</option>';
					}
					echo '</select><div class="select_arrow"></div></div>';
				}						
?>
		</td>
	</tr>
	<tr>
		<td class="negrita">
			Fecha Inicial:
			<input type="text" id="txtFechaIcopiarPlanes" size="10" value="<?= date("Y-m-d") ?>" readonly />
		</td>
		<td class="negrita">
			Fecha Final:
			<input type="text" id="txtFechaFcopiarPlanes" size="10" value="<?= date("Y-m-d") ?>" readonly  />
		</td>
		<td class="negrita">
			Fecha Objetivo:
			<input type="text" id="txtFechaOcopiarPlanes" size="10" value="<?= date("Y-m-d") ?>" readonly  />
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<input type="radio" id="optTodos" name="optPlanes" value="todos" />
            <label for="optTodos">Todos</label>
			<input type="radio" id="optVisitados" name="optPlanes" value="visitados" />
            <label for="optVisitados">Visitados</label>
			<input type="radio" id="optNoVisitados" name="optPlanes" value="novisitados"  />
            <label for="optNoVisitados">No Visitados</label>
		</td>
		<td align="right">
			<button id="btnPrevioCopiarPlanes" style="width:80px;display:none;">Previo</button>&nbsp;&nbsp;&nbsp;
			<button id="btnCopiarPlanesFuncion" style="width:80px;">Copiar</button>&nbsp;&nbsp;&nbsp;
			<button onClick="cerrarInformacion();" style="width:80px;">Cerrar</button>
		</td>
	</tr>
	<tr>
		<td colspan="4"><hr></td>
	</tr>
	<tr>
		<td colspan="4">
			<table id="tblCopiarPlanes" width="100%">
				<thead>
					<tr>
						<td width="50px">Fecha</td>
						<td width="50px">Hora</td>
						<td width="250px">Persona</td>
						<td width="250px">Comentarios</td>
					</tr>
				</thead>
				<tbody>
				</tbody>
				<tfoot>
				</tfoot>
			</table>
		</td>
	</tr>
</table>