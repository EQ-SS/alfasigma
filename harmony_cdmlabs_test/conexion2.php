<?php
	$serverName = "216.152.129.67"; //serverName\instanceName
	$connectionInfo = array( "Database"=>"ALFASIGMA_IOS_TEST", "UID"=>"sa", "PWD"=>"saf");
	$conn = sqlsrv_connect( $serverName, $connectionInfo);

	if( $conn ) {
		//echo "Conexión establecida.<br />";
	}else{
		echo "Conexión no se pudo establecer.<br />";
		die( print_r( sqlsrv_errors(), true));
	}
	
	function llenaCombo($conn, $tabla, $columna){
		$query = "select codelist.CLIST_SNR id,codelist.name nombre,codelist.sort_num orden ";
		$query .= "from codelist,codelistlib where codelist.clib_snr=codelistlib.clib_snr and codelist.status=1 ";
		$query .= "and codelistlib.table_nr=".$tabla." and codelistlib.clib_type=".$columna." and CODELIST.REC_STAT = 0 order by orden, nombre";
		$rs = sqlsrv_query($conn, $query);
		/*if($tabla == 34){
			echo $query."<br>";
		}*/
		return $rs;
	}
	
	function llenaComboInst($conn, $tabla, $columna, $cond){
		$query = "select codelist.CLIST_SNR id,codelist.name nombre,codelist.sort_num orden ";
		$query .= "from codelist,codelistlib where codelist.clib_snr=codelistlib.clib_snr and codelist.status=1 ";
		$query .= "and codelistlib.table_nr=".$tabla." and codelistlib.clib_type=".$columna." and CODELIST.REC_STAT = 0 ";
		$query .= "and clcat_snr = '".$cond."' ";
		$query .= "order by orden, nombre";
		$rs = sqlsrv_query($conn, $query);
		//echo $query;
		/*if($tabla == 61){
			//echo $query."<br>";
		}*/
		return $rs;		
	}
	
		function llenaComboBajas($conn, $tabla, $columna){
		$query = "select codelist.CLIST_SNR id,codelist.name nombre,codelist.sort_num orden ";
		$query .= "from codelist,codelistlib where codelist.clib_snr=codelistlib.clib_snr and codelist.status=1 ";
		$query .= "and codelistlib.table_nr=".$tabla." and codelistlib.clib_type=".$columna." and CODELIST.REC_STAT = 0 ";
		$query .= "and clcat_snr<> '00000000-0000-0000-0000-000000000000' ";
		$query .= "order by orden, nombre";
		$rs = sqlsrv_query($conn, $query);
		return $rs;
		
	}

	
	function planesVisitasCalendarioE($fecha, $planVisita, $conn, $idUsuario, $repre){
		if($planVisita == "plan"){
			$query = "select vp.vispersplan_snr as vp_id, 
				'P ' + vp.time + ' ' + p.lname + ' ' + p.mothers_lname + ' ' + p.fname as nombre,
				vp.time as hora,
				esp.name as esp,
				vp.vispers_snr as idVisita
				from vispersplan vp, person p 
				left outer join codelist esp on esp.clist_snr=p.spec_snr 
				where vp.rec_stat=0 
				and vp.pers_snr=p.pers_snr ";
			if($idUsuario != ''){
				$query .= "and vp.user_snr = '".$idUsuario."' ";
			}else{
				$query .= "and vp.user_snr in ('".$repre."') ";
			}
				$query .= "and vp.plan_date='".$fecha."' 
				and p.rec_stat = 0 
				union 
				select vp.visinstplan_snr as vp_id,
				'I '+ vp.time + ' ' + i.name as nombre,
				vp.time as hora,
				'' as esp,
				vp.visinst_snr as idVisita
				from visinstplan vp, inst i 
				where vp.rec_stat=0 
				and vp.inst_snr=i.inst_snr 
				and vp.plan_date='".$fecha."' ";
			if($idUsuario != ''){
				$query .= "and vp.user_snr = '".$idUsuario."' ";
			}else{
				$query .= "and vp.user_snr in ('".$repre."') "; 
			}
				$query .= "and i.rec_stat = 0
				union
				select vp.DAYPLAN_SNR as vp_id, 
				'O ' + cast(DAYCODE_WEIGHT as varchar(10)) + ' ' + act.NAME as nombre, 
				cast(DAYCODE_WEIGHT as varchar(10))  as hora, 
				'' as esp, 
				'00000000-0000-0000-0000-000000000000' as idVisita
				from VISDAYPLAN vp
				left outer join VISDAYPLAN_CODE dc on vp.DAYPLAN_SNR = dc.VISDAYPLAN_SNR
				left outer join codelist act on act.CLIST_SNR = dc.DAY_CODE
				where vp.REC_STAT = 0 
				and dc.REC_STAT = 0";
			if($idUsuario != ''){
				$query .= "and vp.user_snr = '".$idUsuario."' ";
			}else{
				$query .= "and vp.user_snr in ('".$repre."') ";
			}
				$query .= "and vp.date = '".$fecha."' 
				order by hora, nombre,esp";
			//echo $query;
		}else if($planVisita == "visita"){
			$query = "select vp.vispers_snr vp_id,
				'P ' + vp.time + ' ' + p.lname + ' ' + p.mothers_lname + ' ' + p.fname as nombre,
				esp.name as esp,
				vp.time as hora,
				vp.vispersplan_snr as idPlan
				from visitpers vp, person p 
				left outer join codelist esp on esp.clist_snr=p.spec_snr 
				where vp.rec_stat=0 
				and vp.pers_snr=p.pers_snr 
				and vp.visit_date='".$fecha."'";
			if($idUsuario != ''){
				$query .= "and vp.user_snr = '".$idUsuario."' ";
			}else{
				$query .= "and vp.user_snr in ('".$repre."') ";
			}
				$query .= "and p.fname <> 'AJUSTE' 
				and p.rec_stat = 0
				union 
				select vp.visinst_snr vp_id,
				'I ' + vp.time + ' ' + i.name as nombre,
				'' as esp,
				vp.time as hora,
				vp.visinstplan_snr as idPlan
				from visitinst vp, inst i 
				where vp.rec_stat=0 and vp.inst_snr=i.inst_snr 
				and vp.visit_date='".$fecha."' ";
			if($idUsuario != ''){
				$query .= "and vp.user_snr = '".$idUsuario."' ";
			}else{
				$query .= "and vp.user_snr in ('".$repre."') ";
			}
			$query .= "and i.rec_stat = 0 
				union
				select vp.DAYREPORT_SNR as vp_id, 
				'O ' + cast(VALUE as varchar(10)) + ' ' + act.NAME as nombre, 
				'' as esp,
				cast(VALUE as varchar(10))  as hora, 
				'00000000-0000-0000-0000-000000000000' as idVisita
				from DAY_REPORT vp
				left outer join DAY_REPORT_CODE dc on vp.DAYREPORT_SNR = dc.DAYREPORT_SNR
				left outer join codelist act on act.CLIST_SNR = dc.DAY_CODE_SNR
				where vp.REC_STAT = 0 
				and dc.REC_STAT = 0";
			if($idUsuario != ''){
				$query .= "and vp.user_snr = '".$idUsuario."' ";
			}else{
				$query .= "and vp.user_snr in ('".$repre."') ";
			}
			$query .= "and vp.DATE = '".$fecha."'  
			order by hora, nombre,esp";
		}		
		//echo $query;
		$rs = sqlsrv_query($conn, $query);
		$datos = array();
		$renglones = "";
		$totalPersonas = 0;
		$totalInst = 0;
		$totalOtras = 0;
		while($registro = sqlsrv_fetch_array($rs)){
			$nombre = utf8_encode($registro['nombre']);

			if($planVisita == 'plan'){
				if(substr($registro['nombre'], 0, 1) == "I"){
					if($registro['idVisita'] == '00000000-0000-0000-0000-000000000000' || $registro['idVisita'] == null){
						$renglones .= 'muestraPlanInst(\''.$registro['vp_id'].'\');&border-bottom: 1px solid #ddd;&<i class="fas fa-building col-pink"></i>'.substr($nombre, (strpos($nombre,'I'))+1);
						//$borrar = '<img src="/iconos/bote.png" >';
					}else{
						$renglones .= 'muestraPlanInst(\''.$registro['vp_id'].'\');&border-bottom: 1px solid #ddd;color: #04B404;&<i class="fas fa-building col-light-green"></i>'.substr($nombre, (strpos($nombre,'I'))+1);
					}
					$totalInst++;
				}else if(substr($registro['nombre'], 0, 1) == "P"){ 
					if($registro['idVisita'] == '00000000-0000-0000-0000-000000000000'){
						$renglones .= 'muestraPlan(\''.$registro['vp_id'].'\');"&border-bottom: 1px solid #ddd;&<i class="fas fa-user-md col-pink"></i>'.substr($nombre, (strpos($nombre,'P'))+1);
					}else{
						$renglones .= 'muestraPlan(\''.$registro['vp_id'].'\');&border-bottom: 1px solid #ddd;color: #04B404;&<i class="fas fa-user-md col-light-green"></i>'.substr($nombre, (strpos($nombre,'P'))+1);
					}
					$totalPersonas++;
				}
				$renglones .= "|";
			}else if($planVisita == 'visita'){
				if(substr($registro['nombre'], 0, 1) == "I"){
					if($registro['idPlan'] == '00000000-0000-0000-0000-000000000000'){
						$renglones .= 'muestraVisitaInst(\''.$registro['vp_id'].'\');&border-bottom: 1px solid #ddd;&<i class="fas fa-building col-light-green"></i>'.substr($nombre, (strpos($nombre,'I'))+1);
					}else{
						$renglones .= 'muestraVisitaInst(\''.$registro['vp_id'].'\');&border-bottom: 1px solid #ddd;color: #04B404;&<i class="fas fa-building col-light-green"></i>'.substr($nombre, (strpos($nombre,'I'))+1);
					}
					$totalInst++;
				}else if(substr($registro['nombre'], 0, 1) == "P"){
					if($registro['idPlan'] == '00000000-0000-0000-0000-000000000000'){
						$renglones .= 'muestraVisita(\''.$registro['vp_id'].'\');&border-bottom: 1px solid #ddd;&<i class="fas fa-user-md col-light-green"></i>'.substr($nombre, (strpos($nombre,'P'))+1);
					}else{
						$renglones .= 'muestraVisita(\''.$registro['vp_id'].'\');&border-bottom: 1px solid #ddd;color: #04B404;&<i class="fas fa-user-md col-light-green"></i>'.substr($nombre, (strpos($nombre,'P'))+1);
					}
					$totalPersonas++;
				}
				$renglones .= "|";
			}
			
			if(substr($registro['nombre'], 0, 1) == "O"){
				//$renglones .= '<tr onclick="muestraOtrasActividades(\''.$registro['vp_id'].'\');"><td style="border-bottom: 1px solid #b4b4b4;">'.$registro['nombre'].'</td></tr>';
				$renglones .= 'muestraOtrasActividades(\''.$registro['vp_id'].'\');&border-bottom: 1px solid #ddd;&<i class="fas fa-calendar-week col-light-blue"></i>'.substr($nombre, (strpos($nombre,'O'))+1);
				$totalOtras++;
				$renglones .= "|";
			}
			//echo "renglones: ".$renglones."<br><br>";
		}
		$datos[] = $renglones;
		$datos[] = $totalPersonas;
		$datos[] = $totalInst;
		$datos[] = $totalOtras;
		//print_r ($datos);
		//echo "<br><br>";
		return $datos;
	}
	
	
	function planesVisitasCalendario($fecha, $planVisita, $conn, $idUsuario, $repre){
		if($planVisita == "plan"){
			$query = "select vp.vispersplan_snr as vp_id, 
				'P ' + vp.time + ' ' + p.lname + ' ' + p.mothers_lname + ' ' + p.fname as nombre,
				vp.time as hora,
				esp.name as esp,
				vp.vispers_snr as idVisita
				from vispersplan vp, person p 
				left outer join codelist esp on esp.clist_snr=p.spec_snr 
				where vp.rec_stat=0 
				and vp.pers_snr=p.pers_snr ";
			if($idUsuario != ''){
				$query .= "and vp.user_snr = '".$idUsuario."' ";
			}else{
				$query .= "and vp.user_snr in ('".$repre."') ";
			}
				$query .= "and vp.plan_date='".$fecha."' 
				and p.rec_stat = 0 
				union 
				select vp.visinstplan_snr as vp_id,
				'I '+ vp.time + ' ' + i.name as nombre,
				vp.time as hora,
				'' as esp,
				vp.visinst_snr as idVisita
				from visinstplan vp, inst i 
				where vp.rec_stat=0 
				and vp.inst_snr=i.inst_snr 
				and vp.plan_date='".$fecha."' ";
			if($idUsuario != ''){
				$query .= "and vp.user_snr = '".$idUsuario."' ";
			}else{
				$query .= "and vp.user_snr in ('".$repre."') "; 
			}
				$query .= "and i.rec_stat = 0
				union
				select vp.DAYPLAN_SNR as vp_id, 
				'O ' + cast(DAYCODE_WEIGHT as varchar(10)) + ' ' + act.NAME as nombre, 
				cast(DAYCODE_WEIGHT as varchar(10))  as hora, 
				'' as esp, 
				'00000000-0000-0000-0000-000000000000' as idVisita
				from VISDAYPLAN vp
				left outer join VISDAYPLAN_CODE dc on vp.DAYPLAN_SNR = dc.VISDAYPLAN_SNR
				left outer join codelist act on act.CLIST_SNR = dc.DAY_CODE
				where vp.REC_STAT = 0 
				and dc.REC_STAT = 0";
			if($idUsuario != ''){
				$query .= "and vp.user_snr = '".$idUsuario."' ";
			}else{
				$query .= "and vp.user_snr in ('".$repre."') ";
			}
				$query .= "and vp.date = '".$fecha."'
				order by nombre,esp";
		}else if($planVisita == "visita"){
			$query = "select vp.vispers_snr vp_id,
				'P ' + vp.time + ' ' + p.lname + ' ' + p.mothers_lname + ' ' + p.fname as nombre,
				esp.name as esp,
				vp.vispersplan_snr as idPlan
				from visitpers vp, person p 
				left outer join codelist esp on esp.clist_snr=p.spec_snr 
				where vp.rec_stat=0 
				and vp.pers_snr=p.pers_snr 
				and vp.visit_date='".$fecha."'";
			if($idUsuario != ''){
				$query .= "and vp.user_snr = '".$idUsuario."' ";
			}else{
				$query .= "and vp.user_snr in ('".$repre."') ";
			}
				$query .= "and p.fname <> 'AJUSTE' 
				and p.rec_stat = 0
				union 
				select vp.visinst_snr vp_id,
				'I ' + vp.time + ' ' + i.name as nombre,
				'' as esp,
				vp.visinstplan_snr as idPlan
				from visitinst vp, inst i 
				where vp.rec_stat=0 and vp.inst_snr=i.inst_snr 
				and vp.visit_date='".$fecha."' ";
			if($idUsuario != ''){
				$query .= "and vp.user_snr = '".$idUsuario."' ";
			}else{
				$query .= "and vp.user_snr in ('".$repre."') ";
			}
			$query .= "and i.rec_stat = 0 
				union
				select vp.DAYREPORT_SNR as vp_id, 
				'O ' + cast(VALUE as varchar(10)) + ' ' + act.NAME as nombre, 
				'' as esp, 
				'00000000-0000-0000-0000-000000000000' as idVisita
				from DAY_REPORT vp
				left outer join DAY_REPORT_CODE dc on vp.DAYREPORT_SNR = dc.DAYREPORT_SNR
				left outer join codelist act on act.CLIST_SNR = dc.DAY_CODE
				where vp.REC_STAT = 0 
				and dc.REC_STAT = 0";
			if($idUsuario != ''){
				$query .= "and vp.user_snr = '".$idUsuario."' ";
			}else{
				$query .= "and vp.user_snr in ('".$repre."') ";
			}
			$query .= "and '".$fecha."' between vp.START_DATE and vp.FINISH_DATE 
			order by nombre,esp";
		}		
		//echo $query;
		$rs = sqlsrv_query($conn, $query);
		$datos = array();
		$renglones = "";
		$totalPersonas = 0;
		$totalInst = 0;
		$totalOtras = 0;
		while($registro = sqlsrv_fetch_array($rs)){
			$nombre = utf8_encode($registro['nombre']);
			//$nombre = substr($nombreT, 2);
			//$nombre = substr($nombreT, (strpos($nombreT,'P'))+1);

			if($planVisita == 'plan'){
				if(substr($registro['nombre'], 0, 1) == "I"){
					if($registro['idVisita'] == '00000000-0000-0000-0000-000000000000'){
						$renglones .= '<tr onClick="muestraPlanInst(\''.$registro['vp_id'].'\');"><td style="border-bottom: 1px solid #ddd;"><i class="fas fa-building col-pink"></i>'.substr($nombre, (strpos($nombre,'I'))+1).'</td></tr>';
					}else{
						$renglones .= '<tr onClick="muestraPlanInst(\''.$registro['vp_id'].'\');"><td style="border-bottom: 1px solid #ddd;color: #04B404;"><i class="fas fa-building col-light-green"></i>'.substr($nombre, (strpos($nombre,'I'))+1).'</td></tr>';
					}
					
					$totalInst++;
				}else if(substr($registro['nombre'], 0, 1) == "P"){
					if($registro['idVisita'] == '00000000-0000-0000-0000-000000000000'){
						$renglones .= '<tr onClick="muestraPlan(\''.$registro['vp_id'].'\');"><td style="border-bottom: 1px solid #ddd;"><i class="fas fa-user-md col-pink"></i>'.substr($nombre, (strpos($nombre,'P'))+1).'</td></tr>';
					}else{
						$renglones .= '<tr onClick="muestraPlan(\''.$registro['vp_id'].'\');"><td style="border-bottom: 1px solid #ddd;color: #04B404;"><i class="fas fa-user-md col-light-green"></i>'.substr($nombre, (strpos($nombre,'P'))+1).'</td></tr>';
					}
					$totalPersonas++;
				}
			}else if($planVisita == 'visita'){
				if(substr($registro['nombre'], 0, 1) == "I"){
					if($registro['idPlan'] == '00000000-0000-0000-0000-000000000000'){
						$renglones .= '<tr onClick="muestraVisitaInst(\''.$registro['vp_id'].'\');"><td  style="border-bottom: 1px solid #ddd;"><i class="fas fa-building col-light-green"></i>'.substr($nombre, (strpos($nombre,'I'))+1).'</td></tr>';
					}else{
						$renglones .= '<tr onClick="muestraVisitaInst(\''.$registro['vp_id'].'\');"><td  style="border-bottom: 1px solid #ddd;color: #04B404;"><i class="fas fa-building col-light-green"></i>'.substr($nombre, (strpos($nombre,'I'))+1).'</td></tr>';
					}
					$totalInst++;
				}else if(substr($registro['nombre'], 0, 1) == "P"){
					if($registro['idPlan'] == '00000000-0000-0000-0000-000000000000'){
						$renglones .= '<tr onClick="muestraVisita(\''.$registro['vp_id'].'\');"><td style="border-bottom: 1px solid #ddd;"><i class="fas fa-user-md col-light-green"></i>'.substr($nombre, (strpos($nombre,'P'))+1).'</td></tr>';
					}else{
						$renglones .= '<tr onClick="muestraVisita(\''.$registro['vp_id'].'\');"><td style="border-bottom: 1px solid #ddd;color: #04B404;"><i class="fas fa-user-md col-light-green"></i>'.substr($nombre, (strpos($nombre,'P'))+1).'</td></tr>';
					}
					$totalPersonas++;
				}
			}
			
			if(substr($registro['nombre'], 0, 1) == "O"){
				//$renglones .= '<tr onclick="muestraOtrasActividades(\''.$registro['vp_id'].'\');"><td style="border-bottom: 1px solid #b4b4b4;">'.$registro['nombre'].'</td></tr>';
				$renglones .= '<tr onclick="muestraOtrasActividades(\''.$registro['vp_id'].'\');"><td style="border-bottom: 1px solid #ddd;"><i class="fas fa-calendar-week col-light-blue"></i>'.substr($nombre, (strpos($nombre,'O'))+1).'</td></tr>';
				$totalOtras++;
			}
			
		}
		$datos[] = $renglones;
		$datos[] = $totalPersonas;
		$datos[] = $totalInst;
		$datos[] = $totalOtras;
		return $datos;
	}
	
