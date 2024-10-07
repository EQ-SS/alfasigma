<?php
include "../conexion.php";

if(isset($_POST['idRuta']) && $_POST['idRuta'] != ''){
	$ids = $_POST['idRuta'];
}else{
	$ids = $_POST['ids'];
}

// Unix
setlocale(LC_TIME, 'es_MX.UTF-8');
// En windows
setlocale(LC_TIME, 'spanish');
$fechaHoy = date("d-m-Y");

$queryGrafica3 = "with meds as (select p.pers_snr,
        pcc.total as vis_total,
        case when pcc.total>0 then 1 else 0 end vis_unique
        from person p
        left outer join person_cycle_current pcc on pcc.pers_snr=p.pers_snr
        inner join perslocwork plw on plw.pers_snr=p.pers_snr
        inner join pers_srep_work psw on psw.pwork_snr=plw.pwork_snr
        inner join user_territ ut on ut.user_snr=psw.user_snr and ut.inst_snr=plw.inst_snr
        inner join inst i on i.inst_snr=ut.inst_snr
        left outer join codelist status on status.clist_snr=p.status_snr
        where status.name='ACTIVO'
        and plw.rec_stat=0 and psw.rec_stat=0 and ut.rec_stat=0 and p.rec_stat=0 and i.rec_stat=0
		and psw.user_snr in ('".$ids."')
		)
        select count(*) regist,sum(vis_unique) vis_unique,isnull(sum(vis_total),0) vis_total,
		isnull((select sum(cast(isnull(frecvis.name,0) as int)) frec
		from person p
		left outer join PERS_SREP_WORK psw on psw.PERS_SNR = p.PERS_SNR 
		left outer join CYCLE_PERS_CATEG_SPEC cpcs on cpcs.SPEC_SNR = p.SPEC_SNR and cpcs.CATEGORY_SNR = p.CATEGORY_SNR 
		left outer join CODELIST frecvis on frecvis.CLIST_SNR = cpcs.FRECVIS_SNR
		left outer join CYCLES c on cpcs.CYCLE_SNR = c.CYCLE_SNR 
		left outer join CODELIST status on status.CLIST_SNR = p.STATUS_SNR
		where p.REC_STAT = 0
		and psw.REC_STAT = 0
		and psw.user_snr in ('".$ids."')
		and '".date("Y-m-d")."' between c.start_date and c.finish_date 
		and cpcs.REC_STAT = 0
		and c.REC_STAT = 0
		and status.NAME = 'ACTIVO'
		group by c.name),0) cuota
		from meds ";

//echo $queryGrafica3;
$rsGrafica3 = sqlsrv_query($conn, $queryGrafica3);
while($regGrafica3 = sqlsrv_fetch_array($rsGrafica3)){		
	$registro = $regGrafica3['regist'];
	$visita_u = $regGrafica3['vis_unique'];
	$visita_t = $regGrafica3['vis_total'];
	$cuota = $regGrafica3['cuota'];
}
if ($cuota > 0) {
	$porcentajeC = round(($visita_t / $cuota) * 100);
} else {
	$porcentajeC = 0;
}

if ($registro > 0) {
	$porcentajeR = round(($visita_u / $registro) * 100);
} else {
	$porcentajeR = 0;
}

echo "<script>
		$('#ficheroMedico').empty();
		$('#visitaUnica').empty();
		$('#numeroContactos').empty();
		$('#visitaTotal').empty();

		$('#ficheroMedico').append('<span class=\'number count-to\' data-from=\'0\' data-to=\'".$registro."\' data-speed=\'1000\' data-fresh-interval=\'20\'>".$registro."</span>');
		$('#visitaUnica').append('<span class=\'number count-to\' data-from=\'0\' data-to=\'".$visita_u."\' data-speed=\'1000\' data-fresh-interval=\'20\'>".$visita_u."</span>');
		$('#numeroContactos').append('<span class=\'number count-to\' data-from=\'0\' data-to=\'".$cuota."\' data-speed=\'1000\' data-fresh-interval=\'20\'>".$cuota."</span>');
		$('#visitaTotal').append('<span class=\'number count-to\' data-from=\'0\' data-to=\'".$visita_t."\' data-speed=\'1000\' data-fresh-interval=\'20\'>".$visita_t."</span>');

		</script>";

