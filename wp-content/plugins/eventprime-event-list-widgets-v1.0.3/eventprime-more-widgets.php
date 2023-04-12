<?php
/**
 * Plugin Name: Event List Widgets
 * Plugin URI: http://eventprime.net
 * Description: An EventPrime extension that adds multiple widgets.
 * Version: 1.0.3
 * Author: EventPrime
 * Author URI: http://eventprime.net
 * Requires at least: 4.8
 * Tested up to: 6.1.1
 * Text Domain: eventprime-list-widgets
 * Domain Path: /languages
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('EM_List_Widget')) {

    final class EM_List_Widget {
        /**
         * Plugin version.
         *
         * @var string
         */
        public $version = '1.0.3';

        /**
         * The single instance of the class.
         *
         * @var Event_Magic
         */
        protected static $_instance = null;

        
        public static function instance() {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Cloning is forbidden.
         */
        public function __clone() {
            _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'event_magic'), $this->version);
        }

        /**
         * Unserializing instances of this class is forbidden.
         */
        public function __wakeup() {
            _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'event_magic'), $this->version);
        }

        /**
         * Event_Magic Constructor.
         */
        public function __construct() { 
            $this->define_constants();
            $this->load_textdomain();
            $this->includes();
            $this->define_hooks();
            $em = event_magic_instance();
            array_push($em->extensions,'more_widgets');
        }
       
        public function define_constants(){
            event_magic_instance()->define('EMMW_BASE_URL', plugin_dir_url(__FILE__));
        }
        
        public function includes(){
            include_once('includes/widgets/class-popular-events.php');
            include_once('includes/widgets/class-similar-events.php');
            include_once('includes/widgets/class-featured-events.php');
            include_once('includes/dao/class-em-widget.php'); // Loading DAO
        }

        public function eventprime_register_more_widget(){
            register_widget( 'EventM_Popular_Event_Widget' );
            register_widget( 'EventM_Similar_Event_Widget' );
            register_widget( 'EventM_Featured_Event_Widget' );
        }

        public function define_hooks(){
            add_action('widgets_init', array($this, 'eventprime_register_more_widget'));
            add_action('event_magic_custom_extension_setting',array($this,'event_more_widget_custom_extension_setting'));
            add_filter('ep_before_saving_popup_event', array($this,'event_more_widget_before_saving_popup_event'),10,1);
            add_filter('ep_admin_calendar_event', array($this,'event_more_widget_ep_admin_calendar_event'),10,2);
            add_action('event_magic_popup_custom_settings',array($this,'event_more_widget_popup_custom_extension_setting'));
            add_action('event_magic_popup_custom_settings_edit',array($this,'event_more_widget_popup_custom_extension_setting_edit'));
            add_filter('event_magic_format_model_from_db', array($this,'event_more_widget_format_model_from_db'),10,1);            
            add_filter('event_magic_format_model_to_save', array($this,'event_more_widget_format_model_to_save'),10,1);
            add_action('event_magic_save_event',array($this,'event_more_widget_save_event_featured'));
            add_action('event_magic_gs_settings',array($this,'event_more_widgets_gs_settings'));
        }

        public function event_more_widget_custom_extension_setting($value=''){?>
            <div class="emrow">
                <div class="emfield"><?php _e('Featured','eventprime-list-widgets'); ?></div>
                <div class="eminput">
                    <input  type="checkbox" name="is_featured"  ng-model="data.post.is_featured" ng-true-value="1" ng-false-value="0">
                </div>
                <div class="emnote"><i class="fa fa-info-circle" aria-hidden="true"></i>
                    <?php _e('Enable this option for featured event.','eventprime-list-widgets'); ?>
                </div>
            </div><?php
        }

        public function event_more_widget_before_saving_popup_event($event) {
            $event->is_featured = absint(event_m_get_param('is_featured'));
            return $event;
        }

        public function event_more_widget_ep_admin_calendar_event($cal_event,$event) {
            $cal_event['is_featured']= $event->is_featured;
            return $cal_event;
        }

        public function event_more_widget_popup_custom_extension_setting(){?>
            <div class="emrow">
                <div class="eminput">
                    <label><input type="checkbox" ng-model="calendarNewEventPopup.is_featured" ng-model="calendarNewEventPopup.is_featured" ng-true-value="1" ng-false-value="0"><?php _e('Featured', 'eventprime-list-widgets'); ?></label>
                    }
                </div>
            </div><?php
        }

        public function event_more_widget_popup_custom_extension_setting_edit(){?>
            <div class="emrow">
                <div class="epinputicon">&nbsp;</div>
                <div class="eminput">
                    <label><input type="checkbox" ng-model="calendarNewEventPopup.is_featured" ng-model="calendarNewEventPopup.is_featured" ng-true-value="1" ng-false-value="0"><?php _e('Featured', 'eventprime-list-widgets'); ?></label>
                </div>
            </div><?php
        }

        public function event_more_widget_format_model_from_db($model) {
            $model->is_featured = absint($model->is_featured);
            return $model;
        }

        public function event_more_widget_format_model_to_save($model) {
            $model->is_featured = absint($model->is_featured);
            return $model;
        }

        public function event_more_widget_save_event_featured($event_id) {
            if(!empty($event_id)){
                $event_service = EventM_Factory::get_service('EventM_Service');
                $event = $event_service->load_model_from_db($event_id);
                $is_featured = absint(event_m_get_param('is_featured'));
                $event->is_featured = $is_featured;
                $event_service->update_model($event);
            }
        }

        public function load_textdomain(){
            load_plugin_textdomain('eventprime-list-widgets', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        }

        public function event_more_widgets_gs_settings(){?>
            <a href='javascript:void(0)'>
                <div class="em-settings-box ep-active-extension ep-no-global-settings-model" data-popup="ep-event-list-widget-ext" onclick="CallEPExtensionModal(this)">
                    <img class="em-settings-icon" ng-src="<?php echo EM_BASE_URL; ?>includes/admin/template/images/event-more-widget-icon.png">
                    <div class="em-settings-description"></div>
                    <div class="em-settings-subtitle"><?php _e('List Widgets', 'eventprime-list-widgets'); ?></div>
                    <span><?php _e('Display event data on frontend.', 'eventprime-list-widgets'); ?></span>
                </div>
            </a>
            <?php
        }
    }
}

function em_list_widgets() {
    return EM_List_Widget::instance();
}

function em_list_widgets_checks(){ ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e( 'Event List Widgets won\'t work as EventPrime plugin is not active/installed', 'event-magic' ); ?></p>
    </div>
<?php }

add_action('plugins_loaded',function(){if(!class_exists('Event_Magic')){add_action('admin_notices','em_list_widgets_checks');}});
add_action('event_magic_loaded', 'em_list_widgets');
require_once plugin_dir_path( __FILE__ ) .'extension-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://eventprime.net/event_list_widgets_metadata.json',
    __FILE__,
    'eventprime-event-list-widgets'
);