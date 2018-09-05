<?php

class producto extends StandardObject
{
    public function __construct() {
        $producto_id = 0;
        $categoria_id = 0;
        $nombre_producto = "";
        $marca = 0;
        $talla = "";
        $color = "";
        $modelo = "";
        $precio_compra = 0;
        $precio_venta = 0;
        $existencia = 0;
        $stock =  0;
        $imagen = "";
        $imagen_f = "";
    }
    function categoria_get($id=0)
    {
      $sql_0 ="SELECT categoria_id,nombre_categoria FROM categoria WHERE categoria_id > ?";
      $sql_1 ="SELECT categoria_id,nombre_categoria FROM categoria WHERE categoria_id != ?";
      $sql = ($id == 0) ? $sql_0 : $sql_1;
      $data = array("i", "$id");
      $fields = array("id"=>"","categoria"=>"");
      $results = MySQLiLayer::ejecutar($sql, $data, $fields);
      return $results;
    }
    function marca_get($id=0)
    {
      $sql_0="SELECT marca_id,nombre_marca FROM marca WHERE marca_id > ?";
      $sql_1 ="SELECT marca_id,nombre_marca FROM marca WHERE marca_id != ?";
      $sql = ($id == 0) ? $sql_0 : $sql_1;
      $data = array("i", "0");
      $fields = array("id"=>"","marca"=>"");
      $results = MySQLiLayer::ejecutar($sql, $data, $fields);
      return $results;
    }
    function produco_clien($id=0)
    {
      $sql ="SELECT tp.producto_id, tp.categoria_id, tp.nombre_producto, tp.marca_id, tp.talla, tp.color, tp.modelo, tp.precio_compra, tp.precio_venta, tp.existencia, tp.stock, tp.codigo, tp.imagen, tc.nombre_categoria,tm.nombre_marca
            FROM producto tp, categoria tc, marca tm
            WHERE producto_id = ?
            AND tp.categoria_id = tc.categoria_id
            AND tp.marca_id = tm.marca_id";
      $data = array("i", "$id");
      $fields = array("producto_id"=>"", "categoria_id"=>"", "nombre_producto"=>"", "marca_id"=>"", "talla"=>"", "color"=>"", "modelo"=>"", "precio_compra"=>"", "precio_venta"=>"", "existencia"=>"", "stock"=>"","codigo"=>"", "imagen"=>"", "nombre_categoria"=>"", "nombre_marca"=>"");
      $results = MySQLiLayer::ejecutar($sql, $data, $fields);
      return $results;
    }
    function producto_procedimiento($value='')
    {
      switch ($value) {
        case 'INSERTAR':
          $sql = "INSERT INTO producto (producto_id, categoria_id, nombre_producto, marca_id, talla, color, modelo, precio_compra, precio_venta, existencia, stock, codigo, imagen) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
          $data = array("sssssssssssss", "".$this->producto_id, "".$this->categoria_id, "".$this->nombre_producto, "".$this->marca, "".$this->talla, "".$this->color, "".$this->modelo, "".$this->precio_compra, "".$this->precio_venta, "".$this->existecia, "".$this->stock, "".$this->codigo, "".$this->imagen_f);
          break;
        case 'ACTUALIZAR':
          $sql = "UPDATE  producto set categoria_id=?, nombre_producto=?, marca_id=?, talla=?, color=?, modelo=?, precio_compra=?, precio_venta=?, existencia=?, stock=?, codigo=?, imagen=?
          WHERE producto_id=?";
          $data = array("sssssssssssss", "".$this->categoria_id, "".$this->nombre_producto, "".$this->marca, "".$this->talla, "".$this->color, "".$this->modelo, "".$this->precio_compra, "".$this->precio_venta, "".$this->existecia, "".$this->tock, "".$this->codigo, "".$this->imagen_f, "".$this->producto_id);
          break;
        case 'BORRAR':
          $sql= "DELETE FROM producto WHERE producto_id = ?";
          $data = array("sssssssssssss", "".$this->categoria_id, "".$this->nombre_producto, "".$this->marca, "".$this->talla, "".$this->color, "".$this->modelo, "".$this->precio_compra, "".$this->precio_venta, "".$this->existecia, "".$this->tock, "".$this->codigo, "".$this->imagen_f, "".$this->producto_id);
          break;
      }
      $results = MySQLiLayer::ejecutar($sql, $data);
      return $results;
    }
    function listar()
    {
      $sql ="SELECT tp.producto_id, tp.categoria_id, tp.nombre_producto, tm.nombre_marca, tp.talla, tp.color, tp.modelo, tp.precio_compra, tp.precio_venta, tp.existencia, tp.stock, tp.codigo, tp.imagen, tc.nombre_categoria
              FROM producto tp, categoria tc,marca tm
              WHERE producto_id > ?
              AND tp.categoria_id = tc.categoria_id
              AND tp.marca_id = tm.marca_id
              ORDER BY tp.producto_id DESC";
      $data = array("i", "0");
      $fields = array("producto_id"=>"", "categoria_id"=>"", "nombre_producto"=>"", "marca"=>"", "talla"=>"", "color"=>"", "modelo"=>"", "precio_compra"=>"", "precio_venta"=>"", "existencia"=>"", "stock"=>"", "codigo"=>"", "imagen"=>"", "nombre_categoria"=>"");
      $results = MySQLiLayer::ejecutar($sql, $data, $fields);
      return $results;
    }
     function _faltantes() {
      $sql ="SELECT tp.producto_id, tp.categoria_id, tp.nombre_producto,tm.nombre_marca, tp.talla, tp.color, tp.modelo, tp.precio_compra, tp.precio_venta, tp.existencia, tp.stock, tp.codigo, tp.imagen
              FROM producto tp, marca tm
              WHERE existencia <= stock
              AND producto_id > ?
              AND tp.marca_id = tm.marca_id";
      $data = array("i", "0");
      $fields = array( "producto_id"=>"", "categoria_id"=>"", "nombre_producto"=>"","marca"=>"", "talla"=>"", "color"=>"", "modelo"=>"", "precio_compra"=>"", "precio_venta"=>"", "existencia"=>"", "stock"=>"", "codigo"=>"", "imagen"=>"" );
      $results = MySQLiLayer::ejecutar($sql, $data, $fields);
      return $results;
    }
    function ultimos($cantidad=10)
    {
      $sql ="SELECT tp.producto_id, tp.categoria_id, tp.nombre_producto, tm.nombre_marca, tp.talla, tp.color, tp.modelo, tp.precio_compra, tp.precio_venta, tp.existencia, tp.stock, tp.codigo, tp.imagen, tc.nombre_categoria
              FROM producto tp, categoria tc,marca tm
              WHERE tp.producto_id > ?
              AND tp.categoria_id = tc.categoria_id
              AND tp.marca_id = tm.marca_id
              ORDER BY tp.producto_id DESC
              LIMIT 0,$cantidad";
      $data = array("i", "0");
      $fields = array("producto_id"=>"", "categoria_id"=>"", "nombre_producto"=>"", "marca"=>"", "talla"=>"", "color"=>"", "modelo"=>"", "precio_compra"=>"", "precio_venta"=>"", "existencia"=>"", "stock"=>"", "codigo"=>"", "imagen"=>"", "nombre_categoria"=>"");
      $results = MySQLiLayer::ejecutar($sql, $data, $fields);
      return $results;
    }
}
