<?php
/**
* Modelo para el ABM de Usuarios
*
* @package    EuropioEngine
* @subpackage core.SessionHandler
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/


class User extends StandardObject {

    public function __construct() {
        $this->user_id = 0;
        $this->name = '';
        $this->level = 0;
    }

    function save($pwd=False) {
        if($pwd !== False) {
            $this->pwd = $pwd;
            parent::save();
            unset($this->pwd);
        } else {
            parent::save();
        }
    }
    function producto($value='')
    {
      $sql_pr ="SELECT tp.producto_id, tp.categoria_id, tp.nombre_producto, tm.nombre_marca, tp.talla, tp.color, tp.modelo, tp.precio_compra, tp.precio_venta, tp.existencia, tp.stock, tp.codigo, tp.imagen, tc.nombre_categoria
      FROM producto tp, categoria tc, marca tm
      WHERE producto_id > ?
      AND tp.categoria_id = tc.categoria_id
      AND tp.marca_id = tm.marca_id
      ORDER BY tp.producto_id DESC
      LIMIT 0,6";
      $data_pr = array("i", "0");
      $fields_pr = array("producto_id"=>"", "categoria_id"=>"", "nombre_producto"=>"", "marca"=>"", "talla"=>"", "color"=>"", "modelo"=>"", "precio_compra"=>"", "precio_venta"=>"", "existencia"=>"", "stock"=>"", "codigo"=>"", "imagen"=>"", "nombre_categoria"=>"");
      $results_pr = MySQLiLayer::ejecutar($sql_pr, $data_pr, $fields_pr);
      return $results_pr;
    }

}

?>
