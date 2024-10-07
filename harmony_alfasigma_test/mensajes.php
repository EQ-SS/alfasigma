<section class="content">
	<div class="container-fluid">
		<div class="block-header">
			<div class="row m-t--10">
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
					<h2>
						<i class="fas fa-envelope-square"></i>
						<span>MENSAJES</span>
					</h2>
				</div>
			</div>
		</div>

		<div class="row clearfix">
			<!--Lista de mensajes---->
			<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 plan-list">
				<div class="card">
					<div class="body cardInfCal" style="height: 600px; overflow: auto;">
						<!--------------------------------->
						
						<div id="tabsMensajes">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-0 m-t--20">
								<ul class="nav nav-tabs m-l--35 m-r--35 tab-col-blue p-l-5" role="tablist"
									style="background-color:#efefef;">
									<li role="presentation" class="active">
										<a id="tabEntradaCabecera" href="#tabEntradaMensajes" data-toggle="tab"
											class="show-tooltip p-t-14">
											<!--<i class="fas fa-handshake top-0 p-b-3"></i>-->
											<div class="divTooltip">Entrada</div>
											<span class="hidden-txt-tabs">&nbsp;&nbsp;&nbsp;Entrada</span>
										</a>
									</li>
									<li role="presentation">
										<a id="tabTodosCabecera" href="#tabTodosMensajes" data-toggle="tab" class="show-tooltip p-t-14">
											<!--<i class="fas fa-capsules top-0 p-b-3"></i>-->
											<div class="divTooltip">Todos</div>
											<span class="hidden-txt-tabs">Todos</span>
										</a>
									</li>
									<li role="presentation">
										<a id="tabEnviadosCabecera" href="#tabEnviadosMensajes" data-toggle="tab" class="show-tooltip p-t-14">
											<!--<i class="fas fa-vial top-0 p-b-3"></i>-->
											<div class="divTooltip">Enviados</div>
											<span class="hidden-txt-tabs">Enviados</span>
										</a>
									</li>
								</ul>
							</div>

							<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
								<div class="new-div" id="cargandoInfVisitaMed">
									<div id="tabEntradaMensajes" class="row">
										<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
											<div class="m-b-15 m-t--5">
												<div class="input-group margin-0">
													<input class="form-control buscaMedInst" type="text" id="txtBuscarMensajeEntrada" placeholder="Busqueda por nombre">
													<span class="input-group-addon padding-0 buscaMedInstSpan">
														<button id="btnBuscarMensajeEntrada" class="btn waves-effect btn-indigo2" style="padding: 4px 12px;">
															<i class="glyphicon glyphicon-search" style="color:#777; font-size:14px;"></i>
														</button>
													</span>
												</div>
											</div>

											<div style="height:480px;overflow-y:auto;overflow-x:hide;" id="tbMensajesEntrada">
												
											</div>
										</div>
									</div>

									<div id="tabTodosMensajes" class="row">
										<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
											<div class="m-b-15 m-t--5">
												<div class="input-group margin-0">
													<input class="form-control buscaMedInst" type="text" id="txtBuscarMensajeEntrada" placeholder="Busqueda por nombre">
													<span class="input-group-addon padding-0 buscaMedInstSpan">
														<button id="btnBuscarMensajeEntrada" class="btn waves-effect btn-indigo2" style="padding: 4px 12px;">
															<i class="glyphicon glyphicon-search" style="color:#777; font-size:14px;"></i>
														</button>
													</span>
												</div>
											</div>

											<div style="height:480px;overflow-y:auto;overflow-x:hide;" id="tbMensajesTodos">
												
											</div>
										</div>
									</div>

									<div id="tabEnviadosMensajes" class="row">
										<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
											<div class="m-b-15 m-t--5">
												<div class="input-group margin-0">
													<input class="form-control buscaMedInst" type="text" id="txtBuscarMensajeEntrada" placeholder="Busqueda por nombre">
													<span class="input-group-addon padding-0 buscaMedInstSpan">
														<button id="btnBuscarMensajeEntrada" class="btn waves-effect btn-indigo2" style="padding: 4px 12px;">
															<i class="glyphicon glyphicon-search" style="color:#777; font-size:14px;"></i>
														</button>
													</span>
												</div>
											</div>

											<div style="height:480px;overflow-y:auto;overflow-x:hide;" id="tbMensajesEnviados">
												
											</div>
										</div>
									</div>
									
								</div>
							</div>
						</div>
						
						<!--------------------------------->
					</div>
				</div>
			</div>
			<!--# END Lista de mensajes-->

			<div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
				<!--<div class="card" style="height:735px;">-->
				<div class="card" style="height: 600px; overflow: auto;">
					<div class="header">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 align-left add-m-b">
								<div class="display-inline">
									<h2>
										<p class="font-bold col-indigo text-inline">De:</p>
										<span class="font-bold text-inline" id="lblRemitente"></span>
										<br><br>
										<p class="font-bold col-indigo text-inline">Para:</p>
										<span class="font-bold text-inline" id="lblDestinatario"></span>
									</h2>
								</div>
							</div>

							<!--<div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
								<div class="display-flex">
									
									asasasas
								</div>
							</div>-->
						</div>
					</div>
					<div class="body">
						<div class="cardInfCal">
							<div class="row" style="padding:0 15px;">
								<span class="font-bold text-inline" id="lblAsunto"></span>
							</div>
						</div>
						<hr>
						<div class="cardInfCal">
							<div class="row" style="padding:0 15px;">
								<label class="font-bold" id="lblMensaje"></label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="fixed-action-btn" id="btnCalMed">
			<a class="btn-floating btn-large bg-red btn-txt" id="imgAgregarMensaje">
				<i class="material-icons">add</i>
				<span id="spanP" class="col-white m-l-10" style="display:none;">AGREGAR</span>
			</a>
			
		</div>
	</div>
</section>