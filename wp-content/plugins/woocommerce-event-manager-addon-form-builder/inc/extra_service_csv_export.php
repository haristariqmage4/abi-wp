<?php
function mep_exts_csv_head_row($post_id=''){
    global $woocommerce, $post;
    $head_row = array(
        __('Order ID','mep-form-builder'),
        __('Event','mep-form-builder'),
        __('Name','mep-form-builder'),        
        __('Quantity','mep-form-builder'),                
        __('Unit Price','mep-form-builder'),                
        __('Total Price','mep-form-builder')
    );
    
    
    // Checkout Billing Form

$billing_first_name   = mep_get_option('mep_billing_first_name', 'csv_checkout_export_fileds_sec','');
$billing_last_name    = mep_get_option('mep_billing_last_name', 'csv_checkout_export_fileds_sec','');
$billing_email        = mep_get_option('mep_billing_email', 'csv_checkout_export_fileds_sec','');
$billing_phone        = mep_get_option('mep_billing_phone', 'csv_checkout_export_fileds_sec','');
$billing_company      = mep_get_option('mep_billing_company_name', 'csv_checkout_export_fileds_sec','');
$billing_address_1    = mep_get_option('mep_billing_address_1', 'csv_checkout_export_fileds_sec','');
$billing_address_2    = mep_get_option('mep_billing_address_2', 'csv_checkout_export_fileds_sec','');
$billing_city         = mep_get_option('mep_billing_city', 'csv_checkout_export_fileds_sec','');
$billing_state        = mep_get_option('mep_billing_state', 'csv_checkout_export_fileds_sec','');
$billing_postcode     = mep_get_option('mep_billing_postcode', 'csv_checkout_export_fileds_sec','');

$billing_country      = mep_get_option('mep_billing_country', 'csv_checkout_export_fileds_sec','');

$billing_paid      = mep_get_option('mep_billing_paid', 'csv_checkout_export_fileds_sec','');
$billing_method      = mep_get_option('mep_billing_method', 'csv_checkout_export_fileds_sec','');




    if($billing_first_name=='on'){ $billing_first_name = __('Billing First Name','mep-form-builder'); }else{ $billing_first_name = ''; }

    if($billing_last_name=='on'){ $billing_last_name = __('Billing Last Name','mep-form-builder'); }else{ $billing_last_name = ''; }

    if($billing_email=='on'){ $billing_email = __('Billing Email','mep-form-builder'); }else{ $billing_email = ''; }

    if($billing_phone=='on'){ $billing_phone = __('Billing Phone','mep-form-builder'); }else{ $billing_phone = ''; }

    if($billing_company=='on'){ $billing_company = __('Billing Company','mep-form-builder'); }else{ $billing_company = ''; }

    if($billing_address_1=='on'){ $billing_address_1 = __('Billing Address 1','mep-form-builder'); }else{ $billing_address_1 = ''; }

    if($billing_address_2=='on'){ $billing_address_2 = __('Billing Address 2','mep-form-builder'); }else{ $billing_address_2 = ''; }

    if($billing_city=='on'){ $billing_city = __('Billing City','mep-form-builder'); }else{ $billing_city = ''; }

    if($billing_state=='on'){ $billing_state = __('Billing State','mep-form-builder'); }else{ $billing_state = ''; }

    if($billing_postcode=='on'){ $billing_postcode = __('Billing Post Code','mep-form-builder'); }else{ $billing_postcode = ''; }

    if($billing_country=='on'){ $billing_country = __('Billing Country','mep-form-builder'); }else{ $billing_country = ''; }

    if($billing_paid=='on'){ $billing_paid = __('Total Paid','mep-form-builder'); }else{ $billing_paid = ''; }
 
   if($billing_method=='on'){ $billing_method = __('Payment Method','mep-form-builder'); }else{ $billing_method = ''; }
    
    
    $event_checkout_form = array(
        $billing_first_name,
        $billing_last_name,
        $billing_email,        
        $billing_phone,                
        $billing_company,                
        $billing_address_1,                
        $billing_address_2,                
        $billing_city,                
        $billing_state,                
        $billing_postcode,                
        $billing_country,  
       $billing_paid, 
        $billing_method, 
        __('Order Status','mep-form-builder')
    );
    
    
    return array_merge(array_filter($head_row),array_filter($event_checkout_form));
    
}

