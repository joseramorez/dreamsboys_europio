<?php

class proveedorView {

    public function agregar() {
        $str = file_get_contents(
            STATIC_DIR . "html/tienda/proveedor_agregar.html");
        print Template('Agregar proveedor')->show($str);
    }

    public function editar($obj=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/tienda/proveedor_editar.html");
        $html = Template($str)->render($obj);
        print Template('Editar proveedor')->show($html);
    }

    public function listar($coleccion=array(),$a="") {
        $str = file_get_contents(
            STATIC_DIR . "html/tienda/proveedor_listar.html");
        $html = Template($str)->render_regex('LISTADO', $coleccion);
        $html = Template($html)->render(array("EXT"=>$a));
        print Template('Listado de proveedor')->show($html);
    }
    public function pedido($obj=array(),$tabla="") {
        $str = file_get_contents(
            STATIC_DIR . "html/tienda/proveedor_pedido.html");
        $html = Template($str)->render($obj);
        $html = Template($html)->render(array('PRODUCTO_EXT'=>$tabla));
        print Template('pedido a proveedor')->show($html);
    }
}

?>