function generar_calendario($month,$year,$lang,$day,$holidays = null){
	//echo "funcion calendario::: ";
	$arrMeses = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
	
	$calendar = '<select id="sltMesCal">';
	for($i=1;$i<13;$i++){
		if($month == $i)
			$calendar .= '<option value="'.$i.'" selected>'.$arrMeses[$i].'</option>';
		else
			$calendar .= '<option value="'.$i.'">'.$arrMeses[$i].'</option>';
	}

	$calendar .= '</select>&nbsp;&nbsp;';
	
	$calendar .= "<select id='sltYearCal'>";
	for($i=date("Y")-5;$i<=date("Y")+10;$i++){
		if($year == $i)
			$calendar .= '<option value="'.$i.'" selected>'.$i.'</option>';
		else
			$calendar .= '<option value="'.$i.'" >'.$i.'</option>';
	}
	$calendar .= "</select><br><br>";
		
	
    $calendar .= '<table cellpadding="0" cellspacing="0" class="calendar">';
 
    if($lang=='en'){
        $headings = array('M','T','W','T','F','S','S');
    }
    if($lang=='es'){
		$headings = array('L','M','M','J','V','S','D');
    }
    if($lang=='ca'){
        $headings = array('DI','Dm','Dc','Dj','Dv','Ds','Dg');
    }
    
    $calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';
 
    $running_day = date('w',mktime(0,0,0,$month,1,$year));
    $running_day = ($running_day > 0) ? $running_day-1 : $running_day; 
    $days_in_month = date('t',mktime(0,0,0,$month,1,$year));
    $days_in_this_week = 1;
    $day_counter = 0;
    $dates_array = array();
 
    $calendar.= '<tr class="calendar-row">';
 
    for($x = 0; $x < $running_day; $x++):
        $calendar.= '<td class="calendar-day-np"> </td>';
        $days_in_this_week++;
    endfor;
 
    for($list_day = 1; $list_day <= $days_in_month; $list_day++):
        $calendar.= '<td class="calendar-day">';
         
        $class="day-number ";
        if($running_day == 0 || $running_day == 6 ){
            $class.=" not-work ";
        }
         
        $key_month_day = "month_{$month}_day_{$list_day}";
 
        if($holidays != null && is_array($holidays)){
            $month_key = array_search($key_month_day, $holidays);
             
            if(is_numeric($month_key)){
                $class.=" not-work-holiday ";
            }
        }
        
		if($day == $list_day){
			$calendar.= "<div class='{$class}' onclick='traePlanVisitasRuteo(".$list_day.",".$month.",".$year.");' style='color:blue;'>".$list_day."</div>";
		}else{
			$calendar.= "<div class='{$class}' onclick='traePlanVisitasRuteo(".$list_day.",".$month.",".$year.");'>".$list_day."</div>";
		}
        
        $calendar.= '</td>';
        if($running_day == 6):
            $calendar.= '</tr>';
            if(($day_counter+1) != $days_in_month):
                $calendar.= '<tr class="calendar-row">';
            endif;
            $running_day = -1;
            $days_in_this_week = 0;
        endif;
        $days_in_this_week++; $running_day++; $day_counter++;
    endfor;
 
    if($days_in_this_week < 8):
        for($x = 1; $x <= (8 - $days_in_this_week); $x++):
            $calendar.= '<td class="calendar-day-np"> </td>';
        endfor;
    endif;
 
    $calendar.= '</tr>';
 
    $calendar.= '</table>';
     
    return $calendar;
}
?>