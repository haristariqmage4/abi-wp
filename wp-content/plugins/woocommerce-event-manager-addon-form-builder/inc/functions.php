<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.


add_action( 'init', 'mep_fb_language_load');
function mep_fb_language_load() {
	$plugin_dir = basename( dirname( __DIR__ ) ) . "/languages/";
	load_plugin_textdomain( 'mep-form-builder', false, $plugin_dir );
}


add_action( 'admin_enqueue_scripts', 'mep_event_builder_admin_scripts' );
function mep_event_builder_admin_scripts() {
	// Select2
	wp_register_style( 'select2css', '//cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', false, '1.0', 'all' );
	wp_register_script( 'select2', '//cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array( 'jquery' ), null, true );
	wp_enqueue_style( 'select2css' );
	wp_enqueue_script( 'select2' );
	// Select2 END
	wp_enqueue_style( 'mep-event-report-style', plugin_dir_url( __DIR__ ) . 'css/admin-event_report_style.css', array() );
	wp_enqueue_style( 'mep-event-gallery-style', plugin_dir_url( __DIR__ ) . 'css/admin-event_gallery_style.css', array() );
	
	wp_enqueue_script( 'mep-event-report-scripts', plugin_dir_url( __DIR__ ) . 'js/admin-event-report.js', array( 'jquery' ), 1, true );
	wp_enqueue_script( 'mep-event-gallery-scripts', plugin_dir_url( __DIR__ ) . 'js/admin-event-gallery.js', array( 'jquery' ), 1, true );
}


// Enqueue Scripts for frontend
add_action( 'wp_enqueue_scripts', 'mep_event_builder_enqueue_scripts' );
function mep_event_builder_enqueue_scripts() {	
	wp_enqueue_style('mep-event-form-builder-style-front', plugin_dir_url( __DIR__ ) . 'css/front-mep-form-builder.css', array() );
	wp_enqueue_script('mep-event-form-builder-scripts-front', plugin_dir_url( __DIR__ ) . 'js/front-mep-form-builder.js', array( 'jquery' ), time(), true );
	wp_enqueue_script('form_builder_same_attendee', plugin_dir_url( __DIR__ ) . 'js/same_attendee_script.js', array( 'jquery' ), time(), true );
}


function mep_check_version() {
	$data = get_plugin_data( ABSPATH . "wp-content/plugins/mep-form-builder/woocommerce-event-press.php", false, false );

	return $data['Version'];
}


// Create MKB CPT
function mep_pro_cpt() {

	$argsl = array(
		'public'          => true,
		'label'           => __('Event Attendees','mep-form-builder'),
		'menu_icon'       => 'dashicons-id',
		'supports'        => array( 'title' ),
		// 'show_in_menu' => 'edit.php?post_type=mep_events',
		'show_in_menu'    => false,
		'capability_type' => 'post',
		'capabilities'    => array(
			'create_posts' => 'do_not_allow',
		),
		'map_meta_cap'    => true,
		'show_in_rest'    => true,
		'rest_base'       => 'mep_event_attendee'

	);
	register_post_type( 'mep_events_attendees', $argsl );

	$args = array(
		'public'          => true,
		'label'           => __('Global Reg Form','mep-form-builder'),		
		'supports'        => array( 'title' ),
		'show_in_menu'    => 'edit.php?post_type=mep_events',
        'capability_type' => 'post',
	);
	register_post_type( 'mep_events_reg_form', $args );

}

add_action( 'init', 'mep_pro_cpt' );


add_action( 'rest_api_init', 'mep_fb_event_attendee_cunstom_fields_to_rest_init' );
if ( ! function_exists( 'mep_fb_event_attendee_cunstom_fields_to_rest_init' ) ) {
	function mep_fb_event_attendee_cunstom_fields_to_rest_init() {
		register_rest_field( 'mep_events_attendees', 'attendee_informations', array(
			'get_callback' => 'mep_fb_get_event_attendee_custom_meta_for_api',
			'schema'       => null,
		) );
	}
}
if ( ! function_exists( 'mep_fb_get_event_attendee_custom_meta_for_api' ) ) {
	function mep_fb_get_event_attendee_custom_meta_for_api( $object ) {
		$post_id = $object['id'];
		$post_meta = get_post_meta( $post_id );
		return $post_meta;
	}
}


add_action( 'woocommerce_after_order_itemmeta', 'mep_show_attedee_list_in_order_details', 10, 3 );
function mep_show_attedee_list_in_order_details( $item_id, $item, $_product ) {
	?>
    <style type="text/css">
        .th__title {
            text-transform: capitalize;
            display: inline-block;
            min-width: 140px;
        }
        ul.attendee_list {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            width: 100%;
        }
        ul.attendee_list li {
            border-bottom: 1px dashed #ddd;
            padding: 5px 0 10px;
        }
        ul.attendee_list li h3 {
            padding: 0;
            margin: 0;
        }
        .attendee_sync_btn {
            border: 5px solid #9aa79a;
            background: #ddd;
            color: #373c5b;
            padding: 8px 20px;
            font-size: 16px;
            margin: 0 auto;
            width: 300px;
            display: block;
            cursor: pointer;
        }
        p.mep_warning {
            border: 5px solid red;
            color: #392c2c;
            text-align: center;
            padding: 20px;
            font-size: 17px;
            background: #fff1f1;
            width: 99%;
        }
    </style>
	<?php
	$user_info_arr = wc_get_order_item_meta( $item_id, '_event_user_info', true );
	if ( $user_info_arr ) {
		$event_id = wc_get_order_item_meta( $item_id, 'event_id', true );
		$counter  = 1;
		if ( ! empty( $user_info_arr ) ) {
			foreach ( $user_info_arr as $key => $value ) {

				$uname            = $value['user_name'];
				$email            = $value['user_email'];
				$phone            = $value['user_phone'];
				$event_id         = $value['user_event_id'];
				$user_ticket_type = $value['user_ticket_type'];
				// $check_before_create  = mep_fb_check_attendee_exist_before_create($_REQUEST['post'],$event_id,$uname,$email,$phone,$user_ticket_type);
				$check_before_create = 0;
				$unq_id              = uniqid();

				echo '<ul class="attendee_list">';
				echo "<li><h3>Attendee: $counter</h3></li>";
				foreach ( $value as $_key => $_value ) {
					if ( ! empty( $_value ) ) {
						echo '<li><span class="th__title">' . mep_string_beauty( $_key ) . ':</span> ' . $_value . '</li>';
					}
				}
				echo '</ul>';
				if ( $check_before_create == 0 ) {
					?>
                    <p id='before_attendee_table_info<?php echo $unq_id; ?>'><p class='mep_warning'> Please check your attendee list before hit this sync button, If this attendee already in the attendee table then it will be duplicate </p></p>
                    <input type='hidden' data-id='<?php echo $item_id; ?>' value='<?php echo $item_id; ?>' id='event_id_<?php echo $unq_id; ?>'/>
                    <input type='hidden' data-id='<?php echo $_REQUEST['post']; ?>' value='<?php echo $_REQUEST['post']; ?>' id='order_id_<?php echo $unq_id; ?>'/>

                    <button id='attendee_sync_<?php echo $unq_id; ?>' data-event='<?php echo $item_id; ?>' data-order='<?php echo $_REQUEST['post']; ?>' class='attendee_sync_btn attendee_sync_<?php echo $unq_id; ?>'><span class='dashicons dashicons-update-alt'></span> Sync Attendee Data</button>


                    <script>
                        (function ($) {
                            'use strict';
                            jQuery(document).ready(function ($) {

                                // $(document).on('click', '#attendee_sync_<?php echo $item_id; ?>', function() {
                                $("#attendee_sync_<?php echo $unq_id; ?>").on("click", function () {
                                    var event_id = jQuery(this).data('event');
                                    var order_id = jQuery(this).data('order');
                                    if (event_id > 0) {
                                        jQuery.ajax({
                                            type: 'POST',
                                            url: ajaxurl,
                                            data: {
                                                "action": "mep_fb_ajax_order_to_attendee_sync",
                                                "attendee_id": event_id,
                                                "order_id": order_id
                                            },
                                            beforeSend: function () {
                                                jQuery('#before_attendee_table_info<?php echo $unq_id; ?>').html('<h5 class="mep-processing"><?php _e( 'Please wait! Attendee data synchronizing from order data', 'mep-form-builder' ); ?></h5>');
                                            },
                                            success: function (data) {
                                                jQuery('.attendee_sync_<?php echo $unq_id; ?>').hide();
                                                jQuery('#before_attendee_table_info<?php echo $unq_id; ?>').html(data);
                                                // window.location.reload();
                                            }
                                        });
                                    }
                                    return false;
                                });


                            });
                        })(jQuery);
                    </script>
					<?php
				}
				$counter ++;
			}
		}
	}
}


function mep_fb_check_attendee_exist_before_create( $order_id, $event_id, $uname = '', $email = '', $phone = '', $ticket_type = '' ) {


	$name_filter = ! empty( $uname ) ? array(
		'key'     => 'ea_name',
		'value'   => $uname,
		'compare' => '='
	) : ' ';

	$email_filter = ! empty( $email ) ? array(
		'key'     => 'ea_phone',
		'value'   => $email,
		'compare' => '='
	) : ' ';

	$phone_filter = ! empty( $phone ) ? array(
		'key'     => 'ea_phone',
		'value'   => $phone,
		'compare' => '='
	) : ' ';


	$args = array(
		'post_type'      => 'mep_events_attendees',
		'posts_per_page' => - 1,
		'meta_query'     => array(
			'relation' => 'AND',
			array(
				'relation' => 'AND',
				array(
					'key'     => 'ea_event_id',
					'value'   => $event_id,
					'compare' => '='
				),
				array(
					'key'     => 'ea_order_id',
					'value'   => $order_id,
					'compare' => '='
				),
				array(
					'key'     => 'ea_ticket_type',
					'value'   => $ticket_type,
					'compare' => '='
				),
				$name_filter,
				$email_filter,
				$phone_filter
			),
			array(
				'relation' => 'OR',
				array(
					'key'     => 'ea_order_status',
					'value'   => 'processing',
					'compare' => '='
				),
				array(
					'key'     => 'ea_order_status',
					'value'   => 'completed',
					'compare' => '='
				)
			)
		)
	);
	$loop = new WP_Query( $args );

	return $loop->post_count;
}


add_action( 'wp_ajax_mep_fb_ajax_order_to_attendee_sync', 'mep_fb_ajax_order_to_attendee_sync' );
function mep_fb_ajax_order_to_attendee_sync() {
	$item_id               = $_REQUEST['attendee_id'];
	$order_id              = $_REQUEST['order_id'];
	$user_info_arr         = wc_get_order_item_meta( $item_id, '_event_user_info', true );
	$event_ticket_info_arr = wc_get_order_item_meta( $item_id, '_event_ticket_info', true );
	// $event_id              = wc_get_order_item_meta($item_id,'event_id',true);
	$order      = wc_get_order( $order_id );
	$order_meta = get_post_meta( $order_id );

	$c  = 1;
	$cn = 1;
	if ( is_array( $user_info_arr ) & sizeof( $user_info_arr ) > 0 ) {
		foreach ( $user_info_arr as $_user_info ) {

			$uname            = $_user_info['user_name'];
			$email            = $_user_info['user_email'];
			$phone            = $_user_info['user_phone'];
			$event_id         = $_user_info['user_event_id'];
			$user_ticket_type = $_user_info['user_ticket_type'];
			// $check_before_create  = mep_fb_check_attendee_exist_before_create($order_id,$event_id,$uname,$email,$phone,$user_ticket_type);
			$check_before_create = 0;


			if ( $check_before_create == 0 ) {
				mep_attendee_create( 'user_form', $order_id, $event_id, $_user_info );
				?>
                <h5 class="mep-processing"><?php _e( 'Attendee Data successfully synchronized from order data.', 'mep-form-builder' ); ?></h5>
				<?php
			} else {
				if ( $c == 1 ) {
					?>
                    <h5 class="mep-processing"><?php _e( 'Attendee Data already exists', 'mep-form-builder' ); ?></h5>
					<?php
				}
			}
			$c ++;
		}
	} else {
		foreach ( $event_ticket_info_arr as $tinfo ) {
			$first_name          = isset( $order_meta['_billing_first_name'][0] ) ? $order_meta['_billing_first_name'][0] : '';
			$last_name           = isset( $order_meta['_billing_last_name'][0] ) ? $order_meta['_billing_last_name'][0] : '';
			$uname               = $first_name . ' ' . $last_name;
			$email               = isset( $order_meta['_billing_email'][0] ) ? $order_meta['_billing_email'][0] : '';
			$phone               = isset( $order_meta['_billing_phone'][0] ) ? $order_meta['_billing_phone'][0] : '';
			$event_id            = $tinfo['event_id'];
			$check_before_create = mep_fb_check_attendee_exist_before_create( $order_id, $event_id, $uname, $email, $phone );

			for ( $x = 1; $x <= $tinfo['ticket_qty']; $x ++ ) {
				if ( $check_before_create == 0 ) {
					// mep_attendee_create('billing',$order_id,$event_id,$tinfo);
				} else {
					if ( $cn == 1 ) {
						?>
                        <h5 class="mep-processing"><?php _e( 'Attendee Data already exists', 'mep-form-builder' ); ?></h5>
						<?php
					}
				}
			}
			$cn ++;
		}
	}

	die();
}


function mep_string_beauty( $stg ) {
	$remove = array( '_', '-' );

	return str_replace( $remove, " ", $stg );
}


function mep_fb_get_reg_form_list($current_id){

	$args = array(
		'post_type' 		=> 'mep_events_reg_form',
		'posts_per_page' 	=> -1
	);
	$l = new WP_Query($args);
	$forms = $l->posts;

	// $mep_form_builder_data = get_post_meta( $post->ID, 'mep_form_builder_data', true );
?>
<select id='mep_event_reg_form_list' name='mep_event_reg_form_id'>
	<option value=''><?php _e('Please Select a Reg. Form','mep-form-builder'); ?></option>
	<option value='custom_form' <?php if('custom_form' == $current_id){ echo 'Selected'; } ?>><?php _e('Custom Form','mep-form-builder'); ?></option>
	<?php 
	foreach($forms as $_reg_form){
		?>
		<option value='<?php echo $_reg_form->ID; ?>' <?php if($_reg_form->ID == $current_id){ echo 'Selected'; } ?>><?php echo get_the_title($_reg_form->ID); ?></option>
		<?php
	}
	?>
</select>
<?php
}

add_action('mep_before_reg_form','mep_fb_select_reg_form');
function mep_fb_select_reg_form($post_id){

	if (get_post_type($post_id) == 'mep_events') {
		$mep_form_builder_data = get_post_meta( $post_id, 'mep_form_builder_data', true ) ? get_post_meta( $post_id, 'mep_form_builder_data', true ) : [];
		$current_form = get_post_meta($post_id,'mep_event_reg_form_id',true) ? get_post_meta($post_id,'mep_event_reg_form_id',true) : 'custom_form';
		?>
		<div class='mep_fb_global_reg_form_list'>
		<label for='mep_event_reg_form_list'>
			<ul>
				<li><?php _e('Please Select Registartion Form:','mep-form-builder'); ?></li>
				<li><?php mep_fb_get_reg_form_list($current_form); ?></li>
			</ul>
			</label>
		</div>
		<?php
	}

}



add_action( 'mp_event_all_in_tab_menu', 'mp_event_reg_form_menu' );
function mp_event_reg_form_menu() {
	?>
    <li data-target-tabs="#mp_event_reg_form_menu">
        <span class="dashicons dashicons-editor-table"></span>&nbsp;<?php _e( 'Attendee Form', 'mep-form-builder' ); ?>
    </li>
	<?php
}

add_action( 'mp_event_all_in_tab_item', 'mp_event_reg_form_item', 10, 1 );
function mp_event_reg_form_item( $post_id ) {
	?>
    <div class="mp_tab_item" data-tab-item="#mp_event_reg_form_menu">
		<?php mep_event_pro_reg_form_meta_box_cb( $post_id ); ?>
    </div>
	<?php
}


add_action( 'admin_init', 'mp_event_reg_form_term_item' );
function mp_event_reg_form_term_item() {

	/**
	 * This Will create Meta Boxes For Events Custom Post Type.
	 */
	$event_re_meta_boxs = array(
		'page_nav' => __( 'Event accept term and Condition Settings', 'mage-eventpress' ),
		'priority' => 10,
		'sections' => array(
			'section_2' => array(
				'title'       => __( '', 'mep-form-builder' ),
				'description' => __( '', 'mep-form-builder' ),
				'options'     => array(

					array(
						'id'      => 'mep_disable_term_condition',
						'title'   => __( 'Display Term & Condition?', 'mep-form-builder' ),
						'details' => __( 'If you want to display Term and Condition please check this Yes', 'mep-form-builder' ),
						'type'    => 'checkbox',
						'default' => '',
						'args'    => array(
							'yes' => __( 'Yes', 'mep-form-builder' )
						),
					),
                    array(
                            'id' => 'mep_term_condition',
                            'title' => __('Term & Condition Details', 'mep-form-builder'),
                            'details' => __('', 'mep-form-builder'),
                            'collapsible' => true,
                            'type' => 'repeatable',
                            'btn_text' => __('Add New Term & Condition','mep-form-builder'),
                            'title_field' => 'mep_term_condition_des',
                            'fields' => array(
                                    array(
                                    'type' => 'select',
                                    'default' => '',
                                    'item_id' => 'mep_term_condition_required',
                                    'name' => __('Terms & Conditions Required?','mep-form-builder'),
                                    'args'    => array(
                                        'yes' => __( 'Required?', 'mep-form-builder' ),
                                        'no' => __( 'Not Required?', 'mep-form-builder' )
                                    ),
                                ),
                                array(
                                    'type' => 'text',
                                    'default' => 'I am agree with the terms & conditions',
                                    'item_id' => 'mep_term_condition_des',
                                    'name' => __('Term & Conditions Label/Title','mep-form-builder'),
                                    'placeholder'   => __("Terms & Conditions",'mep-form-builder'),
                                ),
                                array(
                                    'type' => 'text',
                                    'default' => '',
                                    'item_id' => 'mep_term_condition_url',
                                    'name' => __('Terms & Conditions Description Url','mep-form-builder'),
                                    'placeholder'   => __("Url",'mep-form-builder'),
                                ),

                            ),

                        )
				)
			),

		),
	);
    $events_speaker_list_meta_args = array(
        'meta_box_id'               => 'mep_term_condition_settings_meta_boxes',
        'meta_box_title'            => '<span class="dashicons dashicons-editor-table"></span>&nbsp;&nbsp;'.__('Term & Condition', 'mage-eventpress'),
        'screen'                    => array('mep_events'),
        'context'                   => 'normal',
        'priority'                  => 'high',
        'callback_args'             => array(),
        'nav_position'              => 'none',
        'item_name'                 => "MagePeople",
        'item_version'              => "2.0",
        'panels'                     => array(
            'mep_term_condition_settings_meta_box' => $event_re_meta_boxs
        )
    );


	new AddMetaBox( $events_speaker_list_meta_args );


}

add_action('mep_add_term_condition','mep_add_term_condition_item',10,1);
function mep_add_term_condition_item($post_id){
    $check_condition=get_post_meta( $post_id, 'mep_disable_term_condition', true );
    if($check_condition && $check_condition=='yes'){
        $conditions=get_post_meta($post_id, 'mep_term_condition', true) ?  maybe_unserialize(get_post_meta($post_id, 'mep_term_condition', true)) : [];
        if(sizeof($conditions)>0){
            foreach ($conditions as $condition){
                $required=$condition['mep_term_condition_required']=='yes'?'required':'';
                ?>
                    <label class="term_condition_area">
                    <input type="checkbox" name="accept_term[]" <?php echo $required; ?> />
                    <a href="<?php echo $condition['mep_term_condition_url']; ?>"><?php echo $condition['mep_term_condition_des']; ?></a>
                    </label>
                <?php
            }
        }
    }
}

add_action( 'add_meta_boxes', 'mep_reg_form_meta_box_add' );
function mep_reg_form_meta_box_add(){
    add_meta_box( 'mep_reg_form_cb', __('Event Registration Form','mep-form-builder'), 'mep_event_pro_reg_form_meta_box_cb', 'mep_events_reg_form', 'normal', 'high' );
}



function mep_event_pro_reg_form_meta_box_cb( $post ) {

 $post_id = get_the_id();

	$values = get_post_custom( $post_id );
	
	if ( array_key_exists( 'mep_full_name', $values ) ) {
		$mep_full_name = ( $values['mep_full_name'][0] > 0 ) ? 'checked' : '';
	} else {
		$mep_full_name = '';
	}
	$mep_name_label = false;
	if ( array_key_exists( 'mep_name_label', $values ) ) {
		$mep_name_label = $values['mep_name_label'][0];
	}

	if ( array_key_exists( 'mep_reg_email', $values ) ) {
		$mep_reg_email = ( $values['mep_reg_email'][0] > 0 ) ? 'checked' : '';
	} else {
		$mep_reg_email = '';
	}

	$mep_email_label = false;
	if ( array_key_exists( 'mep_email_label', $values ) ) {
		$mep_email_label = $values['mep_email_label'][0];
	}

	if ( array_key_exists( 'mep_reg_phone', $values ) ) {
		$mep_reg_phone = ( $values['mep_reg_phone'][0] > 0 ) ? 'checked' : '';
	} else {
		$mep_reg_phone = '';
	}
	$mep_phone_label = false;
	if ( array_key_exists( 'mep_phone_label', $values ) ) {
		$mep_phone_label = $values['mep_phone_label'][0];
	}

	if ( array_key_exists( 'mep_reg_address', $values ) ) {
		$mep_reg_address = ( $values['mep_reg_address'][0] > 0 ) ? 'checked' : '';
	} else {
		$mep_reg_address = '';
	}
	$mep_address_label = false;
	if ( array_key_exists( 'mep_address_label', $values ) ) {
		$mep_address_label = $values['mep_address_label'][0];
	}

	if ( array_key_exists( 'mep_reg_tshirtsize', $values ) ) {
		$mep_reg_tshirtsize = ( $values['mep_reg_tshirtsize'][0] > 0 ) ? 'checked' : '';
	} else {
		$mep_reg_tshirtsize = '';
	}
	$mep_tshirt_label = false;
	if ( array_key_exists( 'mep_tshirt_label', $values ) ) {
		$mep_tshirt_label = $values['mep_tshirt_label'][0];
	}
	$mep_reg_tshirtsize_list = false;
	if ( array_key_exists( 'mep_reg_tshirtsize_list', $values ) ) {
		$mep_reg_tshirtsize_list = $values['mep_reg_tshirtsize_list'][0];
	}

	if ( array_key_exists( 'mep_reg_designation', $values ) ) {
		$mep_reg_designation = ( $values['mep_reg_designation'][0] > 0 ) ? 'checked' : '';
	} else {
		$mep_reg_designation = '';
	}
	$mep_desg_label = false;
	if ( array_key_exists( 'mep_desg_label', $values ) ) {
		$mep_desg_label = $values['mep_desg_label'][0];
	}

	if ( array_key_exists( 'mep_reg_website', $values ) ) {
		$mep_reg_website = ( $values['mep_reg_website'][0] > 0 ) ? 'checked' : '';
	} else {
		$mep_reg_website = '';
	}
	$mep_website_label = false;
	if ( array_key_exists( 'mep_website_label', $values ) ) {
		$mep_website_label = $values['mep_website_label'][0];
	}

	if ( array_key_exists( 'mep_reg_veg', $values ) ) {
		$mep_reg_veg = ( $values['mep_reg_veg'][0] > 0 ) ? 'checked' : '';
	} else {
		$mep_reg_veg = '';
	}
	$mep_veg_label = false;
	if ( array_key_exists( 'mep_veg_label', $values ) ) {
		$mep_veg_label = $values['mep_veg_label'][0];
	}

	if ( array_key_exists( 'mep_reg_company', $values ) ) {
		$mep_reg_company = ( $values['mep_reg_company'][0] > 0 ) ? 'checked' : '';
	} else {
		$mep_reg_company = '';
	}
	$mep_company_label = false;
	if ( array_key_exists( 'mep_company_label', $values ) ) {
		$mep_company_label = $values['mep_company_label'][0];
	}

	if ( array_key_exists( 'mep_reg_gender', $values ) ) {
		$mep_reg_gender = ( $values['mep_reg_gender'][0] > 0 ) ? 'checked' : '';
	} else {
		$mep_reg_gender = '';
	}
	$mep_gender_label = false;
	if ( array_key_exists( 'mep_gender_label', $values ) ) {
		$mep_gender_label = $values['mep_gender_label'][0];
	}

	wp_nonce_field('mep_event_reg_nonce', 'mep_event_reg_nonce');
     do_action('mep_before_reg_form',$post_id); ?>
	<div id='mp_event_all_info_in_tab' class='mp_tab_itemss'>

	

    <table class="mp_form_builder_table">
        <tr>
            <td colspan="6">
                <p class="event_meta_help_txt"><?php _e( 'Please Select fields below That you want to enable in attendee registration form.', 'mep-form-builder' ); ?></p>
            </td>
        </tr>
        <tr>
            <th>
                <label>
                    <input type="checkbox" name='mep_full_name' value='1' <?php echo $mep_full_name ?> />
                    <span><?php _e( 'Full Name', 'mep-form-builder' ); ?></span>
                </label>
            </th>
            <td colspan="2">
                <label>
                    <input type="text" name='mep_name_label' class="mp_formControl" value='<?php echo $mep_name_label; ?>' placeholder="<?php _e( 'Enter Label Text Here Default is: Enter Your Name', 'mep-form-builder' ); ?>"/>
                </label>
            </td>
            <th>
                <label>
                    <input type="checkbox" name='mep_reg_email' value='1' <?php echo $mep_reg_email ?> />
                    <span><?php _e( 'Email Address', 'mep-form-builder' ); ?></span>
                </label>
            </th>
            <td colspan="2">
                <label>
                    <input type="text" name='mep_email_label' class="mp_formControl" value='<?php echo $mep_email_label; ?>' placeholder="<?php _e( 'Enter Label Text Here Default is: Enter Your Email', 'mep-form-builder' ); ?>"/>
                </label>
            </td>
        </tr>
        <tr>
            <th>
                <label>
                    <input type="checkbox" name='mep_reg_phone' value='1' <?php echo $mep_reg_phone ?> />
                    <span><?php _e( 'Phone Number', 'mep-form-builder' ); ?></span>
                </label>
            </th>
            <td colspan="2">
                <label>
                    <input type="text" name='mep_phone_label' class="mp_formControl" value='<?php echo $mep_phone_label; ?>' placeholder="<?php _e( 'Enter Label Text Here Default is: Enter Your Phone', 'mep-form-builder' ); ?>"/>
                </label>
            </td>
            <th>
                <label>
                    <input type="checkbox" name='mep_reg_address' value='1' <?php echo $mep_reg_address ?> />
                    <span><?php _e( 'Address', 'mep-form-builder' ); ?></span>
                </label>
            </th>
            <td colspan="2">
                <label>
                    <input type="text" name='mep_address_label' class="mp_formControl" value='<?php echo $mep_address_label; ?>' placeholder="<?php _e( 'Enter Label Text Here Default is: Enter Your Address', 'mep-form-builder' ); ?>"/>
                </label>
            </td>
        </tr>
        <tr>
            <th>
                <label>
                    <input type="checkbox" name='mep_reg_designation' value='1' <?php echo $mep_reg_designation ?> />
                    <span><?php _e( 'Designation', 'mep-form-builder' ); ?></span>
                </label>
            </th>
            <td colspan="2">
                <label>
                    <input type="text" name='mep_desg_label' class="mp_formControl" value='<?php echo $mep_desg_label; ?>' placeholder="<?php _e( 'Enter Label Text Here Default is: Designation', 'mep-form-builder' ); ?>"/>
                </label>
            </td>
            <th>
                <label>
                    <input type="checkbox" name='mep_reg_website' value='1' <?php echo $mep_reg_website ?> />
                    <span><?php _e( 'Website', 'mep-form-builder' ); ?></span>
                </label>
            </th>
            <td colspan="2">
                <label>
                    <input type="text" name='mep_website_label' class="mp_formControl" value='<?php echo $mep_website_label; ?>' placeholder="<?php _e( 'Enter Label Text Here Default is: Enter Your Website', 'mep-form-builder' ); ?>"/>
                </label>
            </td>
        </tr>
        <tr>
            <th>
                <label>
                    <input type="checkbox" name='mep_reg_veg' value='1' <?php echo $mep_reg_veg ?> />
                    <span><?php _e( 'Vegetarian', 'mep-form-builder' ); ?></span>
                </label>
            </th>
            <td colspan="2">
                <label>
                    <input type="text" name='mep_veg_label' class="mp_formControl" value='<?php echo $mep_veg_label; ?>' placeholder="<?php _e( 'Enter Label Text Here Default is: Vegetarian?', 'mep-form-builder' ); ?>"/>
                </label>
            </td>
            <th>
                <label>
                    <input type="checkbox" name='mep_reg_company' value='1' <?php echo $mep_reg_company ?> />
                    <span><?php _e( 'Company Name', 'mep-form-builder' ); ?></span>
                </label>
            </th>
            <td colspan="2">
                <label>
                    <input type="text" name='mep_company_label' class="mp_formControl" value='<?php echo $mep_company_label; ?>' placeholder="<?php _e( 'Enter Label Text Here Default is: Enter Your Company', 'mep-form-builder' ); ?>"/>
                </label>
            </td>
        </tr>
        <tr>
            <th>
                <label>
                    <input type="checkbox" name='mep_reg_gender' value='1' <?php echo $mep_reg_gender ?> />
                    <span><?php _e( 'Gender', 'mep-form-builder' ); ?></span>
                </label>
            </th>
            <td colspan="2">
                <label>
                    <input type="text" name='mep_gender_label' class="mp_formControl" value='<?php echo $mep_gender_label; ?>' placeholder="<?php _e( 'Enter Label Text Here Default is: Gender', 'mep-form-builder' ); ?>"/>
                </label>
            </td>
            <th>
            </th>
            <td colspan="2">
            </td>
        </tr>
        <tr>
            <th colspan="2">
                <label>
                    <input type="checkbox" name='mep_reg_tshirtsize' value='1' <?php echo $mep_reg_tshirtsize ?> />
                    <span><?php _e( 'Selection Option(Ex:T-Shirt Size)', 'mep-form-builder' ); ?></span>
                </label>
            </th>
            <td colspan="2">
                <label>
                    <input type="text" name='mep_tshirt_label' class="mp_formControl" value='<?php echo $mep_tshirt_label; ?>' placeholder="<?php _e( 'Enter Label Text Here Default is: Select TShirt Size', 'mep-form-builder' ); ?>"/>
                </label>
            </td>
            <td colspan="2">
                <label>
                    <input type="text" name='mep_reg_tshirtsize_list' class="mp_formControl" value='<?php echo $mep_reg_tshirtsize_list; ?>' placeholder="<?php _e( 'Input Tshirts size, separetd by comma (M,L,XL)', 'mep-form-builder' ); ?>"/>
                </label>
            </td>
        </tr>
    </table>
	<?php
	do_action( 'mep_after_reg_form', $post_id );
	echo '</div>';
}


function remove_post_custom_fields() {
	global $post;
	$mep_form_builder_data = get_post_meta( $post->ID, 'mep_form_builder_data', true );
	wp_nonce_field( 'mep_event_form_builder_nonce', 'mep_event_form_builder_nonce' );
	?>
    <table class="mp_event_custom_form_table">
		<?php

// print_r($mep_form_builder_data);

		if ( $mep_form_builder_data) {
			foreach ( $mep_form_builder_data as $_field ) {
			    if(array_key_exists('mep_fbc_id',$_field)) {
					
				    mp_event_custom_form( $_field );
			    }
			}
		}

		?>
        <tr></tr>
    </table>
    <div class="mp_event_custom_form_hidden">
        <table>
            <?php mp_event_custom_form(); ?>
        </table>
    </div>
    <button id="mp_event_add_new_form" type="button"><span class="dashicons dashicons-plus-alt"></span><?php _e( 'Add New Field', 'mep-form-builder' ); ?></button>
	<?php
}

add_action( 'mep_after_reg_form', 'remove_post_custom_fields' );




function mp_event_custom_form($_field=array()){
    ?>
    <tr>
        <td colspan="2">
            <label>
                <input type="text" name="mep_fbc_label[]" class="mp_formControl" value="<?php echo ( !empty($_field['mep_fbc_label']) && $_field['mep_fbc_label'] != '' ) ? esc_attr( $_field['mep_fbc_label'] ) : ''; ?>" placeholder="<?php _e( 'Field Label', 'mep-form-builder' ); ?>" />
            </label>
        </td>
        <td colspan="2">
            <label>
			
                <input type="text" <?php if(!empty($_field['mep_fbc_id'])){ echo 'readonly'; } ?>  name="mep_fbc_filed_id[]" class="mp_formControl" value="<?php echo ( !empty($_field['mep_fbc_id']) && $_field['mep_fbc_id'] != '' ) ? esc_attr( $_field['mep_fbc_id'] ) : ''; ?>" placeholder="<?php _e( 'Unique ID', 'mep-form-builder' ); ?>" />
				
            </label>
            <p class="event_meta_help_txt"><?php _e( 'This field must not be empty, Otherwise data will not save into database,', 'mep-form-builder' ); ?></p>
        </td>
        <td colspan="4">
            <div class="mp_form_area">
                <label>
                    <select name="mep_fbc_filed_type[]" class="mp_formControl">
                        <option value=''><?php _e( 'Please Select Type', 'mep-form-builder' ); ?></option>
                        <option value="text" <?php echo ( !empty($_field['mep_fbc_type']) && $_field['mep_fbc_type'] == 'text' )?'selected':''; ?>>
				            <?php _e( 'Text Box', 'mep-form-builder' ); ?>
                        </option>
                        <option value="date" <?php echo ( !empty($_field['mep_fbc_type']) && $_field['mep_fbc_type'] == 'date' )?'selected':''; ?>>
				            <?php _e( 'Date', 'mep-form-builder' ); ?>
                        </option>
                        <option value="textarea" <?php echo ( !empty($_field['mep_fbc_type']) && $_field['mep_fbc_type'] == 'textarea' )?'selected':''; ?>>
				            <?php _e( 'Textarea', 'mep-form-builder' ); ?>
                        </option>
                       <option value="radio" <?php echo ( !empty($_field['mep_fbc_type']) && $_field['mep_fbc_type'] == 'radio' )?'selected':''; ?>>
				            <?php _e( 'Radio Box', 'mep-form-builder' ); ?>
                        </option>
                        <option value="checkbox" <?php echo ( !empty($_field['mep_fbc_type']) && $_field['mep_fbc_type'] == 'checkbox' )?'selected':''; ?>>
				            <?php _e( 'Checkbox', 'mep-form-builder' ); ?>
                        </option>
                        <option value="select" <?php echo ( !empty($_field['mep_fbc_type']) && $_field['mep_fbc_type'] == 'select' )?'selected':''; ?>>
				            <?php _e( 'Dropdown Box', 'mep-form-builder' ); ?>
                        </option>
                    </select>
                </label>
                <label class="mp_event_drop_list <?php echo (!empty($_field['mep_fbc_type']) && ($_field['mep_fbc_type'] == 'select' || $_field['mep_fbc_type'] == 'radio' || $_field['mep_fbc_type'] == 'checkbox')) ? 'mp_event_drop_list_visible' : 'mp_event_drop_list_hidden'; ?>">
                            <input name="mep_fbc_dropdown_data[]" type="text" class="mp_formControl" value="<?php echo (array_key_exists('mep_fbc_dp_data', $_field)) ? esc_attr($_field['mep_fbc_dp_data']) : ''; ?>" placeholder="<?php _e('dropdown list separated by comma(,)', 'mep-form-builder'); ?>"/>
                        </label>
            </div>

        </td>
        <td colspan="2">
            <label>
                <select name="mep_fbc_filed_required[]" class="mp_formControl">
                    <option value=""><?php _e( 'Not Required', 'mep-form-builder' ); ?></option>
                    <option value="1" <?php echo (( array_key_exists( 'mep_fbc_required', $_field ) ) && $_field['mep_fbc_required'])?'selected':''; ?>><?php _e( 'Required', 'mep-form-builder' ); ?></option>
                </select>
            </label>
        </td>
        <td>
            <button type="button" class="mp_event_remove_this_row"><span class="dashicons dashicons-trash"></span></button>
        </td>
    </tr>
    <?php
}


add_action( 'save_post', 'mep_event_fbc_save' );
function mep_event_fbc_save( $post_id ) {
	global $wpdb;

	if ( ! isset( $_POST['mep_event_form_builder_nonce'] ) ||
	     ! wp_verify_nonce( $_POST['mep_event_form_builder_nonce'], 'mep_event_form_builder_nonce' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$old = get_post_meta( $post_id, 'mep_form_builder_data', true );
	$new = array();
	// $options = hhs_get_sample_options();

	$label                 = $_POST['mep_fbc_label'];
	$fbc_id                = $_POST['mep_fbc_filed_id'];
	$fbc_type              = $_POST['mep_fbc_filed_type'];
	$fbc_required          = $_POST['mep_fbc_filed_required'];
	$mep_fbc_dropdown_data = $_POST['mep_fbc_dropdown_data'];
	$mep_event_reg_form_id = $_POST['mep_event_reg_form_id'] ? $_POST['mep_event_reg_form_id'] : 'custom_form';

	$mep_name_label    = $_POST['mep_name_label'];
	$mep_email_label   = $_POST['mep_email_label'];
	$mep_phone_label   = $_POST['mep_phone_label'];
	$mep_address_label = $_POST['mep_address_label'];
	$mep_tshirt_label  = $_POST['mep_tshirt_label'];
	$mep_desg_label    = $_POST['mep_desg_label'];
	$mep_website_label = $_POST['mep_website_label'];
	$mep_veg_label     = $_POST['mep_veg_label'];
	$mep_company_label = $_POST['mep_company_label'];
	$mep_gender_label  = $_POST['mep_gender_label'];


	update_post_meta( $post_id, 'mep_name_label', $mep_name_label );
	update_post_meta( $post_id, 'mep_email_label', $mep_email_label );
	update_post_meta( $post_id, 'mep_phone_label', $mep_phone_label );
	update_post_meta( $post_id, 'mep_address_label', $mep_address_label );
	update_post_meta( $post_id, 'mep_tshirt_label', $mep_tshirt_label );
	update_post_meta( $post_id, 'mep_desg_label', $mep_desg_label );
	update_post_meta( $post_id, 'mep_website_label', $mep_website_label );
	update_post_meta( $post_id, 'mep_veg_label', $mep_veg_label );
	update_post_meta( $post_id, 'mep_company_label', $mep_company_label );
	update_post_meta( $post_id, 'mep_gender_label', $mep_gender_label );
	update_post_meta( $post_id, 'mep_event_reg_form_id', $mep_event_reg_form_id );


// die();
	$count = count( $label );

	for ( $i = 0; $i < $count; $i ++ ) {

		if ( $label[ $i ] != '' ) :
			$new[ $i ]['mep_fbc_label'] = stripslashes( strip_tags( $label[ $i ] ) );
		endif;

		if ( $fbc_id[ $i ] != '' ) :
			$new[ $i ]['mep_fbc_id'] = stripslashes( strip_tags( sanitize_title( $fbc_id[ $i ] ) ) );
		endif;

		if ( $fbc_type[ $i ] != '' ) :
			$new[ $i ]['mep_fbc_type'] = stripslashes( strip_tags( $fbc_type[ $i ] ) );
		endif;

		if ( $fbc_required[ $i ] != '' ) :
			$new[ $i ]['mep_fbc_required'] = stripslashes( strip_tags( $fbc_required[ $i ] ) );
		endif;

		if ( $mep_fbc_dropdown_data[ $i ] != '' ) :
			$new[ $i ]['mep_fbc_dp_data'] = stripslashes( strip_tags( $mep_fbc_dropdown_data[ $i ] ) );
		endif;

	}

	if ( ! empty( $new ) && $new != $old ) {
		update_post_meta( $post_id, 'mep_form_builder_data', $new );
	} elseif ( empty( $new ) && $old ) {
		delete_post_meta( $post_id, 'mep_form_builder_data', $old );
	}
}


function mep_fbc_dropdown_list( $data ) {
	$tee_sizes = $data;
	$tszrray   = explode( ',', $tee_sizes );
	$ts        = "";
	foreach ( $tszrray as $value ) {
		$ts .= "<option value='$value'>$value</option>";
	}

	return trim( $ts );
}


add_action( 'mep_after_reg_form_front', 'mep_users_reg_forms' );

function mep_users_reg_forms( $event_id = '' ) {
	global $post;

	if ( empty( $event_id ) ) {
		$event = $post->ID;
	} else {
		$event = $event_id;
	}

	$mep_form_builder_data = get_post_meta( $event, 'mep_form_builder_data', true ) ? get_post_meta( $event, 'mep_form_builder_data', true ) : [];
    //echo '<pre>';print_r($mep_form_builder_data);echo '</pre>';
	if ( sizeof( $mep_form_builder_data ) > 0 ) {
		foreach ( $mep_form_builder_data as $_field ) {

			$label 		=  array_key_exists( 'mep_fbc_label', $_field ) ? $_field['mep_fbc_label'] : '';
			$uid   		=  array_key_exists( 'mep_fbc_id', $_field ) ? $_field['mep_fbc_id'] . "[]" : '';
			$uidr   	=  array_key_exists( 'mep_fbc_id', $_field ) ? $_field['mep_fbc_id'] : '';
			$type  		=  array_key_exists( 'mep_fbc_type', $_field ) ? $_field['mep_fbc_type'] : '';
			$required 	=  array_key_exists( 'mep_fbc_required', $_field ) ? $_field['mep_fbc_required'] : '';
			$dp_data 	=  array_key_exists( 'mep_fbc_dp_data', $_field ) ? $_field['mep_fbc_dp_data'] : '';
			$req 		= !empty($required) ? 'required' : '';

            $values = explode(',', $dp_data);
			//echo ($_field);

			if ( $type == 'textarea' ) {
				echo "<textarea name='$uid' col='10' row='3' class='mep_input $uidr' placeholder='$label' $req></textarea>";
			}
            if($type == 'radio' && $dp_data){
                 echo "<label class='radio_label'><input type='text' class='dNone' data-radio-value name='$uid' $req /><span class='custom_label'>$label</span>";
                 foreach ($values as $option) {
                     echo "<span class='customRadio' data-radio='$option'>$option</span>";
                 }
                 echo "</label>";

            }
            if($type == 'checkbox' && $dp_data){
                 echo "<div class='customCheckBoxArea'><input type='text' class='dNone' name='$uid' $req /><span class='custom_label'>$label</span>";
                 foreach ($values as $option) {
                     echo "<label class='customCheckboxLabel'><input type='checkbox' class='dNone' data-checked='$option'/><span class='customCheckbox'>$option</span></label>";
                 }
                 echo "</div>";

            }
			if ( $type == 'select' ) {
				echo "<span class='mep-custom-reg-list'><label for='$uid' class='$uidr'>".$label."<select class='$uidr' name='$uid' $req><option value=''>" ?><?php _e('Please Select', 'mep-form-builder'); ?><?php echo $label . "</option>" . mep_fbc_dropdown_list( $dp_data ) . "</select></label></span>";
			}
			if ( $type == 'text' ) {
				echo "<input type='$type' name='$uid' placeholder='$label' class='mep_input $uidr' $req/>";
			}
			if ( $type == 'date' ) {
				echo "<label class='mep_show $uidr'>$label<input type='date' name='$uid' placeholder='$label' class='mep_input $uidr' $req/></label>";
			}


		}
	}
}


function mep_users_reg_forms_checkout( $id ) {
	global $post;
	$mep_form_builder_data = get_post_meta( $id, 'mep_form_builder_data', true );
	if ( $mep_form_builder_data ) {
		foreach ( $mep_form_builder_data as $_field ) {

			$label = $_field['mep_fbc_label'];
			$uid   = $_field['mep_fbc_id'] . "[]";
			$uidr  =  array_key_exists( 'mep_fbc_id', $_field ) ? $_field['mep_fbc_id'] : '';			
			$type  = $_field['mep_fbc_type'];
			if ( array_key_exists( 'mep_fbc_required', $_field ) ) {
				$required = $_field['mep_fbc_required'];
			} else {
				$required = '';
			}
			if ( array_key_exists( 'mep_fbc_dp_data', $_field ) ) {
				$dp_data = $_field['mep_fbc_dp_data'];
			} else {
				$dp_data = '';
			}

			if ( $required ) {
				$req = 'required';
			} else {
				$req = '';
			}

			if ( $type == 'textarea' ) {
				echo "<textarea name='$uid' col='10' row='3' class='mep_input $uidr' placeholder='$label' $req></textarea>";
			}
			if ( $type == 'radio' || $type == 'checkbox' ) {
				echo "<label class='$uidr' for='$uidr'><input type='$type' value='Yes' class='mep-checkbox $uidr' id='$uidr' name='$uid'  $req/> $label</label>";
			}
			if ( $type == 'select' ) {
				echo "<label for='$uid' class='$uidr'><select class='$uidr' name='$uid' $req><option value=''>" ?><?php _e( 'Please Select', 'mep-form-builder' ); ?><?php echo $label . "</option>" . mep_fbc_dropdown_list( $dp_data ) . "</select></label>";
			}
			if ( $type == 'text' ) {
				echo "<input type='$type' name='$uid' placeholder='$label' class='mep_input $uidr' $req/>";
			}
			if ( $type == 'date' ) {
				echo "<label class='mep_show $uidr'>$label<input type='date' name='$uid' placeholder='$label' class='mep_input $uidr' $req/></label>";
			}

		}
	}
}





function mep_fb_forms($start_date,$event,$ticket_type,$form_type='',$seat_no=''){
	$event_id = mep_fb_get_reg_form_id($event);
	$event_meta 			 = get_post_custom($event_id);
	// print_r($event_meta);
	  $mep_form_builder_data = get_post_meta($event_id, 'mep_form_builder_data', true);
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
  
  
	  if($mep_full_name || $mep_reg_email || $mep_reg_phone || $mep_reg_address || $mep_reg_designation || $mep_reg_website || $mep_reg_veg || $mep_reg_company || $mep_reg_gender || $mep_reg_tshirtsize || $mep_form_builder_data){ 
	  ?><div class='mep-user-info-sec'><h5><?php echo $ticket_type; ?> <?php echo mep_get_option('mep_attendee_info_text', 'label_setting_sec') ? mep_get_option('mep_attendee_info_text', 'label_setting_sec') : _e('Attendee info:','mep-form-builder'); if($form_type !='checkout'){ ?><span id='attendee_ticket_type_no'>"+i+"</span><?php }else{ ?> <span> <?php echo $seat_no;  ?> (<?php echo get_the_title($event_id); ?>)</span> <?php } ?></h5><input type='hidden' name='mep_event_id[]' value='<?php echo $event; ?>'/><input type='<?php if($mep_full_name){ echo 'text'; }else{ echo 'hidden'; } ?>' <?php if($mep_full_name){ ?> required='required' <?php } ?> name='user_name[]' class='mep_input mep_user_name' placeholder='<?php if(get_post_meta($event_id,'mep_name_label',true)){ echo get_post_meta($event_id,'mep_name_label',true); }else{ _e('Enter Your Name','mep-form-builder'); } ?>'/><input type='<?php if($mep_reg_email){ echo 'email'; }else{ echo 'hidden'; } ?>' <?php if($mep_reg_email){ ?> required='required' <?php } ?> name='user_email[]' class='mep_input mep_user_email' placeholder='<?php if(get_post_meta($event_id,'mep_email_label',true)){ echo get_post_meta($event_id,'mep_email_label',true); }else{ _e('Enter Your Email','mep-form-builder'); } ?>'/><input type='<?php if($mep_reg_phone){ echo 'text'; }else{ echo 'hidden'; } ?>' <?php if($mep_reg_phone){ ?> required='required' <?php } ?> name='user_phone[]' class='mep_input mep_user_phone' placeholder='<?php if(get_post_meta($event_id,'mep_phone_label',true)){ echo get_post_meta($event_id,'mep_phone_label',true); }else{ _e('Enter Your Phone','mep-form-builder'); } ?>'/><textarea name='user_address[]' class='mep_input <?php if($mep_reg_address){ echo 'mep-show'; }else{ echo 'mep-hidden'; } ?> mep_user_address' rows='3' <?php if($mep_reg_address){ ?> required='required' <?php } ?> placeholder='<?php if(get_post_meta($event_id,'mep_address_label',true)){ echo get_post_meta($event_id,'mep_address_label',true); }else{ _e('Enter you address','mep-form-builder'); } ?>'></textarea><label class='<?php if($mep_reg_tshirtsize){ echo "mep-show"; }else{ echo "mep-hidden"; } ?>' for='tshirt' class='mep_user_tsize_label' style='text-align: left;'><?php if(get_post_meta($event_id,'mep_tshirt_label',true)){ echo get_post_meta($event_id,'mep_tshirt_label',true); }else{ _e('T-Shirt Size','mep-form-builder'); } ?><select name='tshirtsize[]' id='tshirt' class='mep_user_tsize'><option value=''><?php _e('Please Select','mep-form-builder'); ?></option><?php echo mep_get_tshirts_sizes($event_id); ?></select></label><label class='mep_user_gender_label <?php if($mep_reg_gender){ echo 'mep-show'; }else{ echo 'mep-hidden'; } ?>' for='gen' style='text-align: left;'><?php if(get_post_meta($event_id,'mep_gender_label',true)){ echo get_post_meta($event_id,'mep_gender_label',true); }else{ _e('Gender','mep-form-builder'); } ?><select name='gender[]' id='gen' class='mep_user_gender'><option value=''><?php _e('Please Select','mep-form-builder'); ?></option><option value='Male'><?php _e('Male','mep-form-builder'); ?></option><option value='Female'><?php _e('Female','mep-form-builder'); ?></option></select></label><input type='<?php if($mep_reg_company){ echo 'text'; }else{ echo 'hidden'; } ?>' name='user_company[]' class='mep_input mep_user_company' placeholder='<?php if(get_post_meta($event_id,'mep_company_label',true)){ echo get_post_meta($event_id,'mep_company_label',true); }else{ _e('Company','mep-form-builder'); } ?>'/><input type='<?php if($mep_reg_designation){ echo 'text'; }else{ echo 'hidden'; } ?>' name='user_designation[]' class='mep_input mep_user_designation' placeholder='<?php  if(get_post_meta($event_id,'mep_desg_label',true)){ echo get_post_meta($event_id,'mep_desg_label',true); }else{_e('Designation','mep-form-builder'); } ?>'/><input type='<?php if($mep_reg_website){ echo 'text'; }else{ echo 'hidden'; } ?>' name='user_website[]' class='mep_input mep_user_website' placeholder='<?php if(get_post_meta($event_id,'mep_website_label',true)){ echo get_post_meta($event_id,'mep_website_label',true); }else{ _e('Website','mep-form-builder'); } ?>'/><label class='<?php if($mep_reg_veg){ echo 'mep-show'; }else{ echo 'mep-hidden'; } ?> mep_user_veg_label' for='veg' style='text-align: left;'><?php if(get_post_meta($event_id,'mep_veg_label',true)){ echo get_post_meta($event_id,'mep_veg_label',true); }else{ _e('Vegetarian','mep-form-builder'); } ?><select name='vegetarian[]' id='veg' class='mep_user_veg'><option value=''><?php _e('Please Select','mep-form-builder'); ?></option><option value='Yes'><?php _e('Yes','mep-form-builder'); ?></option><option value='No'><?php _e('No','mep-form-builder'); ?></option></select></label><input type='hidden' name='ticket_type[]' class='mep_input' value='<?php echo $ticket_type; ?>' /><input type='hidden' name='event_date[]' class='mep_input' value='<?php echo $start_date; ?>' /><?php if($form_type =='checkout'){ ?> <input type='hidden' name='option_name[]' value='<?php echo $ticket_type; ?>'> <?php } do_action('mep_after_reg_form_front',$event_id); ?></div><?php
  
  }
  }


add_action( 'mep_reg_fields', 'mep_pro_reg_form_fileds', 10, 3 );

function mep_pro_reg_form_fileds( $start_date, $event_id, $ticket_type ) {
	$form_position = mep_get_option( 'mep_user_form_position', 'general_attendee_sec', 'details_page' );
	if ( $form_position == 'details_page' ) {
		mep_fb_forms( $start_date, $event_id, $ticket_type, 'details' );
	}
}


add_filter( 'post_row_actions', 'mep_remove_row_actions', 10, 1 );
function mep_remove_row_actions( $actions ) {
	if ( get_post_type() === 'mep_events_attendees' )
		// unset( $actions['edit'] );
		// unset( $actions['view'] );
		// unset( $actions['trash'] );
	{
		unset( $actions['inline hide-if-no-js'] );
	}

	return $actions;
}


// Add the custom columns to the book post type:
add_filter( 'manage_mep_events_posts_columns', 'mep_pro_set_custom_edit_event_columns' );
function mep_pro_set_custom_edit_event_columns( $columns ) {

	unset( $columns['date'] );

	// $columns['mep_status'] = __( 'Status', 'mep-form-builder' );
	// $columns['mep_atten'] = __( 'Attendees', 'mep-form-builder' );
	// $columns['mep_csv_export'] = __( 'Export', 'mep-form-builder' );

	return $columns;
}


add_action( 'admin_head', 'mep_admin_style_csv' );
function mep_admin_style_csv() {
	?>
    <style>
        span.mep_order_st_completed {
            background: #c8d7e1;
            color: #2e4453;
            padding: 5px 10px;
            text-transform: capitalize;
            margin-top: 0px;
            display: block;
            text-align: center;
            border-radius: 5px;
        }


        span.mep_order_st_on-hold {
            background: #f8dda7;
            color: #94660c;
            padding: 5px 10px;
            text-transform: capitalize;
            margin-top: 0px;
            display: block;
            text-align: center;
            border-radius: 5px;
        }


        span.mep_order_st_processing {

            background: #c6e1c6;
            color: #5b841b;
            padding: 5px 10px;
            text-transform: capitalize;
            margin-top: 0px;
            display: block;
            text-align: center;
            border-radius: 5px;
        }


        span.mep_order_st_cancelled {
            background: red;
            color: #fff;
            padding: 5px 10px;
            text-transform: capitalize;
            margin-top: 0px;
            display: block;
            text-align: center;
            border-radius: 5px;
        }

    </style>
	<?php
}


// Add the custom columns to the book post type:
add_filter( 'manage_mep_events_attendees_posts_columns', 'mep_set_custom_events_attendees_columns' );
function mep_set_custom_events_attendees_columns( $columns ) {

	unset( $columns['title'] );
	unset( $columns['date'] );

	$columns['mep_uid']      = __( 'Ticket No', 'mepevvent' );
	$columns['mep_fn']       = __( 'Full Name', 'mep-form-builder' );
	$columns['mep_ttype']    = __( 'Ticket', 'mep-form-builder' );
	$columns['mep_evnt']     = __( 'Event', 'mep-form-builder' );
	$columns['mep_order_id'] = __( 'Order ID', 'mep-form-builder' );
	$columns['mep_order_st'] = __( 'Order Status', 'mep-form-builder' );

	return apply_filters( 'mep_attendee_dashboard_column', $columns );
}


// Add the data to the custom columns for the book post type:
add_action( 'manage_mep_events_attendees_posts_custom_column', 'mep_events_attendees_column', 10, 2 );
function mep_events_attendees_column( $column, $post_id ) {
	switch ( $column ) {

		case 'mep_uid' :
			echo get_post_meta( $post_id, 'ea_user_id', true ) . get_post_meta( $post_id, 'ea_order_id', true ) . get_post_meta( $post_id, 'ea_event_id', true ) . $post_id;
			break;

		case 'mep_fn' :
			echo get_post_meta( $post_id, 'ea_name', true );
			break;

		case 'mep_email' :
			echo get_post_meta( $post_id, 'ea_email', true );
			break;

		case 'mep_phone' :
			echo get_post_meta( $post_id, 'ea_phone', true );
			break;

		case 'mep_tsize' :
			echo get_post_meta( $post_id, 'ea_tshirtsize', true );
			break;

		case 'mep_address' :
			echo get_post_meta( $post_id, 'ea_address_1', true ) . "<br/>" . get_post_meta( $post_id, 'ea_address_2', true ) . "<br/>" . get_post_meta( $post_id, 'ea_state', true ) . ", " . get_post_meta( $post_id, 'ea_city', true ) . ", " . get_post_meta( $post_id, 'ea_country', true );
			break;

		case 'mep_ttype' :
			echo get_post_meta( $post_id, 'ea_ticket_type', true );
			break;

		case 'mep_evnt' :
			echo get_post_meta( $post_id, 'ea_event_name', true );
			break;

		case 'mep_order_st' :
			echo "<span class=mep_order_st_" . get_post_meta( $post_id, 'ea_order_status', true ) . ">" . get_post_meta( $post_id, 'ea_order_status', true ) . '</span>';
			break;

		case 'mep_order_id' :
			echo '<a href="' . get_site_url() . '/wp-admin/post.php?post=' . get_post_meta( $post_id, 'ea_order_id', true ) . '&action=edit" target="_blank">' . get_post_meta( $post_id, 'ea_order_id', true ) . '</a>';
			break;

		case 'mep_atten' :
			?>

			<?php
			echo '<a class="button button-primary button-large" href="' . get_site_url() . '/wp-admin/edit.php?post_type=mep_events_attendees&meta_value=' . $post_id . '">Attendees List</a>';
			break;
	}
}

// Add the data to the custom columns for the book post type:
add_action( 'manage_mep_events_posts_custom_column', 'mep_custom_event_column_pro', 10, 2 );
function mep_custom_event_column_pro( $column, $post_id ) {
	switch ( $column ) {
		case 'mep_csv_export' :
			$multi_date = get_post_meta( $post_id, 'mep_event_more_date', true ) ? get_post_meta( $post_id, 'mep_event_more_date', true ) : array();
			$recurring = get_post_meta( $post_id, 'mep_enable_recurring', true ) ? get_post_meta( $post_id, 'mep_enable_recurring', true ) : 'no';
			?>
            <form action="" method="get">
				<?php
				if ( $recurring == 'everyday' ) {
					do_action( 'mep_before_csv_export_btn', $post_id );
				} else {
					?>
                    <select name="ea_event_date" id="" style='font-size: 14px;border: 1px solid blue;width: 110px;display:<?php if ( $recurring == 'yes' ) {
						echo 'block';
					} else {
						echo 'none';
					} ?>'>
                        <option value="<?php echo date( 'Y-m-d H:i', strtotime( get_post_meta( $post_id, 'event_start_date', true ) . ' ' . get_post_meta( $post_id, 'event_start_time', true ) ) ); ?>"><?php echo get_mep_datetime( get_post_meta( $post_id, 'event_start_date', true ) . ' ' . get_post_meta( $post_id, 'event_start_time', true ), 'date-text' ) . ' ' . get_mep_datetime( get_post_meta( $post_id, 'event_start_date', true ) . ' ' . get_post_meta( $post_id, 'event_start_time', true ), 'time' ); ?></option>

						<?php foreach ( $multi_date as $multi ) { ?>
                            <option value="<?php echo date( 'Y-m-d H:i', strtotime( $multi['event_more_start_date'] . ' ' . $multi['event_more_start_time'] ) ); ?>"><?php echo get_mep_datetime( $multi['event_more_start_date'], 'date-text' ) . ' ' . get_mep_datetime( $multi['event_more_start_time'], 'time' ); ?></option>
						<?php } ?>
                    </select>
				<?php } ?>
                <input type="hidden" name='post_type' value='mep_events_attendees'>
                <input type="hidden" name='action' value='download_csv_custom'>
                <input type="hidden" name='event_id' value='<?php echo $post_id; ?>'>
                <button class="button button-primary button-large"><?php _e( 'Export CSV', 'mep-form-builder' ); ?></button>

            </form>

            <form action="" method="get">
                <select name="ea_event_date" id="" style='font-size: 14px;border: 1px solid blue;width: 110px;display:<?php if ( $recurring == 'yes' ) {
					echo 'block';
				} else {
					echo 'none';
				} ?>'>
                    <option value="<?php echo date( 'Y-m-d H:i:s', strtotime( get_post_meta( $post_id, 'event_start_date', true ) . ' ' . get_post_meta( $post_id, 'event_start_time', true ) ) ); ?>"><?php echo get_mep_datetime( get_post_meta( $post_id, 'event_start_date', true ) . ' ' . get_post_meta( $post_id, 'event_start_time', true ), 'date-text' ) . ' ' . get_mep_datetime( get_post_meta( $post_id, 'event_start_date', true ) . ' ' . get_post_meta( $post_id, 'event_start_time', true ), 'time' ); ?></option>

					<?php foreach ( $multi_date as $multi ) { ?>
                        <option value="<?php echo date( 'Y-m-d H:i:s', strtotime( $multi['event_more_start_date'] . ' ' . $multi['event_more_start_time'] ) ); ?>"><?php echo get_mep_datetime( $multi['event_more_start_date'], 'date-text' ) . ' ' . get_mep_datetime( $multi['event_more_start_time'], 'time' ); ?></option>
					<?php } ?>
                </select>

                <input type="hidden" name='post_type' value='mep_events_attendees'>
                <input type="hidden" name='action' value='download_csv_extra_service'>
                <input type="hidden" name='event_id' value='<?php echo $post_id; ?>'>
                <button style='margin-top:2px' class="button button-primary button-large"><?php _e( 'Extra Service', 'mep-form-builder' ); ?></button>

            </form>
			<?php
			// echo '<a class="button button-primary button-large" href="'.get_site_url().'/wp-admin/edit.php?post_type=mep_events_attendees&action=download_csv_custom&meta_value='.$post_id.'">Export CSV</a>';
			break;
	}
}


function mep_disable_new_posts() {
// Hide sidebar link
	global $submenu;
	unset( $submenu['edit.php?post_type=mep_events_attendees'][10] );
// // Hide link on listing page
	if ( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'mep_events_attendees' ) {
		echo '<style type="text/css">
      #favorite-actions, .add-new-h2, .tablenav, .page-title-action { display:none; }
      </style>';
	}
}

//add_action('admin_menu', 'mep_disable_new_posts');


add_action( 'mep_single_page_reg', 'form_reg_fields_single' );

function form_reg_fields_single() {
	global $post, $qm;
	$form_position = mep_get_option( 'mep_user_form_position', 'general_attendee_sec', 'details_page' );
	if ( $form_position == 'details_page' ) {
		$event_meta = get_post_custom( $post->ID );
		// print_r($event_meta);
		$mep_full_name         = strip_tags( $event_meta['mep_full_name'][0] );
		$mep_reg_email         = strip_tags( $event_meta['mep_reg_email'][0] );
		$mep_reg_phone         = strip_tags( $event_meta['mep_reg_phone'][0] );
		$mep_reg_address       = strip_tags( $event_meta['mep_reg_address'][0] );
		$mep_reg_designation   = strip_tags( $event_meta['mep_reg_designation'][0] );
		$mep_reg_website       = strip_tags( $event_meta['mep_reg_website'][0] );
		$mep_reg_veg           = strip_tags( $event_meta['mep_reg_veg'][0] );
		$mep_reg_company       = strip_tags( $event_meta['mep_reg_company'][0] );
		$mep_reg_gender        = strip_tags( $event_meta['mep_reg_gender'][0] );
		$mep_reg_tshirtsize    = strip_tags( $event_meta['mep_reg_tshirtsize'][0] );
		$mep_form_builder_data = get_post_meta( $post->ID, 'mep_form_builder_data', true );
		ob_start();
		if ( $mep_full_name || $mep_reg_email || $mep_reg_phone || $mep_reg_address || $mep_reg_designation || $mep_reg_website || $mep_reg_veg || $mep_reg_company || $mep_reg_gender || $mep_reg_tshirtsize || $mep_form_builder_data ) {
			?>
            <div class="user-info-sec">
                <div id="divParent">
                    <div class='mep-user-info-sec'><h5><?php echo $qm; ?> <?php _e( 'Attendee info', 'mep-form-builder' ); ?>:1</h5><input type='<?php if ( $mep_full_name ) {
							echo 'text';
						} else {
							echo 'hidden';
						} ?>' <?php if ( $mep_full_name ) { ?> required='required' <?php } ?> name='user_name[]' class='mep_input' placeholder='<?php if ( get_post_meta( $post->ID, 'mep_name_label', true ) ) {
							echo get_post_meta( $post->ID, 'mep_name_label', true );
						} else {
							_e( 'Enter Your Name', 'mep-form-builder' );
						} ?>'/><input type='<?php if ( $mep_reg_email ) {
							echo 'email';
						} else {
							echo 'hidden';
						} ?>' <?php if ( $mep_reg_email ) { ?> required='required' <?php } ?> name='user_email[]' class='mep_input' placeholder='<?php if ( get_post_meta( $post->ID, 'mep_email_label', true ) ) {
							echo get_post_meta( $post->ID, 'mep_email_label', true );
						} else {
							_e( 'Enter Your Email', 'mep-form-builder' );
						} ?>'/><input type='<?php if ( $mep_reg_phone ) {
							echo 'text';
						} else {
							echo 'hidden';
						} ?>' <?php if ( $mep_reg_phone ) { ?> required='required' <?php } ?> name='user_phone[]' class='mep_input' placeholder='<?php if ( get_post_meta( $post->ID, 'mep_phone_label', true ) ) {
							echo get_post_meta( $post->ID, 'mep_phone_label', true );
						} else {
							_e( 'Enter Your Phone', 'mep-form-builder' );
						} ?>'/><textarea name='user_address[]' class='mep_input <?php if ( $mep_reg_address ) {
							echo 'mep-show';
						} else {
							echo 'mep-hidden';
						} ?>' rows='3' <?php if ( $mep_reg_address ) { ?> required='required' <?php } ?> placeholder='<?php if ( get_post_meta( $post->ID, 'mep_address_label', true ) ) {
							echo get_post_meta( $post->ID, 'mep_address_label', true );
						} else {
							_e( 'Enter you address', 'mep-form-builder' );
						} ?>'></textarea><label class='<?php if ( $mep_reg_tshirtsize ) {
							echo "mep-show";
						} else {
							echo "mep-hidden";
						} ?>' for='gen' style='text-align: left;'><?php if ( get_post_meta( $post->ID, 'mep_tshirt_label', true ) ) {
								echo get_post_meta( $post->ID, 'mep_tshirt_label', true );
							} else {
								_e( 'T-Shirt Size', 'mep-form-builder' );
							} ?><select name='tshirtsize[]' id='gen'>
                                <option value=''><?php _e( 'Please Select', 'mep-form-builder' ); ?></option><?php echo mep_get_tshirts_sizes( $post->ID ); ?></select></label><label class='<?php if ( $mep_reg_gender ) {
							echo 'mep-show';
						} else {
							echo 'mep-hidden';
						} ?>' for='gen' style='text-align: left;'><?php if ( get_post_meta( $post->ID, 'mep_gender_label', true ) ) {
								echo get_post_meta( $post->ID, 'mep_gender_label', true );
							} else {
								_e( 'Gender', 'mep-form-builder' );
							} ?><select name='gender[]' id='gen'>
                                <option value=''><?php _e( 'Please Select', 'mep-form-builder' ); ?></option>
                                <option value='Male'><?php _e( 'Male', 'mep-form-builder' ); ?></option>
                                <option value='Female'><?php _e( 'Female', 'mep-form-builder' ); ?></option>
                            </select></label><input type='<?php if ( $mep_reg_company ) {
							echo 'text';
						} else {
							echo 'hidden';
						} ?>' name='user_company[]' class='mep_input' placeholder='<?php if ( get_post_meta( $post->ID, 'mep_company_label', true ) ) {
							echo get_post_meta( $post->ID, 'mep_company_label', true );
						} else {
							_e( 'Company', 'mep-form-builder' );
						} ?>'/><input type='<?php if ( $mep_reg_designation ) {
							echo 'text';
						} else {
							echo 'hidden';
						} ?>' name='user_designation[]' class='mep_input' placeholder='<?php if ( get_post_meta( $post->ID, 'mep_desg_label', true ) ) {
							echo get_post_meta( $post->ID, 'mep_desg_label', true );
						} else {
							_e( 'Designation', 'mep-form-builder' );
						} ?>'/><input type='<?php if ( $mep_reg_website ) {
							echo 'text';
						} else {
							echo 'hidden';
						} ?>' name='user_website[]' class='mep_input' placeholder='<?php if ( get_post_meta( $post->ID, 'mep_website_label', true ) ) {
							echo get_post_meta( $post->ID, 'mep_website_label', true );
						} else {
							_e( 'Website', 'mep-form-builder' );
						} ?>'/><label class='<?php if ( $mep_reg_veg ) {
							echo 'mep-show';
						} else {
							echo 'mep-hidden';
						} ?>' for='veg' style='text-align: left;'><?php if ( get_post_meta( $post->ID, 'mep_veg_label', true ) ) {
								echo get_post_meta( $post->ID, 'mep_veg_label', true );
							} else {
								_e( 'Vegetarian', 'mep-form-builder' );
							} ?><select name='vegetarian[]' id='veg'>
                                <option value=''><?php _e( 'Please Select', 'mep-form-builder' ); ?></option>
                                <option value='Yes'><?php _e( 'Yes', 'mep-form-builder' ); ?></option>
                                <option value='No'><?php _e( 'No', 'mep-form-builder' ); ?></option>
                            </select></label><input type='hidden' name='ticket_type[]' class='mep_input' value='<?php echo $qm; ?>'/><?php do_action( 'mep_after_reg_form_front' ); ?></div>
                </div>
            </div>
			<?php
			$form = ob_get_clean();
			echo $form;
		}
	}
}


