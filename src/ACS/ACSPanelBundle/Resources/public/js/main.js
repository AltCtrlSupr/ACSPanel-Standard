
$(document).ready(function(){
    $('#sidebar > ul > li span').click(function(){
        //alert('it works');
        if($(this).next('ul')){
            $(this).next('ul').slideToggle();
        }
    });

    $('a.show_attributes_button').each(function(){
        $(this).click(function(){
            if($(this).next('ul.plan_attributes')){
                $(this).next('ul.plan_attributes').slideToggle();
            }
        });
    });

    // TODO: Look for a better place to define
/*    $('#acs_acspanelbundle_fosusertype_plans').multiselect({*/
        //selected: function(){
            //alert('some item chenged');
        //}
    /*});*/

    // TODO: Just to test, remove
    //$('a[title]').qtip();

    // Search results highlight
    if(typeof(hls_query) != 'undefined'){
      $("table.records_list tr td").highlight(hls_query);
    }

    // TODO: Load only when desktop versions
    /*$(document).scroll(function(){
        if($(this).scrollTop() > 110){
            $('#sidebar').css('position', 'fixed');
            $('#sidebar').css('top', '0px');
            $('#top-border').parent().show();
            $('#top-border').css('position', 'fixed');

        }else{
            $('#sidebar').css('position', '');
            $('#top-border').css('position', '');
            $('#top-border').parent().hide();
        }
    });*/


});

