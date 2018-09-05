<?php
/**
* Clase que provee de una API pública para cada uno de los recursos habilitados
*
* Para habilitar recursos, configure la variable $allowed_resources en el
* archivo settings.php
* <code>
*   $allowed_resources = array(
*       "modelo1" => array('recurso1', 'recurso2'),
*       "modelo2" => array('recurso9'),
*   );
* </code>
*
* @package    EuropioEngine
* @subpackage core.api
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/

import('core.helpers.http');
import('core.mvc_engine.helper');


class ApiRESTFul {

    public static function get($datosapi='', $model='', $resource='') {
        $modelo = MVCEngineHelper::set_name('model', $model);
        $recurso = MVCEngineHelper::set_name('resource', $resource);
        self::check_resource($model, $resource);
        header("Content-Type: text/json; charset=utf-8");
        print json_encode($datosapi);
    }
    
    private static function check_resource($model, $resource) {
        $resources = self::check_model($model);
        if(!in_array($resource, $resources)) HTTPHelper::error_response();
    }

    private static function check_model($model='') {
        global $allowed_resources;

        $m = str_replace('-', '', $model);
        if(array_key_exists($m, $allowed_resources)) {
            return $allowed_resources[$m];
        } else {
            HTTPHelper::error_response();
        }
    }

}

?>
