jQuery(function($){
    $('#finish-job').click(function(e){
        if(window.confirm('Are you sure to finish this job/project? This process cannot be undone.')){
            $('#frm-finish-job').submit();
        }else{
            return false;
        }
    });
    $('.btn-change-event-date').click(function(e){
        e.preventDefault();
        var btn = $(this);
        var parent = $(this).parents('.list-group-item');
        var hrefUrl = $(this).attr('href');
        var eid = btn.attr('data-id');
        var token = btn.attr('data-token');
        btn.find('.new-event-date').datepicker('dialog',null,function(date){
            $.ajax({
                url: hrefUrl,
                type: 'post',
                dataType: 'json',
                data: { _token: token, newdate: date, eid: eid },
                success: function(response){
                    if(response.status == 'success'){
                        parent.find('.date-label').text(response.newDate);
                    }else{
                        console.log(response);
                    }
                }
            });
        });
    });
    $('.btn-done-event').click(function(e){
        e.preventDefault();
        var btn = $(this);
        var parent = $(this).parents('.list-group-item');
        var hrefUrl = $(this).attr('href');
        var eid = btn.attr('data-id');
        var token = btn.attr('data-token');
        $.ajax({
            url: hrefUrl,
            type: 'post',
            dataType: 'json',
            data: { _token: token, eid: eid },
            success: function(response){
                if(response.status == 'success'){
                    $('.evt-item-' + response.eventID).hide();
                    if(response.update_meeting){
                        $('.stage-cont-explore-meeting').removeClass('text-dark').addClass('text-success');
                        $('.stage-cont-explore-meeting').find('.material-icons').text('check');
                        $('.stage-cont-explore-meeting').find('.badge').removeClass('badge-dark').addClass('badge-success');
                    }
                    setInterval(() => {
                        $('.evt-item-' + response.eventID).remove();
                        
                    }, 500);
                }else{
                    console.log(response);
                }
            }
        });
    });

});