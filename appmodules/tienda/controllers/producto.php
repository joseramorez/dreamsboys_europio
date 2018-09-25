<?php

import('appmodules.tienda.models.producto');
import('appmodules.tienda.views.producto');
import('appmodules.barcode');
import('appmodules.php-barcode');


class productoController extends Controller {


    public function agregar($base='') {
      @SessionHandler()->check_state(1);
      $results = $this->model->categoria_get();
      $results_m =  $this->model->marca_get();
      if (empty($results)) {
        Funciones::redireccion('No existen categorias para sus productos todavia', '/tienda/categoria/agregar');
        return false;
      }
      if (empty($results_m)) {
        Funciones::redireccion('No existen marcas para sus productos todavia', '/tienda/marca/agregar');
        return false;
      }
      if (!empty($base)) {
        $data_pre = explode(",",$base);
        $_base = $data_pre[0];
        $_ultimo = $data_pre[1];
        $_ultimo = $this->model->produco_clien($_ultimo);
        $_base = array('BASE' => 'checked' );
      }else {
        $_base = array('BASE' => 'checked' );
        $_ultimo = array();
      }
      $this->view->agregar($results,$results_m,$_base,$_ultimo);
    }

    public function editar($id=0) {
      @SessionHandler()->check_state(1);
        if ($id != 0) {
          $categoria_id = 0;
          $results = $this->model->produco_clien($id);
            $c = $results;
            foreach ($c as $key) {
              $this->categoria_id = $key["categoria_id"];
              $this->marca_id = $key["marca_id"];

              if (empty($key["imagen"])) {
               $imagen_estado=array('estado_previa' => 'style="display:none;"', 'estado_subir'=> '' );
             }else {
               $imagen_estado=array('estado_previa' => '', 'estado_subir'=> 'style="display:none;"' );
             }
            }

          $categoria_restante = $this->model->categoria_get($this->categoria_id);
          $results_m = $this->model->marca_get($this->marca_id);
          $this->view->editar($results,$categoria_restante,$imagen_estado,$results_m);
        }
    }

    public function guardar() {
      @SessionHandler()->check_state(1);
      $continuo = false;
      if ($continuo) {
        Funciones::redireccion('Si tenias progreso lo perdiste por ACTUALIZAR', '/tienda/producto/agregar');
        return false;
      }
          $id = isset($_POST['id']) ? $_POST['id'] : 0 ;
          $this->model->producto_id = $id;
          $categoria_id = $this->model->categoria_id = $_POST['categoria_id'];
          $nombre_producto = $this->model->nombre_producto =  $_POST['nombre_producto'];
          $marca = $this->model->marca = $_POST['marca'];
          $talla = $this->model->talla = $_POST['talla'];
          $color = $this->model->color = $_POST['color'];
          $modelo = $this->model->modelo = $_POST['modelo'];
          $precio_compra = $this->model->precio_compra =  $_POST['precio_compra'];
          $precio_venta = $this->model->precio_venta = $_POST['precio_venta'];
          $existecia = $this->model->existecia = $_POST['existencia'];
          $stock = $this->model->stock =  $_POST['stock'];
          $codigo = $this->model->codigo =  $_POST['codigo'];
          $imagen = isset($_FILES['imagen']['name']) ? $_FILES['imagen']['name'] : '' ;
          $imagen_previa = isset($_POST['imgprevia']) ? $_POST['imgprevia'] : '';
          $this->model->imagen_f = empty($imagen) ? $imagen_previa : $imagen;
          $status_c =$_POST['status_save'];
          $existe = $this->__verifica_imagen($id,$imagen);
          if ($existe) {
            Funciones::regresa("El nombre de la imagen ya existe");
            return false;
          }

          if ($id > 0) {
          $result_e = $this->model->producto_procedimiento('ACTUALIZAR');
          }else {
          $result = $this->model->producto_procedimiento('INSERTAR');
          }

          if (is_uploaded_file($_FILES['imagen']['tmp_name'])){
            move_uploaded_file($_FILES['imagen']['tmp_name'],
            STATIC_DIR."dreamsboys/". $_FILES['imagen']['name']);
            }
          if (isset($status_c) && $status_c == "True") {
            HTTPHelper::go("/tienda/producto/agregar/anterior,".$result);
            return false;
          }

        HTTPHelper::go("/tienda/producto/listar");
    }

