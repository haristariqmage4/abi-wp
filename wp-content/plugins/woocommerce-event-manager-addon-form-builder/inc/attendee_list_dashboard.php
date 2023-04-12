<?php

add_action('admin_menu', 'mep_fb_attendee_list_menu');
function mep_fb_attendee_list_menu()
{
    add_submenu_page('edit.php?post_type=mep_events', __('Attendee List', 'mep-form-builder'), __('Attendee List', 'mep-form-builder'), 'manage_woocommerce', 'attendee_list', 'mep_fb_attendee_list_dashboard');
}



function mep_fb_attendee_query($event_id, $event_date, $show,$filter_by='',$ev_filter_key='',$checkin_status='')
{


   $filter_by = $filter_by ? $filter_by : 'event';

    if( $filter_by != 'event'){
        $event_id = 0;
        $event_date = 0;
    }


    $name_filter = $filter_by == 'name' && !empty($ev_filter_key) ? array(
        'key'     => 'ea_name',
        'value'   => $ev_filter_key,
        'compare' => 'LIKE'
    ) : '';


    $email_filter = $filter_by == 'email' && !empty($ev_filter_key) ? array(
        'key'     => 'ea_email',
        'value'   => $ev_filter_key,
        'compare' => '='
    ) : '';



    $phone_filter = $filter_by == 'phone' && !empty($ev_filter_key) ? array(
        'key'     => 'ea_phone',
        'value'   => $ev_filter_key,
        'compare' => '='
    ) : '';

    $ticket_filter = $filter_by == 'ticket' && !empty($ev_filter_key) ? array(
        'key'     => 'ea_ticket_no',
        'value'   => $ev_filter_key,
        'compare' => '='
    ) : '';

    $order_filter = $filter_by == 'order' && !empty($ev_filter_key) ? array(
        'key'     => 'ea_order_id',
        'value'   => $ev_filter_key,
        'compare' => '='
    ) : '';

    $checkin_filter = !empty($checkin_status) ? array(
        'key'     => 'mep_checkin',
        'value'   => $checkin_status,
        'compare' => '='
    ) : '';


    $event_filter = $event_id > 0 ? array(
        'key'     => 'ea_event_id',
        'value'   => $event_id,
        'compare' => '='
    ) : '';
    $event_date_filter = $event_date > 0 ? array(
        'key'     => 'ea_event_date',
        'value'   => $event_date,
        'compare' => 'LIKE'
    ) : '';

    $args = array(
        'post_type' => 'mep_events_attendees',
        'posts_per_page' => $show,
        'meta_query'  => array(
            'relation' => 'AND',
            array(
                'relation' => 'AND',
                $event_filter,
                $event_date_filter,
                $name_filter,
                $email_filter,
                $phone_filter,
                $ticket_filter,
                $checkin_filter,
                $order_filter
            ),
            array(
                'relation' => 'OR',
                array(
                    'key'     => 'ea_order_status',
                    'value'   => 'completed',
                    'compare' => '='
                ),
                array(
                    'key'     => 'ea_order_status',
                    'value'   => 'processing',
                    'compare' => '='
                )
            )
        )
    );
    $loop = new WP_Query($args);
    return $loop;
}


