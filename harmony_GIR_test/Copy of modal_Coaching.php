<!-- Modal Preguntas Coaching -->
<input type="hidden" id="hdnEncuestaNueva"/>
<input type="hidden" id="hdnIdCoachingUser"/>
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
<?php
					/*	$qUsers = "SELECT U.USER_SNR,U.USER_NR+' - '+U.LNAME+' '+U.MOTHERS_LNAME+' '+U.FNAME AS REPRE FROM COACHING_USERS_ASSIGNED CUA 
                        INNER JOIN USERS U ON U.USER_SNR=CUA.USER_SNR
                        WHERE U.REC_STAT=0 AND CUA.REC_STAT=0
                        AND U.USER_SNR<>'00000000-0000-0000-0000-000000000000'
                        AND U.USER_SNR IN ('".$ids."')
                        ORDER BY U.USER_NR,U.LNAME,U.MOTHERS_LNAME,U.FNAME";
					
						$rsU = sqlsrv_query($conn, $qUsers);
						while($row = sqlsrv_fetch_array($rsU)){
							echo "<option value=\"".$row['USER_SNR']."\">".$row['REPRE']."</option>";
						}
                        */
?>

                    </select>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" >
                
            </div>
        </div>
        <br>
        <!--Tabla de seguimiento -->
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" >
                <p id="pFechaInicio">Fecha de Inicio: </p>
                
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" >
                <p>Tabla de Seguimiento</p>
                
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" >
                <p id="pFechaTermino">Fecha de Termino:</p>
                
            </div>

        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div id="divScrollCoaching">
                    <table class="table table-bordered table-wrapper" id="tblCoachingSeguimiento" >
                        <thead>
                            <tr>
                            <th style="text-align: center;">#No.</th>
                            <th style="text-align: center;">Pregunta</th>
                            <th sstyle="text-align: center;">Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>

                </div>
                
                
            </div>
            

        </div>
        
        <!--end tabla de seguimiento-->
        <!--START FICHERO MEDICO -->
        <div style="border-color: black;border-width: 3px;border-style: solid;">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center;vertical-align: middle;">
                    <h4>Fichero médico</h4>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                    <br>
                    <label>Cuenta con 160 médicos en su fichero  y  están actualizados los datos</label>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo01"  >
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS01">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea  class="form-control txtAS"  id="txtAS01" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                    <br>
                    <label>Los médicos tienen la frecuencia correcta para realizar 200 contactos</label>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo02">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS02">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea class="form-control txtAS"  id="txtAS02" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                    <br>
                    <label>Cuenta con un mínimo de 60 farmacias </label>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo03">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS03">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea class="form-control txtAS"  id="txtAS03" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                    <br>
                    <label>Las farmacias tienen la frecuencia correcta para realizar 100 contactos</label>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo04">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS04">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea class="form-control txtAS"  id="txtAS04" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <br>
        </div>
        <!--END FICHERO MEDICO -->
        <br>
        <!--Start KPIS-->
        <div style="border-color: black;border-width: 3px;border-style: solid;">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center;vertical-align: middle;">
                    <h4>KPIs</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                    <p>Cubre la visita diaria  con médicos y farmacias al 100%</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
                    <br>
                    <label>Medicos</label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo05">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS05">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea class="form-control txtAS"  id="txtAS05" rows="2"></textarea>
                    </div>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
                    <br>
                    <label>Farmacias</label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo06">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS06">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea class="form-control txtAS"  id="txtAS06" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                    <p>Cumple con la frecuencia asignada a cada médico y farmacia</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
                    <br>
                    <label>Medicos</label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo07">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS07">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea class="form-control txtAS"  id="txtAS07" rows="2"></textarea>
                    </div>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
                    <br>
                    <label>Farmacias</label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo08">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS08">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea class="form-control txtAS"  id="txtAS08" rows="2"></textarea>
                    </div>
                </div>
            </div>
        
            <br>
        </div>
        <!-- END KPIS-->
        <br>
        <!--Start Conocimiento y dominio del Producto-->
        <div style="border-color: black;border-width: 3px;border-style: solid;">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center;vertical-align: middle;">
                    <h5>Conocimiento y dominio de los productos y su competencia</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <div class="form-group margin-0">
                        <label>Producto 1</label>
                        <select class="form-control sltSiNo" id="sltSiNo09">
                            <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
                        $qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='07774D89-6429-491B-B6D0-0E41A1C1A83C'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
                    
                        $rsino = sqlsrv_query($conn, $qCombo);
                        while($row = sqlsrv_fetch_array($rsino)){
                            echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
                        }
