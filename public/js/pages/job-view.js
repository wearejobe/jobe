jQuery(function($){
    $('.job-tab-item').click(function(){
        $('.job-tab-item').removeClass('active');
        //$(this).addClass('active');
    });
    var applied = false;
    
    
    $('#mdl-apply').on('shown.bs.modal', function () {
        var uploadsURL = $('#uploads-folder').val();
        var token = $('input[name=_token]').val();
        var dzone = $("#apply-files").dropzone({ 
            url: uploadsURL,
            createImageThumbnails: false,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': token
            },
            success: function(file){
                var jsonFile = JSON.parse(file.xhr.response);
                var fileID = jsonFile.id;
                $('#apply-uploaded-files').append('<input type="hidden" name="uploadedFiles[]" value="'+fileID+'" />');
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
          
    });
    $('#frm-apply').submit(function(e){
        e.preventDefault();
        $('#btn-apply').addClass('disabled loading').attr('disabled',true);
        var FormData = $(this).serialize();
        var formAction = $(this).attr('action');
        $.ajax({
            url: formAction,
            type: 'post',
            dataType: 'json',
            data: FormData,
            success: function(res){
                if(res.success == 'true'){
                    $('.apply-form-content').addClass('d-none');
                    $('.applied-content').removeClass('d-none');
                    $('#btn-apply-mdl').addClass('d-none');
                    $('.apply-container').html('<div class="alert-info mb-3"><div class="p-2 text-center text-white"><h3><span class="material-icons">info</span></h3>You applied for this job.</div></div>');
                }else{
                    alert(res);
                }
            }
        });
    });
});