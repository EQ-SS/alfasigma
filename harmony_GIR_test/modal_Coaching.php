<!-- Modal Preguntas Coaching -->
<input type="hidden" id="hdnIdEncuestaNueva"/>
<input type="hidden" id="hdnIdCoachingUser"/>
<input type="hidden" id="hdnColCoachingUser"/>
<input type="hidden" id="hdnNumPreguntasCoachingUser"/>
<?php
    $queryNumPregCoaching="SELECT * FROM COACHING_QUESTIONS WHERE REC_STAT=0 AND  COACHING_QUESTION_SNR<>'00000000-0000-0000-0000-000000000000'";
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $stmtPreguntas = sqlsrv_query( $conn, $queryNumPregCoaching , $params, $options );
    $num_Preguntas = sqlsrv_num_rows( $stmtPreguntas );
    echo "<script>$('#hdnNumPreguntasCoachingUser').val('".$num_Preguntas."');</script>";
?>
<style>
    #divScrollCoaching {
        overflow:scroll;
        height:200px;
        width:100%;
    }
    #divScrollCoaching table {
        width:100%;
        background-color:lightgray;
    }
</style>

<div class="modal fade" id="modal_Coaching" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel"><i class="fas fa-clipboard-list"></i> Coaching</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="height: 80vh;overflow-y: auto;">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" >
                
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" >
                <div class="form-group margin-0">
                    <label class="col-red">Representante*</label>
                    <select class="form-control" id="sltRepreCoaching">
                       <!-- <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>-->
                    </select>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" >
                
            </div>
        </div>

        <br>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="text-align: center;vertical-align: middle;">
                <p class="pciclo" id="pciclo1">Ciclo 1</p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style="text-align: center;vertical-align: middle;" >
                <p class="pciclo" id="pciclo2">Ciclo 2</p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"  style="text-align: center;vertical-align: middle;">
                <p class="pciclo" id="pciclo3">Ciclo 3</p>
            </div>

        </div>

