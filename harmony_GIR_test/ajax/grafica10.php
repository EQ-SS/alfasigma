<?php

	include "../conexion.php";

	if(isset($_POST['idRuta']) && $_POST['idRuta'] != ''){
		$ids = $_POST['idRuta'];
	}else{
		$ids = $_POST['ids'];
	}
		
	$queryGrafica6 = "with grafica9 as (select
        u.user_snr,
        substring(cycles.name,1,7) cycle_name,
        u.physicians_goal goal,
        (select count(*) from person p
        inner join perslocwork plw on plw.pers_snr=p.pers_snr
        inner join pers_srep_work psw on psw.pwork_snr=plw.pwork_snr
        inner join user_territ ut on ut.user_snr=psw.user_snr and ut.inst_snr=plw.inst_snr
        inner join inst i on i.inst_snr=ut.inst_snr
        left outer join codelist status on status.clist_snr=p.status_snr
        where status.name='ACTIVO'
        and plw.rec_stat=0 and psw.rec_stat=0 and ut.rec_stat=0 and p.rec_stat=0 and i.rec_stat=0
        and psw.USER_SNR=u.user_snr
        ) regist,
        (select sum(total) from PERSON_CYCLE_CURRENT pcc
        where pcc.user_snr=u.user_snr) visita_total,
        (select count(total) from PERSON_CYCLE_CURRENT pcc
        where pcc.user_snr=u.user_snr) visita_unica
        from cycles, users u
        where year='".date("Y")."'
        and u.user_snr in ('".$ids."'))
		select cycle_name,sum(goal) goal,sum(regist) regist,
		sum(visita_total) visita_total,sum(visita_unica) visita_unica
		from grafica9
		group by cycle_name ";
		
		$rsGrafica6 = sqlsrv_query($conn, $queryGrafica6);
		$etiquetas = '';
		$datos = '';
		$nombres = array();
		$registrados = array();
		$visitados = array();
		$goal = array();
		while($regGrafica6 = sqlsrv_fetch_array($rsGrafica6)){
			$nombres[] = $regGrafica6['cycle_name'];
			$registrados[] = $regGrafica6['regist'];
			$visitados[] = $regGrafica6['visita_unica'];
			$goal[] = $regGrafica6['goal'];
		}
		
		//$totalRegistrados = array_sum($registrados);
		//$totalVisitados = array_sum($visitados);
		//print_r($nombres);
		for($i=0;$i<count($nombres);$i++){
			if($goal[$i] > 0){
				$por = ($visitados[$i]/$registrados[$i])*100;
			}else{
				$por = 0;
			}
			$etiquetas .= '{"label": "'.$nombres[$i].'"},';
			$datos .= '{"value": "'.$por.'"},';
			//'{"label": "'.$regGrafica5['ESP'].'","value": "'.$regGrafica5['regist'].'"},';
		}
		/*if($totalRegistrados > 0){
			$porTotal = ($totalVisitados/$totalRegistrados)*100;
		}else{
			$porTotal = 0;
		}
		$datos .= '{"label": "TOTAL", "value": "'.$porTotal.'","tooltext": "Visitados, '.$totalVisitados.'"}';*/
		$datos = substr($datos, 0, -1);
		$etiquetas = substr($etiquetas, 0, -1);
		//echo $datos;
		//echo "<br><br>";
		//echo $etiquetas;
		
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
                            "crossLineColor": "#E91E63",
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
                            "exportFormats": "PNG=Descargar como PNG|PDF= Descargar como PDF",
                            "exportFileName":"Cobertura por Fichero Médico",
                            "userInteraction":"1",
                            "xAxisName": "Ciclo",
                            "xAxisNameFont": "Arial",
                            "xAxisNameFontSize": "13",
                            "xAxisNameFontColor": "#000000",
                            "yAxisName": "Porcentaje",
                            "yAxisNameFont": "Arial",
                            "yAxisNameFontSize": "13",
                            "yAxisNameFontColor": "#000000",
                            "showYAxisValues": "1",
                            "palettecolors":"#F44336",
                            "valueFont": "Arial",
                            "valueFontColor": "#000000",
                            "valueFontSize": "14",
                            "valueFontBold": "0",
                            "valueFontItalic": "0",
                            "valueFontAlpha": "90"
                        },
                        "categories": [{
                            "category": [
                                '.$etiquetas.'
                            ]
                        }],
                        "dataset": [{
                             "seriesname": "Real",
                                "data": [
                                    '.$datos.'
								]
                        }]
                   }
               }).render();
           });
	</script>';
?>