<?php

add_action( 'add_meta_boxes', 'mep_meta_box_add' );
function mep_meta_box_add(){
    add_meta_box( 'my-meta-box-id', 'Information', 'mep_form_builder_meta_box_cb', 'mep_events_attendees', 'normal', 'high' );
}

function mep_remove_single_ticket(){
    ?>
    <style>
        div#mep-event-price {
            display: none;
        }        
    </style>
    <?php
}


function mep_form_builder_meta_box_cb($post){
$values = get_post_custom( $post->ID );

$event_meta           = get_post_custom($values['ea_event_id'][0]);
$ticket_type           = get_post_meta( $post->ID, 'ea_ticket_type', true );

    $mep_full_name         = strip_tags($event_meta['mep_full_name'][0]);
    $mep_reg_email         = strip_tags($event_meta['mep_reg_email'][0]);
    $mep_reg_phone         = strip_tags($event_meta['mep_reg_phone'][0]);
    $mep_reg_address       = strip_tags($event_meta['mep_reg_address'][0]);
    $mep_reg_designation   = strip_tags($event_meta['mep_reg_designation'][0]);
    $mep_reg_website       = strip_tags($event_meta['mep_reg_website'][0]);
    $mep_reg_veg           = strip_tags($event_meta['mep_reg_veg'][0]);
    $mep_reg_company       = strip_tags($event_meta['mep_reg_company'][0]);
    $mep_reg_gender        = strip_tags($event_meta['mep_reg_gender'][0]);
    $mep_reg_tshirtsize    = strip_tags($event_meta['mep_reg_tshirtsize'][0]);

    $order      = wc_get_order( $values['ea_order_id'][0] );

    foreach ( $order->get_items() as $item_id => $item_values ) {
        $item_id        = $item_id;
    }

$extra_info_arr = wc_get_order_item_meta($item_id,'_event_service_info',true) ? wc_get_order_item_meta($item_id,'_event_service_info',true) : [];






?>

<div class="mep-attendee-sec-details">
<div class='sec'>
    <span class="ea-label"><?php _e('Event:','mep-form-builder'); ?> </span>
    <span class="ea-value"><?php echo get_the_title($values['ea_event_id'][0]); ?> </span>
</div>

<div class='sec'>
    <span class="ea-label"><?php _e('UserID:','mep-form-builder'); ?> </span>
    <span class="ea-value"><?php echo $values['ea_user_id'][0]; ?></span>
</div>


<?php if($mep_full_name){ ?>
<div class='sec'>
    <span class="ea-label"><?php _e('Full Name:','mep-form-builder'); ?> </span>
    <span class="ea-value"><?php echo $values['ea_name'][0]; ?></span>
</div>
<?php } if($mep_reg_email){ ?>
<div class='sec'>
    <span class="ea-label"><?php _e('Email:','mep-form-builder'); ?> </span>
    <span class="ea-value"><?php echo $values['ea_email'][0]; ?></span>  
</div>
<?php } if($mep_reg_phone){ ?>
<div class='sec'>
    <span class="ea-label"><?php _e('Phone:','mep-form-builder'); ?> </span>
    <span class="ea-value"><?php echo $values['ea_phone'][0]; ?></span>  
</div>
<?php } if($mep_reg_address){ ?>
<div class='sec'>
    <span class="ea-label"><?php _e('Addres:','mep-form-builder'); ?> </span>
    <span class="ea-value">
    <?php echo $values['ea_address_1'][0]; ?>  
    </span>  
</div>
<?php } if($mep_reg_gender){ ?>
<div class='sec'>
    <span class="ea-label"><?php _e('Gender:','mep-form-builder'); ?> </span>
    <span class="ea-value">
    <?php echo $values['ea_gender'][0]; ?>  
    </span>  
</div>
<?php } if($mep_reg_company){ ?>
<div class='sec'>
    <span class="ea-label"><?php _e('Company:','mep-form-builder'); ?> </span>
    <span class="ea-value">
    <?php echo $values['ea_company'][0]; ?>  
    </span>  
</div>
<?php } if($mep_reg_designation){ ?>
<div class='sec'>
    <span class="ea-label"><?php _e('Designation:','mep-form-builder'); ?> </span>
    <span class="ea-value">
    <?php echo $values['ea_desg'][0]; ?>  
    </span>  
</div>
<?php } if($mep_reg_website){ ?>
<div class='sec'>
    <span class="ea-label"><?php _e('Website:','mep-form-builder'); ?> </span>
    <span class="ea-value">
    <?php echo $values['ea_website'][0]; ?>  
    </span>  
</div>

<?php } if($mep_reg_veg){ ?>
<div class='sec'>
    <span class="ea-label"><?php _e('Vegetarian?:','mep-form-builder'); ?> </span>
    <span class="ea-value">
    <?php echo $values['ea_vegetarian'][0]; ?>  
    </span>  
</div>

<?php } if($mep_reg_tshirtsize){ ?>
<div class='sec'>
    <span class="ea-label"><?php _e('T-Shirt Size?:','mep-form-builder'); ?> </span>
    <span class="ea-value">
    <?php echo $values['ea_tshirtsize'][0]; ?>  
    </span>  
</div>
<?php } ?>

<?php 
$mep_form_builder_data = get_post_meta($values['ea_event_id'][0], 'mep_form_builder_data', true);
  if ( $mep_form_builder_data ) {
    foreach ( $mep_form_builder_data as $_field ) {
        // if ( $mep_user_ticket_type[$iu] != '' ) :
        //   $user[$iu][$_field['mep_fbc_id']] = stripslashes( strip_tags( $_POST[$_field['mep_fbc_id']][$iu] ) );
        //   endif; 

?>
<div class='sec'>
    <span class="ea-label"><?php echo $_field['mep_fbc_label']; ?>:</span>
    <span class="ea-value">
    <?php $vname = "ea_".$_field['mep_fbc_id']; echo $values[$vname][0]; ?>  
    </span>  
</div>
<?php

    }
  }


foreach ($extra_info_arr as $_exs) {
if($_exs['option_qty']>0){
    $rs[] = $_exs['option_name']." (".$_exs['option_qty'].")";
    if($ticket_type != $_exs['option_name']){
    ?>
<div class='sec'>
    <span class="ea-label"><?php $_exs['option_name']; ?> </span>
    <span class="ea-value">
    <?php echo $_exs['option_qty']; ?>  
    </span>  
</div>
    <?php
}
}
}
?>




<div class='sec'>
    <span class="ea-label"><?php _e('Event Date:','mep-form-builder'); ?> </span>
    <span class="ea-value">
    <input type="hidden" value='<?php echo $values['ea_event_date'][0]; ?>'  name='mep_attendee_event_date'/>
    <?php echo get_mep_datetime($values['ea_event_date'][0],'date-time-text'); ?>
    </span>  
</div>



<div class='sec'>
    <span class="ea-label"><?php _e('Order ID:','mep-form-builder'); ?> </span>
    <span class="ea-value">
    <?php echo $values['ea_order_id'][0]; ?>  
    </span>  
</div>







</div>
<div class='sec'>
    <span>
    <a href="<?php echo get_site_url(); ?>/wp-admin/post.php?post=<?php echo $values['ea_order_id'][0]; ?>&action=edit" class='button button-primary button-large'><?php _e('View Order','mep-form-builder'); ?></a>
    </span>  
</div>
<?php
  wp_nonce_field( 'mep_events_attendee_data_nonce', 'mep_events_attendee_data_nonce' );
}








