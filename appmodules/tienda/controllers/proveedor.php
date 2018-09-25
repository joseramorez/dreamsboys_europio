<?php

import('appmodules.tienda.models.proveedor');
import('appmodules.tienda.views.proveedor');
import('common.plugins.PHPMailer-master.PHPMailerAutoload');

class proveedorController extends Controller {

    public function agregar() {
      @SessionHandler()->check_state(1);
        $this->view->agregar();
    }

    public function editar($id=0) {
      @SessionHandler()->check_state(1);
        $this->model->proveedor_id = $id;
        $this->model->get();
        $this->view->editar($this->model);
    }

    public function guardar() {
      @SessionHandler()->check_state(1);
      $id = (isset($_POST['id'])) ? $_POST['id'] : 0;
      $ruc = (isset($_POST['ruc'])) ? $_POST['ruc'] : "";
      $razon_social = (isset($_POST['razon_social'])) ? $_POST['razon_social'] : "";
      $direccion = (isset($_POST['direccion'])) ? $_POST['direccion'] : "";
      $descripcion_rubro = (isset($_POST['descripcion_rubro'])) ? $_POST['descripcion_rubro'] : "";
      $email = (isset($_POST['email'])) ? $_POST['email'] : "";
      $telefono = (isset($_POST['telefono'])) ? $_POST['telefono'] : 0;
        $this->model->proveedor_id = $id;
        $this->model->ruc = $ruc;
        $this->model->razon_social = $razon_social;
        $this->model->direccion = $direccion;
        $this->model->descripcion_rubro = $descripcion_rubro;
        $this->model->email = $email;
        $this->model->telefono = $telefono;
        $this->model->save();

        HTTPHelper::go("/tienda/proveedor/listar");
    }

    public function listar() {
      @SessionHandler()->check_state(1);
        $a= (isset($_GET['p'])) ? "&p=".$_GET['p'] : "";
        $collection = CollectorObject::get('proveedor');
        $list = $collection->collection;
        if (empty($list)) {
          Funciones::redireccion('La tabla esta vacia', '/tienda/proveedor/agregar');
        }else {
          $this->view->listar($list,$a);
        }
    }

    public function eliminar($id=0) {
        $this->model->proveedor_id = $id;
        $this->model->destroy();
        HTTPHelper::go("/tienda/proveedor/listar");
    }
    public function pedido(){
      @SessionHandler()->check_state(1);
      $proveedor = (isset($_GET['pr'])) ? $_GET['pr'] : "";
      $producto = (isset($_GET['p'])) ? $_GET['p'] : "";
      $tabla = "";
      $this->model->proveedor_id = $proveedor;
      $this->model->get();
      if (!empty($producto)) {
        $prod_acarreo = $this->model->producto($producto);
        $tabla = $tabla . "<tr>";
        $tabla .= "<td><button type='button' class='glyphicon glyphicon-remove borrar' value='' /></td>";
        $tabla .= "<td style='display:none;'><input class='form-control centrate' readonly  name='id[]' value='".$prod_acarreo[0]["id"]."'></td>";
        $tabla .= "<td><input class='form-control centrate' readonly type='text' name='codigo[]' value='".$prod_acarreo[0]["codigo"]."'></td>";
        $tabla .= "<td><input class='form-control centrate' readonly type='text' name='nombre_producto[]' value='".$prod_acarreo[0]["nombre_producto"]."'></td>";
        $tabla .= "<td><input class='form-control centrate'  type='numeric' name='cantidad[]' value='".$prod_acarreo[0]["cantidad"]."'></td>";
        $tabla .= "<td><input class='form-control centrate' readonly type='text' name='precio_compra[]' value='".$prod_acarreo[0]["precio_compra"]."'></td>";
        $tabla .= "<td><input class='form-control centrate' readonly type='text' name='existencia[]' value='".$prod_acarreo[0]["existencia"]."'></td>";
        $tabla .= "<td><input type='numeric' class='form-control centrate' readonly type='text' name='stock[]' value='".$prod_acarreo[0]["stock"]."'></td>";
        $tabla .= "</tr>";

        $this->view->pedido($this->model,$tabla);
      }else {
        $this->view->pedido($this->model);
      }

    }
    public function realizar_pedido() {
      $proveedor_id = isset($_POST['proveedor_id']) ? $_POST['proveedor_id'] : 0;
      $producto_id = isset($_POST['id']) ? $_POST['id'] : 0;
      $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 0;
      $email = isset($_POST['email']) ? $_POST['email'] : 0;
      $razon_social = isset($_POST['razon_social']) ? $_POST['razon_social'] : 0;
      $codigo = isset($_POST['codigo']) ? $_POST['codigo'] : 0;
      $nombre_producto = isset($_POST['nombre_producto']) ? $_POST['nombre_producto'] : 0;
      $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 0;
      $precio_compra = isset($_POST['precio_compra']) ? $_POST['precio_compra'] : 0;
      $No_total = count($producto_id);
      $plano="";
      $html = "<table border='1'><thead><tr>
                  <th>nombre producto</th><th>codigo</th>
                  <th>cantidad</th></tr>
          </thead><tbody>";
      $total = 0;
      for($i=0; $i<$No_total; $i++){
        $plano .= "producto : ".$nombre_producto[$i]." codigo: ".$codigo[$i]." cantidad: ".$cantidad[$i]."\n";
         $html .= "<tr>"."<td>".$nombre_producto[$i]."</td>"."<td>".$codigo[$i]."</td>"."<td>".$cantidad[$i]."</td>"."</tr>"."\n";
         $total +=($cantidad[$i] * $precio_compra[$i]);
        }
        $html.="</tbody>
                    </table>";
        $this->sendCorreo($plano,$html,$email,$razon_social);
    }

