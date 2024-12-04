jQuery(function($){
    $('#frm-company-info').submit(function(e){
        e.preventDefault();
        $('#btn-to-company-details').addClass('disabled').attr('disabled',true);
        var formData = $(this).serialize();
        var formAction = $(this).attr('action');
        $.ajax({
            url: formAction,
            type: 'post',
            dataType: 'json',
            data: formData,
            success: function(res){
                console.log(res);
                $('.step-1-indicator').addClass('done');
                $('.step-2-indicator').addClass('ready');
                $('#company-info').removeClass('show active');
                $('#company-details').tab('show');
            }
        });
    });
    $('#frm-company-details').submit(function(e){
        e.preventDefault();
        $('#btn-continue').addClass('disabled loading').attr('disabled',true);
        $('.step-2-indicator').addClass('done');
        var formData = $(this).serialize();
        var formAction = $(this).attr('action');
        $.ajax({
            url: formAction,
            type: 'post',
            dataType: 'json',
            data: formData,
            success: function(res){
                if(res.success == 'true'){
                    document.location.href = 'account/profile';
                }
            }
        })
    });
});