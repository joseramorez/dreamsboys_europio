<?php

class compra extends StandardObject {

    public function __construct() {
        $this->compra_id = 0;
        $this->proveedor_id = 0;
        $this->fecha_compra= "";
    }

  function select_proveedor($id=0)
  {

    $sql_1 ="SELECT proveedor_id, razon_social FROM proveedor WHERE proveedor_id > ?";
    $sql_2 ="SELECT proveedor_id, razon_social FROM proveedor WHERE proveedor_id != ?";
    $sql = ($id > 0) ? $slq_2 : $sql_1;
    $data = array("i", "$id");
    $fields = array("id"=>"","razon_social"=>"");
    $results = MySQLiLayer::ejecutar($sql, $data, $fields);
    return $results;
  }
  function select_producto($id=0)
  {
    $sql_1 = "SELECT producto_id, nombre_producto FROM producto WHERE producto_id > ?";
    $sql_2 = "SELECT producto_id, nombre_producto FROM producto WHERE producto_id > ?";
    $s = ($id > 0) ? $slq_2 : $sql_1;
    $d = array("i", "0");
    $f = array("id_p"=>"","producto"=>"");
    $r = MySQLiLayer::ejecutar($s, $d, $f);
    return $r;
  }
  function obtener($id)
  {
    $sql ="SELECT tc.compra_id, tc.proveedor_id, tc.producto_id,
                  tc.cantidad_comprada, DATE_FORMAT(tc.fecha_compra, '%d/%m/%Y'),
                  tc.precio_compra, tc.precio_venta,
                  tpr.razon_social,
                  tp.nombre_producto
            FROM compra tc, proveedor tpr, producto tp
            WHERE tc.compra_id = ?
            AND tc.proveedor_id = tpr.proveedor_id
            AND tc.producto_id = tp.producto_id";
    $data = array("i", "$id");
    $fields = array("compra_id"=>"", "proveedor_id"=>"", "producto_id"=>"",
                  "cantidad_comprada"=>"", "fecha_compra"=>"",
                  "precio_compra"=>"", "precio_venta"=>"",
                  "razon_social"=>"",
                  "nombre_producto"=>"");
    $results = MySQLiLayer::ejecutar($sql, $data, $fields);
    return $results;
  }
  function listar()
  {
    $sql ="SELECT tc.compra_id,tpr.razon_social,DATE_FORMAT(tc.fecha_compra, '%d/%m/%Y')
            FROM compra tc, proveedor tpr
            WHERE tc.compra_id > ?
            AND tc.proveedor_id = tpr.proveedor_id";
    $data = array("i", "0");
    $fields = array("compra_id"=>"", "proveedor"=>"",
                  "fecha_compra"=>"");
    $results = MySQLiLayer::ejecutar($sql, $data, $fields);
    return $results;
  }
  function buscar($proveedor_id=0,$fecha_inicio="",$fecha_fianal="")
  {
    $sql_1="SELECT tc.compra_id,tpr.razon_social,DATE_FORMAT(tc.fecha_compra, '%d/%m/%Y')
            FROM compra tc, proveedor tpr
            WHERE tc.compra_id > ?
            AND tc.proveedor_id = $proveedor_id
            AND tc.proveedor_id = tpr.proveedor_id";
    $sql_2 ="SELECT tc.compra_id,tpr.razon_social,DATE_FORMAT(tc.fecha_compra, '%d/%m/%Y')
                    FROM compra tc, proveedor tpr
                    WHERE tc.compra_id > ?
                    AND tc.proveedor_id = $proveedor_id
                    AND tc.fecha_compra between '$fecha_inicio' and '$fecha_fianal'
                    AND tc.proveedor_id = tpr.proveedor_id";
    $sql_3 ="SELECT tc.compra_id,tpr.razon_social,DATE_FORMAT(tc.fecha_compra, '%d/%m/%Y')
                    FROM compra tc, proveedor tpr
                    WHERE tc.compra_id > ?
                    AND tc.proveedor_id = tpr.proveedor_id
                    AND tc.fecha_compra between '$fecha_inicio' and '$fecha_fianal'";
  if (empty($proveedor_id) && empty($fecha_inicio) && empty($fecha_fianal)) {
    Funciones::redireccion('Debe ingresar almenos un parametro de busqueda','/tienda/compra/busqueda');
    return false;
  }
  elseif (!empty($proveedor_id) && !empty($fecha_inicio) && !empty($fecha_fianal)) {
    // echo "todos";
      $sql=$sql_2;
  }
  elseif ((empty($fecha_inicio) || empty($fecha_fianal)) && $proveedor_id != 0) {

    // echo "fechas vacias";
    $sql = $sql_1;

  }
  elseif (empty($proveedor_id) && (!empty($fecha_inicio) && !empty($fecha_fianal))) {
    // echo "solo fechas";
    $sql = $sql_3;

  }else {
    Funciones::redireccion('parametros incorrectos','/tienda/compra/busqueda');
    return false;

  }
    $data = array("i", "0");
    $fields = array("compra_id"=>"", "proveedor"=>"",  "fecha_compra"=>"");
    $results = MySQLiLayer::ejecutar($sql, $data, $fields);
    return $results;
  }
  function producto($id=0)
  {
    $sql ="SELECT tp.producto_id, tp.categoria_id, tp.nombre_producto, tm.nombre_marca,
                  tp.talla, tp.color, tp.modelo, tp.precio_compra, tp.precio_venta,
                  tp.existencia, tp.stock, tp.codigo, tp.imagen,
                  tc.nombre_categoria,(1) as cantidad
            FROM producto tp, categoria tc,marca tm
            WHERE tp.producto_id = ?
            AND tp.categoria_id = tc.categoria_id
            AND tp.marca_id = tm.marca_id
            LIMIT 0,10";
    $data = array("i", "$id");
    $fields = array("producto_id"=>"", "categoria_id"=>"", "nombre_producto"=>"", "marca"=>"", "talla"=>"", "color"=>"", "modelo"=>"", "precio_compra"=>"", "precio_venta"=>"", "existencia"=>"", "stock"=>"", "codigo"=>"", "imagen"=>"", "nombre_categoria"=>"","cantidad"=>"");
    $results = MySQLiLayer::ejecutar($sql, $data, $fields);
    return $results;
  }
}

?>