function mep_fb_attendee_table_heading()
{
    $ticket_no = mep_get_option('mep_attendee_list_ticket_no', 'mep_attendee_list_settings', 'on');
    $name = mep_get_option('mep_attendee_list_name', 'mep_attendee_list_settings', 'on');
    $email = mep_get_option('mep_attendee_list_email', 'mep_attendee_list_settings', 'off');
    $phone = mep_get_option('mep_attendee_list_phone', 'mep_attendee_list_settings', 'off');
	$address = mep_get_option('mep_attendee_list_address', 'mep_attendee_list_settings', 'off');
    $tsize = mep_get_option('mep_attendee_list_tshize', 'mep_attendee_list_settings', 'off');
    $desg = mep_get_option('mep_attendee_list_desg', 'mep_attendee_list_settings', 'off');
    $website = mep_get_option('mep_attendee_list_website', 'mep_attendee_list_settings', 'off');
    $company = mep_get_option('mep_attendee_list_company', 'mep_attendee_list_settings', 'off');
    $gender = mep_get_option('mep_attendee_list_gender', 'mep_attendee_list_settings', 'off');
    $ticket_type = mep_get_option('mep_attendee_list_ticket_type', 'mep_attendee_list_settings', 'on');
    $event = mep_get_option('mep_attendee_list_event', 'mep_attendee_list_settings', 'on');
    $order_no = mep_get_option('mep_attendee_list_order_no', 'mep_attendee_list_settings', 'on');
    $datetime = mep_get_option('mep_attendee_event_datetime', 'mep_attendee_list_settings', 'on');
    $order_st = mep_get_option('mep_attendee_list_billing_order_status', 'mep_attendee_list_settings', 'off');
    $paid = mep_get_option('mep_attendee_list_billing_paid', 'mep_attendee_list_settings', 'off');
    $pmethod = mep_get_option('mep_attendee_list_billing_method', 'mep_attendee_list_settings', 'off');
    
?>
    <tr>
<?php if($ticket_no == 'on'){ ?> <th style='width: 10%;'><?php _e('Ticket No', 'mep-form-builder'); ?></th> <?php } ?>
<?php if($name == 'on'){ ?>  <th style='width: 18%;'><?php _e('Full Name', 'mep-form-builder'); ?></th> <?php } ?>
<?php if($email == 'on'){ ?>  <th style='width: 18%;'><?php _e('Email', 'mep-form-builder'); ?></th> <?php } ?>
<?php if($phone == 'on'){ ?>  <th style='width: 18%;'><?php _e('Phone', 'mep-form-builder'); ?></th> <?php } ?>
<?php if($address == 'on'){ ?>  <th style='width: 18%;'><?php _e('Address', 'mep-form-builder'); ?></th> <?php } ?>
<?php if($tsize == 'on'){ ?>  <th style='width: 18%;'><?php _e('T-Shirt Size', 'mep-form-builder'); ?></th> <?php } ?>
<?php if($desg == 'on'){ ?>  <th style='width: 18%;'><?php _e('Designation', 'mep-form-builder'); ?></th> <?php } ?>
<?php if($website == 'on'){ ?>  <th style='width: 18%;'><?php _e('Website', 'mep-form-builder'); ?></th> <?php } ?>
<?php if($company == 'on'){ ?>  <th style='width: 18%;'><?php _e('Company', 'mep-form-builder'); ?></th> <?php } ?>
<?php if($gender == 'on'){ ?>  <th style='width: 18%;'><?php _e('Gender', 'mep-form-builder'); ?></th> <?php } ?>
<?php if($ticket_type == 'on'){ ?>   <th style='width: 11%;'><?php _e('Ticket', 'mep-form-builder'); ?></th><?php } ?>
<?php if($event == 'on'){ ?>   <th><?php _e('Event', 'mep-form-builder'); ?></th><?php } ?>
<?php if($order_no == 'on'){ ?>    <th style='width: 7%;'><?php _e('Order ID', 'mep-form-builder'); ?></th><?php } ?>
<?php if($datetime == 'on'){ ?>    <th><?php _e('Event Datetime', 'mep-form-builder'); ?></th><?php } ?>
<?php if($order_st == 'on'){ ?>    <th><?php _e('Order Status', 'mep-form-builder'); ?></th><?php } ?>
<?php if($paid == 'on'){ ?>    <th><?php _e('Paid Amount', 'mep-form-builder'); ?></th><?php } ?>
<?php if($pmethod == 'on'){ ?>    <th><?php _e('Payment Method', 'mep-form-builder'); ?></th><?php } ?>
        <?php do_action('mep_attendee_list_heading'); ?>
        <th style='width: 12%;'><?php _e('Action', 'mep-form-builder'); ?></th>
    </tr>
<?php
}


