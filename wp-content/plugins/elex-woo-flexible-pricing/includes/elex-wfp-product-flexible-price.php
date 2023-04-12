<?php


function elex_wfp_enable_custom_field() {
	global $post;
	$product = wc_get_product( $post->ID );
	if ( ! $product->is_type( 'variable' ) ) {
		?>
<hr style="color:black;">
<span class="elex_cpp_custom_title_heading"><?php echo esc_html_e( __( 'Elex Name Your Price', 'elex_wfp_flexible_price_domain' ) ); ?></span>


<?php

	woocommerce_wp_checkbox(
		array(
			'id'          => 'elex_wfp_custom_text_field_flag',
			'desc'        => __( 'Enable', 'elex_wfp_flexible_price_domain' ),
			'desc_tip'    => __( 'Enable', 'elex_wfp_flexible_price_domain' ),
			'label'       => __( 'Product Min Price', 'elex_wfp_flexible_price_domain' ),
			'description' => __( 'Enable this option to set a minimum price for this product. Please note, this value will override the global minimum price that you may have set.', 'elex_wfp_flexible_price_domain' ),
			'value'       => get_post_meta( $post->ID, 'elex_wfp_custom_text_field_flag', true ),
		)
	);
	woocommerce_wp_text_input(
		array(
			'id'                => 'elex_wfp_custom_price_text_field',
			'label'             => __( 'Set Min Price ', 'elex_wfp_flexible_price_domain' ) . '(' . get_woocommerce_currency_symbol() . ')',
			'class'             => 'elex-wfp-custom-field',
			'type'              => 'number',
			'description'       => __( 'Set the minimum price which the customer can proceed with payment.', 'elex_wfp_flexible_price_domain' ),
			'custom_attributes' => array(
				'step' => 'any',
				'min'  => '00',
			),
			'desc_tip'          => true,
			'value'             => get_post_meta( $post->ID, 'elex_wfp_custom_price_text_field', true ),
		)
	);
	woocommerce_wp_text_input(
		array(
			'id'                => 'elex_wfp_product_min_price_dynamic_label',
			'label'             => __( 'Min Price Label', 'elex_wfp_flexible_price_domain' ),
			'class'             => 'elex-wfp-custom-field',
			'type'              => 'text',
			'description'       => __( 'Enter the label for the minimum price.', 'elex_wfp_flexible_price_domain' ),
			'custom_attributes' => array(
				'step' => '1',
				'min'  => '00',
			),
			'desc_tip'          => true,
			'value'             => get_post_meta( $post->ID, 'elex_wfp_product_min_price_dynamic_label', true ),
		)
	);
	woocommerce_wp_text_input(
		array(
			'id'                => 'elex_wfp_product_min_price_description',
			'label'             => __( 'Min Price Description', 'elex_wfp_flexible_price_domain' ),
			'class'             => 'elex-wfp-custom-field',
			'type'              => 'text',
			'description'       => __( 'Enter Descripton for your minimum price.', 'elex_wfp_flexible_price_domain' ),
			'custom_attributes' => array(
				'step' => '1',
				'min'  => '00',
			),
			'desc_tip'          => true,
			'value'             => get_post_meta( $post->ID, 'elex_wfp_product_min_price_description', true ),
		)
	);
	woocommerce_wp_checkbox(
		array(
			'id'          => 'elex_wfp_hide_price_regular_sale_flag',
			'desc'        => __( 'Enable', 'elex_wfp_flexible_price_domain' ),
			'desc_tip'    => __( 'Enable', 'elex_wfp_flexible_price_domain' ),
			'label'       => __( 'Hide Price', 'elex_wfp_flexible_price_domain' ),
			'description' => __( 'Enable this option to hide Regualar/Sale price', 'elex_wfp_flexible_price_domain' ),
			'value'       => get_post_meta( $post->ID, 'elex_wfp_hide_price_regular_sale_flag', true ),
		)
	);
	}
}
add_action( 'woocommerce_product_options_general_product_data', 'elex_wfp_enable_custom_field' );

