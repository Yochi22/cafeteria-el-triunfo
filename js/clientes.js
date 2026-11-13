// Función para la lista de clientes (Recarga la DataTable)
function consultar() {
    if ($.fn.DataTable.isDataTable('#tblClientes')) {
        $("#tblClientes").DataTable().ajax.reload(null, false);
    }
}

$(document).ready(function () {
    // Inicializar DataTables
    var table = $("#tblClientes").DataTable({
        ajax: {
            url: "index.php?pagina=clientes",
            type: "POST",
            data: { accion: "consultar" },
            dataSrc: ""
        },
        columns: [
            { data: "cedulaCli", className: "text-center" },
            {
                data: null,
                className: "text-center",
                render: function (data, type, row) {
                    return `${row.nombreCli} ${row.apellidoCli}`;
                }
            },
            { data: "tlfCli", className: "text-center" },
            {
                data: null,
                className: "text-center",
                render: function (data, type, row) {
                    return `
                        <button type='button' class='btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1 my-1' data-nombre='${row.nombreCli}' data-apellido='${row.apellidoCli}' onclick='pone(this)'><i class='bi bi-pencil-square'></i><span class='d-none d-sm-inline'> Modificar</span></button>
                        <button type='button' class='btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-1 m-1' onclick='eliminar(this)'><i class='bi bi-trash-fill'></i><span class='d-none d-sm-inline'> Eliminar</span></button>
                    `;
                }
            }
        ],
        dom: 'rt<"row mt-3 align-items-center"<"col-md-6 text-muted small"i><"col-md-6 d-flex justify-content-end"p>>',
        language: {
            "decimal": "",
            "emptyTable": "No hay clientes registrados.",
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
    $("#cedulaCli").on("keypress", function (e) {
        validarkeypress(/^[0-9\-\.]*$/, e);
    });
    $("#cedulaCli").on("keyup", function () {
        validarkeyup(/^[0-9\.]{7,8}$/, $(this), $("#scedulaCli"), "Cédula Inválida - El formato debe ser: 99999999");
    });

    $("#nombreCli").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#nombreCli").on("keyup", function () {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $(this), $("#snombreCli"), "Nombre Inválido - Solo letras");
    });

    $("#apellidoCli").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#apellidoCli").on("keyup", function () {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $(this), $("#sapellidoCli"), "Apellido Inválido - Solo letras");
    });

    $("#tlfCli").on("keypress", function (e) {
        validarkeypress(/^[0-9\-+ ]*$/, e);
    });
    $("#tlfCli").on("keyup", function () {
        validarkeyup(/^[0-9\-+ ]{11,12}$/, $(this), $("#stlfCli"), "Teléfono Inválido");
    });

    $("#f").on("submit", function (e) {
        e.preventDefault();
    });

    // Control de botones Guardar (Incluir / Modificar)
    $("#btnGuardar").on("click", function () {
        if ($(this).text().trim() === 'incluir') {
            if (validarEnvio()) {
                var datos = new FormData();
                datos.append('accion', 'incluir');
                datos.append('cedulaCli', $("#cedulaCli").val());
                datos.append('nombreCli', $("#nombreCli").val());
                datos.append('apellidoCli', $("#apellidoCli").val());
                datos.append('tlfCli', $("#tlfCli").val());
                enviaAjax(datos);
            }
        }
        else if ($(this).text().trim() === 'modificar') {
            if (validarEnvio()) {
                var datos = new FormData();
                datos.append('accion', 'modificar');
                datos.append('cedulaCli', $("#cedulaCli").val());
                datos.append('nombreCli', $("#nombreCli").val());
                datos.append('apellidoCli', $("#apellidoCli").val());
                datos.append('tlfCli', $("#tlfCli").val());
                enviaAjax(datos);
            }
        }
    });

    // Búsqueda en tiempo real
    $("#valorBusqueda").on("keyup", function () {
        table.search(this.value).draw();
    });

    $("#btnBuscar").on("click", function () {
        table.search($("#valorBusqueda").val()).draw();
    });

    // BOTÓN ABRIR MODAL INCLUIR
    $("#incluir").on("click", function () {
        limpia();
        $("#cedulaCli").prop('readonly', false);
        $("#btnGuardar").text("incluir");
        $("#modal_cliente").modal("show");
    });
});

