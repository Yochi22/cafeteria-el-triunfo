$(document).ready(function() {
    try {
        cargarCategorias();
        llenarSelectCategorias();
    } catch (e) {
        console.error(e);
    }

    $("#nombre, #descripcion").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC.,-]*$/, e);
    });

    $("#precio").on("keypress", function (e) {
        if (e.key === 'e' || e.key === 'E' || e.key === '+' || e.key === '-') {
            e.preventDefault();
        }
        validarkeypress(/^[0-9.]*$/, e);
    });

    $("#nombre").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/, $(this), $("#snombre"), "");
    });

    $("#descripcion").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC.,-]{5,150}$/, $(this), $("#sdescripcion"), "");
    });

    $("#f_producto").on("submit", function(e) {
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

let categoriaActual = null;

function cargarCategorias() {
    categoriaActual = null;
    $("#titulo_seccion").html('<i class="bi bi-egg-fried"></i> Productos');
    $("#subtitulo_seccion").text("Todas las Categorías");
    $("#btn_regresar_cat").hide();
    $("#btn_gestionar_cat").show();
    
    let datos = new FormData();
    datos.append('accion', 'listar_categorias');
    enviaAjax(datos);
}

function verCategoria(id, nombre) {
    categoriaActual = id;
    $("#titulo_seccion").text(nombre);
    $("#subtitulo_seccion").text("Productos de Esta Categoría");
    $("#btn_regresar_cat").show();
    $("#btn_gestionar_cat").hide();
    
    let datos = new FormData();
    datos.append('accion', 'listar_productos');
    datos.append('idCategoria', id);
    enviaAjax(datos);
}

function llenarSelectCategorias() {
    let datos = new FormData();
    datos.append('accion', 'cargar_select_categorias');
    enviaAjax(datos);
}

function nuevoProducto() {
    limpia();
    $("#accion").val("incluir"); 
    
    if (categoriaActual !== null) {
        $("#idCategoria_select").val(categoriaActual);
    }

    $("#modal_producto_label").text("Nuevo Producto");
    $("#btn_guardar_prod").text("Guardar Producto"); 
    $("#modal_producto").modal("show");
}

function validarenvio() {
    if (validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/, $("#nombre"), $("#snombre"), "") == 0) {
        muestraMensaje("Verifique el Nombre del Producto");
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
    if (!er.test(tecla) && key != 8 && key != 13) {
        e.preventDefault();
    }
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

function pone(id, nombre, precio, descripcion, foto, idCategoria, accion) {
    if (accion == 0) {
        $("#accion").val("modificar");
        $("#idProducto").val(id);
        $("#nombre").val(nombre);
        $("#precio").val(precio);
        $("#descripcion").val(descripcion);
        $("#foto").val(foto);
        $("#idCategoria_select").val(idCategoria);
        
        $("#modal_producto_label").text("Editar Producto");
        $("#btn_guardar_prod").text("Modificar Producto");
        $("#modal_producto").modal("show");
    } else {
        if (confirm("¿Estás seguro de eliminar este producto?")) {
            var datos = new FormData();
            datos.append('accion', 'eliminar');
            datos.append('idProducto', id);
            enviaAjax(datos);
        }
    }
}

function enviaAjax(datos) {
    $.ajax({
        async: true,
        url: "index.php?pagina=productos",
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        beforeSend: function () {},
        timeout: 10000,
        success: function (respuesta) {
            console.log(respuesta);
            try {
                var lee = JSON.parse(respuesta);
                
                if (lee.resultado == 'listar_categorias' || lee.resultado == 'listar_productos') {
                    $("#cuadricula_items").html(lee.mensaje);
                } 
                else if (lee.resultado == 'cargar_select_categorias') {
                    $("#idCategoria_select").html(lee.mensaje);
                }
                else if (lee.resultado == 'incluir') {
                    muestraMensaje(lee.mensaje);
                    if (lee.mensaje == 'Producto Registrado') { 
                        $("#modal_producto").modal("hide");
                        if (categoriaActual !== null) verCategoria(categoriaActual, $("#titulo_seccion").text());
                        else cargarCategorias();
                    }
                } 
                else if (lee.resultado == 'modificar') {
                    muestraMensaje(lee.mensaje);
                    if (lee.mensaje == 'Producto Modificado') {
                        $("#modal_producto").modal("hide");
                        if (categoriaActual !== null) verCategoria(categoriaActual, $("#titulo_seccion").text());
                        else cargarCategorias();
                    }
                } 
                else if (lee.resultado == 'eliminar') {
                    muestraMensaje(lee.mensaje);
                    if (lee.mensaje == 'Producto Eliminado') {
                        if (categoriaActual !== null) verCategoria(categoriaActual, $("#titulo_seccion").text());
                        else cargarCategorias();
                    }
                } 
                else if (lee.resultado == 'error') {
                    muestraMensaje(lee.mensaje);
                }
            } catch (e) {
                alert("Error en JSON" + e.name);
            }
        },
        error: function(request, status, err) {
            if (status == "timeout") {
                muestraMensaje("Servidor Ocupado, Intente de Nuevo");
            } else {
                muestraMensaje("ERROR: <br/>" + request + status + err);
            }
        },
        complete: function () {},
    });
}

function limpia() {
    $("#f_producto")[0].reset();
    $("#idProducto").val("");
    $("#nombre, #precio, #descripcion, #foto").css("border-color", "");
}