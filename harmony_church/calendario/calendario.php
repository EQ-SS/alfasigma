
<?php

function pintaBullets($fechaB, $idUsuario, $conn, $ids, $repre, $repreNombres){
	if($idUsuario == ''){
		$idUsuario = $ids;
	}
	
	/*$queryPlanes = "select VISPERSPLAN_SNR
		from VISPERSPLAN
		where PLAN_DATE = '".$fechaB."'
		and USER_SNR in ('".$idUsuario."')
		union
		select VISINSTPLAN_SNR
		from VISINSTPLAN
		where PLAN_DATE = '".$fechaB."'
		and USER_SNR in ('".$idUsuario."') ";
		//echo $queryPlanes;*/
	$queryPlanes = "select vp.VISPERSPLAN_SNR 
		from VISPERSPLAN vp, person p
		where vp.PLAN_DATE = '".$fechaB."' 
		and vp.USER_SNR in ('".$idUsuario."') 
		and vp.REC_STAT = 0
		and vp.PERS_SNR = p.PERS_SNR
		and p.REC_STAT = 0
		union 
		select vp.VISINSTplan_SNR 
		from VISINSTPLAN vp, inst i
		where vp.PLAN_DATE = '".$fechaB."' 
		and vp.USER_SNR in ('".$idUsuario."')
		and vp.REC_STAT = 0
		and vp.INST_SNR = i.INST_SNR
		and i.REC_STAT = 0 ";

		
	//echo $query;
	$rsPlanes = sqlsrv_query($conn, $queryPlanes, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	//$rsPlanes = sqlsrv_query($conn, $queryPlanes);
	$totalPlanes = sqlsrv_num_rows($rsPlanes);

	$queryVis = "select VISPERS_SNR 
		from VISITPERS
		where VISIT_DATE = '".$fechaB."'
		and user_snr in ('".$idUsuario."')
		union
		select VISINST_SNR
		from VISITINST
		where VISIT_DATE = '".$fechaB."'
		and user_snr in ('".$idUsuario."') ";
		//echo $queryVis;
	$rsVis = sqlsrv_query($conn, $queryVis, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	$totalVis = sqlsrv_num_rows($rsVis);
	
	$queryOA = "select * 
		from DAY_REPORT dr, DAY_REPORT_CODE drc
		where dr.rec_stat = 0 
		and drc.REC_STAT = 0
		and dr.DAYREPORT_SNR = drc.DAYREPORT_SNR
		and USER_SNR in ('".$idUsuario."') 
		and date = '".$fechaB."' ";
	$rsOA = sqlsrv_query($conn, $queryOA, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	$totalOA = sqlsrv_num_rows($rsOA);

	if(!$rsPlanes){
		//echo 'Sin planes';
	}else{
		if(sqlsrv_num_rows($rsPlanes)){
			$planVisita = "plan";
			//$arrPlanesHoy = planesVisitasCalendarioE($fechaB, $planVisita, $conn, $idUsuario, $repre);
			//echo '<div class="bg-pink tipo-evento2">'.$arrPlanesHoy[1].' Planes</div>';
			echo '<div class="bg-pink tipo-evento2">'.$totalPlanes.' <span class="spanPlanes">Planes</span></div>';
		}
		if(sqlsrv_num_rows($rsVis)){
			echo '<div class="bg-light-green tipo-evento2">'.$totalVis.' <span class="spanVisitas">Visitas</span></div>';
		}
		if(sqlsrv_num_rows($rsOA)){
			echo '<div class="bg-light-blue tipo-evento2">'.$totalOA.' <span class="spanTFT">TFT</span></div>';
			//echo sqlsrv_num_rows($rsOA);
		}

	}
}

function ultimoDia($mes,$ano){
    $ultimo_dia=28;
    while (checkdate($mes,$ultimo_dia + 1,$ano)){
       $ultimo_dia++;
    }
    return $ultimo_dia;
} 

function dame_nombre_mes($mes){
	switch ($mes){
		case 1:
		 $nombre_mes="Enero";
		 break;
		case 2:
		 $nombre_mes="Febrero";
		 break;
		case 3:
		 $nombre_mes="Marzo";
		 break;
		case 4:
		 $nombre_mes="Abril";
		 break;
		case 5:
		 $nombre_mes="Mayo";
		 break;
		case 6:
		 $nombre_mes="Junio";
		 break;
		case 7:
		 $nombre_mes="Julio";
		 break;
		case 8:
		 $nombre_mes="Agosto";
		 break;
		case 9:
		 $nombre_mes="Septiembre";
		 break;
		case 10:
		 $nombre_mes="Octubre";
		 break;
		case 11:
		 $nombre_mes="Noviembre";
		 break;
		case 12:
		 $nombre_mes="Diciembre";
		 break;
 }
 return $nombre_mes;
}

function calendar_html($tipo, $conn, $idUsuario, $ids, $fecha){
	$meses= array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
	//$fecha_fin=date('d-m-Y',time());
	if($fecha == ''){
		$mes=date('m',time());
		$anio=date('Y',time());
		$dia = date('d',time());
	}else{
		$mes = substr($fecha, 5, 2);
		$anio = substr($fecha, 0, 4);
		$dia = substr($fecha, 8, 2);
	}
	//$mes=date('m',time());
	//$anio=date('Y',time());
	?>
	<!--<table style="width:200px;text-align:center;border:1px solid #808080;border-bottom:0px;" cellpadding="0" cellspacing="0">-->

	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pull-center">
		<div class="display-flex">
			<p role="button" onclick="updatePreviousCalendar();" class="margin-0 p-r-10 p-t-3 pointer">
				<i class="material-icons font-25 next-month">chevron_left</i>
			</p>
	  
			<select id="calendar_mes" onchange="update_calendar()" class="slt-calendar">
			<?php
			$mes_numero=1;
			while($mes_numero<=12){
				if($mes_numero==$mes){
					echo "<option value=".$mes_numero." selected=\"selected\">".$meses[$mes_numero]."</option> \n";
				}else{
					echo "<option value=".$mes_numero.">".$meses[$mes_numero]."</option> \n";
				}
				$mes_numero++;
			}
			?>
			</select>

			<select class="slt-calendar m-l-10" id="calendar_anio" onchange="update_calendar()">
			<?php
			// a�os a mostrar
			$anio_min=$anio-7; //hace 30 a�os
			$anio_max=$anio; //a�o actual
			while($anio_min<=$anio_max){
				if(date("Y") == $anio_min){
					echo "<option value=".$anio_min." selected>".$anio_min."</option> \n";
				}else{
					echo "<option value=".$anio_min.">".$anio_min."</option> \n";
				}
				$anio_min++;
			}
			?>
			</select>
			<p role="button" onclick="updateNextCalendar();" class="margin-0 p-l-10 pointer p-t-3">
				<i class="material-icons font-25 next-month">chevron_right</i>
			</p>
		</div>
	</div>

	<div id="calendario_dias">
	<?php calendar($mes,$anio, $tipo, $conn, $idUsuario, $ids, $dia); ?>
	</div>
	<?php
}

function calendar($mes,$anio,$tipo, $conn, $idUsuario, $ids, $diaSeleccionado){
	//echo "<script>alert('idUsuario: ".$idUsuario."');</script>";
	//echo "<script>alert('ids: ".$ids."');</script>";

	if(isset($repre2) && $repre2 != ''){
		$repre = str_replace(",","','",substr($repre2, 0, -1));
	}else{
		$repre = $ids;
	}

	//echo "<script>alert('repre: ".$repre."');</script>";
	
	if(isset($repreNombres2) && $repreNombres2 != ''){
		$repreNombres = $repreNombres2;
	}else{
		$repreNombres = "Seleccione";
	}
	//tomo el nombre del mes que hay que imprimir
	$nombre_mes = dame_nombre_mes($mes);
	
	
	$mes_anterior = $mes - 1;
	$ano_anterior = $anio;
	if ($mes_anterior==0){
		$ano_anterior--;
		$mes_anterior=12;
	}
	
	
	echo'<p style="display:none;" class="margin-0 font-20" style="width: 155px; text-align: center;">
				' . $nombre_mes . " " . $anio . '
			</p>';

	$mes_siguiente = $mes + 1;
	$ano_siguiente = $anio;
	if ($mes_siguiente==13){
		$ano_siguiente++;
		$mes_siguiente=1;
	}			
	
	
	$dia=7;
	if(strlen($mes)==1) $mes='0'.$mes;
?>
	<!--<table style="width:200px;text-align:center;border:1px solid #808080;border-top:0px;" cellpadding="0" cellspacing="0">-->
<?php
	if($tipo == 1){
?>
		<table class="calendar-tbl calendar-month" border="0" cellpadding="0" cellspacing="0">
<?php
	}else{
?>
		<table class="calendar-tbl calendar-month" border="0" cellpadding="0" cellspacing="0">
<?php
	}
?>
		<thead><tr class="align-center font-14 col-grey calendar-day-head" style="height:20px;">
<?php
	if($tipo == 1){
?>
	  <td class="dia-md-screen">Lunes</td>
	  <td class="dia-md-screen">Martes</td>
	  <td class="dia-md-screen">Mi&eacute;rcoles</td>
	  <td class="dia-md-screen">Jueves</td>
	  <td class="dia-md-screen">Viernes</td>
	  <td class="dia-md-screen">S&aacute;bado</td>
	  <td class="dia-md-screen">Domingo</td>

	  <td class="dia-xs-screen">Lun</td>
	  <td class="dia-xs-screen">Mar</td>
	  <td class="dia-xs-screen">Mi&eacute;</td>
	  <td class="dia-xs-screen">Jue</td>
	  <td class="dia-xs-screen">Vie</td>
	  <td class="dia-xs-screen">S&aacute;b</td>
	  <td class="dia-xs-screen">Dom</td>
<?php
	}else{
?>
	  <td>Lun</td>
	  <td>Mar</td>
	  <td>Mi&eacute;</td>
	  <td>Jue</td>
	  <td>Vie</td>
	  <td>S&aacute;b</td>
	  <td>Dom</td>
<?php
	}
?>
	 </tr></thead>
	<?php

	
	//echo $mes.$dia.$anio."ssssssss";
	$numero_primer_dia = date('w', mktime(0,0,0,$mes,$dia,$anio));
	$ultimo_dia=ultimoDia($mes,$anio);
	
	$total_dias=$numero_primer_dia+$ultimo_dia;

	$diames=1;
	//$j dias totales (dias que empieza a contarse el 1� + los dias del mes)
	$j=1;
	while($j<$total_dias){
		echo "<tr style='height:auto; vertical-align:baseline;'> \n";
		//$i contador dias por semana
		$i=0;
		while($i<7){
			if($j<=$numero_primer_dia){
				echo " <td style=\"border:#E6EDF5 1px solid;\"></td> \n";
			}elseif($diames>$ultimo_dia){
				echo " <td style=\"border:#E6EDF5 1px solid;\"></td> \n";
			}else{
				if($diames<10)
					$diames_con_cero='0'.$diames;
				else 
					$diames_con_cero=$diames;
?>
				<td style="border:#E6EDF5 1px solid;" id="day<?= $diames_con_cero ?>" class="align-right" onClick="changeCalendar();">
					<a class="calendar-day" onClick="set_date('<?= $anio."-".$mes."-".$diames_con_cero ?>');">
					<div style="height: 88px;">
<?php
						$fechaHoy= date('Y-m-d');
						$fechaB = $anio."-".$mes."-".$diames_con_cero;

						if($fechaB == $fechaHoy){
							echo "<script>
									$('#day".$diames_con_cero."').addClass('selected-day');
								</script>";
						}else{
							echo "<span style='color:#3e3e3e;'>";
						}
						echo $diames."</span>";

						pintaBullets($fechaB, $idUsuario, $conn, $ids, $repre, $repreNombres);
						
						//echo "</div>";
?>
						</div>
					</a>
				</td>
<?php
				//echo " <td  height=\"20px\"  style=\"border:#E6EDF5 1px solid;\" id=\"day".$diames_con_cero."\" align=\"right\"><a style=\"display:block;cursor:pointer;\" onclick=\"set_date('".$anio."-".$mes."-".$diames_con_cero."')\">".$diames."</a></td> \n";
				//echo " <td  height=\"20px\"  style=\"border:#E6EDF5 1px solid;\" id=\"day".$diames_con_cero."\" align=\"right\"><a style=\"display:block;cursor:pointer;\" >".$diames."</a></td> \n";
				$diames++;
			}
			$i++;
			$j++;
		}
		echo "</tr> \n";
		echo "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
		
	}
	echo '<script>$("#calendario").waitMe("hide");</script>';
	?>
	</table>
	<?php
}
?>