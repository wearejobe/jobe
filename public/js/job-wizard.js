jQuery(function($){
    $("[required]").change(function(e){ $(this).removeClass('required'); $(".alert-container .alert-required, .alert-hourly-wage").addClass('d-none'); });
    $('.btn-next, .step-item').click(function(e){
        var actionedButton = $(this);
        var pane = $('.tab-pane.active');
        var requiredFields = pane.find('[required]');
        var clear = true;
        requiredFields.each(function(index,element){
            if($(this).val()==''){
                actionedButton.removeClass('ready');
                $(this).addClass('required');
                $(".alert-container .alert-required").removeClass('d-none');
                clear = false;
                e.stopPropagation();
            }
        });
        

        if(clear){
            var nextStep = $(this).attr('data-next'),
                currentStep = parseInt(nextStep) - 1;

            $('.step-' + currentStep + '-indicator').addClass('done');
            $('.step-' + nextStep + '-indicator').addClass('ready');
            $('.btn-prev').removeClass('active');
            if(actionedButton.hasClass('step-item')){
                actionedButton.siblings().removeClass('active');
                $('.step-item.ready:not(.active)').addClass('done');
                actionedButton.addClass('ready');
            }
        }
    });
    $('.btn-prev').click(function(e){
        var prevStep = $(this).attr('data-prev'),
            currentStep = parseInt(prevStep) + 1;
        $('.step-' + prevStep + '-indicator').removeClass('done');
        $('.step-' + currentStep + '-indicator').removeClass('ready');
        $('.btn-next').removeClass('active');
    });
    $("#category").change(function(e){
        var category = $(this).val();
        getSkills(category);
    });
    $("input[name=budget_type]").change(function(e){
        var min =$("input[name=budget_type]:checked").attr("data-min");
        $('#hourly_wage').attr("min",min); //OK
    });
    $('#btn-add-deliverable').click(function (e) {
        var title = $("#deliverable-title").val();
        var value = $("#deliverable-value").val();
        if(checkDeliverablesValues(value)){
            if(title=="" || title.includes('|')==true){
                $("#deliverable-title").addClass('required').focus();
                return false;
            }else{
                $("#deliverable-title").removeClass('required');
            }
            var deliverableItem = '<div class="list-group-item list-group-item-action delivery-item d-flex justify-content-between align-items-center">';
                deliverableItem += title;
                deliverableItem += '<div class="list-group-prepend"><span class="badge badge-info">'+value+'%</span>';
                deliverableItem += '<a class="remove-item ml-3"><span class="material-icons">close</span></a></div>';
                deliverableItem += '<input type="hidden" name="deliverables[]" value="'+title+'|'+value+'" />';
                deliverableItem += '<input type="hidden" name="deliverable_value" value="'+ value +'" />';
                deliverableItem += '</div>';
                $("#deliverable-title").val('');
                $("#deliverable-value").val('10');
            $(".deliverable-items-container").append(deliverableItem);
        }else{
            alert('Cannot exceed the 100% of a project value.');
        }
    });
    $(document).on('click','.deliverable-items-container .delivery-item .remove-item',function(e){
        $(this).parents('.delivery-item').remove();
    });
    $(document).on('click','.deliverable-items-container .delivery-item .remove-item-db',function(e){
        e.preventDefault();
        var deliID = $(this).attr('data-id');
        var token = $(this).attr('data-token');
        var actionURL = $(this).attr('href');
        $.ajax({
            url: actionURL,
            type: 'post',
            dataType: 'json',
            data: { _token:token, deliID:deliID },
            success: function(response){
                if(response.status == 'success'){
                    $('.delivery-item-' + deliID).remove();
                }else{
                    alert('Error, please try later.');
                }
            }
        });
        
    });
    $('a[href="#deliverables"]').click(function (e) {
        var hourly_wage = $('#hourly_wage').val();
        var minwage = $('#hourly_wage').attr('min');
        if(parseInt(hourly_wage) < parseInt(minwage)){
            $(".alert-hourly-wage").removeClass('d-none');
            e.stopPropagation();
            return false;
        }
    });
    $('a[href="#review"]').on('shown.bs.tab', function (e) {
        $('input.form-control.reflect-this, textarea.form-control.reflect-this').each(function(index,element){
            var identifier = $(this).attr('name');
                value = $(this).val();
                valueHTML = value.replace(/\r\n|\r|\n/g,"<br />");
            $('.reflect-mirror.'+identifier).html(valueHTML);
        });
        $('input[type=radio].reflect-this:checked').each(function(index,element){
            var identifier = $(this).attr('name'),
                label = $(this).prev().text();
                labelText = label.trim();
            $('.reflect-mirror.'+identifier).html(labelText);
        });
        var skillsHTML = $('.skills-container').html();
        $('.skills-container-mirror').html(skillsHTML);
        
        $('select.form-control.reflect-this').each(function(index,element){
            var identifier = $(this).attr('name'),
            selectedOption = $(this).children('option:selected');
            $('.reflect-mirror.'+identifier).html(selectedOption.text());
        });
    });

    //load skills of current editing category
    var url = window.location.href;
    if(url.indexOf("edit-job") > -1){
        var catID = $("#category").val();
        getSkills(catID);
    }
    $("#btn-add-suggestion").click(function(e){
        e.preventDefault();
        var newSkill = $("#skills").val(),
            token = $(this).attr('data-token'),
            addSkillurl = $(this).attr('href'),
            catID = $("#category").val();
        if(newSkill!='' && catID !=''){
            $.ajax({
                url: addSkillurl,
                type: 'post',
                dataType: 'json',
                data: { _token: token, skillName: newSkill, catID: catID },
                success: function(response){
                    if(response.status == 'success'){
                        var actualItems = $('#skills_source').val();
                        tagID = response.skill.id;
                        tagName = response.skill.name;

                        //look for id in input skills 
                        var arrayItems = actualItems.split(',');
                        var tagIDposition = arrayItems.indexOf(tagID);
                        if(tagIDposition == -1){
                            $('#skills_source').val(actualItems + tagID + ',');
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
function checkDeliverablesValues(addingValue){
    var values = document.getElementsByName('deliverable_value');
    var totalValue = 0;
    var go = true;
    $.each(values,function(index,item){
        totalValue += parseInt(item.value);
    });
    totalValue += parseInt(addingValue);
    if(totalValue>100){
        go = false;
    }else{
        go = true;
    }

    return go;
}
function getSkills(category){
    var token = $("input[name=_token]").val();
    var apiURL = $("input[name=apiURL]").val();
    $.ajax({
        type:'post',
        url: apiURL,
        dataType: 'JSON',
        data: {'_token': token, category:category },
        success:function(data) {
            $( ".jobe-autocomplete-helper" ).autocomplete({
                source: data,
                minLength: 0,
                select: function( event, ui ) {
                    var actualItems = $('#skills_source').val();
                    tagID = ui.item.id;
                    tagName = ui.item.value;

                    //look for id in input skills 
                    var arrayItems = actualItems.split(',');
                    var tagIDposition = arrayItems.indexOf(tagID);
                    if(tagIDposition == -1){
                        $('#skills_source').val(actualItems + tagID + ',');
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
            $(document).on('click','.skills-container button.btn.tag-item',function(e){
                var actualItems = $('#skills_source').val();
                var dataID = $(this).attr("data-id");
                newItems = actualItems.replace(dataID + ',','');
                $('#skills_source').val(newItems);
                $(this).remove();
            });
        }
    });
    
}