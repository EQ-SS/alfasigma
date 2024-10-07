<?php

	include "../conexion.php";
	
	function visitaUnica($conn, $ciclo, $ids, $tipoUsuario){
		$query = "with vis_unique as (select cycles.name cycle, count(*) total, case when count(*)>0 then 1 else 0 end vis_unique
        from visitpers v
        inner join person p on p.pers_snr=v.pers_snr  ";
		if($tipoUsuario != 4){
			$query .= "and p.spec_snr <> '4A7DC158-020D-4D08-9AC1-681B32BDB6F6' ";
		}
		$query .= "
        inner join cycles on  v.visit_date between start_date and finish_date
        where v.rec_stat=0
        and substring(cycles.name,1,7)='".$ciclo."'
		and v.user_snr in ('".$ids."')
        group by cycles.name,v.pers_snr)
        select
        cycles.name,
        isnull(sum(vis_unique),0) vis
        from cycles
        left outer join vis_unique on cycles.name=cycle
        where substring(cycles.name,1,7)='".$ciclo."'
        group by cycles.name ";
		
		//echo $query."<br>";
		
		return sqlsrv_fetch_array(sqlsrv_query($conn, $query))['vis'];
	
	}
	
	if(isset($_POST['idRuta']) && $_POST['idRuta'] != ''){
		$ids = $_POST['idRuta'];
	}else{
		$ids = $_POST['ids'];
	}
	
	if(isset($_POST['tipoUsuario']) && $_POST['tipoUsuario'] != ''){
		$tipoUsuario = $_POST['tipoUsuario'];
	}
		
	/*$queryGrafica6 = "select substring(cycles.name,1,7) cycle_name,
        cycles.number,
        (select sum(physicians_goal) from users where user_snr in ('".$ids."')) goal,
        (select count(*) from person p
        inner join perslocwork plw on plw.pers_snr=p.pers_snr
        inner join pers_srep_work psw on psw.pwork_snr=plw.pwork_snr
        inner join user_territ ut on ut.user_snr=psw.user_snr and ut.inst_snr=plw.inst_snr
        inner join inst i on i.inst_snr=ut.inst_snr
        left outer join codelist status on status.clist_snr=p.status_snr
        where status.name='ACTIVO'
        and plw.rec_stat=0 and psw.rec_stat=0 and ut.rec_stat=0 and p.rec_stat=0 and i.rec_stat=0
        and psw.user_snr in ('".$ids."') ";
		if($tipoUsuario != 4){
			$queryGrafica6 .= "and p.spec_snr <> '4A7DC158-020D-4D08-9AC1-681B32BDB6F6' ";
		}
		$queryGrafica6 .= "
        ) regist,
        (select count(*)
        from visitpers v
        inner join person p on p.pers_snr=v.pers_snr ";
		if($tipoUsuario != 4){
			$queryGrafica6 .= "and p.spec_snr <> '4A7DC158-020D-4D08-9AC1-681B32BDB6F6' ";
		}
		$queryGrafica6 .= "
        where v.rec_stat=0
        and v.visit_date between start_date AND finish_date and v.user_snr in ('".$ids."')) visita_total
        from cycles
        where year='".date("Y")."'
        order by name ";*/
		
		$queryGrafica6 = "select
			substring(cycles.name,1,7) cycle_name,
			(
				select sum(frec.name*1) 
				from person p
				inner join perslocwork plw on plw.pers_snr=p.pers_snr
				inner join pers_srep_work psw on psw.pwork_snr=plw.pwork_snr
				inner join user_territ ut on ut.user_snr=psw.user_snr and ut.inst_snr=plw.inst_snr
				inner join inst i on i.inst_snr=ut.inst_snr
				left outer join codelist frec on frec.clist_snr=p.frecvis_snr
				left outer join codelist status on status.clist_snr=p.status_snr
				where status.name='ACTIVO'
				and plw.rec_stat=0 
				and psw.rec_stat=0 
				and ut.rec_stat=0 
				and p.rec_stat=0 
				and i.rec_stat=0
				and ut.USER_SNR in ('".$ids."')
			) goal,
			(
				select count(*) 
				from person p
				inner join perslocwork plw on plw.pers_snr=p.pers_snr
				inner join pers_srep_work psw on psw.pwork_snr=plw.pwork_snr
				inner join user_territ ut on ut.user_snr=psw.user_snr and ut.inst_snr=plw.inst_snr
				inner join inst i on i.inst_snr=ut.inst_snr
				left outer join codelist status on status.clist_snr=p.status_snr
				where status.name='ACTIVO'
				and plw.rec_stat=0 
				and psw.rec_stat=0 
				and ut.rec_stat=0 
				and p.rec_stat=0 
				and i.rec_stat=0
				and ut.USER_SNR in ('".$ids."')
			) regist,
			(
				select count(*) 
				from visitpers v
				inner join person p on p.pers_snr=v.pers_snr
				inner join perslocwork plw on plw.pers_snr=p.pers_snr
				inner join pers_srep_work psw on psw.pwork_snr=plw.pwork_snr and psw.user_snr=v.user_snr
				inner join user_territ ut on ut.user_snr=psw.user_snr and ut.inst_snr=plw.inst_snr
				inner join inst i on i.inst_snr=ut.inst_snr
				left outer join codelist status on status.clist_snr=p.status_snr
				where v.rec_stat=0
				and plw.rec_stat=0 and psw.rec_stat=0 and ut.rec_stat=0 and p.rec_stat=0 and i.rec_stat=0
				and status.name='ACTIVO'
				and v.novis_snr='00000000-0000-0000-0000-000000000000'
				and v.visit_date between start_date AND finish_date
				and ut.USER_SNR in ('".$ids."')
			) visita_total
			from cycles
			where year='".date("Y")."'
			order by name";
		
		//echo $queryGrafica6."<br>";
		
		$rsGrafica6 = sqlsrv_query($conn, $queryGrafica6);
		$etiquetas = '';
		$datos = '';
		$datos2 = '';
		$nombres = array();
		$registrados = array();
		$visitados = array();
		$goal = array();
		$visi_unica = array();
		while($regGrafica6 = sqlsrv_fetch_array($rsGrafica6)){
			$nombres[] = $regGrafica6['cycle_name'];
			$registrados[] = $regGrafica6['regist'];
			$visitados[] = $regGrafica6['visita_total'];
			$goal[] = $regGrafica6['goal'];
			$visi_unica[] = visitaUnica($conn, $regGrafica6['cycle_name'], $ids, $tipoUsuario);//$regGrafica6['visita_unica'];
		}
		/*print_r($nombres);
		echo "<br><br>";
		print_r($registrados);
		echo "<br><br>";
		print_r($visi_unica);
		echo "<br><br>";
		print_r($goal);
		echo "<br><br>";
		print_r($visitados);*/
		$totalRegistrados = array_sum($registrados);
		$totalVisitados = array_sum($visitados);
		for($i=0;$i<count($nombres);$i++){
			if($goal[$i] > 0){
				$por = ($visitados[$i]/$goal[$i])*100;
				$por2 = ($visi_unica[$i]/$registrados[$i])*100;
			}else{
				$por = 0;
				$por2 = 0;
			}
			$etiquetas .= '{"label": "'.$nombres[$i].'"},';
			$datos .= '{"value": "'.$por.'"},';
			$datos2 .= '{"value": "'.$por2.'"},';
			
			//'{"label": "'.$regGrafica5['ESP'].'","value": "'.$regGrafica5['regist'].'"},';
		}
		if($totalRegistrados > 0){
			$porTotal = ($totalVisitados/$totalRegistrados)*100;
		}else{
			$porTotal = 0;
		}
		$datos .= '{"label": "TOTAL", "value": "'.$porTotal.'","tooltext": "Visitados, '.$totalVisitados.'"}';
		//$datos = substr($datos, 0, -1);
		//$etiquetas = substr($etiquetas, 0, -1);
		/*echo $datos;
		echo "<br><br>";
		echo $etiquetas;*/
		
		echo '<script type="text/javascript">
			FusionCharts.ready(function() {
               var appChart = new FusionCharts({
                   type: \'msline\',
                   width: \'100%\',
                   height: \'450\',
                   renderAt: \'grafica9\',
                   dataFormat: \'json\',
                   dataSource: {
                        "chart": {
                            "theme": "fusion",
                            "exportenabled": "1",
                            "exportMode": "server",
                            "exportFormats": "PNG=Descargar como PNG|PDF= Descargar como PDF",
                            "exportFileName":"Gráfica Cobetura por número de contactos",
                            "tickValueStep": "2",
                            "numbersuffix": "%",
                            "drawcrossline": "1",
                            "crossLineColor": "#4CAF50",
                            "showcanvasborder": "0",
                            "showBorder": "0",
                            "showPlotBorder": "0",
                            "showLegend": "0",
                            "bgColor": "#ffffff",
                            "divLineColor": "#3F51B5",
                            "divLineDashed": "1",
                            "numDivLines": "3",
                            "showAlternateHGridColor": "0",
                            "plottooltext": "<b>$dataValue</b> de $seriesName",
                            "userInteraction":"1",
                            "xAxisName": "Ciclo",                          
                            "yAxisName": "Porcentaje",
                            "showYAxisValues": "1",
                            "palettecolors":"#8BC34A"
                        },
                        "categories": [{
                            "category": [
                                '.$etiquetas.'
                                ]
                        }],
                        "dataset": [{
                             "seriesname": "Número de Contactos",
                                "data": [
                                    '.$datos.'
                                ]
                        }]
                   },
                   "events": {
                    "renderComplete": function(e, a) {
       
                       var addListener = function(elem, evt, fn) {
                         if (elem && elem.addEventListener) {
                           elem.addEventListener(evt, fn);
                         } else if (elem && elem.attachEvent) {
                           elem.attachEvent("on" + evt, fn);
                         } else {
                           elem["on" + evt] = fn;
                         }
                       };
       
                       var exportFC = function() {
                         var types = {
                           "exportpdf9": "pdf",
                           "exportpng9": "png"
                         };
                         if (e && e.sender && e.sender.exportChart) {
                           e.sender.exportChart({
                             exportFormat: types[this.id]
                           });
                         }
                       };
       
                       // Attach events
                       addListener(document.getElementById("exportpdf9"), "click", exportFC);
                       addListener(document.getElementById("exportpng9"), "click", exportFC);
                     }
                   }
               }).render();
           });
	</script>';
	
	echo '<script type="text/javascript">
			FusionCharts.ready(function() {
               var appChart = new FusionCharts({
                   type: \'msline\',
                   width: \'100%\',
                   height: \'450\',
                   renderAt: \'grafica10\',
                   dataFormat: \'json\',
                   dataSource: {
                        "chart": {
                            "theme": "fusion",
                            "numbersuffix": "%",
                            "drawcrossline": "1",
                            "crossLineColor": "#ff0000",
                            "showcanvasborder": "0",
                            "showBorder": "0",
                            "showPlotBorder": "0",
                            "showLegend": "0",
                            "bgColor": "#ffffff",
                            "divLineColor": "#3a4a9a",
                            "divLineDashed": "1",
                            "numDivLines": "3",
                            "showAlternateHGridColor": "0",
                            "plottooltext": "<b>$dataValue</b> de $seriesName",
                            "exportenabled": "1",
                            "exportMode": "server",
                            "exportFormats": "PNG=Descargar como PNG|PDF= Descargar como PDF",
                            "exportFileName":"Gráfica Cobetura por Fichero Médico",
                            "tickValueStep": "2",
                            "userInteraction":"1",
                            "xAxisName": "Ciclo",
                            "yAxisName": "Porcentaje",
                            "showYAxisValues": "1",
                            "palettecolors":"#ff4000"
                        },
                        "categories": [{
                            "category": [
                                '.$etiquetas.'
                            ]
                        }],
                        "dataset": [{
                             "seriesname": "Real",
                                "data": [
                                    '.$datos2.'
								]
                        }]
                   },
                   "events": {
                    "renderComplete": function(e, a) {
       
                       var addListener = function(elem, evt, fn) {
                         if (elem && elem.addEventListener) {
                           elem.addEventListener(evt, fn);
                         } else if (elem && elem.attachEvent) {
                           elem.attachEvent("on" + evt, fn);
                         } else {
                           elem["on" + evt] = fn;
                         }
                       };
       
                       var exportFC = function() {
                         var types = {
                           "exportpdf10": "pdf",
                           "exportpng10": "png"
                         };
                         if (e && e.sender && e.sender.exportChart) {
                           e.sender.exportChart({
                             exportFormat: types[this.id]
                           });
                         }
                       };
       
                       // Attach events
                       addListener(document.getElementById("exportpdf10"), "click", exportFC);
                       addListener(document.getElementById("exportpng10"), "click", exportFC);
                     }
                   }
               }).render();
           });
	</script>';
	/*echo "datos 2<br><br>";
	echo $datos2;*/
?>