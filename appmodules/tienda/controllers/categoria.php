<?php

import('appmodules.tienda.models.categoria');
import('appmodules.tienda.views.categoria');



class categoriaController extends Controller {
// =============================================================================
//                            CONTROLES BASICOS
// =============================================================================
    public function agregar() {
      @SessionHandler()->check_state(1);
        $this->view->agregar();
    }

    public function editar($id=0) {
      @SessionHandler()->check_state(1);
        $this->model->categoria_id = $id;
        $this->model->get();
        $this->view->editar($this->model);
    }

    public function guardar() {
      @SessionHandler()->check_state(1);
        $id = (isset($_POST['id'])) ? $_POST['id'] : 0;
        if (empty($_POST['nombre_categoria'])) {
          Funciones::redireccion('El campo categoria está vacio', '/tienda/categoria/agregar');
        } else {
          $this->model->categoria_id = $id;
          $this->model->nombre_categoria = isset($_POST['nombre_categoria']) ? $_POST['nombre_categoria'] : '';
          $this->model->descripcion = isset($_POST['desc_categoria']) ? $_POST['desc_categoria'] : 'Sin asignar';
          $this->model->save();
          HTTPHelper::go("/tienda/categoria/listar");
        }

    }

    public function listar() {
      @SessionHandler()->check_state(1);
        $collection = CollectorObject::get('categoria');
        $list = $collection->collection;
        if (empty($list)) {
          Funciones::redireccion('La tabla esta vacia', '/tienda/categoria/agregar');
        }else {
          $this->view->listar($list);
        }
    }

    public function eliminar($id=0) {
      @SessionHandler()->check_state(1);
        $this->model->categoria_id = $id;
        $this->model->destroy();
        HTTPHelper::go("/tienda/categoria/listar");
    }

    // =============================================================================
    //                            FUNCIONES ADICIONALES
    // =============================================================================

    public function VerificarCategoria(){
      // @SessionHandler()->check_state(1);
      $existe = False;
      $categoria = urldecode(isset($_POST['categoria']) ? $_POST['categoria'] : "");
      $id = urldecode(isset($_POST['id']) ? $_POST['id'] : "");
      $collection = CollectorObject::get('categoria');
      $list = $collection->collection;
      foreach($list as $obj) {
      $mismo_nombre = (strtolower($obj->nombre_categoria) == strtolower($categoria));
      $distinta_id = ($obj->categoria_id != $id);
      if($mismo_nombre && $distinta_id) $existe = True;
          }
      echo json_encode ($existe);

   }
}

?>
