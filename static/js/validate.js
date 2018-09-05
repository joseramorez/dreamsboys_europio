$(document).ready(function() {
    // ===============================================================
    //            categoria
    // ===============================================================
    $('#nombre_categoria').focusout(function() {
        if (('#nombre_categoria').value != "") {
            $.ajax({
                type: "POST",
                url: "/tienda/categoria/VerificarCategoria",
                dataType: "json",
                data: {
                    categoria: encodeURI($("#nombre_categoria").val()),
                    id: encodeURI($("#categoria_id").val())
                },
                beforeSend: function() {
                    $('#nombre_categoria').addClass('loadinggif');
                },
                success: function(respuesta) {
                    if (respuesta) {
                        alertify.alert("La categoria ya existe");
                        $('#nombre_categoria').val("");
                        $('#nombre_categoria').focus();
                        $('#nombre_categoria').removeClass('loadinggif');

                    }
                    $('#nombre_categoria').removeClass('loadinggif');
                    // $('#nombre_categoria').attr("disabled", false);
                }
            });
        }
        return false;
    });

    $("#frmcategoria").validate({
        rules: {
            nombre_categoria: "required",
            desc_categoria: "required"
        },
        messages: {
            nombre_categoria: "Introduzca una categoría",
            desc_categoria: "Introduzca una descripción"
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
            $(element).parents(".col-sm-6").addClass("has-error").removeClass("has-success");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents(".col-sm-6").addClass("has-success").removeClass("has-error");
        }
    });
    // ===============================================================
    //                       MARCA
    // ===============================================================

    $('#nombre_marca').focusout(function vc() {
        if (('#nombre_marca').value != "") {
            $.ajax({
                type: "POST",
                url: "/tienda/marca/VerificarMarca",
                dataType: "json",
                data: {
                    marca: encodeURI($("#nombre_marca").val()),
                    id: encodeURI($("#marca_id").val())
                },
                beforeSend: function() {
                    $('#nombre_marca').addClass('loadinggif');
                },
                success: function(respuesta) {
                    if (respuesta) {
                        alertify.alert("La Marca ya existe");
                        $('#nombre_marca').val("");
                        $('#nombre_marca').focus();
                        $('#nombre_marca').removeClass('loadinggif');
                    }
                    $('#nombre_marca').removeClass('loadinggif');
                    // $('#nombre_marca').attr("disabled", false);
                }
            });
        }
        return false;
    });
    $("#frmMarca").validate({
        rules: {
            nombre_marca: "required",
            desc_marca: "required"
        },
        messages: {
            nombre_marca: "Introduzca una marca",
            desc_marca: "Introduzca una descripción"
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
            $(element).parents(".col-sm-6").addClass("has-error").removeClass("has-success");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents(".col-sm-6").addClass("has-success").removeClass("has-error");
        }
    });
    // ===============================================================
    //            CORREO
    // ===============================================================
    $("#frmcorreo").validate({
        rules: {
            correo: {
                required: true,
                email: true
            },
            pass: "required"
        },
        messages: {
            correo: {
                required: "Introduzca un correo",
                email: "correo no valido ej. ejemplo@gmail.com"
            },
            pass: "Introduzca su contraseña"
        },
        errorElement: "em",
        // errorPlacement: function(error, element) {
        //     // Add the `help-block` class to the error element
        //     error.addClass("help-block");
        //
        //     if (element.prop("type") === "checkbox") {
        //         error.insertAfter(element.parent("label"));
        //     } else {
        //         error.insertAfter(element);
        //     }
        // },
        highlight: function(element, errorClass, validClass) {
            $(element).parents(".col-sm-6").addClass("has-error").removeClass("has-success");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents(".col-sm-6").addClass("has-success").removeClass("has-error");
        }
    });
    // ===============================================================
    //            producto
    // ===============================================================

    // VALIDAD QUE EL CODIGO DEL PRODUCTO NO SE REPITA
    $('#codigo').focusout(function() {
        if (('#codigo').value != "") {
            $.ajax({
                type: "POST",
                url: "/tienda/producto/verificarexistencia",
                dataType: "json",
                data: {
                    codigo: encodeURI($("#codigo").val()),
                    id: encodeURI($("#producto_id").val())
                },
                beforeSend: function() {
                    $('#codigo').addClass('loadinggif');
                },
                success: function(respuesta) {
                    if (respuesta) {
                        alertify.alert("<B>El codigo del producto ya existe, ingrese uno distinto</B>");
                        $('#codigo').val("");
                        $('#codigo').focus();
                        $('#codigo').removeClass('loadinggif');
                    }
                    $('#codigo').removeClass('loadinggif');
                    // alert("El codigo de producto esta libre");
                }
            });
        }
    });
    return false;
});