function mep_set_attendee_delete( $order_id, $status, $post_status ) {

	$args_search_qqq = array(
		'post_type'      => array( 'mep_events_attendees' ),
		'posts_per_page' => - 1,
		'post_status'    => $post_status,
		'meta_query'     => array(
			array(
				'key'     => 'ea_order_id',
				'value'   => $order_id,
				'compare' => '='
			)
		)

	);

	$loop = new WP_Query( $args_search_qqq );
	while ( $loop->have_posts() ) {
		$loop->the_post();
		$post_id                     = get_the_id(); // change this to your post ID
		$status                      = $status;
		$current_post                = get_post( $post_id, 'ARRAY_A' );
		$current_post['post_status'] = $status;
		wp_update_post( $current_post );
	}
}


function mep_set_attendee_order_status( $order_id, $status ) {

	$args_search_qqq = array(
		'post_type'      => array( 'mep_events_attendees' ),
		'posts_per_page' => - 1,
		'meta_query'     => array(
			array(
				'key'     => 'ea_order_id',
				'value'   => $order_id,
				'compare' => '='
			)
		)

	);

	$loop = new WP_Query( $args_search_qqq );
	while ( $loop->have_posts() ) {
		$loop->the_post();
		$post_id = get_the_id(); // change this to your post ID
		update_post_meta( $post_id, 'ea_order_status', $status );
	}
}


