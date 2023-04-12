<?php
if (!defined('ABSPATH')) {
    die;
}
add_filter('mep_settings_sec_reg', 'mep_cl_settings_sec', 10);
function mep_cl_settings_sec($default_sec) {
    $sections = array(
        array(
            'id' => 'mep_cl_settings',
            'title' => __('Calender', 'mage-eventpress-mm')
        )
    );
    return array_merge($default_sec, $sections);
}









add_filter('mep_settings_sec_fields', 'mep_cl_settings_fields', 10);
function mep_cl_settings_fields($default_fields) {
    $settings_fields = array(
        'mep_cl_settings' => array(
           
            array(
                'name' => 'mep_cl_title_bg_color',
                'label' => __('Title Section Background Color', 'mage-eventpress-waitlist'),
                'desc' => __('Select a Color for calendar background', 'mage-eventpress-waitlist'),
                'type' => 'color',
                'default' => '#128e73',
            ),            
           
            array(
                'name' => 'mep_cl_title_text_color',
                'label' => __('Title Section text Color', 'mage-eventpress-waitlist'),
                'desc' => __('Select a Color Label Text', 'mage-eventpress-waitlist'),
                'type' => 'color',
                'default' => '#fff',
            ),            
           
            array(
                'name' => 'mep_cl_sun_bg_color',
                'label' => __('Sunday Background Color', 'mage-eventpress-waitlist'),
                'desc' => __('Select a Color for sunday background', 'mage-eventpress-waitlist'),
                'type' => 'color',
                'default' => '#ff0000',
            ),                       
            array(
                'name' => 'mep_cl_sun_text_color',
                'label' => __('Sunday Text Color', 'mage-eventpress-waitlist'),
                'desc' => __('Select a Color for sunday text', 'mage-eventpress-waitlist'),
                'type' => 'color',
                'default' => '#ffffff',
            ),            
           
            array(
                'name' => 'mep_cl_mon_bg_color',
                'label' => __('Monday Background Color', 'mage-eventpress-waitlist'),
                'desc' => __('Select a Color for Monday background', 'mage-eventpress-waitlist'),
                'type' => 'color',
                'default' => '#ffff00',
            ),                       
            array(
                'name' => 'mep_cl_mon_text_color',
                'label' => __('Monday Text Color', 'mage-eventpress-waitlist'),
                'desc' => __('Select a Color for Monday text', 'mage-eventpress-waitlist'),
                'type' => 'color',
                'default' => '#ffffff',
            ),            
           
            array(
                'name' => 'mep_cl_tue_bg_color',
                'label' => __('Tuesday Background Color', 'mage-eventpress-waitlist'),
                'desc' => __('Select a Color for Tuesday background', 'mage-eventpress-waitlist'),
                'type' => 'color',
                'default' => '#ffc0cb',
            ),                       
            array(
                'name' => 'mep_cl_tue_text_color',
                'label' => __('Tuesday Text Color', 'mage-eventpress-waitlist'),
                'desc' => __('Select a Color for Tuesday text', 'mage-eventpress-waitlist'),
                'type' => 'color',
                'default' => '#ffffff',
            ),            
           
            array(
                'name' => 'mep_cl_wed_bg_color',
                'label' => __('Wednesday Background Color', 'mage-eventpress-waitlist'),
                'desc' => __('Select a Color for Wednesday background', 'mage-eventpress-waitlist'),
                'type' => 'color',
                'default' => '#008000',
            ),                       
            array(
                'name' => 'mep_cl_wed_text_color',
                'label' => __('Wednesday Text Color', 'mage-eventpress-waitlist'),
                'desc' => __('Select a Color for Wednesday text', 'mage-eventpress-waitlist'),
                'type' => 'color',
                'default' => '#ffffff',
            ),            
           
            array(
                'name' => 'mep_cl_thu_bg_color',
                'label' => __('Thusday Background Color', 'mage-eventpress-waitlist'),
                'desc' => __('Select a Color for Thusday background', 'mage-eventpress-waitlist'),
                'type' => 'color',
                'default' => '#808080',
            ),                       
            array(
                'name' => 'mep_cl_thu_text_color',
                'label' => __('Thusday Text Color', 'mage-eventpress-waitlist'),
                'desc' => __('Select a Color for Thusday text', 'mage-eventpress-waitlist'),
                'type' => 'color',
                'default' => '#ffffff',
            ),            
           
            array(
                'name' => 'mep_cl_fri_bg_color',
                'label' => __('Friday Background Color', 'mage-eventpress-waitlist'),
                'desc' => __('Select a Color for Friday background', 'mage-eventpress-waitlist'),
                'type' => 'color',
                'default' => '#00ffff',
            ),                       
            array(
                'name' => 'mep_cl_fri_text_color',
                'label' => __('Friday Text Color', 'mage-eventpress-waitlist'),
                'desc' => __('Select a Color for Friday text', 'mage-eventpress-waitlist'),
                'type' => 'color',
                'default' => '#ffffff',
            ),            
           
            array(
                'name' => 'mep_cl_sat_bg_color',
                'label' => __('Saturday Background Color', 'mage-eventpress-waitlist'),
                'desc' => __('Select a Color for Saturday background', 'mage-eventpress-waitlist'),
                'type' => 'color',
                'default' => '#800080',
            ),                       
            array(
                'name' => 'mep_cl_sat_text_color',
                'label' => __('Saturday Text Color', 'mage-eventpress-waitlist'),
                'desc' => __('Select a Color for Saturday text', 'mage-eventpress-waitlist'),
                'type' => 'color',
                'default' => '#ffffff',
            ),            
           
            array(
                'name' => 'mep_cl_current_date_bg_color',
                'label' => __('Current Date Background Color', 'mage-eventpress-waitlist'),
                'desc' => __('Select a Color for Current Date background', 'mage-eventpress-waitlist'),
                'type' => 'color',
                'default' => '#ffa500',
            ),                       
            array(
                'name' => 'mep_cl_current_date_text_color',
                'label' => __('Current Date Text Color', 'mage-eventpress-waitlist'),
                'desc' => __('Select a Color for Current Date text', 'mage-eventpress-waitlist'),
                'type' => 'color',
                'default' => '#ff0000',
            ),            
           
            array(
                'name' => 'mep_cl_event_details_bg_color',
                'label' => __('Event Details Background Color', 'mage-eventpress-waitlist'),
                'desc' => __('Select a Color for Event Details background', 'mage-eventpress-waitlist'),
                'type' => 'color',
                'default' => '#000b',
            ),                       
            array(
                'name' => 'mep_cl_event_details_text_color',
                'label' => __('Event Details Text Color', 'mage-eventpress-waitlist'),
                'desc' => __('Select a Color for Event Details text', 'mage-eventpress-waitlist'),
                'type' => 'color',
                'default' => '#fff',
            ),            
           
            array(
                'name' => 'mep_cl_event_details_btn_bg_color',
                'label' => __('Event Details Button Background Color', 'mage-eventpress-waitlist'),
                'desc' => __('Select a Color for Event Details Button background', 'mage-eventpress-waitlist'),
                'type' => 'color',
                'default' => '#ff0000',
            ),                       
            array(
                'name' => 'mep_cl_event_details_btn_text_color',
                'label' => __('Event Details Button Text Color', 'mage-eventpress-waitlist'),
                'desc' => __('Select a Color for Event Details Button text', 'mage-eventpress-waitlist'),
                'type' => 'color',
                'default' => '#fff',
            ),            
            array(
                'name' => 'mep_cl_event_details_imp_color',
                'label' => __('Event Details Important Text Color', 'mage-eventpress-waitlist'),
                'desc' => __('Select a Color for Event Details important text', 'mage-eventpress-waitlist'),
                'type' => 'color',
                'default' => '#ff0000',
            ),  

        )
    );
    return array_merge($default_fields, $settings_fields);
}



