<?php
import('appmodules.FuncionHelper');

class ventaView {

    public function agregar($vendedor=array(),$fecha =array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/tienda/venta_agregar.html");
            $list_vendedor = "";
            foreach ($vendedor as $key => $value) {
                $list_vendedor .= '<option value="'.$value['clave'].'">'.$value['clave'].":".$value['nombre'].'</option>'."\n";
            }
            $dict = array('VENDEDOR' => $list_vendedor);
            $str = Template($str)->render($fecha);
            $str = Template($str)->render($dict);
        print Template('Agregar venta')->show($str);
    }

    public function editar($obj=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/tienda/venta_editar.html");
        $html = Template($str)->render($obj);
        print Template('Editar venta')->show($html);
    }

    public function listar($coleccion=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/tienda/venta_listar.html");
        $html = Template($str)->render_regex('LISTADO', $coleccion);
        print Template('Listado de venta')->show($html);
    }

    public function detalle($coleccion=array(),$venta=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/tienda/venta_detalle.html");
        $dic = Funciones::render_manual($venta);
        $str = Template($str)->render($dic);
        $str = Template($str)->render_regex('LISTADO', $coleccion);
        print Template('Listado de venta')->show($str);
    }
    public function busqueda($vendedor=array())
    {
      $str_contenido = file_get_contents(STATIC_DIR . "html/tienda/venta_busqueda.html");
      $html = $this->_set_dict('Busqueda de Venta', '',$str_contenido);
      $str = file_get_contents(STATIC_DIR . "html/users/control_contenedor.html");
      $list_vendedor = "";
      foreach ($vendedor as $key => $value) {
          $list_vendedor .= '<option value="'.$value['clave'].'">'.$value['clave'].":".$value['nombre'].'</option>'."\n";
      }
      $dict = array('VENDEDOR' => $list_vendedor);
      $html = Template($str)->render($html);
      return Template($html)->render($dict);
    }
    // ARRAY DE CONTENIDO
    private function _set_dict($titulo,$clic,$contenido) {
      return array('tituloContenido'=>$titulo,
                   'clicContenido'=>$clic,
                   'controlContenido'=>$contenido);
    }
}

?>
