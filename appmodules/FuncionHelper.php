<?php

class Funciones{

  public static function redireccion($msg="", $url=""){
       if ($url=="") $url=$_SESSION['uri'];
              $mensaje="<script language='Javascript' type='text/javascript'>"
              ."alertify.alert('".$msg."', function(){ location.href = '".$url."'; });"
              ."</script>";
      print Template('')->show($mensaje);
  }
  public static function redireccion_close($msg="",$url=""){
       if ($url=="") $url=$_SESSION['uri'];
              $mensaje="<script language='Javascript' type='text/javascript'>"
              ."alertify.alert('".$msg."', function(){ window.close(); });"
              ."</script>";
      print Template('')->show($mensaje);
      exit();
  }
  public static function confirmar($msj="",$si="",$no="") {
    $confirmar = "<script language='Javascript' type='text/javascript'>".
                  "alertify.confirm('".$msj."',
                function(){
                  location.href='".$si."';
                },
                function(){
                  location.href='".$no."';
                });"
                ."</script>";
                print Template('')->show($confirmar);
  }

  public static function fecha($date="") {
    if ($date !="00-00-0000" && !empty($date)) {
      $date = DateTime::createFromFormat('d-m-Y', $date );
      $date = $date->format('Y-m-d');
    }else {
      $date = false;
    }
    return $date;
  }

  public static function hora($hora="") {
    $conver = strtotime($hora);
    $time_in_24_hour_format = date("H:i",$conver);
    return $time_in_24_hour_format;
  }

  public static function regresa($msg=""){
    $mensaje=" <script language='Javascript' type='text/javascript'> ".
           " alert('".$msg."'); ".
             "  window.history.back(); ".
           "</script>";
    echo $mensaje;
  }

  public static function str_repeat_extended($input='', $multiplier=0, $separator='') {
  if ($multiplier==0) {
    $cadena = "";
  }else {
    $cadena =" ";
    $cadena .= str_repeat ($input.$separator,$multiplier-1);
    $cadena .= $input;
  }
  return "".$cadena;
 }

 public static function render_manual($acomodo=array()) {
    $dicionario=array();
    foreach ($acomodo as $fila){
      foreach ($fila as $k=>$v){
        $dicionario[$k]=$v;
      }
    }
    return $dicionario;
  }


}
?>
