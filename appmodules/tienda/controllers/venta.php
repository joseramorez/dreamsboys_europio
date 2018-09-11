<?php

import('appmodules.tienda.models.venta');
import('appmodules.tienda.views.venta');
import('common.libs.escpos-php-development.autoload');

class ventaController extends Controller {

    public function agregar() {
      @SessionHandler()->check_state();
      $fecha = array('fecha' => "".date("d-m-Y") );
      $results = $this->model->empleado_get();
      if (empty($results)) {
        Funciones::redireccion('Tiene que agregar un vendedor primero', '/empleados/empleado/agregar');
        return false;
      }
      $this->view->agregar($results,$fecha);
    }

    public function editar($id=0) {
      @SessionHandler()->check_state(1);
        $this->model->venta_id = $id;
        $this->model->get();
        $this->view->editar($this->model);
    }

    public function guardar() {
      // @SessionHandler()->check_state(1);
        // datos de la tabla -> array
      $codigo = isset($_POST['codigo']) ? $_POST['codigo'] : 0;
      $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 0;
      $precio_venta = isset($_POST['precio_venta']) ? $_POST['precio_venta'] : 0;
      $descuento = isset($_POST['descuento']) ? $_POST['descuento'] : 0;
      $importe = isset($_POST['importe']) ? $_POST['importe'] : 0;
      $producto_id = isset($_POST['producto_id']) ? $_POST['producto_id'] : 0;
      $No_total = count($codigo);

        // datos de cobro
        $id = (isset($_POST['venta_id'])) ? $_POST['venta_id'] : 0;
        $clave_vendedor = isset($_POST['clave_vendedor']) ? $_POST['clave_vendedor'] : 0;
        $fecha = isset($_POST['fecha_venta']) ? Funciones::fecha($_POST['fecha_venta']) : "null";
        $total = isset($_POST['total']) ? $_POST['total'] : 0;
        $sub_total = $total; //-> pues todos los precos tienen iva verificar si va a quereer separarlo
        $cantidad_recibida = isset($_POST['cantidad_recibida']) ? $_POST['cantidad_recibida'] : 0;
        $cambio = isset($_POST['cambio']) ? $_POST['cambio'] : 0;
        // insercion de datos de la tabla venta
        $this->model->venta_id = $id;
        $this->model->empleado_id = $clave_vendedor;
        $this->model->fecha_venta = $fecha;
        $this->model->sub_total = $sub_total;
        $this->model->total = $total;
        $this->model->paga_con = $cantidad_recibida;
        $this->model->cambio = $cambio;

                          $this->model->save();
        #descomentar     obtiene la id de la venta para la tabla de venta_detalle
                          $venta_id_pventa = $this->model->venta_id;

        $sql = "INSERT INTO venta_detalle(venta_id, producto_id, cantidad, descuento, importe) VALUES (?,?,?,?,?)";
        $data = array();
        for($i=0; $i<$No_total; $i++){
          ob_start();
          $r=MySQLiLayer::ejecutar($sql, array("issss","".$venta_id_pventa,"".$producto_id[$i],"".$cantidad[$i],"".$descuento[$i],"".$importe[$i]));
          ob_end_clean();
          }
          Funciones::confirmar("NUEVA VENTA","/tienda/venta/agregar","/tienda/venta/listar");
    }

    public function listar() {
      @SessionHandler()->check_state(1);
        $results = $this->model->listar();
        if (empty($results)) {
          Funciones::redireccion('La tabla esta vacia', '/tienda/venta/agregar');
        }else {
          $this->view->listar($results);
        }
    }
    public function detalle($id=0) {
      @SessionHandler()->check_state(1);
      // VENTA
      $r_venta = $this->model->venta($id);
      // DETALLE DE LA VENTA
      $results = $this->model->venta_detalle($id);
      if (empty($results)) {
        Funciones::redireccion('no se encuentra el detalle de la venta '. '$id', '/tienda/venta/listar');
      }else {
        $this->view->detalle($results,$r_venta);
      }
    }
    public function eliminar($id=0) {
      @SessionHandler()->check_state(1);
        $this->model->venta_id = $id;
        $this->model->destroy();
        HTTPHelper::go("/tienda/venta/listar");
    }

    public function busca_producto(){
      @SessionHandler()->check_state(1);
      $codigo = (isset($_POST['codigo'])) ? $_POST['codigo'] : 0;
      $descuento = (isset($_POST['descuento'])) ? $_POST['descuento'] : 0;
      $cantidad = (isset($_POST['cantidad'])) ? $_POST['cantidad'] : 0;
      $this->model->busca_producto($cantidad,$descuento,$descuento,$codigo);
      echo json_encode($results);
    }
    #===========================================================================
    #                         PETICIONES DE CONTROL
    #===========================================================================
    public function busqueda() {
      $results = $this->model->empleado_get();
      $this->view->busqueda($results);
    }
    public function exe_busqueda()
    {
      $n_venta = (isset($_POST['n_venta']) && is_int((int)$_POST['n_venta']) && !empty($_POST['n_venta'])) ? " and tv.venta_id=".$_POST['n_venta'] : "";
      $f_venta = (isset($_POST['f_venta']) && !empty($_POST['f_venta'])) ? " and tv.fecha_venta = "."'".@Funciones::fecha($_POST['f_venta'])."'" :  "";
      $v_nombre = (isset($_POST['v_nombre']) && is_int((int)$_POST['v_nombre']) && !empty($_POST['v_nombre'])) ? " and tv.empleado_id = ".$_POST['v_nombre'] : "";
      if ( empty($n_venta) && empty($f_venta) && empty($v_nombre)) {
        Funciones::redireccion_close('Debes de ingresar almenos un parametro de busqueda');
        return false;
      }
      $results = $this->model->busqueda_venta_c($n_venta, $f_venta, $v_nombre);
      if (empty($results)) {
        Funciones::redireccion_close("NINGUN RESULTADO");
        return false;
      }
      $this->view->listar($results);

    }
    public function conect_impesora()
    {
      try {
        $conn = new WindowsPrintConnector("");
      } catch (\Exception $e) {

      }

    }
    public function info($value='')
    {
      # code...
      phpinfo();
    }

}

?>
