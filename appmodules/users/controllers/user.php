<?php
/**
* Controlador para el ABM de Usuarios y el SessionHandler
*
* @package    EuropioEngine
* @subpackage core.SessionHandler
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     Eugenia Bahit <ebahit@member.fsf.org>
* @link       http://www.europio.org
*/

import('appmodules.users.models.user');
import('appmodules.users.views.user');
import('appmodules.tienda.models.producto');
import('appmodules.tienda.views.producto');
import('appmodules.tienda.models.compra');
import('appmodules.tienda.views.compra');
import('appmodules.tienda.models.venta');
import('appmodules.tienda.views.venta');

class UserController extends Controller {

    # ==========================================================================
    #                    Recursos básicos (estándar)
    # ==========================================================================

    public function agregar() {
        @SessionHandler()->check_state(1);
        $this->view->show_form();
    }


    public function editar($id=0) {
        @SessionHandler()->check_state(1);
        $this->model->user_id = $id;
        $this->model->get();
        $user = $this->model->name;
        $level = $this->model->level;
        $this->view->show_form(False, False, $user, $level, '', 'Editar', $id);
    }

    public function guardar() {
        @SessionHandler()->check_state(1);
        $badpwd = False;
        $id = (isset($_POST['id'])) ? $_POST['id'] : 0;
        $tit = ($id > 0) ? 'Editar' : 'Agregar';
        $this->model->user_id = $id;

        $user_exists = $this->__validar_user($id);
        $this->__set_level();
        list($pwd, $badpwd) = $this->__validar_pwd($id);

        if(!$user_exists and !$badpwd) {
            $this->model->save($pwd);
            HTTPHelper::go("/users/user/listar");
        } else {
            $this->view->show_form($user_exists, $badpwd, $this->model->name,
                $this->model->level, $_POST['pwd'], $tit, $id);
        }
    }

    public function listar() {
        @SessionHandler()->check_state(1);
        $collection = CollectorObject::get('User');
        $list = $collection->collection;
        $this->view->listar($list);
    }

    public function eliminar($id=0) {
        @SessionHandler()->check_state(1);
        $this->model->user_id = $id;
        $this->model->destroy();
        HTTPHelper::go("/users/user/listar");
    }


    # ==========================================================================
    #                    Funciones de control
    # ==========================================================================
    public function correo()
    {
      @SessionHandler()->check_state(1);
      $sql_correo ="SELECT correo,pass FROM correo WHERE correo_id > ? ORDER BY correo_id DESC LIMIT 0,1";
      $data_correo = array("i", "0");
      $fields_correo = array('correo'=>'','pass'=>'');

      $correo = MySQLiLayer::ejecutar($sql_correo, $data_correo, $fields_correo);
      if (empty($correo)) {
        $this->view->correo("Agregar");
      }
      else {
      $this->correo_editar();
      }
    }
    public function correo_guardar()
    {
      @SessionHandler()->check_state(1);
      $correo = isset($_POST['correo']) ? $_POST['correo'] : 0;
      $pass = isset($_POST['pass']) ? $_POST['pass'] : 0;

      $u = htmlentities(strip_tags($correo));
      $p = base64_encode(htmlentities(strip_tags($pass)));

      $sql = "INSERT INTO correo(correo, pass) VALUES (?,?)";
      $data = array("ss", "".$u,"".$p);
      MySQLiLayer::ejecutar($sql, $data);
      HTTPHelper::go("/users/user/control");

    }
    public function correo_editar()
    {
      $sql_correo ="SELECT correo,pass FROM correo WHERE correo_id > ? ORDER BY correo_id DESC LIMIT 0,1";
      $data_correo = array("i", "0");
      $fields_correo = array('correo'=>'','pass'=>'');

      $correo = MySQLiLayer::ejecutar($sql_correo, $data_correo, $fields_correo);
      $this->view->correo("Editar",$correo);
    }

