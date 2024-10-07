<?php
include "../conexion.php";
function generar_calendario_ruteo($month,$year,$lang,$day,$holidays = null){
	
	$arrMeses = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
	
	$calendar = '<select id="sltMesCal" onchange="cambiaMesRuteo();">';
	for($i=1;$i<13;$i++){
		if($month == $i)
			$calendar .= '<option value="'.$i.'" selected>'.$arrMeses[$i].'</option>';
		else
			$calendar .= '<option value="'.$i.'">'.$arrMeses[$i].'</option>';
	}

	$calendar .= '</select>&nbsp;&nbsp;';
	
	$calendar .= '<select id="sltYearCal" onchange="cambiaMesRuteo();">';
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
	$running_day_c = date('D',mktime(0,0,0,$month,1,$year));
	
	//echo $running_day."<br>";
    $running_day = ($running_day > 0) ? $running_day-1 : $running_day; 
    $days_in_month = date('t',mktime(0,0,0,$month,1,$year));
    $days_in_this_week = 1;
    $day_counter = 0;
    $dates_array = array();
 
    $calendar.= '<tr class="calendar-row">';
	//echo $running_day."<br><br>";
	if($running_day_c == 'Sun' && $running_day == 0){
		$running_day = 6;
	}
	//echo $running_day."<br><br>";
    for($x = 0; $x < $running_day; $x++):
		//echo "hi<br>";
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
		//echo $list_day."<br>";
       
		if($day == $list_day){
			$calendar.= "<div class='{$class}' onclick='traePlanVisitasRuteo(".$list_day.",".$month.",".$year.");' style='color:blue;'>".$list_day."</div>";
		}else{
			$calendar.= "<div class='{$class}' onclick='traePlanVisitasRuteo(".$list_day.",".$month.",".$year.");'>".$list_day."</div>";
		}
	
        //$calendar.= "<div class='{$class}' onclick='fecha(".$list_day.",".$month.",".$year.");'>".$list_day."</div>";
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

	if(! $conn){
		echo "<script>alert('No se puede conectar con el servidor intente mas tarde!!!');</script>";
	}else{
		if(isset($_POST['year']) && $_POST['year'] !=''){
			$year = $_POST['year'];
		}else{
			$year = date("Y");
		}
		if(isset($_POST['month']) && $_POST['month'] != ''){
			$month = $_POST['month'];
		}else{
			$month = date("m");
		}
		if(isset($_POST['day']) && $_POST['day'] != ''){
			$day = $_POST['day'];
		}else{
			$day = date("d");
		}
		
		//echo ":::::".$year."<br>".$month."<br>";
		
		$cal = generar_calendario_ruteo($month,$year,'es',$day);
		
		echo "<script>$('#hdnFechaRuteo').val('".$year."-".$month."-".$day."');</script>";
		echo $cal;
	}
?>