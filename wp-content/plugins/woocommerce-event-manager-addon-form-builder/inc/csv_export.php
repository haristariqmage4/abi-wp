<?php
add_action('admin_head','hide_defaut_csv_btn');
function hide_defaut_csv_btn(){
  ?>
<style type="text/css">
  input#csvExport {
    display: none;
}
</style>
  <?php
}





function mep_get_event_user_fields_pro($post){
    // global $woocommerce, $post;
    $post_id    = mep_fb_get_reg_form_id($post);

    $name       = get_post_meta($post_id,'mep_full_name',true);
    $email      = get_post_meta($post_id,'mep_reg_email',true);
    $phone      = get_post_meta($post_id,'mep_reg_phone',true);
    $adrs       = get_post_meta($post_id,'mep_reg_address',true);
    $desg       = get_post_meta($post_id,'mep_reg_designation',true);
    $website    = get_post_meta($post_id,'mep_reg_website',true);
    $veg        = get_post_meta($post_id,'mep_reg_veg',true);
    $company    = get_post_meta($post_id,'mep_reg_company',true);
    $gender     = get_post_meta($post_id,'mep_reg_gender',true);
    $tee        = get_post_meta($post_id,'mep_reg_tshirtsize',true);


    if($name){ $name = __('Full Name','mep-form-builder'); }else{ $name = ''; }
    if($email){ $email = __('Email','mep-form-builder'); }else{ $email = ''; }
    if($phone){ $phone = __('Phone','mep-form-builder'); }else{ $phone = ''; }
    if($adrs){ $address = 'Addresss'; }else{ $address = ''; }
    if($desg){ $desg = __('Designation','mep-form-builder'); }else{ $desg = ''; }
    if($website){ $website = __('Website','mep-form-builder'); }else{ $website = ''; }
    if($veg){ $veg = __('Vegitarian?','mep-form-builder'); }else{ $veg = ''; }
    if($company){ $company = __('Company?','mep-form-builder'); }else{ $company = ''; }
    if($gender){ $gender = __('Company?','mep-form-builder'); }else{ $gender = ''; }
    if($tee){ $teee = __('Tee Size','mep-form-builder'); }else{ $teee = ''; }



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



    $event_info = apply_filters('mep_csv_fixed_cols', array(
        __('Ticket No','mep-form-builder'),
        __('Order ID','mep-form-builder'),
        __('Event Name','mep-form-builder'),        
        __('Event Date','mep-form-builder'),        
        __('Ticket','mep-form-builder')              
    ));

    $event_default_form = array(
        $name,
        $email,
        $phone,        
        $address,                
        $desg,                
        $website,                
        $veg,                
        $company,                
        $gender,                
        $teee,                
    );

    $event_form_builder_data = array();
    $mep_form_builder_data = get_post_meta($post_id, 'mep_form_builder_data', true);
      if ( $mep_form_builder_data ) {
        foreach ( $mep_form_builder_data as $_field ) {
          $event_form_builder_data[] = $_field['mep_fbc_label'];
        }
      }

    $order      = get_post_meta($post, 'mep_events_extra_prices', true);

    $event_extra_service_names        = array();
    if($order){
      foreach ($order as $_exs) {
          $event_extra_service_names[] = $_exs['option_name'];
      }
    }



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




// return $row;
return array_merge(array_filter($event_info),array_filter($event_default_form),array_filter($event_form_builder_data),array_filter($event_checkout_form));
}



