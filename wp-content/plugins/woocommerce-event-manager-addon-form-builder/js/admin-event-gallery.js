(function ($) {
    "use strict";

    $(document).on('click', '#mp_event_add_new_form', function () {
        let empty_form_tr = $('.mp_event_custom_form_hidden table tr').clone(true);
        empty_form_tr.insertAfter('.mp_event_custom_form_table tr:last-child');
    });

    $(document).on('click', '.mp_event_remove_this_row', function () {
        $(this).parents('tr').remove();
    });

    $(document).on('change', '.mp_event_custom_form_table [name="mep_fbc_filed_type[]"]', function () {
        let value=$(this).val();
        if(value==='select' || value==='radio' || value==='checkbox'){
            $(this).parents('label').siblings('.mp_event_drop_list').slideDown(250);
        }else{
            $(this).parents('label').siblings('.mp_event_drop_list').slideUp(250);
        }

    });



    jQuery(document).ready(function ($) {
        var reg_form = jQuery('#mep_event_reg_form_list').val(); 
        if(reg_form > 0){    
        jQuery('.mp_tab_itemss').hide();
        }else if(reg_form == 'custom_form'){
            jQuery('.mp_tab_itemss').show();
        }
    });

    $(document).on('change', '#mep_event_reg_form_list', function () {
        let reg_form = jQuery(this).val();  
        if(reg_form > 0 || reg_form == '' ){    
                jQuery('.mp_tab_itemss').hide(1000);
            }else if(reg_form == 'custom_form'){
                jQuery('.mp_tab_itemss').show(1000);
            }

    });

}(jQuery));