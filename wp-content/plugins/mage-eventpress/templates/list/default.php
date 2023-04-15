<?php
$day                            = mep_get_event_upcomming_date($event_id, 'day');
$month                          = mep_get_event_upcomming_date($event_id, 'month-name');
$recurring                      = get_post_meta($event_id, 'mep_enable_recurring', true) ? get_post_meta($event_id, 'mep_enable_recurring', true) : 'no';
$mep_hide_event_hover_btn       = mep_get_option('mep_hide_event_hover_btn', 'event_list_setting_sec', 'no');
$mep_hide_event_hover_btn_text  = mep_get_option('mep_hide_event_hover_btn_text', 'general_setting_sec', 'Book Now');
$taxonomy_category = MPWEM_Helper::all_taxonomy_as_text($event_id, 'mep_cat');
$taxonomy_organizer = MPWEM_Helper::all_taxonomy_as_text($event_id, 'mep_org');
$date = get_post_meta($event_id, 'event_upcoming_datetime', true);
$event_location_icon        = mep_get_option('mep_event_location_icon', 'icon_setting_sec', 'fas fa-map-marker-alt');
$event_organizer_icon       = mep_get_option('mep_event_organizer_icon', 'icon_setting_sec', 'far fa-list-alt');

?>
    <style>* {
            box-sizing: border-box;
        }

        body {
            background-color: white;
            font-family: 'Roboto', sans-serif;
        }

        h1, .row {
            text-align: center;
        }

        .row figure {
            height: 240px;
            width: 240px;
            margin:0;
            background-size: cover;
            background-position: 50% 50%;
            box-shadow:0 0 15px rgba(0, 0, 0, 0.7);
            transition: all 1s ease-in-out;
            background-repeat:no-repeat;
            z-index:2;
            display:inline-block;
            position:relative;

        }

        .row a.lightbox {

            text-decoration:none;

        }

        /* .row a p {
            position:absolute;
            opacity: 0;
            font-size: 20px;
            top:45%;
            z-index:100;
            left: 0;
            right: 0;
            margin: auto;
        } */

        figure:hover p {
            opacity:1;
        }

        figure:after {
            content:"";
            display:inline-block;
            height:100%;
            width:100%;
            background-color:#DA291C;
            position: absolute;
            left: 0;
            bottom: 0;
            opacity:0;
            transition: all 0.4s ease-in-out 0s;
            z-index:99;
        }

        figure:hover:after {
            opacity:0.7;
        }

        /* .photo01 {
            background-image: url("./imges/sample41.jpg");
        }

        .photo02 {
            background-image: url("./imges/sample42.jpg");
        }

        .photo03 {
            background-image: url("./imges/sample43.jpg");
        } */


        /* lightbox */

        .lightbox-target{
            position: fixed;
            top: -100%;
            width: 100%;
            background: rgba(0, 0, 0, 0.7);
            width: 100%;
            opacity: 0;
            -webkit-transition: opacity .5s ease-in-out;
            -moz-transition: opacity .5s ease-in-out;
            -o-transition: opacity .5s ease-in-out;
            transition: opacity .5s ease-in-out;
            overflow: hidden;
            z-index:300;
        }

        .lightbox-target .container {
            margin: auto;
            position: absolute;
            top: 50vh;
            left: 50vw;
            background-color: white;
            box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.3);
            -webkit-transition: .5s ease-in-out;
            -moz-transition: .5s ease-in-out;
            -o-transition: .5s ease-in-out;
            transition: .5s ease-in-out;
            text-align:left;
            transform: translate3d(-50%, -50%, 0);
            padding: 20px;
            height: 600px;
            overflow-y: scroll;

        }

        h3 {
            text-align: center;
        }

        .container .elements {
            display:inline-block;
            width: 40%;
            max-height: 425px;

        }

        li {
            margin-bottom: 10px;
        }

        a.lightbox-close {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 50px;
            height: 50px;
            background: white;
            color: black;
            text-decoration: none;
            position: absolute;
            top: 0px;
            right: 0;
            margin: 0;
            padding-top: 10px;
        }

        .lightbox-target.active {
            opacity: 1;
            top: 0;
            bottom: 0;
            left: 0;
        }

        .lightbox-target.active img {
            height: 400px;
            vertical-align: top;
        }

        .card-main {
            display: flex;
            flex-direction: column;
            margin-bottom: 30px;
            border: 1px solid #eee;
        }

        .row {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        .row a.lightbox img {
            height: 100%;
            object-fit: cover;
            width: 100%;
        }
        .card-content {
            padding: 20px 0 50px 0;
        }
        .card-content a {
            text-decoration: none;
        }
        .card-content a h2 {
            font-size: 32px;
            color: #b81b2b;
            margin: 10px;
        }
        a.price p {
            font-size: 20px;
            color: #000;
            font-weight: 600;
        }



        /*lightbox inner css starts here*/

        span.mep_waitlist_label.mep-tem3-title-sec {
            z-index: 9;

            color: #000;

        }

        .desc-details-wrapper {
            display: flex !important;
            align-items: flex-start;
            gap: 15px;
        }
        .mep-default-content div.mep-default-feature-date-location {
            display: flex;
            justify-content: space-between;
            margin: 0 !important;
            width: 100%;
        }
        .desc-right-col-wrap{
            display: flex !important;
            flex-direction: column;
            width: 100%;
            align-items: center;
            gap: 30px;
        }
        .desc-right-col-wrap .mep-default-feature-content p {
            font-size: 20px;
            line-height: 32px;
            text-align: center;
        }
        .mep-default-content div.mep-default-feature-date-location>div, .mep_spring_date>div, .bristol_divided>div {
            -webkit-flex: 1;
            justify-content: center;
            align-items: center;
        }
        div.df-dtl p {
            font-weight: 700 !important;
            text-transform: uppercase;
            text-align: center;
            font-family: 'DM Sans' !important;
            color: #000 !important;
            font-size: 14px !important;
        }

        h4.mep-cart-table-title {
            margin: 40px 0;
            font-size: 24px;
            font-weight: 600;
        }
        .mep-default-theme div.mep-default-feature-date, .mep-default-theme div.mep-default-feature-time, .mep-default-theme div.mep-default-feature-location {
            background: #F7F7F7;
            padding: 10px 15px;
            border: 1px solid #C13E47;
        }

        table#mep_event_ticket_type_table {
            margin: 0 auto 40px auto;
        }
        .ex-sec-title {
            background: transparent;
            color: #000000;
        }
        table.table.table-bordered.mep_event_add_cart_table {
            background: #F7F7F7;
            DISPLAY: FLEX;
            JUSTIFY-CONTENT: SPACE-BETWEEN;
            WIDTH: 100%;
            MARGIN: 0;
        }
        table.table.table-bordered.mep_event_add_cart_table tbody {
            width: 100%;
        }
        table.table.table-bordered.mep_event_add_cart_table tbody tr {
            display: flex;
            justify-content: space-between;
            width: 100%;
            align-items: center;
            padding: 0 30px;
        }
        td.total-col {
            font-size: 22px;
        }
        .mep-event-thumbnail {
            width: 450px;
            max-height: 450px;
            overflow: hidden;
        }
        .lightbox-target button[type="submit"] {
            width: 400px;
            display: flex;
            align-items: center;
        }

        #mep_event_ticket_type_table tbody > tr >td {
            text-align: center;
            vertical-align: text-bottom;
        }

        .ticket-qty .mage_input_group input {
            width: 40px;
        }
        thead.ex-sec-title.mep_ticket_type_title tr th {
            padding: 15px 35px 3px;
        }
    </style>
    <div class='filter_item mep-event-list-loop card-main <?php echo esc_attr($columnNumber); echo ' '.$class_name; ?> mep_event_<?php echo esc_attr($style); ?>_item mix <?php echo esc_attr($org_class) . ' ' . esc_attr($cat_class); ?>' data-title="<?php echo get_the_title($event_id); ?>" data-city-name="<?php echo get_post_meta($event_id, 'mep_city', true); ?>" data-category="<?php echo esc_attr($taxonomy_category); ?>" data-organizer="<?php echo esc_attr($taxonomy_organizer); ?>" data-date="<?php echo esc_attr(date('m/d/Y',strtotime($date))); ?>" style="width:calc(<?php echo esc_attr($width); ?>% - 14px);">
        <?php do_action('mep_event_list_loop_header', $event_id); ?>
        <div class="mep_list_thumb lightbox">
            <a href="<?php echo esc_url(get_the_permalink()); ?>">
                <div class="mep_bg_thumb" data-bg-image="<?php mep_get_list_thumbnail_src($event_id, 'large'); ?>"></div>
            </a>
            <div class="mep-ev-start-date">
                <div class="mep-day"><?php echo esc_html(apply_filters('mep_event_list_only_day_number', $day, $event_id)); ?></div>
                <div class="mep-month"><?php echo esc_html(apply_filters('mep_event_list_only_month_name', $month, $event_id)); ?></div>
            </div>
            <?php
            if (is_array($event_multidate) && sizeof($event_multidate) > 0 && $recurring == 'no') { ?>
                <div class='mep-multidate-ribbon mep-tem3-title-sec'>
                    <span><?php echo mep_get_option('mep_event_multidate_ribon_text', 'label_setting_sec', esc_html__('Multi Date Event', 'mage-eventpress')); ?></span>
                </div>
            <?php } elseif ($recurring != 'no') {
                ?>
                <div class='mep-multidate-ribbon mep-tem3-title-sec'>
                    <span><?php echo mep_get_option('mep_event_recurring_ribon_text', 'label_setting_sec', esc_html__('Recurring Event', 'mage-eventpress')); ?></span>
                </div>
                <?php
            }

            if ($event_type == 'online') { ?>
                <div class='mep-eventtype-ribbon mep-tem3-title-sec'>
                    <span><?php echo mep_get_option('mep_event_virtual_label', 'label_setting_sec') ? mep_get_option('mep_event_virtual_label', 'label_setting_sec') : esc_html__('Virtual Event', 'mage-eventpress'); ?></span>
                </div>
            <?php } if($total_left <= 0){ ?>

                <div class="mep-eventtype-ribbon mep-tem3-title-sec sold-out-ribbon"><?php echo mep_get_option('mep_event_sold_out_label', 'label_setting_sec') ? mep_get_option('mep_event_sold_out_label', 'label_setting_sec') : esc_html__('Sold Out', 'mage-eventpress'); ?></div>
            <?php } ?>
        </div>
        <div class="mep_list_event_details">
            <a href="<?php the_permalink(); ?>">
                <div class="mep-list-header">
                    <h2 class='mep_list_title'><?php the_title(); ?></h2>
                    <?php if ($available_seat == 0) {
                        do_action('mep_show_waitlist_label');
                    } ?>
                    <h3 class='mep_list_date'>
                        <?php if ($show_price == 'yes') {
                            echo esc_html($show_price_label). " " . mep_event_list_price($event_id);
                        } ?>
                    </h3>
                </div>
                <?php
                if ($style == 'list') {
                    ?>
                    <div class="mep-event-excerpt">
                        <?php the_excerpt(); ?>
                    </div>
                <?php } ?>

                <div class="mep-list-footer">
                    <ul>
                        <?php
                        if ($hide_org_list == 'no') {
                            if (sizeof($author_terms) > 0) {
                                ?>
                                <li class="mep_list_org_name">
                                    <div class="evl-ico"><i class="<?php echo $event_organizer_icon; ?>"></i></div>
                                    <div class="evl-cc">
                                        <h5>
                                            <?php echo mep_get_option('mep_organized_by_text', 'label_setting_sec') ? mep_get_option('mep_organized_by_text', 'label_setting_sec') : esc_html__('Organized By:', 'mage-eventpress'); ?>
                                        </h5>
                                        <h6><?php echo esc_html($author_terms[0]->name); ?></h6>
                                    </div>
                                </li>
                            <?php }
                        }
                        if ($event_type != 'online') {
                            if ($hide_location_list == 'no') { ?>

                                <li class="mep_list_location_name">
                                    <div class="evl-ico"><i class="<?php echo $event_location_icon; ?>"></i></div>
                                    <div class="evl-cc">
                                        <h5>
                                            <?php echo mep_get_option('mep_location_text', 'label_setting_sec') ? mep_get_option('mep_location_text', 'label_setting_sec') : esc_html__('Location:', 'mage-eventpress'); ?>

                                        </h5>
                                        <h6><?php mep_get_event_city($event_id); ?></h6>
                                    </div>
                                </li>
                            <?php }
                        }
                        if ($hide_time_list == 'no' && $recurring == 'no') {
                            do_action('mep_event_list_date_li', $event_id, 'grid');
                        } elseif ($hide_time_list == 'no' && $recurring != 'no') {
                            do_action('mep_event_list_upcoming_date_li', $event_id);
                        } ?>

                    </ul>
            </a>
            <?php do_action('mep_event_list_loop_footer', $event_id); ?>
        </div>



        <?php if ('yes' == $mep_hide_event_hover_btn) { ?>
            <div class="item_hover_effect">
                <a href="<?php echo get_the_permalink($event_id); ?>"><?php echo esc_html($mep_hide_event_hover_btn_text); ?></a>
            </div>
        <?php } ?>

    </div>


    <div class= "lightbox-target" id="">

        <div class= "container">
            <?php
            // Template Name: Default Theme

            // Settings Value :::::::::::::::::::::::::::::::::::::::;
            $hide_date_details          = mep_get_option('mep_event_hide_date_from_details', 'single_event_setting_sec', 'no');
            $hide_time_details          = mep_get_option('mep_event_hide_time_from_details', 'single_event_setting_sec', 'no');
            $hide_location_details      = mep_get_option('mep_event_hide_location_from_details', 'single_event_setting_sec', 'no');
            $hide_total_seat_details    = mep_get_option('mep_event_hide_total_seat_from_details', 'single_event_setting_sec', 'no');
            $hide_org_by_details        = mep_get_option('mep_event_hide_org_from_details', 'single_event_setting_sec', 'no');
            $hide_address_details       = mep_get_option('mep_event_hide_address_from_details', 'single_event_setting_sec', 'no');
            $hide_schedule_details      = mep_get_option('mep_event_hide_event_schedule_details', 'single_event_setting_sec', 'no');
            $hide_share_details         = mep_get_option('mep_event_hide_share_this_details', 'single_event_setting_sec', 'no');
            $hide_calendar_details      = mep_get_option('mep_event_hide_calendar_details', 'single_event_setting_sec', 'no');
            $speaker_status             = mep_get_option('mep_enable_speaker_list', 'single_event_setting_sec', 'no');
            $event_date_icon            = mep_get_option('mep_event_date_icon', 'icon_setting_sec', 'fa fa-calendar');
            $event_time_icon            = mep_get_option('mep_event_time_icon', 'icon_setting_sec', 'fas fa-clock');
            $event_location_icon        = mep_get_option('mep_event_location_icon', 'icon_setting_sec', 'fas fa-map-marker-alt');
            $event_organizer_icon       = mep_get_option('mep_event_organizer_icon', 'icon_setting_sec', 'far fa-list-alt');
            ?>

            <div class="mep-default-theme mep_flex default_theme">
                <div class="mep-default-content">
                    <div class="mep-default-title">
                        <?php do_action('mep_event_title'); ?>
                    </div>

                    <div class="desc-details-wrapper">
                        <div class="mep-default-feature-image">
                            <?php do_action('mep_event_thumbnail'); ?>
                        </div>
                        <div class="desc-right-col-wrap">
                            <div class="mep-default-feature-date-location">
                                <?php if ($hide_date_details == 'no') { ?>
                                    <div class="mep-default-feature-date">
                                        <div class="df-ico"><i class="<?php echo $event_date_icon; ?>"></i></div>
                                        <div class='df-dtl'>
                                            <h3>
                                                <?php echo mep_get_option('mep_event_date_text', 'label_setting_sec') ? mep_get_option('mep_event_date_text', 'label_setting_sec') : esc_html__('Event Date:', 'mage-eventpress'); ?>
                                            </h3>
                                            <?php do_action('mep_event_date_only',get_the_id()); ?>
                                        </div>
                                    </div>
                                <?php }
                                if ($hide_time_details == 'no') { ?>
                                    <div class="mep-default-feature-time">
                                        <div class="df-ico"><i class="<?php echo $event_time_icon; ?>"></i></div>
                                        <div class='df-dtl'>
                                            <h3>
                                                <?php echo mep_get_option('mep_event_time_text', 'label_setting_sec') ? mep_get_option('mep_event_time_text', 'label_setting_sec') : esc_html__('Event Time:', 'mage-eventpress'); ?>
                                            </h3>
                                            <?php do_action('mep_event_time_only',get_the_id()); ?>
                                        </div>
                                    </div>
                                <?php }
                                if ($hide_location_details == 'no') { ?>
                                    <div class="mep-default-feature-location">
                                        <div class="df-ico"><i class="<?php echo $event_location_icon; ?>"></i></div>
                                        <div class='df-dtl'>
                                            <h3>
                                                <?php echo mep_get_option('mep_event_location_text', 'label_setting_sec') ? mep_get_option('mep_event_location_text', 'label_setting_sec') : esc_html__('Event Location:', 'mage-eventpress'); ?>
                                            </h3>
                                            <p><?php do_action('mep_event_location_venue'); ?>
                                                <?php //do_action('mep_event_location_city'); ?>    </p>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="mep-default-feature-content">
                                <?php do_action('mep_event_details'); ?>
                            </div>
                        </div>

                    </div>



                    <div class="mep-default-feature-cart-sec">
                        <?php do_action('mep_add_to_cart', get_the_id()) ?>
                    </div>

                    <div class="mep-default-feature-faq-sec">
                        <?php do_action('mep_event_faq',get_the_id()); ?>
                    </div>

                </div>
                <div class="mep-default-sidebar">
                    <?php   if ($hide_location_details == 'no') { ?>
                        <div class="mep-default-sidrbar-map">
                            <h3>
                                <?php echo mep_get_option('mep_event_location_text', 'label_setting_sec') ? mep_get_option('mep_event_location_text', 'label_setting_sec') : esc_html__('Event Location:', 'mage-eventpress'); ?>
                            </h3>
                            <?php do_action('mep_event_map',get_the_id()); ?>
                        </div>
                    <?php } ?>
                    <div class="df-sidebar-part">
                        <?php if ($hide_total_seat_details == 'no') { ?>
                            <div class="mep-default-sidrbar-price-seat">
                                <div class="df-seat"><?php do_action('mep_event_seat'); ?></div>
                            </div>
                        <?php } ?>
                        <?php if ($hide_org_by_details == 'no' && has_term('','mep_org',get_the_id())) { ?>
                            <div class="mep-default-sidrbar-meta">
                                <i class="<?php echo $event_organizer_icon; ?>"></i> <?php do_action('mep_event_organizer'); ?>
                            </div>
                        <?php }

                        if ($hide_address_details == 'no') { ?>
                            <div class="mep-default-sidrbar-address">
                                <?php do_action('mep_event_address_list_sidebar',get_the_id()); ?>
                            </div>
                        <?php }
                        if ($hide_schedule_details == 'no') { ?>
                            <div class="mep-default-sidrbar-events-schedule">
                                <?php do_action('mep_event_date_default_theme',get_the_id()); ?>
                            </div>
                        <?php }
                        if ($hide_share_details == 'no') { ?>
                            <div class="mep-default-sidrbar-social">
                                <?php do_action('mep_event_social_share'); ?>
                            </div>
                        <?php }
                        if($speaker_status == 'yes'){ ?>
                            <div class="mep-default-sidebar-speaker-list">

                                <?php do_action('mep_event_speakers_list',get_the_id()); ?>
                            </div>
                            <?php
                        }
                        if ($hide_calendar_details == 'no') { ?>
                            <div class="mep-default-sidrbar-calender-btn">
                                <?php do_action('mep_event_add_calender',get_the_id()); ?>
                            </div>
                        <?php }
                        dynamic_sidebar('mep_default_sidebar');
                        ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

<?php //} ?>
