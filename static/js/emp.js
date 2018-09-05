$(document).ready(function() {
    // busca salario de empleado
    $('#clave_empleado').on('change', function() {
        $('#fecha').val('');
        $('#fecha2').val('');
        $('#prestamo').val('');
        $('#salario').val('');
        if (this.value > 0) {
            $.ajax({
                type: "POST",
                url: "/empleados/empleado/salario",
                dataType: "json",
                data: {
                    salario: $("#clave_empleado").val(),
                },
                success: function(data) {
                    if (jQuery.isEmptyObject(data)) {
                        alertify.alert("no se encuentra la cantidad de pago del empleado");
                    }
                    $.each(data, function(element, fila) {
                        salario = fila.salario;
                    });
                    $("#pago").val(salario);
                },
                error: function() {
                    alertify.alert('Disculpe, existió un problema. Intentelo nuevamente');
                },

            });
        }
    }); //->> fin function ajax
    // verifica rango de fecha (comision, pago)
    $('#fecha,#fecha2').on('change', function() {
        var fecha1 = $('#fecha').val();
        var fecha2 = $('#fecha2').val();
        if (fecha1 != "" && fecha2 != "") {
            var f = fechaCorrecta(fecha1, fecha2);
        }
        if (f == false) {
            alertify.alert("la fecha final no debe de ser inferior o igual a la fecha inicial", function() {
                $('#fecha2').val("");
                $('#fecha2').focus();
            });
        } else if (f == true) {
            $.ajax({
                type: "POST",
                url: "/empleados/empleado/prestamo_calculo",
                dataType: "json",
                data: {
                    fecha: fecha1,
                    fecha2: $("#fecha2").val(),
                    clave_empleado: $("#clave_empleado").val(),
                },
                success: function(data) {
                    if (jQuery.isEmptyObject(data)) {
                        alertify.alert("no se encuentra la cantidad de pago del empleado");
                    }
                    $.each(data, function(element, fila) {
                        if (fila.total_prestamo == null) {
                            total_prestamo = 0
                        } else {
                            total_prestamo = fila.total_prestamo;
                        }
                    });
                    $("#prestamo").val(total_prestamo);
                }
            }); //-->>fin de ajax
        }
    });
}); //->>fin del document.ready

function fechaCorrecta(fecha1, fecha2) {
    var x = fecha1.split('-');
    var z = fecha2.split('-');
    fecha1 = x[2] + '-' + x[1] + '-' + x[0];
    fecha2 = z[2] + '-' + z[1] + '-' + z[0];
    if (Date.parse(fecha1) > Date.parse(fecha2)) {
        return false;
    } else {
        return true;
    }
}

function totall() {
    var pago = $('#pago').val();
    var prestamo = $('#prestamo').val();
    var p_final = (pago - prestamo);
    $('#salario').val(p_final);
}