?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <br>
                    <label>Conoce el padecimiento</label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo10">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS10">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea class="form-control txtAS"  id="txtAS10" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <br>
                    <label>Domina el producto </label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo11">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS11">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea class="form-control txtAS"  id="txtAS11" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <br>
                    <label>Domina la competencia</label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo12">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS12">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea class="form-control txtAS"  id="txtAS12" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <br>
                    <label>Aplica las estrategias de marketing</label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo13">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS13">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea class="form-control txtAS"  id="txtAS13" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <br>
                    <label>Cubre la cuota del producto</label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo14">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS14">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea class="form-control txtAS"  id="txtAS14" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <br>
                    <label>El MS está igual o por arriba de la nación</label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo15">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS15">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea class="form-control txtAS"  id="txtAS15" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <hr style="border: none; border-bottom: 2px solid black;">
            
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <div class="form-group margin-0">
                        <label>Producto 2</label>
                        <select class="form-control sltSiNo" id="sltSiNo16">
                            <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						 $qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                         WHERE COACHING_ANSWER_GROUP_SNR='07774D89-6429-491B-B6D0-0E41A1C1A83C'
                         AND REC_STAT=0
                         ORDER BY SORT_NUM";
                     
                         $rsino = sqlsrv_query($conn, $qCombo);
                         while($row = sqlsrv_fetch_array($rsino)){
                             echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
                         }
?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <br>
                    <label>Conoce el padecimiento</label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo17">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS17">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea class="form-control txtAS"  id="txtAS17" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <br>
                    <label>Domina el producto </label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo18">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS18">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea class="form-control txtAS"  id="txtAS18" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <br>
                    <label>Domina la competencia</label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo19">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS19">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea class="form-control txtAS"  id="txtAS19" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <br>
                    <label>Aplica las estrategias de marketing</label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo20">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS20">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea class="form-control txtAS"  id="txtAS20" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <br>
                    <label>Cubre la cuota del producto</label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo21">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS21">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea class="form-control txtAS"  id="txtAS21" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <br>
                    <label>El MS está igual o por arriba de la nación</label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo22">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS22">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea class="form-control txtAS"  id="txtAS22" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <hr style="border: none; border-bottom: 2px solid black;">
            
            
            
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <div class="form-group margin-0">
                        <label>Producto 3</label>
                        <select class="form-control sltSiNo" id="sltSiNo23">
                            <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						 $qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                         WHERE COACHING_ANSWER_GROUP_SNR='07774D89-6429-491B-B6D0-0E41A1C1A83C'
                         AND REC_STAT=0
                         ORDER BY SORT_NUM";
                     
                         $rsino = sqlsrv_query($conn, $qCombo);
                         while($row = sqlsrv_fetch_array($rsino)){
                             echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
                         }
