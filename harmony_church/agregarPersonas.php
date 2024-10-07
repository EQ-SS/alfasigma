<html>
	<head>
		<title>Personas</title>
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<!---<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>--->
		<script> 
			function seleccionado(numero, titulo, paterno, materno, nombre, especialidad, puesto){
				var fila = '';
				fila = "<tr><td>"+ numero +"</td>";
				fila += "<td>"+ titulo +"</td>";
				fila += "<td>"+ paterno +"</td>";
				fila += "<td>"+ materno +"</td>";
				fila += "<td>"+ nombre + "</td>";
				fila += "<td>"+ especialidad + "</td>";
				fila += "<td>"+ puesto + "</td><tr>";
				//window.opener.document.getElementById('deHijo').append();
				$( '#tblPersonasDepartamentoInstituciones', window.opener.document ).append(fila);
				this.close();
			}
		</script>
		<style type="text/css">
			body{
				font-family: Arial;
				font-size: 12px;
				background: #F5F5F6;
			}
			
			input, select{
				-moz-border-radius: 5px;
				-webkit-border-radius: 5px;
			}
			
			button{
				border-radius: 5px;
			}
			
			.rojo{
				color:red;
				font-weight:bold;
			}
			
			.negrita{
				font-weight: bold;
				
			}
			
			.grid{
				font-family: Arial;
				font-size: 12px;
				border-spacing: 0px 0px;
			}
			
			.grid thead, .grid tfoot{
				background:#C0C0C0;
				font-size: 14px;
				font-weight:bold;
			}
			
			.grid tbody tr:nth-child(2n){
				background: #F0F8FF;
			}
			
			.grid tbody tr:nth-child(2n+1){
				background: #B0E0E6;
			}
			
			.grid tbody tr:hover{
				background:#1E90FF;
				color:#FFFFFF;
			}
			
			.grid tbody td{
				padding: 0px 10px 0px 0px;
			}
		</style>
	</head>
	<body>
		<table style="font-family:Arial; font-size:12px">
			<tr>
				<td class="negrita">Apellido Paterno: <input type="text" size="20"/></td>
				<td class="negrita">Apellido Materno: <input type="text" size="20"/></td>
				<td class="negrita">Nombre: <input type="text" size="20"/></td>
			</tr>
		</table>
		<hr>
		<table width="100%" class="grid">
				<thead>
					<tr>
						<td>Apellido Paterno</td>
						<td>Nombre</td>
						<td>Esp Primaria</td>
						<td>Categ...</td>
						<td>Instituci&oacute;n</td>
						<td>Calle</td>
						<td>Colonia</td>
						<td>Del./Mun.</td>
						<td>Estado</td>
						<td>Brick</td>
					</tr>
				</thead>
				<tbody>
<?php
					for($i=1;$i<16;$i++){
						$numero = rand(1, 100);
						$titulo = "MEDICINA GENERAL";
						$paterno = "LÓPEZ";
						$materno = "MARTÍNEZ";
						$nombre = "MARIA CONCEPCIÓN";
						$especialidad = "CARDIÓLOGO";
						$puesto = "GERENCIA";
						echo "<tr onclick='seleccionado(".$numero.",\"".$titulo."\",\"".$paterno."\",\"".$materno."\",\"".$nombre."\",\"".$especialidad."\",\"".$puesto."\");'>
						<td>
							Apellido Paterno
						</td>
						<td>
							Nombre
						</td>
						<td>
							Esp Primaria
						</td>
						<td>
							SC
						</td>
						<td>
							Instituci&oacute;n
						</td>
						<td>
							Calle
						</td>
						<td>
							Colonia
						</td>
						<td>
							Del./Mun.
						</td>
						<td>
							Estado
						</td>
						<td>
							00000001
						</td>
					</tr>
						";
					}
?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="14" align="center">
							1
							<a href="#">2</a>
							<a href="#">3</a>
							<a href="#">4</a>
							<a href="#">5</a>
							<a href="#">6</a>
							<a href="#">7</a>
							<a href="#">8</a>
							<a href="#">9</a>
							<a href="#">10</a>
							<a href="#">11</a>
							<a href="#">12</a>
							<a href="#">13</a>
							<a href="#">14</a>
							<a href="#">&gt;</a>
							<a href="#">&gt;&gt;</a>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							Pag. 1 de 14&nbsp;&nbsp;&nbsp; Registros 200
						</td>
					</tr>
				</tfoot>
			</table>
	</body>
</html>