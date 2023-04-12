<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

function mfb_register_my_session() {
	if ( ! session_id() ) {
		session_start();
	}
}

// add_action('init', 'mfb_register_my_session');

// Woocommerce My account dashboard use action hook for attendee edit
function mfb_attendee_th() {
	echo '<th>' . __( 'Action', 'mep-form-builder' ) . '</th>';
}

add_action( 'mep_user_order_list_table_head', 'mfb_attendee_th' );

function mfb_attendee_td( $id ) {
	$event_id       = get_post_meta( $id, 'ea_event_id', true );
	$is_edit_enable = get_post_meta( $event_id, 'mep_event_attendee_edit_frontend', true );
	if ( $is_edit_enable === 'on' ) {
		echo '<td><a href="' . mfb_endpoint_url() . $id . '">' . __( 'Edit', 'mep-form-builder' ) . '</a></td>';
	} else {
		echo '<td style="text-decoration: line-through;color:#ddd">' . __( 'Edit', 'mep-form-builder' ) . '</td>';
	}
}

add_action( 'mep_user_order_list_table_row', 'mfb_attendee_td' );

function mfb_endpoint_url() {
	// $url = rtrim(wc_get_endpoint_url('attendee-edit', '?id='), '/');
	$url = get_home_url() . '/my-account/attendee-edit/?id=';

	return $url;
}

// Woocommerce My account page ************************************************

add_action( 'init', 'mfb_add_endpoint' );
function mfb_add_endpoint() {

	// WP_Rewrite is my Achilles' heel, so please do not ask me for detailed explanation
	add_rewrite_endpoint( 'attendee-edit', EP_PAGES );

}

function mfb_query_vars( $vars ) {
	$vars[] = 'attendee-edit';

	return $vars;
}

add_filter( 'query_vars', 'mfb_query_vars', 0 );

// function mfb_insert_after_helper( $items, $new_items, $after ) {
// 	// Search for the item position and +1 since is after the selected item key.
// 	$position = array_search( $after, array_keys( $items ) ) + 1;

// 	// Insert the new item.
// 	$array = array_slice( $items, 0, $position, true );
// 	$array += $new_items;
// 	$array += array_slice( $items, $position, count( $items ) - $position, true );

//     return $array;
// }

/**
 * Insert the new endpoint into the My Account menu.
 *
 * @param array $items
 *
 * @return array
 */
// function mfb_event_menu_items( $items ) {
// 	$new_items = array();
// 	$new_items['attendee-edit'] = __( 'Attedee Edit', 'mep-form-builder' );

// 	// Add the new item after `orders`.
// 	return mfb_insert_after_helper( $items, $new_items, 'event-organizer' );
// }
// add_filter( 'woocommerce_account_menu_items', 'mfb_event_menu_items' );

