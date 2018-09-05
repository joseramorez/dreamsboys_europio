function habilitar(field) {
    if (document.getElementById('level').selectedIndex == 3) {
        document.getElementById(field).disabled = false;
        document.getElementById(field).focus();
    } else {
        document.getElementById(field).value = '';
        document.getElementById(field).disabled = true;
    }

}


function generar_pwd(field) {
    var raritos = ["@", "#", "$", "-", "_"];
    var minusculas = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k',
        'm', 'n', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'
    ];
    var mayusculas = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K',
        'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
    ];
    var numeros = [1, 2, 3, 4, 5, 6, 7, 8, 9, 0];

    clave = get_random_char(minusculas, 6);
    clave += get_random_char(raritos, 1);
    clave += get_random_char(mayusculas, 3);
    clave += get_random_char(numeros, 2);

    document.getElementById(field).readOnly = true;
    document.getElementById(field).value = clave;
    document.getElementById(field).className = 'm';
    document.getElementById('errorpwd').innerHTML = '';
    document.getElementById('pwd_generada').style.display = 'inline';
    str_clave = " - Guarde esta clave: <b>" + clave + "</b>";
    document.getElementById('pwd_generada').innerHTML = str_clave;
}


function get_random_char(stack, cantidad) {
    choice = '';
    for (i = 0; i < cantidad; i++) {
        indice = Math.floor((Math.random() * (stack.length - 1)) + 1);
        choice += stack[indice];
    }
    return choice;
}


function verificar_nombre() {
    username = document.getElementById('name').value;
    if (username.length > 5) {
        document.getElementById('name').className = 'form-control';
        document.getElementById('usererror').innerHTML = '';
    }
}


function confirmar(username, userid) {
    pregunta = "¿Está seguro de eliminar al usuario \"" + username + "\"?";
    if (confirm(pregunta)) {
        location.href = '/users/user/eliminar/' + userid;
    }
}

function cerrar() {
    if (confirm('Desea cerrar sesion')) {
        location.href = '/users/user/logout'
    }
}

// editar segimiento
$(document).ready(function() {
    $("#editarOficio").click(function() {
        if (confirm("Desea editar el archivo")) {
            $('#divOficio').css("display", "block"); //muestro mediante id
            $('#divOficioE').css("display", "none"); //muestro mediante clase
        }
    });
    $("#editarRespuesta").click(function() {
        if (confirm("Desea editar el archivo")) {
            $('#divRespuesta').css("display", "block"); //muestro mediante id
            $('#divRespuestaE').css("display", "none"); //muestro mediante clase
        }
    });
    $("#editarResponde").click(function() {
        if (confirm("Desea editar el archivo")) {
            $('#divResponde').css("display", "block"); //muestro mediante id
            $('#divRespondeE').css("display", "none"); //muestro mediante clase
        }
    });
    // $("#ocultar").on( "click", function() {
    // 	$('#target').hide(); //oculto mediante id
    // 	$('.target').hide(); //muestro mediante clase
    // });
});

$(document).ready(function() {
    var hash = window.location.hash;
    hash && $('ul.nav a[href="' + hash + '"]').tab('show');
    // alert(hash);
    $('.nav-tabs a').click(function(e) {
        $(this).tab('show');
        var scrollmem = $('body').scrollTop() || $('html').scrollTop();
        window.location.hash = this.hash;
        $('html,body').scrollTop(scrollmem);
    });
});
