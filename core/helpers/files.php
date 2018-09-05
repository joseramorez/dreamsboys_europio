<?php
/**
* Clase que provee de métodos para manejar archivos
*
* @package    EuropioEngine
* @subpackage core.helpers
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/
class FileManager {

    public static function show($file='') {
        if(file_exists($file)) {
            $resource = finfo_open(FILEINFO_MIME_TYPE);
            $type = finfo_file($resource, $file);
            finfo_close($resource);
            header("Content-type: $type");
            readfile($file);
        } else {
            HTTPHelper::error_response();
        }
    }

}

?>
