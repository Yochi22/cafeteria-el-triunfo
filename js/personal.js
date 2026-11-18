function consultar() {
    if ($.fn.DataTable.isDataTable('#tblPersonal')) {
        $("#tblPersonal").DataTable().ajax.reload(null, false);
    }
}

$(document).ready(function () {
    // Inicializar DataTables
    var table = $("#tblPersonal").DataTable({
        ajax: {
            url: "index.php?pagina=personal",
            type: "POST",
            data: { accion: "consultar" },
            dataSrc: ""
        },
        columns: [
            { data: "cedulaPer", className: "text-center" }, // Columna 0: Cédula
            {
                data: null,
                className: "text-center",
                render: function (data, type, row) {
                    return `${row.nombrePer} ${row.apellidoPer}`; // Columna 1: Nombre y Apellido juntos
                }
            },
            {
                data: null,
                className: "text-center",
                render: function (data, type, row) {
                    return `
                        <button type='button' class='btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1 my-1' 
                            data-cedula='${row.cedulaPer}' 
                            data-nombre='${row.nombrePer}' 
                            data-apellido='${row.apellidoPer}' 
                            onclick='pone(this)'>
                            <i class='bi bi-pencil-square'></i><span class='d-none d-sm-inline'> Modificar</span>
                        </button>
                        <button type='button' class='btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-1 m-1' 
                            data-cedula='${row.cedulaPer}' 
                            onclick='eliminar(this)'>
                            <i class='bi bi-trash-fill'></i><span class='d-none d-sm-inline'> Eliminar</span>
                        </button>
                    `;
                }
            } // Columna 2: Acciones
        ],
        dom: 'rt<"row mt-3 align-items-center"<"col-md-6 text-muted small"i><"col-md-6 d-flex justify-content-end"p>>',
        language: {
            "decimal": "",
            "emptyTable": "No hay empleados registrados.",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 a 0 de 0 registros",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "lengthMenu": "Mostrar _MENU_ registros",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": `<div class='text-center text-muted py-4'>
                                <span><i class='bi bi-person-fill-slash fs-1' style='color: #FF8C00'></i></span>
                                <h5 class='text-dashboard'>No se encontraron registros</h5>
                                <h6 class='text-secondary'>Intenta de nuevo.</h6>
                            </div>`,
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        }
    });
    // VALIDACIÓN DE DATOS
    // Validación de cédula
    $("#cedulaPer").on("keypress", function (e) {
        validarkeypress(/^[0-9-\.]*$/, e);
    });
    $("#cedulaPer").on("keyup", function () {
        validarkeyup(/^[0-9\.]{7,12}$/, $(this), $("#scedulaPer"), "Cédula Inválida - El Formato debe ser: 99999999");
    });

    // Validación de nombre
    $("#nombrePer").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#nombrePer").on("keyup", function () {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $(this), $("#snombrePer"), "Nombre Inválido - Solo letras entre 3 y 100 carácteres");
    });

    // Validación de apellido
    $("#apellidoPer").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#apellidoPer").on("keyup", function () {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $(this), $("#sapellidoPer"), "Apellido Inválido - Solo letras entre 3 y 100 carácteres");
    });

    $("#btnGuardar").on("click", function () {
        // BOTÓN INCLUIR
        if ($(this).text() == 'incluir') {
            if (validarEnvio()) {
                var datos = new FormData();
                datos.append('accion', 'incluir');
                datos.append('cedulaPer', $("#cedulaPer").val());
                datos.append('nombrePer', $("#nombrePer").val());
                datos.append('apellidoPer', $("#apellidoPer").val());
                enviaAjax(datos);
            }
        }
        // BOTÓN MODIFICAR
        else if ($(this).text() == 'modificar') {
            if (validarEnvio()) {
                var datos = new FormData();
                datos.append('accion', 'modificar');
                datos.append('cedulaPer', $("#cedulaPer").val());
                datos.append('nombrePer', $("#nombrePer").val());
                datos.append('apellidoPer', $("#apellidoPer").val());
                enviaAjax(datos);
            }
        }
    });

    // BOTÓN BUSCAR
    $("#valorBusqueda").on("keyup", function () {
        table.search(this.value).draw();
    });

    $("#btnBuscar").on("click", function () {
        table.search($("#valorBusqueda").val()).draw();
    });

    $("#valorBusqueda").on("keyup", function () {
        ejecutarBusqueda();
    });

    // BOTÓN CONFIRMAR ELIMINACIÓN DE PERSONAL
    $("#btnEliminar").on("click", function () {
        var cedulaEliminada = $("#eliminar").val();
        var datos = new FormData();
        datos.append('accion', 'eliminar');
        datos.append('cedulaPer', cedulaEliminada);
        enviaAjax(datos);
    });

    $("#incluir").on("click", function () {
        limpia();
        $("#cedulaPer").prop("readonly", false);
        $("#btnGuardar").text("incluir");
        $("#modal_personal").modal("show");
    });
});