    public function listar() {
      @SessionHandler()->check_state(1);
        $results = $this->model->listar();
        if (empty($results)) {
          Funciones::redireccion('La tabla esta vacia', '/tienda/producto/agregar');
        }else {
          $this->view->listar($results);
        }

    }

    public function eliminar($id=0) {
      @SessionHandler()->check_state(1);
      if ($id != 0) {
          $this->model->producto_id = $id;
          $results = $this->model->eliminar();
          $c = $results;
          foreach ($c as $key) {
            $this->nombre_imagen = $key["imagen"];
          }
        }
        if (!empty($this->nombre_imagen)) {
          unlink(STATIC_DIR."dreamsboys/".$this->nombre_imagen);
        }
        $this->model->producto_id = $id;
        $this->model->destroy();
        HTTPHelper::go("/tienda/producto/listar");
    }
    public function faltantes()
    {
      @SessionHandler()->check_state(1);
      $results = $this->model->_faltantes();
      if (empty($results)) {
        Funciones::redireccion('La tabla esta vacia','/tienda/producto/listar');
      }else {
      $this->view->stock($results);
      }
    }



  // =========================================================================
  //                    PETICIONES DE CONTROL
  // =========================================================================
  public function reportes() {
    $faltantes = $this->model->_faltantes();
    $this->view->alertas($faltantes);
  }

