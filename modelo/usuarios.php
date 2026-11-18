<?php

require_once('modelo/datos.php');

class usuarios extends datos
{
    // Atributos
    private $idUsuario;
    private $idPersonal;
    private $idRol;
    private $username;
    private $password;

    // Setters
    function set_idUsuario($valor) { $this->idUsuario = $valor; }
    function set_idPersonal($valor) { $this->idPersonal = $valor; }
    function set_idRol($valor) { $this->idRol = $valor; }
    function set_username($valor) { $this->username = trim($valor); }
    function set_password($valor) { $this->password = $valor; }

    // Getters
    function get_idUsuario() { return $this->idUsuario; }
    function get_idPersonal() { return $this->idPersonal; }
    function get_idRol() { return $this->idRol; }
    function get_username() { return $this->username; }
    function get_password() { return $this->password; }

    // Función Existe Usuario
    function existe($idUsuario)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $resultado = $co->prepare("SELECT * FROM usuarios WHERE idUsuario = :idUsuario");
            $resultado->bindParam(':idUsuario', $idUsuario);
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

    // Función Existe Personal
    function existePersonal($idPersonal)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $resultado = $co->prepare("SELECT * FROM usuarios WHERE idPersonal = :idPersonal");
            $resultado->bindParam(':idPersonal', $idPersonal);
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

        if (!$this->existePersonal($this->idPersonal)) {
            try {
                $inc = $co->prepare("INSERT INTO usuarios(idPersonal, idRol, username, password) VALUES (:idPersonal, :idRol, :username, :password)");

                $passwordCifrada = password_hash($this->password, PASSWORD_DEFAULT);

                $inc->bindParam(':idPersonal', $this->idPersonal);
                $inc->bindParam(':idRol', $this->idRol);
                $inc->bindParam(':username', $this->username);
                $inc->bindParam(':password', $passwordCifrada);
                $inc->execute();

                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Usuario incluido.';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Este empleado ya tiene un usuario asignado.';
        }

        return $r;
    }

    // Función Consultar Usuarios
    function consultar()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $resultado = $co->query("
                SELECT usuarios.idUsuario, usuarios.idPersonal, usuarios.idRol,
                       usuarios.username, personal.cedulaPer, personal.nombrePer,
                       personal.apellidoPer, roles.rol
                FROM usuarios
                INNER JOIN personal ON usuarios.idPersonal = personal.idPersonal
                INNER JOIN roles ON usuarios.idRol = roles.idRol
            ");
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return $r;
        }
    }

    // Función Consultar Personal
    function consultarPersonal()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $resultado = $co->query("SELECT * FROM personal");

            $respuesta = "<option value=''>Seleccione el personal</option>";

            foreach ($resultado as $fila) {
                $respuesta .= "<option value='" . $fila['idPersonal'] . "'>";
                $respuesta .= $fila['cedulaPer'] . " - " . $fila['nombrePer'] . " " . $fila['apellidoPer'];
                $respuesta .= "</option>";
            }

            $r['resultado'] = 'consultarPersonal';
            $r['mensaje'] = $respuesta;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    // Función Consultar Roles
    function consultarRoles()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $resultado = $co->query("SELECT * FROM roles");

            $respuesta = "<option value=''>Seleccione un rol</option>";

            foreach ($resultado as $fila) {
                $respuesta .= "<option value='" . $fila['idRol'] . "'>";
                $respuesta .= $fila['rol'];
                $respuesta .= "</option>";
            }

            $r['resultado'] = 'consultarRoles';
            $r['mensaje'] = $respuesta;
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

        if ($this->existe($this->idUsuario)) {
            try {

                if ($this->password != "") {
                    $passwordCifrada = password_hash($this->password, PASSWORD_DEFAULT);

                    $mod = $co->prepare("UPDATE usuarios SET idPersonal = :idPersonal, idRol = :idRol, username = :username, password = :password WHERE idUsuario = :idUsuario");

                    $mod->bindParam(':password', $passwordCifrada);
                } else {
                    $mod = $co->prepare("UPDATE usuarios SET idPersonal = :idPersonal, idRol = :idRol, username = :username WHERE idUsuario = :idUsuario");
                }

                $mod->bindParam(':idUsuario', $this->idUsuario);
                $mod->bindParam(':idPersonal', $this->idPersonal);
                $mod->bindParam(':idRol', $this->idRol);
                $mod->bindParam(':username', $this->username);
                $mod->execute();

                $r['resultado'] = 'modificar';
                $r['mensaje'] = 'Usuario modificado.';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'El usuario no existe.';
        }

        return $r;
    }

    // Función Eliminar
    function eliminar()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($this->existe($this->idUsuario)) {
            try {
                $eli = $co->prepare("DELETE FROM usuarios WHERE idUsuario = :idUsuario");
                $eli->bindParam(':idUsuario', $this->idUsuario);
                $eli->execute();

                $r['resultado'] = 'eliminar';
                $r['mensaje'] = 'Usuario eliminado.';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'El usuario no existe.';
        }

        return $r;
    }
}

?>