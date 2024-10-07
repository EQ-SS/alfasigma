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
	$queryGrafica5 = "select 999 as sort_num, 
		isnull(spec.name_short,'OTRAS') as ESP, 
		(select count(*) from person p inner join pers_srep_work psw on psw.pers_snr=p.pers_snr 
		inner join perslocwork plw on plw.pers_snr=p.pers_snr 
		inner join user_territ ut on ut.user_snr=psw.user_snr 
		and ut.inst_snr=plw.inst_snr inner join inst i on i.inst_snr=ut.inst_snr 
		left outer join codelist status on status.clist_snr=p.status_snr where p.rec_stat=0 
		and status.name='ACTIVO' and p.spec_snr=spec.clist_snr and psw.rec_stat=0 
		and plw.rec_stat=0 and ut.rec_stat=0 and i.rec_stat=0 
		and psw.user_snr in ('".$ids."') ) as regist, 
		(select count(*) from person_cycle_current pcc 
		inner join person p on p.pers_snr=pcc.pers_snr 
		inner join pers_srep_work psw on psw.pers_snr=p.pers_snr 
		inner join perslocwork plw on plw.pers_snr=p.pers_snr 
		inner join user_territ ut on ut.user_snr=psw.user_snr and ut.inst_snr=plw.inst_snr 
		inner join inst i on i.inst_snr=ut.inst_snr 
		left outer join codelist status on status.clist_snr=p.status_snr 
		where p.rec_stat=0 and status.name='ACTIVO' and p.spec_snr=spec.clist_snr 
		and psw.rec_stat=0 and plw.rec_stat=0 and ut.rec_stat=0 and i.rec_stat=0 
		and psw.user_snr in ('".$ids."') ) as VISITADOS 
		from codelist spec 
		--left outer join compline_specs spec_target on spec_target.spec_snr=spec.clist_snr
		where spec.clib_snr in (select clib_snr from codelistlib where table_nr=19 and list_nr=1)
		and spec.rec_stat=0 and spec.status=1 order by esp ";
		
	/*$queryGrafica5 = "select isnull(spec_target.sort_num,999) as sort_num, 
		isnull(spec.name_short,'OTRAS') as ESP,
		(select count(*) from person p inner join pers_srep_work psw on psw.pers_snr=p.pers_snr 
		inner join perslocwork plw on plw.pers_snr=p.pers_snr 
		inner join user_territ ut on ut.user_snr=psw.user_snr and ut.inst_snr=plw.inst_snr 
		inner join inst i on i.inst_snr=ut.inst_snr 
		left outer join codelist status on status.clist_snr=p.status_snr 
		where p.rec_stat=0 and status.name='ACTIVO' and p.spec_snr=spec.clist_snr 
		and psw.rec_stat=0 and plw.rec_stat=0 and ut.rec_stat=0 
		and i.rec_stat=0 and psw.user_snr in ('".$ids."') ) as regist, 
		(select count(*) from person_cycle_current pcc 
		inner join person p on p.pers_snr=pcc.pers_snr 
		inner join pers_srep_work psw on psw.pers_snr=p.pers_snr 
		inner join perslocwork plw on plw.pers_snr=p.pers_snr 
		inner join user_territ ut on ut.user_snr=psw.user_snr and ut.inst_snr=plw.inst_snr 
		inner join inst i on i.inst_snr=ut.inst_snr 
		left outer join codelist status on status.clist_snr=p.status_snr where p.rec_stat=0 
		and status.name='ACTIVO' and p.spec_snr=spec.clist_snr 
		and psw.rec_stat=0 and plw.rec_stat=0 and ut.rec_stat=0 and i.rec_stat=0 
		and psw.user_snr in ('".$ids."') ) as VISITADOS 
		from codelist spec 
		--left outer join compline_specs spec_target on spec_target.spec_snr=spec.clist_snr 
		where spec.clib_snr in (select clib_snr from codelistlib where table_nr=19 and list_nr=1) 
		and spec.rec_stat=0 and spec.status=1 
		order by sort_num ";*/

		
		//echo $queryGrafica5."<br>";
		
		$rsGrafica5 = sqlsrv_query($conn, $queryGrafica5);
		$datos = '';
		$datos2 = '';
		$registrados = $especialidad = $visitados  = array();
		while($regGrafica5 = sqlsrv_fetch_array($rsGrafica5)){		
			$datos .= '{"label": "'.$regGrafica5['ESP'].'","value": "'.$regGrafica5['regist'].'"},';
			$especialidad[] = $regGrafica5['ESP'];
			$registrados[] = $regGrafica5['regist'];
			$visitados[] = $regGrafica5['VISITADOS'];
		}
		$datos = substr($datos, 0, -1);
		
		$totalRegistrados = array_sum($registrados);
		$totalVisitados = array_sum($visitados);
		for($i=0;$i<count($especialidad);$i++){
			if($registrados[$i] > 0){
				$por = ($visitados[$i]/$registrados[$i])*100;
			}else{
				$por = 0;
			}
			$datos2 .= '{"label": "'.$especialidad[$i].'", "value": "'.$por.'","tooltext": "Visitados, '.$visitados[$i].'"},';
			//'{"label": "'.$regGrafica5['ESP'].'","value": "'.$regGrafica5['regist'].'"},';
		}
		if($totalRegistrados > 0){
			$porTotal = ($totalVisitados/$totalRegistrados)*100;
		}else{
			$porTotal = 0;
		}
		$datos2 .= '{"label": "TOTAL", "value": "'.$porTotal.'","tooltext": "Visitados, '.$totalVisitados.'"}';
		//echo $datos2;
		echo "<script type=\"text/javascript\">
		FusionCharts.ready(function () {
			var ageGroupChart = new FusionCharts({
				type: 'pie2d',
				renderAt: 'grafica5',
				width: '100%',
				height: '450',
				dataFormat: 'json',
				dataSource: {
					\"chart\": {
						
					\"paletteColors\": \"#4CAF50,#FFC107,#FF5722,#03A9F4,#FF9800,#3F51B5,#E91E63,#009688,#795548\",
					\"bgColor\": \"#ffffff\",
					\"showBorder\": \"0\",
					\"showLabels\": \"0\",
					\"theme\": \"fusion\",

					\"showShadow\": \"1\",
					\"drawCustomLegendIcon\": \"1\",

					\"smartLineColor\": \"#ff0000\",
					\"smartLineThickness\": \"1\",
					\"smartLineAlpha\": \"100\",
					\"isSmartLineSlanted\": \"1\",
					\"labelDistance\": \"15\",
					\"labelFontSize\": \"14\",

					\"showPercentValues\": \"0\",
					\"baseFontSize\": \"10\",
					\"startingAngle\": \"310\",
					\"enableSlicing\": \"1\",
					\"showValue\": \"1\",
					\"showTooltip\": \"1\",
					\"decimals\": \"0\",

					\"showToolBarButtonTooltext\": \"0\",
					\"exportenabled\":\"1\",
					\"exportMode\": \"server\",
					\"exportFormats\": \"PNG=Descargar como PNG|PDF= Descargar como PDF\",
					\"exportFileName\":\"Distribución de Fichero Médico\",

					\"legendIconSides\": \"2\",
					\"legendIconBorderThickness\": \"0\",
					\"showLegend\": \"1\",
					\"legendPosition\": \"right\",
					\"legendIconScale\": \"1\",
					\"legendBgColor\": \"FFFFFF\",
					\"legendShadow\": \"0\",
					\"legendBorderColor\": \"#666666\",
					\"legendBorderThickness\": \"1\",
					\"legendBorderAlpha\": \"30\",
					\"legendCaption\": \"Especialidad\",
					\"legendCaptionFontSize\": \"14\",
					\"legendItemFontSize\": \"13\",
					},
					\"data\": [".$datos."]
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
							'exportpdf5': 'pdf',
							'exportpng5': 'png'
							};
							if (e && e.sender && e.sender.exportChart) {
							e.sender.exportChart({
								exportFormat: types[this.id]
							});
							}
						};
		
						// Attach events
						addListener(document.getElementById('exportpdf5'), 'click', exportFC);
						addListener(document.getElementById('exportpng5'), 'click', exportFC);
				  },
					'dataPlotClick': function(ev, props) {
						//console.log(props);

						var especialidad = props.categoryLabel;

						if(props.categoryLabel == 'GASTRO'){
							$('#headDisFichero').css('background-color', '#4caf50');
						}
						if(props.categoryLabel == 'GASTRO HE'){
							$('#headDisFichero').css('background-color', '#ffc107');
						}
						if(props.categoryLabel == 'GASTRO UC'){
							$('#headDisFichero').css('background-color', '#ff5722');
						}
						if(props.categoryLabel == 'MG'){
							$('#headDisFichero').css('background-color', '#03a9f4');
						}
						if(props.categoryLabel == 'MI'){
							$('#headDisFichero').css('background-color', '#ff9800');
						}
						if(props.categoryLabel == 'PEDIA'){
							$('#headDisFichero').css('background-color', '#3f51b5');
						}
						if(props.categoryLabel == 'ANGIO'){
							$('#headDisFichero').css('background-color', '#e91e63');
						}
						if(props.categoryLabel == 'CIRUG'){
							$('#headDisFichero').css('background-color', '#009688');
						}
						if(props.categoryLabel == 'FLEBO'){
							$('#headDisFichero').css('background-color', '#795548');
						}

						muestradatos();
						$('body,html').stop(true,true).animate({				
							scrollTop: $('#numMedDisFichero').offset().top
						},1000);
						cargandoDisFichero()
	
						function muestradatos(){
							var idRuta = $('#sltRutas2').val();
							var ids = $('#hdnIds').val()+','+$('#hdnIdUser').val();
	
							$('#containerDisFichero').show();
							$('#bodyTblDisFichero').load('ajax/listadoGrafica5.php',{idRuta:idRuta,ids:ids,especialidad:especialidad},function(){
								$('#containerDisFichero').waitMe('hide');
							});
						}
					}
				}
			}).render();
		});
		
		FusionCharts.ready(function() {
         var appChart = new FusionCharts({
			   type: 'column2d',
			   width: '100%',
			   height: '450',
			   renderAt: 'grafica6',
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
						\"plotspacepercent\": \"5\",
						\"showToolBarButtonTooltext	\": \"0	\",

						\"xAxisName\": \"Especialidad\",
						\"yAxisName\": \"Porcentaje\",
						\"showYAxisValues\": \"1\",

						\"theme\": \"fusion\",
						\"palettecolors\":\"#4CAF50,#FFC107,#FF5722,#03A9F4,#FF9800,#3F51B5,#E91E63,#009688,#795548,#607D8B\",

						\"placeValuesInside\": \"0\",
						\"rotateValues\": \"0\",
						\"valueFontColor\": \"#000000\",
						\"valueFontSize\": \"14\",

						\"divLineColor\": \"#306DBD\",
						\"divLineAlpha\": \"60\",
						\"divLineDashed\": \"1\",

						\"plotHighlightEffect\": \"fadeout\",
					
						\"exportenabled\": \"1\",
						\"exportMode\": \"server\",
						\"exportFormats\": \"PNG=Descargar como PNG|PDF= Descargar como PDF\",
						\"exportFileName\":\"Gráfica Cobertura Visita vs Fichero Médico\",

						\"tickValueStep\": \"2\",
						\"showPercentInTooltip\": \"1\",
						\"showValues\" : \"1\",
						\"showPercentValues\" : \"0\",

						\"labelFontSize\": \"14\",
						\"baseFontSize\": \"13\",

						\"numberSuffix\": \"%\",
						\"stack100Percent\": \"0\",
					
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
					\"data\": [".$datos2."]
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
							'exportpdf6': 'pdf',
							'exportpng6': 'png'
						};
						if (e && e.sender && e.sender.exportChart) {
							e.sender.exportChart({
							exportFormat: types[this.id]
							});
						}
						};
		
						// Attach events
						addListener(document.getElementById('exportpdf6'), 'click', exportFC);
						addListener(document.getElementById('exportpng6'), 'click', exportFC);
					},
					'dataPlotClick': function(ev, props) {
						//console.log(props);

						var especialidad = props.categoryLabel;

						if(props.categoryLabel == 'GASTRO'){
							$('#headCobFichero').css('background-color', '#4caf50');
						}
						if(props.categoryLabel == 'GASTRO HE'){
							$('#headCobFichero').css('background-color', '#ffc107');
						}
						if(props.categoryLabel == 'GASTRO UC'){
							$('#headCobFichero').css('background-color', '#ff5722');
						}
						if(props.categoryLabel == 'MG'){
							$('#headCobFichero').css('background-color', '#03a9f4');
						}
						if(props.categoryLabel == 'MI'){
							$('#headCobFichero').css('background-color', '#ff9800');
						}
						if(props.categoryLabel == 'PEDIA'){
							$('#headCobFichero').css('background-color', '#3f51b5');
						}
						if(props.categoryLabel == 'ANGIO'){
							$('#headCobFichero').css('background-color', '#e91e63');
						}
						if(props.categoryLabel == 'CIRUG'){
							$('#headCobFichero').css('background-color', '#009688');
						}
						if(props.categoryLabel == 'FLEBO'){
							$('#headCobFichero').css('background-color', '#795548');
						}
						if(props.categoryLabel == 'TOTAL'){
							$('#headCobFichero').css('background-color', '#607d8b');
						}

						muestradatos();
						$('body,html').stop(true,true).animate({				
							scrollTop: $('#numMedCobFichero').offset().top
						},1000);
						cargandoCobFichero();
	
						function muestradatos(){
							var idRuta = $('#sltRutas2').val();
							var ids = $('#hdnIds').val()+','+$('#hdnIdUser').val();
	
							$('#containerCobFichero').show();
							$('#bodyTblCobFichero').load('ajax/listadoGrafica6.php',{idRuta:idRuta,ids:ids,especialidad:especialidad},function(){
								$('#containerCobFichero').waitMe('hide');
							});
						}
					}
			   }
		   }).render();
	   });
	</script>";
?>