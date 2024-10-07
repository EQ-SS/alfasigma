/**
 * Persona alerts
 */

function alertCambiarPass(pass, clave, idUsuario) {
    swal({
        title: "¿Está seguro de cambiar su contraseña?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Si, cambiar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            $('#divRespuesta').load("ajax/actualizaClave.php", {
                pass: pass,
                clave: clave,
                idUsuario: idUsuario
            });
        }
    });
}

function alertPassError() {
    swal({
        title: "Las contraseñas no coinciden",
        type: "error",
        confirmButtonText: "Aceptar"
    });
}

function alertPassActualError() {
    swal({
        title: "La contraseña actual es incorrecta",
        type: "error",
        confirmButtonText: "Aceptar"
    });
}

function alertPassOk() {
    swal({
        title: "Clave actualizada",
        type: "success",
        timer: 900,
        showConfirmButton: false,
        showLoaderOnConfirm: true
    });
}

function alertPassErrorAct() {
    swal({
        title: "La clave no se actualizó",
        type: "error",
        confirmButtonText: "Aceptar"
    });
}

function alertFaltanDatos() {
    swal({
        title: "Faltan datos y/o datos incorrectos",
        text: "Ingrese los datos que se solicitan",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertInstitucionM() {
    swal({
        title: "Faltan datos",
        text: "Seleccione nombre de la institución",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertFechaNacimientoMes() {
    swal({
        title: "Fecha de nacimiento incompleta",
        text: "Seleccione el mes",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertFechaNacimientoMes() {
    swal({
        title: "Fecha de nacimiento incompleta",
        text: "Seleccione el mes",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertFechaNacimientoAnio() {
    swal({
        title: "Fecha de nacimiento incompleta",
        text: "Seleccione el año",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertFechaNacimientoDia() {
    swal({
        title: "Fecha de nacimiento incompleta",
        text: "Seleccione el día",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertErrorServidor() {
    swal({
        title: "Oops",
        text: "No se pudo conectar con el servidor, intente más tarde",
        type: "error",
        confirmButtonText: "Aceptar"
    });
}

function alertErrorGeocoding(status) {
    swal({
        title: "Oops",
        text: "Geocoding no tuvo éxito debido a: " + status,
        type: "error",
        confirmButtonText: "Aceptar"
    });
}

function alertErrorAgregarMedico() {
    swal({
        title: "Lo sentimos",
        text: "Hubo problemas al guardar el registro",
        type: "error",
        confirmButtonText: "Aceptar"
    });
}

function alertEliminarMedico(idUsuario, idPersona, tipoUsuario, ruta) {
    swal({
        title: "¿Está seguro de eliminar el médico?",
        text: "Se eliminará permanentemente",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            swal({
                title: "Eliminado",
                text: "El médico fue eliminado",
                type: "success",
                timer: 500,
                showConfirmButton: false,
                showLoaderOnConfirm: true,
            }, function () {
                setTimeout(function () {
                    $('#divRespuesta').load("ajax/eliminarMedico.php", {
                        idPersona: idPersona,
                        idUsuario: idUsuario,
                        tipoUsuario: tipoUsuario,
                        ruta: ruta
                    });
                }, 500);
            });
        } else {
            swal("Cancelado", "El médico no fue eliminado", "error");
        }
    });
}

function alertEliminarPlan(idPlan) {
    swal({
        title: "¿Está seguro de eliminar el plan?",
        text: "Se eliminará permanentemente",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            $('#divRespuesta').load("ajax/eliminarPlan.php", {
                idPlan: idPlan
            });
        } else {
            swal("Cancelado", "El plan no fue eliminado", "error");
        }
    });
}

function alertEliminarPlanInst(idPlan) {
    swal({
        title: "¿Está seguro de eliminar el plan?",
        text: "Se eliminará permanentemente",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            $('#divRespuesta').load("ajax/eliminarPlanInst.php", {
                idPlan: idPlan
            });
        } else {
            swal("Cancelado", "El plan no fue eliminado", "error");
        }
    });
}

function alertEliminarPlanOk() {
    swal({
        title: "Plan eliminado",
        type: "success",
        timer: 1000,
        showConfirmButton: false,
        showLoaderOnConfirm: true
    });
}


function alertEliminarPlanError() {
    swal({
        title: "Hubo un error",
        text: "El plan no fue eliminado",
        type: "error",
        confirmButtonText: "Aceptar",
    });
}

function alertEliminarVisita(idVisita,fecha,tipoUsuario,idUsuario,ruta,idPers,idMedicoC) {
    swal({
        title: "¿Está seguro de eliminar la visita?",
        text: "Se eliminará permanentemente",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            $('#divRespuesta').load("ajax/eliminarVisita.php", {
                idVisita: idVisita
            },function(){
                $("#divRespuesta").load("ajax/cambiarCirculoStatus.php", {fecha:fecha,tipoUsuario:tipoUsuario,idUsuario:idUsuario,ruta:ruta,idPers:idPers,idMedicoC:idMedicoC});
            });
            
        } else {
            swal("Cancelado", "La visita no fue eliminada", "error");
        }
    });
}

function alertEliminarVisitaInst(idVisita) {
    swal({
        title: "¿Está seguro de eliminar la visita?",
        text: "Se eliminará permanentemente",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            $('#divRespuesta').load("ajax/eliminarVisitaInst.php", {
                idVisita: idVisita
            });
        } else {
            swal("Cancelado", "La visita no fue eliminada", "error");
        }
    });
}

function alertEliminarVisitaOk() {
    swal({
        title: "Visita eliminada",
        type: "success",
        timer: 1000,
        showConfirmButton: false,
        showLoaderOnConfirm: true
    });
}

function alertEliminarVisitaError() {
    swal({
        title: "Hubo un error",
        text: "La visita no fue eliminada",
        type: "error",
        confirmButtonText: "Aceptar",
    });
}

function alertFirmaBlanco() {
    swal({
        title: "Firma incorrecta",
        text: "La firma que intenta guardar está en blanco",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertFirmaMed() {
    swal({
        title: "Al menos un producto requiere la firma del médico",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertFirma() {
    swal({
        title: "Al menos un producto requiere la firma",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertErrorFirmaProd() {
    swal({
        title: "Firma incorrecta",
        text: "Debe entregar al menos un producto para guardar la firma",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertErrorMuestra() {
    swal({
        title: "Oops",
        text: "No se grabó la muestra",
        type: "error",
        confirmButtonText: "Aceptar"
    });
}

function alertErrorTopProductos() {
    swal({
        title: "No se grabó top productos",
        type: "error",
        confirmButtonText: "Aceptar"
    });
}

function alertErrorPlan() {
    swal({
        title: "Oops",
        text: "No se guardó el plan",
        type: "error",
        confirmButtonText: "Aceptar"
    });
}

function alertErrorVisita() {
    swal({
        title: "Oops",
        text: "No se guardó la visita",
        type: "error",
        confirmButtonText: "Aceptar"
    });
}

function alertErrorGuardarRegistro() {
    swal({
        title: "Oops",
        text: "Hubo problemas al guardar el registro",
        type: "error",
        confirmButtonText: "Aceptar"
    });
}

function alertPlaneaFechasAnt() {
    swal({
        title: "No se puede planear en fechas anteriores",
        text: "Al día de hoy",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertPlaneaFechasPos() {
    swal({
        title: "No se puede reportar en dias posteriores",
        text: "a la fecha del día de hoy",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertPlanExistente() {
    swal({
        title: "Plan ya existente",
        text: "Registre un plan diferente",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertPlanCodigo() {
    swal({
        title: "Oops",
        text: "Seleccione el Código del Plan",
        type: "error",
        confirmButtonText: "Aceptar"
    });
}

function alertPlanHora() {
    swal({
        title: "Oops",
        text: "Seleccione la hora del Plan",
        type: "error",
        confirmButtonText: "Aceptar"
    });
}

function alertVisitaExistente() {
    swal({
        title: "Ya existe una visita en esa fecha",
        text: "Seleccione una fecha diferente",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertCuotaOk() {
    swal({
        title: "Ya se cumplió con la cuota",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertMasPiezas(max) {
    swal({
        title: "Sólo puede entregar " + max + " piezas de ese producto",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertExistenciaPiezas(existencia) {
    swal({
        title: "Solo tienes " + existencia + " piezas de ese producto",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertPromocionar() {
    swal({
        title: "Debe promocionar al menos un producto",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertVisitaHora() {
    swal({
        title: "Oops",
        text: "Seleccione la hora de la Visita",
        type: "error",
        confirmButtonText: "Aceptar"
    });
}

function alertComenVisita() {
    swal({
        title: "Ingrese el resultado de la visita",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertInfoSiguienteVisita() {
    swal({
        title: "Ingrese el objetivo de la siguiente visita",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertCodVisita() {
    swal({
        title: "Seleccione el código de la visita",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertReportPlan() {
    swal({
        title: "Ya se ha reportado ese plan",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertReportarError() {
    swal({
        title: "No se puede reportar en esa fecha",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertFechaObjMayor() {
    swal({
        title: "La fecha Objetivo debe ser mayor a la fecha final",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertFechaObjMenor() {
    swal({
        title: "La fecha Objetivo debe ser menor a la fecha final",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertFechaInicial() {
    swal({
        title: "La fecha inicial no puede ser mayor a la fecha final",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertNumHoras() {
    swal({
        title: "Indique el número de horas",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertMaxHoras() {
    swal({
        title: "El máximo a reportar son 8 Hrs.",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertFechaFinal() {
    swal({
        title: "La fecha final no puede ser mayor a la fecha inicial",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertSelRepresentante() {
    swal({
        title: "Seleccione un representante",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertSelUnRepresentante() {
    swal({
        title: "Seleccione sólo un representante",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertIngresarComen() {
    swal({
        title: "Ingrese un comentario",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertIngresarMotivo() {
    swal({
        title: "Seleccione un motivo",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertSeleccionaMed() {
    swal({
        title: "Seleccione al menos un médico",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertNuevaRuta() {
    swal({
        title: "Seleccione la nueva ruta",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertSeleccionaRuta() {
    swal({
        title: "Seleccione una ruta",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertCambioRutaRealizado() {
    swal({
        title: "Cambio de ruta realizado",
        type: "success",
        timer: 1000,
        showConfirmButton: false,
        showLoaderOnConfirm: true
    });
}
/*FIN persona */

function alertSelArchivo() {
    swal({
        title: "Seleccione un archivo",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertInfoArchivo() {
    swal({
        title: "Faltan datos",
        text: "La informacion del archivo no puede ir en blanco",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertExisteArchivo() {
    swal({
        title: "El archivo que desea subir ya existe",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertSesionExpirada() {
    swal({
        title: "Oops!",
        text: "Tu sesión ha expirado",
        imageUrl: "images/sad-face.png",
        confirmButtonText: "Aceptar"
    }, function (isConfirm) {
        if (isConfirm) {
            $('#imgLogout').click();
        }
    });
}

function alertActualizando() {
    swal({
        title: "Actualizando",
        text: "Espere por favor",
        imageUrl: "images/fenix1.gif",
        timer: 2000,
        showConfirmButton: false,
        showLoaderOnConfirm: true,
    }, function () {
        setTimeout(function () {
            swal({
                title: "Actualización completada",
                type: "success",
                timer: 1000,
                showConfirmButton: false,
            });
        }, 2000);
    });
}

function alertErrorActualizar() {
    swal({
        title: "No se pudo actualizar",
        type: "error",
        confirmButtonText: "Aceptar"
    })
}

function alertErrorReactivar() {
    swal({
        title: "No se pudo reactivar al médico",
        type: "error",
        confirmButtonText: "Aceptar"
    })
}

function alertCantidadBlanco() {
    swal({
        title: "La cantidad aceptada no puede ir en blanco",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertCantidadMayor() {
    swal({
        title: "La cantidad aceptada no puede ser mayor a la recibida",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertSeleccionaInst() {
    swal({
        title: "Seleccione al menos una institución",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertSeleccionaActividad() {
    swal({
        title: "Seleccione al menos una actividad",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertSelUnDepartamento() {
    swal({
        title: "Debe tener al menos un departamento",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertSelDepartamento() {
    swal({
        title: "Seleccione un departamento",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertEliminarMedicoRepre() {
    swal({
        title: "Eliminación del médico pendiente",
        text: "Este movimiento tiene que ser aprobado",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertEliminarInstRepre() {
    swal({
        title: "Eliminación de institución pendiente",
        text: "Este movimiento tiene que ser aprobado",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertEliminarInst(idInst, idUsuario, tipoUsuario) {
    swal({
        title: "¿Está seguro de eliminar la institución?",
        text: "Se eliminará permanentemente",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            swal({
                title: "Eliminado",
                text: "La institución fue eliminada",
                type: "success",
                timer: 500,
                showConfirmButton: false,
                showLoaderOnConfirm: true,
            }, function () {
                setTimeout(function () {
                    $('#divRespuesta').load('ajax/eliminarInst.php', {
                        idInst: idInst,
                        idUsuario: idUsuario,
                        tipoUsuario: tipoUsuario
                    });
                }, 500);
            });
        } else {
            swal("Cancelado", "La institución no fue eliminada", "error");
        }
    });
}

function alertMotivoRechazo(idProdForm, fecha, producto) {
    var prodAjuste = fecha + " " + producto;
    swal({
        title: "Manejo de existencias",
        text: "<h2>" + prodAjuste + "</h2><div class=\"form-group align-left\"><label class=\"col-red\">Motivo de rechazo *</label><select id=\"sltMotivoRechazoMuestra\" class=\"form-control\"><option value=\"00000000-0000-0000-0000-000000000000\">Seleccione</option></select><label id=\"selMotivAlert\" class=\"error2\" style=\"display:none;\">Seleccione un motivo</label></div>",
        html: true,
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Rechazar",
        cancelButtonText: "Cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            var motivo = $("#sltMotivoRechazoMuestra").val();
            if (motivo == '00000000-0000-0000-0000-000000000000') {
                $("#selMotivAlert").show();
            } else {
                $("#selMotivAlert").hide();
                $("#" + idProducto + "Rech").removeClass('btn-inv-head-focus');
                $("#divRespuesta").load("ajax/guardaAjusteMaterial.php", {
                    idProdForm: idProdForm,
                    cantidad: '',
                    catidadAceptada: '',
                    motivo: motivo,
                    movimiento: 'rechazado'
                });
            }
        } else {
            $("#" + idProdForm + "Rech").removeClass('btn-inv-head-focus');
        }
    });
}

function alertConfirmarReactivar(idPersonaReactivar) {
    swal({
        title: "¿Está seguro de reactivar al médico?",
        text: "Se agregará al fichero",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Si, reactivar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            setTimeout(function () {
                $('#divRespuesta').load("ajax/reactivarMedico.php", {
                    idPersona: idPersonaReactivar
                });
            }, 500);
        }
    });
}

function alertMailNoValido() {
    swal({
        title: "Correo Incorrecto",
        text: "Debe ingresar un correo válido",
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}

function alertEvento(mensaje) {
    swal({
        title: mensaje,
        type: "warning",
        confirmButtonText: "Aceptar"
    });
}