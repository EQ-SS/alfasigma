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

	$nombreListado="Listado_Stock";
	
	$qMedicos = "Select
	cl.name as Linea,
	Ugte.user_nr as Gte,
	U.user_nr as Ruta,
	upper(U.lname)+' '+upper(U.fname) as Representante,
	'{'+cast(I.inst_snr as varchar(36))+'}' as Codigo_Inst,
	upper(T.name) as Tipo,
	upper(ST.name) as Sub_Tipo,
	upper(formato.name) as Formato,
	upper(I.name) as Nombre,
	upper(I.street1) as Direccion,
	I.num_ext as Num_Ext, 
	City.name as Colonia,
	City.zip as CPostal,
	IMS.name as Brick,
	Dst.name as Ciudad,
	State.name as Estado,
	stat.name as Estatus,
	cast(cast(VI.visit_date as DATE) as varchar(10)) as Fecha_Visita,
	Prod.name as Producto, 
	PF.name as Presentacion,
	Stock.suggest as Existencia,
	Stock.quantity as Existencia2
	/*Stock.price as Precio,
	Stock.displace as Pedido*/
	/* PFComp.name as Competidor, 
	Stock_Comp.quantity as Cantidad,
	Stock_Comp.price as Precio,
	Stock_Comp.Comment1 as Comentarios */
	
	from Visitinst VI
	inner join Inst I on I.inst_snr = VI.inst_snr
	inner join Users U on U.user_snr = VI.user_snr
	inner join compline cl on U.cline_snr = cl.cline_snr
	inner join kloc_reg repre on repre.kloc_snr = U.user_snr
	inner join users Ugte on Ugte.user_snr = repre.reg_snr and Ugte.user_type=5
	left outer join City on City.city_snr = I.city_snr
	inner join District Dst on city.distr_snr = Dst.distr_snr
	inner join State on Dst.state_snr = State.state_snr
	left outer join Brick IMS on IMS.brick_snr = City.brick_snr	 
	left outer join codelist T on I.type_snr = T.clist_snr
	left outer join codelist ST on I.subtype_snr = ST.clist_snr
	left outer join codelist formato on I.format_snr = formato.clist_snr
	left outer join codelist stat on I.status_snr = stat.clist_snr
	inner join Product Prod on Prod.rec_stat=0
	inner join Prodform PF on PF.Prod_snr = Prod.Prod_snr
	inner join Inst_stock Stock on VI.Visinst_snr = Stock.Visinst_snr and PF.Prodform_snr = Stock.Prodform_snr
	/*left outer join Inst_Stock_Competitor Stock_Comp on Stock_Comp.Inst_stock_snr = Stock.Inst_stock_snr and Stock_Comp.rec_stat=0
	left outer join Prodform_Competitor PFComp on PFComp.Prodform_competitor_snr = Stock_Comp.Prodform_competitor_snr and PFComp.rec_stat=0 */
	
	where
	VI.inst_snr<>'00000000-0000-0000-0000-000000000000'
	and I.inst_snr <> '00000000-0000-0000-0000-000000000000'
	and VI.rec_stat=0
	and I.rec_stat=0
	and U.rec_stat=0
	and U.status=1
	and U.user_type=4
	and repre.rec_stat=0
	and Ugte.rec_stat=0
	and PF.rec_stat=0
	and Stock.rec_stat=0
	and Stock.quantity<>0
	/*and (Stock.quantity<>'0' or Stock.suggested<>' ' or OUT_OF_STOCK_SNR<>'00000000-0000-0000-0000-000000000000' 
	or CHAIN_SNR<>'00000000-0000-0000-0000-000000000000' or CURRENT_PROMOTION_SNR<>'00000000-0000-0000-0000-000000000000')*/
	and U.user_snr in ('".$ids."')
	and VI.visit_date between '".$fechaI."' and '".$fechaF."' 
	
	order by U.user_nr,U.lname,U.fname,VI.visit_date,I.name,Prod.name,PF.name/*,PFComp.name*/ ";
		
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
