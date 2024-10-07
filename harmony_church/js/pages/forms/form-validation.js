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
                lettersonly: true
            },
            nombreP: {
                required: true,
                lettersonly: true
            },
			/*subespecialidadP: {
                fielSelected: true
            },*/
			especialidadP: {
                fielSelected: true
            },
			pacientesSemanaP: {
                fielSelected: true
            },
            honorariosP: {
                fielSelected: true
            },
            cedulaP: {
                required: true,
                numbersonly2: true
            },
			/*sexoP: {
                fielSelected: true
            },*/
			fechaNacDiaP: {
				required: true,
				fielSelected: true
			},
			fechaNacMesP: {
				required: true,
				fielSelected: true
			},
			fechaNacAñoP: {
				required: true,
				fielSelected: true
			},
            estatusP: {
                fielSelected: true
            },
            nombreInstP: {
                nombreInsPer: true
            },
            email1PerfilP: {
                required: true, 
				email: true
            },
            /*email2PerfilP: {
                email: true
            },
            emailPerfilP: {
                email: true
            },*/
            tel1P: {
				required: true,
                numbersonly: true
            },
            tel2P: {
                numbersonly: true
            },
            celP: {
				required: true,
                numbersonly: true
            },
            telefono1P: {
                numbersonly: true
            },
            telefono2P: {
                numbersonly: true
            },
            nombreInstPer: {
                validaNombreInst: true
            },
			field02: {
				required: true,
                fielSelected: true
            },
            field03: {
				required: true,
                fielSelected: true
            }/*,
            field04: {
                fielSelected: true
            },
            field05: {
                fielSelected: true
            },
            txtField01: {
                required: true
            },
            txtField02: {
                numbersonly: true
            }*/

        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error)
        },

        messages: {
            apellidoP: {
                required: "Ingrese apellido paterno"
            },
            nombreP: {
                required: "Ingrese el nombre"
            },
            cedulaP:{
                required:"Ingrese la cédula"
            }, 
			tipoPersona: "Seleccione el tipo",
            especialidadP: "Seleccione la especialidad",
			subespecialidadP: "Seleccione la especialidad Columbia",
            pacientesSemanaP: "Seleccione los pacientes por semana",
            honorariosP: "Seleccione los honorarios",
            estatusP: "Seleccione el estatus",
            nombreInstP: "Seleccione nombre de la institución",
			tel1P: "Ingrese un número de telefono válido",
			celP: "Ingrese un número de celular válido",
            email1PerfilP: "Ingrese un correo válido",
            email2PerfilP: "Ingrese un correo válido",
            emailPerfilP: "Ingrese un correo válido",
			fechaNacDiaP: "Seleccione la fecha de nacimiento completa",
			fechaNacMesP: "Seleccione la fecha de nacimiento completa",
			fechaNacAñoP: "Seleccione la fecha de nacimiento completa",
			field02: "Seleccione el Segmento por Potencial",
			field03: "Seleccione Líder de Opinion",
			field05: "Seleccione Recetario Libre",
			sexoP: "Seleccione el sexo",
			txtField01: "Ingrese el establecimiento personal"
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
            /*estatusI: "required",*/
            nombreI: "required",
            calleI: "required",
			numExtI: "required",
            codigoPostalI: {
                required: true,
                numbersonly2: true,
                minlength: 5
            },
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
			subTipoI: "Seleccione el subtipo de Institución",
			formatoI: "Seleccione el formato de Institución",
            estatusI: "Seleccione el estatus",
            nombreI: "Ingrese el nombre de la institución",
            calleI: "Ingrese la calle de la institución",
            codigoPostalI: {
                required: "Ingrese el código postal",
                minlength: "El C.P. está formado por 5 dígitos"
            },
            emailI: "Ingrese un correo válido",
			numExtI: "Ingrese el número exterior"
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