<?php 
// Template Name: Invoice Style

$template_mode   = mep_get_option('mep_pdf_template_mood', 'mep_pdf_gen_settings', 'individual');


$values             = get_post_custom($ticket_id);

$name 		= $template_mode == 'single' ? mep_pdf_get_single_names($order_id,$event_id,$ticket_type,'ea_name') : $values['ea_name'][0]; 
$phone 		= $template_mode == 'single' ? mep_pdf_get_single_names($order_id,$event_id,$ticket_type,'ea_phone') : $values['ea_phone'][0]; 
$email 		= $template_mode == 'single' ? mep_pdf_get_single_names($order_id,$event_id,$ticket_type,'ea_email') : $values['ea_email'][0]; 
$address 	= $template_mode == 'single' ? mep_pdf_get_single_names($order_id,$event_id,$ticket_type,'ea_address_1') : $values['ea_address_1'][0]; 
$desg 		= $template_mode == 'single' ? mep_pdf_get_single_names($order_id,$event_id,$ticket_type,'ea_desg') : $values['ea_desg'][0]; 
$company 	= $template_mode == 'single' ? mep_pdf_get_single_names($order_id,$event_id,$ticket_type,'ea_company') : $values['ea_company'][0]; 
$website 	= $template_mode == 'single' ? mep_pdf_get_single_names($order_id,$event_id,$ticket_type,'ea_website') : $values['ea_website'][0]; 
$gender 	= $template_mode == 'single' ? mep_pdf_get_single_names($order_id,$event_id,$ticket_type,'ea_gender') : $values['ea_gender'][0]; 
$veg 		= $template_mode == 'single' ? mep_pdf_get_single_names($order_id,$event_id,$ticket_type,'ea_vegetarian') : $values['ea_vegetarian'][0]; 
$tsize 		= $template_mode == 'single' ? mep_pdf_get_single_names($order_id,$event_id,$ticket_type,'ea_tshirtsize') : $values['ea_tshirtsize'][0]; 
$p_method	= get_post_meta($order_id,'_payment_method',true) ? get_post_meta($order_id,'_payment_method',true): '';
$invoice_label = !empty($p_method) && $p_method == 'bacs' ? 'Quote' : 'Invoice';

