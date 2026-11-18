// Función para recargar DataTable de cuentas
function consultar() {
    if ($.fn.DataTable.isDataTable('#tblCuentas')) {
        $("#tblCuentas").DataTable().ajax.reload(null, false);
    }
}

$(document).ready(function () {
    // Inicializar DataTables
    var table = $("#tblCuentas").DataTable({
        ajax: {
            url: "index.php?pagina=cuentas",
            type: "POST",
            data: { accion: "consultar" },
            dataSrc: ""
        },
        columns: [
            { data: "nombreBanco", className: "text-center" },
            { data: "cedulaTitular", className: "text-center" },
            { data: "tlfCuenta", className: "text-center" },
            { data: "tipoCuenta", className: "text-center" },
            { data: "numCuenta", className: "text-center" },
            {
                data: null,
                className: "text-center",
                render: function (data, type, row) {
                    return `
                    <button type='button' class='btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1 my-1' 
                        data-banco='${row.nombreBanco}' 
                        data-cedula='${row.cedulaTitular}' 
                        data-tlf='${row.tlfCuenta}' 
                        data-tipo='${row.tipoCuenta}' 
                        data-num='${row.numCuenta}' 
                        onclick='pone(this)'>
                        <i class='bi bi-pencil-square'></i><span class='d-none d-sm-inline'> Modificar</span>
                    </button>
                    <button type='button' class='btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-1 m-1' onclick='eliminar(this)'><i class='bi bi-trash-fill'></i><span class='d-none d-sm-inline'> Eliminar</span></button>
                `;
                }
            }
        ],
        dom: 'rt<"row mt-3 align-items-center"<"col-md-6 text-muted small"i><"col-md-6 d-flex justify-content-end"p>>',
        language: {
            "decimal": "",
            "emptyTable": "No hay cuentas registradas.",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 a 0 de 0 registros",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "lengthMenu": "Mostrar _MENU_ registros",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": `<div class='text-center text-muted py-4'>
                                <span><i class='bi bi-x-octagon fs-1' style='color: #FF8C00'></i></span>
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
    // -- Validación de nombre de banco --
    $("#nombreBanco").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#nombreBanco").on("keyup", function () {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $(this), $("#snombreBanco"), "Nombre Inválido - Solo letras (entre 3 y 100 caracteres)");
    });

    // -- Validación de cédula --
    $("#cedulaTitular").on("keypress", function (e) {
        validarkeypress(/^[0-9]*$/, e);
    });
    $("#cedulaTitular").on("keyup", function () {
        validarkeyup(/^[0-9]{7,8}$/, $(this), $("#scedulaTitular"), "Cédula Inválida - Debe tener entre 7 y 8 dígitos numéricos.");
    });

    // -- Validación de teléfono --
    $("#tlfCuenta").on("keypress", function (e) {
        validarkeypress(/^[0-9]*$/, e);
    });
    $("#tlfCuenta").on("keyup", function () {
        validarkeyup(/^(0412|0414|0416|0422|0424|0426|02[0-9]{2})[0-9]{7}$/, $(this), $("#stlfCuenta"), "Teléfono Inválido - Formato : 04241234567.");
    });

    // -- Validación de número de cuenta --
    $("#numCuenta").on("keypress", function (e) {
        validarkeypress(/^[0-9]*$/, e);
    });
    $("#numCuenta").on("keyup", function () {
        validarkeyup(/^[0-9]{20}$/, $(this), $("#snumCuenta"), "Cuenta Inválida - Debe tener exactamente 20 números.");
    });

    // -- Validación de tipo de cuenta (Select) --
    $("#tipoCuenta").on("change", function () {
        validarSelect($(this), $("#stipoCuenta"), "Tipo de Cuenta Inválido - Debe seleccionar una opción");
    });
    // Fin de Validaciones de datos

    // Evitar que el formulario se envíe con Enter
    $("#f").on("submit", function (e) {
        e.preventDefault();
    });

    // Control de botones
    $("#btnGuardar").on("click", function () {
        // BOTÓN INCLUIR
        if ($(this).text() === 'incluir') {
            if (validarEnvio()) {
                var datos = new FormData();
                datos.append('accion', 'incluir');
                datos.append('nombreBanco', $("#nombreBanco").val());
                datos.append('cedulaTitular', $("#cedulaTitular").val());
                datos.append('tlfCuenta', $("#tlfCuenta").val());
                datos.append('tipoCuenta', $("#tipoCuenta").val());
                datos.append('numCuenta', $("#numCuenta").val());
                enviaAjax(datos);
            }
        }
        // BOTÓN MODIFICAR
        else if ($(this).text() === 'modificar') {
            if (validarEnvio()) {
                var datos = new FormData();
                datos.append('accion', 'modificar');
                datos.append('nombreBanco', $("#nombreBanco").val());
                datos.append('cedulaTitular', $("#cedulaTitular").val());
                datos.append('tlfCuenta', $("#tlfCuenta").val());
                datos.append('tipoCuenta', $("#tipoCuenta").val());
                datos.append('numCuenta', $("#numCuenta").val());
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

    // BOTON MODAL INCLUIR
    $("#incluir").on("click", function () {
        limpia();
        $("#numCuenta").prop('readonly', false);
        $("#btnGuardar").text("incluir");
        $("#modal_cuentas").modal("show");
    });
});

// Validación de los datos antes de enviarlos
function validarEnvio() {
    if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $("#nombreBanco"), $("#snombreBanco"), "Nombre Inválido - Solo letras") === 0) {
        mostrarAlerta("", "Nombre de Banco Inválido <br/>(Solo letras entre 3 y 100 caracteres)", "warning");
        return false;
    }
    else if (validarkeyup(/^[0-9]{7,8}$/, $("#cedulaTitular"), $("#scedulaTitular"), "Cédula Inválida") === 0) {
        mostrarAlerta("", "Cédula Titular Inválida <br/>(El formato debe ser: 99999999)", "warning");
        return false;
    }
    else if (validarkeyup(/^(0412|0414|0416|0422|0424|0426|02[0-9]{2})[0-9]{7}$/, $("#tlfCuenta"), $("#stlfCuenta"), "Teléfono Inválido") === 0) {
        mostrarAlerta("", "Teléfono Inválido <br/>Teléfono Inválido - Formato : 04241234567.", "warning");
        return false;
    }
    else if (validarSelect($("#tipoCuenta"), $("#stipoCuenta"), "Seleccione una opción") === 0) {
        mostrarAlerta("", "Tipo de Cuenta Inválido <br>(Debe seleccionar Ahorro o Corriente)", "warning");
        return false;
    }
    else if (validarkeyup(/^[0-9]{20}$/, $("#numCuenta"), $("#snumCuenta"), "Cuenta Inválida") === 0) {
        mostrarAlerta("", "Número de Cuenta Inválido <br>(Debe tener exactamente 20 números)", "warning");
        return false;
    }

    return true;
}

// Función para mostrar alertas
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

// Función para validar select
function validarSelect(etiqueta, etiquetamensaje, mensaje) {
    let valor = etiqueta.val();

    if (valor === "" || valor === null) {
        etiquetamensaje.text(mensaje);
        return 0;
    } else {
        etiquetamensaje.text("");
        return 1;
    }
}

// Función para llenar el formulario con los datos de la cuenta
function pone(pos) {
    var linea = $(pos).closest('tr');
    var btn = $(pos);

    $("#nombreBanco").val(btn.data('banco'));
    $("#cedulaTitular").val(btn.data('cedula'));
    $("#tlfCuenta").val(btn.data('tlf'));
    $("#tipoCuenta").val(btn.data('tipo'));
    $("#numCuenta").val(btn.data('num'));

    $("#btnGuardar").text('modificar');
    $("#modal_cuentas").modal("show");
    $("#numCuenta").prop('readonly', true);
}

// Función Eliminar
function eliminar(pos) {
    var linea = $(pos).closest('tr');
    var numCuenta = $(linea).find("td:eq(4)").text().trim();

    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas eliminar esta cuenta del sistema?",
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
            datos.append('numCuenta', numCuenta);
            enviaAjax(datos);
        }
    });
}

// Función AJAX
function enviaAjax(datos) {
    $.ajax({
        async: true,
        url: "index.php?pagina=cuentas",
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

                if (lee.resultado === 'incluir') {
                    if (lee.mensaje === 'Cuenta registrada con éxito.') {
                        mostrarAlerta("¡Éxito!", lee.mensaje, "success");
                        $("#modal_cuentas").modal("hide");
                        consultar();
                    } else {
                        mostrarAlerta("Información", lee.mensaje, "info");
                    }
                }
                else if (lee.resultado === 'modificar') {
                    if (lee.mensaje === 'Cuenta modificada con éxito.') {
                        mostrarAlerta("¡Éxito!", lee.mensaje, "success");
                        $("#modal_cuentas").modal("hide");
                        consultar();
                    } else {
                        mostrarAlerta("Información", lee.mensaje, "info");
                    }
                }
                else if (lee.resultado === 'eliminar') {
                    if (lee.mensaje === 'Cuenta eliminada con éxito.') {
                        mostrarAlerta("¡Éxito!", lee.mensaje, "success");
                        consultar();
                    } else {
                        mostrarAlerta("Atención", lee.mensaje, "warning");
                    }
                }
                else if (lee.resultado === "error") {
                    mostrarAlerta("Error", lee.mensaje, "error");
                }
            } catch (e) {
                mostrarAlerta("Error de sistema", "Error en formato JSON: " + e.name, "error");
            }
        },
        error: function (request, status, err) {
            if (status === "timeout") {
                mostrarAlerta("Servidor Ocupado", "El servidor tardó demasiado, intente de nuevo.", "warning");
            } else {
                mostrarAlerta("Error crítico", "Ocurrió un error de comunicación.", "error");
            }
        }
    });
}

// Función para limpiar
function limpia() {
    // Vaciar los inputs
    $("#nombreBanco").val("");
    $("#cedulaTitular").val("");
    $("#tlfCuenta").val("");
    $("#tipoCuenta").val("");
    $("#numCuenta").val("");

    // Limpiar los mensajes de validación
    $("#snombreBanco").text("");
    $("#scedulaTitular").text("");
    $("#stlfCuenta").text("");
    $("#stipoCuenta").text("");
    $("#snumCuenta").text("");
}