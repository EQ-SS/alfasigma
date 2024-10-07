<?php
include "../conexion.php";

if(isset($_POST['idRuta']) && $_POST['idRuta'] != ''){
	$ids = $_POST['idRuta'];
}else{
	$ids = $_POST['ids'];
}

if(isset($_POST['tipoUsuario']) && $_POST['tipoUsuario'] != ''){
	$tipoUsuario = $_POST['tipoUsuario'];
}

// Unix
setlocale(LC_TIME, 'es_MX.UTF-8');
// En windows
setlocale(LC_TIME, 'spanish');
$fechaHoy = date("d-m-Y");

$queryGrafica1 = "with chart3 as (
        select
		cat.sort_num,
        p.pers_snr,
        cat.name as categ,
        isnull(pcc.total,0) as vis_current,
        isnull(pc1.total,0) as vis_cycle1,
        isnull(pc2.total,0) as vis_cycle2,
        case when isnull(pcc.total,0)>0 then 1 else 0 end vis_unique_current,
        case when isnull(pcc.total,0)>0 then 1 else 0 end+case when isnull(pc1.total,0)>0 then 1 else 0 end+case when isnull(pc2.total,0)>0 then 1 else 0 end total
        from person p
        inner join perslocwork plw on plw.pers_snr=p.pers_snr
        inner join pers_srep_work psw on psw.pwork_snr=plw.pwork_snr
        inner join user_territ ut on ut.user_snr=psw.user_snr and ut.inst_snr=plw.inst_snr
        inner join inst i on i.inst_snr=ut.inst_snr
        left outer join person_cycle_current pcc on pcc.pers_snr=p.pers_snr
        left outer join PERSON_CYCLE_1 pc1 on pc1.pers_snr=p.pers_snr
        left outer join PERSON_CYCLE_2 pc2 on pc2.pers_snr=p.pers_snr
        left outer join codelist cat on cat.clist_snr=p.category_snr
        left outer join codelist status on status.clist_snr=p.status_snr
        where status.name='ACTIVO'
        and plw.rec_stat=0 and psw.rec_stat=0 and ut.rec_stat=0 and p.rec_stat=0 and i.rec_stat=0
		and psw.user_snr in ('".$ids."') ";
		if($tipoUsuario != 4){
			$queryGrafica1 .= "and p.spec_snr <> '4A7DC158-020D-4D08-9AC1-681B32BDB6F6' ";
		}
		$queryGrafica1 .= ")
        select
		sort_num,
        case total when 3 then 'A' when 2 then 'B' when 1 then 'C' when 0 then 'D' end as frec,
        categ,
        count(*) regist,
        sum(vis_current) visit,
        sum(vis_unique_current) visit_unique,
        count(*)-sum(vis_unique_current) novis
        from chart3
        group by sort_num, case total when 3 then 'A' when 2 then 'B' when 1 then 'C' when 0 then 'D' end,categ
		order by frec, sort_num, categ ";

	//echo "<script>$('#divTest').text('".$queryGrafica1."');</script>";
	$rsGrafica1 = sqlsrv_query($conn, $queryGrafica1);

	$categ = array();
	$valores_Frec_A = '';
	$valores_Frec_B = '';
	$valores_Frec_C = '';
	$valores_Frec_D = '';
	$nombres ="";
	
	$categorias = array();
	$registrados = array();
	$visitados = array();
	$noVisitados = array();
	
	while($regGrafica1 = sqlsrv_fetch_array($rsGrafica1)){
		
		if (!in_array($regGrafica1['categ'],$categ)) {
			$categ[] = $regGrafica1['categ'];
			$nombres .= '{"label": "'.$regGrafica1['categ'].'"},';
		}
		//echo $regGrafica1['frec']."<br>";
		switch ($regGrafica1['frec']){
			case "A":
				$valores_Frec_A .=  '{"value": "'.$regGrafica1['regist'].'"},';
				break;
			case "B":
				$valores_Frec_B .=  '{"value": "'.$regGrafica1['regist'].'"},';
				break;
			case "C":
				$valores_Frec_C .=  '{"value": "'.$regGrafica1['regist'].'"},';
				break;
			case "D":
				$valores_Frec_D .=  '{"value": "'.$regGrafica1['regist'].'"},';
				break;
		}
		
		
		$categorias[] = $regGrafica1['categ'];
		$registrados[] = $regGrafica1['regist'];
		$visitados[] = $regGrafica1['visit_unique'];
		$noVisitados[] = $regGrafica1['novis'];
	} 
	$nombres = substr($nombres, 0, -1);
	$valores_Frec_A = substr($valores_Frec_A, 0, -1);
	$valores_Frec_B = substr($valores_Frec_B, 0, -1);
	$valores_Frec_C = substr($valores_Frec_C, 0, -1);
	$valores_Frec_D = substr($valores_Frec_D, 0, -1);
	
	$chartCenterX = 200;
	$chartEndY = 280;
	
	$visitadosT = '';
	$noVisitadosT = '';
	
	/*print_r($categorias);echo "<br>";
	print_r($registrados);echo "<br>";
	print_r($visitados);echo "<br>";
	print_r($noVisitados);echo "<br>";*/
	
	for($i=0;$i<count($categ);$i++){
		$v{$categ[$i]} = 0;
		${$categ[$i]} = 0;
		for($j=0;$j<count($categorias);$j++){
			if($categ[$i] == $categorias[$j]){
				$v{$categ[$i]} += $visitados[$j];
				${$categ[$i]} += $noVisitados[$j];
			}
		}
		$visitadosT .= '{"value": "'.$v{$categ[$i]}.'"},';
		$noVisitadosT .= '{"value": "'.${$categ[$i]}.'"},';
	//	echo "visi: ".$v{$categ[$i]}."<br>";
	//	echo "no: ".${$categ[$i]}."<br>";
	}
	
	/*echo "nombres : ".$nombres."<br>";
	echo "visitados : ".$visitadosT."<br>";
	echo "noVisitados : ".$noVisitadosT."<br>";*/
	
	/*$nombres2 = substr($nombres2, 0, -1);
	$registrados = substr($registrados, 0, -1);
	$visitas = substr($visitas, 0, -1);*/
	
	/*$sumCat = array();
	for($i=0;$i<count($categ);$i++){
		for($j=0;$j<count($categorias);$j++){
			$sumCat[$categ[$i]] += $regist
		}
	}*/
	
	//echo $valores_Frec_A."<br>".$valores_Frec_B."<br>".$valores_Frec_C."<br>".$valores_Frec_D;
	
	/*echo '<script>
		$("#ficheroMedico").text("'.$regTotales['regist'].'");
		$("#visitaUnica").text("'.$regTotales['vis_unique'].'");
		$("#numeroContactos").text("'.$regTotales['goal'].'");
		$("#visitaTotal").text("'.$regTotales['vis_total'].'");
	</script>';*/
	