add_action('save_post', 'mep_events_attendee_data_save');
function mep_events_attendee_data_save($post_id) {  
  if ( ! isset( $_POST['mep_event_reg_nonce'] ) ||
  ! wp_verify_nonce( $_POST['mep_event_reg_nonce'], 'mep_event_reg_nonce' ) )
    return;
  
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
    return;
  
  if (!current_user_can('edit_post', $post_id))
    return;
  
if (get_post_type($post_id) == 'mep_events_reg_form') {

    $mep_full_name           = isset($_POST['mep_full_name']) ? strip_tags($_POST['mep_full_name']) : "";
    $mep_reg_email           = isset($_POST['mep_reg_email']) ? strip_tags($_POST['mep_reg_email']) : "";
    $mep_reg_phone           = isset($_POST['mep_reg_phone']) ? strip_tags($_POST['mep_reg_phone']) : "";
    $mep_reg_address         = isset($_POST['mep_reg_address']) ? strip_tags($_POST['mep_reg_address']) : "";
    $mep_reg_designation     = isset($_POST['mep_reg_designation']) ? strip_tags($_POST['mep_reg_designation']) : "";
    $mep_reg_website         = isset($_POST['mep_reg_website']) ? strip_tags($_POST['mep_reg_website']) : "";
    $mep_reg_veg             = isset($_POST['mep_reg_veg']) ? strip_tags($_POST['mep_reg_veg']) : "";
    $mep_reg_company         = isset($_POST['mep_reg_company']) ? strip_tags($_POST['mep_reg_company']) : "";
    $mep_reg_gender          = isset($_POST['mep_reg_gender']) ? strip_tags($_POST['mep_reg_gender']) : "";
    $mep_reg_tshirtsize      = isset($_POST['mep_reg_tshirtsize']) ? strip_tags($_POST['mep_reg_tshirtsize']) : "";
    $mep_reg_tshirtsize_list = isset($_POST['mep_reg_tshirtsize_list']) ? strip_tags($_POST['mep_reg_tshirtsize_list']) : "";
    // $mep_event_template      = isset($_POST['mep_event_template']) ? strip_tags($_POST['mep_event_template']) : "";

    update_post_meta($post_id, 'mep_full_name', $mep_full_name);
    update_post_meta($post_id, 'mep_reg_email', $mep_reg_email);
    update_post_meta($post_id, 'mep_reg_phone', $mep_reg_phone);
    update_post_meta($post_id, 'mep_reg_address', $mep_reg_address);
    update_post_meta($post_id, 'mep_reg_designation', $mep_reg_designation);
    update_post_meta($post_id, 'mep_reg_website', $mep_reg_website);

    update_post_meta($post_id, 'mep_reg_veg', $mep_reg_veg);
    update_post_meta($post_id, 'mep_reg_company', $mep_reg_company);
    update_post_meta($post_id, 'mep_reg_gender', $mep_reg_gender);
    update_post_meta($post_id, 'mep_reg_tshirtsize', $mep_reg_tshirtsize);
    update_post_meta($post_id, 'mep_reg_tshirtsize_list', $mep_reg_tshirtsize_list);
    // update_post_meta($post_id, 'mep_event_template', $mep_event_template);
    // update_post_meta($post_id, 'mep_org_address', $mep_org_address);
}

}


