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
    public function busca_producto($codigo,$cantidad)
    {
      $sql ="SELECT producto_id,codigo,nombre_producto,(?) as cantidad, precio_compra, existencia, stock FROM producto WHERE codigo = ?";
      $data = array("ii", "".$cantidad,"".$codigo);
      $fields = array("id"=>"","codigo"=>"","nombre_producto"=>"","cantidad"=>"","precio_compra"=>"","existencia"=>"","stock"=>"");
      $results = MySQLiLayer::ejecutar($sql, $data, $fields);
      return $results;
     }
     public function sendCorreo()
     {
       $sql_correo ="SELECT correo,pass FROM correo WHERE correo_id > ? ORDER BY correo_id DESC LIMIT 0,1";
       $data_correo = array("i", "0");
       $fields_correo = array('correo'=>'','pass'=>'');
       $correo = MySQLiLayer::ejecutar($sql_correo, $data_correo, $fields_correo);
       return $correo;
     }

}

?>
