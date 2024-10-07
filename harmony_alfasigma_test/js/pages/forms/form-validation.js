function agregarPersona() {
    $("#formAgregarPersona").validate({ // initialize plugin on the form
        rules: {
			tipoPersona: {
				fielSelected: true
			},
            apellidoP: {
                required: true,
                lettersonly: true
            },
            apellidoM: {
				required: true,
                lettersonly: true
            },
            nombreP: {
                required: true,
                lettersonly: true
            },
            cedulaP: {
				required: true,
                numbersonly2: true
            },
            especialidadP: {
				required: true,
                fielSelected: true
            },
			categoriaP: {
				required: false,
                fielSelected: true
            },
			sltSegmentacionFlonorPersonaNuevo: {
				required: true,
                fielSelected: true
            },
			sltSegmentacionVesselPersonaNuevo: {
				required: true,
                fielSelected: true
            },
			sltSegmentacionZirfosPersonaNuevo: {
				required: true,
                fielSelected: true
            },
			sltSegmentacionAtekaPersonaNuevo: {
				required: true,
                fielSelected: true
            },
            sltEstiloDisc: {
				required: true,
                fielSelected: true
            },
            pacientesSemanaP: {
				required: true,
                fielSelected: true
            },
            honorariosP: {
				required: true,
                fielSelected: true
            },
            estatusP: {
				required: true,
                fielSelected: true
            },
            nombreInstP: {
                nombreInsPer: true
            },
            /*email1PerfilP: {
				required: true,
                email: true
            },
            email2PerfilP: {
                email: true
            },*/
            emailPerfilP: {
                email: true
            },
            tel1P: {
				required: true,
                numbersonly: true
            },
            tel2P: {
                numbersonly: true
            },
            celP: {
                numbersonly: true
            },
            telefono1P: {
                numbersonly: true
            },
            telefono2P: {
                numbersonly: true
            },
			field13P: {
				fielSelected: true
			},
			field14PFlonorm: {
				fielSelected: true
			},
			field15P: {
				fielSelected: true
			},
			field16P: {
				fielSelected: true
			},
			field17P: {
				fielSelected: true
			},
			field18P: {
				fielSelected: true
			},
            nombreInstPer: {
                validaNombreInst: true
            }

        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error)
        },

        messages: {
            apellidoP: {
                required: "Ingrese apellido paterno"
            },
			apellidoM: {
                required: "Ingrese apellido materno"
            },
            nombreP: {
                required: "Ingrese el nombre"
            },
            cedulaP:{
                required:"Ingrese la cédula"
            },			
            tipoPersona: "Seleccione el tipo de persona",
            especialidadP: "Seleccione la especialidad",
            pacientesSemanaP: "Seleccione pacientes por semana",
            honorariosP: "Seleccione los honorarios",
            estatusP: "Seleccione el estatus",
			categoriaP: "Seleccione la categoría",
            nombreInstP: "Seleccione nombre de la institución",
            email1PerfilP: "Ingrese un correo válido",
            email2PerfilP: "Ingrese un correo válido",
            emailPerfilP: "Ingrese un correo válido",
			tel1P: "Ingrese el número telefónico",
			field13P: "Seleccione la categoría AlfaSigma",
			field14PFlonorm: "Seleccione la categoría Flonorm",
			field15P: "Seleccione la categoría Vessel",
			field16P: "Seleccione la categoría Ateka",
			field17P: "Seleccione la categoría Esoxx",
			field18P: "Seleccione la categoría Zifros",
			sltSegmentacionFlonorPersonaNuevo: "Seleccione la Segmentacion Flonor",
			sltSegmentacionVesselPersonaNuevo: "Seleccione la Segmentacion Vessel",
			sltSegmentacionZirfosPersonaNuevo: "Seleccione la Segmentacion Zirfos",
			sltSegmentacionAtekaPersonaNuevo: "Seleccione la Segmentacion Ateka",
            sltEstiloDisc:"Seleccione Estilo Disc"
			
        },

        ignore: "",

        highlight: function (input) {
            $(input).addClass('invalid');
        },
        unhighlight: function (input) {
            $(input).removeClass('invalid');
        },
        highlight: function (select) {
            $(select).addClass('invalid-slt');
        },
        unhighlight: function (select) {
            $(select).removeClass('invalid-slt');
        }
    });

    
}