function mep_fb_attendee_list_items($attendee_id)
{
    $ticket_no = mep_get_option('mep_attendee_list_ticket_no', 'mep_attendee_list_settings', 'on');
    $name = mep_get_option('mep_attendee_list_name', 'mep_attendee_list_settings', 'on');
    $email = mep_get_option('mep_attendee_list_email', 'mep_attendee_list_settings', 'off');
    $phone = mep_get_option('mep_attendee_list_phone', 'mep_attendee_list_settings', 'off');
	$address = mep_get_option('mep_attendee_list_address', 'mep_attendee_list_settings', 'off');
    $tsize = mep_get_option('mep_attendee_list_tshize', 'mep_attendee_list_settings', 'off');
    $desg = mep_get_option('mep_attendee_list_desg', 'mep_attendee_list_settings', 'off');
    $website = mep_get_option('mep_attendee_list_website', 'mep_attendee_list_settings', 'off');
    $company = mep_get_option('mep_attendee_list_company', 'mep_attendee_list_settings', 'off');
    $gender = mep_get_option('mep_attendee_list_gender', 'mep_attendee_list_settings', 'off');
    $ticket_type = mep_get_option('mep_attendee_list_ticket_type', 'mep_attendee_list_settings', 'on');
    $event = mep_get_option('mep_attendee_list_event', 'mep_attendee_list_settings', 'on');
    $order_no = mep_get_option('mep_attendee_list_order_no', 'mep_attendee_list_settings', 'on');
    $datetime = mep_get_option('mep_attendee_event_datetime', 'mep_attendee_list_settings', 'on');
    $order_st = mep_get_option('mep_attendee_list_billing_order_status', 'mep_attendee_list_settings', 'off');
    $paid = mep_get_option('mep_attendee_list_billing_paid', 'mep_attendee_list_settings', 'off');
    $pmethod = mep_get_option('mep_attendee_list_billing_method', 'mep_attendee_list_settings', 'off');    
    $pin = get_post_meta($attendee_id, 'ea_user_id', true) . get_post_meta($attendee_id, 'ea_order_id', true) . get_post_meta($attendee_id, 'ea_event_id', true) . $attendee_id;
?>
    <tr class='attendee_<?php echo $attendee_id; ?>'>
    <?php if($ticket_no == 'on'){ ?>  <td><?php echo $pin; ?></td>  <?php } ?>
    <?php if($name == 'on'){ ?> <td><?php echo get_post_meta($attendee_id, 'ea_name', true); ?></td> <?php } ?>
    <?php if($email == 'on'){ ?> <td><?php echo get_post_meta($attendee_id, 'ea_email', true) ?></td> <?php } ?>
    <?php if($phone == 'on'){ ?> <td><?php echo get_post_meta($attendee_id, 'ea_phone', true) ?></td> <?php } ?>
	<?php if($address == 'on'){ ?> <td><?php echo get_post_meta($attendee_id, 'ea_address_1', true) ?></td> <?php } ?>
    <?php if($tsize == 'on'){ ?> <td><?php echo get_post_meta($attendee_id, 'ea_tshirtsize', true) ?></td> <?php } ?>
    <?php if($desg == 'on'){ ?> <td><?php echo get_post_meta($attendee_id, 'ea_desg', true) ?></td> <?php } ?>
    <?php if($website == 'on'){ ?> <td><?php echo get_post_meta($attendee_id, 'ea_website', true) ?></td> <?php } ?>
    <?php if($company == 'on'){ ?> <td><?php echo get_post_meta($attendee_id, 'ea_company', true) ?></td> <?php } ?>
    <?php if($gender == 'on'){ ?> <td><?php echo get_post_meta($attendee_id, 'ea_gender', true) ?></td> <?php } ?>
    <?php if($ticket_type == 'on'){ ?> <td><?php echo get_post_meta($attendee_id, 'ea_ticket_type', true) ?></td> <?php } ?>
    <?php if($event == 'on'){ ?> <td><?php echo get_the_title(get_post_meta($attendee_id, 'ea_event_id', true)); ?></td> <?php } ?>
    <?php if($order_no == 'on'){ ?> <td><?php echo get_post_meta($attendee_id, 'ea_order_id', true); ?></td> <?php } ?>
    <?php if($datetime == 'on'){ ?> <td><?php echo get_mep_datetime(get_post_meta($attendee_id, 'ea_event_date', true), 'date-time-text'); ?></td> <?php } ?>
    <?php if($order_st == 'on'){ ?> <td><?php echo get_post_meta($attendee_id, 'ea_order_status', true); ?></td> <?php } ?>       
    <?php if($paid == 'on'){ ?> <td><?php echo get_post_meta($attendee_id, 'ea_ticket_price', true); ?></td> <?php } ?>       
    <?php if($pmethod == 'on'){ ?> <td><?php echo get_post_meta($attendee_id, 'ea_payment_method', true); ?></td> <?php } ?>       
        <?php do_action('mep_attendee_list_item', $attendee_id); ?>
        <td>
            <a href="<?php echo get_the_permalink($attendee_id); ?>" title='View' target='_blank'><span class="dashicons dashicons-visibility"></span></a>
            <a href="<?php echo get_admin_url() . "post.php?post=$attendee_id&action=edit"; ?>"  title='Edit' target='_blank'><span class="dashicons dashicons-edit-large"></span></a>
            <?php do_action('mep_attendee_list_item_action_middile', $attendee_id); ?>
            <span  title='Delete' class="mep_del_attendee" data-id=<?php echo $attendee_id; ?>><span class="dashicons dashicons-no"></span></span>
            <?php do_action('mep_attendee_list_item_action_after', $attendee_id); ?>
        </td>
    </tr>
<?php
}