// Validación de datos antes de enviar
function validarEnvio() {
    if (validarkeyup(/^[0-9\.]{7,8}$/, $("#cedulaCli"), $("#scedulaCli"), "Cédula Inválida") === 0) {
        mostrarAlerta("Atención", "Cédula Inválida <br/>(El formato debe ser: 99999999)", "warning");
        return false;
    }
    else if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $("#nombreCli"), $("#snombreCli"), "Nombre Inválido") === 0) {
        mostrarAlerta("Atención", "Nombre Inválido <br>(Solo letras entre 3 y 100 caracteres)", "warning");
        return false;
    }
    else if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $("#apellidoCli"), $("#sapellidoCli"), "Apellido Inválido") === 0) {
        mostrarAlerta("Atención", "Apellido Inválido <br>(Solo letras entre 3 y 100 caracteres)", "warning");
        return false;
    }
    else if (validarkeyup(/^[0-9\-+ ]{11,12}$/, $("#tlfCli"), $("#stlfCli"), "Teléfono Inválido") === 0) {
        mostrarAlerta("Atención", "Teléfono Inválido <br>(Formato: 0000-0000000)", "warning");
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

// Llenar formulario para Modificar
function pone(pos) {
    var linea = $(pos).closest('tr');
    var btn = $(pos);

    $("#cedulaCli").val($(linea).find("td:eq(0)").text().trim());
    $("#nombreCli").val(btn.data('nombre'));
    $("#apellidoCli").val(btn.data('apellido'));
    $("#tlfCli").val($(linea).find("td:eq(2)").text().trim());

    $("#btnGuardar").text('modificar');
    $("#modal_cliente").modal("show");
    $("#cedulaCli").prop('readonly', true);
}

// Función Eliminar
function eliminar(pos) {
    var linea = $(pos).closest('tr');
    var cedula = $(linea).find("td:eq(0)").text().trim();

    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡Deseas eliminar este cliente del sistema!",
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
            datos.append('cedulaCli', cedula);
            enviaAjax(datos);
        }
    });
}

// Función AJAX
function enviaAjax(datos) {
    $.ajax({
        async: true,
        url: "index.php?pagina=clientes",
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        timeout: 10000,
        success: function (respuesta) {
            console.log(respuesta);
            try {
                var lee = JSON.parse(respuesta);

                if (lee.resultado === 'incluir') {
                    if (lee.mensaje === 'Cliente registrado con éxito.') {
                        mostrarAlerta("¡Éxito!", lee.mensaje, "success");
                        $("#modal_cliente").modal("hide");
                        consultar();
                    } else {
                        mostrarAlerta("Información", lee.mensaje, "info");
                    }
                }
                else if (lee.resultado === 'modificar') {
                    if (lee.mensaje === 'Cliente modificado con éxito.') {
                        mostrarAlerta("¡Éxito!", lee.mensaje, "success");
                        $("#modal_cliente").modal("hide");
                        consultar();
                    } else {
                        mostrarAlerta("Información", lee.mensaje, "info");
                    }
                }
                else if (lee.resultado === 'eliminar') {
                    if (lee.mensaje === 'Cliente eliminado con éxito.') {
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

// Limpiar formulario y spans
function limpia() {
    $("#cedulaCli").val("");
    $("#nombreCli").val("");
    $("#apellidoCli").val("");
    $("#tlfCli").val("");

    $("#scedulaCli").text("");
    $("#snombreCli").text("");
    $("#sapellidoCli").text("");
    $("#stlfCli").text("");
}