<?php
add_action('mep_pdf_style','mep_pdf_style_css');
function mep_pdf_style_css(){
$pdflibrary  = 'mpdf';
$bgcolor  = mep_get_option('mep_pdf_bg_color', 'mep_pdf_gen_settings', '');
$textcolor  = mep_get_option('mep_pdf_text_color', 'mep_pdf_gen_settings', '');
?>    
html, body{ height:0;}
@page {  size: 21cm 29.7cm;   /*A4*/ margin: 1px; /*webkit says no*/ }
html{  margin: 0;padding: 0;position: relative;width: 100%;}
body {margin: 0;padding: 0;width: 100%;}
.pdf-ticket-body{height:950px;width:100%;}
.mep_ticket_body {<?php  if($bgcolor) { ?>background:<?php echo $bgcolor; ?>;<?php }else{ ?>background: #fbfbfb;<?php } ?>padding: 0 10px;<?php if($textcolor) { ?>
color:<?php echo $textcolor; ?>;<?php }else{ ?>color:#000;<?php } ?>position: relative;background-repeat: no-repeat;background-size: 100%;padding:80px 10px;}
ul li, .mep_event_order_information, .mep_event_order_information p, table{<?php if($textcolor) { ?>color:<?php echo $textcolor; ?>;<?php }else{ ?>color:#000;<?php } ?>}
.mep_tkt_row {display: block;overflow: hidden;margin: 5px 0;}
.mep_ticket_body_col_6 {<?php if($pdflibrary == 'dompdf'){ ?>width: 49%;display:inline-block;<?php }else{ ?>width: 48%;float:left;<?php } ?>vertical-align: top;}
.mep_ticket_body ul {padding: 0;margin: 0;list-style: none;vertical-align: top;}
.mep_ticket_body ul li{margin: 10px 0;}
.mep_event_qr_code_section {text-align: center;}
.mep_event_qr_code_section img {width: 100px;}
.mep_ticket_body ul li p {padding: 0;margin: 0;display: inline-block;}
.mep_event_order_information ul li {text-align: center;float: left;}
.mep_event_order_information ul li p {display: block;}
.mep_event_order_information ul li {vertical-align: top;}
.mep_event_ticket_terms {text-align: center;font-size: 12px;border-top: 1px solid #ddd;margin-top: 20px;}
.mep_event_ticket_terms h3 {font-size: 15px;font-weight: bold;margin: 10px 0;padding: 0;}
.page_break{page-break-after: always;width: 612px; height: auto; overflow: hidden; font-family: Arial, Helvetica; position: relative; color: #545554;}
.mep_event_location_information h3{margin:20px 0 10px 0;}        
.mep_event_location_information .extra_serivce_order_id{display:block;background:#ddd;text-align:center;padding:5px;}    
.mep_event_location_information table{width:100%;}    
.mep_event_location_information table tr td, .mep_event_location_information table tr th{padding:5px;border:1px solid #ddd;}
.mep_event_location_information table tr th{background:#ddd;border-color:#fff;}
<?php
} 