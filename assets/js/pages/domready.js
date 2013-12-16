$(document).ready(function() {
    $('.datepicker.autostart').datepicker({autoclose:true, format:'yyyy-mm-dd'});
    $('select.select2.autostart').select2({allowClear:true, placeholder:'-'});
    //$('select').not('.select2.autostart').select2(/*{minimumResultsForSearch: -1}*/);
});