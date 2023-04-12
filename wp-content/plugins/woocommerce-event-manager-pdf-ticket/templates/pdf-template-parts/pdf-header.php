<?php 
function mep_pdf_body_style(){
    $ticket_bg_url  = mep_get_option('mep_pdf_bg', 'mep_pdf_gen_settings', '');    
    if(!empty($ticket_bg_url)){ ?>
    style="background: url(<?php echo $ticket_bg_url; ?>);" 
    <?php }
}

add_action('mep_pdf_logo','mep_display_pdf_logo');
function mep_display_pdf_logo(){
      $logo_url  = mep_get_option('mep_pdf_logo', 'mep_pdf_gen_settings', '');
      if( ! empty( $logo_url ) ){
            echo "<img src=$logo_url />";
      }
}

add_action('mep_pdf_company_address','mep_display_pdf_address');
function mep_display_pdf_address(){
    echo mep_get_option('mep_pdf_address', 'mep_pdf_gen_settings', '');
}

add_action('mep_pdf_company_phone','mep_display_pdf_phone');
function mep_display_pdf_phone(){
    echo mep_get_option('mep_pdf_phone', 'mep_pdf_gen_settings', '');
}