function mep_exts_csv_passenger_data($post_id=''){
    
    
    $passenger_data = array(
            get_post_meta( $post_id, 'ea_extra_service_order', true ),
            get_the_title(get_post_meta( $post_id, 'ea_extra_service_event', true )),  
            get_post_meta( $post_id, 'ea_extra_service_name', true ),              
            get_post_meta( $post_id, 'ea_extra_service_qty', true ),              
            get_post_meta( $post_id, 'ea_extra_service_unit_price', true ),              
            get_post_meta( $post_id, 'ea_extra_service_total_price', true )  

    );
    
    
    
    
    
    // Checkout Billing Form

$billing_first_name   = mep_get_option('mep_billing_first_name', 'csv_checkout_export_fileds_sec','');
$billing_last_name    = mep_get_option('mep_billing_last_name', 'csv_checkout_export_fileds_sec','');
$billing_email        = mep_get_option('mep_billing_email', 'csv_checkout_export_fileds_sec','');
$billing_phone        = mep_get_option('mep_billing_phone', 'csv_checkout_export_fileds_sec','');
$billing_company      = mep_get_option('mep_billing_company_name', 'csv_checkout_export_fileds_sec','');
$billing_address_1    = mep_get_option('mep_billing_address_1', 'csv_checkout_export_fileds_sec','');
$billing_address_2    = mep_get_option('mep_billing_address_2', 'csv_checkout_export_fileds_sec','');
$billing_city         = mep_get_option('mep_billing_city', 'csv_checkout_export_fileds_sec','');
$billing_state        = mep_get_option('mep_billing_state', 'csv_checkout_export_fileds_sec','');
$billing_postcode     = mep_get_option('mep_billing_postcode', 'csv_checkout_export_fileds_sec','');
$billing_country      = mep_get_option('mep_billing_country', 'csv_checkout_export_fileds_sec','');
$billing_paid      = mep_get_option('mep_billing_paid', 'csv_checkout_export_fileds_sec','');
$billing_method      = mep_get_option('mep_billing_method', 'csv_checkout_export_fileds_sec','');

    $order              = wc_get_order(get_post_meta( $post_id, 'ea_extra_service_order', true ));
    $order_meta         = get_post_meta(get_post_meta( $post_id, 'ea_extra_service_order', true ));  
    $country            = $order->get_billing_country();
    $state              = $order->get_billing_state();

    $first_name             = array_key_exists('_billing_first_name',$order_meta) ? $order_meta['_billing_first_name'][0] : ' ';
    $last_name              = array_key_exists('_billing_last_name',$order_meta) ? $order_meta['_billing_last_name'][0] : ' ';
    $company_name           = array_key_exists('_billing_company',$order_meta) ? $order_meta['_billing_company'][0] : ' ';
    $bill_address_1         = array_key_exists('_billing_address_1',$order_meta) ? $order_meta['_billing_address_1'][0] : ' ';
    $bill_address_2         = array_key_exists('_billing_address_2',$order_meta) ? $order_meta['_billing_address_2'][0] : ' ';
    $bill_city              = array_key_exists('_billing_city',$order_meta) ? $order_meta['_billing_city'][0] : ' ';
    // $bill_state             = $order_meta['_billing_state'][0];
    $bill_state             = WC()->countries->get_states( $country )[$state] ? WC()->countries->get_states( $country )[$state] : ' ';
    $bill_postcode          = array_key_exists('_billing_postcode',$order_meta) ? $order_meta['_billing_postcode'][0] : ' ';
    // $bill_country           = $order_meta['_billing_country'][0];
    $bill_country           = !empty($country) && WC()->countries->countries[$country] ? WC()->countries->countries[$country] : ' ';
    $bill_email             = array_key_exists('_billing_email',$order_meta) ? $order_meta['_billing_email'][0] : ' ';
    $bill_phone             = array_key_exists('_billing_phone',$order_meta) ? $order_meta['_billing_phone'][0] : ' ';
    $bill_total             = $order->get_total() ? $order->get_total() : ' ';
    // $bill_total             = 0;
    $payment_method         = array_key_exists('_payment_method_title',$order_meta) ? $order_meta['_payment_method_title'][0] : ' ';
    $user_id                = array_key_exists('_customer_user',$order_meta) ? $order_meta['_customer_user'][0] : ' ';



    if($billing_first_name=='on'){ $billing_first_name = $first_name; }else{ $billing_first_name = ''; }

    if($billing_last_name=='on'){ $billing_last_name = $last_name; }else{ $billing_last_name = ''; }

    if($billing_email=='on'){ $billing_email = $bill_email; }else{ $billing_email = ''; }

    if($billing_phone=='on'){ $billing_phone = $bill_phone; }else{ $billing_phone = ''; }

    if($billing_company=='on'){ $billing_company = $company_name; }else{ $billing_company = ''; }

    if($billing_address_1=='on'){ $billing_address_1 = $bill_address_1; }else{ $billing_address_1 = ''; }

    if($billing_address_2=='on'){ $billing_address_2 = $bill_address_2; }else{ $billing_address_2 = ''; }

    if($billing_city=='on'){ $billing_city = $bill_city; }else{ $billing_city = ''; }

    if($billing_state=='on'){ $billing_state = $bill_state; }else{ $billing_state = ''; }

    if($billing_postcode=='on'){ $billing_postcode = $bill_postcode; }else{ $billing_postcode = ''; }

    if($billing_country=='on'){ $billing_country = $bill_country; }else{ $billing_country = ''; }
    
    if($billing_paid=='on'){ $billing_paid = $bill_total; }else{ $billing_paid = ''; }
    if($billing_method=='on'){ $billing_method = $payment_method; }else{ $billing_method = ''; }
    
    
    
    
    
        $event_checkout_form = array(
        $billing_first_name,
        $billing_last_name,
        $billing_email,        
        $billing_phone,                
        $billing_company,                
        $billing_address_1,                
        $billing_address_2,                
        $billing_city,                
        $billing_state,                
        $billing_postcode,                
        $billing_country,  
        $billing_paid, 
        $billing_method, 
        get_post_meta( $post_id, 'ea_extra_service_order_status', true )  
    );
    
    
    
    
    
    
    
  return array_merge($passenger_data,array_filter($event_checkout_form));  
    
    
    
    
    
    
// return $passenger_data;
}


