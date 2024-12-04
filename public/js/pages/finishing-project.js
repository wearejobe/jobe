jQuery(function($){
    $('.btn-toggle-contracted').click(function(e){
        var targetElement = $(this).attr('data-target');
        $('#'+targetElement).toggleClass('contracted');
        $(this).children('.more').toggleClass('d-none')
        $(this).children('.less').toggleClass('d-none')
    });
   
    $('.rating-star').click(function(e){
        var beforeSibligns = $(this).prevAll();
        var nextSibligns = $(this).nextAll();
        var rating = parseInt($(this).index()) +1;
        beforeSibligns.addClass('active');
        nextSibligns.removeClass('active');
        $(this).addClass('active');

        

        $('#rating').val(rating);
    });
   
})