echo "<script type=\"text/javascript\">
	FusionCharts.ready(function () {
		var cSatScoreChart = new FusionCharts({
			type: 'angulargauge',
			renderAt: 'grafica3',
			width:'99%',
			height: '280',
			dataFormat: 'json',
			dataSource: {
				\"chart\": {
					\"caption\": \"Cobertura vs Universo\",
					\"subcaption\":\"".$fechaHoy."\",
					\"lowerLimit\": \"0\",
					\"upperLimit\": \"100\",
					\"lowerLimitDisplay\": \"0\",
					\"upperLimitDisplay\": \"100\",
					\"gaugeFillRatio\": \"30\",
					\"gaugeOuterRadius\": \"160\",
					\"gaugeInnerRadius\": \"100\",
					\"tickMarkDistance\": \"10\",
					\"placeValuesInside\": \"0\",
					\"tickValueStep\": \"0\",
					\"showTickMarks\": \"1\",
					\"showTickValues\": \"1\",
					\"numberSuffix\": \"%\",
					\"SubCaptionPadding\": \"0\",
					
					\"exportEnabled\": \"1\",
					\"exportShowMenuItem\": \"1\",
					\"majorTMNumber\": \"9\",
					\"minorTMNumber\": \"4\",
					\"theme\": \"fusion\",
					\"chartBottomMargin\": \"12\"
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
							\"code\": \"#6baa01\"
						}
					]
				},
				\"dials\": {
					\"dial\": [{
						\"value\": \"".$porcentajeR."\",
						\"showValue\": \"1\"
					}]
				}
			},
			\"events\": {
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
							'exportpdf3': 'pdf',
							'exportpng3': 'png'
						};
						if (e && e.sender && e.sender.exportChart) {
							e.sender.exportChart({
								exportFileName: 'FC_sample_export',
								exportFormat: types[this.id]
							});
						}
					};
	
					addListener(document.getElementById('exportpng3'), 'click', exportFC);
					addListener(document.getElementById('exportpdf3'),'click', exportFC);
				}
			}
		});
		cSatScoreChart.render();
	});

	FusionCharts.ready(function () {
		var cSatScoreChart2 = new FusionCharts({
			type: 'angulargauge',
			renderAt: 'grafica4',
			width: '100%',
			height: '280',
			dataFormat: 'json',
			dataSource: {
				\"chart\": {
					\"caption\": \"Cobertura vs Fichero\",
					\"subcaption\":\"".$fechaHoy."\",
					\"lowerLimit\": \"0\",
					\"upperLimit\": \"100\",
					\"lowerLimitDisplay\": \"0\",
					\"upperLimitDisplay\": \"100\",
					\"gaugeFillRatio\": \"30\",
					\"gaugeOuterRadius\": \"160\",
					\"gaugeInnerRadius\": \"100\",
					\"tickMarkDistance\": \"10\",
					\"placeValuesInside\": \"0\",
					\"tickValueStep\": \"0\",
					\"showTickMarks\": \"1\",
					\"showTickValues\": \"1\",
					\"numberSuffix\": \"%\",
					\"exportEnabled\": \"1\",
          \"exportShowMenuItem\": \"1\",
					\"exportFileName\":\"Grafica Cobertura Vs. Fichero\",
					\"lowerDisplayLimit\": \"2\",
					\"majorTMNumber\": \"9\",
					\"minorTMNumber\": \"4\",
					\"theme\": \"fusion\",
					\"chartBottomMargin\": \"12\"
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
							\"code\": \"#6baa01\"
						}
					]
				},
				\"dials\": {
					\"dial\": [{
						\"value\": \"".$porcentajeC."\",
						\"showValue\": \"1\"
					}]
				}
			}
		});
		cSatScoreChart2.render();

		function export_chart2() {
			var types = {
				'exportpdf4': 'pdf',
				'exportpng4': 'png'
			};

			cSatScoreChart2.exportChart({
					'exportFormat': types[this.id]
			});
		}
		document.getElementById('exportpng4').addEventListener('click', export_chart2);
		document.getElementById('exportpdf4').addEventListener('click', export_chart2);
	});
	</script>";
?>
<script>
	$(function () {
    $('.count-to').countTo();
	});
</script>
