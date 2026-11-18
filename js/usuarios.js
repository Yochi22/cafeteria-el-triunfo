function consultar() {
    if ($.fn.DataTable.isDataTable('#tblUsuarios')) {
        $("#tblUsuarios").DataTable().ajax.reload(null, false);
    }
}

function consultarPersonal() {
    var datos = new FormData();
    datos.append("accion", "consultarPersonal");
    enviaAjax(datos);
}

function consultarRoles() {
    var datos = new FormData();
    datos.append("accion", "consultarRoles");
    enviaAjax(datos);
}

$(document).ready(function () {
    // Inicializar DataTables
    var table = $("#tblUsuarios").DataTable({
        ajax: {
            url: "index.php?pagina=usuarios",
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
            { data: "username", className: "text-center" }, // Columna 2: Usuario
            { data: "rol", className: "text-center" }, // Columna 3: Rol
            {
                data: null,
                className: "text-center",
                orderable: false,
                render: function (data, type, row) {
                    return `
                        <button type='button' class='btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1 my-1'
                            data-idusuario='${row.idUsuario}'
                            data-idpersonal='${row.idPersonal}'
                            data-idrol='${row.idRol}'
                            data-username='${row.username}'
                            onclick='pone(this)'>
                            <i class='bi bi-pencil-square'></i><span class='d-none d-sm-inline'> Modificar</span>
                        </button>
                        <button type='button' class='btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-1 m-1'
                            data-idusuario='${row.idUsuario}'
                            onclick='eliminar(this)'>
                            <i class='bi bi-trash-fill'></i><span class='d-none d-sm-inline'> Eliminar</span>
                        </button>
                    `;
                }
            } // Columna 4: Acciones
        ],
        dom: 'rt<"row mt-3 align-items-center"<"col-md-6 text-muted small"i><"col-md-6 d-flex justify-content-end"p>>',
        language: {
            "decimal": "",
            "emptyTable": `<div class='text-center text-muted py-4'>
                                <span><i class='bi bi-person-fill-slash fs-1' style='color: #FF8C00'></i></span>
                                <h5 class='text-dashboard'>No Hay Usuarios Registrados.</h5>
                                <h6 class='text-secondary'>Registra un Usuario.</h6>
                            </div>`,
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

    consultarPersonal();
    consultarRoles();

    // VALIDACIÓN DE DATOS
    $("#username").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9_.-]*$/, e);
    });

    $("#username").on("keyup", function () {
        validarkeyup(
            /^[A-Za-z0-9_.-]{3,30}$/,
            $(this),
            $("#susername"),
            "Usuario inválido - Debe tener entre 3 y 30 caracteres.",
        );
    });

    $("#password").on("keyup", function () {
        if ($(this).val() != "") {
            $("#spassword").text("");
        }
    });

    $("#btnGuardar").on("click", function () {
        // BOTÓN INCLUIR
        if ($(this).text() == "incluir") {
            if (validarEnvio()) {
                var datos = new FormData();
                datos.append("accion", "incluir");
                datos.append("idPersonal", $("#idPersonal").val());
                datos.append("idRol", $("#idRol").val());
                datos.append("username", $("#username").val());
                datos.append("password", $("#password").val());
                enviaAjax(datos);
            }
        }
        // BOTÓN MODIFICAR
        else if ($(this).text() == "modificar") {
            if (validarEnvio()) {
                var datos = new FormData();
                datos.append("accion", "modificar");
                datos.append("idUsuario", $("#idUsuario").val());
                datos.append("idPersonal", $("#idPersonal").val());
                datos.append("idRol", $("#idRol").val());
                datos.append("username", $("#username").val());
                datos.append("password", $("#password").val());
                enviaAjax(datos);
            }
        }
    });

    // BÚSQUEDA (filtra directamente en DataTables)
    $("#valorBusqueda").on("keyup", function () {
        table.search(this.value).draw();
    });

    $("#btnBuscar").on("click", function () {
        table.search($("#valorBusqueda").val()).draw();
    });

    $("#incluir").on("click", function () {
        limpia();
        $("#btnGuardar").text("incluir");

        $("#mensajePassword").text(
            "La contraseña es obligatoria al incluir un usuario. Al modificar, déjala vacía para conservar la actual.",
        );
        $("#modal_usuarios").modal("show");
    });
});

