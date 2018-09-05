<?php

class marcaView { 

    public function agregar() {
        $str = file_get_contents(
            STATIC_DIR . "html/tienda/marca_agregar.html");
        print Template('Agregar marca')->show($str);
    }
    
    public function editar($obj=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/tienda/marca_editar.html");
        $html = Template($str)->render($obj);
        print Template('Editar marca')->show($html);
    }

    public function listar($coleccion=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/tienda/marca_listar.html");
        $html = Template($str)->render_regex('LISTADO', $coleccion);
        print Template('Listado de marca')->show($html);
    }
}

?>
