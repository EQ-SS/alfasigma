<?php

	include "../conexion.php";

	if(isset($_POST['idRuta']) && $_POST['idRuta'] != ''){
		$ids = $_POST['idRuta'];
	}else{
		$ids = $_POST['ids'];
	}
		
	$queryGrafica6 = "select
        isnull(spec_target.sort_num,999) sort_num,
        isnull(spec.name_short,'OTRAS') ESP,
        count(*) regist,
        isnull(count(pcc.total),0) VISITADOS
        from person p
        inner join perslocwork plw on plw.pers_snr=p.pers_snr
        inner join pers_srep_work psw on psw.pwork_snr=plw.pwork_snr
        inner join user_territ ut on ut.user_snr=psw.user_snr and ut.inst_snr=plw.inst_snr
        inner join inst i on i.inst_snr=ut.inst_snr
        left outer join person_cycle_current pcc on pcc.pers_snr=p.pers_snr
        left outer join compline_specs spec_target on spec_target.spec_snr=p.spec_snr
        left outer join codelist status on status.clist_snr=p.status_snr
        left outer join codelist spec on spec.clist_snr=spec_target.spec_snr
        where status.name='ACTIVO' and p.rec_stat=0
        and plw.rec_stat=0 and psw.rec_stat=0 and ut.rec_stat=0 and i.rec_stat=0 
		and psw.user_snr in ('".$ids."')
        group by spec.name,spec_target.sort_num, spec.NAME_SHORT
        order by sort_num,ESP ";
		
		//echo $queryGrafica6."<br>";
		$rsGrafica6 = sqlsrv_query($conn, $queryGrafica6);
		$datos = '';
		$especialidad = array();
		$registrados = array();
		$visitados = array();
		while($regGrafica6 = sqlsrv_fetch_array($rsGrafica6)){
			$especialidad[] = $regGrafica6['ESP'];
			$registrados[] = $regGrafica6['regist'];
			$visitados[] = $regGrafica6['VISITADOS'];
		}
		
		$totalRegistrados = array_sum($registrados);
		$totalVisitados = array_sum($visitados);
		for($i=0;$i<count($especialidad);$i++){
			if($totalRegistrados > 0){
				$por = ($registrados[$i]/$totalRegistrados)*100;
			}else{
				$por = 0;
			}
			$datos .= '{"label": "'.$especialidad[$i].'", "value": "'.$por.'","tooltext": "Visitados, '.$visitados[$i].'"},';
			//'{"label": "'.$regGrafica5['ESP'].'","value": "'.$regGrafica5['regist'].'"},';
		}
		if($totalRegistrados > 0){
			$porTotal = ($totalVisitados/$totalRegistrados)*100;
		}else{
			$porTotal = 0;
		}
		$datos .= '{"label": "TOTAL", "value": "'.$porTotal.'","tooltext": "Visitados, '.$totalVisitados.'"}';
		//$datos = substr($datos, 0, -1);
		//echo $datos;
		
		echo '<script type="text/javascript">
		FusionCharts.ready(function() {
               var appChart = new FusionCharts({
                   type: \'column2d\',
                   width: \'100%\',
                   height: \'450\',
                   renderAt: \'grafica6\',
                   dataFormat: \'json\',
                   dataSource: {
                        "chart": {
                            "canvasbgColor": "#FFFFFF",
                            "showCanvasBase": "0",
                            "canvasbgAlpha": "0",
                            "showCanvasBorder":"0",
                            "userInteraction":"0",
                            "canvasBgAngle": "0",
                            "showCanvasBase": "0",
                            "plotspacepercent": "5",
                            "showToolBarButtonTooltext":"0",

                            "xAxisName": "Especialidad",
                            "yAxisName": "Porcentaje",
                            "showYAxisValues": "1",

                            "theme": "fint",
                            "palettecolors":"#4CAF50,#FFC107,#FF5722,#2196F3,#795548,#3F51B5,#E91E63,#607D8B,#009688,#FF9800",

                            "placeValuesInside": "0",
                            "rotateValues":"0",
                            "valueFontColor": "#000000",
                            "valueFontSize": "14",

                            "divLineColor": "#306DBD",
                            "divLineAlpha": "60",
                            "divLineDashed": "1",

                            "plotHighlightEffect":"fadeout",

                            "tickValueStep": "2",
                            "showPercentInTooltip": "1",
                            "showValues" : "1",
                            "showPercentValues" : "0",

                            "labelFontSize": "14",
                            "baseFontSize": "13",

                            "numberSuffix": "%",
                            "stack100Percent": "0",
                        
                            "legendIconScale": "1",
                            "legendPosition": "bottom",
                            "legendBgColor": "FFFFFF",
                            "legendShadow": "1",
                            "legendBorderColor": "#666666",
                            "legendBorderThickness": "1",
                            "legendBorderAlpha": "30",
                            "legendCaption": "Médicos",
                            "legendCaptionFontSize": "14",
                            "legendItemFontSize": "13",
                            "legendAllowDrag": "0"
                        },
                        "data": [{"label": "GASTRO", "value": "17.159763313609","tooltext": "Visitados, 1"},
							{"label": "MG", "value": "56.804733727811","tooltext": "Visitados, 8"},
							{"label": "MI", "value": "5.3254437869822","tooltext": "Visitados, 0"},
							{"label": "PEDIA", "value": "4.1420118343195","tooltext": "Visitados, 1"},
							{"label": "ANGIO", "value": "4.1420118343195","tooltext": "Visitados, 0"},
							{"label": "CIRUG", "value": "7.1005917159763","tooltext": "Visitados, 3"},
							{"label": "FLEBO", "value": "5.3254437869822","tooltext": "Visitados, 1"},
							{"label": "TOTAL", "value": "8.2840236686391","tooltext": "Visitados, 14"}]
                   }
               }).render();
           });
	</script>';
?>