// Validación de los datos antes de enviarlos
function validarEnvio() {
    // Validación de envio de cédula
    if (validarkeyup(/^[0-9\.]{7,12}$/, $("#cedulaPer"), $("#scedulaPer"), "Cédula Inválida") === 0) {
        mostrarAlerta("Atención", "Cédula Inválida <br/>(El formato debe ser: 99999999)", "warning");
        return false;
    }
    // Validación de envio de nombre
    else if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $("#nombrePer"), $("#snombrePer"), "Nombre Inválido - (Solo letras entre 3 y 100 carácteres)") == 0) {
        mostrarAlerta("Atención", "Apellido Inválido - Solo letras (entre 3 y 100 caracteres)", "warning");
        return false;
    }
    // Validación de envio de apellido
    else if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $("#apellidoPer"), $("#sapellidoPer"), "Apellido Inválido - (Solo letras entre 3 y 100 carácteres)") == 0) {
        mostrarAlerta("Atención", "Apellido Inválido - Solo letras (entre 3 y 100 caracteres)", "warning");
        return false;
    }

    return true;
}

function mostrarAlerta(titulo, mensaje, icono) {
    Swal.fire({
        title: titulo,
        html: mensaje,
        icon: icono,
        confirmButtonText: 'Aceptar',
        confirmButtonColor: '#FF8C00',
        customClass: {
            popup: 'rounded-4 shadow'
        }
    });
}

// Función para mostrar del modal del mensaje
function mostrarMensaje(mensaje) {
    $("#contenidoModal").html(mensaje);
    $("#mostrarModal").modal("show");
    setTimeout(function () { $("#mostrarModal").modal("hide") }, 5000);
}

// Función para validar por keypress
function validarkeypress(er, e) {
    key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);

    if (!a) {
        e.preventDefault();
    }
}

// Función para validar por keyup
function validarkeyup(er, etiqueta, etiquetamensaje, mensaje) {
    a = er.test(etiqueta.val());

    if (a) {
        etiquetamensaje.text("");
        return 1;
    } else {
        etiquetamensaje.text(mensaje);
        return 0;
    }
}

// Función para llenar el formulario con los datos del personal
function pone(pos) {
    var linea = $(pos).closest('tr');

    var btnModificar = $(linea).find("button.btn-outline-primary");

    // Extrar los datos exactos desde los atributos data-*
    var cedula = btnModificar.attr("data-cedula");
    var nombre = btnModificar.attr("data-nombre");
    var apellido = btnModificar.attr("data-apellido");


    $("#cedulaPer").prop("readonly", true);
    $("#cedulaPer").val(cedula);
    $("#nombrePer").val(nombre);
    $("#apellidoPer").val(apellido);

    $("#btnGuardar").text('modificar');
    $("#modal_personal").modal("show");
}

function eliminar(pos) {
    var cedula = $(pos).data('cedula');
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas eliminar este cliente del sistema?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: 'red',
        cancelButtonColor: 'darkgray',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        customClass: {
            popup: 'rounded-4 shadow'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            var datos = new FormData();
            datos.append('accion', 'eliminar');
            datos.append('cedulaPer', cedula);
            enviaAjax(datos);
        }
    });

}

function enviaAjax(datos) {
    $.ajax({
        async: true,
        url: "index.php?pagina=personal",
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        beforeSend: function () { },
        timeout: 10000,
        success: function (respuesta) {
            console.log(respuesta);
            try {
                var lee = JSON.parse(respuesta);

                if (lee.resultado == 'consultar') {
                    $("#listaPersonal").html(lee.mensaje);
                }
                if (lee.resultado === 'incluir') {
                    if (lee.mensaje === 'Empleado incluido.') {
                        mostrarAlerta("¡Éxito!", lee.mensaje, "success");
                        $("#modal_personal").modal("hide");
                        consultar();
                    } else {
                        mostrarAlerta("Información", lee.mensaje, "info");
                    }
                }
                else if (lee.resultado === 'modificar') {
                    if (lee.mensaje === 'Empleado modificado') {
                        mostrarAlerta("¡Éxito!", lee.mensaje, "success");
                        $("#modal_personal").modal("hide");
                        consultar();
                    } else {
                        mostrarAlerta("Información", lee.mensaje, "info");
                    }
                }
                else if (lee.resultado === 'eliminar') {
                    mostrarAlerta("Atención", lee.mensaje, "success");
                    consultar();
                    $("#modal_eliminar").modal("hide");
                }
                else if (lee.resultado == 'buscar') {
                    $('#listaPersonal').html(lee.mensaje);
                }
                else if (lee.resultado == "error") {
                    mostrarMensaje(lee.mensaje);
                }
            } catch (e) {
                alert("Error en JSON" + e.name);
            }
        },
        error: function (request, status, err) {
            if (status == "timeout") {
                mostrarMensaje("Servidor Ocupado, Intente de Nuevo");
            } else {
                mostrarMensaje("ERROR: <br/>" + request + status + err);
            }
        },
        complete: function () { },
    });
}

function limpia() {
    $("#cedulaPer").val("");
    $("#nombrePer").val("");
    $("#apellidoPer").val("");
}