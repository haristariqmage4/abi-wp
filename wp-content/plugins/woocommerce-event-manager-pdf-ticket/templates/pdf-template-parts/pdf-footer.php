<?php 
add_action('mep_pdf_event_ticket_term_title','mep_display_pdf_terms_title');
function mep_display_pdf_terms_title(){
    $term_title = mep_get_option('mep_pdf_tc_title', 'mep_pdf_gen_settings', '');
    if($term_title){ echo "<h3>".$term_title."</h3>"; }
}

add_action('mep_pdf_event_ticket_term_text','mep_display_pdf_terms_text');
function mep_display_pdf_terms_text(){
    $term_text = mep_get_option('mep_pdf_tc_text', 'mep_pdf_gen_settings', '');
    if($term_text){ ?><span style="text-align:left;"><?php echo $term_text; ?></span> <?php }
}