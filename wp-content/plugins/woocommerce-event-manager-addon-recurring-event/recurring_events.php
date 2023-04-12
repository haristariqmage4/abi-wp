<?php
/**
* Plugin Name: Woocommerce Event Manager Addon: Recurring Events
* Plugin URI: http://mage-people.com
* Description: This plugin will add Recurring Events Feature into Woocommerce Event Manager plugin.
* Version: 2.2.2
* Author: MagePeople Team
* Author URI: http://www.mage-people.com/
* Text Domain: mage-eventpress-re
* Domain Path: /languages/
*/

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && is_plugin_active( 'mage-eventpress/woocommerce-event-press.php' )) {

  require_once(dirname(__FILE__) . "/inc/plugin-updates/plugin-update-checker.php");

  $ExampleUpdateChecker = PucFactory::buildUpdateChecker(
      'https://vaincode.com/update/event/recurring-event/recurring-event.json',
      __FILE__
  );
require_once(dirname(__FILE__) . "/inc/file_include.php");




add_filter('mep_event_total_seat_count','mep_update_total_seat_count',10,2);
function mep_update_total_seat_count($total,$event_id){
  $status = get_post_meta($event_id,'mep_enable_recurring',true) ? get_post_meta($event_id,'mep_enable_recurring',true) : 'normal';

  if($status == 'yes' || $status == 'everyday'){
    $mdate = get_post_meta($event_id,'mep_event_more_date',true) ? get_post_meta($event_id,'mep_event_more_date',true) : array();
     $event_more_dates     = count($mdate)+1;
    
    
  $total_seat = mep_event_total_seat($event_id,'total') * $event_more_dates;
  $total_resv = mep_event_total_seat($event_id,'resv') * $event_more_dates;
  $total_sold = mep_ticket_sold($event_id) * $event_more_dates;
  $total_left = $total_seat - ($total_sold + $total_resv);
  $total_seat = mep_event_total_seat($event_id,'total');
  
  
  return $total_seat; 
  }else{
    return $total;
  }
    
}




add_action('mep_after_date_section','show_recurring_box');
function show_recurring_box($post_id){
$status = get_post_meta($post_id,'mep_enable_recurring',true);
$periods = get_post_meta($post_id,'mep_repeated_periods',true);
$mep_show_upcoming_event = get_post_meta($post_id,'mep_show_upcoming_event',true);
?>
<div class="show_rec_checkbox">
<label for="mep_normal_event">
  <input id='mep_normal_event' type="radio" name='mep_enable_recurring' value='no' <?php if($status=='no'){ echo 'Checked'; } ?> /> <?php _e('Normal Event','mage-eventpress-recurring'); ?>
</label>
<label for="mep_everyday_event">
  <input id='mep_everyday_event' type="radio" name='mep_enable_recurring' value='everyday' <?php if($status=='everyday'){ echo 'Checked'; } ?> /> <?php _e('Repeated Event?','mage-eventpress-recurring'); ?>
</label>

<span id='mep_repeated_periods_sec'>
<label for="mep_repeated_periods">
<?php _e('Repeated After ','mage-eventpress-recurring'); ?> <input style='width:60px' id='mep_repeated_periods' type="number" name='mep_repeated_periods' value='<?php echo $periods; ?>' /><?php _e(' Days','mage-eventpress-re'); ?> </label>
</span>

<label for="mep_recurring_event">
  <input id='mep_recurring_event' type="radio" name='mep_enable_recurring' value='yes' <?php if($status=='yes'){ echo 'Checked'; } ?> /> <?php _e('Recurring Event of date listed above?','mage-eventpress-recurring'); ?>
</label>

</div>

<div class="show_rec_checkbox" id='show_rec_checkbox'>
<label for="mep_show_upcoming_event">
  <input id='mep_show_upcoming_event' type="checkbox" name='mep_show_upcoming_event' value='yes' <?php if($mep_show_upcoming_event=='yes'){ echo 'Checked'; } ?> /> <?php _e('Show Only Upcoming Event?','mage-eventpress-recurring'); ?>
</label>
</div>
<script>
jQuery(document).ready(function ($) {
  
  <?php 
  if($status=='everyday'){
    ?>
    jQuery('#mep_repeated_periods_sec').show();
    jQuery('#mp_event_all_info_in_tab [data-tab-item="#mp_event_time"] .wrap.ppof-settings.ppof-metabox').show();
    <?php
  }else{
    ?>
jQuery('#mep_repeated_periods_sec').hide();
jQuery('#mp_event_all_info_in_tab [data-tab-item="#mp_event_time"] .wrap.ppof-settings.ppof-metabox').hide();
    <?php
  }
  ?>

  <?php 
  if($status=='yes'){
    ?>
    jQuery('#show_rec_checkbox').show();
    <?php
  }else{
    ?>
jQuery('#show_rec_checkbox').hide();
    <?php
  }
  ?>

jQuery('input[name="mep_enable_recurring"]').click(function () {
        if (jQuery(this).attr("value") == "everyday") {
          jQuery('#mep_repeated_periods_sec').show();
          jQuery('#mp_event_all_info_in_tab [data-tab-item="#mp_event_time"] .wrap.ppof-settings.ppof-metabox').show();
        }else{
          jQuery('#mep_repeated_periods_sec').hide();
          jQuery('#mp_event_all_info_in_tab [data-tab-item="#mp_event_time"] .wrap.ppof-settings.ppof-metabox').hide();
        }
    });

jQuery('input[name="mep_enable_recurring"]').click(function () {
        if (jQuery(this).attr("value") == "yes") {
          jQuery('#show_rec_checkbox').show();
        }else{
          jQuery('#show_rec_checkbox').hide();
        }
    });






});
</script>
<?php
}

add_action('save_post', 'mep_recurring_events_meta_save');
function mep_recurring_events_meta_save($post_id) {
  if ( ! isset( $_POST['mep_event_ticket_type_nonce'] ) ||
  ! wp_verify_nonce( $_POST['mep_event_ticket_type_nonce'], 'mep_event_ticket_type_nonce' ) )
    return;
  
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
    return;
  
  if (!current_user_can('edit_post', $post_id))
    return;
  
  if (get_post_type($post_id) == 'mep_events') {   
      $recurring_status = isset($_POST['mep_enable_recurring']) ? $_POST['mep_enable_recurring'] : '';
      $mep_show_upcoming_event = isset($_POST['mep_show_upcoming_event']) ? $_POST['mep_show_upcoming_event'] : '';
      $mep_repeated_periods = isset($_POST['mep_repeated_periods']) ? $_POST['mep_repeated_periods'] : '';
      update_post_meta( $post_id, 'mep_enable_recurring', $recurring_status );
      update_post_meta( $post_id, 'mep_show_upcoming_event', $mep_show_upcoming_event );
      update_post_meta( $post_id, 'mep_repeated_periods', $mep_repeated_periods );
  }
}




add_filter('mage_event_extra_service_list', 'mep_rq_extra_service_list', 10, 4);
function mep_rq_extra_service_list($content, $event_id, $event_meta,$start_date){
  $recurring = get_post_meta($event_id, 'mep_enable_recurring', true) ? get_post_meta($event_id, 'mep_enable_recurring', true) : 'no';

  if($recurring == 'everyday'){
    $count =1;
    $start_date = wp_date('Y-m-d');
  ?>
  
  <!-- <input type="hidden" name='mepre_event_id' id='mep_event_id' value='<?php echo $event_id; ?>'>        -->
  <div id='mep_recurring_extra_service_list'></div>
  
  
  <?php  
  }elseif($recurring == 'yes'){

    $event_more_date[0]['event_more_start_date']    = date('Y-m-d',strtotime(get_post_meta($event_id,'event_start_date',true)));
    $event_more_date[0]['event_more_start_time']    = date('H:i',strtotime(get_post_meta($event_id,'event_start_time',true)));
    $event_more_date[0]['event_more_end_date']      = date('Y-m-d',strtotime(get_post_meta($event_id,'event_end_date',true)));
    $event_more_date[0]['event_more_end_time']      = date('H:i',strtotime(get_post_meta($event_id,'event_end_time',true)));
    $event_more_dates                               = get_post_meta($event_id,'mep_event_more_date',true) ? get_post_meta($event_id,'mep_event_more_date',true) : array();
    $event_multi_date                               = array_merge($event_more_date,$event_more_dates);
    $mep_available_seat                             = array_key_exists('mep_available_seat', $event_meta) ? $event_meta['mep_available_seat'][0] : 'on';

        ?>
          <div id='mep_recurring_extra_service_list'></div>
        <?php
    }else{
      return apply_filters('mage_event_extra_service_list_recurring', $content, $event_id, $event_meta,$start_date);
      // return $content;
    }
}






add_filter('mage_event_ticket_type_list', 'multi_date_event_list', 10, 4);
function multi_date_event_list($content,$event_id,$event_meta,$ticket_type_label){
  $recurring = get_post_meta($event_id, 'mep_enable_recurring', true) ? get_post_meta($event_id, 'mep_enable_recurring', true) : 'no';
  $mep_show_upcoming_event = get_post_meta($event_id, 'mep_show_upcoming_event', true) ? get_post_meta($event_id, 'mep_show_upcoming_event', true) : 'no';
  $mep_event_ticket_type   = get_post_meta($event_id,'mep_event_ticket_type',true) ? get_post_meta($event_id,'mep_event_ticket_type',true) : [];
 
if($recurring == 'everyday'){
  $count =1;
  $start_date = wp_date('Y-m-d');
?>

<input type="hidden" name='mepre_event_id' id='mep_event_id' value='<?php echo $event_id; ?>'>
<?php mep_re_get_everyday_event_date_sec($event_id); ?>
<h3 class='ex-sec-title mep_ticket_type_title'><?php echo $ticket_type_label;  ?></h3>
          
<div id='mep_recutting_ticket_type_list'></div>


<?php  
}elseif($recurring == 'yes'){
  $event_more_date[0]['event_more_start_date']    = date('Y-m-d',strtotime(get_post_meta($event_id,'event_start_date',true)));
  $event_more_date[0]['event_more_start_time']    = date('H:i',strtotime(get_post_meta($event_id,'event_start_time',true)));
  $event_more_date[0]['event_more_end_date']      = date('Y-m-d',strtotime(get_post_meta($event_id,'event_end_date',true)));
  $event_more_date[0]['event_more_end_time']      = date('H:i',strtotime(get_post_meta($event_id,'event_end_time',true)));
  $event_more_dates                               = get_post_meta($event_id,'mep_event_more_date',true) ? get_post_meta($event_id,'mep_event_more_date',true) : array();
  $event_multi_date                               = array_merge($event_more_date,$event_more_dates);
  $mep_available_seat = array_key_exists('mep_available_seat', $event_meta) ? $event_meta['mep_available_seat'][0] : 'on';

    if(empty($event_multi_date)){
        return apply_filters('mep_event_ticket_type_loop', $content, $event_id);
    }

  $count =1;
?>
<?php echo get_mep_re_recurring_date($event_id,$event_multi_date,$mep_show_upcoming_event); ?>
<input type="hidden" name='mepre_event_id' id='mep_event_id' value='<?php echo $event_id; ?>'>
<h3 class='ex-sec-title'> <?php echo mep_get_label($event_id,'mep_event_ticket_type_text','Ticket Type For
          ');  ?></h3>
<div id='mep_recutting_ticket_type_list'></div>
<?php                        
  }else{
         return $content;
  }


}









if (!function_exists('mep_re_event_ticket_sold')) {   
  function mep_re_event_ticket_sold($event_id,$date){
    
    $args = array(
            'post_type' => 'mep_events_attendees',
            'posts_per_page' => -1,
  
        'meta_query' => array(    
          'relation' => 'AND',
          array(    
            'relation' => 'AND',           
            array(
              'key'       => 'ea_event_id',
              'value'     => $event_id,
              'compare'   => '='
            ),		        
            array(
              'key'       => 'ea_event_date',
              'value'     => $date,
              'compare'   => 'LIKE'
            )
            ),array(    
              'relation' => 'OR',           
              array(
                'key'       => 'ea_order_status',
                'value'     => 'processing',
                'compare'   => '='
              ),		        
              array(
                'key'       => 'ea_order_status',
                'value'     => 'completed',
                'compare'   => '='
              )
              )
          )            
        ); 
     $loop = new WP_Query($args);
    return $loop->post_count;
  }
}  


if (!function_exists('mep_re_event_available_seat')) {   
  function mep_re_event_available_seat($event_id, $date){
// echo $date;    
  $total_seat = mep_event_total_seat($event_id,'total');
  $total_resv = mep_event_total_seat($event_id,'resv');
  $total_sold = mep_re_event_ticket_sold($event_id,$date);
  $total_left = $total_seat - ($total_sold + $total_resv);
  $total_left = apply_filters('mep_total_ticket_of_type', $total_left, $event_id, '',$date);
  return $total_left;
  }
} 







}else{
  function mep_recurring_admin_notice_wc_not_active() {
    $class = 'notice notice-error';
    $message = __( 'Woocommerce Event Manager Addon: Recurring Events is Dependent on WooCommerce & Woocommerce Event Manager', 'mage-eventpress-recurring' );
    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
  }
  add_action( 'admin_notices', 'mep_recurring_admin_notice_wc_not_active' );
  }