$("#frmproducto").validate({
    rules: {
        categoria_id: "required",
        nombre_producto: "required",
        marca: "required",
        talla: "required",
        color: "required",
        modelo: "required",
        precio_compra: "required",
        precio_venta: "required",
        existencia: "required",
        stock: "required",
        codigo: "required"
            // imagen: "required",

    },
    messages: {
        categoria_id: "Seleccione una categoría",
        nombre_producto: "Introduzca un producto",
        marca: "Seleccione una marca",
        talla: "Introduzca una talla",
        color: "Introduzca un color",
        modelo: "Introduzca un modelo",
        precio_compra: "Introduzca el precio de compra",
        precio_venta: "Introduzca el precio de venta ",
        existencia: "Introduzca la existencia",
        stock: "Introduzca el stok",
        codigo: "Introduzca un código"
            // imagen: "Introduzca una imagen",

    },
    errorElement: "em",
    errorPlacement: function(error, element) {
        // Add the `help-block` class to the error element
        error.addClass("help-inline");

        if (element.prop("type") === "checkbox") {
            error.insertAfter(element.parent("label"));
        } else {
            error.insertAfter(element);
        }
    },
    highlight: function(element, errorClass, validClass) {
        $(element).parents(".form-group").addClass("has-error").removeClass("has-success");
    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).parents(".form-group").addClass("has-success").removeClass("has-error");
    },
    submitHandler: function(form) {
        alertify.confirm("¿Seguro que desea guardar el producto?", function() {
            alertify.success('Ok');
            form.submit();
        });
    }
});

// ===============================================================
//            Proveedor
// ===============================================================

$("#frmproveedor").validate({
    rules: {
        razon_social: "required",
        ruc: "required",
        direccion: "required",
        descripcion_rubro: "required",
        email: {
            required: true,
            email: true
        },
        telefono: {
            required: true,
            minlength: 7,
            maxlength: 13
        },

    },
    messages: {
        razon_social: "Introduzca un proveedor",
        ruc: "Introduzca la RUC",
        direccion: "Introduzca una direccón",
        descripcion_rubro: "Introduzca una descripcion del rubro",
        email: {
            required: "Introduzca un email",
            email: "Estructura invalida: ej. ejemplo@ejemplo.com"
        },
        telefono: {
            required: "Introduzca un teléfono",
            minlength: "Minimo 7 digitos",
            maxlength: "Como maximo se aceptan 13 digitos"
        },

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
        $(element).parents(".col-sm-6").addClass("has-error").removeClass("has-success");
    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).parents(".col-sm-6").addClass("has-success").removeClass("has-error");
    }
});


// ===============================================================
//                  COMPRA
// ===============================================================

$("#frmcompra").validate({
    rules: {
        proveedor_id: "required",
        fecha_compra: "required",
    },
    messages: {
        proveedor_id: "Seleccione un proveedor",
        fecha_compra: "Seleccione una fecha",
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
        $(element).parents(".col-md-5").addClass("has-error").removeClass("has-success");
    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).parents(".col-md-5").addClass("has-success").removeClass("has-error");
    }
});

// ===============================================================
//            usuario
// ===============================================================

