<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Profilegrid_EventPrime_Integration
 * @subpackage Profilegrid_EventPrime_Integration/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Profilegrid_EventPrime_Integration
 * @subpackage Profilegrid_EventPrime_Integration/public
 * @author     Your Name <email@example.com>
 */
class Profilegrid_EventPrime_Integration_Public {

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
     * @param      string    $profilegrid_eventprime_integration       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $profilegrid_eventprime_integration, $version ) {

            $this->profilegrid_eventprime_integration = $profilegrid_eventprime_integration;
            $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
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

            wp_enqueue_style( $this->profilegrid_eventprime_integration, plugin_dir_url( __FILE__ ) . 'css/profilegrid-eventprime-integration-public.css', array(), $this->version, 'all' );

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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
	    wp_enqueue_script('jquery');
	    //wp_enqueue_script('jquery-ui-tabs');
            wp_enqueue_script( $this->profilegrid_eventprime_integration, plugin_dir_url( __FILE__ ) . 'js/profilegrid-eventprime-integration-public.js', array( 'jquery' ), $this->version, true );

    }



    public function pg_eventbooking_tab($id,$newtab,$uid,$gid)
    {
         if($id=='pg_event_booking_tab_content' && isset($newtab) && $newtab['status']=='1')
         {
            if($uid == get_current_user_id()):
            ?>
           <li class="pm-profile-tab pm-pad10"><a class="pm-dbfl" href="#pg_event_booking_tab_content"><?php echo _e($newtab['title'],'profilegrid-eventprime-integration');?></a></li>
          <?php         
           endif;
         }
    }

    public function pg_show_eventbooking_tab_content($id,$newtab,$uid,$gid,$primary_gid)
    {
        if($id=='pg_event_booking_tab_content' && isset($newtab) && $newtab['status']=='1')
        {
            if($uid == get_current_user_id()):
            echo '<div id="pg_event_booking_tab_content" class="pm-dbfl pg-profile-tab-content">';
                echo do_shortcode('[em_profile uid="'.$uid.'"]');
            echo '</div>';
            endif;
        }
    }
    
    public function pg_show_group_events_tab($uid,$gid)
    {
       
            echo '<li class="pm-profile-tab pm-pad10"><a class="pm-dbfl" href="#pg_group_events">'. __('Events','profilegrid-eventprime-integration').'</a></li>';
       
    }
    
    public function pm_show_group_events_content($uid,$gid)
    {
      
                echo '<div id="pg_group_events" class="pm-dbfl pg-profile-tab-content">';
                 echo do_shortcode('[em_events pg_gid="'.$gid.'"]');
                echo '</div>';
       
    }

    public function ep_load_profile($shortcode,$atts)
    {
        if(isset($atts['uid']) || isset($_REQUEST['em_bookings']))
        {
            return false;
        }
        else
        {
           return '[PM_Profile]';   
        }
    }
    
    public function ep_event_model_fields($properties)
    {
        $properties['pg_gid'] = array();
        return $properties;
    }
    
    public function ep_filter_calendar_events($posts,$params)
    {
        if(!isset($params['pg_gid'])){
            return $posts;
        }
        $event_service= EventM_Factory::get_service('EventM_Service');
        // Get current user PG group ID
        $gid = absint($params['pg_gid']);
        foreach($posts as $index=>$post){
            $event_gid = $event_service->get_meta($post->ID,'pg_gid');
            if(empty($event_gid) || !in_array($gid, $event_gid)){
                unset($posts[$index]);
            }
        }
        $posts = array_values($posts);
        return $posts;
    }
    
    public function ep_admin_calendar_event($cal_event,$ev)
    {
        $cal_event['pg_gid']= $ev->pg_gid;
        return $cal_event;
    }
    public function ep_admin_event_popup()
    { 
        $dbhandler = new PM_DBhandler;
        $groups = $dbhandler->get_all_result('GROUPS', array('id', 'group_name'));
        ?>
        <div class="emrow">
            <div class="epinputicon ep-add-location"><i class="material-icons">group</i></div>
            <div class="eminput">
                <select class="pg-select-group" multiple ie-select-fix="calendarNewEventPopup.pg_gid"  ng-model="calendarNewEventPopup.pg_gid">
                   <option disabled value="" <?php echo empty($gid) ? 'selected' : ''; ?>><?php _e('None', 'eventprime-event-calendar-management'); ?></option>
                    <?php
                    foreach ($groups as $group) {
                        ?>
                        <option value="<?php echo $group->id; ?>" <?php if (!empty($gid)) selected($gid, $group->id); ?>><?php echo $group->group_name; ?></option>
                    <?php }
                    ?>
                    
                </select>
            </div>
        </div> 
        <script>
            $(document).bind('new_event_popup',function (e,calenderNewEvent,calEvent){
                calenderNewEvent.pg_gid= calEvent.pg_gid;
            });
            $(document).bind('after_saving_popup_event',function(e,calEvent,event){
                console.log(event);
                calEvent.pg_gid= event.pg_gid;
            });
            $(document).bind('after_dropping_event',function(e,calEvent,event){
                console.log(event);
                calEvent.pg_gid= event.pg_gid;
            });

        </script>
        <?php 
        
    }
    
    public function ep_before_saving_popup_event($event)
    {
        if(isset($_POST['pg_gid'])){
            $event->pg_gid = $_POST['pg_gid'];
        }
        return $event;
    }
    
    public function pm_em_event_filter_form()
    {
        if(isset($_REQUEST['gid'])):
        ?>
        <input type="hidden" name="gid" value="<?php echo $_REQUEST['gid'];?>" />
        <?php
        endif;
    }
    
    public function profile_magic_profile_tab_link_fun($id,$newtab,$uid,$gid,$primary_gid)
    {
        if(isset($newtab) && $newtab['status']=='1'):
            switch($id)
            {
                case 'pg_event_booking_tab_content':
                    $this->pg_eventbooking_tab($id,$newtab,$uid,$primary_gid);
                    break;
                
            }
        endif;
    }
    
    public function profile_magic_profile_tab_extension_content_fun($id,$newtab,$uid,$gid,$primary_gid)
    {
        if(isset($newtab) && $newtab['status']=='1'):
            switch($id)
            {
                case 'pg_event_booking_tab_content':
                    $this->pg_show_eventbooking_tab_content($id,$newtab,$uid,$gid,$primary_gid);
                    break;
               
            }
        endif;
    }



}