function mep_get_event_user_fields_pro_data($post_id,$event,$order_status,$event_date){
  $event__reg_form_id    = mep_fb_get_reg_form_id($event);
$values = get_post_custom( $post_id );



    $name       = get_post_meta($event__reg_form_id,'mep_full_name',true);
    $email      = get_post_meta($event__reg_form_id,'mep_reg_email',true);
    $phone      = get_post_meta($event__reg_form_id,'mep_reg_phone',true);
    $adrs       = get_post_meta($event__reg_form_id,'mep_reg_address',true);
    $desg       = get_post_meta($event__reg_form_id,'mep_reg_designation',true);
    $website    = get_post_meta($event__reg_form_id,'mep_reg_website',true);
    $veg        = get_post_meta($event__reg_form_id,'mep_reg_veg',true);
    $company    = get_post_meta($event__reg_form_id,'mep_reg_company',true);
    $gender     = get_post_meta($event__reg_form_id,'mep_reg_gender',true);
    $tee        = get_post_meta($event__reg_form_id,'mep_reg_tshirtsize',true);


    if($name){ $name = get_post_meta($post_id,'ea_name',true); }else{ $name = ''; }
    if($email){ $reg_email = get_post_meta($post_id,'ea_email',true); }else{ $reg_email = ''; }
    if($phone){ $phone = get_post_meta($post_id,'ea_phone',true); }else{ $phone = ''; }
    if($adrs){ $address = get_post_meta($post_id,'ea_address_1',true); }else{ $address = ''; }
    if($desg){ $desg = get_post_meta($post_id,'ea_desg',true); }else{ $desg = ''; }
    if($website){ $website = get_post_meta($post_id,'ea_website',true); }else{ $website = ''; }
    if($veg){ $veg = get_post_meta($post_id,'ea_vegetarian',true); }else{ $veg = ''; }
    if($company){ $company = get_post_meta($post_id,'ea_company',true); }else{ $company = ''; }
    if($gender){ $gender = get_post_meta($post_id,'ea_gender',true); }else{ $gender = ''; }
    if($tee){ $teee = get_post_meta($post_id,'ea_tshirtsize',true); }else{ $teee = ''; }



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
$billing_paid         = mep_get_option('mep_billing_paid', 'csv_checkout_export_fileds_sec','');
$billing_method       = mep_get_option('mep_billing_method', 'csv_checkout_export_fileds_sec','');

  $checkin_status = get_post_meta($post_id,'mep_checkin',true);

  if($checkin_status){
    $status = $checkin_status;
  }else{
    $status = 'no';
  }

    
    $ticket                 = get_post_meta( $post_id, 'ea_user_id', true ).get_post_meta( $post_id, 'ea_order_id', true ).$event.$post_id;    
    $order                  = wc_get_order(get_post_meta( $post_id, 'ea_order_id', true ));
    $order_meta             = get_post_meta(get_post_meta( $post_id, 'ea_order_id', true ));  
    $country                = $order->get_billing_country() ? $order->get_billing_country() : '';
    $state                  = $order->get_billing_state() ? $order->get_billing_state() : "";

    $first_name             = array_key_exists('_billing_first_name' , $order_meta) ? $order_meta['_billing_first_name'][0] : ' ';
    $last_name              = array_key_exists('_billing_last_name' , $order_meta) ? $order_meta['_billing_last_name'][0] : ' ';
    $company_name           = array_key_exists('_billing_company' , $order_meta)  ? $order_meta['_billing_company'][0] : ' ';
    $bill_address_1         = array_key_exists('_billing_address_1' , $order_meta) ? $order_meta['_billing_address_1'][0] : ' ';
    $bill_address_2         = array_key_exists('_billing_address_2' , $order_meta)  ? $order_meta['_billing_address_2'][0] : ' ';
    $bill_city              = array_key_exists('_billing_city' , $order_meta) ? $order_meta['_billing_city'][0] : ' ';
    // $bill_state             = $order_meta['_billing_state'][0];
    $bill_state             = !empty($state) && WC()->countries->get_states( $country )[$state] ? WC()->countries->get_states( $country )[$state] : ' ';
    $bill_postcode          = array_key_exists('_billing_postcode' , $order_meta) ? $order_meta['_billing_postcode'][0] : ' ';
    // $bill_country           = $order_meta['_billing_country'][0];
    $bill_country           = !empty($country) && WC()->countries->countries[$country] ? WC()->countries->countries[$country] : ' ';
    $bill_email             = array_key_exists('_billing_email' , $order_meta) ? $order_meta['_billing_email'][0] : ' ';
    $bill_phone             = array_key_exists('_billing_phone' , $order_meta) ? $order_meta['_billing_phone'][0] : ' ';
    $bill_total             = $order->get_total() ? $order->get_total() : ' ';
    // $bill_total             = 0;
    $payment_method         = array_key_exists('_payment_method_title',$order_meta) ? $order_meta['_payment_method_title'][0] : ' ';
    $user_id                = $order_meta['_customer_user'][0] ? $order_meta['_customer_user'][0] : '';



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



    $event_info = apply_filters('mep_csv_fixed_cols_data',array(
          $ticket,
            get_post_meta( $post_id, 'ea_order_id', true ),
            get_the_title(get_post_meta( $post_id, 'ea_event_id', true )),  
            get_mep_datetime(get_post_meta( $post_id, 'ea_event_date', true ),'date-time-text'),  
            html_entity_decode(get_post_meta( $post_id, 'ea_ticket_type', true )),              
    ),$post_id);


    $event_default_form = array(
        $name,
        $reg_email,
        $phone,        
        $address,                
        $desg,                
        $website,                
        $veg,                
        $company,                
        $gender,                
        $teee,                
    );


 $event_form_builder_data = array();
    $mep_form_builder_data = get_post_meta($event__reg_form_id, 'mep_form_builder_data', true);
    if ( $mep_form_builder_data ) {
    foreach ( $mep_form_builder_data as $_field ) {
      $vname = "ea_".$_field['mep_fbc_id'];
      if(array_key_exists($vname, $values)){
        $event_form_builder_data[] =  get_post_meta( $post_id, $vname , true ) ? get_post_meta( $post_id, $vname , true ) : ' ';
      }else{
        $event_form_builder_data[] =  ' ';
    }
    }
  }



$order      = get_post_meta($event, 'mep_events_extra_prices', true);
$event_extra_service_names        = array();
$order_extra_service_arr = mep_get_event_extra_service_items_pro($post_id);
if($order_extra_service_arr){
  if($order){
foreach ($order as $_exs) {
    // $exs[] = $_exs['option_name'];
    $event_extra_service_names[] = mep_get_extra_service_order_qty_pro($_exs['option_name'], $order_extra_service_arr);
}
}
}

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
        get_post_meta( $post_id, 'ea_order_status', true )
    );

