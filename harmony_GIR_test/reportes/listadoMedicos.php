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
	
	if(isset($_POST['hdnEstatusListado']) && $_POST['hdnEstatusListado'] != ''){
		$estatus = $_POST['hdnEstatusListado'];
	}else{
		$estatus = '';
	}
	//echo"esto es".$estatus;
	$nombreListado="Listado_Medicos";
	
	$qMedicos = "Select 
	/*cl.name as Linea, 
	Ugte.user_nr as Gte,*/
	U.user_nr as Ruta,
	upper(U.lname)+' '+upper(U.fname) as Representante,
	'{'+cast(P.pers_snr as varchar(36))+'}' as Codigo_Med, 
	'{'+cast(I.inst_snr as varchar(36))+'}' as Codigo_Inst, 
	upper(IT.name) as Tipo_Inst, 
	upper(type.name) as Tipo_Pers, 
	upper(P.lname) as Paterno, 
	upper(P.mothers_lname) as Materno, 
	upper(P.fname) as Nombre, 
	upper(I.street1) as Calle, 
	I.num_ext as Num_Ext, 
	plw.num_int as Num_Int, 
	City.name as Colonia, 
	City.zip as CPostal, 
	IMS.name as Brick, 
	Dst.name as Poblacion, 
	State.name as Estado, 
	PLW.latitude, 
	PLW.longitude, 
	HON.name as Honorarios, 
	ESP.name as Esp, 
	ESP2.name as Sub_Esp, 
	ST.name as Estatus, 
	P.prof_id as Cedula,
	cast(cast(p.BIRTHDATE as DATE) as varchar(10)) as Fecha_Nac, 
	SEXO.name as Sexo, 
	FV.name as Frec_Vis, 
	CAT.name as Categoria, 
	PT.name as PacsxSem, 
	SPEAK.name as Speaker, 
	(case when year(P.BIRTHDATE) > 0 and year(P.BIRTHDATE) <> 1900 then year(getdate())-year(P.BIRTHDATE) else '' end) as Edad, 
	P.tel1 as Tel1, 
	P.tel2 as Tel2, 
	P.mobile as Celular, 
	P.email1 as Email,
	(case when authorized_privacy = 1 then 'SI' else 'NO' end) as Aviso_privacidad,
	cast(cast(p.authorized_privacy_date as DATE) as varchar(10)) as Fecha_aviso_privacidad, 
	/*PERFIL1.name as Medico_Keto,*/
	(case when (select top 1 PLAN_DATE from VISPERSPLAN VP where VP.rec_stat=0 and VP.pers_snr = P.pers_snr and VP.user_snr = U.user_snr 
	and PLAN_DATE between (select START_DATE from CYCLES where rec_stat=0 and GETDATE() between START_DATE and FINISH_DATE) 
	and (select FINISH_DATE from CYCLES where rec_stat=0 and GETDATE() between START_DATE and FINISH_DATE) ) <> ' ' then 'SI' else 'NO' end ) as Plan_ciclo,
	cast(cast((case when P.creation_timestamp='1900-01-01' then null else P.creation_timestamp end) as DATE) as varchar(10)) as Fecha_Alta, 
	cast(cast(P.changed_timestamp as DATE) as varchar(10)) as Fecha_Mod,
	cast(P.cod_pers as varchar(10)) as cod_pers
	
	from person P
	inner join pers_srep_work PSW on PSW.pers_snr = P.pers_snr
	inner join perslocwork PLW on PSW.pwork_snr = PLW.pwork_snr
	inner join inst I on I.inst_snr = PSW.inst_snr
	inner join user_territ UT on PSW.user_snr = UT.user_snr and I.inst_snr = UT.inst_snr
	inner join users U on U.user_snr = PSW.user_snr 
	inner join compline cl on U.cline_snr = cl.cline_snr
	/*inner join kloc_reg repre on repre.kloc_snr = U.user_snr
	inner join users Ugte on Ugte.user_snr = repre.reg_snr and Ugte.user_type=5*/
	left outer join City on City.city_snr = I.city_snr
	inner join District Dst on city.distr_snr = Dst.distr_snr
	inner join State on Dst.state_snr = State.state_snr
	left outer join Brick IMS on IMS.brick_snr = City.brick_snr
	left outer join inst_Type IT on IT.inst_type = I.inst_type and IT.rec_Stat=0
	left outer join codelist type on P.perstype_snr = type.clist_snr 
	left outer join codelist ST on P.status_snr = ST.clist_snr
	left outer join codelist SEXO on P.sex_snr = SEXO.clist_snr
	left outer join codelist CAT on P.category_snr = CAT.clist_snr
	left outer join codelist PT on P.patperweek_snr = PT.clist_snr 
	left outer join codelist ESP on P.spec_snr = ESP.clist_snr
	left outer join codelist ESP2 on P.subSpec_snr = ESP2.clist_snr 
	left outer join codelist HON on P.FEE_TYPE_SNR = HON.clist_snr 
	left outer join codelist FV on P.frecvis_snr = FV.clist_snr 
	left outer join codelist SPEAK on P.speaker_snr = SPEAK.clist_snr 
	left outer join PERSON_UD PUD on PUD.pers_snr = P.pers_snr and PUD.rec_stat=0
	/*left outer join codelist PERFIL1 on PUD.field_01_snr = PERFIL1.clist_snr 
	left outer join codelist PERFIL2 on PUD.field_02_snr = PERFIL2.clist_snr*/
	
	where
	P.pers_snr <> '00000000-0000-0000-0000-000000000000'
	and P.rec_stat=0
	and PSW.rec_Stat=0
	and PLW.rec_stat=0
	and UT.rec_stat=0
	and U.rec_stat=0
	and U.status=1
	and U.user_type=4 
	/*and repre.rec_stat=0
	and Ugte.rec_stat=0*/
	and U.user_snr in ('".$ids."') ";

	if($estatus != ''){
		$qMedicos .= " and P.status_snr in ('".$estatus."') ";
	}
		
	$qMedicos .= " order by U.user_nr,U.lname,U.fname,P.lname,P.mothers_lname,P.fname ";
		
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
