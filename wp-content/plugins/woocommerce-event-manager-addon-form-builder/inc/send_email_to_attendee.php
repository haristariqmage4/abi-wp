<?php
add_action('admin_menu', 'mep_fb_send_waitlist_email');
function mep_fb_send_waitlist_email()
{
  add_submenu_page('edit.php?post_type=mep_events', __('Send Email to Attendee', 'mep-form-builder'), __('Send Email to Attendee', 'mep-form-builder'), 'manage_options', 'mep_email_to_attendee', 'mep_email_to_attendee');
}


function mep_email_to_attendee()
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
    <h2><?php _e('Send email To Attendee','mep-form-builder'); ?></h2>
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
                                "action": "mep_fb_ajax_email_send_attendee_filter",
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




add_action('wp_ajax_mep_fb_ajax_email_send_attendee_filter', 'mep_fb_ajax_email_send_attendee_filter');
function mep_fb_ajax_email_send_attendee_filter()
{
  $event_date       = $_REQUEST['event_date'];
  $event_id         = $_REQUEST['event_id'];
  $a_query          = mep_fb_attendee_query($event_id, $event_date,-1);
  $count_waitlist   = $a_query->post_count;
  $attendee_query   = $a_query->posts;
  $current_site_name = get_bloginfo();
  $admin_email      = get_bloginfo('admin_email');
  $form_name        = mep_get_option('mep_email_form_name', 'email_setting_sec', get_bloginfo()) ? mep_get_option('mep_email_form_name', 'email_setting_sec', get_bloginfo()) : $current_site_name;
  $form_email       = mep_get_option('mep_email_form_email', 'email_setting_sec', $admin_email) ? mep_get_option('mep_email_form_email', 'email_setting_sec', $admin_email) : $admin_email;

  if ($count_waitlist > 0) {
  ?>

    <div id='mep_wailtlist_email_send_section'>
      <div id='mep_email_sending_info'></div>
      <input type="hidden" id='mep_wl_event_date' value="<?php echo $event_date; ?>">
      <form action="">
        <ul>
          <li><span class='mep-section-name'><?php _e('Event Name:', 'mep-form-builder'); ?></span> <span class='mep-section-data'><?php echo get_the_title($event_id); ?></span></li>
          <?php if ($event_date > 0) { ?>
            <li><span class='mep-section-name'><?php _e('Event Date:', 'mep-form-builder'); ?></span> <span class='mep-section-data'><?php echo get_mep_datetime($event_date, 'date-time-text'); ?></span></li>
          <?php } ?>
          <li><span class='mep-section-name'><?php _e('Attendee Found:', 'mep-form-builder'); ?></span> <span class='mep-section-data'><?php echo $count_waitlist; ?></span></li>
          <li><span class='mep-section-name'><?php _e('Form Name:', 'mep-form-builder'); ?></span> <span class='mep-section-data'><?php echo $form_name; ?></span></li>
          <li><span class='mep-section-name'><?php _e('Form Email:', 'mep-form-builder'); ?></span> <span class='mep-section-data'><?php echo $form_email; ?></span></li>
          <li><span class='mep-section-name'><?php _e('Subject:', 'mep-form-builder'); ?></span> <span class='mep-section-data'>
              <input required type="text" name='waitlist_email_subject' id='waitlist_email_subject'>
            </span></li>
          <li><span class='mep-section-name'><?php _e('Select Email Template:', 'mep-form-builder'); ?></span> <span class='mep-section-data'>
              <select name="waitlist_email_id" id="waitlist_email_id" class="select2" required>
                <option value=""><?php _e('Select Email Template', 'mep-form-builder'); ?></option>
                <?php
                $args = array(
                  'post_type' => 'mep_waitlist_email',
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
            </span></li>
          <li>
            <button id='send_waitlist_email' type="submit"><?php _e('Send Email Now', 'mep-form-builder'); ?></button>
          </li>
        </ul>
      </form>

      <script>
        (function($) {
          'use strict';
          jQuery('#send_waitlist_email').on('click', function() {
            var event_id = <?php echo $event_id; ?>;
            // var event_date      = <?php echo $event_date; ?>;
            var event_date = jQuery('#mep_wl_event_date').val();
            var subject = jQuery('#waitlist_email_subject').val();
            var email_id = jQuery('#waitlist_email_id').val();
            if (subject && email_id) {
              jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                  "action": "mep_fb_ajax_send_email_to_attendee",
                  "event_date": event_date,
                  "event_id": event_id,
                  "subject": subject,
                  "email_id": email_id
                },
                beforeSend: function() {
                  jQuery('#mep_email_sending_info').html('<h5 class="mep-processing"><?php echo mep_get_option("mep_event_rec_please_wait_attendee_loading_text", "label_setting_sec", "Please wait! Attendee Email Now Sending.."); ?></h5>');
                },
                success: function(data) {
                  jQuery('#mep_email_sending_info').html(data);

                }
              });
            } else {
              alert('<?php _e('Please Write Email Subject & Select a Email From The list', 'mep-form-builder'); ?>');
            }
            return false;
          });
        })(jQuery);
      </script>
    </div>
  <?php
  } else {
  ?>
    <div class='mep_warning'>
      <h5 class="mep-processing"> <?php _e('Sorry, No Attendee found!', 'mep-form-builder'); ?></h5>
    </div>
  <?php
  }
  die();
}




