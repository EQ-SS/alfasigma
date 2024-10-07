<!-- Large modal Nueva Persona -->


<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true" id="ModalCargaDatosPersona">
    <div class="modal-dialog modal-lg" style=" overflow-y: initial !important;">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabelAsignaMedico"><i class="fas fa-user-md"></i> Carga Medicos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" style="height: 80vh;overflow-y: auto;">
            <input type="hidden" id="hdnTextValidaCargaPersona">

            <input type="hidden" id="hdnTextDatosCargaPersona">

            <div class="row">
                <div class="col-sm-8">
                    <label for="formFile" class="form-label">Adjuntar Archivo</label>
                    <input accept=".xls,.xlsx" class="form-control" type="file" id="formFile">

                </div>
                <div class="col-sm-4">
                    <br>
                    
                    <button id="btnValidaCargaPersona" type="button" class="btn btn-primary mb-3" style="display:none;">validar Registros</button>

                </div>
            </div>
            <br>
            <br>

            <div class="row">
                <div class="col-sm-12">


                    <table class="table table-bordered" id="tblCargaDatosPersona">
                            <thead>
                                <tr>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Tipo</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Apellido Paterno</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Apellido Materno</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Nombre(s)</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Sexo</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Especialidad Cartera</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Sub Especialidad</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Cedula</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Categoria Audit</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Estatus</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Pacientes por semana</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Honorarios</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Fecha de nacimiento</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Tel1</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Tel2</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Celular</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Email1</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Email2</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Nombre del asistente</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Teléfono del asistente</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Email del asistente</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Frecuencia de la visita</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Dificultad de la visita</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Nombre de la Institución</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Calle</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Num. Interior</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">C.P</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Colonia</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Ciudad</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Estado</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Nombre del Brick</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Brick</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Full nombre hospital</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Preferencia de contacto</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Acepta apoyo</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">¿Porqué?</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Médico Botiquín</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Compra directa</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Líder de Opinión</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Speaker</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Tipo de Consulta</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Representante</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Cuadro Basico</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Obj Corto Plazo</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Obj Largo Plazo</th>
                                    <th style="color:white;background-color:#1a45b8;text-align:center;" scope="col">Comentarios Generales</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--
                                <tr>
                                    <td>Consultorio</td>
                                    <td>Martinez</td>
                                    <td>Servin</td>
                                    <td>Roberto Carlos</td>
                                    <td>Masculino</td>
                                    <td>Acupuntura</td>
                                    <td>Administrativo</td>
                                    <td>43534535</td>
                                    <td>1</td>
                                    <td>Activo</td>
                                    <td>0 A 25</td>
                                    <td>BAJOS</td>
                                    <td>30-07-1999</td>
                                    <td>5548415540</td>
                                    <td>5548415540</td>
                                    <td>5548415540</td>
                                    <td>carlosservin3007@outlook.com</td>
                                    <td>carlosservin3007@outlook.com</td>
                                    <td>Eduardo</td>
                                    <td>5515056869</td>
                                    <td>rmartinez@smar-scale.com</td>
                                    <td>1</td>
                                    <td>ENTRE PACIENTES</td>
                                    <td>ANGELES</td>
                                    <td>LA CRUZ</td>
                                    <td>21</td>
                                    <td>06870</td>
                                    <td>PAULINO NAVARRO</td>
                                    <td>CDMX</td>
                                    <td>CIUDAD DE MEXICO</td>
                                    <td>SIN NOMBRE</td>
                                    <td>01022038</td>
                                    <td>ANGELES PEDREGAL</td>
                                    <td>PERSONAL</td>
                                    <td>SI</td>
                                    <td>NECESITA APOYO</td>
                                    <td>SI</td>
                                    <td>SI</td>
                                    <td>SI</td>
                                    <td>SI</td>
                                    <td>CONSULTA PRIVADA</td>
                                    <td>1143154F5</td>
                                </tr>
-->
                            
            
                             </tbody>
                    </table>

                </div>
            </div>

               
                


            </div>





            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnGuardaCargaPersonas">Guardar</button>
            </div>



        </div>
    </div>
</div>

<!-- END Large modal Nueva Persona -->
<script>
   
</script>