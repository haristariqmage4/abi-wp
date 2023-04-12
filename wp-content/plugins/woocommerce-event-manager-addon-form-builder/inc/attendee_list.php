<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

// Attendee list show Setting
//add_action( 'add_meta_boxes', 'mep_event_attendee_list_is_show' );
//function mep_event_attendee_list_is_show() {
//
//	add_meta_box( 'mep-event-attendee-list-is-show', __( 'Show Attendee list?', 'mep-form-builder' ), 'mep_event_attendee_list_show_cb', 'mep_events', 'side', 'low' );
//}

add_action( 'mp_event_switching_button_hook', 'mep_event_attendee_list_show_cb', 10, 1 );
function mep_event_attendee_list_show_cb( $post_id ) {
	$values      = get_post_custom( $post_id );
	$reg_checked = '';
	if ( array_key_exists( 'mep_event_show_attendee_list', $values ) ) {
		if ( $values['mep_event_show_attendee_list'][0] == 'on' ) {
			$reg_checked = 'checked';
		}
	} else {
		$reg_checked = 'checked';
	}
	?>
    <tr>
        <th><span><?php _e( 'Show Attendee list?', 'mep-form-builder' ); ?></span></th>
        <td colspan="3">
            <label>
                <input class="mp_opacity_zero" type="checkbox" name="mep_event_show_attendee_list" <?php echo $reg_checked; ?> />
                <span class="slider round"></span>
            </label>
        </td>
    </tr>
	<?php
}

add_action( 'save_post', 'mep_attendee_list_show_status_meta_save' );
function mep_attendee_list_show_status_meta_save( $post_id ) {
	if ( isset( $_POST['mep_event_show_attendee_list'] ) ) {
		$mep_attendee_list_show_status = strip_tags( $_POST['mep_event_show_attendee_list'] );
	} else {
		$mep_attendee_list_show_status = 'off';
	}
	$update_seat = update_post_meta( $post_id, 'mep_event_show_attendee_list', $mep_attendee_list_show_status );
}

// Attendee list show Setting END

add_action( 'after-single-events', 'mep_event_attendee_list', 10 );
if ( ! function_exists( 'mep_event_attendee_list' ) ) {
	function mep_event_attendee_list() {

		global $post;
		$event_id              = get_the_ID();
		$is_show_attendee_list = get_post_meta( $event_id, 'mep_event_show_attendee_list', true );

		if ( $is_show_attendee_list && $is_show_attendee_list == 'on' ) {
			$ea_event_date = get_post_meta( $event_id, 'event_start_date', true ) . ' ' . get_post_meta( $event_id, 'event_start_time', true );

			if ( isset( $event_id ) ) {
				$event_id      = $event_id;
				$ea_event_date = $ea_event_date;
				$event_date    = date( 'Y-m-d', strtotime( $ea_event_date ) );
			}

			$args = array(
				'post_type'      => array( 'mep_events_attendees' ),
				'posts_per_page' => - 1,
				'meta_query'     => array(
					'relation' => 'AND',
					array(
						array(
							'key'     => 'ea_event_id',
							'value'   => $event_id,
							'compare' => '='
						),
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
						),
					)
				)

			);

			$attendee_lists = new WP_Query( $args );
			$total_attendee = $attendee_lists->post_count;

			?>

            <div class="mep-event-attendee--list">
                <div class="mep-event-attendee-list--header">
                    <h3 class="header-tag"><?php _e( 'Attendee', 'mep-form-builder' ); ?> <span class="attendee-count">(<?php echo $total_attendee; ?>)</span></h3>
					<?php
					if ( $total_attendee > 1 ) { ?>
                        <button id="attendee--see-all"><?php _e( 'See all', 'mep-form-builder' ) ?></button>
						<?php
					}
					?>
                </div>
                <div class="mep-event-attendee--inner">
					<?php
					if ( $attendee_lists ) :

						while ( $attendee_lists->have_posts() ) : $attendee_lists->the_post(); ?>

                            <div class="mep-event-attendee--item">
								<?php
								$user_id             = get_post_meta( get_the_ID(), 'ea_user_id', true );
								$user_name           = get_post_meta( get_the_ID(), 'ea_name', true );
								$get_author_gravatar = get_avatar_url( $user_id, array( 'size' => 64 ) );
								?>
                                <img class="attendee_image" src="<?php echo $get_author_gravatar; ?>" alt="<?php echo $user_name; ?>"/>
                                <p class="attendee_name"><?php echo $user_name; ?></p>
                            </div>

						<?php

						endwhile;
					endif;
					?>
                </div>
            </div>

			<?php
			wp_reset_query();
		}
	}
}