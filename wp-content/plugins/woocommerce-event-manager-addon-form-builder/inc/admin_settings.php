<?php 

add_filter('mep_settings_sec_reg','mep_pro_csv_settings_sec',10);
function mep_pro_csv_settings_sec($default_sec){
    $sections = array(
        array(
            'id' => 'mep_attendee_list_settings',
            'title' => '<i class="fas fa-clipboard-list"></i>'.__( 'Attendee List Column Settings', 'mep-form-builder' )
        ),
        array(
            'id' => 'csv_checkout_export_fileds_sec',
            'title' => '<i class="fa fa-list-alt"></i>'.__( 'CSV Settings', 'mep-form-builder' )
        )
    );
  return array_merge($default_sec,$sections);
}
add_filter('mep_settings_sec_fields','mep_pro_csv_settings_fields',10);
function mep_pro_csv_settings_fields($default_fields){
  $settings_fields = array(

    'csv_general_attendee_sec' => array(

        array(
            'name' => 'mep_csv_export_status',
            'label' => __( 'CSV Export Status?', 'mep-form-builder' ),
            'desc' => __( 'Please select which order status data you want to export', 'mep-form-builder' ),
            'type' => 'select',
            'default' => 'details_page',
            'options' =>  array(           
                'processing' => 'Processing',
                'completed' => 'Completed',
            )
        ),  

    ),            
    'csv_checkout_export_fileds_sec' => array(

        array(
            'name' => 'mep_billing_first_name',
            'label' => __( 'First name', 'mep-form-builder' ),
            'type' => 'checkbox',
        ),  

        array(
            'name' => 'mep_billing_last_name',
            'label' => __( 'Last name', 'mep-form-builder' ),
            'type' => 'checkbox',
        ), 

        array(
            'name' => 'mep_billing_email',
            'label' => __( 'Email', 'mep-form-builder' ),
            'type' => 'checkbox',
        ),  

        array(
            'name' => 'mep_billing_phone',
            'label' => __( 'Phone', 'mep-form-builder' ),
            'type' => 'checkbox',
        ),

        array(
            'name' => 'mep_billing_company_name',
            'label' => __( 'Company name', 'mep-form-builder' ),
            'type' => 'checkbox',
        ),  

        array(
            'name' => 'mep_billing_address_1',
            'label' => __( 'Address Line 1', 'mep-form-builder' ),
            'type' => 'checkbox',
        ),  

        array(
            'name' => 'mep_billing_address_2',
            'label' => __( 'Address Line 2', 'mep-form-builder' ),
            'type' => 'checkbox',
        ),  

        array(
            'name' => 'mep_billing_city',
            'label' => __( 'City', 'mep-form-builder' ),
            'type' => 'checkbox',
        ),  

        array(
            'name' => 'mep_billing_state',
            'label' => __( 'State', 'mep-form-builder' ),
            'type' => 'checkbox',
        ),  

        array(
            'name' => 'mep_billing_postcode',
            'label' => __( 'Postcode', 'mep-form-builder' ),
            'type' => 'checkbox',
        ),  

        array(
            'name' => 'mep_billing_country',
            'label' => __( 'Country', 'mep-form-builder' ),
            'type' => 'checkbox',
        ),  
        array(
            'name' => 'mep_billing_paid',
            'label' => __( 'Total Paid', 'mep-form-builder' ),
            'type' => 'checkbox',
        ), 
        array(
            'name' => 'mep_billing_method',
            'label' => __( 'Payment Method', 'mep-form-builder' ),
            'type' => 'checkbox',
        ), 

    ),
    'mep_attendee_list_settings' => array(

        array(
            'name' => 'mep_attendee_list_ticket_no',
            'label' => __( 'Ticket No', 'mep-form-builder' ),
            'type' => 'checkbox',
            'default' => 'on'
        ),  

        array(
            'name' => 'mep_attendee_list_name',
            'label' => __( 'Name', 'mep-form-builder' ),
            'type' => 'checkbox',
            'default' => 'on'
        ),  

        array(
            'name' => 'mep_attendee_list_email',
            'label' => __( 'Email', 'mep-form-builder' ),
            'type' => 'checkbox',
        ), 

        array(
            'name' => 'mep_attendee_list_phone',
            'label' => __( 'Phone', 'mep-form-builder' ),
            'type' => 'checkbox',
        ),  

        array(
            'name' => 'mep_attendee_list_tshize',
            'label' => __( 'T-Shirt Size', 'mep-form-builder' ),
            'type' => 'checkbox',
        ),  

        array(
            'name' => 'mep_attendee_list_desg',
            'label' => __( 'Designation', 'mep-form-builder' ),
            'type' => 'checkbox',
        ),  

        array(
            'name' => 'mep_attendee_list_website',
            'label' => __( 'Website', 'mep-form-builder' ),
            'type' => 'checkbox',
        ),  

        array(
            'name' => 'mep_attendee_list_company',
            'label' => __( 'Company', 'mep-form-builder' ),
            'type' => 'checkbox',
        ),  

        array(
            'name' => 'mep_attendee_list_gender',
            'label' => __( 'Gender', 'mep-form-builder' ),
            'type' => 'checkbox',
        ),  

        array(
            'name' => 'mep_attendee_list_ticket_type',
            'label' => __( 'Ticket Type', 'mep-form-builder' ),
            'type' => 'checkbox',
            'default' => 'on'
        ),

        array(
            'name' => 'mep_attendee_list_event',
            'label' => __( 'Event Name', 'mep-form-builder' ),
            'type' => 'checkbox',
            'default' => 'on'
        ),

        array(
            'name' => 'mep_attendee_list_order_no',
            'label' => __( 'Order No', 'mep-form-builder' ),
            'type' => 'checkbox',
            'default' => 'on'
        ), 
        array(
            'name' => 'mep_attendee_event_datetime',
            'label' => __( 'Event Datetime', 'mep-form-builder' ),
            'type' => 'checkbox',
            'default' => 'on'
        ), 
        array(
            'name' => 'mep_attendee_list_billing_order_status',
            'label' => __( 'Order Status', 'mep-form-builder' ),
            'type' => 'checkbox',
        ), 
        array(
            'name' => 'mep_attendee_list_billing_paid',
            'label' => __( 'Total Paid', 'mep-form-builder' ),
            'type' => 'checkbox',
        ), 
        array(
            'name' => 'mep_attendee_list_billing_method',
            'label' => __( 'Payment Method', 'mep-form-builder' ),
            'type' => 'checkbox',
           

        ), 

    )
    );
    return array_merge($default_fields,$settings_fields);
}