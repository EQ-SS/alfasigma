<?php
	include "../conexion.php";
	$buscar = array(chr(13).chr(10), "\r\n", "\n", "\r");
	$reemplazar = array(" ", " ", " ", " ");
	$reemplazar1 = array("", "", "", "");
	$reemplazar2 = array("<br>", "<br>", "<br>", "<br>");
	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		$tipoUsuario = $_POST['tipoUsuario'];
		echo "<script>
				$('#tblEventos tbody').empty();";

		$queryTabla = " select e.EVENT_SNR, 
			tipo.name as tipo,
			e.PLACE, 
			e.name, 
			cast(year(e.START_DATE) as varchar) + '-' + format(month(e.start_date), '00') + '-' + format(day(e.start_date), '00') as start_date, 
			cast(year(e.finish_DATE) as varchar) + '-' + format(month(e.finish_date), '00') + '-' + format(day(e.FINISH_DATE), '00') as finish_date, 
			e.INFO from EVENT e 
			left outer join CODELIST tipo on tipo.CLIST_SNR = TYPE_SNR 
			where e.REC_STAT = 0 
			and e.EVENT_SNR <> '00000000-0000-0000-0000-000000000000' 
			order by e.start_date desc";
		//echo $queryTabla."<br>";
		$rsTabla = sqlsrv_query($conn, $queryTabla, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		$trEventos = 0;
		while($registro = sqlsrv_fetch_array($rsTabla)){
			$id = $registro['EVENT_SNR'];
			$tipo = str_replace("'", "\'", $registro['tipo']);
			$nombre = str_replace("'", "\'", $registro['name']);	
			$lugar = str_replace("'", "\'", $registro['PLACE']);					
			$fecIni = $registro['start_date'];										
			$fecFin = $registro['finish_date'];										
			$info = str_ireplace($buscar, $reemplazar2, $registro['INFO']);

			if($tipoUsuario != 4){
				echo "$('#tblEventos tbody').append('<tr id=\"trEventos".$trEventos."\" class=\"align-center\"><td style=\"width:25%;\" onClick=\"traeAsistentesEventos(\'".$id."\',\'trEventos".$trEventos."\');\">".$tipo."</td><td style=\"width:25%;\" onClick=\"traeAsistentesEventos(\'".$id."\',\'trEventos".$trEventos."\');\">".$nombre."</td><td style=\"width:25%;\" onClick=\"traeAsistentesEventos(\'".$id."\',\'trEventos".$trEventos."\');\">".$lugar."</td><td style=\"width:15%;\" onClick=\"traeAsistentesEventos(\'".$id."\',\'trEventos".$trEventos."\');\">".$fecIni."</td><td style=\"width:15%;\" onClick=\"traeAsistentesEventos(\'".$id."\',\'trEventos".$trEventos."\');\">".$fecFin."</td><td style=\"width:20%;\" onClick=\"traeAsistentesEventos(\'".$id."\',\'trEventos".$trEventos."\');\">".$info ."</td><td style=\"width:5%;\"><button type=\"button\" class=\"btn bg-indigo btn bg-indigo waves-effect btn-indigo little-button\" onClick=\"editarEvento(\'".$id."\',\'trEventos".$trEventos."\');\"><i class=\"material-icons pointer\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"Editar\">edit</i></button></td><td style=\"width:5%;\"><button type=\"button\" class=\"btn bg-indigo btn bg-indigo waves-effect btn-indigo little-button\" onClick=\"eliminarEvento(\'".$id."\',\'trEventos".$trEventos."\');\"><i class=\"material-icons pointer\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"Eliminar\">delete</i></button></td></tr>');\r\n";
			}else{
				echo "$('#tblEventos tbody').append('<tr id=\"trEventos".$trEventos."\" class=\"align-center\"><td style=\"width:25%;\" onClick=\"traeAsistentesEventos(\'".$id."\',\'trEventos".$trEventos."\');\">".$tipo."</td><td style=\"width:25%;\" onClick=\"traeAsistentesEventos(\'".$id."\',\'trEventos".$trEventos."\');\">".$nombre."</td><td style=\"width:25%;\" onClick=\"traeAsistentesEventos(\'".$id."\',\'trEventos".$trEventos."\');\">".$lugar."</td><td style=\"width:15%;\" onClick=\"traeAsistentesEventos(\'".$id."\',\'trEventos".$trEventos."\');\">".$fecIni."</td><td style=\"width:15%;\" onClick=\"traeAsistentesEventos(\'".$id."\',\'trEventos".$trEventos."\');\">".$fecFin."</td><td style=\"width:20%;\" onClick=\"traeAsistentesEventos(\'".$id."\',\'trEventos".$trEventos."\');\">".$info ."</td><td style=\"width:5%;\"><button type=\"button\" class=\"btn bg-indigo btn bg-indigo waves-effect btn-indigo little-button\" onClick=\"editarEvento(\'".$id."\',\'trEventos".$trEventos."\');\"><i class=\"material-icons pointer\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"Editar\">edit</i></button></td></tr>');\r\n";
			}
		
			
			$trEventos++;
		}
		echo "</script>";
	}
?>