return array_merge($event_info,array_filter($event_default_form),array_filter($event_form_builder_data),array_filter($event_checkout_form));

}





function mep_get_event_extra_service_items_pro($post_id){

global $wpdb;

// $order_id = get_post_meta($post_id, 'ea_order_id', true);

// $item_table_name = $wpdb->prefix."woocommerce_order_items";

// if($order_id){
//   $sql = "SELECT order_item_id FROM $item_table_name WHERE order_item_type = 'line_item' AND order_id=$order_id";
//   $results = $wpdb->get_results($sql); //or die(mysql_error());

// if(!empty($results)){
// $order_item_id = $results[0]->order_item_id;

//   $table_name = $wpdb->prefix."woocommerce_order_itemmeta";

//   $sql2 = "SELECT event_id FROM $table_name WHERE order_item_id =$order_item_id AND meta_key='_event_service_info'";
//   $results2 = $wpdb->get_results($sql2);

//     if($results2){
//         return unserialize($results2[0]->event_id);
//     }else{
//         return array();
//     }
// }else{
//     return array();
// }
// }else{
//     return array();
// }

return array();
}

function mep_get_extra_service_order_qty_pro($name, $array) {
  if(!empty($array)){
   foreach ($array as $key => $val) {
       if ($val['option_name'] === $name ) {
           $extra_val = $val['option_qty'] ?: '00';
            return $extra_val;
       }
   }
}
   return '00';
}



// Add action hook only if action=download_csv
if ( isset($_REQUEST['action'] ) && $_REQUEST['action'] == 'download_csv_custom' )  {
  // Handle CSV Export
  add_action( 'admin_init', 'csv_export_pro') ;
}


// Add action hook only if action=download_csv






function csv_export_pro($id='',$date='') {
    // Check for current user privileges 
    if( !current_user_can( 'manage_options' ) ){ return false; }
    // Check if we are in WP-Admin
    if( !is_admin() ){ return false; }
    ob_start();
    $domain = $_SERVER['SERVER_NAME'];
    $filename = 'Event_Manager_Export_' . $domain . '_' . time() . '.csv';
    

        if(isset($_REQUEST['event_id'])){
          $post_id  = strip_tags($_REQUEST['event_id']);
          $header_row      = mep_get_event_user_fields_pro($post_id);          
        }else{
        $header_row = array(
        __('Ticket No','mep-form-builder'),
        __('Order ID','mep-form-builder'),
        __('Event','mep-form-builder'),        
        __('Ticket','mep-form-builder'),                
        __('Full Name','mep-form-builder'),
        __('Email','mep-form-builder'),
        __('Phone','mep-form-builder'),
        __('Addresss','mep-form-builder'),
        __('Tee Size','mep-form-builder'),
        __('Check in','mep-form-builder')
        );         
        }

    $data_rows = array();
    global $wpdb;
    $admin_order_status = mep_get_option('mep_csv_export_status', 'csv_general_attendee_sec','completed');

    if(isset($_REQUEST['event_id'])){
            $event_id     = $_REQUEST['event_id'];
            $event_date   = $_REQUEST['ea_event_date'];            
            $checkin_status   = $_REQUEST['checkin'];            
    }
    // $a_query = mep_fb_attendee_query($event_id, $event_date, -1,$filter_by,$ev_filter_key,$checkin_status);

$loop = mep_fb_attendee_query($event_id,$event_date,-1,'','',$checkin_status);

while ($loop->have_posts()) {
$loop->the_post();
$post_id  = get_the_id();


if (get_post_type($post_id) == 'mep_events_attendees') {
    
   
     $order_status = get_post_meta( $post_id, 'ea_order_status', true );   

    
        if(isset($_REQUEST['event_id'])){ 
            
          $event    = strip_tags($_REQUEST['event_id']);
          $row      = mep_get_event_user_fields_pro_data($post_id,$event,$order_status,$event_date);   
        
        
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
            get_the_title(get_post_meta( $post_id, 'ea_event_id', true )),  
            get_mep_datetime(get_post_meta( $post_id, 'ea_event_date', true ),'date-time-text'),  
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