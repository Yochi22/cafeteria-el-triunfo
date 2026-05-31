<?php
require_once('modelo/datos.php');

class categorias extends datos
{
    private $idCategoria;
    private $nombre;
    private $descripcion;
    private $foto;
    private $estado;

    function set_idCategoria($valor) {
        $this->idCategoria = $valor;
    }

    function set_nombre($valor) {
        $this->nombre = $valor;
    }

    function set_descripcion($valor) {
        $this->descripcion = $valor;
    }

    function set_foto($valor) {
        $this->foto = $valor;
    }

    function set_estado($valor) {
        $this->estado = $valor;
    }

    function get_idCategoria() {
        return $this->idCategoria;
    }

    function get_nombre() {
        return $this->nombre;
    }

    function get_descripcion() {
        return $this->descripcion;
    }

    function get_foto() {
        return $this->foto;
    }

    function get_estado() {
        return $this->estado;
    }

function incluir()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $stmt = $co->prepare("SELECT idCategoria, estado FROM categorias WHERE nombre = :nombre");
            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->execute();
            $categoria_existente = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($categoria_existente) {
                if ($categoria_existente['estado'] == 1) {
                    return "Ya Existe una Categoría ese Nombre";
                } else {
                    $m = $co->prepare("UPDATE categorias SET descripcion = :descripcion, foto = :foto, estado = 1 WHERE idCategoria = :idCategoria");
                    $m->bindParam(':idCategoria', $categoria_existente['idCategoria']);
                    $m->bindParam(':descripcion', $this->descripcion);
                    $m->bindParam(':foto', $this->foto);
                    $m->execute();

                    return "Registro Incluido";
                }
            } else {
                $r = $co->prepare("INSERT INTO categorias(idCategoria, nombre, descripcion, foto) 
                                   VALUES(:idCategoria, :nombre, :descripcion, :foto)");

                $r->bindParam(':idCategoria', $this->idCategoria);
                $r->bindParam(':nombre', $this->nombre);
                $r->bindParam(':descripcion', $this->descripcion);
                $r->bindParam(':foto', $this->foto);
                $r->execute();

                return "Registro Incluido";
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    function modificar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            if ($this->existe_nombre($this->nombre)) {

                $m = $co->prepare("UPDATE categorias SET nombre = :nombre, descripcion = :descripcion, foto = :foto WHERE idCategoria = :idCategoria");

                $m->bindParam(':idCategoria', $this->idCategoria);
                $m->bindParam(':nombre', $this->nombre);
                $m->bindParam(':descripcion', $this->descripcion);
                $m->bindParam(':foto', $this->foto);
                $m->execute();
                return "Registro Modificado";
            } else {
                return "No Existe esa Categoría";
            }
        } catch (Exception $e) {
            return $e->getMessage();
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
                return "Registro Eliminado";
            } else {
                return "No Existe esa Categoría";
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    function consultar()
    {
        try {
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $resultado = $co->query("SELECT * FROM categorias WHERE estado = 1");
            
            if ($resultado) {
                $respuesta = '';
                foreach ($resultado as $r) {
                    $foto = !empty($r['foto']) ? $r['foto'] : 'img/principal.jpg';
                    $respuesta .= '
                    <div class="col-md-4 mb-4">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                            <img src="' . $foto . '" class="card-img-top" style="height:180px; object-fit:cover;">
                            <div class="card-body">
                                <h5 class="text-dashboard fw-bold mb-1">' . $r['nombre'] . '</h5>
                                <p class="text-muted small mb-3">' . $r['descripcion'] . '</p>
                                <div class="d-flex justify-content-end gap-2">
                                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="editar(' . $r['idCategoria'] . ')">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="eliminar(' . $r['idCategoria'] . ')">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
                return $respuesta;
            }
            return '';
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    private function existe_nombre($nombre)
    {
        try {
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $co->prepare("SELECT idCategoria FROM categorias WHERE nombre = ?");
            $stmt->execute([$nombre]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    private function existe_id($id)
    {
        try {
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $co->prepare("SELECT idCategoria FROM categorias WHERE idCategoria = ?");
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
            $stmt = $co->prepare("SELECT * FROM categorias WHERE idCategoria = ?");
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
            return json_encode(array('resultado' => $e->getMessage()));
        }
    }
}
?>