  public function herramientas() {
    $this->view->herramientas();
  }
  public function buscar($control=0) {

    $results = $this->model->categoria_get();
    $results_m = $this->model->marca_get();
  if (empty($results)) {
      Funciones::redireccion('No existen categorias para sus productos todavia', '/tienda/categoria/agregar');
      return false;
    }
    if (empty($results_m)) {
      Funciones::redireccion('No existen marcas para sus productos todavia', '/tienda/marca/agregar');
      return false;
    }
    $this->view->buscar($control,$results,$results_m);
  }
  public function buscar_result()
  {
    // parametros
    $categoria_id = empty(isset($_POST['categoria_id']) && is_numeric($_POST['categoria_id'])) ? "" : " AND tp.categoria_id = " .$_POST['categoria_id'];
    $nombre_producto = empty(isset($_POST['nombre_producto']) && strlen($_POST['nombre_producto'])>1) ? "" : " AND tp.nombre_producto LIKE"."'%".$_POST['nombre_producto']."%'";
    $marca = empty((isset($_POST['marca']) && is_numeric($_POST['marca']) && $_POST['marca'] > 0)) ? "" : " AND tp.marca_id = ".$_POST['marca'];
    $talla = empty(isset($_POST['talla']) && strlen($_POST['talla'])>1) ? "" : " AND tp.talla LIKE "."'%".$_POST['talla']."%'";
    $color = empty(isset($_POST['color']) && strlen($_POST['color'])>1) ? "" : " AND tp.color LIKE "."'%".$_POST['color']."%'";
    $modelo = empty(isset($_POST['modelo']) && strlen($_POST['modelo'])>1) ? "" : " AND tp.modelo LIKE "."'%".$_POST['modelo']."%'";
    $precio_compra = empty((isset($_POST['precio_compra']) && is_numeric($_POST['precio_compra']))) ? "" : " AND tp.precio_compra = ".$_POST['precio_compra'];
    $precio_venta = empty((isset($_POST['precio_venta']) && is_numeric($_POST['precio_venta']))) ? "" : " AND tp.precio_venta = ".$_POST['precio_venta'];
    $existencia = empty((isset($_POST['existencia']) && is_numeric($_POST['existencia']))) ? "" : " AND tp.existencia = ".$_POST['existencia'];
    $stock = empty((isset($_POST['stock']) && is_numeric($_POST['stock']))) ? "" : " AND tp.stock = ".$_POST['stock'];
    $codigo = empty((isset($_POST['codigo']) && is_numeric($_POST['codigo']))) ? "" : " AND tp.codigo = ".$_POST['codigo'];
    // resolver
    if ( empty($categoria_id) && empty($nombre_producto) && empty($marca) && empty($talla) && empty($color) && empty($modelo) && empty($precio_compra) && empty($precio_venta) && empty($existencia) && empty($stock) && empty($codigo)) {
      Funciones::redireccion_close('Debes de ingresar almenos un parametro de busqueda');
      return false;
    }
    // consulta base
    $this->model->buscar_result($categoria_id,$nombre_producto,$marca,$talla,$color,$modelo,$precio_compra,$precio_venta,$existencia,$stock,$codigo);
    if (empty($results)) {
      Funciones::redireccion_close('Busqueda sin Resultados, Realice una nueva busqueda', '/tienda/producto/buscar');
    }else {
      $this->view->listar($results);
    }
  }
  public function ultimos()
  {
  $results = $this->model->ultimos();
    if (empty($results)) {
      Funciones::redireccion('La tabla esta vacia', '/tienda/producto/agregar');
    }else {
      $this->view->listar($results," ordenados descendentemente");
    }

  }
  // =========================================================================
  //                    PETICIONES
  // =========================================================================
  public function _barcode($code_number='') {
    header("Content-Description: File Transfer");
    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment");
    $img = new barCodeGenrator($code_number,0,'hello.gif', 190, 130, true);
  }
    public function eliminarimg(){
      $id = isset($_POST['id']) ? $_POST['id'] : false;
      $eliminada = false;
      if ($id != 0) {
        $categoria_id = 0;
        $results = $this->model->eliminarimg($id);
          $c = $results;
          foreach ($c as $key) {
            $this->nombre_imagen = $key["imagen"];
          }
        }
        if (!empty($this->nombre_imagen)) {
          unlink(STATIC_DIR."dreamsboys/".$this->nombre_imagen);
          $eliminada = true;
          $imagen = "";
          $this->model->update_img($imagen,$id);
        }
        echo json_encode ($eliminada);
    }

    public function Verificarexistencia(){
      $codigo = urldecode(isset($_POST['codigo']) ? $_POST['codigo'] : "");
      $id = urldecode(isset($_POST['id']) ? $_POST['id'] : "");
      $existe = False;
      $results = $this->model->Verificarexistencia();
      if (empty($codigo)) {
        echo json_encode ($existe);
      }else {
        foreach($results as $obj) {
          $mismo_nombre = ($obj['codigo'] == $codigo);
          $distinta_id = ($obj['producto_id'] != $id);
          if($mismo_nombre && $distinta_id) $existe = True;
        }
        echo json_encode ($existe);
      }

   }
   private function __verifica_imagen($id=0,$imagen=""){
     $existe = false;
      $results = $this->model->__verifica_imagen($imagen);
     foreach ($results as $value) {
       if(!empty($value[1])) $existe = true;
       return $existe;
     }

   }
   private function __verifica_codigo($id=0,$codigo=""){
     $existe = false;
      $this->model->__verifica_codigo();
     if(!empty($results[1])) $existe = true;
     return $existe;
   }

   public function _autocompletado()
   {
     $nombre_buscar = urldecode(isset($_POST['nombre_buscar'])?$_POST['nombre_buscar']:"no especificado");
    // BUS PARA LA BASE DE DATOS
    $results = $this->model->_autocompletado();
     echo json_encode($results);
   }
   public function _buscar_producto()
   {
     $codigo = urldecode(isset($_POST['codigo'])?$_POST['codigo']:"no especificado");
     $results = $this->model->_buscar_producto($codigo);
     echo json_encode($results);
   }


}

?>