add_action('wp_ajax_mep_fb_ajax_send_email_to_attendee', 'mep_fb_ajax_send_email_to_attendee');
function mep_fb_ajax_send_email_to_attendee()
{
  $event_date         = $_REQUEST['event_date'];
  $event_id           = $_REQUEST['event_id'];
  $subject            = $_REQUEST['subject'];
  $email_id           = $_REQUEST['email_id'];
  $current_site_name  = get_bloginfo();
  $admin_email        = get_bloginfo('admin_email');
  $form_name          = mep_get_option('mep_email_form_name', 'email_setting_sec', get_bloginfo()) ? mep_get_option('mep_email_form_name', 'email_setting_sec', get_bloginfo()) : $current_site_name;
  $form_email         = mep_get_option('mep_email_form_email', 'email_setting_sec', $admin_email) ? mep_get_option('mep_email_form_email', 'email_setting_sec', $admin_email) : $admin_email;

  $content_post       = get_post($email_id);
  $content            = $content_post->post_content;
  $content            = apply_filters('the_content', $content);
  $content            = str_replace(']]>', ']]&gt;', $content);

  $a_query        = mep_fb_attendee_query($event_id, $event_date,-1);
  $count_waitlist = $a_query->post_count;
  $attendee_query = $a_query->posts;
  $count          = 0;
  $waitlist_data  = [];

  foreach ($attendee_query as $wt) {
    $waitlist_id                  = $wt->ID;
    
    $user_email                      = get_post_meta($waitlist_id, 'ea_email', true) ? get_post_meta($waitlist_id, 'ea_email', true) : '';
    $user_name                       = get_post_meta($waitlist_id, 'ea_name', true) ? get_post_meta($waitlist_id, 'ea_name', true) : '';
    $order_id                        = get_post_meta($waitlist_id, 'ea_order_id', true) ? get_post_meta($waitlist_id, 'ea_order_id', true) : '';
    $order                           = wc_get_order( $order_id );
    $billing_email                   = $order->get_billing_email();
    $billing_first_name              = $order->get_billing_first_name();
    $billing_last_name               = $order->get_billing_last_name();

    $sent_to                         = !empty($user_email) ? $user_email : $billing_email;
    
    $waitlist_data['user_name']      = !empty($user_name) ? $user_name : $billing_first_name;

    $waitlist_data['user_email']     = $sent_to;
    $waitlist_data['event_name']     = get_the_title($event_id);
    $waitlist_data['event_date']     = $event_date == 0 || empty($event_date) ? get_mep_datetime(get_post_meta($waitlist_id, 'ea_event_date', true), 'date-time-text') : $event_date;

    $count                           = mep_fb_email_sent($sent_to, $subject, $content, $form_name, $form_email, $waitlist_data);

  }
  if ($count > 0) {
    echo '<span class="mep_success"> Total ' . $count_waitlist . ' Email Sent Successfully.</span>';
  }
  die();
}


function mep_fb_email_sent($sent_to, $email_sub, $email_body, $form_name, $form_email, $waitlist_data)
{
  $name         = $waitlist_data['user_name'] ? $waitlist_data['user_name'] : '';
  $email        = $waitlist_data['user_email'] ? $waitlist_data['user_email'] : '';
  $event        = $waitlist_data['event_name'] ? $waitlist_data['event_name'] : '';
  $event_date   = $waitlist_data['event_date'] ? $waitlist_data['event_date'] : '';

  $email_body   = str_replace("{name}", $name, $email_body);
  $email_body   = str_replace("{email}", $email, $email_body);
  $email_body   = str_replace("{event}", $event, $email_body);
  $email_body   = str_replace("{event_date}", $event_date, $email_body);
  $headers[]    = "From: $form_name <$form_email>";
  $headers[]    = "Content-Type: text/html; charset=UTF-8";
  $sent         = wp_mail($sent_to, $email_sub, $email_body, $headers);
  if ($sent) {
    return 1;
  }
}