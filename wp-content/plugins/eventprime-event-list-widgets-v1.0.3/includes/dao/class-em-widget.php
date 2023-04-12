<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class EventM_More_Widget_DAO
{
    /**
     * Get current bookable event
     */
    public function get_bookable_events() { 
        $filter = array(
            'meta_key' => em_append_meta_key('start_date'),
            'orderby' => 'meta_value_num',
            'numberposts' => -1,
            'order' => 'ASC',
            'post_status' => 'publish',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    array(
                        'key' => em_append_meta_key('start_booking_date'),
                        'value' => current_time('timestamp'),
                        'compare' => '<=',
                        'type' => 'NUMERIC',
                    ),
                    array(
                        'key' => em_append_meta_key('last_booking_date'),
                        'value' => current_time('timestamp'),
                        'compare' => '>=',
                        'type' => 'NUMERIC',
                    )
                ),
            ),
            'post_type' => EM_EVENT_POST_TYPE
        );
        $posts = get_posts($filter);
        return $posts;
    }

    /**
     * get number of booking of an event
     */
    public function get_event_booking_num($eventid){
        if(empty($eventid) && !is_int($eventid)) return;
        $args = array(
            'numberposts' => -1,
            'post_status'=> 'completed',
            'post_type'=> EM_BOOKING_POST_TYPE,
            'meta_key' => em_append_meta_key('event'),
            'meta_value' => $eventid,
        );
        $booking_posts = get_posts($args);
        return count($booking_posts);
    }

    /**
     * get featured event list
     */
    public function get_featured_events($num) { 
        $filter = array(
            'meta_key' => em_append_meta_key('start_date'),
            'orderby' => 'meta_value_num',
            'numberposts' => $num,
            'order' => 'ASC',
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key'   => 'em_is_featured',
                    'value' => 1,
                )
            ),
            'post_type' => EM_EVENT_POST_TYPE
        );
        $posts = get_posts($filter);
        return $posts;
    }

    public function get_parents_events() { 
        $filter = array(
            'meta_key' => em_append_meta_key('start_date'),
            'orderby' => 'meta_value_num',
            'numberposts' => $num,
            'order' => 'ASC',
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => em_append_meta_key('parent_event'),
                    'value' => 0,
                ),
            ),
            'post_type' => EM_EVENT_POST_TYPE
        );
        $posts = get_posts($filter);
        return $posts;
    }

    public function get_similar_events($eventId) {
        $eventM_dao = new EventM_Event_DAO();
        $event = $eventM_dao->get($eventId);
        $similar_events = $similar_events_data = array();
        $similar_type_event = $this->get_similar_events_by_type($event);
        $similar_venue_event = $this->get_similar_events_by_venue($event);
        $similar_performer_event = $this->get_similar_events_by_performer($event);
        $similar_events_data = array_merge($similar_type_event, $similar_venue_event, $similar_performer_event);
        $similar_events = $this->unique_multidim_array($similar_events_data, 'id');
        return $similar_events;
    }

    public function get_similar_events_by_type($event){
        $filter = array(
            'meta_key' => em_append_meta_key('start_date'),
            'orderby' => 'meta_value_num',
            'numberposts' => -1,
            'order' => 'ASC',
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => em_append_meta_key('event_type'),
                    'value' => $event->event_type,
                ),
            ),
            'post_type' => EM_EVENT_POST_TYPE
        );
        $posts = get_posts($filter);
        $data = array();
        if(!empty($posts)){
            foreach ($posts as $post) {
                $data[] = array("id" => $post->ID, "title" => $post->post_title);
            }
        }
        return $data;
    }

    public function get_similar_events_by_venue($event){
        $filter = array(
            'meta_key' => em_append_meta_key('start_date'),
            'orderby' => 'meta_value_num',
            'numberposts' => -1,
            'order' => 'ASC',
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => em_append_meta_key('venue'),
                    'value' => $event->venue,
                ),
            ),
            'post_type' => EM_EVENT_POST_TYPE
        );
        $posts = get_posts($filter);
        $data = array();
        if(!empty($posts)){
            foreach ($posts as $post) {
                $data[] = array("id" => $post->ID, "title" => $post->post_title);
            }
        }
        return $data;
    }

    public function get_similar_events_by_performer($event){
        $filter = array(
            'meta_key' => em_append_meta_key('start_date'),
            'orderby' => 'meta_value_num',
            'numberposts' => -1,
            'order' => 'ASC',
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => em_append_meta_key('performer'),
                    'value'   => $event->performer,
                    'compare' => 'IN',
                ),
            ),
            'post_type' => EM_EVENT_POST_TYPE
        );
        $posts = get_posts($filter);
        $data = array();
        if(!empty($posts)){
            foreach ($posts as $post) {
                $data[] = array("id" => $post->ID, "title" => $post->post_title);
            }
        }
        return $data;
    }

    public function unique_multidim_array($array, $key) {
        $temp_array = $key_array = array();
        $i = 0;
        foreach($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }
}