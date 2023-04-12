<?php
if ( ! defined('ABSPATH')) exit;  // if direct access 
 
 
 
if (!function_exists('mep_get_mpdf_support_version')) {
  function mep_get_mpdf_support_version(){
    // if(is_plugin_active( 'magepeople-pdf-support-master/mage-pdf.php' )){
    //   $data = get_plugin_data( ABSPATH . "wp-content/plugins/magepeople-pdf-support-master/mage-pdf.php", false, false );
    //   return $data['Version'];
    // }else{
    //   return 1;
    // }
    
    return 2;
  }
}
 
 
 
 
 
/**
 * The below functions are for PDF Templating
 */
function mep_pdf_file_path($type){
    $extrathemefile     = mep_get_option('mep_pdf_extra_service_theme', 'mep_pdf_gen_settings', 'default.php');
        if($type == 'main'){
           $themefile          = mep_get_option('mep_pdf_theme', 'mep_pdf_gen_settings', 'default.php');
            $template_path      = get_stylesheet_directory().'/mage-events-pdf-themes/';
            $default_path       = plugin_dir_path( __DIR__ ) . 'templates/pdf-theme/'; 
              if (is_dir($template_path)) {
                 $thedir = $template_path;
              }else{
                 $thedir =$default_path;
              }
            $themedir = $thedir.$themefile;
            if(is_file($themedir)){
                $themedir = $themedir;
            }else{
                $themedir =  $default_path.'default.php';
            }            
        }elseif($type == 'extra-service'){
            $template_path      = get_stylesheet_directory().'/mage-events-pdf-themes/extra-service/';
            $default_path       = plugin_dir_path( __DIR__ ) . 'templates/pdf-theme/extra-service/'; 
            
              if (is_dir($template_path)) {
                 $thedir = $template_path;
              }else{
                 $thedir =$default_path;
              }
            $themedir = $thedir.$extrathemefile;
            
            if(is_file($themedir)){
                $themedir =     $themedir;
            }else{
                $themedir =     $default_path.'default.php';
            }
        }          
return $themedir;
}


function mep_pdf_single_template_make_arr($arr,$ticket_id){
    $event_id = [];

    foreach ($arr as $_ticket) {   
        $_eid = $ticket_id;        
        $event_id[] = get_post_meta($_eid,'ea_event_id',true);
    }


$ticket_type_arr    = [];
$event_id_arr       = is_array($arr) && count($arr) == 1 ? array(get_post_meta($ticket_id,'ea_event_id',true)) : array_unique($event_id);
// print_r($event_id_arr);
$cn = 0;
foreach ($event_id_arr as $eid_arr) {
    foreach ($arr as $ticket) {            
        $eid                                    = is_object($arr) ? $arr->ID : $ticket->ID;  
        $ticket_type_arr[$cn]['ticket_type']    = get_post_meta($eid,'ea_ticket_type',true);
        $ticket_type_arr[$cn]['order_id']       = get_post_meta($eid,'ea_order_id',true);
        $ticket_type_arr[$cn]['event_id']       = $eid_arr;        
        $ticket_type_arr[$cn]['ticket_id']      = $ticket_id;        
        $cn++;
    }
}

$input = array_map("unserialize", array_unique(array_map("serialize", $ticket_type_arr)));
return $input;
}

function mep_pdf_get_single_attendee_id($order_id,$event_id){
    $loop = mep_attendee_theme_query($order_id,$event_id);
    $count =1;
    foreach ($loop->posts as $attendee) {
        # code...
        if($count == 1){
            $attndee_id = $attendee->ID;        
        }       
    }
    return $attndee_id;
}

add_action('mep_pdf_template_single_body','mep_pdf_template_single_body_theme',10,2);
function mep_pdf_template_single_body_theme($arr,$order_id){
       
$arr = is_array($arr) ? $arr : array($arr);

    $themefile          = mep_get_option('mep_pdf_theme', 'mep_pdf_gen_settings', 'default.php');
    $main_pdf_theme     = mep_pdf_file_path('main');
    $ticket_id          = count($arr) == 1 ? $arr[0]->ID : $arr[0]->ID;
    $single_theme_arr   = mep_pdf_single_template_make_arr($arr,$ticket_id);    

// print_r($single_theme_arr);


    foreach($single_theme_arr as $ticket_info){
        $event_id       = $ticket_info['event_id'];
        $order_id       = $ticket_info['order_id'];
        $ticket_type    = $ticket_info['ticket_type']; 
        $event_date     = $ticket_info['event_date']; 
        $ticket_id      = mep_pdf_get_single_attendee_id($order_id,$event_id); 
        $total_tkt      = (int) mep_pdf_get_single_names($order_id,$event_id,$ticket_type,'ea_name','count');
        if($total_tkt > 0){
            require($main_pdf_theme);    
        }   
    }
}

add_action('mep_pdf_template_body','mep_include_pdf_themes',10,2);
function mep_include_pdf_themes($ticket_id,$order_id){
    $event_id = get_post_meta($ticket_id,'ea_event_id',true);
    $order_id = get_post_meta($ticket_id,'ea_order_id',true);
    $ticket_type = get_post_meta($ticket_id,'ea_ticket_type',true);
    $themefile   = mep_get_option('mep_pdf_theme', 'mep_pdf_gen_settings', 'default.php');
    $main_pdf_theme = mep_pdf_file_path('main');
    require($main_pdf_theme);
}

add_action('mep_extra_service_pdf_content','mep_include_pdf_extra_service_themes',10,2);
function mep_include_pdf_extra_service_themes($event_extra_service,$order_id){
    $themefile   = mep_get_option('mep_pdf_extra_service_theme', 'mep_pdf_gen_settings', 'default.php');
    $main_pdf_theme = mep_pdf_file_path('extra-service');
    require($main_pdf_theme);    
}

