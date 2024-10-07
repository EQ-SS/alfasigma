<?php
include "../conexion.php";

if(isset($_POST['idRuta']) && $_POST['idRuta'] != ''){
	$ids = $_POST['idRuta'];
}else{
	$ids = $_POST['ids'];
}

$queryGrafica4 = "with Meds as (Select PSW.pers_snr,
count(*) Regist,
isnull(vis.total,0) visitados
From Pers_SRep_Work PSW 
inner join Perslocwork PLW on PSW.pwork_snr=plw.pwork_snr and plw.rec_stat=0
inner join Person P on P.pers_snr=PSW.pers_snr and p.rec_stat=0 and p.STATUS_SNR = 'B426FB78-8498-4185-882D-E0DC381460E8'
left outer join PERSON_CYCLE_CURRENT vis on vis.user_snr=psw.user_snr and vis.pers_snr=psw.pers_snr
Where PSW.user_snr in ('".$ids."')
and PSW.rec_stat=0 
group by PSW.pers_snr,ISNULL(vis.total,0))
select sum(regist) as REGISTRADOS,sum(visitados) as VISITADOS 
from meds";
$rsGrafica4 = sqlsrv_query($conn, $queryGrafica4);
while($regGrafica4 = sqlsrv_fetch_array($rsGrafica4)){							
	$visitas = $regGrafica4['VISITADOS'];
	$registrados = $regGrafica4['REGISTRADOS'];
}
if ($registrados > 0) {
	$porcentaje = ceil(($visitas / $registrados) * 100);
} else {
	$porcentaje = 0;
}

echo $queryGrafica4;

echo "<script type=\"text/javascript\">
	FusionCharts.ready(function () {
		var cSatScoreChart = new FusionCharts({
			type: 'angulargauge',
			renderAt: 'grafica4',
			width: '100%',
			height: '245',
			dataFormat: 'json',
			dataSource: {
				\"chart\": {
					\"subcaptionFont\": \"Arial\",
					\"subcaptionFontSize\": \"14\",
					\"subcaptionFontColor\": \"#306DBD\",
					\"subcaptionFontBold\": \"0\",
					\"lowerLimit\": \"0\",
					\"upperLimit\": \"100\",
					\"lowerLimitDisplay\": \"0\",
					\"upperLimitDisplay\": \"100\",
					\"gaugeFillRatio\": \"30\",
					\"tickMarkDistance\": \"10\",
					\"placeValuesInside\": \"0\",
					\"tickValueStep\": \"0\",
					\"showTickMarks\": \"1\",
					\"showTickValues\": \"1\",
					\"numberSuffix\": \"%\",
					\"SubCaptionPadding\": \"0\",
					 \"exportenabled\": \"1\",
					 \"showToolBarButtonTooltext	\": \"0	\",
					\"exportMode\": \"server\",
					\"exportFormats\": \"PNG=Descargar como PNG|PDF= Descargar como PDF\",
					\"exportFileName\":\"Grafica Cobertura Vs. Fichero\",
					\"lowerDisplayLimit\": \"2\",
					\"majorTMNumber\": \"9\",
					\"minorTMNumber\": \"4\",
					\"theme\": \"fint\"
				},
				\"colorRange\": {
					\"color\": [
						{
							\"minValue\": \"0\",
							\"maxValue\": \"50\",
							\"code\": \"#F44336\"
						},
						{
							\"minValue\": \"50\",
							\"maxValue\": \"80\",
							\"code\": \"#f8bd19\"
						},
						{
							\"minValue\": \"80\",
							\"maxValue\": \"100\",
							\"code\": \"#62B58F\"
						}
					]
				},
				\"dials\": {
					\"dial\": [{
						\"value\": \"".$porcentaje."\",
						\"showValue\": \"1\",
						\"radius\": \"120\",
						\"rearExtension\": \"5\"
					}]
				}
			},
			'events': {
			  'renderComplete': function(e, a) {

				var addListener = function(elem, evt, fn) {
				  if (elem && elem.addEventListener) {
					elem.addEventListener(evt, fn);
				  } else if (elem && elem.attachEvent) {
					elem.attachEvent('on' + evt, fn);
				  } else {
					elem['on' + evt] = fn;
				  }
				};

				var exportFC = function() {
				  var types = {
					'exportpdf4': 'pdf',
					'exportpng4': 'png'
				  };
				  if (e && e.sender && e.sender.exportChart) {
					e.sender.exportChart({
					  exportFormat: types[this.id]
					});
				  }
				};

				// Attach events
				addListener(document.getElementById('exportpdf4'), 'click', exportFC);
				addListener(document.getElementById('exportpng4'), 'click', exportFC);
			  }
			}
		}).render();    
	});
	</script>";
?>