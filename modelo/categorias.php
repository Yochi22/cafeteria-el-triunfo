<?php
require_once('modelo/datos.php');

class categorias extends datos
{
    private $idCategoria;
    private $nombre;
    private $descripcion;
    private $foto;
    private $estado;

    function set_idCategoria($valor)
    {
        $this->idCategoria = $valor;
    }
    function set_nombre($valor)
    {
        $this->nombre = $valor;
    }
    function set_descripcion($valor)
    {
        $this->descripcion = $valor;
    }
    function set_foto($valor)
    {
        $this->foto = $valor;
    }
    function set_estado($valor)
    {
        $this->estado = $valor;
    }

    function get_idCategoria()
    {
        return $this->idCategoria;
    }
    function get_nombre()
    {
        return $this->nombre;
    }
    function get_descripcion()
    {
        return $this->descripcion;
    }
    function get_foto()
    {
        return $this->foto;
    }
    function get_estado()
    {
        return $this->estado;
    }

    function incluir()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $r = $co->prepare("SELECT idCategoria, estado FROM categorias WHERE nombre = :nombre");
            $r->bindParam(':nombre', $this->nombre);
            $r->execute();
            $categoria_existente = $r->fetch(PDO::FETCH_ASSOC);

            if ($categoria_existente) {
                if ($categoria_existente['estado'] == 1) {
                    return "Ya Existe una Categoría con ese Nombre.";
                } else {
                    $m = $co->prepare("UPDATE categorias SET descripcion = :descripcion, foto = :foto, estado = 1 WHERE idCategoria = :idCategoria");
                    $m->bindParam(':idCategoria', $categoria_existente['idCategoria']);
                    $m->bindParam(':descripcion', $this->descripcion);
                    $m->bindParam(':foto', $this->foto);
                    $m->execute();
                    return "Registro Incluido.";
                }
            } else {
                $r = $co->prepare("INSERT INTO categorias(nombre, descripcion, foto, estado) 
                                   VALUES(:nombre, :descripcion, :foto, 1)");
                $r->bindParam(':nombre', $this->nombre);
                $r->bindParam(':descripcion', $this->descripcion);
                $r->bindParam(':foto', $this->foto);
                $r->execute();
                return "Registro Incluido.";
            }
        } catch (Exception $e) {
            $e->getMessage();
            return "Error al Intentar Incluir el Registro.";
        }
    }

    function modificar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            if ($this->existe_id($this->idCategoria)) {
                $m = $co->prepare("SELECT idCategoria FROM categorias WHERE nombre = :nombre AND idCategoria != :idCategoria AND estado = 1");
                $m->bindParam(':nombre', $this->nombre);
                $m->bindParam(':idCategoria', $this->idCategoria);
                $m->execute();

                if ($m->rowCount() > 0) {
                    return "Ya Existe Otra Categoría con ese Nombre.";
                }

                $m = $co->prepare("UPDATE categorias SET nombre = :nombre, descripcion = :descripcion, foto = :foto WHERE idCategoria = :idCategoria");
                $m->bindParam(':idCategoria', $this->idCategoria);
                $m->bindParam(':nombre', $this->nombre);
                $m->bindParam(':descripcion', $this->descripcion);
                $m->bindParam(':foto', $this->foto);
                $m->execute();

                return "Registro Modificado.";
            } else {
                return "No Existe esa Categoría.";
            }
        } catch (Exception $e) {
            $e->getMessage();
            return "Error al Intentar Modificar el Registro.";
        }
    }

    function eliminar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            if ($this->existe_id($this->idCategoria)) {
                $e = $co->prepare("UPDATE categorias SET estado = 0 WHERE idCategoria = :idCategoria");
                $e->bindParam(':idCategoria', $this->idCategoria);
                $e->execute();
                return "Registro Eliminado.";
            } else {
                return "No Existe esa Categoría.";
            }
        } catch (Exception $e) {
            $e->getMessage();
            return "Error al Intentar Eliminar el Registro.";
        }
    }

    function consultar()
    {
        try {
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $resultado = $co->query("SELECT * FROM categorias WHERE estado = 1");

            if ($resultado) {
                $respuesta = $resultado->fetchAll(PDO::FETCH_ASSOC);
                return json_encode($respuesta);
            }
            return json_encode([]);
        } catch (Exception $e) {
            $e->getMessage();
            return json_encode(['error' => 'Error al Consultar la Base de Datos.']);
        }
    }

    private function existe_id($id)
    {
        try {
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $co->prepare("SELECT idCategoria FROM categorias WHERE idCategoria = ? AND estado = 1");
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    function consultar_id()
    {
        try {
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $co->prepare("SELECT * FROM categorias WHERE idCategoria = ? AND estado = 1");
            $stmt->execute([$this->idCategoria]);
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($fila) {
                $envia = array('resultado' => 'encontro');
                $envia = array_merge($envia, $fila);
                return json_encode($envia);
            } else {
                return json_encode(array('resultado' => 'noencontro'));
            }
        } catch (Exception $e) {
            $e->getMessage();
            return json_encode(array('resultado' => 'Error de Servidor'));
        }
    }
}