$(document).ready(function() {
    // $('#principal').click('load', function() {
    // });
    // $('#herramientas').click('load', function() {
    //     herramientas();
    // });
    // $('#graficas').click('load', function() {
    //     graficas();
    // });
    // $('#busqueda').click('load', function() {
    //     busquedas();
    // });
    // $('#reportes').click('load', function() {
    //     reportes();
    // });
    // $('#sistema').click('load', function() {
    //     sistema();
    // });
    // mapa de tamaños
    var heights = $(".alto").map(function() {
            return $(this).height();
        }).get(),

        maxHeight = Math.max.apply(null, heights);

    $(".alto").height(maxHeight);

    $("#busqueda_avanzada").change(function(){
                var op = $("#busqueda_avanzada").val();
                alertify.confirm('¿va a cambiar de formulario ?',
                    function() {
                      busqueda_select(op);
                    }, function() {
                      $( "$categoria_id option:value" ).prev().attr("selected", "selected");
                    });
        });

});

// CONTROL
function sistema() {
    $.ajax({
        type: "POST",
        url: "/users/user/sistema",
        success: function(data) {
            $('#contenedor').html(data);
        }
    });
}

function reportes() {
    $.ajax({
        type: "POST",
        url: "/users/user/reportes",
        success: function(data) {
            $('#contenedor').html(data);
        }
    });
}

function graficas() {
    $.ajax({
        type: "POST",
        url: "/users/user/graficas",
        success: function(data) {
            $('#contenedor').html(data);
        }
    });
}
function herramientas() {
    $.ajax({
        type: "POST",
        url: "/tienda/producto/herramientas",
        success: function(data) {
            $('#contenedor').html(data);
        }
    });
}
function busquedas() {
    $.ajax({
        type: "POST",
        url: "/users/user/busquedas",
        success: function(data) {
            $('#contenedor').html(data);
        }
    });
}
// funciones de busqueda para ver form en select idependiente
function producto() {
    $.ajax({
        type: "POST",
        url: "/tienda/producto/buscar/1",
        success: function(data) {
          $('#contenedor_busqueda').html(data);
        }
    });

}
function compras() {
    $.ajax({
        type: "POST",
        url: "/tienda/compra/busqueda/1",
        success: function(data) {
            $('#contenedor_busqueda').html(data);
        }
    });
}
function ventas() {
    $.ajax({
        type: "POST",
        url: "/tienda/venta/busqueda",
        success: function(data) {
            $('#contenedor_busqueda').html(data);
        }
    });
}

function c_faltantes() {
    alertify.confirm('¿Quiere ir a la lista de faltantes?',
        function() {
            location.href = '/tienda/producto/faltantes';
            alertify.success('Ok');
        });
}

function c_productos() {
    alertify.confirm('¿Quiere ir a la lista de productos?',
        function() {
            location.href = '/tienda/producto/listar';
            alertify.success('Ok');
        });
}

function c_ventas() {
    alertify.confirm('¿Quiere ir a la lista de ventas?',
        function() {
            location.href = '/tienda/venta/listar';
            alertify.success('Ok');
        });
}
function generar_codigo() {
  var codigo = $('#val_codigo').val();
  alertify.confirm('¿Desea generar el codigo '+codigo+ '?',
  function(){location.href = '/tienda/producto/_barcode/'+codigo;
  $('#val_codigo').val("");
  $('#val_codigo').focus(); });
}
function busqueda_select(seleccion) {
  var url_b = '/users/user/control/?lu=busqueda';
  switch (seleccion) {
    case '0':
      location.href = url_b;
      break;
    case '1':
    // producto();
      location.href = url_b+'&'+'re=producto';
      break;
    case '2':
      // compras();
      location.href = url_b+'&'+'re=compras';
      break;
    case '3':
      // ventas();
      location.href = url_b+'&'+'re=ventas';
      break;
    default:
  }
}