function mep_attendee_query_stat($a_query, $event_id, $event_date, $checkin_status = 'all')
{
    // echo $event_id;
    $attendee_count = $a_query->post_count;
    $total_attendee_count = $a_query->found_posts;
?>
    <ul class="attendee_stat">
        <li class='total_attendee'>
            <?php _e('Total ', 'mep-form-builder');
            echo $total_attendee_count;
            _e(' Attendee Found', 'mep-form-builder'); ?>
        </li>
        <li class='showing_attendee'>
            <?php _e('Showing ', 'mep-form-builder');
            echo $attendee_count;
            _e(' Attendees', 'mep-form-builder'); ?>
        </li>
        <li class='attendee_export_btn'>
            <?php if ($total_attendee_count > 0 && $event_id > 0) { ?>
                <a class='mep_export_csv' href="edit.php?post_type=mep_events&page=attendee_list&action=download_csv_custom&event_id=<?php echo $event_id; ?>&ea_event_date=<?php echo $event_date; ?>&checkin=<?php echo $checkin_status; ?>" target='_blank'><span class="dashicons dashicons-media-text"></span> <?php _e('CSV Export Attendee','mep-form-builder'); ?></a>
                <a class='mep_export_csv' href="edit.php?post_type=mep_events&page=attendee_list&action=download_csv_extra_service&event_id=<?php echo $event_id; ?>&ea_event_date=<?php echo $event_date; ?>" target='_blank'><span class="dashicons dashicons-media-text"></span> <?php _e('CSV Export Extra Service','mep-form-builder'); ?></a>
            <?php }else{ 
                _e('Please Filter the List for CSV Export','mep-form-builder');
            } ?>
        </li>
    </ul>
<?php
}






