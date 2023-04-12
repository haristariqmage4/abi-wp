<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

add_action('admin_menu', 'mep_fb_rpv_menu');
function mep_fb_rpv_menu()
{
    add_submenu_page('edit.php?post_type=mep_events', __('Report Overview', 'mep-form-builder'), __('Report Overview', 'mep-form-builder'), 'manage_woocommerce', 'mep_report_overview', 'mep_fb_rpv_attendee_list_dashboard');
}

function mep_fb_rpv_attendee_query($start_date, $end_date, $event_id = '', $type= '',$payment_gateway=0)
{
    $event_filter =  !empty($event_id) ? array(
        'key'           => 'ea_event_id',
        'value'         => $event_id,
        'compare'       => '='
    ) : '';

    $event_type_filter =  !empty($type) ? array(
        'key'           => 'ea_ticket_type',
        'value'         => $type,
        'compare'       => '='
    ) : '';

    $event_gateway_filter =  !empty($payment_gateway) ? array(
        'key'           => 'ea_payment_method',
        'value'         => $payment_gateway,
        'compare'       => 'IN'
    ) : '';
    
    $f_date             = date('Y-m-d', strtotime($start_date));
    $start_date         = date('Y-m-d H:i', strtotime('-1 day', strtotime($start_date . ' 11:59 pm')));    
    $end_date           = date('Y-m-d H:i', strtotime($end_date . ' 11:59 pm'));    
    $event_date_meta_q  = $start_date != '' && $end_date != '' ? array(
        'key'           => 'ea_event_date',
        'value'         => array($start_date, $end_date),
        'compare'       => 'BETWEEN',
        'type'          => 'DATE'
    ) : '';

    $args = array(
        'post_type'             => 'mep_events_attendees',
        'posts_per_page'        => -1,        
        'meta_query'            => array(
            'relation'          => 'AND',
            array(
                'relation'      => 'AND',
                $event_filter,
                $event_type_filter,
                $event_gateway_filter,
                $event_date_meta_q,                
            ),            
            array(
                'relation'      => 'OR',
                
                array(
                    'key'       => 'ea_order_status',
                    'value'     => 'completed',
                    'compare'   => '='
                ),
                array(
                    'key'       => 'ea_order_status',
                    'value'     => 'processing',
                    'compare'   => '='
                )
            )
        )
    );
    $loop = new WP_Query($args);
    return $loop;
}


function mep_fb_rpv_extra_service_query($start_date, $end_date, $event_id = '', $type= '',$payment_gateway='')
{
    // echo $type;
// echo $payment_gateway;
    $event_filter =  !empty($event_id) ? array(
        'key'     => 'ea_extra_service_event',
        'value'   => $event_id,
        'compare' => '='
    ) : '';

    $event_type_filter =  !empty($type) ? array(
        'key'     => 'ea_extra_service_name',
        'value'   => $type,
        'compare' => '='
    ) : '';

    $event_gateway_filter =  !empty($payment_gateway) ? array(
        'key'     => 'ea_payment_method',
        'value'   => $payment_gateway,
        'compare' => 'IN'
    ) : '';

    // $event_gateway_filter =  !empty($payment_gateway) ? mep_fb_rpv_payment_gateway_arr($payment_gateway): '';
    $f_date = date('Y-m-d', strtotime($start_date));
//     $start_date = date('Y-m-d',strtotime($start_date)) == date('Y-m-d',strtotime($end_date)) ? date('Y-m-d', strtotime('-1 day', strtotime($start_date))) : $start_date;
   
    $start_date = date('Y-m-d',strtotime($start_date)) == date('Y-m-d',strtotime($end_date)) ? date('Y-m-d H:i', strtotime('-1 day', strtotime($start_date . ' 11:59 pm'))) : date('Y-m-d H:i', strtotime($start_date. ' 12:01 am'));
    
    //  $start_date;
     $end_date = date('Y-m-d H:i', strtotime($end_date . ' 11:59 pm'));   
   
   
   
    $event_date_meta_q = $start_date != '' && $end_date != '' ? array(
        'key' => 'ea_extra_service_event_date',
        'value' => array($start_date,$end_date),
        'compare' => 'BETWEEN',
        'type' => 'DATE'
    ) : '';


    $args = array(
        'post_type'             => 'mep_extra_service',
        'posts_per_page'        => -1,
        // 'date_query'            => array(
        //     //set date ranges with strings!
        //     'after'             => date('Y-m-d H:i:s', strtotime($start_date . ' 12:01 am')),
        //     'before'            => date('Y-m-d H:i:s', strtotime($end_date . ' 11:59 pm')),
        //     //allow exact matches to be returned
        //     'inclusive'         => true,
        // ),        
        'meta_query'  => array(
            'relation' => 'AND',
            array(
                'relation' => 'AND',
                $event_filter,
                $event_gateway_filter,
                $event_date_meta_q,
                $event_type_filter
            ),            
            array(
                'relation' => 'OR',
                array(
                    'key'     => 'ea_extra_service_order_status',
                    'value'   => 'completed',
                    'compare' => '='
                ),
                array(
                    'key'     => 'ea_extra_service_order_status',
                    'value'   => 'processing',
                    'compare' => '='
                )
            )
        )
    );
    $loop = new WP_Query($args);
// 	    echo '<pre>'; print_r($loop); echo '</pre>';
    return $loop;
}


