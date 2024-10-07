<?php

	include "../conexion.php";

	if(isset($_POST['idRuta']) && $_POST['idRuta'] != ''){
		$ids = $_POST['idRuta'];
	}else{
		$ids = $_POST['ids'];
	}
		
	$queryGrafica6 = "with chart2 as (select
        p.pers_snr,
        case when cat_audit.name = ' ' then 'NC' else isnull(cat_audit.name,'NC') end as categ,
        case when isnull(pcc.total,0)>0 then 1 else 0 end as unique_visit
        from person p
        inner join perslocwork plw on plw.pers_snr=p.pers_snr
        inner join pers_srep_work psw on psw.pwork_snr=plw.pwork_snr
        inner join user_territ ut on ut.user_snr=psw.user_snr and ut.inst_snr=plw.inst_snr
        inner join inst i on i.inst_snr=ut.inst_snr
        left outer join person_cycle_current pcc on pcc.pers_snr=p.pers_snr
        left outer join person_ud pu on p.pers_snr=pu.pers_snr
        left outer join codelist cat_audit on cat_audit.clist_snr=pu.field_12_snr
        left outer join codelist status on status.clist_snr=p.status_snr
        where status.name='ACTIVO'
        and plw.rec_stat=0 and psw.rec_stat=0 and ut.rec_stat=0 and p.rec_stat=0 and i.rec_stat=0
		and psw.USER_SNR in ('".$ids."'))
        select
        categ,
        count(*) regist,
        sum(unique_visit) unique_visit
        from chart2
        group by categ";
		
		//echo $queryGrafica6."<br>";
		$rsGrafica6 = sqlsrv_query($conn, $queryGrafica6);
    $datos = '';
    $datos2 = '';
		$especialidad = array();
		$registrados = array();
		$visitados = array();
		$totalRegistrados = 0;
		while($regGrafica6 = sqlsrv_fetch_array($rsGrafica6)){
			$registrados[$regGrafica6['categ']] = $regGrafica6['regist'];
			$totalRegistrados += $regGrafica6['regist'];
			//$registrados[] = $regGrafica6['regist'];
			$visitados[] = $regGrafica6['unique_visit'];
			$categorias[] = $regGrafica6['categ'];
		}
		
		/*print_r($categorias);
		print_r($visitados);
		print_r($registrados);*/
		
    $totalRegistrados2 = array_sum($registrados);
		$totalVisitados = array_sum($visitados);
		$datos2 = '';
		//$porTotal = 0;
		for($i=0;$i<count($categorias);$i++){
			if($registrados[$categorias[$i]] > 0){
				$por = ($visitados[$i] / $registrados[$categorias[$i]])*100;
				//$porTotal += $por;
			}else{
				$por = 0;
			}
			if($categorias[$i] == 'NC'){
				$datos2 .= '{"label": "NC", "value": "'.round($por, 0).'","tooltext": "Visitados, '.$visitados[$i].'"},';
			}else{
				$datos2 .= '{"label": "Quintil '.$categorias[$i].'", "value": "'.round($por, 0).'","tooltext": "Visitados, '.$visitados[$i].'"},';
			}
		}
		if($totalRegistrados2 > 0){
			$porTotal = ($totalVisitados/$totalRegistrados2)*100;
		}else{
			$porTotal = 0;
		}
		$datos2 .= '{"label": "TOTAL", "value": "'.round($porTotal, 0).'","tooltext": "Visitados, '.$totalVisitados.'"}';
		
		echo '<script type="text/javascript">
		FusionCharts.ready(function() {
               var appChart = new FusionCharts({
                   type: \'doughnut2d\',
                   width: \'100%\',
                   height: \'450\',
                   renderAt: \'grafica7\',
                   dataFormat: \'json\',
                   dataSource: {
                       "chart": {
                           
                           "paletteColors": "#4CAF50,#FFC107,#FF5722,#2196F3,#795548,#673AB7",
                           "bgColor": "#ffffff",
                           "bgAlpha": "100",
                           "canvasBgAlpha": "0",
                           "showBorder": "0",
                           "theme": "fusion",
                           "showLegend": "1",
                           "use3DLighting": "0",
                           "enableSlicing": "1",
                           "showShadow": "1",
                           "drawCustomLegendIcon": "1",

                           "showLabels": "0",
                           "smartLineColor": "#ff0000",
                          "smartLineThickness": "1",
                          "smartLineAlpha": "100",
                          "isSmartLineSlanted": "1",
                          "labelDistance": "15",
                          "labelFontSize": "14",

                           "exportenabled": "1",
                            "exportMode": "server",
                            "exportFormats": "PNG=Descargar como PNG|PDF= Descargar como PDF",
                            "exportFileName":"Grafica Distribución de Fichero por Quintil",
                           
                           "showPercentValues": "1",
                           "legendIconSides": "2",
                           "legendIconBorderThickness": "0",
                           "defaultCenterLabel": "Total: '.$totalRegistrados.'",
                           "centerLabel": "Categ. $label: $value",
                           "centerLabelBold": "1",
                           "centerLabelFontSize": "18",

                           "baseFontSize": "15",
                           "startingAngle": "100",

                           "baseFontColor": "#000000",
                           "showTooltip": "1",
                           "valueFontColor": "#000000",
                           "valueFontBold": "1",
                           "valueFontSize": "14",
                           "showPlotBorder": "1",

                           "legendIconScale": "2",
                           "legendPosition": "right",
                          "legendBgColor": "FFFFFF",
                          "legendShadow": "0",
                          "legendBorderColor": "#666666",
                          "legendBorderThickness": "1",
                          "legendBorderAlpha": "30",
                          "legendCaption": "Categoría",
                          "legendCaptionFontSize": "14",
                          "legendItemFontSize": "13"
                       },
                       "data": [
                            {
                                "label": "1",
                                "value": "'.$registrados['1'].'"
                            },
                            {
                                "label": "2",
                                "value": "'.$registrados['2'].'"
                            },
                            {
                                "label": "3",
                                "value": "'.$registrados['3'].'"
                            },
                            {
                                "label": "4",
                                "value": "'.$registrados['4'].'"
                            },
                            {
                                "label": "5",
                                "value": "'.$registrados['5'].'"
                            },
                            {
                                "label": "NC",
                                "value": "'.$registrados['NC'].'"
                            }

                        ]
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
                           "exportpdf7": "pdf",
                           "exportpng7": "png"
                         };
                         if (e && e.sender && e.sender.exportChart) {
                           e.sender.exportChart({
                             exportFormat: types[this.id]
                           });
                         }
                       };
       
                       // Attach events
                       addListener(document.getElementById("exportpdf7"), "click", exportFC);
                       addListener(document.getElementById("exportpng7"), "click", exportFC);
                     },
                      dataPlotClick: function(ev, props) {
                       //console.log(props);
           
                       var quintil = props.categoryLabel;
           
                       if(props.categoryLabel == "1"){
                         $("#headDisQuintil").css("background-color", "#4caf50");
                       }
                       if(props.categoryLabel == "2"){
                        $("#headDisQuintil").css("background-color", "#ffc107");
                      }
                      if(props.categoryLabel == "3"){
                        $("#headDisQuintil").css("background-color", "#ff5722");
                      }
                      if(props.categoryLabel == "4"){
                        $("#headDisQuintil").css("background-color", "#2196f3");
                      }
                      if(props.categoryLabel == "5"){
                        $("#headDisQuintil").css("background-color", "#795548");
                      }
                      if(props.categoryLabel == "NC"){
                        $("#headDisQuintil").css("background-color", "#673ab7");
                      }
           
                       muestradatos();
                       $("body,html").stop(true,true).animate({				
                        scrollTop: $("#numMedDisQuintil").offset().top
                      },1000);
                       cargandoDisQuintil();
             
                       function muestradatos(){
                         var idRuta = $("#sltRutas2").val();
                         var ids = $("#hdnIds").val()+","+$("#hdnIdUser").val();
             
                         $("#containerDisQuintil").show();
                         $("#bodyTblDisQuintil").load("ajax/listadoGrafica7.php",{idRuta:idRuta,ids:ids,quintil:quintil},function(){
                          $("#containerDisQuintil").waitMe("hide");
                        });
                       }
                     }
                   }
               }).render();
           });
		   
		FusionCharts.ready(function() {
               var appChart = new FusionCharts({
                   type: \'column2d\',
                   width: \'100%\',
                   height: \'450\',
                   renderAt: \'grafica8\',
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
                          "plotspacepercent": "20",
                          "showToolBarButtonTooltext":"0",

                            "xAxisName": "Quintiles",
                            "yAxisName": "Porcentaje",
                            "showYAxisValues": "1",

                            "theme": "fusion",
                            "palettecolors":"#4CAF50,#FFC107,#FF5722,#2196F3,#795548,#673AB7,#6b838e",

                            "exportenabled": "1",
                            "exportMode": "server",
                            "exportFormats": "PNG=Descargar como PNG|PDF= Descargar como PDF",
                            "exportFileName":"Grafica Cobertura de Visita por Quintil",

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
                            "legendItemFontSize": "13"
                        },
                        "data": [
							'.$datos2.'
						]
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
                           "exportpdf8": "pdf",
                           "exportpng8": "png"
                         };
                         if (e && e.sender && e.sender.exportChart) {
                           e.sender.exportChart({
                             exportFormat: types[this.id]
                           });
                         }
                       };
       
                       // Attach events
                       addListener(document.getElementById("exportpdf8"), "click", exportFC);
                       addListener(document.getElementById("exportpng8"), "click", exportFC);
                     },
                     dataPlotClick: function(ev, props) {
                      //console.log(props);
          
                      var quintil = "";
          
                      if(props.categoryLabel == "Quintil 1"){
                        quintil = "1";
                        $("#headCobQuintil").css("background-color", "#4caf50");
                      }
                      if(props.categoryLabel == "Quintil 2"){
                        quintil = "2";
                        $("#headCobQuintil").css("background-color", "#ffc107");
                      }
                      if(props.categoryLabel == "Quintil 3"){
                        quintil = "3";
                        $("#headCobQuintil").css("background-color", "#ff5722");
                      }
                      if(props.categoryLabel == "Quintil 4"){
                        quintil = "4";
                        $("#headCobQuintil").css("background-color", "#2196f3");
                      }
                      if(props.categoryLabel == "Quintil 5"){
                        quintil = "5";
                        $("#headCobQuintil").css("background-color", "#795548");
                      }
                      if(props.categoryLabel == "NC"){
                        quintil = "NC";
                        $("#headCobQuintil").css("background-color", "#673ab7");
                      }
                      if(props.categoryLabel == "TOTAL"){
                        quintil = "TOTAL";
                        $("#headCobQuintil").css("background-color", "#6b838e");
                      }
          
                      muestradatos();
                      $("body,html").stop(true,true).animate({				
                        scrollTop: $("#numMedCobQuintil").offset().top
                      },1000);
                      cargandoCobQuintil();
            
                      function muestradatos(){
                        var idRuta = $("#sltRutas2").val();
                        var ids = $("#hdnIds").val()+","+$("#hdnIdUser").val();
            
                        $("#containerCobQuintil").show();
                        $("#bodyTblCobQuintil").load("ajax/listadoGrafica8.php",{idRuta:idRuta,ids:ids,quintil:quintil},function(){
                          $("#containerCobQuintil").waitMe("hide");
                        });
                      }
                    }
                   }
               }).render();
           });
		   
	</script>';
?>