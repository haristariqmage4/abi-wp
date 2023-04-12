<?php
if (!defined('ABSPATH')) {
  die;
}
// Language Load
add_action('init', 'mepw_language_load');
function mepw_language_load()
{
  $plugin_dir = basename(dirname(__DIR__)) . "/languages/";
  load_plugin_textdomain('mage-eventpress-waitlist', false, $plugin_dir);
}

// Enqueue Scripts for frontend
add_action('wp_enqueue_scripts', 'mep_wl_enqueue_scripts');
function mep_wl_enqueue_scripts()
{
  wp_enqueue_style('mep-event-waitlist-style', plugin_dir_url(__DIR__) . 'css/style.css', array());
}

add_action('admin_enqueue_scripts', 'mep_wl_event_admin_scripts');
function mep_wl_event_admin_scripts()
{
  wp_enqueue_style('mep-waitlist-admin-style', plugin_dir_url(__DIR__) . 'css/admin_style.css', array());
}



function mepw_waitlist_email_sent($sent_to, $email_sub, $email_body, $form_name, $form_email, $waitlist_data)
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



function mepw_no_list($event_id)
{
  $args = array(
    'post_type' => 'mep_event_waitlist',
    'posts_per_page' => -1,
    'meta_query' => array(
      'relation' => 'AND',
      array(
        'relation' => 'AND',
        array(
          'key'       => 'event_id',
          'value'     => $event_id,
          'compare'   => '='
        ),
        array(
          'key'       => 'status',
          'value'     => 1,
          'compare'   => '='
        )
      )
    )
  );
  $loop = new WP_Query($args);
  return $loop->post_count;
}


function mepw_waitlist_query($event_id, $event_date = '')
{
  if (!empty($event_date) && $event_date > 0) {
    $args = array(
      'post_type' => 'mep_event_waitlist',
      'posts_per_page' => -1,
      'meta_query' => array(
        'relation' => 'AND',
        array(
          'relation' => 'AND',
          array(
            'key'       => 'event_id',
            'value'     => $event_id,
            'compare'   => '='
          ),
          array(
            'key'       => 'event_datetime',
            'value'     => $event_date,
            'compare'   => 'LIKE'
          ),
          array(
            'key'       => 'status',
            'value'     => 1,
            'compare'   => '='
          )
        )
      )
    );
  } else {
    $args = array(
      'post_type' => 'mep_event_waitlist',
      'posts_per_page' => -1,
      'meta_query' => array(
        'relation' => 'AND',
        array(
          'relation' => 'AND',
          array(
            'key'       => 'event_id',
            'value'     => $event_id,
            'compare'   => '='
          ),
          array(
            'key'       => 'status',
            'value'     => 1,
            'compare'   => '='
          )
        )
      )
    );
  }
  $loop = new WP_Query($args);
  return $loop;
}



function mepw_check_in_list($event_id, $user_email, $event_date)
{
  $args = array(
    'post_type' => 'mep_event_waitlist',
    'posts_per_page' => -1,
    'meta_query' => array(
      'relation' => 'AND',
      array(
        'relation' => 'AND',
        array(
          'key'       => 'event_id',
          'value'     => $event_id,
          'compare'   => '='
        ),
        array(
          'key'       => 'user_email',
          'value'     => $user_email,
          'compare'   => '='
        ),
        array(
          'key'       => 'event_datetime',
          'value'     => $event_date,
          'compare'   => 'LIKE'
        ),
        array(
          'key'       => 'status',
          'value'     => 1,
          'compare'   => '='
        )
      )
    )
  );
  $loop = new WP_Query($args);
  return $loop->post_count;
}


function mep_waitlist_table_heading()
{
?>
  <tr>
    <th>Name</th>
    <th>Email</th>
    <th>Event</th>
    <th>Event Datetime</th>
    <th>Join Datetime</th>
    <!-- <th>Action</th> -->
  </tr>
<?php
}