    public function sistema(){
      @SessionHandler()->check_state(1);
      $fecha = date('d-m-Y');
      $hora = date('H:i:s');
      $sistema = array("fecha"=>"$fecha","hora"=>"$hora");

      $sql_correo ="SELECT correo FROM correo WHERE correo_id > ?";
      $data_correo = array("i", "0");
      $fields_correo = array('correo'=>'');

      $correo = MySQLiLayer::ejecutar($sql_correo, $data_correo, $fields_correo);
        if (empty($correo)) {
          Funciones::redireccion('Necesita registrar un correo electronico', '/users/user/correo');
        }
      return $this->view->sistema($sistema,$correo);
    }
    public function control() {
        @SessionHandler()->check_state(1);
        //NUMERO FALTANTES DE PRODUNTO
        /**
         * principal,
         * herramientas,
         * graficas[producto,compras,ventas],
         * busquedas[producto,compras,ventas,empleado],
         * reportes,
         * sistema
         */
         $apertado = (isset($_GET['lu'])) ? $_GET['lu'] : "principal" ;
         $recurso =  (isset($_GET['re'])) ? $_GET['re'] : "" ;
        //  apartado obtener contenido de apartado
        $contenido = $this->contenido_control($apertado,$recurso);
        // print $contenido;
        $this->view->contenido_control_get($contenido);

    }
    public function contenido_control($apartado,$recurso)
    {
      $productoView = new productoView();
      $productomodel = new producto();
      $compraView = new compraView();
      $compramodel = new compra();
      $ventaView = new ventaView();
      $ventamodel = new venta();

      switch ($apartado) {
        case 'principal':
          return $this->principal();
          break;
        case 'herramientas':
          return $productoView->herramientas();
          break;
        case 'graficas':
            return $this->view->graficas();
          break;
        case 'busqueda':
          if ($recurso == "producto") {
            $results = $productomodel->categoria_get();
            $results_m = $productomodel->marca_get();
          if (empty($results)) {
              Funciones::redireccion('No existen categorias para sus productos todavia', '/tienda/categoria/agregar');
              return false;
            }
            if (empty($results_m)) {
              Funciones::redireccion('No existen marcas para sus productos todavia', '/tienda/marca/agregar');
              return false;
            }
             $conten =  $productoView->buscar(1,$results,$results_m);
             return $this->view->busquedas($conten,$recurso);
          }elseif ($recurso == "compras") {
            $proveedor = $compramodel->select_proveedor();
            $conten = $compraView->busqueda(1,$proveedor);
            return $this->view->busquedas($conten,$recurso);

          }elseif ($recurso == "ventas") {
            # code...
            $results = $ventamodel->empleado_get();
            $conten = $ventaView->busqueda($results);
            return $this->view->busquedas($conten,$recurso);

          }else {
            return $this->view->busquedas("","cero");
          }
          break;
        case 'reportes':
          return $this->view->reportes();
          break;
        case 'sistema':
          $data = $this->sistema();
          return $data;
          break;
        default:
          HTTPHelper::error_response();
          break;
      }
    }
    public function principal($value='')
    {
      $sqlCF ="SELECT count(producto_id) AS cantidad FROM producto WHERE existencia <= stock AND producto_id > ?";
      $dataCF = array("i", "0");
      $fieldsCF = array('faltante'=>''  );
      $contar_faltantes = MySQLiLayer::ejecutar($sqlCF, $dataCF, $fieldsCF);
      // NUMERO DE PRODUCTOS
      $sqlCP ="SELECT count(producto_id) AS cantidad FROM producto WHERE producto_id > ?";
      $dataCP = array("i", "0");
      $fieldsCP = array('c_producto'=>''  );
      $contar_producto = MySQLiLayer::ejecutar($sqlCP, $dataCP, $fieldsCP);

      // dias de la semana
      $year=date('Y');
      $month=date('m');
      $day=date('d');
      # Obtenemos el numero de la semana
      $semana=date("W",mktime(0,0,0,$month,$day,$year));
      # Obtenemos el día de la semana de la fecha dada
      $diaSemana=date("w",mktime(0,0,0,$month,$day,$year));
      # el 0 equivale al domingo...
      if($diaSemana==0)
          $diaSemana=7;
      # A la fecha recibida, le restamos el dia de la semana y obtendremos el lunes
      $primerDia=date("Y-m-d",mktime(0,0,0,$month,$day-$diaSemana+1,$year));
      # A la fecha recibida, le sumamos el dia de la semana menos siete y obtendremos el domingo
      $ultimoDia=date("Y-m-d",mktime(0,0,0,$month,$day+(7-$diaSemana),$year));
      // VENTAS REALIZADAS EN LA SEMANA
      $sqlV ="SELECT count(venta_id) AS cantidad FROM venta WHERE venta_id > ?
            AND fecha_venta BETWEEN '$primerDia' AND '$ultimoDia'";
      $dataV = array("i", "0");
      $fieldsV = array('c_ventas'=>''  );
      $contar_venta = MySQLiLayer::ejecutar($sqlV, $dataV, $fieldsV);

      $sqlTV ="SELECT SUM(total) FROM venta WHERE venta_id > ? AND fecha_venta
                BETWEEN '$primerDia' AND '$ultimoDia'";
      $dataTV = array("i", "0");
      $fieldsTV = array('c_ventas_total'=>''  );
      $total_ventas = MySQLiLayer::ejecutar($sqlTV, $dataTV, $fieldsTV);

      $results_pr = $this->model->producto();
      // VENTA
      $sql_v ="SELECT tv.venta_id, tv.empleado_id, tv.fecha_venta, tv.sub_total, tv.total, tv.paga_con, tv.cambio, CONCAT(te.empleado_id,':', te.nombre,' ',te.apellido_paterno) as nombre
      FROM venta tv, empleado te WHERE venta_id > ? AND tv.empleado_id=te.empleado_id
      ORDER BY venta_id DESC
      LIMIT 0,12";
      $data_v = array("i", "0");
      $fields_v = array("venta_id"=>"","empleado_id"=>"","fecha_venta"=>"","sub_total"=>"","total"=>"","paga_con"=>"","cambio"=>"","nombre_empleado"=>"");
      $r_venta = MySQLiLayer::ejecutar($sql_v, $data_v, $fields_v);
      // echo "<br>Semana: ".$semana." - año: ".$year;
      // echo "<br>Primer día ".$primerDia;
      // echo "<br>Ultimo día ".$ultimoDia;
      return $this->view->control($contar_faltantes,$contar_producto,$contar_venta,$total_ventas,$results_pr,$r_venta);
    }
    public function graficas()
    {
      $this->view->graficas();
    }

