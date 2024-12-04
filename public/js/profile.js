
jQuery(function($){
    timezone()
    if(firstTime){
        var modalInstance = $('#slide-first-time').modal('show');
        $('body').addClass("fist-time-modal-open");
        modalInstance.on('hide.bs.modal', function (e) {
            setTimeout(function(e){
                $('body').removeClass("fist-time-modal-open");
            },1500);
        });
        var first_time_swiper = new Swiper('.sw-first-time-container',{
            effect: 'fade', 
            observer: true,
            observeParents: true,
            observeSlideChildren: true,
            navigation: {
                nextEl: '.swiper-button-next', 
                prevEl: '.swiper-button-prev',
            }
        });
    }  
    // Javascript to enable link to tab
    var url = document.location.toString();
    if (url.match('#')) {
        $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
    } 

    // Change hash for page-reload
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
        var page_url = url.split('#')[0];
        $("input#return_url").val(page_url + e.target.hash);
    })

    $('#phone').inputmask("(999) 9{7,8}"); 
    $('[data-toggle="tooltip"]').tooltip();


    $("#btn-add-suggestion").click(function(e){
        e.preventDefault();
        var newSkill = $("#skills").val(),
            token = $(this).attr('data-token'),
            addSkillurl = $(this).attr('href'),
            catID = $("#category").val();
        console.log(catID);
        if(newSkill!='' && catID !=''){
            $.ajax({
                url: addSkillurl,
                type: 'post',
                dataType: 'json',
                data: { _token: token, skillName: newSkill, catID: catID },
                success: function(response){
                    if(response.status == 'success'){
                        var actualItems = $('#skills-source').val();
                        tagID = response.skill.id;
                        tagName = response.skill.name;

                        //look for id in input skills 
                        var arrayItems = actualItems.split(',');
                        var tagIDposition = arrayItems.indexOf(tagID);
                        if(tagIDposition == -1){
                            $('#skills-source').val(actualItems + tagID + ',');
                            $('.skills-container').append('<button data-id="'+tagID+'" type="button" class="btn btn-sm btn-outline-info tag-item">'+tagName+'<span class="material-icons ml-1">highlight_off</span></button>');
                            $("#skills").val(''); 
                            $(".add-suggestion-container").addClass('d-none');
                            return false;
                        }
                    }else{
                        alert(response.msg);
                    }
                }
            });
        }else{
            alert('Skill and Category are required');
        }
    });
    
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
function getSkills(token,category){
    $.ajax({
        type:'post',
        url:'../api/getSkills',
        dataType: 'JSON',
        data: {'_token': token, category:category },
        success:function(data) {
            //jobe_autocomplete('skills-source',data);
            $( ".jobe-autocomplete-helper" ).autocomplete({
                source: data,
                minLength: 2,
                select: function( event, ui ) {
                    var actualItems = $('#skills-source').val();
                    tagID = ui.item.id;
                    tagName = ui.item.value;

                    //look for id in input skills 
                    var arrayItems = actualItems.split(',');
                    var tagIDposition = arrayItems.indexOf(tagID);
                    if(tagIDposition == -1){
                        $('#skills-source').val(actualItems + tagID + ',');
                        $('.skills-container').append('<button data-id="'+tagID+'" type="button" class="btn btn-sm btn-outline-info tag-item">'+tagName+'<span class="material-icons ml-1">highlight_off</span></button>');
                        $(this).val(''); return false;
                    }
                },
                response: function( event, ui ) {
                    var numResults = Object.keys(ui.content).length;
                    if(numResults == 0){
                        $('.add-suggestion-container').removeClass('d-none');
                    }else{
                        $('.add-suggestion-container').addClass('d-none');
                    }
                }
            });
            $(document).on('click','button.btn.tag-item',function(e){
                var actualItems = $('#skills-source').val();
                var dataID = $(this).attr("data-id");
                newItems = actualItems.replace(dataID + ',','');
                $('#skills-source').val(newItems);
                $(this).remove();
            });
        }
    });
}