// Validación de los datos antes de enviarlos
function validarEnvio() {
    if ($("#idPersonal").val() == "") {
        mostrarAlerta("Atención", "Debe seleccionar el personal.", "warning");
        return false;
    } else if ($("#idRol").val() == "") {
        mostrarAlerta("Atención", "Debe seleccionar un rol.", "warning");
        return false;
    } else if (
        validarkeyup(
            /^[A-Za-z0-9_.-]{3,30}$/,
            $("#username"),
            $("#susername"),
            "Usuario inválido - Debe tener entre 3 y 30 caracteres.",
        ) == 0
    ) {
        mostrarAlerta("Atención", "Nombre de usuario inválido <br/>(Entre 3 y 30 caracteres)", "warning");
        return false;
    } else if (
        $("#btnGuardar").text() == "incluir" &&
        $("#password").val().trim() == ""
    ) {
        mostrarAlerta("Atención", "Debe ingresar una contraseña.", "warning");
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
    var key = e.keyCode;
    var tecla = String.fromCharCode(key);
    var a = er.test(tecla);

    if (!a) {
        e.preventDefault();
    }
}

// Función para validar por keyup
function validarkeyup(er, etiqueta, etiquetamensaje, mensaje) {
    var a = er.test(etiqueta.val());

    if (a) {
        etiquetamensaje.text("");
        return 1;
    } else {
        etiquetamensaje.text(mensaje);
        return 0;
    }
}

// Función para llenar el formulario con los datos del usuario
function pone(pos) {
    var btn = $(pos);

    // Extraer los datos exactos desde los atributos data-*
    $("#idUsuario").val(btn.attr("data-idusuario"));
    $("#idPersonal").val(btn.attr("data-idpersonal"));
    $("#idRol").val(btn.attr("data-idrol"));
    $("#username").val(btn.attr("data-username"));
    $("#password").val("");
    $("#susername").text("");
    $("#spassword").text("");

    $("#btnGuardar").text("modificar");
    $("#mensajePassword").text(
        "Deja la contraseña vacía para conservar la actual.",
    );
    $("#modal_usuarios").modal("show");
}

function eliminar(pos) {
    var idUsuario = $(pos).data('idusuario');
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas eliminar este usuario del sistema?",
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
            datos.append('idUsuario', idUsuario);
            enviaAjax(datos);
        }
    });
}

function enviaAjax(datos) {
    $.ajax({
        async: true,
        url: "index.php?pagina=usuarios",
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

                if (lee.resultado === 'consultarPersonal') {
                    $("#idPersonal").html(lee.mensaje);
                }
                else if (lee.resultado === 'consultarRoles') {
                    $("#idRol").html(lee.mensaje);
                }
                else if (lee.resultado === 'incluir') {
                    if (lee.mensaje === 'Usuario incluido.') {
                        mostrarAlerta("¡Éxito!", lee.mensaje, "success");
                        $("#modal_usuarios").modal("hide");
                        consultar();
                    } else {
                        mostrarAlerta("Información", lee.mensaje, "info");
                    }
                }
                else if (lee.resultado === 'modificar') {
                    if (lee.mensaje === 'Usuario modificado.') {
                        mostrarAlerta("¡Éxito!", lee.mensaje, "success");
                        $("#modal_usuarios").modal("hide");
                        consultar();
                    } else {
                        mostrarAlerta("Información", lee.mensaje, "info");
                    }
                }
                else if (lee.resultado === 'eliminar') {
                    mostrarAlerta("Atención", lee.mensaje, "success");
                    consultar();
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
    $("#idUsuario").val("");
    $("#idPersonal").val("");
    $("#idRol").val("");
    $("#username").val("");
    $("#password").val("");
    $("#susername").text("");
    $("#spassword").text("");
}