function elex_wfp_save_custom_field( $post_id ) {
	if ( ! ( isset( $_POST['woocommerce_meta_nonce'] ) || wp_verify_nonce( sanitize_key( $_POST['woocommerce_meta_nonce'] ), 'woocommerce_save_data' ) ) ) { // Input var okay.
		return false;
	}
	$product = wc_get_product( $post_id );
	if ( $product->is_type( 'simple' ) ) {
		$flag = isset( $_POST['elex_wfp_custom_text_field_flag'] ) ? sanitize_text_field( $_POST['elex_wfp_custom_text_field_flag'] ) : '';
		update_post_meta( $post_id, 'elex_wfp_custom_text_field_flag', $flag );

		$custom_price = isset( $_POST['elex_wfp_custom_price_text_field'] ) ? sanitize_text_field( $_POST['elex_wfp_custom_price_text_field'] ) : '';
		update_post_meta( $post_id, 'elex_wfp_custom_price_text_field', $custom_price );

		$custom_price_label = isset( $_POST['elex_wfp_product_min_price_dynamic_label'] ) ? sanitize_text_field( $_POST['elex_wfp_product_min_price_dynamic_label'] ) : '';
		update_post_meta( $post_id, 'elex_wfp_product_min_price_dynamic_label', $custom_price_label );

		$custom_price_desc = isset( $_POST['elex_wfp_product_min_price_description'] ) ? sanitize_text_field( $_POST['elex_wfp_product_min_price_description'] ) : '';
		update_post_meta( $post_id, 'elex_wfp_product_min_price_description', $custom_price_desc );

		$hide_price = isset( $_POST['elex_wfp_hide_price_regular_sale_flag'] ) ? sanitize_text_field( $_POST['elex_wfp_hide_price_regular_sale_flag'] ) : '';
		update_post_meta( $post_id, 'elex_wfp_hide_price_regular_sale_flag', $hide_price );

		$product->save();
	}

}

add_action( 'woocommerce_process_product_meta', 'elex_wfp_save_custom_field' );

// return the customer input categories
function elex_cpp_product_categories() {
	return get_option( 'elex_cpp_settings_tab_product_category', array() );
}