function mep_waitlist_table_items($wid)
{
?>
  <tr>
    <td><?php echo get_post_meta($wid, 'user_name', true); ?></td>
    <td><?php echo get_post_meta($wid, 'user_email', true);; ?></td>
    <td><?php echo get_the_title(get_post_meta($wid, 'event_id', true)); ?></td>
    <td><?php if (get_post_meta($wid, 'event_datetime', true) > 0) {
          echo get_mep_datetime(get_post_meta($wid, 'event_datetime', true), 'date-time-text');
        } ?></td>
    <td><?php echo get_the_date(get_option('date_format'), $wid); ?></td>
  </tr>
  <?php
}

add_action('wp_ajax_mep_wl_ajax_email_send_filter', 'mep_wl_ajax_email_send_filter');
function mep_wl_ajax_email_send_filter()
{
  $event_date       = $_REQUEST['event_date'];
  $event_id         = $_REQUEST['event_id'];
  $a_query          = mepw_waitlist_query($event_id, $event_date);
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
          <li><span class='mep-section-name'><?php _e('Event Name:', 'mage-eventpress-waitlist'); ?></span> <span class='mep-section-data'><?php echo get_the_title($event_id); ?></span></li>
          <?php if ($event_date > 0) { ?>
            <li><span class='mep-section-name'><?php _e('Event Date:', 'mage-eventpress-waitlist'); ?></span> <span class='mep-section-data'><?php echo get_mep_datetime($event_date, 'date-time-text'); ?></span></li>
          <?php } ?>
          <li><span class='mep-section-name'><?php _e('Waitlist Member Found:', 'mage-eventpress-waitlist'); ?></span> <span class='mep-section-data'><?php echo $count_waitlist; ?></span></li>
          <li><span class='mep-section-name'><?php _e('Form Name:', 'mage-eventpress-waitlist'); ?></span> <span class='mep-section-data'><?php echo $form_name; ?></span></li>
          <li><span class='mep-section-name'><?php _e('Form Email:', 'mage-eventpress-waitlist'); ?></span> <span class='mep-section-data'><?php echo $form_email; ?></span></li>
          <li><span class='mep-section-name'><?php _e('Subject:', 'mage-eventpress-waitlist'); ?></span> <span class='mep-section-data'>
              <input required type="text" name='waitlist_email_subject' id='waitlist_email_subject'>
            </span></li>
          <li><span class='mep-section-name'><?php _e('Select Email Template:', 'mage-eventpress-waitlist'); ?></span> <span class='mep-section-data'>
              <select name="waitlist_email_id" id="waitlist_email_id" class="select2" required>
                <option value=""><?php _e('Select Waitlist Email', 'mep-form-builder'); ?></option>
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
            <button id='send_waitlist_email' type="submit"><?php _e('Send Waitlist Email', 'mage-eventpress-waitlist'); ?></button>
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
                  "action": "mep_wl_ajax_send_email",
                  "event_date": event_date,
                  "event_id": event_id,
                  "subject": subject,
                  "email_id": email_id
                },
                beforeSend: function() {
                  jQuery('#mep_email_sending_info').html('<h5 class="mep-processing"><?php echo mep_get_option("mep_event_rec_please_wait_attendee_loading_text", "label_setting_sec", "Please wait! Waitlist Email Now Sending.."); ?></h5>');
                },
                success: function(data) {
                  jQuery('#mep_email_sending_info').html(data);

                }
              });
            } else {
              alert('<?php _e('Please Write Email Subject & Select a Email From The list', 'mage-eventpress-waitlist'); ?>');
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
      <?php _e('Sorry, No waitlist found!', 'mage-eventpress-waitlist'); ?>
    </div>
  <?php
  }
  die();
}

