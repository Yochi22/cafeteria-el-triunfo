<?php
require_once('modelo/datos.php');

class categorias extends datos
{
    private $idCategoria;
    private $nombre;
    private $descripcion;
    private $foto;

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

    function incluir()
    {
        if (!$this->existe($this->nombre)) {
            try {
                $co = $this->conecta();
                $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $sql = "INSERT INTO categoria (nombre, descripcion, foto) VALUES (?, ?, ?)";
                $stmt = $co->prepare($sql);
                $stmt->execute([$this->nombre, $this->descripcion, $this->foto]);
                
                return "Registro Incluido";
            } catch (Exception $e) {
                return $e->getMessage();
            }
        } else {
            return "Ya Existe una Categoría con ese Nombre";
        }
    }

    function modificar()
    {
        if ($this->existe_id($this->idCategoria)) {
            try {
                $co = $this->conecta();
                $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $sql = "UPDATE categoria SET nombre = ?, descripcion = ?, foto = ? WHERE idCategoria = ?";
                $stmt = $co->prepare($sql);
                $stmt->execute([$this->nombre, $this->descripcion, $this->foto, $this->idCategoria]);
                
                return "Registro Modificado";
            } catch (Exception $e) {
                return $e->getMessage();
            }
        } else {
            return "Categoría no Registrada";
        }
    }

    function eliminar()
    {
        if ($this->existe_id($this->idCategoria)) {
            try {
                $co = $this->conecta();
                $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql_check = "SELECT idCategoria FROM producto WHERE idCategoria = ?";
                $stmt_check = $co->prepare($sql_check);
                $stmt_check->execute([$this->idCategoria]);
                
                if ($stmt_check->rowCount() > 0) {
                    return "No se puede Eliminar la Categoría porque tiene Productos Asociados";
                }

                $sql = "DELETE FROM categoria WHERE idCategoria = ?";
                $stmt = $co->prepare($sql);
                $stmt->execute([$this->idCategoria]);
                
                return "Registro Eliminado";
            } catch (Exception $e) {
                return $e->getMessage();
            }
        } else {
            return "Categoría no Registrada";
        }
    }

    function consultar()
    {
        try {
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $resultado = $co->query("SELECT * FROM categoria");
            
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

    private function existe($nombre)
    {
        try {
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $co->prepare("SELECT idCategoria FROM categoria WHERE nombre = ?");
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
            $stmt = $co->prepare("SELECT idCategoria FROM categoria WHERE idCategoria = ?");
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
            $stmt = $co->prepare("SELECT * FROM categoria WHERE idCategoria = ?");
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