function elex_wfp_display_custom_field() {

	global $post,$elex_wfp_custom_field_nonce;
	$product = wc_get_product( $post->ID );
	$product_categories = elex_cpp_product_categories();
	$terms = wc_get_product_term_ids( $post->ID, 'product_cat' );

	if ( $product->is_type( 'simple' ) ) {
		if ( 'yes' !== $product->get_meta( 'elex_wfp_custom_text_field_flag' ) && 'yes' !== get_option( 'elex_cpp_settings_enable_min_product_price' ) ) {
			return;
		}
		if ( $product->get_meta( 'elex_wfp_custom_text_field_flag' ) === 'yes' ) {
			$flag              = $product->get_meta( 'elex_wfp_custom_text_field_flag' );
			$product_min_price = $product->get_meta( 'elex_wfp_custom_price_text_field' );
			$product_min_label = $product->get_meta( 'elex_wfp_product_min_price_dynamic_label' );
			$product_min_label = elex_wfp_return_wpml_string( $product_min_label, 'Product price label' );
			$product_min_desc  = $product->get_meta( 'elex_wfp_product_min_price_description' );
			$product_min_desc  = elex_wfp_return_wpml_string( $product_min_desc, 'Product price description' );
		}
		if ( $product->get_meta( 'elex_wfp_custom_text_field_flag' ) !== 'yes' && 'yes' === get_option( 'elex_cpp_settings_enable_min_product_price' ) && ( array_intersect( $product_categories, $terms ) || empty( $product_categories ) ) ) {
			$product_min_price = get_option( 'elex_cpp_settings_tab_min_product_price', 1 );
			$product_min_desc  = get_option( 'elex_cpp_settings_tab_min_product_price_description', 1 );
			$product_min_desc  = elex_wfp_return_wpml_string( $product_min_desc, 'Product price description' );
			$product_min_label = get_option( 'elex_cpp_settings_tab_min_product_price_label', 1 );
			$product_min_label = elex_wfp_return_wpml_string( $product_min_label, 'Product price label' );
		}

		if ( isset( $produc_min_label ) ) {
			$product_min_label = __( 'Enter Your Price', 'elex_wfp_flexible_price_domain' );
		}
		if ( isset( $product_min_price ) ) {
			echo '<div class="wrap">';
			?>
			<label class="custom-min-price1" for="custom-price-field"><?php echo wp_kses_post( $product_min_label ) . ' (' . wp_kses_post( get_woocommerce_currency_symbol() ) . ')'; ?></label>
			<input type="hidden" id="<?php echo 'elex_custom_field_nonce_' . wp_kses_post( $post->ID ); ?>" name="<?php echo 'elex_custom_field_nonce_' . wp_kses_post( $post->ID ); ?>" data-product_id ="<?php echo   wp_kses_post( $post->ID ); ?>" value="<?php echo wp_kses_post( $elex_wfp_custom_field_nonce ); ?>"/>
			<input type="number" step="any" min="0" data-product_id ="<?php echo wp_kses_post( $post->ID ); ?>" class="custom-price" value="<?php echo  strlen( wp_kses_post( $product_min_price ) ) === 0 ? 0 : wp_kses_post( $product_min_price ); ?>" id="<?php echo  'custom_price_field_' . wp_kses_post( $post->ID ); ?>" name="<?php echo  'custom_price_field_' . wp_kses_post( $post->ID ); ?>"  />
			<small class="description_product"> <?php echo '*' . wp_kses_post( $product_min_desc ); ?> </small>
			<?php
			echo '</div>';
		}
	}
}

add_action( 'woocommerce_before_add_to_cart_button', 'elex_wfp_display_custom_field' );
add_action( 'woocommerce_after_shop_loop_item', 'elex_wfp_display_custom_field' );
add_filter( 'woocommerce_variable_price_html', 'elex_variation_price_formate', 999, 2 );
add_filter( 'woocommerce_get_price_html', 'elex_wfp_get_price_html', 99, 2 );

// for variable price range
function elex_variation_price_formate( $price, $product ) {
	// Get min/max regular and sale variation prices
	if ( $product->is_type( 'variable' ) ) {
		$prices = $product->get_variation_prices( true );
		$hide_price = false;
		if ( empty( $prices['price'] ) ) {
			return $price;
		}
		foreach ( $prices['price'] as $pid => $old_price ) {
			if ( 'yes' === get_post_meta( $pid, 'elex_wfp_checkbox', true ) ) {
				$hide_price = true;
			}
			if ( 'yes' === get_post_meta( $pid, 'elex_wfp_checkbox', true ) ) {

				if ( 'yes' !== get_post_meta( $pid, 'elex_wfp_enable_price', true ) ) {
					$pobj                    = wc_get_product( $pid );
					$prices['price'][ $pid ] = wc_get_price_to_display( $pobj );
				} else {
					unset( $prices['price'][ $pid ] );
				}
			} else {
				$pobj                    = wc_get_product( $pid );
				$prices['price'][ $pid ] = wc_get_price_to_display( $pobj );
			}
		}
		if ( 'yes' === get_option( 'elex_cpp_settings_enable_min_product_price' ) ) {
			if ( 'yes' === get_option( 'elex_cpp_settings_enable_regular_sale_price_on_product_page' ) && ! $hide_price ) {
				return '';
			}
		}
		if ( empty( $prices['price'] ) ) {
			return '';
		}
		asort( $prices['price'] );
		asort( $prices['regular_price'] );
		$min_price = current( $prices['price'] );
		$max_price = end( $prices['price'] );
		if ( $min_price !== $max_price ) {
			$price = wc_format_price_range( $min_price, $max_price ) . $product->get_price_suffix();
		} else {
			$price = wc_price( $max_price ) . $product->get_price_suffix();
		}
	}
	return $price;
}

