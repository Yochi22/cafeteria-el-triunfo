<?php

require_once('modelo/datos.php');

class personal extends datos
{
    // Atributos 
    private $cedulaPer;
    private $nombrePer;
    private $apellidoPer;

    // Setters
    function set_cedulaPer($valor){ $this->cedulaPer = $valor; }
    function set_nombrePer($valor){ $this->nombrePer = $valor; }
    function set_apellidoPer($valor){ $this->apellidoPer = $valor; }

    // Getters
    function get_cedulaPer($valor){ $this->cedulaPer = $valor; }
    function get_nombrePer($valor){ $this->nombrePer = $valor; }
    function get_apellidoPer($valor){ $this->apellidoPer = $valor; }

    // Función Existe
    function existe($cedulaPer)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $resultado = $co->prepare("SELECT * FROM personal WHERE cedulaPer = :cedulaPer");
            $resultado->bindParam(':cedulaPer', $cedulaPer);
            $resultado->execute();

            $fila = $resultado->fetchAll(PDO::FETCH_BOTH);

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

        if (!$this->existe($this->cedulaPer)) {
            try {
                $inc = $co->prepare("INSERT INTO personal(cedulaPer, nombrePer, apellidoPer) VALUES (:cedulaPer, :nombrePer, :apellidoPer)");
                $inc->bindParam(':cedulaPer', $this->cedulaPer);
                $inc->bindParam(':nombrePer', $this->nombrePer);
                $inc->bindParam(':apellidoPer', $this->apellidoPer);
                $inc->execute();

                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Empleado incluido.';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Ya existe un empleado con esa cédula.';
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
            $resultado = $co->query("SELECT * FROM personal");

            if ($resultado) {
                $respuesta = '';

                foreach ($resultado as $fila) {
                    $respuesta .= "<tr>";
                    $respuesta .= "<td>" . $fila['cedulaPer'] . "</td>";
                    $respuesta .= "<td>" . $fila['nombrePer'] . " " . $fila['apellidoPer'] . "</td>";
                    $respuesta .= "<td>";
                    $respuesta .= "<button type='button' class='btn text-white w-80 small-width m-1' style='background-color: #FF8C00;' onclick='pone(this)'><i class='bi bi-pencil-square'></i> Modificar</button>";
                    $respuesta .= "<button type='button' class='btn text-white w-80 small-width m-1' style='background-color: #FF8C00;' onclick='eliminar(this)'><i class='bi bi-trash-fill'></i> Eliminar</button>";
                    $respuesta .= "</td>";
                    $respuesta .= "</tr>";
                }

                if ($respuesta == "") {
                    $respuesta .= "<tr>";
                    $respuesta .= "<td colspan = '5' class='text-center text-muted py-4>'";
                    $respuesta .= "<span><i class='bi bi-person-fill fs-1' style='color: #FF8C00'></i></span>";
                    $respuesta .= "<h5 class= 'text-dashboard'> No tienes empleados registrados.</h5>";
                    $respuesta .= "</td>";
                    $respuesta .= "</tr>";
                }

                $r['resultado'] = 'consultar';
                $r['mensaje'] = $respuesta;
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

        if ($this->existe($this->cedulaPer)) {
            try {
                $mod = $co->prepare("UPDATE personal SET nombrePer = :nombrePer, apellidoPer = :apellidoPer WHERE cedulaPer = :cedulaPer");
                $mod->bindParam(':cedulaPer', $this->cedulaPer);
                $mod->bindParam(':nombrePer', $this->nombrePer);
                $mod->bindParam(':apellidoPer', $this->apellidoPer);
                $mod->execute();

                $r['resultado'] = 'modificar';
                $r['mensaje'] = 'Empleado modificado.';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'La cédula no existe.';
        }

        return $r;
    }

    // Función Eliminar
    function eliminar()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($this->existe($this->cedulaPer)) {
            try {
                $eli = $co->prepare("DELETE FROM personal WHERE cedulaPer = :cedulaPer");
                $eli->bindParam(':cedulaPer', $this->cedulaPer);
                $eli->execute();

                $r['resultado'] = 'eliminar';
                $r['mensaje'] = 'Empleado eliminado.';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'La cédula no existe.';
        }

        return $r;
    }

    // Función Buscar
    function buscar($valor)
    {
        $r = array();
        $valor = trim($valor);
        $busqueda = "%" . $valor . "%";
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            if ($busqueda) {
                $bus = $co->prepare("SELECT * FROM personal WHERE (cedulaPer LIKE :busqueda OR nombrePer LIKE :busqueda OR apellidoPer LIKE :busqueda)");
                $bus->bindParam(':busqueda', $busqueda);
                $bus->execute();
                $resultado = $bus->fetchAll();
                $respuesta = "";

                foreach ($resultado as $fila) {
                    $respuesta .= "<tr>";
                    $respuesta .= "<td>" . $fila['cedulaPer'] . "</td>";
                    $respuesta .= "<td>" . $fila['nombrePer'] . " " . $fila['apellidoPer'] . "</td>";
                    $respuesta .= "<td>";
                    $respuesta .= "<button type='button' class='btn text-white w-80 small-width m-1' style='background-color: #FF8C00;' onclick='pone(this)'><i class='bi bi-pencil-square'></i> Modificar</button>";
                    $respuesta .= "<button type='button' class='btn text-white w-80 small-width m-1' style='background-color: #FF8C00;' onclick='eliminar(this)'><i class='bi bi-trash-fill'></i> Eliminar</button>";
                    $respuesta .= "</td>";
                    $respuesta .= "</tr>";
                }

                if ($respuesta == "") {
                    $respuesta .= "<tr>";
                    $respuesta .= "<td colspan='5' class='text-center text-muted py-4'>";
                    $respuesta .= "<span><i class='bi bi-person-fill-slash fs-1' style='color : #FF8C00'></i></i></span>";
                    $respuesta .= "<h5 class='text-dashboard'> No se encuentran empleados</h5>";
                    $respuesta .= "<h6 class='text-secondary'> Intenta con otra búsqueda.</h6>";
                    $respuesta .= "</td>";
                    $respuesta .= "</tr>";
                }

                $r['resultado'] = 'buscar';
                $r['mensaje'] = $respuesta;
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