function mep_event_pdf_theme_list(){
    $template_name      = 'index.php';
    $template_path      = get_stylesheet_directory().'/mage-events-pdf-themes/';
    $default_path       = plugin_dir_path( __DIR__ ) . 'templates/pdf-theme/'; 
    
      if (is_dir($template_path)) {
        $thedir = glob($template_path."*");
      }else{
        $thedir = glob($default_path."*");
      }
  $theme = array();
  foreach($thedir as $filename){
      if(is_file($filename)){
        $file = basename($filename);
        $naame = str_replace("?>","",strip_tags(file_get_contents($filename, FALSE, NULL, 24, 14))); 
      }   
       $theme[$file] = $naame;
  }
  return $theme;
  }
 
 
 function mep_event_pdf_extra_service_theme_list(){
    $template_name      = 'index.php';
    $template_path      = get_stylesheet_directory().'/mage-events-pdf-themes/extra-service/';
    $default_path       = plugin_dir_path( __DIR__ ) . 'templates/pdf-theme/extra-service/'; 
     if (is_dir($template_path)) {
        $thedir = glob($template_path."*");
      }else{
        $thedir = glob($default_path."*");
      }
 
  $theme = array();
  foreach($thedir as $filename){
      if(is_file($filename)){
        $file = basename($filename);
        $naame = str_replace("?>","",strip_tags(file_get_contents($filename, FALSE, NULL, 24, 44))); 
      }   
       $theme[$file] = $naame;
  }
  return $theme;
  }

/**
 * The taxdomain is loading .......
 */
add_action( 'init', 'mep_pdf_language_load');
function mep_pdf_language_load(){
    $plugin_dir = basename(dirname(__DIR__))."/languages/";
    load_plugin_textdomain( 'mage-eventpress-pdf', false, $plugin_dir );
}

/**
 * The PDF file genarate function, It will work with the ajax power.
 */
function mep_events_generate_pdf(){  
    if( empty( $_GET['action'] ) || ! check_admin_referer( $_GET['action'] ) ) {
        wp_die( __( 'You do not have sufficient permissions to access this page.', 'woo-invoice' ) );
    }
    $order_id       = isset( $_GET[ 'order_id' ] ) ? sanitize_text_field( $_GET[ 'order_id' ] ) : ''; 
    $document_type  = isset( $_GET[ 'document_type' ] ) ? sanitize_text_field( $_GET[ 'document_type' ] ) : ''; 
    global $wbtm;
    header("Content-Type: application/pdf; charset=UTF-8");
    echo $wbtm->generate_pdf( $order_id, "", true, false );
    exit;
}
add_action('wp_ajax_generate_pdf', 'mep_events_generate_pdf');
add_action('wp_ajax_nopriv_generate_pdf', 'mep_events_generate_pdf' );

/**
 * This function will be add the pdf download link into the thank page, but the button will only display if the order status matching with the email sending status.
 */
function mep_events_show_ticket_download_after_order( $order_id ){
    global $wbtm;
    $wbtm_email_status = mep_get_option('mep_pdf_email_status', 'mep_pdf_email_settings', array('completed'));
    
    $wbtm_email_status = empty( $wbtm_email_status ) ? array('completed') : $wbtm_email_status;   
    
    
    $__wbtm_email_status = empty( $wbtm_email_status ) ? array('completed') : $wbtm_email_status;   
                    
                    $wbtm_email_status = is_array($__wbtm_email_status) ? $__wbtm_email_status : array($__wbtm_email_status); 
                    
    
    $order = wc_get_order( $order_id );
    $payment_method = $order->get_payment_method();
    foreach ( $order->get_items() as $item_id => $item_values ) {
        $item_id        = $item_id;
    }
    $event_id = wc_get_order_item_meta($item_id,'event_id',true);
    if (get_post_type($event_id) == 'mep_events') { 
        if( in_array( $order->get_status(), $wbtm_email_status ) || $payment_method == 'paypal') {
            $download_url = $wbtm->get_invoice_ajax_url( array( 'order_id' => $order_id ) );
            printf( '<a class="wbtm-button" href="%s">%s</a>', $download_url, __('Download Ticket') );
        }
    }
}
add_action( 'woocommerce_thankyou', 'mep_events_show_ticket_download_after_order', 10, 1 );

/**
 * This is the magical email senting function, It will fire on every time when a order status is changed, If the order status is matching with the pdf email sending status then the pdf ticket will be sent to selected email address
 */

add_action('mep_wc_order_status_change', 'mep_event_sent_pdf', 10, 3);
function mep_event_sent_pdf( $order_status,$event_id,$order_id ) {
    global $wpdb,$wbtm;
       // Getting an instance of the order object
        $order              = wc_get_order( $order_id );
        $order_meta         = get_post_meta($order_id); 
        $wbtm_email_status  = mep_get_option('mep_pdf_email_status', 'mep_pdf_email_settings', array());
    
        if (get_post_type($event_id) == 'mep_events') { 
                if(!empty($wbtm_email_status)){                    
                    $__wbtm_email_status = empty( $wbtm_email_status ) ? array() : $wbtm_email_status;   
                    
                    $wbtm_email_status = is_array($__wbtm_email_status) ? $__wbtm_email_status : array($__wbtm_email_status);    
                                                            
                        if( in_array( $order_status, $wbtm_email_status ) ) {
                            $wbtm->send_email( $order_id, $order );
                        }                           
                
            }else{
                // $wbtm->send_email( $order_id, $order );
            }
}      
}
    


