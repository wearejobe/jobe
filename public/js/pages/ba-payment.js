jQuery(function($){
    $('#btn-register-transaction').click(function(e) {
        var tname = $('[name=transaction_name]').val();
        var tnumber = $('[name=transaction_number]').val();
        var tdate = $('[name=transaction_date]').val();
        if(tname != '' || tnumber!='' || tdate !=''){
            $("#frm-transaction-pay").submit();
        }else{
            $('.text-required').removeClass('d-none')
            return false;
        }
    });
    $('.datepicker').datepicker({
        showOn: "button",
        dateFormat: "yy-mm-dd",
        buttonText: "<i class='fa fa-calendar'></i>"
      });
});
