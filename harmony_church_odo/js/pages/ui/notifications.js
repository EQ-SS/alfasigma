function notificationPersonaRegistro() {
    $.notify({
        icon: 'glyphicon glyphicon-ok-sign',
        message: 'Médico guardado exitosamente'
    },{
        type: "success",
        allow_dismiss: true,
        placement: {
            from: "top",
            align: "center"
        },
        template: '<div data-notify="container" class="bootstrap-notify-container alert alert-{0}" role="alert">' +
            '<button type="button" aria-hidden="true" class="close col-white" data-notify="dismiss">×</button>' +
            '<span data-notify="icon" class="m-r-5 font-13"></span> ' +
            '<span data-notify="title">{1}</span> ' +
            '<span data-notify="message">{2}</span>' +
            '</div>'
    });
}

function notificationInstRegistro() {
    $.notify({
        icon: 'glyphicon glyphicon-ok-sign',
        message: 'Institución guardada exitosamente'
    },{
        type: "success",
        allow_dismiss: true,
        placement: {
            from: "top",
            align: "center"
        },
        template: '<div data-notify="container" class="bootstrap-notify-container alert alert-{0}" role="alert">' +
            '<button type="button" aria-hidden="true" class="close col-white" data-notify="dismiss">×</button>' +
            '<span data-notify="icon" class="m-r-5 font-13"></span> ' +
            '<span data-notify="title">{1}</span> ' +
            '<span data-notify="message">{2}</span>' +
            '</div>'
    });
}

function notificationPlanGuardado() {
    $.notify({
        icon: 'glyphicon glyphicon-ok-sign',
        message: 'Plan registrado'
    },{
        type: "success",
        allow_dismiss: true,
        placement: {
            from: "top",
            align: "center"
        },
        template: '<div data-notify="container" class="bootstrap-notify-container alert alert-{0}" role="alert">' +
            '<button type="button" aria-hidden="true" class="close col-white" data-notify="dismiss">×</button>' +
            '<span data-notify="icon" class="m-r-5 font-13"></span> ' +
            '<span data-notify="title">{1}</span> ' +
            '<span data-notify="message">{2}</span>' +
            '</div>'
    });
}

function notificationVisitaGuardada() {
    $.notify({
        icon: 'glyphicon glyphicon-ok-sign',
        message: 'Visita registrada'
    },{
        type: "success",
        allow_dismiss: true,
        placement: {
            from: "top",
            align: "center"
        },
        template: '<div data-notify="container" class="bootstrap-notify-container alert alert-{0}" role="alert">' +
            '<button type="button" aria-hidden="true" class="close col-white" data-notify="dismiss">×</button>' +
            '<span data-notify="icon" class="m-r-5 font-13"></span> ' +
            '<span data-notify="title">{1}</span> ' +
            '<span data-notify="message">{2}</span>' +
            '</div>'
    });
}

function notificationMedActivado() {
    $.notify({
        icon: 'glyphicon glyphicon-ok-sign',
        message: 'Médico Activado'
    },{
        type: "success",
        allow_dismiss: true,
        placement: {
            from: "top",
            align: "center"
        },
        template: '<div data-notify="container" class="bootstrap-notify-container alert alert-{0}" role="alert">' +
            '<button type="button" aria-hidden="true" class="close col-white" data-notify="dismiss">×</button>' +
            '<span data-notify="icon" class="m-r-5 font-13"></span> ' +
            '<span data-notify="title">{1}</span> ' +
            '<span data-notify="message">{2}</span>' +
            '</div>'
    });
}

function notificationMovimientoAprovado() {
    $.notify({
        icon: 'glyphicon glyphicon-ok-sign',
        message: 'Este movimiento ha sido aprobado '
    },{
        type: "success",
        allow_dismiss: true,
        placement: {
            from: "top",
            align: "center"
        },
        template: '<div data-notify="container" class="bootstrap-notify-container alert alert-{0}" role="alert">' +
            '<button type="button" aria-hidden="true" class="close col-white" data-notify="dismiss">×</button>' +
            '<span data-notify="icon" class="m-r-5 font-13"></span> ' +
            '<span data-notify="title">{1}</span> ' +
            '<span data-notify="message">{2}</span>' +
            '</div>'
    });
}

/*function notificationArchivoSubido() {
    $.notify({
        icon: 'glyphicon glyphicon-ok-sign',
        message: 'El archivo ha sido subido exitosamente'
    },{
        type: "success",
        allow_dismiss: true,
        placement: {
            from: "top",
            align: "center"
        },
        template: '<div data-notify="container" class="bootstrap-notify-container alert alert-{0}" role="alert">' +
            '<button type="button" aria-hidden="true" class="close col-white" data-notify="dismiss">×</button>' +
            '<span data-notify="icon" class="m-r-5 font-13"></span> ' +
            '<span data-notify="title">{1}</span> ' +
            '<span data-notify="message">{2}</span>' +
            '</div>'
    });
}*/

function notificationAprobacionAceptada() {
    $.notify({
        icon: 'glyphicon glyphicon-ok-sign',
        message: 'Este movimiento ha sido aprobado.'
    },{
        type: "success",
        allow_dismiss: true,
        placement: {
            from: "top",
            align: "center"
        },
        template: '<div data-notify="container" class="bootstrap-notify-container alert alert-{0}" role="alert">' +
            '<button type="button" aria-hidden="true" class="close col-white" data-notify="dismiss">×</button>' +
            '<span data-notify="icon" class="m-r-5 font-13"></span> ' +
            '<span data-notify="title">{1}</span> ' +
            '<span data-notify="message">{2}</span>' +
            '</div>'
    });
}