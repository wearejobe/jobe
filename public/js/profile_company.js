jQuery(function($){
    timezone();
});
function timezone(){
    var token = $('input[name=_token]').val();
    var TimeZone = moment.tz.guess();
    $.ajax({
        type:'post',
        url:'../api/saveTimeZone',
        dataType: 'JSON',
        data: {'_token': token, timezone:TimeZone },
        success:function(data) {
            console.log(data);
        }
    });
}