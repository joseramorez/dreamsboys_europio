<?php

import('appmodules.empleados.models.empleado');
import('appmodules.empleados.views.empleado');
import('appmodules.FuncionHelper');


class empleadoController extends Controller
{
    public function agregar()
    {
        @SessionHandler()->check_state(1);
        $this->view->agregar();
    }

    public function editar($id=0)
    {
        @SessionHandler()->check_state(1);
        $editar = $this->model->empleado_get($id);
        $this->view->editar($editar);
    }

    public function guardar()
    {
        @SessionHandler()->check_state(1);
        $id = (isset($_POST['id'])) ? $_POST['id'] : 0;
        $nombre = (isset($_POST['nombre'])) ? $_POST['nombre'] : "";
        $apellido_paterno = (isset($_POST['apellido_paterno'])) ? $_POST['apellido_paterno'] : "";
        $apellido_materno = (isset($_POST['apellido_materno'])) ? $_POST['apellido_materno'] : "";
        $edad = (isset($_POST['edad'])) ? $_POST['edad'] : 0;
        $fecha_nacimiento = (isset($_POST['fecha_nacimiento'])) ? Funciones::fecha($_POST['fecha_nacimiento']) : "null";
        $direccion = (isset($_POST['direccion'])) ? $_POST['direccion'] : "";
        $telefono = (isset($_POST['telefono'])) ? $_POST['telefono'] : 0;
        $curp = (isset($_POST['curp'])) ? $_POST['curp'] : "";
        $rfc = (isset($_POST['rfc'])) ? $_POST['rfc'] : "";
        $salario = (isset($_POST['salario'])) ? $_POST['salario'] : 0;
        $this->model->empleado_id = $id;
        $this->model->nombre = $nombre;
        $this->model->apellido_paterno = $apellido_paterno;
        $this->model->apellido_materno = $apellido_materno;
        $this->model->edad = $edad;
        $this->model->fecha_nacimiento = $fecha_nacimiento;
        $this->model->direccion = $direccion;
        $this->model->telefono = $telefono;
        $this->model->curp = $curp;
        $this->model->rfc = $rfc;
        $this->model->salario = $salario;
        $this->model->save();
        HTTPHelper::go("/empleados/empleado/listar");
    }

    public function listar()
    {
        @SessionHandler()->check_state(1);
        $list = $this->model->listar();
        if (empty($list)) {
            Funciones::redireccion('La tabla esta vacia', '/empleados/empleado/agregar');
        } else {
            $this->view->listar($list);
        }
    }

    public function eliminar($id=0)
    {
        $this->model->empleado_id = $id;
        $this->model->destroy();
        HTTPHelper::go("/empleados/empleado/listar");
    }
    // ==================== PAGO ===========================================
    public function pago() {
        @SessionHandler()->check_state(1);
        $list = $this->model->empleado_get();
        // ejecucion de ultimos movimientos
        $r = $this->model->pago_listar("LIMIT 0,10");
        if (empty($list)) {
            Funciones::redireccion('La tabla de empleado esta vacia', '/empleados/empleado/agregar');
        } else {
            $this->view->pago($list,$r);
        }
    }
    public function pago_guardar() {
      @SessionHandler()->check_state(1);

      $clave_empleado = isset($_POST['clave_empleado']) ? $_POST['clave_empleado'] : 0;
      $pago = isset($_POST['pago']) ? $_POST['pago'] : 0;
      $fecha_pago_inicio = isset($_POST['fecha_pago_inicio']) ? Funciones::fecha($_POST['fecha_pago_inicio']) : 0;
      $fecha_pago_final = isset($_POST['fecha_pago_final']) ? Funciones::fecha($_POST['fecha_pago_final']) : 0;
      $prestamo = isset($_POST['prestamo']) ? $_POST['prestamo'] : 0;
      $pago_final = isset($_POST['pago_final']) ? $_POST['pago_final'] : 0;
      $this->model->pago_guardar($clave_empleado,$pago,$fecha_pago_inicio,$fecha_pago_final,$prestamo,$pago_final);
      Funciones::confirmar("Va a realizar otro Nuevo pago","/empleados/empleado/pago","/empleados/empleado/listar");

    }
    public function pago_listar()
    {
      @SessionHandler()->check_state(1);

      // UNICO PARAMETRO ES EL LIMIT
      $r = $this->model->pago_listar("LIMIT 0,50");
      $this->view->pago_listar($r);

    }
    #solicitudes a desde cliente
    public function  salario() {

      $id = isset($_POST['salario']) ? $_POST['salario']  : 0;
      $list = $this->model->salario($id);
      echo json_encode($list);
    }
    // buscar pagos ya realizados al empleado
    #vista del formulario
    public function pago_buscar()
    {
      @SessionHandler()->check_state(1);
      $empleado = $this->model->empleado_get();
      $this->view->pago_buscar_form($empleado);
      // var_dump($_GET);
    }
    public function pagobuscar()
    {
      @SessionHandler()->check_state(1);

      $a =  (isset($_POST['empleado_id'])) ? $_POST['empleado_id'] : 0;
      $b =  (isset($_POST['fecha_inicio'])) ? Funciones::fecha($_POST['fecha_inicio']) : "";
      $c =  (isset($_POST['fecha_final'])) ? Funciones::fecha($_POST['fecha_final']) : "";
      // $sql=0,$fecha_inicio="",$fecha_final="",$empleado_id=0
      /**
       * sql 1: solo empleado
       * sql 2:todo
       * sql 3 : solo fehcas
       */
       $fechas = (!empty($b) && !empty($c));
       $empleado = (!empty($a));
      if (empty($a) && empty($b) && empty($c)) {
        Funciones::redireccion('Debe ingresar almenos un parametro de busqueda','/empleados/empleado/pago_buscar');
        return false;
      }
      elseif ($empleado && $fechas) {
        // echo "todos";
          $result = $this->model->buscarpago(2,$b,$c,$a);
      }
      elseif (($fechas===false) && ($empleado === true)) {
        // echo "fechas vacias";
        $result = $this->model->buscarpago(1,$b,$c,$a);
      }
      elseif (($fechas===true) && ($empleado === false)) {
        // echo "solo fechas";
        $result = $this->model->buscarpago(3,$b,$c,$a);
      }
      $this->view->pago_listar($result);

    }
    // ==================== COMISION ===========================================