add_action('wp_ajax_mep_wl_ajax_send_email', 'mep_wl_ajax_send_email');
function mep_wl_ajax_send_email()
{
  $event_date   = $_REQUEST['event_date'];
  $event_id     = $_REQUEST['event_id'];
  $subject      = $_REQUEST['subject'];
  $email_id     = $_REQUEST['email_id'];
  $current_site_name = get_bloginfo();
  $admin_email = get_bloginfo('admin_email');
  $form_name      = mep_get_option('mep_email_form_name', 'email_setting_sec', get_bloginfo()) ? mep_get_option('mep_email_form_name', 'email_setting_sec', get_bloginfo()) : $current_site_name;
  $form_email      = mep_get_option('mep_email_form_email', 'email_setting_sec', $admin_email) ? mep_get_option('mep_email_form_email', 'email_setting_sec', $admin_email) : $admin_email;

  $content_post = get_post($email_id);
  $content = $content_post->post_content;
  $content = apply_filters('the_content', $content);
  $content = str_replace(']]>', ']]&gt;', $content);



  $a_query        = mepw_waitlist_query($event_id, $event_date);
  $count_waitlist = $a_query->post_count;
  $attendee_query = $a_query->posts;
  $count = 0;
  $waitlist_data = [];
  foreach ($attendee_query as $wt) {
    $waitlist_id = $wt->ID;
    $sent_to = get_post_meta($waitlist_id, 'user_email', true);
    $waitlist_data['user_name'] = get_post_meta($waitlist_id, 'user_name', true);
    $waitlist_data['user_email'] = get_post_meta($waitlist_id, 'user_email', true);
    $waitlist_data['event_name'] = get_the_title($event_id);
    $waitlist_data['event_date'] = $event_date == 0 || empty($event_date) ? get_mep_datetime(get_post_meta($waitlist_id, 'event_datetime', true), 'date-time-text') : $event_date;

    $count = mepw_waitlist_email_sent($sent_to, $subject, $content, $form_name, $form_email, $waitlist_data);
  }
  if ($count > 0) {
    echo '<span class="mep_success"> Total ' . $count_waitlist . ' Email Sent Successfully.</span>';
  }
  die();
}

add_action('wp_ajax_mep_wl_ajax_list_filter', 'mep_wl_ajax_list_filter');
function mep_wl_ajax_list_filter()
{
  $event_date = $_REQUEST['event_date'];
  $event_id = $_REQUEST['event_id'];
  ?>
  <div id='event_attendee_list_table_item'>
    <?php
    $a_query = mepw_waitlist_query($event_id, $event_date);
    $attendee_query = $a_query->posts;
    // mep_attendee_query_stat($a_query, $event_id, $event_date);
    ?>
    <table class="wp-list-table widefat fixed striped posts">
      <thead>
        <?php mep_waitlist_table_heading(); ?>
      </thead>
      <tbody>
        <?php
        foreach ($attendee_query as $_attendee) {
          $attendee_id = $_attendee->ID;
          mep_waitlist_table_items($attendee_id);
        }
        ?>
      </tbody>
    </table>
  </div>
  <?php
  die();
}



add_action('wp_ajax_mep_wl_ajax_waitlist_date', 'mep_wl_ajax_waitlist_date');
add_action('wp_ajax_nopriv_mep_wl_ajax_waitlist_date', 'mep_wl_ajax_waitlist_date');
function mep_wl_ajax_waitlist_date()
{
  global $wpdb;
  if ( wp_verify_nonce( $_POST['nonce'], 'mep-waitlist-add-nonce' ) ) {
  $user_name            = sanitize_text_field($_POST['user_name']);
  $user_email           = sanitize_text_field($_REQUEST['user_email']);
  $event_date           = sanitize_text_field($_REQUEST['event_date']);
  $event_id             = sanitize_text_field($_REQUEST['event_id']);
  $check                = mepw_check_in_list($event_id, $user_email, $event_date);
  $wt = [];
  $wt['event_id']       = $event_id;
  $wt['user_name']      = $user_name;
  $wt['user_email']     = $user_email;
  $wt['event_datetime'] = $event_date;
  $wt['status']       = 1;
  $wt['email_status'] = 0;
  if ($check == 0) {
    $add_waitlist = mep_wl_create_new_waitlist($wt);
    if ($add_waitlist) {
      echo '<span class="mepw-success">';
      _e('Thank you for joining the waitlist. We will be in contact if a place becomes available.', 'mage-eventpress-waitlist');
      echo '</span>';
    }
  } else {
    echo '<span class="mepw-error">';
    _e('You already in the waitlist.', 'mage-eventpress-waitlist');
    echo '</span>';
  }
}
  die();
}




