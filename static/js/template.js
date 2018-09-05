//override defaults
alertify.defaults.transition = "slide";
alertify.defaults.theme.ok = "btn btn-primary";
alertify.defaults.theme.cancel = "btn btn-danger";
alertify.defaults.theme.input = "form-control";

function cerrar() {
    alertify.confirm('Dreamsboys', 'Desea cerrar sesion', function() {
        location.href = '/users/user/logout';
        alertify.success('Ok');
    }, function() {
        // alertify.error('cancelado');
    });


}

function cancelar(url) {
    alertify.confirm("¡Se perderan los datos!", function() {
        location.href = url;
        alertify.success('Ok');
    })
}

function confirmar(mensaje, url) {
    alertify.confirm(mensaje, function() {
        location.href = url;
        alertify.success('Ok');
    });
}

// personalizado1
function confirmar_personalizado(mensaje, url,okas,cancelar) {
    alertify.confirm(mensaje, function() {
        location.href = url;
        alertify.success('Ok');
    }).set('labels', {ok:okas, cancel:cancelar});
}
// personalizado2 auto cancelar despues de 5 segundos
function confirmar_time(mensaje,url) {
  alertify.confirm(mensaje, function() {
      location.href = url;
  }).autoCancel(5).set('oncancel', function(){alertify.error('Abartado');});

}

$(document).ready(function() {
  // para todos los tooltip del sistema
  $('[data-toggle="tooltip"]').tooltip();
// mostrar y ocultar pas
    $('#mostrar_contrasena').click(function() {
        if ($('#mostrar_contrasena').is(':checked')) {
            $('#pass').attr('type', 'text');
        } else {
            $('#pass').attr('type', 'password');
        }
    });

});//--fin del document ready
