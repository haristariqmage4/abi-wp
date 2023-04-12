<?php
/*
Plugin Name: ELEX WooCommerce Name Your Price 
Plugin URI: https://elextensions.com/plugin/woocommerce-catalog-mode-wholesale-role-based-pricing/
Description: The plugin allows the customer to give his own price and proceed with the checkout. You can easily set the minimum price for your woocommerce products both globally and individually.
Author: ELEXtensions
Author URI: https://elextensions.com
Version: 2.0.1
Developer: ELEXExtension
WC requires at least: 2.6
WC tested up to: 7.0
*/

// echo "<b>Please Activate Woocommerce</b>";
// exit;
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Hey, Please Login!' );
}
define( 'PLUGIN_NAME', plugin_basename( dirname( __FILE__ ) ) );

require  'includes/scripts.php' ;  //registering and loading scripts
require  'includes/elex-wfp-product-flexible-price.php' ; //Contains all hooks and data filtering functions
require  'includes/elex-wfp-variable-product-flexible-pricing.php' ;

require_once  ABSPATH . 'wp-admin/includes/plugin.php' ;

if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

	if ( ! class_exists( 'WC_Settings_Page' ) ) {
		require_once  WP_PLUGIN_DIR . '/woocommerce/includes/admin/settings/class-wc-settings-page.php' ;
	}

	if ( ! class_exists( 'Elex_Wfp_Name_Your_Price_Setting' ) ) {

		class Elex_Wfp_Name_Your_Price_Setting extends WC_Settings_Page {

			public function __construct() {
				$this->id    = 'elex-flexible-pricing';
				$this->label = __( 'Name Your Price', 'elex_wfp_flexible_price_domain' );
				add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 99 );
				add_action( 'woocommerce_sections_' . $this->id, array( $this, 'output_sections' ) );
				add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
				add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'elex_wfp_action_links' ) );
			}
			public function get_sections() {
				$sections = array(
					'' => __( 'General Settings', 'elex_wfp_flexible_price_domain' ),
				);

				/**
				 * To woocommerce get sections.
				 *
				 * @since  1.0.0
				 */
				return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
			}

			public function output_sections() {
				global $current_section;
				$sections = $this->get_sections();
				if ( empty( $sections ) || 1 === count( $sections ) ) {
					return;
				}
				echo '<ul class="subsubsub">';
				$array_keys = array_keys( $sections );
				foreach ( $sections as $id => $label ) {
					echo '<li><a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=' . $this->id . '&section=' . sanitize_title( $id ) ) ) . '" class="' . ( $current_section === $id ? 'current' : '' ) . '">' . wp_kses_post( $label ) . '</a> ' . ( end( $array_keys ) === $id ? '' : '|' ) . ' </li>';
				}
				echo '</ul><br class="clear" />';
			}

			public function get_settings() {
				global $woocommerce , $current_section;
				$product_category = get_terms(
					'product_cat',
					array(
						'fields' => 'id=>name',
						'hide_empty' => false,
						'orderby' => 'title',
						'order' => 'ASC',
					)
				);
				$settings = array();
				if ( 'interested_products' === $current_section ) {

					$woocommerce_version = function_exists( 'WC' ) ? WC()->version : $woocommerce->version;
					wp_enqueue_style( 'elex-cpp-plugin-bootstrap', plugins_url( 'woocomerce-name-your-price-basic/assets/css/bootstrap.css', dirname( __FILE__ ) ), array(), $woocommerce_version );
					include_once  'includes/market.php';
				} else {
					$settings = array(
						'section_title'             => array(
							'name' => __( 'Elex Name Your Price', 'elex_wfp_flexible_price_domain' ),
							'type' => 'title',
							'id'   => 'elex_cpp_settings_min_product_title',
						),
						'enable_checkbox'           => array(
							'name'     => __( 'Product Min Price', 'elex_wfp_flexible_price_domain' ),
							'type'     => 'checkbox',
							'desc'     => __( 'Enable', 'elex_wfp_flexible_price_domain' ),
							'label'    => __( 'Enable', 'elex_wfp_flexible_price_domain' ),
							'desc_tip'     => __( 'Enable this option to set a minimum price for all the products within the category. <br>Please note, if you have set a minimum price at individual product level, it will override this value.', 'elex_wfp_flexible_price_domain' ),
							'id'       => 'elex_cpp_settings_enable_min_product_price',
						),

						'product_min_price'         => array(
							'name'     => __( 'Set Min Price ', 'elex_wfp_flexible_price_domain' ) . '(' . get_woocommerce_currency_symbol() . ')',
							'type'     => 'number',
							'custom_attributes' => array(
								'step' => 'any',
								'min'  => '00',
							),
							'default' => 0,
							'placeholder' => 'Enter Your Price',
							'css'      => 'width:200px',
							'desc_tip' => true,
							'desc'              => __( 'Set the minimum price which the customer can proceed with payment.', 'elex_wfp_flexible_price_domain' ),
							'id'                => 'elex_cpp_settings_tab_min_product_price',
						),
						'product_dynamic_label'     => array(
							'name'        => __( 'Min Price Label', 'elex_wfp_flexible_price_domain' ),
							'type'        => 'text',
							'placeholder' => __( 'Enter Your Price label', 'elex_wfp_flexible_price_domain' ),
							'css'         => 'width:400px',
							'desc_tip'    => true,
							'desc'        => __( 'Enter the label for the minimum price.', 'elex_wfp_flexible_price_domain' ),
							'id'          => 'elex_cpp_settings_tab_min_product_price_label',
						),
						'product_description_label' => array(
							'name'        => __( 'Min Price Description', 'elex_wfp_flexible_price_domain' ),
							'type'        => 'textarea',
							'css'         => 'width:400px',
							'placeholder' => __( 'A simple min price applied to product', 'elex_wfp_flexible_price_domain' ),
							'desc_tip'    => true,
							'desc'        => __( 'Enter Descripton for your minimum price.', 'elex_wfp_flexible_price_domain' ),
							'id'          => 'elex_cpp_settings_tab_min_product_price_description',
						),
						'enable_regular_sale_price_checkbox' => array(
							'name'     => __( 'Hide Price', 'elex_wfp_flexible_price_domain' ),
							'type'     => 'checkbox',
							'desc'     => __( 'Enable', 'elex_wfp_flexible_price_domain' ),
							'label'    => __( 'Enable', 'elex_wfp_flexible_price_domain' ),
							'desc_tip' => true,
							'desc'     => __( 'Enabling this option will hide the prices from shop and product page.<br>Please note, it is only applicable for products within the category.', 'elex_wfp_flexible_price_domain' ),
							'id'       => 'elex_cpp_settings_enable_regular_sale_price_on_product_page',
						),
						'categories_availabilty'    => array(
							'title'    => __( 'Product Categories', 'elex_wfp_flexible_price_domain' ),
							'type'     => 'multiselect',
							'class'    => 'chosen_select elex_product_category_field',
							'desc_tip' => true,
							'desc'     => __( 'Select the categories to display the minimum price on the basis of product category', 'wf-address-autocomplete-validation' ),
							'id'       => 'elex_cpp_settings_tab_product_category',
							'css'      => 'width: 300px;',
							'default'  => '',
							'options'  => $product_category,
						),
						'section_end'               => array(
							'type' => 'sectionend',
							'id'   => 'wc_elex_flexible_pricing_section_end',
						),
					);
				}
				/**
				 * To woocommerce get settings.
				 *
				 * @since  1.0.0
				 */
				return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings );
			}

			public function output() {
				$settings = $this->get_settings();
				WC_Admin_Settings::output_fields( $settings );
			}

			public function save() {
				global $current_section;
				$settings = $this->get_settings();
				WC_Admin_Settings::save_fields( $settings );

				if ( $current_section ) {
					/**
				 * To woocommerce get sections.
				 *
				 * @since  1.0.0
				 */
					do_action( 'woocommerce_update_options_' . $this->id . '_' . $current_section );
				}
			}

			public function elex_wfp_action_links( $links ) {
				$plugin_links = array(
					'<a href="' . admin_url( '/admin.php?page=wc-settings&tab=elex-flexible-pricing' ) . '">' . __( 'Settings', 'elex_wfp_flexible_price_domain' ) . '</a>',
					'<a href=https://elextensions.com/product-category/plugins/> ' . __( 'ELEX Premium Plugins', 'elex_wfp_flexible_price_domain' ) . '</a>',
				);
				return array_merge( $plugin_links, $links );
			}
		}
	}
	new Elex_Wfp_Name_Your_Price_Setting();
} else {
	add_action( 'admin_notices', 'elex_wfp_woocommerce_inactive_notice' );
	return;
}

/** Function to notify if woocommerce is active */
function elex_wfp_woocommerce_inactive_notice() {
	?>
	<div id="message" class="error">
		<p>
		<?php	echo esc_html( __( 'WooCommerce plugin must be active for ELEX WooCommerce Name Your Price to work. ', 'elex_wfp_flexible_price_domain' ) ); ?>
		</p>
	</div>
	<?php
}

/** Load Plugin Text Domain. */

function elex_wfp_load_plugin_textdomain() {
	load_plugin_textdomain( 'elex_wfp_flexible_price_domain', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
}
add_action( 'plugins_loaded', 'elex_wfp_load_plugin_textdomain' );

function elex_wfp_wc_ajax_get_refreshed_fragments() {
	global $woocommerce;
	if ( $woocommerce->cart ) {
		$woocommerce->cart->calculate_totals();
	}
}
add_action( 'wc_ajax_get_refreshed_fragments', 'elex_wfp_wc_ajax_get_refreshed_fragments', 1 );