// Content
function mfb_endpoint_content() {
	$a_id        = $_GET['id'];
	$event_id    = get_post_meta( $a_id, 'ea_event_id', true );
	$tshirt_size = get_post_meta( $event_id, 'mep_reg_tshirtsize_list', true );
	$name        = get_post_meta( $a_id, 'ea_name', true );


	if ( isset( $_POST['mfd_attendee_edit'] ) && isset( $_POST['mfd_attendee_edit_nonce_field'] ) && wp_verify_nonce( $_POST['mfd_attendee_edit_nonce_field'], 'mfd_attendee_edit_nonce_field_action' ) && $_SERVER['REQUEST_METHOD'] === 'POST' ) {
		if ( isset( $_POST['ea_name'] ) ) {
			update_post_meta( $a_id, 'ea_name', sanitize_text_field( $_POST['ea_name'] ) );
		}

		if ( isset( $_POST['ea_email'] ) ) {
			update_post_meta( $a_id, 'ea_email', sanitize_email( $_POST['ea_email'] ) );
		}

		if ( isset( $_POST['ea_phone'] ) ) {
			update_post_meta( $a_id, 'ea_phone', $_POST['ea_phone'] );
		}

		if ( isset( $_POST['ea_address_1'] ) ) {
			update_post_meta( $a_id, 'ea_address_1', $_POST['ea_address_1'] );
		}

		if ( isset( $_POST['ea_company'] ) ) {
			update_post_meta( $a_id, 'ea_company', sanitize_text_field( $_POST['ea_company'] ) );
		}

		if ( isset( $_POST['ea_desg'] ) ) {
			update_post_meta( $a_id, 'ea_desg', sanitize_text_field( $_POST['ea_desg'] ) );
		}

		if ( isset( $_POST['ea_website'] ) ) {
			update_post_meta( $a_id, 'ea_website', sanitize_text_field( $_POST['ea_website'] ) );
		}

		if ( isset( $_POST['ea_vegetarian'] ) ) {
			update_post_meta( $a_id, 'ea_vegetarian', sanitize_text_field( $_POST['ea_vegetarian'] ) );
		}

		if ( isset( $_POST['ea_gender'] ) ) {
			update_post_meta( $a_id, 'ea_gender', $_POST['ea_gender'] );
		}

		if ( isset( $_POST['ea_tshirtsize'] ) ) {
			update_post_meta( $a_id, 'ea_tshirtsize', $_POST['ea_tshirtsize'] );
		}


		$_SESSION['mfd_attendee_edit_msg'] = 'Updated Successfully';

		echo '
		<script>
			if ( window.history.replaceState ) {
			window.history.replaceState( null, null, window.location.href );
			}
		</script>
    ';
	}

	if ( isset( $_SESSION['mfd_attendee_edit_msg'] ) ) {
		echo '<p class="mefs-notification">' . $_SESSION['mfd_attendee_edit_msg'] . '</p>';
		unset( $_SESSION['mfd_attendee_edit_msg'] );
	}
	?>

    <form action="" method="POST">
		<?php wp_nonce_field( 'mfd_attendee_edit_nonce_field_action', 'mfd_attendee_edit_nonce_field' ); ?>
        <div class="mfb-form-inner">
			<?php if ( get_post_meta( $a_id, 'ea_name', true ) ) : ?>
                <div class="mfb-form-group">
                    <label for="mfb_ea_name"><?php _e( 'Fullname', 'mep-form-builder' ) ?></label>
                    <input type="text" name="ea_name" placeholder="<?php _e( 'Fullname', 'mep-form-builder' ) ?>" value="<?php echo get_post_meta( $a_id, 'ea_name', true ) ?>">
                </div>
			<?php endif; ?>

			<?php if ( get_post_meta( $a_id, 'ea_email', true ) ) : ?>
                <div class="mfb-form-group">
                    <label for="mfb_ea_email"><?php _e( 'Email', 'mep-form-builder' ) ?></label>
                    <input type="email" name="ea_email" placeholder="<?php _e( 'Email', 'mep-form-builder' ) ?>" value="<?php echo get_post_meta( $a_id, 'ea_email', true ) ?>">
                </div>
			<?php endif; ?>

			<?php if ( get_post_meta( $a_id, 'ea_phone', true ) ) : ?>
                <div class="mfb-form-group">
                    <label for="mfb_ea_phone"><?php _e( 'Phone', 'mep-form-builder' ) ?></label>
                    <input type="text" name="ea_phone" placeholder="<?php _e( 'Phone', 'mep-form-builder' ) ?>" value="<?php echo get_post_meta( $a_id, 'ea_phone', true ) ?>">
                </div>
			<?php endif; ?>

			<?php if ( get_post_meta( $a_id, 'ea_address_1', true ) ) : ?>
                <div class="mfb-form-group">
                    <label for="mfb_ea_address_1"><?php _e( 'Address', 'mep-form-builder' ) ?></label>
                    <input type="text" name="ea_address_1" placeholder="<?php _e( 'Address', 'mep-form-builder' ) ?>" value="<?php echo get_post_meta( $a_id, 'ea_address_1', true ) ?>">
                </div>
			<?php endif; ?>

			<?php if ( get_post_meta( $a_id, 'ea_company', true ) ) : ?>
                <div class="mfb-form-group">
                    <label for="mfb_ea_company"><?php _e( 'Company', 'mep-form-builder' ) ?></label>
                    <input type="text" name="ea_company" placeholder="<?php _e( 'Company', 'mep-form-builder' ) ?>" value="<?php echo get_post_meta( $a_id, 'ea_company', true ) ?>">
                </div>
			<?php endif; ?>

			<?php if ( get_post_meta( $a_id, 'ea_desg', true ) ) : ?>
                <div class="mfb-form-group">
                    <label for="mfb_ea_desg"><?php _e( 'Designation', 'mep-form-builder' ) ?></label>
                    <input type="text" name="ea_desg" placeholder="<?php _e( 'Designation', 'mep-form-builder' ) ?>" value="<?php echo get_post_meta( $a_id, 'ea_desg', true ) ?>">
                </div>
			<?php endif; ?>

			<?php if ( get_post_meta( $a_id, 'ea_website', true ) ) : ?>
                <div class="mfb-form-group">
                    <label for="mfb_ea_website"><?php _e( 'Website', 'mep-form-builder' ) ?></label>
                    <input type="text" name="ea_website" placeholder="<?php _e( 'Website', 'mep-form-builder' ) ?>" value="<?php echo get_post_meta( $a_id, 'ea_website', true ) ?>">
                </div>
			<?php endif; ?>

			<?php if ( get_post_meta( $a_id, 'ea_vegetarian', true ) ) : ?>
                <div class="mfb-form-group">
                    <label for="mfb_ea_vegetarian"><?php _e( 'Vegetarian', 'mep-form-builder' ) ?></label>
                    <select name="ea_vegetarian" id="ea_vegetarian">
						<?php
						$veg = get_post_meta( $a_id, 'ea_vegetarian', true );
						?>
                        <option value="">Please Select</option>
                        <option value="Yes" <?php echo( ( 'Yes' == $veg ) ? 'selected' : '' ) ?>><?php _e( 'Yes', 'mep-form-builder' ) ?></option>
                        <option value="No" <?php echo( ( 'No' == $veg ) ? 'selected' : '' ) ?>><?php _e( 'No', 'mep-form-builder' ) ?></option>
                    </select>
                </div>
			<?php endif; ?>

			<?php if ( get_post_meta( $a_id, 'ea_gender', true ) ) : ?>
                <div class="mfb-form-group">
                    <label for="mfb_ea_gender"><?php _e( 'Gender', 'mep-form-builder' ) ?></label>
                    <select name="ea_gender" id="ea_gender">
						<?php
						$gender = get_post_meta( $a_id, 'ea_gender', true );
						?>
                        <option value="">Please Select</option>
                        <option value="Male" <?php echo( ( 'Male' == $gender ) ? 'selected' : '' ) ?>><?php _e( 'Male', 'mep-form-builder' ) ?></option>
                        <option value="Female" <?php echo( ( 'Female' == $gender ) ? 'selected' : '' ) ?>><?php _e( 'Female', 'mep-form-builder' ) ?></option>
                    </select>
                </div>
			<?php endif; ?>

			<?php if ( get_post_meta( $a_id, 'ea_tshirtsize', true ) ) : ?>
                <div class="mfb-form-group">
                    <label for="mfb_ea_tshirtsize"><?php _e( 'Tshirt size', 'mep-form-builder' ) ?></label>

                    <select name="ea_tshirtsize" id="ea_tshirtsize">
						<?php
						$old_size = get_post_meta( $a_id, 'ea_tshirtsize', true );
						?>
                        <option value="">Please Select</option>
						<?php
						if ( $tshirt_size ) {
							$tshirt_size = explode( ',', $tshirt_size );
							foreach ( $tshirt_size as $item ) {
								echo '<option value="' . $item . '" ' . ( ( $item == $old_size ) ? ' selected' : '' ) . '>' . __( $item, "mep-form-builder" ) . '</option>';
							}
						} ?>
                    </select>
                </div>
			<?php endif; ?>

            <div class="mfb-form-group full-row">
                <input type="submit" value="<?php _e( 'Update', 'mep-form-builder' ); ?>" name="mfd_attendee_edit">
            </div>
        </div>
    </form>

	<?php
}

