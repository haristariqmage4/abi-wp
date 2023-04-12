<?php
if (!defined('ABSPATH')) {
    die;
}

if (!class_exists('WbbmReport')) {
    class WbbmReport
    {
        protected $text_domain = '';
        protected $post_type = '';
        protected $post_slug = '';
        public $page_for = '';
        public $this_page = '';
        public $currency = '';

        public function __construct($post_type, $post_slug, $text_domain)
        {
            /*
             * Temp Code (clear all attendee list from db)
             * Dont't uncomment it on live
             */

            // global $wpdb;
            // $sql = "SELECT * FROM wp_posts WHERE post_type = 'mep_events_attendees'";
            // $res = $wpdb->get_results($sql);
            // echo $wpdb->num_rows;die;
            // // foreach($res as $r) {
            // //     $wpdb->delete('wp_postmeta', array('post_id' => $r->ID));
            // //     $wpdb->delete('wp_posts', array('ID' => $r->ID));
            // // }

            $this->post_type = $post_type;
            $this->post_slug = $post_slug;
            $this->text_domain = $text_domain;
            $this->currency = '$';
            $this->this_page = get_admin_url() . 'edit.php?post_type=' . $this->post_type . '&page=' . $this->post_slug;

            add_action('admin_menu', array($this, 'wbbm_report_page'));

            $this->page_for = isset($_GET['page_for']) ? $_GET['page_for'] : 'sales';

            // Shortcode
            add_shortcode('order-report-pdf', array($this, 'mep_order_report_pdf_callback'));

            // Ajax Handler
            add_action('wp_ajax_wbbm_get_bus_details', array($this, 'wbbm_get_bus_details'));

            // Tab assign
            add_action('wp_ajax_wbtm_tab_assign', array($this, 'wbtm_tab_assign_callback'));

            // Order wise details
            add_action('wp_ajax_wbbm_get_order_details', array($this, 'wbbm_get_order_details_callback'));

            // PDF
            add_action('wp_ajax_mep_report_generate_pdf', array($this, 'mep_report_generate_pdf_callback'));

            // PDF
            add_action('wp_ajax_mep_fb_ajax_report', array($this, 'mep_fb_ajax_report'));

        }

        public function mep_fb_ajax_report()
        {
            $event_id = isset($_REQUEST['event_id']) ? $_REQUEST['event_id'] : '';
            $event_date = isset($_REQUEST['event_date']) ? $_REQUEST['event_date'] : '';
            $to_date = isset($_REQUEST['to_date']) ? $_REQUEST['to_date'] : '';
            $from_date = isset($_REQUEST['form_date']) ? $_REQUEST['form_date'] : '';
            $filter_by = isset($_REQUEST['filter_by']) ? $_REQUEST['filter_by'] : '';
            $sort = isset($_REQUEST['sort']) ? $_REQUEST['sort'] : '';
            $this->report_details($event_id, $from_date, $to_date, $sort, $event_date, $filter_by);
            die();
        }

        public function wbbm_report_page()
        {
            add_submenu_page('edit.php?post_type=' . $this->post_type, __('Reports', 'mep-form-builder'), __('Reports', 'mep-form-builder'), 'manage_woocommerce', $this->post_slug, array($this, 'wbbm_reports_entry_point'));
        }

        /*
         * Page Entry Point
         */
        public function wbbm_reports_entry_point()
        {

            if (isset($_GET['wbbm_order_wise_csv_export'])) {
                $this->wbbm_order_wise_export_csv_callback();
            }

            $this->start_div('wbbm_page_wrapper'); // Main Wrap Start

            // Content
            $this->section_content();
            // Content END

            $this->end_div(); // Main Wrap End
        }

        /*
         * Page Heading
         */
        public function section_heading($heading)
        {
            echo '<h1>' . strtoupper($this->lang($heading)) . '</h1>';
        }

        public function current_tab()
        {
            return 'three';
        }

        public function wbtm_tab_assign_callback()
        {
            return 'three';

        }

        public function section_tab()
        {
            ?>

            <div class="wbtm-page-top">
                <div class="wbtm-page-top-inner">
                    <h3>
                        <?php _e('Select Report', 'mep-form-builder');?>
                    </h3>
                    <ul class="wbtm_tab_link_wrap">
                        <li class="clickme">
                            <button data-tag="order_wise_details_report" data-tab-no="three"
                                    class="wbtm_tab_link wbtm_btn_primary  <?php echo $this->current_tab() == 'three' ? 'wbtm_tab_active' : '' ?>"><?php _e('Order wise details Report', 'mep-form-builder');?></button>
                        </li>
                    </ul>
                </div>
            </div>

            <?php
}

        // Get Filter Field
        public function get_filter_field($from_date, $to_date)
        {
            switch ($this->page_for) {
                default:
                    $filter_fields = $this->filter_sales_fields($from_date, $to_date);
            }
            return $filter_fields;
        }

        // Get Content Data
        public function get_content_data(): array
        {
            switch ($this->page_for) {
                default:
                    $content_data = $this->content_sales();
            }
            return $content_data;
        }

        /*
         * Section Filter
         */
        public function section_filter($from_date, $to_date)
        {
            $this->start_div('wbbm_page_filter_wrapper'); // Wrapper Start
            $this->start_div('wbbm_page_filter_inner'); // Inner Start
            $this->start_div('wbbm_page_filter_top'); // Top Start

            $filter_active = '';
            if (isset($_GET['filter_by'])) {
                $filter_active = $_GET['filter_by'];
            }

            // echo '<a href="' . $this->this_page . '" class="wbbm_btn_new reset-filter">' . __('Reset all filter','mep-form-builder') . '</a>';
            $this->end_div(); // Top End

            $this->start_div('wbbm_page_filter_bottom'); // Bottom Start
            ?>
            <label class="sec-label"><?php _e('Filter By', 'mep-form-builder');?></label>
            <form action="<?php echo get_admin_url(); ?>edit.php?post_type=wbbm_bus&page=wbbm-reports" method="GET">
                <input type="hidden" name="post_type" value="<?php echo $this->post_type ?>">
                <input type="hidden" name="page" value="<?php echo $this->post_slug ?>">
                <div class="wbbm-form-inner">
                    <?php $this->get_filter_field($from_date, $to_date);?>
                    <input type="submit" class="wbbm_btn_new" name="submit"
                           value="<?php _e('Filter', 'mep-form-builder');?>">
                </div>
            </form>
            <?php
$this->end_div(); // Bottom End
            $this->end_div(); // Inner End
            $this->end_div(); // Content End
        }

        /*
         * Section Content
         */
        public function section_content()
        {
            ?>
            <div style="clear: both;"></div>
            <div id="container">
                <div class="wbtm_content_item <?php echo $this->current_tab() == 'three' ? 'active' : 'hide' ?>"
                     id="order_wise_details_report">
                    <?php $this->content_tab_three();?>
                </div>
            </div>
            <?php
}

        protected function content_tab_one()
        {

            $this->start_div('wbbm_page_content_wrapper');
            $get_content_data = $this->get_content_data();
            $head = $get_content_data['head'];
            $body = $get_content_data['body'];
            $this->end_div();
        }

        protected function content_tab_three()
        {
            $this->section_filter('three_from_date', 'three_to_date');
            $this->start_div('wbbm_page_content_wrapper');
            $this->end_div();
        }

        public function report_query($sort = 'DESC', $event_id = '', $order_id = '', $from_date = '', $to_date = '', $ea_event_date = '')
        {

            $from_date = date('Y-m-d', strtotime($from_date)) == date('Y-m-d', strtotime($to_date)) ? date('Y-m-d', strtotime('-1 day', strtotime($from_date))) : $from_date;
            $f_date = date('Y-m-d', strtotime($from_date));

            $event_id_filter = !empty($event_id) ? array(
                'key' => 'ea_event_id',
                'compare' => '=',
                'value' => $event_id,
            ) : '';

            $order_id_filter = !empty($order_id) ? array(
                'key' => 'ea_order_id',
                'compare' => '=',
                'value' => $order_id,
            ) : '';

            // $event_date_meta_q = $from_date != '' && $to_date != '' ? array(
            //     'key' => 'ea_event_date',
            //     'value' => array(date('Y-m-d H:i:s', strtotime($f_date . ' 00:01')), date('Y-m-d H:i:s', strtotime($to_date . ' 23:59'))),
            //     'compare' => 'BETWEEN',
            //     'type' => 'DATE'
            // ) : '';

            $requrring_filter = !empty($ea_event_date) ? array(
                'key' => 'ea_event_date',
                'value' => date('Y-m-d H:i', strtotime($ea_event_date)),
                'compare' => 'LIKE',
            ) : '';

            $meta_query = array(
                'relation' => 'AND',
                array(
                    'key' => 'ea_order_status',
                    'value' => array('completed', 'processing'),
                    'compare' => 'IN',
                ),
                $event_id_filter,
                $order_id_filter,
                // $event_date_meta_q,
                $requrring_filter,
            );

            if($from_date) {
                $date_query = array(
                    array(
                        'column' => 'post_date_gmt',
                        'after' => $from_date ? date('Y-m-d H:i:s', strtotime($from_date)) : '',
                        'before' => $to_date ? date('Y-m-d H:i:s', strtotime($to_date . '23:59:59')) : '',
                        'inclusive' => false,
                    ),
                );
            } else {
                $date_query = array();
            }

            $args = array(
                'post_type' => 'mep_events_attendees',
                'posts_per_page' => -1,
                'order' => $sort,
                'orderby' => 'meta_value',
                'meta_key' => 'ea_event_date',
                'date_query' => $date_query,
                'meta_query' => $meta_query,
            );

            return new WP_Query($args);
        }

        public function report_details($event_id = '', $from_date = '', $to_date = '', $sort = 'DESC', $ea_event_date = '', $attendee_filter_by = 'event')
        {

            // Query
            $filter_where = [];
            $event_id = !empty($event_id) ? $event_id : '';
            $from_date = !empty($from_date) ? $from_date : '';
            $to_date = !empty($to_date) ? $to_date : '';
            $sort = !empty($sort) ? $sort : '';
            $attendee_filter_by = !empty($attendee_filter_by) ? $attendee_filter_by : '';
            $ea_event_date = !empty($ea_event_date) ? $ea_event_date : '';

            $res = $this->report_query($sort, $event_id, '', $from_date, $to_date, $ea_event_date);

            $table_data = '';
            if ($res):
                $all_orders = [];
                while ($res->have_posts()) {
                    $res->the_post();
                    $all_orders[] = array(
                        'id' => get_post_meta(get_the_ID(), 'ea_order_id', true),
                        'amount' => (is_numeric(get_post_meta(get_the_ID(), 'ea_ticket_price', true)) ? get_post_meta(get_the_ID(), 'ea_ticket_price', true) : 0),
                        'attendee_id' => get_the_ID(),
                    );
                }
                wp_reset_postdata();

                $i = 0;
                $total_order = 0;
                $total_ticket = 0;
                $total_amount = 0;
                while ($res->have_posts()): $res->the_post();
                    $id = get_the_ID();
                    $order = wc_get_order($this->wbbm_post_meta($id, 'ea_order_id'));
                    $order_refunds = $order->get_total_refunded();
                    $discount = $order->get_total_discount();
                    $order_total = $order->get_total();

                    $current_order = $this->wbbm_post_meta($id, 'ea_order_id');
                    $prev_order = (isset($prev_order) ? $prev_order : $current_order);

                    $amount = 0;
                    $count = 0;
                    $j = 0;
                    $seat = array();
                    $ea_name = array();
                    $price_str = '';
                    $price_html = '';

                    if ($current_order != $prev_order || $i == 0) {
                        foreach ($all_orders as $o) {
                            if ($current_order == $o['id']) {
                                // $amount += (float)$o['amount']; // Old one
                                $amount = (float) get_post_meta($o['id'], '_order_total', true);
                                $count += $j + 1;
                                $seat[] = $this->wbbm_post_meta($o['attendee_id'], 'ea_ticket_type');
                                $ea_name[] = $this->wbbm_post_meta($o['attendee_id'], 'ea_name');
                            }
                        }
                        if ($seat) {
                            $seat_count_arr = array_count_values($seat);
                            $seat_count = [];
                            foreach ($seat_count_arr as $s => $c) {
                                $seat_count[] = $s . '(' . $c . ')';
                            }
                        }

                        // Discount
                        if ($discount > 0) {
                            $price_str .= '<tr><td>' . $this->lang('Discount') . ':</td><td> <span style="color:#c36182">-' . wc_price($discount) . '</span></td></tr>';
                        }

                        // Refund
                        if ($order_refunds > 0) {
                            $amount = $amount - $order_refunds;
                            $price_str .= '<tr><td>' . $this->lang('Refund') . ':</td><td> <span style="color:#c36182">-' . wc_price($order_refunds) . '</span></td></tr>';
                        }

                        // If has refund or discount
                        if ($price_str != '') {
                            $price_html .= '<table class="mep_report_amount">';
                            $price_html .= '<tr><td>' . $this->lang('Subtotal') . ':</td><td>' . wc_price($amount + $discount + $order_refunds) . '</td></tr>';
                            $price_html .= $price_str;
                            $price_html .= '<tr style="font-weight:700"><td>' . $this->lang('Total') . ':</td><td>' . wc_price($amount) . '</td></tr>';
                            $price_html .= '</table>';
                        }

                        $table_data .= '<tr>';
                        $table_data .= '<td>#' . $current_order . '</td>';
                        $table_data .= '<td>' . ($order ? $order->get_formatted_billing_full_name() : "") . '</td>';
                        $table_data .= '<td>' . implode(', ', $seat_count) . '</td>';
                        $table_data .= '<td>' . implode(', ', $ea_name) . '</td>';
                        $table_data .= '<td>' . $count . '</td>';
                        $table_data .= '<td>' . get_the_title($this->wbbm_post_meta($id, 'ea_event_id')) . '</td>';
                        $table_data .= '<td>' . get_the_date() . '</td>';
                        $table_data .= '<td>' . get_mep_datetime(get_post_meta($id, 'ea_event_date', true), 'date-time-text') . '</td>';
                        $table_data .= '<td>' . ($price_str != "" ? $price_html : '<span class="wbbm_amount_raw">' . wc_price($amount) . '</span>') . '</td>';
                        $table_data .= '<td>' . $this->wbbm_post_meta($id, 'ea_order_status') . '</td>';
                        $table_data .= '<td class="wbbm_order_detail--report" data-event-id="' . $event_id . '" data-order-id="' . $current_order . '"><img class="wbbm_report_loading" src="' . plugin_dir_url(__FILE__) . '../' . 'img/loading.gif' . '"/> <div class="action-btn-wrap"><button class="wbbm_detail_inside">' . $this->lang("Details Inside") . '</button></div></td>';
                        $table_data .= '</tr>';

                        $total_amount += (float) ($order_total - ($order_refunds));

                        $total_order++;
                    }

                    $total_ticket++;
                    // $total_amount += (int) $this->wbbm_post_meta($id, 'ea_ticket_price'); // old one

                    $j++;
                    $prev_order = $current_order;
                    $i++;
                endwhile;endif;
            wp_reset_postdata();

            ?>

            <div class="wbbm-table-top">
                <div class="left">
                    <div class="item">
                        <strong><?php _e('Number of Order', 'mep-form-builder');?>:</strong>
                        <span><?php echo ($total_order < 10) ? str_pad($total_order, 1, '0', STR_PAD_LEFT) : $total_order; ?></span>
                    </div>
                    <div class="item">
                        <strong><?php _e('Total Ticket', 'mep-form-builder');?>:</strong>
                        <span><?php echo ($total_ticket < 10) ? str_pad($total_ticket, 1, '0', STR_PAD_LEFT) : $total_ticket; ?></span>
                    </div>
                    <div class="item">
                        <strong><?php _e('Sold Amount', 'mep-form-builder');?>:</strong>
                        <span><?php echo ($total_amount) ? wc_price($total_amount) : 0.00; ?></span>
                    </div>
                </div>

                <div class="right">

                    <form action="" method="GET">
                        <input type="hidden" name="post_type" value="<?php echo $this->post_type ?>">
                        <input type="hidden" name="page" value="<?php echo $this->post_slug ?>">
                        <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                        <input type="hidden" name="from_date" value="<?php echo $from_date; ?>">
                        <input type="hidden" name="to_date" value="<?php echo $to_date; ?>">
                        <input type="hidden" name="ea_event_date" value="<?php echo $ea_event_date; ?>">
                        <input type="hidden" name="attendee_filter_by" value="<?php echo $attendee_filter_by; ?>">
                        <input type="hidden" name="sort" value="<?php echo $sort; ?>">

                        <input style="background: #47a96e;font-weight: 700;border-radius:0" class="wbbm_btn_new" type="submit"
                            name="wbbm_order_wise_csv_export"
                            value="&#8595; <?php _e('Export CSV', 'mep-form-builder');?>">
                    </form>
                </div>
            </div>

            <div id="wbbm_report_table_main">
                <table class="wbbm-main-table-order-wise">
                    <thead>
                    <tr>
                        <th><?php _e('Order no', 'mep-form-builder');?></th>
                        <th><?php _e('Billing Name', 'mep-form-builder');?></th>
                        <th><?php _e('Ticket', 'mep-form-builder');?></th>
                        <th><?php _e('Attendee Name', 'mep-form-builder');?></th>
                        <th><?php _e('Qty', 'mep-form-builder');?></th>
                        <th><?php _e('Event Name', 'mep-form-builder');?></th>
                        <th><?php _e('Order Date', 'mep-form-builder');?></th>
                        <th>
                        <form method='get'>
                        <?php

            $sort_icon = $sort == 'DESC' ? '<span class="dashicons dashicons-arrow-up"></span>' : '<span class="dashicons dashicons-arrow-down"></span>';
            $new_sort = $sort == 'DESC' ? 'ASC' : 'DESC';
            ?>
                            <button
                             data-id-sort="<?php echo $new_sort; ?>"
                             data-id-event="<?php echo $event_id; ?>"
                             data-id-from_date="<?php echo $from_date; ?>"
                             data-id-to_date="<?php echo $to_date; ?>"
                             data-id-ea_event_date="<?php echo $ea_event_date; ?>"
                             data-id-sort="<?php echo $sort; ?>"
                             data-id-attendee_filter_by="<?php echo $attendee_filter_by; ?>"
                             type='submit' id='report_data_sorting' style='background: transparent;border: 0;display: inline-block;padding: 0;margin: 0 0 0 0;cursor: pointer;'><?php _e('Event Date', 'mep-form-builder');?> <?php echo $sort_icon; ?></button>
                        </form>
                        </th>
                        <th><?php _e('Price', 'mep-form-builder');?> <small class="mep-tax-text">(includes Tax)</small></th>
                        <th><?php _e('Status', 'mep-form-builder');?></th>
                        <th><?php _e('Action', 'mep-form-builder');?></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php echo $table_data; ?>
                    </tbody>
                </table>
            </div>
            <?php
}

        // Filter fields for Sales
        public function filter_sales_fields($fd, $td)
        {
            $filter_active = 'filter_active';

            $from_date = null;
            if (isset($_GET['from_date'])) {
                $from_date = $_GET['from_date'] != '' ? $filter_active : null;
            }

            $to_date = null;
            if (isset($_GET['to_date'])) {
                $to_date = $_GET['to_date'] != '' ? $filter_active : null;
            }

            // Event
            $event_id = null;
            if (isset($_GET['event_id'])) {
                $event_id = $_GET['event_id'] != '' ? $_GET['event_id'] : null;
            }

            // attendee_filter_by
            $attendee_filter_by = isset($_GET['attendee_filter_by']) ? $_GET['attendee_filter_by'] : 'event';
            ?>
            <style>
            .filter_by_sec {
                display: block;
                overflow: hidden;
                width: 100%;
            }

            .filter_by_sec ul {
                display: block;
                padding: 0;
                margin: 0;
                list-style: none;
            }

            .filter_by_sec ul li {
                display: inline-block;
                margin: 0 10px;
            }

            div#mep_date_range_sec {
                display: inline-block;
            }

            div#mep_date_range_sec .wbbm-field-group, div#mep_date_range_sec .event_filter {
                display: inline-block;
            }

            </style>
            <div class='filter_by_sec'>
            <ul>
                        <li><?php _e('Filter List By:', 'mep-form-builder');?></li>
                            <li><label for="event_filter"><input class='mep_attn_filter_by' type="radio" name='attendee_filter_by' id='event_filter' value='event' <?php if ($attendee_filter_by == 'event') {echo 'checked';}?>><?php _e('Event', 'mep-form-builder');?></label></li>
                            <li><label for="ticket_filter"><input class='mep_attn_filter_by' type="radio" name='attendee_filter_by' id='ticket_filter' value='date_range' <?php if ($attendee_filter_by == 'date_range') {echo 'checked';}?>><?php _e('Date Range', 'mep-form-builder');?></label></li>
                        </ul>
            </div>
            <div class='mep-report-date-range' id='mep_date_range_sec' style='display:none'>
                        <div class="wbbm-field-group">
                            <label for="<?php echo $fd ?>"><?php echo $this->lang('From Date') ?></label>
                            <input class="from_date <?php echo ($from_date ? $filter_active : '') ?>" type="text"
                                id="<?php echo $fd ?>"
                                name="from_date"
                                value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : '' ?>"
                                placeholder="yyyy-mm-dd">
                        </div>

                        <div class="wbbm-field-group">
                            <label for="<?php echo $td ?>"><?php echo $this->lang('To Date') ?></label>
                            <input class="to_date <?php echo ($to_date ? $filter_active : '') ?>" type="text" id="<?php echo $td ?>"
                                name="to_date"
                                value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : '' ?>" placeholder="yyyy-mm-dd">
                        </div>

            </div>
            <div class='mep-report-event-list' id='mep_event_list_sec'>
                        <ul class="event_filter_wrap">
                            <li>
                                <div class='event_filter'>
                                <select name="event_id" id="mep_event_id" class="select2 mep_report_event_list" required>
                                    <option value="0"><?php _e('Select Event', 'mep-form-builder');?></option>
                                    <?php
$args = array(
                'post_type' => 'mep_events',
                'posts_per_page' => -1,
            );
            $loop = new WP_Query($args);
            $events_query = $loop->posts;
            foreach ($events_query as $event) {
                ?>
                                        <option value="<?php echo $event->ID; ?>" <?php if ($event_id == $event->ID) {
                    echo 'selected';
                }?>><?php echo get_the_title($event->ID); ?></option>
                                    <?php
}
            ?>
                                </select>
                                </div>

                                <div class='attendee_key_filter'  style="display: none;">
                                    <input type="text" name='filter_key' value='' id='attendee_filter_key'>
                                </div>

                            </li>
                            <div id='mep_ajax_sec'>
                            <li id='filter_attitional_btn'>
                                <input type="hidden" id='mep_everyday_ticket_time' name='mep_attendee_list_filter_event_date' value='<?php //echo $event_date; ?>'>
                            </li>
                            </div>
                            <?php //do_action('mep_attendee_list_filter_form_before_btn'); ?>
                        </ul>
            </div>
            <?php
$this->three_tab_js_dependancy();
        }

        // Content for Sales
        public function content_sales()
        {}

        public function filter_query()
        {}

        public function filter_day_items()
        {}

        public function buses()
        {}

        public function lang($text): string
        {
            $text = __($text, $this->text_domain);
            return $text;
        }

        /*
         * Start Div
         */
        public function start_div($className = null, $id = null)
        {
            echo '<div ' . ($className ? "class=$className" : "") . ' ' . ($id ? "id=$id" : "") . '>';
        }

        /*
         * End Div
         */
        public function end_div()
        {
            echo '</div>';
        }

        // Ajax
        public function wbbm_get_bus_details()
        {}

        public function wbbm_get_order_details_callback()
        {
            $order_id = $_POST['order_id'];
            $event_id = $_POST['event_id'];
            $res = $this->report_query('DESC', $event_id, $order_id);
            $html = '';
            if ($res) {
                ob_start();
                ?>
                <tr class="wbbm_report_detail">
                    <td colspan="10">
                        <style>
                            .report-action-btns a {
                                display: inline-block;
                                text-decoration: none;
                                margin: 0 3px;
                            }
                            a.mep_sync_data {
                                display: none;
                            }
                        </style>
                        <table>
                            <thead>
                                <tr>
                                    <th><?php _e('Ticket No', 'mep-form-builder');?></th>
                                    <th><?php _e('Name', 'mep-form-builder');?></th>
                                    <th><?php _e('Email', 'mep-form-builder');?></th>
                                    <th><?php _e('Phone', 'mep-form-builder');?></th>
                                    <th><?php _e('Seat', 'mep-form-builder');?></th>
                                    <th><?php _e('Price', 'mep-form-builder');?></th>
                                    <?php do_action('mep_attendee_list_heading');?>
                                    <th><?php _e('Action', 'mep-form-builder');?></th>
                                </tr>
                            </thead>
                            <tbody>
                        <?php
while ($res->have_posts()) {
                    $res->the_post();
                    $id = get_the_ID();
                    $order = wc_get_order($this->wbbm_post_meta($id, 'wbtm_order_id'));
                    ?>
                            <tr>
                                <td><?php echo $this->wbbm_post_meta($id, 'ea_ticket_no'); ?></td>
                                <td><?php echo ucfirst($this->wbbm_post_meta($id, 'ea_name')); ?></td>
                                <td><?php echo $this->wbbm_post_meta($id, 'ea_email'); ?></td>
                                <td><?php echo $this->wbbm_post_meta($id, 'ea_phone'); ?></td>
                                <td><?php echo ucfirst($this->wbbm_post_meta($id, 'ea_ticket_type')); ?></td>
                                <td><?php echo wc_price(($this->wbbm_post_meta($id, 'ea_ticket_price') > 0) ? $this->wbbm_post_meta($id, 'ea_ticket_price') : 0); ?></td>
                                <?php do_action('mep_attendee_list_item', $id);?>
                                <td>
                                <div class='report-action-btns'>
                                <a href="<?php echo get_the_permalink($id); ?>" title='View' target='_blank'><span class="dashicons dashicons-visibility"></span></a>
                                <a href="<?php echo get_admin_url() . "post.php?post=$id&action=edit"; ?>"  title='Edit' target='_blank'><span class="dashicons dashicons-edit-large"></span></a>
                                <?php do_action('mep_attendee_list_item_action_middile', $id);?>
                                <?php do_action('mep_attendee_list_item_action_after', $id);?>
                                </div>
                                </td>
                            </tr>
                            <?php
}
            }
            wp_reset_postdata();
            ?>
                        </tbody>
                        </table>
                    </td>
                </tr>
            <?php
echo ob_get_clean();
            exit;
        }

        protected function wbbm_post_meta($id, $key)
        {
            if ($id && $key) {
                return get_post_meta($id, $key, true);
            } else {
                return false;
            }
        }

        // Export CSV
        public function wbbm_export_csv_callback()
        {}

        public function wbbm_order_wise_export_csv_callback()
        {
            $msg = false;
            $sort = $_REQUEST['sort'];
            $event_id = $_REQUEST['event_id'];
            $from_date = $_REQUEST['from_date'];
            $to_date = $_REQUEST['to_date'];
            $ea_event_date = $_REQUEST['ea_event_date'];

            $res = $this->report_query($sort, $event_id, '', $from_date, $to_date, $ea_event_date);
            $header_row = array(
                __('Order no', 'mep-form-builder'),
                __('Billing Name', 'mep-form-builder'),
                __('Ticket', 'mep-form-builder'),
                __('Qty', 'mep-form-builder'),
                __('Event Name', 'mep-form-builder'),
                __('Event Date', 'mep-form-builder'),
                __('Price', 'mep-form-builder'),
                __('Status', 'mep-form-builder'),
            );

            $data_rows = array();
            if ($res):
                $all_orders = [];
                while ($res->have_posts()) {
                    $res->the_post();
                    $all_orders[] = array(
                        'id' => get_post_meta(get_the_ID(), 'ea_order_id', true),
                        'amount' => (is_numeric(get_post_meta(get_the_ID(), 'ea_ticket_price', true)) ? get_post_meta(get_the_ID(), 'ea_ticket_price', true) : 0),
                        'attendee_id' => get_the_ID(),
                    );
                }
                wp_reset_postdata();

                $i = 0;
                $total_order = 0;
                $total_ticket = 0;
                $total_amount = 0;
                while ($res->have_posts()): $res->the_post();
                    $id = get_the_ID();
                    $order = wc_get_order($this->wbbm_post_meta($id, 'ea_order_id'));
                    $order_refunds = $order->get_total_refunded();

                    $current_order = $this->wbbm_post_meta($id, 'ea_order_id');
                    $prev_order = (isset($prev_order) ? $prev_order : $current_order);

                    $amount = 0;
                    $count = 0;
                    $j = 0;
                    $seat = array();

                    if ($current_order != $prev_order || $i == 0) {
                        foreach ($all_orders as $o) {
                            if ($current_order == $o['id']) {
                                // $amount += (float)$o['amount'];
                                $amount = (float) get_post_meta($o['id'], '_order_total', true);
                                $count += $j + 1;
                                $seat[] = $this->wbbm_post_meta($o['attendee_id'], 'ea_ticket_type');
                            }
                        }
                        if ($seat) {
                            $seat_count_arr = array_count_values($seat);
                            $seat_count = [];
                            foreach ($seat_count_arr as $s => $c) {
                                $seat_count[] = $s . '(' . $c . ')';
                            }
                        }

                        // Refund
                        if ($order_refunds > 0) {
                            $amount = $amount - $order_refunds;
                        }

                        $data_rows[] = array(
                            $current_order,
                            ($order ? $order->get_formatted_billing_full_name() : ""),
                            implode(', ', $seat_count),
                            $count,
                            get_the_title($this->wbbm_post_meta($id, 'ea_event_id')),
                            get_mep_datetime(get_post_meta($id, 'ea_event_date', true), 'date-time-text'),
                            $amount,
                            $this->wbbm_post_meta($id, 'ea_order_status'),
                        );
                    }
                    $j++;
                    $prev_order = $current_order;
                    $i++;
                endwhile;endif;
            wp_reset_postdata();

            if ($data_rows) {
                $domain = isset($filter_text) ? $filter_text : '';
                $filename = 'Report_' . $domain . '_' . time() . '.csv';
                $this->csv($header_row, $data_rows, $filename);
                $msg = true;
            }
        }

        public function wbbm_detail_export_csv($bus_id)
        {}

        public function wbbm_order_wise_detail_export_csv($order_id)
        {}

        public function csv($header_row, $data_rows, $filename)
        {
            $fh = fopen('php://output', 'w');
            ob_clean(); // clean slate
            fprintf($fh, chr(0xEF) . chr(0xBB) . chr(0xBF));
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Content-Description: File Transfer');
            header('Content-type: text/csv');
            header("Content-Disposition: attachment; filename={$filename}");
            header('Expires: 0');
            header('Pragma: public');
            fputcsv($fh, $header_row);
            foreach ($data_rows as $data_row) {
                fputcsv($fh, $data_row);
            }
            ob_flush(); // dump buffer
            fclose($fh);
            die();
        }

        public function bus_lists()
        {}

        public function three_tab_js_dependancy()
        {
            ?>
            <script>
                (function($) {
                    'use strict';
                    $(document).ready(function($) {
                        $('#mep_event_list_sec').show();
                        var filter_by = $("input[name='attendee_filter_by']:checked").val();
                                    if(filter_by == 'event'){
                                        $('#mep_date_range_sec').hide();
                                        $('#mep_ajax_sec').show();
                                        $('#three_from_date').val('');
                                        $('#three_to_date').val('');

                                    }else{
                                        $('#mep_date_range_sec').show();
                                        $('#mep_ajax_sec').hide();
                                    }




                        $(document).on('click', '.mep_attn_filter_by', function() {

                            var filter_by = $("input[name='attendee_filter_by']:checked").val();
                                    if(filter_by == 'event'){
                                        // $('#mep_event_list_sec').show();
                                        $('#mep_ajax_sec').show();

                                        $('#mep_date_range_sec').hide();
                                        $('#three_from_date').val('');
                                        $('#three_to_date').val('');
                                    }else{
                                        // $('#mep_event_list_sec').hide();
                                        $('#mep_ajax_sec').hide();
                                        $('#mep_date_range_sec').show();
                                    }
                            });


                            jQuery('.wbbm_btn_new').on('click', function() {

                                    var event_id        = jQuery('#mep_event_id').val();
                                    var form_date       = jQuery('#three_from_date').val();
                                    var to_date         = jQuery('#three_to_date').val();
                                    var every_day_time  = jQuery('#mep_everyday_ticket_time').val();
                                    var recurring_date  = jQuery('#mep_recurring_date').val();
                                    var filter_by       = $("input[name='attendee_filter_by']:checked").val();
                                    var ev_event_date   = jQuery('#mep_everyday_ticket_time').val();
                                    var re_event_date   = jQuery('#mep_recurring_date').val();
                                    var event__date     = re_event_date && re_event_date != 0 ? re_event_date : ev_event_date;
                                    var event_date      = event__date == 0 || event__date === undefined || event__date === null ? jQuery('#mep_everyday_datepicker').val() : event__date;

                                    jQuery.ajax({
                                        type: 'POST',
                                        url: ajaxurl,
                                        data: {
                                            "action"        : "mep_fb_ajax_report",
                                            "filter_by"     : filter_by,
                                            "form_date"     : form_date,
                                            "to_date"       : to_date,
                                            "event_date"    : event_date,
                                            "sort"          : "DESC",
                                            "event_id"      : event_id
                                        },
                                        beforeSend: function() {
                                            jQuery('.wbbm_page_content_wrapper').html('<h5 class="mep-processing"><?php echo mep_get_option('mep_event_rec_please_wait_attendee_loading_text', 'label_setting_sec', 'Please wait! Report is Loading..'); ?></h5>');
                                        },
                                        success: function(data) {
                                            jQuery('.wbbm_page_content_wrapper').html(data);
                                        }
                                    });
                                return false;
                            });


                            // jQuery('#report_data_sorting').on('click', function() {
                                $(document).on('click', '#report_data_sorting', function() {
                                    let $this           = $(this);
                                    var event_id        = jQuery('#mep_event_id').val();
                                    var form_date       = jQuery('#three_from_date').val();
                                    var to_date         = jQuery('#three_to_date').val();
                                    var every_day_time  = jQuery('#mep_everyday_ticket_time').val();
                                    var recurring_date  = jQuery('#mep_recurring_date').val();
                                    var filter_by       = $("input[name='attendee_filter_by']:checked").val();
                                    var ev_event_date   = jQuery('#mep_everyday_ticket_time').val();
                                    var re_event_date   = jQuery('#mep_recurring_date').val();
                                    let sort            = $this.attr('data-id-sort');
                                    var event__date     = re_event_date && re_event_date != 0 ? re_event_date : ev_event_date;
                                    var event_date      = event__date == 0 || event__date === undefined || event__date === null ? jQuery('#mep_everyday_datepicker').val() : event__date;

                                    jQuery.ajax({
                                        type: 'POST',
                                        url: ajaxurl,
                                        data: {
                                            "action"        : "mep_fb_ajax_report",
                                            "filter_by"     : filter_by,
                                            "form_date"     : form_date,
                                            "to_date"       : to_date,
                                            "event_date"    : event_date,
                                            "sort"          : sort,
                                            "event_id"      : event_id
                                        },
                                        beforeSend: function() {
                                            jQuery('.wbbm_page_content_wrapper').html('<h5 class="mep-processing"><?php echo mep_get_option('mep_event_rec_please_wait_attendee_loading_text', 'label_setting_sec', 'Please wait! Report is Loading..'); ?></h5>');
                                        },
                                        success: function(data) {
                                            jQuery('.wbbm_page_content_wrapper').html(data);

                                        }
                                    });
                                return false;
                            });


                            $(document).on('click', '.wbbm_detail_inside', function() {

                                let $this = $(this);
                                let parent = $this.parents('tr');
                                let order_id = $this.parents('td').attr('data-order-id');
                                let event_id = $this.parents('td').attr('data-event-id');

                                if (parent.next('.wbbm_report_detail').hasClass('show')) {
                                    parent.next('.wbbm_report_detail').removeClass('show');
                                    parent.next('.wbbm_report_detail').hide();
                                    return;
                                }

                                $('.wbbm-main-table-order-wise tbody tr.wbbm_report_detail').each(function () {
                                    if ($(this).hasClass('show')) {
                                        $(this).removeClass('show');
                                        $(this).hide();
                                    }
                                });

                                $.ajax({
                                        url: ajaxurl,
                                        type: 'post',
                                        dataType: 'html',
                                        data: { order_id: order_id, event_id: event_id, action: 'wbbm_get_order_details' },
                                        beforeSend: function () {
                                            $this.parent().siblings('.wbbm_report_loading').show();
                                        },
                                        success: function (data) {
                                            if (data) {
                                                // parent.next('.wbbm_report_detail').hide();
                                                if (parent.next('.wbbm_report_detail').children().length == 0) {
                                                    $(data).insertAfter(parent);
                                                    parent.next('.wbbm_report_detail').slideDown(100);
                                                }
                                                if (parent.next('.wbbm_report_detail').hasClass('show')) {
                                                    //
                                                } else {
                                                    parent.next('.wbbm_report_detail').slideDown(100);
                                                }
                                                parent.next('.wbbm_report_detail').toggleClass('show');

                                                $this.parent().siblings('.wbbm_report_loading').hide();
                                            }
                                        }
                                    });
                                });

                    <?php do_action('mep_fb_attendee_list_script');?>
                    });
                })(jQuery);
            </script>
            <?php
}

        public function mep_report_generate_pdf_callback()
        {}
        public function get_current_data_pdf()
        {}
        public function mep_order_report_pdf_callback($attr)
        {}
        protected function report_pdf_style()
        {}

    }

    new WbbmReport('mep_events', 'mep-reports', 'mep-form-builder');
}