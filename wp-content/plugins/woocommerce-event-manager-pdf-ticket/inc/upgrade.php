<?php
if ( ! defined( 'ABSPATH' ) ) { die; }
add_action('admin_init', 'mep_pdf_upgrade', 10);
function mep_pdf_upgrade()
{

    if (get_option('mep_pdf_old_settings_upgrade_013') != 'completed') {

        global $wbtm;

        $pdflibrary                     = 'mpdf';
        $wbtm_send_pdf                  = get_option('wbtm_send_pdf') ? get_option('wbtm_send_pdf') : 'yes';
        $mep_pdf_theme                  = get_option('mep_pdf_theme') ? get_option('mep_pdf_theme') : 'default';
        $mep_pdf_extra_service_theme    = get_option('mep_pdf_extra_service_theme') ? get_option('mep_pdf_extra_service_theme') : 'default';
        $wbtm_logo                      = get_option('wbtm_logo') ? wp_get_attachment_url(get_option('wbtm_logo')) : '';
        $wbtm_ticket_bg                 = get_option('wbtm_ticket_bg') ? wp_get_attachment_url(get_option('wbtm_ticket_bg')) : '';
        $wbtm_address                   = get_option('wbtm_address') ? get_option('wbtm_address') : '';
        $wbtm_phone                     = get_option('wbtm_phone') ? get_option('wbtm_phone') : '';
        $wbtm_ticket_tc_title           = get_option('wbtm_ticket_tc_title') ? get_option('wbtm_ticket_tc_title') : '';
        $wbtm_ticket_tc                 = get_option('wbtm_ticket_tc') ? get_option('wbtm_ticket_tc') : '';
        $wbtm_ticket_bg_color           = get_option('wbtm_ticket_bg_color') ? get_option('wbtm_ticket_bg_color') : '';
        $wbtm_ticket_text_color         = get_option('wbtm_ticket_text_color') ? get_option('wbtm_ticket_text_color') : '';
    
        $old_pdf_settings = array(
            'mep_pdf_lib'                   => $pdflibrary,
            'mep_pdf_theme'                 => $mep_pdf_theme,
            'mep_pdf_extra_service_theme'   => $mep_pdf_extra_service_theme,
            'mep_pdf_logo'                  => $wbtm_logo,
            'mep_pdf_bg'                    => $wbtm_ticket_bg,
            'mep_pdf_address'               => $wbtm_address,
            'mep_pdf_phone'                 => $wbtm_phone,
            'mep_pdf_tc_title'              => $wbtm_ticket_tc_title,
            'mep_pdf_tc_text'               => $wbtm_ticket_tc,
            'mep_pdf_bg_color'              => $wbtm_ticket_bg_color,
            'mep_pdf_text_color'            => $wbtm_ticket_text_color,
    
        );
        update_option( 'mep_pdf_gen_settings', $old_pdf_settings);
    
  
        $wbtm_email_status                      = get_option('wbtm_email_status') ? mep_convert_old_email_status(get_option('wbtm_email_status')) : 'processing';
        $wbtm_email_subject                     = get_option('wbtm_email_subject') ? get_option('wbtm_email_subject') : '';
        $wbtm_email_content                     = get_option('wbtm_email_content') ? get_option('wbtm_email_content') : '';
        $wbtm_admin_notification_email          = get_option('wbtm_admin_notification_email') ? get_option('wbtm_admin_notification_email') : '';
        $wbtm_email_from_name                   = get_option('wbtm_email_from_name') ? get_option('wbtm_email_from_name') : '';
        $wbtm_email_from                        = get_option('wbtm_email_from') ? get_option('wbtm_email_from') : '';
        $old_pdf_email_settings = array(
            'mep_pdf_send_status'               => $wbtm_send_pdf,
            'mep_pdf_email_status'              => $wbtm_email_status,
            'mep_pdf_email_subject'             => $wbtm_email_subject,
            'mep_pdf_email_content'             => $wbtm_email_content,
            'mep_pdf_admin_notification_email'  => $wbtm_admin_notification_email,
            'mep_pdf_email_from_name'           => $wbtm_email_from_name,
            'mep_pdf_email_from'                => $wbtm_email_from,
        );
        

        
        
        update_option( 'mep_pdf_email_settings', $old_pdf_email_settings);

        update_option('mep_pdf_old_settings_upgrade_013', 'completed');
    }
}