add_action('mep_after_no_seat_notice', 'mepw_show_waitlist_form');
function mepw_show_waitlist_form($event_id)
{
  global $post, $wpdb;
  ob_start();
  
  $_check_status            = get_post_meta($event_id, 'mep_show_waitlist', true) ? get_post_meta($event_id, 'mep_show_waitlist', true) : 'off';
  $check_status             =  apply_filters('mep_waitlist_status',$_check_status,$event_id);
  $mep_waitlist_status      = $check_status;

  if ($mep_waitlist_status == 'on') {
    $recurring              = get_post_meta($event_id, 'mep_enable_recurring', true) ? get_post_meta($event_id, 'mep_enable_recurring', true) : 'no';

    if ($recurring == 'no') {
      $event_date = get_post_meta($event_id, 'event_start_datetime', true);
  ?>
      <input type="hidden" name="mep_recurring_date" id="mep_recurring_date" value='<?php echo $event_date; ?>'>
    <?php } ?>

    <div class="mep-wailist-form">
      <div id='mep_waitlist_sec'>
        <p><?php _e('Join the waitlist to be emailed when seats becomes available', 'mage-eventpress-waitlist'); ?></p>
        <input type="hidden" name="mepw_event_id" id='mepw_event_id' value='<?php echo $event_id; ?>'>
        <input type="text" name="mepw_user_name" id='mepw_user_name' required placeholder="<?php _e('Your Name', 'mage-eventpress-waitlist'); ?>">
        <input type="email" required name="mepw_user_email" id='mepw_user_email' placeholder="<?php _e('Your Email Address', 'mage-eventpress-waitlist'); ?>">
        <button type='submit' id="mep_add_waitlist_button" class="mep-waitlist" name="mepw_add_waitlist"><?php _e('Join Waitlist', 'mage-eventpress-waitlist'); ?></button>
      </div>
    </div>
    <script>
      jQuery('#mep_add_waitlist_button').on('click', function() {

        var event_id = jQuery('#mepw_event_id').val();
        var user_name = jQuery('#mepw_user_name').val();
        var user_email = jQuery('#mepw_user_email').val();
        var event_date = jQuery('#mep_everyday_ticket_time').val() ? jQuery('#mep_everyday_ticket_time').val() : jQuery('#mep_recurring_date').val();
        var event_date = event_date ? event_date : jQuery('#mep_everyday_datepicker').val();
        // alert(event_date);
        if (user_name && user_email) {
          jQuery.ajax({
            type: 'POST',            
            url: ajaxurl,
            data: {
              "action"      : "mep_wl_ajax_waitlist_date",
              "nonce"       : '<?php echo wp_create_nonce('mep-waitlist-add-nonce'); ?>',    
              "event_id"    : event_id,
              "user_name"   : user_name,
              "user_email"  : user_email,
              "event_date"  : event_date
            },
            beforeSend: function() {
              // jQuery('#event_attendee_filter_btn').hide(); 
              jQuery('#mep_waitlist_sec').html("<?php _e('Adding to waitlist, Please Wait...', 'mage-eventpress-waitlist'); ?>");
            },
            success: function(data) {
              // jQuery('#event_attendee_filter_btn').show(); 
              jQuery('#mep_waitlist_sec').html(data);
            }
          });
        } else {
          alert('<?php _e('Please Enter your Name & Email Address', 'mage-eventpress-waitlist'); ?>');
        }
        return false;
      });
    </script>
  <?php
  }
  $content = ob_get_clean();
  echo $content;
}