    public function comision() {
        @SessionHandler()->check_state(1);
        $lsit = $this->model->comision();
        $this->view->comision($list);
    }
    public function comision_listar() {
      @SessionHandler()->check_state(1);

      $a = isset($_POST['fecha_inicion']) ? Funciones::fecha($_POST['fecha_inicion']) : "";
      $b = isset($_POST['fecha_final']) ? Funciones::fecha($_POST['fecha_final']) : "";
      $estado  = (empty($a) && empty($b)) ? true : false;
      $list = ($estado) ? $this->model->comicion_listar() : $this->model->comision_lista_posicion($a,$b);
      $rango = array('f_i' => $a , 'f_f' => $b );
      if (empty($list)) {
          Funciones::redireccion('La tabla esta vacia', '/empleados/empleado/comision');
          return false;
      }
      if($estado) {
        $titul = "Listado general de ventas de los empleados";
        $this->view->comision_listar($list,$titul);
      }else {
        $a= date_create($a);
        $b= date_create($b);
        $a1 = date_format($a, 'd-m-Y');
        $b1 = date_format($b, 'd-m-Y');
        $titul = "Listado de comision correspondiente del $a1 al $b1 ";
        $this->view->comision_listar_posision($list,$titul,$rango);
      }
    }
    public function comision_guardar() {
      @SessionHandler()->check_state(1);
      $guardado = false;
      $id = isset($_POST['id']) ? $_POST['id'] : 0;
      $comision = isset($_POST['comision']) ? $_POST['comision'] : 0;
      $fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : "";
      $fecha_final = isset($_POST['fecha_final']) ? $_POST['fecha_final'] : "";
      $fechas = array('fecha_inico' => $fecha_inicio,'fecha_final' => $fecha_final);
      $empleado_get = $this->model->comision_empleado($id,$comision,$fecha_inicio,$fecha_final);
      $empleado_pre = Funciones::render_manual($empleado_get);
      $empleado = array_merge($empleado_pre,$fechas);
      $result = $this->model->comision_guardar($empleado);
      if ($result > 0){
        $guardado = true;
      }
      echo json_encode($guardado);

    }
    // ==================== PRESTAMO ===========================================
    public function prestamo() {
        @SessionHandler()->check_state(1);
        $list = $this->model->prestamo();
        $hoy = date('d-m-Y');
        if (empty($list)) {
            Funciones::redireccion('La tabla esta vacia', '/empleados/empleado/agregar');
        } else {
          $this->view->prestamo($list,$hoy);
        }
    }
    public function prestamo_guardar() {
      @SessionHandler()->check_state(1);
      $clave_empleado = isset($_POST['clave_empleado']) ? $_POST['clave_empleado'] : 0;
      $prestamo = isset($_POST['prestamo']) ? $_POST['prestamo'] : 0;
      $fecha = isset($_POST['fecha']) ? Funciones::fecha($_POST['fecha']) : "null";
      $this->model->prestamo_guardar($clave_empleado,$prestamo,$fecha);
      HTTPHelper::go("/empleados/empleado/prestamo_listar/$clave_empleado");
    }
    public function prestamo_calculo() {
      @SessionHandler()->check_state(1);
      $fecha = isset($_POST['fecha']) ? Funciones::fecha($_POST['fecha']) : "null";
      $fecha2 = isset($_POST['fecha2']) ? Funciones::fecha($_POST['fecha2']) : "null";
      $clave_empleado = isset($_POST['clave_empleado']) ? $_POST['clave_empleado'] :0;
      $this->model->prestamo_calculo($fecha,$fecha2,$clave_empleado);
      echo json_encode($list);
    }
    public function prestamo_listar($id=0) {
      @SessionHandler()->check_state(1);
      $this->model->prestamo_listar($id);
      $this->view->prestamo_listar($list);
    }
}
