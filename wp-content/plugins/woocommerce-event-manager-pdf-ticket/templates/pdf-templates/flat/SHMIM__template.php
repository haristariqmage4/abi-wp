<?php
/*
* Template Name : Flat
* ----------------------------
* Author        : @magepeopleteam
* Copyright     : 2019 @ magepeopleteam
*/

 if ( ! defined('ABSPATH')) exit;  // if direct access  

global $wbtm, $wpdb;

//$html = preg_replace('/>\s+</', "><", $html);

?>
<html>
<body>
<style type="text/css">
html, body{
    height:0;
}
.pdf-ticket-body{
    height:950px;
    width: 100%;
}
.mep_ticket_body {
    <?php if($wbtm->settings->wbtm_ticket_bg_color) { ?>
background:<?php echo $wbtm->settings->wbtm_ticket_bg_color; ?>;  
    <?php }else{ ?>    
   background: #fbfbfb;
<?php } ?>
    padding: 0 10px; 
    <?php if($wbtm->settings->wbtm_ticket_text_color) { ?>
color:<?php echo $wbtm->settings->wbtm_ticket_text_color; ?>;  
    <?php }else{ ?>    
    color:#000;  
<?php } ?>
    position: relative;

}
ul li, .mep_event_order_information, .mep_event_order_information p, table{
    <?php if($wbtm->settings->wbtm_ticket_text_color) { ?>
color:<?php echo $wbtm->settings->wbtm_ticket_text_color; ?>;  
    <?php }else{ ?>    
    color:#000;  
<?php } ?>
}
.mep_tkt_row {
    display: block;
    overflow: hidden;
    margin: 10px 0;
}

.mep_ticket_body_col_6 {
    <?php if($pdflibrary == 'dompdf'){ ?>
    width: 49%;
    display:inline-block;
    <?php }else{ ?>
    width: 48%;
    float:left;
    <?php } ?>
    vertical-align: top;
}

.mep_ticket_body ul {
    padding: 0;
    margin: 0;
    list-style: none;
    vertical-align: top;
}
.mep_ticket_body ul li{
    margin: 10px 0;
}
.mep_event_qr_code_section {
    text-align: center;
}

.mep_event_qr_code_section img {
    width: 100px;
}
.mep_ticket_body ul li p {
    padding: 0;
    margin: 0;
    display: inline-block;
}
.mep_event_order_information ul li {
    text-align: center;
    float: left;
}
.mep_event_order_information ul li p {
    display: block;
}

