<html>
	<head>
		<style type="text/css">
			body{
				font-family: Arial;
				font-size: 12px;
				background: #68B0AB;
			}
			
			button{
				border-radius: 5px;
			}
			
			table{
				font-family: Arial;
				font-size: 12px;
				box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
				box-shadow: 0 0 20px rgba(0,0,0,0.8);
			}
			
			.tituloModulo{
				font-family: Arial;
				color: grey;
				font-size:20px;
				font-weight: bold;
				text-decoration: underline;
			}
			
			.negrita{
				font-weight: bold;
				
			}
			
		</style>
	</head>
	<body>
		<table width="100%" bgcolor="#FFFFFF" border="0">
			<tr>
				<td class="tituloModulo">Lista de Usuarios</td>
			</tr>
			<tr>
				<td><hr></td>
			</tr>
			<tr>
				<td>
					<button id="btnGuardar" type="button">
						<img src="iconos/ok.png" width="15px"> Guardar
					</button>
					<button name="btnCancelar" type="button">
						<img src="iconos/tache.png" width="15px"> Cancelar					 
					</button>
				</td>
			</tr>
			<tr>
				<td class="negrita">
					Linea:<br>
					<select>
						<option value="0">Seleccione</option>
						<option value="1">Alfa</option>
						<option value="2">Omega</option>
						<option value="3">Infinito</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="negrita">
					Región:<br>
					<select>
						<option value="0">Seleccione</option>
						<option value="1">México</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="negrita">
					Tipo de Usuario:<br>
					<select>
						<option value="0">Seleccione</option>
						<option value="1">Administrador</option>
						<option value="2">Gerente de Distrito</option>
						<option value="3">Gerente Nacional</option>
						<option value="4">Gerente Regional</option>
						<option value="5">Representante</option>
					</select>
				</td>
			</tr>
			<tr>
				<td valign="top" >
					<div style="overflow: auto;height:200px;">
					</div>
				</td>
			</tr>
		</table>
	</body>
</html>