function elex_wfp_get_price_html( $price, $product ) {
	$product_type = elex_wfp_get_product_type( $product );
	if ( elex_wfp_is_hide_price( $product ) && 'variable' !== $product_type ) {
		$price = '';
	}
	return $price;
}

function elex_wfp_is_hide_price( $product ) {
	$hide         = false;
	$product_id = elex_wfp_get_product_id( $product );
	$product_type = elex_wfp_get_product_type( $product );
	$product_categories = elex_cpp_product_categories();
	if ( 'variation' === $product_type ) {
		$product_id = elex_wfp_get_product_id( $product );
	}
	$terms = wc_get_product_term_ids( $product_id, 'product_cat' );

	if ( 'variation' === $product_type ) {
		if ( 'yes' === get_post_meta( $product_id, 'elex_wfp_checkbox', true ) &&
			'yes' === get_post_meta( $product_id, 'elex_wfp_enable_price', true ) ) {
				return true;
		}

		if ( 'yes' === get_option( 'elex_cpp_settings_enable_min_product_price' ) &&
			'yes' === get_option( 'elex_cpp_settings_enable_regular_sale_price_on_product_page' ) &&
			( array_intersect( $product_categories, $terms ) ||
			empty( $product_categories ) ) &&
			empty( get_post_meta( $product_id, 'elex_wfp_checkbox', true ) ) ) {
				return true;
		}
		return $hide;
	}

	if ( 'yes' === $product->get_meta( 'elex_wfp_custom_text_field_flag' ) &&
		'yes' === $product->get_meta( 'elex_wfp_hide_price_regular_sale_flag' ) ) {
			return true;
	}

	if ( 'yes' === get_option( 'elex_cpp_settings_enable_min_product_price' ) &&
		'yes' === get_option( 'elex_cpp_settings_enable_regular_sale_price_on_product_page' ) && 
		( array_intersect( $product_categories, $terms ) || empty( $product_categories ) ) &&
		'' === $product->get_meta( 'elex_wfp_custom_text_field_flag' ) ) {
			return true;
	}

	return $hide;
}

function elex_wfp_get_product_type( $product ) {
	if ( empty( $product ) ) {
		return 'not a valid object';
	}
	if ( WC()->version < '2.7.0' ) {
		$product_type = $product->product_type;
	} else {
		$product_type = $product->get_type();
	}
	return $product_type;
}

function elex_wfp_get_product_id( $product ) {
	if ( empty( $product ) ) {
		return 'not a valid object';
	}
	if ( WC()->version < '2.7.0' ) {
		$product_id = $product->post->id;
	} else {
		$product_id = $product->get_id();
	}
	return $product_id;
}

function elex_wfp_get_product_parent_id( $product ) {
	if ( empty( $product ) ) {
		return 'not a valid object';
	}
	if ( WC()->version < '2.7.0' ) {
		$product_parent_id = $product->parent->id;
	} else {
		$product_parent_id = $product->get_parent_id();
	}
	return $product_parent_id;
}

global $elex_wfp_custom_field_nonce;
$elex_wfp_custom_field_nonce = '';

function elex_wfp_ajax_load_scripts() {
	global $woocommerce,$elex_wfp_custom_field_nonce;
	$elex_wfp_custom_field_nonce = wp_create_nonce( 'elex_wfp_custom_price_field_nonce' );
	$woocommerce_version = function_exists( 'WC' ) ? WC()->version : $woocommerce->version;
	wp_enqueue_script( 'ajax-test', plugins_url( '/assets/js/ajax-test.js', dirname( __FILE__ ) ), array( 'jquery' ), $woocommerce_version );
	wp_localize_script(
		'ajax-test',
		'the_ajax_script',
		array(
			'ajaxurl'                        => admin_url( 'admin-ajax.php' ), 
			'elex_wfp_variation_nonce_token' => wp_create_nonce( 'elex_wfp_variation_nonce' ),
		)
	);
}
add_action( 'wp_print_scripts', 'elex_wfp_ajax_load_scripts' );


