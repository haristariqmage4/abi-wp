<?php 
// Template Name: tow_ticket


$template_mode   = mep_get_option('mep_pdf_template_mood', 'mep_pdf_gen_settings', 'individual');
?>

<!-- internal css start -->
<style type="text/css">
	body{
		font-family: 'Poppins', sans-serif;
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
		background: #fdfdfd;
	}
	.bg-shape {
		position: absolute;
		right: 0;
		top: 0;
	}
	.bg-shape img {
		height: 250px;
	}
	.ticket_header {
		padding: 40px;
		border-bottom: 4px solid #0293C9;
		position: relative;
		background: #f3f3f3;
	}
	.ticket_header:before {
		position: absolute;
		left: 50%;
		right: 0;
		height: 10px;
		width: 250px;
		content: "";
		background: #0293C9;
		bottom: -7px;
		border-radius: 20px;
		transform: translateX(-50%);
	}
	.ticket_header ul li {
		font-size: 18px;
		margin-top: 6px;
		font-weight: 400;
	}
	.ticket_header img {
		height: 75px;
		width: auto;
		margin-left: -15px;
	}
	.ticket_content {
		padding: 40px;
	}
	.content_left {
		width: 50%;
		float: left;
		overflow: hidden;
		padding-right: 50px;
	}
	.content_left ul li {
		font-size: 18px;
		padding: 15px 0px;
		border-bottom: 1px solid #ddd;
	}
	.content_left ul li strong {
		color: #000;
		margin-right: 11px;
	}
	.content_left ul li img {
		width: 10rem;
		height: auto;
		border: 6px solid #ddd;
	}
	.content_right {
		overflow: hidden;
		display: inline-block;
	}
	.order_info {
		text-align: center;
		background: #0293C9;
	}
	.order_info strong {
		color: #fff !important;
	}
	.order_info p {
		color: #fff;
		font-size: 18px;
		font-weight: 600;
		padding: 15px;
	}
	.order_info td {
		color: #fff;
	}
	.content_right ul li {
		font-size: 18px;
		padding: 15px 0;
		border-bottom: 1px solid #ddd;
		padding-left: 0;
	}
	.content_right ul li strong {
		color: #000;
		margin-right: 11px;
	}
	.ticket_footer {
		margin:30px 0 20px 0;
		text-align: center;
	}
	.ticket_footer h3 {
		background: #0293C9;
		color: #fff;
		padding: 10px 25px;
		font-size: 20px;
		margin: 0 auto;
		border-radius: 50px;
		width: 500px;
		text-align: center;
	}
	.ticket_footer p {
		font-size: 16px;
		color: #000;
		padding-left: 40px;
		padding-right: 40px;
	}
	.ticket_footer p:last-child {
		padding: 0 20px;
		margin: 0;
	}
	
</style>
<!-- internal css end -->
	
</head>
<body>
	<div class="ticket_container">
		<div class="ticket_header">
			<ul>
				<li><?php do_action('mep_pdf_logo'); ?></li>
				<li><strong><?php do_action('mep_pdf_company_address'); ?></strong></li>
				<li><strong><?php do_action('mep_pdf_company_phone'); ?></strong></li>
			</ul>
		</div>
		<div class="ticket_content">
			<div class="content_left">
				<ul>
					<li><strong><?php _e('Event : ','mage-eventpress-pdf') ?></strong><span><?php do_action('mep_pdf_event_name',$ticket_id,$event_id,$order_id,$ticket_type); ?></span></li>
					<li><strong><?php _e('Organized by : ','mage-eventpress-pdf') ?></strong><span><?php do_action('mep_pdf_org_name',$ticket_id,$event_id,$order_id,$ticket_type); ?></span></li>
					<li><strong><?php _e('Start Date : ','mage-eventpress-pdf') ?></strong><span><?php do_action('mep_pdf_start_date',$ticket_id,$event_id,$order_id,$ticket_type); ?></span></li>
					<li><strong><?php _e('Start Time : ','mage-eventpress-pdf') ?></strong><span><?php do_action('mep_pdf_start_time',$ticket_id,$event_id,$order_id,$ticket_type);  ?></span></li>
					<li><strong><?php _e('Event location : ','mage-eventpress-pdf') ?></strong><span><?php do_action('mep_pdf_event_location',$ticket_id,$event_id,$order_id,$ticket_type);  ?></span></li>
					<li><?php do_action('mep_qr_code', get_the_permalink($ticket_id),$ticket_id); ?></li>
				</ul>
			</div>
			<div class="content_right">
				<ul>
					<li><strong><?php do_action('mep_pdf_attendee_info',$ticket_id,$event_id,$order_id,$ticket_type); ?></strong></li>
				</ul>
			</div>
		</div>
		<div class="order_info">
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
		<div class="ticket_footer">
			<h3><?php  do_action('mep_pdf_event_ticket_term_title');?></h3>
			<p><?php do_action('mep_pdf_event_ticket_term_text');  ?></p>
		</div>
	</div>
</body>