<?php

if (!function_exists('mep_fb_email_template_cpt')) {
    function mep_fb_email_template_cpt() {
    
        $argsl = array(
            'public'   			=> true,
            'label'    			=> 'Email Template',
            'menu_icon'  		=> 'dashicons-id',
            'supports'  		=> array('title','editor'),
            'show_in_menu' 		=> 'edit.php?post_type=mep_events',
            'map_meta_cap' 		=> true, 
            'show_in_rest'      => true,
            'rest_base'         => 'mep_email_template'
    
        );
        register_post_type( 'mep_waitlist_email', $argsl );
    }
}
add_action( 'init', 'mep_fb_email_template_cpt' );
    
    
add_action( 'add_meta_boxes', 'mep_fb_event_meta_box_add' );
if (!function_exists('mep_fb_event_meta_box_add')) {
    function mep_fb_event_meta_box_add(){
        add_meta_box( 'mep-waitlist-email-help-text', __('<span class="dashicons dashicons-admin-generic" style="color:green; padding-right:10px;"></span>Email  Dynamic Tags List','mage-eventpress-waitlist'), 'mep_wl_email_help_text', 'mep_waitlist_email', 'normal', 'low' );
    }
}
    
if (!function_exists('mep_wl_email_help_text')) {
    function mep_wl_email_help_text($post){
        ?>
        <div class='sec'>
            <h6>Availabe Tags, You can use the below tags into email content.</h6>
            <ul>
                <li>{name}</li>
                <li>{email}</li>
                <li>{event}</li>
                <li>{event_date}</li>
            </ul>
        </div>
        <?php
    }
}