add_action( 'wp_head', 'mep_builder_css' );
function mep_builder_css() {
	?>
    <style type="text/css">
        [type="date"] {
            background: #fff url(https://cdn1.iconfinder.com/data/icons/cc_mono_icon_set/blacks/16x16/calendar_2.png) 97% 50% no-repeat;
        }
        [type="date"]::-webkit-inner-spin-button {
            display: none;
        }
        [type="date"]::-webkit-calendar-picker-indicator {
            opacity: 0;
        }
    </style>
	<?php
}


add_action( 'admin_init', 'wbtm_update_databas_once' );
function wbtm_update_databas_once() {
	global $wpdb;

	if ( empty( get_option( 'mep_update_db_once_1989' ) ) && get_option( 'mep_update_db_once_1989' ) != 'completed' ) {
		$args = array(
			'post_type'      => array( 'mep_events_attendees' ),
			'posts_per_page' => - 1

		);
		$loop = new WP_Query( $args );
		//print_r($loop);
		while ( $loop->have_posts() ) {
			$loop->the_post();
			$ticket     = get_post_meta( get_the_id(), 'ea_user_id', true ) . get_post_meta( get_the_id(), 'ea_order_id', true ) . get_post_meta( get_the_id(), 'ea_event_id', true ) . get_the_id();
			$update_pin = update_post_meta( get_the_id(), 'ea_ticket_no', $ticket );

		}
		wp_reset_postdata();
		update_option( 'mep_update_db_once_1989', 'completed' );
	}

	if ( empty( get_option( 'mep_update_db_once_198919' ) ) && get_option( 'mep_update_db_once_198919' ) != 'completed' ) {
		$args = array(
			'post_type'      => array( 'mep_events_attendees' ),
			'posts_per_page' => - 1

		);
		$loop = new WP_Query( $args );
		//print_r($loop);
		while ( $loop->have_posts() ) {
			$loop->the_post();

			$order = wc_get_order( get_post_meta( get_the_id(), 'ea_order_id', true ) );
			if ( is_object( $order ) ) {
				$order_status  = $order->get_status();
				$update_status = update_post_meta( get_the_id(), 'ea_order_status', $order_status );
			}
		}
		wp_reset_postdata();
		update_option( 'mep_update_db_once_198919', 'completed' );
	}
}


