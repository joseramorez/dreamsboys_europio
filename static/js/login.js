// logear
function logear() {
    $.ajax({
        url: '/users/user/verificar',
        data: $("#login").serialize(),
        type: "post",
        success: function(respuesta) {
            if (respuesta == "false") {
                alertify.alert("Verifique", "usuario contraseña incorrecto", function() {
                    alertify.error('Intente Nuevamente');
                });
                document.getElementById('pwd').value = '';
                document.getElementById('user').value = '';
                $('#user').focus();

            } else {
                alertify.success('Bienvenido');
                $('#login').submit();
            }
        }
    });
}
$(document).ready(function() {
    $('#user').focus();
    $("#login").keypress(function(e) {
        if (e.which == 13) {
            // Acciones a realizar, por ej: enviar formulario.
            // $('#frm').submit();
            logear();

        }
    });
});

$("#login").validate({
    rules: {
        user: "required",
        pwd: "required"

    },
    messages: {
        user: "introdusca su usuario",
        pwd: "introdusca su contraseña"

    },
    errorElement: "em",
    errorPlacement: function(error, element) {
        // Add the `help-block` class to the error element
        error.addClass("help-block");

        if (element.prop("type") === "checkbox") {
            error.insertAfter(element.parent("label"));
        } else {
            error.insertAfter(element);
        }
    },
    highlight: function(element, errorClass, validClass) {
        $(element).parents(".col-sm-5").addClass("has-error").removeClass("has-success");
    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).parents(".col-sm-5").addClass("has-success").removeClass("has-error");
    }
});
