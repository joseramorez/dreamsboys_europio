<?php

import('appmodules.tienda.models.marca');
import('appmodules.tienda.views.marca');


class marcaController extends Controller {

    public function agregar() {
        $this->view->agregar();
    }

    public function editar($id=0) {
        $this->model->marca_id = $id;
        $this->model->get();
        $this->view->editar($this->model);
    }

    public function guardar() {
      if (empty($_POST['nombre_marca'])) {
        Funciones::redireccion('El campo de marca está vacio', '/tienda/categoria/agregar');
        return false;
      }
      $id = (isset($_POST['id'])) ? $_POST['id'] : 0;
      $nombre = (isset($_POST['nombre_marca'])) ? $_POST['nombre_marca'] : "";
      $descripcion = (isset($_POST['desc_marca'])) ? $_POST['desc_marca'] : "";
        $this->model->marca_id = $id;
        $this->model->nombre_marca = $nombre;
        $this->model->descripcion = $descripcion;
        $this->model->save();
        HTTPHelper::go("/tienda/marca/listar");
    }

    public function listar() {
        $collection = CollectorObject::get('marca');
        $list = $collection->collection;
        $this->view->listar($list);
    }

    public function eliminar($id=0) {
        $this->model->marca_id = $id;
        $this->model->destroy();
        HTTPHelper::go("/tienda/marca/listar");
    }

    public function VerificarMarca() {
      $existe = False;
      $marca = urldecode(isset($_POST['marca']) ? $_POST['marca'] : "");
      $id = urldecode(isset($_POST['id']) ? $_POST['id'] : "");
      $collection = CollectorObject::get('marca');
      $list = $collection->collection;
      foreach($list as $obj) {
      $mismo_nombre = (strtolower($obj->nombre_marca) == strtolower($marca));
      $distinta_id = ($obj->marca_id != $id);
      if($mismo_nombre && $distinta_id) $existe = True;
          }
      echo json_encode ($existe);

    }

}

?>