// add_action('woocommerce_order_status_changed', 'mep_event_sent_pdf', 10, 4);
function mep_event_sent_pdf_old( $order_id, $from_status, $to_status, $order ) {
global $wpdb,$wbtm;
   // Getting an instance of the order object
    $order      = wc_get_order( $order_id );
    $order_meta = get_post_meta($order_id); 
    $wbtm_email_status = mep_get_option('mep_pdf_email_status', 'mep_pdf_email_settings', array());

    foreach ( $order->get_items() as $item_id => $item_values ) {
        $item_id        = $item_id;
    }
    $event_id = wc_get_order_item_meta($item_id,'event_id',true);

    if (get_post_type($event_id) == 'mep_events') { 
        if(!empty($wbtm_email_status)){
            $wbtm_email_status = empty( $wbtm_email_status ) ? array() : $wbtm_email_status;
            foreach ( $order->get_items() as $item_id => $item_values ) {
                $item_quantity = $item_values->get_quantity();
                $item_id = $item_id;
            }
            if($order->has_status( 'processing' ) || $order->has_status( 'pending' )) {
                if( in_array( $order->get_status(), $wbtm_email_status ) ) {
                    $wbtm->send_email( $order_id, $order );
                }
            }
            if($order->has_status( 'cancelled' )) {
                if( in_array( $order->get_status(), $wbtm_email_status ) ) {
                    $wbtm->send_email( $order_id, $order );
                } 
            }
            if( $order->has_status( 'completed' )) {
                if( in_array( $order->get_status(), $wbtm_email_status ) ) {
                    $wbtm->send_email( $order_id, $order );
                }
        }
        }else{
            $wbtm->send_email( $order_id, $order );
        }
    }                                                       
}

/**
 * Adding a Download Button into the Order List of User My Account Order List table
 */
function mep_events_my_account_pdf_download_buttons( $actions, $order ){ 
    global $wbtm;
    $status = $order->get_status();
    if($status == 'completed' || $status == 'processing'){
        $actions['wbtm-downloads'] = array(
        'url'   => $wbtm->get_invoice_ajax_url( array( 'order_id' => $order->get_id() ) ),
        'name'  => __( 'Download Ticket' ),
    );
    }else{
        $actions = $actions;
    }
    return $actions;
}
add_filter( 'woocommerce_my_account_my_orders_actions', 'mep_events_my_account_pdf_download_buttons', 10, 2 );

// Add the custom columns to the book post type:
add_filter( 'manage_mep_events_attendees_posts_columns', 'mep_pdfdl_set_custom_events_attendees_columns' );
function mep_pdfdl_set_custom_events_attendees_columns($columns) {
    $columns['mep_dl_pdf'] = __( 'Download', 'mage-eventpress-pdf' );
    return $columns;
}

// Add the data to the custom columns for the book post type:
add_action( 'manage_mep_events_attendees_posts_custom_column' , 'mep_dl_pdf_events_attendees_column', 10, 2 );
function mep_dl_pdf_events_attendees_column( $column, $post_id ) {
    global $post,$wbtm;
    $order_id = get_post_meta( $post_id, 'ea_order_id', true );
    $download_url = $wbtm->get_invoice_ajax_url( array( 'order_id' => $order_id ) );

    
    switch ( $column ) {

        case 'mep_dl_pdf' :
        printf( '<a style="display: block;margin: 0 auto;background: #666;width: auto;text-align: center;color: #ffffff;padding: 5px;border-radius: 5px;" href="%s">%s</a>', $download_url, __('<span class="dashicons dashicons-tickets-alt"></span> Download Ticket') );
        ?>
     
        <?php
        break;

    }
}


add_action('mep_attendee_list_item_action_middile','mep_pdf_dl_btn_attendee_list');
function mep_pdf_dl_btn_attendee_list($attendee_id){
    global $wbtm;
    $order_id = get_post_meta( $attendee_id, 'ea_order_id', true );
    $download_url = $wbtm->get_invoice_ajax_url( array( 'order_id' => $order_id ) );
    ?>
        <a href="<?php echo $download_url; ?>"  title='Download Ticket' target='_blank'><span class="dashicons dashicons-tickets-alt"></span></a>
        <a href='#' id='mep_pdf_resend' data-id='<?php echo $order_id; ?>' title="Re-send PDF ticket to Billing Email"><span class="dashicons dashicons-email"></span></a>
    <?php
}


add_action('mep_fb_attendee_list_script','mep_pdf_attendee_list_script');
function mep_pdf_attendee_list_script(){
    ?>
$(document).on('click', '#mep_pdf_resend', function() {

var order_id = $(this).data("id");

                    if (order_id > 0) {
                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl,
                            // url: ajaxurl,
                            data: {
                                "action": "mep_pdf_resend_email",
                                "order_id": order_id
                            },
                            beforeSend: function() {
                                jQuery('#before_attendee_table_info').html('<h5 class="mep-processing"><?php _e('Please wait! PDF is Sending...','mep-form-builder'); ?></h5>');
                            },
                            success: function(data) {
                                jQuery('#before_attendee_table_info').html('<h5 class="mep-processing">'+data+'</h5>');                              
                            }
                        });
                    } 

return false;
});
<?php
}

add_action('wp_ajax_mep_pdf_resend_email', 'mep_pdf_resend_email');
function mep_pdf_resend_email(){
    global $wbtm;
    $order_id       = strip_tags($_REQUEST['order_id']);
    $order          = wc_get_order( $order_id );
    $order_meta     = get_post_meta($order_id); 
    $send_email     = mep_pdf_send_pdf_ticket($order_id);
    if($send_email){
        $customer_email = $order_meta['_billing_email'][0];
        _e("PDF ticket successfully sent to: $customer_email Order ID: $order_id ","mage-eventpress-pdf");
    }
    exit;
}



function mep_pdf_send_pdf_ticket($order_id){
    global $wbtm;
    $order          = wc_get_order( $order_id );
    $order_meta     = get_post_meta($order_id); 
    $wbtm->send_email( $order_id, $order );
    return true;
}





