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

	$nombreListado="Listado_Sincronizaciones";
	
	$qMedicos = "with User_version as (select ROW_NUMBER() over(partition by USER_LOGGING.user_snr, USER_LOGGING.start_action_time order by USER_LOGGING.user_snr, USER_LOGGING.start_action_time) as orden
	, USER_SNR, start_action_time, version 
	from USER_LOGGING
	where ACTION_TYPE is null and version is not null and TYPE is not null ) 
	/*order by start_action_time desc )*/
	
	select 
	cl.name as Linea, 
	Ugte.user_nr as Gte,
	U.user_nr as Ruta,
	upper(U.lname)+' '+upper(U.fname) as Representante,
	cast( convert(date, UL.start_action_time, 20) as nvarchar(10)) as Fecha_Sincronizacion,
	convert(char(5), UL.start_action_time, 108) as Hora_Sincronizacion,
	(select top 1 version from User_version where orden=1 and user_snr = UL.user_snr order by start_action_time desc) as 'Version'
	
	from USER_LOGGING UL
	inner join Users U on U.user_snr = UL.user_snr 
	inner join compline cl on U.cline_snr = cl.cline_snr
	inner join kloc_reg repre on repre.kloc_snr = U.user_snr
	inner join users Ugte on Ugte.user_snr = repre.reg_snr and Ugte.user_type=5
	
	where UL.action_type=1
	and U.rec_stat=0
	and U.status=1
	and U.user_type=4 
	and repre.rec_stat=0
	and Ugte.rec_stat=0
	and U.user_snr in ('".$ids."')
	and convert(date,UL.start_action_time,101) between '".$fechaI."' and '".$fechaF."'
	/*and convert(date,UL.start_action_time,101) between '2023-10-01' and '2023-10-30' */
	
	group by cl.name,Ugte.user_nr,U.user_nr,U.lname,U.fname,UL.start_action_time,UL.user_snr
	
	order by U.user_nr,U.lname,U.fname,UL.start_action_time ";
		
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