// Add the custom columns to the book post type:
add_filter('manage_mep_events_posts_columns', 'mepw_pro_set_custom_edit_event_columns');
function mepw_pro_set_custom_edit_event_columns($columns)
{
  unset($columns['date']);
  $columns['mep_waitlist'] = __('WaitList', 'mage-eventpress-waitlist');
  return $columns;
}


// Add the data to the custom columns for the book post type:
add_action('manage_mep_events_posts_custom_column', 'mepw_events_attendees_column', 10, 2);
function mepw_events_attendees_column($column, $post_id)
{
  switch ($column) {

    case 'mep_waitlist':
      echo '<a class="button button-primary button-large" href="' . get_site_url() . '/wp-admin/edit.php?post_type=mep_events&page=view_waitlist&event=' . $post_id . '">WaitList</a>';
      break;
  }
}



function mepw_set_html_in_wpmail()
{
  return "text/html";
}
// add_filter( 'wp_mail_content_type','mepw_set_html_in_wpmail' );






add_action('add_meta_boxes', 'mepw_event_meta_box_add');
function mepw_event_meta_box_add()
{

  add_meta_box('mep-event-waitlist-on-off', __('Show Waitlist Form?', 'mage-eventpress-waitlist'), 'mep_event_waitlist_cb', 'mep_events', 'side', 'low');
}


function mep_event_waitlist_cb($post)
{
  $values = get_post_custom($post->ID);
  ?>
  <div class='sec'>
    <label for="mep_ev_209882123"> <?php _e('Show Waitlist Form?', 'mage-eventpress-waitlist'); ?>
      <label class="switch">
        <input type="checkbox" id="mep_ev_209882123" name='mep_show_waitlist' <?php if (array_key_exists('mep_show_waitlist', $values)) {
                                                                                if ($values['mep_show_waitlist'][0] == 'on') {
                                                                                  echo 'checked';
                                                                                }
                                                                              } else {
                                                                                echo 'checked';
                                                                              } ?> />
        <span class="slider round"></span>
      </label>
    </label>
  </div>
  <?php
}


add_action('save_post', 'mepw_waitlist_status_meta_save');
function mepw_waitlist_status_meta_save($post_id)
{

	if (
		!isset($_POST['mep_event_ticket_type_nonce']) ||
		!wp_verify_nonce($_POST['mep_event_ticket_type_nonce'], 'mep_event_ticket_type_nonce')
	) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	if (get_post_type($post_id) == 'mep_events') {
    $mep_waitlist_status     = isset($_POST['mep_show_waitlist']) ? strip_tags($_POST['mep_show_waitlist']) : 'off';
    update_post_meta($post_id, 'mep_show_waitlist', $mep_waitlist_status);
  }
}







add_filter('mep_translation_string_arr', 'mep_wl_translation_strings_reg');
function mep_wl_translation_strings_reg($default_translation)
{
  $waitlist_translation = array(
    array(
      'name' => 'mep_event_waitlist_ribbon_text',
      'label' => __('No Seat Available, Join our Waitlist', 'mage-eventpress-waitlist'),
      'desc' => __('Enter Text For No Seat Available, Join our Waitlist Text', 'mage-eventpress-waitlist'),
      'type' => 'text',
      'default' => 'No Seat Available, Join our Waitlist'
    )
  );
  return array_merge($default_translation, $waitlist_translation);
}