function elex_wfp_ajax_process_request() {

	if ( ! ( isset( $_POST['_ajax_nonce'] ) || wp_verify_nonce( sanitize_key( $_POST['_ajax_nonce'] ), 'woocommerce_save_data' ) ) ) { // Input var okay.
		return false;
	}
	$var_id          = isset( $_POST['var_id'] ) ? sanitize_text_field( $_POST['var_id'] ) : '';
	$enable_checkbox = get_post_meta( sanitize_text_field( $_POST['var_id'] ), 'elex_wfp_checkbox', true );
	if ( 'no' === get_option( 'elex_cpp_settings_enable_min_product_price' ) && 'yes' !== $enable_checkbox ) { 
		$json_response = array(
			'general_flag'    => 'no',
			'value'           => '',
			'label'           => '',
			'desc'            => '',
			'hide_price'      => get_option( 'elex_cpp_settings_enable_regular_sale_price_on_product_page' ),
			'currency_symbol' => get_woocommerce_currency_symbol(),
		);
		echo json_encode( $json_response );
		die();
	}
	$product_categories = elex_cpp_product_categories();
	$parent_id = elex_wfp_get_product_parent_id( wc_get_product( $var_id ) );
	$terms = wc_get_product_term_ids( $parent_id, 'product_cat' );
	if ( 'yes' === $enable_checkbox ) {

		$custom_field_value = get_post_meta( $var_id, 'elex_wfp_text_field', true );
		$custom_field_label = get_post_meta( $var_id, 'elex_wfp_label_field', true ); 
		$custom_field_label = elex_wfp_return_wpml_string( $custom_field_label, 'Product variation price label' ); 
		$custom_field_desc  = get_post_meta( $var_id, 'elex_wfp_desc_field', true );
		$custom_field_desc  = elex_wfp_return_wpml_string( $custom_field_desc, 'Product variation price description' );   
		$enable_price_flag  = get_post_meta( $var_id, 'elex_wfp_enable_price', true );
	} elseif ( array_intersect( $product_categories, $terms ) || empty( $product_categories ) ) {
		$custom_field_value = get_option( 'elex_cpp_settings_tab_min_product_price' );
		$custom_field_label = get_option( 'elex_cpp_settings_tab_min_product_price_label' );
		$custom_field_label = elex_wfp_return_wpml_string( $custom_field_label, 'Product variation price label text' );
		$custom_field_desc  = get_option( 'elex_cpp_settings_tab_min_product_price_description' );
		$custom_field_desc  = elex_wfp_return_wpml_string( $custom_field_desc, 'Product variation price description' );
		$enable_price_flag  = get_option( 'elex_cpp_settings_enable_regular_sale_price_on_product_page' );

	}

	if ( isset( $custom_field_value ) ) {
		$json_response = array(
			'general_flag'    => 'yes',
			'value'           => $custom_field_value,
			'label'           => $custom_field_label,
			'desc'            => $custom_field_desc,
			'hide_price'      => $enable_price_flag,
			'var_id'          => $var_id,
			'currency_symbol' => get_woocommerce_currency_symbol(),
		);
		echo json_encode( $json_response );
		die();
	}
}
add_action( 'wp_ajax_test_response', 'elex_wfp_ajax_process_request' );
add_action( 'wp_ajax_nopriv_test_response', 'elex_wfp_ajax_process_request' );