// Add pdf download button on Mage Event List
// add_action('manage_mep_events_posts_custom_column', 'mep_event_export_column', 10, 2);
function mep_event_export_column($column, $post_id) {

    global $post,$wbtm;

    $multi_date = get_post_meta($post_id,'mep_event_more_date',true) ? get_post_meta($post_id,'mep_event_more_date',true) : array();
    $recurring  = get_post_meta($post_id, 'mep_enable_recurring', true) ? get_post_meta($post_id, 'mep_enable_recurring', true) : 'no';

    $event_id = $post_id;
    $post_type = 'mep_events_attendees';
    $action = 'generate_attendee_pdf';
    $ea_event_date= get_post_meta($post_id,'event_start_date',true).' '.get_post_meta($post_id,'event_start_time',true);

    $args = array(
        'action' => $action,
        'event_id' => $event_id,
        'post_type' => $post_type,
        'ea_event_date' => $ea_event_date,
    );
    $build_url  = http_build_query( $args );
    $nonce_url  = wp_nonce_url( admin_url( "admin-ajax.php?" . $build_url ), $args['action'] );

    $download_url = $nonce_url;

    switch ( $column ) {

        case 'mep_csv_export':

            printf( '<a class="button button-primary button-large" style="margin-top:2px" href="%s">%s</a>', $download_url, __('Export PDF') );

        break;

    }
}



function mep_attendee_generate_pdf(){
    if( empty( $_GET['action'] ) || ! check_admin_referer( $_GET['action'] ) ) {
        wp_die( __( 'You do not have sufficient permissions to access this page.', 'woo-invoice' ) );
    }
    $event_id     = isset( $_GET[ 'event_id' ] ) ? sanitize_text_field( $_GET[ 'event_id' ] ) : '';
    $post_type  = isset( $_GET[ 'post_type' ] ) ? sanitize_text_field( $_GET[ 'post_type' ] ) : '';
    $ea_event_date  = isset( $_GET[ 'ea_event_date' ] ) ? sanitize_text_field( $_GET[ 'ea_event_date' ] ) : '';
    global $wbtm;
    header("Content-Type: application/pdf; charset=UTF-8");
    echo $wbtm->generate_attendee_pdf( $event_id, $post_type, $ea_event_date, "", false, false );
    exit;
}
add_action('wp_ajax_generate_attendee_pdf', 'mep_attendee_generate_pdf');



/**
 * Function for get the order price by Order ID.
 */
function mep_get_ticket_price($order_id){
    $order = wc_get_order($order_id);
    echo get_woocommerce_currency_symbol().$order->get_total();
}

/**
 * Gettings Event Location for PDF Ticket
 */
function mep_ev_location_pdf_ticket($event_id,$event_meta){
    $location_sts = get_post_meta($event_id,'mep_org_address',true);
    if($location_sts){
    $org_arr = get_the_terms( $event_id, 'mep_org' );
    $org_id = $org_arr[0]->term_id;
    ?>
              <?php echo get_term_meta( $org_id, 'org_location', true ); ?><br/>
    
              <?php if(get_term_meta( $org_id, 'org_street', true )){ ?><?php echo get_term_meta( $org_id, 'org_street', true ); ?> <?php } ?> 
    <br/>
              <?php if(get_term_meta( $org_id, 'org_city', true )){ ?> <?php echo get_term_meta( $org_id, 'org_city', true ); ?>, <?php } ?>
    <br/>
              <?php if(get_term_meta( $org_id, 'org_state', true )){ ?> <?php echo get_term_meta( $org_id, 'org_state', true ); ?>, <?php } ?>
    <br/>
              <?php if(get_term_meta( $org_id, 'org_postcode', true )){ ?> <?php echo get_term_meta( $org_id, 'org_postcode', true ); ?>, <?php } ?>
    <br/>
              <?php if(get_term_meta( $org_id, 'org_country', true )){ ?> <?php echo get_term_meta( $org_id, 'org_country', true ); ?> <?php } 
    }else{
    ?>
             <?php echo $event_meta['mep_location_venue'][0]; ?><br/>
              <?php if($event_meta['mep_street'][0]){ ?><?php echo $event_meta['mep_street'][0]; ?>, <?php } ?> 
    <br/>
              <?php if($event_meta['mep_city'][0]){ ?> <?php echo $event_meta['mep_city'][0]; ?> <?php } ?>
    <br/>
              <?php if($event_meta['mep_state'][0]){ ?> <?php echo $event_meta['mep_state'][0]; ?> <?php } ?>
    <br/>
              <?php if($event_meta['mep_postcode'][0]){ ?> <?php echo $event_meta['mep_postcode'][0]; ?> <?php } ?>
    <br/>
              <?php if($event_meta['mep_country'][0]){ ?> <?php echo $event_meta['mep_country'][0]; ?> <?php } 
             
        }
    
    }
/**
 * Function to get the Ticket Price by Ticket Type Name
 */
function mep_get_event_ticket_price($event,$type) {
        $ticket_type = get_post_meta($event,'mep_event_ticket_type',true);
      if($ticket_type){
          $all_ticket_tyle = get_post_meta($event,'mep_event_ticket_type',true);
             foreach ($all_ticket_tyle as $key => $val) {
             if ($val['option_name_t'] === $type) {
                 echo wc_price($val['option_price_t']);
                 // return $key;
             }
         }
      }else{
          echo wc_price(get_post_meta($event,'_price',true));
      }
      return null;
}


/**
 * Adding the PDF Download Button into the Order Details Page
 */
