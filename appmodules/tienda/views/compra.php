<?php

class compraView {

    public function agregar($obj=array(),$producto=array(),$producto_compra="") {
        $str = file_get_contents(STATIC_DIR . "html/tienda/compra_agregar.html");
        $provedor_opt = "";
        foreach ($obj as $value) {
          $value = (object) $value;
          $provedor_opt .= '<option value="'.$value->id.'">'.$value->razon_social.'</option>';
        }
        $producto_opt = "";
        foreach ($producto as $v) {
          $v = (object) $v;
          $producto_opt .= '<option value="'.$v->id_p.'">'.$v->producto.'</option>';
        }
        $dict = array('OPCIONES' => $provedor_opt,'PRODUCTO' => $producto_opt,"PRODUCTO_EXT"=>$producto_compra);
        $html = Template($str)->render($dict);
        print Template('Agregar compra')->show($html);
    }

    public function editar($obj=array(),$proveedor=array(),$producto=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/tienda/compra_editar.html");
            // compra
            $dict=array();
            foreach ($obj as $fila){
              foreach ($fila as $k=>$v){
                $dict[$k]=$v;
              }
            }
            // proveedor
            $provedor_opt = "";
            foreach ($proveedor as $value) {
              $value = (object) $value;
              $provedor_opt .= '<option value="'.$value->id.'">'.$value->razon_social.'</option>';
            }
            // producto
            $producto_opt = "";
            foreach ($producto as $v) {
              $v = (object) $v;
              $producto_opt .= '<option value="'.$v->id_p.'">'.$v->producto.'</option>';
            }

            $dic = array('OPCIONES' => $provedor_opt);
            $d = array('PRODUCTO' => $producto_opt);

        $str = Template($str)->render($dic);
        $str = Template($str)->render($d);
        $str = Template($str)->render($dict);
        print Template('Editar compra')->show($str);
    }

    public function listar($coleccion=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/tienda/compra_listar.html");
        $html = Template($str)->render_regex('LISTADO', $coleccion);
        print Template('Listado de compras')->show($html);
    }
    public function detalle($compra=array(),$compra_d=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/tienda/compra_detalle.html");
            $c = Funciones::render_manual($compra);
        $html = Template($str)->render($c);
        $html = Template($html)->render_regex('LISTADO',$compra_d);
        print Template('Detalle de compras')->show($html);
    }
    #===========================================================================
    #                         PETICIONES DE CONTROL
    #===========================================================================

    public function busqueda($control,$proveedor=array())
    {
      $proveedor_opt="";
      foreach ($proveedor as $value) {
        $proveedor_opt .= '<option value="'.$value['id'].'">'.$value['razon_social'].'</option>'.chr(10);
      }
      $opt = array('PROVEEDOR' => $proveedor_opt);
      $str_contenido = file_get_contents(STATIC_DIR . "html/tienda/compra_busqueda.html");
      $html = $this->_set_dict('Busqueda de Compra', '',$str_contenido);
      $str = file_get_contents(STATIC_DIR . "html/users/control_contenedor.html");
      $str = Template($str)->render($html);
      $str = Template($str)->render($opt);
      if($control==1){return $str;}else {
        print Template('Busqueda de compara')->show($str);
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
