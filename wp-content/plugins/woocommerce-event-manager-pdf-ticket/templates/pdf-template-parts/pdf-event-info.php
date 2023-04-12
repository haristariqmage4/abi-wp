<?php
add_action('mep_pdf_event_name','mep_display_pdf_event_name',10,4);
function mep_display_pdf_event_name($ticket_id,$event_id='',$order_id='',$ticket_type=''){
    $template_mode   = mep_get_option('mep_pdf_template_mood', 'mep_pdf_gen_settings', 'individual');
    echo $template_mode == 'single' ? get_the_title($event_id) : get_the_title(get_post_meta($ticket_id,'ea_event_id',true));
}

add_action('mep_pdf_org_name','mep_display_pdf_event_org_name',10,4);
function mep_display_pdf_event_org_name($ticket_id,$event_id='',$order_id='',$ticket_type=''){
    $template_mode      = mep_get_option('mep_pdf_template_mood', 'mep_pdf_gen_settings', 'individual');
    $event_id           = $template_mode == 'single' ? $event_id :get_post_meta($ticket_id,'ea_event_id',true);
    
    $org_arr = get_the_terms( $event_id, 'mep_org' );
    if(is_array($org_arr)){
    echo $org_arr[0]->name;
    }
}


add_action('mep_pdf_start_date','mep_display_pdf_event_start_date',10,4);
function mep_display_pdf_event_start_date($ticket_id,$event_id='',$order_id='',$ticket_type=''){
$template_mode      = mep_get_option('mep_pdf_template_mood', 'mep_pdf_gen_settings', 'individual');
$event_id           = $template_mode == 'single' ? $event_id : get_post_meta($ticket_id,'ea_event_id',true);
$values             = $template_mode == 'single' ? array() : get_post_custom($ticket_id);
$ea_event_date      = isset($values['ea_event_date'][0]) && !empty($values['ea_event_date'][0]) && $template_mode != 'single' ? $values['ea_event_date'][0] : mep_pdf_theme_single_get_date($order_id,$event_id,$ticket_type);
echo get_mep_datetime($ea_event_date, 'date-text');
}


add_action('mep_pdf_start_time','mep_display_pdf_event_start_time',10,4);
function mep_display_pdf_event_start_time($ticket_id,$event_id='',$order_id='',$ticket_type=''){
    $template_mode      = mep_get_option('mep_pdf_template_mood', 'mep_pdf_gen_settings', 'individual');
    $event_id           = $template_mode == 'single' ? $event_id : get_post_meta($ticket_id,'ea_event_id',true);
    $values             = $template_mode == 'single' ? array() : get_post_custom($ticket_id);
    $ea_event_date      = isset($values['ea_event_date'][0]) && !empty($values['ea_event_date'][0]) && $template_mode != 'single' ? $values['ea_event_date'][0] : mep_pdf_theme_single_get_date($order_id,$event_id,$ticket_type);
echo get_mep_datetime($ea_event_date, 'time');
}



add_action('mep_pdf_event_multidate','mep_display_pdf_event_moredate',10,4);
function mep_display_pdf_event_moredate($ticket_id,$event_id='',$order_id='',$ticket_type=''){
    global $post;

    $template_mode      = mep_get_option('mep_pdf_template_mood', 'mep_pdf_gen_settings', 'individual');
    $event_id           = $template_mode == 'single' ? $event_id : get_post_meta($ticket_id,'ea_event_id',true);
    $recurring          = get_post_meta($event_id, 'mep_enable_recurring', true) ? get_post_meta($event_id, 'mep_enable_recurring', true) : 'no';
    $event_more_date    = get_post_meta($event_id,'mep_event_more_date',true);
          if(is_array($event_more_date) && sizeof($event_more_date) > 0 && $recurring == 'no'){ ?>
            <li><strong><?php _e('More Date & Time:','mage-eventpress-pdf'); ?></strong> <ul> <?php foreach($event_more_date as $more_date){
            ?>
            <li style='font-size:12px'><?php echo get_mep_datetime($more_date['event_more_start_date'].' '.$more_date['event_more_start_time'], 'date-time-text');  ?> - <?php if($more_date['event_more_start_date'] != $more_date['event_more_end_date']){ echo get_mep_datetime($more_date['event_more_end_date'].' '.$more_date['event_more_end_time'], 'date-time-text'); }else{ echo get_mep_datetime($more_date['event_more_end_time'], 'time'); }  ?></li>
            <?php
            } ?></ul></li>
            <?php } 
}


add_action('mep_pdf_event_location','mep_display_pdf_event_location',10,4);
function mep_display_pdf_event_location($ticket_id,$event_id='',$order_id='',$ticket_type=''){
ob_start();
    $template_mode      = mep_get_option('mep_pdf_template_mood', 'mep_pdf_gen_settings', 'individual');
    $event_id           = $template_mode == 'single' ? $event_id : get_post_meta($ticket_id,'ea_event_id',true);
    $event_meta         = get_post_custom($event_id); 
    do_action('mep_event_location_ticket',$event_id,$event_meta);
 echo ob_get_clean();   
}


add_action('mep_pdf_event_order_id','mep_display_pdf_event_order_id',10,4);
function mep_display_pdf_event_order_id($ticket_id,$event_id='',$order_id='',$ticket_type=''){    
    $template_mode      = mep_get_option('mep_pdf_template_mood', 'mep_pdf_gen_settings', 'individual');
    $order_id           = $template_mode == 'single' ? $order_id : get_post_meta($ticket_id,'ea_order_id',true);
    echo $order_id;
}

add_action('mep_pdf_event_ticket_no','mep_display_pdf_event_ticket_id',10,4);
function mep_display_pdf_event_ticket_id($ticket_id,$event_id='',$order_id='',$ticket_type=''){
    $template_mode          = mep_get_option('mep_pdf_template_mood', 'mep_pdf_gen_settings', 'individual');
    if($template_mode == 'single'){
        echo mep_pdf_get_single_ticket_number($order_id,$event_id,$ticket_type);
    }else{
        $event_meta         = get_post_custom($ticket_id); 
        $ticket             = $event_meta['ea_user_id'][0].$event_meta['ea_order_id'][0].$event_meta['ea_event_id'][0].$ticket_id;    
        echo $ticket;
    }
}


add_action('mep_pdf_event_ticket_price','mep_display_pdf_event_ticket_price',10,4);
function mep_display_pdf_event_ticket_price($ticket_id,$event_id='',$order_id='',$ticket_type=''){
    $template_mode      = mep_get_option('mep_pdf_template_mood', 'mep_pdf_gen_settings', 'individual');
    if($template_mode == 'single'){
        echo mep_pdf_get_single_ticket_price($order_id,$event_id,$ticket_type);
    }else{
        $event_meta         = get_post_custom($ticket_id); 
        mep_get_event_ticket_price($event_meta['ea_event_id'][0],$event_meta['ea_ticket_type'][0]);
    }
}


add_action('mep_pdf_event_ticket_type','mep_display_pdf_event_ticket_type',10,4);
function mep_display_pdf_event_ticket_type($ticket_id,$event_id='',$order_id='',$ticket_type=''){
    $template_mode      = mep_get_option('mep_pdf_template_mood', 'mep_pdf_gen_settings', 'individual');
    echo $template_mode == 'single' ? $ticket_type : get_post_meta($ticket_id,'ea_ticket_type',true);    
}