function mep_event_payment_gateway_list(){
    global $wpdb;
      $table_name = $wpdb->prefix."postmeta";
      $sql = "SELECT meta_value FROM $table_name WHERE meta_key ='ea_payment_method' GROUP BY meta_value";
      $results = $wpdb->get_results($sql);
      ob_start();
      ?>
      <div class='mep-city-list'>
        <select name='report_overview_gateway_list' id='report_overview_gateway_list' class='select2 mep_report_event_list' multiple>
            <option value=0><?php _e('Select Payment Gateway','mep-form-builder'); ?></option>
        <?php
        foreach( $results as $result ) {
            if(!empty($result->meta_value)){
        ?>
            <option value='<?php echo $result->meta_value; ?>'><?php echo $result->meta_value; ?></option>
        <?php
            }
        }
        ?>
        </select>
      </div>
      <script>

jQuery(document).ready(function($) {
                <?php do_action('mep_fb_rpv_attendee_list_script'); ?>                
                $('.mep_report_event_list').select2({
            width: 'resolve',
            theme: "classic"
        });            
        });            
      </script>
      <?php
      return ob_get_clean();
}


function mep_fb_rpv_payment_gateway_arr($arr){
    if(sizeof($arr) > 0){
        $cn = 0;
        foreach($arr as $_arr){
            $t[$cn] = array(
                'key'     => 'ea_payment_method',
                'value'   => $_arr,
                'compare' => '='
            );
            $cn++;
        }

        
    }
return $t;

}

function mep_fb_rpv_attendee_list_dashboard()
{
    $event_id = isset($_REQUEST['event_id']) ? strip_tags($_REQUEST['event_id']) : 0;
    $event_date = isset($_REQUEST['ea_event_date']) ? strip_tags($_REQUEST['ea_event_date']) : 0;

?>

    <div class="wrap">
        <h2><?php _e('Event Overall Report', 'mep-form-builder'); ?></h2>

<?php 
// $ff = mep_fb_rpv_payment_gateway_arr(array('cod','bank'));
// print_r($ff);
?>



        <div class='attendee_filter_section'>     
        
            <ul>                
                <li>
                    <div class='event_filter'>
                    <input class="from_date" type="text" id="three_from_date" name="from_date" value="" placeholder="yyyy-mm-dd">
                    </div>
                </li>            
                <li>
                    <div class='event_filter'>
                    <input class="to_date" type="text" id="three_to_date" name="to_date" value="" placeholder="yyyy-mm-dd">
                    </div>
                </li>    
                <li>
                    <?php echo mep_event_payment_gateway_list(); ?>
                </li>        
                <li>
                    <button id='event_attendee_filter_btn'><?php _e('Generate Report','mep-form-builder'); ?></button>
                </li>
            </ul>
        </div>
        <div id='before_attendee_table_info'></div>
        <div id='event_attendee_list_table_item'></div>
    </div>
    <script>
        (function($) {
            'use strict';
            jQuery(document).ready(function($) {
                <?php do_action('mep_fb_rpv_attendee_list_script'); ?>                
                $('#mep_event_id').select2({
            width: 'resolve',
            theme: "classic"
        });                  

                jQuery('#event_attendee_filter_btn').on('click', function() {
                    var event_id = jQuery('#mep_event_id').val();


                        var start_date      = jQuery('#three_from_date').val();
                        var end_date        = jQuery('#three_to_date').val();
                        var payment_gateway = jQuery('#report_overview_gateway_list').val();
                    if(start_date != '' && end_date != ''){
                        // alert(payment_gateway);
                        jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl,
                            data: {
                                "action": "mep_fb_rpv_ajax_attendee_filter",
                                "start_date"        : start_date,
                                "payment_gateway"   : payment_gateway,
                                "end_date"          : end_date
                            },
                            beforeSend: function() {
                                jQuery('#event_attendee_list_table_item').html('<h5 class="mep-processing"><?php echo mep_get_option('mep_event_rpv_please_wait_attendee_loading_text', 'label_setting_sec', __('Please wait! Report is generating.....', 'mep-form-builder')); ?></h5>');
                            },
                            success: function(data) {
                                jQuery('#event_attendee_list_table_item').html(data);
                               
                            }
                        });
                    } else {
                        alert('<?php _e('Please Enter the Report Generate Start & End Date First.', 'mep-form-builder'); ?>');
                    }
                    return false;
                });

                $(document).on('click', '#mep_rpv_export_pdf', function() {

                   var start_date   = $(this).attr("data-start");
                   var end_date     = $(this).attr("data-end");
                   var event_id     = $(this).attr("data-event");
                   var payment_gateway = jQuery('#report_overview_gateway_list').val();
                
                       jQuery.ajax({
                            type: 'POST',
                            url: ajaxurl,                            
                            data: {
                                "action": "mep_fb_rpv_ajax_export_pdf",
                                "start_date": start_date,
                                "event_id": event_id,
                                "payment_gateway"   : payment_gateway,
                                "end_date": end_date
                            },
                            beforeSend: function() {
                                jQuery('#mep_export_notice').html('<h5 class="mep-processing"><?php echo mep_get_option('mep_event_rec_please_wait_attendee_loading_text', 'label_setting_sec', __('Please wait! PDF Report is generating.....', 'mep-form-builder')); ?></h5>');
                            },
                            success: function(data) {                                
                                jQuery('#mep_export_notice').hide();
                                window.open(data);
                               
                            }
                        });
                });
            });
        })(jQuery);
    </script>
