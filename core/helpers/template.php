<?php
/**
* Clase que permite realizar sustituciones estáticas y dinámicas (iterativas)
*
* @package      EuropioEngine
* @subpackage   core.helpers
* @license      http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author       Eugenia Bahit <ebahit@member.fsf.org>
* @link         http://www.europio.org
* @contributor  Jimmy Daniel Barranco
*/

class Template {

    public function __construct($str='') {
        $this->str = $str;
        $this->filename = STATIC_DIR . "html/template.html";
    }

    public function render($dict=array()) {
        settype($dict, 'array');
        $this->set_dict($dict);
        return str_replace(array_keys($this->dict), array_values($this->dict),
            $this->str);
    }
    public function render_safe($dict=array()) {
        if(empty($dict)) $dict = $_GLOBALS['DICT'];
        $new_dict = array();
        foreach($dict as $key=>$value) {
            $identificador = "<!--$key-->";
            if(!$value) {
                $ini = strpos($this->str, $identificador);
                if($ini !== False) {
                    $search = $this->get_substr($key, False);
                    $this->str = str_replace($search, "", $this->str);
                }
                $this->str = str_replace("{{$key}}", "", $this->str);
            } else {
                $new_dict[$key] = $value;
            }
            $this->str = str_replace($identificador, "", $this->str);
        }
        return $this->render($new_dict);
    }
    function get_regex($key, $remove_keys=True) {
        if(USE_PCRE) {
            $actual_recursion_limit = ini_set("pcre.recursion_limit", 10000);
            $regex = "/<!--$key-->(.|\n){1,}<!--$key-->/";
            preg_match($regex, $this->str, $matches);
            $no_keys = str_replace("<!--$key-->", "", $matches[0]);
            ini_set("pcre.recursion_limit", $actual_recursion_limit);
            if(PREG_RECURSION_LIMIT_ERROR === preg_last_error()) {
                return $this->get_substr($key, $remove_keys);
            } else {
                return ($remove_keys) ? $no_keys : $matches[0];
            }

        } else {
            return $this->get_substr($key, $remove_keys);
        }
    }

    function get_substr($key, $remove_keys=True) {
        $needle = "<!--$key-->";
        $first = strpos($this->str, $needle);
        $last = strrpos($this->str, $needle);
        $long = ($last - $first) + strlen($needle);
        $str = substr($this->str, $first, $long);
        $no_keys = str_replace($needle, "", $str);
        return ($remove_keys) ? $no_keys : $str;
    }
    function delete($key) {
        $code = $this->get_substr($key, False);
        return str_replace($code, '', $this->str);
    }
    function render_regex($key='REGEX', $stack=array(), $use_pcre=USE_PCRE) {
        $originalstr = $this->str;
        $func = ($use_pcre) ? "get_regex" : "get_substr";
        $match = $this->$func($key, False);
        $this->str = $this->$func($key);
        $render = '';
        foreach($stack as $dict) $render .= $this->render($dict);
        return str_replace($match, $render, $originalstr);
    }

    function render_substr($key='REGEX', $stack=array()) {
        return $this->render_regex($key, $stack, False);
    }
    // funcion para ver usuario
    function render_navbar() {
        $key = ($_SESSION['user_id'] > 0) ? 'nologin' : 'login';
        $user = (empty($_SESSION['username'])) ? '' : $_SESSION['username'];
        $level = ($_SESSION['level'] == 1) ? " admin" : " usuario";
        $dict = array("username"=>$user, "level"=>$level);
        $this->str = Template($this->delete($key))->render($dict);
    }
    //fin funcion para ver usuario
    // funcion para ver admin
    function render_admin() {
        $level = ($_SESSION['level'] == 1) ? "" : "usuario";
        $this->str = Template($this->delete($level))->render();
    }
    //fin funcion para ver admin

    protected function set_dict($dict=array()) {
        $this->sanitize($dict);
        $keys = array_keys($dict);
        $values = array_values($dict);
        foreach($keys as &$key) {
            $key = "{{$key}}";
        }
        $this->dict = array_combine($keys, $values);
    }

    private function sanitize(&$dict) {
        foreach($dict as $key=>&$value) {
            if(is_array($value) or is_object($value)) {
                $value = print_r($value, True);
                if(strlen($value) > 100) {
                    $value = substr($value, 0, 100) . chr(10) . "(...)";
                    $value = nl2br($value);
                }
            }
        }
    }

    // nuevo show anexado en europio 4
    public function show($contenido='') {
        $title = $this->str;
        $this->str = file_get_contents($this->filename);
        $this->render_navbar();
        $dict = array("TITLE"=>$title,"CONTENIDO"=>$contenido);
        $this->str = Template($this->str)->render($dict);
        return $this->str;
    }
}


# Función agregada para compatibilidad con PHP 5.3
function Template($str='') {
    return new Template($str);
}


# Alias para estilo por procedimientos
function template_render($str='', $dict=array()) {
    return Template($str)->render($dict);
}

function template_render_regex($str='', $key='REGEX', $dict=array()) {
    return Template($str)->render_regex($key, $dict);
}
?>