add_action('wp_head','mep_cl_apply_style',90);
function mep_cl_apply_style(){
    $title_bg = mep_get_option('mep_cl_title_bg_color','mep_cl_settings','#128e73');
    $title_text = mep_get_option('mep_cl_title_text_color','mep_cl_settings','#fff');

    $sun_bg = mep_get_option('mep_cl_sun_bg_color','mep_cl_settings','#ff0000');
    $sun_text = mep_get_option('mep_cl_sun_text_color','mep_cl_settings','#fff');

    $mon_bg = mep_get_option('mep_cl_mon_bg_color','mep_cl_settings','#ffff00');
    $mon_text = mep_get_option('mep_cl_mon_text_color','mep_cl_settings','#fff');

    $tue_bg = mep_get_option('mep_cl_tue_bg_color','mep_cl_settings','#ffc0cb');
    $tue_text = mep_get_option('mep_cl_tue_text_color','mep_cl_settings','#fff');

    $wed_bg = mep_get_option('mep_cl_wed_bg_color','mep_cl_settings','#008000');
    $wed_text = mep_get_option('mep_cl_wed_text_color','mep_cl_settings','#fff');

    $thu_bg = mep_get_option('mep_cl_thu_bg_color','mep_cl_settings','#808080');
    $thu_text = mep_get_option('mep_cl_thu_text_color','mep_cl_settings','#fff');

    $fri_bg = mep_get_option('mep_cl_fri_bg_color','mep_cl_settings','#00ffff');
    $fri_text = mep_get_option('mep_cl_fri_text_color','mep_cl_settings','#fff');

    $sat_bg = mep_get_option('mep_cl_sat_bg_color','mep_cl_settings','#800080');
    $sat_text = mep_get_option('mep_cl_sat_text_color','mep_cl_settings','#fff');

    $current_bg = mep_get_option('mep_cl_current_date_bg_color','mep_cl_settings','#000b');
    $current_text = mep_get_option('mep_cl_current_date_text_color','mep_cl_settings','#fff');


    $event_bg = mep_get_option('mep_cl_event_details_bg_color','mep_cl_settings','#000b');
    $event_text = mep_get_option('mep_cl_event_details_text_color','mep_cl_settings','#fff');

    $event_btn_bg = mep_get_option('mep_cl_event_details_btn_bg_color','mep_cl_settings','#ff0000');
    $event_btn_text = mep_get_option('mep_cl_event_details_btn_text_color','mep_cl_settings','#fff');

    $event_imp_text = mep_get_option('mep_cl_event_details_imp_color','mep_cl_settings','#ff0000');

    ?>
<style>
div.event-calender-sec .calender_top_title th {
    background: <?php echo $title_bg; ?>;
    color: <?php echo $title_text; ?>;
}
div.event-calender-sec .calender_top_title th a {
    color: <?php echo $title_text; ?>;
}

div.event-calender-sec th.sunday {
    background:  <?php echo $sun_bg; ?>;
    color:  <?php echo $sun_text; ?>;
}

div.event-calender-sec th.monday {
    background:  <?php echo $mon_bg; ?>;
    color:  <?php echo $mon_text; ?>;
}

div.event-calender-sec th.tuesday {
    background:  <?php echo $tue_bg; ?>;
    color:  <?php echo $tue_text; ?>;
}
div.event-calender-sec th.wednesday {
    background:  <?php echo $wed_bg; ?>;
    color:  <?php echo $wed_text; ?>;
}
div.event-calender-sec th.thursday {
    background:  <?php echo $thu_bg; ?>;
    color:  <?php echo $thu_text; ?>;
}
div.event-calender-sec th.friday {
    background:  <?php echo $fri_bg; ?>;
    color:  <?php echo $fri_text; ?>;
}
div.event-calender-sec th.saturday {
    background:  <?php echo $sat_bg; ?>;
    color:  <?php echo $sat_text; ?>;
}

.calender_date.current-date {
    background-color:  <?php echo $current_bg; ?>!important;
    color: <?php echo $current_text; ?>!important;
}

span.item_count_badge, div.cal_fixed_date, div.event-calender-more-details ul li a, div.event-calender-more-details ul li a:hover{
    background-color:  <?php echo $event_btn_bg; ?>!important;
    /* color:  <?php echo $event_btn_text; ?>!important; */
}

div.event-calender-more-details ul li span, div.event-calender-more-details ul li strong {
    color:  <?php echo $event_imp_text; ?>;
}

.cal_event_list {
    background-color:  <?php echo $event_bg; ?>;
}

div.event-calender-more-details ul, div.event-calender-more-details ul li, div.cal_event_list a.cal_list_title {
    color: <?php echo $event_text; ?>;
}
</style>
<?php
}