$(document).ready(function() {
    $(".guardar_comision").click(function() {
        var valores = "";
        // Obtenemos todos los valores contenidos en los <td> de la fila
        // seleccionada
        $(this).parents("tr").find("td").each(function() {
            valores += $(this).html() + "\n";
        });

        alert(valores);
    });
});

function pago_comision(id, comision, f_i, f_f) {
  $.ajax({
        url: "/empleados/empleado/comision_guardar",
        dataType: "json",
        type: 'post',
        data: {
            id: id,
            comision: comision,
            fecha_inicio: f_i,
            fecha_final: f_f,
        },
        success: function(data) {
            // $("#respuesta").html(data);
            if (data == true) {
                alertify.success("Se a guardado el pago de la comision exitosamente");
                borrar_fila_comision(id);
                return false;
            }else {
              alertify.error("ocurrio un error intentelo nuevamnete");
              return false;
            }
        }
    });
}

function borrar_fila_comision(idj) {
    $('#' + idj).remove();
}
