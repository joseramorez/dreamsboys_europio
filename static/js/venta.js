
$(document).ready(function() {
    $("#total").attr("readonly", "readonly");
    $("#total").addClass("readOnly");
    $("#monto_a_pagar").attr("readonly", "readonly");
    $("#monto_a_pagar").addClass("readOnly");
    $("#cambio").attr("readonly", "readonly");
    $("#cambio").addClass("readonly");

    // CONTENIDO MODAL INICIA OCULTO
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
// evento de enter
    $("#codigo_venta").keypress(function(e){
        if (e.which == 13) {
          console.log("clic en enter");
          $("#agregar").click();
        }
    });
// funcion en boton agregar
    $("#agregar").click(function() {
        var url = "/tienda/venta/busca_producto";
        if ($("#codigo_venta").val().length > 0) {
            var cant = prompt("CANTIDAD", 1);
            var desc = prompt("DESCUENTO", 0);
            $.ajax({
                type: "POST",
                url: url,
                dataType: "json",
                data: {
                    codigo: $("#codigo_venta").val(),
                    cantidad: cant,
                    descuento: desc
                },
                success: function(data) {
                    var tabla = '';
                    if (jQuery.isEmptyObject(data)) {
                        alertify.alert("El producto no existe ó esta agotado");
                    }
                    $.each(data, function(element, fila) {
                        tabla = tabla + "<tr>";
                        tabla += "<td><button type='button' class='glyphicon glyphicon-remove borrar' value=''></button></i></td>";
                        tabla += "<td style='display:none;'><input class='form-control centrate' readonly  name='producto_id[]' value='" + fila.id + "'></td>"
                        tabla += "<td><input class='form-control centrate' readonly type='text' name='codigo[]' value='" + fila.codigo + "'></td>"
                        tabla += "<td><input class='form-control centrate' readonly type='text' name='nombre_producto[]' value='" + fila.nombre_producto + "'></td>"
                        tabla += "<td><input class='form-control centrate' readonly type='text' name='cantidad[]' value='" + fila.cantidad + "'></td>"
                        tabla += "<td><input class='form-control centrate' readonly type='text' name='precio_venta[]' value='" + fila.precio_venta + "'></td>"
                        tabla += "<td><input class='form-control centrate' readonly type='text' name='descuento[]' value='" + fila.descuento + "'></td>"
                        tabla += "<td><input type='numeric' class='importe form-control centrate' readonly type='text' name='importe[]' value='" + fila.importe + "'></td>"
                    });
                    tabla += "</tr>";
                    $("#tablaventa tr:last").after(tabla);
                    $("#codigo_venta").val("");
                    $("#codigo_venta").focus();
                    total();
                },
                error: function() {
                    alertify.alert('Disculpe, existió un problema. Intentelo nuevamente');
                },

            });
        } else {
            alertify.alert("Necesita introducir un codigo");
        }

    });
// =================================================================================
    // CALCULO DE CAMBIO
    $("#cantidad_recibida").keyup(function() {
      var total = $('#total').val();
      var paga_con = $('#cantidad_recibida').val();
      var cambio = (paga_con - total);
      $('#cambio').val(cambio);

    });
});//-------->FIN--- funciones de venta

// manda code a input de agregar
function agregarApedido() {
 var codigo = $('#codigo span').text();
 $('#nombre_buscar').val("");
 $('#codigo_venta').val(codigo);
 $('#modal_buscar').modal('hide');
}
// borra fila
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
