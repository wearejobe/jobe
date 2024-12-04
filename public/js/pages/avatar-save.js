jQuery(function($){
    var image_crop = $("#cropper-profile").croppie({
        enableExif: true,
        viewport:{
            width: 300, height: 300, type: 'square'
        },
        boundary: {
            width: 400, height: 400
        }
    });
    
    $("#image-selector").change(function(e){
        var reader = new FileReader();
        reader.onload = function(e){
            image_crop.croppie('bind',{
                url: event.target.result
            }).then(function(){
                console.log("jQuery bind complete");
            })
        }
        reader.readAsDataURL(this.files[0]);
        $(".image-selection").addClass('d-none');
        $(".image-cropper").removeClass('d-none');
    });
    $("#btn-change").click(function(e){
        $(".image-selection").removeClass('d-none');
        $(".image-cropper").addClass('d-none');
    });
    $("#btn-save-avatar").click(function(e){
        var uploadUrl = $('input[name=upload]').val();
        var urlSave = $('input[name=urlav]').val();
        var urlToken = $('input[name=_token]').val();
        image_crop.croppie('result',{
            type: 'canvas', 
            size: 'viewport'
        }).then(function(response){
            $.ajax({
            url: uploadUrl,
            method: 'post',
            data: { _token: urlToken, file: response },
            success: function(data){
                    if('id' in data){
                        $.ajax({
                            url: urlSave,
                            method: 'post',
                            data: { _token: urlToken, id: data.id },
                            success: function(data){
                                if(data.status == 'success'){
                                    document.location.reload();
                                }else{
                                    alert('Error was ocurred, please try later');
                                }
                            } 
                        });
                    }else{
                        alert('Error was ocurred, please try later');
                    }
            } 
            });
        })
    });
});