// Add action hook only if action=download_csv
if ( isset($_GET['action'] ) && $_GET['action'] == 'download_csv_extra_service' )  {
  // Handle CSV Export
  add_action( 'admin_init', 'mep_exts_export_default_form') ;
}

function mep_exts_export_default_form() {
    // Check for current user privileges 
    if( !current_user_can( 'manage_options' ) ){ return false; }
    // Check if we are in WP-Admin
    if( !is_admin() ){ return false; }
    ob_start();
	$event_id = strip_tags($_GET['event_id']);
    $j_date = strip_tags($_GET['ea_event_date']); 
//    die();    
    $domain = $_SERVER['SERVER_NAME'];
    $filename = 'Extra_Service_list' . $domain . '_' . time() . '.csv';
    $header_row      = mep_exts_csv_head_row();   
    $data_rows = array();
    $args = array(
        'post_type' => 'mep_extra_service',
        'posts_per_page' => -1,
        'meta_query'  => array(
            'relation' => 'AND',
            array(
                    array(
                        'key'     => 'ea_extra_service_event',
                        'value' => $event_id,
                        'compare' => '='									
                    ),							
                    array(
                        'key'     => 'ea_extra_service_event_date',
                        'value' => $j_date,
                        'compare' => 'LIKE'									
                    )							
            ),
            array(
                'relation' => 'OR',										
                array(
                    'key'     => 'ea_extra_service_order_status',
                    'value'   => 'processing',
                    'compare' => '='
                ),
                array(
                    'key'     => 'ea_extra_service_order_status',
                    'value' => 'completed',
                    'compare' => '='
                ),
            )
        )	
    );
    $passenger = new WP_Query($args);
    $passger_query = $passenger->posts;
    foreach ($passger_query as $_passger) {
    $passenger_id = $_passger->ID;

           
    $row      =  mep_exts_csv_passenger_data($passenger_id);              
    $data_rows[] = $row;
    }
    wp_reset_postdata();
    $fh = @fopen( 'php://output', 'w' );
    fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
    header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
    header( 'Content-Description: File Transfer' );
    header( 'Content-type: text/csv' );
    header( "Content-Disposition: attachment; filename={$filename}" );
    header( 'Expires: 0' );
    header( 'Pragma: public' );
    fputcsv( $fh, $header_row );
    foreach ( $data_rows as $data_row ) {
        fputcsv( $fh, $data_row );
    }
    fclose( $fh );    
    ob_end_flush();    
    die();
}