<!doctype html>
<html lang="en">
	<head>
		<link href="css/estiloSmart.css" rel="stylesheet">
		<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
		<script>
			$(document).ready(function(){
				$("#chkTodo").change(function () {
					$("input:checkbox").prop('checked', $(this).prop("checked"));
				});
			});
		</script>
		<style type="text/css">
			#tblProductos{
				background: #FFFFFF;
				box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
				box-shadow: 0 0 20px rgba(0,0,0,0.8);
				border-radius: 5px;
			}
		</style>
	</head>
	<body>
		<table width="100%" id="tblProductos">
			<tr>
				<td class="tituloModulo">Visualizar Lista</td>
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
				<td>
					<br>
					Buscar:
					<input type="text" size="50">
				</td>
			</tr>
			<tr>
				<td>
					<br>
					<input type="checkbox" id="chkTodo"><label for="chkTodo" >Marcar/Desmarcar todo<label><br>
					<div style="width:300px; height:300px;overflow:auto;">
						<table>
							<tr><td><input type="checkbox">CERTEZZA</td></tr>
							<tr><td><input type="checkbox">DOLO-TIAMINAL</td></tr>
							<tr><td><input type="checkbox">DORSAL</td></tr>
							<tr><td><input type="checkbox">FOLIVITAL</td></tr>
							<tr><td><input type="checkbox">GLIMETAL</td></tr>
							<tr><td><input type="checkbox">GLIMETAL LEX</td></tr>
							<tr><td><input type="checkbox">GLINORBORAL COMP</td></tr>
							<tr><td><input type="checkbox">GRANULOX</td></tr>
							<tr><td><input type="checkbox">LIMAGAL</td></tr>
							<tr><td><input type="checkbox">MALIVAL AP</td></tr>
							<tr><td><input type="checkbox">MALIVAL COMPUESTO</td></tr>
							<tr><td><input type="checkbox">PREDIAL</td></tr>
							
						</table>
					</div>
				</td>
			</tr>
		<table>
	</body>
</html>