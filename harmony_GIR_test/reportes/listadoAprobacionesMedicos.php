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

	$nombreListado="Listado_Aprobacion_Medicos";
	
	$qMedicos = "Select
	/*aps.approval_status_snr,*/
	cl.name as Linea,
	Ugte.user_nr as Gte,
	U.user_nr as Ruta,
	upper(U.lname)+' '+upper(U.fname) as Representante,
	'{'+cast((case when PA.p_movement_type='N' and aps.approved_status in (1,3,4) then PA.p_pers_snr else P.pers_snr end) as varchar(36))+'}' as Codigo_Med,
	'{'+cast((case when PA.p_movement_type='N' and aps.approved_status in (1,3,4) then PA.plw_inst_snr else I.inst_snr end) as varchar(36))+'}' as Codigo_Inst,
	(case when PA.p_movement_type='N' and aps.approved_status in (1,3,4) then upper(PA.p_lname)+' '+upper(PA.p_mothers_lname)+' '+upper(PA.p_fname) else upper(P.lname)+' '+upper(P.mothers_lname)+' '+upper(P.fname) end) as Medico,
	(case when PA.p_movement_type='N' and aps.approved_status in (1,3,4) then upper(Insta.STREET1) else upper(I.street1) end) as Direccion,
	(case when PA.p_movement_type='N' and aps.approved_status in (1,3,4) then (case when Insta.num_ext='0' then '' else Insta.num_ext end)
	else (case when I.num_ext='0' then '' else I.num_ext end) end) as Num_Ext,
	(case when PA.p_movement_type='N' and aps.approved_status in (1,3,4) then Citya.name else City.name end) as Colonia,
	(case when PA.p_movement_type='N' and aps.approved_status in (1,3,4) then Citya.zip else City.zip end) as Cod_Postal,
	(case when PA.p_movement_type='N' and aps.approved_status in (1,3,4) then Dsta.name else Dst.name end) as Ciudad,
	(case when PA.p_movement_type='N' and aps.approved_status in (1,3,4) then Statea.name else State.name end) as Estado,
	(case when PA.p_movement_type='N' and aps.approved_status in (1,3,4) then isnull(SEXOa.name,' ') else SEXO.name end) as Sexo,
	(case when PA.p_movement_type='N' and aps.approved_status in (1,3,4) then isnull(ESP2a.name,' ') else isnull(ESP2.name,' ') end) as Sub_Esp,
	(case when PA.p_movement_type='N' and aps.approved_status in (1,3,4) then isnull(HONa.name,' ') else HON.name end) as Honorarios,
	(case when PA.p_movement_type='N' and aps.approved_status in (1,3,4) then isnull(PTa.name,' ') else isnull(PT.name,' ') end) as PacxSem,
	(case when PA.p_movement_type='N' and aps.approved_status in (1,3,4) then isnull(PA.p_prof_id,' ') else P.prof_id end) as Cedula,
	(case when PA.p_movement_type='N' and aps.approved_status in (1,3,4) then isnull(PA.p_mobile,' ') else P.mobile end) as Celular,
	cast(cast(PA.creation_timestamp as DATE) as varchar(10)) as Fecha_Alta,
	PA.p_movement_type as Tipo_mov,
	(case when aps.approved_status in (1,4) then 'Pendiente' when aps.approved_status=2 then 'Aprobado' when aps.approved_status=3 then 'Rechazado' end) as Estatus_mov,
	isnull(STab.name,' ') as Tipo_Baja,
	isnull(PA.plw_del_reason,' ') as Motivo_Baja,
	isnull(convert(varchar(16), aps.date_change, 120),' ') as 'Fecha solicitud cambio',
	isnull(Usa.lname+' '+Usa.fname,' ') as Persona_Aprueba,
	(case when aps.approved_status in (1,4) then null else convert(varchar(16), aps.approved_date, 120) end) as 'Fecha aprobacion cambio',
	isnull(MRa.name,' ') as 'Motivo Rechazo',
	(case when PA.p_movement_type='N' and aps.approved_status in (1,4) then upper(PA.p_lname) else upper(P.lname) end) as Paterno,
	(case when PA.p_movement_type='N' and aps.approved_status in (1,4) then upper(PA.p_mothers_lname) else upper(P.mothers_lname) end) as Materno,
	(case when PA.p_movement_type='N' and aps.approved_status in (1,4) then upper(PA.p_fname) else upper(P.fname) end) as Nombre,
	(case when PA.p_movement_type='N' and aps.approved_status in (1,4) then ' ' else isnull(PA.p_lname,' ') end) as Paterno_Nvo,
	(case when PA.p_movement_type='N' and aps.approved_status in (1,4) then ' ' else isnull(PA.p_mothers_lname,' ') end) as Materno_Nvo,
	(case when PA.p_movement_type='N' and aps.approved_status in (1,4) then ' ' else isnull(PA.p_fname,' ') end) as Nombre_Nvo,
	(case when PA.p_movement_type='N' and aps.approved_status in (1,4) then upper(espa.name) else upper(ESP.name) end) as Esp,
	(case when PA.p_movement_type='N' and aps.approved_status in (1,4) then ' ' else isnull(espa.name,' ') end) as Esp_Nvo,
	(case when PA.p_movement_type='N' and aps.approved_status in (1,4) then upper(CATa.name) else upper(CAT.name) end) as Cat,
	(case when PA.p_movement_type='N' and aps.approved_status in (1,4) then ' ' else isnull(CATa.name,' ') end) as Cat_Nvo,
	(case when PA.p_movement_type='N' and aps.approved_status in (1,4) then upper(STa.name) else upper(ST.name) end) as Estatus,
	(case when PA.p_movement_type='N' and aps.approved_status in (1,4) then ' ' else isnull(STa.name,' ') end) as Estatus_Nvo
	
	from approval_status APS
	Inner join PERSON_APPROVAL PA on PA.pers_approval_snr = APS.pers_approval_snr 
	left outer join Users U on U.user_snr = APS.change_user_snr and U.rec_stat=0
	inner join kloc_reg repre on repre.kloc_snr = U.user_snr
	inner join users Ugte on Ugte.user_snr = repre.reg_snr and Ugte.user_type=5
	left outer join person P on P.pers_snr = PA.p_pers_snr and P.rec_stat=0
	left outer join pers_srep_work PSW on PSW.pers_snr = P.pers_snr and PSW.rec_stat=0 
	left outer join perslocwork PLW on PLW.pwork_snr = PSW.pwork_snr and PLW.rec_stat=0 
	left outer join inst I on I.inst_snr = PLW.inst_snr and I.rec_stat=0
	left outer join User_Territ UT on PSW.user_snr = UT.user_snr and i.inst_snr = UT.inst_snr and UT.rec_stat=0
	left outer join City on City.city_snr = I.city_snr
	left outer join District Dst on city.distr_snr = Dst.distr_snr
	left outer join State on Dst.state_snr = State.state_snr
	left outer join Brick IMS on IMS.brick_snr = City.brick_snr
	left outer join compline cl on U.cline_snr = cl.cline_snr
	left outer join inst_Type IT on IT.inst_type = I.inst_type 
	left outer join codelist type on I.type_snr = type.clist_snr
	left outer join codelist ST on P.status_snr = ST.clist_snr
	left outer join codelist SEXO on P.sex_snr = SEXO.clist_snr
	left outer JOIN codelist CAT on P.category_snr = CAT.clist_snr
	left outer join codelist ESP on P.spec_snr = ESP.clist_snr
	left outer join codelist ESP2 on P.subSpec_snr = ESP2.clist_snr 
	left outer join codelist HON on P.fee_type_snr = HON.clist_snr
	left outer join codelist FV on P.frecvis_snr = FV.clist_snr 
	left outer join codelist PT on P.patperweek_snr = PT.clist_snr 
	left outer join PERSON_UD PUD on PUD.pers_snr = P.pers_snr and PUD.rec_stat=0
	
	left outer join Users Usa on Usa.user_snr = APS.approved_user_snr and Usa.rec_stat=0
	left outer join Inst Insta on PA.plw_inst_snr = Insta.inst_snr and Insta.rec_stat=0
	left outer join City Citya on Citya.city_snr = Insta.city_snr
	left outer join District Dsta on citya.distr_snr = Dsta.distr_snr
	left outer join State Statea on Dsta.state_snr = Statea.state_snr
	left outer join Brick IMSa on IMSa.brick_snr = Citya.brick_snr
	left outer join inst_Type ITa on ITa.inst_type = Insta.inst_type 
	left outer join codelist typea on Insta.type_snr = typea.clist_snr
	left outer join codelist STa on PA.p_status_snr = STa.clist_snr
	left outer join codelist SEXOa on PA.p_sex_snr = SEXOa.clist_snr
	left outer join CODELIST CATa on PA.p_category_snr = CATa.clist_snr
	left outer join codelist ESPa on PA.p_spec_snr = ESPa.clist_snr 
	left outer join codelist ESP2a on PA.p_subspec_snr = ESP2a.clist_snr 
	left outer join codelist HONa on PA.p_fee_type_snr = HONa.clist_snr
	left outer join codelist FVa on PA.p_frecvis_snr = FVa.clist_snr 
	left outer join codelist PTa on PA.p_patperweek_snr = PTa.clist_snr 
	left outer join codelist MRa on APS.reject_reason_snr = MRa.clist_snr 
	left outer join codelist STab on PA.plw_del_status_snr = STab.clist_snr 
	
	where APS.rec_stat=0
	and PA.rec_stat=0
	and PA.p_pers_snr <> '00000000-0000-0000-0000-000000000000'
	and APS.table_nr=456
	and U.status=1
	and U.user_type=4
	and repre.rec_stat=0
	and Ugte.rec_stat=0
	and APS.change_user_snr in ('".$ids."')
	and cast(APS.date_change as date) between '".$fechaI."' and '".$fechaF."'
	/*and cast(APS.date_change as date) between '2023-06-01' and '2023-07-31'*/
	
	Order by U.user_nr,U.lname,P.lname,P.mothers_lname,P.fname ";
		
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
