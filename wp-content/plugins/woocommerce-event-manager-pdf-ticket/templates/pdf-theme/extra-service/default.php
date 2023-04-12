<?php
// Template Name: Default Extra Theme
?>
<div class='pdf-ticket-body' style='height:950px;overflow: hidden'>
<div class="mep_ticket_body" <?php mep_pdf_body_style(); ?>>
<div class="pdf-header">
    <?php do_action('mep_pdf_logo'); ?>
    <p><?php do_action('mep_pdf_company_address'); ?> </p>
    <p><?php do_action('mep_pdf_company_phone'); ?> </p>
</div>
<div class="mep_tkt_row">
<div class="mep_ticket_body_col_12">
    <div class="mep_event_location_information">
        <h3><?php _e('Extra Service:','mage-eventpress-pdf'); ?></h3>
        <span class='extra_serivce_order_id'><?php _e('Order ID: ','mage-eventpress-pdf'); echo $order_id; ?></span>
        <table>
            <thead>
                <tr>
                    <th><?php _e('Service Name','mage-eventpress-pdf'); ?></th>
                    <th><?php _e('Quantity','mage-eventpress-pdf'); ?></th>
                    <th><?php _e('Unit Price','mage-eventpress-pdf'); ?></th>
                    <th><?php _e('Total Price','mage-eventpress-pdf'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php  
                    $style = 'table';
                    do_action('mep_pdf_event_extra_serive_info',$event_extra_service,$style, $order_id);
                ?>
            </tbody>
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