add_action( 'restrict_manage_posts', 'mep_add_order_status_filter_list' );
function mep_add_order_status_filter_list() {
	$type = 'mep_events_attendees';
	if ( isset( $_GET['post_type'] ) ) {
		$type = $_GET['post_type'];
	}

	//only add filter to post type you want
	if ( 'mep_events_attendees' == $type ) {
		//change this to the list of values you want to show
		//in 'label' => 'value' format
		$values = array(
			// 'Hold' => 'on-hold',
			// 'Pending' => 'pending',
			'Processing' => 'processing',
			'Completed'  => 'completed',
			// 'Cancelled' => 'cancelled',
			// 'Refunded' => 'refunded',
			// 'Failed' => 'failed',
		);
		?>
        <select name="filter_by_status">
            <option value=""><?php _e( 'Filter By Order Status ', 'mep-form-builder' ); ?></option>
			<?php
			$current_v = isset( $_GET['filter_by_status'] ) ? $_GET['filter_by_status'] : '';
			foreach ( $values as $label => $value ) {
				printf
				(
					'<option value="%s"%s>%s</option>',
					$value,
					$value == $current_v ? ' selected="selected"' : '',
					$label
				);
			}
			?>
        </select>
		<?php
	}
}


add_action( 'admin_init', 'mep_add_custom_query_hook' );
function mep_add_custom_query_hook() {
	if ( ! isset( $_GET['meta_value'] ) && ! isset( $_GET['action'] ) ) {
		global $pagenow;
		if ( $pagenow == 'edit.php' ) {


		}
	}
	add_filter( 'parse_query', 'mep_order_filter_push' );
}

