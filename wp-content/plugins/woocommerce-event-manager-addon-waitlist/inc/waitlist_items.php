<?php
add_action('admin_menu', 'mepw_waitlist_page');
function mepw_waitlist_page()
{
  add_submenu_page('edit.php?post_type=mep_events', __('Waitlist', 'mage-eventpress-waitlist'), __('Waitlist', 'mage-eventpress-waitlist'), 'manage_options', 'view_waitlist', 'mepw_waitlist');
}

function mepw_waitlist()
{
  global $wpdb;
  $table_name = $wpdb->prefix . 'mep_event_waitlist';
  if (isset($_GET['event_id'])) {
    $event_id = strip_tags($_GET['event_id']);
  } else {
    $event_id = 0;
  }
  $event_date = isset($_REQUEST['ea_event_date']) ? strip_tags($_REQUEST['ea_event_date']) : 0;
?>
  <div class="waitlist">
    <div class="wrap">
      <h2>Waitlist</h2>
      <?php
      if (isset($_GET['action']) && $_GET['action'] == 'delete_user') {
        // echo 'Yes Did';
        $event = strip_tags($_GET['event']);
        $list_id = strip_tags($_GET['list_id']);
        $status = 3;
        $del = update_post_meta($list_id,'status',$status);
        if ($del) {
      ?>
          <div id="message" class="updated notice notice-success is-dismissible">
            <p>Deleted</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
          </div>
      <?php
        }
      }
      ?>

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
                            <option value="<?php echo $event->ID; ?>" <?php if ($event_id == $event->ID) {
                                                                            echo 'selected';
                                                                        } ?>><?php echo get_the_title($event->ID); ?></option>
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
                                "action": "mep_wl_ajax_list_filter",
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
  </div>
<?php
}
