<?php
/*
* @Author 		MagePeople
* Copyright: 	MagePeople
*/

use Dompdf\Dompdf; 

if ( ! defined('ABSPATH')) exit;  // if direct access 
if( ! class_exists( 'WBTM_Functions' ) ) {
class WBTM_Functions{

    public $settings = array();

    public function __construct(){
        $this->set_shortcodes();
        // $this->add_shortcodes();
        add_filter( 'wbtm_filter_settings', array( $this, 'setup_data' ), 10, 1 );
    }


    public function setup_data( $options ){
       
        $settings_data = new Pick_settings( array( 'add_in_menu' => false, 'pages' => $options, ) );

        foreach( $settings_data->get_option_ids() as $option_id ) {
            $this->settings[ $option_id ] = get_option( $option_id );
        }
        $this->settings = (object) $this->settings;

        return $options;
    }


    public function get_templates(){
        $templates      = array();
        $arr_templates  = apply_filters( 'wbtm_pdf_templates_internal', array(
            'flat'          => __('Flat','mage-eventpress-pdf'),
        ) );
        foreach( $arr_templates as $template_name => $label ){ 
            $templates[ $template_name ] = array(
                'label'      => $label,
                'thumb'      => sprintf( '%1$stemplates/pdf-templates/%2$s/%2$s.png', WBTM_PRO_PLUGIN_URL, $template_name ),
                'template'   => sprintf( '%1$stemplates/pdf-templates/%2$s/template.php', WBTM_PRO_PLUGIN_DIR, $template_name ),
                'stylesheet' => sprintf( '%1$stemplates/pdf-templates/%2$s/style.css', WBTM_PRO_PLUGIN_URL, $template_name ),
            );
        }
        return apply_filters( 'wbtm_pdf_templates', $templates );
    }


public function generate_pdf( $order_id = 0, $html = "", $download_pdf = true, $save_pdf = false ){
$pdflibrary         = 'mpdf';
if($pdflibrary == 'dompdf'){


    if( $order_id == 0 ) return new WP_Error( 'invalid_data', __('Invalid order id provided','mage-eventpress-pdf') );

    if (!class_exists('HTML5_Data')) {
         require WBTM_PRO_PLUGIN_DIR . 'dompdf/autoload.inc.php';
     }
 
         $html   = empty( $html ) ? $this->get_order_pdf_html( $order_id, 'pdf' ) : $html;
 
         $dompdf = new Dompdf( array(
             'isRemoteEnabled' => true,
         ) );
 
         $dompdf->setPaper( 'A4', 'portrait' ); 
         $dompdf->set_option('isHtml5ParserEnabled', true);
         $dompdf->loadHtml( $html, 'UTF-8' );
         $dompdf->render(); 
 
         $output = $dompdf->output(); 
 
         if( $save_pdf ) {
 
             $invoice_file_name = sprintf( '%s/uploads/ticket-%s-%s.pdf', WP_CONTENT_DIR, $order_id, time("H:s") );
             file_put_contents( $invoice_file_name, $output);
             return $invoice_file_name;
         }
 
         if( $download_pdf ) {
             
             $dompdf->stream( sprintf( "%s-%s", __('Ticket','mage-eventpress-pdf'), $order_id ) );
             return true;
         }
 
         return $output;


        }else{
            // if( class_exists( 'mPDF' ) ) {

            $upload_dir   = wp_upload_dir();    
            if( $order_id == 0 ) return new WP_Error( 'invalid_data', __('Invalid order id provided','mage-eventpress-pdf') );
            $ticket_name = $upload_dir['basedir'].'/'.__('Ticket','mage-eventpress-pdf').$order_id.'.pdf';
            $file_name = __('Ticket','mage-eventpress-pdf').$order_id.'.pdf';
            $html   = empty( $html ) ? $this->get_order_pdf_html( $order_id, 'pdf' ) : $html;

            
            
            if(mep_get_mpdf_support_version() == 2.0){ 
                $mpdf = new \Mpdf\Mpdf();
            }else{
                $mpdf = new mPDF();
            }

            // 
            
            $mpdf->allow_charset_conversion=true;  // Set by default to TRUE
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;
            $mpdf->autoLangToFont = true;
    
            $mpdf->WriteHTML($html);
            $mpdf->Output($file_name, 'D');
            exit;

        // }
        }


        }
    
    
        public function generate_attendee_pdf($event_id, $post_type, $ea_event_date, $html = "", $download_pdf = true, $save_pdf = false) {
    
            // Check for current user privileges
            if( !current_user_can( 'manage_options' ) ){ return false; }
            // Check if we are in WP-Admin
            if( !is_admin() ){ return false; }
    
            $domain = $_SERVER['SERVER_NAME'];
            $filename = 'Event_Manager_Export_' . $domain . '_' . time() . '.csv';

            if(isset($event_id)){
                $post_id  = strip_tags($event_id);
                $header_row      = mep_get_event_user_fields_pro($post_id);

            }else {
                $header_row = array(
                    'Ticket No',
                    'Order ID',
                    'Event',
                    'Ticket',
                    'Full Name',
                    'Email',
                    'Phone',
                    'Addresss',
                    'Tee Size',
                    'Check in'
                );
            }

            $data_rows = array();
            global $wpdb;
            $admin_order_status = mep_get_option('mep_csv_export_status', 'csv_general_attendee_sec','completed');

            if(isset($event_id)){
                $event_id = $event_id;
                $ea_event_date = $ea_event_date;
                $event_date = date('Y-m-d',strtotime($ea_event_date));
            }

            $args_search_qqq = array (
                'post_type'        => array( 'mep_events_attendees' ),
                'posts_per_page'   => -1,
                'meta_query'  => array(
                    'relation' => 'AND',
                    array(
                        array(
                            'key'     => 'ea_event_id',
                            'value' => $event_id,
                            'compare' => '='
                        ),
                        array(
                            'key'       => 'ea_event_date',
                            'value'     => $event_date,
                            'compare'   => 'LIKE'
                        )
                    ),
                    array(
                        'relation' => 'OR',
                        array(
                            'key'     => 'ea_order_status',
                            'value'   => 'processing',
                            'compare' => '='
                        ),
                        array(
                            'key'     => 'ea_order_status',
                            'value' => 'completed',
                            'compare' => '='
                        ),
                    )
                )

            );

            $loop = new WP_Query( $args_search_qqq );

            while ($loop->have_posts()) {
                $loop->the_post();
                $post_id  = get_the_id();

                if (get_post_type($post_id) == 'mep_events_attendees') {

                    $order_status = get_post_meta( $post_id, 'ea_order_status', true );

                    if(isset($event_id)){
                        $event    = strip_tags($event_id);
                        $row      = mep_get_event_user_fields_pro_data($post_id,$event,$order_status);
                    }else{
                        $post_id = $post_id;

                        $checkin_status = get_post_meta($post_id,'mep_checkin',true);

                        if($checkin_status){
                            $status = $checkin_status;
                        }else{
                            $status = 'no';
                        }
                        $ticket = get_post_meta( $post_id, 'ea_user_id', true ).get_post_meta( $post_id, 'ea_order_id', true ).get_post_meta( $post_id, 'ea_event_id', true ).$post_id;
                        $row = array(
                            $ticket,
                            get_post_meta( $post_id, 'ea_order_id', true ),
                            get_post_meta( $post_id, 'ea_event_name', true ),
                            get_post_meta( $post_id, 'ea_ticket_type', true ),
                            get_post_meta( $post_id, 'ea_name', true ),
                            get_post_meta( $post_id, 'ea_email', true ),
                            get_post_meta( $post_id, 'ea_phone', true ),
                            get_post_meta( $post_id, 'ea_address_1', true ),
                            get_post_meta( $post_id, 'ea_tshirtsize', true ),
                            $status
                        );
                    }
                    $data_rows[] = $row;
                }

            }

            wp_reset_postdata();

            // Pdf Setting
            global $wbtm;
            $pdflibrary = mep_get_option('mep_pdf_lib', 'mep_pdf_gen_settings', 'mpdf');

            if($pdflibrary == 'dompdf'){

                if (!class_exists('HTML5_Data')) {
                    require WBTM_PRO_PLUGIN_DIR . 'dompdf/autoload.inc.php';
                }

                $html = getFileContent(WBTM_PRO_PLUGIN_DIR . '/templates/pdf-attendee.php', array('event_id' => $event_id, 'data' => $data_rows));

                 $dompdf = new Dompdf( array(
                      'isRemoteEnabled' => true,
                 ) );

                 $dompdf->setPaper( 'A4', 'landscape' );
                 $dompdf->set_option('isHtml5ParserEnabled', true);
                 $dompdf->loadHtml( $html, 'UTF-8' );
                 $dompdf->render();

                 $output = $dompdf->output();

                 if( $save_pdf ) {

                     $invoice_file_name = sprintf( '%s/uploads/attendee-%s-%s.pdf', WP_CONTENT_DIR, $order_id, time("H:s") );
                     file_put_contents( $invoice_file_name, $output);
                     return $invoice_file_name;
                 }

                 if( $download_pdf ) {

                     $dompdf->stream( sprintf( "%s-%s", __('Attendee','mage-eventpress-pdf'), $event_id ) );
                     return true;
                 }

                 return $output;
            } else {

                $upload_dir   = wp_upload_dir();
                if( $event_id == 0 ) return new WP_Error( 'invalid_data', __('Invalid Event id provided','mage-eventpress-pdf') );
                $ticket_name = $upload_dir['basedir'].'/'.__('Attendee','mage-eventpress-pdf').$event_id.'.pdf';
                $file_name = __('Attendee','mage-eventpress-pdf').$event_id.'.pdf';

                $html = getFileContent(WBTM_PRO_PLUGIN_DIR . '/templates/pdf-attendee.php', array('event_id' => $event_id, 'data' => $data_rows));

                

                if(mep_get_mpdf_support_version() == 2.0){
                    $mpdf = new \Mpdf\Mpdf();
                }else{
                    $mpdf = new mPDF();
                }
                $mpdf->allow_charset_conversion=true;  // Set by default to TRUE
                $mpdf->autoScriptToLang = true;
                $mpdf->baseScript = 1;
                $mpdf->autoVietnamese = true;
                $mpdf->autoArabic = true;
                $mpdf->autoLangToFont = true;
                $mpdf->WriteHTML($html);
               $mpdf->Output($file_name, 'D');
            }

        }
    

    
    
        public function get_pdf_ticket_attachment_file( $order_id = 0, $html = "", $download_pdf = true, $save_pdf = false ){
    
            if( $order_id == 0 ) return new WP_Error( 'invalid_data', __('Invalid order id provided','mage-eventpress-pdf') );
            // if( class_exists( 'mPDF' ) ) {            
            $upload_dir   = wp_upload_dir();
            $html   = empty( $html ) ? $this->get_order_pdf_html( $order_id, 'pdf' ) : $html;
            $ticket_name = $upload_dir['basedir'].'/'.__('Ticket','mage-eventpress-pdf').$order_id.'.pdf';

            

            if(mep_get_mpdf_support_version() == 2.0){
                $mpdf = new \Mpdf\Mpdf();
            }else{
                $mpdf = new mPDF();
            }
            $mpdf->allow_charset_conversion=true;  // Set by default to TRUE
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;
            $mpdf->autoLangToFont = true;
    
            $mpdf->WriteHTML($html);
    
            $mpdf->Output($ticket_name, 'F');
            return $ticket_name;
        // }
    
        }

    public function get_order_pdf_html( $order_id = 0, $type = "" ){

        if( $order_id == 0 ) return new WP_Error( 'invalid_data', __('Invalid order id provided') );
        // ob_start();
        // echo do_shortcode("[order-pdf order_id=$order_id type='' template='flat']");
        // return ob_get_clean();


        $file_slug = 'order-pdf';

        $template_dir = sprintf( "%stemplates/%s.php", WBTM_PRO_PLUGIN_DIR, $file_slug );
        // $template_dir = apply_filters( 'wbtm_filter_shortcode_template_dir', $template_dir, $shortcode );
        $template_dir = file_exists( $template_dir ) ? $template_dir : '';

        // if( empty( $template_dir ) ) return new WP_Error( 'empty_data', sprintf( __( 'Template file not found for shortcode : [%s]', 'woo-invoice' ), $shortcode ) );
        
        ob_start();
        include $template_dir;
        return ob_get_clean();

    }



    public function get_invoice_ajax_url( $args = array() ){        
        $default_args = array(
            'action'            => 'generate_pdf',
            'doccument_type'    => 'pdf',
            'order_id'          => '',
        );        
        $args       = wp_parse_args( $args, $default_args );
        $build_url  = http_build_query( $args );
        $nonce_url  = wp_nonce_url( admin_url( "admin-ajax.php?" . $build_url ), $args['action'] );        
        return apply_filters( 'wbtm_filters_invoice_ajax_url', $nonce_url );
    }



    public function get_order_data( $order_id = 0, $return_as = false ){

        if( $order_id == 0 ) return new WP_Error( 'invalid_data', __('Invalid order id provided') );

        $data   = array();
        $order  = wc_get_order( $order_id );
        $data   = $order->get_data();

        $item_total = 0;
        foreach( $order->get_items() as $item_id => $item ) {

            $total      = isset( $item['total'] ) ? (float) $item['total'] : 0;
            $subtotal   = isset( $item['subtotal'] ) ? (float) $item['subtotal'] : 0;

            $data['items'][] = array_merge( $item->get_data(), array(
                'thumbnail_url' => get_the_post_thumbnail_url( $item->get_product_id(), array( 50, 50 ) ),
                'permalink'     => get_the_permalink( $item->get_product_id() ),
                'discount'      => $subtotal > $total ?  $subtotal - $total : 0,    
            ) );

            $item_total += $subtotal;
        }

        $data['order_date']     = $order->get_date_created()->date( 'M j, Y' );
        $data['item_total']     = $item_total;
        $data['order']          = $order;

        if( $return_as && $return_as == 'object' ) {
            
            $data['billing']        = isset( $data['billing'] )         ? (object)$data['billing']          : (object)array();
            $data['shipping']       = isset( $data['shipping'] )        ? (object)$data['shipping']         : (object)array();
            
            return (object)$data;
        }
        if( $return_as && $return_as == 'json' ) return json_encode( $data );

        return $data;
    }



    public function print_error( $wp_error ){        
        $classes = array( $wp_error->get_error_code() );
        if( is_admin() ) $classes[] = 'is-dismissible';
        printf( "<div class='notice notice-error error wooin-notice %s'><p>%s</p></div>", 
            implode( ' ', $classes ), $wp_error->get_error_message() 
        );   
    }


    function get_option( $option_name = '', $default = '' ){
        $option_value = get_option( $option_name, $default );
        $option_value = empty( $option_value ) ? $default : $option_value;
        return $option_value;
    }

    private function set_shortcodes(){
		$this->shortcodes = apply_filters( 'wbtm_filter_shortcodes', array(            
            'order-pdf' => array(
                'file-slug' => 'order-pdf',
            ),
		) );
	}
	
	private function add_shortcodes(){
		foreach( $this->shortcodes as $shortcode => $args ) : 
		add_shortcode( $shortcode, array( $this, 'shortcode_content_display' ), $shortcode );
		endforeach;
	}
	
	public function shortcode_content_display( $atts, $content = null, $shortcode='' ) {               
        ob_start();
        if( is_wp_error( $shortcode_html = $this->get_shortcode_html( $shortcode, $atts ) ) ) {
            return ob_get_clean();
        }        
		echo $shortcode_html;
		return ob_get_clean();
    }
    
    public function get_shortcode_html( $shortcode, $atts ){

        $file_slug = isset( $this->shortcodes[ $shortcode ]['file-slug'] ) ? $this->shortcodes[ $shortcode ][ 'file-slug' ] : '';

        $template_dir = sprintf( "%stemplates/%s.php", WBTM_PRO_PLUGIN_DIR, $file_slug );
        $template_dir = apply_filters( 'wbtm_filter_shortcode_template_dir', $template_dir, $shortcode );
        $template_dir = file_exists( $template_dir ) ? $template_dir : '';

        if( empty( $template_dir ) ) return new WP_Error( 'empty_data', sprintf( __( 'Template file not found for shortcode : [%s]', 'woo-invoice' ), $shortcode ) );
        ob_start();
        include $template_dir;
        return ob_get_clean();
    }

    public function send_email( $order_id = '', $order = false ){

        if( empty( $order_id ) || ! $order ) return false;

            $pdflibrary             = mep_get_option('mep_pdf_lib', 'mep_pdf_gen_settings', 'mpdf');
            $subject                = mep_get_option('mep_pdf_email_subject', 'mep_pdf_email_settings', 'PDF Ticket Confirmation');
            $content                = mep_get_option('mep_pdf_email_content', 'mep_pdf_email_settings', 'Here is PDF Ticket Confirmation Attachment');
            $form_name              = mep_get_option('mep_pdf_email_from_name', 'mep_pdf_email_settings', get_bloginfo( 'name' ));
            $form_email             = mep_get_option('mep_pdf_email_from', 'mep_pdf_email_settings', get_bloginfo( 'admin_email' ));
            $admin_notify_email     = mep_get_option('mep_pdf_admin_notification_email', 'mep_pdf_email_settings', '');
            $email_status           = mep_get_option('mep_pdf_send_status', 'mep_pdf_email_settings', 'yes');
            $attachments            = array();
            $headers                = array( 
                sprintf( "From: %s <%s>", $form_name,    $form_email ),
            );

            if( $email_status == 'yes' ) {

                if($pdflibrary == 'mpdf'){

                    $attathment_file_url = $this->get_pdf_ticket_attachment_file( $order_id, "", false, true );

                }elseif($pdflibrary == 'dompdf'){

                    $attathment_file_url = $this->generate_pdf( $order_id, "", false, true );

                }else{

                    $attathment_file_url = $this->generate_pdf( $order_id, "", false, true );
                    
                }
                
                if( ! is_wp_error( $attathment_file_url ) ) $attachments[] = $attathment_file_url;

            $email_address_arr = array(
                $order->get_billing_email(),$admin_notify_email             
            );
            $email_address = implode( ",", $email_address_arr );

            // Mail content dynamic
            $content = $this->make_dynamic_mail_content($content, $order);
				
			// Add to Calendar Content
            $is_add_to_calendar_show = mep_get_option('mep_pdf_add_to_calendar', 'mep_pdf_email_settings', 'yes');

            if( $is_add_to_calendar_show === 'yes' ) {
                $content = $this->mep_calendar_schedule_on_email($content, $order);
            }
           $pdf_email_content = apply_filters('mep_event_pdf_email_text',$content,$order_id); 
            wp_mail( $email_address, $subject, nl2br($pdf_email_content), $headers, $attachments );
        }
    }

    public function make_dynamic_mail_content($content, $order) {

        $get_content = $content;
        $get_content = str_replace('{customer_name}', $order->get_billing_first_name().' '.$order->get_billing_last_name(), $get_content);
        $get_content = str_replace('{order_id}', $order->get_order_number(), $get_content);

        $index = 0;
        foreach ( $order->get_items() as $item_id => $item ) {

            if(count($order->get_items()) > 1) { // Multiple order items
                $event_id = wc_get_order_item_meta( $item_id, 'event_id', true );

                $event_data = get_post($event_id);
                $event_name = $event_data->post_title;
                $event_venue = get_post_meta($event_id, 'mep_location_venue', true);
                $event_date = get_post_meta($event_id, 'event_start_date', true);
                $event_time = date('g:i a', strtotime(get_post_meta($event_id, 'event_start_time', true)));

                // Replace String
                if(count($order->get_items()) - 1 !== $index) {
                    $get_content = str_replace('{event_name}', '<sup>'.($index+1).'</sup>'. $event_name. ' | {event_name}', $get_content);
                    $get_content = str_replace('{event_venue}', '<sup>'.($index+1).'</sup>'. $event_venue . ' | {event_venue}', $get_content);
                    $get_content = str_replace('{event_date}', '<sup>'.($index+1).'</sup>'. $event_date.' '.$event_time. ' | {event_date}', $get_content);
                } else { // Last index
                    $get_content = str_replace('{event_name}', '<sup>'.($index+1).'</sup>'. $event_name, $get_content);
                    $get_content = str_replace('{event_venue}', '<sup>'.($index+1).'</sup>'. $event_venue, $get_content);
                    $get_content = str_replace('{event_date}', '<sup>'.($index+1).'</sup>'. $event_date.' '.$event_time, $get_content);
                }
            } else { // Single order item
                $event_id = wc_get_order_item_meta( $item_id, 'event_id', true );

                $event_data         = get_post($event_id);
                $event_name         = $event_data->post_title;
                $event_venue        = get_post_meta($event_id, 'mep_location_venue', true);
                $event_date         = get_post_meta($event_id, 'event_start_date', true);
                $event_time         = date('g:i a', strtotime(get_post_meta($event_id, 'event_start_time', true)));

                // Replace String
                $get_content = str_replace('{event_name}', $event_name, $get_content);
                $get_content = str_replace('{event_venue}', $event_venue, $get_content);
                $get_content = str_replace('{event_date}', $event_date.' '.$event_time, $get_content);
            }

            $index++;
        }

        return $get_content;
    }
    
    
    public function remove_qt($str){    
        $str = str_replace('"', '', $str);        
        return '';
    }


	public function mep_calendar_schedule_on_email($content, $order) {

        $l_s = 'background: #ffe492;text-decoration: none;padding: 4px 5px;border-radius: 3px;color: #138200;font-weight: 700;display:inline-block';
        $index = 0;
        foreach ( $order->get_items() as $item_id => $item ) {

            if(count($order->get_items()) > 1) { // Multiple order items
                $event_id = wc_get_order_item_meta( $item_id, 'event_id', true );

                $event_data = get_post($event_id);
                $event_name = $event_data->post_title;
                $event_content = $event_data->post_content;
                $event_start_date = get_post_meta($event_id, 'event_start_datetime', true);
                $event_end_date = get_post_meta($event_id, 'event_end_datetime', true);

                $venue = ((get_post_meta($event_id, 'mep_location_venue', true) != null) ? get_post_meta($event_id, 'mep_location_venue', true) : '');
                $street = ((get_post_meta($event_id, 'mep_street', true) != null) ? get_post_meta($event_id, 'mep_street', true) : '');
                $city = ((get_post_meta($event_id, 'mep_city', true) != null) ? get_post_meta($event_id, 'mep_city', true) : '');
                $state = ((get_post_meta($event_id, 'mep_state', true) != null) ? get_post_meta($event_id, 'mep_state', true) : '');
                $postcode = ((get_post_meta($event_id, 'mep_postcode', true) != null) ? get_post_meta($event_id, 'mep_postcode', true) : '');
                $country = ((get_post_meta($event_id, 'mep_country', true) != null) ? get_post_meta($event_id, 'mep_country', true) : '');

                $location = $venue. '  ' .$street. '  ' .$city. '  ' .$state. '  ' .$postcode. '  ' .$country;

                $html = '';
                $html = '<br><br><br>';
                $html .= "<strong>".__('Add', 'mage-eventpress-pdf')." '$event_name' ".__('to calendar', 'mage-eventpress-pdf').":</strong><br><br>";
                $html .= '<div><a href="https://calendar.google.com/calendar/r/eventedit?text='.$event_name.'&dates='.mep_calender_date($event_start_date).'/'.mep_calender_date($event_end_date).'&details='.$this->remove_qt(substr(strip_tags($event_content),0,1000)).'&location='.$location.'&sf=true" rel="noopener noreferrer" target="_blank" style="'.$l_s.'" rel="nofollow">Google</a></div>';
                $html .= ' <div><a href="https://calendar.yahoo.com/?v=60&view=d&type=20&title='.$event_name.'&st='.mep_calender_date($event_start_date).'&et'.mep_calender_date($event_end_date).'&desc='.$this->remove_qt(substr(strip_tags($event_content),0,1000)).'&in_loc='.$location.'&uid=" rel="noopener noreferrer" target="_blank" style="'.$l_s.'" rel="nofollow">Yahoo</a></div>';
                $html .= ' <div><a href="https://outlook.live.com/owa/?path=/calendar/view/Month&rru=addevent&subject='.$event_name.'&startdt='.mep_calender_date($event_start_date).'&enddt'.mep_calender_date($event_end_date).'" rel="noopener noreferrer" target="_blank" style="'.$l_s.'" rel="nofollow">Outlook</a></div>';
                $html .= ' <div><a href="https://webapps.genprod.com/wa/cal/download-ics.php?summary='.$event_name.'&date_start='.mep_calender_date($event_start_date).'&date_end'.mep_calender_date($event_end_date).'&description='.$this->remove_qt(substr(strip_tags($event_content),0,1000)).'&location='.$location.'&uid=" rel="noopener noreferrer" target="_blank" style="'.$l_s.'" rel="nofollow">Apple</a></div>';

                $content .= $html;
                
            } else { // Single order item
                $event_id = wc_get_order_item_meta( $item_id, 'event_id', true );
                $event_data = get_post($event_id);
                $event_name = $event_data->post_title;
                $event_content = $event_data->post_content;
                $event_start_date = get_post_meta($event_id, 'event_start_datetime', true);
                $event_end_date = get_post_meta($event_id, 'event_end_datetime', true);
                $venue = ((get_post_meta($event_id, 'mep_location_venue', true) != null) ? get_post_meta($event_id, 'mep_location_venue', true) : '');
                $street = ((get_post_meta($event_id, 'mep_street', true) != null) ? get_post_meta($event_id, 'mep_street', true) : '');
                $city = ((get_post_meta($event_id, 'mep_city', true) != null) ? get_post_meta($event_id, 'mep_city', true) : '');
                $state = ((get_post_meta($event_id, 'mep_state', true) != null) ? get_post_meta($event_id, 'mep_state', true) : '');
                $postcode = ((get_post_meta($event_id, 'mep_postcode', true) != null) ? get_post_meta($event_id, 'mep_postcode', true) : '');
                $country = ((get_post_meta($event_id, 'mep_country', true) != null) ? get_post_meta($event_id, 'mep_country', true) : '');
                $location = $venue. '  ' .$street. '  ' .$city. '  ' .$state. '  ' .$postcode. '  ' .$country;
                $html = '';
                $html = '<br><br><br>';
                $html .= "<strong>".__('Add', 'mage-eventpress-pdf')." '$event_name' ".__('to calendar', 'mage-eventpress-pdf').":</strong><br><br>";
                $html .= '<div><a href="https://calendar.google.com/calendar/r/eventedit?text='.$event_name.'&dates='.mep_calender_date($event_start_date).'/'.mep_calender_date($event_end_date).'&details='.$this->remove_qt(substr(strip_tags($event_content),0,1000)).'&location='.$location.'&sf=true" rel="noopener noreferrer" target="_blank" style="'.$l_s.'" rel="nofollow">Google</a></div>';               
                $html .= ' <div><a href="https://calendar.yahoo.com/?v=60&view=d&type=20&title='.$event_name.'&st='.mep_calender_date($event_start_date).'&et'.mep_calender_date($event_end_date).'&desc='.$this->remove_qt(substr(strip_tags($event_content),0,1000)).'&in_loc='.$location.'&uid=" rel="noopener noreferrer" target="_blank" style="'.$l_s.'" rel="nofollow">Yahoo</a></div>';               
                $html .= ' <div><a href="https://outlook.live.com/owa/?path=/calendar/view/Month&rru=addevent&subject='.$event_name.'&startdt='.mep_calender_date($event_start_date).'&enddt'.mep_calender_date($event_end_date).'" rel="noopener noreferrer" target="_blank" style="'.$l_s.'" rel="nofollow">Outlook</a></div>';
                $html .= ' <div><a href="https://webapps.genprod.com/wa/cal/download-ics.php?summary='.$event_name.'&date_start='.mep_calender_date($event_start_date).'&date_end'.mep_calender_date($event_end_date).'&description='.$this->remove_qt(substr(strip_tags($event_content),0,1000)).'&location='.$location.'&uid=" rel="noopener noreferrer" target="_blank" style="'.$l_s.'" rel="nofollow">Apple</a></div>';
                $content .= $html;
            }
            $index++;
        }
        return $content;
    }
}

global $wbtm;
$wbtm = new WBTM_Functions();

}