function mep_fb_attendee_list_dashboard()
{
    $event_id = isset($_REQUEST['event_id']) ? strip_tags($_REQUEST['event_id']) : 0;
    $event_date = isset($_REQUEST['ea_event_date']) ? strip_tags($_REQUEST['ea_event_date']) : 0;

?>
    <div class="wrap">
        <h2><?php _e('Event Attendee List', 'mep-form-builder'); ?></h2>
        <div class='attendee_filter_section'>
            <ul>
            <li><?php _e('Filter List By:', 'mep-form-builder'); ?></li>
                <li><label for="event_filter"><input class='mep_attn_filter_by' type="radio" name='attendee_filter_by' id='event_filter' value='event' checked><?php _e('Event','mep-form-builder'); ?></label></li>
                <li><label for="ticket_filter"><input class='mep_attn_filter_by' type="radio" name='attendee_filter_by' id='ticket_filter' value='ticket'><?php _e('Ticket No','mep-form-builder'); ?></label></li>
                <li><label for="name_filter"><input class='mep_attn_filter_by' type="radio" name='attendee_filter_by' id='name_filter' value='name'><?php _e('Name','mep-form-builder'); ?></label></li>
                <li><label for="order_filter"><input class='mep_attn_filter_by' type="radio" name='attendee_filter_by' id='order_filter' value='order'><?php _e('Order','mep-form-builder'); ?></label></li>
                <li><label for="phone_filter"><input class='mep_attn_filter_by' type="radio" name='attendee_filter_by' id='phone_filter' value='phone'><?php _e('Phone','mep-form-builder'); ?></label></li>
                <li><label for="email_filter"><input class='mep_attn_filter_by' type="radio" name='attendee_filter_by' id='email_filter' value='email'><?php _e('Email','mep-form-builder'); ?></label></li>
            </ul>
            <ul>
                
                <li>
                    <div class='event_filter'>
                    <select name="event_id" id="mep_event_id" class="select2" required>
                        <option value="0"><?php _e('Select Event', 'mep-form-builder'); ?></option>
                        <?php
                        $args = array(
                            'post_type' => 'mep_events',
                            'posts_per_page' => -1
                        );
                        $loop = new WP_Query($args);
                        $events_query = $loop->posts;
                        foreach ($events_query as $event) {
                        ?>
                        <option value="<?php echo $event->ID; ?>" <?php if ($event_id == $event->ID) {  echo 'selected'; } ?>><?php echo get_the_title($event->ID); ?></option>
                        <?php
                        }
                        ?>
                    </select>
                    </div>
                    <div class='attendee_key_filter' style="display: none;">
                        <input type="text" name='filter_key' value='' id='attendee_filter_key'>
                    </div>
                </li>
                <li id='filter_attitional_btn'>
                    <input type="hidden" id='mep_everyday_ticket_time' name='mep_attendee_list_filter_event_date' value='<?php echo $event_date; ?>'>
                </li>
                <?php do_action('mep_attendee_list_filter_form_before_btn'); ?>
                <li>

                    <button id='event_attendee_filter_btn'><?php _e('Filter','mep-form-builder'); ?></button>
                </li>
            </ul>
        </div>
        <div id='before_attendee_table_info'></div>
        <div id='event_attendee_list_table_item'>
            <?php
            $a_query = mep_fb_attendee_query($event_id, $event_date, 50);
            $attendee_query = $a_query->posts;
            mep_attendee_query_stat($a_query, $event_id, $event_date);
            ?>

            <table class="wp-list-table widefat striped posts">
                <thead>
                    <?php mep_fb_attendee_table_heading(); ?>
                </thead>
                <tbody>
                    <?php
                    foreach ($attendee_query as $_attendee) {
                        $attendee_id = $_attendee->ID;
                        mep_fb_attendee_list_items($attendee_id);
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        (function($) {
            'use strict';
            jQuery(document).ready(function($) {
                
                <?php do_action('mep_fb_attendee_list_script'); ?>

                
                    $(document).on('click', '.mep_sync_data', function() {
                    var event_id = jQuery(this).data('id');
                    // alert(event_id);
                    if (event_id > 0) {
                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl,
                            // url: ajaxurl,
                            data: {
                                "action": "mep_fb_ajax_attendee_sync",
                                "attendee_id": event_id
                            },
                            beforeSend: function() {
                                jQuery('#before_attendee_table_info').html('<h5 class="mep-processing"><?php _e('Please wait! Attendee data synchronizing from order data','mep-form-builder'); ?></h5>');
                            },
                            success: function(data) {
                                jQuery('#before_attendee_table_info').html(data);
                                window.location.reload();
                            }
                        });
                    } 
                    return false;
                });

                jQuery('#event_attendee_filter_btn').on('click', function() {
                    var event_id = jQuery('#mep_event_id').val();

                    // if (event_id > 0) {
                        var filter_by       = $("input[name='attendee_filter_by']:checked").val();
                        var ev_filter_key   = jQuery('#attendee_filter_key').val();
                        var ev_event_date   = jQuery('#mep_everyday_ticket_time').val();
                        var re_event_date   = jQuery('#mep_recurring_date').val();
                        var re_event_datepicker   = jQuery('#mep_everyday_datepicker').val();
                        var checkin_status  = jQuery('#mep_attendee_checkin').val() ? jQuery('#mep_attendee_checkin').val() : '';
                        var event_date_t      = re_event_date ? re_event_date : ev_event_date;
                        var event_date      = event_date_t != 0 && event_date_t ? event_date_t : re_event_datepicker;
                        
                        // alert(event_date);

                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl,
                            // url: ajaxurl,
                            data: {
                                "action": "mep_fb_ajax_attendee_filter",
                                "filter_by": filter_by,
                                "ev_filter_key": ev_filter_key,
                                "event_date": event_date,
                                "checkin_status": checkin_status,
                                "event_id": event_id
                            },
                            beforeSend: function() {
                                jQuery('#event_attendee_list_table_item').html('<h5 class="mep-processing"><?php echo mep_get_option('mep_event_rec_please_wait_attendee_loading_text', 'label_setting_sec', 'Please wait! Attendee List is Loading..'); ?></h5>');
                            },
                            success: function(data) {
                                jQuery('#event_attendee_list_table_item').html(data);
                               
                            }
                        });
                    // } else {
                    //     alert('Please Select a Event From The List');
                    // }
                    return false;
                });


                $(document).on('click', '.mep_del_attendee', function() {

                    var attendee_id = $(this).data("id");
                    jQuery.ajax({
                        type: 'POST',
                        // url: mep_ajax.mep_ajaxurl,
                        url: ajaxurl,
                        data: {
                            "action": "mep_fb_ajax_attendee_delete",
                            "attendee_id": attendee_id
                        },
                        beforeSend: function() {
                        },
                        success: function(data) {
                            jQuery('.attendee_' + attendee_id).hide();
                        }
                    });
                    return false;
                });


                $(document).on('click', '.mep_attn_filter_by', function() {

                   var filter_by = $("input[name='attendee_filter_by']:checked").val();
                
                   if(filter_by == 'event'){
                       $('.event_filter').show();
                       $('#filter_attitional_btn').show();
                       $('.attendee_key_filter').hide();
                   }else{
                    $('.event_filter').hide();
                    $('#filter_attitional_btn').hide();
                       $('.attendee_key_filter').show();
                   }     

                });

            });
        })(jQuery);
    </script>
