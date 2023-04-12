<?php 
// Template Name: Default Theme
$template_mode   = mep_get_option('mep_pdf_template_mood', 'mep_pdf_gen_settings', 'individual');
?>
<div class='pdf-ticket-body'>
<div class="mep_ticket_body" <?php  mep_pdf_body_style(); ?>>
<div class="pdf-header">
    <?php do_action('mep_pdf_logo'); ?>
    <p><?php do_action('mep_pdf_company_address'); ?> </p>
    <p><?php do_action('mep_pdf_company_phone'); ?> </p>
</div>

<div class="mep_tkt_row">
<div class="mep_ticket_body_col_6">
    <div class="mep_event_information">
        <ul>
            <li><strong><?php _e('Event:','mage-eventpress-pdf') ?></strong> <?php do_action('mep_pdf_event_name',$ticket_id,$event_id,$order_id,$ticket_type); ?></li>
            <li><strong><?php _e('Organized by:','mage-eventpress-pdf') ?></strong> <?php do_action('mep_pdf_org_name',$ticket_id,$event_id,$order_id,$ticket_type); ?></li>
            <li><strong><?php _e('Start Date:','mage-eventpress-pdf') ?></strong> <?php do_action('mep_pdf_start_date',$ticket_id,$event_id,$order_id,$ticket_type); ?></li>
            <li><strong><?php _e('Start Time:','mage-eventpress-pdf') ?></strong> <?php do_action('mep_pdf_start_time',$ticket_id,$event_id,$order_id,$ticket_type);  ?></li>
            <?php  do_action('mep_pdf_event_multidate',$ticket_id,$event_id,$order_id,$ticket_type); ?>
        </ul>
    </div>
</div>
<div class="mep_ticket_body_col_6">
    <div class="mep_atndee_information">
        <?php do_action('mep_pdf_attendee_info',$ticket_id,$event_id,$order_id,$ticket_type); ?>
    </div>
</div>
</div>   
<div class="mep_tkt_row">
<div class="mep_ticket_body_col_12">
    <div class="mep_event_location_information">
        <ul>
            <li><strong><?php _e('Event location:','mage-eventpress-pdf'); ?></strong> <?php do_action('mep_pdf_event_location',$ticket_id,$event_id,$order_id,$ticket_type);  ?></li>    
        </ul>
    </div>
</div>
</div> 
<div class="mep_tkt_row">
<div class="mep_ticket_body_col_12">
        <div class="mep_event_qr_code_section"> 
            <?php do_action('mep_qr_code', get_the_permalink($ticket_id),$ticket_id); ?>
        </div>
</div>
</div> 
<div class="mep_tkt_row">
<div class="mep_ticket_body_col_12">
    <div class="mep_event_order_information">
        <table style="width:100%">
            <tr>
                <td align="center"><p><strong><?php _e('Order ID:','mage-eventpress-pdf'); ?></strong></p> <?php do_action('mep_pdf_event_order_id',$ticket_id,$event_id,$order_id,$ticket_type); ?></td>
                <?php if($template_mode == 'individual'){ ?>
                    <td align="center"><p><strong><?php _e('Ticket number:','mage-eventpress-pdf'); ?></strong></p> <?php do_action('mep_pdf_event_ticket_no',$ticket_id,$event_id,$order_id,$ticket_type); ?></td>
                <?php } ?>
                <td align="center"><p><strong><?php _e('Ticket price:','mage-eventpress-pdf'); ?></strong></p>  <?php do_action('mep_pdf_event_ticket_price',$ticket_id,$event_id,$order_id,$ticket_type);  ?></td>
                <?php do_action('mep_pdf_body_after_price_event_info',$ticket_id,$event_id,$order_id,$ticket_type); ?>
                <td align="center"><p><strong><?php _e('Ticket type:','mage-eventpress-pdf') ?></strong></p> <?php do_action('mep_pdf_event_ticket_type',$ticket_id,$event_id,$order_id,$ticket_type); ?></td>
            </tr>
        </table>
    </div>
</div>
</div>  
<div class="mep_tkt_row">
<div class="mep_ticket_body_col_12">
<div class="mep_event_ticket_terms">
        <?php  do_action('mep_pdf_event_ticket_term_title');  do_action('mep_pdf_event_ticket_term_text');  ?>       
</div>
</div>
</div>   
</div>
</div>