<?php
}


add_action('wp_ajax_mep_fb_rpv_ajax_export_pdf', 'mep_fb_rpv_ajax_export_pdf');
function mep_fb_rpv_ajax_export_pdf()
{

        $start_date                 = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
        $end_date                   = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
        $payment_gateway            = isset($_REQUEST['payment_gateway']) ? $_REQUEST['payment_gateway'] : [];        
        $event_id                   = 123;
        $time = time();

        $a_query                    = mep_fb_rpv_attendee_query($start_date, $end_date,'','',$payment_gateway);
        $events                     = mep_fb_get_events($a_query);
        $attendee_query             = $a_query->posts;    
        
        $upload_dir                 = wp_upload_dir();        
        $ticket_name                = $upload_dir['basedir'].'/'.__('event_report_of','mep-form-builder').$start_date.$time.'.pdf';
        $file_name                  = __('event_report_of','mep-form-builder').$start_date.$time.'.pdf';

        $html                       = mep_fb_rpv_report_table($events,$start_date,$end_date,$a_query,$payment_gateway);
      
        $mpdf                       = new \Mpdf\Mpdf();

        $mpdf->allow_charset_conversion=true; 
        $mpdf->autoScriptToLang = true;
        $mpdf->baseScript = 1;
        $mpdf->autoVietnamese = true;
        $mpdf->autoArabic = true;
        $mpdf->autoLangToFont = true;
        $mpdf->WriteHTML($html);
        $mpdf->Output($ticket_name, 'F');
        echo $upload_dir['baseurl'].'/'.$file_name;
    die();
}

function mep_fb_get_events($query){
    $events = [];    
    foreach ($query->posts as $_attendee) {
        $attendee_id = $_attendee->ID;
        $event_id = get_post_meta($attendee_id,'ea_event_id',true) ? get_post_meta($attendee_id,'ea_event_id',true) : 0;
        $events[] = $event_id;
    }    
    return array_unique($events);
}


