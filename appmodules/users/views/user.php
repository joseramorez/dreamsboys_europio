<?php
/**
* Vistas del ABM de Usuarios y SessionHandler
*
* @package    EuropioEngine
* @subpackage core.SessionHandler
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/
import('appmodules.FuncionHelper');


class UserView
{
    public function show_form($user_exists=false, $badpwd=false, $user='',
                              $level=1, $pwd='', $tit='Agregar', $id=0)
    {
        $dict = $this->__set_dict($user, $pwd, $tit, $id);
        $this->__set_styledict($user_exists, $dict);
        $this->__set_stylepdict($badpwd, $dict);
        $this->__set_optleveldict($level, $dict);

        $str = file_get_contents(
            STATIC_DIR . "html/users/user_form.html");
        $html = Template($str)->render($dict);
        print Template('Agregar Usuario')->show($html);
    }

    public function listar($coleccion=array())
    {
        $str = file_get_contents(
            STATIC_DIR . "html/users/user_listar.html");
        foreach ($coleccion as &$obj) {
            $obj->admin = ($obj->level == 1) ? "true" : "false";
        }
        $html = Template($str)->render_regex('LISTADO', $coleccion);
        print Template("Usuarios")->show($html);
    }

    public function show_login()
    {
        $tmpl = file_get_contents(STATIC_DIR . "html/login.html");
        $dict = array("WEB_DIR" => WEB_DIR, "DEFAULT_VIEW"=>DEFAULT_VIEW);
        print Template($tmpl)->render($dict);

    }
    # ==========================================================================
    #                       FUNCIONES PARA CONTROL
    # ==========================================================================
    public function correo($titulo="",$correo=array())
    {
      if (!empty($correo)) {
        foreach ($correo as $key=>&$value) {
          $value['pass'] = base64_decode($value['pass']);
        }
      }
        $dict_inicial = array("WEB_DIR" => WEB_DIR, "DEFAULT_VIEW"=>DEFAULT_VIEW,"correo"=>"","pass"=>"");
        $tmpl = file_get_contents(STATIC_DIR . "html/users/correo.html");
        $dict = empty($correo) ? $dict_inicial : Funciones::render_manual($correo);
        $html = Template($tmpl)->render($dict);
        print Template('Correo '.$titulo)->show($html);

    }
    public function contenido_control_get($contenido)
    {
      $str = file_get_contents(STATIC_DIR . "html/users/control.html");
      $html =  Template($str)->render(array('CONTENIDO_CONTROL' => $contenido ));
      print Template('')->show($html);

    }
    public function control($faltantes=array(),$producto=array(),$venta=array(),$ganancias=array(),$producto_ultimos=array(), $ventas_ultimas=array()) {
        $str = file_get_contents(STATIC_DIR . "html/users/principal.html");
        // faltantes
        $faltantesDic = Funciones::render_manual($faltantes);
        // contar produtos
        $ProductosDic = Funciones::render_manual($producto);
        // contar ventas
        $ventaDic = Funciones::render_manual($venta);
        // ganancias
        $ganaciasDic = Funciones::render_manual($ganancias);
        // agrupamiento de array
        $dicionario = array_merge($faltantesDic,$ProductosDic,$ventaDic,$ganaciasDic);
        $html =  Template($str)->render($dicionario);
        $html =  Template($html)->render_regex('PRODUCTOS',$producto_ultimos);
        $html =  Template($html)->render_regex('VENTAS',$ventas_ultimas);
        return $html;
        // print Template("Control")->show($html);

    }
    public function graficas()
    {
      $str = file_get_contents(STATIC_DIR . "html/users/graficas.html");
      return $str;
    }

    public function sistema($contenido=array(),$correo=array()) {
        $str = file_get_contents(STATIC_DIR . "html/users/sistema.html");
        $c = Funciones::render_manual($correo);
        $html = Template($str)->render($c);
        $html = Template($html)->render($contenido);
        return $html;
    }
    public function busquedas($conten,$recurso)
    {
      $str_contenido = file_get_contents(STATIC_DIR . "html/users/busquedas.html");
      $html = $this->_set_dict('Busquedas avanzadas', '',$str_contenido);
      $str = file_get_contents(STATIC_DIR . "html/users/control_contenedor.html");
      $html = Template($str)->render($html);
      $html =  Template($html)->render(array("$recurso" => "selected" ));
      return Template($html)->render(array('CONTENIDO_BUSCAR' => $conten ));

    }
    // ARRAY DE CONTENIDO
    private function _set_dict($titulo,$clic,$contenido) {
      return array('tituloContenido'=>$titulo,
                   'clicContenido'=>$clic,
                   'controlContenido'=>$contenido);
    }
    public function reportes()
    {
      $str_contenido = file_get_contents(STATIC_DIR . "html/users/reportes_control.html");
      $html = $this->_set_dict('REPORTES', '',$str_contenido);
      $str = file_get_contents(STATIC_DIR . "html/users/control_contenedor.html");
      return Template($str)->render($html);
    }
    # ==========================================================================
    #                       PRIVATE FUNCTIONS: Helpers
    # ==========================================================================

    private function __set_msgs()
    {
        $msgu = "El nombre de usuario ya existe o NO es un nombre de";
        $msgu .= " usuario válido (mínimo 6 caracteres; espacios en blanco no";
        $msgu .= " computan.)" . chr(10);
        $msgp = "Contraseña incorrecta. Mínimo 6 caracteres." . chr(10);
        return array($msgu, $msgp);
    }

    private function __set_styledict($user_exists, &$dict)
    {
        list($msgu, $msgp) = $this->__set_msgs();
        unset($msgp);
        if (!$user_exists) {
            $dict['style'] = '';
            $dict['error'] = '';
        } else {
            $dict['style'] = ' error';
            $dict['error'] = nl2br($msgu);
        }
    }

    private function __set_stylepdict($badpwd, &$dict)
    {
        list($msgu, $msgp) = $this->__set_msgs();
        unset($msgu);
        if (!$badpwd) {
            $dict['stylep'] = '';
            $dict['errorp'] = '';
        } else {
            $dict['stylep'] = ' error';
            $dict['errorp'] = nl2br($msgp);
        }
    }

    private function __set_optleveldict($level, &$dict)
    {
        if ($level > 0 and $level < 6) {
            $dict["opt$level"] = ' selected';
        } else {
            $dict["opt0"] = ' selected';
            $dict['level'] = $level;
            $dict["disabled"] = '';
        }
    }

    private function __set_dict($user, $pwd, $tit, $id)
    {
        return array('opt1'=>'', 'opt2'=>'', 'opt3'=>'', 'opt4'=>'', 'opt5'=>'',
            'opt0'=>'', 'user'=>$user, 'level'=>'', 'pwd'=>$pwd,
            'disabled'=>'disabled', 'titulo'=>$tit, 'user_id'=>$id);
    }
}
