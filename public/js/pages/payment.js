jQuery(function($){
    $('#mdl-payment').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) 
        var pid = button.data('pid') 
        var currency = button.data('currency');
        var subtotal = button.data('subtotal');
        var variations = button.data('variations');
        var total = button.data('total');
        var variationItems = '';

        
        variationItems +='<label class="d-block">Service Fee:<b class="service-fee float-right">'+currency+variations.service_fee+'</b></label>'; 
        

        var modal = $(this)
        modal.find('.subtotal').text(subtotal);
        modal.find('.variations').html(variationItems);
        modal.find('.total').text(total);
        
        modal.find('#pid').val(pid);
    });
});
