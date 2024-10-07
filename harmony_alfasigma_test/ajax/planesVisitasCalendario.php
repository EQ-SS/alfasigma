<?php
	include "conexion.php";
	$dia = $_POST['dia'];
	$fecha = $_POST['fecha'];
	$idUsuario = $_POST['idUsuario'];
	
	$query = "select vp.vpersplan_snr as vp_id, 
		'P ' + vp.vrijeme + ' ' + p.lname + ' ' + p.name_father + ' ' + p.fname as nombre,
		vp.vrijeme as hora,
		esp.name as esp 
		from vispersplan vp, person p 
		left outer join codelist esp on esp.clist_snr=p.spec_snr 
		where vp.rec_stat=0 
		and vp.pers_snr=p.pers_snr 
		and vp.user_snr = '".$idUsuario."' 
		and vp.datum='".$fecha."' 
		union 
		select vp.visinstplan_snr as vp_id,
		'I '+ vp.vrijeme + ' ' + i.name as nombre,
		vp.vrijeme as hora,
		'' as esp 
		from visinstplan vp, inst i 
		where vp.rec_stat=0 
		and vp.inst_snr=i.inst_snr 
		and vp.datum='".$fecha."' 
		and vp.user_snr = '".$idUsuario."' 
		order by hora,nombre,esp ";
	
	echo $query;
	
	$rs = sqlsrv_query($conn, $query);
	
	echo "<script>
			$('#tbl".$dia."').empty();
			$('#lblTotalPersonas".$dia."').text('0');
			$('#lblTotalInst".$dia."').text('0');";
	$totalPersonas = 0;
	$totalInst = 0;
	
	while($registro = sqlsrv_fetch_array($rs)){
		$nombre = $registro['nombre'];
		if(substr($registro['nombre'], 0, 1) == "I"){
			echo "$('#tbl".$dia."').append('<tr onClick=\"muestraPlanInst(\'".$registro['vp_id']."\');\"><td><i class=\"fas fa-building col-pink\"></i>".substr($nombre, (strpos($nombre,'I'))+1)."</td></tr>');";
			$totalPersonas++;
		}else if(substr($registro['nombre'], 0, 1) == "P"){
			echo "$('#tbl".$dia."').append('<tr onClick=\"muestraPlan(\'".$registro['vp_id']."\');\"><td><i class=\"fas fa-user-md col-pink\"></i>".substr($nombre, (strpos($nombre,'P'))+1)."</td></tr>');";
			$totalInst++;
		}
	}
	echo "$('#lblTotalPersonas".$dia."').text('".$totalPersonas."');
			$('#lblTotalInst".$dia."').text('".$totalInst."');
		</script>";
	
?>