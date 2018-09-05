<?php
import('appmodules.FuncionHelper');

class productoView {

    public function agregar($categoria=array(),$marca=array(),$base=array(),$ultimo=array()) {
        $str = file_get_contents(STATIC_DIR . "html/tienda/producto_agregar.html");
        $html= Template($str)->render_regex('CATEGORIA',$categoria);
        $html= Template($html)->render_regex('MARCA',$marca);
        print Template('Agregar Producto')->show($html);
        if (!empty($ultimo)) {
          $nombre_producto = $ultimo[0]['nombre_producto'];
          $color = $ultimo[0]['color'];
          $modelo = $ultimo[0]['modelo'];
          $precio_compra = $ultimo[0]['precio_compra'];
          $precio_venta = $ultimo[0]['precio_venta'];
          $stock = $ultimo[0]['stock'];
          $categoria_id = $ultimo[0]['categoria_id'];
          $marca_id = $ultimo[0]['marca_id'];
          $codigo = $ultimo[0]['codigo'];
          echo "<script type='text/javascript'>
              $('#nombre_producto').val('$nombre_producto');
              $('#color').val('$color');
              $('#modelo').val('$modelo');
              $('#precio_compra').val('$precio_compra');
              $('#precio_venta').val('$precio_venta');
              $('#stock').val('$stock');
              $('#categoria_id option[value=\"$categoria_id\"]').attr(\"selected\", \"selected\");
              $('#marca_id option[value=\"$marca_id\"]').attr(\"selected\", \"selected\");
              alertify.alert('Se guardo con éxito el producto anterior: <br> Nombre: $nombre_producto <br> y Codigo: $codigo <br> <B>continuar guardando...</B> <br><a href=\"/tienda/producto/listar\">IR AL LISTADO</a>');
              </script>
              ";
        }
    }

    public function agregar_progresivo($categoria_list=array(),$marca_list=array(),
                                       $categoria_id, $nombre_producto, $marca, $color, $modelo, $precio_compra, $precio_venta, $stock) {
        $str = file_get_contents(STATIC_DIR . "html/tienda/producto_agregar.html");
        $categoria_opt="";
        foreach ($categoria_list as $value) {
          $categoria_opt .= '<option value="'.$value['id'].'">'.$value['categoria'].'</option>'."\n";
        }
        $marca_opt="";
        foreach ($marca_list as $value) {
          $marca_opt .= '<option value="'.$value['id'].'">'.$value['marca'].'</option>'."\n";
        }
        $dict = array('OPCIONES' => $categoria_opt,'MARCA' => $marca_opt);
        $html= Template($str)->render($dict);
        print Template('Agregar Producto')->show($html);
        echo "<script type='text/javascript'>
            $('#nombre_producto').val($nombre_producto);
            $('#color').val($color);
            $('#modelo').val($modelo);
            $('#precio_compra').val($precio_compra);
            $('#precio_venta').val($precio_venta);
            $('#stock').val($stock);
            $('#categoria_id option[value=\"$categoria_id\"]').attr(\"selected\", \"selected\");
            $('#marca_id option[value=\"$marca\"]').attr(\"selected\", \"selected\");
            </script>";
    }

    public function editar($obj=array(),$categoria_restante=array(),$imagen_estado = array(),$marca_restantes=array()) {
        $str = file_get_contents(STATIC_DIR . "html/tienda/producto_editar.html");
        $dict = Funciones::render_manual($obj);
        $categoria_opt="";
        foreach ($categoria_restante as $value) {
          $categoria_opt .= '<option value="'.$value['id'].'">'.$value['categoria'].'</option>'."\n";
        }
        $marca_opt="";
        foreach ($marca_restantes as $value) {
          $marca_opt .= '<option value="'.$value['id'].'">'.$value['marca'].'</option>'."\n";
        }
        $dict_categoria = array('OPCIONES' => $categoria_opt, 'MARCA'=> $marca_opt);
        $str = Template($str)->render($dict);
        $str = Template($str)->render($dict_categoria);
        $str = Template($str)->render($imagen_estado);
        print Template('Editar producto')->show($str);
    }

    public function listar($coleccion=array(),$titl_ext="") {
        $str = file_get_contents(
            STATIC_DIR . "html/tienda/producto_listar.html");
        $html = Template($str)->render_regex('LISTADO', $coleccion);
        print Template('Listado de producto '.$titl_ext)->show($html);
    }

    public function stock($coleccion=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/tienda/producto_stock.html");
        $html = Template($str)->render_regex('LISTADO', $coleccion);
        print Template('Listado de producto faltante')->show($html);
    }
    // =========================================================================
    //                         CONTROLES
    // =========================================================================
    public function alertas($coleccion=array()) {
      $str_contenido = file_get_contents(STATIC_DIR . "html/tienda/producto_stock.html");
      $contenido = Template($str_contenido)->render_regex('LISTADO', $coleccion);
      $html = $this->_set_dict('Producto faltante', 'alertas();',$contenido);
      $str = file_get_contents(STATIC_DIR . "html/users/control_contenedor.html");
      print Template($str)->render($html);
    }
    public function herramientas() {
      $str_contenido = file_get_contents(STATIC_DIR . "html/tienda/producto_generar_codigo.html");
      $html = $this->_set_dict('Generador de codigo', '',$str_contenido);
      $str = file_get_contents(STATIC_DIR . "html/users/control_contenedor.html");
      return Template($str)->render($html);
    }
    public function buscar($control,$categoria=array(),$marca=array())
    {
      $str_contenido = file_get_contents(STATIC_DIR . "html/tienda/producto_busqueda.html");
      $categoria_opt="";
      foreach ($categoria as $value) {
        $categoria_opt .= '<option value="'.$value['id'].'">'.$value['categoria'].'</option>'."\n";
      }
      $marca_opt="";
      foreach ($marca as $value) {
        $marca_opt .= '<option value="'.$value['id'].'">'.$value['marca'].'</option>'."\n";
      }
      $dict = array('OPCIONES' => $categoria_opt,'MARCA' => $marca_opt);
      $html = $this->_set_dict('Busqueda de Producto', '',$str_contenido);
      $str = file_get_contents(STATIC_DIR . "html/users/control_contenedor.html");

      if ($control==0) {
        $h = Template($str)->render($html);
        $h = Template($h)->render($dict);
        print Template('Busqueda de Producto')->show($h);
      }else {
         $h = Template($str)->render($html);
         return Template($h)->render($dict);
      }
    }
    // ARRAY DE CONTENIDO
    private function _set_dict($titulo,$clic,$contenido) {
      return array('tituloContenido'=>$titulo,
                   'clicContenido'=>$clic,
                   'controlContenido'=>$contenido);
    }

}

?>
