<?php
require_once("modelo/datos.php");

class categorias extends datos
{
    private $idCategoria;
    private $nombre;
    private $descripcion;
    private $foto;
    private $estado;

    function set_idCategoria($valor) { $this->idCategoria = $valor; }
    function set_nombre($valor) { $this->nombre = $valor; }
    function set_descripcion($valor) { $this->descripcion = $valor; }
    function set_foto($valor) { $this->foto = $valor; }
    function set_estado($valor) { $this->estado = $valor; }

    function get_idCategoria() { return $this->idCategoria; }
    function get_nombre() { return $this->nombre; }
    function get_descripcion() { return $this->descripcion; }
    function get_foto() { return $this->foto; }
    function get_estado() { return $this->estado; }

    private function existe($idCategoria)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $st = $co->prepare("SELECT idCategoria FROM categorias WHERE idCategoria = :idCategoria AND estado = 1");
            $st->bindParam(':idCategoria', $idCategoria);
            $st->execute();
            
            $fila = $st->fetch(PDO::FETCH_ASSOC);
            return $fila ? true : false;
        } catch (Exception $e) {
            return false;
        }
    }

    function incluir()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $st = $co->prepare("SELECT idCategoria, estado FROM categorias WHERE nombre = :nombre");
            $st->bindParam(':nombre', $this->nombre);
            $st->execute();
            $categoria_existente = $st->fetch(PDO::FETCH_ASSOC);

            if ($categoria_existente) {
                if ($categoria_existente['estado'] == 1) {
                    $r['resultado'] = 'incluir';
                    $r['mensaje'] = 'Ya Existe una Categoría con ese Nombre.';
                } else {
                    $m = $co->prepare("UPDATE categorias SET descripcion = :descripcion, foto = :foto, estado = 1 WHERE idCategoria = :idCategoria");
                    $m->bindParam(':idCategoria', $categoria_existente['idCategoria']);
                    $m->bindParam(':descripcion', $this->descripcion);
                    $m->bindParam(':foto', $this->foto);
                    $m->execute();
                    
                    $r['resultado'] = 'incluir';
                    $r['mensaje'] = 'Registro Incluido';
                }
            } else {
                $m = $co->prepare("INSERT INTO categorias(nombre, descripcion, foto) VALUES(:nombre, :descripcion, :foto)");
                $m->bindParam(':nombre', $this->nombre);
                $m->bindParam(':descripcion', $this->descripcion);
                $m->bindParam(':foto', $this->foto);
                $m->execute();
                
                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Registro Incluido';
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    function modificar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        if ($this->existe($this->idCategoria)) {
            try {
                $m_check = $co->prepare("SELECT idCategoria FROM categorias WHERE nombre = :nombre AND idCategoria != :idCategoria AND estado = 1");
                $m_check->bindParam(':nombre', $this->nombre);
                $m_check->bindParam(':idCategoria', $this->idCategoria);
                $m_check->execute();

                if ($m_check->rowCount() > 0) {
                    $r['resultado'] = 'modificar';
                    $r['mensaje'] = 'Ya Existe Otra Categoría con ese Nombre.';
                    return $r;
                }

                $m = $co->prepare("UPDATE categorias SET nombre = :nombre, descripcion = :descripcion, foto = :foto WHERE idCategoria = :idCategoria");
                $m->bindParam(':idCategoria', $this->idCategoria);
                $m->bindParam(':nombre', $this->nombre);
                $m->bindParam(':descripcion', $this->descripcion);
                $m->bindParam(':foto', $this->foto);
                $m->execute();

                $r['resultado'] = 'modificar';
                $r['mensaje'] = 'Registro Modificado';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] =  $e->getMessage();
            }
        } else {
            $r['resultado'] = 'modificar';
            $r['mensaje'] =  'Categoría no Registrada';
        }
        return $r;
    }

    function eliminar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        
        if ($this->existe($this->idCategoria)) {
            try {
                $e = $co->prepare("UPDATE categorias SET estado = 0 WHERE idCategoria = :idCategoria");
                $e->bindParam(':idCategoria', $this->idCategoria);
                $e->execute();
                
                $r['resultado'] = 'eliminar';
                $r['mensaje'] = 'Registro Eliminado';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] =  $e->getMessage();
            }
        } else {
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'Categoría no Registrada';
        }
        return $r;
    }

    function consultar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $resultado = $co->query("SELECT * FROM categorias WHERE estado = 1");
            if ($resultado) {
                $respuesta = '';

                foreach ($resultado as $fila) {
                    $foto = (!empty($fila['foto']) && trim($fila['foto']) !== '') ? $fila['foto'] : 'img/principal.jpg';

                    $nombre_js = addslashes($fila['nombre']);
                    $desc_js = addslashes($fila['descripcion']);
                    $foto_js = addslashes($fila['foto']);
                    $id = $fila['idCategoria'];

                    $respuesta .= '
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                            <img src="'.$foto.'" class="card-img-top" style="height:180px; object-fit:cover;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="text-dashboard fw-bold mb-1">'.$fila['nombre'].'</h5>
                                <p class="text-muted small mb-3">'.$fila['descripcion'].'</p>

                                <div class="d-flex justify-content-end gap-2 mt-auto">
                                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="pone('.$id.', \''.$nombre_js.'\', \''.$desc_js.'\', \''.$foto_js.'\', 0)">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="pone('.$id.', \''.$nombre_js.'\', \''.$desc_js.'\', \''.$foto_js.'\', 1)">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
                $r['resultado'] = 'consultar';
                $r['mensaje'] =  $respuesta;
            } else {
                $r['resultado'] = 'consultar';
                $r['mensaje'] =  '';
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] =  $e->getMessage();
        }
        return $r;
    }

    function consultar_id()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $st = $co->prepare("SELECT * FROM categorias WHERE idCategoria = :idCategoria");
            $st->bindParam(':idCategoria', $this->idCategoria);
            $st->execute();
            
            $fila = $st->fetch(PDO::FETCH_ASSOC);
            if ($fila) {
                $r['resultado'] = 'consultar_id';
                $r['mensaje'] = $fila; 
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'Categoría no encontrada';
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] =  $e->getMessage();
        }
        return $r;
    }
}
?>