<style>
	#table_listado th{
		background-color:#337ab7;
		color:white;
	}

	#table_listado>tbody>tr>td{
		vertical-align:middle;
	}
</style>

<input type="hidden" id="hdnQuery">
<input type="hidden" id="hdnTitulos">
<input type="hidden" id="hdnNombreListado">

<?php
	include "../conexion.php";
	
	$ids = (substr($_POST['hdnIDSListado'], -1) == ',') ? str_replace(",","','",trim( $_POST['hdnIDSListado'], ",")) : $_POST['hdnIDSListado'] ;
	$tipo = $_POST['hdnTipoListado'];
	if(isset($_POST['pagina']) && $_POST['pagina'] != ''){
		$numPagina = $_POST['pagina'];
	}else{
		$numPagina = 1;
	}
	
	$fechaI = $_POST['hdnFechaIListado'];
	$fechaF = $_POST['hdnFechaFListado'];

	$nombreListado="Listado_Ayudas_Visuales";
	
	$qMedicos = "Select 
	cl.name as Linea, 
	Ugte.user_nr as Gte,
	U.user_nr as Ruta,
	upper(U.lname)+' '+upper(U.fname) as Representante,
	'{'+cast(P.pers_snr as varchar(36))+'}' as Codigo_Med, 
	isnull(upper(P.lname),'') as Paterno,
	isnull(upper(P.mothers_lname),'') as Materno,
	isnull(upper(P.fname),'') as Nombre,
	/*upper(I.street1) as Calle,
	I.num_ext as Num_Ext, 
	plw.num_int as Num_Int, 
	City.name as Colonia,
	City.zip as CPostal,
	IMS.name as Brick, 
	Dst.name as Poblacion,
	State.name as Estado,
	ESP.name as Esp,
	CAT.name as Categoria, */
	(select name from CYCLES where Hist.CREATION_TIMESTAMP between START_DATE and FINISH_DATE) as Ciclo,
	upper(AV.display_name) as Nombre_av,
	convert(varchar(10), Hist.clm_date, 120) as Fecha,
	convert(char(5), Hist.time, 108) as Hora,
	upper(Slide.display_name) as Nombre_slide,
	SlideHist.time_used as Segundos
	
	from person P 
	inner join pers_srep_work PSW on PSW.pers_snr = P.pers_snr
	inner join perslocwork PLW on PSW.pwork_snr = PLW.pwork_snr
	inner join inst I on I.inst_snr = PSW.inst_snr 
	inner join user_Territ UT on PSW.user_snr = UT.user_snr and I.inst_snr = UT.inst_snr
	inner join Users U on U.user_snr = PSW.user_snr
	inner join compline cl on U.cline_snr = cl.cline_snr
	inner join kloc_reg repre on repre.kloc_snr = U.user_snr
	inner join users Ugte on Ugte.user_snr = repre.reg_snr and Ugte.user_type=5
	/*left outer join City on City.city_snr = I.city_snr
	inner join District Dst on city.distr_snr = Dst.distr_snr
	inner join State on Dst.state_snr = State.state_snr
	left outer join Brick IMS on IMS.brick_snr = City.brick_snr
	left outer join codelist ESP on P.spec_snr = ESP.clist_snr
	left outer join codelist CAT on P.category_snr = CAT.clist_snr */
	inner join CLM_HISTORY as Hist on P.pers_snr = Hist.pers_snr and U.user_snr = Hist.user_snr
	inner join CLM as AV on AV.clm_snr = Hist.clm_snr
	inner join CLM_SLIDE_HISTORY as SlideHist on SlideHist.clm_snr = AV.clm_snr and SlideHist.clm_history_snr = Hist.clm_history_snr
	inner join CLM_SLIDE as Slide on Slide.clm_snr = AV.clm_snr and Slide.slide_snr = SlideHist.slide_snr
	
	where P.pers_snr <> '00000000-0000-0000-0000-000000000000'
	and P.rec_stat=0
	and PLW.rec_stat=0
	and PSW.rec_Stat=0
	and UT.rec_stat=0 
	and U.rec_stat=0
	and U.status=1
	and U.user_type=4 
	and repre.rec_stat=0
	and Ugte.rec_stat=0
	and Hist.rec_stat=0
	and AV.rec_stat in (0,2)
	and U.user_snr in ('".$ids."') 
	and convert(date,Hist.CREATION_TIMESTAMP,101) between '".$fechaI."' and '".$fechaF."'
	/*and convert(date,Hist.CREATION_TIMESTAMP,101) between '2023-03-01' and '2023-05-30'*/
	
	order by U.user_nr,U.lname,U.fname,P.lname,P.mothers_lname,P.fname,AV.display_name,Hist.clm_date desc,Slide.display_name ";
		
	$qMedicos2 = addslashes($qMedicos);
	$qMedicos2 = str_replace(array("\r", "\n","\r\n",""), " ", $qMedicos2);
		
	$rsQueryExport = sqlsrv_query($conn, utf8_decode($qMedicos), array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

	$tabla="";
	$tabla="<table id=\"table_listado\" class=\"table table-bordered table-hover tabled-striped  nowrap\" style=\"width:100%\" >
				<thead><tr>";

	$titulo=array();
	$j=0;
	foreach(sqlsrv_field_metadata($rsQueryExport) as $field){
		$tabla.="<th>".$field['Name']."</th>";
		$titulo[$j]=$field['Name'];
		$j++;
	}
	$titulos=implode(",", $titulo);
	$tabla.="</tr></thead><tbody></tbody></table>";

	echo "<div id=\"divListado\" style=\"border:0px\">";
	echo $tabla;
	echo "</div>";
	echo "<script>
    
    $(document).ready(function () {
		queryListado=$('#hdnQuery').val('".$qMedicos2."');
		titulos=$('#hdnTitulos').val('".$titulos."');
		$('#hdnNombreListado').val('".$nombreListado."');
		
		queryListado=$('#hdnQuery').val();
		
        table= $(\"#table_listado\").DataTable();
        table.destroy();
		
        $(\"#table_listado\").DataTable({
			processing: true,
			serverSide: true,
  			ajax:{
              url:\"ajax/consultaListado.php\",
              data:{queryListado:queryListado},
              type:\"post\"
          },\"dom\": \"Birtl\",
          \"lengthMenu\": [[10, 25, 50], [10, 25, 50]],
		
          \"buttons\": [
                  {
                      \"extend\": 'excel',
                    //  \"text\": '<button class=\"btn\"><i class=\"fa fa-file-excel-o\" style=\"color: green;\"></i>  Excel</button>',
                      \"text\": '<i class=\"fa fa-file-excel-o\" style=\"color: green;\"> <span >Excel</span></i>   ',
                      \"titleAttr\": 'Excel',
                      \"action\":expExcel
                  },
				  {
                    \"extend\": 'csv',
                  //  \"text\": '<button class=\"btn\"><i class=\"fa fa-file-csv-o\" style=\"color: green;\"></i>  CSV</button>',
                    \"text\": '<i class=\"fa fa-file-text-o\" style=\"color: green;\"> <span >CSV</span></i>   ',
                    \"titleAttr\": 'CSV',
                    \"action\":expCsv
                },
              ] ,
              'language': {
                'url': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json'
            },
            columnDefs:[{
                targets: \"_all\",
                sortable: false
            }],
});

$('#btnExportarListadoExcelPlano').hide();
$('#btnExportarListadoExcel').hide();
$('#btnExportarListadoPDF').hide();

function expExcel(){
    alertListados('LISTADO', 'Generando listado, esto puede tardar unos minutos dependiendo de su conexion a internet.', 'info');
	
	queryListado=$('#hdnQuery').val();
	titulos=$('#hdnTitulos').val();
	nombreListado=$('#hdnNombreListado').val();
	
	// crea un nuevo objeto `Date`
	var today = new Date();
	
	// obtener la fecha y la hora
	var now = today.toISOString();
	
    var form = $(document.createElement('form'));
    $(form).attr('action', 'ajax/listadoExcel.php');
    $(form).attr('method', 'POST');
	$(form).attr('id', 'form1');
    $(form).css('display', 'none');
	
	var input_queryListado = $('<input>')
    .attr('type', 'text')
    .attr('name', 'query')
    .val(queryListado);
	
	var input_titulos = $('<input>')
    .attr('type', 'text')
    .attr('name', 'titulos')
    .val(titulos);
	
	var input_nombreListado = $('<input>')
    .attr('type', 'text')
    .attr('name', 'nombreListado')
    .val(nombreListado);
	
	$(form).append($(input_queryListado));
	$(form).append($(input_titulos));
	$(form).append($(input_nombreListado));
    form.appendTo( document.body );
	
    $(form).submit();
}

function expCsv(){
    alertListados('LISTADO', 'Generando listado, esto puede tardar unos minutos dependiendo de su conexion a internet.', 'info');
	queryListado=$('#hdnQuery').val();
	titulos=$('#hdnTitulos').val();
	nombreListado=$('#hdnNombreListado').val();
	
    var form = $(document.createElement('form'));
    $(form).attr('action', 'ajax/listadoCsv.php');
    $(form).attr('method', 'POST');
    $(form).css('display', 'none');
	
	var input_queryListado = $('<input>')
    .attr('type', 'text')
    .attr('name', 'query')
    .val(queryListado);
	
	var input_titulos = $('<input>')
    .attr('type', 'text')
    .attr('name', 'titulos')
    .val(titulos);
	
	var input_nombreListado = $('<input>')
    .attr('type', 'text')
    .attr('name', 'nombreListado')
    .val(nombreListado);
	
	$(form).append($(input_queryListado));
	$(form).append($(input_titulos));
	$(form).append($(input_nombreListado));
	
    form.appendTo( document.body );
    $(form).submit();
}
});</script>";

?>
<style>
	#divListado{
		overflow:scroll;
		width:1330px;
	}
</style>

<script>
	$('#table_listado').DataTable().on("draw", function(){
		$('#divCargando').hide();
	});
</script>