add_action( 'woocommerce_order_item_add_action_buttons', 'mep_pdf_show_download_btn_order_details');
function mep_pdf_show_download_btn_order_details( $order ){
    global $wbtm;
    $order_id = $order->get_id();

    $wbtm_email_status = mep_get_option('mep_pdf_email_status', 'mep_pdf_email_settings', array());
    $wbtm_email_status = empty( $wbtm_email_status ) ? array() : $wbtm_email_status;   
    $order = wc_get_order( $order_id );
    $payment_method = $order->get_payment_method();

    foreach ( $order->get_items() as $item_id => $item_values ) {
        $item_id        = $item_id;
    }
    $event_id = wc_get_order_item_meta($item_id,'event_id',true);



    
    if (get_post_type($event_id) == 'mep_events') { 
            $download_url = $wbtm->get_invoice_ajax_url( array( 'order_id' => $order_id ) );
            printf( '<a class="button refund-items" href="%s">%s</a>', $download_url, __('Download Event PDF Ticket') );
           ?>
 
              <span id='before_attendee_table_info' class='button refund-items'>  <a style='text-decoration: none;' href='#' class="" id='mep_pdf_resend' data-id='<?php echo $order_id; ?>' title="Re-send PDF ticket to Billing Email"><?php _e('Re Send PDF Ticket','mage-evnetpress-pdf'); ?></a>   </span>

<script>

(function($) {
            'use strict';
            jQuery(document).ready(function($) {

                $(document).on('click', '#mep_pdf_resend', function() {

                    var order_id = $(this).data("id");

                                        if (order_id > 0) {
                                            jQuery.ajax({
                                                type: 'POST',
                                                url: ajaxurl,
                                                // url: ajaxurl,
                                                data: {
                                                    "action": "mep_pdf_resend_email",
                                                    "order_id": order_id
                                                },
                                                beforeSend: function() {
                                                    jQuery('#before_attendee_table_info').html('<?php _e('Please wait! PDF is Sending...','mep-form-builder'); ?>');
                                                },
                                                success: function(data) {
                                                    jQuery('#before_attendee_table_info').html(data);                              
                                                }
                                            });
                                        } 

                    return false;
                    });
            });
        })(jQuery);                

</script>




           <?php
    }
}

function mep_convert_old_email_status($old){
    $s = [];
    foreach($old as $st){
        $s[$st] = $st;
    }
    return $s;
}


function getFileContent($filePath, $variables = array(), $print = false)
{
    $output = NULL;
    if(file_exists($filePath)){        
        extract($variables);        
        ob_start();        
        include $filePath;        
        $output = ob_get_clean();
    }
    if ($print) {
        print $output;
    }
    return $output;

}

function mep_attendee_theme_query($order_id,$event_id,$ticket_type=''){
    $type_filter = !empty($ticket_type) ? array(
        'key'       => 'ea_ticket_type',
        'value'     => $ticket_type,
        'compare'   => '='          
      ) : '';
    $processing_status_filter =    array(
      'key'       => 'ea_order_status',
      'value'     => 'processing',
      'compare'   => '='
    );
    $completed_status_filter = array(
      'key'       => 'ea_order_status',
      'value'     => 'completed',
      'compare'   => '='
    );    
    $args = array(
      'post_type'       => 'mep_events_attendees',
      'posts_per_page'  => -1,
      'meta_query'      => array(    
        'relation'      => 'AND',
        array(    
          'relation'    => 'AND',   
                  
          array(
            'key'       => 'ea_event_id',
            'value'     => $event_id,
            'compare'   => '='
          ),		        
          array(
            'key'       => 'ea_order_id',
            'value'     => $order_id,
            'compare'   => '='
          ),
          $type_filter
          ),array(    
            'relation' => 'OR',           
            $processing_status_filter,
            $completed_status_filter
            )
        )            
    ); 
$loop = new WP_Query($args);
return $loop;
}

function mep_pdf_get_single_names($order_id,$event_id,$ticket_type,$meta_name,$type='data'){
    $q = mep_attendee_theme_query($order_id,$event_id,$ticket_type);

// echo '<pre>';
// print_r($q);
// echo '</pre>';

    $_ticket_no = [];
    foreach($q->posts as $ticket){
        $tid = $ticket->ID;
        $name    =   get_post_meta($tid,$meta_name,true) ? get_post_meta($tid,$meta_name,true) : '';     
        if(!empty($name)){
            $_ticket_no[] = $name;
        }
    }
    $ticket_no = array_unique($_ticket_no);

    if(sizeof($ticket_no) > 0){
        if($type == 'data'){
            return implode(',',$ticket_no);
        }elseif($type == 'count'){
            return count($_ticket_no);
        }else{
            return implode(',',$ticket_no);
        }
    }else{
        return null;
    }
}

function mep_pdf_get_single_ticket_number($order_id,$event_id,$ticket_type=''){    
    $q = mep_attendee_theme_query($order_id,$event_id,$ticket_type);
    $ticket_no = [];
    foreach($q->posts as $ticket){
        $tid = $ticket->ID;
        $user_id    =   get_post_meta($tid,'ea_user_id',true);
        $order_id    =   get_post_meta($tid,'ea_order_id',true);
        $event_id    =   get_post_meta($tid,'ea_event_id',true);        
        $ticket_no[] = $user_id.$order_id.$event_id.$tid;        
    }
    return implode(',',$ticket_no);
}

