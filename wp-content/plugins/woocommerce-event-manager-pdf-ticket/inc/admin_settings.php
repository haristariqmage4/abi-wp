<?php
if ( ! defined( 'ABSPATH' ) ) { die; }
add_filter('mep_settings_sec_reg', 'mep_pro_pdf_settings_sec', 10);
function mep_pro_pdf_settings_sec($default_sec)
{
    $sections = array(
        array(
            'id' => 'mep_pdf_gen_settings',
            'title' => '<i class="fa fa-file-pdf"></i>'.__('PDF Settings', 'mage-eventpress-pdf')
        ),
        array(
            'id' => 'mep_pdf_email_settings',
            'title' => '<i class="fa fa-envelope-open"></i>'.__('PDF Email Settings', 'mage-eventpress-pdf')
        )
    );
    return array_merge($default_sec, $sections);
}

add_filter('mep_settings_sec_fields', 'mep_pro_pdf_settings_fields', 10);
function mep_pro_pdf_settings_fields($default_fields)
{
    $settings_fields = array(
        'mep_pdf_gen_settings' => array(
            array(
                'name' => 'mep_pdf_lib',
                'label' => __('PDF Library', 'mage-eventpress-pdf'),
                'desc' => __('Please select which PDF library you want to use. By default, Dompdf is being used, but if you face any font issue in the pdf ticket, we recommend you use the mPDF library. If you select mPDF, an Additional plugin needs to be installed and activated, which will appear in the admin notification panel. If the Mage PDF Support plugin is not installed on your system and if the download notification has not appeared, Please Download this <a href="https://github.com/magepeopleteam/magepeople-pdf-support/archive/master.zip" target="_blank">Mage PDF Support Plugin</a> and install it on your website.', 'mage-eventpress-pdf'),
                'type' => 'select',
                'default' => 'mpdf',
                'options' =>  array(                    
                    'mpdf'   => __('mPDF'),
                )
            ),
            array(
                'name' => 'mep_pdf_template_mood',
                'label' => __('PDF Ticket Style', 'mage-eventpress-pdf'),
                'desc' => __('Please select how you want to generate the PDF ticket. If you want to generate all the ticket info into a single ticket, select the single ticket, or select the individual ticket.', 'mage-eventpress-pdf'),
                'type' => 'select',
                'default' => 'mpdf',
                'options' =>  array(
                    'individual' => __('Individual Ticket','mage-eventpress-pdf'),
                    'single'   => __('Single Ticket','mage-eventpress-pdf'),
                )
            ),
            array(
                'name' => 'mep_pdf_theme',
                'label' => __('PDF Theme', 'mage-eventpress-pdf'),
                'desc' => __('Choose the PDF theme.', 'mage-eventpress-pdf'),
                'type' => 'select',
                'default' => 'default.php',
                'options' =>  mep_event_pdf_theme_list()
            ),
            array(
                'name' => 'mep_pdf_extra_service_theme',
                'label' => __('PDF Extra Service Theme', 'mage-eventpress-pdf'),
                'desc' => __('Choose the PDF extra service theme.', 'mage-eventpress-pdf'),
                'type' => 'select',
                'default' => 'default.php',
                'options' =>  mep_event_pdf_extra_service_theme_list()
            ),
            array(
                'name' => 'mep_pdf_logo',
                'label' => __( 'Logo', 'mage-eventpress-pdf' ),
                'desc' => __( 'Add your custom logo what will appear on the PDF ticket', 'mage-eventpress-pdf' ),
                'type' => 'file',
                'default' => ''
            ),
            array(
                'name' => 'mep_pdf_bg',
                'label' => __( 'Ticket Background Image', 'mage-eventpress-pdf' ),
                'desc' => __( 'You can add a custom Background Image for ticket. The image width should be 680px', 'mage-eventpress-pdf' ),
                'type' => 'file',
                'default' => ''
            ),
            array(
                'name' => 'mep_pdf_bg_color',
                'label' => __( 'Background Color', 'mage-eventpress-pdf' ),
                'desc' => __( 'Choose the PDF ticket background color', 'mage-eventpress-pdf' ),
                'type' => 'color',
                'default' => ''
            ),
            array(
                'name' => 'mep_pdf_text_color',
                'label' => __( 'Text Color', 'mage-eventpress-pdf' ),
                'desc' => __( 'Choose PDF ticket text color', 'mage-eventpress-pdf' ),
                'type' => 'color',
                'default' => ''
            ),
            array(
                'name' => 'mep_pdf_address',
                'label' => __( 'Company address', 'mage-eventpress-pdf' ),
                'desc' => __( 'Add your company address', 'mage-eventpress-pdf' ),
                'type' => 'textarea'
            ),            
            array(
                'name' => 'mep_pdf_phone',
                'label' => __( 'Phone Number', 'mage-eventpress-pdf' ),
                'desc' => __( 'Add company phone number here', 'mage-eventpress-pdf' ),
                'type' => 'text',
                'default' => ''
            ),
            array(
                'name' => 'mep_pdf_tc_title',
                'label' => __( 'Terms and Conditions Title', 'mage-eventpress-pdf' ),
                'desc' => __( 'This terms and conditions title will display in the PDF ticket footer', 'mage-eventpress-pdf' ),
                'type' => 'text',
                'default' => ''
            ),
            array(
                'name' => 'mep_pdf_tc_text',
                'label' => __( 'Terms and Conditions Text', 'mage-eventpress-pdf' ),
                'desc' => __( 'This terms and conditions text will display in the ticket footer', 'mage-eventpress-pdf' ),
                'type' => 'wysiwyg',
                'default' => ''
            ),


            array(
                'name' => 'mep_pdf_billing_first_name',
                'label' => __( 'Billing name', 'mage-eventpress-pdf' ),
                'desc' => __( 'Display billing name in the PDF ticket', 'mage-eventpress-pdf' ),                
                'type' => 'checkbox',
            ),  
    
            // array(
            //     'name' => 'mep_pdf_billing_last_name',
            //     'label' => __( 'Billing Last name', 'mage-eventpress-pdf' ),
            //     'desc' => __( 'If you want to display it in the PDF Ticket, Please tick this field', 'mage-eventpress-pdf' ),                                
            //     'type' => 'checkbox',
            // ), 
    
            array(
                'name' => 'mep_pdf_billing_email',
                'label' => __( 'Billing Email', 'mage-eventpress-pdf' ),
                'desc' => __( 'Display billing email in the PDF ticket', 'mage-eventpress-pdf' ),                                
                'type' => 'checkbox',
            ),  
    
            array(
                'name' => 'mep_pdf_billing_phone',
                'label' => __( 'Billing Phone', 'mage-eventpress-pdf' ),
                'desc' => __( 'Display billing phone in the PDF ticket', 'mage-eventpress-pdf' ),                                
                'type' => 'checkbox',
            ),
    
            array(
                'name' => 'mep_pdf_billing_company_name',
                'label' => __( 'Billing Company name', 'mage-eventpress-pdf' ),
                'desc' => __( 'Display billing company name in the PDF ticket', 'mage-eventpress-pdf' ),                                
                'type' => 'checkbox',
            ),  
    
            array(
                'name' => 'mep_pdf_billing_address_1',
                'label' => __( 'Billing Address', 'mage-eventpress-pdf' ),
                'desc' => __( 'Display billing address in the PDF ticket', 'mage-eventpress-pdf' ),                                
                'type' => 'checkbox',
            ),  
    
            // array(
            //     'name' => 'mep_pdf_billing_address_2',
            //     'label' => __( 'Billing Address Line 2', 'mage-eventpress-pdf' ),
            //     'desc' => __( 'If you want to display it in the PDF Ticket, Please tick this field', 'mage-eventpress-pdf' ),                                
            //     'type' => 'checkbox',
            // ),  
    
            array(
                'name' => 'mep_pdf_billing_city',
                'label' => __( 'Billing City', 'mage-eventpress-pdf' ),
                'desc' => __( 'Display billing city in the PDF ticket', 'mage-eventpress-pdf' ),                                
                'type' => 'checkbox',
            ),  
    
            array(
                'name' => 'mep_pdf_billing_state',
                'label' => __( 'Billing State', 'mage-eventpress-pdf' ),
                'desc' => __( 'Display billing state in the PDF ticket', 'mage-eventpress-pdf' ),                                
                'type' => 'checkbox',
            ),  
    
            array(
                'name' => 'mep_pdf_billing_postcode',
                'label' => __( 'Billing Postcode', 'mage-eventpress-pdf' ),
                'desc' => __( 'Display billing postcode in the PDF ticket', 'mage-eventpress-pdf' ),                                
                'type' => 'checkbox',
            ),  
    
            array(
                'name' => 'mep_pdf_billing_country',
                'label' => __( 'Billing Country', 'mage-eventpress-pdf' ),
                'desc' => __( 'Display billing country in the PDF ticket', 'mage-eventpress-pdf' ),                                
                'type' => 'checkbox',
            ),  
            array(
                'name' => 'mep_pdf_billing_method',
                'label' => __( 'Billing Payment Method', 'mage-eventpress-pdf' ),
                'desc' => __( 'Display billing payment method in the PDF ticket', 'mage-eventpress-pdf' ),                                
                'type' => 'checkbox',
            ),
        ),
        'mep_pdf_email_settings' => array(
            array(
                'name' => 'mep_pdf_send_status',
                'label' => __('Send Ticket', 'mage-eventpress-pdf'),
                'desc' => __('Please select which order status data you want to export', 'mage-eventpress-pdf'),
                'type' => 'select',
                'default' => 'yes',
                'options' =>  array(
                    'yes' => 'Yes',
                    'no' => 'No',
                )
            ),
            array(
                'name' => 'mep_pdf_email_status',
                'label' => __( 'Send Email on', 'mage-eventpress-pdf' ),
                'desc' => __( 'Send email with the ticket as attachment when these order status comes', 'mage-eventpress-pdf' ),
                'type' => 'multicheck',
                'options' => array(
                    'pending' => 'Pending',
                    'on-hold' => 'On Hold',
                    'processing' => 'Processing',
                    'completed' => 'Completed'
                )
            ),
			array(
                'name' => 'mep_pdf_add_to_calendar',
                'label' => __('Add to calender in email', 'mage-eventpress-pdf'),
                'desc' => __('Add to calender in email', 'mage-eventpress-pdf'),
                'type' => 'select',
                'default' => 'no',
                'options' =>  array(
                    'yes' => 'Yes',
                    'no' => 'No',
                )
            ),
            array(
                'name' => 'mep_pdf_email_subject',
                'label' => __( 'Email Subject', 'mage-eventpress-pdf' ),
                'desc' => __( 'Set email subject here', 'mage-eventpress-pdf' ),
                'type' => 'text',
                'default' => 'PDF Ticket Confirmation'
            ),
            array(
                'name' => 'mep_pdf_email_content',
                'label' => __( 'Email Content', 'mage-eventpress-pdf' ),
                'desc' => __( '<span style="color: red">Please use this shortcode for get real data.</span> <br><br> Customer Name: {customer_name} <br> Event Name: {event_name} <br>  Event Venue: {event_venue} <br>  Event Date: {event_date} <br> Order ID: {order_id} <br>', 'mage-eventpress-pdf' ),
                'type' => 'wysiwyg',
                'default' => 'Hello {customer_name}, <br><br> Thank you for registering. <br><br> Please download pdf ticket in this attachment. <br><br> Please carry out printing ticket on event spot. <br><br> Here is details of event: <br><br> Event Name: {event_name} <br><br> Event Date: {event_date} <br><br> Event Venue: {event_venue}'
            ),
            array(
                'name' => 'mep_pdf_admin_notification_email',
                'label' => __( 'Admin Notification Email', 'mage-eventpress-pdf' ),
                'desc' => __( 'Please enter an email address if admin want to get a pdf ticket after an order placed.', 'mage-eventpress-pdf' ),
                'type' => 'text',
                'default' => get_option('admin_email')
            ),
            array(
                'name' => 'mep_pdf_email_from_name',
                'label' => __( 'Email From Name', 'mage-eventpress-pdf' ),
                'desc' => __( 'Please Enter the Email From Name Here. Keep this fields empty will be cause for email went into SPAM', 'mage-eventpress-pdf' ),
                'type' => 'text',
                'default' => function_exists('mep_get_user_display_name_by_email') ? mep_get_user_display_name_by_email(get_option('admin_email')) : ''
            ),
            array(
                'name' => 'mep_pdf_email_from',
                'label' => __( 'Email From', 'mage-eventpress-pdf' ),
                'desc' => __( 'Please Enter the Email From Here. Keep this fields empty will be cause for email went into SPAM', 'mage-eventpress-pdf' ),
                'type' => 'text',
                'default' => get_option('admin_email')
            ),

        )
    );
    return array_merge($default_fields, $settings_fields);
}