$.validator.addMethod("lettersonly", function (value, element) {
        return this.optional(element) || /^[ a-záéíóúüñ]+$/i.test(value);
    },
    "Ingrese sólo letras"
);

$.validator.addMethod("numbersonly", function (value, element) {
        return this.optional(element) || /^[ 0-9()+-]+$/i.test(value);
    },
    "Ingrese un teléfono válido"
);

$.validator.addMethod("numbersonly2", function (value, element) {
    return this.optional(element) || /^[ 0-9]+$/i.test(value);
},
"Ingrese sólo números"
);

$.validator.addMethod("numbersonly3", function (value, element) {
    return this.optional(element) || /^[0-9]+$/i.test(value);
},
"Ingrese sólo números enteros"
);

$.validator.addMethod("fielSelected", function (value, element) {
    if (value == '00000000-0000-0000-0000-000000000000' || value == '0') {
        return false; // PASS validation when REGEX matches
    } else {
        return true; // FAIL validation
    };
});

$.validator.addMethod("validaNombreInst", function () {
    if ($('#txtNombreInstPersonaNuevo').val() == '' && $('#txtCalleInstPersonaNuevo').val() == '') {
        //alertInstitucionM();
        //$('#txtNombreInstPersonaNuevo').addClass('invalid');
        $('#txtNombreInstPersonaNuevoError').show();
        return false;
    }else{
       // $('#txtNombreInstPersonaNuevo').removeClass('invalid-slt');
        //$('#txtNombreInstPersonaNuevoError').hide();
        return true;
    }
},
""
);

function agregarInstitucion() {
    $("#formAgregarInst").validate({ // initialize plugin on the form
        rules: {
            tipoI: "required",
			subTipoI: "required",
			formatoI: "required",
            estatusI: "required",
            nombreI: "required",
            calleI: "required",
            codigoPostalI: {
                required: true,
                numbersonly2: true,
                minlength: 5
            },
			numExtI: "required",
            emailI: {
                email: true
            },
            tel1I: {
                numbersonly: true
            },
            tel2I: {
                numbersonly: true
            }

        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error)
        },

        messages: {
            tipoI: "Seleccione el tipo de Institución",
			subTipoI: "Seleccione el subtipo de la Institución",
			formatoI: "Seleccione el formato de la Institución",
            estatusI: "Seleccione el estatus",
            nombreI: "Ingrese el nombre de la institución",
            calleI: "Ingrese la calle de la institución",
			numExtI: "Ingrese el número exterior",
            codigoPostalI: {
                required: "Ingrese el código postal",
                minlength: "El C.P. está formado por 5 dígitos"
            },
            emailI: "Ingrese un correo válido"
        },

        ignore: "",

        highlight: function (input) {
            $(input).addClass('invalid');
        },
        unhighlight: function (input) {
            $(input).removeClass('invalid');
        },
        highlight: function (select) {
            $(select).addClass('invalid-slt');
        },
        unhighlight: function (select) {
            $(select).removeClass('invalid-slt');
        }
    });
}

function agregarDepto() {
    $("#formAgregarDepto").validate({ // initialize plugin on the form
        rules: {
            tipoD: "required",
            nombreD: "required",
            nombreResD: "required",
            codigoPostalD: {
                numbersonly: true,
                minlength: 5
            },
            emailD: {
                email: true
            }

        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error)
        },

        messages: {
            tipoD: "Seleccione el tipo de departamento",
            nombreD: "Ingrese nombre del departamento",
            nombreResD: "Ingrese nombre del responsable",
            codigoPostalD: {
                required: "Ingrese el código postal",
                minlength: "El C.P. está formado por 5 dígitos"
            },
            emailD: "Ingrese un correo válido"
        },

        ignore: "",

        highlight: function (input) {
            $(input).addClass('invalid');
        },
        unhighlight: function (input) {
            $(input).removeClass('invalid');
        },
        highlight: function (select) {
            $(select).addClass('invalid-slt');
        },
        unhighlight: function (select) {
            $(select).removeClass('invalid-slt');
        }
    });
}

function validaMuestrasMed() {
    $("#formMuestrasMed").validate({ // initialize plugin on the form
        rules: {
            muestraMed: {
                numbersonly3: true
            }
        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error)
        },

        ignore: "",

        highlight: function (input) {
            $(input).addClass('invalid');
        },
        unhighlight: function (input) {
            $(input).removeClass('invalid');
        }
    });
}