add_action('mep_pdf_ticket_after_attendee_info','mep_pdf_display_billing_info');
function mep_pdf_display_billing_info($ticket_id){
    $order_id       =   get_post_meta($ticket_id,'ea_order_id',true) ? get_post_meta($ticket_id,'ea_order_id',true) : '';
    if($order_id){
        $order              = wc_get_order( $order_id );
        $order_meta         = get_post_meta($order_id); 
        $order_status       = $order->get_status();
        $billing_fname      = mep_get_option('mep_pdf_billing_first_name', 'mep_pdf_gen_settings', 'off');        
        $billing_email      = mep_get_option('mep_pdf_billing_email', 'mep_pdf_gen_settings', 'off');
        $billing_phone      = mep_get_option('mep_pdf_billing_phone', 'mep_pdf_gen_settings', 'off');
        $billing_company    = mep_get_option('mep_pdf_billing_company_name', 'mep_pdf_gen_settings', 'off');
        $billing_add_1      = mep_get_option('mep_pdf_billing_address_1', 'mep_pdf_gen_settings', 'off');        
        $billing_city       = mep_get_option('mep_pdf_billing_city', 'mep_pdf_gen_settings', 'off');
        $billing_state      = mep_get_option('mep_pdf_billing_state', 'mep_pdf_gen_settings', 'off');
        $billing_postcode   = mep_get_option('mep_pdf_billing_postcode', 'mep_pdf_gen_settings', 'off');
        $billing_country    = mep_get_option('mep_pdf_billing_country', 'mep_pdf_gen_settings', 'off');
        $billing_payment    = mep_get_option('mep_pdf_billing_method', 'mep_pdf_gen_settings', 'off');



        

  // Billing Information 
    $first_name       = isset($order_meta['_billing_first_name'][0]) && array_key_exists('_billing_first_name',$order_meta) ? $order_meta['_billing_first_name'][0] : '';
    $last_name        = isset($order_meta['_billing_last_name'][0]) && array_key_exists('_billing_last_name',$order_meta) ? $order_meta['_billing_last_name'][0] : '';
    
    if($billing_fname == 'on' && array_key_exists('_billing_first_name',$order_meta) && array_key_exists('_billing_last_name',$order_meta) ){
        echo '<li><strong>'.__('Billing Name: ','mage-eventpress-pdf').'</strong>'.$first_name.' '.$last_name.'</li>';
    }

    if($billing_email == 'on' && array_key_exists('_billing_email',$order_meta)){
        echo '<li><strong>'.__('Billing Email: ','mage-eventpress-pdf').'</strong>'.$order_meta['_billing_email'][0].'</li>';
    }

    if($billing_phone == 'on' && array_key_exists('_billing_phone',$order_meta)){
        echo '<li><strong>'.__('Billing Phone: ','mage-eventpress-pdf').'</strong>'.$order_meta['_billing_phone'][0].'</li>';
    }

    if($billing_company == 'on' && array_key_exists('_billing_company',$order_meta)){
        echo '<li><strong>'.__('Billing Company: ','mage-eventpress-pdf').'</strong>'.$order_meta['_billing_company'][0].'</li>';
    }

    if($billing_add_1 == 'on' && array_key_exists('_billing_address_1',$order_meta)){
        echo '<li><strong>'.__('Billing Address: ','mage-eventpress-pdf').'</strong>'.$order_meta['_billing_address_1'][0].' '.$order_meta['_billing_address_2'][0].'</li>';
    }

    if($billing_city == 'on' && array_key_exists('_billing_city',$order_meta)){
        echo '<li><strong>'.__('Billing City: ','mage-eventpress-pdf').'</strong>'.$order_meta['_billing_city'][0].'</li>';
    }

    if($billing_state == 'on' && array_key_exists('_billing_state',$order_meta)){
        echo '<li><strong>'.__('Billing State: ','mage-eventpress-pdf').'</strong>'.$order_meta['_billing_state'][0].'</li>';
    }

    if($billing_postcode == 'on' && array_key_exists('_billing_postcode',$order_meta)){
        echo '<li><strong>'.__('Billing Postcode: ','mage-eventpress-pdf').'</strong>'.$order_meta['_billing_postcode'][0].'</li>';
    }

    if($billing_country == 'on' && array_key_exists('_billing_country',$order_meta)){
        echo '<li><strong>'.__('Billing Country: ','mage-eventpress-pdf').'</strong>'.$order_meta['_billing_country'][0].'</li>';
    }

    if($billing_payment == 'on' && array_key_exists('_payment_method_title',$order_meta)){
        echo '<li><strong>'.__('Payment Method: ','mage-eventpress-pdf').'</strong>'.$order_meta['_payment_method_title'][0].'</li>';
    }

    }
}

function mep_get_event_ticket_price_raw($event,$type) {
    $ticket_type = get_post_meta($event,'mep_event_ticket_type',true) ? get_post_meta($event,'mep_event_ticket_type',true) : [];
    $price = 0;

    if(sizeof($ticket_type) > 0){      
            foreach ($ticket_type as $val) {
            if ($val['option_name_t'] === $type) {
                $price = $val['option_price_t'];             
            }
        }
    }
    
  return $price;
}


function mep_pdf_get_single_ticket_price($order_id,$event_id,$ticket_type=''){
    $q = mep_attendee_theme_query($order_id,$event_id,$ticket_type);
    $price = 0;
    foreach($q->posts as $ticket){
        $tid = $ticket->ID;
        $event_id       = get_post_meta($tid,'ea_event_id',true);
        $t_type         = get_post_meta($tid,'ea_ticket_type',true);
        $price          = mep_get_event_ticket_price_raw($event_id,$t_type) + $price;
    }
    return wc_price($price);
}


function mep_pdf_theme_single_get_date($order_id,$event_id,$ticket_type){
    $q = mep_attendee_theme_query($order_id,$event_id,$ticket_type);
    $date = '';
    foreach($q->posts as $ticket){
        $tid = $ticket->ID;        
        $date = get_post_meta($tid,'ea_event_date',true);        
    }
    return $date;
}





add_action('mep_event_status_notice_sec','mep_pdf_status_page_action');

function mep_pdf_status_page_action(){

    if(isset($_REQUEST['active_mep_pdf_support_plugin']) && $_REQUEST['active_mep_pdf_support_plugin'] == 'yes'){
        activate_plugin( 'magepeople-pdf-support-master/mage-pdf.php' );
    }

    if(isset($_REQUEST['install_mep_pdf_support_plugin']) && $_REQUEST['install_mep_pdf_support_plugin'] == 'yes'){
        include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
        include_once( ABSPATH . 'wp-admin/includes/file.php' );
        include_once( ABSPATH . 'wp-admin/includes/misc.php' );
        include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
        $upgrader = new Plugin_Upgrader( new Plugin_Installer_Skin( compact('title', 'url', 'nonce', 'plugin', 'api') ) );
        $upgrader->install('https://github.com/magepeopleteam/magepeople-pdf-support/archive/master.zip');
    }

}