echo "<script type=\"text/javascript\">
	FusionCharts.ready(function () {
		var revenueChart = new FusionCharts({
			type: 'stackedbar2d',
			renderAt: 'grafica1',
			width: '100%',
			height: '450',
			dataFormat: 'json',
			dataSource: {
				\"chart\": {
					\"caption\": \"Distribución de Secuencia por Categoría (trimestral)\",
					\"subcaption\":\"".$fechaHoy."\",
					\"canvasbgColor\": \"#FFFFFF\",
					\"showCanvasBase\": \"0\",
					\"canvasbgAlpha\": \"0\",
					\"showCanvasBorder\":\"0\",
					\"userInteraction\":\"0\",
					\"canvasBgAngle\": \"0\",
					\"showCanvasBase\": \"0\",
					\"plotspacepercent\": \"50\",

					\"showToolBarButtonTooltext	\": \"0	\",

					\"xAxisName\": \"Categoria\",
					\"yAxisName\": \"Porcentaje\",
					\"showYAxisPercentValues\": \"0\",
				
					\"theme\": \"fusion\",
					\"palettecolors\":\"8BC34A,FFC107,F44336,00BCD4\",
					
					\"placeValuesInside\": \"1\",
					\"valueFontColor\": \"#ffffff\",
					\"valueFontSize\": \"15\",

					\"divLineColor\": \"#306DBD\",
					\"divLineAlpha\": \"60\",
					\"divLineDashed\": \"1\",

					\"plotHighlightEffect\": \"fadeout\",

					\"exportenabled\": \"1\",
					\"exportMode\": \"server\",
					\"exportFormats\": \"PNG=Descargar como PNG|PDF= Descargar como PDF\",
					\"exportFileName\":\"Grafica Secuencia por Categoria (trimestral)\",

					\"tickValueStep\": \"2\",
					\"showPercentInTooltip\": \"1\",
					\"showValues\" : \"1\",
					\"showPercentValues\" : \"0\",
					
					\"labelFontSize\": \"14\",
					\"baseFontSize\": \"13\",

					\"stack100Percent\": \"1\",

					\"legendIconScale\": \"1\", 
					\"legendPosition\": \"right\",
					\"legendBgColor\": \"FFFFFF\",
					\"legendShadow\": \"1\",
					\"legendBorderColor\": \"#666666\",
					\"legendBorderThickness\": \"1\",
					\"legendBorderAlpha\": \"30\",
					\"legendCaption\": \"Secuencia\",
					\"legendCaptionFontSize\": \"14\",
					\"legendItemFontSize\": \"13\",
					\"legendAllowDrag\": \"0\",
					\"showaboutmenuitem\":\"1\",
					\"chartBottomMargin\": \"30\"
				},
				\"categories\": [{
					\"category\": [
					".$nombres."
					]
				}],
				\"dataset\": [{
					\"seriesname\": \"A\",
						\"data\": [".$valores_Frec_A."]
				}, {
					\"seriesname\": \"B\",
						\"data\": [".$valores_Frec_B."]
				}, {
					\"seriesname\": \"C\",
						\"data\": [".$valores_Frec_C."]
				}, {
					\"seriesname\": \"D\",
						\"data\": [".$valores_Frec_D."]
				}]
			},
			'events': {
			  'renderComplete': function(e, a) {
				
				document.getElementById('lblGrafica1').style.display = 'block';

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
					'exportpdf': 'pdf',
					'exportpng': 'png'
				  };
				  if (e && e.sender && e.sender.exportChart) {
					e.sender.exportChart({
					  exportFormat: types[this.id]
					});
				  }
				};

				// Attach events
				addListener(document.getElementById('exportpdf'), 'click', exportFC);
				addListener(document.getElementById('exportpng'), 'click', exportFC);
				},
				'dataPlotClick': function(ev, props) {
					//console.log(props);

					
					var categoria = props.categoryLabel;
					var frecuencia = 0;
					
					if(props.datasetName == 'A'){
						frecuencia = 3;
						$('#headFrecCat').css('background-color', '#8bc34a');
					}
					if(props.datasetName == 'B'){
						frecuencia = 2;
						$('#headFrecCat').css('background-color', '#ffc107');
					}
					if(props.datasetName == 'C'){
						frecuencia = 1;
						$('#headFrecCat').css('background-color', '#f44336');
					}
					if(props.datasetName == 'D'){
						frecuencia = 0;
						$('#headFrecCat').css('background-color', '#00bcd4');
					}

					muestradatos();
					$('body,html').stop(true,true).animate({				
						scrollTop: $('#numMedFreCat').offset().top
					},1000);

					cargandoFrecCat();
					function muestradatos(){
						var idRuta = $('#sltRutas2').val();
						var ids = $('#hdnIds').val()+','+$('#hdnIdUser').val();

						$('#containerFreCat').show();
						$('#bodyTblFrecCat').load('ajax/listadoGrafica3.php',{idRuta:idRuta,ids:ids,categoria:categoria,frecuencia:frecuencia},function(){
							$('#containerFreCat').waitMe('hide');
						});
					}
				}
			}
		});
		revenueChart.render();
	});
	
	</script>";
	
