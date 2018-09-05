<?php

import('appmodules.tienda.models.compra');
import('appmodules.tienda.views.compra');


class compraController extends Controller {

    public function agregar($producto_id=0) {
        @SessionHandler()->check_state(1);
        $tabla = "";
        $results = $this->model->select_proveedor();
        $r = $this->model->select_producto();
        if (empty($results)) {
          Funciones::redireccion('Tiene que agregar un proveedor primero', '/tienda/proveedor/agregar');
          return false;
        }
        if (empty($r)) {
          Funciones::redireccion('Tiene que agregar un producto primero', '/tienda/producto/agregar');
          return false;
        }

        if ($producto_id>0) {
          $producto = $this->model->producto($producto_id);
          $tabla = $tabla."<tr>";
          $tabla .= "<td><button type='button' class='glyphicon glyphicon-remove borrar' value='' /></td>";
          $tabla .= "<td style='display:none;'><input class='form-control centrate' readonly  name='id[]' value='".$producto[0]["producto_id"]. "'></td>";
          $tabla .= "<td><input class='form-control centrate' readonly type='text' name='codigo[]' value='".$producto[0]["codigo"]. "'></td>";
          $tabla .= "<td><input class='form-control centrate' readonly type='text' name='nombre_producto[]' value='".$producto[0]["nombre_producto"]. "'></td>";
          $tabla .= "<td><input class='form-control centrate'  type='numeric' name='cantidad[]' value='".$producto[0]["cantidad"]. "'></td>";
          $tabla .= "<td><input class='form-control centrate'  type='text' name='precio_compra[]' value='".$producto[0]["precio_compra"]. "'></td>";
          $tabla .= "<td><input class='form-control centrate'  type='text' name='precio_venta[]' value='".$producto[0]["precio_venta"]. "'></td>";
          $this->view->agregar($results,$r,$tabla);
        } else {
          $this->view->agregar($results,$r);
        }
    }

    public function editar($id=0) {
      @SessionHandler()->check_state(1);
        //optener datos
        $results = $this->model->obtener($id);
        $diferente = $results;
        foreach ($diferente as $key) {
          $this->proveedor_id = $key["proveedor_id"];
          $this->producto_id = $key["producto_id"];
        }
        //proveedor
        $results_p = $this->model->select_proveedor($this->proveedor_id);
        //producto
        $r = $this->model->select_producto($this->producto_id);
        // mandar a vista
        $this->view->editar($results,$results_p,$r);

    }

    public function guardar() {
      @SessionHandler()->check_state(1);
      // datos en compra
        $id = (isset($_POST['compra_id'])) ?  $_POST['compra_id'] : 0;
        $fecha_compra =(isset($_POST['fecha_compra'])) ? Funciones::fecha($_POST['fecha_compra']) : "null";
        $proveedor_id =(isset($_POST['proveedor_id'])) ? $_POST['proveedor_id'] : "null";
        $this->model->compra_id = $id;
        $this->model->proveedor_id = $proveedor_id;
        $this->model->fecha_compra = $fecha_compra;
        $this->model->save();
        // datos compra_detalle
        $compra_id_pcompra_detalle = $this->model->compra_id;
        $p_id = isset($_POST['id']) ? $_POST['id'] : 0;
        $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 0;
        $precio_compra = isset($_POST['precio_compra']) ? $_POST['precio_compra'] : 0;
        $precio_venta = isset($_POST['precio_venta']) ? $_POST['precio_venta'] : 0;
        $No_total= count($p_id);

        $sql = "INSERT INTO compra_detalle( compra_id, producto_id, cantidad, precio_compra, precio_venta, importe) VALUES (?,?,?,?,?,?)";
        $data = array();
        for($i=0; $i<$No_total; $i++){
          ob_start();
          $r=MySQLiLayer::ejecutar($sql, array("issssi","".$compra_id_pcompra_detalle,"".$p_id[$i],"".$cantidad[$i],"".$precio_compra[$i],"".$precio_venta[$i],"".($cantidad[$i]*$precio_compra[$i])));
          ob_end_clean();
          }
        HTTPHelper::go("/tienda/compra/listar");
    }

    public function listar() {
      @SessionHandler()->check_state(1);
        $results = $this->model->listar();
        if (empty($results)) {
          Funciones::redireccion('La tabla esta vacia', '/tienda/compra/agregar');
        }else {
        $this->view->listar($results);
      }
    }

    public function eliminar($id=0) {
      @SessionHandler()->check_state(1);
        $sql="DELETE FROM compra_detalle WHERE compra_id = ?";
        $sql_c="DELETE FROM compra WHERE compra_id = ?";
        $data = array("i", "$id");
        MySQLiLayer::ejecutar($sql, $data);
        MySQLiLayer::ejecutar($sql_c, $data);
        HTTPHelper::go("/tienda/compra/listar");
    }
    public function detalle($id=0)
    {
      $sql ="SELECT tc.compra_id,tpr.razon_social,DATE_FORMAT(tc.fecha_compra, '%d/%m/%Y')
              FROM compra tc, proveedor tpr
              WHERE tc.compra_id = ?
              AND tc.proveedor_id = tpr.proveedor_id";
      $data = array("i", "$id");
      $fields = array("compra_id"=>"", "proveedor"=>"",
                    "fecha_compra"=>"");
      $compra = MySQLiLayer::ejecutar($sql, $data, $fields);

      $sql_d = "SELECT tcd.compra_detalle_id,tp.nombre_producto,tp.codigo,tcd.cantidad,tcd.precio_compra,tcd.precio_venta,tcd.importe
              FROM compra_detalle tcd,producto tp
              WHERE tcd.compra_id = ?
              AND tcd.producto_id = tp.producto_id";
      $data_d = array("i", "$id");
      $fields_d = array("compra_detalle_id"=>"","nombre_producto"=>"","codigo"=>"","cantidad"=>"","precio_compra"=>"","precio_venta"=>"","importe"=>"");
      $compra_d = MySQLiLayer::ejecutar($sql_d, $data_d, $fields_d);

      $this->view->detalle($compra,$compra_d);
    }
    #===========================================================================
    #                         PETICIONES DE CONTROL
    #===========================================================================
    public function busqueda($control=0) {
      @SessionHandler()->check_state(1);
      $proveedor = $this->model->select_proveedor();
      $this->view->busqueda($control,$proveedor);
    }
    public function buscar_compra()
    {
      @SessionHandler()->check_state(1);
      $proveedor_id = (isset($_POST['proveedor_id'])) ? $_POST['proveedor_id'] : 0;
      $fecha_inicio = (isset($_POST['fecha_inicio'])) ? Funciones::fecha($_POST['fecha_inicio']) : "";
      $fecha_fianal = (isset($_POST['fecha_fianal'])) ? Funciones::fecha($_POST['fecha_fianal']) : "";
      $resultado = $this->model->buscar($proveedor_id,$fecha_inicio,$fecha_fianal);
      if (empty($resultado)) {
        Funciones::redireccion_close("No hay resultados de la busqueda");
        return false;
      }
      $this->view->listar($resultado);
    }

}

?>