    public function busca_producto(){
      $codigo = (isset($_POST['codigo'])) ? $_POST['codigo'] : 0;
      $cantidad = "1";
      $results = $this->model->busca_producto($codigo,$cantidad);
      echo json_encode($results);
    }
  public function sendCorreo($texto_plano="",$texto_html="",$correo_proveedor="",$razon_social="")
  {
    $correo = $this->model->sendCorreo();
    if (!empty($correo)) {
      foreach ($correo as $key=>&$value) {
        $value['pass'] = base64_decode($value['pass']);
         $this->correo_tienda = $value['correo'];
         $this->pass_tienda = $value['pass'];
      }
    }
    //Recibir todos los parámetros del formulario
    $para = $correo_proveedor;
    $asunto = "Pedido DreamsBoys";
    $mensaje = $texto_html;
    $plano = $texto_plano;
    // $archivo = $_FILES['file'];

    //Este bloque es importante
    $mail = new PHPMailer();
    $mail->IsSMTP();
    // $mail->SMTPDebug = 2;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "ssl";
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465;
    // Dirección de correo del remitente
    $mail->From = "$this->correo_tienda";
    // Nombre del remitente
    $mail->FromName = "DREAMS BOYS";
    //Nuestra cuenta
    $mail->Username = $this->correo_tienda;
    $mail->Password = $this->pass_tienda; //Su password

    //Agregar destinatario
    $mail->AddAddress($para);
    $mail->Subject = $asunto;
    $mail->Body = $mensaje;
    //Para adjuntar archivo
    // $mail->AddAttachment($archivo['tmp_name'], $archivo['name']);
    $mail->MsgHTML($mensaje);
    $this->AltBody = $plano;

    //Avisar si fue enviado o no y dirigir al index
    if($mail->Send())
    {
      Funciones::redireccion('Enviado Correctamente.', '/tienda/proveedor/listar');
     return false;
    }
    else{
      Funciones::redireccion('NO ENVIADO, intentar de nuevo. Al salio mal', '/tienda/proveedor/listar');
    }

  }
  public function exportar($plano='')
  {
    $hoy = strftime('%d-%m-%y', time());
    header("Content-type: application/vnd.ms-excel; name='excel'");
    header("Content-Disposition: filename=Sismo"."pedido"."_"."$hoy.xls");
    header('Pragma: no-cache');
    header('Expires: 0');
    $dados_recebido = iconv('utf-8', 'iso-8859-1//IGNORE', $texto_plano);
    echo $dados_recebido;
  }
}
?>
