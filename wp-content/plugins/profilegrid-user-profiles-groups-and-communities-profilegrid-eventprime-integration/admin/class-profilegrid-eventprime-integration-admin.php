<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_EventPrime_Integration
 * @subpackage Profilegrid_EventPrime_Integration/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Profilegrid_EventPrime_Integration
 * @subpackage Profilegrid_EventPrime_Integration/admin
 * @author     Your Name <email@example.com>
 */
class Profilegrid_EventPrime_Integration_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $profilegrid_eventprime_integration    The ID of this plugin.
	 */
	private $profilegrid_eventprime_integration;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $profilegrid_eventprime_integration       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $profilegrid_eventprime_integration, $version ) {

		$this->profilegrid_eventprime_integration = $profilegrid_eventprime_integration;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Profilegrid_EventPrime_Integration_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Profilegrid_EventPrime_Integration_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
            if (class_exists('Profile_Magic') ) {
                wp_enqueue_style( $this->profilegrid_eventprime_integration, plugin_dir_url( __FILE__ ) . 'css/profilegrid-eventprime-integration-admin.css', array(), $this->version, 'all' );
            }
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Profilegrid_EventPrime_Integration_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Profilegrid_EventPrime_Integration_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
            if (class_exists('Profile_Magic') ) {
                wp_enqueue_script( $this->profilegrid_eventprime_integration, plugin_dir_url( __FILE__ ) . 'js/profilegrid-eventprime-integration-admin.js', array( 'jquery' ), $this->version, false );
            }
	}
        
      
      
        public function profile_magic_woocommerce_notice_fun()
        {
            if (!class_exists('Profile_Magic') ) {
                    
                $this->Woocommerce_installation();
                    //wp_die( "ProfileGrid Stripe won't work as unable to locate ProfileGrid plugin files." );
            }
            
            if (!class_exists('Event_Magic'))
            {
                $this->Woocommerce_installation2();
            }
        }
        
        public function Woocommerce_installation()
        {
            $plugin_slug= 'profilegrid-user-profiles-groups-and-communities';
            $installUrl = admin_url('update.php?action=install-plugin&plugin=' . $plugin_slug);
            $installUrl = wp_nonce_url($installUrl, 'install-plugin_' . $plugin_slug);
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo sprintf(__( "ProfileGrid EventPrime Integration work with ProfileGrid Plugin. You can install it  from <a href='%s'>Here</a>.", 'profilegrid-eventprime-integration'),$installUrl ); ?></p>
            </div>
            <?php
            deactivate_plugins('profilegrid-user-profiles-groups-and-communities-profilegrid-eventprime-integration/profilegrid-eventprime-integration.php'); 
        }
        
        public function Woocommerce_installation2()
        {
            $plugin_slug= 'eventprime-event-calendar-management';
            $installUrl = admin_url('update.php?action=install-plugin&plugin=' . $plugin_slug);
            $installUrl = wp_nonce_url($installUrl, 'install-plugin_' . $plugin_slug);
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo sprintf(__( "You have activated the ProfileGrid - EventPrime Integration extension but EventPrime is not activated yet. <a href='%s'>Install and Activate EventPrime</a> now to enjoy the best social events creation system on your site!", 'profilegrid-eventprime-integration') ,$installUrl ); ?></p>
            </div>
            <?php
            deactivate_plugins('profilegrid-user-profiles-groups-and-communities-profilegrid-eventprime-integration/profilegrid-eventprime-integration.php');
        }
      
        
        public function activate_sitewide_plugins($blog_id)
        {
            // Switch to new website
            $dbhandler = new PM_DBhandler;
            $activator = new Profile_Magic_Activator;
            switch_to_blog( $blog_id );
            // Activate
            foreach( array_keys( get_site_option( 'active_sitewide_plugins' ) ) as $plugin ) {
                do_action( 'activate_'  . $plugin, false );
                do_action( 'activate'   . '_plugin', $plugin, false );
                $activator->activate();
                
            }
            // Restore current website 
            restore_current_blog();
        }
        
        
        
        public function ep_ext_integrations($post_id)
        { 
            ?>
            <div class="ep-grid-icon difl" id="ep-event-type">
                <a href="<?php echo admin_url("/admin.php?page=em_dashboard&tab=profile-grid&post_id=$post_id") ?>" class="ep-dash-link">    
                    <div class="ep-grid-icon-area dbfl">
                        <img class="ep-grid-icon dibfl" src="<?php echo EM_BASE_URL . 'includes/admin/template/images/profilegrid-icon.png' ?>">
                    </div>
                    <div class="ep-grid-icon-label dbfl"><?php _e('ProfileGrid', 'eventprime-event-calendar-management'); ?></div>
                </a>
            </div>
        <?php 
        
        }
        
        

        public function ep_profile_grid_dashboard($event_id)
        {
            $event_service= EventM_Factory::get_service('EventM_Service');
            $event = $event_service->load_model_from_db($event_id);
            $dbhandler = new PM_DBhandler;
            $groups = $dbhandler->get_all_result('GROUPS', array('id', 'group_name'));
            ?>
            <div class="kikfyre kf-container">
                <div class="kf-db-content">
                    <div class="kf-db-title">
                        <?php _e('ProfileGrid Integration', 'eventprime-event-calendar-management'); ?>
                    </div>

                    <form  name="postForm" method="POST">
                        <div class="emrow">
                            <div class="emfield" style="text-transform: none;"><?php _e('User Group(s)', 'eventprime-event-calendar-management'); ?></div>
                            <div class="eminput">
                                <select multiple name="gid[]" id="gid" class="pg-select-group">
                                    <option disabled value="" <?php echo empty($event->pg_gid) ? 'selected' : ''; ?>><?php _e('None', 'eventprime-event-calendar-management'); ?></option>
                                    <?php
                                    foreach ($groups as $group) {
                                        ?>
                                    <option <?php if (isset($event->pg_gid)): echo in_array($group->id,$event->pg_gid) ? 'selected' : ''; endif; ?> value="<?php echo $group->id; ?>" ><?php echo $group->group_name; ?></option>
                                    <?php }
                                    ?>
                                    
                                </select>
                            </div>
                            <div class="emnote"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                <?php _e("Select the ProfileGrid User Group(s) to which this event will belong.", 'eventprime-event-calendar-management'); ?>
                            </div>
                        </div>
                        <input type="hidden" name="ep_pg_save" />
                        <input type="hidden" name="previous_gids" value='<?php echo maybe_serialize($event->pg_gid);?>' />
                        <div class="dbfl kf-buttonarea">
                            <div class="em_cancel"><a class="kf-cancel" href="<?php echo admin_url('/admin.php?page=em_dashboard&post_id=' . $event_id); ?>"><?php _e('Cancel', 'eventprime-event-calendar-management'); ?></a></div>
                            <button type="submit" class="btn btn-primary" ng-disabled="postForm.$invalid || requestInProgress"><?php _e('Save', 'eventprime-event-calendar-management'); ?></button>

                        </div>
                    </form>
                </div>
            </div>

        <?php 
        
        }
        
        public function ep_popup_event_saved($old_event,$event_id)
        {
            $event_service= EventM_Factory::get_service('EventM_Service');
            $event = $event_service->load_model_from_db($event_id);
            $post_id = $event->id;
            $gid = $event->pg_gid;
            
            if(isset($gid) && !empty($gid))
            {
                $previous_gids = $old_event->pg_gid;
                if(!isset($previous_gids) || empty($previous_gids))
                {
                    $previous_gids = array();
                }

                $new_gid_array = array_diff($gid,$previous_gids);
            }
            else
            {
                $new_gid_array = array();
            }
            
            $notification = new Profile_Magic_Notification;
            $notification->pg_new_group_event_notification($post_id,$new_gid_array);
        }

        public function save_ep_pg()
        {
            if($_POST && isset($_POST['ep_pg_save'])){
                $post_id = isset($_GET['post_id']) ? absint($_GET['post_id']) : 0;
                $event_service= EventM_Factory::get_service('EventM_Service');
                if(isset($_POST['gid']))
                {
                    $gid = $_POST['gid'];
                   

                    // Add your own logic to send notification to all the Group members
                    /*
                     * Custom logic
                     */

                 
             
                    $previous_gids = maybe_unserialize(stripslashes($_POST['previous_gids']));
                
                    if(!isset($previous_gids) || empty($previous_gids))
                    {
                        $previous_gids = array();
                    }

                    $new_gid_array = array_diff($gid,$previous_gids);
                    
                   
                }
                else
                {
                    $gid = array();
                    $new_gid_array = array();
                }
                $notification = new Profile_Magic_Notification;
                $notification->pg_new_group_event_notification($post_id,$new_gid_array);
                $event_service->set_meta($post_id,'pg_gid',$gid);
                wp_redirect(admin_url('/admin.php?page=em_dashboard&post_id='.$post_id));
                exit;
            }  
        }
        
        public function ep_external_integrations($integrations){

            array_push($integrations,'pg');

            return $integrations;

        }
        
       
        public function pm_eventprime_tabs_filters($pm_profile_tabs_status)
        {
            
            $check_ids = array();
            foreach($pm_profile_tabs_status as $oldtab)
            {
                $check_ids[] =$oldtab['id'];
            }
            if(!in_array('pg_event_booking_tab_content',$check_ids))
            {
                $pm_profile_tabs_status['pg_event_booking_tab_content'] = array('id'=>'pg_event_booking_tab_content','title'=>__('Event Bookings','profilegrid-eventprime-integration'),'status'=>'1','class'=>'');
            }
           
            
            return $pm_profile_tabs_status;
           
        }
}
