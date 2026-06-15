$(document).ready(function() {

try {
    cargarCategorias();
    llenarSelectCategorias();
} catch (e) {
        console.error(e);
}

    $("#codigoProd").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#codigoProd").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{5,50}$/, $(this), $("#scodigoProd"), "Código inválido.");
    });

    $("#nombreProd").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#nombreProd").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/, $(this), $("#snombreProd"), "Nombre inválido.");
    });

    $("#descProd").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC.,-]*$/, e);
    });
    $("#descProd").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC.,-]{3,150}$/, $(this), $("#sdescProd"), "Descripción inválida.");
    });

    $("#precioProd").on("keypress", function (e) {
        if (e.key === 'e' || e.key === 'E' || e.key === '+' || e.key === '-') {
            e.preventDefault();
        }
        validarkeypress(/^[0-9.]*$/, e);
    });
    $("#precioProd").on("keyup", function () {
        validarkeyup(/^[0-9]{1,8}(\.[0-9]{1,2})?$/, $(this), $("#sprecioProd"), "Precio inválido.");
    });

    $("#btn_guardar_prod").on("click", function (e) {
        e.preventDefault();
        if ($(this).text() == 'incluir') {
            if (validarEnvio()) {
                var datos = new FormData();
                datos.append('accion', 'incluir');
                datos.append('codigoProd', $("#codigoProd").val());
                datos.append('nombreProd', $("#nombreProd").val());
                datos.append('descProd', $("#descProd").val());
                datos.append('precioProd', $("#precioProd").val());
                datos.append('fotoProd', $("#fotoProd").val());
                datos.append('idCategoria', $("#idCategoria_select").val());
                enviaAjax(datos);
            }
        }
        else if ($(this).text() == 'modificar') {
            if (validarEnvio()) {
                var datos = new FormData();
                datos.append('accion', 'modificar');
                datos.append('codigoOrig', $("#codigoOrig").val());
                datos.append('codigoProd', $("#codigoProd").val());
                datos.append('nombreProd', $("#nombreProd").val());
                datos.append('descProd', $("#descProd").val());
                datos.append('precioProd', $("#precioProd").val());
                datos.append('fotoProd', $("#fotoProd").val());
                datos.append('idCategoria', $("#idCategoria_select").val());
                enviaAjax(datos);
            }
        }
    });

    function ejecutarBusqueda() {
        var valor = $("#valorBusqueda").val();

        if (valor && valor.trim().length > 0) {
            var datos = new FormData();
            datos.append('accion', 'buscar');
            datos.append('valorBusqueda', valor);
            enviaAjax(datos);
        } else {
            consultar();
        }
    }

    $("#valorBusqueda").on("keyup", function () {
        ejecutarBusqueda();
    });

    $("#btnBuscar").on("click", function () {
        ejecutarBusqueda();
    });

    $("#btnEliminar").on("click", function () {
        var codigoEliminado = $("#codigoEliminar").val();
        var datos = new FormData();
        datos.append('accion', 'eliminar');
        datos.append('codigoProd', codigoEliminado); 
        enviaAjax(datos);
    });

    $("#btn_nuevo_producto").on("click", function () {
        nuevoProducto();
    });
});

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
    if (categoriaActual !== null) {
        $("#idCategoria_select").val(categoriaActual);
    }
    $("#modal_producto_label").text("Nuevo Producto");
    $("#btn_guardar_prod").text("incluir"); 
    $("#modal_producto").modal("show");
}

function pone(codigoProd, nombreProd, precioProd, descProd, fotoProd, idCategoria) {
    $("#codigoOrig").val(codigoProd); 
    $("#codigoProd").val(codigoProd);
    $("#nombreProd").val(nombreProd);
    $("#precioProd").val(precioProd);
    $("#descProd").val(descProd);
    $("#fotoProd").val(fotoProd);
    $("#idCategoria_select").val(idCategoria);

    $("#modal_producto_label").text("Modificar Producto");
    $("#btn_guardar_prod").text('modificar'); 
    $("#modal_producto").modal("show");
}