// add_filter( 'parse_query', 'mep_order_filter_load' );
function mep_order_filter_load( $query ) {
	global $pagenow;

	$q_vars = &$query->query_vars;


	if ( $pagenow == 'edit.php' && isset( $q_vars['post_type'] ) && $q_vars['post_type'] == 'mep_events_attendees' && isset( $_GET['event_id'] ) ) {


		// $event_date = date('Y-m-d',strtotime($_GET['ea_event_date']));
		$event_date = $_GET['ea_event_date'];

		$meta_query = array(
			'relation' => 'AND',
			array(
				'relation' => 'AND',
				array(
					'key'     => 'ea_event_id',
					'value'   => $_GET['event_id'],
					'compare' => '='
				),
				array(
					'key'     => 'ea_event_date',
					'value'   => $event_date,
					'compare' => 'LIKE'
				)
			),
			array(
				'relation' => 'OR',
				array(
					'key'     => 'ea_order_status',
					'value'   => 'completed',
					'compare' => '='
				),
				array(
					'key'     => 'ea_order_status',
					'value'   => 'processing',
					'compare' => '='
				)
			)
		);


		$query->set( 'meta_query', $meta_query );


	} elseif ( $pagenow == 'edit.php' && isset( $q_vars['post_type'] ) && $_GET['post_type'] == 'mep_events_attendees' && ! isset( $_GET['action'] ) ) {

		$meta_query = array(
			'relation' => 'OR',
			[
				'key'     => 'ea_order_status',
				'value'   => 'completed',
				'compare' => '='
			],
			[
				'key'     => 'ea_order_status',
				'value'   => 'processing',
				'compare' => '='
			]
		);

		$query->set( 'meta_query', $meta_query );
	}


	// print_r($query);
}

