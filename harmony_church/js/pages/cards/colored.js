var effect = 'timer';
var effect2 = 'win8_linear';
var effect3 = 'rotation';
var color = $.AdminBSB.options.colors['lightBlue'];

function cargandoGrafica1() {
    $('.grafica1-body').waitMe({
        effect: effect3,
        text: 'Cargando gráfica...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoGrafica2() {
    $('.grafica2-body').waitMe({
        effect: effect3,
        text: 'Cargando gráfica...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoGrafica3() {
    $('.grafica3-body').waitMe({
        effect: effect3,
        text: 'Cargando gráfica...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoGrafica4() {
    $('.grafica4-body').waitMe({
        effect: effect3,
        text: 'Cargando gráfica...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoGrafica5() {
    $('.grafica5-body').waitMe({
        effect: effect3,
        text: 'Cargando gráfica...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoInventario() {
    $('#inventario').waitMe({
        effect: effect,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoInventario2() {
    $('.tblEntradaSalidaInv').waitMe({
        effect: effect,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoInventario3() {
    $('#pendiente').waitMe({
        effect: effect,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoMedico() {
    $('.cardListMedicos').waitMe({
        effect: effect2,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoMedico2() {
    $('#cardInfMedicos').waitMe({
        effect: effect2,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoMedico3() {
    $('#divMedicosInactivos').waitMe({
        effect: effect2,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoInst() {
    $('.cardListInst').waitMe({
        effect: effect2,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoInst2() {
    $('#cardInfInst').waitMe({
        effect: effect2,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoAprobaciones() {
    $('#tblAprobacionesPersGerente').waitMe({
        effect: effect,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
    $('#tblAprobacionesInstGerente').waitMe({
        effect: effect,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoCalendario() {
    $('.cardInfCal').waitMe({
        effect: effect,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoCalendario2() {
    $('#divWeek').waitMe({
        effect: effect2,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoCalendario3() {
    $('#calendario').waitMe({
        effect: effect2,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoDocumentos() {
    $('#tblSubirDocumentosDatosPersonales').waitMe({
        effect: effect,
        text: 'Subiendo archivo, por favor espere...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

/**txtbuscarinst */
function cargandoBuscadorInst() {
    $('#tblBuscarInst').waitMe({
        effect: effect3,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoBuscadorInstSearch() {
    $('.buscaInst').waitMe({
        effect: effect3,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoBuscadorInst2() {
    $('.cargaBusquedaInst').waitMe({
        effect: effect,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}


/**txtbuscarmedico */
function cargandoBuscadorMed() {
    $('#tblBuscarPersonas').waitMe({
        effect: effect,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoBuscadorMedSearch() {
    $('.cargaBusquedaMed').waitMe({
        effect: effect3,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoVisita() {
    $('#cargandoInfVisitaMed').waitMe({
        effect: effect,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoVisita2() {
    $('#cargandoInfVisitaInst').waitMe({
        effect: effect,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

/**Cargando visitas/planes */
function cargandoTablaPlanMed() {
    $('#tblPlan').waitMe({
        effect: effect3,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoTablaVisitaMed() {
    $('#tblVisitas').waitMe({
        effect: effect3,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoTablaPlanInst() {
    $('#tblVisitasInst').waitMe({
        effect: effect3,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoTablaVisitaInst() {
    $('#tblPlanesInstituciones').waitMe({
        effect: effect3,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoFrecCat(){
    $('#containerFreCat').waitMe({
        effect: effect3,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoCobCat(){
    $('#containerCobCat').waitMe({
        effect: effect3,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoDisFichero(){
    $('#containerDisFichero').waitMe({
        effect: effect3,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoCobFichero(){
    $('#containerCobFichero').waitMe({
        effect: effect3,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoDisQuintil(){
    $('#containerDisQuintil').waitMe({
        effect: effect3,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}

function cargandoCobQuintil(){
    $('#containerCobQuintil').waitMe({
        effect: effect3,
        text: 'Cargando...',
        bg: 'rgba(255,255,255,0.90)',
        color: color
    });
}