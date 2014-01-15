$(document).ready(function() {
    $('.datepicker.autostart').datepicker({autoclose:true, language: "es", format:'yyyy-mm-dd'});
    //$('select.select2.with-filter').select2({allowClear:true, placeholder:'-'});
    //$('select.select2.without-filter').select2({allowClear:true, placeholder:'-', minimumResultsForSearch: -1});
    $('.switch').bootstrapSwitch();
});

var count = function(obj) {
    var cnt = 0;
    for(var x in obj)
        if(obj.hasOwnProperty(x))
            cnt++;
    return cnt;
}

function notif(msg) {
    if(typeof msg == 'string') msg = {type:'success', text:msg};
        $.jGrowl(msg.text, {
            theme: msg.type,
            life:6000,
            position: 'center',
            sticky: false,
            closeTemplate: '<i class="icon16 i-close-2"></i>',
            animateOpen: {
                width: 'show',
                height: 'show'
            }
        });
    
}

function myDataTable(selector, additionalOptions)
{
    var oLanguage = {
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "",//"<span>Mostrar _MENU_ registros</span>",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "<span>Buscar:</span> _INPUT_",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
    }

    var options = {
        "sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-6'p>>",
        "sPaginationType": "bootstrap",
        "bJQueryUI": false,
        "bAutoWidth": false,
        'iDisplayLength':20,
        'oLanguage': oLanguage,
    };

    if(additionalOptions) {
        for(var prop in additionalOptions) {
            options[prop] = additionalOptions[prop];
        }
    }
    $(selector).dataTable(options);
    $('.dataTables_length select').uniform();
    $('.dataTables_paginate > ul').addClass('pagination');
}