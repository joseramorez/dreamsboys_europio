<?php

class proveedor extends StandardObject {

    public function __construct() {
        $this->proveedor_id = 0;
        $this->ruc = "";
        $this->razon_social = "";
        $this->direccion = "";
        $this->descripcion_rubro = "";
        $this->email = "";
        $this->telefono = 0;
    }
    function producto($id=0)
    {
      $sql ="SELECT producto_id,codigo,nombre_producto,(?) as cantidad, precio_compra, existencia, stock
              FROM producto WHERE producto_id = ?";
      $data = array("ii", "1","$id");
      $fields = array("id"=>"","codigo"=>"","nombre_producto"=>"","cantidad"=>"","precio_compra"=>"","existencia"=>"","stock"=>"");
      $results = MySQLiLayer::ejecutar($sql, $data, $fields);
      return $results;
    }

}

?>
