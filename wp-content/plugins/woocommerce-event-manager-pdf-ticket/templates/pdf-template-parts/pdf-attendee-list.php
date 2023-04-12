<?php
add_action('mep_pdf_attendee_info','mep_display_attendee_list',10,4);
function mep_display_attendee_list($ticket_id,$event_id='',$order_id='',$ticket_type=''){
$template_mode  = mep_get_option('mep_pdf_template_mood', 'mep_pdf_gen_settings', 'individual');
$values         = get_post_custom($ticket_id);

$name           = $template_mode == 'single' ? mep_pdf_get_single_names($order_id,$event_id,$ticket_type,'ea_name') : $values['ea_name'][0]; 
$phone          = $template_mode == 'single' ? mep_pdf_get_single_names($order_id,$event_id,$ticket_type,'ea_phone') : $values['ea_phone'][0]; 
$email          = $template_mode == 'single' ? mep_pdf_get_single_names($order_id,$event_id,$ticket_type,'ea_email') : $values['ea_email'][0]; 
$address        = $template_mode == 'single' ? mep_pdf_get_single_names($order_id,$event_id,$ticket_type,'ea_address_1') : $values['ea_address_1'][0]; 
$desg           = $template_mode == 'single' ? mep_pdf_get_single_names($order_id,$event_id,$ticket_type,'ea_desg') : $values['ea_desg'][0]; 
$company        = $template_mode == 'single' ? mep_pdf_get_single_names($order_id,$event_id,$ticket_type,'ea_company') : $values['ea_company'][0]; 
$website        = $template_mode == 'single' ? mep_pdf_get_single_names($order_id,$event_id,$ticket_type,'ea_website') : $values['ea_website'][0]; 
$gender         = $template_mode == 'single' ? mep_pdf_get_single_names($order_id,$event_id,$ticket_type,'ea_gender') : $values['ea_gender'][0]; 
$veg            = $template_mode == 'single' ? mep_pdf_get_single_names($order_id,$event_id,$ticket_type,'ea_vegetarian') : $values['ea_vegetarian'][0]; 
$tsize          = $template_mode == 'single' ? mep_pdf_get_single_names($order_id,$event_id,$ticket_type,'ea_tshirtsize') : $values['ea_tshirtsize'][0]; 

    ob_start();
    ?>
            <ul>
            <?php 
                if($template_mode == 'single'){
                    ?>
                        <li><strong><?php _e('No of Ticket:','mage-eventpress-pdf') ?></strong> <?php echo mep_pdf_get_single_names($order_id,$event_id,$ticket_type,'ea_name','count'); ?></li>
                    <?php
                }
            ?>
            <li><strong><?php echo mep_get_reg_label($event_id,'Name').':'; ?></strong> <?php echo $name; ?></li>
            <?php if($email){ ?>
            <li><strong><?php echo mep_get_reg_label($event_id,'Email').':'; ?></strong> <?php echo $email; ?></li> 
            <?php } if($phone){ ?>
            <li><strong><?php echo mep_get_reg_label($event_id,'Phone').':'; ?></strong> <?php echo $phone; ?> </li>
            <?php } if($address){ ?>
            <li><strong><?php echo mep_get_reg_label($event_id,'Address').':'; ?></strong> <?php echo $address; ?></li> 
            <?php } if($desg){ ?>
            <li><strong><?php echo mep_get_reg_label($event_id,'Designation').':'; ?></strong> <?php echo $desg; ?></li> 
            <?php } if($company){ ?>
            <li><strong><?php echo mep_get_reg_label($event_id,'Company').':'; ?></strong> <?php echo $company; ?> </li>
            <?php } if($website){ ?>
            <li><strong><?php echo mep_get_reg_label($event_id,'Website').':'; ?></strong> <?php echo $website; ?></li>
            <?php } if($gender){ ?>
            <li><strong><?php echo mep_get_reg_label($event_id,'Gender').':';  ?></strong> <?php echo $gender; ?></li>
            <?php } if($veg){ ?>
            <li><strong><?php echo mep_get_reg_label($event_id,'Vegetarian').':';  ?></strong> <?php echo $veg; ?></li>
            <?php } if($tsize){ ?>
            <li><strong><?php echo mep_get_reg_label($event_id,'T-Shirt Size').':';  ?></strong> <?php echo $tsize; ?></li>
            <?php } 


            $mep_form_builder_data = $template_mode == 'single' ? get_post_meta(mep_fb_get_reg_form_id($event_id), 'mep_form_builder_data', true) : get_post_meta(mep_fb_get_reg_form_id($values['ea_event_id'][0]), 'mep_form_builder_data', true);
             
            
            
            if ( $mep_form_builder_data ) {
                foreach ( $mep_form_builder_data as $_field ) {        
               $vname = "ea_".$_field['mep_fbc_id']; 
               $vals = $values[$vname][0];
            // if($vals){
            ?>
                <li><strong><?php echo $_field['mep_fbc_label']; ?>:</strong> <?php echo $template_mode == 'single' ? mep_pdf_get_single_names($order_id,$event_id,$ticket_type,$vname) : $vals; ?></li>   
            <?php
            // }
            }
        }
        do_action('mep_pdf_ticket_after_attendee_info',$ticket_id);
        ?>
        </ul>
    <?php
    echo ob_get_clean();
}

add_action('mep_event_pdf_attendee_name','mep_display_attendee_name');
function mep_display_attendee_name($ticket_id){
    
    $values             = get_post_custom($ticket_id);
    echo $template_mode == 'single' ? mep_pdf_get_single_names($order_id,$event_id,$ticket_type,'ea_name') : $values['ea_name'][0];
}