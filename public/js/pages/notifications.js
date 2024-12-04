jQuery(function($){
    $("a.notification").click(function(e){
        e.preventDefault();
        var readUrl = $(this).attr("data-read");
        var notiAction = $(this).attr("href");
        $.ajax({
            url :readUrl,
            type:'get' ,
            dataType: 'json', 
            success: function(response){
                if(response.success == 'true'){
                    document.location.href = notiAction;
                }else{
                    console.log(response);
                }
            },error: function(error){
                console.log(error);
            }

        });
    });
});