function eliminar(codigoProd) {
    $("#codigoEliminar").val(codigoProd); 
    $("#modal_eliminar").modal("show");
}

function validarEnvio() {
    if ($("#btn_guardar_prod").text() == 'modificar') {
        if (validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{5,50}$/, $("#codigoProd"), $("#scodigoProd"), "Código inválido.") == 0) {
            mostrarMensaje("Código inválido.");
            return false;
        }
    }
    if (validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/, $("#nombreProd"), $("#snombreProd"), "Nombre inválido.") == 0) {
        mostrarMensaje("Nombre inválido.");
        return false;
    }
    if (validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC.,-]{3,150}$/, $("#descProd"), $("#sdescProd"), "Descripción inválida.") == 0) {
        mostrarMensaje("Descripción inválida.");
        return false;
    }
    if (validarkeyup(/^[0-9]{1,8}(\.[0-9]{1,2})?$/, $("#precioProd"), $("#sprecioProd"), "Precio inválido.") == 0) {
        mostrarMensaje("Precio inválido.");
        return false;
    }

    return true;
}

function mostrarMensaje(mensaje) {
    $("#contenidoModal").html(mensaje);
    $("#mostrarModal").modal("show");
    setTimeout(function () { $("#mostrarModal").modal("hide") }, 3000);
}

function validarkeypress(er, e) {
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
    if (!a && key != 8 && key != 13) {
        e.preventDefault();
    }
}

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

function enviaAjax(datos) {
    $.ajax({
        async: true,
        url: "index.php?pagina=productos",
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        beforeSend: function () { },
        timeout: 10000,
        success: function (respuesta) {
            try {
                var lee = JSON.parse(respuesta);
                
                if (lee.resultado == 'listar_categorias' || lee.resultado == 'listar_productos' || lee.resultado == 'buscar') {
                    $("#cuadricula_items").html(lee.mensaje);
                } 
                else if (lee.resultado == 'cargar_select_categorias') {
                    $("#idCategoria_select").html(lee.mensaje);
                }
                else if (lee.resultado == 'incluir') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje == 'Producto Registrado') { 
                        $("#modal_producto").modal("hide");
                        if (categoriaActual !== null) verCategoria(categoriaActual, $("#titulo_seccion").text());
                        else cargarCategorias();
                    }
                } 
                else if (lee.resultado == 'modificar') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje == 'Producto Modificado') {
                        $("#modal_producto").modal("hide");
                        if (categoriaActual !== null) verCategoria(categoriaActual, $("#titulo_seccion").text());
                        else cargarCategorias();
                    }
                } 
                else if (lee.resultado == 'eliminar') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje == 'Producto Eliminado') { 
                        $("#modal_eliminar").modal("hide");
                        if (categoriaActual !== null) verCategoria(categoriaActual, $("#titulo_seccion").text());
                        else cargarCategorias();
                    }
                } 
                else if (lee.resultado == 'error') {
                    mostrarMensaje(lee.mensaje);
                }
            } catch (e) {
                alert("Error en JSON " + e.name);
            }
        },
        error: function(request, status, err) {
            if (status == "timeout") {
                mostrarMensaje("Servidor Ocupado, Intente de Nuevo");
            } else {
                mostrarMensaje("ERROR: <br/>" + request + status + err);
            }
        },
        complete: function () {},
    });
}
function limpia() {
    $("#codigoOrig").val("");
    $("#codigoProd").val("");
    $("#nombreProd").val("");
    $("#precioProd").val("");
    $("#descProd").val("");
    $("#fotoProd").val("");
    
    $("#scodigoProd").text("");
    $("#snombreProd").text("");
    $("#sprecioProd").text("");
    $("#sdescProd").text("");
}