add_action( 'woocommerce_account_attendee-edit_endpoint', 'mfb_endpoint_content' );


function mfb_event_endpoint_title( $title ) {
	global $wp_query;

	$is_endpoint = isset( $wp_query->query_vars['attendee-edit'] );

	if ( $is_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
		// New page title.
		$title = __( 'Attendee Edit', 'mep-form-builder' );

		remove_filter( 'the_title', 'mfb_event_endpoint_title' );
	}

	return $title;

}

add_filter( 'the_title', 'mfb_event_endpoint_title' );


// Attendee edit enable/disable Setting
//add_action( 'add_meta_boxes', 'mfb_event_attendee_is_edit_frontend' );
//function mfb_event_attendee_is_edit_frontend() {
//
//	add_meta_box( 'mep-event-attendee-list-is-edit', __( 'Enable Attendee information edit?', 'mep-form-builder' ), 'mep_event_attendee_edit_frontend_show_cb', 'mep_events', 'side', 'low' );
//}

add_action( 'mp_event_switching_button_hook', 'mep_event_attendee_edit_frontend_show_cb', 10, 1 );
function mep_event_attendee_edit_frontend_show_cb( $post_id ) {
	$values      = get_post_custom( $post_id );
	$reg_checked = '';
	if ( array_key_exists( 'mep_event_attendee_edit_frontend', $values ) ) {
		if ( $values['mep_event_attendee_edit_frontend'][0] == 'on' ) {
			$reg_checked = 'checked';
		}
	} else {
		$reg_checked = 'checked';
	}
	?>
    <tr>
        <th><span><?php _e( 'Enable Attendee information edit?', 'mep-form-builder' ); ?></span></th>
        <td colspan="3">
            <label>
                <input class="mp_opacity_zero" type="checkbox" name="mep_event_attendee_edit_frontend" <?php echo $reg_checked; ?> />
                <span class="slider round"></span>

            </label>
            <p class="event_meta_help_txt"><?php _e( 'If enable, Attendee can be edited from the frontend', 'mep-form-builder' ) ?></p>
        </td>
    </tr>
	<?php
}

add_action( 'save_post', 'mep_attendee_edit_frontend_status_meta_save' );
function mep_attendee_edit_frontend_status_meta_save( $post_id ) {
	if ( isset( $_POST['mep_event_attendee_edit_frontend'] ) ) {
		$mep_attendee_edit_frontend_status = strip_tags( $_POST['mep_event_attendee_edit_frontend'] );
	} else {
		$mep_attendee_edit_frontend_status = 'off';
	}
	update_post_meta( $post_id, 'mep_event_attendee_edit_frontend', $mep_attendee_edit_frontend_status );
}

// Attendee edit enable/disable Setting END

function mfb_check_submit_value( $post_, $post_meta_key ) {
	if ( isset( $post ) && ( get_post_meta( $post->ID, $post_meta_key, true ) != null ) ) {
		return get_post_meta( $post->ID, $post_meta_key, true );
	}
}