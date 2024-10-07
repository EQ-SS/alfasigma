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

	$nombreListado="Listado_Encuesta_Modafinilo";
	
	$qMedicos = "DECLARE @Encuesta as varchar(36)
		DECLARE @Pregunta1 as varchar(36)
		DECLARE @Pregunta2 as varchar(36)
		DECLARE @Pregunta3 as varchar(36)
		DECLARE @Pregunta4 as varchar(36)
		DECLARE @Pregunta5 as varchar(36)
		
		set @Encuesta='48A77AA0-8677-4E4C-83D8-81BF5471E92C'
		set @Pregunta1=(select survey_questions_snr from SURVEY_QUESTIONS where rec_stat=0 and survey_snr = @Encuesta and sort_num=1)
		set @Pregunta2=(select survey_questions_snr from SURVEY_QUESTIONS where rec_stat=0 and survey_snr = @Encuesta and sort_num=2)
		set @Pregunta3=(select survey_questions_snr from SURVEY_QUESTIONS where rec_stat=0 and survey_snr = @Encuesta and sort_num=3)
		
		Select 
		cl.name as Linea, 
		Ugte.user_nr as Gte,
		U.user_nr as Ruta,
		substring(upper(U.lname),9,30)+' '+upper(U.fname) as Representante,
		'{'+cast(P.pers_snr as varchar(36))+'}' as Codigo_Med, 
		upper(P.lname) as Paterno, 
		upper(P.mothers_lname) as Materno, 
		upper(P.fname) as Nombre, 
		upper(S.name) as Nombre_Encuesta,
		cast(cast(SAnsw.date as DATE) as nvarchar(10)) as Fecha_Aplicacion,
		/*cast(SAnsw.time as nvarchar(10)) as Hora_Aplicacion,*/
		(case when Resp1.answer_snr<>'00000000-0000-0000-0000-000000000000' then SA1.name else Resp1.answer_string end) as 'En el ultimo mes ¿ha prescrito MODAFINILO? (Si respondiste no, termina la encuesta)', 
		(case when Resp2.answer_snr<>'00000000-0000-0000-0000-000000000000' then SA2.name else Resp2.answer_string end) as 'En Este ultimo mes ¿cuantos pacientes tiene en tratamiento con MODAFINILO?', 
		(case when Resp3.answer_snr<>'00000000-0000-0000-0000-000000000000' then SA3.name else Resp3.answer_string end) as '¿Cual es la reaccion adversa mas frecuente que le reportan sus pacientes tratados con MODAFINILO?' 
		
		from SURVEY_ANSWERED SAnsw
		inner join SURVEY S on S.survey_snr = SAnsw.survey_snr 
		inner join person P on P.pers_snr = SAnsw.pers_snr 
		inner join users U on U.user_snr = SAnsw.user_snr 
		inner join compline cl on U.cline_snr = cl.cline_snr
		inner join kloc_reg repre on repre.kloc_snr = U.user_snr
		inner join users Ugte on Ugte.user_snr = repre.reg_snr and Ugte.user_type=5
		left outer join SURVEY_ANSWERED Resp1 on Resp1.user_snr = SAnsw.user_snr and Resp1.pers_snr = SAnsw.pers_snr and Resp1.date = SAnsw.date and Resp1.rec_stat = 0 and Resp1.SURVEY_QUESTION_SNR = @Pregunta1
		left outer join SURVEY_ANSWERED Resp2 on Resp2.user_snr = SAnsw.user_snr and Resp2.pers_snr = SAnsw.pers_snr and Resp2.date = SAnsw.date and Resp2.rec_stat = 0 and Resp2.SURVEY_QUESTION_SNR = @Pregunta2
		left outer join SURVEY_ANSWERED Resp3 on Resp3.user_snr = SAnsw.user_snr and Resp3.pers_snr = SAnsw.pers_snr and Resp3.date = SAnsw.date and Resp3.rec_stat = 0 and Resp3.SURVEY_QUESTION_SNR = @Pregunta3 
		left outer join SURVEY_ANSWER SA1 on SA1.survey_answer_snr = Resp1.answer_snr and SA1.rec_stat = 0
		left outer join SURVEY_ANSWER SA2 on SA2.survey_answer_snr = Resp2.answer_snr and SA2.rec_stat = 0
		left outer join SURVEY_ANSWER SA3 on SA3.survey_answer_snr = Resp3.answer_snr and SA3.rec_stat = 0
		
		where
		P.pers_snr <> '00000000-0000-0000-0000-000000000000'
		and SAnsw.rec_stat=0 
		and P.rec_stat=0
		and U.rec_stat=0
		and U.status=1
		and U.user_type=4 
		and repre.rec_stat=0
		and Ugte.rec_stat=0
		and S.survey_snr = @Encuesta
		and U.user_snr in ('".$ids."') 
		
		group by cl.name,Ugte.user_nr,U.user_nr,U.lname,U.fname,P.pers_snr,P.lname,P.mothers_lname,P.fname,S.name,SAnsw.date,Resp1.answer_snr,Resp2.answer_snr,Resp3.answer_snr
		,SA1.name,SA2.name,SA3.name,Resp1.answer_string,Resp2.answer_string,Resp3.answer_string 
		
		order by U.user_nr,U.lname,U.fname,P.lname,P.mothers_lname,P.fname,SAnsw.date ";
		
	$qMedicos2 = addslashes($qMedicos);
	$qMedicos2 = str_replace(array("\r", "\n","\r\n",""), " ", $qMedicos2);
		
	$rsQueryExport = sqlsrv_query($conn, $qMedicos, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

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
