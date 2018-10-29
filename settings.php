<?php
/**
* Constantes de configuración personalizada.
*
* Este archivo debe renombrarse a settings.php (o ser copiado como tal)
* Al renombrarlo/copiarlo, modificar el valor de todas las constantes.
*
* @package    EuropioEngine
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/

session_start();

# Estado de Deploy
const PRODUCTION = False;  # True cuando esté en producción

# Constantes de acceso a base de datos
const DB_HOST = "localhost";
const DB_USER = "root";
const DB_PASS = "";
const DB_NAME = "dreamsboys";

#cosntates asignadas para dreamsboys
const LENJUAGE = "/static/json/Spanish.json";
# Rutas absolutas
const APP_DIR = "C:/xampp/htdocs/dreamsboys_europio/";
const STATIC_DIR = "C:/xampp/htdocs/dreamsboys_europio/static/";
#False para utilizar privilegios escalados
const SESSION_STRICT_LEVEL = False;
# Directorio Web (ruta después del dominio. / para directorio raíz)
const WEB_DIR = "/";
# Ruta de la vista por defecto
const DEFAULT_VIEW = "/tienda/welcome/inicio";

# (opcional) directorio privado con permisos de escritura
# solo por si en el futuro lo necesita algún módulo
# no es utilizado por EuropioEngine
const WRITABLE_DIR = "/path/to/writable/dir/";


# Configuraciones para las sesiones
const SESSION_LIFE_TIME = 1800;  # milisegundos


# APIRestFull: Recursos públicos permitidos
$allowed_resources = array(
);

# Configuración específica para PHP
const USE_PCRE = False;  # False para prevenir el bug #52818 de PHP en la función
                        # preg_match. https://bugs.php.net/bug.php?id=52818

# Plugins instalados (dentro de la carpeta common/plugins) y habilitados
# EJEMPLO: $enabled_apps = array('webform', 'collectorviewer');
$enabled_apps = array();

#zona horaria
date_default_timezone_set('America/Mexico_City');


# ------------------------------------------------------------------------------
# --------------------------- *Fin Configuraciones* ----------------------------
# ------------------------------------------------------------------------------

# Configuración de directivas de php.ini
ini_set('include_path', APP_DIR);
if(!PRODUCTION) {
    ini_set('error_reporting', E_ALL | E_NOTICE | E_STRICT);
    ini_set('display_errors', '1');
    ini_set('track_errors', 'On');
} else {
    ini_set('display_errors', '0');
}


# Importación rápida
function import($str='') {
    $file = str_replace('.', '/', $str);
    if(!file_exists(APP_DIR . "$file.php")) exit(
        "FATAL ERROR: No module named $str");
    require_once "$file.php";

}
?>