//
function mep_order_filter_push( $query ) {
	global $pagenow;
	$type = 'mep_events_attendees';
	if ( isset( $_GET['post_type'] ) ) {
		$type = $_GET['post_type'];
	}
	if ( 'mep_events_attendees' == $type && is_admin() && $pagenow == 'edit.php' && isset( $_GET['filter_by_status'] ) && $_GET['filter_by_status'] != '' && ! isset( $_GET['action'] ) ) {

		$query->query_vars['meta_key']   = 'ea_order_status';
		$query->query_vars['meta_value'] = $_GET['filter_by_status'];
	}
}

add_action( 'mep_user_order_list_table_head', 'mep_pro_show_download_head' );
function mep_pro_show_download_head() {
	ob_start();
	?>
    <th><?php _e( 'Download', 'mep-form-builder' ); ?></th>
	<?php
	echo ob_get_clean();
}

add_action( 'mep_user_order_list_table_row', 'mep_pro_show_download_btn_in_list', 10 );
function mep_pro_show_download_btn_in_list( $attendee_id ) {
	$order_array  = array( 'processing', 'completed' );
	$order_status = get_post_meta( $attendee_id, 'ea_order_status', true );
	ob_start();
	if ( in_array( $order_status, $order_array ) ) { ?>
        <td><a href="<?php echo get_the_permalink( $attendee_id ); ?>"><?php _e( 'Download', 'mep-form-builder' ); ?></a>
        </td>
	<?php } else { ?>
        <td></td>
	<?php }
	echo ob_get_clean();
}