.mep_event_order_information ul li {
    vertical-align: top;
}
.mep_event_ticket_terms {
    text-align: center;
    font-size: 12px;
    border-top: 1px solid #ddd;
    margin-top: 30px;
}
.mep_event_ticket_terms h3 {
    font-size: 15px;
    font-weight: bold;
    margin: 10px 0;
    padding: 0;
}
.page_break{
    page-break-after: always;
}
.page_break:last-child {
     page-break-after: auto;
}
</style>
<?php
$pdflibrary         = 'mpdf';
    $order_data     = $wbtm->get_order_data( $order_id, 'object' );
    $logo_url       = empty( $wbtm->settings->wbtm_logo ) ? '' : wp_get_attachment_url( $wbtm->settings->wbtm_logo );
    $ticket_bg_url       = empty( $wbtm->settings->wbtm_ticket_bg ) ? '' : wp_get_attachment_url( $wbtm->settings->wbtm_ticket_bg );

    $order      = wc_get_order( $order_id );

    foreach ( $order->get_items() as $item_id => $item_values ) {
        $product_id     = $item_values->get_product_id(); 
        $item_data      = $item_values->get_data();
        $product_id     = $item_data['product_id'];
        $item_quantity  = $item_values->get_quantity();
        $product        = get_page_by_title( $item_data['name'], OBJECT, 'mep_events' );
        $event_name     = $item_data['name'];
        $event_id       = $product->ID;
        $item_id        = $item_id;
    }

    $extra_info_arr = wc_get_order_item_meta($item_id,'_event_service_info',true);

    $args   =   array(
        'posts_per_page'   => -1,
        'post_type'     => 'mep_events_attendees',
        'meta_query'    => array(
            array(
            'key'       => 'ea_order_id',
            'value'     => $order_id,
            'compare'   => '=',
            )
        )
    );
    $query = new WP_Query($args);
    while($query->have_posts()){
    $query->the_post();
    $values = get_post_custom(get_the_id());
$event_id = $values['ea_event_id'][0];
$event_meta = get_post_custom($event_id);
$org_arr = get_the_terms( $event_id, 'mep_org' );
$ea_event_date = isset($values['ea_event_date'][0]) && !empty($values['ea_event_date'][0]) ? $values['ea_event_date'][0] : get_post_meta($values['ea_event_id'][0],'event_start_datetime',true);
?>


<div class='pdf-ticket-body'>

<div class="mep_ticket_body" <?php if($ticket_bg_url){ ?>style="background: url(<?php echo $ticket_bg_url; ?>);" <?php } ?>>
<div class="pdf-header">
    <?php if( ! empty( $logo_url ) ) printf( '<img src="%s"/>', $logo_url ); ?>
    <p><?php echo $wbtm->settings->wbtm_address; ?> </p>
    <p><?php echo $wbtm->settings->wbtm_phone; ?> </p>
</div>
<div class="mep_tkt_row">
<div class="mep_ticket_body_col_6">
    <div class="mep_event_information">
        <ul>
            <li><strong><?php _e('Event:','mage-eventpress-pdf') ?></strong> <?php echo $values['ea_event_name'][0]; ?></li>
            <li><strong><?php _e('Organized by:','mage-eventpress-pdf') ?></strong> <?php echo $org_arr[0]->name; ?></li>
            <li><strong><?php _e('Start Date:','mage-eventpress-pdf') ?></strong> <?php echo get_mep_datetime($ea_event_date, 'date-text');  ?></li>
            <li><strong><?php _e('Start Time:','mage-eventpress-pdf') ?></strong> <?php echo get_mep_datetime($ea_event_date, 'time');  ?></li>
            
        </ul>
    </div>
</div>
<div class="mep_ticket_body_col_6">
    <div class="mep_atndee_information">
        <ul>
            <li><strong><?php _e('Name:','mage-eventpress-pdf') ?></strong> <?php echo $values['ea_name'][0]; ?></li>
            <?php if($values['ea_email'][0]){ ?>
            <li><strong><?php _e('Email:','mage-eventpress-pdf') ?></strong> <?php echo $values['ea_email'][0]; ?></li> 
            <?php } if($values['ea_phone'][0]){ ?>
            <li><strong><?php _e('Phone:','mage-eventpress-pdf') ?></strong> <?php echo $values['ea_phone'][0]; ?> </li>
            <?php } if($values['ea_address_1'][0]){ ?>
            <li><strong><?php _e('Address:','mage-eventpress-pdf') ?></strong> <?php echo $values['ea_address_1'][0]; ?></li> 
            <?php } if($values['ea_desg'][0]){ ?>
            <li><strong><?php _e('Designation:','mage-eventpress-pdf') ?></strong> <?php echo $values['ea_desg'][0]; ?></li> 
            <?php } if($values['ea_company'][0]){ ?>
            <li><strong><?php _e('Company:','mage-eventpress-pdf') ?></strong> <?php echo $values['ea_company'][0]; ?> </li>
            <?php } if($values['ea_website'][0]){ ?>
            <li><strong><?php _e('Website:','mage-eventpress-pdf') ?></strong> <?php echo $values['ea_website'][0]; ?></li>
            <?php } if($values['ea_gender'][0]){ ?>
            <li><strong><?php _e('Gender:','mage-eventpress-pdf') ?></strong> <?php echo $values['ea_gender'][0]; ?></li>
            <?php } if($values['ea_vegetarian'][0]){ ?>
            <li><strong><?php _e('Vegetarian:','mage-eventpress-pdf') ?></strong> <?php echo $values['ea_vegetarian'][0]; ?></li>
            <?php } if($values['ea_tshirtsize'][0]){ ?>
            <li><strong><?php _e('T-Shirt Size:','mage-eventpress-pdf') ?></strong> <?php echo $values['ea_tshirtsize'][0]; ?></li>
            <?php } 
            $mep_form_builder_data = get_post_meta($values['ea_event_id'][0], 'mep_form_builder_data', true);
              if ( $mep_form_builder_data ) {
                foreach ( $mep_form_builder_data as $_field ) {        
                $vname = "ea_".$_field['mep_fbc_id']; 
                $vals = $values[$vname][0];
            if($vals){
            ?>
                <li><strong><?php echo $_field['mep_fbc_label']; ?>:</strong> <?php echo $vals; ?></li>   
            <?php
            }
            }
        }
        ?>
        </ul>
    </div>
</div>
</div>   

<div class="mep_tkt_row">
<div class="mep_ticket_body_col_12">
    <div class="mep_event_location_information">
        <ul>
            <li><strong><?php _e('Event location:','mage-eventpress-pdf'); ?></strong> <?php mep_ev_location_ticket($event_id,$event_meta); ?></li>    
        </ul>
    </div>
</div>
</div> 

<div class="mep_tkt_row">
<div class="mep_ticket_body_col_12">
        <div class="mep_event_qr_code_section">
            <?php do_action('mep_qr_code', get_the_permalink()); ?>
        </div>
</div>
</div> 

<div class="mep_tkt_row">
<div class="mep_ticket_body_col_12">
    <div class="mep_event_order_information">
        <table style="width:100%">
            <tr>
                <td align="center"><p><strong><?php _e('Order ID:','mage-eventpress-pdf'); ?></strong></p> <?php echo $values['ea_order_id'][0]; ?></td>
                <td align="center"><p><strong><?php _e('Ticket number:','mage-eventpress-pdf'); ?></strong></p> <?php echo $values['ea_user_id'][0].$values['ea_order_id'][0].$event_id.get_the_id(); ?></td>
                <td align="center"><p><strong><?php _e('Ticket price:','mage-eventpress-pdf'); ?></strong></p>  <?php mep_get_event_ticket_price($values['ea_event_id'][0],$values['ea_ticket_type'][0]); ?></td>
                <?php if($values['ea_ticket_type'][0]){ ?><td align="center"><p><strong><?php _e('Ticket type:','mage-eventpress-pdf') ?></strong></p> <?php echo $values['ea_ticket_type'][0]; ?></td><?php } ?>
            </tr>
        </table>
    </div>
</div>
</div>  

<div class="mep_tkt_row">
<div class="mep_ticket_body_col_12">
    <div class="mep_event_ticket_terms">
<?php if($wbtm->settings->wbtm_ticket_tc_title){ echo "<h3>".$wbtm->settings->wbtm_ticket_tc_title."</h3>"; }
if($wbtm->settings->wbtm_ticket_tc){ ?><span style="text-align:left;"><?php echo $wbtm->settings->wbtm_ticket_tc; ?></span> <?php } ?>       
    </div>
</div>
</div>         
</div>
</div>

<div class="page_break"></div>
</body>
</html>
<?php
}
wp_reset_postdata();
?>



