<?php

if (!is_file("modelo/" . $pagina . ".php")) {
    echo "Falta definir la clase " . $pagina;
    exit;
}

require_once("modelo/" . $pagina . ".php");

if (is_file("vista/" . $pagina . ".php")) {
    $o = new reportes();

    if (!empty($_POST)) {
        $accion = $_POST['accion'] ?? '';

        switch ($accion) {
            case 'filtrar':
                $o->set_fecIni($_POST['fecIni'] ?? '');
                $o->set_fecFin($_POST['fecFin'] ?? '');
                echo json_encode($o->consultarDatosReporte());
                break;

            case 'generar_pdf':
                $o->set_fecIni($_POST['fecIni'] ?? '');
                $o->set_fecFin($_POST['fecFin'] ?? '');
                $o->generarPDF();
                break;
        }

        exit;
    }

    require_once("vista/" . $pagina . ".php");
} else {
    echo "PÁGINA EN CONSTRUCCIÓN";
}

?>