echo "<script type=\"text/javascript\">
	FusionCharts.ready(function () {
		var revenueChart = new FusionCharts({
			type: 'stackedcolumn2d',
			renderAt: 'grafica2',
			width: '100%',
			height: '450',
			dataFormat: 'json',
			dataSource: {
				\"chart\": {
					  
          \"canvasbgColor\": \"#FFFFFF\",
					\"showCanvasBase\": \"0\",
					\"canvasbgAlpha\": \"0\",
					\"showCanvasBorder\":\"0\",
					\"userInteraction\":\"0\",
					\"canvasBgAngle\": \"0\",
          \"showCanvasBase\": \"0\",
          \"plotspacepercent\": \"70\",
          \"showToolBarButtonTooltext	\": \"0	\",
          
          \"xAxisName\": \"Categoria\",
          
          \"yAxisName\": \"Porcentaje\",
          \"showYAxisValues\": \"1\",

          \"theme\": \"fusion\",
          \"palettecolors\":\"#2196F3,#009688\",
          
          \"placeValuesInside\": \"1\",
					\"valueFontColor\": \"#ffffff\",
					\"valueFontSize\": \"15\",

          \"divLineColor\": \"#306DBD\",
					\"divLineAlpha\": \"60\",
					\"divLineDashed\": \"1\",

          \"plotHighlightEffect\": \"fadeout\",

          \"exportenabled\": \"1\",
          \"exportMode\": \"server\",
          \"exportFormats\": \"PNG=Descargar como PNG|PDF= Descargar como PDF\",
          \"exportFileName\":\"Gráfica de Categoría\",

          \"tickValueStep\": \"2\",
          \"showPercentInTooltip\": \"1\",
          \"showValues\" : \"1\",
          \"showPercentValues\" : \"0\",

          \"labelFontSize\": \"14\",
					\"baseFontSize\": \"13\",

          \"stack100Percent\": \"1\",

          \"legendIconScale\": \"1\",
          \"legendPosition\": \"bottom\",
          \"legendBgColor\": \"FFFFFF\",
					\"legendShadow\": \"1\",
					\"legendBorderColor\": \"#666666\",
					\"legendBorderThickness\": \"1\",
					\"legendBorderAlpha\": \"30\",
					\"legendCaption\": \"Médicos\",
					\"legendCaptionFontSize\": \"14\",
					\"legendItemFontSize\": \"13\",
					\"legendAllowDrag\": \"0\"
				},


				\"categories\": [{
					\"category\": [".$nombres."]
				}],
				\"dataset\": [{
					\"seriesname\": \"Visitados\",
						\"data\": [".$visitadosT."]
				}, {
					\"seriesname\": \"No visitados\",
						\"data\": [".$noVisitadosT."]
				}]
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
					'exportpdf2': 'pdf',
					'exportpng2': 'png'
				  };
				  if (e && e.sender && e.sender.exportChart) {
					e.sender.exportChart({
					  exportFormat: types[this.id]
					});
				  }
				};

				// Attach events
				addListener(document.getElementById('exportpdf2'), 'click', exportFC);
				addListener(document.getElementById('exportpng2'), 'click', exportFC);
			  },
				'dataPlotClick': function(ev, props) {
					//console.log(props);

					var categoria = props.categoryLabel;
					var esVisitado = 0;
					
					if(props.datasetName == 'Visitados'){
						esVisitado = 1;
						$('#headCobCat').css('background-color', '#2196f3');
					}
					if(props.datasetName == 'No visitados'){
						esVisitado = 0;
						$('#headCobCat').css('background-color', '#009688');
					}

					muestradatos();
					$('body,html').stop(true,true).animate({				
						scrollTop: $('#numMedCobCat').offset().top
					},1000);
					cargandoCobCat();

					function muestradatos(){
						var idRuta = $('#sltRutas2').val();
						var ids = $('#hdnIds').val()+','+$('#hdnIdUser').val();

						$('#containerCobCat').show();
						$('#bodyTblCobCat').load('ajax/listadoGrafica4.php',{idRuta:idRuta,ids:ids,categoria:categoria,esVisitado:esVisitado},function(){
							$('#containerCobCat').waitMe('hide');
						});
					}
				}
			}
		});

		revenueChart.render();
	});
</script>";
?>