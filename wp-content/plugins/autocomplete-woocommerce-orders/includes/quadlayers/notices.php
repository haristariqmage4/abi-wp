<?php

class ACO_Notices {

	protected static $_instance;

	public function __construct() {
		add_action( 'wp_ajax_aco_dismiss_notice', array( $this, 'ajax_dismiss_notice' ) );
		add_action( 'admin_notices', array( $this, 'add_notices' ) );
		register_activation_hook( ACO_PLUGIN_FILE, array( $this, 'add_transient' ) );
	}

	function ajax_dismiss_notice() {
		if ( check_admin_referer( 'aco_dismiss_notice', 'nonce' ) && isset( $_REQUEST['notice_id'] ) ) {

			$notice_id = sanitize_key( $_REQUEST['notice_id'] );

			update_user_meta( get_current_user_id(), $notice_id, true );
			set_transient( 'aco-notice-delay', true, MONTH_IN_SECONDS );

			wp_send_json( $notice_id );
		}

		wp_die();
	}

	function add_transient() {
		set_transient( 'aco-notice-delay', true, MONTH_IN_SECONDS );
	}

	function add_notices() {

		$transient = get_transient( 'aco-notice-delay' );

		if ( $transient ) {
			return;
		}

		?>
		<script>
			(function($) {
				$(document).ready(()=> {
					$('.aco-notice').on('click', '.notice-dismiss', function(e) {
						e.preventDefault();
						var notice_id = $(e.delegateTarget).data('notice_id');
						$.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {
							notice_id: notice_id,
							action: 'aco_dismiss_notice',
							nonce: '<?php echo esc_attr( wp_create_nonce( 'aco_dismiss_notice' ) ); ?>'
						},
							success: function(response) {
							console.log(response);
						},
						});
					});
				})
			})(jQuery);
		</script>
		<?php

		$plugin_slug = ACO_PREMIUM_SELL_SLUG;

		$user_rating     = ! get_user_meta( get_current_user_id(), 'aco-user-rating', true );
		$user_premium    = ! get_user_meta( get_current_user_id(), 'aco-user-premium', true ) && ! $this->is_installed( "{$plugin_slug}/{$plugin_slug}.php" );
		$user_cross_sell = ! get_user_meta( get_current_user_id(), 'aco-user-cross-sell', true );

		if ( $user_rating ) {
			?>
			<div id="aco-admin-rating" class="aco-notice notice notice-info is-dismissible" data-notice_id="aco-user-rating">
				<div class="notice-container" style="padding-top: 10px; padding-bottom: 10px; display: flex; justify-content: left; align-items: center;">
					<div class="notice-image">
						<img style="border-radius:50%;max-width: 90px;" src="<?php echo plugins_url( '/assets/backend/img/logo.jpg', ACO_PLUGIN_FILE ); ?>" alt="<?php echo esc_html( ACO_PLUGIN_NAME ); ?>>">
					</div>
					<div class="notice-content" style="margin-left: 15px;">
						<p>
							<?php printf( esc_html__( 'Hello! Thank you for choosing the %s plugin!', 'autocomplete-woocommerce-orders' ), ACO_PLUGIN_NAME ); ?>
							<br/>
							<?php esc_html_e( 'Could you please give it a 5-star rating on WordPress?. Your feedback will boost our motivation and help us promote and continue to improve this product.', 'autocomplete-woocommerce-orders' ); ?>
						</p>
						<a href="<?php echo esc_url( ACO_REVIEW_URL ); ?>" class="button-primary" target="_blank">
							<?php esc_html_e( 'Yes, of course!', 'autocomplete-woocommerce-orders' ); ?>
						</a>
						<a href="<?php echo esc_url( ACO_SUPPORT_URL ); ?>" class="button-secondary" target="_blank">
							<?php esc_html_e( 'Report a bug', 'autocomplete-woocommerce-orders' ); ?>
						</a>
					</div>
				</div>
			</div>
			<?php
			return;
		}

		if ( ! $user_rating && $user_premium ) {
			?>
			<div class="aco-notice notice notice-info is-dismissible" data-notice_id="aco-user-premium">
				<div class="notice-container" style="padding-top: 10px; padding-bottom: 10px; display: flex; justify-content: left; align-items: center;">
					<div class="notice-image">
						<img style="border-radius:50%;max-width: 90px;" src="<?php echo esc_url( plugins_url( '/assets/backend/img/logo.jpg', ACO_PLUGIN_FILE ) ); ?>" alt="<?php echo esc_html( ACO_PLUGIN_NAME ); ?>>">
					</div>
					<div class="notice-content" style="margin-left: 15px;">
						<p>
						<?php esc_html_e( 'Hello! We have a special gift!', 'autocomplete-woocommerce-orders' ); ?>
							<br />
						<?php
						printf(
							esc_html__( 'Today we want to make you a special gift. Using this coupon before the next 48 hours you can get a 20 percent discount on the premium version of the %s plugin.', 'autocomplete-woocommerce-orders' ),
							esc_html( ACO_PREMIUM_SELL_NAME )
						)
						?>
						</p>
						<a href="<?php echo esc_url( ACO_PREMIUM_SELL_URL ); ?>" class="button-primary" target="_blank">
							<?php esc_html_e( 'More info', 'autocomplete-woocommerce-orders' ); ?>
						</a>
						<input style="width:130px" type="text" value="ADMINPANEL20%"/>
					</div>
				</div>
			</div>
			<?php
			return;
		}

		if ( ! $user_rating && ! $user_premium && $user_cross_sell ) {

			$cross_sell = $this->get_cross_sell();

			if ( empty( $cross_sell ) ) {
				return;
			}

			list($action, $action_link) = $cross_sell;

			?>
			<div class="aco-notice notice notice-info is-dismissible" data-notice_id="aco-user-cross-sell">
				<div class="notice-container" style="padding-top: 10px; padding-bottom: 10px; display: flex; justify-content: left; align-items: center;">
					<div class="notice-image">
						<img style="border-radius:50%;max-width: 90px;" src="<?php echo plugins_url( '/assets/backend/img/logo.jpg', ACO_PLUGIN_FILE ); ?>" alt="<?php echo esc_html( ACO_PLUGIN_NAME ); ?>>">
					</div>
					<div class="notice-content" style="margin-left: 15px;">
						<p>
						<?php printf( esc_html__( 'Hello! We want to invite you to try our %s plugin!', 'autocomplete-woocommerce-orders' ), esc_html( ACO_CROSS_INSTALL_NAME ) ); ?>
							<br/>
						<?php echo esc_html( ACO_CROSS_INSTALL_DESCRIPTION ); ?>
						</p>
						<a href="<?php echo esc_url( $action_link ); ?>" class="button-primary">
						<?php echo esc_html( $action ); ?>
						</a>
						<a href="<?php echo esc_url( ACO_CROSS_INSTALL_URL ); ?>" class="button-secondary" target="_blank">
						<?php esc_html_e( 'More info', 'autocomplete-woocommerce-orders' ); ?>
						</a>
					</div>
				</div>
			</div>
			<?php
			return;
		}

	}

	function get_cross_sell() {

		$screen = get_current_screen();

		if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
			return array();
		}

		$plugin_slug = ACO_CROSS_INSTALL_SLUG;

		$plugin_file = "{$plugin_slug}/{$plugin_slug}.php";

		if ( is_plugin_active( $plugin_file ) ) {
			return array();
		}

		if ( $this->is_installed( $plugin_file ) ) {

			if ( ! current_user_can( 'activate_plugins' ) ) {
				return array();
			}

			return array(
				esc_html__( 'Activate', 'autocomplete-woocommerce-orders' ),
				wp_nonce_url( "plugins.php?action=activate&amp;plugin={$plugin_file}&amp;plugin_status=all&amp;paged=1", "activate-plugin_{$plugin_file}" ),
			);

		}

		if ( ! current_user_can( 'install_plugins' ) ) {
			return array();
		}

		return array(
			esc_html__( 'Install', 'autocomplete-woocommerce-orders' ),
			wp_nonce_url( self_admin_url( "update.php?action=install-plugin&plugin={$plugin_slug}" ), "install-plugin_{$plugin_slug}" ),
		);

	}

	function is_installed( $path ) {

		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $path ] );
	}

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}

ACO_Notices::instance();