add_action( 'admin_notices', 'mep_pdf_ticket_admin_notice_wc_not_active_a' );
function mep_pdf_ticket_admin_notice_wc_not_active_a() {
    
    $admin_url = get_admin_url();
    $active_mpdf_plugin_url = '<a href="'.$admin_url.'edit.php?post_type=mep_events&page=mep_event_status_page&active_mep_pdf_support_plugin=yes" class="page-title-action">Active Now</a>';
    $install_mpdf_plugin_url = '<a href="'.$admin_url.'edit.php?post_type=mep_events&page=mep_event_status_page&install_mep_pdf_support_plugin=yes" class="page-title-action">Install Now</a>';

    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    $plugin_dir  = ABSPATH . 'wp-content/plugins/magepeople-pdf-support-master';
    if (is_plugin_active('magepeople-pdf-support-master/mage-pdf.php')) {
        $message = null;
    }elseif(is_dir($plugin_dir)){        
        $message = '<span class="mep_warning"> <span class="dashicons dashicons-no-alt"></span>Mage PDF Support Plugin should be Activated But its only Installed But Not Actived '.$active_mpdf_plugin_url.'</span>';
    }else{        
        $message = '<span class="mep_warning"> <span class="dashicons dashicons-no-alt"></span>Mage PDF Support Plugin should be Installed & Activated But its not installed in your website  '.$install_mpdf_plugin_url.'</span>';        
    }
    if(!empty($message)){
        $class = 'notice notice-error';
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ),$message); 
    }
  }


  





function mep_pdf_support_install_check(){ 
    $admin_url = get_admin_url();
    $active_mpdf_plugin_url = '<a href="'.$admin_url.'edit.php?post_type=mep_events&page=mep_event_status_page&active_mep_pdf_support_plugin=yes" class="page-title-action">Active Now</a>';
    $install_mpdf_plugin_url = '<a href="'.$admin_url.'edit.php?post_type=mep_events&page=mep_event_status_page&install_mep_pdf_support_plugin=yes" class="page-title-action">Install Now</a>';
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    $plugin_dir  = ABSPATH . 'wp-content/plugins/magepeople-pdf-support-master';
    if (is_plugin_active('magepeople-pdf-support-master/mage-pdf.php')) {
      echo '<span class="mep_success"> <span class="dashicons dashicons-saved"></span>Yes</span>';
    }elseif(is_dir($plugin_dir)){        
        echo '<span class="mep_warning"> <span class="dashicons dashicons-no-alt"></span>Installed But Not Active '.$active_mpdf_plugin_url.'</span>';
    }else{        
        echo '<span class="mep_warning"> <span class="dashicons dashicons-no-alt"></span>Not Installed  '.$install_mpdf_plugin_url.'</span>';        
    }
}


add_action('mep_event_status_table_item_sec','mep_pdf_event_status_table_item');
function mep_pdf_event_status_table_item(){
    ?>
		<tr>
			<td data-export-label="WC Version">Woocommerce Event Manager Addon: PDF Tickets Installed:</td>
			<td class="help"><span class="woocommerce-help-tip"></span></td>
			<td><?php  echo '<span class="mep_success"> <span class="dashicons dashicons-saved"></span>Yes</span>';   ?></td>
		</tr>    
		<tr>
			<td data-export-label="WC Version">MagePeople PDF Support Installed:</td>
			<td class="help"><span class="woocommerce-help-tip"></span></td>
			<td><?php mep_pdf_support_install_check();   ?></td>
		</tr>    
		<tr>
			<td data-export-label="WC Version">Woocommerce Event Manager PDF library Used:</td>
			<td class="help"><span class="woocommerce-help-tip"></span></td>
			<td><?php  echo '<span class="mep_success"> <span class="dashicons dashicons-saved"></span>'.mep_get_option('mep_pdf_lib', 'mep_pdf_gen_settings', 'mpdf').'</span>';   ?></td>
		</tr>          
		<tr>
			<td data-export-label="WC Version">PHP GD library Installed:</td>
			<td class="help"><span class="woocommerce-help-tip"></span></td>
			<td><?php  if(extension_loaded('gd')){ echo '<span class="mep_success"> <span class="dashicons dashicons-saved"></span>Yes</span>'; }else{ echo '<span class="mep_error"> <span class="dashicons dashicons-no-alt"></span>No</span>'; }  ?></td>
		</tr>          
    <?php
}

add_action('mep_rpv_export_pdf_btn','mep_pdf_rpv_export_pdf_btn',10,3);
function mep_pdf_rpv_export_pdf_btn($start_date,$end_date,$events){
?>
    <button id="mep_rpv_export_pdf" data-start='<?php echo $start_date; ?>' data-end='<?php echo $end_date; ?>' data-event='<?php //echo $events; ?>'><span class="dashicons dashicons-media-document"></span> <?php _e('Export PDF','mage-eventpress-pdf'); ?></button>
<?php
}


add_action('mep_fb_after_attendee_edit_date_success','mep_pdf_fb_after_attendee_edit_date_success',10,4);
function mep_pdf_fb_after_attendee_edit_date_success($event_id,$a_query,$old_datetime,$new_datetime){

$a_query                = mep_fb_attendee_query($event_id, $new_datetime, -1);
$found_attendee         = $a_query->post_count;
if($found_attendee > 0){
?>
<style>
.mep_pdf_send_bulk_pdf_email {
    display: block;
    width: 500px;
    margin: 20px auto 0;
}

.mep_pdf_send_bulk_pdf_email label, .mep_pdf_send_bulk_pdf_email label input, .mep_pdf_send_bulk_pdf_email label textarea {
    display: block;
    width: 100%;
    text-align: left;    
    font-weight: normal;
    font-size: 16px;
    margin: 30px 0;
}
.mep_pdf_send_bulk_pdf_email label input, .mep_pdf_send_bulk_pdf_email label textarea {
    margin: 0;
}
</style>

<span id="send_pdf_ticket_btn" class='btn button mep-btn'><?php _e('Send PDF Ticket','mage-eventpress-pdf'); ?></span>

<div class='mep_pdf_send_bulk_pdf_email' style="display: none;">
    <label for="mep_pdf_subject">
    <?php _e('Subject:','mage-eventpress-pdf'); ?>
        <input type="text" name="mep_pdf_subject" id="mep_pdf_subject" value='<?php _e('Event Date Changed Notification','mage-eventpress-pdf'); ?>'>
    </label>
    <label for="mep_pdf_email_content">
    <?php _e('Email Body Text:','mage-eventpress-pdf'); ?>
        <textarea name="mep_pdf_email_content" id="mep_pdf_email_content" cols="30" rows="10">Hello {customer_name},
            Our Event {event_name} date is changed. The new date is {event_date}. 
            Please download pdf ticket in this attachment.
            Please carry out printing ticket on event spot. 

            Thanks
        </textarea>
    </label>
    <input type="hidden" id="mep_pdf_date_edit_event_id" value='<?php echo $event_id; ?>'>
    <input type="hidden" id="mep_pdf_date_edit_old_date" value='<?php echo $old_datetime; ?>'>
    <input type="hidden" id="mep_pdf_date_edit_new_date" value='<?php echo $new_datetime; ?>'>
    <button id="send_mep_bulk_pdf_ticket" type="button"><?php _e('Send PDF Ticket to Attendee','mage-eventpress-pdf'); ?></button>
</div>
<?php
}
}