function elex_wfp_cart_validation( $passed, $product_id, $quantity, $variation_id = null ) {
	$product_categories = elex_cpp_product_categories();
	$terms = wc_get_product_term_ids( $product_id, 'product_cat' );
	$nonce = isset( $_POST['elex_wfp_custom_price_field_nonce'] ) ? wp_verify_nonce( sanitize_key( $_POST['elex_wfp_custom_price_field_nonce'] ) ) : ( isset( $_POST[ 'elex_custom_field_nonce_' . $product_id ] ) ? wp_verify_nonce( sanitize_key( $_POST[ 'elex_custom_field_nonce_' . $product_id ] ) ) : '' );
	if ( ! ( isset( $nonce ) || wp_verify_nonce( sanitize_key( $nonce ), 'woocommerce_save_data' ) ) ) {
		return false;
	}
	$product = wc_get_product( $product_id );
	if ( $product->is_type( 'simple' ) ) {
		$_POST[ 'custom_price_field_' . $product_id ] = isset( $_POST['custom_price'] ) ? sanitize_text_field( $_POST['custom_price'] ) : ( isset( $_POST[ 'custom_price_field_' . $product_id ] ) ? sanitize_text_field( $_POST[ 'custom_price_field_' . $product_id ] ) : '' );
		$product_min_price = $product->get_meta( 'elex_wfp_custom_price_text_field' );
		$flag = $product->get_meta( 'elex_wfp_custom_text_field_flag' );
		if ( 'no' === get_option( 'elex_cpp_settings_enable_min_product_price' ) && 'yes' !== $product->get_meta( 'elex_wfp_custom_text_field_flag' ) ) {
			$passed;
		} else {
			// check if product is not in the category and product custom_text_field_flag is not enabled.
			if ( ! empty( $product_categories ) && empty( array_intersect( $product_categories, $terms ) ) && 'yes' !== $flag ) {
				return $passed;
			}
			if ( 0 === strlen( sanitize_text_field( $_POST[ 'custom_price_field_' . $product_id ] ) ) ) {
				$passed = false;
				wc_add_notice( __( 'Custom Price is a required field.', 'elex_wfp_flexible_price_domain' ), 'error' );
				return $passed;
			}

			if ( 'yes' === $flag ) {
				if ( $product_min_price > sanitize_text_field( (float) $_POST[ 'custom_price_field_' . $product_id ] ) ) {
					$passed = false;
					wc_add_notice( __( 'Custom Price Cannot be less than ', 'elex_wfp_flexible_price_domain' ) . $product_min_price, 'error' );
					return $passed;
				}
			} else {
				if ( get_option( 'elex_cpp_settings_tab_min_product_price', 1 ) > sanitize_text_field( (float) $_POST[ 'custom_price_field_' . $product_id ] ) ) {
					$passed = false;
					wc_add_notice( __( 'Custom Price Cannot be less than ', 'elex_wfp_flexible_price_domain' ) . get_option( 'elex_cpp_settings_tab_min_product_price', 1 ), 'error' );
					return $passed;
				}
			}
			update_post_meta( $product_id, 'custom_price_field_' . $product_id, sanitize_text_field( $_POST[ 'custom_price_field_' . $product_id ] ) );
		}
		return $passed;
	} elseif ( $product->is_type( 'variable' ) ) {
		$custom_variation_price = isset( $_POST[ 'custom_price_field_variation_' . $variation_id ] ) ? sanitize_text_field( $_POST[ 'custom_price_field_variation_' . $variation_id ] ) : '';
		if ( 'no' === get_option( 'elex_cpp_settings_enable_min_product_price' ) && 'yes' !== get_post_meta( $variation_id, 'elex_wfp_checkbox', true ) ) {
			$passed;
		} else {
			if ( ! empty( $product_categories ) && ( empty( array_intersect( $product_categories, $terms ) ) && 'yes' !== get_post_meta( $variation_id, 'elex_wfp_checkbox', true ) ) ) {
				return $passed;
			}
			if ( 0 === strlen( $custom_variation_price ) ) {
				$passed = false;
				wc_add_notice( __( 'Custom Price is a required field.', 'elex_wfp_flexible_price_domain' ), 'error' );
				return $passed;
			}
			if ( get_post_meta( $variation_id, 'elex_wfp_checkbox', true ) === 'yes' ) {
				if ( get_post_meta( $variation_id, 'elex_wfp_text_field', true ) > (float) $custom_variation_price ) {
					$passed = false;
					wc_add_notice( __( 'Custom Price Cannot be less than ' , 'elex_wfp_flexible_price_domain' ) . get_post_meta( $variation_id, 'elex_wfp_text_field', true ), 'error' );
				}
			} else {
				if ( get_option( 'elex_cpp_settings_tab_min_product_price', 1 ) > (float) $custom_variation_price ) {
					$passed = false;
					wc_add_notice( __( 'Custom Price Cannot be less than ', 'elex_wfp_flexible_price_domain' ) . get_option( 'elex_cpp_settings_tab_min_product_price', 1 ), 'error' );
					return $passed;
				}
			}
			update_post_meta( $product_id, 'custom_price_field', $custom_variation_price );
		}

		return $passed;
	}
}

