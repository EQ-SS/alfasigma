<input type="hidden" id="hdnIdEncuesta" />
<div class="row m-r--15 m-l--15">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center-ver-div">
		<div class="card m-b--15 card-add-new">
			<div class="header row padding-0">
				<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 m-t-15">
					<h2>
						Encuesta
					</h2>
				</div>
				<div class="col-lg-5 col-md-5 col-sm-8 col-xs-10 align-center m-t-10 m-b-10 display-inline">
					<button id="btnGuardarEncuestaGerente" type="button" class="m-t-5 btn bg-indigo waves-effect btn-indigo">
						Guardar
					</button>
				</div>
				<div class="col-lg-2 col-md-2 col-sm-4 col-xs-2 align-right m-t-10">
					<p id="btnCancelarEncuestaGerente" class="pointer p-t-5 btn-close-per">
						<i class="material-icons" data-toggle="tooltip" data-placement="top" title="Cerrar">close</i>
					</p>
				</div>
			</div>
			<div class="body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
						<label class="col-red">Representante *</label>
						<select id="sltRepreEncuesta" class="form-control" <?= $tipoUsuario == 4 ? 'disabled' : '' ?>>
						</select>
					</div>
					<div style="height: 80%;overflow:auto;" id="divPreguntasGerente" class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
<?php
					if($tipoUsuario == 4){
?>						
						<div><label class="col-red">Réplica</label><input class="form-control" type="text" id="txtReplica"></div>
<?php
					}
?>
					</div>
					<input type="hidden" id="hdnPreguntas" />
					<input type="hidden" id="hdnObligatorias" />
				</div>
			</div>
		</div>
	</div>
</div>