add_action('wp_ajax_mep_pdf_ajax_send_pdf_ticket', 'mep_pdf_ajax_send_pdf_ticket');
function mep_pdf_ajax_send_pdf_ticket()
{
    $event_id               = sanitize_text_field($_REQUEST['event_id']);
    $old_date               = sanitize_text_field($_REQUEST['old_date']);
    $new_date               = sanitize_text_field($_REQUEST['new_date']);
    $subject                = sanitize_text_field($_REQUEST['subject']);
    $content                = sanitize_text_field($_REQUEST['content']);
    $a_query                = mep_fb_attendee_query($event_id, $new_date, -1);
    $found_attendee         = $a_query->post_count;

    if($found_attendee > 0){
        mep_pdf_send_bulk_ticket($a_query,$subject,$content);
        echo $found_attendee.' PDF Ticket Sent';
    }
    die();
}


function mep_pdf_send_bulk_ticket($query,$subject,$content){
    $order_ids = [];
    foreach ($query->posts as $value) {
            $attendee_id = $value->ID;
            $order_id = get_post_meta($attendee_id,'ea_order_id',true);
            $order_ids[] = $order_id;            
    }


    $orders = array_unique($order_ids);

    foreach ($orders as $order_id) {     
        mep_pdf_send_pdf_ticket_bulk($order_id,$subject,$content);
    }
}




function mep_pdf_send_pdf_ticket_bulk($order_id,$subject='',$content=''){
    global $wbtm;
    // echo $content;

    $order  = wc_get_order( $order_id );
    if( empty( $order_id ) || ! $order ) return false;
    $pdflibrary             = mep_get_option('mep_pdf_lib', 'mep_pdf_gen_settings', 'mpdf');
    $subject                = !empty($subject) ? $subject : mep_get_option('mep_pdf_email_subject', 'mep_pdf_email_settings', 'PDF Ticket Confirmation');
    $content                = !empty($content) ? $content : mep_get_option('mep_pdf_email_content', 'mep_pdf_email_settings', 'Here is PDF Ticket Confirmation Attachment');
    $form_name              = mep_get_option('mep_pdf_email_from_name', 'mep_pdf_email_settings', get_bloginfo( 'name' ));
    $form_email             = mep_get_option('mep_pdf_email_from', 'mep_pdf_email_settings', get_bloginfo( 'admin_email' ));
    $admin_notify_email     = mep_get_option('mep_pdf_admin_notification_email', 'mep_pdf_email_settings', '');
    $email_status           = mep_get_option('mep_pdf_send_status', 'mep_pdf_email_settings', 'yes');
    $attachments            = array();
    $headers                = array( 
        sprintf( "From: %s <%s>", $form_name,    $form_email ),
    );

    
    $attathment_file_url = $wbtm->get_pdf_ticket_attachment_file( $order_id, "", false, true );

    if( ! is_wp_error( $attathment_file_url ) ) $attachments[] = $attathment_file_url;

    $email_address_arr = array(
        $order->get_billing_email()             
    );
    $email_address = implode( ",", $email_address_arr );

    // Mail content dynamic
    $content = $wbtm->make_dynamic_mail_content($content, $order);
        
   $pdf_email_content = apply_filters('mep_event_pdf_email_text',$content,$order_id); 
    wp_mail( $email_address, $subject, nl2br($pdf_email_content), $headers, $attachments );
}

add_action('mep_fb_bulk_attendee_date_script','mep_pdf_admin_notification_email');
function mep_pdf_admin_notification_email(){
    ?>

                $(document).on('click', '#send_pdf_ticket_btn', function() {
                        jQuery(this).hide();
                        jQuery('.mep_pdf_send_bulk_pdf_email').show(1000);
                });

                $(document).on('click', '#send_mep_bulk_pdf_ticket', function() {
                    var event_id    = jQuery('#mep_pdf_date_edit_event_id').val();
                    var old_date    = jQuery('#mep_pdf_date_edit_old_date').val();
                    var new_date    = jQuery('#mep_pdf_date_edit_new_date').val();
                    var subject     = jQuery('#mep_pdf_subject').val();
                    var content     = jQuery('#mep_pdf_email_content').val();
                    // if ( (event_id > 0)  ) {                                        
                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl,
                            data: {
                                "action": "mep_pdf_ajax_send_pdf_ticket",
                                "event_id": event_id,
                                "old_date": old_date,
                                "new_date": new_date,
                                "subject": subject,
                                "content": content
                            },
                            beforeSend: function() {
                                jQuery('.mep_pdf_send_bulk_pdf_email').html('<?php _e("Sending PDF Tickets....","mage-eventpress-pdf"); ?>');
                            },
                            success: function(data) {
                                <!-- alert(data); -->
                                jQuery('.mep_pdf_send_bulk_pdf_email').html(data);
                            }
                        });
                    // }
                    return false;
                });
    <?php
}