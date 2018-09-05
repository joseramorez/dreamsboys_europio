<?php

class categoriaView {

    public function agregar() {
        $str = file_get_contents(
            STATIC_DIR . "html/tienda/categoria_agregar.html");
        print Template('Agregar Categoría')->show($str);
    }

    public function editar($obj=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/tienda/categoria_editar.html");
        $html = Template($str)->render($obj);
        print Template('Editar Categoría')->show($html);
    }

    public function listar($coleccion=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/tienda/categoria_listar.html");
        $html = Template($str)->render_regex('LISTADO', $coleccion);
        print Template('Listado de Categoría')->show($html);
    }
}

?>
