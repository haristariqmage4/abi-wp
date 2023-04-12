<?php
add_action('admin_menu', 'mepw_send_waitlist_email');
function mepw_send_waitlist_email()
{
  add_submenu_page('edit.php?post_type=mep_events', __('Send Email to Waitlist', 'mage-eventpress-waitlist'), __('Send Email to Waitlist', 'mage-eventpress-waitlist'), 'manage_options', 'email_to_waitlist', 'mepw_send_waitlist_form');
}


function mepw_send_waitlist_form()
{
  global $wpdb;
  $event_id = 0;
  $event_date = isset($_REQUEST['ea_event_date']) ? strip_tags($_REQUEST['ea_event_date']) : 0;
  ?>
  <style type="text/css">
    .wrap.waitlist_body {
      display: block;
    }

    .wrap.waitlist_body h2 {
      text-align: center;
      text-transform: capitalize;
      margin: 30px 0;
    }

    .wrap.waitlist_body label {
      width: 100%;
      display: block;
      overflow: hidden;
    }

    .wrap.waitlist_body label input,
    .wrap.waitlist_body label select,
    .wrap.waitlist_body label textarea {
      display: block;
      width: 100%;
    }

    .wrap.waitlist_body label textarea {
      min-height: 300px;
      margin-bottom: 30px;
    }

    .wrap.waitlist_body .row {
      width: 500px;
      margin: 0 auto;
    }
  </style>
  <div class="wrap waitlist_body">
    <h2>Send email to Waitlist</h2>
<div class='attendee_filter_section'>
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
                    <button id='event_attendee_filter_btn'>Filter</button>
                </li>
            </ul>
        </div>


        <script>
        (function($) {
            'use strict';
            jQuery(document).ready(function($) {
                <?php do_action('mep_fb_attendee_list_script'); ?>
            });
            jQuery('#event_attendee_filter_btn').on('click', function() {
                    var event_id = jQuery('#mep_event_id').val();
                        var ev_event_date   = jQuery('#mep_everyday_ticket_time').val();
                        var re_event_date   = jQuery('#mep_recurring_date').val();                      
                        var event_date      = re_event_date ? re_event_date : ev_event_date;
                        var event_date      = event_date ? event_date : jQuery('#mep_everyday_datepicker').val();
                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl,
                            data: {
                                "action": "mep_wl_ajax_email_send_filter",
                                "event_date": event_date,
                                "event_id": event_id
                            },
                            beforeSend: function() {
                                jQuery('#event_wait_list_table_item').html('<h5 class="mep-processing"><?php echo mep_get_option('mep_event_rec_please_wait_attendee_loading_text', 'label_setting_sec', 'Please wait! Waitl List is Loading..'); ?></h5>');
                            },
                            success: function(data) {
                                jQuery('#event_wait_list_table_item').html(data);
                               
                            }
                        });
                    return false;
                });
        })(jQuery);
    </script>

    <div id='event_wait_list_table_item'></div>
</div>
<?php
}
