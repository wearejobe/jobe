jQuery(function($){
    $('#tbl-requests').DataTable({
        "order": [[ 0, "desc" ]],
        
        "info":     false
    });
    $('.btn-procesar').click(function(e) {
        e.preventDefault();
        
        var transactionNumber = window.prompt('Type transaction number');
        if(transactionNumber){
            var requestID = $(this).attr('data-id');
            var token = $(this).attr('data-token');
            var actionurl = $(this).attr('href');
            $(this).addClass('loading');
            $.ajax({
                url: actionurl,
                type: 'post',
                dataType: 'json',
                data: { _token: token, transactionNumber:transactionNumber, requestID: requestID },
                success: function(response){
                    if(response.status == 'success'){
                        document.location.reload();
                    }else{
                        alert(response.msg);
                    }
                }
            });
        }
    });
});