add_filter( 'woocommerce_add_to_cart_validation', 'elex_wfp_cart_validation', 10, 4 );

function elex_wfp_update_cart_item_data( $cart_item_data, $product_id, $variation_id ) {

	$product_categories = elex_cpp_product_categories();
	$terms = wc_get_product_term_ids( $product_id, 'product_cat' );
	$product = wc_get_product( $product_id );
	if ( $product->is_type( 'simple' ) ) {
		$flag = $product->get_meta( 'elex_wfp_custom_text_field_flag' );
	} elseif ( $product->is_type( 'variable' ) ) {
		$flag = get_post_meta( $variation_id, 'elex_wfp_checkbox', true );
	}

	if ( ( 'yes' !== $flag ) && ( empty( array_intersect( $product_categories, $terms ) ) && ! empty( $product_categories ) ) || ( get_option( 'elex_cpp_settings_enable_min_product_price' ) === 'no' ) ) {
		return $cart_item_data;
	}
	if ( $product->is_type( 'simple' ) ) {
		$custom_price = get_post_meta( $product_id, 'custom_price_field_' . $product_id, true );
	} else {
		$custom_price = get_post_meta( $product_id, 'custom_price_field', true );
	}
	if ( strlen( $custom_price ) === 0 || is_null( $custom_price ) ) {
		return $cart_item_data;
	}
	$cart_item_data['custom_price_field'] = $custom_price;
	$cart_item_data['total_price']        = $custom_price;
	return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'elex_wfp_update_cart_item_data', 10, 3 );

function elex_wfp_before_calculate_totals( $cart_obj ) {

	foreach ( $cart_obj->get_cart() as $key => $value ) {
		if ( isset( $value['total_price'] ) ) {
			$price = $value['total_price'];
			$value['data']->set_price( $price );
		}
	}
}
add_action( 'woocommerce_before_calculate_totals', 'elex_wfp_before_calculate_totals', 10, 3 );

function elex_wfp_return_wpml_string( $string_to_translate, $name ) {
	// https://wpml.org/documentation/support/wpml-coding-api/wpml-hooks-reference/#hook-620585
	// https://wpml.org/documentation/support/wpml-coding-api/wpml-hooks-reference/#hook-620618
	$package = array(
		'kind'      => 'Elex Woocommerce Flexible Pricing',
		'name'      => 'elex_wfp_flexible_price_domain',
		'title'     => $name,
		'edit_link' => '',
	);
	/**
				 * To wpml register string.
				 *
				 * @since  1.0.0
				 */
	do_action( 'wpml_register_string', $string_to_translate, $name, $package, $name, 'LINE' );
	/**
				 * To wpml translate string.
				 *
				 * @since  1.0.0
				 */
	$ret_string = apply_filters( 'wpml_translate_string', $string_to_translate, $name, $package );
	return $ret_string;
}
