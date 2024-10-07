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

	$nombreListado="Listado_Aprobacion_Instituciones";
	
	$qMedicos = "Select
	/*aps.approval_status_snr,*/
	cl.name as Linea,
	Ugte.user_nr as Gte,
	U.user_nr as Ruta,
	upper(U.lname)+' '+upper(U.fname) as Representante,
	'{'+cast((case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then IA.i_inst_snr else I.inst_snr end) as varchar(36))+'}' as Codigo_Inst,
	(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then Dsta.name else Dst.name end) as Ciudad,
	(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then Statea.name else State.name end) as Estado,
	isnull(cast(cast(IA.creation_timestamp as DATE) as varchar(10)),' ') as Fecha_Alta, 
	IA.I_movement_type as Tipo_mov,
	(case when aps.APPROVED_STATUS in (1,4) then 'Pendiente' when aps.APPROVED_STATUS=2 then 'Aprobado' when aps.APPROVED_STATUS=3 then 'Rechazado' end) as Estatus_mov,
	isnull(IA.I_DEL_REASON,' ') as Motivo_Baja,
	isnull(convert(varchar(16), aps.date_change, 120),' ') as 'Fecha Solicitud Cambio',
	isnull(Usa.lname+' '+Usa.fname,' ') as Persona_Aprueba,
	(case when aps.APPROVED_STATUS in (1,4) then null else convert(varchar(16), aps.APPROVED_DATE, 120) end) as 'Fecha aprobacion cambio',
	(case when I.INST_TYPE=1 then 'HOSPITAL' when I.INST_TYPE=2 then 'FARMACIA' when I.INST_TYPE=3 then 'CONSULTORIO' when I.INST_TYPE=5 then 'OTRO' end) as Tipo_Inst,
	upper(ST.name) as SubTipo,
	(case when IA.I_INST_TYPE=1 then 'HOSPITAL' when IA.I_INST_TYPE=2 then 'FARMACIA' when IA.I_INST_TYPE=3 then 'CONSULTORIO' when IA.I_INST_TYPE=5 then 'OTRO' end) as Tipo_Inst_Nvo,
	upper(STa.name) as SubTipo_Nvo,
	(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(Ia.i_name) else upper(I.name) end) as Nombre,
	(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then ' ' else isnull(Ia.i_name,' ') end) as Nombre_Nvo,
	(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(Ia.i_street1) else upper(I.street1) end) as Direccion,
	(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then ' ' else isnull(Ia.i_street1,' ') end) as Direccion_Nva,
	(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(Citya.name) else upper(City.name) end) as Colonia,
	(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(Citya.zip) else upper(City.zip) end) as CP,
	(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then ' ' else isnull(Citya.name,' ') end) as Colonia_Nva,
	(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then ' ' else isnull(Citya.zip,' ') end) as CP_Nvo, 
	(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(CATa.NAME) else upper(CAT.name) end) as Cat,
	(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then ' ' else isnull(CATa.name,' ') end) as Cat_Nvo,
	(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then upper(Statusa.name) else upper(status.name) end) as Estatus,
	(case when aps.movement_type='N' and aps.APPROVED_STATUS in (1,4) then ' ' else isnull(Statusa.name,' ') end) as Estatus_Nvo
	
	from approval_status APS
	Inner join INST_APPROVAL IA on IA.INST_APPROVAL_SNR = APS.INST_APPROVAL_SNR 
	left outer join Users U on U.user_snr = APS.change_user_snr and U.rec_stat=0
	inner join kloc_reg repre on repre.kloc_snr = U.user_snr
	inner join users Ugte on Ugte.user_snr = repre.reg_snr and Ugte.user_type=5
	left outer join inst I on I.inst_snr = IA.i_inst_snr and I.rec_stat=0
	left outer join User_Territ UT on UT.inst_snr = I.inst_snr and UT.rec_stat=0
	left outer join compline cl on U.cline_snr = cl.cline_snr
	left outer join City on City.city_snr = I.city_snr
	left outer join District Dst on city.distr_snr = Dst.distr_snr
	left outer join State on Dst.state_snr = State.state_snr
	left outer join Brick IMS on IMS.brick_snr = City.brick_snr
	left outer join codelist T on I.type_snr = T.clist_snr
	left outer join codelist ST on I.subtype_snr = ST.clist_snr
	left outer join codelist status on I.status_snr = status.clist_snr
	left outer join codelist CAT on I.category_snr = CAT.clist_snr
	
	left outer join Users Usa on Usa.user_snr = APS.approved_user_snr and Usa.rec_stat=0
	left outer join City Citya on Citya.city_snr = IA.I_city_snr
	left outer join District Dsta on citya.distr_snr = Dsta.distr_snr
	left outer join State Statea on Dsta.state_snr = Statea.state_snr
	left outer join Brick IMSa on IMSa.brick_snr = Citya.brick_snr
	left outer join codelist Ta on IA.I_type_snr = Ta.clist_snr
	left outer join codelist STa on IA.I_subtype_snr = STa.clist_snr
	left outer join codelist Statusa on IA.I_status_snr = Statusa.clist_snr
	left outer join codelist CATa on IA.I_category_snr = CATa.clist_snr
	
	where APS.rec_stat=0
	and IA.rec_stat=0
	and APS.approval_status_snr<>'00000000-0000-0000-0000-000000000000'
	and IA.i_inst_snr<>'00000000-0000-0000-0000-000000000000'
	and APS.table_nr=492
	and U.status=1
	and U.user_type=4
	and repre.rec_stat=0
	and Ugte.rec_stat=0
	and APS.change_user_snr in ('".$ids."')
	and cast(APS.date_change as date) between '".$fechaI."' and '".$fechaF."'
	/*and cast(APS.date_change as date) between '2023-01-02' and '2023-02-12'*/
	
	order by U.user_nr,U.lname,U.fname,I.name,I.street1 ";
		
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
