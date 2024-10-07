<?php
include "../conexion.php";

if(isset($_POST['idRuta']) && $_POST['idRuta'] != ''){
	$ids = $_POST['idRuta'];
}else{
	$ids = $_POST['ids'];
}

$queryGrafica2 = "with chart3 as (
	select
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
and psw.user_snr in ('".$ids."')
)
	select
	case total when 3 then 'A' when 2 then 'B' when 1 then 'C' when 0 then 'D' end as frec,
	categ,
	count(*) regist,
	sum(vis_current) visit,
	sum(vis_unique_current) visit_unique,
	count(*)-sum(vis_unique_current) novis
	from chart3
	group by case total when 3 then 'A' when 2 then 'B' when 1 then 'C' when 0 then 'D' end,categ
order by frec, categ ";
//echo $queryGrafica1."<br>";
$rsGrafica2 = sqlsrv_query($conn, $queryGrafica2);
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

while($regGrafica2 = sqlsrv_fetch_array($rsGrafica2)){

if (!in_array($regGrafica2['categ'],$categ)) {
$categ[] = $regGrafica2['categ'];
$nombres .= '{"label": "'.$regGrafica2['categ'].'"},';
}
//echo $regGrafica1['frec']."<br>";
switch ($regGrafica2['frec']){
case "A":
	$valores_Frec_A .=  '{"value": "'.$regGrafica2['regist'].'"},';
	break;
case "B":
	$valores_Frec_B .=  '{"value": "'.$regGrafica2['regist'].'"},';
	break;
case "C":
	$valores_Frec_C .=  '{"value": "'.$regGrafica2['regist'].'"},';
	break;
case "D":
	$valores_Frec_D .=  '{"value": "'.$regGrafica2['regist'].'"},';
	break;
}


$categorias[] = $regGrafica2['categ'];
$registrados[] = $regGrafica2['regist'];
$visitados[] = $regGrafica2['visit_unique'];
$noVisitados[] = $regGrafica2['novis'];

/*$nombres2 .= '{"label": "'.$regGrafica1['categ'].'"},';
$registrados .= '{"value": "'.$regGrafica1['regist'].'"},';
$visitas .= '{"value": "'.$regGrafica1['visit'].'"},';*/

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
//echo "visi: ".$v{$categ[$i]}."<br>";
//echo "no: ".${$categ[$i]}."<br>";
}

/*echo "nombres : ".$nombres."<br>";
echo "visitados : ".$visitadosT."<br>";
echo "noVisitados : ".$noVisitadosT."<br>";*/
						
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

          \"theme\": \"fint\",
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
          \"exportFileName\":\"Gráfica Cobertura por Categoría\",

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
			  }
			}
		});

		//revenueChart.render();
	});
</script>";
//echo $queryGrafica2
?>