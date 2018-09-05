$(document).ready(function() {

  // function pulsar(e) {
  //   tecla = (document.all) ? e.keyCode : e.which;
  //   return (tecla!=13);
  //
  // }


 $("#cont_producto").css("display", "none");
    // autocompletado para producto
    $("#nombre_buscar").autocomplete({
        source: function(request, response) {
            $('#nombre_buscar').addClass('loadinggif');
            $.ajax({
                url: "/tienda/producto/_autocompletado",
                dataType: "json",
                type: 'post',
                data: {
                    nombre_buscar: encodeURI($("#nombre_buscar").val())
                },
                success: function(data) {
                    response($.map(data, function(item) {
                        $('#nombre_buscar').removeClass('loadinggif');
                        return {
                            label: item.nombre_producto,
                            marca: item.marca,
                            modelo: item.modelo,
                            precio_compra: item.precio_compra,
                            existencia: item.existencia,
                            codigo: item.codigo,
                            imagen: item.imagen
                        };
                    }));
                }
            });
        },
        minLength: 1,
        select: function(event, ui) {
            $('#nombre_buscar').val(ui.item.label);
            $('#nombre_producto').val(ui.item.label);
            $('#marca').val(ui.item.marca);
            $('#modelo span').text(ui.item.modelo);
            $('#precio_compra span').text(ui.item.precio_compra);
            $('#existencia').val(ui.item.existencia);
            $('#codigo span').text(ui.item.codigo);
            $('#imagen').attr("src", function(){return "/static/dreamsboys/" +ui.item.imagen;});
            $("#cont_producto").css("display", "block");

        }
    });

  $("#agregar").click(function () {
      var url = "/tienda/proveedor/busca_producto";
      if ($("#codigo_pedido").val().length > 0) {

          $.ajax({
              type: "POST",
              url: url,
              dataType: "json",
              data: {
                  codigo: $("#codigo_pedido").val(),
              },
              success: function(data) {
                  var tabla = '';
                  if (jQuery.isEmptyObject(data)) {
                      alertify.alert("El producto no existe");
                  }
                  $.each(data, function(element, fila) {
                      tabla = tabla + "<tr>";
                      tabla += "<td><button type='button' class='glyphicon glyphicon-remove borrar' value='' /></td>";
                      tabla += "<td style='display:none;'><input class='form-control centrate' readonly  name='id[]' value='" + fila.id + "'></td>"
                      tabla += "<td><input class='form-control centrate' readonly type='text' name='codigo[]' value='" + fila.codigo + "'></td>"
                      tabla += "<td><input class='form-control centrate' readonly type='text' name='nombre_producto[]' value='" + fila.nombre_producto + "'></td>"
                      tabla += "<td><input class='form-control centrate'  type='numeric' name='cantidad[]' value='" + fila.cantidad + "'></td>"
                      tabla += "<td><input class='form-control centrate' readonly type='text' name='precio_compra[]' value='" + fila.precio_compra + "'></td>"
                      tabla += "<td><input class='form-control centrate' readonly type='text' name='existencia[]' value='" + fila.existencia + "'></td>"
                      tabla += "<td><input type='numeric' class='form-control centrate' readonly type='text' name='stock[]' value='" + fila.stock + "'></td>"
                      // tabla += "<td><input type='numeric' class='importe form-control centrate' readonly type='text' name='stock[]' value=''></td>"
                  });
                  tabla += "</tr>";
                  $("#tablacompra tr:last").after(tabla);
                  $("#codigo_pedido").val("");
                  $("#codigo_pedido").focus();
                  // total();
              },
              error: function() {
                  alertify.alert('Disculpe, existió un problema. Intentelo nuevamente');
              },

          });
      } else {
          alertify.alert("Necesita introducir un codigo");
      }

  });

}); //fin de document ready

function agregarApedido() {
 var codigo = $('#codigo span').text();
 $('#nombre_buscar').val("");
 $('#codigo_pedido').val(codigo);
 $('#modal_buscar').modal('hide');
}
$(document).on('click', '.borrar', function(event) {
    event.preventDefault();
    $(this).closest('tr').remove();
    total();
});
function total() {
    var totalDeuda = 0;

    $(".importe").each(function() {
        totalDeuda += parseFloat($(this).val());
    });
    $("#total").val(totalDeuda);
}
function enviar() {
  alertify.confirm('¿Enviar el pedido?', function(){ $( "#frmpedido" ).submit(); });
}