function mep_fb_rpv_report_table($events,$start_date,$end_date,$query,$payment_gateway){
ob_start();
?>
<style>
.report-table {
    background: #fff;
    padding: 10px;
    /* margin: 10px 0; */
    border: 1px solid #ddd;
    /* width: 55%; */
    margin: 20px auto;
}

.report-table h2 {
    margin: 0 0 20px 0;
    font-size: 16px;
    /* background: #ddd; */
    padding: 5px 10px 15px 5px;
    text-align: center;
    border-bottom: 1px dashed #ddd;
}

.report-table h3 {
    margin: 20px 0 -5px 0;
    font-size: 14px;
    background: #ddd;
    padding: 8px;
    font-weight: normal;
    display: inline-block;
}

.report-table table {
    width: 100%;
    border: 0px solid #ddd;
    margin: 5px 0;
}

.report-table table tr th {
    text-align: left;
    background: #f1f1f1;
    padding: 5px;
}

.report-table table tr td {
    border-bottom: 1px solid #ddd;
    padding: 5px;
}
.mep_pdf_header_sec {
    background: #fff;
    padding: 10px;
    margin-bottom: -10px;
    border: 1px solid #ddd;
    margin-top: 10px;
    /* width: 55%; */
    margin: 0 auto -18px;
}

.mep_pdf_header_sec h2 {
    padding: 0;
    margin: 0;
    font-size: 15px;
    text-align: center;
}

.mep_pdf_header_sec h3 {
    text-align: center;
    font-size: 13px;
}
button#event_attendee_filter_btn {
    cursor: pointer;
}
.attendee_filter_section {
    background: #fff;
    padding: 10px;
    border: 2px solid #ddd;
    width: 55%;
    margin: 0 auto;
    text-align: center;
}
.report-table .heading {
    font-size: 15px;
    text-align: center;
    display: block;
    font-weight: bold;
    border-bottom: 1px dashed #ddd;
    padding: 5px 0 10px 0;
    margin: 0 0 20px 0;
}
</style>
<div class='mep_pdf_header_sec'>
<h2><?php _e('Report Overview','mep-form-builder'); ?></h2>
<h3><?php _e('Selected Period:','mep-form-builder'); ?> <?php echo get_mep_datetime($start_date.'00:01','date-time-text'); ?> - <?php echo get_mep_datetime($end_date.'23:59','date-time-text'); ?></h3>
<?php if(!empty($payment_gateway)){
?>
    <h3><?php _e('Payment Method:','mage-eventpress');  echo implode(',',$payment_gateway);  ?></h3>
<?php
} ?>
</div>
<?php
    foreach($events as $event){
        ?>
        <div class="report-table">
            <span class='heading'><?php _e('Event: ','mep-form-builder');  echo get_the_title($event); ?> </span>
            <!-- <h3><?php _e('Ticket Type:','mep-form-builder'); ?></h3> -->
            <table>
                <thead>
                    <tr>
                        <th width='20%'><?php _e('Ticket Type Name','mep-form-builder'); ?></th>
                        <th width='20%'><?php _e('Quantity','mep-form-builder'); ?></th>
                        <th width='20%'><?php _e('Price incl. VAT','mep-form-builder'); ?></th>
                        <th width='20%'><?php _e('Price excl. VAT','mep-form-builder'); ?></th>
                        <th width='20%'><?php _e('VAT','mep-form-builder'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php mep_fb_rpv_ticket_type_list($event,$start_date,$end_date,$payment_gateway); ?>
                </tbody>
            </table>

            <!-- <h3>Extra Service:</h3> -->
            <table>
                <thead>
                    <tr>
                    <th width='20%'><?php _e('Extra Service Name','mep-form-builder'); ?></th>
                        <th width='20%'><?php _e('Quantity','mep-form-builder'); ?></th>
                        <th width='20%'><?php _e('Price incl. VAT','mep-form-builder'); ?></th>
                        <th width='20%'><?php _e('Price excl. VAT','mep-form-builder'); ?></th>
                        <th width='20%'><?php _e('VAT','mep-form-builder'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php  mep_fb_rpv_extra_service_list($event,$start_date,$end_date,$payment_gateway); ?>
                </tbody>            
            </table>
        </div>
        <?php
    }
return ob_get_clean();
}


function mep_fb_get_total_sold_ticket_type_qty($event_id,$ticket_type,$start_date,$end_date,$payment_gateway){
    $a_query   = mep_fb_rpv_attendee_query($start_date, $end_date,$event_id,$ticket_type,$payment_gateway);
    return $a_query->post_count;
}



function mep_fb_get_ticket_price_by_name($event_id,$ticket_name,$type='ticket'){
    $ticket_type      = get_post_meta($event_id, 'mep_event_ticket_type', true) ? get_post_meta($event_id, 'mep_event_ticket_type', true) : array();
    $extra_prices    = get_post_meta($event_id, 'mep_events_extra_prices', true) ? get_post_meta($event_id, 'mep_events_extra_prices', true) : array();
    
if($type == 'ticket'){
    if(is_array($ticket_type) && sizeof($ticket_type) > 0){
        foreach ($ticket_type as $field) {
            if($field['option_name_t'] === $ticket_name){
                return (float) $field['option_price_t'];
            }
        }
    }else{
        return 0;
    }
}
    
if($type == 'service'){
    if(is_array($extra_prices) && sizeof($extra_prices) > 0){
        foreach ($extra_prices as $field) {
            if($field['option_name'] === $ticket_name){
                return (float) $field['option_price'];
            }
        }
    }else{
        return 0;
    }
}




}


function mep_fb_get_total_sold_ticket_type_amount($event_id,$ticket_type,$start_date,$end_date,$payment_gateway){
    $a_query                    = mep_fb_rpv_attendee_query($start_date, $end_date,$event_id,$ticket_type,$payment_gateway);


// echo '<pre>'; print_r($a_query); echo '</pre>';

    $total                      = 0;
    foreach ($a_query->posts as $atn) {
        $atn_id = $atn->ID; 
         $total = $total + (float) mep_fb_get_ticket_price_by_name($event_id,get_post_meta($atn_id,'ea_ticket_type',true));
    }
    return $total;
}


function mep_fb_rpv_ticket_type_list($event_id,$start_date,$end_date,$payment_gateway=0){
    $event_ticket_type      = get_post_meta($event_id, 'mep_event_ticket_type', true) ? get_post_meta($event_id, 'mep_event_ticket_type', true) : array();
    $total_qty              = 0;
    $total_price_exc_vat    = 0;
    $total_price_inc_vat    = 0;
    
    foreach($event_ticket_type as $tickets){
        $type_name = $tickets['option_name_t'];

        $qty                    = mep_fb_get_total_sold_ticket_type_qty($event_id,$type_name,$start_date,$end_date,$payment_gateway);
        $price_exc_vat          = mep_get_price_excluding_tax( $event_id,mep_fb_get_total_sold_ticket_type_amount($event_id,$type_name,$start_date,$end_date,$payment_gateway));
        $price_inc_vat          = mep_get_price_including_tax( $event_id,mep_fb_get_total_sold_ticket_type_amount($event_id,$type_name,$start_date,$end_date,$payment_gateway));
        $vat                    = ($price_inc_vat - $price_exc_vat);
        $total_qty              = $total_qty + $qty;
        $total_price_exc_vat    = $price_exc_vat + $total_price_exc_vat;
        $total_price_inc_vat    = $price_inc_vat + $total_price_inc_vat;
        ?>
            <tr>
                <td><?php echo $type_name; ?></td>
                <td><?php echo $qty; ?></td>
                <td><?php echo wc_price($price_inc_vat); ?></td>
                <td><?php echo wc_price($price_exc_vat); ?></td>
                <td><?php echo wc_price($vat); ?></td>
            </tr>
        <?php
    }
    ?>
   <tr style="background-color: #f5f5f5;">
                <td><strong><?php _e('Total:',''); ?></strong></td>
                <td><strong><?php echo $total_qty; ?></strong></td>
                <td><strong><?php echo wc_price($total_price_inc_vat); ?></strong></td>
                <td><strong><?php echo wc_price($total_price_exc_vat); ?></strong></td>
                <td><strong><?php echo wc_price($total_price_inc_vat - $total_price_exc_vat); ?></strong></td>
            </tr>
    <?php
}



function mep_fb_get_extra_service_total($event_id,$ticket_type,$start_date,$end_date,$payment_gateway,$type='qty'){    
    $a_query                    = mep_fb_rpv_extra_service_query($start_date,$end_date,$event_id,$ticket_type,$payment_gateway);
    $total                      = 0;
    foreach ($a_query->posts as $atn) {
        $atn_id = $atn->ID;
        $meta_name = $type == 'qty' ? 'ea_extra_service_qty' : 'ea_extra_service_total_price';
        $price = (int) get_post_meta($atn_id,$meta_name,true);
        // ea_extra_service_name
    //  echo   mep_fb_get_ticket_price_by_name($event_id,get_post_meta($atn_id,'ea_extra_service_name',true),'service');
        $total = $total + (float) get_post_meta($atn_id,$meta_name,true);
    }
    
    return $total;
}



function mep_fb_rpv_extra_service_list($event_id,$start_date,$end_date,$payment_gateway){
    
    $event_ticket_type      = get_post_meta($event_id, 'mep_events_extra_prices', true) ? get_post_meta($event_id, 'mep_events_extra_prices', true) : array();
    $total_qty              = 0;
    $total_price_exc_vat    = 0;
    $total_price_inc_vat    = 0;
    foreach($event_ticket_type as $tickets){
        $type_name = $tickets['option_name'];
        $qty           = mep_fb_get_extra_service_total($event_id,$type_name,$start_date,$end_date,$payment_gateway,'qty');
        $price_exc_vat = mep_get_price_excluding_tax( $event_id,mep_fb_get_extra_service_total($event_id,$type_name,$start_date,$end_date,$payment_gateway,'price'));
        $price_inc_vat = mep_get_price_including_tax( $event_id,mep_fb_get_extra_service_total($event_id,$type_name,$start_date,$end_date,$payment_gateway,'price'));
        $vat           = ($price_inc_vat - $price_exc_vat);
        $total_qty              = $total_qty + $qty;
        $total_price_exc_vat    = $price_exc_vat + $total_price_exc_vat;
        $total_price_inc_vat    = $price_inc_vat + $total_price_inc_vat;
        ?>
            <tr>
                <td><?php echo $type_name;  ?></td>
                <td><?php echo $qty; ?></td>
                <td><?php echo wc_price($price_inc_vat); ?></td>
                <td><?php echo wc_price($price_exc_vat); ?></td>
                <td><?php echo wc_price($vat);  ?></td>
            </tr>
        <?php
    }
    ?>
   <tr style="background-color: #f5f5f5;">
                <td><strong><?php _e('Total:',''); ?></strong></td>
                <td><strong><?php echo $total_qty; ?></strong></td>
                <td><strong><?php echo wc_price($total_price_inc_vat); ?></strong></td>
                <td><strong><?php echo wc_price($total_price_exc_vat); ?></strong></td>
                <td><strong><?php echo wc_price($total_price_inc_vat - $total_price_exc_vat); ?></strong></td>
            </tr>
    <?php    
}




add_action('wp_ajax_mep_fb_rpv_ajax_attendee_filter', 'mep_fb_rpv_ajax_attendee_filter');
function mep_fb_rpv_ajax_attendee_filter()
{
    
    $start_date                 = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
    $end_date                   = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
    $payment_gateway            = isset($_REQUEST['payment_gateway']) ? $_REQUEST['payment_gateway'] : [];
    $a_query                    = mep_fb_rpv_attendee_query($start_date, $end_date,'','', $payment_gateway);
    $events                     = mep_fb_get_events($a_query);
    $attendee_query             = $a_query->posts;  
    
    // echo '<pre>'; print_r($a_query); echo '</pre>';
    
    ?>
    <style>
   .mep-fb-rpv-pdf button {
    text-align: center;
    display: block;
    /* float: right; */
    overflow: hidden;
    background: #d63638;
    border: 1px solid #ddd;
    padding: 10px 20px;
    color: #fff;
    cursor: pointer;
    margin: 15px auto;
    width: 200px;
}
    .mep-fb-rpv-pdf {overflow: hidden;}
    .mep_fb_rpv_table_sec {
    width: 56%;
    margin: 0 auto;
}    
    </style>
    <div class='mep-fb-rpv-pdf'>
        <?php do_action('mep_rpv_export_pdf_btn',$start_date,$end_date,$payment_gateway); ?>
        <div id="mep_export_notice"></div>
    </div>
    <div class='mep_fb_rpv_table_sec'>
    <?php      
      echo  mep_fb_rpv_report_table($events,$start_date,$end_date,$a_query,$payment_gateway);
    ?>
    </div>
    <?php
    die();
}


add_action('admin_init','mep_rep_ov_upgrade',90);
function mep_rep_ov_upgrade(){
    if (get_option('mep_report_overview_upgrade_02') != 'completed') {
        $args = array(
            'post_type' => 'mep_extra_service',
            'posts_per_page' => -1
        );
        $qr = new WP_Query($args);
        foreach ($qr->posts as $result) {
            $post_id                = $result->ID;
            $order_id               = get_post_meta($post_id, 'ea_extra_service_order', true);
            $order                  = wc_get_order( $order_id );
            $order_status           = $order->get_payment_method_title();
            update_post_meta($post_id, 'ea_payment_method', $order_status);            
        }
        update_option('mep_report_overview_upgrade_02', 'completed');
    }
}