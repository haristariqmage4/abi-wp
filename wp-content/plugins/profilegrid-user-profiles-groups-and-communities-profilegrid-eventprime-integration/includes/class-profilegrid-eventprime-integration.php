<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_EventPrime_Integration
 * @subpackage Profilegrid_EventPrime_Integration/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Profilegrid_EventPrime_Integration
 * @subpackage Profilegrid_EventPrime_Integration/includes
 * @author     Your Name <email@example.com>
 */
class Profilegrid_EventPrime_Integration {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Profilegrid_EventPrime_Integration_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $profilegrid_eventprime_integration    The string used to uniquely identify this plugin.
	 */
	protected $profilegrid_eventprime_integration;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->profilegrid_eventprime_integration = 'profilegrid-eventprime-integration';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Profilegrid_EventPrime_Integration_Loader. Orchestrates the hooks of the plugin.
	 * - Profilegrid_EventPrime_Integration_i18n. Defines internationalization functionality.
	 * - Profilegrid_EventPrime_Integration_Admin. Defines all hooks for the admin area.
	 * - Profilegrid_EventPrime_Integration_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-profilegrid-eventprime-integration-loader.php';
                
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-profilegrid-eventprime-integration-i18n.php';
                require_once plugin_dir_path(  dirname( __FILE__ )) . 'includes/class-profilegrid-eventprime-integration-activator.php';
                require_once plugin_dir_path( dirname( __FILE__ )   ) . 'includes/class-profilegrid-eventprime-integration-deactivator.php';
                
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-profilegrid-eventprime-integration-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-profilegrid-eventprime-integration-public.php';

		
                $this->loader = new Profilegrid_EventPrime_Integration_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Profilegrid_EventPrime_Integration_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Profilegrid_EventPrime_Integration_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Profilegrid_EventPrime_Integration_Admin( $this->get_profilegrid_eventprime_integration(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
                //$this->loader->add_action( 'admin_menu', $plugin_admin, 'profilegrid_eventprime_integration_admin_menu' );
               // $this->loader->add_action( 'profile_magic_setting_option', $plugin_admin, 'profilegrid_eventprime_integration_add_option_setting_page' );
                $this->loader->add_action( 'admin_notices', $plugin_admin, 'profile_magic_woocommerce_notice_fun' );
                $this->loader->add_action( 'network_admin_notices', $plugin_admin, 'profile_magic_woocommerce_notice_fun' );
               // $this->loader->add_action( 'profile_magic_group_option', $plugin_admin, 'profile_magic_woocommerce_group_option',10,2 );
                $this->loader->add_action('wpmu_new_blog', $plugin_admin, 'activate_sitewide_plugins');
                
                /*
                * Adding Profile Grid icon in Event Dashbaord
                */
                $this->loader->add_action('ep_ext_integrations',$plugin_admin,'ep_ext_integrations');
                /*
                * Profile Grid Group selection screen
                */
                $this->loader->add_action('event_magic_dashboard_profile-grid_tab',$plugin_admin,'ep_profile_grid_dashboard');
                /*
                * Saving dashboard options
                */
                $this->loader->add_filter('ep_external_integrations',$plugin_admin,'ep_external_integrations',10,1);
                $this->loader->add_action('admin_init',$plugin_admin,'save_ep_pg');
                $this->loader->add_action('ep_popup_event_saved',$plugin_admin,'ep_popup_event_saved',10,2);
                $this->loader->add_filter('pm_profile_tabs', $plugin_admin, 'pm_eventprime_tabs_filters');
        }

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
            $plugin_public = new Profilegrid_EventPrime_Integration_Public( $this->get_profilegrid_eventprime_integration(), $this->get_version() );
            $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
            $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
            //$this->loader->add_action('profile_magic_profile_tab',$plugin_public, 'pg_eventbooking_tab',10,2);
            //$this->loader->add_action('profile_magic_profile_tab_content',$plugin_public, 'pg_show_eventbooking_tab_content',10,2);
            $this->loader->add_action('profile_magic_group_photos_tab',$plugin_public, 'pg_show_group_events_tab',10,2);
            $this->loader->add_action('profile_magic_group_photos_tab_content',$plugin_public, 'pm_show_group_events_content',10,2);
            $this->loader->add_action('em_event_filter_form',$plugin_public, 'pm_em_event_filter_form');
            $this->loader->add_action('profile_magic_profile_tab_link',$plugin_public, 'profile_magic_profile_tab_link_fun',10,5);
            $this->loader->add_action('profile_magic_profile_tab_extension_content',$plugin_public, 'profile_magic_profile_tab_extension_content_fun',10,5);
            
            

            // PG Event Prime Integration

            /*
             * Overriding EP profile shortcode
             */
            $this->loader->add_filter('ep_load_profile',$plugin_public,'ep_load_profile',10,2);
            /*
            * Adding corresponding field in the model
            */
            $this->loader->add_action('ep_event_model_fields',$plugin_public,'ep_event_model_fields');
            /*
            * Filtering events for calendar view on the basis of user group ID. Allows to show show only events which are associated with the PG group
            */
            $this->loader->add_filter('ep_filter_front_events',$plugin_public,'ep_filter_calendar_events',10,2);
            /*
            * Admin event popup 
            */
            $this->loader->add_filter('ep_admin_calendar_event',$plugin_public,'ep_admin_calendar_event',10,2);
            
            $this->loader->add_action('ep_admin_event_popup',$plugin_public,'ep_admin_event_popup');
            
            $this->loader->add_filter('ep_before_saving_popup_event',$plugin_public,'ep_before_saving_popup_event');
        }

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_profilegrid_eventprime_integration() {
		return $this->profilegrid_eventprime_integration;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Profilegrid_EventPrime_Integration_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}