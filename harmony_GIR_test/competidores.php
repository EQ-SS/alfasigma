<input type="hidden" id="hdnIdProductosCompetidores" value="" />
<input type="hidden" id="hdnExistenciaCompetidores" value="" />
<input type="hidden" id="hdnPrecioCompetidores" value="" />
<table width="95%" border="0">
	<tr>
		<td>
			<h3>Competidores</h3>
		</td>
		<td align="right">
			<button id="btnGuardarCompetidor" type="button"
				class="btn bg-indigo waves-effect btn-indigo m-l-10">
				Guardar
			</button>
			<button id="btnCancelarCompetidor" type="button"
				class="btn bg-indigo waves-effect btn-indigo m-l-10">
				Cancelar					 
			</button>
		</td>
	</tr>
</table>
<hr>
<center>
<div id="datos" >
	<h2><b><label id="lblProductoCompetidor"></label></b></h2>
	<table id="tblCompetidor" width="95%" 
		class="table table-striped grid_scroll_body">
		<thead class="bg-grey">
			<tr>
				<td width="250px">Producto</td>
				<td width="100px">Existencia</td>
				<td width="100px">Precio</td>
			</tr>
		</thead>
		<tbody height="150px">
		</tbody>
	</table>
</div>
</center>