<?php
    //include "conexion.php";
   $queryPreguntas="SELECT CQ.NAME AS PREGUNTA,CQG.NAME AS GRUPO,CAG.NAME AS TIPO
   FROM COACHING_QUESTIONS CQ 
   INNER JOIN COACHING_QUESTIONS_GROUP CQG ON CQ.COACHING_QUESTION_GROUP_SNR=CQG.COACHING_QUESTION_GROUP_SNR
   INNER JOIN COACHING_ANSWER_GROUP CAG ON CAG.COACHING_ANSWER_GROUP_SNR=CQ.COACHING_ANSWER_GROUP_SNR
   WHERE CQ.COACHING_QUESTION_SNR <>'00000000-0000-0000-0000-000000000000'
   order by CQ.SORT_NUM";

    $rsPreguntas = sqlsrv_query($conn, $queryPreguntas);

    $i=1;
    $x=0;
    $num=1;

    $nombreGrupo="";
    while($regPreg = sqlsrv_fetch_array($rsPreguntas)){

        if($i<10){
            $idSltC1="sltComboC1_0".$i;
            $idSltC2="sltComboC2_0".$i;
            $idSltC3="sltComboC3_0".$i;
            $num="0".$i;
        }else{
            $idSltC1="sltComboC1_".$i;
            $idSltC2="sltComboC2_".$i;
            $idSltC3="sltComboC3_".$i;
            $num=$i;
        }

        if($x==0){
            $nombreGrupo=$regPreg['GRUPO'];
            echo "
            <div class=\"row\">
                <br>
                <div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\" style=\"text-align: center;vertical-align: middle;\">
                    <h4 style=\"color:black;\">".utf8_encode($nombreGrupo)."</h4>
                </div>
            </div>";
        }


        if($nombreGrupo !=$regPreg['GRUPO']){

            $nombreGrupo=$regPreg['GRUPO'];

            echo " 
            <div class=\"row\">
                <br>
                <div style=\"border-color: black;border-width: 3px;border-style: solid;\"> </div>
            </div> 
            <div class=\"row\">
            
                <br>
                <div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\" style=\"text-align: center;vertical-align: middle;\">
                    <h4 style=\"color:black;\"> ".utf8_encode($nombreGrupo)."</h4>
                </div>
            </div>";

        }

       

        echo "
        <div class=\"row\">
            <div class=\"col-lg-7 col-md-7 col-sm-12 col-xs-12\">
                <br>
                <label>".$i." - ".utf8_encode($regPreg['PREGUNTA'])."</label>
            </div>
        </div>";

        if($regPreg['TIPO']=="COMBO SI/NO"){

            


            echo "
            <div class=\"row\">
            <div class=\"col-lg-4 col-md-4 col-sm-12 col-xs-12\">
                <br>
                <label>C1  </label>
                <br>
               <select class=\"form-control sltComboCoach\" id=\"".$idSltC1."\">
                    <option value=\"00000000-0000-0000-0000-000000000000\" >Seleccione</option>";
                $queryComboCoaching="select COACHING_ANSWER_SNR as id,NAME as nombre from COACHING_ANSWER where REC_STAT=0 and COACHING_ANSWER_SNR <>'00000000-0000-0000-0000-000000000000'
                and COACHING_ANSWER_GROUP_SNR='aed78499-1449-4c6f-b9f0-a2e84c73535f'
                order by NAME";
                $rsComboCoaching = sqlsrv_query($conn, $queryComboCoaching);
                while($arrComboCoach = sqlsrv_fetch_array($rsComboCoaching)){
                    echo '<option value="'.$arrComboCoach['id'].'">'.$arrComboCoach['nombre'].'</option>';
                }
                


              echo " </select>
            </div>
            <div class=\"col-lg-4 col-md-4 col-sm-12 col-xs-12\">
                <br>
                <label>C2  </label>
                <br>
               <select class=\"form-control sltComboCoach\" id=\"".$idSltC2."\"> 
                 <option value=\"00000000-0000-0000-0000-000000000000\" >Seleccione</option>";
               $queryComboCoaching="select COACHING_ANSWER_SNR as id,NAME as nombre from COACHING_ANSWER where REC_STAT=0 and COACHING_ANSWER_SNR <>'00000000-0000-0000-0000-000000000000'
               and COACHING_ANSWER_GROUP_SNR='aed78499-1449-4c6f-b9f0-a2e84c73535f'
               order by NAME";
               $rsComboCoaching = sqlsrv_query($conn, $queryComboCoaching);
               while($arrComboCoach = sqlsrv_fetch_array($rsComboCoaching)){
                   echo '<option value="'.$arrComboCoach['id'].'">'.$arrComboCoach['nombre'].'</option>';
               }
              echo " </select>
            </div>
            <div class=\"col-lg-4 col-md-4 col-sm-12 col-xs-12\">
                <br>
                <label>C3  </label>
                <br>
               <select class=\"form-control sltComboCoach\" id=\"".$idSltC3."\"> 
                    <option value=\"00000000-0000-0000-0000-000000000000\"  >Seleccione</option>";

               $queryComboCoaching="select COACHING_ANSWER_SNR as id,NAME as nombre from COACHING_ANSWER where REC_STAT=0 and COACHING_ANSWER_SNR <>'00000000-0000-0000-0000-000000000000'
                and COACHING_ANSWER_GROUP_SNR='aed78499-1449-4c6f-b9f0-a2e84c73535f'
                order by NAME";
                $rsComboCoaching = sqlsrv_query($conn, $queryComboCoaching);
                while($arrComboCoach = sqlsrv_fetch_array($rsComboCoaching)){
                    echo '<option value="'.$arrComboCoach['id'].'">'.$arrComboCoach['nombre'].'</option>';
                }
              echo" </select>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-4 col-md-4 col-sm-12 col-xs-12 \" >
                <div class=\"divAS\" id=\"divASC1_".$num."\">
                    <br>
                    <label>Accion a Seguir:  </label>
                    <br>
                    <textarea   class=\"form-control txtASC1\"  id=\"txtASC1_".$num."\" rows=\"3\"></textarea>
                </div>
               
            </div>
            <div  class=\"col-lg-4 col-md-4 col-sm-12 col-xs-12 \" >
                <div class=\"divAS\" id=\"divASC2_".$num."\">
                    <br>
                    <label>Accion a Seguir:  </label>
                    <br>
                    <textarea class=\"form-control txtASC2\"  id=\"txtASC2_".$num."\" rows=\"3\"></textarea>
                </div>
            </div>
            <div  class=\"col-lg-4 col-md-4 col-sm-12 col-xs-12 \" >
                <div class=\"divAS\" id=\"divASC3_".$num."\">
                    <br>
                    <label>Accion a Seguir:  </label>
                    <br>
                    <textarea   class=\"form-control txtASC3\"  id=\"txtASC3_".$num."\" rows=\"3\"></textarea>
                </div>
              
            </div>
        </div>
        ";
        $i++;

        }else if($regPreg['TIPO']=="COMBO PRODUCTO"){

            echo "
            <div class=\"row\">
                <div class=\"col-lg-4 col-md-4 col-sm-12 col-xs-12\">
                    <br>
                    <select  class=\"form-control sltComboCoach\" id=\"".$idSltC1."\">
                        <option value=\"00000000-0000-0000-0000-000000000000\">Seleccione</option>";
                $queryComboProdCoaching="SELECT COACHING_ANSWER_SNR AS id,NAME as nombre FROM COACHING_ANSWER 
                WHERE COACHING_ANSWER_GROUP_SNR='07774d89-6429-491b-b6d0-0e41a1c1a83c'
                AND REC_STAT=0
                ORDER BY SORT_NUM ";
                $rsComboProdCoaching = sqlsrv_query($conn, $queryComboProdCoaching);
                while($arrComboProdCoach = sqlsrv_fetch_array($rsComboProdCoaching)){
                    echo '<option value="'.$arrComboProdCoach['id'].'">'.$arrComboProdCoach['nombre'].'</option>';
                }


            echo "  </select>
                </div>
           
            ";

            //ESTO SE AGREGO----------
            echo "
          
                <div class=\"col-lg-4 col-md-4 col-sm-12 col-xs-12\">
                    <br>
                    <select  class=\"form-control sltComboCoach\" id=\"".$idSltC2."\">
                        <option value=\"00000000-0000-0000-0000-000000000000\">Seleccione</option>";
                $queryComboProdCoaching="SELECT COACHING_ANSWER_SNR AS id,NAME as nombre FROM COACHING_ANSWER 
                WHERE COACHING_ANSWER_GROUP_SNR='07774d89-6429-491b-b6d0-0e41a1c1a83c'
                AND REC_STAT=0
                ORDER BY SORT_NUM ";
                $rsComboProdCoaching = sqlsrv_query($conn, $queryComboProdCoaching);
                while($arrComboProdCoach = sqlsrv_fetch_array($rsComboProdCoaching)){
                    echo '<option value="'.$arrComboProdCoach['id'].'">'.$arrComboProdCoach['nombre'].'</option>';
                }


            echo "  </select>
                </div>

            ";


            echo "
           
                <div class=\"col-lg-4 col-md-4 col-sm-12 col-xs-12\">
                    <br>
                    <select  class=\"form-control sltComboCoach\" id=\"".$idSltC3."\">
                        <option value=\"00000000-0000-0000-0000-000000000000\">Seleccione</option>";
                $queryComboProdCoaching="SELECT COACHING_ANSWER_SNR AS id,NAME as nombre FROM COACHING_ANSWER 
                WHERE COACHING_ANSWER_GROUP_SNR='07774d89-6429-491b-b6d0-0e41a1c1a83c'
                AND REC_STAT=0
                ORDER BY SORT_NUM ";
                $rsComboProdCoaching = sqlsrv_query($conn, $queryComboProdCoaching);
                while($arrComboProdCoach = sqlsrv_fetch_array($rsComboProdCoaching)){
                    echo '<option value="'.$arrComboProdCoach['id'].'">'.$arrComboProdCoach['nombre'].'</option>';
                }


            echo "  </select>
                </div>
            </div>
            ";

            //END ESTO SE AGREGO------
            $i++;

        }else if($regPreg['TIPO']=="TEXTO"){
            echo "
            <div class=\"row\">
                <div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\">
                    <br>
                    <label>C1  </label>
                    <br>
                    <textarea class=\"form-control txtCG\"  id=\"txtASC1_".$num."\" rows=\"3\"></textarea>
                </div>
            </div>

            <div class=\"row\">
                <div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\">
                    <br>
                    <label>C2  </label>
                    <br>
                    <textarea class=\"form-control txtCG\"  id=\"txtASC2_".$num."\" rows=\"3\"></textarea>
                </div>
            </div>

            <div class=\"row\">
                
                <div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\">
                    <br>
                    <label>C3  </label>
                    <br>
                    <textarea class=\"form-control txtCG\"  id=\"txtASC3_".$num."\" rows=\"3\"></textarea>
                </div>
            </div>

            ";
            $i++;
              

        }
        
     
        $x++;
        $num++;
    }


