<?php 
add_action('admin_init','mep_wl_upgrade');
function mep_wl_upgrade(){
    
    if (get_option('mep_event_waitlist_upgration') != 'completed') {
    global $wpdb;
    $table_name = $wpdb->prefix . 'mep_event_waitlist';
    $wait_list_query = $wpdb->get_results("SELECT * FROM $table_name WHERE status = 1");
    foreach ($wait_list_query as $waitlist) {
        $event_id           = $waitlist->event_id;
        $name               = $waitlist->user_name;
        $user_email         = $waitlist->user_email;
        $event_datetime	    = $waitlist->event_datetime;
        $status             = $waitlist->status;
        $email_status       = $waitlist->email_status;
        
        $wt                 =[];
        $wt['event_id']     = $event_id;
        $wt['user_name']    = $name;
        $wt['user_email']   = $user_email;
        $wt['event_datetime'] = $event_datetime;
        $wt['status']       = $status;
        $wt['email_status'] = $email_status;
        mep_wl_create_new_waitlist($wt);
    }
    update_option('mep_event_waitlist_upgration', 'completed');
}



    if (get_option('mep_event_waitlist_upgration_03') != 'completed') {
        $args = array(
            'post_type' => 'mep_event_waitlist',
            'posts_per_page' => -1,           
        ); 
        $a_query = new WP_Query($args);
        $wt_query = $a_query->posts;

        foreach($wt_query as $wt){
            $wtid = $wt->ID;
            $event_id = get_post_meta($wtid,'event_id',true);
            $event_start_datetime = get_post_meta($event_id,'event_start_datetime',true);
            $waitlist_event_start_datetime = get_post_meta($wtid,'event_datetime',true);
            if(empty($waitlist_event_start_datetime)){
                update_post_meta($wtid,'event_datetime',$event_start_datetime);
            }
        }
    update_option('mep_event_waitlist_upgration_03', 'completed');
}







}