?>


	<div class="ticket_container">
		<div class="ticket_header">
			<div class="header_left">
				<li><?php do_action('mep_pdf_logo'); ?></li>
				<li><p><?php do_action('mep_pdf_company_address'); ?></p></li>
				<li><p><?php do_action('mep_pdf_company_phone'); ?></p></li>			
			</div>
			<div class="header_right">
				<li><h2><?php echo $invoice_label; ?></h2></li>
				<li><p><?php echo $ticket_id; //do_action('mep_pdf_event_ticket_no',$ticket_id,$event_id,$order_id,$ticket_type); ?></p></li>
			</div>
		</div>
		<div class="ticket_content">
			<div class="content_left">
				<ul>
					<li><p><?php _e('Bill To: ','mage-eventpress-pdf'); echo $name; ?></p></li>
					<li><p><?php _e('Billing Company : ','mage-eventpress-pdf'); echo get_post_meta($order_id,'_billing_company',true) ? get_post_meta($order_id,'_billing_company',true) : $company; ?></p></li>
					<!-- <li><p><?php // _e('Billing Name : ','mage-eventpress-pdf') ?></p></li> -->
					<li><p><?php _e('Billing E-mail : ','mage-eventpress-pdf'); echo $email; ?></p></li>
					<li><p><?php _e('Billing Phone : ','mage-eventpress-pdf'); echo $phone; ?></p></li>
					<li><p><?php _e('Billing Address : ','mage-eventpress-pdf'); echo get_post_meta($order_id,'_billing_address_1',true) ? get_post_meta($order_id,'_billing_address_1',true).' '.get_post_meta($order_id,'_billing_address_2',true) : $address; ?></p></li>
					<li><p><?php _e('Billing VAT No. : ','mage-eventpress-pdf'); echo get_post_meta($order_id,'billing_vat',true) ? get_post_meta($order_id,'billing_vat',true) : 'N/A'; ?></p></li>
					<li><p><?php _e('Order ID : ','mage-eventpress-pdf') ?><?php do_action('mep_pdf_event_order_id',$ticket_id,$event_id,$order_id,$ticket_type); ?></p></li>
				</ul>
			</div>
			<div class="content_right">
				<ul>
					<li><p><?php echo $invoice_label.' Date'; ?></p><span><?php do_action('mep_pdf_start_date',$ticket_id,$event_id,$order_id,$ticket_type); ?> <?php do_action('mep_pdf_start_time',$ticket_id,$event_id,$order_id,$ticket_type);  ?></span></li>
				</ul>
			</div>
		</div>
		<div class="order_info">
			<table class="table_info">
				<tr>
				  <th><?php _e('No.','mage-eventpress-pdf'); ?></th>
				  <th><?php _e('Event','mage-eventpress-pdf'); ?></th>
				  <th><?php _e('Ticket','mage-eventpress-pdf'); ?></th>
				  <th><?php _e('Qty','mage-eventpress-pdf'); ?></th>
				  <th><?php _e('Rate','mage-eventpress-pdf'); ?></th>
				  <th><?php _e('Amount','mage-eventpress-pdf'); ?></th>
				</tr>
				<tr>
				  <td>1</td>
				  <td><?php do_action('mep_pdf_event_name',$ticket_id,$event_id,$order_id,$ticket_type); ?></td>
				  <td><?php do_action('mep_pdf_event_ticket_type',$ticket_id,$event_id,$order_id,$ticket_type); ?></td>
				  <td>1</td>
				  <td><?php do_action('mep_pdf_event_ticket_price',$ticket_id,$event_id,$order_id,$ticket_type);  ?></td>
				  <td><?php do_action('mep_pdf_event_ticket_price',$ticket_id,$event_id,$order_id,$ticket_type);  ?></td>
				</tr>
			
				<tr>
				  <td></td>
				  <td></td>
				  <td></td>
				  <td></td>
				  <td><?php _e('Total','mage-eventpress-pdf'); ?>(includes 15% VAT)</td>
				  <td><?php do_action('mep_pdf_event_ticket_price',$ticket_id,$event_id,$order_id,$ticket_type);  ?> <span style='font-size:8px'></span></td>
				</tr>
			
			  </table>
		</div>
		<div class="ticket_footer">
			<?php  do_action('mep_pdf_event_ticket_term_title');  do_action('mep_pdf_event_ticket_term_text');  ?>      
		</div>
	</div>


		<!-- internal css start -->
		<style type="text/css">
		body{
			font-family: 'Poppins', sans-serif;
		}
		p{
			margin: 0px;
			padding: 0px;
			font-size: 16px;	
		}
		span{
			margin: 0px;
			padding: 0px;
			font-size: 16px;	
		}
		ul {
			margin: 0px;
			padding: 0px;
		}
		li {
			list-style: none
		}
		.ticket_container {
			max-width: 1000px;
			margin: auto;
			position: relative;
			background: #fff;
			padding: 40px;
		}
		.ticket_header {
			padding: 30px 0px;
			width: 100%;
		}
		.header_left {
			width: 50%;
			float: left;
			margin-right: 40px;
		}
		.header_right {
			text-align: right;
		}
		.header_right h2 {
			margin: 0;
			color: #134A9E;
			font-weight: 500;
			font-size: 35px;
		}
		.header_right li p {
			font-size: 20px;
			font-weight: 600;
		}
		.header_left li img {
			width: 70%;
			height: auto;
			margin-bottom: 10px;
		}
		.header_left li p {
			font-size: 15px;
		}
		.ticket_content {
			margin-top: 40px;
			display: block;
			overflow: hidden;
		}
		.content_left {
			width: 50%;
			margin-right: 40px;
			float: left;
		}
		.content_right {
			text-align: right;
			padding-top: 40px;
		}
		.content_left ul li p {
			font-size: 15px;
			padding-left: 0;
			display: inline-block;
			margin-right: 10px;
			font-weight: 600;
		}
		.order_info {
			margin-top: 40px;
		}
		.order_info table {
		border-collapse: collapse;
		width: 100%;
		}
		.order_info td {
		border: 1px solid #dddddd;
		text-align: center;
		padding: 8px;
		font-weight: 500;
		}
		.order_info th {
			background-color: #134A9E;
			color: #fff;
			text-align: center;
			padding: 8px;
		}
		.order_info tr:nth-child(odd) {
		background-color: #f7f3f3;
		}
		.content_right ul li p {
			font-size: 15px;
			padding-left: 0;
			margin-right: 10px;
			display: inline-block;
			font-weight: 500;
		}
		.ticket_footer {
			margin-bottom: 30px;
			margin-top: 30px;
		}
		.ticket_footer h5 {
			margin: 0;
		}
		.ticket_footer p {
			font-size: 15px;
			color: #000;
			display: inline-block;
		}
		.ticket_footer h5 {
			font-size: 16px;
			margin-bottom: 5px;
		}
		.bank_note {
			margin-bottom: 40px;
		}
		.bank_note p {
			width: 500px;
		}
		.bank_details p {
			margin-right: 10px;
			font-weight: 600;
			float: left;
		}
	</style>
	<!-- internal css end -->