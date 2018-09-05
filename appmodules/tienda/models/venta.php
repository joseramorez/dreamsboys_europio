<?php

class venta extends StandardObject {

    public function __construct() {
        $this->venta_id = 0;
        $this->empleado_id =0;
        $this->fecha_venta ='';
        $this->sub_total =0;
        $this->total =0;
        $this->paga_con =0;
        $this->cambio =0;
    }
  function empleado_get()
  {
    $sql ="SELECT empleado_id,nombre FROM empleado WHERE empleado_id > ?";
    $data = array("i", "0");
    $fields = array("clave"=>"","nombre"=>"");
    $results = MySQLiLayer::ejecutar($sql, $data, $fields);
    return $results;
  }
  function listar()
  {
    $sql ="SELECT tv.venta_id, tv.empleado_id, tv.fecha_venta, tv.sub_total, tv.total, tv.paga_con, tv.cambio, CONCAT(te.empleado_id,':', te.nombre,' ',te.apellido_paterno) as nombre FROM venta tv, empleado te WHERE venta_id > ? AND tv.empleado_id=te.empleado_id";
    $data = array("i", "0");
    $fields = array("venta_id"=>"","empleado_id"=>"","fecha_venta"=>"","sub_total"=>"","total"=>"","paga_con"=>"","cambio"=>"","nombre"=>"");
    $results = MySQLiLayer::ejecutar($sql, $data, $fields);
    return $results;
  }
  function venta($id=0)
  {
    $sql ="SELECT tv.empleado_id, tv.fecha_venta, tv.sub_total, tv.total, tv.paga_con, tv.cambio, CONCAT(te.empleado_id,':', te.nombre,' ',te.apellido_paterno) as nombre
    FROM venta tv, empleado te WHERE venta_id = ? AND tv.empleado_id=te.empleado_id";
    $data = array("i", "$id");
    $fields = array("empleado_id"=>"","fecha_venta"=>"","sub_total"=>"","total"=>"","paga_con"=>"","cambio"=>"","nombre_empleado"=>"");
    $results = MySQLiLayer::ejecutar($sql, $data, $fields);
    return $results;
  }
  function venta_detalle($id=0)
  {
    $sql ="SELECT td.venta_detalle_id,td.venta_id, td.producto_id, td.cantidad, td.descuento, td.importe, tp.nombre_producto FROM venta_detalle td, producto tp
            WHERE td.venta_id = ?
            AND td.producto_id = tp.producto_id";
    $data = array("i", "$id");
    $fields = array("venta_detalle_id"=>"","venta_id"=>"","codigo"=>"","cantidad"=>"","descuento"=>"","importe"=>"","nombre_producto"=>"");
    $results = MySQLiLayer::ejecutar($sql, $data, $fields);
    return $results;
 }
 function busqueda_venta_c($n_venta="", $f_venta="", $v_nombre="")
 {
   $sql ="SELECT tv.venta_id, tv.empleado_id, tv.fecha_venta, tv.sub_total, tv.total, tv.paga_con, tv.cambio, CONCAT(te.empleado_id,':', te.nombre,' ',te.apellido_paterno) as nombre
   FROM venta tv, empleado te WHERE tv.venta_id>? AND tv.empleado_id=te.empleado_id ".$n_venta.$f_venta.$v_nombre ;
   $data = array("i", "0");
   $fields = array("venta_id"=>"","empleado_id"=>"","fecha_venta"=>"","sub_total"=>"","total"=>"","paga_con"=>"","cambio"=>"","nombre"=>"");
   $results = MySQLiLayer::ejecutar($sql, $data, $fields);
   return $results;
 }
}

?>
