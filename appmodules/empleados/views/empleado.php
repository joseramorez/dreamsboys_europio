<?php

class empleadoView {

    public function agregar() {
        $str = file_get_contents(
            STATIC_DIR . "html/empleados/empleado_agregar.html");
        print Template('Agregar empleado')->show($str);
    }

    public function editar($obj=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/empleados/empleado_editar.html");
            $dict = array();
            foreach ($obj as $value) {
              foreach ($value as $k => $v) {
                $dict[$k]=$v;
              }
            }
        $html = Template($str)->render($dict);
        print Template('Editar empleado')->show($html);
    }

    public function listar($coleccion=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/empleados/empleado_listar.html");
        $html = Template($str)->render_regex('LISTADO', $coleccion);
        print Template('Listado de empleados')->show($html);
    }
// =========================== PAGO

    public function pago($empleado=array(),$ultimos_pagos = array()) {
      $str = file_get_contents(
          STATIC_DIR . "html/empleados/empleado_pago.html");
          $list_empleado = "";
          foreach ($empleado as $key => $value) {
              $list_empleado .= '<option value="'.$value['empleado_id'].'">'.$value['nombre'].'</option>'."\n";
          }
          $dict = array('EMPLEADO' => $list_empleado);
          $str = Template($str)->render_regex('LISTADO',$ultimos_pagos);
          $str = Template($str)->render($dict);
      print Template('Pago de empleado')->show($str);
    }
    public function pago_listar($coleccion=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/empleados/pago_listar.html");
        $html = Template($str)->render_regex('LISTADO', $coleccion);
        print Template('Listado de pagos a Empleados')->show($html);
    }
    public function pago_buscar_form($empleado=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/empleados/pago_buscar.html");
        $list_empleado = "";
        foreach ($empleado as $key => $value) {
            $list_empleado .= '<option value="'.$value['empleado_id'].'">'.$value['nombre'].'</option>'."\n";
        }
        $dict = array('EMPLEADO' => $list_empleado);
        $html = Template($str)->render($dict);
        print Template('buscar pago del Empleados')->show($html);
    }
// =========================== COMISION

    public function comision($empleados=array()) {
      $str = file_get_contents(
          STATIC_DIR . "html/empleados/comision.html");
          $list_empleado = "";
          foreach ($empleados as $key => $value) {
              $list_empleado .= '<option value="'.$value['id'].'">'.$value['nombre'].'</option>'."\n";
          }
          $dict = array('EMPLEADO' => $list_empleado);
          $html = Template($str)->render($dict);
      print Template('Comisiones')->show($html);
    }
    public function comision_listar($coleccion=array(),$titul="") {
        $str = file_get_contents(
            STATIC_DIR . "html/empleados/comision_listar.html");
            $html = Template($str)->delete('PAGOCOMISION');
            $html = Template($html)->render_regex('LISTADO', $coleccion);
        print Template($titul)->show($html);
    }
    public function comision_listar_posision($coleccion=array(),$titul="",$rango=array()) {
      $str = file_get_contents(
            STATIC_DIR . "html/empleados/comision_listar.html");
            $html = Template($str)->delete('LISTACOMISON');

            $html = Template($html)->render_regex('POSISION1',$coleccion["pocision_1"]);
            $html = Template($html)->render_regex('POSISION2',$coleccion["pocision_2"]);
            $html = Template($html)->render_regex('POSISION3', $coleccion["pocision_3"]);
            $html = Template($html)->render_regex('LISTADO', $coleccion["restantes"]);
            $html = Template($html)->render($rango);
        print Template($titul)->show($html);
    }
// =========================== PRESTAMO
    public function prestamo($empleado=array(),$hoy='') {
      $str = file_get_contents(
          STATIC_DIR . "html/empleados/empleado_prestamo.html");
          $list_empleado = "";
          foreach ($empleado as $key => $value) {
              $list_empleado .= '<option value="'.$value['id'].'">'.$value['nombre'].'</option>'."\n";
          }
          $dict = array('EMPLEADO' => $list_empleado);
          $html = Template($str)->render($dict);
          $html = Template($html)->render(array('hoy' => $hoy));
      print Template('Pestamo a Empleado')->show($html);
    }
    public function prestamo_listar($coleccion=array()) {
        $str = file_get_contents(
            STATIC_DIR . "html/empleados/prestamo_listar.html");
        $html = Template($str)->render_regex('LISTADO', $coleccion);
        print Template('Listado de prestamos a Empleados')->show($html);
    }
}

?>
