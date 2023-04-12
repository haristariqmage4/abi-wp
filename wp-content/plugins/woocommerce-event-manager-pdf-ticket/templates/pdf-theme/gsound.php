<?php
// Template Name: G-Sound
?>
<style>
img {
    max-width: 100%;
    width: auto;
    height: auto;
    max-height: 100%;
}
.mep_tkt_row {
    display: block;
    overflow: hidden;
    margin: 10px 0;
}
.pdf_ticket_thumbnail {
    width: 280px;
    
    float: left;
    padding:0;
  
}
.pdf_ticket_content {
width: 286px;
    float: left;
    overflow: hidden;
    border: 2px solid #000;
    margin: 0 0px 0 20px;
}
.pdf_ticket_body h3 {
    padding: 0;
    margin: 0;
    background: #000;
    color: #fff;
    text-align: center;
    padding: 5px;
}
.pdf_attendee_name {
    text-align: center;
    border-bottom: 1px solid #000;
    margin: 0 10px;
}
.pdf_attendee_name ul{
    padding:0;
    margin:30px 0;
    list-style:none;
}
.pdf_event_details {
    text-align: center;
    font-size: 12px;
    border-bottom: 1px solid #000;
    margin: 0 10px;
}
.pdf_order_details {
    display: block;
    overflow: hidden;
    padding: 10px 0;
}
.order_content {
    width: 50%;
    float: left;
}
.order_qr_content {
    width: 50%;
    float: left;
}
.order_content ul {
    padding: 0;
    margin: 0;
    list-style: none;
}
.order_content ul li {
    font-size: 11px;
    padding-left: 10px;
    margin-bottom: 10px;
}
.order_content ul li p{
    padding:0;
    margin:0;
}
span.ticket_list_title {
    display: block;
    font-weight: bold;
    width: 100%;
    font-size: 12px;
}
.gsound-pdf-body {
    width: 595px;
    margin:0 auto;
}
.pdf_ticket_footer_content {
    text-align: left;
    font-size: 12px;
    border: 2px solid #000;
    padding: 10px;
}
.pdf_ticket_footer_content h3 {
    padding: 0;
    margin: 0;
}
.pdf-thumbnail-img{
    border: 2px solid #000;
    height:100%;
    width:100%;
}
</style>
<div class='gsound-pdf-body'>
    
    <div class="mep_tkt_row">
            <div class='pdf_ticket_thumbnail'>
                <?php 
                    $event_id = get_post_meta($ticket_id,'ea_event_id',true);
                    echo get_the_post_thumbnail( $event_id, 'full', array( 'class' => 'pdf-thumbnail-img' ) );
                ?>
            </div>
            <div class='pdf_ticket_content'>
                <div class='pdf_ticket_body'>
                    <h3><?php _e('Ticket','mage-eventpress-pdf'); ?></h3>
                    <div class='pdf_attendee_name'>
                        <h4><?php do_action('mep_event_pdf_attendee_name',$ticket_id); ?></h4>
                    </div>
                    <div class='pdf_event_details'>
                        <h4><?php do_action('mep_pdf_event_name',$ticket_id); ?></h4>
                        <p><?php do_action('mep_pdf_start_date',$ticket_id); ?> <?php do_action('mep_pdf_start_time',$ticket_id);  ?></p>
                        <p><?php do_action('mep_pdf_event_location',$ticket_id);  ?></p>
                    </div>
                    <div class='pdf_order_details'>
                        <div class='order_content'>
                            <ul>
                                <li><span class='ticket_list_title'><?php _e('Ticket Type:','mage-eventpress-pdf'); ?></span>
                                    <p><?php do_action('mep_pdf_event_ticket_type',$ticket_id); ?></p></li>
                                <li>
                                   <span class='ticket_list_title'><?php _e('Price:','mage-eventpress-pdf'); ?></span> 
                                    <p><?php do_action('mep_pdf_event_ticket_price',$ticket_id); ?></p></li>
                                <li>
                                    <span class='ticket_list_title'><?php _e('Ticket No:','mage-eventpress-pdf'); ?></span>
                                    <p><?php do_action('mep_pdf_event_ticket_no',$ticket_id); ?></p>
                                </li>                                
                                <li>
                                    <span class='ticket_list_title'><?php _e('Order ID:','mage-eventpress-pdf'); ?></span>
                                    <p><?php do_action('mep_pdf_event_order_id',$ticket_id); ?></p>
                                </li>
                            </ul>
                        </div>
                        <div class='order_qr_content'>
                            <?php do_action('mep_qr_code', get_the_permalink($ticket_id),$ticket_id); ?>
                        </div>
                    </div>
                </div>
            </div>            
    </div>    
    <div class="mep_tkt_row">
            <div class='pdf_ticket_footer_content'>
                <h3><?php  do_action('mep_pdf_event_ticket_term_title'); ?></h3>
                <p><?php do_action('mep_pdf_event_ticket_term_text'); ?></p>
            </div>              
    </div>    
    <div class="mep_tkt_row">
            <div class='pdf_ticket_footer_contents'>
                <?php do_action('mep_pdf_logo'); ?>
            </div>              
    </div>
</div>