function vhire(id){
    var getHireURL = $('#getHireURL').val();
    var token = $('input[name=_token]').val();
    $.ajax({
        url: getHireURL,
        type: 'post',
        dataType: 'json',
        data: { _token: token, id: id },
        success: function(response){
            
            $('#mdl-hire-pj').on('show.bs.modal', function (event) {
                var modal = $(this)
                modal.find('#frm-accept-hire').attr('action',response.frmAction);
                modal.find('.company_name').text(response.company.name);
                modal.find('.company_contact').text(response.company_contact);
                modal.find('.hire-message').html(nl2br(response.hiring.description));
                modal.find('.modal-body #hire-content .hire-files').html('');
                $.each(response.attachments,function(index,file){
                    fileButton = '<div><a class="badge badge-dark" href="'+file.link+'" target="_blank"><span class="material-icons md-16 mr-2">attachment</span> '+file.filename+'</a></div>';
                    modal.find('.modal-body #hire-content .hire-files').append(fileButton);
                });
            });
            $('#mdl-hire-pj').modal('show');
        }
    });
    
}
jQuery(function($){
    
});