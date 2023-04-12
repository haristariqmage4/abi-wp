<?php
/*
Plugin Name: Always Show Admin Bar
Plugin URI: http://www.leewillis.co.uk/wordpress-plugins/
Description: Always show the admin bar
Author: Lee Willis
Version: 0.3
Author URI: http://www.leewillis.co.uk/
*/

class asabfa {

	function __construct() {
		add_action( 'init', array( $this, 'show_admin_bar' ) );
		add_action( 'admin_bar_menu', array( $this, 'add_login_links' ), 11 );
	}

	function add_login_links() {
		global $wp_admin_bar;
		if ( $this->is_logged_in() )
			return;
		if ( isset( $wp_admin_bar ) )
			$wp_admin_bar->add_menu( array( 'id' => 'login-link', 'title' => __( 'Not Logged in, login here', 'asabfa' ), 'href' => admin_url() ) );
	}

	function has_logged_in() {
		// Have they ever logged in?
		return isset( $_COOKIE['asabfa_logged_in'] ) && $_COOKIE['asabfa_logged_in'] ;
	}

	function is_logged_in() {
		$user_id = get_current_user_id();
		if ( 0 != $user_id ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function show_admin_bar( $show ) {
		// If the user has logged in previously - show the bar
		if ( $this->has_logged_in() ) {
			$show = TRUE;
		}
		// If user is currently logged in. Show the bar, and set a cookie
		if ( $this->is_logged_in() ) {
			setcookie( 'asabfa_logged_in', '1', time() + 60 * 60 * 24 * 1000 );
			$show = TRUE;
		}
		if ( $show && !is_admin() ) {
			add_filter( 'show_admin_bar', '__return_true' );
		} else {
			add_filter( 'show_admin_bar', '__return_false' );
		}
	}

}

$asabfa = new asabfa();