// Add the data to the custom columns for the book post type:
add_action( 'manage_mep_events_posts_custom_column', 'mep_fb_custom_event_column', 10, 2 );
if ( ! function_exists( 'mep_fb_custom_event_column' ) ) {
	function mep_fb_custom_event_column( $column, $post_id ) {
		switch ( $column ) {

			case 'mep_atten' :
				$multi_date = get_post_meta( $post_id, 'mep_event_more_date', true ) ? get_post_meta( $post_id, 'mep_event_more_date', true ) : array();
				$recurring = get_post_meta( $post_id, 'mep_enable_recurring', true ) ? get_post_meta( $post_id, 'mep_enable_recurring', true ) : 'no';
				?>
                <form action="" method="get">
					<?php
					if ( $recurring == 'everyday' ) {
						do_action( 'mep_before_attendee_list_btn', $post_id );
					} else {
						?>
                        <select name="ea_event_date" id="" style='font-size: 14px;border: 1px solid blue;width: 110px;display:<?php if ( $recurring == 'yes' ) {
							echo 'block';
						} else {
							echo 'none';
						} ?>'>
                            <option value="<?php echo date( 'Y-m-d H:i', strtotime( get_post_meta( $post_id, 'event_start_date', true ) . ' ' . get_post_meta( $post_id, 'event_start_time', true ) ) ); ?>"><?php echo get_mep_datetime( get_post_meta( $post_id, 'event_start_date', true ), 'date-text' ) . ' ' . get_mep_datetime( get_post_meta( $post_id, 'event_start_date', true ) . ' ' . get_post_meta( $post_id, 'event_start_time', true ), 'time' ); ?></option>
							<?php foreach ( $multi_date as $multi ) { ?>
                                <option value="<?php echo date( 'Y-m-d H:i', strtotime( $multi['event_more_start_date'] . ' ' . $multi['event_more_start_time'] ) ); ?>"><?php echo get_mep_datetime( $multi['event_more_start_date'], 'date-text' ) . ' ' . get_mep_datetime( $multi['event_more_start_time'], 'time' ); ?></option>
							<?php } ?>
                        </select>
					<?php } ?>
                    <input type="hidden" name='post_type' value='mep_events'>
                    <input type="hidden" name='page' value='attendee_list'>
                    <input type="hidden" name='event_id' value='<?php echo $post_id; ?>'>
                    <button class="button button-primary button-large"><?php _e( 'Attendees List', 'mep-form-builder' ); ?></button>
                </form>
				<?php
				break;
		}
	}
}


if ( ! function_exists( 'mep_fb_change_attandee_status' ) ) {
	function mep_fb_change_attandee_status( $attendee_id, $set_status, $post_status ) {
		$args = array(
			'post_type'      => array( 'mep_events_attendees' ),
			'posts_per_page' => - 1,
			'p'              => $attendee_id,
			'post_status'    => $post_status
		);
		$loop = new WP_Query( $args );
		$tid  = array();
		foreach ( $loop->posts as $ticket ) {
			$post_id                     = $ticket->ID;
			$current_post                = get_post( $post_id, 'ARRAY_A' );
			$current_post['post_status'] = $set_status;
			wp_update_post( $current_post );
		}
	}
}


// Ajax Issue
add_action( 'wp_head', 'mep_fb_ajax_url', 5 );
add_action( 'admin_head', 'mep_fb_ajax_url', 5 );
function mep_fb_ajax_url() {
	?>
    <script type="text/javascript">
        // WooCommerce Event Manager Ajax URL
        var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
    </script>
	<?php
}


add_action( 'mep_attendee_list_item_action_middile', 'mep_fb_sync_attendee_date' );
function mep_fb_sync_attendee_date( $attendee_id ) {
	?>
    <a href="#" class='mep_sync_data' data-id='<?php echo $attendee_id; ?>' title='Sync Data'><span class="dashicons dashicons-update-alt"></span></a>
	<?php
}


function mep_fb_attendee_data_sync_from_order_meta( $order_id, $attendee_id ) {
	$order        = wc_get_order( $order_id );
	$order_meta   = get_post_meta( $order_id );
	$order_status = $order->get_status();
	foreach ( $order->get_items() as $item_id => $item_values ) {
		$item_id = $item_id;
	}
	$event_id              = wc_get_order_item_meta( $item_id, 'event_id', true );
	$user_info_arr         = wc_get_order_item_meta( $item_id, '_event_user_info', true );
	$service_info_arr      = wc_get_order_item_meta( $item_id, '_event_service_info', true );
	$event_ticket_info_arr = wc_get_order_item_meta( $item_id, '_event_ticket_info', true );
	if ( is_array( $user_info_arr ) & sizeof( $user_info_arr ) > 0 ) {
		foreach ( $user_info_arr as $_user_info ) {
			mep_fb_update_attendee_data( $attendee_id, $_user_info, $order_id, $event_id, 'user' );
		}
	} else {
		foreach ( $event_ticket_info_arr as $tinfo ) {
			for ( $x = 1; $x <= $tinfo['ticket_qty']; $x ++ ) {
				mep_fb_update_attendee_data( $attendee_id, $tinfo, $order_id, $event_id, 'billing' );
			}
		}
	}
}


function mep_fb_update_attendee_data( $attendee_id, $user_info, $order_id, $event_id, $type ) {
	$attendee_name 		= get_post_meta( $attendee_id, 'ea_name', true );
	$ea_order_id   		= get_post_meta( $attendee_id, 'ea_order_id', true );

	$order      		= wc_get_order( $order_id );
	$order_meta 		= get_post_meta( $order_id );
	$first_name 		= isset( $order_meta['_billing_first_name'][0] ) ? $order_meta['_billing_first_name'][0] : '';
	$last_name  		= isset( $order_meta['_billing_last_name'][0] ) ? $order_meta['_billing_last_name'][0] : '';
	$billing_full_name  = $first_name . ' ' . $last_name;
	$uname 				= array_key_exists('user_name',$user_info) && !empty($user_info['user_name']) ? $user_info['user_name'] : $billing_full_name;
	

	if ( $type == 'user' ) {
		
		if ( empty($user_info['user_name']) && $attendee_name != $user_info['user_name'] && $order_id == $ea_order_id ) {						
			update_post_meta($attendee_id, 'ea_name', $uname);
			update_post_meta( $attendee_id, 'ea_event_date', $user_info['user_event_date'] );
			update_post_meta( $attendee_id, 'ea_ticket_type', $user_info['user_ticket_type'] );
			update_post_meta( $attendee_id, 'ea_event_id', $event_id );
			?>
            	<h5 class="mep-processing"><?php _e( 'Attendee Data successfully synchronized from order data. Please wait! this page is now reloading....', 'mep-form-builder' ); ?></h5>
			<?php
		}
	} elseif ( $type == 'billing' ) {

		if ( $attendee_name == $uname && $order_id == $ea_order_id ) {
			$ticket_type = stripslashes( $user_info['ticket_name'] );
			$event_date  = $user_info['event_date'];
			update_post_meta( $attendee_id, 'ea_event_date', $event_date );
			update_post_meta( $attendee_id, 'ea_ticket_type', $ticket_type );
			update_post_meta( $attendee_id, 'ea_event_id', $event_id );
			?>
            	<h5 class="mep-processing"><?php _e( 'Attendee Data successfully synchronized from order data. Please wait! this page is now reloading....', 'mep-form-builder' ); ?></h5>
			<?php
		}
	}
}

add_filter('mep_settings_general_arr','mep_fb_gen_settings_item');
function mep_fb_gen_settings_item($default_translation){
    $gen_settings = array( 
		array(
			'name' => 'mep_enable_same_attendee',
			'label' => __('Enable Same Attendee?', 'mage-eventpress'),
			'desc' => __('If you want to enable Same Attendee For reg form Select YES', 'mep-form-builder'),
			'type' => 'select',
			'default' => 'no',
			'options' =>  array(
				'yes' => 'Yes',
				'no' => 'No'
			)
		)  
    );
	return array_merge($default_translation,$gen_settings);	
}

add_action('mep_event_user_custom_styling','mep_fb_event_user_custom_styling');
function mep_fb_event_user_custom_styling(){
	$same_attendee_enable = mep_get_option( 'mep_enable_same_attendee', 'general_setting_sec', 'no');
	if($same_attendee_enable == 'yes'){
		?>
			label.mep_same_attendee {
				display: block!important;
			}
			.mep-user-info-sec h5 label {
				display: block!important;
			}		
		<?php
	}else{
		?>
			label.mep_same_attendee {
				display: none!important;
			}
			.mep-user-info-sec h5 label {
			   display: none!important;
			}		
		<?php
	}
}
function mep_fb_attendee_theme_query($order_id,$event_id,$ticket_type=''){
    $type_filter = !empty($ticket_type) ? array(
        'key'       => 'ea_ticket_type',
        'value'     => $ticket_type,
        'compare'   => '='          
      ) : '';
    $processing_status_filter =    array(
      'key'       => 'ea_order_status',
      'value'     => 'processing',
      'compare'   => '='
    );
    $completed_status_filter = array(
      'key'       => 'ea_order_status',
      'value'     => 'completed',
      'compare'   => '='
    );    
    $args = array(
      'post_type'       => 'mep_events_attendees',
      'posts_per_page'  => -1,
      'meta_query'      => array(    
        'relation'      => 'AND',
        array(    
          'relation'    => 'AND',   
                  
          array(
            'key'       => 'ea_event_id',
            'value'     => $event_id,
            'compare'   => '='
          ),		        
          array(
            'key'       => 'ea_order_id',
            'value'     => $order_id,
            'compare'   => '='
          ),
          $type_filter
          ),array(    
            'relation' => 'OR',           
            $processing_status_filter,
            $completed_status_filter
            )
        )            
    ); 
$loop = new WP_Query($args);
return $loop;
}
function mep_get_single_names($order_id,$event_id,$ticket_type,$meta_name,$type='data'){
	$q = mep_fb_attendee_theme_query($order_id,$event_id,$ticket_type);
	$_ticket_no = [];
	foreach($q->posts as $ticket){
		$tid = $ticket->ID;
		$name    =   get_post_meta($tid,$meta_name,true) ? get_post_meta($tid,$meta_name,true) : '';     
		if(!empty($name)){
			$_ticket_no[] = $name;
		}
	}
	$ticket_no = array_unique($_ticket_no);
  
	if(sizeof($ticket_no) > 0){
		if($type == 'data'){
			return implode(',',$ticket_no);
		}elseif($type == 'count'){
			return count($_ticket_no);
		}else{
			return implode(',',$ticket_no);
		}
	}else{
		return null;
	}
  }
  function mep_fb_get_single_ticket_number($order_id,$event_id,$ticket_type=''){    
    $q = mep_attendee_theme_query($order_id,$event_id,$ticket_type);
    $ticket_no = [];
    foreach($q->posts as $ticket){
        $tid = $ticket->ID;
        $user_id    =   get_post_meta($tid,'ea_user_id',true);
        $order_id    =   get_post_meta($tid,'ea_order_id',true);
        $event_id    =   get_post_meta($tid,'ea_event_id',true);        
        $ticket_no[] = $user_id.$order_id.$event_id.$tid;        
    }
    return implode(',',$ticket_no);
}  

