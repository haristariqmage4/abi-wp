<?php
/*
* Template Name : Flat
* ----------------------------
* Author        : @magepeopleteam
* Copyright     : 2019 @ magepeopleteam
*/
if ( ! defined('ABSPATH')) exit;  // if direct access  

global $wbtm, $wpdb;
 
?>
<html><body>
<style type="text/css">
    <?php do_action('mep_pdf_style'); ?>
</style>
<?php
    $order_data         = $wbtm->get_order_data( $order_id, 'object' );
    $order              = wc_get_order( $order_id );
    foreach ( $order->get_items() as $item_id => $item_values ) {
        $item_id        = $item_id;
    }

    $extra_info_arr = wc_get_order_item_meta($item_id,'_event_service_info',true);
    $template_mode   = mep_get_option('mep_pdf_template_mood', 'mep_pdf_gen_settings', 'individual');
    $args   =   array(
        'posts_per_page'   => -1,
        'post_type'     => 'mep_events_attendees',
        'meta_query'    => array(
            array(
            'key'       => 'ea_order_id',
            'value'     => $order_id,
            'compare'   => '=',
            )
        )
    );
    $loop = new WP_Query($args);
            
    $i = 0; //counter
    $Tpost = $loop->post_count;
    $attendee = $loop->posts;

if($template_mode == 'single'){

    do_action('mep_pdf_template_single_body',$attendee,$order_id);
    
}else{
    foreach ($attendee as $_attendee) {
    $attendee_id = $_attendee->ID;
        do_action('mep_pdf_template_body',$attendee_id,$order_id);
    if ($i < $Tpost - 1) { 
        $i++; 
       ?>
   <div class="page_break"></div>
   <?php   
            
        }
    }
}

 $args = array(
    'post_type'         => 'mep_extra_service',
    'posts_per_page'    => -1,
    'meta_query' => array(
        array(
            'key'       => 'ea_extra_service_order',
            'value'     => $order_id,
            'compare'   => '='
        )
    ),
);
$loop = new WP_Query($args);
$event_extra_service = $loop->posts;
if(sizeof($event_extra_service) > 0){
    do_action('mep_extra_service_pdf_content',$event_extra_service,$order_id);
}