?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <br>
                    <label>Conoce el padecimiento</label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo24">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS24">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea class="form-control txtAS"  id="txtAS24" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <br>
                    <label>Domina el producto </label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo25" >
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS25">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea class="form-control txtAS"  id="txtAS25" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <br>
                    <label>Domina la competencia</label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo26">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS26">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea class="form-control txtAS"  id="txtAS26" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <br>
                    <label>Aplica las estrategias de marketing</label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo27">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS27">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea class="form-control txtAS"  id="txtAS27" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <br>
                    <label>Cubre la cuota del producto</label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo28">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS28">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea class="form-control txtAS"  id="txtAS28" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <br>
                    <label>El MS está igual o por arriba de la nación</label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo29">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS29">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea class="form-control txtAS"  id="txtAS29" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <br>
        </div>
        <!--End Conocimiento y dominio del Producto-->
        <br>
        <!--START Mapa de territorio-->
        <div style="border-color: black;border-width: 3px;border-style: solid;">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center;vertical-align: middle;">
                    <h4>Mapa de territorio</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <br>
                    <label>Cuenta con su mapa de territorio actualizado </label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo30">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS30">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea class="form-control txtAS"  id="txtAS30" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <br>
                    <label>Reconoce sus prioridades </label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    <br>
                    <select class="form-control sltSiNo" id="sltSiNo31">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS31">
                    <div class="form-group margin-0">
                        <label>Acción a seguir:</label>
                        <textarea class="form-control txtAS"  id="txtAS31" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <br>
            <div id="divPrioridadesCoaching" style="display:none;">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group margin-0">
                            <label>Prioridad 1:</label>
                            <textarea class="form-control txtAS"  id="txtAS32"  rows="2"></textarea>
                        </div>
                    
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group margin-0">
                            <label>Prioridad 2:</label>
                            <textarea class="form-control txtAS"  id="txtAS33"  rows="2"></textarea>
                        </div>
                    
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group margin-0">
                            <label>Prioridad 3:</label>
                            <textarea class="form-control txtAS"  id="txtAS34"  rows="2"></textarea>
                        </div>
                    
                    </div>
                </div>
            </div>
            <br>
        </div>
        <!--END Mapa de territorio-->
        <br>
        <!--START Mapa de Técnica GANA-->
        <div style="border-color: black;border-width: 3px;border-style: solid;">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center;vertical-align: middle;">
                    <h4>Técnica GANA - Previsita</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Realiza un análisis, identifica la categoría del médico, el estilo social y plantea el objetivo alineado a la información identificada</label>
                </div>
            </div>
            <br>
            <div class="row">

                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    
                    <select class="form-control sltSiNo" id="sltSiNo35">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS35">
                    <div class="input-group">
                    <span class="input-group-addon">Acción a seguir:</span>
                    <textarea type="text" class="form-control txtAS"  id="txtAS35" rows="2"></textarea>
                    </div>
                    
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>El objetivo planteado es SMART</label>
                </div>
            </div>
            <br>
            <div class="row">

                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    
                    <select class="form-control sltSiNo" id="sltSiNo36">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS36">
                    <div class="input-group">
                    <span class="input-group-addon">Acción a seguir:</span>
                    <textarea type="text" class="form-control txtAS"  id="txtAS36" rows="2"></textarea>
                    </div>
                    
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Genera interés con alto impacto </label>
                </div>
            </div>
            <br>
            <div class="row">

                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    
                    <select class="form-control sltSiNo" id="sltSiNo37">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS37">
                    <div class="input-group">
                    <span class="input-group-addon">Acción a seguir:</span>
                    <textarea type="text" class="form-control txtAS"  id="txtAS37" rows="2"></textarea>
                    </div>
                    
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Asocia el producto con la necesidad detectada</label>
                </div>
            </div>
            <br>
            <div class="row">

                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    
                    <select class="form-control sltSiNo" id="sltSiNo38">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS38">
                    <div class="input-group">
                    <span class="input-group-addon">Acción a seguir:</span>
                    <textarea type="text" class="form-control txtAS"  id="txtAS38" rows="2"></textarea>
                    </div>
                    
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Verifica que se solucionó la necesidad, objeción o duda</label>
                </div>
            </div>
            <br>
            <div class="row">

                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    
                    <select class="form-control sltSiNo" id="sltSiNo39">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS39">
                    <div class="input-group">
                    <span class="input-group-addon">Acción a seguir:</span>
                    <textarea type="text" class="form-control txtAS"  id="txtAS39" rows="2"></textarea>
                    </div>
                    
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Establece un acuerdo real y verificable (cierre / compromiso)</label>
                </div>
            </div>
            <br>
            <div class="row">

                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    
                    <select class="form-control sltSiNo" id="sltSiNo40">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS40">
                    <div class="input-group">
                    <span class="input-group-addon">Acción a seguir:</span>
                    <textarea type="text" class="form-control txtAS"  id="txtAS40" rows="2"></textarea>
                    </div>
                    
                </div>
            </div>
            <hr style="border: none; border-bottom: 2px solid black;">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center;vertical-align: middle;">
                    <h4>Seguimiento Post-Visita</h4>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Reflexiona sobre su entrevista autoevaluación y autocrítica</label>
                </div>
            </div>
            <br>
            <div class="row">

                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    
                    <select class="form-control sltSiNo" id="sltSiNo41">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS41">
                    <div class="input-group">
                    <span class="input-group-addon">Acción a seguir:</span>
                    <textarea type="text" class="form-control txtAS"  id="txtAS41" rows="2"></textarea>
                    </div>
                    
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Registra su visita de forma adecuada </label>
                </div>
            </div>
            <br>
            <div class="row">

                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    
                    <select class="form-control sltSiNo" id="sltSiNo42">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS42">
                    <div class="input-group">
                    <span class="input-group-addon">Acción a seguir:</span>
                    <textarea type="text" class="form-control txtAS"  id="txtAS42" rows="2"></textarea>
                    </div>
                    
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Establece el objetivo SMART de la siguiente visita</label>
                </div>
            </div>
            <br>
            <div class="row">

                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    
                    <select class="form-control sltSiNo" id="sltSiNo43">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS43">
                    <div class="input-group">
                    <span class="input-group-addon">Acción a seguir:</span>
                    <textarea type="text" class="form-control txtAS"  id="txtAS43" rows="2"></textarea>
                    </div>
                    
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label>Establece las acciones de riego </label>
                </div>
            </div>
            <br>
            <div class="row">

                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                    
                    <select class="form-control sltSiNo" id="sltSiNo44">
                        <option value="00000000-0000-0000-0000-000000000000">Seleccione</option>