?>

      </div>
      <div class="modal-footer">
       <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
        <button type="button" class="btn btn-primary" id="btnGuardaCoaching">Guardar</button>
       <!-- <button type="button" class="btn btn-success" id="btnFinalizaCoaching">Finalizar</button>-->
      </div>
    </div>
  </div>
</div>
<script>

    $(".sltComboCoach").change(function () {	 
        
        idslt=$(this).attr('id');
        valor=this.value;
        idslt=idslt.substr(-5);
        //si es Deficiente o En desarrollo
       // console.log("esto es id : "+idslt);
        if(valor=="F75816CB-BFEB-4AF3-802A-4831E7CEFDAD" || valor=="19387357-945A-41B6-8604-AE87F7660190"){

            $("#divAS"+idslt).show();
           
        }else{
            $("#divAS"+idslt).hide();  
          
        }
	});

    $("#btnGuardaCoaching").on( "click", function(){	 
        
        if($("#sltRepreCoaching").val()=="00000000-0000-0000-0000-000000000000"){
            alert('Seleccione Representante');
            return true;
        }
        num_Preguntas=$('#hdnNumPreguntasCoachingUser').val();
        
        idEncuesta = $("#hdnIdEncuesta").val();
        tipoUsuario = $("#hdnTipoUsuario").val();
        idUsuario = $('#hdnIdUser').val();
        col = $('#hdnColCoachingUser').val();
        //alert(col);
       // return true;

        arraySlt=[];
        arraytxt=[];
         j=1;
        for(i=1;i<=num_Preguntas;i++){

            if(j<10){
                j="0"+j;
            }else{
                j=i;
            }
            sltrespuesta=$("#sltComboC"+col+"_"+j).val();
            if(sltrespuesta=== undefined){
                sltrespuesta="00000000-0000-0000-0000-000000000000";
            }
            arraySlt[i]=sltrespuesta;

            j++;

        }

        j=1;
        for(i=1;i<=num_Preguntas;i++){

            if(j<10){
                j="0"+j;
            }else{
                j=i;
            }

            txtrespuesta=$("#txtASC"+col+"_"+j).val().toUpperCase();
            if(txtrespuesta=== undefined){
                txtrespuesta="";
            }
            arraytxt[i]=txtrespuesta;
            j++;
        }

        arraySlt=arraySlt.toString();
        arraytxt=arraytxt.join('|');

       

        $("#divRespuesta").load("ajax/guardarEncuesta.php",{
            repre:$("#sltRepreCoaching").val(),
            idEncuesta:idEncuesta,
            tipoUsuario:tipoUsuario,
            respuestasSlt:arraySlt,
            respuestasTxt:arraytxt,
            EncuestaNueva:$("#hdnEncuestaNueva").val(),
            idCoachingUser:$("#hdnIdCoachingUser").val(),
            idUsuario:idUsuario,
            num_Preguntas:num_Preguntas


        });

	});



   /* $(".sltSiNo").change(function () {	 
        idslt=$(this).attr('id');
        valor=this.value;

        if(idslt=="sltSiNo31"){
            //si es Si del combo si/No
            if(valor=="19387357-945A-41B6-8604-AE87F7660190"){

                $("#divPrioridadesCoaching").show();
               
            }else{
                $("#divPrioridadesCoaching").hide();
                
            }
        }
        
        //console.log(idslt);
        idslt=idslt.substr(-2);
        //si es No del combo si/No
        if(valor=="F75816CB-BFEB-4AF3-802A-4831E7CEFDAD"){
            $("#divAS"+idslt).show();
           
        }else{
            $("#divAS"+idslt).hide();  
          
        }
	});*/




    /*

    $(".sltSiNo").change(function () {	 
        idslt=$(this).attr('id');
        valor=this.value;

        if(idslt=="sltSiNo31"){
            //si es Si del combo si/No
            if(valor=="19387357-945A-41B6-8604-AE87F7660190"){

                $("#divPrioridadesCoaching").show();
               
            }else{
                $("#divPrioridadesCoaching").hide();
                
            }
        }
        
        //console.log(idslt);
        idslt=idslt.substr(-2);
        //si es No del combo si/No
        if(valor=="F75816CB-BFEB-4AF3-802A-4831E7CEFDAD"){
            $("#divAS"+idslt).show();
           
        }else{
            $("#divAS"+idslt).hide();  
          
        }
	});

    $("#btnGuardaCoaching").on( "click", function() {

        if($("#sltRepreCoaching").val()=="00000000-0000-0000-0000-000000000000"){
            alert('Seleccione Representante');
            return true;
        }

       //tenia 63


        arraySlt=[];
        j=1;
        for(i=1;i<=63;i++){
            if(j<10){
                j="0"+j;
            }else{
                j=i;
            }
            sltrespuesta=$("#sltSiNo"+j).val();
            if(sltrespuesta=== undefined){
                sltrespuesta="00000000-0000-0000-0000-000000000000";
            }
            arraySlt[i]=sltrespuesta;
            j++;
        }

        arraytxt=[];
        j=1;
        for(i=1;i<=63;i++){
            if(j<10){
                j="0"+j;
            }else{
                j=i;
            }
            txtrespuesta=$("#txtAS"+j).val();
            if(txtrespuesta=== undefined){
                txtrespuesta="";
            }
            arraytxt[i]=txtrespuesta;
            j++;
        }

        arraySlt=arraySlt.toString();
        arraytxt=arraytxt.join('|');

        idEncuesta = $("#hdnIdEncuesta").val();
        tipoUsuario = $("#hdnTipoUsuario").val();
        idUsuario = $('#hdnIdUser').val();

        $("#divRespuesta").load("ajax/guardarEncuesta.php",{
            repre:$("#sltRepreCoaching").val(),
            idEncuesta:idEncuesta,
            tipoUsuario:tipoUsuario,
            respuestasSlt:arraySlt,
            respuestasTxt:arraytxt,
            EncuestaNueva:$("#hdnEncuestaNueva").val(),
            finCoaching:0,
            idCoachingUser:$("#hdnIdCoachingUser").val(),
            replica:$("#txtReplicaRepre").val(),
            replica2:$("#txtReplicaRepre2").val(),
            replica3:$("#txtReplicaRepre3").val(),
            idUsuario:idUsuario


        });
    });

    $("#btnFinalizaCoaching").on( "click", function() {

        if($("#sltRepreCoaching").val()=="00000000-0000-0000-0000-000000000000"){
            alert('Seleccione Representante');
            return true;
        }

        arraySlt=[];
        j=1;
        for(i=1;i<=63;i++){
            if(j<10){
                j="0"+j;
            }else{
                j=i;
            }
            sltrespuesta=$("#sltSiNo"+j).val();
            if(sltrespuesta=== undefined){
                sltrespuesta="00000000-0000-0000-0000-000000000000";
            }
            arraySlt[i]=sltrespuesta;
            j++;
        }

        arraytxt=[];
        j=1;
        for(i=1;i<=63;i++){
            if(j<10){
                j="0"+j;
            }else{
                j=i;
            }
            txtrespuesta=$("#txtAS"+j).val();
            if(txtrespuesta=== undefined){
                txtrespuesta="";
            }
            arraytxt[i]=txtrespuesta;
            j++;
        }

        arraySlt=arraySlt.toString();
        arraytxt=arraytxt.join('|');

        idEncuesta = $("#hdnIdEncuesta").val();
        tipoUsuario = $("#hdnTipoUsuario").val();
        idUsuario = $('#hdnIdUser').val();

        $("#divRespuesta").load("ajax/guardarEncuesta.php",{
            repre:$("#sltRepreCoaching").val(),
            idEncuesta:idEncuesta,
            tipoUsuario:tipoUsuario,
            respuestasSlt:arraySlt,
            respuestasTxt:arraytxt,
            EncuestaNueva:$("#hdnEncuestaNueva").val(),
            finCoaching:1,
            idCoachingUser:$("#hdnIdCoachingUser").val(),
            replica:$("#txtReplicaRepre").val(),
            replica2:$("#txtReplicaRepre2").val(),
            replica3:$("#txtReplicaRepre3").val(),
            idUsuario:idUsuario
        });
    });

    $(function () {
        $("#txtAS47").datepicker({
            format: 'yyyy-mm-dd',
            changeMonth: false,
            changeYear: false,
            todayBtn: "linked",
            language: "es",
            autoclose: true,
            todayHighlight: true
        });
	});

    $(function () {
        $("#txtAS48").datepicker({
            format: 'yyyy-mm-dd',
            changeMonth: false,
            changeYear: false,
            todayBtn: "linked",
            language: "es",
            autoclose: true,
            todayHighlight: true
        });
	});

    $(function () {
        $("#txtAS49").datepicker({
            format: 'yyyy-mm-dd',
            changeMonth: false,
            changeYear: false,
            todayBtn: "linked",
            language: "es",
            autoclose: true,
            todayHighlight: true
        });
	});

    $(function () {
        $("#txtAS53").datepicker({
            format: 'yyyy-mm-dd',
            changeMonth: false,
            changeYear: false,
            todayBtn: "linked",
            language: "es",
            autoclose: true,
            todayHighlight: true
        });
	});

    $(function () {
        $("#txtAS54").datepicker({
            format: 'yyyy-mm-dd',
            changeMonth: false,
            changeYear: false,
            todayBtn: "linked",
            language: "es",
            autoclose: true,
            todayHighlight: true
        });
	});

    $(function () {
        $("#txtAS55").datepicker({
            format: 'yyyy-mm-dd',
            changeMonth: false,
            changeYear: false,
            todayBtn: "linked",
            language: "es",
            autoclose: true,
            todayHighlight: true
        });
	});


    $(function () {
        $("#txtAS59").datepicker({
            format: 'yyyy-mm-dd',
            changeMonth: false,
            changeYear: false,
            todayBtn: "linked",
            language: "es",
            autoclose: true,
            todayHighlight: true
        });
	});

    $(function () {
        $("#txtAS60").datepicker({
            format: 'yyyy-mm-dd',
            changeMonth: false,
            changeYear: false,
            todayBtn: "linked",
            language: "es",
            autoclose: true,
            todayHighlight: true
        });
	});

    $(function () {
        $("#txtAS61").datepicker({
            format: 'yyyy-mm-dd',
            changeMonth: false,
            changeYear: false,
            todayBtn: "linked",
            language: "es",
            autoclose: true,
            todayHighlight: true
        });
	});
    */

</script>