function mep_fb_attendee_table_view($attendee_id){
	$values 			= get_post_custom($attendee_id);
	$order_id 			= get_post_meta($attendee_id,'ea_order_id',true) ? get_post_meta($attendee_id,'ea_order_id',true) : '';
	$event_id 			= get_post_meta($attendee_id,'ea_event_id',true) ? get_post_meta($attendee_id,'ea_event_id',true) : '';
	$ticket_type 		= get_post_meta($attendee_id,'ea_ticket_type',true) ? get_post_meta($attendee_id,'ea_ticket_type',true) : '';
	$template_mode      = mep_get_option('mep_pdf_template_mood', 'mep_pdf_gen_settings', 'individual');
	$ticket 			= $values['ea_user_id'][0].$values['ea_order_id'][0].$values['ea_event_id'][0].$attendee_id; 		
	$name 				= $template_mode == 'single' ? mep_get_single_names($order_id,$event_id,$ticket_type,'ea_name') : $values['ea_name'][0]; 
	$phone 				= $template_mode == 'single' ? mep_get_single_names($order_id,$event_id,$ticket_type,'ea_phone') : $values['ea_phone'][0]; 
	$email 				= $template_mode == 'single' ? mep_get_single_names($order_id,$event_id,$ticket_type,'ea_email') : $values['ea_email'][0]; 
	$address 			= $template_mode == 'single' ? mep_get_single_names($order_id,$event_id,$ticket_type,'ea_address_1') : $values['ea_address_1'][0]; 
	$desg 				= $template_mode == 'single' ? mep_get_single_names($order_id,$event_id,$ticket_type,'ea_desg') : $values['ea_desg'][0]; 
	$company 			= $template_mode == 'single' ? mep_get_single_names($order_id,$event_id,$ticket_type,'ea_company') : $values['ea_company'][0]; 
	$website 			= $template_mode == 'single' ? mep_get_single_names($order_id,$event_id,$ticket_type,'ea_website') : $values['ea_website'][0]; 
	$gender 			= $template_mode == 'single' ? mep_get_single_names($order_id,$event_id,$ticket_type,'ea_gender') : $values['ea_gender'][0]; 
	$veg 				= $template_mode == 'single' ? mep_get_single_names($order_id,$event_id,$ticket_type,'ea_vegetarian') : $values['ea_vegetarian'][0]; 
	$tsize 				= $template_mode == 'single' ? mep_get_single_names($order_id,$event_id,$ticket_type,'ea_tshirtsize') : $values['ea_tshirtsize'][0]; 
	$tno 				= $template_mode == 'single' ? mep_fb_get_single_ticket_number($order_id,$event_id,$ticket_type) : $ticket; 
	
	ob_start(); 



?>
<table>
			<tr>
				<td colspan="2" align="center">
					<center>
					<?php echo get_avatar( $values['ea_email'][0], 128 ); ?>
					<h2><?php echo $name; ?></h2>
					<?php do_action('mep_qr_code_checkin_btn',$values['ea_user_id'][0],$attendee_id); ?>
					<h4><?php echo get_the_title($values['ea_event_id'][0]); ?></h4>
				</center>
				</td>
			</tr>
			<?php do_action('mep_attendee_table_row_start',get_the_id()); if($template_mode != 'single'){ ?>			
			<tr>
				<td><?php _e('Ticket No','mage-eventpress'); ?></td>
				<td><?php echo $tno; ?></td>
			</tr>
			<?php }else{ ?>					
				<tr>
							<td><?php _e('No of Ticket','mage-eventpress'); ?></td>
							<td><?php echo mep_get_single_names($order_id,$event_id,$ticket_type,'ea_name','count'); ?></td>
						</tr>
			<?php } ?>
			<tr>
				<td><?php _e('Date','mage-eventpress'); ?></td>
				<td><?php echo get_mep_datetime($values['ea_event_date'][0],'date-time-text'); ?></td>
			</tr>			
			<tr>
				<td><?php _e('Order ID','mage-eventpress'); ?></td>
				<td><?php echo $values['ea_order_id'][0]; ?></td>
			</tr>			
			<?php if($email){ ?>
			<tr>
				<td><?php echo mep_get_reg_label($event_id,'Email'); ?></td>
				<td><?php echo $email; ?></td>
			</tr>
			<?php } if($phone){ ?>
			<tr>
				<td><?php echo mep_get_reg_label($event_id,'Phone'); ?></td>
				<td><?php echo $phone; ?></td>
			</tr>
			<?php } if($address){ ?>
			<tr>
				<td><?php echo mep_get_reg_label($event_id,'Address'); ?></td>
				<td><?php echo $address; ?> </td>
			</tr>
			<?php } if($desg){ ?>
			<tr>
				<td><?php echo mep_get_reg_label($event_id,'Designation'); ?></td>
				<td><?php echo $desg; ?></td>
			</tr>
			<?php } if($company){ ?>
			<tr>
				<td><?php echo mep_get_reg_label($event_id,'Company'); ?></td>
				<td><?php echo $company; ?></td>
			</tr>
			<?php } if($website){ ?>
			<tr>
				<td><?php echo mep_get_reg_label($event_id,'Website'); ?></td>
				<td><?php echo $website; ?> </td>
			</tr>
			<?php } if($gender){ ?>
			<tr>
				<td><?php echo mep_get_reg_label($event_id,'Gender'); ?></td>
				<td><?php echo $gender; ?> </td>
			</tr>

			<?php } if($veg){ ?>
			<tr>
				<td><?php echo mep_get_reg_label($event_id,'Vegetarian'); ?></td>
				<td><?php echo $veg; ?> </td>
			</tr>	
		

			<?php } if($tsize){ ?>
			<tr>
				<td><?php echo mep_get_reg_label($event_id,'T-Shirt Size'); ?></td>
				<td><?php echo $tsize; ?> </td>
			</tr>		
			<?php } if($values['ea_ticket_type'][0]){ ?>
			<tr>
				<td><?php _e('Ticket Type','mage-eventpress'); ?></td>
				<td><?php echo $values['ea_ticket_type'][0]; ?> </td>
			</tr>	
			<?php } 






$mep_form_builder_data = $template_mode == 'single' ? get_post_meta(mep_fb_get_reg_form_id($event_id), 'mep_form_builder_data', true) : get_post_meta(mep_fb_get_reg_form_id($values['ea_event_id'][0]), 'mep_form_builder_data', true);
             
            
            
if ( $mep_form_builder_data ) {
	foreach ( $mep_form_builder_data as $_field ) {        
   	$vname = "ea_".$_field['mep_fbc_id']; 
	$vals = $values[$vname][0];

?>
			<tr>
				<td><?php echo $_field['mep_fbc_label']; ?></td>
				<td><?php echo $template_mode == 'single' ? mep_pdf_get_single_names($order_id,$event_id,$ticket_type,$vname) : $vals; ?></td>
			</tr>
	
<?php
}
}

			do_action('mep_attendee_table_row_end',$attendee_id);
			?>
		</table>
<?php
return ob_get_clean();
}




add_action('mep_attendee_details_page','mep_fb_attendee_details');
function mep_fb_attendee_details($attendee_id){		
	$order_id 		= get_post_meta($attendee_id,'ea_order_id',true) ? get_post_meta($attendee_id,'ea_order_id',true) : '';
	$template_mode   = mep_get_option('mep_pdf_template_mood', 'mep_pdf_gen_settings', 'individual');
	echo mep_fb_attendee_table_view($attendee_id);

	// if($template_mode == 'single'){	
	// 	$args   =   array(
	// 		'posts_per_page'   => -1,
	// 		'post_type'     => 'mep_events_attendees',
	// 		'meta_query'    => array(
	// 			array(
	// 			'key'       => 'ea_order_id',
	// 			'value'     => $order_id,
	// 			'compare'   => '=',
	// 			)
	// 		)
	// 	);
	// 	$loop = new WP_Query($args);

	// 	echo '<h3 align="center">'.$loop->post_count.' Attendees</h3>';
	// 	foreach($loop->posts as $attendee){
	// 		$attendee_id = $attendee->ID;
	// 		echo mep_fb_attendee_table_view($attendee_id);
	// 	}
	// }else{
		
	// }
}



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



add_filter( 'default_content', 'wpse57907_default_content', 10, 2 );
function wpse57907_default_content( $content, $post ) {
    if ( 'mep_waitlist_email' == $post->post_type )
        $content = mep_fb_default_email_template_text();
    return $content;
}


function mep_fb_default_email_template_text(){
	ob_start();
?>
<style> @media only screen and (max-width: 620px) { table.body h1 { font-size: 28px !important; margin-bottom: 10px !important;}
  table.body p,table.body ul,table.body ol,table.body td,table.body span,table.body a { font-size: 16px !important;}
  table.body .wrapper,table.body .article { padding: 10px !important;}
  table.body .content { padding: 0 !important; }
  table.body .container { padding: 0 !important; width: 100% !important; }
  table.body .main { border-left-width: 0 !important; border-radius: 0 !important; border-right-width: 0 !important; }
  table.body .btn table { width: 100% !important; }
  table.body .btn a { width: 100% !important; }
  table.body .img-responsive { height: auto !important; max-width: 100% !important; width: auto !important; } }
@media all { .ExternalClass { width: 100%; }
  .ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div { line-height: 100%;}
  .apple-link a { color: inherit !important;font-family: inherit !important; font-size: inherit !important; font-weight: inherit !important line-height: inherit !important; text-decoration: none !important; }
  #MessageViewBody a { color: inherit; text-decoration: none; font-size: inherit; font-family: inherit; font-weight: inherit; line-height:inherit;}
  .btn-primary table td:hover {background-color: #34495e !important;}
  .btn-primary a:hover { background-color: #34495e !important; border-color: #34495e !important;}}</style>
  <table class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f6f6f6; width: 100%;" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#f6f6f6"><tbody><tr><td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top"></td><td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; max-width: 580px; padding: 10px; width: 580px; margin: 0 auto;" valign="top" width="580"><div class="content" style="box-sizing: border-box; display: block; margin: 0 auto; max-width: 580px; padding: 10px;"><table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background: #ffffff; border-radius: 3px; width: 100%;" role="presentation" width="100%"><tbody><tr><td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;" valign="top"><table style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top"><p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;"><strong>Hi {name},</strong></p><p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">This is a sample email notification template, Please edit this text before you send it to the customer. The user has registered for this event.</p><strong>Event Name</strong>: {event}<br/><strong>Event Date</strong>: {event_date}<br/><table class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; width: 100%;" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding-bottom: 15px;" align="left" valign="top"><table class=" aligncenter" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;" role="presentation" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td style="font-family: sans-serif; font-size: 14px; vertical-align: top; border-radius: 5px; background-color: #3498db;" align="center" valign="top" bgcolor="#3498db"><a style="border: solid 1px #3498db; border-radius: 5px; box-sizing: border-box; cursor: pointer; display: inline-block; font-size: 14px; font-weight: bold; margin: 30 auto; padding: 12px 25px; text-decoration: none; text-transform: capitalize; background-color: #3498db; border-color: #3498db; color: #ffffff; width: 200px;" href="http://yourwebsite.com" target="_blank" rel="noopener">Call To Action</a></td></tr></tbody></table></td></tr></tbody></table><p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">This is a really simple email template. Its sole purpose is to get the recipient to click the button with no distractions.</p><p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Good luck! Hope it works.</p></td></tr></tbody></table></td></tr></tbody></table><div class="footer" style="clear: both; margin-top: 10px; text-align: center; width: 100%;"><table style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td class="content-block" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; color: #999999; font-size: 12px; text-align: center;" align="center" valign="top"><span class="apple-link" style="color: #999999; font-size: 12px; text-align: center;">Company Inc, 3 Abbey Road, San Francisco CA 94102</span><br/>Phone: 000 111 22 3333</td></tr><tr><td class="content-block powered-by" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; color: #999999; font-size: 12px; text-align: center;" align="center" valign="top">Your Company Name</td></tr></tbody></table></div></div></td><td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top"></td></tr></tbody></table>
<?php
	return ob_get_clean();
}