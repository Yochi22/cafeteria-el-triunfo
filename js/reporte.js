$(document).ready(function () {
    ejecutarFiltrado();

    // Botón para filtrar fechas
    $("#btnFiltrar").on("click", function () {
        ejecutarFiltrado();
    });

    // Botón para limpiar filtro
    $("#btnLimpiar").on("click", function () {
        $("#fecIni").val("");
        $("#fecFin").val("");
        ejecutarFiltrado();
    });

    // Botón para exportar PDF enviando los filtros actuales
    $("#btnGenerarPDF").on("click", function () {
        var fInicio = $("#fecIni").val();
        var fFin = $("#fecFin").val();

        var form = $('<form action="" method="POST" target="_blank">' +
            '<input type="hidden" name="accion" value="generar_pdf">' +
            '<input type="hidden" name="fecIni" value="' + fInicio + '">' +
            '<input type="hidden" name="fecFin" value="' + fFin + '">' +
            '</form>');
        $('body').append(form);
        form.submit();
        form.remove();
    });
});

function ejecutarFiltrado() {
    var datos = new FormData();
    datos.append("accion", "filtrar");
    datos.append("fecIni", $("#fecIni").val());
    datos.append("fecFin", $("#fecFin").val());

    $.ajax({
        async: true,
        url: "index.php?pagina=reporte",
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        success: function (respuesta) {
            try {
                var lee = JSON.parse(respuesta);
                if (lee.resultado == "exito") {
                    $("#totalVen").text(lee.totalVen);
                    $("#promGanancias").text(lee.promedio);
                    $("#count_finalizados").text(lee.pedFinalizados);
                    $("#count_cancelados").text(lee.pedCancelados);
                }
            } catch (e) {
                console.error("Error al procesar la respuesta: ", e);
            }
        },
        error: function () {
            console.error("Error de comunicación con el servidor.");
        }
    });
}