function mep_fb_remove_apostopie($string){
    $str = str_replace("'", '', $string);
    return $str;
  
  }
  


add_action('admin_init', 'mep_fb_meta_boxs',90);
function mep_fb_meta_boxs()
{
global $post;

    $attendee_id = isset($_REQUEST['post']) ? $_REQUEST['post'] : 0;

    $event_id = get_post_meta($attendee_id,'ea_event_id',true) ? get_post_meta($attendee_id,'ea_event_id',true) : 0;
    // $mep_full_name         = strip_tags($event_meta['mep_full_name'][0]);

    $mep_full_name         = get_post_meta($event_id,'mep_full_name',true) ? get_post_meta($event_id,'mep_full_name',true) : [];
    $mep_reg_email         = get_post_meta($event_id,'mep_reg_email',true);
    $mep_reg_phone         = get_post_meta($event_id,'mep_reg_phone',true);
    $mep_reg_address       = get_post_meta($event_id,'mep_reg_address',true);
    $mep_reg_designation   = get_post_meta($event_id,'mep_reg_designation',true);
    $mep_reg_website       = get_post_meta($event_id,'mep_reg_website',true);
    $mep_reg_veg           = get_post_meta($event_id,'mep_reg_veg',true);
    $mep_reg_company       = get_post_meta($event_id,'mep_reg_company',true);
    $mep_reg_gender        = get_post_meta($event_id,'mep_reg_gender',true);
    $mep_reg_tshirtsize    = get_post_meta($event_id,'mep_reg_tshirtsize',true);



    $edit_name = array(
            'id'		    => 'ea_name',
            'title'		    => __('Full Name:','mep-form-builder'),
            'details'	    => __('Attendee Full Name','mep-form-builder'),
            'type'		    => 'text',
            'default'		=> "",
            'placeholder'   => __('','mep-form-builder'),
        );
    

    // $edit_name = !empty($mep_full_name) ? array(
    //     'id'		    => 'ea_name',
    //     'title'		    => __('Full Name:','mep-form-builder'),
    //     'details'	    => __('Attendee Full Name','mep-form-builder'),
    //     'type'		    => 'text',
    //     'default'		=> "",
    //     'placeholder'   => __('Your Name','mep-form-builder'),
    // ) : '';





    // $edit_email = !empty($mep_reg_email) ? array(
    //     'id'		    => 'ea_email',
    //     'title'		    => __('Email:','mep-form-builder'),
    //     'details'	    => __('Attendee Email','mep-form-builder'),
    //     'type'		    => 'text',
    //     'default'		=> "",
    //     'placeholder'   => __('','mep-form-builder'),
    // ) : [];


    $edit_email =  array(
        'id'		    => 'ea_email',
        'title'		    => __('Email:','mep-form-builder'),
        'details'	    => __('Attendee Email','mep-form-builder'),
        'type'		    => 'text',
        'default'		=> "",
        'placeholder'   => __('','mep-form-builder'),
    );


    $edit_phone = array(
        'id'		    => 'ea_phone',
        'title'		    => __('Phone:','mep-form-builder'),
        'details'	    => __('Attendee Phone','mep-form-builder'),
        'type'		    => 'text',
        'default'		=> "",
        'placeholder'   => __('','mep-form-builder'),
    );

    // $edit_phone = !empty($mep_reg_phone) ? array(
    //     'id'		    => 'ea_phone',
    //     'title'		    => __('Phone:','mep-form-builder'),
    //     'details'	    => __('Attendee Phone','mep-form-builder'),
    //     'type'		    => 'text',
    //     'default'		=> "",
    //     'placeholder'   => __('','mep-form-builder'),
    // ) : [];

    $edit_address = array(
        'id'		    => 'ea_address_1',
        'title'		    => __('Address:','mep-form-builder'),
        'details'	    => __('Attendee Address','mep-form-builder'),
        'type'		    => 'textarea',
        'default'		=> "",
        'placeholder'   => __('','mep-form-builder'),
    );

    // $edit_address = !empty($mep_reg_address) ? array(
    //     'id'		    => 'ea_address_1',
    //     'title'		    => __('Address:','mep-form-builder'),
    //     'details'	    => __('Attendee Address','mep-form-builder'),
    //     'type'		    => 'textarea',
    //     'default'		=> "",
    //     'placeholder'   => __('','mep-form-builder'),
    // ) : [];

    $edit_desg = array(
        'id'		    => 'ea_desg',
        'title'		    => __('Designation:','mep-form-builder'),
        'details'	    => __('Attendee Designation','mep-form-builder'),
        'type'		    => 'text',
        'default'		=> "",
        'placeholder'   => __('','mep-form-builder'),
    );

    // $edit_desg = !empty($mep_reg_designation) ? array(
    //     'id'		    => 'ea_desg',
    //     'title'		    => __('Designation:','mep-form-builder'),
    //     'details'	    => __('Attendee Designation','mep-form-builder'),
    //     'type'		    => 'text',
    //     'default'		=> "",
    //     'placeholder'   => __('','mep-form-builder'),
    // ) : [];

    $edit_website = array(
        'id'		    => 'ea_website',
        'title'		    => __('Website:','mep-form-builder'),
        'details'	    => __('Attendee Website','mep-form-builder'),
        'type'		    => 'text',
        'default'		=> "",
        'placeholder'   => __('','mep-form-builder'),
    );

    // $edit_website = !empty($mep_reg_website) ? array(
    //     'id'		    => 'ea_website',
    //     'title'		    => __('Website:','mep-form-builder'),
    //     'details'	    => __('Attendee Website','mep-form-builder'),
    //     'type'		    => 'text',
    //     'default'		=> "",
    //     'placeholder'   => __('','mep-form-builder'),
    // ) : [];

    $edit_veg = array(
        'id'		    => 'ea_vegetarian',
        'title'		    => __('Vegetarian:','mep-form-builder'),
        'details'	    => __('Attendee Vegetarian Status','mep-form-builder'),
        'type'		    => 'text',
        'default'		=> "",
        'placeholder'   => __('','mep-form-builder'),
    );

    // $edit_veg = !empty($mep_reg_veg) ? array(
    //     'id'		    => 'ea_vegetarian',
    //     'title'		    => __('Vegetarian:','mep-form-builder'),
    //     'details'	    => __('Attendee Vegetarian Status','mep-form-builder'),
    //     'type'		    => 'text',
    //     'default'		=> "",
    //     'placeholder'   => __('','mep-form-builder'),
    // ) : [];

    $edit_company = array(
        'id'		    => 'ea_company',
        'title'		    => __('Company:','mep-form-builder'),
        'details'	    => __('Attendee Company','mep-form-builder'),
        'type'		    => 'text',
        'default'		=> "",
        'placeholder'   => __('','mep-form-builder'),
    );

    // $edit_company = !empty($mep_reg_company) ? array(
    //     'id'		    => 'ea_company',
    //     'title'		    => __('Company:','mep-form-builder'),
    //     'details'	    => __('Attendee Company','mep-form-builder'),
    //     'type'		    => 'text',
    //     'default'		=> "",
    //     'placeholder'   => __('','mep-form-builder'),
    // ) : [];

    $edit_gender = array(
        'id'		    => 'ea_gender',
        'title'		    => __('Gender:','mep-form-builder'),
        'details'	    => __('Attendee Gender','mep-form-builder'),
        'type'		    => 'text',
        'default'		=> "",
        'placeholder'   => __('','mep-form-builder'),
    );

    // $edit_gender = !empty($mep_reg_gender) ? array(
    //     'id'		    => 'ea_gender',
    //     'title'		    => __('Gender:','mep-form-builder'),
    //     'details'	    => __('Attendee Gender','mep-form-builder'),
    //     'type'		    => 'text',
    //     'default'		=> "",
    //     'placeholder'   => __('','mep-form-builder'),
    // ) : [];

    $edit_tshirt = array(
        'id'		    => 'ea_tshirtsize',
        'title'		    => __('T-Shirt Size:','mep-form-builder'),
        'details'	    => __('Attendee T-Shirt','mep-form-builder'),
        'type'		    => 'text',
        'default'		=> "",
        'placeholder'   => __('','mep-form-builder'),
    );

    // $edit_tshirt = !empty($$mep_reg_tshirtsize) ? array(
    //     'id'		    => 'ea_tshirtsize',
    //     'title'		    => __('T-Shirt Size:','mep-form-builder'),
    //     'details'	    => __('Attendee T-Shirt','mep-form-builder'),
    //     'type'		    => 'text',
    //     'default'		=> "",
    //     'placeholder'   => __('','mep-form-builder'),
    // ) : [];



   

    $events_speaker_list_meta_boxs = array(
        'page_nav'     => __('Event Additional Meta Boxes', 'mep-form-builder'),
        'priority' => 10,
        'sections' => array(
            'section_2' => array(
                'title'     =>     __('', 'mep-form-builder'),
                'description'     => __('', 'mep-form-builder'),
                'options'     => array(                    
                    $edit_name, 
                    $edit_email,
                    $edit_phone,
                    $edit_address,
                    $edit_desg,
                    $edit_website,
                    $edit_veg,
                    $edit_company,
                    $edit_gender,
                    $edit_tshirt,
                    array(
                        'id'            => 'ea_event_id',
                        'title'            => __('Event', 'mep-form-builder'),
                        'details'        => __('If you want to change Attendee Event, Please select a new event form here.', 'mep-form-builder'),
                        'multiple'        => false,
                        'limit'            => '3',
                        'type'            => 'select',
                        'args'            => 'CPT_%mep_events%',
                    ),
                    array(
                        'id'            => 'ea_ticket_type',
                        'title'            => __('Ticket Type', 'mep-form-builder'),
                        'details'        => __('After change the New Event and Save the ticket type list will show here', 'mep-form-builder'),
                        'multiple'        => false,
                        'limit'            => '3',
                        'type'            => 'select',
                        'args'            => mep_fb_get_event_ticket_type_list($event_id),
                    ),
                    // array(
                    //     'id'		    => 'ea_name',
                    //     'title'		    => __('Full Name:','mep-form-builder'),
                    //     'details'	    => __('','mep-form-builder'),
                    //     'type'		    => 'text',
                    //     'default'		=> "",
                    //     'placeholder'   => __("Full Name",'mep-form-builder'),
                    // ),     
    
                    array(
                        'id'		    => 'ea_event_date',
                        'title'		    => __('Event Date','mep-form-builder'),
                        'details'	    => __('Please Dont Forget to change the corret event datetime otherwise attendee will not show into the list','mep-form-builder'),
                        'type'		    => 'text',
                        'default'		=> "",
                        'placeholder'   => __('','mep-form-builder'),
                    ),     
                    
                    
                )
            ),

        ),
    );
    $events_speaker_list_meta_args = array(
        'meta_box_id'               => 'mep_event_attendee_info_edit_meta_boxes',
        'meta_box_title'            => __('Edit Attendee Information', 'mep-form-builder'),
        'screen'                    => array('mep_events_attendees'),
        'context'                   => 'normal',
        'priority'                  => 'high', 
        'callback_args'             => array(),
        'nav_position'              => 'none',
        'item_name'                 => "MagePeople",
        'item_version'              => "2.0",
        'panels'                     => array(
            'mep_event_attendee_info_edit_meta_boxe' => $events_speaker_list_meta_boxs
        )
    );

  
       new AddMetaBox($events_speaker_list_meta_args);
    
}




function mep_fb_get_event_ticket_type_list($event_id){
    $ticket_type = get_post_meta($event_id,'mep_event_ticket_type',true) ? get_post_meta($event_id,'mep_event_ticket_type',true) : [];
// print_r($ticket_type);
    $arr = [];
    foreach ($ticket_type as $_ticket_type) {
        $tt_name = mep_fb_remove_apostopie($_ticket_type['option_name_t']);
        $arr[$tt_name] = $tt_name;
        # code...
    }

return $arr; 
}