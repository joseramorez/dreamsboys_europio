// tabla general
$(document).ready(function() {
    $('#tabla').DataTable({
        responsive: true,
        fixedHeader: true,
        // keys: true,
        "order": [],
        "language": {
            "url": "/static/json/Spanish.json"
        }
    });
    // usuarios listar
    $('#usertabla').DataTable({
        responsive: true,
        fixedHeader: true,
        // keys: true,
        "order": [],
        "language": {
            "url": "/static/json/Spanish.json"
        }
    });

    // categoria
    $('#tablacategoria').DataTable({
        responsive: true,
        fixedHeader: true,
        // keys: true,
        "order": [],
        "language": {
            "url": "/static/json/Spanish.json"
        }
    });

    var handleDataTableButtons = function() {
        if ($("#tablaunica").length) {
            $("#tablaunica").DataTable({
                dom: "Bfrtip",
                buttons: [{
                        extend: "copy",
                        className: "btn-sm"
                    },
                    {
                        extend: "csv",
                        className: "btn-sm"
                    },
                    {
                        extend: "excel",
                        className: "btn-sm"
                    },
                    {
                        extend: "pdfHtml5",
                        className: "btn-sm"
                    },
                    {
                        extend: "print",
                        className: "btn-sm"
                    },
                    {
                        extend: "colvis",
                        className: "btn-sm"
                    },
                ],
                responsive: true,
                language: {
                    url: "/static/json/Spanish.json"
                }
            });
        }
    };

    TableManageButtons = function() {
        "use strict";
        return {
            init: function() {
                handleDataTableButtons();
            }
        };
    }();




    var $datatable = $('#datatable-checkbox');

    $datatable.dataTable({
        'order': [
            [1, 'asc']
        ],
        'columnDefs': [{
            orderable: false,
            targets: [0]
        }]
    });
    $datatable.on('draw.dt', function() {
        $('input').iCheck({
            checkboxClass: 'icheckbox_flat-green'
        });
    });

    TableManageButtons.init();
});