<style>
    .mep_event_location_information h3{
        margin:20px 0;
    }        
    .mep_event_location_information .extra_serivce_order_id{
        display:block;
        background:#ddd;
        text-align:center;
        padding:5px;
    }    
    .mep_event_location_information table{
        width:100%;
    }    
    .mep_event_location_information table tr td, .mep_event_location_information table tr th{
        padding:5px;
        border:1px solid #ddd;
    }
    .mep_event_location_information table tr th{
        background:#ddd;
        border-color:#fff;
    }
</style>

<?php 
  foreach ( $order->get_items() as $item_id => $item_values ) {
    $item_id                    = $item_id;
  }
   $_event_extra_service   = wc_get_order_item_meta($item_id,'_event_extra_service',true);
    if(is_array($_event_extra_service) && sizeof($_event_extra_service) > 0){
?>

<body>
<html>
<div class='pdf-ticket-body' style='height:950px;overflow: hidden'>
<div class="mep_ticket_body" <?php if($ticket_bg_url){ ?>style="background: url(<?php echo $ticket_bg_url; ?>);" <?php } ?>>
<div class="pdf-header">
<?php if( ! empty( $logo_url ) ) printf( '<img src="%s"/>', $logo_url ); ?>
<p><?php echo $wbtm->settings->wbtm_address; ?> </p>
<p><?php echo $wbtm->settings->wbtm_phone; ?> </p>
</div>
<div class="mep_tkt_row">
<div class="mep_ticket_body_col_12">
    <div class="mep_event_location_information">
        <h3><?php _e('Extra Service:','mage-eventpress'); ?></h3>
        <span class='extra_serivce_order_id'><?php _e('Order ID: ','mage-eventpress'); echo $order_id; ?></span>
        <table>
            <thead>
                <tr>
                    <th><?php _e('Service Name','mage-eventpress'); ?></th>
                    <th><?php _e('Quantity','mage-eventpress'); ?></th>
                    <th><?php _e('Unit Price','mage-eventpress'); ?></th>
                    <th><?php _e('Total Price','mage-eventpress'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($_event_extra_service as $extra_serive){
                    ?>
                    <tr>
                        <td><?php echo $extra_serive['service_name']; ?></td>
                        <td><?php echo $extra_serive['service_qty']; ?></td>
                        <td><?php echo wc_price($extra_serive['service_price']); ?></td>
                        <td><?php echo wc_price($extra_serive['service_qty'] * $extra_serive['service_price']); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</div> 
<div class="mep_tkt_row">
<div class="mep_ticket_body_col_12">
    <div class="mep_event_ticket_terms">
    <?php if($wbtm->settings->wbtm_ticket_tc_title){ echo "<h3>".$wbtm->settings->wbtm_ticket_tc_title."</h3>"; }
        if($wbtm->settings->wbtm_ticket_tc){ ?><span style="text-align:left;"><?php echo $wbtm->settings->wbtm_ticket_tc; ?></span> <?php } ?>       
    </div>
</div>
</div> 
</div>
</div>
</body>
</html>
<div class="page_break"></div>
<?php } ?>