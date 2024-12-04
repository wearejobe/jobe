jQuery(function($){
    $('#tbl-transfers').DataTable({
        "order": [[ 0, "desc" ]],
        
        "info":     false
    });
    $('.btn-validate').click(function(e) {
        e.preventDefault();
        $(this).addClass('loading');
        var transferID = $(this).attr('data-id');
        var token = $(this).attr('data-token');
        var actionurl = $(this).attr('href');
        $.ajax({
            url: actionurl,
            type: 'post',
            dataType: 'json',
            data: { _token: token, transferID: transferID },
            success: function(response){
                if(response.status == 'success'){
                    document.location.reload();
                }else{
                    alert(response.msg);
                }
            }
        });
    });
});