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

	$nombreListado="Listado_Medicos_Visitados";
	
	$qMedicos = "Select /*DISTINCT*/
	/*vp.VISPERS_SNR,*/
	cl.name as Linea,
	Ugte.user_nr as Gte,
	U.user_nr as Ruta,
	upper(U.lname)+' '+upper(U.fname) as Representante,
	'{'+cast(P.pers_snr as varchar(36))+'}' as Codigo_Med,
	'{'+cast(I.inst_snr as varchar(36))+'}' as Codigo_Inst,
	upper(IT.name) as Tipo_Inst,
	upper(type.name) as Tipo_Pers,
	upper(P.lname) Paterno,
	upper(P.mothers_lname) Materno,
	upper(P.fname) as Nombre,
	upper(I.street1) as Direccion,
	I.num_ext as Num_Ext,
	PLW.num_int as Num_Int,
	City.name as Colonia,
	City.zip as CPostal,
	IMS.name as Brick,
	Dst.name as Ciudad,
	State.name as Estado,
	ST.name as Estatus,
	P.prof_id as Cedula,
	SEXO.name as Sexo,
	CAT.name as Categoria,
	FV.name as Frec_Vis,
	ESP.name as Esp,
	ESP2.name as Sub_Esp,
	HON.name as Honorarios,
	PT.name as PacsxSem,
	P.tel1 as Tel1,
	P.tel2 as Tel2,
	P.mobile as Celular,
	cast(cast(VP.visit_date as DATE) as varchar(10)) as Fecha_Visita,
	cast(VP.time as varchar(10)) as Hora_Visita,
	convert(varchar(16), VP.creation_timestamp, 120) as Fecha_Creacion,
	cast(cast(VP.CHANGED_TIMESTAMP as DATE) as varchar(10)) as 'Fecha Modificacion',
	cast(cast(VP.SYNC_TIMESTAMP as DATE) as varchar(10)) as 'Fecha Sincronizacion',
	/*VA1.name as Visita_Acompanada,*/
	(case when VA2.name <> NULL then VA1.name+' '+VA2.name else VA1.name end) as Visita_Acompanada,
	VisCode.name as Tipo_Visita,
	NoVis.name as Motivo_NoVisita,
	substring(VP.latitude,1,7) as Latitud_Vis,
	substring(VP.longitude,1,7) as Longitud_Vis,
	(case when VP.latitude = '0.0' then 'No Geolocalizado' else 'Geolocalizado' end) as Geo_Vis,
	PLW.Latitude as Latitud_Dir,
	PLW.Longitude as Longitud_Dir,
	(case when VPLAN.PLAN_DATE <> ' ' then 'SI' else 'NO' end) as Cumplio_Plan,
	cast(cast(VPLAN.PLAN_DATE as DATE) as varchar(10)) as Fecha_Plan,
	(case when (select COUNT(SlideHist.slide_snr) from CLM_HISTORY as Hist 
	inner join CLM_SLIDE_HISTORY as SlideHist on SlideHist.clm_snr = Hist.clm_snr and SlideHist.clm_history_snr = Hist.clm_history_snr
	where P.pers_snr = Hist.pers_snr and U.user_snr = Hist.user_snr and convert(varchar(10), VP.visit_date, 120) = convert(varchar(10), Hist.clm_date, 120) 
	and Hist.rec_stat=0 ) > 2 then 'SI' else 'NO' end) as Presento_clm,
	VP.info_nextvisit as 'Info Siguiente Visita',
	VP.info as 'Comentario Resultado Visita',
	P.cod_pers as cod_pers
	
	from visitpers VP
	inner join person P on VP.pers_snr = P.pers_snr
	inner join pers_srep_work PSW on PSW.pers_snr = P.pers_snr 
	inner join perslocwork PLW on PLW.pwork_snr = PSW.pwork_snr 
	inner join inst I on I.inst_snr = PLW.inst_snr
	inner join user_territ UT on PSW.user_snr = UT.user_snr and I.inst_snr = UT.inst_snr
	inner join users U on U.user_snr = VP.user_snr
	inner join compline cl on U.cline_snr = cl.cline_snr
	inner join kloc_reg repre on repre.kloc_snr = U.user_snr
	inner join users Ugte on Ugte.user_snr = repre.reg_snr and Ugte.user_type=5
	left outer join City on City.city_snr = I.city_snr
	inner join District Dst on city.distr_snr = Dst.distr_snr
	inner join State on Dst.state_snr = State.state_snr
	left outer join Brick IMS on IMS.brick_snr = City.brick_snr
	left outer join inst_Type IT on IT.inst_type = I.inst_type 
	left outer join codelist type on P.perstype_snr = type.clist_snr
	left outer join codelist ST on P.status_snr = ST.clist_snr
	left outer join codelist SEXO on P.sex_snr = SEXO.clist_snr
	left outer join codelist ESP on P.spec_snr = ESP.clist_snr
	left outer join codelist ESP2 on P.subspec_snr = ESP2.clist_snr
	left outer join codelist HON on P.fee_type_snr = HON.clist_snr
	left outer join codelist PT on P.patperweek_snr = PT.clist_snr
	left outer join codelist FV on P.frecvis_snr = FV.clist_snr
	left outer join codelist CAT on P.category_snr = CAT.clist_snr 
	left outer join codelist VisCode on VP.visit_code_snr = VisCode.clist_snr 
	left outer join codelist NoVis on VP.novis_snr = NoVis.clist_snr 
	left outer join codelist VA1 on cast(VP.escort_snr as varchar(36)) = cast(VA1.clist_snr as varchar(36))
	left outer join codelist VA2 on cast(substring(VP.escort_snr,38,36) as varchar(36)) = cast(VA2.clist_snr as varchar(36))
	left outer join VISPERSPLAN VPLAN on VPLAN.vispersplan_snr = VP.vispersplan_snr
	left outer join PERSON_UD PUD on PUD.pers_snr = P.pers_snr and PUD.rec_stat=0
	
	where
	VP.pers_snr <> '00000000-0000-0000-0000-000000000000'
	and P.pers_snr <> '00000000-0000-0000-0000-000000000000'
	and I.inst_snr <> '00000000-0000-0000-0000-000000000000'
	and P.rec_stat=0
	and VP.rec_stat=0
	and PSW.rec_stat=0
	and PLW.rec_stat=0
	and UT.rec_stat=0
	and U.rec_stat=0
	and U.status=1
	and U.user_type in (4,5)
	and repre.rec_stat=0
	and Ugte.rec_stat=0
	and U.user_snr in ('".$ids."')
	and VP.visit_date between '".$fechaI."' and '".$fechaF."' 
	
	order by U.user_nr,U.lname,U.fname,VP.visit_date,P.lname,P.mothers_lname,P.fname ";
		
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
