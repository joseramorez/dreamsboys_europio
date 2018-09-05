<?php

class empleado extends StandardObject {

    public function __construct() {
        $this->empleado_id = 0;
        $this->nombre = "";
        $this->apellido_paterno = "";
        $this->apellido_materno = "";
        $this->edad = 0;
        $this->fecha_nacimiento = "";
        $this->direccion = "";
        $this->telefono = 0;
        $this->curp = "";
        $this->rfc = "";
        $this->salario = 0;
    }
    function empleado_get($id=0)
    {
      $sql_0 ="SELECT empleado_id, nombre, apellido_paterno, apellido_materno, edad, date_format(fecha_nacimiento,'%d/%m/%Y'), direccion, telefono, curp, rfc, salario FROM empleado WHERE empleado_id>?";
      $sql_1 ="SELECT empleado_id, nombre, apellido_paterno, apellido_materno, edad, date_format(fecha_nacimiento,'%d-%m-%Y'), direccion, telefono, curp, rfc, salario FROM empleado WHERE empleado_id=?";
      $sql = ($id>0) ? $sql_1 : $sql_0;
      $data = array("i", "$id");
      $fields = array("empleado_id"=>"", "nombre"=>"", "apellido_paterno"=>"", "apellido_materno"=>"", "edad"=>"", "fecha_nacimiento"=>"", "direccion"=>"", "telefono"=>"", "curp"=>"", "rfc"=>"", "salario"=>"");
      $results = MySQLiLayer::ejecutar($sql, $data, $fields);
      return $results;
    }
    function listar()
    {
      $sql ="SELECT empleado_id, nombre, apellido_paterno, apellido_materno, edad, date_format(fecha_nacimiento,'%d-%m-%Y'), direccion, telefono, curp, rfc, salario FROM empleado WHERE empleado_id>?";
      $data = array("i", "0");
      $fields = array("empleado_id"=>"", "nombre"=>"", "apellido_paterno"=>"", "apellido_materno"=>"", "edad"=>"", "fecha_nacimiento"=>"", "direccion"=>"", "telefono"=>"", "curp"=>"", "rfc"=>"", "salario"=>"");
      $results = MySQLiLayer::ejecutar($sql, $data, $fields);
      return $results;
    }
    function pago_listar($ext = "")
    {
      $sql ="SELECT tp.pago_empleado_id, tp.empleado_id, tp.fecha_pago_inicio, tp.fecha_pago_final, tp.total_prestamo, tp.pago,
      CONCAT(tp.empleado_id,' : ',te.nombre,' ',te.apellido_paterno) as nombre
            FROM pago_empleado tp, empleado te
            WHERE pago_empleado_id > ?
            AND tp.empleado_id = te.empleado_id
            ORDER BY pago_empleado_id DESC ".$ext;
      $data = array("i", "0");
      $fields = array("pago_id"=>"", "empleado_id"=>"", "fecha_pago_inicio"=>"", "fecha_pago_final"=>"", "total_prestamo"=>"", "pago"=>"", "nombre"=>"");
      $results = MySQLiLayer::ejecutar($sql, $data, $fields);
      return $results;
    }
    function comicion_listar($ext="",$comision=0,$fecha_inicio="",$fecha_final="")
    {
      $sql1 = "SELECT te.nombre, tv.empleado_id, sum(tv.total) as totalsum,
              ({$comision}) as comision, (((sum(tv.total) * {$comision} )/100))
              FROM venta tv, empleado te
              WHERE venta_id > ?
              AND tv.fecha_venta BETWEEN ? AND ?
              AND tv.empleado_id=te.empleado_id
              GROUP BY tv.empleado_id ORDER BY totalsum DESC ".$ext;
      $sql2 = "SELECT tc.comision_id, te.nombre, tc.fecha_inicio, tc.fecha_final, tc.total_ventas, tc.porcentaje_comision, tc.total_comision
                FROM comision tc, empleado te
                WHERE tc.empleado_id > ?
                AND tc.empleado_id = te.empleado_id".$ext;
      $sql = (empty($fecha_inicio) && empty($fecha_final)) ? $sql2 : $sql1;
      $data1 = array("iss", "0",''.$fecha_inicio,''.$fecha_final);
      $data2 = array("i", "0");
      $data =  (empty($fecha_inicio) && empty($fecha_final)) ? $data2 : $data1;
      $fields_1 = array("nombre"=>"","clave_empleado"=>"","vendido"=>"","comision"=>"","totalconcomision"=>"");
      $fields_2 = array("comision_id"=>"","nombre"=>"","fecha_inicio"=>"","fecha_final"=>"","total_ventas"=>"","porcentaje_comision"=>"","total_comision"=>"");
      $fields = (empty($fecha_inicio) && empty($fecha_final)) ? $fields_2 : $fields_1;
// var_dump($fields);
      $list = MySQLiLayer::ejecutar($sql, $data, $fields);
      return $list;
    }
    public function comision_lista_posicion($a,$b)
    {
      $todo = array();
      $todo["pocision_1"] = $this->comicion_listar("LIMIT 0,1",1,$a,$b);
      $todo["pocision_2"] = $this->comicion_listar("LIMIT 1,1",0.8,$a,$b);
      $todo["pocision_3"] = $this->comicion_listar("LIMIT 2,1",0.7,$a,$b);
      $todo["restantes"] = $this->comicion_listar("LIMIT 3,1000",0.5,$a,$b);
      return $todo;
    }
    function comision_guardar($datos=array())
    {
      $clave_empleado = $datos["clave_empleado"];
      $fecha_inicio = $datos["fecha_inico"];
      $fecha_final = $datos["fecha_final"];
      $total_ventas = $datos["vendido"];
      $porcentaje_comision = $datos["comision"];
      $total_comision = $datos["totalconcomision"];

      $sql = "INSERT INTO comision(empleado_id, fecha_inicio, fecha_final, total_ventas, porcentaje_comision, total_comision)
                    VALUES (?,?,?,?,?,?)";
      $data = array("ssssss", "".$clave_empleado,"".$fecha_inicio,"".$fecha_final, "".$total_ventas,"".$porcentaje_comision,"".$total_comision);
      $result = MySQLiLayer::ejecutar($sql, $data);
      return $result;
    }

