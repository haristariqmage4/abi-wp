<?php
if (!defined('ABSPATH')) {
    die;
} // Cannot access pages directly.

add_action('admin_menu', 'mep_fb_bulk_event_date_edit_menu');
function mep_fb_bulk_event_date_edit_menu()
{
    add_submenu_page('edit.php?post_type=mep_events', __('Bulk Attendee Date Edit', 'mep-form-builder'), __('Bulk Attendee Date Edit', 'mep-form-builder'), 'manage_woocommerce', 'mep_bulk_attendee_date_edit', 'mep_bulk_attendee_date_edit_func');
}

function mep_bulk_attendee_date_edit_func()
{
    $event_id = 0;
?>
    <div class="wrap">
        <h2><?php _e('Attendee List Event Date Modification', 'mep-form-builder'); ?></h2>
        <div id='before_attendee_table_info'>

    <h5 class='mep-processing' style="border-color: #dabc0e;color: #826f06;"><?php _e('Before change the Attendee Event Datetime to a New Date, Please first change the event datetime to New Datetime, Then Change the Attendee Event Datetime','mep-form-builder'); ?></h5>        
        </div>
        <div class='attendee_filter_section'>
            <ul>
                <li>
                    <div class='event_filter'>
                        <label for="mep_event_id">
                            <?php _e('Select Event', 'mep-form-builder'); ?>
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
                                ?> <option value="<?php echo $event->ID; ?>" <?php if ($event_id == $event->ID) {
                                                                                echo 'selected';
                                                                            } ?>><?php echo get_the_title($event->ID); ?></option> <?php } ?>
                            </select>
                        </label>
                    </div>
                </li>
                <li>
                    <label for="mep_fb_edc_old_date">
                        <?php _e('Input OLD Event DateTime', 'mep-form-builder'); ?>
                        <input type="date" id="mep_fb_edc_old_date" name="mep_fb_edc_old_date" />
                        <input type="time" id="mep_fb_edc_old_time" name="mep_fb_edc_old_time" />
                    </label>
                </li>
                <li>
                    <label for="mep_fb_edc_new_date">
                        <?php _e('Input New Event DateTime', 'mep-form-builder'); ?>
                        <input type="date" id="mep_fb_edc_new_date" name="mep_fb_edc_new_date" />
                        <input type="time" id="mep_fb_edc_new_time" name="mep_fb_edc_new_time" />
                    </label>
                </li>
                <li>
                    <button id='event_attendee_date_change_btn' class='btn button mep-btn'><?php _e('Modify Attendee Event Datetime', 'mep-form-builder'); ?></button>
                </li>
            </ul>
        </div>
        <script>
            (function($) {
                'use strict';
                jQuery(document).ready(function($) {

                    $(document).on('click', '#event_attendee_date_change_btn', function() {
                        var event_id = jQuery('#mep_event_id').val();
                        var old_date = jQuery('#mep_fb_edc_old_date').val();
                        var old_time = jQuery('#mep_fb_edc_old_time').val();
                        var new_date = jQuery('#mep_fb_edc_new_date').val();
                        var new_time = jQuery('#mep_fb_edc_new_time').val();
                        if ((event_id > 0) && (old_date !== "") && (new_date !== "")) {
                            jQuery.ajax({
                                type: 'POST',
                                url: ajaxurl,
                                data: {
                                    "action": "mep_fb_ajax_attendee_event_date_edit",
                                    "event_id": event_id,
                                    "old_date": old_date,
                                    "old_time": old_time,
                                    "new_date": new_date,
                                    "new_time": new_time
                                },
                                beforeSend: function() {
                                    jQuery('#before_attendee_table_info').html('<h5 class="mep-processing"><?php _e('Please wait! Attendee Event Date is now updating.....', 'mep-form-builder'); ?></h5>');
                                },
                                success: function(data) {
                                    jQuery('#before_attendee_table_info').html(data);
                                }
                            });
                        } else {
                            alert("<?php _e('Please Select Event, Enter Full Datetime in both Old & New Input Box', 'mep-form-builder'); ?>");
                        }
                        return false;
                    });

                    <?php do_action('mep_fb_bulk_attendee_date_script'); ?>
                });
            })(jQuery);
        </script>
    </div>
    <?php
}

add_action('wp_ajax_mep_fb_ajax_attendee_event_date_edit', 'mep_fb_ajax_attendee_event_date_edit');
function mep_fb_ajax_attendee_event_date_edit()
{
    $event_id               = sanitize_text_field($_REQUEST['event_id']);
    $old_date               = sanitize_text_field($_REQUEST['old_date']);
    $old_time               = sanitize_text_field($_REQUEST['old_time']);
    $new_date               = sanitize_text_field($_REQUEST['new_date']);
    $new_time               = sanitize_text_field($_REQUEST['new_time']);

    $event_name             = get_the_title($event_id);
    $old_datetime           = !empty($old_time) ? date('Y-m-d H:i', strtotime($old_date . ' ' . $old_time)) : date('Y-m-d', strtotime($old_date));
    $old_datetime_text      = !empty($old_time) ? get_mep_datetime($old_datetime, 'date-time-text') : get_mep_datetime($old_datetime, 'date-text');
    $new_datetime           = !empty($new_time) ? date('Y-m-d H:i', strtotime($new_date . ' ' . $new_time)) : date('Y-m-d', strtotime($new_date));
    $new_datetime_text      = !empty($new_time) ? get_mep_datetime($new_datetime, 'date-time-text') : get_mep_datetime($new_datetime, 'date-text');
    $a_query                = mep_fb_attendee_query($event_id, $old_datetime, -1);

    $found_attendee         = $a_query->post_count;
    if ($found_attendee > 0) {
        mep_fb_update_attendee_event_date($a_query, $new_datetime, $old_datetime);
    ?>
        <h5 class='mep-processing'>
            <?php _e('Total', 'mep-form-builder'); ?> <?php echo $found_attendee; ?> <?php _e('Attendee Found, They Moved to New Date:', 'mep-form-builder'); ?> <?php echo $new_datetime_text; ?>
            <?php do_action('mep_fb_after_attendee_edit_date_success', $event_id, $a_query, $old_datetime, $new_datetime); ?>
        </h5>
<?php
    } else {
        echo "<h5 class='mep-processing'> Sorry, there are no attendees found on $old_datetime_text Date for the event: $event_name </h5>";
    }
    die();
}

function mep_fb_update_attendee_event_date($query, $new_date, $old_datetime)
{
    foreach ($query->posts as $value) {
        $attendee_id = $value->ID;
        update_post_meta($attendee_id, 'ea_event_date', $new_date);
        update_post_meta($attendee_id, 'ea_event_date_modified', 'Yes');
        update_post_meta($attendee_id, 'ea_event_date_modified_old_date', $old_datetime);
        update_post_meta($attendee_id, 'ea_event_date_modified_new_date', $new_date);
    }
}
