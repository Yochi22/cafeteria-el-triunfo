<?php

require_once("modelo/datos.php");

class monedas extends datos
{
    // Atributos
    private $codigoOriginal;
    private $codigoMon;
    private $nombreMon;
    private $simboloMon;
    private $tasaMon;

    // Setters
    function set_codigoOriginal($valor) { $this->codigoOriginal = $valor; }
    function set_codigoMon($valor) { $this->codigoMon = $valor; }
    function set_nombreMon($valor) { $this->nombreMon = $valor; }
    function set_simboloMon($valor) { $this->simboloMon = $valor; }
    function set_tasaMon($valor) { $this->tasaMon = $valor; }

    // Getters
    function get_codigoOriginal() { return $this->codigoOriginal; }
    function get_codigoMon() { return $this->codigoMon; }
    function get_nombreMon() { return $this->nombreMon; }
    function get_simboloMon() { return $this->simboloMon; }
    function get_tasaMon() { return $this->tasaMon; }

    // Función Existe
    function existe($codigoMon)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $resultado = $co->prepare("SELECT * FROM monedas WHERE codigoMon = :codigoMon");
            $resultado->bindParam(':codigoMon', $codigoMon);
            $resultado->execute();

            $fila = $resultado->fetch(PDO::FETCH_ASSOC);

            if ($fila) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // Función Incluir
    function incluir()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!$this->existe($this->codigoMon)) {
            try {
                $i = $co->prepare("INSERT INTO monedas (codigoMon, nombreMon, simboloMon, tasaMon) VALUES (:codigoMon, :nombreMon, :simboloMon, :tasaMon)");
                $i->bindParam(":codigoMon", $this->codigoMon);
                $i->bindParam(":nombreMon", $this->nombreMon);
                $i->bindParam(":simboloMon", $this->simboloMon);
                $i->bindParam(":tasaMon", $this->tasaMon);
                $i->execute();

                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Moneda registrada.';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Ya existe una moneda con ese código.';
        }

        return $r;
    }

    // Función Consultar
    function consultar()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $resultado = $co->query("SELECT * FROM monedas");

            if ($resultado) {
                $html = '';

                foreach ($resultado as $fila) {
                    $html .= "<tr>";
                    $html .= "<td>" . $fila['codigoMon'] . "</td>";
                    $html .= "<td>" . $fila['nombreMon'] . "</td>";
                    $html .= "<td>" . $fila['simboloMon'] . "</td>";
                    $html .= "<td>" . $fila['tasaMon'] . "</td>";
                    $html .= "<td>";
                    $html .= "<button type='button' class='btn text-white w-80 small-width m-1' style='background-color: #FF8C00;' onclick='pone(this)'><i class='bi bi-pencil-square'></i><span class='d-none d-sm-inline'> Modificar</span></button>";
                    $html .= "<button type='button' class='btn text-white w-80 small-width m-1' style='background-color: #FF8C00;' onclick='eliminar(this)'><i class='bi bi-trash-fill'></i><span class='d-none d-sm-inline'> Eliminar</span></button>";
                    $html .= "</td>";
                    $html .= "</tr>";
                }

                if ($html == "") {
                    $html .= "<tr>";
                    $html .= "<td colspan = '5' class='text-center text-muted py-4>'";
                    $html .= "<span><i class='bi bi-person-fill fs-1' style='color: #FF8C00'></i></span>";
                    $html .= "<h5 class= 'text-dashboard'> No tienes monedas registradas. </h5>";
                    $html .= "</td>";
                    $html .= "</tr>";
                }

                $r['resultado'] = 'consultar';
                $r['mensaje'] = $html;
            } else {
                $r['resultado'] = 'consultar';
                $r['mensaje'] = '';
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    // Función Modificar
    function modificar()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!$this->existe($this->codigoOriginal)) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'La moneda no existe.';
            return $r;
        }

        if ($this->codigoMon !== $this->codigoOriginal && $this->existe($this->codigoMon)) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Ya existe una moneda con ese código.';
            return $r;
        }

        try {
            $m = $co->prepare("UPDATE moendas SET codigoMon = :codigoMon, nombreMon = :nombreMon, simboloMon = :simboloMon, tasaMon = :tasaMon WHERE codigoMon = :codigoOriginal");
            $m->bindParam(":codigoOriginal", $this->codigoOriginal);
            $m->bindParam(":codigoCat", $this->codigoMon);
            $m->bindParam(":nombreCat", $this->nombreMon);
            $m->bindParam(":descCat", $this->simboloMon);
            $m->bindParam(":fotoCat", $this->tasaMon);
            $m->execute();

            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Moneda modificada.';
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    // Función Eliminar
    function eliminar()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($this->existe($this->codigoMon)) {
            try {
                $e = $co->prepare("DELETE FROM monedas WHERE codigoMon = :codigoMon");
                $e->bindParam(":codigoMon", $this->codigoMon);
                $e->execute();

                $r['resultado'] = 'eliminar';
                $r['mensaje'] = 'Moneda eliminada.';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'La moneda no existe.';
        }

        return $r;
    }

    // Función Buscar
    function buscar($valor)
    {
        $r = array();
        $busqueda = "%" . trim($valor) . "%";
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            if ($busqueda) {
                $b = $co->prepare("SELECT * FROM monedas WHERE (codigoMon LIKE :busqueda OR nombreMon LIKE :busqueda OR simboloMon LIKE :busqueda)");
                $b->bindParam(':busqueda', $busqueda);
                $b->execute();
                $resultado = $b->fetchAll();
                $html = '';

                foreach ($resultado as $fila) {
                    $html .= "<tr>";
                    $html .= "<td>" . $fila['codigoMon'] . "</td>";
                    $html .= "<td>" . $fila['nombreMon'] . "</td>";
                    $html .= "<td>" . $fila['simboloMon'] . "</td>";
                    $html .= "<td>" . $fila['tasaMon'] . "</td>";
                    $html .= "<td>";
                    $html .= "<button type='button' class='btn text-white w-80 small-width m-1' style='background-color: #FF8C00;' onclick='pone(this)'><i class='bi bi-pencil-square'></i><span class='d-none d-sm-inline'> Modificar</span></button>";
                    $html .= "<button type='button' class='btn text-white w-80 small-width m-1' style='background-color: #FF8C00;' onclick='eliminar(this)'><i class='bi bi-trash-fill'></i><span class='d-none d-sm-inline'> Eliminar</span></button>";
                    $html .= "</td>";
                    $html .= "</tr>";
                }

                if ($html == "") {
                    $html .= "<tr>";
                    $html .= "<td colspan = '5' class='text-center text-muted py-4>'";
                    $html .= "<span><i class='bi bi-person-fill fs-1' style='color: #FF8C00'></i></span>";
                    $html .= "<h5 class= 'text-dashboard'> No tienes monedas registradas. </h5>";
                    $html .= "</td>";
                    $html .= "</tr>";
                }

                $r['resultado'] = 'buscar';
                $r['mensaje'] = $html;
            } else {
                $r['resultado'] = 'consultar';
                $r['mensaje'] = '';
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
}

?>