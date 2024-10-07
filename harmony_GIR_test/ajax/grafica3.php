<?php
	include "../conexion.php";

	if(isset($_POST['idUser']) && $_POST['idUser'] != ''){
		$idUser = $_POST['idUser'];
	}else{
		$idUser = '';
	}

	$tipoRuta = '';
	$idRuta = '';

	if(isset($_POST['idRuta']) && $_POST['idRuta'] != ''){
		$ids = $idRuta = $_POST['idRuta'];
		$tipoRuta = sqlsrv_fetch_array(sqlsrv_query($conn, "select user_type from users where rec_stat = 0 and user_snr = '".$ids."' "))['user_type'];

		if($tipoRuta != 2 && $tipoRuta != 4){
			$rsRuta = sqlsrv_query($conn, "select USER_SNR, KLOC_SNR 
				from kloc_reg k, users u 
				where reg_snr = '".$ids."' 
				and k.REC_STAT = 0 
				and KLOC_SNR = USER_SNR 
				and u.REC_STAT = 0 
				order by u.LNAME");
			//echo "select KLOC_SNR from KLOC_REG where REG_SNR = '".$ids."'";
			$rutas = array();
			while($regRuta = sqlsrv_fetch_array($rsRuta)){
				$rutas[] = $regRuta['KLOC_SNR'];
			}
			$ids = str_replace(",","','", implode(",",$rutas));
		}
	}else{
		$ids = $_POST['ids'];
	}
	if($tipoRuta == ''){
		if(isset($_POST['tipoUsuario']) && $_POST['tipoUsuario'] != ''){
			$tipoUsuario = $_POST['tipoUsuario'];
		}else{
			$tipoUsuario = sqlsrv_fetch_array(sqlsrv_query($conn, "select user_type from users where user_snr = '".$idSuper."' "))['user_type'];
			//echo "select user_type from users where user_snr = '".$idSuper."' ";
		}
	}else{
		$tipoUsuario = $tipoRuta;
	}

	// Unix
	setlocale(LC_TIME, 'es_MX.UTF-8');
	// En windows
	setlocale(LC_TIME, 'spanish');
	$fechaHoy = date("Y-m-d");

	$queryGrafica3 = "with meds as 
	( 
		select p.pers_snr, 
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
		and plw.rec_stat=0 
		and psw.rec_stat=0 
		and ut.rec_stat=0 
		and p.rec_stat=0 
		and i.rec_stat=0 
		and P.LNAME<>'REPORTE'
		and psw.user_snr in ('".$ids."') 
	) 
	select count(*) regist, 
	sum(vis_unique) vis_unique, 
	isnull(sum(vis_total),0) vis_total, 
	isnull
	(
		(
			select days*contacts 
			from cycles 
			where '".$fechaHoy."' between START_DATE and FINISH_DATE 
			and REC_STAT=0
		),0
	) cuota,
	(
		Select
		(
			(
				cast(sum(drc.value) as float) / 8
			) * cycles.contacts
		) contactos_tft
		from day_report dr, day_report_code drc, cycles
		where dr.dayreport_snr=drc.dayreport_snr 
		and dr.date between cycles.start_date 
		and cycles.finish_date
		and cycle_snr= (select CYCLE_SNR from cycles where '".$fechaHoy."' between START_DATE and FINISH_DATE and REC_STAT=0)
		and dr.user_snr in ('".$ids."') 
		and dr.REC_STAT = 0
		group by cycles.contacts
	) as contactos_tft
	from meds ";

	//echo $queryGrafica3;

	$rsGrafica3 = sqlsrv_query($conn, $queryGrafica3);
	while($regGrafica3 = sqlsrv_fetch_array($rsGrafica3)){		
		$registro = $regGrafica3['regist'];
		$visita_u = $regGrafica3['vis_unique'];
		$visita_t = $regGrafica3['vis_total'];
		$cuota = $regGrafica3['cuota'];
		$cuotaReal = $regGrafica3['vis_unique'];
		//$ficheroReal = $regGrafica3['cuota'] - $regGrafica3['contactos_tft'];
		//$ficheroReal = 200;
		$tft = $regGrafica3['contactos_tft'];
	}

	///obtener cuota Real 

	$qCuotaReal = "select sum(PHYSICIANS_GOAL) as cuota 
	from CONFIG_USER
	where user_snr in ('".$ids."') ";

	$rsCuotaReal = sqlsrv_query($conn, $qCuotaReal);

	$ficheroReal = sqlsrv_fetch_array($rsCuotaReal)['cuota'];


	if($tipoUsuario == 5){
		if($idRuta <> ''){
			$idUser = $idRuta;
		}
		//echo "select count(*) as rep from kloc_reg where REG_SNR = '".$idUser."' and KLOC_SNR <> '".$idUser."' group by REG_SNR";
		$cuentaUsuarios = sqlsrv_fetch_array(sqlsrv_query($conn, "select count(*) as rep from kloc_reg where REG_SNR = '".$idUser."' and KLOC_SNR <> '".$idUser."' and rec_stat = 0 group by REG_SNR"))['rep'];
		$cuota *= $cuentaUsuarios;
		//$cuotaReal *= $cuentaUsuarios;
		//$ficheroReal *= $cuentaUsuarios;
		//echo "select count(*) as rep from kloc_reg where REG_SNR = '".$idUser."' and KLOC_SNR <> '".$idUser."' group by REG_SNR";
		//echo "ficheroReal: ".$ficheroReal;

	}else if($tipoUsuario == 2){
		$cuentaUsuarios = sqlsrv_fetch_array(sqlsrv_query($conn, "select count(*) as rep from users where USER_TYPE = 4 and REC_STAT = 0 group by REGION_SNR"))['rep'];
		$cuota *= $cuentaUsuarios;
		//$cuotaReal *= $cuentaUsuarios;
		//$ficheroReal *= $cuentaUsuarios;
	}

	$ficheroReal -= $tft;
	//$cuotaReal = $ficheroReal - round(($cuotaReal * 10) / 8);

	if ($registro > 0) {
		$porcentajeR = round(($visita_u / $registro) * 100);
	} else {
		$porcentajeR = 0;
	}

	if ($cuota > 0) {
		$porcentajeC = round(($visita_t / $cuota) * 100);
	} else {
		$porcentajeC = 0;
	}

	if ($ficheroReal > 0) {
		$porcentajeD = round(($visita_u / $ficheroReal) * 100);
	} else {
		$porcentajeD = 0;
	}


	$fecha_inicio = sqlsrv_fetch_array(sqlsrv_query($conn, "SELECT convert(varchar,START_DATE,120) START_DATE FROM CYCLES WHERE  '".date("Y-m-d")."' BETWEEN START_DATE AND FINISH_DATE"))['START_DATE'];
	$fecha_termino = sqlsrv_fetch_array(sqlsrv_query($conn, "SELECT convert(varchar,FINISH_DATE,120) FINISH_DATE FROM CYCLES WHERE  '".date("Y-m-d")."' BETWEEN START_DATE AND FINISH_DATE"))['FINISH_DATE'];

	$queryReproduccionAv="Select
	(case when (select COUNT(SlideHist.slide_snr) from CLM_HISTORY as Hist 
	inner join CLM_SLIDE_HISTORY as SlideHist on SlideHist.clm_snr = Hist.clm_snr and SlideHist.clm_history_snr = Hist.clm_history_snr
	where P.pers_snr = Hist.pers_snr and U.user_snr = Hist.user_snr and convert(varchar(10), VP.visit_date, 120) = convert(varchar(10), Hist.clm_date, 120) 
	and Hist.rec_stat=0 ) > 2 then 'SI' else 'NO' end) as Presento_clm	
	from visitpers VP
	inner join person P on VP.pers_snr = P.pers_snr
	inner join users U on U.user_snr = VP.user_snr
	left outer join VISPERSPLAN VPLAN on VPLAN.vispersplan_snr = VP.vispersplan_snr
	where
	VP.pers_snr <> '00000000-0000-0000-0000-000000000000'
	and P.pers_snr <> '00000000-0000-0000-0000-000000000000'
	and P.rec_stat=0
	and VP.rec_stat=0
	and U.rec_stat=0
	and U.status=1
	and U.user_type in (4,5) ";

	if($tipoUsuario==5){
		$queryReproduccionAv.=" and U.user_snr in ('".$ids."') ";
	}
	
	$queryReproduccionAv.=" and VP.visit_date between '".$fecha_inicio."' and '".$fecha_termino."' ";

	$rsContAv = sqlsrv_query($conn, $queryReproduccionAv);

	$iAv=0;
	$jAv=0;
	$porcentajeAv=0;

	while($regContAv = sqlsrv_fetch_array($rsContAv)){
		
		
		if($regContAv['Presento_clm']=="SI"){
			$iAv++;
		}

		$jAv++;
	}

	if($iAv>0){
		$porcentajeAv=($iAv/$jAv) * 100;
	}else{
		$porcentajeAv=0;
	}


echo "<script>
		$('#ficheroMedico').empty();
		$('#visitaUnica').empty();
		$('#numeroContactos').empty();
		$('#visitaTotal').empty();
		$('#numeroContactosReales').empty();
		$('#visitaUnicaReal').empty();
		$('#repdeAv').empty();
		

		$('#ficheroMedico').append('<span class=\'number count-to\' data-from=\'0\' data-to=\'".$registro."\' data-speed=\'1000\' data-fresh-interval=\'20\'>".$registro."</span>');
		$('#visitaUnica').append('<span class=\'number count-to\' data-from=\'0\' data-to=\'".$visita_u."\' data-speed=\'1000\' data-fresh-interval=\'20\'>".$visita_u."</span>');
		$('#numeroContactos').append('<span class=\'number count-to\' data-from=\'0\' data-to=\'".$cuota."\' data-speed=\'1000\' data-fresh-interval=\'20\'>".$cuota."</span>');
		$('#visitaTotal').append('<span class=\'number count-to\' data-from=\'0\' data-to=\'".$visita_t."\' data-speed=\'1000\' data-fresh-interval=\'20\'>".$visita_t."</span>');
		$('#numeroContactosReales').append('<span class=\'number count-to\' data-from=\'0\' data-to=\'".$ficheroReal."\' data-speed=\'1000\' data-fresh-interval=\'20\'>".$ficheroReal."</span>');
		$('#visitaUnicaReal').append('<span class=\'number count-to\' data-from=\'0\' data-to=\'".$visita_u."\' data-speed=\'1000\' data-fresh-interval=\'20\'>".$visita_u."</span>');
		$('#repdeAv').append('<span class=\'number count-to\' data-from=\'0\' data-to=\'".$porcentajeAv." \' data-speed=\'1000\' data-fresh-interval=\'20\'>".$porcentajeAv." %</span>');
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
					\"caption\": \"Cobertura vs Contactos\",
					\"subcaption\":\"".$fechaHoy."\",
					\"lowerLimit\": \"0\",
					
					\"lowerLimitDisplay\": \"0\",
					
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
	FusionCharts.ready(function () {
		var cSatScoreChart2 = new FusionCharts({
			type: 'angulargauge',
			renderAt: 'grafica11',
			width: '100%',
			height: '280',
			dataFormat: 'json',
			dataSource: {
				\"chart\": {
					\"caption\": \"Cobertura vs Contactos\",
					\"subcaption\":\"".$fechaHoy."\",
					\"lowerLimit\": \"0\",
					
					\"lowerLimitDisplay\": \"0\",
					
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
						\"value\": \"".$porcentajeD."\",
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