<?php
}



add_action('wp_ajax_mep_fb_ajax_attendee_sync', 'mep_fb_ajax_attendee_sync');
add_action('wp_ajax_nopriv_mep_fb_ajax_attendee_sync', 'mep_fb_ajax_attendee_sync');
function mep_fb_ajax_attendee_sync()
{
    $attendee_id               = $_REQUEST['attendee_id'];
    $order_id                  = get_post_meta( $attendee_id, 'ea_order_id', true );
    mep_fb_attendee_data_sync_from_order_meta($order_id,$attendee_id);
    die();
}


add_action('wp_ajax_mep_fb_ajax_attendee_filter', 'mep_fb_ajax_attendee_filter');
add_action('wp_ajax_nopriv_mep_fb_ajax_attendee_filter', 'mep_fb_ajax_attendee_filter');
function mep_fb_ajax_attendee_filter()
{
    $event_id               = isset($_REQUEST['event_id']) ? $_REQUEST['event_id'] : '';
    $event_date             = isset($_REQUEST['event_date']) ? $_REQUEST['event_date'] : '';
    $filter_by              = isset($_REQUEST['filter_by']) ? $_REQUEST['filter_by'] : '';
    $ev_filter_key          = isset($_REQUEST['ev_filter_key']) ? $_REQUEST['ev_filter_key'] : '';
    $checkin_status         = isset($_REQUEST['checkin_status']) ? $_REQUEST['checkin_status'] : '';
    $a_query = mep_fb_attendee_query($event_id, $event_date, -1,$filter_by,$ev_filter_key,$checkin_status);
    // echo '<pre>'; print_r($a_query); echo '</pre>';
    $attendee_query = $a_query->posts;
    mep_attendee_query_stat($a_query, $event_id, $event_date, $checkin_status);
?>
    <table class="wp-list-table widefat striped posts">
        <thead>
            <?php mep_fb_attendee_table_heading(); ?>
        </thead>
        <tbody>
            <?php
            foreach ($attendee_query as $_attendee) {
                $attendee_id = $_attendee->ID;
                mep_fb_attendee_list_items($attendee_id);
            }
            ?>
        </tbody>
    </table>
<?php
    die();
}



add_action('wp_ajax_mep_fb_ajax_attendee_delete', 'mep_fb_ajax_attendee_delete');
add_action('wp_ajax_nopriv_mep_fb_ajax_attendee_delete', 'mep_fb_ajax_attendee_delete');
function mep_fb_ajax_attendee_delete()
{
    $attendee_id               = $_REQUEST['attendee_id'];
    mep_fb_change_attandee_status($attendee_id, 'trash', 'publish');
    die();
}
