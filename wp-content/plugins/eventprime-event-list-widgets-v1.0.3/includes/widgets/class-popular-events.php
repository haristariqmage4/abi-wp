<?php
if (!defined( 'ABSPATH')){
    exit;
}

class EventM_Popular_Event_Widget extends WP_Widget 
{

    private $dao;
    
    public function __construct() {
        $this->dao = new EventM_More_Widget_DAO();
        $widget_options = array (
            'classname' => 'EventM_Popular_Event_Widget',
            'description' => 'Show list of popular events.'
        );
        parent::__construct( 'EventM_Popular_Event_Widget', 'EventPrime Popular Events', $widget_options );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        wp_enqueue_style('em_more_pe_widget_style', plugin_dir_url(__DIR__) . 'css/em_more_widget_style.css', false, EVENTPRIME_VERSION);
        $title = ( !empty( $instance['title'] ) ) ? $instance['title'] : __( 'Popular Events', 'eventprime-list-widgets' );
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
        $number = ( !empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
        if ( !$number ) {
            $number = 5;
        }
        $html = '<div class="widget widget_popular_events"><div class="widget-content">';
            $html .= '<h2 class="widget-title subheading heading-size-3">'.$title.'</h2>';
            $events = $this->dao->get_bookable_events();
            if(!empty($events)){
                $setting_service = EventM_Factory::get_service('EventM_Setting_Service');
                $global_settings= $setting_service->load_model_from_db();
                $event_service = EventM_Factory::get_service('EventM_Service');
                $eventData = array();
                foreach ($events as $event) {
                    $booking_no = $event_service->booked_seats($event->ID);
                    /* $event_url = add_query_arg('event', $event->ID, get_page_link($global_settings->events_page)); */
                    $event->id = $event->ID;
                    $event_url = em_get_single_event_page_url($event, $global_settings);
                    $eventData[] = array("id" => $event->ID, "title" => $event->post_title, "count" => $booking_no, "url" => $event_url);
                }
                if(!empty($eventData)){
                    usort($eventData, function($a, $b) {
                        return $b['count'] <=> $a['count'];
                    });
                    $venueService = EventM_Factory::get_service('EventM_Venue_Service');
                    $i = 0;
                    foreach ($eventData as $data) {
                        $eventSer = $event_service->load_model_from_db($data['id']);
                        $venue = $venueService->load_model_from_db($eventSer->venue);
                        $venue_capacity = $eventSer->seating_capacity;
                        if(empty($venue_capacity)){
                            $venue_capacity = $venueService->capacity($eventSer->venue);
                        }
                        $sold_per = 0;
                        if(!empty($venue_capacity)){
                            $sold_per = $data['count'] / $venue_capacity * 100;
                        }
                        $html .= '<div id="ep-popular-events" class="ep-mw-wrap" data-count="'.$data['count'].'" data-capacity="'.$venue_capacity.'" data-sold="'.$sold_per.'">';
                            if((!is_numeric($sold_per) || is_float($sold_per)) && !is_int($sold_per)){
                                $sold_per = number_format($sold_per, 1);
                            }
                            $html .= '<div class="ep-fimage">';
                            if (!empty($eventSer->cover_image_id)):                            
                                $html .= '<a href="'.$data['url'].'">'.get_the_post_thumbnail($data['id'], 'thumbnail').'</a>';
                            else:
                                $html .= '<a href="'.$data['url'].'"><img src="'.esc_url(EM_BASE_FRONT_IMG_URL.'dummy_image.png').'" alt="'.__('Dummy Image','eventprime-list-widgets').'" ></a>';
                            endif;
                            if(!empty($sold_per)){
                                $html .= '<span class="ep-fsold"><span class="sold-num">'.$sold_per.'%</span> '.__('Tickets Sold Out', 'eventprime-list-widgets').'</span>';
                            }
                            $html .= '</div>';
                            $html .= '<div class="ep-fdata"><div class="ep-fname"><a href="'.$data['url'].'">'.$data['title'].'</a></div>';
                            $html .= '<div class="ep-fdate">'.date('M d, Y @ H:i A', $eventSer->start_date).'</div>';
                           
                            if(!empty($venue) && !empty($venue->address)){
                                $html .= '<div class="ep-faddress">'.$venue->address.'</div>';
                            }
                             if(!empty($eventSer->description)){
                                $html .= '<div class="ep-fdesc">'.$eventSer->description.'</div>';
                            }
                            $html .= '</div>';
                        $html .= '</div>';
                        $i++;
                        if($i == $number) break;
                    }
                }
            }
        $html .= '</div></div>';
        echo $html;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = !empty( $instance['title'] ) ? $instance['title'] : '';
        $number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'eventprime-list-widgets' ); ?></label> 
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of events to show:', 'eventprime-list-widgets' ); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
        </p>
        <?php 
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['number'] = (int) $new_instance['number'];

        return $instance;
    }   
}
