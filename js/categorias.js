$(document).ready(function () {
    try {
        consultar();
    } catch (e) {
        console.error(e);
    }

    $("#nombre, #descripcion").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC.,-]*$/, e);
    });

    $("#nombre").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $(this), $("#snombre"), "");
    });

    $("#descripcion").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC.,-]{5,150}$/, $(this), $("#sdescripcion"), "");
    });

    $("#f").on("submit", function (e) {
        e.preventDefault();
        let accion = $("#accion").val();
        if (accion == "incluir" || accion == "modificar") {
            if (validarenvio()) {
                var datos = new FormData(this);
                enviaAjax(datos);
            }
        }
    });
});

function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviaAjax(datos);
}

function nuevo() {
    limpia();
    $("#accion").val("incluir");
    $("#modal_categoria_label").text("Nueva Categoría");
    $("#btn_guardar").text("Guardar Categoría");
    const modalElement = document.getElementById('modal_categoria');
    const miModal = bootstrap.Modal.getOrCreateInstance(modalElement);
    miModal.show();
}

function validarenvio() {
    if (validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $("#nombre"), $("#snombre"), "") == 0) {
        muestraMensaje("Verifique el Nombre de la Categoría");
        return false;
    }
    if (validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC.,-]{5,150}$/, $("#descripcion"), $("#sdescripcion"), "") == 0) {
        muestraMensaje("Verifique la Descripción");
        return false;
    }
    return true;
}

function muestraMensaje(mensaje) {
    alert(mensaje);
}

function validarkeypress(er, e) {
    let key = e.keyCode || e.which;
    let tecla = String.fromCharCode(key);
    if (!er.test(tecla) && key != 8) e.preventDefault();
}

function validarkeyup(er, etiqueta, etiquetamensaje, mensaje) {
    if (er.test(etiqueta.val())) {
        etiqueta.css("border-color", "green");
        return 1;
    } else {
        etiqueta.css("border-color", "red");
        return 0;
    }
}

function pone(id, nombre, descripcion, foto, accion) {
    if (accion == 0) {
        $("#accion").val("modificar");
        $("#idCategoria").val(id);
        $("#nombre").val(nombre);
        $("#descripcion").val(descripcion);
        $("#foto").val(foto);
        $("#modal_categoria_label").text("Editar Categoría");
        $("#btn_guardar").text("Modificar Categoría");
        const modalElement = document.getElementById('modal_categoria');
        const miModal = bootstrap.Modal.getOrCreateInstance(modalElement);
        miModal.show();
    } else {
        if (confirm("¿Estás seguro de eliminar esta categoría?")) {
            var datos = new FormData();
            datos.append('accion', 'eliminar');
            datos.append('idCategoria', id);
            enviaAjax(datos);
        }
    }
}

function enviaAjax(datos) {
    $.ajax({
        url: "?pagina=categorias",
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        success: function (respuesta) {
            try {
                var lee = JSON.parse(respuesta);
                if (lee.resultado == "consultar") {
                    $("#cuadricula_categorias").html(lee.mensaje);
                } else {
                    muestraMensaje(lee.mensaje);
                    if (lee.resultado !== "error") {
                        const modalElement = document.getElementById('modal_categoria');
                        const miModal = bootstrap.Modal.getOrCreateInstance(modalElement);
                        miModal.hide();
                        consultar();
                    }
                }
            } catch (e) {
                console.error(respuesta);
            }
        },
        error: function () { }
    });
}

function limpia() {
    $("#f")[0].reset();
    $("#idCategoria").val("");
    $("#nombre, #descripcion, #foto").css("border-color", "");
}