$("#frmpusuario").validate({
    rules: {
        user: "required",
        pwd: "required",


    },
    messages: {
        user: "Introduzca un usuario",
        pwd: "Introduzca una contraseña",


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
        $(element).parents(".col-sm-6").addClass("has-error").removeClass("has-success");
    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).parents(".col-sm-6").addClass("has-success").removeClass("has-error");
    }
});


// ===============================================================
//            EMPLEADOS
// ===============================================================

$("#frmempleado").validate({
    rules: {
        nombre: "required",
        apellido_paterno: "required",
        apellido_materno: "required",
        edad: "required",
        fecha_nacimiento: "required",
        direccion: "required",
        telefono: "required",
        curp: "required",
        // rfc: "required",
        salario: "required",

    },
    messages: {
        nombre: "Introduzca un nombre",
        apellido_paterno: "Introduzca un apellido paterno",
        apellido_materno: "Introduzca apellido materno",
        edad: "Introduzca una edad",
        fecha_nacimiento: "Introduzca una fecha de nacimiento",
        direccion: "Introduzca una direccion",
        telefono: "Introduzca un teléfono",
        curp: "Introduzca una CURP",
        // rfc: "Introduzca un RFC",
        salario: "Introduzca un salario",

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
        $(element).parents(".col-sm-6").addClass("has-error").removeClass("has-success");
    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).parents(".col-sm-6").addClass("has-success").removeClass("has-error");
    }
});
// ===============================================================
//                          VENTA
// ===============================================================
$("#frmventa").validate({
    rules: {
      clave_vendedor: "required",
      total:"required",
      cantidad_recibida: "required"

    },
    messages: {
        clave_vendedor: "Seleccione a un vendedor",
        total: "No hay nada que cobrar",
        cantidad_recibida: "Introduzca la cantidad recibida"
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
        $(element).parents(".validar").addClass("has-error").removeClass("has-success");
    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).parents(".validar").addClass("has-success").removeClass("has-error");
    }
});


// ===============================================================
//             PAGO EMPLEADO
// ===============================================================
$("#frmpago_empleado").validate({
    rules: {
      clave_empleado: "required",
      pago:"required",
      fecha_pago_inicio: "required",
      fecha_pago_final: "required",
      prestamo: "required",
      pago_final: "required"

    },
    messages: {
        clave_empleado: "Seleccione a un empleado",
        pago: "No hay pago especificado",
        fecha_pago_inicio: "Introduzca la fecha inicio",
        fecha_pago_final: "Introduzca la fecha final",
        prestamo: "Introduzca la cantidad prestada",
        pago_final: "Clic en calcular"
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
        $(element).parents(".col-sm-6").addClass("has-error").removeClass("has-success");
    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).parents(".col-sm-6").addClass("has-success").removeClass("has-error");
    }
});
  // ===============================================================
  //            PRESTAMO EMPLEADO
  // ===============================================================
  $("#frmprestamo").validate({
      rules: {
        clave_empleado: "required",
        prestamo:"required",
        fecha: "required",

      },
      messages: {
          clave_empleado: "Seleccione a un empleado",
          prestamo: "Introduzca una cantidad",
          fecha: "Seleccione la fecha",

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
          $(element).parents(".col-sm-6").addClass("has-error").removeClass("has-success");
      },
      unhighlight: function(element, errorClass, validClass) {
          $(element).parents(".col-sm-6").addClass("has-success").removeClass("has-error");
      }
  });
  // ===============================================================
  //                        COMISION
  // ===============================================================
  $("#frmcomision").validate({
      rules: {
        fecha_inicion: "required",
        fecha_final:"required"

      },
      messages: {
          fecha_inicion: "Seleccione a una fecha de inicio",
          fecha_final: "Seleccione a una fecha de final"

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
          $(element).parents(".col-sm-6").addClass("has-error").removeClass("has-success");
      },
      unhighlight: function(element, errorClass, validClass) {
          $(element).parents(".col-sm-6").addClass("has-success").removeClass("has-error");
      }
  });
