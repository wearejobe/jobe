jQuery(function($){
    $('#frm-hiring').submit(function(e){
        e.preventDefault();
        $('#btn-hire').addClass('disabled loading').attr('disabled',true);
        var FormData = $(this).serialize();
        var formAction = $(this).attr('action');
        $.ajax({
            url: formAction,
            type: 'post',
            dataType: 'json',
            data: FormData,
            success: function(res){
                if(res.success == 'true'){
                    $('#frm-hiring').addClass('d-none');
                    $('.hired-content').removeClass('d-none');
                }else{
                    alert(res);
                }
            }
        });
    });
    $('#mdl-hire').on('shown.bs.modal', function () {
        var uploadsURL = $('#uploads-folder').val();
        var token = $('input[name=_token]').val();
        var dzone = $("#hiring-files").dropzone({ 
            url: uploadsURL,
            createImageThumbnails: false,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': token
            },
            success: function(file){
                var jsonFile = JSON.parse(file.xhr.response);
                var fileID = jsonFile.id;
                $('#hiring-uploaded-files').append('<input type="hidden" name="uploadedFiles[]" value="'+fileID+'" />');
            },
            removedfile: function(file){
                var jsonFile = JSON.parse(file.xhr.response);
                var token = $('input[name=_token]').val();
                var fileID = jsonFile.id;
                var rmURL = $('#rm-file-url').val();

                $('input[value="'+fileID+'"]').remove();
                $.ajax({ 
                    url: rmURL, 
                    type: 'post',
                    dataType: 'json',
                    data: { _token:token  ,id: fileID }, 
                    success: function(resp){
                        file.previewElement.remove();
                    }
                });
            }
        }).addClass('dropzone'); 
          
    })
});