<?php
/**
* Clase que provee de métodos para respuestas HTTP
*
* @package    EuropioEngine
* @subpackage core.helpers
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/
class HTTPHelper {

    # Genera respuesta de error 404
    public static function error_response() {
        header("HTTP/1.1 404 Not Found");
        print file_get_contents(STATIC_DIR ."html/error/page_404.html");
        exit();
    }

    public static function exit_by_forbiden() {
        header("HTTP/1.1 403 Permisos insuficientes");
        print file_get_contents(STATIC_DIR ."html/error/page_403.html");
        exit();
    }

    public static function go($uri='') {
        exit(header("Location: $uri"));
    }

}

?>
