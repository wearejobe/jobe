jQuery.expr[':'].icontains = function(a, i, m) {
    return jQuery(a).text().toUpperCase()
        .indexOf(m[3].toUpperCase()) >= 0;
};
function jobe_autocomplete(element,source){
    var inputElement = $("#"+element);
    var parentItems = element +'_tags';
    var parentSelector = '#'+element +'_tags';
    
    var tagsHTML = '<div id="'+parentItems+'" class="autocomplete-tags-container"><div class="list-group">';
    var $elementHelper = inputElement.prev();
    tagsHTML += '</div></div>';
    tagsHTML += '<div class="autocomplete-tags-container-off" style="display:none;">';
    $.each(source, function(index, item){
        tagsHTML += '<a class="tag-item-helper atag-inlist list-group-item list-group-item-action" href="javascript:void(0)" data-id="'+item.id+'">'+item.name+'</a>';
    });
    tagsHTML += '</div>';
    inputElement.after(tagsHTML);

    $(document).on('click','a.tag-item-helper',function(){
        var actualItems = inputElement.val();
        tagID = $(this).attr('data-id');
        tagName = $(this).text();

        //look for id in input skills 
        var arrayItems = actualItems.split(',');
        var tagIDposition = arrayItems.indexOf(tagID);
        if(tagIDposition == -1){
            inputElement.val(actualItems + tagID + ',');
            $('.skills-container').append('<button data-id="'+tagID+'" type="button" class="btn btn-sm btn-outline-info tag-item">'+tagName+'<span class="material-icons ml-1">highlight_off</span></button>');
        }
        setTimeout(function(e){
            $('#'+ parentItems).removeClass('active');
        },100)
    }); 
    $(document).on('click','button.btn.tag-item',function(e){
        var actualItems = inputElement.val();
        var dataID = $(this).attr("data-id");
        newItems = actualItems.replace(dataID + ',','');
        inputElement.val(newItems);
        $(this).remove();
    }); 
    
    $elementHelper.on('focus',function(e){
        $('#' + parentItems).addClass('active');
        
    }).on('blur',function(e){
        setTimeout(function(e){
            $('#' + parentItems).removeClass('active');
        },100);
    });

    $elementHelper.on('input change',function(e){
        var keyword = $(this).val();
        $(".tag-item-helper").appendTo(".autocomplete-tags-container-off");
        $(".tag-item-helper:icontains("+keyword+")").appendTo(".autocomplete-tags-container .list-group");
    });


    
    var liSelected;
    $(window).keydown(function(e){
        var li = $('.tag-item-helper');
        if($('.autocomplete-tags-container').hasClass('active')){
            if(e.which === 40){
                if(liSelected){
                    liSelected.removeClass('selected');
                    next = liSelected.next();
                    if(next.length > 0){
                        liSelected = next.addClass('selected');
                    }else{
                        liSelected = li.eq(0).addClass('selected');
                    }
                }else{
                    liSelected = li.eq(0).addClass('selected');
                }
            }else if(e.which === 38){
                if(liSelected){
                    liSelected.removeClass('selected');
                    next = liSelected.prev();
                    if(next.length > 0){
                        liSelected = next.addClass('selected');
                    }else{
                        liSelected = li.last().addClass('selected');
                    }
                }else{
                    liSelected = li.last().addClass('selected');
                }
            }else if(e.which === 13){
                liSelected.click();
            }
        }
    });
}