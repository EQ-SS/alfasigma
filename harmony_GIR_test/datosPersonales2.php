<input type="hidden" id="hdnIdPersona" value="" />
<input type="hidden" id="hdnRutaDatosPersonales" value="" />
<input type="hidden" id="hdnEspecialidadPersona" value="" />



<div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-0 m-t--25">
        <ul class="nav nav-tabs m-l--20 m-r--20 tab-col-blue p-l-5" role="tablist" style="background-color:#efefef;">
            <li id="perfilMedico2" role="presentation" class="active">
                <a href="#tabPerfilP" id="aPerfilPersona2" data-toggle="tab" class="show-tooltip">
                    <i class="material-icons">person</i>
                    <div class="divTooltip">Información</div>
                    <span class="hidden-txt-tabs">Información</span>
                </a>
            </li>
        </ul>
    </div>

    <div>
        <div class="row clearfix">
            <div class="col-lg-3 col-md-3 col-sm-5 col-xs-12 align-center">
                <img src="imagenes/nopic.jpg" class="user-image-doctor" />
            </div>
            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-12">
                <div class="row">
                    <div class="col-md-12"><span id="lblEspecialidadBaja" class="label bg-cyan label-esp"></span></div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="font-bold col-indigo text-inline">Dirección: </p><label id="lblDireccion2"></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="font-bold col-indigo text-inline">Brick: </p><label id="lblBrick2"></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="font-bold col-indigo text-inline">Consultorio: </p><label
                            id="lblConsultorio2"></label>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-md-12" style="height:14px;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="font-bold col-indigo text-inline">Categoría: </p><label id="lblCategoria2"></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="font-bold col-indigo text-inline">Cédula: </p><label id="lblCedula2"></label>
                    </div>
                </div>
            </div>
        </div>

        <hr class="style-hr" />
        <h3>Este médico se ha dado de baja</h3>
        <label>¿Desea reactivar al médico?</label>
        <div class="row m-t-15">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <button class="btn bg-indigo waves-effect btn-indigo" id="btnReactivarPersonaNuevo2">Reactivar</button>
                <div class="col-indigo m-t-5">
                    <i class="fas fa-info-circle font-15"></i>
                    <span>Al reactivarlo podrá visualizar toda la información del médico</span>
                </div>
            </div>
        </div>
    </div>
</div>