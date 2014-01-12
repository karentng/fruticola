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