    public function busquedas() {
      $this->view->busquedas();
    }

    # ==========================================================================
    #                    Recursos manejados x SessionHandler
    # ==========================================================================

    # /users/user/login (formulario para loguearse)
    public function login() {
        $this->view->show_login();
    }

    # /users/user/check (valida los datos ingresados en el form de login)
    public function check() {
        SessionHandler()->check_user();
        HTTPHelper::go(DEFAULT_VIEW);
    }

    # /users/user/logout (desconexión)
    public function logout() {
        SessionHandler()->destroy_session(True);
    }
    // verifica si el usuario existe en la base de datos
    public function verificar() {

    $user_r = addslashes(htmlspecialchars($_POST['user']));
    $pwd_r = addslashes(htmlspecialchars($_POST['pwd']));
    $user =$user_r;
    $pass = md5(htmlentities(strip_tags($pwd_r)));
    $sql ="SELECT name, pwd FROM user WHERE name = ? AND pwd = ?";
    $data = array("ss","".$user,"".$pass);
    $fields = array('usuario'=>"" ,'pass'=>"" );
    $respuesta=mysqlilayer::ejecutar($sql,$data,$fields);
    if (json_encode($respuesta)!="[]") {
          echo json_encode ("Sesion Iniciada");
    }else {
          echo json_encode (false);
    }
    }

    # ==========================================================================
    #                       PRIVATE FUNCTIONS: Helpers
    # ==========================================================================

    private function __validar_user($id=0) {
        $user_exists = False;
        $user = str_replace(' ', '', SessionHelper::get_user());
        if(strlen($user) < 6) $user_exists = True;
        $this->model->name = $user;

        $collection = CollectorObject::get('User');
        $list = $collection->collection;
        foreach($list as $obj) {
            $mismo_nombre = ($obj->name == $this->model->name);
            $distinta_id = ($obj->user_id != $id);
            if($mismo_nombre && $distinta_id) $user_exists = True;
        }

        return $user_exists;
    }

    private function __set_level() {
        $level = (isset($_POST['otro'])) ? $_POST['otro'] : $_POST['level'];
        $this->model->level = (int)$level;
    }

    private function __validar_pwd($id=0) {
        $badpwd = false;
        $pwd_ = $_POST['pwd'];
        if(strlen($pwd_) < 6) $badpwd = True;
        $pwd = (!$badpwd) ? str_replace(' ', '',
            SessionHelper::get_pwd()) : False;
        if($pwd_ == '' && $id > 0) $badpwd = False;
        return array($pwd, $badpwd);
    }

}

?>