add_action('mep_event_list_loop_header', 'mep_wl_label');
function mep_wl_label($event_id)
{
  $event_date      = apply_filters('mep_event_upcoming_date_filter', get_post_meta($event_id, 'event_start_datetime', true), $event_id);
  $show_waitlist   = get_post_meta($event_id, 'mep_show_waitlist', true) ? get_post_meta($event_id, 'mep_show_waitlist', true) : 'off';
  $available_seat  = mep_get_event_total_available_seat($event_id, $event_date);

  if ($available_seat <= 0 && $show_waitlist == 'on') {
  ?>
    <span class='mep_waitlist_label mep-tem3-title-sec'><?php echo mep_get_option('mep_event_waitlist_ribbon_text', 'label_setting_sec', __('No Seat Available, Join our Waitlist', 'mage-eventpress-waitlist')); ?></span>
<?php
  }
}

function mep_wl_create_new_waitlist($data = [])
{
  $event_id             = $data['event_id'];
  $user_name            = $data['user_name'];
  $user_email           = $data['user_email'];
  $event_datetime       = $data['event_datetime'];
  $status               = $data['status'];
  $email_status         = $data['email_status'];

  $new_post = array(
    'post_title'    =>   $user_name,
    'post_content'  =>   '',
    'post_category' =>   array(),  // Usable for custom taxonomies too
    'tags_input'    =>   array(),
    'post_status'   =>   'publish', // Choose: publish, preview, future, draft, etc.
    'post_type'     =>   'mep_event_waitlist'  //'post',page' or use a custom post type if you want to
  );

  $pid                = wp_insert_post($new_post);
  update_post_meta($pid, 'event_id', $event_id);
  update_post_meta($pid, 'user_name', $user_name);
  update_post_meta($pid, 'user_email', $user_email);
  update_post_meta($pid, 'event_datetime', $event_datetime);
  update_post_meta($pid, 'status', $status);
  update_post_meta($pid, 'email_status', $email_status);
  return $pid;
}



function mep_wl_get_event_date($order_id,$event_id)
{
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
          'key'       => 'ea_order_id',
          'value'     => $order_id,
          'compare'   => '='
        )
      )
    )
  );

  $loop = new WP_Query($args);
  foreach ($loop->posts as $postss) {
    $attendee_id = $postss->ID;
    $date = get_post_meta($attendee_id, 'ea_event_date', true);
  }
  // echo '<pre>'; print_r($loop); echo '</pre>';
  // echo '<pre>'; print_r($loop->post_count); echo '</pre>';
  // echo $date;
  return $date;
}

function mepw_delete_waitlist($event_id, $user_email, $event_date)
{
  $args = array(
    'post_type' => 'mep_event_waitlist',
    'posts_per_page' => -1,
    'meta_query' => array(
      'relation' => 'AND',
      array(
        'relation' => 'AND',
        array(
          'key'       => 'event_id',
          'value'     => $event_id,
          'compare'   => '='
        ),
        array(
          'key'       => 'user_email',
          'value'     => $user_email,
          'compare'   => '='
        ),
        array(
          'key'       => 'event_datetime',
          'value'     => $event_date,
          'compare'   => 'LIKE'
        ),
        array(
          'key'       => 'status',
          'value'     => 1,
          'compare'   => '='
        )
      )
    )
  );
  $loop = new WP_Query($args);

  foreach ($loop->posts as $waitlist) {
    $waitlist_id = $waitlist->ID;
    update_post_meta($waitlist_id, 'status', 2);
  }
}

add_action('mep_wc_order_status_change', 'mep_spp_event_sent_pdf', 10, 3);
function mep_spp_event_sent_pdf($order_status, $event_id, $order_id)
{
  $order            = wc_get_order($order_id);
  $order_meta       = get_post_meta($order_id);
  if (get_post_type($event_id) == 'mep_events' && ($order_status == 'processing' || $order_status == 'completed')) {
    $user_email     =  get_post_meta($order_id, '_billing_email', true);
    $event_date     = mep_wl_get_event_date($order_id, $event_id);
    $check_waitlist = mepw_check_in_list($event_id, $user_email, $event_date);
    if ($check_waitlist > 0) {
      mepw_delete_waitlist($event_id, $user_email, $event_date);
    }
  }
}