    function comision_empleado($id,$comision,$f_inicio,$f_final)
    {
      $sql = "SELECT te.nombre, tv.empleado_id, sum(tv.total) as totalsum,
              ({$comision}) as comision, (((sum(tv.total) * {$comision} )/100)) + sum(tv.total)
              FROM venta tv, empleado te
              WHERE tv.empleado_id = ?
              AND tv.fecha_venta BETWEEN '$f_inicio' AND '$f_final'
              AND tv.empleado_id=te.empleado_id
              GROUP BY tv.empleado_id ORDER BY totalsum DESC ";
      $data = array("i", "$id",);
      $fields = array("nombre"=>"", "clave_empleado"=>"", "vendido"=>"", "comision"=>"", "totalconcomision"=>"");
      $result = MySQLiLayer::ejecutar($sql, $data, $fields);
      return $result;
    }
    function comision_get($id=0)
    {
      // el sql_1 lista las primeras 200 frmLasPilas
      // el sql_2 lista la primeras 200 filas de la id del empleado especificado
      $sql_1 ="SELECT tc.comision_id, te.nombre, tc.fecha_inicio, tc.fecha_final, tc.total_ventas, tc.porcentaje_comision, tc.total_comision
              FROM comision tc, empleado te
              WHERE tc.comision_id > ?
              AND tc.empleado_id=te.empleado_id
              LIMIT 0,200";
      $sql_2 = "SELECT tc.comision_id, te.nombre, tc.fecha_inicio, tc.fecha_final, tc.total_ventas, tc.porcentaje_comision, tc.total_comision
              FROM comision tc, empleado te
              WHERE tc.comision_id = ?
              AND tc.empleado_id=te.empleado_id
              LIMIT 0,200";
      $sql = ($id>0) ? $sql_2 : $sql_1;
      $fields = array("comision_id"=>"", "nombre"=>"", "fecha_inicio"=>"", "fecha_final"=>"", "total_ventas"=>"", "porcentaje_comision"=>"", "total_comision"=>"");

      $result = MySQLiLayer::ejecutar($sql, $data, $fields);

    }

    function buscarpago($sql,$fecha_inicio,$fecha_final,$empleado_id)
    {
      switch ($sql) {
        case 1:
        // echo "sql: solo empleado sin fechas";
        $sql ="SELECT tp.pago_empleado_id, tp.empleado_id, tp.fecha_pago_inicio, tp.fecha_pago_final, tp.total_prestamo, tp.pago,
                CONCAT(tp.empleado_id,' : ',te.nombre,' ',te.apellido_paterno) as nombre
                      FROM pago_empleado tp, empleado te
                      WHERE pago_empleado_id = ?
                      AND tp.empleado_id = te.empleado_id
                      ORDER BY pago_empleado_id DESC ";
        $data  = array("i", (int)$empleado_id);
          break;
        case 2:
        // echo "sql: empleado y fechas";
        $sql ="SELECT tp.pago_empleado_id, tp.empleado_id, tp.fecha_pago_inicio, tp.fecha_pago_final, tp.total_prestamo, tp.pago,
                CONCAT(tp.empleado_id,' : ',te.nombre,' ',te.apellido_paterno) as nombre
                      FROM pago_empleado tp, empleado te
                      WHERE pago_empleado_id = ?
                      AND tp.fecha_pago_inicio BETWEEN ? AND ?
                      AND tp.empleado_id = te.empleado_id
                      ORDER BY pago_empleado_id DESC ";
        $data = array("iss", (int)$empleado_id,''.$fecha_inicio,''.$fecha_final);

          break;
          case 3:
          // echo "sql: solo fechas";
          $sql ="SELECT tp.pago_empleado_id, tp.empleado_id, tp.fecha_pago_inicio, tp.fecha_pago_final, tp.total_prestamo, tp.pago,
                CONCAT(tp.empleado_id,' : ',te.nombre,' ',te.apellido_paterno) as nombre
                      FROM pago_empleado tp, empleado te
                      WHERE pago_empleado_id > ?
                      AND tp.fecha_pago_inicio BETWEEN ? AND ?
                      AND tp.empleado_id = te.empleado_id
                      ORDER BY pago_empleado_id DESC ";
         $data = array("iss", '0',''.$fecha_inicio,''.$fecha_final);
          break;
      }
      $fields = array("pago_id"=>"", "empleado_id"=>"", "fecha_pago_inicio"=>"", "fecha_pago_final"=>"", "total_prestamo"=>"", "pago"=>"", "nombre"=>"");
      $list = MySQLiLayer::ejecutar($sql, $data, $fields);
      return $list;
    }

}

?>