<?php
						$qCombo = "SELECT COACHING_ANSWER_SNR,NAME FROM COACHING_ANSWER
                        WHERE COACHING_ANSWER_GROUP_SNR='AED78499-1449-4C6F-B9F0-A2E84C73535F'
                        AND REC_STAT=0
                        ORDER BY SORT_NUM";
					
						$rsino = sqlsrv_query($conn, $qCombo);
						while($row = sqlsrv_fetch_array($rsino)){
							echo "<option value=\"".$row['COACHING_ANSWER_SNR']."\">".$row['NAME']."</option>";
						}
?>
                    </select>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 divAS" style="display:none;" id="divAS44">
                    <div class="input-group">
                    <span class="input-group-addon">Acción a seguir:</span>
                    <textarea type="text" class="form-control txtAS"  id="txtAS44" rows="2"></textarea>
                    </div>
                    
                </div>
            </div>
            
            <br>
        </div>
        <!-- END Técnica GANA -->
        <br>
        <!--START Plan de acción-->
        <div style="border-color: black;border-width: 3px;border-style: solid;">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align: center;vertical-align: middle;">
                    <h4>Plan de acción</h4>
                </div>
            </div>
            <div class="row" >
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p>Objetivo 1:</p>
                    <textarea type="text" class="form-control txtAS"  id="txtAS45" rows="2"></textarea>
                </div>
            </div>
            <br>
            <div class="row" >
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p>Tarea 1:</p>
                    <textarea type="text" class="form-control txtAS"  id="txtAS46" rows="2"></textarea>
                </div>
            </div>
            <br>
            <div class="row" >
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <p>Fecha inicio 1:</p>
                    <input type="text" class="form-control txtAS"  id="txtAS47"/>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <p>Fecha seguimiento 1:</p>
                    <input type="text" class="form-control txtAS"  id="txtAS48"/>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <p>Fecha de término 1:</p>
                    <input type="text" class="form-control txtAS"  id="txtAS49"/>
                </div>
            </div>
            <br>
            <div class="row" >
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p>Resultado 1</p>
                    <textarea type="text" class="form-control txtAS"  id="txtAS50" rows="2"></textarea>
                </div>
            </div>
            
            <br>
            <hr style="border: none; border-bottom: 2px solid black;">
            <br>
            
            <div class="row" >
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p>Objetivo 2:</p>
                    <textarea type="text" class="form-control txtAS"  id="txtAS51" rows="2"></textarea>
                </div>
            </div>
            <br>
            <div class="row" >
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p>Tarea 2:</p>
                    <textarea type="text" class="form-control txtAS"  id="txtAS52" rows="2"></textarea>
                </div>
            </div>
            <br>
            <div class="row" >
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <p>Fecha inicio 2:</p>
                    <input type="text" class="form-control txtAS"  id="txtAS53"/>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <p>Fecha seguimiento 2:</p>
                    <input type="text" class="form-control txtAS"  id="txtAS54"/>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <p>Fecha de término 2:</p>
                    <input type="text" class="form-control txtAS"  id="txtAS55"/>
                </div>
            </div>
            <br>
            <div class="row" >
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p>Resultado 2</p>
                    <textarea type="text" class="form-control txtAS"  id="txtAS56" rows="2"></textarea>
                </div>
            </div>

            <br>
            <hr style="border: none; border-bottom: 2px solid black;">
            <br>

            <div class="row" >
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p>Objetivo 3:</p>
                    <textarea type="text" class="form-control txtAS"  id="txtAS57" rows="2"></textarea>
                </div>
            </div>
            <br>
            <div class="row" >
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p>Tarea 3:</p>
                    <textarea type="text" class="form-control txtAS"  id="txtAS58" rows="2"></textarea>
                </div>
            </div>
            <br>
            <div class="row" >
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <p>Fecha inicio 3:</p>
                    <input type="text" class="form-control txtAS"  id="txtAS59"/>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <p>Fecha seguimiento 3:</p>
                    <input type="text" class="form-control txtAS"  id="txtAS60"/>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <p>Fecha de término 3:</p>
                    <input type="text" class="form-control txtAS"  id="txtAS61"/>
                </div>
            </div>
            <br>
            <div class="row" >
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p>Resultado 3</p>
                    <textarea type="text" class="form-control txtAS"  id="txtAS62" rows="2"></textarea>
                </div>
            </div>
            <br>

            


              <!--  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                            <th scope="col">Objetivo</th>
                            <th scope="col">Tareas</th>
                            <th scope="col">Fecha inicio</th>
                            <th scope="col">Fecha seguimiento</th>
                            <th scope="col">Fecha de término</th>
                            <th scope="col">Resultado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <td><input class="form-control txtAS" id="txtAS45"/></td>
                            <td><input class="form-control txtAS" id="txtAS46"/></td>
                            <td><input class="form-control txtAS" id="txtAS47"/></td>
                            <td><input class="form-control txtAS" id="txtAS48"/></td>
                            <td><input class="form-control txtAS" id="txtAS49"/></td>
                            <td><input class="form-control txtAS" id="txtAS50"/></td>
                            </tr>
                            <tr>
                            <td><input class="form-control txtAS" id="txtAS51"/></td>
                            <td><input class="form-control txtAS" id="txtAS52"/></td>
                            <td><input class="form-control txtAS" id="txtAS53"/></td>
                            <td><input class="form-control txtAS" id="txtAS54"/></td>
                            <td><input class="form-control txtAS" id="txtAS55"/></td>
                            <td><input class="form-control txtAS" id="txtAS56"/></td>
                            </tr>
                            <tr>
                            <td><input class="form-control txtAS" id="txtAS57"/></td>
                            <td><input class="form-control txtAS" id="txtAS58"/></td>
                            <td><input class="form-control txtAS" id="txtAS59"/></td>
                            <td><input class="form-control txtAS" id="txtAS60"/></td>
                            <td><input class="form-control txtAS" id="txtAS61"/></td>
                            <td><input class="form-control txtAS" id="txtAS62"/></td>
                            </tr>
                            
                        </tbody>
                    </table>-->
                
           <!-- </div>-->
        </div>
        <!--END Plan de acción-->
        <br>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group margin-0">
                    <label>Comentarios del Gerente de distrito</label>
                    <textarea class="form-control txtAS" id="txtAS63"  rows="2"></textarea>
                </div>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group margin-0">
                    <label>Replica del Representante 1</label>
                    <textarea class="form-control txtAS" id="txtReplicaRepre"  rows="2"></textarea>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group margin-0">
                    <label>Replica del Representante 2</label>
                    <textarea class="form-control txtAS" id="txtReplicaRepre2"  rows="2"></textarea>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group margin-0">
                    <label>Replica del Representante 3</label>
                    <textarea class="form-control txtAS" id="txtReplicaRepre3"  rows="2"></textarea>
                </div>
            </div>
        </div>

      </div>
      <div class="modal-footer">
       <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
        <button type="button" class="btn btn-primary" id="btnGuardaCoaching">Guardar</button>
        <button type="button" class="btn btn-success" id="btnFinalizaCoaching">Finalizar</button>
      </div>
    </div>
  </div>
</div>
<script>

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

</script>