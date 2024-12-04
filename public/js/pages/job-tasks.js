jQuery(function($){
    //TIMER 
    var workingTimer = $('.timer-working').text();
    var TaskTime = workingTimer.split(':');
    
    var timer = new easytimer.Timer({ startValues: { 'hours': parseInt(TaskTime[0]), 'minutes': parseInt(TaskTime[1]), 'seconds': parseInt(TaskTime[2]) } });
    timer.start();
    timer.addEventListener('secondsUpdated', function (e) {
        
        /* if(parseInt(TaskTime[0]) >= 24 ){
            var timeStr = timer.getTimeValues().toString();
            timeStr = TaskTime[0] + timeStr.substring(2,8);
            $('.timer-working').html( timeStr );
        }else{ */
            var days = timer.getTimeValues().days;
            var dayhours = timer.getTimeValues().hours;
            var hoursperday = parseInt(days) * 24;
            var hours = hoursperday + dayhours;
                hours = (hours<10) ? '0' + hours:hours;
            var minutes = timer.getTimeValues().minutes;
                minutes = (minutes<10) ? '0' + minutes:minutes;
            var seconds = timer.getTimeValues().seconds;
                seconds = (seconds<10) ? '0' + seconds:seconds;
            $('.timer-working').html(hours +':'+minutes+':'+seconds);
        //}
    });
    //END TIMER
    $( ".datepicker" ).datepicker({
        showOn: "button",
        dateFormat: "yy-mm-dd",
        buttonText: "<i class='fa fa-calendar'></i>"
    });
    $('.btn-delete-task').click(function(e){
      e.preventDefault();
      if(window.confirm('Sure to delete this task?')){     
        var deleteURL = $(this).attr('href');
        $.ajax({
          url: deleteURL,
          type: 'get',
          dataType: 'json',
          success: function(res){
              if(res.status == 'success'){
                  window.location.reload();
              }else{
                  alert(res);
              }
          }
        });
      }else{ return false; }
    });
    $('.btn-pause, .btn-done').click(function(e){
        e.preventDefault();
        $(this).addClass('loading');
        var token = $(this).attr('data-token');
        var pauseURL = $(this).attr('data-service');
        var isDone = ($(this).hasClass('btn-done')) ? 'true':'false';
        $.ajax({
        url: pauseURL,
        type: 'post',
        dataType: 'json',
        data: { _token: token, finishTask: isDone },
        success: function(res){
            if(res.status == 'success'){
                window.location.reload();
            }else{
                alert(res);
            }
        }
        });
        
    });
    $('#frm-new-task').submit(function(e){
        e.preventDefault();
        $('#btn-new-task').addClass('disabled loading').attr('disabled',true);
        var FormData = $(this).serialize();
        var formAction = $(this).attr('action');
        $.ajax({
            url: formAction,
            type: 'post',
            dataType: 'json',
            data: FormData,
            success: function(res){
                if(res.status == 'success'){
                    window.location.reload();
                }else{
                    alert(res);
                }
            }
        });
    });
    $('.btn-start-interval, .btn-continue').click(function(e){
        e.preventDefault();
        $(this).addClass('loading');
        var taskID = $(this).attr('data-tid');
        var jobID = $(this).attr('data-jid');
        var ajax_url = $(this).attr('data-service');
        var token = $(this).attr('data-token');
        $.ajax({
            url: ajax_url,
            type: 'post',
            dataType: 'json',
            data: { _token: token, tid: taskID, jid: jobID },
            success: function(res){
                if(res.status == 'success'){
                    window.location.reload();
                }else{
                    alert(res.alert);
                }
            }
        });
    });
});